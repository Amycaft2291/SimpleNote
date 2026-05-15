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

    <form method="POST"
          action="{{ route('settings.profile.update') }}"
          class="space-y-6">

        @csrf
        @method('PATCH')

        {{-- PROFILE CARD --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm">

            {{-- USER --}}
            <div class="flex items-center gap-4 mb-8">

                <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>

                <div>
                    <h3 class="font-bold text-xl dark:text-white">
                        {{ $user->name }}
                    </h3>

                    <p class="text-slate-500 text-sm">
                        {{ $user->email }}
                    </p>
                </div>

            </div>

            <div class="space-y-5">

                {{-- NAME --}}
                <div>
                    <label class="text-sm font-medium dark:text-white">
                        Tên hiển thị
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                    @error('name')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="text-sm font-medium dark:text-white">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                    @if(!$user->hasVerifiedEmail())
                        <div class="mt-2 text-sm text-yellow-600">

                            Email chưa xác minh.

                            <form method="POST"
                                  action="{{ route('verification.send') }}"
                                  class="inline">

                                @csrf

                                <button type="submit"
                                        class="underline hover:text-yellow-800">
                                    Gửi lại email xác minh
                                </button>

                            </form>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border dark:border-slate-700">
                <div class="text-sm text-slate-500 mb-1">
                    Tổng ghi chú
                </div>

                <div class="text-3xl font-bold dark:text-white">
                    {{ $user->notes()->count() }}
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border dark:border-slate-700">
                <div class="text-sm text-slate-500 mb-1">
                    Nhãn đã tạo
                </div>

                <div class="text-3xl font-bold dark:text-white">
                    {{ $user->labels()->count() }}
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border dark:border-slate-700">
                <div class="text-sm text-slate-500 mb-1">
                    Thành viên từ
                </div>

                <div class="text-lg font-bold dark:text-white">
                    {{ $user->created_at->format('d/m/Y') }}
                </div>
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end">

            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition">

                Lưu thay đổi

            </button>

        </div>

    </form>

</div>

</x-settings-layout>