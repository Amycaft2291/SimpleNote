<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<div class="max-w-4xl mx-auto p-4 md:p-8">
    <div class="flex items-center gap-3 mb-6">
        <h4 class="text-2xl font-bold dark:text-white">Cài đặt</h4>
    </div>

    <?php if(session('status')): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4"><?php echo e(session('status')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('appearance.update')); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PATCH'); ?>

        
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm">
            <h6 class="font-bold text-slate-800 dark:text-slate-100 mb-1">Chủ đề</h6>
            <p class="text-sm text-slate-500 mb-4">Chọn giao diện phù hợp với bạn</p>
            
            <div class="grid grid-cols-3 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="theme" value="light" class="peer hidden" <?php echo e($user->theme === 'light' ? 'checked' : ''); ?>>
                    <div class="border-2 rounded-xl p-4 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-slate-700 transition-all">
                        <span class="material-symbols-outlined text-yellow-500">light_mode</span>
                        <div class="font-bold mt-2 dark:text-white">Light</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="theme" value="dark" class="peer hidden" <?php echo e($user->theme === 'dark' ? 'checked' : ''); ?>>
                    <div class="border-2 rounded-xl p-4 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-slate-700 transition-all">
                        <span class="material-symbols-outlined text-slate-800 dark:text-white">dark_mode</span>
                        <div class="font-bold mt-2 dark:text-white">Dark</div>
                    </div>
                </label>
            </div>
        </div>

        
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm">
            <h6 class="font-bold text-slate-800 dark:text-slate-100 mb-1">Cỡ chữ ghi chú</h6>
            <input type="range" name="font_size" min="12" max="20" step="2" value="<?php echo e($user->font_size); ?>" class="w-full mt-4">
            <div class="flex justify-between text-xs text-slate-500 mt-2">
                <span>Nhỏ (12px)</span><span>Vừa (16px)</span><span>Lớn (20px)</span>
            </div>
        </div>

        
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm">
            <h6 class="font-bold text-slate-800 dark:text-slate-100 mb-1">Màu mặc định</h6>
            <div class="flex flex-wrap gap-3 mt-3">
                <?php $colors = ['#ffffff', '#fef9c3', '#dcfce7', '#e0f2fe', '#fee2e2', '#f3f4f6', '#1e293b']; ?>
                <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="cursor-pointer">
                    <input type="radio" name="note_color" value="<?php echo e($color); ?>" class="peer hidden" <?php echo e($user->note_color === $color ? 'checked' : ''); ?>>
                    <div style="background-color: <?php echo e($color); ?>" class="w-10 h-10 rounded-full border-2 peer-checked:border-blue-500 peer-checked:scale-110 shadow-sm transition-transform"></div>
                </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">Lưu thay đổi</button>
        </div>
    </form>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\CK Web\Composer\SimpleNote\resources\views/settings/appearance.blade.php ENDPATH**/ ?>