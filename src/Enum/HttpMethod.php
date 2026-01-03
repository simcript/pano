<?php

namespace Pano\Enum;

enum HttpMethod: string
{

    // RFC 7231 / RFC 9110 (Core)
    case GET     = 'GET';
    case POST    = 'POST';
    case PUT     = 'PUT';
    case DELETE  = 'DELETE';
    case PATCH   = 'PATCH';
    case HEAD    = 'HEAD';
    case OPTIONS = 'OPTIONS';
    case TRACE   = 'TRACE';
    case CONNECT = 'CONNECT';

    // WebDAV (RFC 4918)
    case PROPFIND  = 'PROPFIND';
    case PROPPATCH = 'PROPPATCH';
    case MKCOL     = 'MKCOL';
    case COPY      = 'COPY';
    case MOVE      = 'MOVE';
    case LOCK      = 'LOCK';
    case UNLOCK    = 'UNLOCK';

    // WebDAV Search (RFC 5323)
    case SEARCH = 'SEARCH';

    // HTTP Extensions (de-facto standards)
    case PURGE = 'PURGE';     // Varnish / CDNs
    case LINK  = 'LINK';      // Rare but valid
    case UNLINK = 'UNLINK';

    /**
     * Create enum from raw string (case-insensitive)
     */
    public static function fromString(string $method): self
    {
        return self::from(strtoupper($method));
    }

    /**
     * Check if HTTP method can have a request body
     */
    public function allowsBody(): bool
    {
        return match ($this) {
            self::POST,
            self::PUT,
            self::PATCH,
            self::DELETE => true,
            default => false,
        };
    }

    /**
     * Safe methods (RFC 7231)
     * These methods should not modify server state
     */
    public function isSafe(): bool
    {
        return match ($this) {
            self::GET,
            self::HEAD,
            self::OPTIONS,
            self::TRACE => true,
            default => false,
        };
    }

    /**
     * Idempotent methods
     */
    public function isIdempotent(): bool
    {
        return match ($this) {
            self::GET,
            self::HEAD,
            self::PUT,
            self::DELETE,
            self::OPTIONS,
            self::TRACE => true,
            default => false,
        };
    }

    /**
     * Check if method is commonly used for write operations
     */
    public function isWrite(): bool
    {
        return match ($this) {
            self::POST,
            self::PUT,
            self::PATCH,
            self::DELETE => true,
            default => false,
        };
    }

    /**
     * Get all HTTP methods as array of strings
     */
    public static function values(): array
    {
        return array_map(
            static fn(self $method) => $method->value,
            self::cases()
        );
    }
}
