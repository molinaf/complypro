@extends('layouts.app2')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Email Verification Sent</div>

                <div class="card-body">
                    <p>
                        A verification email has been sent to your email address. Please check your inbox and verify your email to activate your account.
                    </p>
                    <p>
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <input type="email" name="email" placeholder="Enter your email to resend verification" required>
                            <button type="submit">Resend Verification Email</button>
                        </form>
                        
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
