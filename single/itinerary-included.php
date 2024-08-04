<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$locale_activities = get_option('activity_api_locale', 'gr');
?>
<?php if (!empty($itinerary['details']['included'])): ?>
    <div class="mt-4">
        <h5 class="text-lg font-semibold">
            <?php echo $locale_activities === 'en' ? 'Included' : 'Περιλαμβάνεται'; ?>
        </h5>
        <ul class="list-disc list-inside">
            <?php foreach ($itinerary['details']['included'] as $included): ?>
                <li><?php echo esc_html($included['title']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
