<x-settings-layout>
    @if(session('status'))
    <div class="mb-6 rounded-2xl bg-green-100 text-green-700 px-4 py-3">
        {{ session('status') }}
    </div>
    @endif

    <form id="appearanceForm" method="POST" action="{{ route('settings.appearance.update') }}" class="space-y-6">
        @csrf @method('PATCH')

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700">
            <h2 class="text-2xl font-bold mb-6 dark:text-white">
                Giao diện
            </h2>

            <div class="space-y-8">
                {{-- THEME --}} {{-- THEME --}}
                <div>
                    <label class="block font-semibold dark:text-white mb-2">
                        Chủ đề
                    </label>

                    <select
                        name="theme"
                        id="themeSelect"
                        onchange="previewTheme(this.value)"
                        class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-800 shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    >
                        <option value="light" {{ ($user->theme ?? 'light') === 'light' ? 'selected' : '' }}> Light </option>

                        <option value="dark" {{ ($user->theme ?? 'light') === 'dark' ? 'selected' : '' }}> Dark </option>
                    </select>
                </div>

                {{-- FONT SIZE --}}
                <div>
                    <label class="block font-semibold dark:text-white mb-2">
                        Cỡ chữ ghi chú
                    </label>

                    <p class="text-sm text-slate-500 mb-3">
                        Hiện tại:
                        <span id="fontSizeLabel" class="font-semibold text-blue-600">
                            {{ $user->font_size ?? 16 }}px
                        </span>
                    </p>

                    <input
                        type="range"
                        name="font_size"
                        id="fontSizeInput"
                        min="12"
                        max="24"
                        step="1"
                        value="{{ $user->font_size ?? 16 }}"
                        oninput="document.getElementById('fontSizeLabel').textContent = this.value + 'px'"
                        class="w-full accent-blue-600"
                    />

                    <div class="flex justify-between text-xs text-slate-400 mt-2">
                        <span>12px</span>
                        <span>16px</span>
                        <span>24px</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div class="flex items-center justify-between pt-2">
            {{-- HỦY BỎ --}}
            <button type="button" id="resetBtn" class="px-6 py-3 rounded-2xl bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:text-white font-semibold transition">
                Hủy bỏ thay đổi
            </button>

            {{-- LƯU --}}
            <button type="submit" class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                Lưu thay đổi
            </button>
        </div>
    </form>

    {{-- SCRIPT --}}
    <script>
        const form = document.getElementById("appearanceForm");
        const resetBtn = document.getElementById("resetBtn");

        const themeSelect = document.getElementById("themeSelect");
        const fontSizeInput = document.getElementById("fontSizeInput");

        // lưu trạng thái ban đầu
        const defaultTheme = themeSelect.value;
        const defaultFont = fontSizeInput.value;

        const defaultCheckedColor = document.querySelector('input[name="note_color"]:checked')?.value;

        // preview theme
        function previewTheme(value) {
            if (value === "dark") {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
        }

        // RESET ALL
        resetBtn.addEventListener("click", function () {
            // reset theme
            themeSelect.value = defaultTheme;
            previewTheme(defaultTheme);

            // reset font size
            fontSizeInput.value = defaultFont;
            document.getElementById("fontSizeLabel").textContent = defaultFont + "px";

            // reset color radio
            if (defaultCheckedColor) {
                document.querySelectorAll('input[name="note_color"]').forEach((el) => {
                    el.checked = el.value === defaultCheckedColor;
                });
            }
        });
    </script>
</x-settings-layout>
