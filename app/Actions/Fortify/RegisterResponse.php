<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        // Redirect Admin immediately to dashboard
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Redirect Staff members to POS terminal view
        if ($user && $user->isStaff()) {
            return redirect()->route('pos.index');
        }

        // CHANGED HERE: Standard customers are now sent directly to the email confirmation middle page
        return redirect()->route('verification.notice');
    }
}
