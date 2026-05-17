<x-settings-layout>

<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold dark:text-white">
            Bảo mật
        </h1>

        <p class="text-slate-500 mt-1">
            Cập nhật mật khẩu và bảo vệ tài khoản của bạn
        </p>
    </div>

    @if(session('status'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="passwordForm"
          method="POST"
          action="{{ route('settings.password.update') }}"
          class="space-y-6">

        @csrf
        @method('PATCH')

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm">

            <h2 class="text-2xl font-bold dark:text-white mb-2">
                Đổi mật khẩu
            </h2>

            <p class="text-slate-500 text-sm mb-6">
                Mật khẩu mới phải có ít nhất 8 ký tự.
            </p>

            <div class="space-y-5">

                <div>
                    <label class="font-medium dark:text-white">
                        Mật khẩu hiện tại
                    </label>

                    <input type="password"
                           name="current_password"
                           id="current_password"
                           autocomplete="current-password"
                           required
                           class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-red-500">
                </div>

                <div>
                    <label class="font-medium dark:text-white">
                        Mật khẩu mới
                    </label>

                    <input type="password"
                           name="password"
                           id="password"
                           autocomplete="new-password"
                           required
                           class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-red-500">
                </div>

                <div>
                    <label class="font-medium dark:text-white">
                        Xác nhận mật khẩu mới
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           autocomplete="new-password"
                           required
                           class="w-full mt-2 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-red-500">
                </div>

            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit"
                    class="px-6 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                Đổi mật khẩu
            </button>

        </div>

    </form>

</div>

<script>
    const form = document.getElementById('passwordForm');
    const resetBtn = document.getElementById('resetBtn');

    const defaultValues = {
        current_password: "",
        password: "",
        password_confirmation: ""
    };

    resetBtn.addEventListener('click', function () {
        form.current_password.value = defaultValues.current_password;
        form.password.value = defaultValues.password;
        form.password_confirmation.value = defaultValues.password_confirmation;
    });
</script>

</x-settings-layout>