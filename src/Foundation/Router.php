<?php

namespace Pano\Foundation;

use Pano\Core\BaseRouter;
use Pano\HttpMethodEnum;

final class Router extends BaseRouter
{

    public function get(string $path, callable $handler): void
    {
        $this->register(HttpMethodEnum::GET, $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->register(HttpMethodEnum::POST, $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->register(HttpMethodEnum::PUT, $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->register(HttpMethodEnum::DELETE, $path, $handler);
    }

    protected function notFound(): mixed
    {
        throw new Exception('Route not found', 404, 404);
    }

}