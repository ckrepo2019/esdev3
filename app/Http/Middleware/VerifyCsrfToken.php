<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/mobile/api_update_pushstatus',
        '/api/mobile/api_update_sms',
        '/api/mobile/api_save_fcmtoken',
        '/api/mobile/deleteFcmToken',
        '/api/mobile/api_send_payment',
        '/api/mobile/api_savescholarship',
        '/api/mobile/api_uploadrequirement',
        '/api/mobile/api_delscholarship',
      	'/api/mobile/employees/add/attendance',
    ];
}
