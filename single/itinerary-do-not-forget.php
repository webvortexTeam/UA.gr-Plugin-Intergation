<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$locale_activities = get_option('activity_api_locale', 'gr');
?>
<?php if (!empty($itinerary['details']['do_not_forget'])): ?>
    <div class="mt-4">
        <h5 class="text-lg font-semibold">
            <?php echo $locale_activities === 'en' ? 'Do Not Forget' : 'Μην ξεχάσετε'; ?>
        </h5>
        <ul class="list-disc list-inside">
            <?php foreach ($itinerary['details']['do_not_forget'] as $do_not_forget): ?>
                <li><?php echo esc_html($do_not_forget['title']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
