<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
   
    public function created(User $user)
    {
        // dd(auth()->user());
        // if(auth()->user()->username != null){
        //     dd(auth()->user());
        //     Log::info("New user".$user."Data Inserted by ".auth()->user()->username);
        // }
        
    }

   
    public function updated(User $user)
    {
        if(auth()->user()->username != null){
            Log::info("User".$user."Data Updated by ".auth()->user()->username);
        }
    }

   
    public function deleted(User $user)
    {
        if(auth()->user()->username != null){
            Log::info("User".$user."Data Deleted by ".auth()->user()->username);
        }
    }

 
    public function restored(User $user)
    {
        //
    }


    public function forceDeleted(User $user)
    {
        //
    }
}
