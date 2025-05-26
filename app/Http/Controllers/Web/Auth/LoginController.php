<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone_number' => ['required'],
            'password' => ['required'],
            'country_code' => ['required'],
        ]);

        $remember = $request->remember ? true : false;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (isset($request['back'])) {
                return back();
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'phone_number' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
