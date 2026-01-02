<?php

namespace Pano\Foundation;

use Pano\Core\BaseBoot;
use Pano\Core\BaseDomain;

final class Boot extends BaseBoot
{

    public function __construct()
    {
        $this->debug(getenv('APP_DEBUG'));
        $this->envLoader();
        $this->request = new Request();
    }

    public function run(): void
    {
        $domainName = config('domains.' . $this->request->getDomain(), '');
        try {
            if (!class_exists($domainName)) {
                throw new \RuntimeException("Domain class ($domainName) not found");
            }
            $reflection = new \ReflectionClass($domainName);
            if (!$reflection->isSubclassOf(BaseDomain::class)) {
                throw new \RuntimeException("Domain ($domainName) must extend " . BaseDomain::class);
            }
            $domain = $reflection->newInstance($this->request);
            $domain->handle()();
        }
        catch (\Throwable $e) {
            Response::exception($e, $this->request)->send();
        }
    }

}
