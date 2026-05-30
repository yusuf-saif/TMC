@props(['title' => null, 'actions' => null, 'class' => ''])
<div {{ $attributes->merge(['class' => "rounded-xl overflow-hidden bg-white tmc-shadow {$class}"]) }}>
  @if($title || $actions)
    <div class="flex items-center justify-between px-4 sm:px-5 py-3 border-b border-slate-200/70 bg-[var(--ivory)]">
      @if($title)
        <h3 class="text-sm font-semibold tracking-wide text-[var(--teal-dk)] uppercase">{{ $title }}</h3>
      @else
        <span></span>
      @endif
      @if($actions)
        <div class="flex items-center gap-2">{{ $actions }}</div>
      @endif
    </div>
  @endif
  <div class="p-4 sm:p-5">
    {{ $slot }}
  </div>
</div>
