<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
   public function store(LoginRequest $request)
   {
       // Authenticate user credentials
       $request->authenticate();

       // Prevent session fixation
       $request->session()->regenerate();

       // Clear any stale cart/session data and sync with logged-in user
       app(CartController::class)->transferSessionCartToDatabase();

       // Redirect based on role
       $user = Auth::user();

       if ($user->role === 'admin') {
           return redirect()->intended(route('second', ['admin', 'dashboard']));
       }

       return redirect()->intended(RouteServiceProvider::HOME);
   }


    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
