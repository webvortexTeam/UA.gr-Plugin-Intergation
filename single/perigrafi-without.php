<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$button_color = get_option('vortex_ua_button_color', '#FA345B');

$locale_activities = get_option('activity_api_locale', 'gr');
?>
<div>
                <h2 class="text-2xl text-gray-900" style="display: inline; margin-right: 5px;"><?php echo $locale_activities === 'en' ? 'Activity' : 'Περιγραφή'; ?></h2>
        <h2 class="text-2xl text-gray-900" style="display: inline; color: <?php echo $button_color;?>; margin-right: 5px;"><?php echo $locale_activities === 'en' ? 'description' : 'δραστηριότητας'; ?></h2>
    <div class="space-y-6 text-base text-gray-900">
        <?php echo wp_kses_post($description); ?>
    </div>
</div>
