<x-member-layout>
  <div class="max-w-3xl mx-auto">
    <x-tmc.page-header title="Profile" />

    <div class="rounded-xl bg-white p-5 shadow space-y-3">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <p class="text-xs uppercase tracking-wider text-slate-500">Name</p>
          <p class="font-semibold text-[var(--teal-dk)]">{{ Auth::user()->name }}</p>
        </div>
        <div>
          <p class="text-xs uppercase tracking-wider text-slate-500">Email</p>
          <p class="font-semibold text-[var(--teal-dk)]">{{ Auth::user()->email }}</p>
        </div>
        <div>
          <p class="text-xs uppercase tracking-wider text-slate-500">Membership No.</p>
          <p class="font-semibold text-[var(--teal-dk)]">{{ Auth::user()->membership_number ?? '—' }}</p>
        </div>
        <div>
          <p class="text-xs uppercase tracking-wider text-slate-500">Member Since</p>
          <p class="font-semibold text-[var(--teal-dk)]">{{ optional(Auth::user()->approved_at)->format('F Y') ?? '—' }}</p>
        </div>
        <div>
          <p class="text-xs uppercase tracking-wider text-slate-500">Status</p>
          <x-tmc.badge :color="match(Auth::user()->status){'approved'=>'teal','pending'=>'gray','rejected'=>'gray','suspended'=>'gray',default=>'gray'}">{{ Str::of(Auth::user()->status)->headline() }}</x-tmc.badge>
        </div>
      </div>
      <div class="pt-2 flex gap-3">
        <a href="{{ route('member-card') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[var(--teal)] text-white font-semibold hover:bg-[var(--teal-dk)]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 10h5"/><path d="M7 14h10"/></svg>
          View Legacy Card
        </a>
        @if (Route::has('logout'))
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[var(--gold)] text-[var(--teal-dk)] font-semibold hover:opacity-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/></svg>
            Logout
          </button>
        </form>
        @endif
      </div>
    </div>

    <div class="mt-6 space-y-6">
      <div class="p-4 sm:p-6 bg-white shadow rounded-xl">
        <div class="max-w-xl">
          <livewire:profile.update-profile-information-form />
        </div>
      </div>
      <div class="p-4 sm:p-6 bg-white shadow rounded-xl">
        <div class="max-w-xl">
          <livewire:profile.update-password-form />
        </div>
      </div>
      <div class="p-4 sm:p-6 bg-white shadow rounded-xl">
        <div class="max-w-xl">
          <livewire:profile.delete-user-form />
        </div>
      </div>
    </div>
  </div>
</x-member-layout>
