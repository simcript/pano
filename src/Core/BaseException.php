<?php

namespace Pano\Core;

abstract class BaseException extends \Exception
{

    abstract public function toArray(bool $debug = false): array;

    abstract public function toHtml(bool $debug = false): string;

    protected int $status;
    protected mixed $payload;
    protected bool $report;

    public function __construct(
        string      $message,
        int         $code = 0,
        int         $status = 500,
        mixed       $payload = null,
        ?\Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);

        $this->status = $status;
        $this->payload = $payload;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function payload(): mixed
    {
        return $this->payload;
    }

    public function shouldReport(): bool
    {
        $this->report = true;
        return $this->report;
    }

}
