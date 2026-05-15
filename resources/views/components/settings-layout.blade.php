<x-app-layout>

<div class="max-w-6xl mx-auto p-4 md:p-8">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold dark:text-white">
            Cài đặt
        </h2>

        <p class="text-slate-500 mt-1">
            Quản lý tài khoản và giao diện hệ thống
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- SIDEBAR --}}
        <div class="lg:col-span-1">

            <div class="bg-white dark:bg-slate-800 rounded-2xl border dark:border-slate-700 overflow-hidden shadow-sm">

                <a href="{{ route('settings.profile') }}"
                   class="flex items-center gap-3 px-5 py-4 transition
                   {{ request()->routeIs('settings.profile') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-700 dark:text-white' }}">

                    <i class="bi bi-person"></i>
                    <span class="font-medium">Hồ sơ</span>
                </a>

                <a href="{{ route('settings.appearance') }}"
                   class="flex items-center gap-3 px-5 py-4 transition
                   {{ request()->routeIs('settings.appearance') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-700 dark:text-white' }}">

                    <i class="bi bi-palette"></i>
                    <span class="font-medium">Giao diện</span>
                </a>

                <a href="{{ route('settings.security') }}"
                   class="flex items-center gap-3 px-5 py-4 transition
                   {{ request()->routeIs('settings.security') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-700 dark:text-white' }}">

                    <i class="bi bi-shield-lock"></i>
                    <span class="font-medium">Bảo mật</span>
                </a>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="lg:col-span-3">

            {{-- CONTENT CARD WRAPPER (NEW) --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border dark:border-slate-700 shadow-sm p-6 md:p-8">

                {{ $slot }}

            </div>

        </div>

    </div>

</div>

</x-app-layout>