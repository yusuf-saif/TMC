<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-sm bg-[var(--gold)] text-[var(--teal-dk)] hover:opacity-95 transition']) }}>
    {{ $slot }}
</button>
