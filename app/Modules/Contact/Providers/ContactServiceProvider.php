<?php

declare(strict_types=1);

namespace App\Modules\Contact\Providers;

use App\Modules\Contact\Contracts\ContactServiceInterface;
use App\Modules\Contact\Services\ContactService;
use App\Modules\Contact\Models\ContactMessage;
use App\Modules\Contact\Policies\ContactPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ContactServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            ContactServiceInterface::class,
            ContactService::class
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'contact');

        // Register Policies
        Gate::policy(ContactMessage::class, ContactPolicy::class);
    }
}
