<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="font-pixel text-soil-dark" style="font-size: 12px; letter-spacing: 0.05em;">
            📧 VERIFY EMAIL
        </h2>
        <p class="font-sans text-soil text-sm mt-3 leading-relaxed">
            Terima kasih sudah daftar! Sebelum mulai, mohon verifikasi email-mu dengan
            klik link yang sudah kami kirim. Tidak menerima emailnya?
            Kami bisa kirim ulang.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 bg-grass-light/40 border border-grass/40 text-grass-dark font-sans text-sm px-3 py-2"
             style="border-radius: 4px;">
            ✓ Link verifikasi baru telah dikirim ke email-mu.
        </div>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full justify-center py-3">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-ghost w-full justify-center py-3">
                Logout
            </button>
        </form>
    </div>
</x-guest-layout>
