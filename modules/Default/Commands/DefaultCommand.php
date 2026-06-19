<?php

namespace Modules\Default\Commands;

use Composer\InstalledVersions;
use Pano\Kernel\BaseCommand;
use Pano\Kernel\ResultCodeEnum;

class DefaultCommand extends BaseCommand
{

    public function handle(array $arguments): ResultCodeEnum
    {
        $version = InstalledVersions::getPrettyVersion('simcript/pano') ?? 'dev';
        $this->info(env('APP_NAME', 'Pano') . " - " . $version);
        return ResultCodeEnum::OK;
    }
}