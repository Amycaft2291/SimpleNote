let currentSortOrder = 'newest';

function applyDynamicCardColors() {
    const cards = document.querySelectorAll('.note-card');

    cards.forEach(card => {
        const hexColor = (card.dataset.color || '#ffffff').toLowerCase();
        const titleEl = card.querySelector('.note-card-title');
        const contentEl = card.querySelector('.note-card-content');

        if (!titleEl || !contentEl) return;

        if (hexColor === '#1e293b') {
            titleEl.className = "note-card-title font-bold text-base leading-snug break-words text-slate-50";
            contentEl.className = "note-card-content line-clamp-5 text-sm leading-relaxed break-words text-slate-200";
        } else {
            // Tất cả các màu còn lại (Trắng mặc định hoặc 6 màu Pastel sáng) -> Dùng chung class chữ tối
            // Thêm dark: để tự động tương thích tốt nếu user bật giao diện Dark Mode hệ thống khi card màu trắng
            titleEl.className = "note-card-title font-bold text-base leading-snug break-words text-slate-900 dark:text-slate-100";
            contentEl.className = "note-card-content line-clamp-5 text-sm leading-relaxed break-words text-slate-600 dark:text-slate-300";
        }
    });
}

function openCreateForm() {
    document.getElementById('createPlaceholder').classList.add('hidden');
    const form = document.getElementById('createForm');
    form.classList.remove('hidden');
    form.querySelector('input[name="title"]').focus();
}

function selectCreateColor(btn, color) {
    document.getElementById('createNoteColorInput').value = color;
    btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('ring-2', 'ring-blue-500'));
    btn.classList.add('ring-2', 'ring-blue-500');
}

function closeCreateForm() {
    document.getElementById('createPlaceholder').classList.remove('hidden');
    document.getElementById('createForm').classList.add('hidden');
}

function handleNoteClick(event, card, isLocked, isUnlocked) {
    if (event.target.closest('button') || event.target.closest('form')) {
        return;
    }
    if (isLocked && !isUnlocked) {
        alert("Ghi chú này đang được bảo vệ. Vui lòng mở khóa trước.");
        return;
    }
    openEditModal(card);
}

function openEditModal(card) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('realEditForm');
    const deleteForm = document.getElementById('deleteForm');

    const id = card.dataset.id;
    const title = card.dataset.title;
    const content = card.dataset.content;
    const labelIds = card.dataset.labels ? card.dataset.labels.split(',') : [];
    const imagesData = JSON.parse(card.dataset.images || '[]');
    const noteColor = card.dataset.color || '#ffffff';

    document.getElementById('editNoteId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editContent').value = content;

    // Đồng bộ màu vào input ẩn của Modal Sửa
    const editColorInput = document.getElementById('editNoteColor');
    if (editColorInput) editColorInput.value = noteColor;

    // Kích hoạt vòng highlight ring xanh đúng màu đang sở hữu trong Modal Sửa
    const palette = document.getElementById('editColorPalette');
    if (palette) {
        palette.querySelectorAll('button').forEach(btn => {
            btn.classList.remove('ring-2', 'ring-blue-500');
            if (btn.dataset.color && btn.dataset.color.toLowerCase() === noteColor.toLowerCase()) {
                btn.classList.add('ring-2', 'ring-blue-500');
            }
        });
    }

    form.action = '/notes/' + id;
    if (deleteForm) deleteForm.action = '/notes/' + id;

    document.querySelectorAll('.edit-label-cb').forEach(cb => {
        cb.checked = labelIds.includes(cb.value);
    });

    const imgContainer = document.getElementById('editImagesContainer');
    document.getElementById('newImagesPreview').innerHTML = '';
    imgContainer.innerHTML = '';

    imagesData.forEach(img => {
        const div = document.createElement('div');
        div.className = 'relative aspect-square rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 group/img';
        div.innerHTML = `
            <img src="${img.path}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                <button type="button" onclick="submitDeleteImage(${img.id})" class="p-1.5 bg-red-500 text-white rounded-full shadow-lg">
                    <span class="material-symbols-outlined text-sm">delete</span>
                </button>
            </div>
        `;
        imgContainer.appendChild(div);
    });

    modal.classList.remove('hidden');
}

// BỔ SUNG: Hàm đổi màu và ghim ring xanh highlight khi bấm bảng màu trong MODAL SỬA
function selectEditColor(btn, color) {
    document.getElementById('editNoteColor').value = color;
    btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('ring-2', 'ring-blue-500'));
    btn.classList.add('ring-2', 'ring-blue-500');
}

function submitDeleteImage(imageId) {
    if (confirm('Bạn có chắc chắn muốn xóa ảnh này không?')) {
        const form = document.getElementById('deleteImageForm');
        if (form) {
            form.action = '/note-images/' + imageId;
            form.submit();
        } else {
            console.error("Không tìm thấy ảnh!");
        }
    }
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.classList.add('hidden');
}

function submitDelete() {
    if (confirm('Bạn có chắc chắn muốn xóa ghi chú này không?')) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.submit();
    }
}

function previewNewImages(input) {
    const previewContainer = document.getElementById('newImagesPreview');
    previewContainer.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement('div');
                div.className = 'relative aspect-square rounded-lg overflow-hidden border';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }
}

function setView(mode) {
    const container = document.getElementById('notesContainer');
    const gridBtn = document.getElementById('gridBtn');
    const listBtn = document.getElementById('listBtn');

    if (mode === 'grid') {
        container.classList.remove('list-view');
        container.classList.add('masonry-grid');
        gridBtn.classList.add('bg-blue-600', 'text-white');
        listBtn.classList.remove('bg-blue-600', 'text-white');
    } else {
        container.classList.remove('masonry-grid');
        container.classList.add('list-view');
        listBtn.classList.add('bg-blue-600', 'text-white');
        gridBtn.classList.remove('bg-blue-600', 'text-white');
    }
    localStorage.setItem('sn_view', mode);
}

function toggleSortOrder() {
    currentSortOrder = (currentSortOrder === 'newest') ? 'oldest' : 'newest';
    const label = document.getElementById('sortLabel');
    const icon = document.getElementById('sortIcon');

    if (label) label.innerText = (currentSortOrder === 'newest') ? 'Mới nhất' : 'Cũ nhất';
    if (icon) icon.innerText = (currentSortOrder === 'newest') ? 'south' : 'north';

    sortNotes();
}

function sortNotes() {
    const container = document.getElementById('notesContainer');
    if (!container) return;
    const cards = Array.from(container.getElementsByClassName('note-card'));

    cards.sort((a, b) => {
        const pinA = parseInt(a.dataset.pinned);
        const pinB = parseInt(b.dataset.pinned);
        if (pinA !== pinB) return pinB - pinA;

        const timeA = parseInt(a.dataset.timestamp);
        const timeB = parseInt(b.dataset.timestamp);
        return (currentSortOrder === 'newest') ? (timeB - timeA) : (timeA - timeB);
    });

    cards.forEach(card => container.appendChild(card));
}

document.addEventListener('DOMContentLoaded', () => {
    const savedView = localStorage.getItem('sn_view') || 'grid';
    setView(savedView);
    sortNotes();

    applyDynamicCardColors();
});