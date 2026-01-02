<?php

namespace Pano\Core;

abstract readonly class BaseDomain
{
    public function __construct(
        protected BaseRequest $request
    ) {}

    abstract protected function handle(): \Closure;
}
