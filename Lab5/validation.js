const form = document.getElementById('login-form');

if (form) {
    form.addEventListener('submit', (event) => {
        const login = document.getElementById('login').value.trim();
        const password = document.getElementById('password').value.trim();

        const loginPattern = /^[A-Za-z0-9_]{3,50}$/;
        const passwordPattern = /^[A-Za-z0-9_!@#$%^&*.\-]{6,72}$/;

        if (!loginPattern.test(login) || !passwordPattern.test(password)) {
            event.preventDefault();
            alert('Invalid input. Only allowed characters are accepted.');
        }
    });
}

