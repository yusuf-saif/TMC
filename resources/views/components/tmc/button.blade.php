@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])
@php
  $base = 'inline-flex items-center gap-2 px-4 py-2 rounded-md font-semibold transition';
  $styles = [
    'primary' => 'bg-[var(--gold)] text-[var(--teal-dk)] hover:opacity-95',
    'secondary' => 'bg-[var(--teal)] text-white hover:bg-[var(--teal-dk)]',
    'ghost' => 'bg-transparent text-white border border-white/80 hover:border-white',
  ][$variant] ?? 'bg-[var(--gold)] text-[var(--teal-dk)] hover:opacity-95';
  $class = $base.' '.$styles;
@endphp
@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
@endif
