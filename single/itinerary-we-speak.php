<div class="mt-4">
                            <h5 class="text-lg font-semibold">Μιλάμε</h5>
                            <ul class="list-disc list-inside">
                                                    <?php foreach ($itinerary['spoken_languages'] as $lang):
                                                        $flag_url = get_country_flag_by_language($lang['title']);
                                                        ?>
                                                        <li class="flex items-center">
                                                            <?php if ($flag_url): ?>
                                                                <img src="<?php echo esc_url($flag_url); ?>"
                                                                    alt="<?php echo esc_attr($lang['title']); ?> Flag" class="w-5 h-auto mr-2">
                                                            <?php endif; ?>
                                                            <?php echo esc_html($lang['title']); ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>