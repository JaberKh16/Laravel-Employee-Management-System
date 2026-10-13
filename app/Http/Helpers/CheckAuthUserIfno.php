<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckAuthUserIfno
{
    public static function checkUserInfo()
    {
        $user = Auth::user();

        if (!$user) {
            return false; // User is not authenticated
        }

        // Check if the user has a profile
        if (!$user->profile) {
            return false; // User does not have a profile
        }

        // Check if the user has a department
        if (!$user->dept_id) {
            return false; // User does not have a department
        }


        return true; // User has both profile and department
    }

    public static function logUserInfo()
    {
        $user = Auth::user();
        $profile = $user ? $user->profile : null;
        $auth_info = Auth::user() ? Auth::user()->toArray() : null;

        if ($user) {
            Log::info('Authenticated User Info:', [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'profile_id' => $user->profile_id,
                'dept_id' => $user->dept_id,
                'profile' => $profile ? $profile->toArray() : null,
                'auth_info' => $auth_info,
            ]);
        } else {
            Log::info('No authenticated user.');
        }
    }
}