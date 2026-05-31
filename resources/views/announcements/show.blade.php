<x-member-layout>
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
</x-member-layout>
