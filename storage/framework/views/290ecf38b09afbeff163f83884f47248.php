<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Leede Fusion | Premium Fashion'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Community-driven streetwear and lifestyle brand.'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-heading: 'Bebas Neue', sans-serif;
            --font-serif: 'Playfair Display', 'Cormorant Garamond', Georgia, serif;
            --font-body: 'Inter', sans-serif;
        }
    </style>

    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/storefront.css')); ?>">
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="antialiased <?php echo $__env->yieldContent('body_class'); ?>"
      x-data
      @keydown.escape.window="$dispatch('close-quick-view')">
    <?php echo $__env->make('partials.preloader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php if (! ($hideWhatsApp ?? false)): ?>
        <?php echo $__env->make('partials.whatsapp', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <?php echo $__env->make('partials.quick-view-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\AR\Desktop\Leede-Fusion\resources\views/layouts/app.blade.php ENDPATH**/ ?>