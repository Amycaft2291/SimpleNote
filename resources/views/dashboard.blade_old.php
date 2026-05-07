<x-app-layout>

{{-- ─────────────────────────────────────────────────────────────────────────
     FLASH / ACTIVATION STATUS
──────────────────────────────────────────────────────────────────────────── --}}
@if(session('status'))
    <div id="flashBanner"
         class="fixed top-16 left-1/2 -translate-x-1/2 z-50 bg-green-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow-lg transition-opacity duration-500">
        {{ session('status') }}
    </div>
    <script>setTimeout(()=>{ const b=document.getElementById('flashBanner'); if(b){b.style.opacity='0'; setTimeout(()=>b.remove(),500);} },3500);</script>
@endif

{{-- ─────────────────────────────────────────────────────────────────────────
     CREATE NOTE — QUICK INPUT BAR
──────────────────────────────────────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto mb-10">
    <div id="createBar"
         class="bg-white rounded-2xl shadow-sm border border-slate-200 transition-all hover:shadow-md">

        {{-- Collapsed placeholder --}}
        <div id="createPlaceholder"
             class="flex items-center justify-between p-4 cursor-pointer group text-slate-400 hover:text-slate-500"
             onclick="openCreateForm()">
            <span class="font-medium text-sm">Tạo ghi chú mới...</span>
            <div class="flex gap-1">
                <button class="p-2 hover:bg-slate-50 rounded-lg pointer-events-none">
                    <span class="material-symbols-outlined text-xl">check_box</span>
                </button>
                <button class="p-2 hover:bg-slate-50 rounded-lg pointer-events-none">
                    <span class="material-symbols-outlined text-xl">image</span>
                </button>
            </div>
        </div>

        {{-- Expanded form --}}
        <div id="createForm" class="hidden p-4 space-y-2">
            <input id="newTitle" type="text" placeholder="Tiêu đề"
                   class="w-full font-semibold text-slate-800 text-sm bg-transparent border-none outline-none placeholder-slate-400"
                   onkeydown="if(event.key==='Enter'){document.getElementById('newContent').focus();}">
            <textarea id="newContent" rows="4" placeholder="Nội dung ghi chú..."
                      class="w-full text-sm text-slate-600 bg-transparent border-none outline-none resize-none placeholder-slate-400"></textarea>
            <div class="flex justify-end gap-2 pt-1">
                <button onclick="closeCreateForm()"
                        class="px-4 py-1.5 text-sm text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                    Đóng
                </button>
                <button onclick="saveNote()"
                        class="px-4 py-1.5 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors">
                    Lưu
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────────
     TOOLBAR — TIÊU ĐỀ + GRID/LIST TOGGLE
──────────────────────────────────────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
            <span id="pageTitle">Tất cả ghi chú</span>
            <span id="noteCounter"
                  class="text-sm font-medium bg-slate-100 px-2.5 py-0.5 rounded-full text-slate-500">
                {{ $notes->count() }} ghi chú
            </span>
        </h1>
        <p class="text-xs text-slate-400 mt-1">Ghi chú đã ghim luôn được hiển thị ở trên cùng.</p>
    </div>

    <div class="flex items-center gap-2 bg-white p-1 rounded-xl shadow-sm border border-slate-100 w-fit">
        <div class="flex items-center border-r border-slate-100 pr-2 mr-1">
            <button id="gridViewBtn" onclick="setView('grid')"
                    class="p-1.5 rounded-lg bg-blue-50 text-blue-600" title="Grid view">
                <span class="material-symbols-outlined text-xl">grid_view</span>
            </button>
            <button id="listViewBtn" onclick="setView('list')"
                    class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-50" title="List view">
                <span class="material-symbols-outlined text-xl">view_list</span>
            </button>
        </div>
        <button onclick="toggleSort()"
                class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-lg">
            <span class="material-symbols-outlined text-sm">sort</span>
            <span id="sortText">Mới nhất</span>
            <span class="material-symbols-outlined text-sm">expand_more</span>
        </button>
    </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────────
     EMPTY STATE
──────────────────────────────────────────────────────────────────────────── --}}
<div id="emptyState" class="{{ $notes->isEmpty() ? '' : 'hidden' }} bg-white border border-dashed border-slate-300 rounded-2xl p-10 text-center mb-6">
    <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <span class="material-symbols-outlined text-slate-400 text-3xl">note_stack</span>
    </div>
    <h2 class="text-lg font-bold text-slate-800">Chưa có ghi chú nào</h2>
    <p class="text-sm text-slate-500 mt-1">Nhấn vào ô phía trên để tạo ghi chú đầu tiên!</p>
</div>

{{-- ─────────────────────────────────────────────────────────────────────────
     NOTES GRID / LIST
──────────────────────────────────────────────────────────────────────────── --}}
<style>
    .masonry-grid { column-count:1; column-gap:1.25rem; }
    @media(min-width:640px)  { .masonry-grid { column-count:2; } }
    @media(min-width:1024px) { .masonry-grid { column-count:3; } }
    @media(min-width:1280px) { .masonry-grid { column-count:4; } }
    .masonry-item { break-inside:avoid; margin-bottom:1.25rem; }
    .list-view { column-count:1 !important; }
    .list-view .masonry-item { margin-bottom:0.75rem; }
    .active-filter { background:#eff6ff; color:#2563eb; }
    .note-card-enter { animation: noteIn .25s ease both; }
    @keyframes noteIn { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
</style>

<div id="notesContainer" class="masonry-grid">
    @forelse($notes as $note)
        <div class="masonry-item group cursor-pointer note-card note-card-enter"
             data-id="{{ $note->id }}"
             data-pinned="{{ $note->is_pinned ? 'true' : 'false' }}"
             data-created="{{ $note->created_at->toISOString() }}"
             data-title="{{ addslashes($note->title) }}"
             data-content="{{ addslashes($note->content ?? '') }}"
             onclick="openEditModal(this)">
            <div class="note-inner bg-white rounded-xl shadow-[0_2px_12px_0_rgba(30,41,59,0.06)] border border-slate-100
                        hover:border-blue-200/60 hover:shadow-[0_8px_32px_0_rgba(30,41,59,0.10)] transition-all duration-200 overflow-hidden relative">

                {{-- Pin badge --}}
                @if($note->is_pinned)
                    <div class="absolute top-3 left-3 z-10">
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest">
                            ĐÃ GHIM
                        </span>
                    </div>
                @endif

                {{-- Pin toggle button --}}
                <button class="pin-btn absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity
                               p-1.5 rounded-lg border shadow-sm text-sm
                               {{ $note->is_pinned ? 'bg-yellow-50 border-yellow-200 text-yellow-600' : 'bg-white border-slate-100 text-slate-400 hover:text-yellow-500' }}"
                        onclick="event.stopPropagation(); pinNote(this, {{ $note->id }});"
                        title="{{ $note->is_pinned ? 'Bỏ ghim' : 'Ghim ghi chú' }}">
                    <span class="material-symbols-outlined text-sm leading-none"
                          style="font-variation-settings:'FILL' {{ $note->is_pinned ? 1 : 0 }}">push_pin</span>
                </button>

                <div class="p-5 {{ $note->is_pinned ? 'pt-8' : '' }}">
                    <h2 class="text-base font-bold text-slate-900 mb-1.5 leading-snug break-words">
                        {{ $note->title }}
                    </h2>
                    @if($note->content)
                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-4 break-words">
                            {{ $note->content }}
                        </p>
                    @endif
                    <p class="text-xs text-slate-300 mt-3">{{ $note->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    @empty
    @endforelse
</div>

{{-- ─────────────────────────────────────────────────────────────────────────
     MODAL — XEM / SỬA / XÓA GHI CHÚ
──────────────────────────────────────────────────────────────────────────── --}}
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeEditModal()"></div>

    {{-- Modal box --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-3 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Chỉnh sửa ghi chú</h3>
            <button onclick="closeEditModal()" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-4 space-y-3">
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Tiêu đề</label>
                <input id="editTitle" type="text"
                       class="w-full font-semibold text-slate-800 border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all"
                       placeholder="Tiêu đề ghi chú...">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Nội dung</label>
                <textarea id="editContent" rows="7"
                          class="w-full text-slate-600 border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 resize-none transition-all"
                          placeholder="Nội dung ghi chú..."></textarea>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between px-6 py-4 bg-slate-50 border-t border-slate-100">
            <button onclick="deleteNote()"
                    class="flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-base">delete</span>
                Xóa
            </button>
            <div class="flex gap-2">
                <button onclick="closeEditModal()"
                        class="px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                    Hủy
                </button>
                <button onclick="updateNote()"
                        class="px-5 py-2 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors">
                    Lưu thay đổi
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────────
     JAVASCRIPT
──────────────────────────────────────────────────────────────────────────── --}}
<script>
/* ─── STATE ─────────────────────────────────────────────────────────────── */
let currentNoteId  = null;
let sortNewestFirst = true;
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

/* ─── CREATE FORM ────────────────────────────────────────────────────────── */
function openCreateForm() {
    document.getElementById('createPlaceholder').classList.add('hidden');
    document.getElementById('createForm').classList.remove('hidden');
    document.getElementById('newTitle').focus();
}

function closeCreateForm() {
    document.getElementById('createPlaceholder').classList.remove('hidden');
    document.getElementById('createForm').classList.add('hidden');
    document.getElementById('newTitle').value = '';
    document.getElementById('newContent').value = '';
}

async function saveNote() {
    const title   = document.getElementById('newTitle').value.trim();
    const content = document.getElementById('newContent').value.trim();

    if (!title) {
        document.getElementById('newTitle').focus();
        return;
    }

    const res  = await fetch('{{ route('notes.store') }}', {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
        body: JSON.stringify({ title, content }),
    });
    const data = await res.json();

    if (data.success) {
        closeCreateForm();
        prependNoteCard(data.note);
        updateCounter(1);
    }
}

/* ─── ADD CARD TO DOM ───────────────────────────────────────────────────── */
function prependNoteCard(note) {
    const container = document.getElementById('notesContainer');
    document.getElementById('emptyState').classList.add('hidden');

    const div = document.createElement('div');
    div.className = 'masonry-item group cursor-pointer note-card note-card-enter';
    div.dataset.id      = note.id;
    div.dataset.pinned  = 'false';
    div.dataset.created = note.created_at;
    div.dataset.title   = note.title;
    div.dataset.content = note.content || '';
    div.setAttribute('onclick', 'openEditModal(this)');

    div.innerHTML = `
        <div class="note-inner bg-white rounded-xl shadow-[0_2px_12px_0_rgba(30,41,59,0.06)] border border-slate-100
                    hover:border-blue-200/60 hover:shadow-[0_8px_32px_0_rgba(30,41,59,0.10)] transition-all duration-200 overflow-hidden relative">
            <button class="pin-btn absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity
                           p-1.5 rounded-lg border border-slate-100 shadow-sm bg-white text-slate-400 hover:text-yellow-500"
                    onclick="event.stopPropagation(); pinNote(this, ${note.id});" title="Ghim ghi chú">
                <span class="material-symbols-outlined text-sm leading-none">push_pin</span>
            </button>
            <div class="p-5">
                <h2 class="text-base font-bold text-slate-900 mb-1.5 leading-snug break-words">${escHtml(note.title)}</h2>
                ${note.content ? `<p class="text-slate-500 text-sm leading-relaxed line-clamp-4 break-words">${escHtml(note.content)}</p>` : ''}
                <p class="text-xs text-slate-300 mt-3">Vừa xong</p>
            </div>
        </div>`;

    container.insertBefore(div, container.firstChild);
}

/* ─── EDIT MODAL ─────────────────────────────────────────────────────────── */
function openEditModal(el) {
    currentNoteId = el.dataset.id;
    document.getElementById('editTitle').value   = el.dataset.title || '';
    document.getElementById('editContent').value = el.dataset.content || '';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    currentNoteId = null;
}

async function updateNote() {
    if (!currentNoteId) return;

    const title   = document.getElementById('editTitle').value.trim();
    const content = document.getElementById('editContent').value.trim();

    if (!title) {
        document.getElementById('editTitle').focus();
        return;
    }

    const res  = await fetch(`/notes/${currentNoteId}`, {
        method: 'PUT',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
        body: JSON.stringify({ title, content }),
    });
    const data = await res.json();

    if (data.success) {
        // Cập nhật DOM card
        const card = document.querySelector(`.note-card[data-id="${currentNoteId}"]`);
        if (card) {
            card.dataset.title   = data.note.title;
            card.dataset.content = data.note.content || '';

            const h2 = card.querySelector('h2');
            if (h2) h2.textContent = data.note.title;

            const p = card.querySelector('.line-clamp-4');
            if (p) p.textContent = data.note.content || '';
        }
        closeEditModal();
        showToast('Đã lưu!');
    }
}

async function deleteNote() {
    if (!currentNoteId || !confirm('Xóa ghi chú này?')) return;

    const res  = await fetch(`/notes/${currentNoteId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
    });
    const data = await res.json();

    if (data.success) {
        const card = document.querySelector(`.note-card[data-id="${currentNoteId}"]`);
        if (card) card.remove();
        updateCounter(-1);
        closeEditModal();
        showToast('Đã xóa!', 'red');

        const remaining = document.querySelectorAll('.note-card').length;
        if (remaining === 0) document.getElementById('emptyState').classList.remove('hidden');
    }
}

/* ─── PIN ────────────────────────────────────────────────────────────────── */
async function pinNote(btn, id) {
    const res  = await fetch(`/notes/${id}/pin`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
    });
    const data = await res.json();

    if (data.success) {
        const card = document.querySelector(`.note-card[data-id="${id}"]`);
        if (!card) return;

        card.dataset.pinned = data.is_pinned ? 'true' : 'false';
        const inner = card.querySelector('.note-inner');
        const icon  = btn.querySelector('.material-symbols-outlined');

        if (data.is_pinned) {
            icon.style.fontVariationSettings = "'FILL' 1";
            btn.classList.add('bg-yellow-50','border-yellow-200','text-yellow-600');
            btn.classList.remove('bg-white','border-slate-100','text-slate-400');

            // Badge ghim
            if (!inner.querySelector('.pin-badge-label')) {
                const badge = document.createElement('div');
                badge.className = 'pin-badge-label absolute top-3 left-3 z-10';
                badge.innerHTML = '<span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest">ĐÃ GHIM</span>';
                inner.prepend(badge);
                inner.querySelector('.p-5').classList.add('pt-8');
            }

            // Di chuyển lên đầu
            document.getElementById('notesContainer').insertBefore(card, document.getElementById('notesContainer').firstChild);
        } else {
            icon.style.fontVariationSettings = "'FILL' 0";
            btn.classList.remove('bg-yellow-50','border-yellow-200','text-yellow-600');
            btn.classList.add('bg-white','border-slate-100','text-slate-400');

            const badge = inner.querySelector('.pin-badge-label');
            if (badge) badge.remove();
            inner.querySelector('.p-5')?.classList.remove('pt-8');
        }
    }
}

/* ─── GRID / LIST VIEW ───────────────────────────────────────────────────── */
function setView(mode) {
    const container = document.getElementById('notesContainer');

    if (mode === 'grid') {
        container.classList.remove('list-view');
        container.classList.add('masonry-grid');
        document.getElementById('gridViewBtn').className = 'p-1.5 rounded-lg bg-blue-50 text-blue-600';
        document.getElementById('listViewBtn').className = 'p-1.5 rounded-lg text-slate-400 hover:bg-slate-50';
    } else {
        container.classList.remove('masonry-grid');
        container.classList.add('list-view');
        document.getElementById('listViewBtn').className = 'p-1.5 rounded-lg bg-blue-50 text-blue-600';
        document.getElementById('gridViewBtn').className = 'p-1.5 rounded-lg text-slate-400 hover:bg-slate-50';
    }
    localStorage.setItem('sn_view', mode);
}

/* ─── SORT ───────────────────────────────────────────────────────────────── */
function toggleSort() {
    sortNewestFirst = !sortNewestFirst;
    document.getElementById('sortText').textContent = sortNewestFirst ? 'Mới nhất' : 'Cũ nhất';

    const container = document.getElementById('notesContainer');
    const cards = Array.from(document.querySelectorAll('.note-card'));

    cards.sort((a, b) => {
        const aPinned = a.dataset.pinned === 'true';
        const bPinned = b.dataset.pinned === 'true';
        if (aPinned && !bPinned) return -1;
        if (!aPinned && bPinned) return  1;

        const aTime = new Date(a.dataset.created).getTime();
        const bTime = new Date(b.dataset.created).getTime();
        return sortNewestFirst ? bTime - aTime : aTime - bTime;
    });

    cards.forEach(c => container.appendChild(c));
}

/* ─── SEARCH (live, client-side) ─────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('[data-search-input]');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            let visible = 0;

            document.querySelectorAll('.note-card').forEach(card => {
                const title   = (card.dataset.title   || '').toLowerCase();
                const content = (card.dataset.content || '').toLowerCase();
                const match   = !q || title.includes(q) || content.includes(q);

                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            updateCounter(0, visible);
        });
    }

    // Restore view preference
    const savedView = localStorage.getItem('sn_view') || 'grid';
    setView(savedView);
});

/* ─── HELPERS ────────────────────────────────────────────────────────────── */
function updateCounter(delta, absolute) {
    const el = document.getElementById('noteCounter');
    if (!el) return;
    if (typeof absolute === 'number') {
        el.textContent = `${absolute} ghi chú`;
        return;
    }
    const match = el.textContent.match(/\d+/);
    const cur   = match ? parseInt(match[0]) : 0;
    el.textContent = `${Math.max(0, cur + delta)} ghi chú`;
}

function escHtml(str) {
    return (str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function showToast(msg, color) {
    const toast = document.createElement('div');
    const bg    = color === 'red' ? 'bg-red-600' : 'bg-green-600';
    toast.className = `fixed bottom-8 left-1/2 -translate-x-1/2 z-50 ${bg} text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow-lg transition-opacity duration-500`;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 500); }, 2000);
}

// Close modal on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeEditModal();
});
</script>

</x-app-layout>
