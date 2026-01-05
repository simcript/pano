<?php

namespace Pano\Core;

use Pano\Foundation\View;

abstract readonly class BaseModule
{
    abstract protected function routes(): BaseRouter;

    public function __construct(
        protected BaseRequest $request
    ) {}

    protected function view(): BaseView
    {
        return new View($this->viewBasePath());
    }

    protected function viewBasePath(): string
    {
        return $this->moduleBasePath() . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR;
    }

    public function moduleBasePath():string
    {
        $reflector = new \ReflectionClass(static::class);

        return dirname($reflector->getFileName());
    }
}
