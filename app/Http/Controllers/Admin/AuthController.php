<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->get(config('admin.session_key'))) {
            return redirect()->route('admin.painel');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], [
            'username' => 'usuário',
            'password' => 'senha',
        ]);

        $rateLimitKey = 'admin-login:'.$request->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return back()
                ->withErrors([
                    'username' => 'Muitas tentativas. Aguarde '.$seconds.' segundos e tente de novo.',
                ])
                ->onlyInput('username');
        }

        $configuredUser = (string) config('admin.username');
        $configuredPassword = (string) config('admin.password');

        $userOk = $configuredUser !== ''
            && hash_equals($configuredUser, $credentials['username']);
        $passwordOk = $configuredPassword !== ''
            && hash_equals($configuredPassword, $credentials['password']);

        if (! $userOk || ! $passwordOk) {
            RateLimiter::hit($rateLimitKey, 60);

            return back()
                ->withErrors([
                    'username' => 'Usuário ou senha incorretos.',
                ])
                ->onlyInput('username');
        }

        RateLimiter::clear($rateLimitKey);

        $request->session()->regenerate();
        $request->session()->put(config('admin.session_key'), true);

        return redirect()->intended(route('admin.painel'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(config('admin.session_key'));
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('status', 'Você saiu da área administrativa.');
    }

    public function painel(): View
    {
        return view('admin.painel');
    }
}
