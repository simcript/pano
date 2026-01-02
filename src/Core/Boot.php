<?php

namespace Pano\Core;

final readonly class Boot
{
    private readonly Request $request;

    public function __construct()
    {
        $this->debug(getenv('APP_DEBUG'));
        $this->request = new Request();
        try {
            $this->envLoader();
        } catch (Exception $e) {
            Response::exception($e, $this->request)->send();
        }
    }

    public function run(): void
    {
        $domainName = config('domains.' . $this->request->getDomain(), '');
        try {
            if (!class_exists($domainName)) {
                throw new \RuntimeException("Domain class ($domainName) not found");
            }
            $reflection = new \ReflectionClass($domainName);
            if (!$reflection->isSubclassOf(Domain::class)) {
                throw new \RuntimeException("Domain ($domainName) must extend " . Domain::class);
            }
            $domain = $reflection->newInstance($this->request);
            $domain->handle()();
        }
        catch (\Throwable $e) {
            Response::exception($e, $this->request)->send();
        }
    }

    private function debug(bool $status): void
    {
        error_reporting(E_ERROR | E_PARSE);
        ini_set('display_errors', $status ? '1' : '0');
    }

    /**
     * @throws Exception
     */
    private function envLoader(): void
    {
        $envFilePath = BASE_PATH . DIRECTORY_SEPARATOR . '.env';
        if (!file_exists($envFilePath)) {
            throw new Exception('.env file not found. Create a environment file(.env) similar to .env.example file.', 500, 500);
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
