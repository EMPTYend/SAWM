# Lab 6 Report: User Action Logging for Security

## Goal
Add security logging of user activity and provide admin-only log viewing.

## Implemented
- Added `logger.php` with file-based JSON logging.
- Added log storage path:
  - `logs/actions.log` (auto-created if missing).
- Logged security events:
  - successful login,
  - failed login,
  - validation-rejected login,
  - logout,
  - denied access (no auth / wrong role / no permission),
  - admin panel open,
  - manager panel open,
  - account list view,
  - account edit open,
  - account update,
  - account delete success/failure.
- Added admin-only log viewer page:
  - `view_logs.php`.
- Added RBAC permission:
  - `logs.view` assigned only to role `administrator`.

## Why Logging Is Required
- Detect suspicious behavior and abuse attempts.
- Support incident investigation and accountability.
- Correlate actions over time (who/when/what).

## Privacy and Log Content
The log stores operational security metadata only:
- timestamp,
- action code,
- user id/login/role,
- IP,
- non-sensitive details.

Sensitive secrets (raw passwords, session tokens) are not logged.

## Log Analysis and Rotation
Recommended operational practices:
- monitor unusual failed login patterns,
- review permission-denied events,
- archive/rotate large logs periodically,
- restrict read access to log files.

## How to Run
1. Initialize DB:
   - `mysql -u root -p < init.sql`
2. Start server:
   - `php -S localhost:8000`
3. Login as admin:
   - `admin / admin123`
4. Open:
   - `view_logs.php` from admin panel.

## Conclusion
Lab 6 adds security auditability by recording user actions and exposing logs only to authorized administrator accounts.
