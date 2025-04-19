<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    // }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Store a value in session to indicate user is logged in
            $request->session()->put('is_logged_in', true);

            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('dashboard');
            } elseif ($user->isManager()) {
                return redirect()->route('dashboard');
            } elseif ($user->isSupportAgent()) {
                return redirect()->route('dashboard.tickets.index');
            } else {
                // Check if there was a redirect from product page
                if ($request->has('redirect')) {
                    return redirect($request->redirect);
                }

                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }


    /**
     * Log the user out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Remove the logged-in flag
        $request->session()->forget('is_logged_in');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}