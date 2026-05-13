<!DOCTYPE html>
@php
    $theme = Auth::check() ? Auth::user()->theme : 'light';
    $themeClass = $theme === 'dark' ? 'dark' : '';
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $themeClass }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SimpleNote</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: '#7494ec',
                        primaryDark: '#5f7fe0',
                        primaryLight: '#9eb4f5'
                    },
                    fontFamily: { sans: ['Inter'] }
                }
            }
        };
    </script>

    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .masonry-grid { column-count: 1; column-gap: 1.5rem; }
        @media (min-width: 768px) { .masonry-grid { column-count: 2; } }
        @media (min-width: 1280px) { .masonry-grid { column-count: 3; } }
        @media (min-width: 1536px) { .masonry-grid { column-count: 4; } }
        .masonry-item { break-inside: avoid; margin-bottom: 1.5rem; }
        .list-view { column-count: 1 !important; }
        .list-view .masonry-item { margin-bottom: 1rem; max-width: 850px; margin-left: auto; margin-right: auto; }
        body {
            background: radial-gradient(circle at top left, rgba(116,148,236,.08), transparent 25%),
                        radial-gradient(circle at bottom right, rgba(116,148,236,.05), transparent 25%);
        }
    </style>
</head>

<body class="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased font-sans">

    {{--top nabar--}}
    <header class="fixed top-0 w-full h-16 z-50 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
        <div class="h-full px-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary to-primaryDark text-white flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined">edit_note</span>
                </div>
                <h1 class="text-lg font-black tracking-tight text-slate-800 dark:text-white">SimpleNote</h1>
            </div>

            {{--tìm --}}
            <form action="{{ route('dashboard') }}" method="GET" class="hidden md:flex flex-1 max-w-2xl mx-10 relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input name="search" type="text" value="{{ request('search') }}" placeholder="Tìm kiếm ghi chú..." 
                       class="w-full pl-12 pr-5 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 outline-none focus:border-primary text-sm">
            </form>

            <div class="flex items-center gap-3 relative">
                <button onclick="document.getElementById('userMenu').classList.toggle('hidden')" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary to-primaryDark text-white flex items-center justify-center font-bold">
                        {{ substr(Auth::user()->display_name ?? Auth::user()->name, 0, 1) }}
                    </div>
                </button>
                <div id="userMenu" class="hidden absolute right-0 top-14 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-52 overflow-hidden z-50">
                    <a href="{{ route('settings.profile') }}" class="flex items-center gap-2 px-4 py-3 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                        <span class="material-symbols-outlined text-[18px]">settings</span> Cài đặt
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-3 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-slate-700">
                            <span class="material-symbols-outlined text-[18px]">logout</span> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex pt-16 h-screen overflow-hidden">
        {{--sidebar--}}
        <aside class="w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 hidden md:flex flex-col px-4 py-5 flex-shrink-0">
            <button onclick="openCreateForm()" class="w-full py-3 px-4 rounded-2xl bg-primary hover:bg-primaryDark text-white font-semibold text-sm flex items-center justify-center gap-2 shadow-lg mb-8">
                <span class="material-symbols-outlined text-[20px]">add</span> Ghi chú mới
            </button>

            <div class="space-y-1">
                <h3 class="text-[11px] uppercase tracking-[0.2em] text-slate-400 font-bold px-2 mb-3">Chính</h3>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <span class="material-symbols-outlined text-[20px]">grid_view</span> <span class="font-semibold text-sm">Tất cả ghi chú</span>
                </a>
            </div>

            <div class="mt-8 border-t border-slate-200 dark:border-slate-800 pt-6">
                <div class="flex items-center justify-between px-2 mb-3">
                    <h3 class="text-[11px] uppercase tracking-[0.2em] text-slate-400 font-bold">Nhãn</h3>
                    <button onclick="document.getElementById('addLabelModal').classList.remove('hidden')" class="text-slate-500 hover:text-primary">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </button>
                </div>

                {{--tag bên sidebar--}}
                <div class="space-y-1">
                    @foreach($labels ?? [] as $label)
                        <div class="group flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                            <a href="{{ route('dashboard', ['label' => $label->id]) }}" class="flex items-center gap-3 truncate flex-1">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $label->color }}"></span>
                                <span class="text-sm text-slate-700 dark:text-slate-200 truncate">{{ $label->name }}</span>
                            </a>
                            <button onclick="openEditLabelModal({{ $label->id }}, '{{ $label->name }}', '{{ $label->color }}')" class="hidden group-hover:block text-slate-400 hover:text-primary">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto p-5 md:p-8 bg-transparent">
            {{ $slot }}
        </main>
    </div>

    {{--các modal--}}
        <div id="addLabelModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="font-black text-lg mb-5 dark:text-white">Tạo nhãn mới</h3>
                <form action="{{ route('labels.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" required class="w-full border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-2xl p-3 mb-4 text-sm outline-none focus:ring-2 focus:ring-primary" placeholder="Tên nhãn...">
                    <div class="flex items-center gap-3 mb-5">
                        <label class="text-sm dark:text-slate-300">Màu nhãn:</label>
                        <input type="color" name="color" value="#7494ec" class="w-10 h-10 rounded-xl cursor-pointer border-0 p-0">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('addLabelModal').classList.add('hidden')" class="px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 rounded-xl">Hủy</button>
                        <button type="submit" class="px-5 py-2 text-sm bg-primary text-white rounded-xl shadow-lg">Thêm</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="editLabelModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="font-black text-lg mb-5 dark:text-white">Sửa nhãn</h3>
                <form id="editLabelForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" id="editLabelName" name="name" required class="w-full border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-2xl p-3 mb-4 text-sm outline-none focus:ring-2 focus:ring-primary">
                    <div class="flex items-center gap-3 mb-5">
                        <label class="text-sm dark:text-slate-300">Màu nhãn:</label>
                        <input type="color" id="editLabelColor" name="color" class="w-10 h-10 rounded-xl cursor-pointer border-0 p-0">
                    </div>
                    <div class="flex justify-between items-center">
                        <button type="button" onclick="deleteLabel()" class="text-sm text-red-500 hover:text-red-700 font-medium">Xóa nhãn</button>
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('editLabelModal').classList.add('hidden')" class="px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 rounded-xl">Hủy</button>
                            <button type="submit" class="px-5 py-2 text-sm bg-primary text-white rounded-xl shadow-lg">Lưu</button>
                        </div>
                    </div>
                </form>
                <form id="deleteLabelForm" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>

    <script>
        function openEditLabelModal(id, name, color) {
            document.getElementById('editLabelName').value = name;
            document.getElementById('editLabelColor').value = color;
            document.getElementById('editLabelForm').action = '/labels/' + id;
            document.getElementById('deleteLabelForm').action = '/labels/' + id;
            document.getElementById('editLabelModal').classList.remove('hidden');
        }

        document.addEventListener('click', (e) => {
            const menu = document.getElementById('userMenu');
            if (menu && !menu.classList.contains('hidden') && !e.target.closest('[onclick*="userMenu"]')) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>