<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-sm bg-[var(--teal)] text-white hover:bg-[var(--teal-dk)] disabled:opacity-50 transition']) }}>
    {{ $slot }}
</button>
