<x-app-layout>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">
            Cài đặt
        </h2>

        <p class="text-muted mb-0">
            Quản lý tài khoản và giao diện hệ thống
        </p>
    </div>

    <div class="row g-4">

        {{-- SIDEBAR --}}
        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                <div class="list-group list-group-flush">

                    {{-- PROFILE --}}
                    <a href="{{ route('settings.profile') }}"
                       class="list-group-item list-group-item-action border-0 py-3 px-4
                       {{ request()->routeIs('settings.profile') ? 'active' : '' }}">

                        <i class="bi bi-person me-2"></i>
                        Hồ sơ
                    </a>

                    {{-- APPEARANCE --}}
                    <a href="{{ route('settings.appearance') }}"
                       class="list-group-item list-group-item-action border-0 py-3 px-4
                       {{ request()->routeIs('settings.appearance') ? 'active' : '' }}">

                        <i class="bi bi-palette me-2"></i>
                        Giao diện
                    </a>

                    {{-- SECURITY --}}
                    <a href="{{ route('settings.security') }}"
                       class="list-group-item list-group-item-action border-0 py-3 px-4
                       {{ request()->routeIs('settings.security') ? 'active' : '' }}">

                        <i class="bi bi-shield-lock me-2"></i>
                        Bảo mật
                    </a>

                </div>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="col-lg-9">
            {{ $slot }}
        </div>

    </div>

</div>

</x-app-layout>