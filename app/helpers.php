<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function app_base_path(): string
{
    $scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    return $scriptDirectory === '/' || $scriptDirectory === '.' ? '' : rtrim($scriptDirectory, '/');
}

function url(string $action = 'listar', array $parameters = []): string
{
    $query = http_build_query(['accion' => $action] + $parameters);
    return app_base_path() . '/index.php?' . $query;
}

function asset(string $path): string
{
    return app_base_path() . '/' . ltrim($path, '/');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_is_valid(): bool
{
    $sent = $_POST['_token'] ?? '';
    return is_string($sent)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $sent);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }

    $saved = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return is_string($saved) ? $saved : null;
}

function redirect(string $action = 'listar', array $parameters = []): never
{
    header('Location: ' . url($action, $parameters), true, 303);
    exit;
}

function text_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

function initial_letter(string $value): string
{
    $first = function_exists('mb_substr')
        ? mb_substr($value, 0, 1, 'UTF-8')
        : (preg_split('//u', $value, -1, PREG_SPLIT_NO_EMPTY)[0] ?? substr($value, 0, 1));
    return function_exists('mb_strtoupper') ? mb_strtoupper($first, 'UTF-8') : strtoupper($first);
}

function money(string|int|float $amount): string
{
    return '$ ' . number_format((float) $amount, 2, ',', '.');
}
