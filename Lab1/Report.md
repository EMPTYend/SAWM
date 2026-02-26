# Lab 1 Report: Basic Vulnerable Authentication

## Goal
Build a baseline mini web app with:
- login form (login + password),
- database table `user`,
- restricted admin page (not yet truly protected).

## Implemented Files
- `config.php` - DB and app settings.
- `db.php` - PDO connection helper.
- `index.php` - login form.
- `login.php` - authentication handler (intentionally vulnerable SQL).
- `admin.php` - admin page (open by URL without auth check).
- `style.css` - basic UI style.
- `init.sql` - DB schema + 7 users.

## Security State
- SQL injection is possible in `login.php` because SQL is built via string concatenation.
- Direct access to `admin.php` is possible without authentication.
- Passwords are stored in plain text.

## Test Notes
1. Run SQL script:
   - `mysql -u root -p < init.sql`
2. Start PHP server:
   - `php -S localhost:8000`
3. Open:
   - `http://localhost:8000/index.php`
4. Check:
   - valid login works;
   - `admin.php` opens directly.

## Conclusion
Lab 1 creates an intentionally weak baseline that will be hardened in next labs.

