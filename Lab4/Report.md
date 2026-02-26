# Lab 4 Report: Confidential Data Protection

## Goal
Protect credentials by replacing plain-text password storage with secure salted hashes.

## Implemented Changes
- Reused app from previous labs (auth + guestbook).
- Updated authentication logic in `login.php`:
  - query user by login,
  - verify password with `password_verify()`.
- Updated `init.sql`:
  - passwords are stored as Argon2id hashes, not plain text.
- Updated `admin.php`:
  - displays contents of table `user` to show hash format in DB.

## Analysis: Why Plain Text Passwords Are Vulnerable
If DB is leaked, attackers immediately get real passwords and can:
- sign in directly,
- reuse passwords on other services,
- automate account takeover.

## Recommended Password Policy
- Length: 12+ characters.
- Mix: upper/lower letters, digits, symbols.
- Avoid dictionary words and personal data.
- Use unique password per service.

Examples:
- weak: `123456`, `qwerty`, `password`, `admin123`
- strong: `Y3!nM7#qL2@vP9sK`, `Tea_Cabin+River_2041!`

## Why MD5 and SHA1 Are Not Safe for Passwords
- They are too fast, enabling large-scale brute force.
- Collision resistance is broken/weak for practical security usage.
- They are not memory-hard, so GPU/ASIC cracking is efficient.

Attacks against MD5-hashed passwords:
- dictionary and brute-force cracking,
- rainbow table lookup,
- credential stuffing if recovered plain password is reused.

## Why Argon2id Was Chosen
- Modern password hashing function.
- Memory-hard design makes GPU attacks expensive.
- Built-in random salt generation in `password_hash()`.
- Supported in modern PHP versions.

## Role of Salt
Salt is a random value added before hashing.
Benefits:
- same password does not produce same hash for different users,
- rainbow table attacks become impractical,
- per-user cracking cost increases significantly.

## How to Run
1. Initialize DB:
   - `mysql -u root -p < init.sql`
2. Start server:
   - `php -S localhost:8000`
3. Open:
   - `http://localhost:8000/index.php`
4. Login example:
   - `admin / admin123`

## Conclusion
Lab 4 hardens credential storage and verification using modern salted password hashing (Argon2id).
