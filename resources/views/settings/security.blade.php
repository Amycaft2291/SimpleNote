<x-app-layout>

<div class="max-w-6xl mx-auto p-4 md:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div>
            @include('settings.sidebar')
        </div>

        <div class="lg:col-span-3">

            @if(session('status'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-4">
                    ✅ {{ session('status') }}
                </div>
            @endif

            <form method="POST"
                  action="{{ route('settings.password.update') }}"
                  class="space-y-6">

                @csrf
                @method('PATCH')

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border dark:border-slate-700">

                    <h2 class="text-2xl font-bold mb-2 dark:text-white">Bảo mật</h2>
                    <p class="text-slate-500 text-sm mb-6">
                        Mật khẩu mới phải có ít nhất 8 ký tự.
                    </p>

                    <div class="space-y-5">

                        {{-- Mật khẩu hiện tại --}}
                        <div>
                            <label class="font-medium dark:text-white">Mật khẩu hiện tại</label>
                            <input
                                type="password"
                                name="current_password"
                                autocomplete="current-password"
                                class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mật khẩu mới --}}
                        <div>
                            <label class="font-medium dark:text-white">Mật khẩu mới</label>
                            <input
                                type="password"
                                name="password"
                                autocomplete="new-password"
                                class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Xác nhận mật khẩu --}}
                        <div>
                            <label class="font-medium dark:text-white">Xác nhận mật khẩu mới</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                autocomplete="new-password"
                                class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>

                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium transition">
                        Đổi mật khẩu
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

</x-app-layout>