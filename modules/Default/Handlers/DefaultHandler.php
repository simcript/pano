<?php


namespace Modules\Default\Handlers;

use Composer\InstalledVersions;
use Pano\Kernel\BaseHandler;
use Pano\Foundation\Response;

final class DefaultHandler extends BaseHandler
{

    public function info(): Response
    {
        $version = InstalledVersions::getPrettyVersion('simcript/pano') ?? 'dev';

        return Response::html(
            $this->module->view()
            ->with(['name' => env('APP_NAME', 'Pano'), 'version' => $version])
            ->layout('layout')
            ->render('home')
        );
    }
}