<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{

    //TODO: Implement login logic
    public function login()
    {
        $credentials = request()->validate([
            'username' => ['required', Rule::exists('users', 'username')],
            'password' => ['required']
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'username' => 'Username or password invalid.'
            ]);
        }

        session()->regenerate();

        return redirect(route('registrations.index'));
    }

    public function logout()
    {
        auth()->logout();

        return redirect(route('registrations.create'));
    }
}
