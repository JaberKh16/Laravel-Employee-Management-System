<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\RegisteredSocialService;
use App\Models\State;
use App\Models\User;
use App\Observers\CityObserver;
use App\Observers\CountryObserver;
use App\Observers\DepartmentObserver;
use App\Observers\EmployeeObserver;
use App\Observers\StateObserver;
use App\Observers\UserObserver;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('public.disk', function () {
            return Storage::disk('public');
        });

        $this->app->bind(Filesystem::class, function ($app) {
            return $app['public.disk'];
        });
    }


    public function boot()
    {
        // all observers
        City::observe(CityObserver::class);
        Country::observe(CountryObserver::class);
        State::observe(StateObserver::class);
        Department::observe(DepartmentObserver::class);
        Employee::observe(EmployeeObserver::class);
        User::observe(UserObserver::class);
        Paginator::useBootstrap();
        RegisteredSocialService::bootstrapIntoConfig();



        //  Register @selected manually in AppServiceProvider::boot()
        Blade::directive('selected', function ($expression) {
            return "<?php echo ($expression) ? 'selected' : ''; ?>";
        });

        // daily cleanup of expired notifications
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->call(function () {
        //         Notification::whereNotNull('expires_at')
        //             ->where('expires_at', '<', now())
        //             ->delete();
        //     })->daily();
        // });

        // // dialy prune of soft-deleted records (optional)
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->call(function () {
        //         City::onlyTrashed()->where('deleted_at', '<', now()->subDays(30))->forceDelete();
        //         Country::onlyTrashed()->where('deleted_at', '<', now()->subDays(30))->forceDelete();
        //         State::onlyTrashed()->where('deleted_at', '<', now()->subDays(30))->forceDelete();
        //         Department::onlyTrashed()->where('deleted_at', '<', now()->subDays(30))->forceDelete();
        //         Employee::onlyTrashed()->where('deleted_at', '<', now()->subDays(30))->forceDelete();
        //         User::onlyTrashed()->where('deleted_at', '<', now()->subDays(30))->forceDelete();
        //     })->daily();
        // });
    }
}
