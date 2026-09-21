<?php

declare(strict_types=1);

namespace CampusLynk\Migration\Orchestrator;

/**
 * RiskClassifier
 *
 * Evaluates migration units and waves to assign GREEN, YELLOW, or RED risk classification
 * based on architectural impact, schema modifications, and critical system boundaries.
 */
class RiskClassifier
{
    public const RISK_GREEN = 'GREEN';
    public const RISK_YELLOW = 'YELLOW';
    public const RISK_RED = 'RED';

    /**
     * Known unit risk mappings based on architectural classification.
     */
    private const UNIT_RISK_MAP = [
        // Base Schema / Core Models (RED)
        'M1.1' => self::RISK_RED,

        // Backend Controllers / Endpoints / Schema additions (YELLOW)
        'M2.1' => self::RISK_YELLOW,
        'M2.4' => self::RISK_YELLOW,
        'M2.6' => self::RISK_YELLOW,
        'M3.1' => self::RISK_YELLOW,
        'M3.3' => self::RISK_YELLOW,
        'M4.1' => self::RISK_YELLOW,
        'M4.2' => self::RISK_YELLOW,
        'M4.3' => self::RISK_YELLOW,
        'M5.1' => self::RISK_YELLOW,
        'M5.2' => self::RISK_YELLOW,
        'M6.1' => self::RISK_YELLOW,

        // Additive Views / Partials / Reports / Dashboards (GREEN)
        'M2.2' => self::RISK_GREEN,
        'M2.3' => self::RISK_GREEN,
        'M2.5' => self::RISK_GREEN,
        'M2.7' => self::RISK_GREEN,
        'M3.2' => self::RISK_GREEN,
        'M6.2' => self::RISK_GREEN,
        'M7.1' => self::RISK_GREEN,
        'M8.1' => self::RISK_GREEN,
        'M8.2' => self::RISK_GREEN,
        'M8.3' => self::RISK_GREEN,
        'M8.4' => self::RISK_GREEN,
        'M8.5' => self::RISK_GREEN,
        'M9.1' => self::RISK_GREEN,
        'M9.2' => self::RISK_GREEN,
        'M9.3' => self::RISK_GREEN,
        'M9.4' => self::RISK_GREEN,
    ];

    /**
     * Classifies a migration unit by its ID and metadata.
     */
    public function classifyUnit(string $unitId, array $unitData = []): string
    {
        if (isset(self::UNIT_RISK_MAP[$unitId])) {
            return self::UNIT_RISK_MAP[$unitId];
        }

        // Dynamic heuristic fallback
        $title = strtolower($unitData['title'] ?? '');
        $feature = strtolower($unitData['feature'] ?? '');

        if (str_contains($title, 'schema') || str_contains($title, 'migration') || str_contains($feature, 'schema')) {
            return self::RISK_RED;
        }

        if (str_contains($title, 'controller') || str_contains($title, 'backend') || str_contains($title, 'engine') || str_contains($title, 'service')) {
            return self::RISK_YELLOW;
        }

        return self::RISK_GREEN;
    }

    /**
     * Classifies a wave based on the maximum risk of its constituent units.
     *
     * @param string[] $unitIds
     */
    public function classifyWave(array $unitIds, array $unitsData = []): string
    {
        $hasYellow = false;

        foreach ($unitIds as $unitId) {
            $risk = $this->classifyUnit($unitId, $unitsData[$unitId] ?? []);
            if ($risk === self::RISK_RED) {
                return self::RISK_RED;
            }
            if ($risk === self::RISK_YELLOW) {
                $hasYellow = true;
            }
        }

        return $hasYellow ? self::RISK_YELLOW : self::RISK_GREEN;
    }

    /**
     * Returns human-readable description and execution policy for a risk tier.
     */
    public function getRiskPolicy(string $risk): array
    {
        return match ($risk) {
            self::RISK_RED => [
                'name' => 'High Risk (Critical Path / Schema)',
                'badge' => '🔴 RED',
                'policy' => 'Strict verification. Mandatory pre-wave and post-wave supervisor review. Level 1, 2, and 3 test verification required.',
                'auto_proceed' => false
            ],
            self::RISK_YELLOW => [
                'name' => 'Medium Risk (Controllers / Services / APIs)',
                'badge' => '🟡 YELLOW',
                'policy' => 'Automated unit + domain verification required. Single consolidated wave report to supervisor.',
                'auto_proceed' => true
            ],
            self::RISK_GREEN => [
                'name' => 'Low Risk (Additive Views / Reports / Dashboards)',
                'badge' => '🟢 GREEN',
                'policy' => 'Full autonomy within wave. Execute implementation + tests in one pass without slice gates. Level 1 + Level 2 verification.',
                'auto_proceed' => true
            ],
            default => [
                'name' => 'Unknown Risk',
                'badge' => '⚪ UNKNOWN',
                'policy' => 'Treat as RED (fail-closed).',
                'auto_proceed' => false
            ]
        };
    }
}
