<?php
return [
    'name' => getenv('APP_NAME') ?? 'Pano',
    'env' => getenv('APP_ENV') ?? 'local',
    'key' => getenv('APP_KEY') ?? null,
    'debug' => getenv('APP_DEBUG') ?? false,
    'url' => getenv('APP_URL') ?? null,
    'resolver' => getenv('DOMAIN_RESOLVER') ?? 'path',
];