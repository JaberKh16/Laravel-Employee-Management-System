<?php

use App\Http\Controllers\Admin\SocialServiceController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/* Public / auth */
Route::get('/', fn() => redirect()->route('login'));
Auth::routes();

Route::get('/home', [DashboardController::class, 'index'])->name('home');
Route::get('/generate-report', [DashboardController::class, 'generateReport'])->name('generate.report');

/*
|--------------------------------------------------------------------------
| Authenticated area — /admin/*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    /* ---------- Employee (all authenticated users) ---------- */
    Route::view('employees', 'admin.pages.employees.index')->name('employees.index');

    /* ---------- SYSTEM section — available to every authenticated user ---------- */
    // Geo
    Route::get('countries/export', [CountryController::class, 'export'])->name('countries.export');
    Route::resource('countries', CountryController::class);
    Route::get('cities/export', [CityController::class, 'export'])->name('cities.export');
    Route::resource('cities', CityController::class);
    Route::get('states/export', [StateController::class, 'export'])->name('states.export');
    Route::resource('states', StateController::class);

    // Branch
    Route::patch('branches/{branch}/status', [BranchController::class, 'updateStatus'])->name('branches.update-status');
    Route::resource('branches', BranchController::class);

    // Department
    Route::patch('departments/{department}/status', [DepartmentController::class, 'updateStatus'])->name('departments.update-status');
    Route::get('departments/export', [DepartmentController::class, 'export'])->name('departments.export');
    Route::resource('departments', DepartmentController::class);

    /* ---------- ADMINISTRATION section — admin/manager only ---------- */
    Route::middleware(['role:Admin|super-admin|manager'])->group(function () {

        // Users + profile
        // Import
        Route::get('users/import', [UserController::class, 'importForm'])->name('users.import');
        Route::post('users/import', [UserController::class, 'importStore'])->name('users.import.store');
        Route::get('users/import/template', [UserController::class, 'importTemplate'])->name('users.import.template');
        // Export
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');

        // Bulk actions — MUST come BEFORE resource
        Route::patch('users/bulk-status', [UserController::class, 'bulkStatus'])->name('users.bulk-status');
        Route::patch('users/bulk-role', [UserController::class, 'bulkRole'])->name('users.bulk-role');
        Route::patch('users/bulk-operation', [UserController::class, 'bulkOperation'])->name('users.bulk-operation');

        Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
        Route::post('users/{user}/make-employee', [UserController::class, 'makeEmployee'])->name('users.make-employee');

        // Profile modal (specific enough — has /modal suffix)
        Route::get('users/{user}/profile', [UserController::class, 'profileShow'])->name('users.profile.show');

        Route::put('users/{user}/profile/modal', [ProfileController::class, 'updateProfile'])->name('users.profile.modal.update');
        Route::resource('users', UserController::class);

        // Live validation — same controller, trait-supplied actions
        Route::prefix('api/validate')->name('validate.')->group(function () {
            Route::post('username', [UserController::class, 'checkUsername'])->name('username');
            Route::post('email', [UserController::class, 'checkEmail'])->name('email');
            Route::post('phone', [UserController::class, 'checkPhone'])->name('phone');
            Route::post('password', [UserController::class, 'checkPassword'])->name('password');
        });

        // Own profile
        Route::get('profile/index', [UserController::class, 'profile'])->name('users.profile');
        Route::put('profile/update', [UserController::class, 'profileUpdate'])->name('users.profile.update');

        // ACL
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);

        // Social Services
        Route::post('social-services/reorder', [SocialServiceController::class, 'reorder'])->name('social.services.reorder');
        Route::get('social-services', [SocialServiceController::class, 'index'])->name('social.services.index');
        Route::post('social-services', [SocialServiceController::class, 'store'])->name('social.services.store');
        Route::put('social-services/{social_service}', [SocialServiceController::class, 'update'])->name('social.services.update');
        Route::patch('social-services/{social_service}', [SocialServiceController::class, 'update'])->name('social.services.patch');
        Route::delete('social-services/{social_service}', [SocialServiceController::class, 'destroy'])->name('social.services.destroy');

        // Optional audit/settings (create when ready)
        // Route::get('audit', [...])->name('audit.index');
        // Route::get('settings', [...])->name('settings.index');



        /* =========================================================
       NOTIFICATIONS — available to every authenticated user
       ========================================================= */
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
            Route::patch('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::patch('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
            Route::patch('/{notification}/dismiss', [NotificationController::class, 'dismiss'])->name('dismiss');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        });
    });
});
/*
|--------------------------------------------------------------------------
| SPA fallback — catches deep links for the employee SPA
| Must stay LAST, inside auth so it never hijacks public assets
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/{any}', fn() => view('admin.pages.Employee.index'))
        ->where('any', '^(?!js/|css/|images/|fonts/|storage/|build/).*$');
});

/*
|--------------------------------------------------------------------------
| Global fallback — bounce guests to login, users to their dashboard
|--------------------------------------------------------------------------
*/
Route::fallback(fn() => auth()->check()
    ? redirect()->route('users.index')
    : redirect()->route('login'));