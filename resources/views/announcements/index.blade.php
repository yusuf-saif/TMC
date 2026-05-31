<x-member-layout>
  <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
    <x-tmc.page-header title="Announcements" />

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
</x-member-layout>
