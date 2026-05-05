<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function getUser(): Authenticatable|User|null
    {
        return Auth::user();
    }
}
