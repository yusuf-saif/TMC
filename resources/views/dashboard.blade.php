<x-app-layout>
    @php
      $user = Auth::user();
      $firstName = trim(explode(' ', $user->name ?? '')[0] ?? '');
      $memberSince = optional($user->approved_at ?? now())->format('F Y');

      $todaysReflection = \App\Models\DailyReflection::forTodayOrLatest();
      $upcomingEvents = [
        ['title' => 'Weekly Halaqah', 'date' => now()->addDays(2)->format('D, M j'), 'tag' => 'Gathering'],
        ['title' => 'Night of Dhikr', 'date' => now()->addDays(5)->format('D, M j'), 'tag' => 'Remembrance'],
        ['title' => "Sisters' Circle", 'date' => now()->addDays(8)->format('D, M j'), 'tag' => 'Community'],
      ];
      $announcement = [
        'title' => 'TMC Ramadan Prep Series',
        'body' => 'We are excited to share a series of gentle prompts and live sessions to help prepare hearts for Ramadan...'
      ];
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--teal-dk)] leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
      :root{ --teal:#1A6B72; --teal-dk:#0D3F44; --ivory:#FAF8F3; --gold:#C8A84B; --gold-lt:#E8CB7A; }
      .tmc-shadow{ box-shadow: 0 18px 50px rgba(26,107,114,.12); }
      .tmc-accent{ background: linear-gradient(90deg, var(--gold), var(--gold-lt)); height: 3px; }
      .tmc-label{ letter-spacing:.18em; }
    </style>

    <div class="py-6 sm:py-10 bg-[var(--ivory)]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Greeting -->
        <x-tmc.card class="tmc-shadow">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm text-[var(--teal)] tmc-label uppercase">Assalamu Alaikum</p>
              <h1 class="mt-1 text-2xl sm:text-3xl font-semibold text-[var(--teal-dk)]">Sister {{ $firstName }}</h1>
            </div>
            <img src="/images/img1.png" alt="TMC" class="w-10 h-10 object-contain" />
          </div>
        </x-tmc.card>

        <!-- Membership Summary -->
        <x-tmc.card title="Membership" class="tmc-shadow">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <p class="text-xs tmc-label uppercase text-slate-500">Membership Number</p>
              <p class="text-lg font-medium tracking-wide text-[var(--teal-dk)]">{{ $user->membership_number ?? '—' }}</p>
            </div>
            <div>
              <p class="text-xs tmc-label uppercase text-slate-500">Member Since</p>
              <p class="text-lg font-medium text-[var(--teal-dk)]">{{ $memberSince }}</p>
            </div>
            <div class="flex items-end sm:items-center">
              <a href="{{ route('member-card') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[var(--gold)] text-[var(--teal-dk)] font-semibold hover:opacity-95">
                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='1.75'><rect x='3' y='5' width='18' height='14' rx='2'/><path d='M7 10h5'/><path d='M7 14h10'/></svg>
                View Legacy Card
              </a>
            </div>
          </div>
        </x-tmc.card>

        <!-- Reflection of the Day -->
        <x-tmc.card title="Reflection of the Day" class="tmc-shadow">
          @if($todaysReflection)
            <div class="space-y-2">
              <p class="text-xs tmc-label uppercase text-slate-500">
                @switch($todaysReflection->type)
                  @case('quran') {{ "Qur'an" }} @break
                  @case('hadith') Hadith @break
                  @default Reflection
                @endswitch
              </p>
              <h3 class="text-lg font-semibold text-[var(--teal-dk)]">{{ $todaysReflection->title }}</h3>
              <div class="prose max-w-none prose-sm sm:prose" style="color:#374151;">
                {!! nl2br(e($todaysReflection->body)) !!}
              </div>
              @if($todaysReflection->source)
                <p class="text-slate-500 text-sm">Source: {{ $todaysReflection->source }}</p>
              @endif
            </div>
          @else
            <p class="text-slate-500 text-sm">No reflection available today. Check back soon, sister.</p>
          @endif
        </x-tmc.card>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Upcoming Events -->
          <x-tmc.card title="Upcoming Events" class="tmc-shadow lg:col-span-2">
            @if(!empty($upcomingEvents))
              <ul class="divide-y divide-slate-200/70">
                @foreach($upcomingEvents as $event)
                  <li class="py-3 flex items-center justify-between">
                    <div>
                      <p class="text-[var(--teal-dk)] font-medium">{{ $event['title'] }}</p>
                      <p class="text-xs text-slate-500 tmc-label uppercase">{{ $event['tag'] }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-md bg-[var(--ivory)] border border-slate-200 text-[var(--teal)] text-sm font-semibold">{{ $event['date'] }}</span>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="text-slate-500 text-sm">No upcoming events yet.</p>
            @endif
          </x-tmc.card>

          <!-- Latest Announcement -->
          <x-tmc.card title="Latest Announcement" class="tmc-shadow">
            @if($announcement)
              <h3 class="text-[var(--teal-dk)] font-semibold">{{ $announcement['title'] }}</h3>
              <p class="mt-1 text-slate-600 text-sm leading-6">{{ $announcement['body'] }}</p>
            @else
              <p class="text-slate-500 text-sm">No announcements right now.</p>
            @endif
          </x-tmc.card>
        </div>

        <!-- Support TMC -->
        <x-tmc.card title="Support TMC" class="tmc-shadow">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="#" class="rounded-lg border border-slate-200 p-4 hover:border-[var(--gold)] hover:shadow-sm transition">
              <p class="text-[var(--teal-dk)] font-semibold">Volunteer</p>
              <p class="text-slate-600 text-sm mt-1">Offer time or skills to help sisters across the community.</p>
            </a>
            <a href="#" class="rounded-lg border border-slate-200 p-4 hover:border-[var(--gold)] hover:shadow-sm transition">
              <p class="text-[var(--teal-dk)] font-semibold">Mentorship</p>
              <p class="text-slate-600 text-sm mt-1">Support a sister’s growth with gentle guidance and care.</p>
            </a>
            <a href="#" class="rounded-lg border border-slate-200 p-4 hover:border-[var(--gold)] hover:shadow-sm transition">
              <p class="text-[var(--teal-dk)] font-semibold">Donate</p>
              <p class="text-slate-600 text-sm mt-1">Give in support of acts that beautify hearts and homes.</p>
            </a>
          </div>
        </x-tmc.card>
      </div>
    </div>
</x-app-layout>
