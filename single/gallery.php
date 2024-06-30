<?php 
    
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
        ?>
       <div class="mx-auto mt-6 max-w-2xl sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8">
            <?php if (!empty($photos)) : ?>
                <?php foreach ($photos as $index => $photo) : ?>
                    <?php if ($index === 0) : ?>
                        <div class="aspect-h-4 aspect-w-3 hidden overflow-hidden rounded-lg lg:block">
                            <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center">
                        </div>
                    <?php elseif ($index === 1 || $index === 2) : ?>
                        <?php if ($index === 1) : ?>
                            <div class="hidden lg:grid lg:grid-cols-1 lg:gap-y-8">
                        <?php endif; ?>
                        <div class="aspect-h-2 aspect-w-3 overflow-hidden rounded-lg">
                            <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center">
                        </div>
                        <?php if ($index === 2) : ?>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="aspect-h-5 aspect-w-4 lg:aspect-h-4 lg:aspect-w-3 sm:overflow-hidden sm:rounded-lg">
                            <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="aspect-h-4 aspect-w-3 hidden overflow-hidden rounded-lg lg:block">
                    <img src="https://tailwindui.com/img/ecommerce-images/product-page-02-secondary-product-shot.jpg" alt="Placeholder image" class="h-full w-full object-cover object-center">
                </div>
            <?php endif; ?>
        </div>