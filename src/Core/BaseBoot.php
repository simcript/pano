<?php

namespace Pano\Core;

abstract class BaseBoot
{
    abstract public function run(): void;

    protected BaseRequest $request;

    protected function debug(bool $status): void
    {
        error_reporting(E_ERROR | E_PARSE);
        ini_set('display_errors', $status ? '1' : '0');
    }

    protected function envLoader(): void
    {
        $envFilePath = BASE_PATH . DIRECTORY_SEPARATOR . '.env';
        if (file_exists($envFilePath)) {
            $env = file_get_contents($envFilePath);
            $lines = explode(PHP_EOL, $env);

            foreach ($lines as $line) {
                preg_match("/([^#]+)=(.*)/", $line, $matches);
                if (isset($matches[2])) {
                    putenv(trim($line));
                }
            }
        }
    }
}
