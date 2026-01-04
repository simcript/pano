<?php


namespace Pano\Modules\Default;

use Pano\Core\BaseModule;
use Pano\Core\BaseRouter;
use Pano\Enum\HttpStatus;
use Pano\Foundation\Response;
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
        Response::text('Pano a php nano framework')->send();
    }

}