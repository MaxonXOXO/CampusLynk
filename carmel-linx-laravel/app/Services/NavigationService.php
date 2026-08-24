<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class NavigationService
{
    /**
     * Resolve the complete navigation items array for a user role.
     *
     * @param string|null $role
     * @param string|null $active
     * @param array $context
     * @return array
     */
    public static function getNavigationItems(?string $role = null, ?string $active = null, array $context = []): array
    {
        $resolvedRole = self::resolveRoleKey($role ?? Session::get('userRole', Session::get('role', 'faculty')));
        $config = config('navigation.roles.' . $resolvedRole);

        if (!$config) {
            $config = config('navigation.roles.faculty');
        }

        $items = self::resolveRoleItems($resolvedRole);

        // Apply contextual dynamic roles (e.g. Faculty or Demonstrator who is also a Tutor or Mentor or HOD)
        if ($resolvedRole === 'faculty' || $resolvedRole === 'demonstrator') {
            $userId = Session::get('userId');
            $sessionRole = Session::get('userRole');
            $isPrincipal = in_array($sessionRole, ['Principal', 'Executive']) || str_contains(strtolower($sessionRole ?? ''), 'principal');
            $isAdminUser = in_array($sessionRole, ['Admin', 'Super_Admin', 'SuperAdmin', 'Chairman', 'Academic_Coordinator', 'Academic Coordinator']);

            if ($isPrincipal) {
                $returnItem = [
                    [
                        'id' => 'return_principal',
                        'label' => 'Return to Dashboard',
                        'icon' => 'arrow-left',
                        'url' => '/dashboard/principal',
                        'position' => 'before:my_batches',
                    ]
                ];
                $items = self::insertItems($items, $returnItem);
            } elseif ($isAdminUser) {
                $returnItem = [
                    [
                        'id' => 'return_admin',
                        'label' => 'Return to Dashboard',
                        'icon' => 'arrow-left',
                        'url' => '/dashboard/admin',
                        'position' => 'before:my_batches',
                    ]
                ];
                $items = self::insertItems($items, $returnItem);
            } elseif ($sessionRole === 'HOD') {
                $returnItem = [
                    [
                        'id' => 'return_hod',
                        'label' => 'Return to Dashboard',
                        'icon' => 'arrow-left',
                        'url' => '/dashboard/hod',
                        'position' => 'before:my_batches',
                    ]
                ];
                $items = self::insertItems($items, $returnItem);
            } elseif (in_array($sessionRole, ['Trade_Instructor', 'Workshop_Superintendent'])) {
                $returnItem = [
                    [
                        'id' => 'return_demonstrator',
                        'label' => 'Return to Console',
                        'icon' => 'arrow-left',
                        'url' => '/dashboard/demonstrator',
                        'position' => 'before:my_batches',
                    ]
                ];
                $items = self::insertItems($items, $returnItem);
            }

            if ($isPrincipal || $isAdminUser || $sessionRole === 'HOD' || in_array($sessionRole, ['Trade_Instructor', 'Workshop_Superintendent'])) {
                // Remove redundant items that already exist in the executive main dashboard sidebar
                $items = array_values(array_filter($items, function ($it) {
                    return !in_array($it['id'], ['prof_activities', 'profile']);
                }));
            }

            $isHod = ($sessionRole === 'HOD') || ($context['is_hod'] ?? false);
            $isTutor = ($context['is_tutor'] ?? false);
            $isMentor = ($context['is_mentor'] ?? false);

            // Check database context if not explicitly passed in context
            if (!$isTutor && !$isMentor && $userId) {
                try {
                    $isTutor = \App\Models\ClassManagement::where('tutor_mobile_no', $userId)->exists()
                        || \App\Models\R26ClassManagement::where('tutor_mobile_no', $userId)->exists();
                    $isMentor = \App\Models\ClassManagement::where('mentor_mobile_no', $userId)->exists()
                        || \App\Models\R26ClassManagement::where('mentor_mobile_no', $userId)->exists();
                } catch (\Throwable $e) {
                    // Fail silently to avoid breaking session flow
                }
            }

            // Keep faculty desk clean with Return to Dashboard link when HOD accesses My Batches

            if ($isTutor || $isMentor) {
                $tutorItems = [];
                if ($isTutor) {
                    $tutorItems[] = [
                        'id' => 'tutor_console',
                        'label' => 'Tutor Console',
                        'icon' => 'user-check',
                        'url' => '/dashboard/tutor',
                        'position' => 'after:my_batches',
                    ];
                }
                $tutorItems[] = [
                    'id' => 'my_mentoring',
                    'label' => 'My Mentoring',
                    'icon' => 'heart-handshake',
                    'url' => '/dashboard/tutor',
                    'onclick' => "sessionStorage.setItem('openMentoring', 'true'); window.location.href='/dashboard/tutor';",
                    'position' => 'after:tutor_console',
                ];
                $items = self::insertItems($items, $tutorItems);
            }
        }

        // Mark active item state
        return array_map(function ($item) use ($active) {
            $item['is_active'] = ($active !== null && $active === ($item['id'] ?? null));
            return $item;
        }, $items);
    }

    /**
     * Resolve the desk subtitle displayed in the sidebar brand header.
     *
     * @param string|null $role
     * @return string
     */
    public static function getDeskSubtitle(?string $role = null): string
    {
        $sessionRole = Session::get('userRole', Session::get('role', 'faculty'));
        
        if (in_array($sessionRole, ['Principal', 'Executive']) || str_contains(strtolower($sessionRole ?? ''), 'principal')) {
            return 'Principal Desk';
        }
        if (in_array($sessionRole, ['Admin', 'Super_Admin', 'SuperAdmin', 'Chairman'])) {
            return 'Control Desk';
        }
        if ($sessionRole === 'HOD') {
            return 'HOD Console';
        }
        if ($sessionRole === 'Tutor') {
            return 'Tutor Desk';
        }
        if ($sessionRole === 'Demonstrator') {
            return 'Demonstrator Console';
        }
        
        $resolvedRole = self::resolveRoleKey($role ?? $sessionRole);
        $config = config('navigation.roles.' . $resolvedRole);

        if ($config && isset($config['subtitle'])) {
            return $config['subtitle'];
        }

        return 'Faculty Platform';
    }

    /**
     * Recursively resolve navigation items with inheritance.
     *
     * @param string $roleKey
     * @return array
     */
    protected static function resolveRoleItems(string $roleKey): array
    {
        $config = config('navigation.roles.' . $roleKey);
        if (!$config) {
            return [];
        }

        $inheritedItems = [];
        if (!empty($config['inherits'])) {
            $inheritedItems = self::resolveRoleItems($config['inherits']);
        }

        $currentItems = $config['items'] ?? [];

        if (empty($inheritedItems)) {
            return $currentItems;
        }

        return self::insertItems($inheritedItems, $currentItems);
    }

    /**
     * Merge items into an existing item array respecting 'position' directives.
     *
     * @param array $baseItems
     * @param array $newItems
     * @return array
     */
    protected static function insertItems(array $baseItems, array $newItems): array
    {
        $result = $baseItems;

        foreach ($newItems as $newItem) {
            $position = $newItem['position'] ?? 'append';

            if (str_starts_with($position, 'after:')) {
                $targetId = substr($position, 6);
                $inserted = false;
                $temp = [];

                foreach ($result as $existingItem) {
                    $temp[] = $existingItem;
                    if (($existingItem['id'] ?? '') === $targetId) {
                        $temp[] = $newItem;
                        $inserted = true;
                    }
                }

                if ($inserted) {
                    $result = $temp;
                    continue;
                }
            } elseif (str_starts_with($position, 'before:')) {
                $targetId = substr($position, 7);
                $inserted = false;
                $temp = [];

                foreach ($result as $existingItem) {
                    if (($existingItem['id'] ?? '') === $targetId) {
                        $temp[] = $newItem;
                        $inserted = true;
                    }
                    $temp[] = $existingItem;
                }

                if ($inserted) {
                    $result = $temp;
                    continue;
                }
            }

            // Default: append if not already present
            $exists = false;
            foreach ($result as $existing) {
                if (($existing['id'] ?? '') === ($newItem['id'] ?? '')) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $result[] = $newItem;
            }
        }

        return $result;
    }

    /**
     * Normalize raw role strings to registered navigation keys.
     *
     * @param string $rawRole
     * @return string
     */
    public static function resolveRoleKey(string $rawRole): string
    {
        $normalized = strtolower(trim($rawRole));

        if (empty($normalized)) {
            return 'faculty';
        }

        // Direct key check
        if (config('navigation.roles.' . $normalized)) {
            return $normalized;
        }

        // Aliases check
        $aliases = config('navigation.aliases', []);
        if (isset($aliases[$normalized])) {
            return $aliases[$normalized];
        }

        // Fuzzy matches
        if (str_contains($normalized, 'student')) return 'student';
        if (str_contains($normalized, 'principal')) return 'principal';
        if (str_contains($normalized, 'super_admin') || str_contains($normalized, 'superadmin')) return 'super_admin';
        if (str_contains($normalized, 'admin') || str_contains($normalized, 'chairman') || str_contains($normalized, 'coordinator')) return 'admin';
        if (str_contains($normalized, 'hod')) return 'hod';
        if (str_contains($normalized, 'tutor')) return 'tutor';

        return 'faculty';
    }
}
