<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
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
        dd($user);

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
}
