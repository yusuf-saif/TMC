<p>Assalamu alaykum {{ $user->name }},</p>
<p>This is a reminder for <strong>{{ $event->title }}</strong> happening tomorrow.</p>
<p><strong>Date:</strong> {{ optional($event->event_date)->format('D, M j, g:ia') }}</p>
@if($event->location_type === 'online')
  <p><strong>Online:</strong> {{ $event->location_detail }}</p>
@elseif($event->location_type === 'in_person')
  <p><strong>Location:</strong> {{ $event->location_detail }}</p>
@else
  <p><strong>Hybrid:</strong> {{ $event->location_detail }}</p>
@endif
<p>We look forward to having you with us.</p>
<p>— The Muhsinat Club</p>
