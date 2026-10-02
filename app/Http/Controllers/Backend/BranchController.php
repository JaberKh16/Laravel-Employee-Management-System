<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

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
        return view('admin.pages.Branch.index', compact('branches'));
    }


    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        return view('admin.pages.Branch.create', compact('countries', 'states', 'cities'));
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
        $branch = Branch::findOrFail($id);
        return view('admin.pages.Branch.show', compact('branch'));
    }


    public function edit(Branch $branch)
    {
        $countries = Country::orderBy('name')->get();
        $states    = State::where('country_id', $branch->country_id)
                        ->orderBy('name')->get();
        $cities    = City::where('state_id', $branch->state_id)
                        ->orderBy('name')->get();

        return view('admin.pages.Branch.edit', compact('branch', 'countries', 'states', 'cities'));
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
}
