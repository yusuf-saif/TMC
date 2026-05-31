<x-member-layout>
  <div class="max-w-xl mx-auto">
    <x-tmc.page-header title="Membership Card" />

    <div id="card-wrap" class="w-full">
      <div id="member-card" class="rounded-xl overflow-hidden" style="background:var(--ivory); box-shadow:0 25px 60px rgba(26,107,114,.18)">
        <div class="p-6 sm:p-8" style="background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dk) 100%);">
          <div class="flex items-center gap-3">
            <img src="/images/img1.png" alt="TMC" class="w-10 h-10 object-contain"/>
            <div>
              <p class="text-white/80 text-xs uppercase tracking-[.2em]">The Muhsinat Club</p>
              <h1 class="text-white text-2xl sm:text-3xl font-semibold leading-tight">Legacy Member</h1>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-1 gap-4 text-[var(--ivory)]/95">
            <div>
              <p class="text-xs uppercase tracking-[.2em] text-white/70">Name</p>
              <p class="text-lg sm:text-xl font-medium">{{ Auth::user()->name }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs uppercase tracking-[.2em] text-white/70">Membership No.</p>
                <p class="text-lg sm:text-xl font-medium tracking-wider">{{ Auth::user()->membership_number ?? '—' }}</p>
              </div>
              <div>
                <p class="text-xs uppercase tracking-[.2em] text-white/70">Member Since</p>
                <p class="text-lg sm:text-xl font-medium">{{ optional(Auth::user()->approved_at ?? now())->format('F Y') }}</p>
              </div>
            </div>
          </div>
        </div>
        <div style="background: linear-gradient(90deg, var(--gold), var(--gold-lt)); height:4px;"></div>
        <div class="p-4 sm:p-6 bg-white">
          <p class="text-slate-600 text-sm">This digital card recognizes your legacy membership in The Muhsinat Club.</p>
        </div>
      </div>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row gap-3 justify-center">
      <button id="shareBtn" class="inline-flex justify-center items-center gap-2 px-4 py-3 rounded-md bg-[var(--gold)] text-[var(--teal-dk)] font-semibold hover:opacity-95">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7"/><path d="m16 6-4-4-4 4"/><path d="M12 2v14"/></svg>
        Share
      </button>
      <button id="downloadBtn" class="inline-flex justify-center items-center gap-2 px-4 py-3 rounded-md bg-[var(--teal)] text-white font-semibold hover:bg-[var(--teal-dk)]">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
        Download PNG
      </button>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
  <script>
    const shareBtn = document.getElementById('shareBtn');
    const dlBtn = document.getElementById('downloadBtn');
    const card = document.getElementById('member-card');

    shareBtn?.addEventListener('click', async () => {
      const name = @json(Auth::user()->name);
      const mem = @json(Auth::user()->membership_number);
      const url = @json(route('member-card'));
      const text = `Legacy Member — ${name}${mem ? ' — ' + mem : ''}`;

      if (navigator.share) {
        try {
          await navigator.share({ title: 'The Muhsinat Club', text, url });
        } catch (e) { /* dismissed */ }
      } else {
        try {
          await navigator.clipboard.writeText(url);
          alert('Link copied to clipboard');
        } catch (e) {
          alert('Share not supported on this device.');
        }
      }
    });

    dlBtn?.addEventListener('click', async () => {
      const canvas = await html2canvas(card, { backgroundColor: null, scale: 2 });
      const dataUrl = canvas.toDataURL('image/png');
      const a = document.createElement('a');
      a.href = dataUrl; a.download = 'tmc-legacy-card.png';
      document.body.appendChild(a); a.click(); a.remove();
    });
  </script>
</x-member-layout>
