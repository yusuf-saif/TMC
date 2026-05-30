<p>Assalamu alaykum {{ $user->name }},</p>
<p>Your RSVP for <strong>{{ $event->title }}</strong> is confirmed.</p>
<p><strong>Date:</strong> {{ optional($event->event_date)->format('D, M j, g:ia') }}</p>
@if($event->location_type === 'online')
  <p><strong>Online:</strong> {{ $event->location_detail }}</p>
@elseif($event->location_type === 'in_person')
  <p><strong>Location:</strong> {{ $event->location_detail }}</p>
@else
  <p><strong>Hybrid:</strong> {{ $event->location_detail }}</p>
@endif
<p>Jazakillahu khayran,</p>
<p>The Muhsinat Club</p>
