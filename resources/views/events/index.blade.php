<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Events — The Muhsinat Club</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>:root{--teal:#1A6B72;--teal-dk:#0D3F44;--ivory:#FAF8F3;--gold:#C8A84B;--gold-lt:#E8CB7A}</style>
</head>
<body class="bg-[var(--ivory)] text-slate-800">
  <div class="max-w-5xl mx-auto px-4 py-6 space-y-8">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-[var(--teal-dk)]">Events</h1>
      <a href="{{ route('dashboard') }}" class="text-[var(--teal)] hover:text-[var(--gold)] font-semibold">Dashboard</a>
    </div>

    <section>
      <h2 class="text-sm uppercase tracking-wider text-slate-500">Upcoming Events</h2>
      @if($upcoming->isEmpty())
        <p class="mt-2 text-slate-600">No upcoming events yet.</p>
      @else
        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($upcoming as $e)
            <a href="{{ route('events.show',$e->slug) }}" class="rounded-xl bg-white p-4 shadow hover:shadow-md transition">
              <p class="text-xs uppercase tracking-wider text-slate-500">{{ $e->event_date->format('D, M j, g:ia') }}</p>
              <h3 class="mt-1 font-semibold text-[var(--teal-dk)]">{{ $e->title }}</h3>
              @if($e->speaker_name)
                <p class="text-sm text-slate-600">Speaker: {{ $e->speaker_name }}</p>
              @endif
            </a>
          @endforeach
        </div>
      @endif
    </section>

    <section>
      <h2 class="text-sm uppercase tracking-wider text-slate-500">Past Events</h2>
      @if($past->isEmpty())
        <p class="mt-2 text-slate-600">No past events.</p>
      @else
        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($past as $e)
            <div class="rounded-xl bg-white p-4 shadow">
              <p class="text-xs uppercase tracking-wider text-slate-500">{{ $e->event_date->format('D, M j, g:ia') }}</p>
              <h3 class="mt-1 font-semibold text-[var(--teal-dk)]">{{ $e->title }}</h3>
            </div>
          @endforeach
        </div>
      @endif
    </section>
  </div>
</body>
</html>
