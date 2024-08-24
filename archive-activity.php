<?php
/**
 * Template Name: Archive Activity
 */
function convertTimeToHours($timeStr) {
    $timeUnits = [
        'min' => 1 / 60,
        'mins' => 1 / 60,
        'minute' => 1 / 60,
        'minutes' => 1 / 60,
        'hr' => 1,
        'hrs' => 1,
        'hour' => 1,
        'hours' => 1,
        'day' => 24,
        'days' => 24,
        'λεπτό' => 1 / 60,
        'λεπτά' => 1 / 60,
        'ώρα' => 1,
        'ώρες' => 1,
        'ημέρα' => 24,
        'ημέρες' => 24
    ];

    $totalHours = 0;

    // Extract time components using regex
    $timeComponents = $timeStr ? preg_match_all('/(\d+)\s*(min|mins|minute|minutes|hr|hrs|hour|hours|day|days|λεπτό|λεπτά|ώρα|ώρες|ημέρα|ημέρες)/', $timeStr, $matches, PREG_SET_ORDER) : [];

    if ($timeComponents) {
        foreach ($matches as $component) {
            $number = (int)$component[1];
            $unit = $component[2];
            $totalHours += $number * $timeUnits[$unit];
        }
    }

    return $totalHours;
}
function enqueue_custom_scripts() {
    wp_enqueue_style('tailwindcss', 'https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script('hammerjs', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', array(), null, true);

    wp_enqueue_style('jquery-ui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


get_header();

$locale_activities = get_option('activity_api_locale', 'gr');
$button_color = get_option('vortex_ua_button_color', '#FA345B');

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
                // Convert the duration to hours
                $durationHours = convertTimeToHours($itinerary['duration']);

                // Continue with your existing logic
                if ($itinerary['min_price'] < $min_price) {
                    $min_price = $itinerary['min_price'];
                }
                if ($itinerary['min_price'] > $max_price) {
                    $max_price = $itinerary['min_price'];
                }

                // Store the maximum duration
                if ($durationHours > $max_duration) {
                    $max_duration = $durationHours;
                }
            }
        }
    }
    wp_reset_postdata();
}

$min_price = $min_price == PHP_INT_MAX ? 0 : $min_price;
$max_duration = isset($max_duration) ? $max_duration : 0; // Ensure max_duration is set
?>
<style>
    #toggle-filters-vortex {
        padding: 10px 20px;
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
        right: 10px;
        background-color: <?php echo $button_color;?>;
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
    #price-slider .ui-slider-range {
        background: <?php echo $button_color;?>; !important;
    }
        #duration-slider .ui-slider-range {
        background: <?php echo $button_color;?>; !important;
    }
    /* Ensure no conflicting CSS rules */
.ui-slider-handle {
    pointer-events: auto;
}

</style>
<div class="container mx-auto p-4 flex">
    <!-- Main Content Wrapper -->
    <div class="flex-1">
        <!-- Total Activities Count -->
        <ul class="mb-4 text-lg text-gray-800">
            <li id="total-activities">
                <?php
                echo $locale_activities === 'en' 
                    ? 'Total <span id="activity-count" style="color: ' . esc_attr($button_color) . '; font-size: medium;">' . esc_html($total_activities) . '</span> activities found' 
                    : 'Βρέθηκαν <span id="activity-count" style="color: ' . esc_attr($button_color) . '; font-size: medium;">' . esc_html($total_activities) . '</span> δραστηριότητες';
                ?>
            </li>
        </ul>

        <!-- Sort Buttons -->
        <div class="flex justify-end mb-4">
            <div class="relative inline-block text-left">
                <button id="dropdownMenuButton" class="bg-black text-white px-4 py-2 rounded inline-flex items-center" style="border-radius: 25px;">
                    <?php echo $locale_activities === 'en' ? 'Sort By' : 'Ταξινόμηση Κατά'; ?>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="<?php echo $button_color; ?>" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="dropdownMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="z-index: 9999; border-radius: 25px;">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="dropdownMenuButton">
                        <a href="#" id="sort-price" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem"><?php echo $locale_activities === 'en' ? 'Lowest Price' : 'Χαμηλότερη Τιμή'; ?></a>
                        <!-- <a href="#" id="sort-rating" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem"><?php echo $locale_activities === 'en' ? 'Highest Rating' : 'Υψηλότερη Βαθμολογία'; ?></a> -->
                        <a href="#" id="sort-duration" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem"><?php echo $locale_activities === 'en' ? 'Lowest Duration' : 'Χαμηλότερη Διάρκεια'; ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Grid -->
        <div id="activities-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
            <?php
            $activity_query = new WP_Query([
                'post_type' => 'activity',
                'posts_per_page' => -1,
            ]);
            if ($activity_query->have_posts()) :
                while ($activity_query->have_posts()) : $activity_query->the_post();
                    $categories = get_the_terms(get_the_ID(), 'activity_category');
                    $locations = get_the_terms(get_the_ID(), 'activity_location');
                    $category_slugs = $categories ? join(',', wp_list_pluck($categories, 'slug')) : '';
                    $location_slugs = $locations ? join(',', wp_list_pluck($locations, 'slug')) : '';
                    $location_names = wp_list_pluck($locations, 'name');
                    $itineraries = get_field('itineraries');
                    $min_price = PHP_INT_MAX;
                    $duration = 0;
                    if ($itineraries) {
                        // Initialize variables to store the first itinerary's values
                        $first_itinerary = reset($itineraries);
                        if ($first_itinerary) {
                            // Set min_price and duration based on the first itinerary
                            $min_price = $first_itinerary['min_price'];
                            $duration = $first_itinerary['duration'];
                        }
                    }

                    $rating = get_field('rating');
                    $min_price = $min_price == PHP_INT_MAX ? 0 : $min_price;
            ?>
            <div class="activity-card duration-300" data-category="<?php echo esc_attr($category_slugs); ?>" data-location="<?php echo esc_attr($location_slugs); ?>" data-price="<?php echo esc_attr($min_price); ?>" data-rating="<?php echo esc_attr($rating); ?>" data-duration="<?php echo esc_attr($duration); ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" class="block relative">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-64 object-cover rounded-t-lg" style="border-radius: 25px;">
                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 mb-4 bg-white bg-opacity-25 backdrop-blur-sm text-white px-4 py-2 rounded-full flex space-x-4 whitespace-nowrap overflow-hidden text-ellipsis" style="border-radius: 25px; backdrop-filter: blur(10px);">
                            <div class="flex items-center space-x-1">
                                <span class="text-sm"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="white" d="M12.25 2c-5.514 0-10 4.486-10 10s4.486 10 10 10 10-4.486 10-10-4.486-10-10-10M18 13h-6.75V6h2v5H18z"/></svg></span>
                                <p class="text-sm"><?php echo esc_html($duration); ?></p>
                            </div>
                            <div class="flex items-center space-x-1 relative" style="z-index: 50;">
                                <span class="text-sm"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="white" d="M17.657 5.304c-3.124-3.073-8.189-3.073-11.313 0a7.78 7.78 0 0 0 0 11.13L12 21.999l5.657-5.565a7.78 7.78 0 0 0 0-11.13M12 13.499c-.668 0-1.295-.26-1.768-.732a2.503 2.503 0 0 1 0-3.536c.472-.472 1.1-.732 1.768-.732s1.296.26 1.768.732a2.503 2.503 0 0 1 0 3.536c-.472.472-1.1.732-1.768.732"/></svg></span>
                                <?php
                                // Capture the list of location names as a single string
                                $location_names = join(', ', wp_list_pluck($locations, 'name'));

                                // Explode the string to get the first location name
                                $first_location_name = explode(', ', $location_names)[0];

                                // Store the first location name in the $LocationFilter variable
                                $LocationFilter = esc_html($first_location_name);
                                ?>

                                <div class="location-name text-sm"><?php echo $LocationFilter; ?></div>

                            </div>
                        </div>
                    </a>
                <?php endif; ?>
                <div class="p-4">
                    <a href="<?php the_permalink(); ?>">
<?php
// Get the terms for the 'activity_category' taxonomy for the current post
$terms = get_the_terms(get_the_ID(), 'activity_category');

// Check if terms were retrieved and if they are valid
if ($terms && !is_wp_error($terms)) {
    // Create an array to hold the term names
    $term_names = array();

    // Loop through each term and add its name to the array
    foreach ($terms as $term) {
        $term_names[] = $term->name;
    }

    // Convert the array of term names into a comma-separated string
    $term_list = implode(', ', $term_names);

    // Output the term list wrapped in a div with the desired style
    echo '<div class="category-list-vortex" style="font-size: 9px;">' . esc_html($term_list) . '</div>';
}
?>
                        <a href="<?php the_permalink(); ?>" class="text-m font-bold text-gray-900"><?php the_title(); ?></a>
                        <p class="text-sm text-gray-700"><?php echo $locale_activities === 'en' ? 'from' : 'από'; ?> <?php echo esc_html($min_price); ?>€ / <?php echo $locale_activities === 'en' ? 'person' : 'το άτομο'; ?></p>
                    </a>
                    <div class="flex items-center mt-2 text-gray-700 space-x-2">
                        <div class="flex items-center space-x-1">
                            <span class="text-sm"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="black" d="m7.325 18.923l1.24-5.313-4.123-3.572 5.431-.47L12 4.557l2.127 5.01 5.43.47-4.123 3.572 1.241 5.313L12 16.102z"/></svg></span>
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
    </div>

    <!-- Filter Toggle Button -->
    <a id="toggle-filters-vortex" class="rounded-md text-sm">
        <?php echo $locale_activities === 'en' ? 'F<br>I<br>L<br>T<br>E<br>R<br>S' : 'Φ<br>Ι<br>Λ<br>Τ<br>Ρ<br>A'; ?>
    </a>

    <!-- Filter Container -->
    <div id="filter-container" class="w-64 fixed right-0 top-1/2 transform -translate-y-1/2 bg-white shadow-lg p-6 rounded-lg hidden border border-gray-200" style="z-index: 999999;">
        <!-- Category Filter -->
        <div class="mb-6">
            <label for="category-filter" class="block text-sm font-medium text-gray-900">
                <?php echo $locale_activities === 'en' ? 'Categories:' : 'Κατηγορίες:'; ?>
            </label>
            <select id="category-filter" class="mt-2 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#fa345b] focus:border-[#fa345b] sm:text-sm rounded-md">
                <option value=""><?php echo $locale_activities === 'en' ? 'All Categories' : 'Όλες οι Κατηγορίες'; ?></option>
                <?php
                $excluded_terms = ['Αέρας', 'Νερό', 'Πόλη', 'Air', 'Tours', 'City', 'Water'];
                $excluded_ids = array_map(fn($name) => get_term_by('name', $name, 'activity_category')->term_id, $excluded_terms);
                $categories = get_terms(['taxonomy' => 'activity_category', 'hide_empty' => true, 'exclude' => $excluded_ids]);
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>

        </div>

        <!-- Location Filter -->
        <div class="mb-6 hidden">
            <label for="location-filter" class="block text-sm font-medium text-gray-900">
                <?php echo $locale_activities === 'en' ? 'Locations:' : 'Τοποθεσίες:'; ?>
            </label>
            <input id="location-filter" type="text" class="mt-2 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#fa345b] focus:border-[#fa345b] sm:text-sm rounded-md" placeholder="<?php echo $locale_activities === 'en' ? 'Type location' : 'Πληκτρολογήστε τοποθεσία'; ?>">
            <div id="location-suggestions" class="mt-2 absolute bg-white border border-gray-300 rounded-md hidden"></div>
        </div>

        <!-- Rating Filter -->
        <div class="mb-6">
            <label for="rating-filter" class="block text-sm font-medium text-gray-900">
                <?php echo $locale_activities === 'en' ? 'Reviews:' : 'Κριτικές:'; ?>
            </label>
            <select id="rating-filter" class="mt-2 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#fa345b] focus:border-[#fa345b] sm:text-sm rounded-md">
                <option value=""><?php echo $locale_activities === 'en' ? 'All Reviews' : 'Όλες οι Κριτικές'; ?></option>
                <?php
                $ratings = [1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'];
                foreach ($ratings as $value => $label) {
                    echo '<option value="' . $value . '">' . ($locale_activities === 'en' ? $label : str_replace(['★', ''], ['★', ''], $label)) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Price Slider -->
        <div class="mb-6">
            <label for="price-slider" class="block text-sm font-medium text-gray-900">
                <?php echo $locale_activities === 'en' ? 'Price:' : 'Τιμή:'; ?>
            </label>
            <div id="price-slider" class="mt-2"></div>
            <div id="price-range" class="text-sm text-gray-900 mt-2" style="color: <?php echo esc_attr($button_color); ?>; font-size: medium;">
                €0 - €<?php echo esc_html($max_price); ?>
            </div>
        </div>

    <!-- Duration Slider -->
    <div class="mb-6">
        <label for="duration-slider" class="block text-sm font-medium text-gray-900">
            <?php echo $locale_activities === 'en' ? 'Duration (hrs):' : 'Διάρκεια (ώρες):'; ?>
        </label>
        <div id="duration-slider" class="mt-2"></div>
        <div id="duration-range" class="text-sm text-gray-900 mt-2">0 - <?php echo number_format($max_duration, 1); ?> hrs</div>
    </div>

    </div> <!-- Closing div for filter-container -->
</div> <!-- Closing div for container -->
<?php
// Ensure proper escaping and quoting of the filterText
$filterText = $locale_activities === 'en' ? 'F<br>I<br>L<br>T<br>E<br>R<br>S' : 'Φ<br>Ι<br>Λ<br>Τ<br>Ρ<br>Α';
?>
    <!-- END FILTER DIV -->
    <script>
jQuery(document).ready(function($) {
    // Dropdown toggle
    $('#dropdownMenuButton').on('click', function() {
        $('#dropdownMenu').toggleClass('hidden');
    });

    // Click outside dropdown to close it
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#dropdownMenuButton, #dropdownMenu').length) {
            $('#dropdownMenu').addClass('hidden');
        }
    });

    var locations = <?php echo json_encode(wp_list_pluck(get_terms(array('taxonomy' => 'activity_location', 'hide_empty' => true)), 'name')); ?>;
    var minPrice = 0;
    var maxPrice = <?php echo $max_price; ?>;
    var maxDurationV = <?php echo number_format($max_duration, 1); ?>;
    // Convert time format to minutes
    function convertTimeToMinutes(timeStr) {
        const timeUnits = {
            'min': 1 / 60,
            'mins': 1 / 60,
            'minute': 1 / 60,
            'minutes': 1 / 60,
            'hr': 1,
            'hrs': 1,
            'hour': 1,
            'hours': 1,
            'day': 24,
            'days': 24,
            'λεπτό': 1 / 60,
            'λεπτά': 1 / 60,
            'ώρα': 1,
            'ώρες': 1,
            'ημέρα': 24,
            'ημέρες': 24
        };

        let totalMinutes = 0;

        // Extract time components using regex
        const timeComponents = timeStr.match(/(\d+)\s*(min|mins|minute|minutes|hr|hrs|hour|hours|day|days|λεπτό|λεπτά|ώρα|ώρες|ημέρα|ημέρες)/g);

        if (timeComponents) {
            timeComponents.forEach(component => {
                const match = component.match(/(\d+)\s*(min|mins|minute|minutes|hr|hrs|hour|hours|day|days|λεπτό|λεπτά|ώρα|ώρες|ημέρα|ημέρες)/);
                if (match) {
                    const [, number, unit] = match;
                    totalMinutes += parseFloat(number) * timeUnits[unit];
                }
            });
        }

        return totalMinutes;
    }

  var $priceSlider = $("#price-slider");
    var $durationSlider = $("#duration-slider");

    // Function to check sliders and reload page if necessary
    function checkAndReload() {
        var priceValues = $priceSlider.slider("values");
        var durationValues = $durationSlider.slider("values");

        if (priceValues[0] === 0 && priceValues[1] === maxPrice && durationValues[0] === 0 && durationValues[1] === maxDurationV) {
            location.reload(); // Reload the page
        }
    }

    // Initialize sliders
    $priceSlider.slider({
        range: true,
        min: 0,
        max: maxPrice,
        values: [0, maxPrice],
        slide: function(event, ui) {
            $("#price-range").text("€" + ui.values[0] + " - €" + ui.values[1]);
            filterActivities();
        },
        stop: checkAndReload // Check when slider stops
    });

    $durationSlider.slider({
        range: true,
        min: 0,
        step: 0.1, // Set the increment to 0.1
        max: maxDurationV, // Representing up to 8 hours (8*60 minutes)
        values: [0, maxDurationV],
        slide: function(event, ui) {
            $("#duration-range").text(ui.values[0] + " - " + ui.values[1] + " hrs");
            filterActivities();
        },
        stop: checkAndReload // Check when slider stops
    });

    $('#location-filter').on('input', function() {
        var input = $(this).val().toLowerCase();
        var suggestions = $('#location-suggestions');
        
        if (input.length === 0) {
            suggestions.empty().addClass('hidden');
            return;
        }

        var filteredLocations = locations.filter(function(location) {
            return location.toLowerCase().indexOf(input) !== -1;
        });

        suggestions.empty().removeClass('hidden');

        filteredLocations.forEach(function(location) {
            suggestions.append('<div class="suggestion-item px-4 py-2 cursor-pointer hover:bg-gray-200">' + location + '</div>');
        });

        $('.suggestion-item').on('click', function() {
            $('#location-filter').val($(this).text());
            suggestions.empty().addClass('hidden');
            filterActivities(); // Trigger filtering when a location is selected
        });
    });
    var filterText = <?php echo json_encode($filterText); ?>;

  $(document).on('click', function(e) {
        if (!$(e.target).closest('#location-filter, #location-suggestions').length) {
            $('#location-suggestions').empty().addClass('hidden');
        }
        if (!$(e.target).closest('#filter-container, #toggle-filters-vortex').length) {
            $('#filter-container').addClass('hidden');
            $('#toggle-filters-vortex').html(filterText);
        }
    });

    $('#toggle-filters-vortex').on('click', function() {
        $('#filter-container').toggleClass('hidden');
        $(this).html($('#filter-container').hasClass('hidden') ? filterText : '');
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
        var selectedLocation = $("#location-filter").val().toLowerCase();
        var priceRange = $("#price-slider").slider("values");
        var durationRange = $("#duration-slider").slider("values");

        $(".activity-card").each(function() {
            var $this = $(this);
            var category = $this.data("category");
            var rating = $this.data("rating");
            var location = $this.find(".location-name").text().toLowerCase();
            var price = $this.data("price");
            var duration = convertTimeToMinutes($this.data("duration"));

            var matchesCategory = !selectedCategory || category.includes(selectedCategory);
            var matchesRating = !selectedRating || rating == selectedRating;
            var matchesLocation = !selectedLocation || location.includes(selectedLocation);
            var matchesPrice = price >= priceRange[0] && price <= priceRange[1];
            var matchesDuration = duration >= durationRange[0] && duration <= durationRange[1];

            if (matchesCategory && matchesRating && matchesLocation && matchesPrice && matchesDuration) {
                $this.show();
            } else {
                $this.hide();
            }
        });

        updateActivityCount();
    }

    function sortActivities(criterion) {
        var $grid = $("#activities-grid");
        var $activities = $grid.children(".activity-card");

        $activities.sort(function(a, b) {
            var aValue, bValue;

            if (criterion === "price") {
                aValue = $(a).data("price");
                bValue = $(b).data("price");
            } else if (criterion === "rating") {
                aValue = $(a).data("rating");
                bValue = $(b).data("rating");
            } else if (criterion === "duration") {
                aValue = convertTimeToMinutes($(a).data("duration"));
                bValue = convertTimeToMinutes($(b).data("duration"));
            }

            return aValue - bValue;
        });

        $grid.append($activities);
    }

    function updateActivityCount() {
        var visibleActivities = $(".activity-card:visible").length;
        $("#activity-count").text(visibleActivities);
    }

});


</script>



<?php get_footer(); ?>
