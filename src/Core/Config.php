<?php

namespace Pano\Core;

final readonly class Config
{
    public function __construct()
    {
    }

    public function config(string $key, mixed $default = null): mixed
    {
        static $configs = [];

        if (empty($configs)) {
            $configPath = BASE_PATH . '/config';

            if (is_dir($configPath)) {
                foreach (glob($configPath . '/*.php') as $file) {
                    $name = basename($file, '.php');
                    $configs[$name] = require $file;
                }
            }
        }

        return $this->dataGet($configs, $key, $default);
    }

    private function dataGet(array $array, string $key, mixed $default = null): mixed
    {
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        return $array;
    }


}
