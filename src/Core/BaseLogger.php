<?php

namespace Pano\Core;

use Pano\Enum\LogLevel;

abstract class BaseLogger
{
    abstract public function emergency(string $message, array $context = []): void;

    abstract public function alert(string $message, array $context = []): void;

    abstract public function critical(string $message, array $context = []): void;

    abstract public function error(string $message, array $context = []): void;

    abstract public function warning(string $message, array $context = []): void;

    abstract public function notice(string $message, array $context = []): void;

    abstract public function info(string $message, array $context = []): void;

    abstract public function debug(string $message, array $context = []): void;

    public function __construct(
        private readonly string $filePath,
    )
    {
    }

    protected function log(LogLevel $level, string $message, array $context = []): void
    {
        $directory = dirname($this->filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        if (!file_exists($this->filePath)) {
            touch($this->filePath);
            chmod($this->filePath, 0666);
        }

        $date = date('H:i:s');

        $context = $context
            ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : '';

        $line = "[{$date}] {$level->value}: {$message} {$context}" . PHP_EOL;

        file_put_contents(
            $this->filePath,
            $line,
            FILE_APPEND | LOCK_EX
        );
    }


}
