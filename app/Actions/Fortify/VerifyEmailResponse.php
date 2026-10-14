<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    public function toResponse($request)
    {
        // Return users to their intended destination, or the shop by default.
        return redirect()->intended(route('shop.index'))
            ->with('success', 'Your email has been successfully verified! Welcome to LIVE café.');
    }
}
