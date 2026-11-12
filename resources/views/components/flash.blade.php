@if (session('success'))
    <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-300 p-4 text-emerald-800 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <span class="font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 rounded-lg bg-red-50 border border-red-300 p-4 text-red-800 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <span class="font-bold">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif