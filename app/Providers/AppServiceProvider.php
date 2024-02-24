<?php

namespace App\Providers;

use Cloudinary\Cloudinary;
use Illuminate\Support\ServiceProvider;
use App\Core\Services\Interfaces\IUtilityService;
use App\Core\Services\Interfaces\ICloudinaryService;
use App\Core\Services\Implementations\UtilityService;
use App\Core\Services\Interfaces\IFlutterWaveService;
use App\Core\Services\Implementations\CloudinaryService;
use App\Core\Services\Implementations\FlutterwaveService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->scoped(ICloudinaryService::class, CloudinaryService::class);
        $this->app->scoped(IFlutterWaveService::class, FlutterwaveService::class);
        $this->app->scoped(IUtilityService::class,UtilityService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
