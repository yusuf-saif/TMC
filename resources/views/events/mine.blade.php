<x-member-layout>
  <div class="max-w-5xl mx-auto px-4 py-6 space-y-8">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-[var(--teal-dk)]">My Events</h1>
      <a href="{{ route('events.index') }}" class="text-[var(--teal)] hover:text-[var(--gold)] font-semibold">Browse Events</a>
    </div>

    <section>
      <h2 class="text-sm uppercase tracking-wider text-slate-500">Upcoming RSVPs</h2>
      @if($upcoming->isEmpty())
        <x-tmc.empty-state title="No upcoming RSVPs" body="RSVP to an event to see it here." class="mt-2" />
      @else
        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($upcoming as $rsvp)
            <a href="{{ route('events.show',$rsvp->event->slug) }}" class="rounded-xl bg-white p-4 shadow hover:shadow-md transition">
              <p class="text-xs uppercase tracking-wider text-slate-500">{{ $rsvp->event->event_date->format('D, M j, g:ia') }}</p>
              <h3 class="mt-1 font-semibold text-[var(--teal-dk)]">{{ $rsvp->event->title }}</h3>
            </a>
          @endforeach
        </div>
      @endif
    </section>

    <section>
      <h2 class="text-sm uppercase tracking-wider text-slate-500">Past RSVPs</h2>
      @if($past->isEmpty())
        <x-tmc.empty-state title="No past RSVPs" class="mt-2" />
      @else
        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($past as $rsvp)
            <div class="rounded-xl bg-white p-4 shadow">
              <p class="text-xs uppercase tracking-wider text-slate-500">{{ $rsvp->event->event_date->format('D, M j, g:ia') }}</p>
              <h3 class="mt-1 font-semibold text-[var(--teal-dk)]">{{ $rsvp->event->title }}</h3>
            </div>
          @endforeach
        </div>
      @endif
    </section>
  </div>
</x-member-layout>
