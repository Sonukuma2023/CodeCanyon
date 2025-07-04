<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user->role == 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } 
        elseif ($user->role == 'author') {
            return redirect()->intended(route('author.dashboard'));
        } 
        elseif ($user->role == 'user') {
            if ($user->status == 'active') {
                return redirect()->intended(route('user.dashboard', absolute: false));
            } else {
                auth()->logout();  
                return redirect()->route('login')->withErrors([
                    'status' => 'Your account is inactive. Please contact support.'
                ]);
            }
        }

        auth()->logout();
        return redirect()->route('login')->withErrors([
            'status' => 'Invalid credentials or role.'
        ]);
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
