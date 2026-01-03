<?php

namespace Pano\Foundation;

use Pano\Core\BaseResponse;
use Pano\Core\BaseException;
use Pano\Enum\HttpStatus;

final class Response extends BaseResponse
{
    private bool $sent = false;

    public static function make(
        mixed $body = null,
        HttpStatus $status = HttpStatus::OK,
        array $headers = []
    ): self {
        return (new self())
            ->setStatus($status)
            ->setHeaders($headers)
            ->setBody($body);
    }

    public static function json(
        array|object $data,
        HttpStatus $status = HttpStatus::OK,
        array $headers = []
    ): self {
        return (new self())
            ->setStatus($status)
            ->setHeader('Content-Type', 'application/json; charset=utf-8')
            ->setHeaders($headers)
            ->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public static function text(
        string $text,
        HttpStatus $status = HttpStatus::OK,
        array $headers = []
    ): self {
        return (new self())
            ->setStatus($status)
            ->setHeader('Content-Type', 'text/plain; charset=utf-8')
            ->setHeaders($headers)
            ->setBody($text);
    }

    public static function html(
        string $html,
        HttpStatus $status = HttpStatus::OK,
        array $headers = []
    ): self {
        return (new self())
            ->setStatus($status)
            ->setHeader('Content-Type', 'text/html; charset=utf-8')
            ->setHeaders($headers)
            ->setBody($html);
    }

    public static function stream(
        callable $callback,
        string $contentType = 'application/octet-stream',
        HttpStatus $status = HttpStatus::OK,
        array $headers = []
    ): self {
        return (new self())
            ->setStatus($status)
            ->setHeader('Content-Type', $contentType)
            ->setHeaders($headers)
            ->setBody($callback);
    }

    public static function exception(
        \Throwable $e,
        Request $request
    ): self {
        $debug = config('app.debug', false);

        if ($e instanceof BaseException) {

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
            HttpStatus::INTERNAL_SERVER_ERROR
        );
    }

    public function send(): void
    {
        if ($this->sent) {
            return;
        }

        http_response_code($this->status->value);

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

}
