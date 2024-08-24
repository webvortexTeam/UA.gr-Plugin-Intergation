<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$button_color = get_option('vortex_ua_button_color', '#FA345B');
$locale_activities = get_option('activity_api_locale', 'gr');

// Separate reviews with text from those with only stars
$text_reviews = array_filter($all_reviews, fn($review) => !empty($review['text']));
$star_only_reviews = array_filter($all_reviews, fn($review) => empty($review['text']));

// Check if there are more reviews to load
$has_more_text_reviews = count($text_reviews) > 5;
$has_more_star_only_reviews = count($star_only_reviews) > 5;
?>
<div id="unlimited-a-reviews" class="reviews-section bg-white p-6 rounded-lg">
       <?php if ($has_more_text_reviews || $has_more_star_only_reviews): ?>
    <h2 class="text-2xl font-bold mb-4">
        <?php echo $locale_activities === 'en' ? 'Reviews' : 'Κριτικές'; ?>
    </h2>
    <?php endif; ?>

    
    <!-- Reviews with text displayed one below the other -->
    <?php if (!empty($text_reviews)): ?>
        <div id="text-reviews-container" class="mb-6">
            <?php foreach ($text_reviews as $index => $review): ?>
                <div class="review-item-ua p-4 border rounded-lg <?php echo $index >= 5 ? 'hidden' : ''; ?>" style="background-color: #EEEEEE; border-radius: 25px;">
                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-2"><?php echo esc_html($review['fullname']); ?></p>
                    <div class="flex items-center mb-2">
                        <?php
                        $score = intval($review['score']) / 2;
                        $fullStarSVG = "<svg xmlns='http://www.w3.org/2000/svg' width='23' height='23' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M12 2l2.5 7.5h8l-6 4.5 2.5 7.5-6-4.5-6 4.5L6 14l-6-4.5h8L12 2z' fill='$button_color'/></svg>";
                        $halfStarSVG = "<svg xmlns='http://www.w3.org/2000/svg' width='23' height='23' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M12 2l2.5 7.5h8l-6 4.5 2.5 7.5-6-4.5-6 4.5L6 14l-6-4.5h8L12 2z' fill='$button_color'/><path d='M12 2l2.5 7.5h8l-6 4.5 2.5 7.5-6-4.5-6 4.5L6 14l-6-4.5h8L12 2z' fill='#EEEEEE' clip-path='url(#clip-path)'/><defs><clipPath id='clip-path'><rect x='0' y='0' width='12' height='24'/></clipPath></defs></svg>";
                        $emptyStarSVG = "<svg xmlns='http://www.w3.org/2000/svg' width='23' height='23' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M12 2l2.5 7.5h8l-6 4.5 2.5 7.5-6-4.5-6 4.5L6 14l-6-4.5h8L12 2z' fill='#EEEEEE'/></svg>";

                        // Calculate the number of full stars and the number of half stars
                        $fullStars = floor($score);
                        $halfStars = ($score - $fullStars) >= 0.5 ? 1 : 0;

                        // Output the stars
                        echo str_repeat($fullStarSVG, $fullStars) . str_repeat($halfStarSVG, $halfStars);
                        
                        // Fill in the remaining empty stars
                        $emptyStars = 5 - ($fullStars + $halfStars);
                        if ($emptyStars > 0) {
                            echo str_repeat($emptyStarSVG, $emptyStars);
                        }
                        ?>
                    </div>
                    <p class="text-sm text-gray-700"><?php echo wp_kses_post($review['text']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Star-only reviews displayed in a 3-column grid -->
    <?php if (!empty($star_only_reviews)): ?>
        <div id="star-only-reviews-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <?php foreach ($star_only_reviews as $index => $review): ?>
                <div class="review-item-ua p-4 border rounded-lg <?php echo $index >= 5 ? 'hidden' : ''; ?>" style="background-color: #EEEEEE; border-radius: 25px;">
                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-2"><?php echo esc_html($review['fullname']); ?></p>
                    <div class="flex items-center mb-2">
                        <?php
                        $score = intval($review['score']) / 2;
                        echo str_repeat($fullStarSVG, floor($score)) . str_repeat($halfStarSVG, ($score - floor($score)) >= 0.5 ? 1 : 0) . str_repeat($emptyStarSVG, 5 - floor($score) - (($score - floor($score)) >= 0.5 ? 1 : 0));
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Load More Button -->
    <?php if ($has_more_text_reviews || $has_more_star_only_reviews): ?>
        <div id="load-more-container" class="text-center mt-4">
            <button id="load-more-btn" class="bg-<?php echo esc_attr($button_color); ?> text-black px-4 py-2 rounded-lg"><?php echo $locale_activities === 'en' ? 'Load More' : 'Φόρτωση Περισσότερων'; ?></button>
        </div>
    <?php endif; ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const loadMoreBtn = document.getElementById('load-more-btn');
    const textReviews = document.querySelectorAll('#text-reviews-container .review-item-ua.hidden');
    const starOnlyReviews = document.querySelectorAll('#star-only-reviews-container .review-item-ua.hidden');
    
    let textReviewsLoaded = 5;
    let starOnlyReviewsLoaded = 5;

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            let moreTextReviews = Array.from(textReviews).slice(0, 5);
            let moreStarOnlyReviews = Array.from(starOnlyReviews).slice(0, 5);

            moreTextReviews.forEach(review => review.classList.remove('hidden'));
            moreStarOnlyReviews.forEach(review => review.classList.remove('hidden'));

            textReviewsLoaded += moreTextReviews.length;
            starOnlyReviewsLoaded += moreStarOnlyReviews.length;

            // Update visibility of Load More button
            if (textReviewsLoaded >= textReviews.length && starOnlyReviewsLoaded >= starOnlyReviews.length) {
                loadMoreBtn.classList.add('hidden');
            }
        });
    }
});
</script>
