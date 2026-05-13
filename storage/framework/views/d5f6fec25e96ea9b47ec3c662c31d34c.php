<?php
    $getNoteUI = function($color) {
        $color = $color ?? auth()->user()->note_color ?? '#ffffff';
        $isSpecificDark = ($color === '#1e293b');
        return (object) [
            'bg' => $color,
            'title' => $isSpecificDark ? 'text-white' : 'text-slate-900', 
            'content' => $isSpecificDark ? 'text-slate-200' : 'text-slate-600',
            'muted' => $isSpecificDark ? 'text-slate-400' : 'text-slate-500',
            'border' => $isSpecificDark ? 'border-white/10' : 'border-black/5'
        ];
    };

    $userBg = auth()->user()->note_color ?? '#ffffff';
    $userUI = $getNoteUI($userBg);
?>

<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div id="notes-font-size">
        
        <?php if(!auth()->user()->hasVerifiedEmail()): ?>
            <div class="max-w-2xl mx-auto mb-6">
                <div class="rounded-xl border border-yellow-300 bg-yellow-100 px-4 py-3 text-yellow-800 text-sm">
                    Tài khoản của bạn chưa được xác minh email.
                    Vui lòng kiểm tra hộp thư để hoàn tất kích hoạt tài khoản.

                    <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="ml-2 underline font-semibold hover:text-yellow-900">
                            Gửi lại email
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="max-w-2xl mx-auto mb-10">
            <div id="createBar" class="rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-all hover:shadow-md" 
                style="background-color: <?php echo e($userUI->bg); ?> !important;">
                
                
                <div id="createPlaceholder" class="flex items-center justify-between p-4 cursor-pointer <?php echo e($userUI->content); ?>" onclick="openCreateForm()">
                    <span class="font-medium text-sm">Tạo ghi chú mới...</span>
                    <span class="material-symbols-outlined">image</span>
                </div>

                
                <form id="createForm" action="<?php echo e(route('notes.store')); ?>" method="POST" enctype="multipart/form-data" class="hidden p-4 space-y-3">
                    <?php echo csrf_field(); ?>
                    <input name="title" type="text" placeholder="Tiêu đề" 
                        class="w-full font-bold border-none outline-none focus:ring-0 px-0 <?php echo e($userUI->title); ?>" 
                        style="background-color: transparent !important;">
                    
                    <textarea name="content" rows="3" placeholder="Nội dung ghi chú..." 
                            class="w-full text-sm border-none outline-none resize-none focus:ring-0 px-0 <?php echo e($userUI->content); ?>" 
                            style="background-color: transparent !important;"></textarea>
                    
                    
                    <div>
                        <label class="text-[10px] font-bold <?php echo e($userUI->muted); ?> uppercase block mb-1">Ảnh đính kèm</label>
                        <input type="file" name="images[]" multiple accept="image/*" 
                            class="text-sm <?php echo e($userUI->muted); ?> file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    
                    
                    <div class="border-t <?php echo e($userUI->border); ?> pt-3">
                        <p class="text-[10px] font-bold <?php echo e($userUI->muted); ?> uppercase mb-2">Gán Nhãn</p>
                        <div class="flex flex-wrap gap-2">
                            <?php if(isset($labels)): ?>
                                <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-1.5 text-xs bg-black/5 px-2 py-1 rounded-lg cursor-pointer hover:bg-black/10 <?php echo e($userUI->content); ?>">
                                    <input type="checkbox" name="label_ids[]" value="<?php echo e($label->id); ?>" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: <?php echo e($label->color); ?>"></span>
                                    <?php echo e($label->name); ?>

                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t <?php echo e($userUI->border); ?>">
                        <button type="button" onclick="closeCreateForm()" class="px-4 py-1.5 text-sm rounded-lg hover:bg-black/5 <?php echo e($userUI->content); ?>">Đóng</button>
                        <button type="submit" class="px-4 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
            <h1 class="text-2xl font-black dark:text-white" id="pageTitle">Tất cả ghi chú</h1>
            
            <div class="flex items-center gap-3 bg-white dark:bg-slate-800 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                
                <div class="flex items-center border-r border-slate-100 dark:border-slate-700 pr-2 mr-2">
                    <button onclick="toggleSortOrder()" id="sortBtn" title="Đổi thứ tự sắp xếp" class="p-1.5 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg flex items-center gap-1 transition-all">
                        <span class="material-symbols-outlined text-sm" id="sortIcon">south</span>
                        <span class="text-xs font-bold uppercase" id="sortLabel">Mới nhất</span>
                    </button>
                </div>

                
                <div class="flex gap-1">
                    <button id="gridBtn" onclick="setView('grid')" class="p-1.5 rounded-lg transition-all" title="Chế độ lưới">
                        <span class="material-symbols-outlined text-sm">grid_view</span>
                    </button>
                    <button id="listBtn" onclick="setView('list')" class="p-1.5 rounded-lg transition-all" title="Chế độ danh sách">
                        <span class="material-symbols-outlined text-sm">view_list</span>
                    </button>
                </div>
            </div>
        </div>

        
        <div id="notesContainer" class="masonry-grid">
            <?php $__empty_1 = true; $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $isUnlocked = session("note_unlocked_{$note->id}");
                    $displayTitle = ($note->is_locked && !$isUnlocked) ? 'Ghi chú bị khóa' : $note->title;
                    $displayContent = ($note->is_locked && !$isUnlocked) ? 'Nội dung ẩn' : $note->content;
                ?>

                <div class="masonry-item group cursor-pointer note-card rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden relative shadow-sm hover:shadow-md transition-all" 
                    data-id="<?php echo e($note->id); ?>"
                    data-locked="<?php echo e($note->is_locked ? 1 : 0); ?>"
                    data-color="<?php echo e($userUI->bg); ?>"
                    data-title="<?php echo e(e($displayTitle)); ?>"
                    data-content="<?php echo e(e($displayContent)); ?>" 
                    data-labels="<?php echo e($note->labels->pluck('id')->join(',')); ?>"
                    data-pinned="<?php echo e($note->is_pinned ? 1 : 0); ?>"
                    data-timestamp="<?php echo e($note->updated_at->timestamp); ?>"
                    data-created-at="<?php echo e($note->created_at->diffForHumans()); ?>"                    
                    onclick="handleNoteClick(event, this, <?php echo e($note->is_locked ? 'true' : 'false'); ?>, <?php echo e($isUnlocked ? 'true' : 'false'); ?>)" 
                    style="background-color: <?php echo e($userUI->bg); ?> !important;">
                    
                    <div class="p-5">
                        
                        <div class="flex justify-between items-start mb-2 gap-4">
                            <div class="flex flex-col overflow-hidden">
                                <h2 class="note-title font-bold text-base <?php echo e($userUI->title); ?> leading-snug break-words">
                                    <?php echo e($displayTitle ?: 'Không tiêu đề'); ?>

                                </h2>
                                
                                <div class="flex items-center gap-1 text-[10px] <?php echo e($userUI->muted); ?> mt-1 font-bold uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                    <?php echo e($note->updated_at->format('d/m/Y')); ?>

                                </div>
                            </div>

                            
                            <div class="flex gap-1 shrink-0">
                                <form action="<?php echo e(route('notes.toggle-pin', $note->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="p-1.5 rounded-lg transition-all <?php echo e($note->is_pinned ? 'text-yellow-500 bg-yellow-50' : 'text-slate-400 hover:bg-black/5 opacity-0 group-hover:opacity-100'); ?>">
                                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' <?php echo e($note->is_pinned ? 1 : 0); ?>;">push_pin</span>
                                    </button>
                                </form>

                                <form action="<?php echo e(route('notes.toggle-lock', $note->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="p-1.5 rounded-lg transition-all <?php echo e($note->is_locked ? 'text-blue-500 bg-blue-50' : 'text-slate-400 hover:bg-black/5 opacity-0 group-hover:opacity-100'); ?>">
                                        <span class="material-symbols-outlined text-[20px]">
                                            <?php echo e($note->is_locked ? 'lock' : 'lock_open'); ?>

                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        
                        <div class="mt-3">
                            <?php if($note->is_locked && !$isUnlocked): ?>
                                <div class="py-6 flex flex-col items-center justify-center text-slate-400 bg-black/5 rounded-lg border border-dashed border-slate-300 dark:border-slate-600">
                                    <span class="material-symbols-outlined text-3xl mb-2">encrypted</span>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">Ghi chú đã bị khóa</p>
                                </div>
                            <?php else: ?>
                                <?php if($note->content): ?>
                                    <p class="note-content line-clamp-5 text-sm <?php echo e($userUI->content); ?> leading-relaxed break-words">
                                        <?php echo e($note->content); ?>

                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        
                        <?php if($note->labels->count() > 0): ?>
                            <div class="flex flex-wrap gap-1 mt-4">
                                <?php $__currentLoopData = $note->labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full text-white shadow-sm" style="background-color: <?php echo e($lbl->color); ?>">
                                        <?php echo e($lbl->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-20 text-slate-400">
                    <span class="material-symbols-outlined text-6xl mb-4 block">note_stack</span>
                    <p class="text-lg font-medium">Chưa có ghi chú nào</p>
                    <p class="text-sm mt-1">Bấm "Tạo ghi chú mới" để bắt đầu!</p>
                </div>
            <?php endif; ?>
        </div> 

        
        <div id="editModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">            
            <form id="realEditForm" action="" method="POST" enctype="multipart/form-data" 
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden relative flex flex-col max-h-[90vh]">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="p-5 overflow-y-auto space-y-4 flex-1">
                    <input type="hidden" id="editNoteId" name="id">

                    <input id="editTitle" name="title" type="text" placeholder="Tiêu đề"
                        class="w-full font-bold text-xl bg-transparent border-none outline-none dark:text-white focus:ring-0 px-0">               
                    <textarea id="editContent" name="content" rows="5" placeholder="Nội dung ghi chú..."
                        class="w-full text-sm bg-transparent border-none outline-none resize-none dark:text-slate-300 focus:ring-0 px-0 leading-relaxed"></textarea>
                    
                    
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <p class="text-[11px] font-bold text-slate-400 uppercase mb-3 tracking-wider">Ảnh đính kèm</p>
                        <div id="editImagesContainer" class="grid grid-cols-3 gap-2 mb-3"></div>
                        
                        <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-blue-600 hover:text-blue-700 font-medium">
                            <span class="material-symbols-outlined text-base">add_photo_alternate</span>
                            <span>Thêm ảnh mới</span>
                            <input type="file" name="images[]" id="editImagesInput" multiple accept="image/*" class="hidden" onchange="previewNewImages(this)">
                        </label>
                        <div id="newImagesPreview" class="grid grid-cols-3 gap-2 mt-3"></div>
                    </div>

                    
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <p class="text-[11px] font-bold text-slate-400 uppercase mb-3 tracking-wider">Gán Nhãn</p>
                        <div class="flex flex-wrap gap-2" id="editLabelsContainer">
                            <?php $__currentLoopData = $labels ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-1.5 ...">
                                    <input type="checkbox" name="label_ids[]" value="<?php echo e($label->id); ?>" class="edit-label-cb ...">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: <?php echo e($label->color); ?>"></span>
                                    <?php echo e($label->name); ?>

                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="px-5 py-4 bg-slate-50 dark:bg-slate-900/50 border-t flex justify-between items-center">
                    <button type="button" onclick="submitDelete()" class="text-sm text-red-500 font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">delete</span> XÓA
                    </button>
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm text-slate-600">Hủy</button>
                        <button type="submit" class="px-6 py-2 text-sm font-bold bg-blue-600 text-white rounded-xl">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>

        <form id="deleteForm" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>
        
        
        <div id="setPasswordModal" class="fixed inset-0 bg-black/50 hidden z-[100] flex items-center justify-center backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl border border-slate-200 dark:border-slate-800">
                <h3 class="text-xl font-bold mb-2 text-slate-900 dark:text-white">Thiết lập mật khẩu ghi chú</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Mật khẩu này dùng để bảo vệ tất cả ghi chú của bạn.</p>
                
                <form id="setPasswordForm" onsubmit="handleSetPassword(event)">
                    <div class="space-y-4">
                        <input type="password" name="password" placeholder="Mật khẩu mới (ít nhất 4 ký tự)" required 
                            class="w-full rounded-xl border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required 
                            class="w-full rounded-xl border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" onclick="closeSetPasswordModal()" class="px-4 py-2 text-slate-500 font-medium">Hủy</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-500/30">Lưu & Khóa</button>
                    </div>
                </form>
            </div>
        </div>

        <style>
            #notes-font-size {
                --size-base: <?php echo e(auth()->user()->font_size); ?>px;
                --size-title: <?php echo e(auth()->user()->font_size + 4); ?>px;
            }

            #notes-font-size .note-content, 
            #notes-font-size #newContent, 
            #notes-font-size #editContent {
                font-size: var(--size-base) !important;
            }

            #notes-font-size .note-title, 
            #notes-font-size #newTitle, 
            #notes-font-size #editTitle {
                font-size: var(--size-title) !important;
            }
        </style>
    </div>

    <script>
        const CSRF_TOKEN = '<?php echo e(csrf_token()); ?>';
    </script>
    <script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Web\Composer\SimpleNote\resources\views/dashboard.blade.php ENDPATH**/ ?>