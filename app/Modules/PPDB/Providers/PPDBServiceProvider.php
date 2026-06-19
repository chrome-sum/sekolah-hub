<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Providers;

use App\Modules\PPDB\Contracts\PPDBServiceInterface;
use App\Modules\PPDB\Services\PPDBService;
use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\Registration;
use App\Modules\PPDB\Policies\AcademicYearPolicy;
use App\Modules\PPDB\Policies\AdmissionTrackPolicy;
use App\Modules\PPDB\Policies\RegistrationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PPDBServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            PPDBServiceInterface::class,
            PPDBService::class
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'ppdb');

        // Register Policies
        Gate::policy(AcademicYear::class, AcademicYearPolicy::class);
        Gate::policy(AdmissionTrack::class, AdmissionTrackPolicy::class);
        Gate::policy(Registration::class, RegistrationPolicy::class);
    }
}
