<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
    use Illuminate\Support\Facades\Mail;

    public function resend(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->status !== 'P') {
            return redirect()->back()->with('error', 'Invalid or already verified account.');
        }

        $verificationUrl = route('email.verify', ['id' => $user->id, 'hash' => sha1($user->email)]);
        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));

        return redirect()->route('verification.notice')->with('success', 'Verification email resent.');
    }

}
