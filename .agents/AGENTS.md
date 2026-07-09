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