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

                
                <div id="createForm" class="hidden p-4 space-y-3">
                    <input id="newTitle" type="text" placeholder="Tiêu đề" 
                           class="w-full font-bold border-none outline-none focus:ring-0 px-0 <?php echo e($userUI->title); ?>" 
                           style="background-color: transparent !important;">
                    
                    <textarea id="newContent" rows="3" placeholder="Nội dung ghi chú..." 
                              class="w-full text-sm border-none outline-none resize-none focus:ring-0 px-0 <?php echo e($userUI->content); ?>" 
                              style="background-color: transparent !important;"></textarea>
                    
                    
                    <div>
                        <label class="text-[10px] font-bold <?php echo e($userUI->muted); ?> uppercase block mb-1">Ảnh đính kèm</label>
                        <input type="file" id="newImages" multiple accept="image/*" 
                               class="text-sm <?php echo e($userUI->muted); ?> file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    
                    
                    <div class="border-t <?php echo e($userUI->border); ?> pt-3">
                        <p class="text-[10px] font-bold <?php echo e($userUI->muted); ?> uppercase mb-2">Gán Nhãn</p>
                        <div class="flex flex-wrap gap-2">
                            <?php if(isset($labels)): ?>
                                <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-1.5 text-xs bg-black/5 dark:bg-white/5 <?php echo e($userUI->content); ?> px-2 py-1 rounded-lg cursor-pointer hover:bg-black/10">
                                    <input type="checkbox" value="<?php echo e($label->id); ?>" class="new-label-cb rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: <?php echo e($label->color); ?>"></span>
                                    <?php echo e($label->name); ?>

                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="flex justify-end gap-2 pt-2 border-t <?php echo e($userUI->border); ?>">
                        <button type="button" onclick="closeCreateForm()" class="px-4 py-1.5 text-sm rounded-lg hover:bg-black/5 <?php echo e($userUI->content); ?>">Đóng</button>
                        <button type="button" onclick="saveNote()" class="px-4 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu</button>
                    </div>
                </div>
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
                <div class="masonry-item group cursor-pointer note-card" 
                    data-id="<?php echo e($note->id); ?>"
                    data-locked="<?php echo e($note->is_locked ? 1 : 0); ?>"
                    data-color="<?php echo e($userUI->bg); ?>"
                    data-title="<?php echo e($note->is_locked ? 'Nội dung bị khóa' : e($note->title)); ?>"
                    data-content="<?php echo e($note->is_locked ? 'Vui lòng nhập mật khẩu để xem' : e($note->content)); ?>" 
                    data-labels="<?php echo e($note->labels->pluck('id')->join(',')); ?>"
                    data-images="<?php echo e($note->is_locked ? '[]' : $note->images->toJson()); ?>"
                    data-pinned="<?php echo e($note->is_pinned ? 1 : 0); ?>"
                    data-timestamp="<?php echo e($note->updated_at->timestamp); ?>"
                    data-created-at="<?php echo e($note->created_at->diffForHumans()); ?>"
                    onclick="openEditModal(this)">
                    
                    <div class="note-card rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden relative shadow-sm hover:shadow-md transition-all cursor-pointer group" onclick="handleNoteClick(event, <?php echo e($note->id); ?>, <?php echo e($note->is_locked ? 'true' : 'false'); ?>)" style="background-color: <?php echo e($userUI->bg); ?> !important;">                       
                        <div class="p-5">
                            
                            <div class="flex justify-between items-start mb-2 gap-4">
                                <div class="flex flex-col overflow-hidden">
                                    <h2 class="note-title font-bold text-base <?php echo e($userUI->title); ?> leading-snug break-words">
                                        <?php echo e($note->title ?: 'Không tiêu đề'); ?>

                                    </h2>
                                    
                                    <div class="flex items-center gap-1 text-[10px] <?php echo e($userUI->muted); ?> mt-1 font-bold uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                        <?php echo e($note->updated_at->format('d/m/Y')); ?>

                                    </div>
                                </div>

                                
                                <div class="flex gap-1 shrink-0">
                                    
                                    <button type="button" 
                                        onclick="togglePin(event, this, <?php echo e($note->id); ?>)"
                                        class="p-1.5 rounded-lg transition-all <?php echo e($note->is_pinned ? 'text-yellow-500 bg-yellow-50' : 'text-slate-400 hover:bg-black/5 opacity-0 group-hover:opacity-100'); ?>">
                                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' <?php echo e($note->is_pinned ? 1 : 0); ?>;">push_pin</span>
                                    </button>

                                    
                                    <button type="button" 
                                        onclick="toggleLock(event, this, <?php echo e($note->id); ?>)"
                                        class="p-1.5 rounded-lg transition-all <?php echo e($note->is_locked ? 'text-blue-500 bg-blue-50' : 'text-slate-400 hover:bg-black/5 opacity-0 group-hover:opacity-100'); ?>">
                                        <span class="material-symbols-outlined text-[20px]">
                                            <?php echo e($note->is_locked ? 'lock' : 'lock_open'); ?>

                                        </span>
                                    </button>
                                </div>
                            </div>

                            
                            <div class="mt-3">
                                <?php if($note->is_locked): ?>
                                    <div class="py-6 flex flex-col items-center justify-center text-slate-400 bg-black/5 rounded-lg">
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
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden relative flex flex-col max-h-[90vh]">
                
                <div class="p-5 overflow-y-auto space-y-4 flex-1">
                    <input type="hidden" id="editNoteId">

                    
                    <input id="editTitle" type="text" placeholder="Tiêu đề"
                        class="w-full font-bold bg-transparent border-none outline-none dark:text-white focus:ring-0 px-0">
                    
                    
                    <textarea id="editContent" rows="5" placeholder="Nội dung ghi chú..."
                        class="w-full text-sm bg-transparent border-none outline-none resize-none dark:text-slate-300 focus:ring-0 px-0 leading-relaxed"></textarea>
                    
                    
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-3">Ảnh đính kèm</p>
                        
                        
                        <div id="editImagesContainer" class="grid grid-cols-3 gap-2 mb-3"></div>
                        
                        
                        <label class="flex items-center gap-2 cursor-pointer text-sm text-blue-600 hover:text-blue-700 font-medium">
                            <span class="material-symbols-outlined text-base">add_photo_alternate</span>
                            Thêm ảnh mới
                            <input type="file" id="editImagesInput" multiple accept="image/*" class="hidden" onchange="previewNewImages(this)">
                        </label>
                        
                        
                        <div id="newImagesPreview" class="grid grid-cols-3 gap-2 mt-2"></div>
                    </div>

                    
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-3">Gán Nhãn</p>
                        <div class="flex flex-wrap gap-2" id="editLabelsContainer">
                            <?php if(isset($labels)): ?>
                                <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-1.5 text-xs bg-slate-100 dark:bg-slate-700 dark:text-slate-300 px-2 py-1 rounded-lg cursor-pointer hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors has-[:checked]:bg-blue-100 has-[:checked]:text-blue-700 dark:has-[:checked]:bg-blue-900/40 dark:has-[:checked]:text-blue-300 has-[:checked]:ring-1 has-[:checked]:ring-blue-400">
                                    <input type="checkbox" value="<?php echo e($label->id); ?>" class="edit-label-cb rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: <?php echo e($label->color); ?>"></span>
                                    <?php echo e($label->name); ?>

                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <button onclick="deleteNote()" class="text-sm text-red-500 hover:text-red-700 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">delete</span> Xóa
                    </button>
                    <div class="flex gap-2">
                        <button onclick="closeEditModal()" class="px-4 py-1.5 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">Hủy</button>
                        <button onclick="updateNote()" class="px-4 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu Cập Nhật</button>
                    </div>
                </div>
            </div>
        </div>
        
        
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

        <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        window.NoteLabels = <?php echo json_encode($labels ?? [], 15, 512) ?>;

        //biến toàn cục vs tthái
        let selectedLabels = [];
        let removeImageIds = [];
        let currentSortOrder = 'newest';
        let newSelectedFiles = [];

        //thêm gchu
        function openCreateForm() {
            document.getElementById('createPlaceholder').classList.add('hidden');
            document.getElementById('createForm').classList.remove('hidden');
            document.getElementById('newTitle').focus();
        }

        function closeCreateForm() {
            document.getElementById('createPlaceholder').classList.remove('hidden');
            document.getElementById('createForm').classList.add('hidden');
        }

        async function saveNote() {
            const title = document.getElementById('newTitle').value.trim();
            const content = document.getElementById('newContent').value.trim();
            if (!title && !content) return alert("Vui lòng nhập tiêu đề hoặc nội dung!");

            const formData = new FormData();
            formData.append('title', title || '(Không có tiêu đề)');
            formData.append('content', content);

            const files = document.getElementById('newImages').files;
            for (let i = 0; i < files.length; i++) formData.append('images[]', files[i]);

            document.querySelectorAll('.new-label-cb:checked').forEach(cb => {
                formData.append('label_ids[]', cb.value);
            });

            try {
                const res = await fetch('<?php echo e(route("notes.store")); ?>', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: formData
                });

                if (res.ok) {
                    window.location.reload();
                } else {
                    const err = await res.json();
                    alert("Lỗi: " + (err.message || "Kiểm tra Console (F12)"));
                }
            } catch (e) {
                alert("Lỗi kết nối tới máy chủ!");
            }
        }

        //sửa/xóa gchu
        async function openEditModal(card) {
            const isLocked = card.dataset.locked === "1";
            let noteTitle = card.dataset.title;
            let noteContent = card.dataset.content;
            let noteImages = JSON.parse(card.dataset.images || '[]');

            if (isLocked) {
                const password = prompt("Ghi chú này đã được khóa. Vui lòng nhập mật khẩu bảo mật:");
                if (!password) return; 

                try {
                    const res = await fetch(`/notes/${card.dataset.id}/unlock`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ password: password })
                    });

                    const data = await res.json();

                    //mở khóa/sai pass
                    if (data.success) {
                        //dữ liệu thật từ sv trả về
                        noteTitle = data.title;
                        noteContent = data.content;
                        noteImages = data.images;
                    } else {
                        alert(data.message || "Sai mật khẩu!");
                        return;
                    }
                } catch (e) {
                    alert("Lỗi kết nối khi mở khóa!");
                    return;
                }
            }

            // đổ dữ liệu vào Modal
            document.getElementById('editNoteId').value = card.dataset.id;
            document.getElementById('editTitle').value = noteTitle;
            document.getElementById('editContent').value = noteContent;

            // render ds ảnh
            const imgContainer = document.getElementById('editImagesContainer');
            imgContainer.innerHTML = '';
            noteImages.forEach(img => {
                const div = document.createElement('div');
                div.className = 'relative group rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 aspect-square';
                div.dataset.imgId = img.id;
                div.innerHTML = `
                    <img src="/storage/${img.image_path}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                        <button type="button" class="text-white text-xs bg-red-600 hover:bg-red-700 px-2 py-1 rounded-lg font-medium"
                            onclick="markImageRemove(${img.id}, this.closest('[data-img-id]'))">
                            <span class="material-symbols-outlined text-sm align-middle">delete</span> Xóa
                        </button>
                    </div>
                `;
                imgContainer.appendChild(div);
            });

            //xử lý label/tag
            const noteLabels = card.dataset.labels ? card.dataset.labels.split(',') : [];
            document.querySelectorAll('.edit-label-cb').forEach(cb => {
                cb.checked = noteLabels.includes(cb.value);
            });

            //reset khuôn dữ liệu khi mở gchu mới
            removeImageIds = [];
            newSelectedFiles = [];
            document.getElementById('editImagesInput').value = '';
            document.getElementById('newImagesPreview').innerHTML = '';

            //hiển thị Modal
            document.getElementById('editModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        //đóng modal khi bấm ra ngoài
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        function markImageRemove(imgId, element) {
            removeImageIds.push(imgId);
            element.remove();
        }

        function previewNewImages(input) {
            const preview = document.getElementById('newImagesPreview');
            
            //đẩy file mới vào mảng newSelectedFiles và render thêm thay vì ghi đè HTML
            Array.from(input.files).forEach(file => {
                newSelectedFiles.push(file); //lưu vào mảng tạm trc đã
                
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'relative rounded-lg overflow-hidden bg-slate-100 aspect-square group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <span class="absolute top-1 right-1 bg-green-500 text-white text-[9px] font-bold px-1 py-0.5 rounded">MỚI</span>
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                            <button type="button" class="text-white text-xs bg-red-600 hover:bg-red-700 px-2 py-1 rounded-lg font-medium"
                                onclick="removeNewSelectedImage(this, '${file.name}')">
                                <span class="material-symbols-outlined text-sm align-middle">delete</span> Xóa
                            </button>
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
            
            //reset input để browser cho ng dùng chọn lại file nếu muốn/mở dialog lần sau k lỗi
            input.value = ''; 
        }

        // nếu đổi ý -> xóa ảnh vừa mới thêm
        function removeNewSelectedImage(btn, fileName) {
            newSelectedFiles = newSelectedFiles.filter(f => f.name !== fileName);
            btn.closest('.relative').remove();
        }

        async function updateNote() {
            const id = document.getElementById('editNoteId').value;
            const title = document.getElementById('editTitle').value.trim();
            const content = document.getElementById('editContent').value.trim();

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('title', title || '(Không có tiêu đề)');
            formData.append('content', content);

            //img mới
            for (let i = 0; i < newSelectedFiles.length; i++) {
                formData.append('images[]', newSelectedFiles[i]);
            }

            //tag đc chọn
            document.querySelectorAll('.edit-label-cb:checked').forEach(cb => {
                formData.append('label_ids[]', cb.value);
            });

            //img id cần xóa
            removeImageIds.forEach(imgId => formData.append('remove_image_ids[]', imgId));

            const res = await fetch(`/notes/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: formData
            });

            if (res.ok) window.location.reload();
            else alert("Lỗi cập nhật ghi chú!");
        }

        async function deleteNote() {
            const id = document.getElementById('editNoteId').value;
            if (!confirm('Bạn có chắc chắn muốn xóa ghi chú này?')) return;
            const res = await fetch(`/notes/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            if (res.ok) window.location.reload();
        }

        //ghim + xóa
        async function togglePin(event, button, noteId) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }

            try {
                const response = await fetch(`/notes/${noteId}/toggle-pin`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    console.error('Lỗi từ server:', data);
                }
            } catch (error) {
                console.error('Lỗi kết nối:', error);
            }
        }

        function openSetPasswordModal(noteId) {
            const form = document.getElementById('setPasswordForm');
            form.dataset.noteId = noteId; 
            document.getElementById('setPasswordModal').classList.remove('hidden');
        }

        function closeSetPasswordModal() {
            document.getElementById('setPasswordModal').classList.add('hidden');
        }

        async function handleSetPassword(event) {
            event.preventDefault();
            const form = event.target;
            const noteId = form.dataset.noteId;
            const password = form.password.value;
            const password_confirmation = form.password_confirmation.value;

            const response = await fetch('/user/set-note-password', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ password, password_confirmation })
            });

            const data = await response.json();
            if (data.success) {
                closeSetPasswordModal();
                await toggleLock(null, null, noteId); // Dùng await vì toggleLock giờ là async
            } else {
                alert(data.message || 'Lỗi đặt mật khẩu');
            }
        }

        async function handleNoteClick(event, noteId, isLocked) {
            if (event.target.closest('button')) return; 

            if (isLocked) {
                await openUnlockModal(noteId);
            } else {
                if (typeof openEditModal === 'function') {
                    openEditModal(noteId);
                }
            }
        }

        async function toggleLock(event, button, noteId) {
            if (event) {
                event.stopPropagation(); 
                event.preventDefault();
            }

            try {
                const response = await fetch(`/notes/${noteId}/toggle-lock`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } 
                else if (data.requires_set_password) {
                    openSetPasswordModal(noteId);
                } 
                else if (data.requires_password) {
                    await openUnlockModal(noteId);
                }
            } catch (error) {
                console.error('Lỗi:', error);
            }
        }

        async function openUnlockModal(noteId) {
            const password = prompt("Ghi chú này đã được khóa. Nhập mật khẩu để mở:");
            if (!password) return;

            try {
                const response = await fetch(`/notes/${noteId}/unlock`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || "Sai mật khẩu!");
                }
            } catch (e) {
                console.error("Lỗi unlock:", e);
            }
        }

        //sort + filter
        function toggleLabelFilter(cb) {
            if (cb.checked) {
                selectedLabels.push(cb.value);
            } else {
                selectedLabels = selectedLabels.filter(id => id !== cb.value);
            }
            applyFilters();
            updatePageTitle();
        }

        function filterByLabel(value) {
            //reset tất cả checkbox sidebar
            document.querySelectorAll('#sidebarLabelList input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });
            selectedLabels = [];
            applyFilters();
            updatePageTitle();
        }

        const searchInput = document.querySelector('[data-search-input]');
        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        function applyFilters() {
            const term = searchInput ? searchInput.value.toLowerCase() : '';
            const cards = document.querySelectorAll('.note-card');

            cards.forEach(card => {
                const titleEl = card.querySelector('.note-title');
                const contentEl = card.querySelector('.note-content');
                const title = titleEl ? titleEl.innerText.toLowerCase() : '';
                const content = contentEl ? contentEl.innerText.toLowerCase() : '';
                const noteLabels = card.dataset.labels ? card.dataset.labels.split(',').filter(Boolean) : [];

                const matchesSearch = !term || title.includes(term) || content.includes(term);

                let matchesLabel = true;
                if (selectedLabels.length > 0) {
                    matchesLabel = selectedLabels.some(l => noteLabels.includes(l));
                }

                card.style.display = (matchesSearch && matchesLabel) ? 'block' : 'none';
            });
        }

        function updatePageTitle() {
            const titleEl = document.getElementById('pageTitle');
            if (!titleEl) return;
            if (selectedLabels.length === 0) {
                titleEl.textContent = 'Tất cả ghi chú';
            } else {
                titleEl.textContent = `Lọc theo ${selectedLabels.length} nhãn`;
            }
        }

        //view + DOM
        function setView(mode) {
            const container = document.getElementById('notesContainer');
            const gridBtn = document.getElementById('gridBtn');
            const listBtn = document.getElementById('listBtn');

            if (mode === 'grid') {
                container.classList.remove('list-view');
                container.classList.add('masonry-grid');
                if (gridBtn) gridBtn.className = "p-1.5 rounded-lg bg-blue-600 text-white shadow-sm";
                if (listBtn) listBtn.className = "p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700";
            } else {
                container.classList.remove('masonry-grid');
                container.classList.add('list-view');
                if (listBtn) listBtn.className = "p-1.5 rounded-lg bg-blue-600 text-white shadow-sm";
                if (gridBtn) gridBtn.className = "p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700";
            }
            localStorage.setItem('sn_view', mode);
        }

        function toggleSortOrder() {
            currentSortOrder = currentSortOrder === 'newest' ? 'oldest' : 'newest';
            const label = document.getElementById('sortLabel');
            const icon = document.getElementById('sortIcon');
            if (label) label.innerText = currentSortOrder === 'newest' ? 'Mới nhất' : 'Cũ nhất';
            if (icon) icon.innerText = currentSortOrder === 'newest' ? 'south' : 'north';
            sortNotes();
        }

        function sortNotes() {
            const container = document.getElementById('notesContainer');
            const cards = Array.from(container.getElementsByClassName('note-card'));

            cards.sort((a, b) => {
                const pinA = parseInt(a.dataset.pinned || 0);
                const pinB = parseInt(b.dataset.pinned || 0);
                if (pinA !== pinB) return pinB - pinA;

                const timeA = parseInt(a.dataset.timestamp || 0);
                const timeB = parseInt(b.dataset.timestamp || 0);
                return currentSortOrder === 'newest' ? timeB - timeA : timeA - timeB;
            });

            cards.forEach(card => container.appendChild(card));
        }

        //tạo DOM
        document.addEventListener('DOMContentLoaded', () => {
            const savedView = localStorage.getItem('sn_view') || 'grid';
            setView(savedView);
            sortNotes();
        });
        </script>

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