<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $event->title }} — The Muhsinat Club</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>:root{--teal:#1A6B72;--teal-dk:#0D3F44;--ivory:#FAF8F3;--gold:#C8A84B;--gold-lt:#E8CB7A}</style>
</head>
<body class="bg-[var(--ivory)] text-slate-800">
  <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
    <a href="{{ route('events.index') }}" class="text-[var(--teal)] hover:text-[var(--gold)] font-semibold">← Back to Events</a>

    <div class="rounded-xl bg-white p-5 shadow space-y-2">
      <p class="text-xs uppercase tracking-wider text-slate-500">
        {{ $event->event_date->format('D, M j, g:ia') }}
        @if($event->end_date) — {{ $event->end_date->format('g:ia') }} @endif
      </p>
      <h1 class="text-2xl font-semibold text-[var(--teal-dk)]">{{ $event->title }}</h1>
      @if($event->speaker_name)
        <p class="text-slate-600">Speaker: {{ $event->speaker_name }}</p>
      @endif
      <p class="text-slate-600">
        @switch($event->location_type)
          @case('online') Online: {{ $event->location_detail }} @break
          @case('in_person') Location: {{ $event->location_detail }} @break
          @default Hybrid: {{ $event->location_detail }}
        @endswitch
      </p>
      <div class="prose max-w-none">{!! $event->description !!}</div>

      <div class="pt-3">
        @if($userRsvp && $userRsvp->status === 'registered')
          <form method="POST" action="{{ route('events.rsvp.cancel',$event->slug) }}" class="inline-block">
            @csrf
            <button class="px-4 py-2 rounded-md bg-slate-100 border hover:bg-slate-200">Cancel RSVP</button>
          </form>
        @else
          @if($event->status === 'cancelled')
            <span class="px-3 py-1 rounded bg-red-100 text-red-700 font-semibold">Cancelled</span>
          @elseif($event->isFull())
            <span class="px-3 py-1 rounded bg-slate-100 text-slate-700 font-semibold">Full</span>
          @else
            <form method="POST" action="{{ route('events.rsvp',$event->slug) }}" class="inline-block">
              @csrf
              <button class="px-4 py-2 rounded-md bg-[var(--gold)] text-[var(--teal-dk)] font-semibold hover:opacity-95">RSVP</button>
            </form>
          @endif
        @endif
      </div>

      @if (session('status'))
        <p class="text-green-700 text-sm mt-2">{{ session('status') }}</p>
      @endif
      @if (session('error'))
        <p class="text-red-700 text-sm mt-2">{{ session('error') }}</p>
      @endif
    </div>
  </div>
</body>
</html>
