<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    public function toResponse($request)
    {
        // Once verified, send standard customers straight to the shop page with a success message!
        return redirect()->route('shop.index')->with('success', 'Your email has been successfully verified! Welcome to Live Cafe.');
    }
}
