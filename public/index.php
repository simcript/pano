<?php
define("PANO_STARTED", microtime(true));
define("BASE_PATH", rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);

require BASE_PATH . '/vendor/autoload.php';


(new \Pano\Foundation\Boot())->run($_SERVER);
