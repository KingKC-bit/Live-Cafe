<?php

namespace App\Providers;

<<<<<<< HEAD
use App\Actions\Fortify\CreateNewUser;
=======
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
<<<<<<< HEAD
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
=======
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
<<<<<<< HEAD
        // Your existing LoginResponse binding
        $this->app->singleton(
            LoginResponse::class,
            \App\Actions\Fortify\LoginResponse::class
        );

        // Your existing RegisterResponse binding
        $this->app->singleton(
            RegisterResponse::class,
            \App\Actions\Fortify\RegisterResponse::class
        );

        // ADD THIS: Overrides the redirect engine AFTER clicking the Mailpit link
        $this->app->singleton(
            VerifyEmailResponse::class,
            \App\Actions\Fortify\VerifyEmailResponse::class
        );

        $this->app->singleton(
            CreatesNewUsers::class,
            CreateNewUser::class
        );
=======
        //
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('pages::auth.login'));
<<<<<<< HEAD
        Fortify::registerView(fn () => view('pages::auth.register'));
=======
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
        Fortify::verifyEmailView(fn () => view('pages::auth.verify-email'));
        Fortify::resetPasswordView(fn () => view('pages::auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('pages::auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

    }
}
