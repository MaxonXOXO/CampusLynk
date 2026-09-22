<?php

declare(strict_types=1);

namespace CampusLynk\Migration\Orchestrator;

use RuntimeException;

/**
 * DependencyGraph
 *
 * Manages the topological ordering, cycle detection, and dependency-aware state
 * resolution (READY vs BLOCKED) for all units in the Migration Control Plane.
 */
class DependencyGraph
{
    private array $units = [];
    private array $dependencies = [];

    public function __construct(array $units = [], array $dependencies = [])
    {
        $this->units = $units;
        $this->dependencies = $dependencies;
    }

    public function setGraph(array $units, array $dependencies): void
    {
        $this->units = $units;
        $this->dependencies = $dependencies;
    }

    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * Detects circular dependencies using Depth-First Search (DFS).
     * Returns the cycle path if detected, or null if acyclic.
     */
    public function detectCycles(): ?array
    {
        $visited = [];
        $recursionStack = [];

        foreach (array_keys($this->dependencies) as $node) {
            if (!isset($visited[$node])) {
                $cyclePath = [];
                if ($this->dfsCycle($node, $visited, $recursionStack, $cyclePath)) {
                    return $cyclePath;
                }
            }
        }

        return null;
    }

    private function dfsCycle(string $node, array &$visited, array &$recursionStack, array &$cyclePath): bool
    {
        $visited[$node] = true;
        $recursionStack[$node] = true;
        $cyclePath[] = $node;

        if (isset($this->dependencies[$node])) {
            foreach ($this->dependencies[$node] as $dep) {
                if (!isset($visited[$dep])) {
                    if ($this->dfsCycle($dep, $visited, $recursionStack, $cyclePath)) {
                        return true;
                    }
                } elseif (!empty($recursionStack[$dep])) {
                    $cyclePath[] = $dep;
                    return true;
                }
            }
        }

        array_pop($cyclePath);
        $recursionStack[$node] = false;
        return false;
    }

    /**
     * Computes the topological depth level for every unit in the graph.
     * Level 0: No prerequisites.
     * Level N: 1 + max(depth of prerequisites).
     */
    public function computeTopologicalLevels(): array
    {
        $cycle = $this->detectCycles();
        if ($cycle !== null) {
            throw new RuntimeException("Dependency cycle detected: " . implode(' -> ', $cycle));
        }

        $levels = [];
        foreach (array_keys($this->units) as $unitId) {
            $this->getDepth($unitId, $levels);
        }

        return $levels;
    }

    private function getDepth(string $unitId, array &$levels): int
    {
        if (isset($levels[$unitId])) {
            return $levels[$unitId];
        }

        $deps = $this->dependencies[$unitId] ?? $this->units[$unitId]['dependencies'] ?? [];
        if (empty($deps)) {
            $levels[$unitId] = 0;
            return 0;
        }

        $maxDepDepth = 0;
        foreach ($deps as $dep) {
            $depDepth = $this->getDepth($dep, $levels);
            if ($depDepth > $maxDepDepth) {
                $maxDepDepth = $depDepth;
            }
        }

        $levels[$unitId] = $maxDepDepth + 1;
        return $levels[$unitId];
    }

    /**
     * Resolves the current status of all units into COMPLETED, READY, or BLOCKED.
     *
     * @return array{
     *     completed: array<string, array>,
     *     ready: array<string, array>,
     *     blocked: array<string, array>,
     *     in_progress: array<string, array>
     * }
     */
    public function resolveStates(): array
    {
        $levels = $this->computeTopologicalLevels();

        $completed = [];
        $ready = [];
        $blocked = [];
        $inProgress = [];

        foreach ($this->units as $unitId => $unit) {
            $state = $unit['state'] ?? 'NOT_STARTED';
            $deps = $this->dependencies[$unitId] ?? $unit['dependencies'] ?? [];

            if (in_array($state, ['COMPLETED', 'CHECKPOINTED', 'VERIFIED'], true)) {
                $completed[$unitId] = array_merge($unit, [
                    'topological_level' => $levels[$unitId] ?? 0
                ]);
                continue;
            }

            if (in_array($state, ['IN_PROGRESS', 'IMPLEMENTING', 'VERIFYING', 'REPAIRING', 'ANALYZING'], true)) {
                $inProgress[$unitId] = array_merge($unit, [
                    'topological_level' => $levels[$unitId] ?? 0
                ]);
                continue;
            }

            // Check prerequisite states
            $missingDeps = [];
            $failedDeps = [];

            foreach ($deps as $depId) {
                $depState = $this->units[$depId]['state'] ?? 'NOT_STARTED';
                if ($depState === 'FAILED') {
                    $failedDeps[] = $depId;
                } elseif (!in_array($depState, ['COMPLETED', 'CHECKPOINTED', 'VERIFIED'], true)) {
                    $missingDeps[] = $depId;
                }
            }

            if (!empty($failedDeps)) {
                $blocked[$unitId] = array_merge($unit, [
                    'topological_level' => $levels[$unitId] ?? 0,
                    'block_reason' => 'UPSTREAM_FAILURE',
                    'failed_dependencies' => $failedDeps,
                    'missing_dependencies' => $missingDeps
                ]);
            } elseif (!empty($missingDeps)) {
                $blocked[$unitId] = array_merge($unit, [
                    'topological_level' => $levels[$unitId] ?? 0,
                    'block_reason' => 'UNRESOLVED_DEPENDENCY',
                    'missing_dependencies' => $missingDeps
                ]);
            } else {
                // All dependencies are COMPLETED -> Unit is READY!
                $ready[$unitId] = array_merge($unit, [
                    'topological_level' => $levels[$unitId] ?? 0,
                    'state' => 'READY'
                ]);
            }
        }

        // Sort ready units by topological level (ascending), then by priority
        uasort($ready, function ($a, $b) {
            if ($a['topological_level'] !== $b['topological_level']) {
                return $a['topological_level'] <=> $b['topological_level'];
            }
            $priorityWeight = ['critical' => 1, 'high' => 2, 'medium' => 3, 'low' => 4];
            $pA = $priorityWeight[$a['priority'] ?? 'medium'] ?? 3;
            $pB = $priorityWeight[$b['priority'] ?? 'medium'] ?? 3;
            return $pA <=> $pB;
        });

        return [
            'completed' => $completed,
            'ready' => $ready,
            'blocked' => $blocked,
            'in_progress' => $inProgress,
            'levels' => $levels
        ];
    }

    /**
     * Computes the downstream blast radius (transitive dependents) of a unit.
     *
     * @return string[]
     */
    public function getDownstreamUnits(string $unitId): array
    {
        $downstream = [];
        $this->findDownstream($unitId, $downstream);
        return array_unique($downstream);
    }

    private function findDownstream(string $targetId, array &$downstream): void
    {
        foreach ($this->units as $uId => $u) {
            $deps = $this->dependencies[$uId] ?? $u['dependencies'] ?? [];
            if (in_array($targetId, $deps, true) && !in_array($uId, $downstream, true)) {
                $downstream[] = $uId;
                $this->findDownstream($uId, $downstream);
            }
        }
    }
}
