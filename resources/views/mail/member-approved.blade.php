<p>Assalamu alaykum {{ $user->name }},</p>
<p>Your membership has been approved. Welcome to The Muhsinat Club.</p>
<p style="margin-top:10px;">View your Legacy Member Card: <a href="{{ route('member-card') }}">{{ route('member-card') }}</a></p>
<p>Your membership number: <strong>{{ $user->membership_number }}</strong></p>
<p>You can now sign in and access your dashboard.</p>
<p>Jazakillahu khayran,</p>
<p>The Muhsinat Club</p>
