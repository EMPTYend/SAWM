const guestbookForm = document.getElementById('guestbook-form');

if (guestbookForm) {
    guestbookForm.addEventListener('submit', (event) => {
        const user = document.getElementById('user').value.trim();
        const email = document.getElementById('e_mail').value.trim();
        const message = document.getElementById('text_message').value.trim();

        const userPattern = /^[A-Za-z0-9_ ]{2,50}$/;
        const hasHtmlTags = /<[^>]*>/g.test(message);

        if (!userPattern.test(user)) {
            event.preventDefault();
            alert('User contains invalid characters.');
            return;
        }

        if (!email.includes('@') || email.length > 120) {
            event.preventDefault();
            alert('Email looks invalid.');
            return;
        }

        if (message.length < 1 || message.length > 500 || hasHtmlTags) {
            event.preventDefault();
            alert('Message is invalid or contains HTML/JS.');
        }
    });
}

