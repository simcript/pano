<?php

namespace Pano\Core;

abstract class BaseResponse
{
    abstract public function send(): void;

    protected int $status = 200;
    protected array $headers = [];
    protected mixed $body = null;

    public function setStatus(int $status): self
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
            $this->header($k, $v);
        }
        return $this;
    }

    public function setBody(mixed $body): self
    {
        $this->body = $body;
        return $this;
    }

}
