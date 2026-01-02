<?php

namespace Pano\Domains;

use Pano\Core\BaseDomain;

readonly class Main extends BaseDomain
{

    public function handle(): \Closure
    {
        return fn() => $this->info();
    }

    public function info(): void
    {
        echo 'Pano a php nano framework';
    }

}