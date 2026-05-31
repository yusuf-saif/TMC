@props(['title' => null, 'subtitle' => null, 'actions' => null])
<div class="flex items-start justify-between">
  <div>
    @if($subtitle)
      <p class="text-sm text-[var(--teal)] tmc-label uppercase">{{ $subtitle }}</p>
    @endif
    @if($title)
      <h1 class="mt-1 text-2xl sm:text-3xl font-semibold text-[var(--teal-dk)]" style="font-family:'Dancing Script',cursive;">{{ $title }}</h1>
    @endif
  </div>
  @if($actions)
    <div class="flex items-center gap-2">{{ $actions }}</div>
  @endif
</div>
