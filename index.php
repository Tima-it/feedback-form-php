<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обратная связь</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Форма обратной связи</h1>
    <form id="feedbackForm" novalidate>
        <input type="hidden" name="action" value="message">

        <div class="field">
            <label for="theme">Тема</label>
            <select name="theme" id="theme" required>
                <option value="">Выберите тему</option>
                <option value="Тема 1">Тема 1</option>
                <option value="Тема 2">Тема 2</option>
                <option value="Тема 3">Тема 3</option>
                <option value="Тема 4">Тема 4</option>
                <option value="Тема 5">Тема 5</option>
                <option value="Тема 6">Тема 6</option>
                <option value="Тема 7">Тема 7</option>
            </select>
            <span class="error"></span>
        </div>

        <div class="field">
            <label for="name">ФИО</label>
            <input type="text" name="name" id="name" maxlength="255" required>
            <span class="error"></span>
        </div>

        <div class="field">
            <label for="phone">Телефон</label>
            <input type="tel" name="phone" id="phone" maxlength="255" required>
            <span class="error"></span>
        </div>

        <div class="field">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" maxlength="255" required>
            <span class="error"></span>
        </div>

        <div class="field">
            <label for="message">Сообщение</label>
            <textarea name="message" id="message" maxlength="4096" required></textarea>
            <span class="error"></span>
        </div>

        <div class="field">
            <div id="captcha-container"
                 class="smart-captcha"
                 data-sitekey="ysc1_LYLT154MEZ8z4verXDGW1CRETd28vKGBeV61V7jf12a670f3">
            </div>
            <span class="error" id="captcha-error"></span>
        </div>

        <div class="field checkbox">
            <label>
                <input type="checkbox" name="agree" id="agree" required>
                Согласие на обработку персональных данных
            </label>
            <span class="error"></span>
        </div>

        <button type="submit">Отправить</button>
        <div id="form-result"></div>
    </form>
</div>

<script src="main.js"></script>
</body>
</html>
