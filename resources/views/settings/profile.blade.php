<x-app-layout>
<div class="max-w-5xl mx-auto p-4 md:p-8">

    {{-- TITLE --}}
    <div class="mb-6">
        <h4 class="text-2xl font-bold dark:text-white">Thông tin hồ sơ</h4>
        <p class="text-sm text-slate-500">Quản lý hồ sơ và cài đặt tài khoản của bạn</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        {{-- SIDEBAR --}}
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 shadow-sm space-y-2">

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-blue-600 bg-blue-50 dark:bg-slate-700 dark:text-blue-400 font-medium text-sm">
                    <span class="material-symbols-outlined">person</span>
                    Hồ sơ
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-sm">
                    <span class="material-symbols-outlined">palette</span>
                    Giao diện
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-sm">
                    <span class="material-symbols-outlined">security</span>
                    Bảo vệ
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-sm">
                    <span class="material-symbols-outlined">notifications</span>
                    Thông báo
                </a>

            </div>
        </div>

        {{-- MAIN --}}
        <div class="md:col-span-3 space-y-6">

            {{-- PROFILE CARD --}}
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm">

                <div class="flex items-center gap-4 mb-6">

                    <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                        N
                    </div>

                    <div>
                        <h6 class="font-bold text-slate-800 dark:text-white">Nguyen Van A</h6>
                        <p class="text-xs text-slate-500">TÀI KHOẢN PRO</p>
                    </div>

                    <button class="ml-auto px-4 py-2 text-sm border rounded-lg dark:border-slate-600 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700">
                        Thay đổi ảnh
                    </button>
                </div>

                <div class="space-y-4">

                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-300">Tên hiển thị</label>
                        <input class="w-full mt-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white" value="Thanh Nguyen">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-300">Email</label>
                        <input class="w-full mt-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white" value="thanh.nguyen@student.edu.vn">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-300">Tiểu sử ngắn</label>
                        <textarea rows="3" class="w-full mt-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">Sinh viên ngành Khoa học Máy tính tại TDTU. Sử dụng NoteFlow...</textarea>
                    </div>

                </div>
            </div>

            {{-- STATS --}}
            <div>
                <h5 class="font-bold text-slate-800 dark:text-white mb-3">Chi tiết tài khoản</h5>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-2">
                            <span class="material-symbols-outlined text-blue-500">event</span>
                            Thành viên từ
                        </div>
                        <div class="font-bold dark:text-white">12/02/2025</div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-2">
                            <span class="material-symbols-outlined text-green-500">description</span>
                            Tổng ghi chú
                        </div>
                        <div class="font-bold text-lg dark:text-white">47</div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-2">
                            <span class="material-symbols-outlined text-yellow-500">share</span>
                            Đã chia sẻ
                        </div>
                        <div class="font-bold text-lg dark:text-white">12</div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-2">
                            <span class="material-symbols-outlined text-red-500">label</span>
                            Nhãn tạo
                        </div>
                        <div class="font-bold text-lg dark:text-white">8</div>
                    </div>

                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex justify-between">

                <button class="px-5 py-2.5 rounded-lg border dark:border-slate-600 text-slate-600 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700">
                    Hủy bỏ thay đổi
                </button>

                <button class="px-5 py-2.5 rounded-lg bg-slate-900 text-white hover:bg-black">
                    Lưu những thay đổi
                </button>

            </div>

        </div>
    </div>
</div>
</x-app-layout>