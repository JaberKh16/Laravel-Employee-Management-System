<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        // $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:user-create', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $users = User::latest('id')->paginate(5)->withQueryString();
        if($request->has('search')){
            $users = User::where('username', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")->paginate(2);
        }
        return view('admin.pages.Users.index', compact('users'));
    }


    public function create()
    {
        $roles = Role::select('id', 'name')->latest('id')->get();
        return view('admin.pages.Users.create', compact('roles'));
    }


    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->input('roles'));

        $notification = [
            'alert_type' => 'Success',
            'message' => 'User Created Successfully!!!'
        ];
        // notify()->success($notification['message'],$notification['alert_type'],"topRight");
        return redirect()->route('users.index')->with('success', 'Created');
    }

 
    public function show(User $user)
    {
        //
    }

   
    public function edit(User $user)
    {
        $roles = Role::select('id', 'name')->latest('id')->get();
        $userRole = $user->roles->all();
        return view('admin.pages.Users.edit', compact(
            'user',
            'roles',
            'userRole'
        ));
    }

  
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        $user->assignRole($request->input('roles'));

        if($request->password){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }
        $notification = [
            'alert_type' => 'Success',
            'message' => 'User Updated Successfully!!!'
        ];
        notify()->info($notification['message'],$notification['alert_type'],"topRight");
        return redirect()->route('users.index')->with($notification);
    }

    
    public function destroy(User $user)
    {
        if(Auth::user()->id == $user->id){
            $notification = [
                'alert_type' => 'Success',
                'message' => "You Can't Deleted this account!!!"
            ];
            notify()->error($notification['message'],$notification['alert_type'],"topRight");
            return redirect()->route('users.index')->with($notification);
        }
        $user->delete();
        $notification = [
            'alert_type' => 'Success',
            'message' => 'User Deleted Successfully!!!'
        ];
        notify()->error($notification['message'],$notification['alert_type'],"topRight");
        return redirect()->route('users.index')->with($notification);
    }

    public function profile() 
    {
        $user = Auth::user();
        $countries = Country::select('id','name')->orderBy('name', 'ASC')->get();
        $states = State::select('id','name')->orderBy('name', 'ASC')->get();
        $cities = City::select('id','name')->orderBy('name', 'ASC')->get();
        return view('admin.pages.Users.profile', compact('user', 'countries', 'states', 'cities'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            // User table
            'username'   => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],

            // Profile table
            'phone'      => ['nullable', 'string', 'max:30'],
            'avatar'     => ['nullable', 'image', 'max:2048'],
            'birthdate'  => ['nullable', 'date'],
            'gender'     => ['nullable', 'in:male,female,other'],
            'bio'        => ['nullable', 'string', 'max:1000'],
            'address'    => ['nullable', 'string', 'max:255'],
            'zip_code'   => ['nullable', 'string', 'max:20'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id'   => ['nullable', 'exists:states,id'],
            'city_id'    => ['nullable', 'exists:cities,id'],
            'website'    => ['nullable', 'url', 'max:255'],
            'linkedin'   => ['nullable', 'url', 'max:255'],
            'twitter'    => ['nullable', 'url', 'max:255'],

            // Password (optional)
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Split user vs profile fields
        $userFields = ['username', 'first_name', 'last_name', 'email'];
        $profileFields = [
            'phone', 'avatar', 'birthdate', 'gender', 'bio',
            'address', 'zip_code', 'country_id', 'state_id', 'city_id',
            'website', 'linkedin', 'twitter',
        ];

        $userData = array_intersect_key($data, array_flip($userFields));

        if ($request->filled('password')) {
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        $user->update($userData);
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            array_intersect_key($data, array_flip($profileFields))
        );

        notify()->success('Profile Updated Successfully!!!', 'Success', 'topRight');
        return redirect()->route('users.profile');
    }

}
