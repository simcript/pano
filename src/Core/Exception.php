<?php

namespace Pano\Core;

class Exception extends \Exception
{
    protected int $status = 500;
    protected mixed $payload = null;
    protected bool $report = true;

    public function __construct(
        string $message = 'Server Error',
        int $code = 500,
        int $status = 500,
        mixed $payload = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->status  = $status;
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

    public function toArray(bool $debug = false): array
    {
        $response = [
            'message' => $this->getMessage(),
        ];

        if ($this->payload !== null) {
            $response['data'] = $this->payload;
        }

        if ($debug) {
            $response['exception'] = static::class;
            $response['trace'] = $this->getTrace();
        }

        return $response;
    }

    public function toHtml(bool $debug = false): string
    {
        if (!$debug) {
            return '<h1>Something went wrong</h1>';
        }

        return sprintf(
            '<h1>%s</h1><pre>%s</pre>',
            htmlspecialchars($this->getMessage(), ENT_QUOTES),
            htmlspecialchars($this->getTraceAsString(), ENT_QUOTES)
        );
    }
}
