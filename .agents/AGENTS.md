# Workspace Behavior Rules

## Responsive Font Sizes Policy
- **NO Tiny Fonts:** Avoid using extremely small fonts (`text-xs`, `text-[10px]`, `text-[11px]`, or `text-[9px]`) for inputs, controls, forms, table data, and labels across all user and staff interfaces.
- **Minimum Font Standard:** Always use at least `text-sm` (14px) or `text-base` (16px) for normal data entry text, form elements, descriptions, and labels to maintain excellent readability on mobile, tablet, and desktop screens.

## Local Server Behavior
- **Never Run Node Server (server.js):** Do not start or run the Node.js root server (`server.js`) for local development. Keep it only as a universal pointer/reference.
- **Always Run Laravel Server:** Always run the Laravel development server (`php artisan serve` within `carmel-linx-laravel`) for local development and testing.

## Environment Synchronization & Deployment Policy
- **Local-First Development:** Always perform edits, updates, and testing on the local workspace first. Do not make ad-hoc edits directly on the live AWS server.
- **Git as Single Source of Truth:** Stage, commit, and push all modifications to the remote Git repository (`origin/main`) before deploying.
- **Standardized Deployment Flow:** For deploying to the live AWS environment, always SSH into the AWS instance, pull from Git, clear Laravel caches (`config:clear`, `cache:clear`, `view:clear`, `route:clear`), restart queue workers (`queue:restart`), and restart the PHP-FPM service to apply modifications cleanly.

## Syllabus Parsing & Lesson Planner Guidelines
- **Module Content Isolation:** When parsing syllabus PDFs via regex or AI fallbacks, always restrict module extraction to the text *after* the first occurrence of "Course Outline" (case-insensitive). This avoids matching outcome tables at the beginning of the document and duplicating Module I contents into other modules.
- **Clean Structure Rendering:** Filter out empty or blank Course Outcomes and Modules (such as V and VI) in dashboard views (`renderCourseStructure`) to dynamically reflect the actual structure.
- **Series Tests Configuration:** Automatically append exactly 4 Series Tests sequentially at the end of the semester layout, scaling the plan to the target hours perfectly.
- **Template System Integrity:** Always load cross-batch templates directly by querying rows from the `lesson_plan_templates` table (`day_no`, `co_id`, `topic_content`, etc.), rather than looking for a single non-existent `template_data` JSON column.