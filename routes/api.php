<?php

use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\DailyLogController;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// ─── Auth user ────────────────────────────────────────────
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ─── Daily logs (auth) ────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logs', [DailyLogController::class, 'store']);
    Route::get('/logs/daily', [DailyLogController::class, 'daily']);
});

// ─── Location cascade ─────────────────────────────────────
// Route::middleware(['auth'])->group(function () {
//     Route::get(
//         'states',
//         fn(Request $r) =>
//             State::where('country_id', $r->country_id)->orderBy('name')->get(['id', 'name'])
//     );
//     Route::get(
//         'cities',
//         fn(Request $r) =>
//             City::where('state_id', $r->state_id)->orderBy('name')->get(['id', 'name'])
//     );
// });


Route::get(
    'states',
    fn(Request $r) =>
        State::where('country_id', $r->country_id)->orderBy('name')->get(['id', 'name'])
);
Route::get(
    'cities',
    fn(Request $r) =>
        City::where('state_id', $r->state_id)->orderBy('name')->get(['id', 'name'])
);


// ─── Employees ────────────────────────────────────────────
Route::prefix('v1')            // optional versioning — drop if not needed
    ->name('api.')             // optional name prefix → route('api.employees.index')
    ->group(function () {

        // ─── Employees ────────────────────────────────────────────
        Route::get('employees/filter-meta', [EmployeeController::class, 'filterMeta'])
            ->name('employees.filter-meta');

        Route::get('employees/export', [EmployeeController::class, 'export'])
            ->name('employees.export');

        // Registers: index, store, show, update, destroy
        //   GET    /api/v1/employees
        //   POST   /api/v1/employees
        //   GET    /api/v1/employees/{employee}
        //   PUT    /api/v1/employees/{employee}
        //   DELETE /api/v1/employees/{employee}
        Route::apiResource('employees', EmployeeController::class);


        // ─── Lookup lists ─────────────────────────────────────────
        Route::get('countries',   [EmployeeController::class, 'getCountries'])->name('get.countries');
        Route::get('cities',      [EmployeeController::class, 'getCities'])->name('get.cities');
        Route::get('states',      [EmployeeController::class, 'getStates'])->name('get.states');
        Route::get('departments', [EmployeeController::class, 'getDepartments'])->name('get.departments');
});


