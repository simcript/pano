<?php

namespace Pano\Core;

class Response
{
    private int $status = 200;
    private array $headers = [];
    private mixed $body = null;

    private bool $sent = false;

    /* ------------------------------
     |  Factory Methods
     | ------------------------------ */

    public static function make(
        mixed $body = null,
        int $status = 200,
        array $headers = []
    ): self {
        return (new self())
            ->status($status)
            ->headers($headers)
            ->body($body);
    }

    public static function json(
        array|object $data,
        int $status = 200,
        array $headers = []
    ): self {
        return (new self())
            ->status($status)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->headers($headers)
            ->body(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public static function text(
        string $text,
        int $status = 200,
        array $headers = []
    ): self {
        return (new self())
            ->status($status)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->headers($headers)
            ->body($text);
    }

    public static function html(
        string $html,
        int $status = 200,
        array $headers = []
    ): self {
        return (new self())
            ->status($status)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->headers($headers)
            ->body($html);
    }

    public static function stream(
        callable $callback,
        string $contentType = 'application/octet-stream',
        int $status = 200,
        array $headers = []
    ): self {
        return (new self())
            ->status($status)
            ->header('Content-Type', $contentType)
            ->headers($headers)
            ->body($callback);
    }

    public static function exception(
        \Throwable $e,
        Request $request
    ): self {
        $debug = getenv('APP_DEBUG');

        if ($e instanceof Exception) {

            if ($request->expectsJson()) {
                return self::json(
                    $e->toArray($debug),
                    $e->status()
                );
            }

            return self::html(
                $e->toHtml($debug),
                $e->status()
            );
        }

        return self::text(
            $debug ? $e->getMessage() : 'Server Error',
            500
        );
    }

    /* ------------------------------
     |  Fluent Setters
     | ------------------------------ */

    public function status(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function headers(array $headers): self
    {
        foreach ($headers as $k => $v) {
            $this->header($k, $v);
        }
        return $this;
    }

    public function body(mixed $body): self
    {
        $this->body = $body;
        return $this;
    }

    /* ------------------------------
     |  Send Response
     | ------------------------------ */

    public function send(): void
    {
        if ($this->sent) {
            return;
        }

        http_response_code($this->status);

        foreach ($this->headers as $key => $value) {
            header("$key: $value", true);
        }

        if (is_callable($this->body)) {
            ($this->body)();
        } else {
            echo (string) $this->body;
        }

        $this->sent = true;
    }

    /* ------------------------------
     |  Helpers
     | ------------------------------ */

    public function isSent(): bool
    {
        return $this->sent;
    }

}
