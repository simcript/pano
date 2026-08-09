<?php

use Modules\Default\DefaultModule;

return [
    'pano' => env('APP_ENV','production') === 'local' ? DefaultModule::class : null,
    '' => DefaultModule::class,
];