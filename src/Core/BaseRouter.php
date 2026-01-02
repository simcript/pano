<?php

namespace Pano\Core;

use Pano\HttpMethodEnum;

abstract class BaseRouter
{

    abstract public function get(string $path, callable $handler): void;

    abstract public function post(string $path, callable $handler): void;

    abstract public function put(string $path, callable $handler): void;

    abstract public function delete(string $path, callable $handler): void;

    abstract protected function notFound(): mixed;

    protected BaseRequest $request;

    private array $routes = [];

    public function __construct(BaseRequest $request)
    {
        $this->request = $request;
    }

    protected function register(HttpMethodEnum $method, string $path, callable $handler): void
    {
        $path = trim($path, '/') . '/';

        [$pattern, $params] = $this->compile($path);

        $this->routes[$method->value][] = [
            'pattern' => $pattern,
            'params'  => $params,
            'handler' => $handler,
        ];
    }


    public function dispatch(): mixed
    {
        $method = $this->request->getMethod();
        $uri    = $this->normalizeUri($this->request->getUrl());

        if (!isset($this->routes[$method])) {
            return $this->notFound();
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {

                $args = [];
                foreach ($route['params'] as $name) {
                    $args[] = $matches[$name];
                }

                return ($route['handler'])(...$args);
            }
        }

        return $this->notFound();
    }

    protected function compile(string $path): array
    {
        $params = [];

        $path = $path === '/' ? '/' : rtrim($path, '/');

        $pattern = preg_replace_callback(
            '/\[([a-zA-Z_][a-zA-Z0-9_]*)\]/',
            function ($m) use (&$params) {
                $params[] = $m[1];
                return '(?P<' . $m[1] . '>[^/]+)';
            },
            $path
        );

        return [
            '#^' . $pattern . '$#',
            $params
        ];
    }

    protected function normalizeUri(string $uri): string
    {
        return trim(parse_url($uri, PHP_URL_PATH) ?? '/', '/') ?: '/';
    }
}
