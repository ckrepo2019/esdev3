<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapDocumentTrackingRoutes();

        $this->mapGuidanceRoutes();

        $this->mapAdmissionRoutes();

        $this->mapLibraryRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
    protected function mapDocumentTrackingRoutes()
    {
        Route::prefix('doctrack')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/documenttracking.php'));
    }

    protected function mapGuidanceRoutes()
    {
        Route::prefix('guidance')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/guidance.php'));

    }

    protected function mapAdmissionRoutes()
    {
        Route::prefix('admission')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admission.php'));

    }
    protected function mapLibraryRoutes()
    {
        Route::prefix('library')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/library.php'));

    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
