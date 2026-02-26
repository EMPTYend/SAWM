# Lab 5 Report: Access Control to Web Application

## Goal
Implement mandatory authentication and role-based access control (RBAC).

## Baseline Vulnerability (from previous labs)
Before session control, opening `admin.php` directly by URL gave access without login.
In this lab that issue is fixed.

## Implemented
- Added secure session handling in `auth.php`:
  - session start,
  - session cookie security flags,
  - session ID regeneration after login,
  - logout with session reset.
- Added role field in `user` table with values:
  - `administrator`,
  - `manager`.
- Added RBAC tables:
  - `role`,
  - `permission`,
  - `role_permission`.
- Added role-based redirect after login:
  - administrator -> `admin.php`,
  - manager -> `manager.php`.
- Added protected manager functions:
  - account viewing (`accounts.php`),
  - account editing/deleting (`edit_user.php`, `delete_user.php`).
- Added mandatory permission checks:
  - `require_role()` and `require_permission()`.
- Added logout flow from both closed areas via `logout.php`.

## Access Control Verification
1. Unauthenticated user opening `admin.php` or `manager.php` is redirected to login page.
2. `administrator` account can enter only administrator panel.
3. `manager` account can enter manager panel and use account-management features.
4. Restricted actions are blocked when required permission is absent.
5. Session reset on logout prevents access by browser back/URL reuse.

## How to Run
1. Initialize DB:
   - `mysql -u root -p < init.sql`
2. Start server:
   - `php -S localhost:8000`
3. Test accounts:
   - `admin / admin123`
   - `manager / manager123`

## Conclusion
Lab 5 enforces authentication and role-based permissions so protected resources cannot be accessed by URL guessing or missing session checks.
