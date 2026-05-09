<x-app-layout>
<div class="max-w-5xl mx-auto p-4 md:p-8">

    {{-- TITLE --}}
    <div class="mb-6">
        <h4 class="text-2xl font-bold dark:text-white">Tùy chọn tài khoản</h4>
        <p class="text-sm text-slate-500">
            Quản lý hồ sơ, giao diện, bảo mật và thông báo của bạn
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        {{-- SIDEBAR --}}
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 shadow-sm space-y-2">

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-sm">
                    <span class="material-symbols-outlined">person</span>
                    Hồ sơ
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-sm">
                    <span class="material-symbols-outlined">palette</span>
                    Giao diện
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-blue-600 bg-blue-50 dark:bg-slate-700 dark:text-blue-400 font-medium text-sm">
                    <span class="material-symbols-outlined">security</span>
                    Bảo mật
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-sm">
                    <span class="material-symbols-outlined">notifications</span>
                    Thông báo
                </a>

            </div>
        </div>

        {{-- MAIN --}}
        <div class="md:col-span-3 space-y-6">

            {{-- CHANGE PASSWORD --}}
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm">

                <h6 class="font-bold text-slate-800 dark:text-white">Thay đổi mật khẩu</h6>
                <p class="text-sm text-slate-500 mb-4">Yêu cầu mật khẩu hiện tại để xác minh</p>

                <div class="space-y-4">

                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-300">Mật khẩu hiện tại</label>
                        <input type="password"
                               class="w-full mt-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                               placeholder="Enter your current password">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-300">Mật khẩu mới</label>
                        <input type="password"
                               class="w-full mt-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                               placeholder="Tạo mật khẩu mạnh mới">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-300">Xác nhận mật khẩu</label>
                        <input type="password"
                               class="w-full mt-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                               placeholder="Nhập lại mật khẩu">
                    </div>

                    <button class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Cập nhật mật khẩu
                    </button>

                </div>
            </div>

            {{-- ACTIVE SESSIONS --}}
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm">

                <div class="flex justify-between items-center mb-4">

                    <div>
                        <h6 class="font-bold text-slate-800 dark:text-white">Nơi bạn đăng nhập</h6>
                        <p class="text-sm text-slate-500">Các thiết bị đang hoạt động</p>
                    </div>

                    <button class="flex items-center gap-2 px-4 py-2 text-sm border border-red-500 text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-slate-700">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        Đăng xuất tất cả
                    </button>

                </div>

                {{-- SESSION 1 --}}
                <div class="flex justify-between items-center py-3">
                    <div class="flex items-center gap-3">

                        <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_6px_rgba(34,197,94,0.6)]"></span>

                        <div>
                            <div class="flex items-center gap-2 font-semibold dark:text-white">
                                Chrome trên Windows 11
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                            <p class="text-xs text-slate-500">TP.HCM • 2 phút trước</p>
                        </div>

                    </div>
                </div>

                {{-- SESSION 2 --}}
                <div class="flex justify-between items-center py-3 border-t dark:border-slate-700">
                    <div>
                        <div class="font-semibold dark:text-white">Safari trên iPhone 15</div>
                        <p class="text-xs text-slate-500">TP.HCM • 3 giờ trước</p>
                    </div>
                    <button class="text-red-500 text-sm">Đăng xuất</button>
                </div>

                {{-- SESSION 3 --}}
                <div class="flex justify-between items-center py-3 border-t dark:border-slate-700">
                    <div>
                        <div class="font-semibold dark:text-white">Firefox trên macOS</div>
                        <p class="text-xs text-slate-500">Hà Nội • 2 ngày trước</p>
                    </div>
                    <button class="text-red-500 text-sm">Đăng xuất</button>
                </div>

            </div>

        </div>
    </div>
</div>
</x-app-layout>