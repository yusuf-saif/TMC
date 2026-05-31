<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'The Muhsinat Club') }}</title>
    <link rel="icon" type="image/png" href="/images/img1.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Dancing+Script:wght@400&family=Nunito:ital,wght@0,300;0,400;0,600;1,300&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      :root{ --teal:#1A6B72; --teal-dk:#0D3F44; --ivory:#FAF8F3; --gold:#C8A84B; --gold-lt:#E8CB7A; --ink:#1C1A17; }
      body{ background:var(--ivory); color:var(--ink); font-family:'Nunito', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
      .tmc-shadow{ box-shadow: 0 18px 50px rgba(26,107,114,.12); }
      .tmc-label{ letter-spacing:.18em; }
      .tmc-nav a{ color:#6b7280; }
      .tmc-nav a.active{ color:var(--teal); font-weight:700; }
    </style>
  </head>
  <body class="antialiased">
    <div class="min-h-screen bg-[var(--ivory)] pb-20">
      <!-- Top mini header -->
      <header class="px-4 py-3 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
          <img src="/images/img1.png" alt="TMC" class="w-7 h-7 object-contain"/>
          <span class="text-[var(--teal-dk)] font-semibold" style="font-family:'Dancing Script',cursive;">The Muhsinat Club</span>
        </a>
        <a href="{{ route('profile') }}" class="text-[var(--teal)] font-semibold">Profile</a>
        <div class="hidden"><livewire:layout.navigation /></div>
      </header>

      <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{ $slot }}
      </main>
    </div>

    <!-- Bottom Navigation -->
    <nav class="tmc-nav fixed bottom-0 inset-x-0 bg-white border-t border-slate-200">
      <div class="max-w-5xl mx-auto grid grid-cols-6 text-xs">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 9.5 12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg>
          <span>Home</span>
        </a>
        <a href="{{ route('events.index') }}" class="flex flex-col items-center py-2 {{ request()->is('events') || request()->is('events/*') ? 'active' : '' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M8 2v4M16 2v4M3 10h18M5 6h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/></svg>
          <span>Events</span>
        </a>
        <a href="{{ route('events.mine') }}" class="flex flex-col items-center py-2 {{ request()->routeIs('events.mine') ? 'active' : '' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
          <span>My</span>
        </a>
        <a href="{{ route('announcements.index') }}" class="flex flex-col items-center py-2 {{ request()->is('announcements') || request()->is('announcements/*') ? 'active' : '' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 10l6-6h6l6 6v10H3z"/></svg>
          <span>Updates</span>
        </a>
        <a href="{{ route('member-card') }}" class="flex flex-col items-center py-2 {{ request()->routeIs('member-card') ? 'active' : '' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 10h5"/><path d="M7 14h10"/></svg>
          <span>Card</span>
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center py-2 {{ request()->routeIs('profile') ? 'active' : '' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5z"/><path d="M3 21a9 9 0 0 1 18 0"/></svg>
          <span>Profile</span>
        </a>
      </div>
    </nav>
  </body>
</html>
