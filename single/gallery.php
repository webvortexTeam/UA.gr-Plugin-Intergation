<?php 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@2.0.0/dist/full.css">

<!-- Desktop -->
<div class="hidden lg:grid mx-auto mt-6 max-w-2xl sm:px-6 lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8">
    <?php if (!empty($photos)) : ?>
        <?php foreach ($photos as $index => $photo) : ?>
            <?php if ($index === 0) : ?>
                <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg">
                    <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center">
                </div>
            <?php elseif ($index === 1 || $index === 2) : ?>
                <?php if ($index === 1) : ?>
                    <div class="grid gap-y-8">
                <?php endif; ?>
                <div class="aspect-h-2 aspect-w-3 overflow-hidden rounded-lg">
                    <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center">
                </div>
                <?php if ($index === 2) : ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="aspect-h-5 aspect-w-4 sm:overflow-hidden sm:rounded-lg">
                    <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg">
            <img src="https://tailwindui.com/img/ecommerce-images/product-page-02-secondary-product-shot.jpg" alt="Placeholder image" class="h-full w-full object-cover object-center">
        </div>
    <?php endif; ?>
</div>

<!-- Mobile -->
<div class="block lg:hidden">
    <div class="carousel carousel-vertical rounded-box h-96">
        <?php if (!empty($photos)) : ?>
            <?php foreach ($photos as $photo) : ?>
                <div class="carousel-item h-full">
                    <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center">
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="carousel-item h-full">
                <img src="https://tailwindui.com/img/ecommerce-images/product-page-02-secondary-product-shot.jpg" alt="Placeholder image" class="h-full w-full object-cover object-center">
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Include DaisyUI JS for Carousel Functionality -->
<script src="https://cdn.jsdelivr.net/npm/tw-elements@latest/dist/js/index.min.js"></script>
