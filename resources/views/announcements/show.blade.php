<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $announcement->title }} — The Muhsinat Club</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>:root{--teal:#1A6B72;--teal-dk:#0D3F44;--ivory:#FAF8F3;--gold:#C8A84B;--gold-lt:#E8CB7A}</style>
</head>
<body class="bg-[var(--ivory)] text-slate-800">
  <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
    <a href="{{ route('announcements.index') }}" class="text-[var(--teal)] hover:text-[var(--gold)] font-semibold">← Back to Announcements</a>

    <div class="rounded-xl bg-white p-5 shadow space-y-3">
      <h1 class="text-2xl font-semibold text-[var(--teal-dk)]">{{ $announcement->title }}</h1>
      @if($announcement->published_at)
        <p class="text-xs uppercase tracking-wider text-slate-500">{{ $announcement->published_at->format('D, M j, g:ia') }}</p>
      @endif
      <div class="prose max-w-none">{!! $announcement->body !!}</div>
    </div>
  </div>
</body>
</html>
