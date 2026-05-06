<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SimpleNote</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" rel="stylesheet"/>

    {{-- Tailwind CDN (giữ nguyên như dự án gốc) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-['Inter'] antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ════════════════════════════════════════════════════════
         SIDEBAR
    ═════════════════════════════════════════════════════════ --}}
    <aside class="w-60 bg-white border-r border-slate-100 hidden md:flex flex-col p-4 space-y-6 flex-shrink-0">

        {{-- Brand --}}
        <div class="flex items-center gap-2 px-1 pt-2">
            <span class="material-symbols-outlined text-blue-600 text-2xl">description</span>
            <span class="text-lg font-black text-slate-900 tracking-tight">SimpleNote</span>
        </div>

        {{-- New Note Button --}}
        <button onclick="openCreateForm()"
                class="w-full py-2.5 px-4 bg-gradient-to-br from-blue-600 to-blue-500 text-white rounded-xl
                       font-semibold text-sm shadow-md shadow-blue-200 hover:shadow-lg transition-all
                       flex items-center justify-center gap-2 group active:scale-95">
            <span class="material-symbols-outlined text-lg group-hover:rotate-90 transition-transform">add</span>
            Ghi chú mới
        </button>

        {{-- Navigation --}}
        <nav class="space-y-1 flex-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 text-slate-700 bg-blue-50 text-blue-700 rounded-lg font-medium text-sm">
                <span class="material-symbols-outlined">description</span>
                Tất cả ghi chú
            </a>
            <a href="#"
               class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-slate-50 rounded-lg font-medium text-sm transition-all">
                <span class="material-symbols-outlined">push_pin</span>
                Đã ghim
            </a>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-slate-50 rounded-lg font-medium text-sm transition-all">
                <span class="material-symbols-outlined">settings</span>
                Cài đặt
            </a>
        </nav>

        {{-- Logout --}}
        <div class="pt-4 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors text-sm font-medium">
                    <span class="material-symbols-outlined">logout</span>
                    Đăng xuất
                </button>
            </form>
        </div>
    </aside>

    {{-- ════════════════════════════════════════════════════════
         MAIN AREA
    ═════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Header --}}
        <header class="h-14 bg-white border-b border-slate-100 flex items-center justify-between px-4 md:px-8 flex-shrink-0 gap-4">

            {{-- Mobile brand --}}
            <span class="material-symbols-outlined text-blue-600 md:hidden">description</span>

            {{-- Search --}}
            <div class="flex-1 max-w-xl relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input data-search-input
                       type="text"
                       placeholder="Tìm kiếm ghi chú..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-100 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"/>
            </div>

            {{-- User Avatar --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs uppercase flex-shrink-0">
                        {{ substr(Auth::user()->display_name, 0, 1) }}
                    </div>
                    <span class="hidden sm:block font-medium">{{ Auth::user()->display_name }}</span>
                </a>
            </div>
        </header>

        {{-- Account NOT Activated Banner --}}
        @if(Auth::check() && !Auth::user()->is_activated)
            <div class="bg-amber-50 border-b border-amber-200 px-4 py-2.5 text-center text-sm text-amber-700 font-medium flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">warning</span>
                Tài khoản chưa xác thực! Kiểm tra email <strong class="ml-1">{{ Auth::user()->email }}</strong> để kích hoạt.
            </div>
        @endif

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
