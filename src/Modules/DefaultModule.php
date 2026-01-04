<?php


namespace Pano\Modules;

use Pano\Core\BaseModule;
use Pano\Core\BaseRouter;
use Pano\Foundation\Router;

final readonly class DefaultModule extends BaseModule
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