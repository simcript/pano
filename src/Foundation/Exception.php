<?php

namespace Pano\Foundation;

use Pano\Core\BaseException;

final class Exception extends BaseException
{

    public function toArray(bool $debug = false): array
    {
        $response = [
            'message' => $this->getMessage(),
        ];

        if ($this->payload !== null) {
            $response['data'] = $this->payload;
        }

        if ($debug) {
            $response['exception'] = static::class;
            $response['trace'] = $this->getTrace();
        }

        return $response;
    }

    public function toHtml(bool $debug = false): string
    {
        if (!$debug) {
            return sprintf(
                '<h1>Something went wrong</h1><pre>%s</pre>',
                htmlspecialchars($this->getMessage(), ENT_QUOTES)
            );
        }

        return sprintf(
            '<h1>%s</h1><pre>%s</pre>',
            htmlspecialchars($this->getMessage(), ENT_QUOTES),
            htmlspecialchars($this->getTraceAsString(), ENT_QUOTES)
        );
    }
}
