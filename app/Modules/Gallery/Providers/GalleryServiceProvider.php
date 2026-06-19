<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Providers;

use App\Modules\Gallery\Contracts\GalleryServiceInterface;
use App\Modules\Gallery\Services\GalleryService;
use App\Modules\Gallery\Models\GalleryAlbum;
use App\Modules\Gallery\Policies\GalleryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GalleryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            GalleryServiceInterface::class,
            GalleryService::class
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'gallery');

        // Register Policies
        Gate::policy(GalleryAlbum::class, GalleryPolicy::class);
    }
}
