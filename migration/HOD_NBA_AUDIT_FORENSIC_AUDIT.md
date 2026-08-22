# CAMPUSLYNK — NBA CRITERIA ACCREDITATION AUDIT
## PHASE 2C.8 — CROSS-ROLE FORENSIC AUDIT & PRE-MIGRATION BASELINE
**Date:** August 22, 2026  
**Auditor:** DeepMind Antigravity AI Engineering Suite  
**Status:** STRICT READ-ONLY / FORENSIC INSPECTION COMPLETE (ZERO PRODUCTION CODE MODIFIED)  
**Primary Surface:** `resources/views/hod_nba_audit.blade.php` (140 lines)  
**Print Target:** `resources/views/hod_nba_audit_print.blade.php` (152 lines)  
**Primary Route:** `/hod/nba-audit`  
**Database Table:** `nba_criteria_documents`  
**Migration Table:** `2026_07_05_120000_create_audit_compliance_tables.php`

---

## 1. Executive Summary

The **NBA Criteria Accreditation Sub-Desk** (`/hod/nba-audit`) provides Department Heads and Academic Administrators with an accreditation document repository organized into **9 National Board of Accreditation (NBA) Self Assessment Report (SAR) Criteria Folders**.

### Key Subsystem Highlights:
- **Criteria Structure:** Covers 9 Tier-1 Diploma / Engineering Accreditation Criteria containing 18 statutory compliance documents.
- **Document Lifecycle:** Tracks document status (`Pending` $\rightarrow$ `Uploaded` $\rightarrow$ `Verified`), upload timestamps, and auditor mobile IDs.
- **File Management:** Supports direct multi-format uploads (PDF, JPG, PNG up to 5MB) stored under `public/uploads/nba_audit/`.
- **Accreditation Printout:** Generates official A4 Portrait NBA Compliance Checklists (`/hod/nba-audit/print`) with tripartite signature blocks (Class Tutor, HOD, Principal).
- **Current Technical State:** Standalone legacy dark theme (`#0b1329`), `@tailwindcss/browser@4` runtime CDN, Google Fonts Inter, and Material Symbols Rounded CDN.

---

## 2. Files Investigated

| # | File Path | Type | Purpose |
|:---:|:---|:---:|:---|
| 1 | [`resources/views/hod_nba_audit.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_nba_audit.blade.php) | Blade View (140 lines) | Main HOD NBA criteria document repository and upload interface. |
| 2 | [`resources/views/hod_nba_audit_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_nba_audit_print.blade.php) | Blade View (152 lines) | Official A4 Portrait NBA criteria checklist print template. |
| 3 | [`routes/web.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/routes/web.php) | Route Registry (Lines 1595–1696) | NBA route closures for view, file upload, and print checklist. |
| 4 | [`database/migrations/2026_07_05_120000_create_audit_compliance_tables.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/database/migrations/2026_07_05_120000_create_audit_compliance_tables.php) | Database Migration | Schema definition for `nba_criteria_documents` table. |
| 5 | [`config/navigation/hod.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/config/navigation/hod.php) | Navigation Config | HOD sidebar navigation structure. |
| 6 | [`resources/views/hod_dashboard.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_dashboard.blade.php) | Blade Workspace | Report Centre Card 8 linking to `/hod/nba-audit`. |
| 7 | [`resources/views/student_course_exit_survey.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/student_course_exit_survey.blade.php) | Blade View | Student indirect Course Outcome (CO) survey feeding Criterion 3. |

---

## 3. Cross-Role Comparison

| Capability / Surface | HOD | Principal / Admin | Faculty / Lecturer | Student | Shared Backend Infrastructure |
|:---|:---:|:---:|:---:|:---:|:---|
| **NBA Criteria Folders Console** | **YES** (`/hod/nba-audit`) | **YES** (Permitted via role check in `/hod/nba-audit`) | No | No | `nba_criteria_documents` table |
| **Document Upload & Replacement** | **YES** (Per document row) | **YES** | No | No | `POST /hod/nba-audit/upload` |
| **Document PDF Viewing** | **YES** (Public link) | **YES** | No | No | `/uploads/nba_audit/...` filesystem |
| **Checklist A4 Print Generation** | **YES** (`/hod/nba-audit/print`) | **YES** | No | No | `hod_nba_audit_print.blade.php` |
| **Course File & CO-PO Mapping (Crit 3)** | Read & Audit | Read & Audit | **YES** (In Lecturer Dashboard) | No | `course_files` & `batch_subjects` |
| **Course Exit Survey (Indirect Attainment)** | Read Attainment | Read Attainment | Review Indirect COs | **YES** (Submits exit survey) | `student_course_exit_surveys` table |

---

## 4. Full Functional Inventory

| # | Feature / Workflow | UI Element & Trigger | Route / Endpoint | HTTP Method | Database Table | Response / UI Effect |
|:---:|:---|:---|:---|:---:|:---|:---|
| **1** | **Academic Year Filter** | `<select name="academic_year" onchange="this.form.submit()">` | `/hod/nba-audit` | `GET` | `nba_criteria_documents` | Reloads criteria document list for selected year. Seeds default documents if none exist. |
| **2** | **Document Upload / Replace** | `<input type="file" name="file" onchange="this.form.submit()">` inside row form | `/hod/nba-audit/upload` | `POST` (multipart) | `nba_criteria_documents` | Validates file (PDF/JPG/PNG max 5MB), saves to `/uploads/nba_audit/`, updates status to `Uploaded`, redirects with success toast. |
| **3** | **View Uploaded Document** | `<a href="{{ $doc->file_path }}" target="_blank">View PDF</a>` | File Path URL | `GET` | Storage Disk | Opens uploaded PDF/image document in a new browser tab. |
| **4** | **Print Compliance Checklist** | `<a href="/hod/nba-audit/print" target="_blank">Print Audit</a>` | `/hod/nba-audit/print` | `GET` | `nba_criteria_documents` | Renders formatted A4 Portrait checklist sheet with signature blocks. |

---

## 5. NBA Criteria Breakdown (18 Statutory Documents Across 9 Folders)

```
NBA Accreditation Document Hierarchy (Criteria 1 - 9)
│
├── Criteria 1 Folder: Vision, Mission & Program Educational Objectives
│   ├── Document 1: Vision, Mission & Program Educational Objectives (PEOs)
│   └── Document 2: Program Specific Outcomes (PSOs) Statement Review
│
├── Criteria 2 Folder: Program Curriculum & Teaching-Learning Process
│   ├── Document 1: Program Curriculum & Structure Design
│   └── Document 2: Teaching-Learning Process Methodologies
│
├── Criteria 3 Folder: Course Outcomes & Program Outcomes (CO-PO)
│   ├── Document 1: Course Outcomes (CO) Attainments
│   └── Document 2: Program Outcomes (PO) Attainments Matrix
│
├── Criteria 4 Folder: Students' Performance & Success Rate
│   ├── Document 1: Student Enrollment Statistics & Success Rate
│   └── Document 2: Placement, Higher Studies & Entrepreneurship Records
│
├── Criteria 5 Folder: Faculty Information & Contributions
│   ├── Document 1: Student-Faculty Ratio (SFR) Statement
│   └── Document 2: Faculty Retention & Professional Development Profiles
│
├── Criteria 6 Folder: Facilities & Technical Support
│   ├── Document 1: Laboratory Maintenance Logbooks Audit
│   └── Document 2: Technical Support Staff Roster
│
├── Criteria 7 Folder: Continuous Improvement
│   ├── Document 1: Continuous Attainment Improvement Action Plan
│   └── Document 2: Academic Audit Reviews & Feedback Closure
│
├── Criteria 8 Folder: First Year Academics
│   ├── Document 1: First-Year Academics Student-Faculty Ratio
│   └── Document 2: First-Year Continuous Internal Assessment Roster
│
└── Criteria 9 Folder: Student Support Systems & Governance
    ├── Document 1: Student Support Systems Feedback Log
    └── Document 2: Governance Structure, Budget & Financial Resources Audit
```

---

## 6. DOM Preservation Inventory

### Critical Elements & Form Names:
| Identifier / Attribute | Scope | Criticality | Purpose |
|:---|:---:|:---:|:---|
| `<form method="GET" action="/hod/nba-audit">` | Header Bar | **CRITICAL** | Year switcher form. |
| `<select name="academic_year">` | Header Bar | **CRITICAL** | Academic year parameter selector. |
| `<form method="POST" action="/hod/nba-audit/upload">` | Document Row | **CRITICAL** | Multi-part document upload form per document row. |
| `<input type="hidden" name="id" value="{{ $doc->id }}">` | Document Row | **CRITICAL** | Document UUID primary key. |
| `<input type="file" name="file">` | Document Row | **CRITICAL** | File input (`accept=".pdf,image/*"`). |
| `onchange="this.form.submit()"` | Form triggers | **CRITICAL** | Native auto-submit on file selection. |

---

## 7. JavaScript Preservation Inventory

- **Inline JavaScript:** The legacy view uses native browser form submissions (`onchange="this.form.submit()"`) without external JavaScript frameworks.
- **Client-Side Behavior:** Fast, robust, and zero client-side dependencies.
- **Preservation Requirement:** Maintain the exact form attributes (`enctype="multipart/form-data"`, `@csrf`, hidden `name="id"`, file `name="file"`).

---

## 8. Backend / API Contract Matrix

| HTTP Method | Route URI | Auth / Role Check | Input Parameters | Database Operations | Response |
|:---:|:---|:---|:---|:---|:---|
| `GET` | `/hod/nba-audit` | HOD, Principal (`Session::get('userBranch')`) | `academic_year` (optional) | Auto-seeds default 18 document records if missing; queries `nba_criteria_documents` grouped by `criteria_no`. | Renders `hod_nba_audit.blade.php`. |
| `POST` | `/hod/nba-audit/upload` | HOD, Principal | `id` (UUID string), `file` (PDF/JPG/PNG max 5MB) | Moves file to `public/uploads/nba_audit/`; updates `file_path`, `status = 'Uploaded'`, `uploaded_by = Session::get('userMobile')`. | Redirects `back()->with('success', ...)`. |
| `GET` | `/hod/nba-audit/print` | HOD, Principal | `academic_year` | Queries `nba_criteria_documents` grouped by `criteria_no`. | Renders `hod_nba_audit_print.blade.php`. |

---

## 9. Database & Storage Dependencies

### Database Table: `nba_criteria_documents`
```sql
CREATE TABLE `nba_criteria_documents` (
  `id` char(36) NOT NULL,
  `criteria_no` int(11) NOT NULL,
  `document_name` varchar(150) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Pending',
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);
```

### Document Status Values:
- `Pending` (Default initial state)
- `Uploaded` (File attached, audit pending)
- `Verified` (Audited and confirmed)

### Storage Filesystem:
- Path: `public/uploads/nba_audit/nba_{timestamp}_{originalName}`
- Direct public URL: `/uploads/nba_audit/...`

> [!NOTE]
> Database schema and storage architecture are **OUT OF SCOPE** for the UI migration.

---

## 10. Print / Export Pipeline

- **Print Route:** `GET /hod/nba-audit/print`
- **Print View:** [`resources/views/hod_nba_audit_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_nba_audit_print.blade.php) (152 lines)
- **Format:** A4 Portrait (`@page { size: A4 portrait; margin: 0.5cm; }`), monochrome high-contrast tables.
- **Includes:** Carmel Polytechnic College heading, criteria checklist table (Criteria 1–9), status indicators (`VERIFIED`, `UPLOADED`, `MISSING`), and 3 official sign-off blocks (Class Tutor, HOD, Principal).
- **Migration Directive:** **OFFICIAL PRINT TEMPLATE PRESERVED 100% UNTOUCHED**.

---

## 11. Current UI Audit

| Element | Current Legacy State | Violations / Deficiencies |
|:---|:---|:---|
| **Layout Shell** | Standalone `<!DOCTYPE html>` | Isolated shell, missing unified CampusLynk topbar and sidebar navigation. |
| **Canvas Background** | `#0b1329` dark gradient with radial glow | Violates the clean `#FAFAFB` canvas rule. |
| **Card Containers** | `bg-slate-900/90 border border-slate-800` | Dark surfaces with low contrast text. |
| **Typography** | Inter via Google CDN, micro-fonts (10px, 12px) | Violates Poppins and 14px minimum interactive typography standard. |
| **Icons** | Material Symbols (`menu_book`, `folder`, `check_circle`, `cloud_upload`) | Violates native Lucide icon standard. |
| **Navigation** | Back link points to legacy `/hod/report-centre` | Should integrate cleanly with Report Centre sub-desk breadcrumbs. |

---

## 12. CDN & Legacy Dependency Audit

- **Tailwind CDN:** `<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>` (Must be removed; uses canonical Vite bundle).
- **Google Fonts CDN:** `<link href="https://fonts.googleapis.com/css2?family=Inter:..." rel="stylesheet">` (Must be removed; uses Poppins).
- **Material Symbols CDN:** `<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:..." rel="stylesheet" />` (Must be removed; uses Lucide).

---

## 13. Navigation Audit (`config/navigation/hod.php`)

- **Parent Navigation:** HOD Sidebar `Report Centre` (`handleHodSidebarNav('report_centre')`).
- **Report Centre Catalog:** Card 8 ("NBA Criteria Accreditation") links to `/hod/nba-audit`.
- **Sub-Desk Position:** Dedicated sub-desk inheriting `<x-layouts.app-shell>` with breadcrumb navigation back to `Report Centre`.

---

## 14. Recommended Future UI Architecture

```
CampusLynk App Shell (<x-layouts.app-shell activeNav="report_centre">)
│
├── Topbar: "NBA Criteria Accreditation"
│
└── Breadcrumb: Report Centre  /  NBA Criteria Accreditation
    │
    ├── Header Card:
    │   ├── Department Badge & Title
    │   ├── Academic Year Selector (<select name="academic_year">)
    │   ├── Print Audit Action (printer icon)
    │   └── Back to Report Centre Button
    │
    ├── Overview Metrics Card:
    │   ├── Total Criteria: 9 Folders
    │   ├── Total Documents: 18 Documents
    │   ├── Uploaded / Verified Count
    │   └── Pending Documents Count
    │
    ├── Segmented Criteria Filter / Quick Navigation:
    │   ├── [All Criteria Folders (1–9)]
    │   ├── [Institutional & Curriculum (Crit 1–3)]
    │   ├── [Students & Faculty (Crit 4–6)]
    │   └── [Continuous Improvement & Support (Crit 7–9)]
    │
    └── 9 Criteria Folder Cards (Criteria 1 - 9):
        ├── Card Header: Folder Numeral Badge, Folder Title, SAR Tag, Status Summary
        └── Document Rows:
            ├── Document Name & Description
            ├── Status Badge (Verified: Emerald, Uploaded: Amber, Pending: Slate)
            ├── View PDF Action (external-link icon, if file_path exists)
            └── File Upload Trigger (upload-cloud icon, native auto-submit file input)
```

---

## 15. Recommended UX Improvements

1. **Criteria Grouping & Quick Filter:** Allow switching between grouped views (Criteria 1–3, 4–6, 7–9) or viewing all 9 folders simultaneously.
2. **Document Status Chips:**
   - **Verified:** `bg-emerald-50 text-emerald-700 border-emerald-200` with Lucide `check-circle-2`.
   - **Uploaded (Pending Audit):** `bg-amber-50 text-amber-700 border-amber-200` with Lucide `clock`.
   - **Pending / Missing:** `bg-slate-100 text-slate-500 border-slate-200` with Lucide `alert-circle`.
3. **Upload Feedback:** Styled file dropzone button with Lucide `upload-cloud` and 14px text.
4. **Contextual Metrics Bar:** Derived counters showing total verified, uploaded, and pending documents for the active academic year.

---

## 16. Preservation Contract Matrix

| Subsystem Component | Current Implementation | Migration Action |
|:---|:---|:---:|
| **Route Contracts** | `/hod/nba-audit`, `/hod/nba-audit/upload`, `/hod/nba-audit/print` | **PRESERVE 100%** |
| **Upload Form Contract** | `POST /hod/nba-audit/upload`, `name="id"`, `name="file"` | **PRESERVE 100%** |
| **Auto-Submit on Upload** | `onchange="this.form.submit()"` | **PRESERVE 100%** |
| **Database Table & Columns** | `nba_criteria_documents` (`id`, `criteria_no`, `document_name`, `file_path`, `status`) | **PRESERVE 100%** |
| **Upload Storage Path** | `public/uploads/nba_audit/` | **PRESERVE 100%** |
| **A4 Print View** | `hod_nba_audit_print.blade.php` | **PRESERVE 100%** |
| **UI Presentation & Shell** | Standalone dark HTML with CDNs | **MIGRATE to `<x-layouts.app-shell>`** |

---

## 17. Risk Assessment

| Risk Category | Level | Mitigation |
|:---|:---:|:---|
| **File Upload Breakage** | **LOW** | Preserve exact `name="id"` and `name="file"` attributes, multipart enctype, and CSRF token. |
| **Document State Desync** | **ZERO** | Document state is dynamically fetched from `nba_criteria_documents` on page load. |
| **Print View Regressions** | **ZERO** | `hod_nba_audit_print.blade.php` is completely untouched. |
| **Role Authorization** | **ZERO** | Auth checks remain enforced at the route closure level. |

---

## 18. Proposed Migration Scope (Phase 2C.8)

- **Target File:** [`resources/views/hod_nba_audit.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_nba_audit.blade.php)
- **Scope of Work:**
  - Wrap in `<x-layouts.app-shell>`.
  - Remove all external CDN links.
  - Implement `#FAFAFB` canvas with clean `#FFFFFF` criteria cards.
  - Upgrade typography to Poppins with 14px minimum control sizes.
  - Replace Material Symbols with native Lucide icons (`menu-book`, `folder`, `upload-cloud`, `file-text`, `printer`, `check-circle-2`, `clock`).
  - Add segmented criteria filter and progress counters derived from `$documents`.

---

## 19. Explicitly Out-of-Scope Items

- DO NOT modify `routes/web.php`.
- DO NOT modify `hod_nba_audit_print.blade.php`.
- DO NOT modify database migrations or table schemas.
- DO NOT modify file storage paths or upload logic.
- DO NOT modify authorization checks.

---

## 20. NBA Audit Migration Baseline Summary

```
============================================================
NBA AUDIT MIGRATION BASELINE
============================================================

View:
resources/views/hod_nba_audit.blade.php

Route:
/hod/nba-audit

Criteria Folders:
9 statutory criteria folders (18 compliance documents)

Forms:
1 GET academic year switcher form + 18 POST multipart upload forms (1 per document row)

Upload Endpoint:
POST /hod/nba-audit/upload (id, file)

Print Pipeline:
GET /hod/nba-audit/print -> hod_nba_audit_print.blade.php

JavaScript Functions:
Native form submission (onchange="this.form.submit()")

Database Table:
nba_criteria_documents

Business Logic Risk:
ZERO (Upload closure and database queries untouched)

UI Migration Risk:
LOW (Clean mapping to CampusLynk App Shell & components)

Recommended Shell:
<x-layouts.app-shell title="CampusLynk - NBA Criteria Accreditation" topbarTitle="NBA Criteria Accreditation" activeNav="report_centre">

Recommended Next Step:
UI migration after baseline approval (Phase 2C.8)
============================================================
```

---

**AUDIT COMPLETE — NO PRODUCTION FILES MODIFIED.**
