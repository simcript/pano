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
        $domainName = config('domains.' . $this->request->getDomain(), null);
        try {
            if ($domainName === null) {
                throw new Exception("Domain ({$this->request->getDomain()}) is not defined");
            }
            if (!class_exists($domainName)) {
                throw new Exception("Domain class ($domainName) not found");
            }
            $reflection = new \ReflectionClass($domainName);
            if (!$reflection->isSubclassOf(BaseDomain::class)) {
                throw new Exception("Domain ($domainName) must extend " . BaseDomain::class);
            }
            $domain = $reflection->newInstance($this->request);
            $domain->routes()->dispatch();
        }
        catch (\Throwable $e) {
            Response::exception($e, $this->request)->send();
        }
    }

}
