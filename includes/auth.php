<?php

require_once __DIR__ . '/../functions/helpers.php';

function ensure_session_started(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function is_logged_in(): bool
{
    ensure_session_started();
    return isset($_SESSION['userid']);
}

function is_admin(): bool
{
    ensure_session_started();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_login(string $redirect = '/coffee/pages/login.php'): void
{
    if (is_logged_in()) {
        return;
    }

    header('Location: ' . $redirect);
    exit;
}

function require_admin(): void
{
    if (is_admin()) {
        return;
    }

    http_response_code(403);
    exit('관리자만 접근 가능합니다.');
}

function set_flash(string $key, $value): void
{
    ensure_session_started();
    $_SESSION['_flash'][$key] = $value;
}

function pull_flash(string $key, $default = null)
{
    ensure_session_started();
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);

    return $value;
}

function require_post(string $redirect): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        return;
    }

    header('Location: ' . $redirect);
    exit;
}

function csrf_token(): string
{
    ensure_session_started();

    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf_or_fail(): void
{
    ensure_session_started();
    $submitted = (string)($_POST['csrf_token'] ?? '');
    $stored = (string)($_SESSION['_csrf_token'] ?? '');

    if ($stored !== '' && hash_equals($stored, $submitted)) {
        return;
    }

    http_response_code(419);
    exit('요청이 만료되었거나 올바르지 않습니다. 다시 시도해주세요.');
}
