<?php
define("PANO_STARTED", microtime(true));
define("BASE_PATH", rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);

require __DIR__ . '/vendor/autoload.php';

(new \Pano\Foundation\Boot())->run();
