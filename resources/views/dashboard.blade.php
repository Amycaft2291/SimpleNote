@php
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
@endphp

<x-app-layout>
    <div id="notes-font-size">
        {{--xác thực tk = mail--}}
        @if(!auth()->user()->hasVerifiedEmail())
            <div class="max-w-2xl mx-auto mb-6">
                <div class="rounded-xl border border-yellow-300 bg-yellow-100 px-4 py-3 text-yellow-800 text-sm">
                    Tài khoản của bạn chưa được xác minh email.
                    Vui lòng kiểm tra hộp thư để hoàn tất kích hoạt tài khoản.

                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="ml-2 underline font-semibold hover:text-yellow-900">
                            Gửi lại email
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{--khung nhập gchu nhanh--}}
        <div class="max-w-2xl mx-auto mb-10">
            <div id="createBar" class="rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-all hover:shadow-md" 
                style="background-color: {{ $userUI->bg }} !important;">
                
                {{--form chưa mở--}}
                <div id="createPlaceholder" class="flex items-center justify-between p-4 cursor-pointer {{ $userUI->content }}" onclick="openCreateForm()">
                    <span class="font-medium text-sm">Tạo ghi chú mới...</span>
                    <span class="material-symbols-outlined">image</span>
                </div>

                {{--form đã mở--}}
                <form id="createForm" action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data" class="hidden p-4 space-y-3">
                    @csrf
                    <input name="title" type="text" placeholder="Tiêu đề" 
                        class="w-full font-bold border-none outline-none focus:ring-0 px-0 {{ $userUI->title }}" 
                        style="background-color: transparent !important;">
                    
                    <textarea name="content" rows="3" placeholder="Nội dung ghi chú..." 
                            class="w-full text-sm border-none outline-none resize-none focus:ring-0 px-0 {{ $userUI->content }}" 
                            style="background-color: transparent !important;"></textarea>
                    
                    {{--img--}}
                    <div>
                        <label class="text-[10px] font-bold {{ $userUI->muted }} uppercase block mb-1">Ảnh đính kèm</label>
                        <input type="file" name="images[]" multiple accept="image/*" 
                            class="text-sm {{ $userUI->muted }} file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    
                    {{--label--}}
                    <div class="border-t {{ $userUI->border }} pt-3">
                        <p class="text-[10px] font-bold {{ $userUI->muted }} uppercase mb-2">Gán Nhãn</p>
                        <div class="flex flex-wrap gap-2">
                            @isset($labels)
                                @foreach($labels as $label)
                                <label class="flex items-center gap-1.5 text-xs bg-black/5 px-2 py-1 rounded-lg cursor-pointer hover:bg-black/10 {{ $userUI->content }}">
                                    <input type="checkbox" name="label_ids[]" value="{{ $label->id }}" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $label->color }}"></span>
                                    {{ $label->name }}
                                </label>
                                @endforeach
                            @endisset
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t {{ $userUI->border }}">
                        <button type="button" onclick="closeCreateForm()" class="px-4 py-1.5 text-sm rounded-lg hover:bg-black/5 {{ $userUI->content }}">Đóng</button>
                        <button type="submit" class="px-4 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

        {{--toolbar nhỏ--}}
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
            <h1 class="text-2xl font-black dark:text-white" id="pageTitle">Tất cả ghi chú</h1>
            
            <div class="flex items-center gap-3 bg-white dark:bg-slate-800 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                {{--sort--}}
                <div class="flex items-center border-r border-slate-100 dark:border-slate-700 pr-2 mr-2">
                    <button onclick="toggleSortOrder()" id="sortBtn" title="Đổi thứ tự sắp xếp" class="p-1.5 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg flex items-center gap-1 transition-all">
                        <span class="material-symbols-outlined text-sm" id="sortIcon">south</span>
                        <span class="text-xs font-bold uppercase" id="sortLabel">Mới nhất</span>
                    </button>
                </div>

                {{--chế độ xem lưới--}}
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

        {{--lưới gchu--}}
        <div id="notesContainer" class="masonry-grid">
            @forelse($notes as $note)
                @php
                    $isUnlocked = session("unlocked_notes.{$note->id}");
                    
                    $noteColor = $note->note_color ?? '#ffffff';
                    $noteUI = $getNoteUI($noteColor);

                    $displayTitle = ($note->is_locked && !$isUnlocked) ? 'Ghi chú bị khóa' : $note->title;
                    $displayContent = ($note->is_locked && !$isUnlocked) ? 'Nội dung ẩn để bảo mật' : $note->content;
                @endphp

                <div class="masonry-item group cursor-pointer note-card rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden relative shadow-sm hover:shadow-md transition-all" 
                    data-id="{{ $note->id }}"
                    data-locked="{{ $note->is_locked ? 1 : 0 }}"
                    data-unlocked="{{ $isUnlocked ? 1 : 0 }}"
                    data-color="{{ $noteColor }}"
                    data-title="{{ e($displayTitle) }}"
                    data-content="{{ e($displayContent) }}" 
                    data-labels="{{ $note->labels->pluck('id')->join(',') }}"
                    data-pinned="{{ $note->is_pinned ? 1 : 0 }}"
                    data-timestamp="{{ $note->updated_at->timestamp }}"
                    data-created-at="{{ $note->created_at->diffForHumans() }}"                    
                    onclick="handleNoteClick(event, this, {{ $note->is_locked ? 'true' : 'false' }}, {{ $isUnlocked ? 'true' : 'false' }})" 
                    style="background-color: {{ $noteColor }} !important;">
                    
                    <div class="p-5">
                        {{--title + icon--}}
                        <div class="flex justify-between items-start mb-2 gap-4">
                            <div class="flex flex-col overflow-hidden">
                                <h2 class="note-title font-bold text-base {{ $noteUI->title }} leading-snug break-words">
                                    {{ $displayTitle ?: '(Không có tiêu đề)' }}
                                </h2>
                                <div class="flex items-center gap-1 text-[10px] {{ $noteUI->muted }} mt-1 font-bold uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                    {{ $note->updated_at->format('d/m/Y') }}
                                </div>
                            </div>

                            {{--ghim + khóa--}}
                            <div class="flex gap-1 shrink-0">
                                <form action="{{ route('notes.toggle-pin', $note->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="event.stopPropagation();" class="p-1.5 rounded-lg transition-all {{ $note->is_pinned ? 'text-yellow-500 bg-yellow-50' : 'text-slate-400 hover:bg-black/5 opacity-0 group-hover:opacity-100' }}">
                                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ $note->is_pinned ? 1 : 0 }};">push_pin</span>
                                    </button>
                                </form>

                                @if($note->is_locked)
                                    @if($isUnlocked)
                                        {{--trạng thái mở khóa--}}
                                        <form action="{{ route('notes.re-lock', $note->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="event.stopPropagation();" 
                                                    class="p-1.5 rounded-lg transition-all text-green-600 bg-green-50 hover:bg-green-100"
                                                    title="Bấm để KHÓA LẠI NGAY LẬP TỨC">
                                                <span class="material-symbols-outlined text-[20px]">lock_open</span>
                                            </button>
                                        </form>
                                    @else
                                        {{--trạng thái khóa--}}
                                        <button type="button" 
                                                onclick="event.stopPropagation(); openUnlockModal({{ $note->id }})" 
                                                class="p-1.5 rounded-lg transition-all text-red-500 bg-red-50 hover:bg-red-100"
                                                title="Ghi chú đang khóa. Bấm để MỞ KHÓA XEM">
                                            <span class="material-symbols-outlined text-[20px]">lock</span>
                                        </button>
                                    @endif
                                @else
                                    {{--trạng thái trống khóa--}}
                                    <button type="button" 
                                            onclick="event.stopPropagation(); openSetPasswordModal({{ $note->id }})" 
                                            class="p-1.5 rounded-lg transition-all text-slate-400 hover:bg-black/5 opacity-0 group-hover:opacity-100"
                                            title="Thiết lập mật khẩu bảo vệ">
                                        <span class="material-symbols-outlined text-[20px]">lock_open</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{--text--}}
                        <div class="mt-3">
                            @if($note->is_locked && !$isUnlocked)
                                {{--khóa--}}
                                <div class="py-6 flex flex-col items-center justify-center text-slate-400 bg-black/5 dark:bg-black/20 rounded-lg border border-dashed border-slate-300 dark:border-slate-600">
                                    <span class="material-symbols-outlined text-3xl mb-2">encrypted</span>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">Nội dung bị khóa</p>
                                </div>
                            @else
                                {{--k khóa--}}
                                @if($note->content)
                                    <p class="note-content line-clamp-5 text-sm {{ $noteUI->content }} leading-relaxed break-words">
                                        {{ $note->content }}
                                    </p>
                                @endif
                            @endif
                        </div>

                        {{--img--}}
                        @if(!($note->is_locked && !$isUnlocked) && $note->images->count() > 0)
                            <div class="grid grid-cols-{{ min($note->images->count(), 2) }} gap-1 mb-4 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-700 mt-3">
                                @foreach($note->images->take(4) as $image)
                                    <div class="relative aspect-video">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                                        @if($loop->iteration == 4 && $note->images->count() > 4)
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white text-xs font-bold">
                                                +{{ $note->images->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{--label--}}
                        @if($note->labels->count() > 0)
                            <div class="flex flex-wrap gap-1 mt-4">
                                @foreach($note->labels as $lbl)
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full text-white shadow-sm" style="background-color: {{ $lbl->color }}">
                                        {{ $lbl->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 text-slate-400">
                    <span class="material-symbols-outlined text-6xl mb-4 block">note_stack</span>
                    <p class="text-lg font-medium">Chưa có ghi chú nào</p>
                </div>
            @endforelse 

        {{--modal sửa/xóa gchu--}}
        <div id="editModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">            
            <form id="realEditForm" action="" method="POST" enctype="multipart/form-data" 
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden relative flex flex-col max-h-[90vh]">
                @csrf
                @method('PUT')

                <div class="p-5 overflow-y-auto space-y-4 flex-1">
                    <input type="hidden" id="editNoteId" name="id">

                    <input id="editTitle" name="title" type="text" placeholder="Tiêu đề"
                        class="w-full font-bold text-xl bg-transparent border-none outline-none dark:text-white focus:ring-0 px-0">               
                    <textarea id="editContent" name="content" rows="5" placeholder="Nội dung ghi chú..."
                        class="w-full text-sm bg-transparent border-none outline-none resize-none dark:text-slate-300 focus:ring-0 px-0 leading-relaxed"></textarea>
                    
                    {{--img--}}
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

                    {{-- tag/label--}}
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <p class="text-[11px] font-bold text-slate-400 uppercase mb-3 tracking-wider">Gán Nhãn</p>
                        <div class="flex flex-wrap gap-2" id="editLabelsContainer">
                            @foreach($labels ?? [] as $label)
                                <label class="flex items-center gap-1.5 ...">
                                    <input type="checkbox" name="label_ids[]" value="{{ $label->id }}" class="edit-label-cb ...">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $label->color }}"></span>
                                    {{ $label->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{--footer--}}
                <div class="px-5 py-4 bg-slate-50 dark:bg-slate-900/50 border-t flex justify-between items-center">
                    {{--trái: xóa gchu--}}
                    <button type="button" onclick="submitDelete()" class="text-sm text-red-500 font-bold flex items-center gap-1 hover:text-red-600 transition-all">
                        <span class="material-symbols-outlined text-base">delete</span> XÓA
                    </button>
                    
                    {{--phải--}}
                    <div class="flex items-center gap-3">
                        
                        {{--gỡ mk--}}
                        <div id="modalDisableLockContainer" class="hidden">
                            <button type="button" onclick="triggerDisableLockFromEdit()" 
                                    class="px-3 py-2 text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-950/30 dark:hover:bg-red-900/40 rounded-xl font-bold flex items-center gap-1 transition-all text-xs">
                                <span class="material-symbols-outlined text-[16px]">no_encryption</span>
                                Gỡ mật khẩu
                            </button>
                        </div>

                        {{--đóng--}}
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">Đóng</button>
                        
                        {{--lưu--}}
                        <button type="submit" class="px-6 py-2 text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md transition-all">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>

        <form id="deleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        
        {{--modal đặt/gỡ/mở pass gchu--}}
        <div id="unlockModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 dark:border-slate-800 m-4 animate-scaleUp">
                <div class="flex items-center gap-3 text-red-500 mb-4">
                    <span class="material-symbols-outlined text-3xl">lock</span>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Yêu cầu mật khẩu</h3>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Ghi chú này đã được bảo vệ. Vui lòng nhập mật khẩu riêng của ghi chú để xem nội dung.</p>
                
                {{-- Form gửi dữ liệu đến hàm unlock() --}}
                <form method="POST" action="">
                    @csrf
                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Nhập mật khẩu ghi chú" required
                            class="w-full rounded-xl border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('unlock_password')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('unlockModal').classList.replace('flex', 'hidden')" class="px-4 py-2 text-slate-500 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">Hủy</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-all">Mở khóa</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="setPasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 dark:border-slate-800 m-4">
                <div class="flex items-center gap-3 text-blue-600 mb-4">
                    <span class="material-symbols-outlined text-3xl">enhanced_encryption</span>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Đặt mật khẩu ghi chú</h3>
                </div>
                
                {{-- Form gửi dữ liệu đến hàm lock() --}}
                <form method="POST" action="">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Mật khẩu mới</label>
                            <input type="password" name="password" placeholder="Tối thiểu 4 ký tự" required
                                class="w-full rounded-xl border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu mới" required
                                class="w-full rounded-xl border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="document.getElementById('setPasswordModal').classList.replace('flex', 'hidden')" class="px-4 py-2 text-slate-500 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">Hủy</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-all">Kích hoạt khóa</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="disableLockModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 dark:border-slate-800 m-4">
                <div class="flex items-center gap-3 text-amber-500 mb-4">
                    <span class="material-symbols-outlined text-3xl">no_encryption</span>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tắt bảo vệ ghi chú</h3>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Để tắt tính năng bảo mật, vui lòng nhập lại mật khẩu hiện tại của ghi chú này để xác nhận.</p>
                
                {{-- Form gửi dữ liệu đến hàm disableLock() --}}
                <form method="POST" action="">
                    @csrf
                    <div class="mb-4">
                        <input type="password" name="confirm_password" placeholder="Nhập mật khẩu hiện tại" required
                            class="w-full rounded-xl border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('disableLockModal').classList.replace('flex', 'hidden')" class="px-4 py-2 text-slate-500 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">Hủy</button>
                        <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold shadow-lg shadow-amber-500/20 transition-all">Gỡ bỏ khóa</button>
                    </div>
                </form>
            </div>
        </div>

        <style>
            #notes-font-size {
                --size-base: {{ auth()->user()->font_size }}px;
                --size-title: {{ auth()->user()->font_size + 4 }}px;
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
        const CSRF_TOKEN = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</x-app-layout>