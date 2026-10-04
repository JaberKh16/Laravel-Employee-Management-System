<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Enums\ActiveStatus;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Throwable;

class UserController extends Controller
{
    /**
     * Create a new instance of the class.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:user-create', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }


    // public function index(Request $request)
    // {
    //     try {
    //         $users = User::query()
    //             ->with('profile')          // eager-load to avoid N+1 in views
    //             ->when($request->filled('search'), function ($q) use ($request) {
    //                 $q->where(function ($sub) use ($request) {
    //                     $sub->where('username', 'like', "%{$request->search}%")
    //                         ->orWhere('email', 'like', "%{$request->search}%");
    //                 });
    //             })
    //             ->latest('id')
    //             ->paginate(15)
    //             ->withQueryString();

    //         return view('admin.pages.Users.index', compact('users'));

    //     } catch (Throwable $e) {
    //         Log::error('Failed to load users index', [
    //             'error' => $e->getMessage(),
    //         ]);

    //         return redirect()
    //             ->route('users.index')
    //             ->with('error', 'Unable to load users.');
    //     }
    // }

    public function index()
    {
        try {
            $query = User::query();

            if ($search = request('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($name = request('name')) {
                $query->where(function ($q) use ($name) {
                    $q->where('full_name', 'like', "%{$name}%")
                        ->orWhere('username', 'like', "%{$name}%");
                });
            }

            if ($email = request('email')) {
                $query->where('email', 'like', "%{$email}%");
            }

            if ($late = request('late')) {
                if ($late === 'older') {
                    $query->where('updated_at', '<', now()->subYear());
                } else {
                    $query->where('updated_at', '>=', now()->subDays((int) $late));
                }
            }

            $users = $query->latest('updated_at')->paginate(15)->withQueryString();

            return view('admin.pages.Users.index', compact('users'));

        } catch (Throwable $e) {
            Log::error('Failed to load users index', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Unable to load users.');
        }
    }


    public function create()
    {
        try {
            $roles = Role::select('id', 'name')->orderBy('name')->get();

            return view('admin.pages.Users.create', compact('roles'));

        } catch (Throwable $e) {
            Log::error('Failed to load user create form', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Unable to open the create form.');
        }
    }


    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // 1) Create the User (only columns that exist on the users table).
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => ActiveStatus::Active->value,
            ]);

            // 2) Create the Profile with the names + optional fields.
            //    Profile is the table that owns first_name / last_name.
            $user->profile()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
            ]);

            // 3) Assign roles (Spatie). Handle both array and single string.
            $roles = $request->input('roles', []);
            if (!empty($roles)) {
                $user->syncRoles($roles);
            }

            DB::commit();

            notify()->success('User Created Successfully!!!', 'Success', 'topRight');

            return redirect()
                ->route('users.index')
                ->with('success', 'User Created Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to create user', [
                'payload' => $request->except(['password', 'password_confirmation']),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }


    public function show(User $user)
    {
        //
    }


    public function edit(User $user)
    {
        try {
            $roles = Role::select('id', 'name')->orderBy('name')->get();
            $userRole = $user->roles->pluck('name')->all();


            // Ensure a profile exists so the form's old() fallbacks work.
            $user->loadMissing('profile');

            return view('admin.pages.Users.edit', compact(
                'user',
                'roles',
                'userRole'
            ));

        } catch (Throwable $e) {
            Log::error('Failed to load user edit form', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Unable to open the edit form.');
        }
    }


    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            // 1) Update User table columns only.
            $userData = [
                'username' => $request->username,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // 2) Update or create the Profile.
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'birthdate' => $request->birthdate,
                ]
            );

            // 3) Sync roles.
            $roles = $request->input('roles', []);
            if (!empty($roles)) {
                $user->syncRoles($roles);
            }

            DB::commit();

            notify()->info('User Updated Successfully!!!', 'Success', 'topRight');

            return redirect()
                ->route('users.index')
                ->with('success', 'User Updated Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to update user', [
                'user_id' => $user->id,
                'payload' => $request->except(['password', 'password_confirmation']),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }


    public function destroy(User $user)
    {
        try {
            // Prevent self-deletion.
            if (Auth::id() === $user->id) {
                notify()->error("You can't delete your own account.", 'Error', 'topRight');

                return redirect()
                    ->route('users.index')
                    ->with('error', "You can't delete your own account.");
            }

            DB::beginTransaction();

            // Delete the profile first (or rely on FK cascade).
            $user->profile()?->delete();

            $user->delete();

            DB::commit();

            notify()->success('User Deleted Successfully!!!', 'Success', 'topRight');

            return redirect()
                ->route('users.index')
                ->with('success', 'User Deleted Successfully!!!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to delete user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('users.index')
                ->with('error', 'Failed to delete user. Please try again.');
        }
    }


    public function profile()
    {
        $user = Auth::user();
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        $states = State::select('id', 'name')->orderBy('name')->get();
        $cities = City::select('id', 'name')->orderBy('name')->get();

        return view('admin.pages.Users.profile', compact('user', 'countries', 'states', 'cities'));
    }


    public function profileUpdate(ProfileUpdateRequest $request)
    {
        try {
            $user = $request->user();

            DB::beginTransaction();

            /* ---------- 1. User table ---------- */
            $userData = $request->validatedUserFields();

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->input('password'));
            }

            $user->update($userData);

            /* ---------- 2. Profile table ---------- */
            $profileData = $request->validatedProfileFields();

            // Avatar upload — delete old file first.
            if ($request->hasFile('avatar')) {
                if ($user->profile?->avatar && Storage::disk('public')->exists($user->profile->avatar)) {
                    Storage::disk('public')->delete($user->profile->avatar);
                }

                $profileData['avatar'] = $request->file('avatar')
                    ->store('avatars/' . $user->id, 'public');
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            DB::commit();

            return redirect()
                ->route('users.profile')
                ->with('success', 'Profile Updated Successfully!');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to update profile', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }
}