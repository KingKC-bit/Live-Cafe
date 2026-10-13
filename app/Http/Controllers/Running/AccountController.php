<?php

namespace App\Http\Controllers\Running;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Sends a visitor to sign in or register, then back to the run club page
 * they came from instead of the home page.
 *
 * How it works: Laravel keeps a "url.intended" value in the session. After a
 * customer signs in, LoginResponse redirects to it (redirect()->intended()),
 * and after a new member verifies their email, VerifyEmailResponse does the
 * same. These links set that value just before handing over to the normal
 * sign-in and register pages.
 */
class AccountController extends Controller
{
    public function signIn(Request $request): RedirectResponse
    {
        return $this->handOff($request, 'login');
    }

    public function register(Request $request): RedirectResponse
    {
        return $this->handOff($request, 'register');
    }

    private function handOff(Request $request, string $route): RedirectResponse
    {
        // Only a run's id is accepted (not a full URL) so these links can't be
        // used to bounce people to another website after they sign in.
        $event = $request->filled('event') ? Event::query()->find($request->integer('event')) : null;

        $returnTo = $event
            ? route('running.events.show', $event).'#rsvp'
            : route('running.index');

        if ($request->user()) {
            return redirect()->to($returnTo);
        }

        $request->session()->put('url.intended', $returnTo);

        return redirect()->route($route);
    }
}
