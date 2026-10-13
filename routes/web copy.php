<?php

use App\Http\Controllers\Admin\SocialServiceController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Public / Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| Guest-only routes (login + social auth)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])
        ->name('login');

    // Social auth
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])
        ->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->name('social.callback');
});

/*
|--------------------------------------------------------------------------
| Dashboard (public in your current setup)
|--------------------------------------------------------------------------
*/
Route::get('/home', [DashboardController::class, 'index'])->name('home');
Route::get('/generate-report', [DashboardController::class, 'generateReport'])->name('generate.report');

/*
|--------------------------------------------------------------------------
| Authenticated + Admin area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->group(function () {

        // ── Users ─────────────────────────────────────────────
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::post('users/{user}/make-employee', [UserController::class, 'makeEmployee'])->name('users.make-employee');
        Route::get('users/{user}/profile', [UserController::class, 'profileShow'])->name('users.profile.show');

        // ─── Profile Modal (dedicated endpoints) ───────────────
        Route::get('users/{user}/profile/modal', [ProfileController::class, 'showProfile'])->name('users.profile.modal.show');
        Route::put('users/{user}/profile/modal', [ProfileController::class, 'updateProfile'])->name('users.profile.modal.update');

        Route::resource('users', UserController::class);

        // ── Profile ───────────────────────────────────────────
        Route::get('profile/index', [UserController::class, 'profile'])->name('users.profile');
        Route::put('profile/update', [UserController::class, 'profileUpdate'])->name('users.profile.update');

        // ── Geo ───────────────────────────────────────────────
        Route::get('countries/export', [CountryController::class, 'export'])->name('countries.export');
        Route::resource('countries', CountryController::class);

        Route::get('cities/export', [CityController::class, 'export'])->name('cities.export');
        Route::resource('cities', CityController::class);

        Route::get('states/export', [StateController::class, 'export'])->name('states.export');
        Route::resource('states', StateController::class);

        // ── Branches ──────────────────────────────────────────
        Route::patch('branches/{branch}/status', [BranchController::class, 'updateStatus'])
            ->name('branches.update-status');
        Route::resource('branches', BranchController::class);

        // ── Departments ───────────────────────────────────────
        Route::patch('departments/{department}/status', [DepartmentController::class, 'updateStatus'])
            ->name('departments.update-status');
        Route::get('departments/export', [DepartmentController::class, 'export'])->name('departments.export');
        Route::resource('departments', DepartmentController::class);

        // ── Access control ────────────────────────────────────
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);

        // ── Social Services (for social login) ────────────────
        /*
         * IMPORTANT: `reorder` must be declared BEFORE the resource-style
         * parameterized routes so {social_service} doesn't swallow "reorder".
         */
        Route::post('social-services/reorder', [SocialServiceController::class, 'reorder'])
            ->name('social-services.reorder');

        Route::get('social-services', [SocialServiceController::class, 'index'])
            ->name('social.services.index');
        Route::post('social-services', [SocialServiceController::class, 'store'])
            ->name('social.services.store');
        Route::put('social-services/{social_service}', [SocialServiceController::class, 'update'])
            ->name('social.services.update');
        Route::patch('social-services/{social_service}', [SocialServiceController::class, 'update'])
            ->name('social-services.patch');
        Route::delete('social-services/{social_service}', [SocialServiceController::class, 'destroy'])
            ->name('social.services.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | SPA fallback — inside auth, so unauthenticated users are redirected
    |--------------------------------------------------------------------------
    */
    Route::get('/{any}', function () {
        return view('admin.pages.Employee.index');
    })->where('any', '^(?!js/|css/|images/|fonts/|storage/|build/).*$');
});

/*
|--------------------------------------------------------------------------
| Catch-all — MUST BE LAST and OUTSIDE auth middleware
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('users.index');
    }
    return redirect()->route('login');
});