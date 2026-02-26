# Lab 7 Report: Error Handling and Error Logging

## Goal
Implement secure error handling, user-safe fallback behavior, and centralized error logging.

## Implemented
- Added global error subsystem in `error_handler.php`:
  - `set_error_handler()` for warnings/notices,
  - `set_exception_handler()` for uncaught exceptions,
  - `register_shutdown_function()` for fatal errors,
  - all events written to `logs/errors.log`.
- Disabled direct error display to users (`display_errors=0`).
- Added static fallback page:
  - `safe.html` (contains at least one link back to `index.php`).
- Updated DB connection in `db.php`:
  - internal exception details are logged,
  - user receives generic service-unavailable behavior.
- Added admin-only error log viewer:
  - `view_errors.php` with `errors.view` permission.
- Kept user-facing feedback concise and safe via existing form-level messages.

## Why Visible Internal Errors Are Dangerous
If raw framework/DB error details are shown to normal users, attackers may learn:
- table names and SQL structure,
- file system paths,
- stack traces and internal endpoints,
- library versions and server specifics.

This information directly helps exploit development.

## Why Safe Page Should Be Static
- It must stay available even when dynamic stack is unstable.
- It should not depend on DB, sessions, or templates.
- It gives a controlled fallback UX and avoids exposing internals.

## Error Log Protection
- Error details are written to text log file only.
- Access to `view_errors.php` is restricted to admin role through RBAC permission.

## How to Run
1. Initialize DB:
   - `mysql -u root -p < init.sql`
2. Start server:
   - `php -S localhost:8000`
3. Login as admin:
   - `admin / admin123`
4. Open from admin panel:
   - `view_errors.php`

## Conclusion
Lab 7 prevents sensitive error disclosure to users while preserving full internal diagnostics for administrators.
