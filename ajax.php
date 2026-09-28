<?php
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__ . '/php_errors.log');
error_reporting(E_ALL);

if (empty($_POST)) {
    http_response_code(403);
    die();
}

header('Content-Type: application/json; charset=utf-8');

$serverKey = 'YOUR_SERVER_KEY_HERE';

function respond($success, $message = '', $errors = [])
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'errors' => $errors
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$data = [
    'action' => trim($_POST['action'] ?? ''),
    'theme' => trim($_POST['theme'] ?? ''),
    'name' => trim($_POST['name'] ?? ''),
    'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'message' => trim($_POST['message'] ?? ''),
    'agree' => isset($_POST['agree']),
    'token' => $_POST['smart-token'] ?? ''
];

$errors = [];

if ($data['action'] !== 'message') {
    $errors['action'] = 'Неверное действие';
}

if ($data['theme'] === '') {
    $errors['theme'] = 'Выберите тему';
}

if ($data['name'] === '') {
    $errors['name'] = 'Введите ФИО';
} elseif (mb_strlen($data['name']) > 255) {
    $errors['name'] = 'Максимум 255 символов';
}

if ($data['phone'] === '') {
    $errors['phone'] = 'Введите телефон';
} elseif (strlen($data['phone']) !== 11 || $data['phone'][0] !== '7') {
    $errors['phone'] = 'Неверный формат телефона';
}

if ($data['email'] === '') {
    $errors['email'] = 'Введите e-mail';
} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 255) {
    $errors['email'] = 'Неверный e-mail';
}

if ($data['message'] === '') {
    $errors['message'] = 'Введите сообщение';
} elseif (mb_strlen($data['message']) > 4096) {
    $errors['message'] = 'Максимум 4096 символов';
}

if (!$data['agree']) {
    $errors['agree'] = 'Необходимо согласие';
}

if ($data['token'] === '') {
    $errors['captcha'] = 'Пройдите капчу';
} else {
    $ch = curl_init('https://smartcaptcha.yandexcloud.net/validate');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'secret' => $serverKey,
            'token' => $data['token'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ]),
        CURLOPT_TIMEOUT => 5
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    if (empty($result['status']) || $result['status'] !== 'ok') {
        $errors['captcha'] = 'Капча не пройдена';
    }
}

if ($errors) {
    respond(false, 'Исправьте ошибки в форме', $errors);
}

$to = $data['email']; // по ТЗ отправка на e-mail из формы
$subject = 'Обратная связь: ' . $data['theme'];
$body = "Тема: {$data['theme']}\n"
    . "ФИО: {$data['name']}\n"
    . "Телефон: +{$data['phone']}\n"
    . "E-mail: {$data['email']}\n\n"
    . "Сообщение:\n{$data['message']}";

$headers = "From: noreply@" . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n"
    . "Content-Type: text/plain; charset=utf-8\r\n";


file_put_contents('log.txt', date('[Y-m-d H:i:s] ') . print_r([$data, $body], true) . "\n", FILE_APPEND);

if (mail($to, $subject, $body, $headers)) {
    respond(true, 'Сообщение успешно отправлено');
} else {
    respond(false, 'Ошибка при отправке письма');
}
