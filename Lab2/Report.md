# Lab 2 Report: SQL Injection Prevention

## Goal
Implement protection against SQL injection in the authentication mini-app.

## Preconditions Covered
- Login form created (`index.php`) with login and password fields.
- Restricted admin page exists (`admin.php`) as target.
- Database has `user(id, login, password)`.

## Implemented
1. Added 7 records into `user` table in `init.sql`.
2. Demonstrated SQLi vulnerability in Lab 1 with payload examples:
   - login: `admin' -- `
   - password: any text
3. Added client-side validation (`validation.js`):
   - blocks symbols outside allowed sets.
4. Added server-side validation (`login.php`):
   - regex validation for login/password.
   - blocked requests with invalid symbols.
5. Replaced dynamic SQL with prepared statement:
   - `SELECT ... WHERE login = :login AND password = :password`.

## Security Verification
- SQL payloads with `'`, comment markers, or operators are rejected by validation.
- Prepared statement prevents SQL code from being interpreted as executable query logic.

## How to Run
1. Initialize DB:
   - `mysql -u root -p < init.sql`
2. Start server:
   - `php -S localhost:8000`
3. Open:
   - `http://localhost:8000/index.php`

## Conclusion
SQL injection on login form is mitigated by combined client/server validation and parameterized queries.

