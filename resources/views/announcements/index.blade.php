<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Announcements — The Muhsinat Club</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>:root{--teal:#1A6B72;--teal-dk:#0D3F44;--ivory:#FAF8F3;--gold:#C8A84B;--gold-lt:#E8CB7A}</style>
</head>
<body class="bg-[var(--ivory)] text-slate-800">
  <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-[var(--teal-dk)]">Announcements</h1>
      <a href="{{ route('dashboard') }}" class="text-[var(--teal)] hover:text-[var(--gold)] font-semibold">Dashboard</a>
    </div>

    @if($announcements->isEmpty())
      <p class="text-slate-600">No announcements yet.</p>
    @else
      <div class="space-y-4">
        @foreach($announcements as $a)
          <a href="{{ route('announcements.show',$a->slug) }}" class="block rounded-xl bg-white p-5 shadow hover:shadow-md transition">
            <h2 class="text-lg font-semibold text-[var(--teal-dk)]">{{ $a->title }}</h2>
            <p class="text-slate-600 text-sm mt-1">{{ Str::limit(strip_tags($a->body), 140) }}</p>
            @if($a->published_at)
              <p class="text-xs uppercase tracking-wider text-slate-500 mt-2">{{ $a->published_at->format('D, M j, g:ia') }}</p>
            @endif
          </a>
        @endforeach
      </div>

      <div class="mt-6">{{ $announcements->links() }}</div>
    @endif
  </div>
</body>
</html>
