# instruction.md

## 1. Быстрый запуск любой лабораторной

Важно: в этом проекте лучше использовать PHP и MySQL из XAMPP, чтобы не ловить ошибку `could not find driver`.

1. Запусти `C:\xampp\xampp-control.exe`.
2. Нажми `Start` у `MySQL`.
3. В отдельном `cmd`:

```bat
cd "C:\Users\vipia\OneDrive\Рабочий стол\SAWM\LabN"
"C:\xampp\mysql\bin\mysql.exe" -u root < init.sql
"C:\xampp\php\php.exe" -S localhost:800N
```

Где `N` — номер лабораторной.

Порты:
- `Lab1 -> 8001`
- `Lab2 -> 8002`
- `Lab3 -> 8003`
- `Lab4 -> 8004`
- `Lab5 -> 8005`
- `Lab6 -> 8006`
- `Lab7 -> 8007`

Открывать в браузере: `http://localhost:800N/index.php`.

Тестовые аккаунты:
- админ: `admin / admin123`
- менеджер: `manager / manager123`

---

## 2. Lab1 — базовые уязвимости (контрольная точка)

URL: `http://localhost:8001/index.php`

Проверка 1: прямой доступ к админке
1. Открой `http://localhost:8001/admin.php` без логина.
2. Ожидаемо: страница открылась (это уязвимость, и это нормально для Lab1).

Проверка 2: SQL-injection на логине
1. На форме логина заполни:
   - `Login`: `admin' -- `
   - `Password`: `anything`
2. Нажми `Sign In`.
3. Ожидаемо: вход выполнится (уязвимость подтверждена).

Проверка 3: обычный вход
1. Введи `admin / admin123`.
2. Ожидаемо: вход выполняется.

---

## 3. Lab2 — защита от SQL-injection

URL: `http://localhost:8002/index.php`

Проверка 1: корректный вход работает
1. Введи `admin / admin123`.
2. Ожидаемо: успешный вход.

Проверка 2: SQL payload блокируется на клиенте
1. Введи:
   - `Login`: `admin' -- `
   - `Password`: `anything`
2. Нажми `Sign In`.
3. Ожидаемо: alert про невалидный ввод, форма не отправляется.

Проверка 3: SQL payload блокируется на сервере
1. Отключи JavaScript в браузере (или отправь форму из DevTools вручную).
2. Повтори payload из проверки 2.
3. Ожидаемо: возврат на `index.php` с ошибкой `Input contains blocked symbols.`

Проверка 4: прямой URL админки пока еще уязвим
1. Открой `http://localhost:8002/admin.php`.
2. Ожидаемо: страница откроется напрямую (RBAC еще не внедрен до Lab5).

---

## 4. Lab3 — защита от XSS в гостевой книге

URL: `http://localhost:8003/guestbook.php`

Поля формы:
- `User`
- `E-mail`
- `Message`

Проверка 1: нормальное сообщение
1. Введи:
   - `User`: `alex`
   - `E-mail`: `alex@test.com`
   - `Message`: `Привет, это тест.`
2. Нажми `Save Message`.
3. Ожидаемо: `Message saved successfully.` и запись появляется в таблице.

Проверка 2: XSS через `<script>`
1. Введи:
   - `User`: `alex`
   - `E-mail`: `alex@test.com`
   - `Message`: `<script>alert(1)</script>`
2. Нажми `Save Message`.
3. Ожидаемо с JS: блокировка на клиенте (`Message is invalid or contains HTML/JS.`).

Проверка 3: серверная защита (даже если обойти JS)
1. Отключи JavaScript и повтори payload из проверки 2.
2. Ожидаемо: код не исполняется в браузере, popup не появляется.
3. В таблице вывод безопасный: HTML/JS не выполняются.

Проверка 4: XSS через `img onerror`
1. `Message`: `<img src=x onerror="alert(1)">`
2. Ожидаемо: никакого выполнения JavaScript.

---

## 5. Lab4 — защита паролей (хеши + соль)

URL: `http://localhost:8004/index.php`

Проверка 1: вход по реальному паролю
1. Введи `admin / admin123`.
2. Ожидаемо: вход успешный.

Проверка 2: неверный пароль
1. Введи `admin / wrong123`.
2. Ожидаемо: `Invalid login or password.`

Проверка 3: в базе и на экране хеши, не plain text
1. После входа открой/останься на `admin.php`.
2. В таблице `Password Hash` проверь:
   - значения длинные,
   - начинаются с `$argon2id$`,
   - нет открытых значений вроде `admin123`.

---

## 6. Lab5 — обязательная аутентификация и RBAC

URL: `http://localhost:8005/index.php`

Проверка 1: без логина доступ запрещен
1. Открой напрямую:
   - `http://localhost:8005/admin.php`
   - `http://localhost:8005/manager.php`
2. Ожидаемо: редирект на логин с `Authentication is required.`

Проверка 2: роль administrator
1. Войди как `admin / admin123`.
2. Ожидаемо: редирект в `admin.php`.
3. Попробуй открыть `http://localhost:8005/manager.php`.
4. Ожидаемо: `Access denied for this role.`

Проверка 3: роль manager
1. Выйди (`logout`), войди как `manager / manager123`.
2. Ожидаемо: редирект в `manager.php`.
3. Открой `accounts.php`.
4. Ожидаемо: список пользователей доступен.

Проверка 4: защита админ-аккаунта от изменений
1. В `accounts.php` у строки администратора смотри колонку `Actions`.
2. Ожидаемо: `Protected`.
3. Вручную открой `http://localhost:8005/delete_user.php?id=1`.
4. Ожидаемо: удаление не пройдет (`Delete failed or target is protected.`).

Проверка 5: logout сбрасывает сессию
1. Нажми `Logout`.
2. Снова открой `admin.php` или `manager.php`.
3. Ожидаемо: снова требуется логин.

---

## 7. Lab6 — журналирование действий (action logs)

URL: `http://localhost:8006/index.php`

Файл лога: `Lab6/logs/actions.log`
Страница просмотра: `http://localhost:8006/view_logs.php` (только админ)

Проверка 1: логируются неуспешные входы
1. Введи `admin / wrong123`.
2. Ожидаемо: ошибка входа.
3. После этого как админ открой `view_logs.php` и найди `"action":"login_failure"`.

Проверка 2: логируется отклонение валидацией
1. Введи:
   - `Login`: `admin' -- `
   - `Password`: `anything`
2. Ожидаемо: блокировка ввода.
3. В логах должна быть запись `"action":"login_rejected_by_validation"`.

Проверка 3: логируется доступ в панели/функции
1. Войди как `manager / manager123`, открой `accounts.php`.
2. Ожидаемо в логе: `manager_panel_open`, `accounts_view`.

Проверка 4: логи доступны только администратору
1. В сессии manager открой `http://localhost:8006/view_logs.php`.
2. Ожидаемо: `Permission denied.`
3. В логе должна появиться запись `access_denied_permission`.

Проверка 5: logout логируется
1. Нажми `Logout`.
2. В логе должна быть запись `"action":"logout"`.

---

## 8. Lab7 — безопасная обработка ошибок + error log

URL: `http://localhost:8007/index.php`

Файлы:
- лог ошибок: `Lab7/logs/errors.log`
- safe-страница: `http://localhost:8007/safe.html`
- просмотр ошибок: `http://localhost:8007/view_errors.php` (только админ)

Проверка 1: обычный пользователь не видит внутренние детали
1. Войди как `manager / manager123`.
2. Открой `http://localhost:8007/view_errors.php`.
3. Ожидаемо: отказ доступа (`Permission denied.`).

Проверка 2: принудительно вызвать внутреннюю ошибку
1. Оставь PHP-сервер Lab7 запущенным.
2. В `XAMPP Control Panel` останови `MySQL`.
3. Открой `http://localhost:8007/guestbook.php`.
4. Ожидаемо: редирект на `safe.html` с текстом `Temporary Safe Page...`, без stack trace и SQL-деталей.

Проверка 3: ошибка записана в лог
1. Снова запусти `MySQL`.
2. Войди как `admin / admin123`.
3. Открой `http://localhost:8007/view_errors.php`.
4. Ожидаемо: в таблице есть JSON-запись с `db_connection_error` (или другой тип ошибки обработчика).

Проверка 4: safe page статическая и с ссылкой возврата
1. Открой `http://localhost:8007/safe.html` напрямую.
2. Ожидаемо: есть ссылка `Back to application` на `index.php`.

---

## 9. Мини-чеклист для защиты перед сдачей

1. Lab2: SQLi payload не дает войти.
2. Lab3: XSS payload не исполняется.
3. Lab4: в таблице пользователей только хеши Argon2id.
4. Lab5: без сессии закрытые страницы не открываются.
5. Lab6: действия пишутся в `actions.log`, просмотр только у админа.
6. Lab7: при внутренней ошибке показывается `safe.html`, детали в `errors.log`, просмотр ошибок только у админа.
