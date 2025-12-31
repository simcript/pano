<?php

namespace Pano\Core;

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