<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        // Admins land on the admin dashboard unless they were on their way to
        // another page when asked to sign in, such as the run club's
        // management page at /running/manage.
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->isStaff()) {
            return redirect()->route('pos.index');
        }

        return redirect()->intended(route('home'));
    }
}
