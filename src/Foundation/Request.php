<?php

namespace Pano\Foundation;

use Pano\Core\BaseRequest;

final class Request extends BaseRequest
{
    public function __construct()
    {
        $this->fetchMethod($_SERVER)
            ->fetchQuery($_SERVER)
            ->fetchHost($_SERVER)
            ->fetchSegments($_SERVER)
            ->fetchUrl()
            ->fetchData()
            ->fetchFiles()
            ->fetchHeaders();
    }

    public function getModule(): string
    {
        if (config('app.resolver', 'path')) {
            return $this->segments[0] ?? '';
        } else {
            $host = parse_url($this->url, PHP_URL_HOST);
            if (($host === false) || ($host === null)) {
                return '';
            }
            $parts = explode('.', $host);
            if (count($parts) < 3) {
                return '';
            }
            $subdomainParts = array_slice($parts, 0, -2);
            return implode('.', $subdomainParts);
        }
    }

    public function expectsJson(): bool
    {
        $accept = $this->headers['Accept'] ?? '';
        return str_contains($accept, '*/json');
    }

    private function fetchData(): self
    {
        $data = file_get_contents('php://input');
        if (empty($data)) {
            $data = $_REQUEST;
        }
        $this->data = $data;
        return $this;
    }

    private function fetchFiles(): self
    {
        $this->files = $_FILES;
        return $this;
    }

    private function fetchHeaders(): self
    {
        $this->headers = getallheaders();
        return $this;
    }

    private function fetchMethod(array $info): self
    {
        $this->method = $info['REQUEST_METHOD'] ?? 'GET';
        return $this;
    }

    private function fetchHost(array $info): self
    {
        $host = ($info['REQUEST_SCHEME'] ?? 'http') . '://' . ($info['HTTP_HOST'] ?? '');
        $this->host = trim($host, '/');
        return $this;
    }

    private function fetchSegments(array $info): self
    {
        $uri = trim(($info['REQUEST_URI'] ?? ''), '/');
        $this->segments = explode('/', $uri);
        return $this;
    }

    private function fetchUrl(): self
    {
        $uriSections = $this->segments;
        unset($uriSections[0]);
        $path = implode('/', $uriSections);
        $path = str_replace($this->query, '', $path);
        $this->url = trim($path, '?');
        return $this;
    }

    private function fetchQuery(array $info): self
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