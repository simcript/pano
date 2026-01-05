<?php

namespace Pano\Core;

abstract class BaseView
{
    protected string $basePath;
    protected array $data = [];
    protected ?string $layout = null;
    protected array $sections = [];
    protected ?string $currentSection = null;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function with(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function layout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    public function render(string $view): void
    {
        extract($this->data, EXTR_SKIP);

        ob_start();
        require $this->resolve($view);
        $content = ob_get_clean();

        if ($this->layout) {
            $this->sections['content'] = $content;
            require $this->resolve($this->layout);
            return;
        }

        echo $content;
    }

    protected function resolve(string $view): string
    {
        return $this->basePath . '/' . ltrim($view, '/') . '.php';
    }

    public function start(string $name): void
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function end(): void
    {
        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = null;
    }

    public function section(string $name): void
    {
        echo $this->sections[$name] ?? '';
    }

    public function include(string $view, array $data = []): void
    {
        extract(array_merge($this->data, $data));
        require $this->resolve($view);
    }

    public function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
