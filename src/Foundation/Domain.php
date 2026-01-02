<?php

namespace Pano\Foundation;

use Pano\Core\BaseDomain;
use Pano\Core\BaseRouter;

final readonly class Domain extends BaseDomain
{
    public function routes(): BaseRouter
    {
        $router = new Router($this->request);
        $router->get('/', fn() => $this->info());

        return $router;
    }

    public function info(): void
    {
        echo 'Pano a php nano framework';
    }

}