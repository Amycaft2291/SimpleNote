<x-app-layout>
{{--khung nhập gchu nhanh--}}
<div class="max-w-2xl mx-auto mb-10">
    <div id="createBar" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-all hover:shadow-md">
        <div id="createPlaceholder" class="flex items-center justify-between p-4 cursor-pointer text-slate-400" onclick="openCreateForm()">
            <span class="font-medium text-sm">Tạo ghi chú mới...</span>
            <span class="material-symbols-outlined">image</span>
        </div>

        <div id="createForm" class="hidden p-4 space-y-3">
            <input id="newTitle" type="text" placeholder="Tiêu đề" class="w-full font-bold text-lg bg-transparent border-none outline-none dark:text-white focus:ring-0 px-0">
            <textarea id="newContent" rows="3" placeholder="Nội dung ghi chú..." class="w-full text-sm bg-transparent border-none outline-none resize-none dark:text-slate-300 focus:ring-0 px-0"></textarea>
            
            <div>
                <label class="text-xs font-bold text-slate-400 uppercase block mb-1">Ảnh đính kèm</label>
                <input type="file" id="newImages" multiple accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-blue-300">
            </div>
            
            <div class="border-t border-slate-100 dark:border-slate-700 pt-3">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Gán Nhãn</p>
                <div class="flex flex-wrap gap-2">
                    @isset($labels)
                        @foreach($labels as $label)
                        <label class="flex items-center gap-1.5 text-xs bg-slate-100 dark:bg-slate-700 dark:text-slate-300 px-2 py-1 rounded-lg cursor-pointer hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                            <input type="checkbox" value="{{ $label->id }}" class="new-label-cb rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $label->color }}"></span>
                            {{ $label->name }}
                        </label>
                        @endforeach
                    @endisset
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button onclick="closeCreateForm()" class="px-4 py-1.5 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">Đóng</button>
                <button onclick="saveNote()" class="px-4 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu</button>
            </div>
        </div>
    </div>
</div>

{{--toolbar tùy chỉnh hiển thị v sắp xếp--}}
<div class="flex flex-wrap items-center justify-between mb-6 gap-4">
    <h1 class="text-2xl font-black dark:text-white" id="pageTitle">Tất cả ghi chú</h1>
    
    <div class="flex items-center gap-3 bg-white dark:bg-slate-800 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
        {{-- Sắp xếp --}}
        <div class="flex items-center border-r border-slate-100 dark:border-slate-700 pr-2 mr-2">
            <button onclick="toggleSortOrder()" id="sortBtn" title="Đổi thứ tự sắp xếp" class="p-1.5 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg flex items-center gap-1 transition-all">
                <span class="material-symbols-outlined text-sm" id="sortIcon">south</span>
                <span class="text-xs font-bold uppercase" id="sortLabel">Mới nhất</span>
            </button>
        </div>

        {{-- Grid/List View --}}
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
        <div class="masonry-item group cursor-pointer note-card" 
             data-id="{{ $note->id }}" 
             data-title="{{ e($note->title) }}"
             data-content="{{ e($note->content) }}"
             data-labels="{{ $note->labels->pluck('id')->join(',') }}"
             data-images="{{ $note->images->toJson() }}"
             data-pinned="{{ $note->is_pinned ? 1 : 0 }}"
             data-timestamp="{{ $note->updated_at->timestamp }}"
             onclick="openEditModal(this)">
            
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden relative shadow-sm hover:shadow-md transition-all">
                {{--nút ghim--}}
                <button 
                    onclick="event.stopPropagation(); pinNote(this, {{ $note->id }})"
                    class="absolute top-2 right-2 z-10 p-1 rounded-full opacity-0 group-hover:opacity-100 transition-all
                    {{ $note->is_pinned ? '!opacity-100 text-yellow-500' : 'text-slate-400 hover:text-yellow-500 bg-white/80 dark:bg-slate-800/80' }}"
                    title="{{ $note->is_pinned ? 'Bỏ ghim' : 'Ghim ghi chú' }}">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' {{ $note->is_pinned ? 1 : 0 }}, 'wght' 400, 'GRAD' 0, 'opsz' 24;">push_pin</span>
                </button>

                @if($note->images->count() > 0)
                <div class="w-full h-40 overflow-hidden bg-slate-100 dark:bg-slate-700">
                    <img src="{{ asset('storage/' . $note->images->first()->image_path) }}" class="w-full h-full object-cover">
                </div>
                @endif

                <div class="p-4">
                    @if($note->title)
                    <h2 class="note-title font-bold mb-1 dark:text-white leading-snug">{{ $note->title }}</h2>
                    @else
                    <h2 class="note-title hidden"></h2>
                    @endif
                    <p class="note-content line-clamp-5 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $note->content }}</p>
                    
                    @if($note->labels->count() > 0)
                    <div class="flex flex-wrap gap-1 mt-3">
                        @foreach($note->labels as $lbl)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-white" style="background-color: {{ $lbl->color }}">{{ $lbl->name }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-4 text-center py-20 text-slate-400">
            <span class="material-symbols-outlined text-6xl mb-4 block">note_stack</span>
            <p class="text-lg font-medium">Chưa có ghi chú nào</p>
            <p class="text-sm mt-1">Bấm "Tạo ghi chú mới" để bắt đầu!</p>
        </div>
    @endforelse
</div>

{{--modal sửa/xóa gchu--}}
<div id="editModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden relative flex flex-col max-h-[90vh]">
        
        <div class="p-5 overflow-y-auto space-y-4 flex-1">
            <input type="hidden" id="editNoteId">

            {{--tiêu đề--}}
            <input id="editTitle" type="text" placeholder="Tiêu đề"
                class="w-full font-bold text-xl bg-transparent border-none outline-none dark:text-white focus:ring-0 px-0">
            
            {{--nd--}}
            <textarea id="editContent" rows="5" placeholder="Nội dung ghi chú..."
                class="w-full text-sm bg-transparent border-none outline-none resize-none dark:text-slate-300 focus:ring-0 px-0 leading-relaxed"></textarea>
            
            {{--ảnh/img--}}
            <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                <p class="text-xs font-bold text-slate-400 uppercase mb-3">Ảnh đính kèm</p>
                
                {{--ds ảnh hiện tại --}}
                <div id="editImagesContainer" class="grid grid-cols-3 gap-2 mb-3"></div>
                
                {{--upload ảnh mới --}}
                <label class="flex items-center gap-2 cursor-pointer text-sm text-blue-600 hover:text-blue-700 font-medium">
                    <span class="material-symbols-outlined text-base">add_photo_alternate</span>
                    Thêm ảnh mới
                    <input type="file" id="editImagesInput" multiple accept="image/*" class="hidden" onchange="previewNewImages(this)">
                </label>
                
                {{--xem trc ảnh mới --}}
                <div id="newImagesPreview" class="grid grid-cols-3 gap-2 mt-2"></div>
            </div>

            {{--nhãn/tag--}}
            <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                <p class="text-xs font-bold text-slate-400 uppercase mb-3">Gán Nhãn</p>
                <div class="flex flex-wrap gap-2" id="editLabelsContainer">
                    @isset($labels)
                        @foreach($labels as $label)
                        <label class="flex items-center gap-1.5 text-xs bg-slate-100 dark:bg-slate-700 dark:text-slate-300 px-2 py-1 rounded-lg cursor-pointer hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors has-[:checked]:bg-blue-100 has-[:checked]:text-blue-700 dark:has-[:checked]:bg-blue-900/40 dark:has-[:checked]:text-blue-300 has-[:checked]:ring-1 has-[:checked]:ring-blue-400">
                            <input type="checkbox" value="{{ $label->id }}" class="edit-label-cb rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $label->color }}"></span>
                            {{ $label->name }}
                        </label>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>

        {{-- Footer actions --}}
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

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

window.NoteLabels = @json($labels ?? []);

/*biến toàn cục vs trạng thái*/
let selectedLabels = [];
let removeImageIds = [];
let currentSortOrder = 'newest';
let newSelectedFiles = [];

/*thêm gchu*/
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
        const res = await fetch('{{ route("notes.store") }}', {
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

/*sửa/xóa gchu*/
function openEditModal(card) {
    //chuyển nd từ note lên modal
    document.getElementById('editNoteId').value = card.dataset.id;
    document.getElementById('editTitle').value = card.dataset.title;
    document.getElementById('editContent').value = card.dataset.content;

    //ktra tag htại của note
    const noteLabels = card.dataset.labels ? card.dataset.labels.split(',') : [];
    document.querySelectorAll('.edit-label-cb').forEach(cb => {
        cb.checked = noteLabels.includes(cb.value);
    });

    //chuyển dữ liệu ảnh của note khác trong lần chỉnh sửa trước sang note cần sửa hiện tại
    removeImageIds = [];
    newSelectedFiles = [];
    const images = JSON.parse(card.dataset.images || '[]');
    const imgContainer = document.getElementById('editImagesContainer');
    imgContainer.innerHTML = '';

    images.forEach(img => {
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

    //reset phần thêm ảnh trong trường hợp chưa lưu lại edit trước trong note (cũ lẫn mới)
    document.getElementById('editImagesInput').value = '';
    document.getElementById('newImagesPreview').innerHTML = '';

    document.getElementById('editModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Đóng modal khi bấm ra ngoài
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

function markImageRemove(imgId, element) {
    removeImageIds.push(imgId);
    element.remove();
}

function previewNewImages(input) {
    const preview = document.getElementById('newImagesPreview');
    
    // Đẩy file mới vào mảng newSelectedFiles và render thêm thay vì ghi đè HTML
    Array.from(input.files).forEach(file => {
        newSelectedFiles.push(file); // Lưu vào mảng tạm
        
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
    
    // Quan trọng: Reset input để browser cho phép user chọn lại file nếu muốn hoặc mở dialog lần sau mà không lỗi
    input.value = ''; 
}

// [THÊM HÀM MỚI NÀY] Hỗ trợ người dùng đổi ý xóa ảnh vừa mới thêm
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

    // Ảnh mới
    for (let i = 0; i < newSelectedFiles.length; i++) {
        formData.append('images[]', newSelectedFiles[i]);
    }

    // Nhãn được chọn
    document.querySelectorAll('.edit-label-cb:checked').forEach(cb => {
        formData.append('label_ids[]', cb.value);
    });

    // ID ảnh cần xóa
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

async function pinNote(btn, id) {
    const res = await fetch(`/notes/${id}/pin`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
    });
    if (res.ok) window.location.reload();
}

/* ==========================================
   4. LỌC ĐA LỰA CHỌN & TÌM KIẾM
   ========================================== */
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
    // Reset tất cả checkbox sidebar
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

/* ==========================================
   5. VIEW & SẮP XẾP DOM
   ========================================== */
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

// Khởi tạo
document.addEventListener('DOMContentLoaded', () => {
    const savedView = localStorage.getItem('sn_view') || 'grid';
    setView(savedView);
    sortNotes();
});
</script>
</x-app-layout>