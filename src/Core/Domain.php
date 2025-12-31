<?php

namespace Pano\Core;

abstract class Domain
{
    public function __construct(
        protected readonly Request $request
    ) {}

    abstract protected function handle(): \Closure;
}
