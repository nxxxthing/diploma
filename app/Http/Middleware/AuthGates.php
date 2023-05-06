<?php

namespace App\Http\Middleware;

use App\Facades\FlashMessages;
use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();

        if (!app()->runningInConsole() && $user) {
            $roles = Role::with('permissions')->get();

            foreach ($roles as $role) {
                foreach ($role->permissions as $permissions) {
                    $permissionsArray[$permissions->slug][] = $role->id;
                }
            }

            if (empty($permissionsArray)) {
                Auth::logout();
                return redirect('/admin/login');
            } else {
                foreach ($permissionsArray as $slug => $roles) {
                    Gate::define($slug, function (User $user) use ($roles) {
                        return in_array($user->role()->first()?->id, $roles);
                    });
                }
            }
        }

        return $next($request);
    }
}
