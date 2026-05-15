<x-settings-layout>

@if(session('status'))
    <div class="mb-6 rounded-2xl bg-green-100 text-green-700 px-4 py-3">
        {{ session('status') }}
    </div>
@endif

<form id="appearanceForm"
      method="POST"
      action="{{ route('settings.appearance.update') }}"
      class="space-y-6">

    @csrf
    @method('PATCH')

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700">

        <h2 class="text-2xl font-bold mb-6 dark:text-white">
            Giao diện
        </h2>

        <div class="space-y-8">

            {{-- THEME --}}
            <div>
                <label class="block font-semibold dark:text-white mb-2">
                    Chủ đề
                </label>

                <select
                    name="theme"
                    id="themeSelect"
                    onchange="previewTheme(this.value)"
                    class="w-full rounded-2xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                    <option value="light"
                        {{ ($user->theme ?? 'light') === 'light' ? 'selected' : '' }}>
                        Light
                    </option>

                    <option value="dark"
                        {{ ($user->theme ?? 'light') === 'dark' ? 'selected' : '' }}>
                        Dark
                    </option>

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
                    class="w-full accent-blue-600">

                <div class="flex justify-between text-xs text-slate-400 mt-2">
                    <span>12px</span>
                    <span>16px</span>
                    <span>24px</span>
                </div>
            </div>

            {{-- NOTE COLOR --}}
            <div>
                <label class="block font-semibold dark:text-white mb-2">
                    Màu mặc định ghi chú
                </label>

                @php
                    $colors = [
                        'white'  => 'bg-white border border-slate-300',
                        'yellow' => 'bg-yellow-100',
                        'green'  => 'bg-green-100',
                        'blue'   => 'bg-blue-100',
                        'purple' => 'bg-purple-100',
                        'pink'   => 'bg-pink-100',
                        'red'    => 'bg-red-100',
                        'gray'   => 'bg-slate-200',
                    ];

                    $currentColor = $user->note_color ?? 'white';
                @endphp

                <div class="flex flex-wrap gap-4 mt-3">

                    @foreach($colors as $value => $bg)

                        <label class="cursor-pointer">

                            <input
                                type="radio"
                                name="note_color"
                                value="{{ $value }}"
                                class="hidden peer"
                                {{ $currentColor === $value ? 'checked' : '' }}>

                            <div class="w-10 h-10 rounded-full {{ $bg }}
                                transition-all duration-200
                                peer-checked:ring-4
                                peer-checked:ring-blue-500
                                peer-checked:ring-offset-2
                                hover:scale-105">
                            </div>

                        </label>

                    @endforeach

                </div>
            </div>

        </div>
    </div>

    {{-- BUTTONS --}}
    <div class="flex justify-end gap-3">

        {{-- HỦY BỎ --}}
        <button type="button"
                id="resetBtn"
                class="px-6 py-3 rounded-2xl bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:text-white font-semibold transition">
            Hủy bỏ thay đổi
        </button>

        {{-- LƯU --}}
        <button type="submit"
                class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
            Lưu thay đổi
        </button>

    </div>

</form>

{{-- SCRIPT --}}
<script>
    const form = document.getElementById('appearanceForm');
    const resetBtn = document.getElementById('resetBtn');

    const themeSelect = document.getElementById('themeSelect');
    const fontSizeInput = document.getElementById('fontSizeInput');

    // lưu trạng thái ban đầu
    const defaultTheme = themeSelect.value;
    const defaultFont = fontSizeInput.value;

    const defaultCheckedColor = document.querySelector('input[name="note_color"]:checked')?.value;

    // preview theme
    function previewTheme(value) {
        if (value === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    // RESET ALL
    resetBtn.addEventListener('click', function () {

        // reset theme
        themeSelect.value = defaultTheme;
        previewTheme(defaultTheme);

        // reset font size
        fontSizeInput.value = defaultFont;
        document.getElementById('fontSizeLabel').textContent = defaultFont + 'px';

        // reset color radio
        if (defaultCheckedColor) {
            document.querySelectorAll('input[name="note_color"]').forEach(el => {
                el.checked = (el.value === defaultCheckedColor);
            });
        }
    });
</script>

</x-settings-layout>