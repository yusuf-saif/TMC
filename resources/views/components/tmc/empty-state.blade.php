@props(['title' => 'Nothing here yet', 'body' => null, 'action' => null])
<div {{ $attributes->merge(['class' => 'text-center py-10 text-slate-500']) }}>
  <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 12h18"/><path d="M12 3v18"/></svg>
  </div>
  <h3 class="text-[var(--teal-dk)] font-semibold">{{ $title }}</h3>
  @if($body)
    <p class="text-sm mt-1">{{ $body }}</p>
  @endif
  @if($action)
    <div class="mt-3">{{ $action }}</div>
  @endif
</div>
