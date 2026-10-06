<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StateStoreRequest;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Exports\StatesExport;

class StateController extends Controller
{
    /**
     * Create a new instance of the class
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('permission:state-list|state-create|state-edit|state-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:state-create', ['only' => ['create','store']]);
        // $this->middleware('permission:state-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:state-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        try {
            $states = $this->buildFilteredQuery($request)
                ->with('country')       // eager-load to avoid N+1 on the flag column
                ->paginate(15)
                ->withQueryString();

            return view('admin.pages.State.index', [
                'states'    => $states,
                'countries' => Country::orderBy('name')->get(['id', 'name', 'country_code']),
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to load states', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Unable to load states. Please try again.');
        }
    }

    protected function buildFilteredQuery(Request $request)
    {
        $query = State::query();

        // Global search
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                ->orWhere('state_code', 'like', "%{$s}%");
            });
        }

        // Advanced filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . trim($request->name) . '%');
        }
        if ($request->filled('code')) {
            $query->where('state_code', 'like', strtoupper(trim($request->code)) . '%');
        }
        if ($request->filled('country_id')) {
            $query->where('country_id', (int) $request->country_id);
        }
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        // Sorting
        $sortable  = ['name', 'state_code', 'country_id', 'created_at', 'updated_at', 'id'];
        $sort      = in_array($request->sort, $sortable, true) ? $request->sort : 'id';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction);
    }

    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $query  = $this->buildFilteredQuery($request)->with('country');

            $filename = 'states-' . now()->format('Y-m-d_His');

            return match ($format) {
                'csv', 'xlsx' => \Maatwebsite\Excel\Facades\Excel::download(
                                    new \App\Exports\StatesExport($query),
                                    "{$filename}.{$format}"
                                ),
                'pdf'  => \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.states', [
                            'states' => (clone $query)->get(),
                        ])->download("{$filename}.pdf"),
                'json' => response()->json((clone $query)->get()),
                default => back()->with('error', 'Invalid export format.'),
            };
        } catch (\Throwable $e) {
            \Log::error('Failed to export states', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
            ]);

            return back()->with('error', 'Unable to export states.');
        }
    }


    /**
     * Show the form for creating a new state.
     */
    public function create()
    {
        try {
            $countries = Country::select('id', 'name')->orderBy('name')->get();

            return view('admin.pages.State.create', compact('countries'));

        } catch (Throwable $e) {
            Log::error('Failed to load state create form', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('states.index')
                ->with('error', 'Unable to open the create form. Please try again.');
        }
    }


    /**
     * Store a newly created state in storage.
     */
    public function store(StateStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $state = State::create($request->validated());

            DB::commit();

            notify()->success(
                'State Created Successfully!!!',
                'Success',
                'topRight'
            );

            return redirect()
                ->route('states.index')
                ->with('success', 'State Created Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to create state', [
                'payload' => $request->validated(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create state. Please try again.');
        }
    }


    /**
     * Display the specified state.
     */
    public function show(State $state)
    {
        //
    }


    /**
     * Show the form for editing the specified state.
     */
    public function edit(State $state)
    {
        try {
            $countries = Country::select('id', 'name')->orderBy('name')->get();

            return view('admin.pages.State.edit', compact('state', 'countries'));

        } catch (Throwable $e) {
            Log::error('Failed to load state edit form', [
                'state_id' => $state->id,
                'error'    => $e->getMessage(),
            ]);

            return redirect()
                ->route('states.index')
                ->with('error', 'Unable to open the edit form. Please try again.');
        }
    }


    /**
     * Update the specified state in storage.
     */
    public function update(StateStoreRequest $request, State $state)
    {
        try {
            DB::beginTransaction();

            $state->update($request->validated());

            DB::commit();

            notify()->success(
                'State Updated Successfully!!!',
                'Success',
                'topRight'
            );

            return redirect()
                ->route('states.index')
                ->with('success', 'State Updated Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to update state', [
                'state_id' => $state->id,
                'payload'  => $request->validated(),
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update state. Please try again.');
        }
    }


    /**
     * Remove the specified state from storage.
     */
    public function destroy(State $state)
    {
        try {
            DB::beginTransaction();

            // Guard: prevent deleting a state that still has dependent records.
            // Uncomment and adjust if you have related models (e.g. cities, users).
            //
            // if ($state->cities()->exists()) {
            //     DB::rollBack();
            //     return redirect()
            //         ->route('states.index')
            //         ->with('error', 'Cannot delete this state because it still has cities assigned.');
            // }

            $state->delete();

            DB::commit();

            notify()->success(
                'State Deleted Successfully!!!',
                'Success',
                'topRight'
            );

            return redirect()
                ->route('states.index')
                ->with('success', 'State Deleted Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to delete state', [
                'state_id' => $state->id,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('states.index')
                ->with('error', 'Failed to delete state. Please try again.');
        }
    }
}