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


    /**
     * Display a listing of the states.
     */
    public function index(Request $request)
    {
        try {
            $states = State::query()
                ->with(['country'])
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where(function ($sub) use ($request) {
                        $sub->where('name', 'like', "%{$request->search}%")
                            ->orWhere('state_code', 'like', "%{$request->search}%");
                    });
                })
                ->when($request->filled('country_id'), function ($query) use ($request) {
                    $query->where('country_id', $request->country_id);
                })
                ->latest('id')
                ->paginate(15)
                ->withQueryString();

            // Needed by the filter dropdown in the index view
            $countries = Country::select('id', 'name')->orderBy('name')->get();

            return view('admin.pages.State.index', compact('states', 'countries'));

        } catch (Throwable $e) {
            Log::error('Failed to load states index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('states.index')
                ->with('error', 'Unable to load states. Please try again.');
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