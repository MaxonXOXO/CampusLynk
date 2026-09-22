<?php

declare(strict_types=1);

namespace CampusLynk\Migration\Orchestrator;

use RuntimeException;

/**
 * WaveEngine
 *
 * Manages the formation, execution lifecycle, risk gating, and failure isolation
 * of migration Waves across topologically independent, ready units.
 */
class WaveEngine
{
    private DependencyGraph $graph;
    private RiskClassifier $classifier;
    private array $waves = [];
    private ?array $activeWave = null;

    public function __construct(DependencyGraph $graph, RiskClassifier $classifier, array $waves = [], array|string|null $activeWave = null)
    {
        $this->graph = $graph;
        $this->classifier = $classifier;
        $this->waves = $waves;
        if (is_string($activeWave) && isset($waves[$activeWave])) {
            $this->activeWave = $waves[$activeWave];
        } else {
            $this->activeWave = is_array($activeWave) ? $activeWave : null;
        }
    }

    /**
     * Forms the next optimal wave from the currently ready units.
     *
     * @param int $maxWaveSize Maximum number of units to include in the wave (default 4).
     * @return array|null The formed wave structure or null if no units are ready.
     */
    public function formNextWave(int $maxWaveSize = 4): ?array
    {
        $resolution = $this->graph->resolveStates();
        $readyUnits = $resolution['ready'];

        if (empty($readyUnits)) {
            return null;
        }

        // Determine next wave number
        $waveIndex = count($this->waves) + 1;
        $waveId = 'W' . $waveIndex;

        // Group candidate units: prioritize same-feature cohesion or same topological level
        $selectedUnitIds = [];
        $selectedFeatures = [];

        // Check if there are related feature groups (e.g. role_dashboards M9.1-M9.4)
        foreach ($readyUnits as $unitId => $unit) {
            $feature = $unit['feature'] ?? 'general';

            // If we haven't selected any, start with this feature
            if (empty($selectedUnitIds)) {
                $selectedUnitIds[] = $unitId;
                $selectedFeatures[$feature] = true;
                continue;
            }

            // If unit belongs to the same feature and wave size is under limit
            if (isset($selectedFeatures[$feature]) && count($selectedUnitIds) < $maxWaveSize) {
                // Ensure no dependency between units in the same wave
                if (!$this->hasMutualDependency($unitId, $selectedUnitIds)) {
                    $selectedUnitIds[] = $unitId;
                }
            }
        }

        // If we still have capacity, fill with other ready independent units
        if (count($selectedUnitIds) < $maxWaveSize) {
            foreach ($readyUnits as $unitId => $unit) {
                if (in_array($unitId, $selectedUnitIds, true)) {
                    continue;
                }
                if (!$this->hasMutualDependency($unitId, $selectedUnitIds)) {
                    $selectedUnitIds[] = $unitId;
                    if (count($selectedUnitIds) >= $maxWaveSize) {
                        break;
                    }
                }
            }
        }

        $waveRisk = $this->classifier->classifyWave($selectedUnitIds, $readyUnits);

        // Generate descriptive wave name based on features
        $featureTitles = [];
        foreach ($selectedUnitIds as $uId) {
            $fId = $readyUnits[$uId]['feature'] ?? $uId;
            $featureTitles[$fId] = true;
        }
        $waveName = 'Wave ' . $waveIndex . ': ' . implode(' & ', array_keys($featureTitles));

        $newWave = [
            'id' => $waveId,
            'name' => $waveName,
            'risk' => $waveRisk,
            'units' => $selectedUnitIds,
            'state' => 'FORMED',
            'started_at' => null,
            'completed_at' => null,
            'checkpoint_commit' => null,
            'verification_summary' => null
        ];

        $this->waves[$waveId] = $newWave;
        return $newWave;
    }

    /**
     * Creates an explicitly specified wave.
     */
    public function formExplicitWave(string $waveId, string $waveName, array $unitIds): array
    {
        $allUnits = $this->graph->getUnits();
        $waveRisk = $this->classifier->classifyWave($unitIds, $allUnits);

        $newWave = [
            'id' => $waveId,
            'name' => $waveName,
            'risk' => $waveRisk,
            'units' => $unitIds,
            'state' => 'FORMED',
            'started_at' => null,
            'completed_at' => null,
            'checkpoint_commit' => null,
            'verification_summary' => null
        ];

        $this->waves[$waveId] = $newWave;
        return $newWave;
    }

    /**
     * Checks if a candidate unit has any dependency relationship with already selected units in the wave.
     */
    private function hasMutualDependency(string $candidateId, array $selectedIds): bool
    {
        $downstream = $this->graph->getDownstreamUnits($candidateId);
        foreach ($selectedIds as $sId) {
            if (in_array($sId, $downstream, true)) {
                return true;
            }
            $sDownstream = $this->graph->getDownstreamUnits($sId);
            if (in_array($candidateId, $sDownstream, true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Activates a wave for execution.
     */
    public function startWave(string $waveId): array
    {
        if (!isset($this->waves[$waveId])) {
            throw new RuntimeException("Wave {$waveId} does not exist.");
        }

        $this->waves[$waveId]['state'] = 'IN_PROGRESS';
        $this->waves[$waveId]['started_at'] = gmdate('Y-m-d\TH:i:s\Z');
        $this->activeWave = [
            'id' => $waveId,
            'state' => 'IN_PROGRESS',
            'started_at' => $this->waves[$waveId]['started_at']
        ];

        return $this->waves[$waveId];
    }

    /**
     * Records wave verification and marks wave VERIFIED if all tests pass.
     */
    public function recordWaveVerification(string $waveId, array $verificationSummary): array
    {
        if (!isset($this->waves[$waveId])) {
            throw new RuntimeException("Wave {$waveId} does not exist.");
        }

        $allPassed = ($verificationSummary['status'] ?? 'PASS') === 'PASS';

        $this->waves[$waveId]['verification_summary'] = $verificationSummary;
        $this->waves[$waveId]['state'] = $allPassed ? 'VERIFIED' : 'FAILED';

        return $this->waves[$waveId];
    }

    /**
     * Completes and checkpoints a wave atomically.
     */
    public function checkpointWave(string $waveId, string $commitHash): array
    {
        if (!isset($this->waves[$waveId])) {
            throw new RuntimeException("Wave {$waveId} does not exist.");
        }

        $this->waves[$waveId]['state'] = 'COMPLETED';
        $this->waves[$waveId]['checkpoint_commit'] = $commitHash;
        $this->waves[$waveId]['completed_at'] = gmdate('Y-m-d\TH:i:s\Z');

        $this->activeWave = null;

        return $this->waves[$waveId];
    }

    /**
     * Handles failure isolation for a specific unit within a wave.
     *
     * @param string $failedUnitId The unit that failed.
     * @param string $error Error message or diagnostic.
     * @param int $maxAttempts Maximum allowed retry attempts.
     * @return array Status of the unit and impacted downstream units.
     */
    public function handleUnitFailure(string $failedUnitId, string $error, int $maxAttempts = 3): array
    {
        $downstream = $this->graph->getDownstreamUnits($failedUnitId);

        return [
            'failed_unit' => $failedUnitId,
            'error' => $error,
            'downstream_blocked' => $downstream,
            'isolated' => true,
            'can_independent_units_proceed' => true
        ];
    }

    public function getWaves(): array
    {
        return $this->waves;
    }

    public function getActiveWave(): ?array
    {
        return $this->activeWave;
    }

    public function setWaves(array $waves): void
    {
        $this->waves = $waves;
    }
}
