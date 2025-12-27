<?php

namespace Domains;

class Main extends \Domain
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