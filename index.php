<?php

main(domains: [
    '' => \Domains\Main::class,
]);

function main(array $domains): void
{
    define("PANO_STARTED", microtime(true));
    define("ROOT_PATH", rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);

    try {
        spl_autoload_register(function ($class) {
            try {
                if (requireClass($class, ROOT_PATH)) {
                    return true;
                }
                error_log("Class $class not found");
                return false;
            } catch (\Throwable $th) {
                error_log('Error in load class ' . $th->getMessage());
                return false;
            }
        });

        (new Boot($domains))->run();
    } catch (\Throwable $th) {
        error_log('Unhandled Error ' . $th->getMessage() . $th->getTraceAsString());
        dieError(500, 'Unhandled Error', 500, 'Unhandled Error');
    }

}

function requireClass(string $class, string $basePath): bool
{
    $file = $basePath . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $file = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $file);
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
}

function envLoader(string $envDirectory): void
{
    $envFilePath = $envDirectory . DIRECTORY_SEPARATOR . '.env';
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

function debug(bool $status): void
{
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', $status ? '1' : '0');
}

function dd(...$args): void
{
    highlight_string("<?php\n" . var_export($args, true) . ";\n?>");
    exit();
}

function dieError(int $code, string $message, int $status = 500, string $logMessage = ''): void
{
    http_response_code($status);
    if (!empty($logMessage)) {
        error_log($logMessage);
    }
    dd($code, $message);
}

final readonly class Boot
{
    public function __construct(private array $domains)
    {
    }

    public function run(): void
    {
        $request = new Request();
        $domainName = $this->domains[$request->getDomain()] ?? '';
        try {
            if (!class_exists($domainName)) {
                throw new RuntimeException("Domain class ($domainName) not found");
            }
            $reflection = new ReflectionClass($domainName);
            if (!$reflection->isSubclassOf(Domain::class)) {
                throw new RuntimeException("Domain ($domainName) must extend " . Domain::class);
            }
            $domain = $reflection->newInstance($request);
            $domain->handle()();
        } catch (Throwable $e) {
            dieError(404, $e->getMessage(), 500, $e->getMessage());
        }
    }

}

final readonly class Request
{
    private string|array $data;
    private array $files;
    private array $headers;
    private array $queries;
    private string $method;
    private string $query;
    private string $url;
    private array $segments;
    private string $host;

    public function __construct()
    {
        $this->parser($_SERVER);
    }

    public function parser(array $requestData): void
    {
        $this->fetchMethod($requestData)
            ->fetchQuery($requestData)
            ->fetchHost($requestData)
            ->fetchSegments($requestData)
            ->fetchUrl()
            ->fetchData()
            ->fetchFiles()
            ->fetchHeaders();
    }

    public function getData(): array|string
    {
        return $this->data;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getQueries(): array
    {
        return $this->queries;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSegments(): array
    {
        return $this->segments;
    }

    public function getSegment(int $index): string
    {
        return $this->segments[$index] ?? '';
    }

    public function getDomain(): string
    {
        return $this->segments[0] ?? '';
    }

    public function getHost(): string
    {
        return $this->host;
    }

    private function fetchData(): Request
    {
        $data = file_get_contents('php://input');
        if (empty($data)) {
            $data = $_REQUEST;
        }
        $this->data = $data;
        return $this;
    }

    private function fetchFiles(): Request
    {
        $this->files = $_FILES;
        return $this;
    }

    private function fetchHeaders(): Request
    {
        $this->headers = getallheaders();
        return $this;
    }

    private function fetchMethod(array $info): Request
    {
        $this->method = $info['REQUEST_METHOD'] ?? 'GET';
        return $this;
    }

    private function fetchHost(array $info): Request
    {
        $host = ($info['REQUEST_SCHEME'] ?? 'http') . '://' . ($info['HTTP_HOST'] ?? '');
        $this->host = trim($host, '/');
        return $this;
    }

    private function fetchSegments(array $info): Request
    {
        $uri = trim(($info['REQUEST_URI'] ?? ''), '/');
        $this->segments = explode('/', $uri);
        return $this;
    }

    private function fetchUrl(): Request
    {
        $uriSections = $this->segments;
        unset($uriSections[0]);
        $path = implode('/', $uriSections);
        $path = str_replace($this->query, '', $path);
        $this->url = trim($path, '?');
        return $this;
    }

    private function fetchQuery(array $info): Request
    {
        $this->query = $info['QUERY_STRING'] ?? '';
        $queries = explode('&', $this->query);
        $result = [];
        foreach ($queries as $query) {
            $tmpQuery = explode('=', $query);
            $field = $tmpQuery[0];
            if (empty($field)) {
                continue;
            }
            unset($tmpQuery[0]);
            $value = implode('=', $tmpQuery);
            $result[$field] = $value;
        }
        $this->queries = $result;
        return $this;
    }


}

abstract class Domain
{
    public function __construct(
        protected readonly Request $request
    ) {}

    abstract protected function handle(): \Closure;
}
