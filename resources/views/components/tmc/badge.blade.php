@props(['color' => 'teal'])
@php
$classes = [
  'teal' => 'bg-[var(--ivory)] text-[var(--teal)] border border-[var(--teal)]/30',
  'gold' => 'bg-[var(--gold)] text-[var(--teal-dk)]',
  'gray' => 'bg-slate-100 text-slate-700',
][$color] ?? 'bg-[var(--ivory)] text-[var(--teal)] border border-[var(--teal)]/30';
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold '.$classes]) }}>
  {{ $slot }}
</span>
