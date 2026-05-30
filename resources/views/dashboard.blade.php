<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <p>{{ __("You're logged in!") }}</p>
                    @if(Auth::user()->status === 'approved')
                        <a href="{{ route('member-card') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-amber-500 text-white font-semibold hover:bg-amber-600">
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='1.75'><rect x='3' y='5' width='18' height='14' rx='2'/><path d='M7 10h5'/><path d='M7 14h10'/></svg>
                            View Legacy Card
                        </a>
                    @endif
                </div
            </div>
        </div>
    </div>
</x-app-layout>
