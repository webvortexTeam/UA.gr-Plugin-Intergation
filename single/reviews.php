<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$locale_activities = get_option('activity_api_locale', 'gr');
?>
<div id="unlimited-a-reviews" class="reviews-section bg-white p-6 rounded-lg">
    <h2 class="text-2xl font-bold mb-4">
        <?php echo $locale_activities === 'en' ? 'Reviews' : 'Κριτικές'; ?>
    </h2>
    <?php if (!empty($all_reviews)): ?>
        <div id="reviews-container-ua" class="space-y-4">
            <?php foreach ($all_reviews as $index => $review): ?>
                <div class="review-item-ua p-4 border rounded-lg <?php echo $index >= 5 ? 'hidden' : ''; ?>">
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 text-yellow-300 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                        <p class="ms-2 text-sm font-bold text-gray-900 dark:text-white"><?php echo esc_html($review['score']); ?></p>
                        <span class="w-1 h-1 mx-1.5 bg-gray-500 rounded-full dark:bg-gray-400"></span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo esc_html($review['fullname']); ?></p>
                    </div>
                    <p class="text-sm text-gray-700"><?php echo wp_kses_post($review['text']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="pagination-review-ua" class="mt-4 flex justify-center space-x-2"></div>
    <?php else: ?>
        <p class="text-gray-700">
            <?php echo $locale_activities === 'en' ? 'No reviews available' : 'Δεν υπάρχουν κριτικές'; ?>
        </p>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reviewsPerPageUA = 5;
        const reviewsContainer = document.getElementById('reviews-container-ua');
        const reviews = reviewsContainer.getElementsByClassName('review-item-ua');
        const totalPages = Math.ceil(reviews.length / reviewsPerPageUA);

        function showPage(page) {
            const start = (page - 1) * reviewsPerPageUA;
            const end = start + reviewsPerPageUA;

            for (let i = 0; i < reviews.length; i++) {
                reviews[i].classList.add('hidden');
            }

            for (let i = start; i < end && i < reviews.length; i++) {
                reviews[i].classList.remove('hidden');
            }
        }

        function createPagination() {
            const paginationContainer = document.getElementById('pagination-review-ua');

            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.classList.add('pagination-button', 'px-2', 'py-1', 'border', 'rounded');
                button.addEventListener('click', function () {
                    showPage(i);
                    updateActiveButton(i);
                });
                paginationContainer.appendChild(button);
            }
        }

        function updateActiveButton(activePage) {
            const buttons = document.querySelectorAll('.pagination-button');
            buttons.forEach(button => {
                button.classList.remove('bg-blue-500', 'text-white');
                button.classList.add('bg-gray-200', 'text-gray-700');
            });

            buttons[activePage - 1].classList.add('bg-blue-500', 'text-white');
            buttons[activePage - 1].classList.remove('bg-gray-200', 'text-gray-700');
        }

        if (totalPages > 1) {
            createPagination();
            showPage(1);  // Show the first page by default
            updateActiveButton(1);  // Highlight the first page button
        }
    });
</script>
