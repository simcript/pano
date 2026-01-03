<?php

namespace Pano\Foundation;

use Pano\Core\BaseModule;
use Pano\Core\BaseRouter;

final readonly class Module extends BaseModule
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