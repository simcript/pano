<?php


namespace Pano\Modules\Default;

use Pano\Core\BaseLogger;
use Pano\Core\BaseModule;
use Pano\Core\BaseRouter;
use Pano\Core\BaseView;
use Pano\Foundation\Logger;
use Pano\Foundation\Router;
use Pano\Foundation\View;

final readonly class DefaultModule extends BaseModule
{
    protected function view(): BaseView
    {
        return new View($this->viewBasePath());
    }

    protected function log(): BaseLogger
    {
        return new Logger($this->logsBasePath());
    }

    public function routes(): BaseRouter
    {
        $router = new Router($this->request);
        $router->get('/', fn() => $this->info());

        return $router;
    }

    public function info(): void
    {
        $this->log()->info('sss', ['asdf']);
        $this->view()
            ->with(['name' => 'Pano'])
            ->layout('layout')
            ->render('home');
    }
}