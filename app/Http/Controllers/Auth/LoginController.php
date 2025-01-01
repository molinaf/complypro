<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        if ($user->status === 'P') {
            Auth::logout();
            return redirect()->route('email.sent')->with('error', 'Your account is pending approval. Please verify your email or wait for endorsement.');
        }
		if ($user->role === 'U') { return redirect()->route('dashboard.user');}
		if ($user->role === 'S') { return redirect()->route('dashboard.supervisor');}
		if ($user->role === 'A') { return redirect()->route('dashboard.admin');}
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();
    
        if ($user && $user->status === 'P') {
            throw ValidationException::withMessages([
                'email' => ['The email/password combination are correct. However, your account is pending approval and awaiting a supervisor\'s or admin\'s endorsement. You will receive an email notification once the endorsement is complete.'],
            ]);
        }
    
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
    protected function attemptLogin(Request $request)
    {
        //dd($request);
        $credentials = $this->credentials($request);

        // Find the user and check their status
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && $user->status === 'P') {
            return false; // Prevent login for pending users
        }

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

}
