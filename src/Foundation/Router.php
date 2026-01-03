<?php

namespace Pano\Foundation;

use Pano\Core\BaseRouter;
use Pano\Enum\HttpMethod;

final class Router extends BaseRouter
{

    public function get(string $path, callable $handler): void
    {
        $this->register(HttpMethod::GET, $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->register(HttpMethod::POST, $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->register(HttpMethod::PUT, $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->register(HttpMethod::DELETE, $path, $handler);
    }

    protected function notFound(): mixed
    {
        throw new Exception('Route not found', 404, 404);
    }

}