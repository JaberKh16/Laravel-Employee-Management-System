<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryStoreRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Exports\CountriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CountryController extends Controller
{
    /**
     * Create a new instance of the class.
     */
    public function __construct()
    {
        // $this->middleware('permission:country-list|country-create|country-edit|country-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:country-create', ['only' => ['create','store']]);
        // $this->middleware('permission:country-edit',   ['only' => ['edit','update']]);
        // $this->middleware('permission:country-delete', ['only' => ['destroy']]);
    }

    // ═══════════════════════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════════════════════
    // public function index(Request $request)
    // {
    //     try {
    //         $query = Country::query();

    //         if ($request->filled('search')) {
    //             $search = trim($request->search);
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                   ->orWhere('country_code', 'like', "%{$search}%");
    //             });
    //         }

    //         $countries = $query->latest('id')
    //             ->paginate(15)
    //             ->withQueryString();

    //         return view('admin.pages.Country.index', compact('countries'));

    //     } catch (Throwable $e) {
    //         Log::error('Failed to load countries list', [
    //             'error'   => $e->getMessage(),
    //             'file'    => $e->getFile(),
    //             'line'    => $e->getLine(),
    //             'user_id' => auth()->id(),
    //         ]);

    //         return redirect()->back()
    //             ->with('error', 'Unable to load countries. Please try again.');
    //     }
    // }

    protected function buildFilteredQuery(Request $request)
    {
        $query = Country::query();

        // ── Global search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('country_code', 'like', "%{$search}%");
            });
        }

        // ── Advanced filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . trim($request->name) . '%');
        }

        if ($request->filled('code')) {
            $query->where('country_code', 'like', strtoupper(trim($request->code)) . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        // ── Sorting
        $sortable  = ['name', 'country_code', 'created_at', 'updated_at', 'id'];
        $sort      = in_array($request->sort, $sortable, true) ? $request->sort : 'id';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction);
    }

    public function index(Request $request)
    {
        try {
             $countries = $this->buildFilteredQuery($request)
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.Country.index', compact('countries'));

        } catch (Throwable $e) {
            Log::error('Failed to load countries', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to load countries. Please try again.');
        }
    }

    // ═══════════════════════════════════════════════════════════
    // CREATE
    // ═══════════════════════════════════════════════════════════
    public function create()
    {
        try {
            return view('admin.pages.Country.create');

        } catch (Throwable $e) {
            Log::error('Failed to open country create form', [
                'error'   => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('countries.index')
                ->with('error', 'Unable to open the create form.');
        }
    }

    // ═══════════════════════════════════════════════════════════
    // STORE
    // ═══════════════════════════════════════════════════════════
    public function store(CountryStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $country = Country::create($request->validated());

            DB::commit();

            return redirect()
                ->route('countries.index')
                ->with('success', "Country \"{$country->name}\" created successfully.");

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to create country', [
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'payload' => $request->validated(),
                'user_id' => auth()->id(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unable to create country. Please try again.');
        }
    }

    // ═══════════════════════════════════════════════════════════
    // SHOW
    // ═══════════════════════════════════════════════════════════
    public function show(Country $country)
    {
        try {
            return view('admin.pages.Country.show', compact('country'));

        } catch (Throwable $e) {
            Log::error('Failed to show country', [
                'error'      => $e->getMessage(),
                'country_id' => $country->id,
                'user_id'    => auth()->id(),
            ]);

            return redirect()->route('countries.index')
                ->with('error', 'Unable to load country details.');
        }
    }

    // ═══════════════════════════════════════════════════════════
    // EDIT
    // ═══════════════════════════════════════════════════════════
    public function edit(Country $country)
    {
        try {
            return view('admin.pages.Country.edit', compact('country'));

        } catch (Throwable $e) {
            Log::error('Failed to open country edit form', [
                'error'      => $e->getMessage(),
                'country_id' => $country->id,
                'user_id'    => auth()->id(),
            ]);

            return redirect()->route('countries.index')
                ->with('error', 'Unable to open the edit form.');
        }
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE
    // ═══════════════════════════════════════════════════════════
    public function update(CountryStoreRequest $request, Country $country)
    {
        DB::beginTransaction();

        try {
            $country->update($request->validated());

            DB::commit();

            return redirect()
                ->route('countries.index')
                ->with('success', "Country \"{$country->name}\" updated successfully.");

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to update country', [
                'error'      => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'country_id' => $country->id,
                'payload'    => $request->validated(),
                'user_id'    => auth()->id(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unable to update country. Please try again.');
        }
    }

    // ═══════════════════════════════════════════════════════════
    // DESTROY
    // ═══════════════════════════════════════════════════════════
    public function destroy(Country $country)
    {
        DB::beginTransaction();

        try {
            $countryName = $country->name;
            $country->delete();

            DB::commit();

            return redirect()
                ->route('countries.index')
                ->with('success', "Country \"{$countryName}\" deleted successfully.");

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to delete country', [
                'error'      => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'country_id' => $country->id,
                'user_id'    => auth()->id(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Unable to delete country. It may be referenced by other records.');
        }
    }

    // CountryController@export
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');

            // ⚠️ NOTE: no ->get() — pass the query builder for FromQuery exports
            $query = $this->buildFilteredQuery($request);

            $filename = 'countries-' . now()->format('Y-m-d_His');

            return match ($format) {
                'csv'   => Excel::download(new CountriesExport($query), "{$filename}.csv"),
                'xlsx'  => Excel::download(new CountriesExport($query), "{$filename}.xlsx"),
                'pdf'   => PDF::loadView('exports.countries', [
                                'countries' => (clone $query)->get(),   // ← materialize only for PDF
                            ])->download("{$filename}.pdf"),
                'json'  => response()->json((clone $query)->get()),
                default => back()->with('error', 'Invalid export format.'),
            };
        } catch (\Throwable $e) {
            Log::error('Failed to export countries', [
                'error'  => $e->getMessage(),
                'file'   => $e->getFile(),
                'line'   => $e->getLine(),
                'format' => $request->get('format'),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Unable to export countries.');
        }
    }
}