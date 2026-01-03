<?php

namespace Pano\Core;

use Pano\Enum\HttpStatus;

abstract class BaseResponse
{
    abstract public function send(): void;

    protected HttpStatus $status = HttpStatus::OK;
    protected array $headers = [];
    protected mixed $body = null;

    public function setStatus(HttpStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setHeaders(array $headers): self
    {
        foreach ($headers as $k => $v) {
            $this->setHeader($k, $v);
        }
        return $this;
    }

    public function setBody(mixed $body): self
    {
        $this->body = $body;
        return $this;
    }

}
