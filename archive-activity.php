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
$total_activities = $all_activities->found_posts;

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
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

<section class="flex items-center justify-center h-screen">
    <div class="w-full max-w-6xl relative h-3/4">
        <img src="<?php echo esc_url(get_option('vortex_ua_walpaper_url')); ?>" alt="Hero Image Activities" class="w-full h-full object-cover" style="border-radius: 55px;">
        <div class="absolute inset-0 flex items-center justify-center text-white text-6xl">
            <?php echo esc_html($duration); ?> <?php echo $locale_activities === 'en' ? 'Activities' : 'Δραστηριότητες'; ?>
        </div>
    </div>
</section>

<div class="container mx-auto p-4 flex">
    <!-- Main Content Wrapper -->
    <div class="flex-1">
        <ul class="mb-4 text-lg text-gray-800">
            <li id="total-activities">
                <?php if ($locale_activities === 'en') {
                    echo 'Total <span id="activity-count" style="color: #fa345b; font-size: medium;">' . $total_activities . '</span> activities found';
                } else {
                    echo 'Βρέθηκαν <span id="activity-count" style="color: #fa345b; font-size: medium;">' . $total_activities . '</span> δραστηριότητες ';
                } ?>
            </li>
        </ul>
        <!-- Sort Buttons -->
<div class="flex justify-end mb-4">
  <div class="relative inline-block text-left">
    <button id="dropdownMenuButton" class="bg-black text-white px-4 py-2 rounded inline-flex items-center" style="border-radius: 25px;">
     <?php echo $locale_activities === 'en' ? 'Sort By' : 'Ταξινόμηση Κατά'; ?>
      <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
      </svg>
    </button>
    <div id="dropdownMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="z-index: 9999; border-radius: 25px;">
      <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="dropdownMenuButton">
        <a href="#" id="sort-price" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem"><?php echo $locale_activities === 'en' ? 'Lowest Price' : 'Χαμηλότερη Τιμή'; ?></a>
        <a href="#" id="sort-rating" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem"><?php echo $locale_activities === 'en' ? 'Highest Rating' : 'Υψηλότερη Βαθμολογία'; ?></a>
        <a href="#" id="sort-duration" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem"><?php echo $locale_activities === 'en' ? 'Highest Duration' : 'Υψηλότερη Διάρκεια'; ?></a>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('dropdownMenuButton').addEventListener('click', function() {
    var menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
  });

  window.addEventListener('click', function(e) {
    if (!document.getElementById('dropdownMenuButton').contains(e.target)) {
      document.getElementById('dropdownMenu').classList.add('hidden');
    }
  });
</script>


        <div id="activities-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
            <?php
            $activity_query = new WP_Query(array(
                'post_type' => 'activity',
                'posts_per_page' => 24, // Set the number of posts per page
                'paged' => $paged,
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

            <div class="activity-card <?php echo esc_attr($category_slugs); ?> <?php echo esc_attr($location_slugs); ?> duration-300" data-price="<?php echo esc_attr($min_price); ?>" data-rating="<?php echo esc_attr($rating); ?>" data-duration="<?php echo esc_attr($duration); ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" class="block relative">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-64 object-cover rounded-t-lg" style="border-radius: 25px;">
                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 mb-4 bg-white bg-opacity-25 backdrop-blur-sm text-white px-4 py-2 rounded-full flex space-x-4 whitespace-nowrap overflow-hidden text-ellipsis" style="border-radius: 25px; backdrop-filter: blur(10px);">
                            <div class="flex items-center space-x-1">
                                <span class="text-sm"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="white" d="M12.25 2c-5.514 0-10 4.486-10 10s4.486 10 10 10s10-4.486 10-10s-4.486-10-10-10M18 13h-6.75V6h2v5H18z" /></svg></span>
                                <p class="text-sm"><?php echo esc_html($duration); ?> <?php echo $locale_activities === 'en' ? 'hours' : 'ώρες'; ?></p>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-sm"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="white" d="M17.657 5.304c-3.124-3.073-8.189-3.073-11.313 0a7.78 7.78 0 0 0 0 11.13L12 21.999l5.657-5.565a7.78 7.78 0 0 0 0-11.13M12 13.499c-.668 0-1.295-.26-1.768-.732a2.503 2.503 0 0 1 0-3.536c.472-.472 1.1-.732 1.768-.732s1.296.26 1.768.732a2.503 2.503 0 0 1 0 3.536c-.472.472-1.1.732-1.768.732" /></svg></span>
                                <p class="text-sm"><?php echo esc_html(explode(', ', join(', ', wp_list_pluck($locations, 'name')))[0]); ?></p>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
                <div class="p-4">
                    <a href="<?php the_permalink(); ?>">
                        <h2 class="text-m font-bold text-gray-900"><?php the_title(); ?></h2>
                        <p class="text-sm text-gray-700"><?php echo $locale_activities === 'en' ? 'from' : 'από'; ?> <?php echo esc_html($min_price); ?>€ / <?php echo $locale_activities === 'en' ? 'person' : 'το άτομο'; ?></p>
                    </a>
                    <div class="flex items-center mt-2 text-gray-700 space-x-2">
                        <div class="flex items-center space-x-1">
                            <span class="text-sm"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="black" d="m7.325 18.923l1.24-5.313l-4.123-3.572l5.431-.47L12 4.557l2.127 5.01l5.43.47l-4.123 3.572l1.241 5.313L12 16.102z" /></svg></span>
                            <p class="text-sm"><?php echo esc_html($rating); ?></p>
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

<div class="flex justify-center mt-4">
    <?php
    $pagination_links = paginate_links(array(
        'total' => $activity_query->max_num_pages,
        'current' => $paged,
        'prev_text' => __('« Prev'),
        'next_text' => __('Next »'),
        'type' => 'array',
        'show_all' => true,
    ));

    if (!empty($pagination_links)) {
        echo '<nav class="flex space-x-2" aria-label="Pagination">';
        foreach ($pagination_links as $link) {
            $link = str_replace('page-numbers', 'page-numbers rounded-full px-4 py-2', $link);
            echo '<div style="border-radius: 25px; --tw-text-opacity: 1; color: rgba(250, 52, 91, var(--tw-text-opacity)); --tw-border-opacity: 1; border-color: rgba(250, 52, 91, var(--tw-border-opacity)); --tw-bg-opacity: 1;" class="border hover:bg-[rgba(250,52,91,1)] hover:text-white">' . $link . '</div>';
        }
        echo '</nav>';
    }
    ?>
</div>


    </div>
</div>

<style>
    #toggle-filters-vortex {
        padding: 10px 20px;
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
        right: 10px;
        background-color: #fa345b;
        color: #FFFFFF;
        text-align: center;
        display: inline-block;
        border-radius: 25px;
        max-width: 50px; /* Ensure it doesn't overflow */
        word-wrap: break-word; /* Break the text to prevent overflow */
    }

    @media (max-width: 768px) {
        #toggle-filters-vortex {
            right: 5px; /* Adjust the right position for smaller screens */
            padding: 8px 15px; /* Adjust padding for smaller screens */
            font-size: 14px; /* Adjust font size for smaller screens */
            max-width: 40px; /* Ensure it doesn't overflow on smaller screens */
        }
    }
</style>

<a id="toggle-filters-vortex" class="rounded-md text-sm">
    <?php echo $locale_activities === 'en' ? 'F' : 'Φ'; ?><br>
    <?php echo $locale_activities === 'en' ? 'I' : 'Ι'; ?><br>
    <?php echo $locale_activities === 'en' ? 'L' : 'Λ'; ?><br>
    <?php echo $locale_activities === 'en' ? 'T' : 'Τ'; ?><br>
    <?php echo $locale_activities === 'en' ? 'E' : 'Ρ'; ?><br>
    <?php echo $locale_activities === 'en' ? 'R' : 'Α'; ?><br>
    <?php echo $locale_activities === 'en' ? 'S' : ''; ?><br>
</a>

<div id="filter-container" class="w-64 fixed right-0 top-1/2 transform -translate-y-1/2 bg-white shadow-lg p-6 rounded-lg hidden border border-gray-200">
    <!-- Category Filter -->
    <div class="mb-6">
        <label for="category-filter" class="block text-sm font-medium text-gray-900">
            <?php echo $locale_activities === 'en' ? 'Categories:' : 'Κατηγορίες:'; ?>
        </label>
        <select id="category-filter" class="mt-2 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-#fa345b focus:border-#fa345b sm:text-sm rounded-md">
            <option value=""><?php echo $locale_activities === 'en' ? 'All Categories' : 'Όλες οι Κατηγορίες'; ?></option>
            <?php
            $categories = get_terms(array(
                'taxonomy' => 'activity_category',
                'hide_empty' => true,
                'exclude' => array(
                    get_term_by('name', 'Αέρας', 'activity_category')->term_id,
                    get_term_by('name', 'Νερό', 'activity_category')->term_id,
                    get_term_by('name', 'Πόλη', 'activity_category')->term_id,
                    get_term_by('name', 'Air', 'activity_category')->term_id,
                    get_term_by('name', 'Tours', 'activity_category')->term_id,
                    get_term_by('name', 'City', 'activity_category')->term_id,
                    get_term_by('name', 'Water', 'activity_category')->term_id
                )
            ));
            foreach ($categories as $category) {
                echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
            }
            ?>
        </select>
    </div>

    <!-- Location Filter -->
    <div class="mb-6">
        <label for="location-filter" class="block text-sm font-medium text-gray-900">
            <?php echo $locale_activities === 'en' ? 'Locations:' : 'Τοποθεσίες:'; ?>
        </label>
        <select id="location-filter" class="mt-2 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-#fa345b focus:border-#fa345b sm:text-sm rounded-md">
            <option value=""><?php echo $locale_activities === 'en' ? 'All Locations' : 'Όλες οι Τοποθεσίες'; ?></option>
            <?php
$locations = get_terms(array(
    'taxonomy' => 'activity_location',
    'hide_empty' => true,
    'number' => 0, // Ensures we retrieve all terms regardless of count
));
            foreach ($locations as $location) {
                echo '<option value="' . esc_attr($location->slug) . '">' . esc_html($location->name) . '</option>';
            }
            ?>
        </select>
    </div>

    <!-- Rating Filter -->
    <div class="mb-6">
        <label for="rating-filter" class="block text-sm font-medium text-gray-900">
            <?php echo $locale_activities === 'en' ? 'Reviews:' : 'Κριτικές:'; ?>
        </label>
        <select id="rating-filter" class="mt-2 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-#fa345b focus:border-#fa345b sm:text-sm rounded-md">
            <option value=""><?php echo $locale_activities === 'en' ? 'All Reviews' : 'Όλες οι Κριτικές'; ?></option>
            <option value="1"><?php echo $locale_activities === 'en' ? '★ Star' : '★ Αστέρι'; ?></option>
            <option value="2"><?php echo $locale_activities === 'en' ? '★★ Stars' : '★★ Αστέρια'; ?></option>
            <option value="3"><?php echo $locale_activities === 'en' ? '★★★ Stars' : '★★★ Αστέρια'; ?></option>
            <option value="4"><?php echo $locale_activities === 'en' ? '★★★★ Stars' : '★★★★ Αστέρια'; ?></option>
            <option value="5"><?php echo $locale_activities === 'en' ? '★★★★★ Stars' : '★★★★★ Αστέρια'; ?></option>
        </select>
    </div>

    <!-- Price Slider -->
    <div class="mb-6">
        <label for="price-slider" class="block text-sm font-medium text-gray-900">
            <?php echo $locale_activities === 'en' ? 'Price:' : 'Τιμή:'; ?>
        </label>
<style>
    #price-slider .ui-slider-range {
        background: #fa345b !important;
    }
        #duration-slider .ui-slider-range {
        background: #fa345b !important;
    }
</style>

<div id="price-slider" class="mt-2"></div>
        <div id="price-range" class="text-sm text-gray-900 mt-2"  style="color: #fa345b; font-size: medium;">
            €<?php echo esc_html($min_price); ?> - €<?php echo esc_html($max_price); ?>
        </div>
    </div>

    <!-- Duration Slider -->
    <div class="mb-6">
        <label for="duration-slider" class="block text-sm font-medium text-gray-900">
            <?php echo $locale_activities === 'en' ? 'Duration (hrs):' : 'Διάρκεια (hrs):'; ?>
        </label>
        <div id="duration-slider" class="mt-2"></div>
        <div id="duration-range" class="text-sm text-gray-900 mt-2">0 - 200 hrs</div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#toggle-filters-vortex').on('click', function() {
            $('#filter-container').toggleClass('hidden');
            if ($('#filter-container').hasClass('hidden')) {
                $('#toggle-filters-vortex').text('Show Filters');
            } else {
                $('#toggle-filters-vortex').text('Hide Filters');
            }
        });

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

        $("#sort-price").on("click", function() {
            sortActivities("price");
        });

        $("#sort-rating").on("click", function() {
            sortActivities("rating");
        });

        $("#sort-duration").on("click", function() {
            sortActivities("duration");
        });

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

        function sortActivities(criteria) {
            var cards = $(".activity-card").get();
            cards.sort(function(a, b) {
                var valueA = parseInt($(a).data(criteria), 10);
                var valueB = parseInt($(b).data(criteria), 10);

                if (valueA < valueB) return -1;
                if (valueA > valueB) return 1;
                return 0;
            });

            $.each(cards, function(index, card) {
                $("#activities-grid").append(card);
            });
        }
    });
</script>

<?php get_footer(); ?>
