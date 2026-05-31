<x-app-layout>
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
            <x-tmc.button variant="secondary">Cancel RSVP</x-tmc.button>
          </form>
        @else
          @if($event->status === 'cancelled')
            <x-tmc.badge color="gray">Cancelled</x-tmc.badge>
          @elseif($event->isFull())
            <x-tmc.badge color="gray">Full</x-tmc.badge>
          @else
            <form method="POST" action="{{ route('events.rsvp',$event->slug) }}" class="inline-block">
              @csrf
              <x-tmc.button>RSVP</x-tmc.button>
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
</x-app-layout>
