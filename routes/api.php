<?php

use App\Http\Controllers\API\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\State;
use App\Models\City;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware(['auth'])->group(function () {

// });

// ─── Location cascade endpoints ────────────────────────────
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('states', fn (Request $r) =>
        State::where('country_id', $r->country_id)->orderBy('name')->get(['id', 'name'])
    );
    Route::get('cities', fn (Request $r) =>
        City::where('state_id', $r->state_id)->orderBy('name')->get(['id', 'name'])
    );
});


// employe resource api
Route::get('employees/filter-meta', [EmployeeController::class, 'filterMeta'])->name('employees.filter-meta');
Route::get('employees/export',      [EmployeeController::class, 'export'])->name('employees.export');

Route::get('employees',             [EmployeeController::class, 'index'])->name('employees.index');
Route::get('employees/{employee}',  [EmployeeController::class, 'show'])->name('employees.show');
Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
Route::apiResource('employees', EmployeeController::class);

// fetch dependent fields
Route::get('/countries', [EmployeeController::class, 'getCountries'])->name('get.countries');
Route::get('/cities', [EmployeeController::class, 'getCities'])->name('get.cities');
Route::get('/states', [EmployeeController::class, 'getStates'])->name('get.states');
Route::get('/departments', [EmployeeController::class, 'getDepartments'])->name('get.departments');
