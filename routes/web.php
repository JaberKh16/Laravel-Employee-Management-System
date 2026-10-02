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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/generate-report', [HomeController::class, 'generateReport'])->name('generate.report');
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('profile/index', [UserController::class, 'profile'])->name('users.profile');
        Route::put('profile/update', [UserController::class, 'profileUpdate'])->name('users.profile.update');
        Route::resource('countries', CountryController::class);
        Route::resource('cities', CityController::class);
        Route::resource('states', StateController::class);
        Route::resource('branches', BranchController::class);
        Route::patch('branches/{branch}/status', [BranchController::class, 'updateStatus'])->name('branches.update-status');
        Route::resource('departments', DepartmentController::class);
        Route::patch('departments/{department}/status', [DepartmentController::class, 'updateStatus'])->name('departments.update-status');
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);

    });
    Route::get('{any}', function () {
        return view('admin.pages.Employee.index');
    })->where('any', '.*');
});
