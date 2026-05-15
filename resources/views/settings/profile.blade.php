<x-settings-layout>

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold dark:text-white">
            Hồ sơ tài khoản
        </h1>

        <p class="text-slate-500 mt-1">
            Quản lý thông tin cá nhân của bạn
        </p>
    </div>

    {{-- SUCCESS --}}
    @if(session('status'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">
            {{ session('status') }}
        </div>
    @endif

    {{-- ERRORS --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form id="profileForm"
          method="POST"
          action="{{ route('settings.profile.update') }}"
          enctype="multipart/form-data"
          class="space-y-6">

        @csrf
        @method('PATCH')

        {{-- PROFILE CARD --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm">

            {{-- USER + AVATAR --}}
            <div class="flex items-center gap-6 mb-8">

                {{-- AVATAR --}}
                <div class="relative">

                    <img
                        id="avatarPreview"
                        src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                        class="w-16 h-16 rounded-full object-cover border"
                    >

                    {{-- INPUT FILE --}}
                    <label class="absolute -bottom-1 -right-1 bg-blue-600 text-white p-1 rounded-full cursor-pointer text-xs">
                        ✎
                        <input type="file"
                               id="avatarInput"
                               name="avatar"
                               accept="image/*"
                               class="hidden">
                    </label>

                </div>

                {{-- INFO --}}
                <div>
                    <h3 class="font-bold text-xl dark:text-white">
                        {{ $user->name }}
                    </h3>

                    <p class="text-slate-500 text-sm">
                        {{ $user->email }}
                    </p>

                    {{-- RESET AVATAR --}}
                    <button type="button"
                            id="resetAvatarBtn"
                            class="text-xs text-red-500 mt-1 hover:underline">
                        Hủy thay đổi ảnh
                    </button>
                </div>

            </div>

            {{-- INPUTS --}}
            <div class="space-y-5">

                {{-- NAME --}}
                <div>
                    <label class="text-sm font-medium dark:text-white">
                        Tên hiển thị
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           required
                           class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="text-sm font-medium dark:text-white">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                    @if(!$user->hasVerifiedEmail())
                        <div class="mt-2 text-sm text-yellow-600">
                            Email chưa xác minh.
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border dark:border-slate-700">
                <div class="text-sm text-slate-500 mb-1">Tổng ghi chú</div>
                <div class="text-3xl font-bold dark:text-white">
                    {{ $user->notes()->count() }}
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border dark:border-slate-700">
                <div class="text-sm text-slate-500 mb-1">Nhãn đã tạo</div>
                <div class="text-3xl font-bold dark:text-white">
                    {{ $user->labels()->count() }}
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border dark:border-slate-700">
                <div class="text-sm text-slate-500 mb-1">Thành viên từ</div>
                <div class="text-lg font-bold dark:text-white">
                    {{ $user->created_at->format('d/m/Y') }}
                </div>
            </div>

        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-3">

            {{-- HỦY --}}
            <button type="button"
                    id="resetBtn"
                    class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:text-white font-medium transition">
                Hủy bỏ thay đổi
            </button>

            {{-- LƯU --}}
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                Lưu thay đổi
            </button>

        </div>

    </form>

</div>

{{-- JS --}}
<script>
    const form = document.getElementById('profileForm');

    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');

    const resetBtn = document.getElementById('resetBtn');
    const resetAvatarBtn = document.getElementById('resetAvatarBtn');

    // trạng thái ban đầu
    const defaultAvatar = avatarPreview.src;
    const defaultName = form.name.value;
    const defaultEmail = form.email.value;

    // preview avatar
    avatarInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => avatarPreview.src = e.target.result;
            reader.readAsDataURL(file);
        }
    });

    // RESET TOÀN FORM
    resetBtn.addEventListener('click', function () {
        form.name.value = defaultName;
        form.email.value = defaultEmail;

        avatarInput.value = "";
        avatarPreview.src = defaultAvatar;
    });

    // RESET CHỈ AVATAR
    resetAvatarBtn.addEventListener('click', function () {
        avatarInput.value = "";
        avatarPreview.src = defaultAvatar;
    });
</script>

</x-settings-layout>