<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\VerifyEmail;
use App\Mail\PendingUserNotification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default, this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function resendVerification(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->email_verified_at) {
            return redirect()->back()->with('error', 'Invalid email or already verified.');
        }

        $verificationUrl = route('email.verify', ['id' => $user->id, 'hash' => sha1($user->email)]);
        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));

        return redirect()->route('email.sent')->with('success', 'Verification email resent.');
    }

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form with the list of companies.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $companies = Company::all();
        return view('auth.register', compact('companies'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_id' => ['required'], // Ensure a company is selected
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'U',
            'status' => 'P',
            'company_id' => $data['company_id'] !== 'no_company' ? $data['company_id'] : null,
        ]);
    
        // Send verification email
        $verificationUrl = route('email.verify', ['id' => $user->id, 'hash' => sha1($user->email)]);
        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));
    
        return $user;
    }
    
    protected function registered(Request $request, $user)
    {
        {
        // Log the user out immediately after registration
        auth()->logout();

        // Redirect to a custom "email sent" view
        return redirect()->route('verification.notice');
        }
    }   

    /**
     * Verify email through the one-time link.
     *
     * @param int $id
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail($id, $hash)
    {
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash) {
            abort(403, 'Invalid verification link.');
        }
        $user->email_verified_at = now();
        $user->save();
        // Notify supervisors or admin
        if ($user->company_id) {
            $supervisors = User::where('role', 'S')->where('company_id', $user->company_id)->get();
            foreach ($supervisors as $supervisor) {
                Mail::to($supervisor->email)->send(new PendingUserNotification($user));
            }
        } else {
            $admins = User::where('role', 'A')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new PendingUserNotification($user));
            }
        }

        return redirect()->route('login')->with('success', 'Email verified successfully.');
    }
}
