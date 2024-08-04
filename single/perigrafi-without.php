<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$locale_activities = get_option('activity_api_locale', 'gr');
?>
<div>
    <h3 class="sr-only">
        <?php echo $locale_activities === 'en' ? 'Description' : 'Περιγραφή'; ?>
    </h3>
    <div class="space-y-6 text-base text-gray-900">
        <?php echo wp_kses_post($description); ?>
    </div>
</div>
