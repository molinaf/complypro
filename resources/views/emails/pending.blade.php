<p>A new user {{ $user->name }} has registered and is awaiting endorsement.</p>
<p><a href="{{ route('endorse.user', $user->id) }}">Click here to endorse</a>.</p>
