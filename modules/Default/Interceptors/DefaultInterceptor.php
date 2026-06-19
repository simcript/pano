<?php

namespace Modules\Default\Interceptors;

use Pano\Kernel\BaseInterceptor;
use Pano\Kernel\BaseResponse;
use Pano\Foundation\Exception;
use Pano\Foundation\Response;

class DefaultInterceptor extends BaseInterceptor
{
    public function onRequest(): void
    {
    }

    public function onResponse(BaseResponse $response): BaseResponse
    {
        return parent::onResponse($response);
    }

}