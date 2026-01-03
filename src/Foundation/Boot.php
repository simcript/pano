<?php

namespace Pano\Foundation;

use Pano\Core\BaseBoot;
use Pano\Core\BaseModule;

final class Boot extends BaseBoot
{

    public function __construct()
    {
        $this->debug(config('app.debug', false));
        $this->envLoader();
        $this->request = new Request();
    }

    public function run(): void
    {
        $moduleName = config('modules.' . $this->request->getModule(), null);
        try {
            if ($moduleName === null) {
                throw new Exception("Module ({$this->request->getModule()}) is not defined");
            }
            if (!class_exists($moduleName)) {
                throw new Exception("Module class ($moduleName) not found");
            }
            $reflection = new \ReflectionClass($moduleName);
            if (!$reflection->isSubclassOf(BaseModule::class)) {
                throw new Exception("Module ($moduleName) must extend " . BaseModule::class);
            }
            $module = $reflection->newInstance($this->request);
            $module->routes()->dispatch();
        }
        catch (\Throwable $e) {
            Response::exception($e, $this->request)->send();
        }
    }

}
