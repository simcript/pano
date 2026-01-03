<?php

namespace Pano\Core;

abstract readonly class BaseModule
{
    public function __construct(
        protected BaseRequest $request
    ) {}

    abstract protected function routes(): BaseRouter;
}
