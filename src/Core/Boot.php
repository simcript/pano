<?php

namespace Pano\Core;

final readonly class Boot
{
    public function __construct()
    {
    }

    public function run(): void
    {
        $this->debug(getenv('APP_DEBUG'));
        $this->envLoader();
        $request = new Request();
        $domainName = config('domains.' . $request->getDomain(), '');
        try {
            if (!class_exists($domainName)) {
                throw new \RuntimeException("Domain class ($domainName) not found");
            }
            $reflection = new \ReflectionClass($domainName);
            if (!$reflection->isSubclassOf(Domain::class)) {
                throw new \RuntimeException("Domain ($domainName) must extend " . Domain::class);
            }
            $domain = $reflection->newInstance($request);
            $domain->handle()();
        } catch (\Throwable $e) {
            exception(404, $e->getMessage(), 500, $e->getMessage());
        }
    }

    private function debug(bool $status): void
    {
        error_reporting(E_ERROR | E_PARSE);
        ini_set('display_errors', $status ? '1' : '0');
    }

    private function envLoader(): void
    {
        $envFilePath = BASE_PATH . DIRECTORY_SEPARATOR . '.env';
        if (!file_exists($envFilePath)) {
            dieError(500, '.env file not found. Create a environment file(.env) similar to .env.example file.');
        }
        $env = file_get_contents($envFilePath);
        $lines = explode(PHP_EOL, $env);

        foreach ($lines as $line) {
            preg_match("/([^#]+)=(.*)/", $line, $matches);
            if (isset($matches[2])) {
                putenv(trim($line));
            }
        }
    }
}
