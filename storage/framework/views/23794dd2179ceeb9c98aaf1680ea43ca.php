<!DOCTYPE html>
<?php
    $theme = Auth::check() ? Auth::user()->theme : 'light';
    $themeClass = $theme === 'dark' ? 'dark' : '';
?>

<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="<?php echo e($themeClass); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

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
                    fontFamily: {
                        sans: ['Inter']
                    }
                }
            }
        };
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24;
        }

        .masonry-grid {
            column-count: 1;
            column-gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .masonry-grid {
                column-count: 2;
            }
        }

        @media (min-width: 1280px) {
            .masonry-grid {
                column-count: 3;
            }
        }

        @media (min-width: 1536px) {
            .masonry-grid {
                column-count: 4;
            }
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }

        .list-view {
            column-count: 1 !important;
        }

        .list-view .masonry-item {
            margin-bottom: 1rem;
            max-width: 850px;
            margin-left: auto;
            margin-right: auto;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(116,148,236,.08), transparent 25%),
                radial-gradient(circle at bottom right, rgba(116,148,236,.05), transparent 25%);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.4);
            border-radius: 999px;
        }
    </style>
</head>

<body class="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased font-sans">


<header class="fixed top-0 w-full h-16 z-50 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">

    <div class="h-full px-5 flex items-center justify-between">

        
        <div class="flex items-center gap-3">

            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary to-primaryDark text-white flex items-center justify-center shadow-lg shadow-[rgba(116,148,236,0.35)]">
                <span class="material-symbols-outlined">edit_note</span>
            </div>

            <div>
                <h1 class="text-lg font-black tracking-tight text-slate-800 dark:text-white">
                    SimpleNote
                </h1>
            </div>
        </div>

        
        <div class="hidden md:flex flex-1 max-w-2xl mx-10 relative">

            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                search
            </span>

            <input
                data-search-input
                type="text"
                placeholder="Tìm kiếm ghi chú..."
                class="w-full pl-12 pr-5 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:ring-4 focus:ring-[rgba(116,148,236,.20)] focus:border-primary outline-none transition-all text-sm dark:text-white"
            >
        </div>

        
        <div class="flex items-center gap-3 relative">

            <button onclick="document.getElementById('userMenu').classList.toggle('hidden')" class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary to-primaryDark text-white flex items-center justify-center font-bold shadow-lg shadow-[rgba(116,148,236,0.25)]">
                    <?php echo e(substr(Auth::user()->display_name ?? 'U', 0, 1)); ?>

                </div>
            </button>

            
            <div id="userMenu" class="hidden absolute right-0 top-14 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-52 overflow-hidden z-50">

                <a href="<?php echo e(route('settings.profile')); ?>"
                   class="flex items-center gap-2 px-4 py-3 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">

                    <span class="material-symbols-outlined text-[18px]">settings</span>
                    Cài đặt
                </a>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>

                    <button type="submit"
                            class="w-full text-left flex items-center gap-2 px-4 py-3 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-slate-700 transition-all">

                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>


<div class="flex pt-16 h-screen overflow-hidden">

    
    <aside class="w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 hidden md:flex flex-col px-4 py-5 flex-shrink-0 overflow-y-auto">

        
        <div class="mb-6 px-2">
            <h2 class="text-lg font-black tracking-wide text-slate-800 dark:text-white">
                SimpleNote
            </h2>
        </div>

        
        <button
            onclick="document.getElementById('createNoteModal').classList.remove('hidden')"
            class="w-full py-3 px-4 rounded-2xl bg-primary hover:bg-primaryDark transition-all text-white font-semibold text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">

            <span class="material-symbols-outlined text-[20px]">add</span>
            Ghi chú mới
        </button>

        
        <div class="mt-8">

            <h3 class="text-[11px] uppercase tracking-[0.2em] text-slate-400 font-bold px-2 mb-3">
                Chính
            </h3>

            <div class="space-y-1">

                
            <a href="<?php echo e(route('dashboard')); ?>"
            onclick="if(typeof filterByLabel === 'function') { event.preventDefault(); filterByLabel('all'); }"
            class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">

                <div class="flex items-center gap-3">

                    <span class="material-symbols-outlined text-[20px]">
                        grid_view
                    </span>

                    <span class="font-semibold text-sm">
                        Tất cả ghi chú
                    </span>
                </div>
            </a>

                
                <button
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">

                    <div class="flex items-center gap-3">

                        <span class="material-symbols-outlined text-[20px]">
                            group
                        </span>

                        <span class="font-medium text-sm">
                            Được chia sẻ
                        </span>
                    </div>
                </button>
                
            </div>
        </div>

        
        <div class="mt-8 border-t border-slate-200 dark:border-slate-800 pt-6">

            <div class="flex items-center justify-between px-2 mb-3">

                <h3 class="text-[11px] uppercase tracking-[0.2em] text-slate-400 font-bold">
                    Nhãn
                </h3>

                <button
                    onclick="document.getElementById('addLabelModal').classList.remove('hidden')"
                    class="text-slate-500 hover:text-primary transition-all">

                    <span class="material-symbols-outlined text-[18px]">
                        add
                    </span>
                </button>
            </div>

            
            <div class="space-y-1" id="sidebarLabelList"></div>
        </div>
    </aside>

    
    <main class="flex-1 overflow-y-auto p-5 md:p-8 bg-transparent">

        

        <?php echo e($slot); ?>

    </main>
</div>


<div id="createNoteModal" class="hidden fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">

    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">

        
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800">

            <div>
                <h2 class="text-xl font-black text-slate-800 dark:text-white">
                    Tạo ghi chú mới
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Viết nhanh ý tưởng của bạn ✨
                </p>
            </div>

            <button
                onclick="document.getElementById('createNoteModal').classList.add('hidden')"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-all">

                <span class="material-symbols-outlined text-slate-500">
                    close
                </span>
            </button>
        </div>

        
        <div class="p-6">

            <?php if(View::exists('notes.partials.create-form')): ?>
                <?php echo $__env->make('notes.partials.create-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

        </div>
    </div>
</div>


<div id="addLabelModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700">

        <h3 class="font-black text-lg mb-5 dark:text-white">
            Tạo Nhãn Mới
        </h3>

        <form action="<?php echo e(route('labels.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <input
                type="text"
                name="name"
                required
                class="w-full border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-2xl p-3 mb-4 text-sm outline-none focus:ring-4 focus:ring-[rgba(116,148,236,.25)]"
                placeholder="Tên nhãn..."
            >

            <div class="flex items-center gap-3 mb-5">
                <label class="text-sm dark:text-slate-300">
                    Màu nhãn:
                </label>

                <input type="color" name="color" value="#7494ec" class="w-10 h-10 rounded-xl cursor-pointer border-0 p-0">
            </div>

            <div class="flex justify-end gap-2">

                <button
                    type="button"
                    onclick="document.getElementById('addLabelModal').classList.add('hidden')"
                    class="px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl">

                    Hủy
                </button>

                <button
                    type="submit"
                    class="px-5 py-2 text-sm bg-primary hover:bg-primaryDark text-white rounded-xl shadow-lg shadow-[rgba(116,148,236,.25)] transition-all">

                    Thêm
                </button>
            </div>
        </form>
    </div>
</div>


<div id="editLabelModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700">

        <h3 class="font-black text-lg mb-5 dark:text-white">
            Sửa Nhãn
        </h3>

        <form id="editLabelForm" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <input
                type="text"
                id="editLabelName"
                name="name"
                required
                class="w-full border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-2xl p-3 mb-4 text-sm outline-none focus:ring-4 focus:ring-[rgba(116,148,236,.25)]"
            >

            <div class="flex items-center gap-3 mb-5">

                <label class="text-sm dark:text-slate-300">
                    Màu nhãn:
                </label>

                <input type="color" id="editLabelColor" name="color" class="w-10 h-10 rounded-xl cursor-pointer border-0 p-0">
            </div>

            <div class="flex justify-between items-center">

                <button type="button"
                        onclick="deleteLabel()"
                        class="text-sm text-red-500 hover:text-red-700 font-medium">

                    Xóa nhãn
                </button>

                <div class="flex gap-2">

                    <button
                        type="button"
                        onclick="document.getElementById('editLabelModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl">

                        Hủy
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2 text-sm bg-primary hover:bg-primaryDark text-white rounded-xl shadow-lg shadow-[rgba(116,148,236,.25)] transition-all">

                        Lưu
                    </button>
                </div>
            </div>
        </form>

        <form id="deleteLabelForm" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>
    </div>
</div>


<script>

    // Đóng user menu
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('userMenu');

        if (
            menu &&
            !menu.classList.contains('hidden') &&
            !e.target.closest('[onclick*="userMenu"]')
        ) {
            menu.classList.add('hidden');
        }
    });

    // Đóng modal khi click nền
    ['addLabelModal', 'editLabelModal', 'createNoteModal'].forEach(id => {
        const el = document.getElementById(id);

        if (el) {
            el.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }
    });

    // Mở modal sửa label
    function openEditLabelModal(id, name, color) {
        document.getElementById('editLabelName').value = name;
        document.getElementById('editLabelColor').value = color;

        document.getElementById('editLabelForm').action = `/labels/${id}`;
        document.getElementById('deleteLabelForm').action = `/labels/${id}`;

        document.getElementById('editLabelModal').classList.remove('hidden');
    }

    // Xóa label
    function deleteLabel() {
        if (confirm('Bạn có chắc chắn muốn xóa nhãn này?')) {
            document.getElementById('deleteLabelForm').submit();
        }
    }

    <?php if(session('error')): ?>
        alert('<?php echo e(session('error')); ?>');
    <?php endif; ?>

    // Render labels sidebar
    document.addEventListener('DOMContentLoaded', () => {

        if (window.NoteLabels && window.NoteLabels.length > 0) {

            const container = document.getElementById('sidebarLabelList');

            if (container) {

                container.innerHTML = window.NoteLabels.map(label => `
                    <div class="group flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">

                        <label class="flex items-center gap-3 cursor-pointer truncate flex-1 min-w-0">

                            <input
                                type="checkbox"
                                value="${label.id}"
                                onchange="if(typeof toggleLabelFilter === 'function') toggleLabelFilter(this)"
                                class="hidden"
                            >

                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                                style="background-color: ${label.color}">
                            </span>

                            <span class="text-sm text-slate-700 dark:text-slate-200 truncate">
                                ${label.name}
                            </span>
                        </label>

                        <button
                            type="button"
                            onclick="openEditLabelModal(${label.id}, '${label.name.replace(/'/g, "\\'")}', '${label.color}')"
                            class="hidden group-hover:flex items-center text-slate-400 hover:text-primary transition-all">

                            <span class="material-symbols-outlined text-[16px]">
                                edit
                            </span>
                        </button>
                    </div>
                `).join('');
            }
        }
    });

</script>

</body>
</html><?php /**PATH C:\CK Web\Composer\SimpleNote\resources\views/layouts/app.blade.php ENDPATH**/ ?>