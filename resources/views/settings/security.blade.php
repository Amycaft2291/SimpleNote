<x-settings-layout>

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold dark:text-white">
            Bảo mật
        </h1>

        <p class="text-slate-500 mt-1">
            Cập nhật mật khẩu và bảo vệ tài khoản của bạn
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
          action="{{ route('settings.password.update') }}"
          class="space-y-6">

        @csrf
        @method('PATCH')

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700">

            <h2 class="text-2xl font-bold mb-2 dark:text-white">
                Đổi mật khẩu
            </h2>

            <p class="text-slate-500 text-sm mb-6">
                Mật khẩu mới phải có ít nhất 8 ký tự.
            </p>

            <div class="space-y-5">

                {{-- CURRENT PASSWORD --}}
                <div>

                    <label class="font-medium dark:text-white">
                        Mật khẩu hiện tại
                    </label>

                    <input
                        type="password"
                        name="current_password"
                        autocomplete="current-password"
                        required
                        class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- NEW PASSWORD --}}
                <div>

                    <label class="font-medium dark:text-white">
                        Mật khẩu mới
                    </label>

                    <input
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        required
                        class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- CONFIRM PASSWORD --}}
                <div>

                    <label class="font-medium dark:text-white">
                        Xác nhận mật khẩu mới
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        required
                        class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                </div>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end">

            <button
                type="submit"
                class="px-6 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-semibold transition">

                Đổi mật khẩu

            </button>

        </div>

    </form>

</div>

</x-settings-layout>