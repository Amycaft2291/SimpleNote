let currentSortOrder = 'newest';

function openCreateForm() {
    document.getElementById('createPlaceholder').classList.add('hidden');
    const form = document.getElementById('createForm');
    form.classList.remove('hidden');
    form.querySelector('input[name="title"]').focus();
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

    document.getElementById('editNoteId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editContent').value = content;

    form.action = '/notes/' + id;
    if (deleteForm) {
        deleteForm.action = '/notes/' + id;
    }

    document.querySelectorAll('.edit-label-cb').forEach(cb => {
        cb.checked = labelIds.includes(cb.value);
    });

    modal.classList.remove('hidden');
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
});