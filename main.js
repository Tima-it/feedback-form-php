document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('feedbackForm');
    const result = document.getElementById('form-result');
    const phoneInput = document.getElementById('phone');

    // Маска телефона
    phoneInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value[0] === '8') value = '7' + value.slice(1);
            if (value[0] !== '7') value = '7' + value;
        }
        let formatted = '+7';
        if (value.length > 1) formatted += ' (' + value.slice(1, 4);
        if (value.length >= 5) formatted += ') ' + value.slice(4, 7);
        if (value.length >= 8) formatted += '-' + value.slice(7, 9);
        if (value.length >= 10) formatted += '-' + value.slice(9, 11);
        this.value = formatted;
    });


    // Форма с ajax
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();
        result.style.display = 'none';

        if (!validate()) return;

        const formData = new FormData(form);
        const token = window.smartCaptcha ? window.smartCaptcha.getResponse() : '';
        formData.append('smart-token', token);

        const btn = form.querySelector('button');
        btn.disabled = true;

        try {
            const res = await fetch('ajax.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                result.className = 'success';
                result.textContent = data.message || 'Сообщение успешно отправлено';
                form.reset();
                if (window.smartCaptcha) window.smartCaptcha.reset();
            } else {
                result.className = 'error';
                result.textContent = data.message || 'Ошибка отправки';
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        showError(key, data.errors[key]);
                    });
                }
            }
        } catch (err) {
            result.className = 'error';
            result.textContent = 'Ошибка сети. Попробуйте позже.';
        }

        btn.disabled = false;
        result.style.display = 'block';
    });

    function validate() {
        let valid = true;

        const theme = form.theme.value.trim();
        if (!theme) {
            showError('theme', 'Выберите тему');
            valid = false;
        }

        const name = form.name.value.trim();
        if (!name) {
            showError('name', 'Введите ФИО');
            valid = false;
        } else if (name.length > 255) {
            showError('name', 'Максимум 255 символов');
            valid = false;
        }

        const phone = form.phone.value.replace(/\D/g, '');
        if (!phone) {
            showError('phone', 'Введите телефон');
            valid = false;
        } else if (phone.length !== 11 || phone[0] !== '7') {
            showError('phone', 'Неверный формат телефона');
            valid = false;
        }

        const email = form.email.value.trim();
        const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            showError('email', 'Введите e-mail');
            valid = false;
        } else if (!emailRe.test(email) || email.length > 255) {
            showError('email', 'Неверный e-mail');
            valid = false;
        }

        const message = form.message.value.trim();
        if (!message) {
            showError('message', 'Введите сообщение');
            valid = false;
        } else if (message.length > 4096) {
            showError('message', 'Максимум 4096 символов');
            valid = false;
        }

        if (!form.agree.checked) {
            showError('agree', 'Необходимо согласие');
            valid = false;
        }

        const token = window.smartCaptcha ? window.smartCaptcha.getResponse() : '';
        if (!token) {
            document.getElementById('captcha-error').textContent = 'Пройдите капчу';
            valid = false;
        }

        return valid;
    }

    function showError(field, text) {
        const el = form.querySelector(`[name="${field}"]`);
        if (el) {
            const errorSpan = el.closest('.field').querySelector('.error');
            if (errorSpan) errorSpan.textContent = text;
        }
    }

    function clearErrors() {
        form.querySelectorAll('.error').forEach(el => el.textContent = '');
        document.getElementById('captcha-error').textContent = '';
    }
});
