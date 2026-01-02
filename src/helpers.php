<?php

if (!function_exists('dd')) {
    function dd(...$args): void
    {
        highlight_string("<?php\n" . var_export($args, true) . ";\n?>");
        exit();
    }
}

if (!function_exists('config')) {

    function config(string $key, mixed $default = null): mixed
    {
        return (new \Pano\Core\Config())->config($key, $default);
    }
}

