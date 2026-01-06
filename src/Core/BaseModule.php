<?php

namespace Pano\Core;

abstract readonly class BaseModule
{
    abstract protected function routes(): BaseRouter;

    abstract protected function view(): BaseView;

    abstract protected function log(): BaseLogger;

    public function __construct(
        protected BaseRequest $request
    )
    {
    }

    protected function viewBasePath(): string
    {
        return $this->moduleBasePath() . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR;
    }

    protected function logsBasePath(): string
    {
        return $this->moduleBasePath() . DIRECTORY_SEPARATOR . 'Logs' . DIRECTORY_SEPARATOR;
    }

    public function moduleBasePath(): string
    {
        $reflector = new \ReflectionClass(static::class);

        return dirname($reflector->getFileName());
    }
}
