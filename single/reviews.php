<?php if (!empty($reviews)): ?>
                            <div class="mt-10">
                                <h2 class="text-sm font-medium text-gray-900">Κριτικές</h2>
                                <div class="mt-4 prose max-w-none text-gray-700">
                                    <?php echo wp_kses_post($reviews); ?>
                                </div>
                            </div>
                        <?php endif; ?>