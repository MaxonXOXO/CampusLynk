# CampusLynk UI Migration Changelog
**Project:** CampusLynk AMS UI Migration (v1.0.0 Architecture)  
**Standard:** Strict Page-by-Page Migration & Verification Protocol  

---

## Migration Entries

### [2026-08-16] — Phase 1: Authentication & Startup Experience (Baseline)
* **Migration Date:** 2026-08-16
* **Original Files:**
  * `resources/views/login.blade.php` (Legacy dark layout, Tailwind CDN)
  * `resources/views/parent_login.blade.php`
* **Migrated Files:**
  * `resources/views/login.blade.php` (Promoted from verified `modern/auth/login.blade.php`)
  * `resources/views/modern/auth/splash.blade.php`
  * `resources/views/modern/auth/forgot-password.blade.php`
  * `resources/views/modern/auth/reset-password.blade.php`
  * `resources/views/modern/auth/access-denied.blade.php`
  * `resources/views/modern/auth/session-expired.blade.php`
  * `resources/views/modern/auth/loading.blade.php`
  * `resources/views/modern/auth/auth-error.blade.php`
* **Components Introduced:**
  * `Button.v1` (`resources/views/components/ui/button.blade.php`)
  * `Input.v1` (`resources/views/components/ui/input.blade.php`)
  * `Select.v1` (`resources/views/components/ui/select.blade.php`)
  * `Badge.v1` (`resources/views/components/ui/badge.blade.php`)
  * `Alert.v1` (`resources/views/components/ui/alert.blade.php`)
  * `AuthLayout.v1` (`resources/views/components/layouts/auth-layout.blade.php`)
* **Design Tokens Used:**
  * Color Hierarchy: Level 1 (70% neutral `#FAFAFB` / `#FFFFFF`), Level 2 (15% soft tint), Level 3 (10% icons), Level 4 (5% CTA `#2563EB`).
  * Typography: Google Font Poppins (300, 400, 500, 600, 700).
  * Radius: `rounded-xl` (12px), `rounded-2xl` (16px), `rounded-3xl` (24px).
  * Icons: Lucide Vector Icons (2px stroke) + itsHover CSS keyframe animations.
* **Issues Discovered & Resolved:**
  * Resolved transient role switcher outline glitch during Student/Staff toggle.
  * Replaced native OS select box with custom CampusLynk floating dropdown menu.
  * Standardized auth recovery and error screen backgrounds to light neutral canvas.
* **Breaking Changes:** None (All backend routes, POST handlers, and session flows 100% preserved).
* **Verification Status:** ✅ VERIFIED & APPROVED (Vite build passed, HTTP 200 OK on all auth endpoints).

---

### [2026-08-16] — Phase 2A: Student Dashboard (`student_dashboard.blade.php`)
* **Migration Date:** 2026-08-16
* **Original File:** `resources/views/student_dashboard.blade.php` (Legacy dark layout, Material symbols, Tailwind CDN)
* **Migrated File:** `resources/views/student_dashboard.blade.php` (CampusLynk Design Language v1.0.0, Vite pipeline)
* **Components Introduced:**
  * `<x-layout.sidebar role="student" />` (Unified standalone role-based navigation)
  * `<x-layout.topbar />` (Topbar with student metadata badges)
  * `<x-layout.notifications />`
  * Standard table markup matching `<x-ui.table />`
  * Soft semantic badge pills matching `<x-ui.badge />`
* **Design Tokens Used:**
  * 70/15/10/5 Color Balance (70% `#FAFAFB` canvas, `#FFFFFF` cards, `#E5E7EB` borders; 15% soft hover; 10% Lucide icons; 5% blue `#2563EB` action buttons).
  * Typography: Google Font Poppins, minimum font size >= 14px (`text-sm`) for inputs and tables.
  * Radius: `rounded-xl` (12px), `rounded-2xl` (16px), `rounded-3xl` (24px).
  * Icons: Lucide 2px vector line icons (zero emojis).
* **Issues Discovered & Resolved:**
  * Eliminated `@tailwindcss/browser@4` runtime dependency in favor of the production Vite bundle.
  * Preserved 100% of AJAX endpoints (`/api/student/academic-report`, `/student/tests/active`, `/student/activity-points`, etc.).
  * Upgraded Chart.js trend charts and SVG gauges to clean high-contrast neutral and primary blue colors.
* **Breaking Changes:** None.
* **Verification Status:** ✅ VERIFIED & AUDITED ([`audit-phase-2a-student-dashboard.md`](file:///d:/AMs/academic-platform/migration/audit-phase-2a-student-dashboard.md)).

---

<!-- Future migration entries will be appended here page-by-page -->
