# Lab 3 Report: XSS Injection Prevention

## Goal
Create a mini guest-book application and prevent XSS attacks.

## Implemented
- Reused Lab 2 authentication pages.
- Added DB table `guest(id, user, text_message, e_mail, data_time_message)`.
- Added `guestbook.php`:
  - form for user/email/message input,
  - insert message into DB,
  - render all guest-book entries.
- Added client-side checks in `guest_validation.js`.
- Added server-side checks in `guestbook.php`:
  - user regex validation,
  - email validation,
  - message length limits,
  - `strip_tags()` before DB insert,
  - `htmlspecialchars()` on output.

## XSS Demonstration
The classic payloads tested during analysis stage:
- `<script>alert(1)</script>`
- `<img src=x onerror="document.body.innerHTML='Hacked'">`

Expected secure behavior now:
- payload is blocked by validation or rendered as plain text,
- browser does not execute attacker JavaScript.

## Security Verification
- Guest-book output is escaped in all table cells.
- HTML/JS payloads are neutralized.
- App no longer allows replacing page body or forced redirect via injected script.

## How to Run
1. Initialize DB:
   - `mysql -u root -p < init.sql`
2. Start server:
   - `php -S localhost:8000`
3. Open:
   - `http://localhost:8000/guestbook.php`

## Conclusion
Lab 3 demonstrates XSS risks in data-entry forms and applies layered controls to stop script injection.
