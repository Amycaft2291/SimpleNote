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
        tailwind.config = { darkMode: "class", theme: { extend: { fontFamily: { sans: ['Inter'] } } } };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .masonry-grid { column-count: 1; column-gap: 1.5rem; }
        @media (min-width: 768px) { .masonry-grid { column-count: 2; } }
        @media (min-width: 1280px) { .masonry-grid { column-count: 3; } }
        @media (min-width: 1536px) { .masonry-grid { column-count: 4; } }
        .masonry-item { break-inside: avoid; margin-bottom: 1.5rem; }
        .list-view { column-count: 1 !important; }
        .list-view .masonry-item { margin-bottom: 1rem; max-width: 800px; margin-left: auto; margin-right: auto; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased font-sans">

    
    <header class="fixed top-0 w-full h-14 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-100 dark:border-slate-800 flex items-center justify-between px-4">
        <div class="flex items-center gap-4">
            <span class="text-xl font-black text-blue-600 dark:text-blue-400 tracking-tight">SimpleNote</span>
        </div>

        <div class="hidden md:flex flex-1 max-w-xl mx-8 relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input data-search-input type="text" placeholder="Tìm kiếm ghi chú..."
                class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all dark:text-white"/>
        </div>

        <div class="flex items-center gap-3 relative">
            <button onclick="document.getElementById('userMenu').classList.toggle('hidden')" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                    <?php echo e(substr(Auth::user()->display_name ?? 'U', 0, 1)); ?>

                </div>
            </button>
            <div id="userMenu" class="hidden absolute right-0 top-11 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 w-48 overflow-hidden z-50">
                <a href="<?php echo e(route('appearance.edit')); ?>" class="block px-4 py-3 text-sm hover:bg-slate-50 dark:hover:bg-slate-700">Cài đặt giao diện</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-slate-700">Đăng xuất</button>
                </form>
            </div>
        </div>
    </header>

    
    <div class="flex pt-14 h-screen">

        
        <aside class="w-60 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 hidden md:flex flex-col p-4 flex-shrink-0 space-y-4 overflow-y-auto">

            
            <button onclick="if(typeof openCreateForm === 'function') { openCreateForm(); } else { window.location.href='<?php echo e(route('dashboard')); ?>'; }"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm shadow-md flex items-center justify-center gap-2 transition-all">
                <span class="material-symbols-outlined">add</span> Ghi chú mới
            </button>

            
            <nav>
                <a href="<?php echo e(route('dashboard')); ?>"
                    onclick="if(typeof filterByLabel === 'function') { event.preventDefault(); filterByLabel('all'); }"
                    class="flex items-center gap-3 px-3 py-2 text-blue-700 bg-blue-50 dark:bg-slate-800 dark:text-blue-400 rounded-lg font-medium text-sm">
                    <span class="material-symbols-outlined">description</span> Tất cả ghi chú
                </a>
            </nav>

            
            <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                <h3 class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                    Nhãn
                    <button onclick="document.getElementById('addLabelModal').classList.remove('hidden')"
                        class="hover:text-blue-500 transition-colors">
                        <span class="material-symbols-outlined text-sm">add</span>
                    </button>
                </h3>

                <div class="space-y-1" id="sidebarLabelList">

                </div>
            </div>
        </aside>

        
        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-50 dark:bg-slate-950">
            <?php echo e($slot); ?>

        </main>
    </div>

    
    <div id="addLabelModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-sm p-5">
            <h3 class="font-bold mb-4 dark:text-white">Tạo Nhãn Mới</h3>
            <form action="<?php echo e(route('labels.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="text" name="name" required
                    class="w-full border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-2 mb-3 text-sm outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Tên nhãn (Phân biệt hoa/thường)...">
                <div class="flex items-center gap-3 mb-4">
                    <label class="text-sm dark:text-slate-300">Màu nhãn:</label>
                    <input type="color" name="color" value="#3b82f6" class="w-8 h-8 rounded cursor-pointer border-0 p-0">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button"
                        onclick="document.getElementById('addLabelModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">Hủy</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Thêm</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="editLabelModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-sm p-5">
            <h3 class="font-bold mb-4 dark:text-white">Sửa Nhãn</h3>
            <form id="editLabelForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="text" id="editLabelName" name="name" required
                    class="w-full border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-2 mb-3 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                <div class="flex items-center gap-3 mb-4">
                    <label class="text-sm dark:text-slate-300">Màu nhãn:</label>
                    <input type="color" id="editLabelColor" name="color" class="w-8 h-8 rounded cursor-pointer border-0 p-0">
                </div>
                <div class="flex justify-between items-center">
                    <button type="button" onclick="deleteLabel()"
                        class="text-sm text-red-500 hover:text-red-700 font-medium">Xóa nhãn</button>
                    <div class="flex gap-2">
                        <button type="button"
                            onclick="document.getElementById('editLabelModal').classList.add('hidden')"
                            class="px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">Hủy</button>
                        <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu</button>
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
        // Đóng userMenu khi click ra ngoài
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            if (menu && !menu.classList.contains('hidden') && !e.target.closest('[onclick*="userMenu"]')) {
                menu.classList.add('hidden');
            }
        });

        // Mở/đóng modal nhãn khi click vùng nền
        ['addLabelModal', 'editLabelModal'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });
        });

        // Hàm mở modal sửa nhãn — được gọi từ sidebar
        function openEditLabelModal(id, name, color) {
            document.getElementById('editLabelName').value = name;
            document.getElementById('editLabelColor').value = color;
            document.getElementById('editLabelForm').action = `/labels/${id}`;
            document.getElementById('deleteLabelForm').action = `/labels/${id}`;
            document.getElementById('editLabelModal').classList.remove('hidden');
        }

        // Hàm xóa nhãn
        function deleteLabel() {
            if (confirm('Bạn có chắc chắn muốn xóa nhãn này? Các ghi chú sẽ không bị xóa.')) {
                document.getElementById('deleteLabelForm').submit();
            }
        }

        <?php if(session('error')): ?>
            alert('<?php echo e(session('error')); ?>');
        <?php endif; ?>
        
        document.addEventListener('DOMContentLoaded', () => {
            if (window.NoteLabels && window.NoteLabels.length > 0) {
                const container = document.getElementById('sidebarLabelList');
                if (container) {
                    container.innerHTML = window.NoteLabels.map(label => `
                        <div class="flex items-center justify-between group px-2 py-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                            <label class="flex items-center gap-2 cursor-pointer truncate flex-1 min-w-0">
                                <input type="checkbox" value="${label.id}" onchange="if(typeof toggleLabelFilter === 'function') toggleLabelFilter(this)" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 bg-white flex-shrink-0">
                                <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: ${label.color}"></span>
                                <span class="text-sm text-slate-700 dark:text-slate-300 truncate">${label.name}</span>
                            </label>
                            <button type="button" onclick="openEditLabelModal(${label.id}, '${label.name.replace(/'/g, "\\'")}', '${label.color}')" class="hidden group-hover:flex items-center text-slate-400 hover:text-blue-600 p-1 flex-shrink-0 transition-colors" title="Sửa nhãn">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>
                        </div>
                    `).join('');
                }
            }
        });
    </script>

</body>
</html><?php /**PATH C:\CK Web\Composer\SimpleNote\resources\views/layouts/app.blade.php ENDPATH**/ ?>