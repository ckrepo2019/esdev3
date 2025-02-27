<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'college' => [

            \App\Http\Middleware\AuthenticateDean::class,


        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'isAdministrator' => \App\Http\Middleware\AuthenticateAdmin::class,
        'isAdminAdmin' => \App\Http\Middleware\AuthenticateAdminAdmin::class,
        'isRegistrar' => \App\Http\Middleware\AuthenticateRegistrar::class,
        'isAdmission' => \App\Http\Middleware\AuthenticateAdmission::class,
        'isPrincipal' => \App\Http\Middleware\AuthenticatePrincipal::class,
        'isParent' => \App\Http\Middleware\AuthenticateParent::class,
        'isStudent' => \App\Http\Middleware\AuthenticateStudent::class,
        'isTeacher' => \App\Http\Middleware\AuthenticateTeacher::class,
        'isFinance' => \App\Http\Middleware\AuthenticateFinance::class,
        'isFinanceAdmin' => \App\Http\Middleware\AuthenticateFinance::class,
        'isHumanResource' => \App\Http\Middleware\AuthenticateHumanResource::class,
        'isDefaultPass' => \App\Http\Middleware\AuthenticateDefaultPass::class,
        'withSchoolInfo' => \App\Http\Middleware\AuthenticateSchoolInfo::class,
        'isDean' => \App\Http\Middleware\AuthenticateDean::class,
        'isSuperAdmin' => \App\Http\Middleware\AuthenticateSuperAdmin::class,
        'isCT' => \App\Http\Middleware\AuthenticateCT::class,
        'isCP' => \App\Http\Middleware\AuthenticateCP::class,
        'checkModule' => \App\Http\Middleware\CheckModule::class,
        'isAccounting' => \App\Http\Middleware\AuthenticateAccounting::class,
        'cors' => \App\Http\Middleware\Cor::class,
        'studsurvey' => \App\Http\Middleware\StudentSurvey::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,

    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
