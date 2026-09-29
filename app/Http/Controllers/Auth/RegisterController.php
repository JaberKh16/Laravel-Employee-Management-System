<?php

namespace App\Http\Controllers\Auth;

use App\Http\Casts\UserStatusCast;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9._-]+$/',      // letters, numbers, dot, underscore, hyphen
                'unique:users,username',
            ],
            'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\pL\s\'-]+$/u'],
            'last_name' => ['nullable', 'string', 'min:2', 'max:50', 'regex:/^[\pL\s\'-]+$/u'],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->withoutTrashed(),
            ],
            'password' => [
                'required',
                'confirmed',
                // Password::min(10)
                //     ->letters()
                //     ->mixedCase()
                //     ->numbers()
                //     ->symbols()
                //     ->uncompromised(),          // checks against HaveIBeenPwned
            ],
        ]);
    }

    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => 'inactive'
            ]);

            $user->profile()->create([
                'user_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
            ]);

            return $user;
        });
    }
}
