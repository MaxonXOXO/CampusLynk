<?php

declare(strict_types=1);

namespace CampusLynk\Migration\Orchestrator;

/**
 * VerificationPipeline
 *
 * Implements the 3-tier verification model:
 * Level 1 (Targeted) -> Level 2 (Domain) -> Level 3 (Full Suite)
 */
class VerificationPipeline
{
    private string $laravelDir;

    public function __construct(?string $laravelDir = null)
    {
        $this->laravelDir = $laravelDir ?? (dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'carmel-linx-laravel');
    }

    /**
     * Executes Level 1: Targeted unit test.
     */
    public function runTargetedTest(string $testFilter): array
    {
        return $this->executeArtisanTest("--filter=" . escapeshellarg($testFilter), "Level 1 (Targeted: {$testFilter})");
    }

    /**
     * Executes Level 2: Domain/Feature test suite.
     */
    public function runDomainTest(string $domainFilter): array
    {
        return $this->executeArtisanTest("--filter=" . escapeshellarg($domainFilter), "Level 2 (Domain: {$domainFilter})");
    }

    /**
     * Executes Level 3: Full application regression test suite.
     */
    public function runFullSuite(): array
    {
        return $this->executeArtisanTest("", "Level 3 (Full Application Suite)");
    }

    /**
     * Executes all 3 verification levels for a wave.
     *
     * @param array<string, string> $unitTestFilters Map of unitId => testFilter
     * @param string[] $domainFilters List of domain filters
     */
    public function executeWaveVerification(array $unitTestFilters, array $domainFilters = []): array
    {
        $summary = [
            'status' => 'PASS',
            'level1_targeted' => [],
            'level2_domain' => [],
            'level3_full' => null,
            'total_assertions' => 0,
            'duration_seconds' => 0.0,
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z')
        ];

        // 1. Run Level 1 for each unit
        foreach ($unitTestFilters as $unitId => $filter) {
            $res = $this->runTargetedTest($filter);
            $summary['level1_targeted'][$unitId] = $res;
            $summary['total_assertions'] += $res['assertions'];
            $summary['duration_seconds'] += $res['duration'];
            if (!$res['passed']) {
                $summary['status'] = 'FAIL';
                return $summary; // Fail-fast on unit failure
            }
        }

        // 2. Run Level 2 for domains
        foreach ($domainFilters as $domain) {
            $res = $this->runDomainTest($domain);
            $summary['level2_domain'][$domain] = $res;
            $summary['total_assertions'] += $res['assertions'];
            $summary['duration_seconds'] += $res['duration'];
            if (!$res['passed']) {
                $summary['status'] = 'FAIL';
                return $summary;
            }
        }

        // 3. Run Level 3 Full Suite
        $fullRes = $this->runFullSuite();
        $summary['level3_full'] = $fullRes;
        $summary['total_assertions'] = max($summary['total_assertions'], $fullRes['assertions']);
        $summary['duration_seconds'] += $fullRes['duration'];
        if (!$fullRes['passed']) {
            $summary['status'] = 'FAIL';
        }

        return $summary;
    }

    /**
     * Helper to run php artisan test and parse output.
     */
    private function executeArtisanTest(string $args, string $levelLabel): array
    {
        $cmd = 'php artisan test ' . $args;
        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];

        $startTime = microtime(true);
        $process = proc_open($cmd, $descriptorSpec, $pipes, $this->laravelDir);
        $output = '';
        $stderr = '';

        if (is_resource($process)) {
            fclose($pipes[0]);
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            $exitCode = proc_close($process);
        } else {
            $exitCode = 1;
            $output = 'Failed to execute artisan process';
        }
        $duration = round(microtime(true) - $startTime, 2);

        $passed = ($exitCode === 0);
        $assertions = 0;
        $testsCount = 0;

        // Parse: Tests:  X passed (Y assertions)
        if (preg_match('/(?:Tests:\s+)?(\d+)\s+passed(?:,\s+(\d+)\s+total)?\s*(?:\((\d+)\s+assertions\))?/i', $output, $matches)) {
            $testsCount = (int)($matches[1] ?? 0);
            $assertions = (int)($matches[3] ?? 0);
        }

        return [
            'level' => $levelLabel,
            'passed' => $passed,
            'exit_code' => $exitCode,
            'tests_count' => $testsCount,
            'assertions' => $assertions,
            'duration' => $duration,
            'output_snippet' => substr(trim($output), 0, 500)
        ];
    }
}
