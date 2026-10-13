<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    public function toResponse($request)
    {
        // Once verified, send standard customers straight to the shop page with a success message!
        // If they signed up from somewhere else (e.g. a run club RSVP), intended() sends them back there instead.
        return redirect()->intended(route('shop.index'))->with('success', 'Your email has been successfully verified! Welcome to Live Cafe.');
    }
}
