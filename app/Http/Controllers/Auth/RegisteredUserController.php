<?php

namespace App\Http\Controllers\Auth;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest; 
use App\Providers\RouteServiceProvider;
use App\Notifications\RegistrationConfirmation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_token' => Str::random(60), 
        ]);

        $userRole = Role::findByName('利用者');
        $user->assignRole($userRole);

        $user->notify(new RegistrationConfirmation());

        return redirect()->route('register.thanks');
    }
}
