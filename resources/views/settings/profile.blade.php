<x-settings-layout>
    <div class="space-y-8">
        {{-- HEADER --}}
        <div>
            <h1 class="text-3xl font-bold dark:text-white">
                Hồ sơ tài khoản
            </h1>
        </div>

        {{-- SUCCESS --}} @if(session('status'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">
            {{ session('status') }}
        </div>
        @endif {{-- ERRORS --}} @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        @endif {{-- FORM --}}
        <form id="profileForm" method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf @method('PATCH') {{-- PROFILE CARD --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border dark:border-slate-700 shadow-sm">
                {{-- TOP USER INFO --}}
                <div class="flex items-center gap-6 mb-8">
                    {{-- AVATAR --}}
                    <div class="relative group w-20 h-20">
                        @if($user->avatar)
                        <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm" />
                        @else
                        <div
                            id="avatarPreview"
                            class="w-20 h-20 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center text-white text-2xl font-semibold select-none"
                            style="background-color: #7494ec;"
                        >
                            {{ strtoupper(mb_substr($user->display_name, 0, 1)) }}
                        </div>
                        @endif {{-- Overlay upload --}}
                        <label class="absolute inset-0 flex items-center justify-center bg-black/40 text-white opacity-0 group-hover:opacity-100 rounded-full cursor-pointer transition">
                            <i class="bi bi-camera-fill text-lg"></i>

                            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" />
                        </label>
                    </div>

                    {{-- USER INFO --}}
                    <div>
                        <h2 class="text-2xl font-bold dark:text-white">
                            {{ $user->display_name }}
                        </h2>

                        <p class="text-slate-500 text-sm mt-1">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>

                {{-- INPUTS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NAME --}}
                    <div class="bg-slate-50 dark:bg-slate-900/40 p-5 rounded-2xl border dark:border-slate-700">
                        <label class="text-sm font-semibold dark:text-white">
                            Tên hiển thị
                        </label>

                        <input
                            type="text"
                            name="display_name"
                            value="{{ old('display_name', $user->display_name) }}"
                            class="w-full mt-3 px-4 py-3 rounded-xl border dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    {{-- EMAIL --}}
                    <div class="bg-slate-50 dark:bg-slate-900/40 p-5 rounded-2xl border dark:border-slate-700">
                        <label class="text-sm font-semibold dark:text-white">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-3 px-4 py-3 rounded-xl border dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500" />

                        @if(!$user->hasVerifiedEmail())
                        <p class="mt-3 text-xs text-yellow-600 bg-yellow-50 dark:bg-yellow-500/10 p-2 rounded-lg">
                            Email chưa xác minh
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- NOTES --}}
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border dark:border-slate-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600">description</span>
                    </div>

                    <div>
                        <p class="text-slate-500 text-sm">Tổng ghi chú</p>
                        <p class="text-2xl font-bold dark:text-white mt-1">
                            {{ $user->notes()->count() }}
                        </p>
                    </div>
                </div>

                {{-- LABELS --}}
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border dark:border-slate-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600">label</span>
                    </div>

                    <div>
                        <p class="text-slate-500 text-sm">Nhãn đã tạo</p>
                        <p class="text-2xl font-bold dark:text-white mt-1">
                            {{ $user->labels()->count() }}
                        </p>
                    </div>
                </div>

                {{-- CREATED DATE --}}
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border dark:border-slate-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600">event</span>
                    </div>

                    <div>
                        <p class="text-slate-500 text-sm">Tạo tài khoản</p>
                        <p class="text-lg font-bold dark:text-white mt-1">
                            {{ $user->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
            {{-- BUTTONS --}}
            <div class="flex items-center justify-between pt-2">
                {{-- LEFT --}}
                <button type="button" id="resetBtn" class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:text-white font-medium transition">
                    Hủy bỏ thay đổi
                </button>

                {{-- RIGHT --}}
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-sm transition">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    {{-- AVATAR PREVIEW + RESET --}}
<script>
    const avatarInput  = document.getElementById('avatarInput');
    const resetBtn     = document.getElementById('resetBtn');
    const profileForm  = document.getElementById('profileForm');

    // ── Giá trị gốc để reset ──────────────────────────────────────
    const originalDisplayName = "{{ old('display_name', $user->display_name) }}";
    const originalEmail       = "{{ old('email', $user->email) }}";

    // ── Preview ảnh khi chọn file ─────────────────────────────────
    avatarInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();

       reader.onload = function (e) {

    let preview = document.getElementById('avatarPreview');

    // Nếu đang là <div> chữ cái → thay bằng <img>
    if (preview.tagName === 'IMG') {

        const img = document.createElement('img');

        img.id = 'avatarPreview';

        img.className =
            'w-20 h-20 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm';

        preview.replaceWith(img);

        preview = img;
    }

    preview.src = e.target.result;
};

        reader.readAsDataURL(file);
    });

    // ── Hủy bỏ thay đổi ──────────────────────────────────────────
    resetBtn.addEventListener('click', function () {
        // Reset text fields
        profileForm.querySelector('[name="display_name"]').value = originalDisplayName;
        profileForm.querySelector('[name="email"]').value        = originalEmail;

        // Reset avatar input
        avatarInput.value = '';

        // Khôi phục preview về trạng thái ban đầu
        const preview = document.getElementById('avatarPreview');

        @if($user->avatar)
            // Có ảnh thật → giữ nguyên src gốc
            if (preview.tagName === 'IMG') {
                preview.src = "{{ asset('storage/' . $user->avatar) }}";
            }
        @else
            // Chưa có ảnh → khôi phục về div chữ cái
            if (preview.tagName === 'IMG') {
                const div = document.createElement('div');
                div.id        = 'avatarPreview';
                div.className = 'w-20 h-20 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center text-white text-2xl font-semibold select-none';
                div.style.backgroundColor = '#7494ec';
                div.textContent = "{{ strtoupper(mb_substr($user->display_name, 0, 1)) }}";
                preview.replaceWith(div);
            }
        @endif
    });
</script>
</x-settings-layout>
