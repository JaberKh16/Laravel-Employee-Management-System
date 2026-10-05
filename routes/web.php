<?php

use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\HomeController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/generate-report', [HomeController::class, 'generateReport'])->name('generate.report');
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        // ── Users ─────────────────────────────────────────────
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::post('users/{user}/make-employee', [UserController::class, 'makeEmployee'])->name('users.make-employee');
        Route::get('users/{user}/profile', [UserController::class, 'profileShow'])->name('users.profile.show');

         // ─── Profile Modal (dedicated endpoints) ───────────────
        Route::get('users/{user}/profile/modal',  [ProfileController::class, 'showProfile'])->name('users.profile.modal.show');
        Route::put('users/{user}/profile/modal',  [ProfileController::class, 'updateProfile'])->name('users.profile.modal.update');

        Route::resource('users', UserController::class);

        // ── Profile ───────────────────────────────────────────
        Route::get('profile/index', [UserController::class, 'profile'])->name('users.profile');
        Route::put('profile/update', [UserController::class, 'profileUpdate'])->name('users.profile.update');

        // ── Geo ───────────────────────────────────────────────
        Route::resource('countries', CountryController::class);
        Route::resource('cities', CityController::class);
        Route::resource('states', StateController::class);

     

        // ── Branches ──────────────────────────────────────────
        Route::patch('branches/{branch}/status', [BranchController::class, 'updateStatus'])
            ->name('branches.update-status');
        Route::resource('branches', BranchController::class);

        // ── Departments ───────────────────────────────────────
        Route::patch('departments/{department}/status', [DepartmentController::class, 'updateStatus'])
            ->name('departments.update-status');
        Route::resource('departments', DepartmentController::class);

        // ── Access control ────────────────────────────────────
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);
    });
    Route::get('{any}', function () {
        return view('admin.pages.Employee.index');
    })->where('any', '.*');
});


/*
|--------------------------------------------------------------------------
| Catch-all — MUST BE LAST and OUTSIDE auth middleware
|--------------------------------------------------------------------------
*/
// Route::fallback(function () {
//     abort(404);
// });

// ── at the bottom of routes/web.php ──
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('users.index');
    }
    return redirect()->route('login');
});
