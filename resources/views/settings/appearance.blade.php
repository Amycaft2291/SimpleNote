<x-app-layout>

<div class="max-w-6xl mx-auto p-4 md:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div>
            @include('settings.sidebar')
        </div>

        <div class="lg:col-span-3">

            @if(session('status'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST"
                  action="{{ route('settings.appearance.update') }}"
                  class="space-y-6">

                @csrf
                @method('PATCH')

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border dark:border-slate-700">

                    <h2 class="text-2xl font-bold mb-6 dark:text-white">Giao diện</h2>

                    <div class="space-y-6">

                        {{-- Theme --}}
                        <div>
                            <label class="font-medium dark:text-white">Chủ đề</label>
                            <p class="text-sm text-slate-500 mb-2">Chọn giao diện sáng hoặc tối</p>
                            <select
                                name="theme"
                                id="themeSelect"
                                onchange="previewTheme(this.value)"
                                class="w-full mt-1 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                                <option value="light" {{ ($user->theme ?? 'light') === 'light' ? 'selected' : '' }}>
                                    Light 
                                </option>
                                <option value="dark" {{ ($user->theme ?? 'light') === 'dark' ? 'selected' : '' }}>
                                    Dark 
                                </option>
                            </select>
                        </div>

                        {{-- Font Size --}}
                        <div>
                            <label class="font-medium dark:text-white">Cỡ chữ ghi chú</label>
                            <p class="text-sm text-slate-500 mb-2">
                                Hiện tại: <span id="fontSizeLabel" class="font-semibold text-blue-600">
                                    {{ $user->font_size ?? 16 }}px
                                </span>
                            </p>
                            <input
                                type="range"
                                name="font_size"
                                id="fontSizeRange"
                                min="12"
                                max="24"
                                step="1"
                                value="{{ $user->font_size ?? 16 }}"
                                oninput="document.getElementById('fontSizeLabel').textContent = this.value + 'px'"
                                class="w-full mt-1 accent-blue-600">
                            <div class="flex justify-between text-xs text-slate-400 mt-1">
                                <span>Nhỏ (12px)</span>
                                <span>Vừa (16px)</span>
                                <span>Lớn (24px)</span>
                            </div>
                        </div>

                        {{-- Note Color --}}
                        <div>
                            <label class="font-medium dark:text-white">Màu mặc định ghi chú</label>
                            <p class="text-sm text-slate-500 mb-3">Màu nền cho ghi chú mới</p>
                            <div class="flex gap-3 flex-wrap">
                                @php
                                    $colors = [
                                        'white'  => ['bg' => 'bg-white border-2 border-slate-300', 'label' => 'Trắng'],
                                        'yellow' => ['bg' => 'bg-yellow-100', 'label' => 'Vàng'],
                                        'green'  => ['bg' => 'bg-green-100',  'label' => 'Xanh lá'],
                                        'blue'   => ['bg' => 'bg-blue-100',   'label' => 'Xanh dương'],
                                        'purple' => ['bg' => 'bg-purple-100', 'label' => 'Tím'],
                                        'pink'   => ['bg' => 'bg-pink-100',   'label' => 'Hồng'],
                                        'red'    => ['bg' => 'bg-red-100',    'label' => 'Đỏ'],
                                        'gray'   => ['bg' => 'bg-slate-200',  'label' => 'Xám'],
                                    ];
                                    $currentColor = $user->note_color ?? 'white';
                                @endphp

                                @foreach($colors as $value => $color)
                                    <label class="cursor-pointer" title="{{ $color['label'] }}">
                                        <input type="radio"
                                               name="note_color"
                                               value="{{ $value }}"
                                               class="sr-only"
                                               {{ $currentColor === $value ? 'checked' : '' }}>
                                        <div class="w-9 h-9 rounded-full {{ $color['bg'] }} transition
                                            {{ $currentColor === $value ? 'ring-2 ring-offset-2 ring-blue-500 scale-110' : 'hover:scale-105' }}">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>

                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                        Lưu thay đổi
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
// Preview theme ngay khi chọn mà chưa submit
function previewTheme(value) {
    if (value === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

// Highlight màu được chọn khi click
document.querySelectorAll('input[name="note_color"]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('input[name="note_color"] + div').forEach(div => {
            div.classList.remove('ring-2', 'ring-offset-2', 'ring-blue-500', 'scale-110');
        });
        this.nextElementSibling.classList.add('ring-2', 'ring-offset-2', 'ring-blue-500', 'scale-110');
    });
});
</script>

</x-app-layout>