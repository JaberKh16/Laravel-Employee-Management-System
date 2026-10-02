<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Enums\BranchStatus;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:branch-list|branch-create|branch-edit|branch-delete', ['only' => ['index','store']]);
    //     $this->middleware('permission:branch-create', ['only' => ['create','store']]);
    //     $this->middleware('permission:branch-edit', ['only' => ['edit','update']]);
    //     $this->middleware('permission:branch-delete', ['only' => ['destroy']]);
    // }
    public function index(Request $request)
    {
        // $branches = Branch::all();
        $branches = Branch::query()
        ->with(['country', 'state', 'city'])
        ->search($request->input('search'))
        ->latest()
        ->paginate(15)
        ->withQueryString();
        $branchStatus = BranchStatus::cases();
        return view('admin.pages.Branch.index', compact('branches', 'branchStatus'));
    }


    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $branchStatus = BranchStatus::cases();
        return view('admin.pages.Branch.create', compact('countries', 'states', 'cities', 'branchStatus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

 
    public function show(string $id)
    {
        $branch = Branch::with(['country', 'state', 'city'])->findOrFail($id);

        return view('admin.pages.Branch.show', [
            'branch'       => $branch,
            'branchStatus' => BranchStatus::cases(),
        ]);
    }

    public function edit(Branch $branch)
    {
        $countries = Country::orderBy('name')->get();
        $states    = State::where('country_id', $branch->country_id)
                        ->orderBy('name')->get();
        $cities    = City::where('state_id', $branch->state_id)
                        ->orderBy('name')->get();
        $branchStatus = BranchStatus::cases();

        return view('admin.pages.Branch.edit', compact('branch', 'countries', 'states', 'cities', 'branchStatus'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch updated successfully!');
    }

  
    public function destroy(string $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }


    /**
     * Update the status of a single branch.
     *
     * Supports two response modes:
     *  - JSON  → when the request is AJAX (Accept: application/json or X-Requested-With)
     *  - Redirect → when the request comes from a standard form submit
     */
    public function updateStatus(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(BranchStatus::values())],
        ]);

        $branch->update(['status' => $validated['status']]);

        // AJAX / fetch() call
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Branch status updated.',
                'data'    => [
                    'id'     => $branch->id,
                    'status' => $branch->status?->value,
                    'label'  => $branch->status?->label(),
                    'color'  => $branch->status?->color(),
                ],
            ]);
        }

        // Standard form fallback
        return redirect()
            ->route('branches.index', $request->only('search', 'page'))
            ->with('success', 'Branch status updated successfully.');
    }
}
