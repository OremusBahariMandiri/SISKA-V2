<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('isMenuActive')) {
    function isMenuActive(array $routes)
    {
        foreach ($routes as $route) {
            if (request()->is($route)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('hasMenuAccess')) {
    function hasMenuAccess(array $permissions)
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($user->hasAccess($permission)) {
                return true;
            }
        }

        return false;
    }
}