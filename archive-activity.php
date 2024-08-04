<?php
/**
 * Template Name: Archive Activity
 */

function enqueue_custom_scripts() {
    wp_enqueue_style('tailwindcss', 'https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_style('jquery-ui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

get_header();

$locale_activities = get_option('activity_api_locale', 'gr');

$all_activities = new WP_Query(array(
    'post_type' => 'activity',
    'posts_per_page' => -1,
));

$min_price = PHP_INT_MAX;
$max_price = 0;

if ($all_activities->have_posts()) {
    while ($all_activities->have_posts()) {
        $all_activities->the_post();
        $itineraries = get_field('itineraries');
        if ($itineraries) {
            foreach ($itineraries as $itinerary) {
                if ($itinerary['min_price'] < $min_price) {
                    $min_price = $itinerary['min_price'];
                }
                if ($itinerary['min_price'] > $max_price) {
                    $max_price = $itinerary['min_price'];
                }
            }
        }
    }
    wp_reset_postdata();
}

$min_price = $min_price == PHP_INT_MAX ? 0 : $min_price;
?>
<div class="container mx-auto p-4">
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Category Filter -->
        <div>
            <label for="category-filter" class="block text-sm font-medium text-gray-800">
                <?php echo $locale_activities === 'en' ? 'Categories:' : 'Κατηγορίες:'; ?>
            </label>
            <select id="category-filter" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value=""><?php echo $locale_activities === 'en' ? 'All Categories' : 'Όλες οι Κατηγορίες'; ?></option>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'activity_category',
                    'hide_empty' => true,
                    'exclude' => array(
                        get_term_by('name', 'Αέρας', 'activity_category')->term_id,
                        get_term_by('name', 'Νερό', 'activity_category')->term_id,
                        get_term_by('name', 'Πόλη', 'activity_category')->term_id,
                        get_term_by('name', 'Γή', 'activity_category')->term_id
                    )
                ));
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Location Filter -->
        <div>
            <label for="location-filter" class="block text-sm font-medium text-gray-800">
                <?php echo $locale_activities === 'en' ? 'Locations:' : 'Τοποθεσίες:'; ?>
            </label>
            <select id="location-filter" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value=""><?php echo $locale_activities === 'en' ? 'All Locations' : 'Όλες οι Τοποθεσίες'; ?></option>
                <?php
                $locations = get_terms(array(
                    'taxonomy' => 'activity_location',
                    'hide_empty' => true,
                ));
                foreach ($locations as $location) {
                    echo '<option value="' . esc_attr($location->slug) . '">' . esc_html($location->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Rating Filter -->
        <div>
            <label for="rating-filter" class="block text-sm font-medium text-gray-800">
                <?php echo $locale_activities === 'en' ? 'Reviews:' : 'Κριτικές:'; ?>
            </label>
            <select id="rating-filter" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value=""><?php echo $locale_activities === 'en' ? 'All Reviews' : 'Όλες οι Κριτικές'; ?></option>
                <option value="1"><?php echo $locale_activities === 'en' ? '1 Star' : '1 Αστέρι'; ?></option>
                <option value="2"><?php echo $locale_activities === 'en' ? '2 Stars' : '2 Αστέρια'; ?></option>
                <option value="3"><?php echo $locale_activities === 'en' ? '3 Stars' : '3 Αστέρια'; ?></option>
                <option value="4"><?php echo $locale_activities === 'en' ? '4 Stars' : '4 Αστέρια'; ?></option>
                <option value="5"><?php echo $locale_activities === 'en' ? '5 Stars' : '5 Αστέρια'; ?></option>
            </select>
        </div>

        <!-- Price Slider -->
        <div>
            <label for="price-slider" class="block text-sm font-medium text-gray-800">
                <?php echo $locale_activities === 'en' ? 'Price:' : 'Τιμή:'; ?>
            </label>
            <div id="price-slider" class="mt-1"></div>
            <div id="price-range" class="text-sm text-gray-800 mt-1">
                €<?php echo esc_html($min_price); ?> - €<?php echo esc_html($max_price); ?>
            </div>
        </div>

        <!-- Duration Slider -->
        <div>
            <label for="duration-slider" class="block text-sm font-medium text-gray-800">
                <?php echo $locale_activities === 'en' ? 'Duration (hrs):' : 'Διάρκεια (hrs):'; ?>
            </label>
            <div id="duration-slider" class="mt-1"></div>
            <div id="duration-range" class="text-sm text-gray-800 mt-1">0 - 200 hrs</div>
        </div>
    </div>

    <div id="activities-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        <?php
        $activity_query = new WP_Query(array(
            'post_type' => 'activity',
            'posts_per_page' => -1,
        ));
        if ($activity_query->have_posts()) :
            while ($activity_query->have_posts()) : $activity_query->the_post();
                $categories = get_the_terms(get_the_ID(), 'activity_category');
                $locations = get_the_terms(get_the_ID(), 'activity_location');

                $category_slugs = '';
                $location_slugs = '';
                if ($categories && !is_wp_error($categories)) {
                    $category_slugs = join(' ', wp_list_pluck($categories, 'slug'));
                }
                if ($locations && !is_wp_error($locations)) {
                    $location_slugs = join(' ', wp_list_pluck($locations, 'slug'));
                }
                $itineraries = get_field('itineraries');
                $min_price = PHP_INT_MAX;
                $duration = 0;

                if ($itineraries) {
                    foreach ($itineraries as $itinerary) {
                        if ($itinerary['min_price'] < $min_price) {
                            $min_price = $itinerary['min_price'];
                        }
                        $duration += $itinerary['duration'];
                    }
                }
                $rating = get_field('rating');
                $min_price = $min_price == PHP_INT_MAX ? 0 : $min_price;
        ?>
        <div class="activity-card <?php echo esc_attr($category_slugs); ?> <?php echo esc_attr($location_slugs); ?> bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300" data-price="<?php echo esc_attr($min_price); ?>" data-rating="<?php echo esc_attr($rating); ?>" data-duration="<?php echo esc_attr($duration); ?>">
            <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-48 object-cover rounded-t-lg">
                </a>
            <?php endif; ?>
            <div class="p-4">
                <a href="<?php the_permalink(); ?>">
                    <h2 class="text-sm font-bold text-gray-900"><?php the_title(); ?></h2>
                    <p class="text-sm text-gray-700"><?php echo $locale_activities === 'en' ? 'from' : 'από'; ?> <?php echo esc_html($min_price); ?>€ / <?php echo $locale_activities === 'en' ? 'person' : 'το άτομο'; ?></p>
                </a>
                <div class="flex items-center mt-2 text-gray-700 space-x-2">
                    <div class="flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" viewBox="0 0 256 256">
                            <path fill="currentColor" d="M239.18 97.26A16.38 16.38 0 0 0 224.92 86l-59-4.76l-22.78-55.09a16.36 16.36 0 0 0-30.27 0L90.11 81.23L31.08 86a16.46 16.46 0 0 0-9.37 28.86l45 38.83L53 211.75a16.38 16.38 0 0 0 24.5 17.82l50.5-31.08l50.53 31.08A16.4 16.4 0 0 0 203 211.75l-13.76-58.07l45-38.83a16.43 16.43 0 0 0 4.94-17.59m-15.34 5.47l-48.7 42a8 8 0 0 0-2.56 7.91l14.88 62.8a.37.37 0 0 1-.17.48c-.18.14-.23.11-.38 0l-54.72-33.65a8 8 0 0 0-8.38 0l-54.72 33.67c-.15.09-.19.12-.38 0a.37.37 0 0 1-.17-.48l14.88-62.8a8 8 0 0 0-2.56-7.91l-48.7-42c-.12-.1-.23-.19-.13-.5s.18-.27.33-.29l63.92-5.16a8 8 0 0 0 6.72-4.94l24.62-59.61c.08-.17.11-.25.35-.25s.27.08.35.25L153 91.86a8 8 0 0 0 6.75 4.92l63.92 5.16c.15 0 .24 0 .33.29s0 .4-.16.5" />
                        </svg>
                        <p class="text-sm"><?php echo esc_html($rating); ?> / 5</p>
                    </div>
                </div>
                <div class="flex items-center mt-2 text-gray-700 space-x-2">
                    <div class="flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" viewBox="0 0 32 32">
                            <path fill="currentColor" d="M16 30a14 14 0 1 1 14-14a14 14 0 0 1-14 14m0-26a12 12 0 1 0 12 12A12 12 0 0 0 16 4" />
                            <path fill="currentColor" d="M20.59 22L15 16.41V7h2v8.58l5 5.01z" />
                        </svg>
                        <p class="text-sm"><?php echo esc_html($duration); ?> <?php echo $locale_activities === 'en' ? 'hours' : 'ώρες'; ?></p>
                    </div>
                    <div class="flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 32 32">
                            <path fill="currentColor" d="M16 18a5 5 0 1 1 5-5a5 5 0 0 1-5 5m0-8a3 3 0 1 0 3 3a3.003 3.003 0 0 0-3-3" />
                            <path fill="currentColor" d="m16 30l-8.436-9.949a35 35 0 0 1-.348-.451A10.9 10.9 0 0 1 5 13a11 11 0 0 1 22 0a10.9 10.9 0 0 1-2.215 6.597l-.001.003s-.3.394-.345.447ZM8.813 18.395s.233.308.286.374L16 26.908l6.91-8.15c.044-.055.278-.365.279-.366A8.9 8.9 0 0 0 25 13a9 9 0 1 0-18 0a8.9 8.9 0 0 0 1.813 5.395" />
                        </svg>
                        <p class="text-sm"><?php echo esc_html(join(', ', wp_list_pluck($locations, 'name'))); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>' . ($locale_activities === 'en' ? 'No activities found.' : 'Δεν βρέθηκαν δραστηριότητες.') . '</p>';
        endif;
        ?>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        var minPrice = <?php echo $min_price; ?>;
        var maxPrice = <?php echo $max_price; ?>;

        $("#price-slider").slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [minPrice, maxPrice],
            slide: function(event, ui) {
                $("#price-range").text("€" + ui.values[0] + " - €" + ui.values[1]);
                filterActivities();
            }
        });

        $("#duration-slider").slider({
            range: true,
            min: 0,
            max: 200,
            values: [0, 200],
            slide: function(event, ui) {
                $("#duration-range").text(ui.values[0] + " - " + ui.values[1] + " hrs");
                filterActivities();
            }
        });

        $("#category-filter, #rating-filter, #location-filter").on("change", filterActivities);

        function filterActivities() {
            var selectedCategory = $("#category-filter").val();
            var selectedRating = $("#rating-filter").val();
            var selectedLocation = $("#location-filter").val();
            var priceRange = $("#price-slider").slider("values");
            var durationRange = $("#duration-slider").slider("values");

            $(".activity-card").each(function() {
                var card = $(this);
                var cardCategory = card.hasClass(selectedCategory) || selectedCategory === '';
                var cardLocation = card.hasClass(selectedLocation) || selectedLocation === '';
                var cardPrice = parseInt(card.data("price"), 10);
                var cardRating = parseInt(card.data("rating"), 10);
                var cardDuration = parseInt(card.data("duration"), 10);

                var priceCondition = (cardPrice >= priceRange[0] && cardPrice <= priceRange[1]);
                var ratingCondition = (selectedRating === '' || cardRating == selectedRating);
                var durationCondition = (cardDuration >= durationRange[0] && cardDuration <= durationRange[1]);

                if (cardCategory && cardLocation && priceCondition && ratingCondition && durationCondition) {
                    card.show();
                } else {
                    card.hide();
                }
            });
        }
    });
</script>

<?php get_footer(); ?>
