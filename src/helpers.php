<?php

if (!function_exists('dd')) {
    function dd(...$args): void
    {
        highlight_string("<?php\n" . var_export($args, true) . ";\n?>");
        exit();
    }
}

if (!function_exists('exception')) {
    function exception(int $code, string $message, int $status = 500, string $logMessage = ''): void
    {
        http_response_code($status);
        if (!empty($logMessage)) {
            error_log($logMessage);
        }
        dd($code, $message);
    }
}


if (!function_exists('config')) {

    function config(string $key, mixed $default = null): mixed
    {
        return (new \Pano\Core\Config())->config($key, $default);
    }
}

