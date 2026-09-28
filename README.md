# Feedback Form (PHP + AJAX)

Простое тестовое задание: форма обратной связи с валидацией, AJAX-отправкой и Яндекс SmartCaptcha.

## Что сделано

- Форма с обязательными полями (тема, ФИО, телефон, email, сообщение, согласие)
- Клиентская валидация + маска телефона (JS)
- Отправка через AJAX
- Серверная валидация и проверка капчи
- Отправка письма через `mail()`
- Логирование запросов и ошибок (для отладки)

## Структура
├── index.php      # форма
├── style.css      # стили
├── main.js        # валидация + AJAX
├── ajax.php       # обработчик
└── php_errors.log # лог ошибок (создаётся автоматически)

## Требования

- PHP 7.4+
- Модули: `mbstring`, `curl`
- Настроенный `mail()` (Postfix / sendmail)

## Установка

1. Склонируй репозиторий в веб-директорию
2. В `ajax.php` и `index.php` вставь свои ключи Яндекс SmartCaptcha:

```php
// ajax.php
$serverKey = 'YOUR_SERVER_KEY_HERE';
HTML<!-- index.php -->
data-sitekey="YOUR_CLIENT_KEY_HERE"
```

Открой index.php в браузере

## Ключи для теста (если нужно)

```php
YOUR_CLIENT_KEY_HERE = ysc1_LYLT154MEZ8z4verXDGW1CRETd28vKGBeV61V7jf12a670f3
YOUR_SERVER_KEY_HERE = ysc2_LYLT154MEZ8z4verXDGWgK0o0Jy30XX0v82qdxX0a9c66a63
```

## Примечание
Код тестировался на Kali Linux.

Если письмо не уходит — проверь настройки почтового сервера и логи (php_errors.log, /var/log/mail.log).






