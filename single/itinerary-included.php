<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<?php if (!empty($itinerary['details']['included'])): ?>
                                                <div class="mt-4">
                                                    <h5 class="text-lg font-semibold">Περιλαμβάνεται</h5>
                                                    <ul class="list-disc list-inside">
                                                        <?php foreach ($itinerary['details']['included'] as $included): ?>
                                                            <li><?php echo esc_html($included['title']); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>