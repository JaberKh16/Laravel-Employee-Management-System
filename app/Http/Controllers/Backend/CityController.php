<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityStoreRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Exports\CitiesExport;

class CityController extends Controller
{
    /**
     * Create a new instance of the class
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('permission:city-list|city-create|city-edit|city-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:city-create', ['only' => ['create','store']]);
        // $this->middleware('permission:city-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:city-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the cities.
     */
    // public function index(Request $request)
    // {
    //     try {
    //         $cities = City::query()
    //             ->with(['state.country'])          // eager-load the full chain
    //             ->when($request->filled('search'), function ($query) use ($request) {
    //                 $query->where('name', 'like', "%{$request->search}%");
    //             })
    //             ->when($request->filled('state_id'), function ($query) use ($request) {
    //                 $query->where('state_id', $request->state_id);
    //             })
    //             ->latest('id')
    //             ->paginate(15)
    //             ->withQueryString();

    //         // Needed by the state filter dropdown in the index view.
    //         // Eager-load country so each option can render "State — Country".
    //         $states = State::with('country')
    //             ->select('id', 'name', 'country_id')
    //             ->orderBy('name')
    //             ->get();

    //         return view('admin.pages.City.index', compact('cities', 'states'));

    //     } catch (Throwable $e) {
    //         Log::error('Failed to load cities index', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return redirect()
    //             ->route('cities.index')
    //             ->with('error', 'Unable to load cities. Please try again.');
    //     }
    // }

    public function index(Request $request)
    {
        try {
            $cities = $this->buildFilteredQuery($request)
                ->with(['state.country'])      // eager-load both relations (avoids N+1)
                ->paginate(15)
                ->withQueryString();

            return view('admin.pages.City.index', [
                'cities'    => $cities,
                'states'    => State::with('country')->orderBy('name')->get(['id', 'name', 'country_id']),
                'countries' => Country::orderBy('name')->get(['id', 'name']),
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to load cities', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Unable to load cities. Please try again.');
        }
    }

    protected function buildFilteredQuery(Request $request)
    {
        $query = City::query();

        // Global search
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where('name', 'like', "%{$s}%");
        }

        // Advanced filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . trim($request->name) . '%');
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', (int) $request->state_id);
        }

        if ($request->filled('country_id')) {
            // Filter cities whose parent state belongs to this country
            $query->whereHas('state', function ($q) use ($request) {
                $q->where('country_id', (int) $request->country_id);
            });
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        // Sorting
        $sortable  = ['name', 'state_id', 'created_at', 'updated_at', 'id'];
        $sort      = in_array($request->sort, $sortable, true) ? $request->sort : 'id';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction);
    }

    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $query  = $this->buildFilteredQuery($request)->with('state.country');

            $filename = 'cities-' . now()->format('Y-m-d_His');

            return match ($format) {
                'csv', 'xlsx' => Excel::download(
                                    new CitiesExport($query),
                                    "{$filename}.{$format}"
                                ),
                'pdf'  => Pdf::loadView('exports.cities', [
                            'cities' => (clone $query)->get(),
                        ])->download("{$filename}.pdf"),
                'json' => response()->json((clone $query)->get()),
                default => back()->with('error', 'Invalid export format.'),
            };
        } catch (\Throwable $e) {
            \Log::error('Failed to export cities', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
            ]);

            return back()->with('error', 'Unable to export cities.');
        }
    }


    /**
     * Show the form for creating a new city.
     */
    public function create()
    {
        try {
            // Eager-load country on each state so the dropdown can show
            // "State — Country" without firing an N+1 query per row.
            $states = State::with('country')
                ->select('id', 'name', 'country_id')
                ->orderBy('name')
                ->get();

            // Used by the country filter dropdown in the create form.
            $countries = Country::select('id', 'name')
                ->orderBy('name')
                ->get();

            return view('admin.pages.City.create', compact('states', 'countries'));

        } catch (Throwable $e) {
            Log::error('Failed to load city create form', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('cities.index')
                ->with('error', 'Unable to open the create form. Please try again.');
        }
    }


    /**
     * Store a newly created city in storage.
     */
    public function store(CityStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $city = City::create($request->validated());

            DB::commit();

            notify()->success(
                'City Created Successfully!!!',
                'Success',
                'topRight'
            );

            return redirect()
                ->route('cities.index')
                ->with('success', 'City Created Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to create city', [
                'payload' => $request->validated(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create city. Please try again.');
        }
    }


    /**
     * Display the specified city.
     */
    public function show(City $city)
    {
        //
    }


    /**
     * Show the form for editing the specified city.
     */
    public function edit(City $city)
    {
        try {
            // Eager-load country on each state for the dropdown labels.
            $states = State::with('country')
                ->select('id', 'name', 'country_id')
                ->orderBy('name')
                ->get();

            // Used by the country filter dropdown in the edit form.
            $countries = Country::select('id', 'name')
                ->orderBy('name')
                ->get();

            return view('admin.pages.City.edit', compact(
                'city',
                'states',
                'countries'
            ));

        } catch (Throwable $e) {
            Log::error('Failed to load city edit form', [
                'city_id' => $city->id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()
                ->route('cities.index')
                ->with('error', 'Unable to open the edit form. Please try again.');
        }
    }


    /**
     * Update the specified city in storage.
     */
    public function update(CityStoreRequest $request, City $city)
    {
        try {
            DB::beginTransaction();

            $city->update($request->validated());

            DB::commit();

            notify()->success(
                'City Updated Successfully!!!',
                'Success',
                'topRight'
            );

            return redirect()
                ->route('cities.index')
                ->with('success', 'City Updated Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to update city', [
                'city_id' => $city->id,
                'payload' => $request->validated(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update city. Please try again.');
        }
    }


    /**
     * Remove the specified city from storage.
     */
    public function destroy(City $city)
    {
        try {
            DB::beginTransaction();

            // Guard: prevent deletion if dependent records exist.
            // Uncomment and adjust once you have related models (e.g. addresses,
            // users, districts).
            //
            // if ($city->addresses()->exists()) {
            //     DB::rollBack();
            //     return redirect()
            //         ->route('cities.index')
            //         ->with('error', 'Cannot delete this city because it still has addresses assigned.');
            // }

            $city->delete();

            DB::commit();

            notify()->success(
                'City Deleted Successfully!!!',
                'Success',
                'topRight'
            );

            return redirect()
                ->route('cities.index')
                ->with('success', 'City Deleted Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to delete city', [
                'city_id' => $city->id,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('cities.index')
                ->with('error', 'Failed to delete city. Please try again.');
        }
    }
}