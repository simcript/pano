<?php


namespace Pano\Modules\Default;

use Pano\Foundation\Router;
use Pano\Kernel\BaseLogger;
use Pano\Kernel\BaseModule;
use Pano\Kernel\BaseRouter;
use Pano\Kernel\BaseView;
use Pano\Foundation\Exception;
use Pano\Foundation\Logger;
use Pano\Foundation\View;
use Pano\Modules\Default\Commands\DefaultCommand;
use Pano\Modules\Default\Handlers\DefaultHandler;
use Pano\Modules\Default\Interceptors\DefaultInterceptor;

final readonly class DefaultModule extends BaseModule
{
    /**
     * @throws Exception
     */
    public function routes(): BaseRouter
    {
        $router = new Router($this->request, $this);
        $router->get('/', DefaultHandler::class, 'info', [DefaultInterceptor::class]);
        $router->command('app:info', DefaultCommand::class);

        return $router;
    }

    public function view(): BaseView
    {
        return new View($this->viewPath());
    }

    public function log(): BaseLogger
    {
        return new Logger($this->logPath());
    }

}