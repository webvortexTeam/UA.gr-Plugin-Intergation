
<?php

if (!defined('ABSPATH')) exit;
function activity_reviews_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'single/reviews.php';
    return ob_get_clean();
}
add_shortcode('webvortex_activity_reviews', 'activity_reviews_shortcode');
function enqueue_activity_styles_scripts() {
    if (is_page_template('single-activity.php')) {
        global $wp_styles;
        $allowed_styles = array_filter($wp_styles->queue, fn($handle) => strpos($handle, 'unlimitedv2') !== false);

        foreach ($wp_styles->queue as $handle) {
            wp_dequeue_style($handle);
            wp_deregister_style($handle);
        }

        foreach ($allowed_styles as $handle) {
            wp_enqueue_style($handle);
        }

        wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', [], null, true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_activity_styles_scripts', 100);

$show_headers = get_option('vortex_ua_show_headers', 'yes');

if ($show_headers === 'yes') {
    $selected_header = get_option('vortex_ua_selected_header');
    if ($selected_header) {
        if (did_action('elementor/loaded')) {
            echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display($selected_header);
        } elseif (class_exists('Vc_Manager') && get_post_type($selected_header) == 'wpb_vc_templates') {
            echo apply_filters('the_content', get_post_field('post_content', $selected_header));
        } elseif (class_exists('FLBuilder') && get_post_type($selected_header) == 'fl-builder-template') {
            echo FLBuilder::render_content_by_id($selected_header);
        } else {
            echo apply_filters('the_content', get_post_field('post_content', $selected_header));
        }
    } else {
        get_header();
    }
}

if (have_posts()) : while (have_posts()) : the_post();

    // Retrieve ACF fields
    $title = get_field('title');
    $activity_id = get_field('activity_id');
    $provider_id = get_field('provider_id');
    $rating = get_field('rating');
    $active_months = get_field('active_months');
    $category_ids = get_field('category_ids');
    $duration = get_field('duration');
    $description = get_field('description');
    $photos = get_field('photos');
    $itineraries = get_field('itineraries');
    $locale_activities = get_option('activity_api_locale', 'gr');
    $meeting_point = get_field('meeting_point');
    $meeting_time = get_field('meeting_time');
    $additional_info = get_field('additional_info');
    $min_price = null;

    if (have_rows('field_webvortex_itineraries')) {
        while (have_rows('field_webvortex_itineraries')) {
            the_row();
            $price = get_sub_field('field_webvortex_itinerary_min_price');
            if ($price !== '' && ($min_price === null || $price < $min_price)) {
                $min_price = $price;
            }
        }
    }

    $all_reviews = [];
    if (!empty($itineraries)) {
        foreach ($itineraries as $itinerary) {
            if (!empty($itinerary['ratings'])) {
                foreach ($itinerary['ratings'] as $review) {
                    $all_reviews[] = $review;
                }
            }
        }
    }
    $reviews = get_field('reviews');

    $button_color = get_option('vortex_ua_button_color', '#000000');
    $itinerary_bg_color = get_option('vortex_ua_itinerary_bg_color', '#f6f9fc');

    echo '<style>.vortex-ua-button{background-color:' . esc_attr($button_color) . ';}.vortex-ua-itinerary-bg{background-color:' . esc_attr($itinerary_bg_color) . ';}.itinerary-container{margin-bottom:1rem;border-radius:0.5rem;z-index:9900099;}.itinerary-header{padding:0.75rem 1rem;cursor:pointer;border-bottom:1px solid #e5e7eb;}.itinerary-content{padding:1rem;}.itinerary-price{font-size:1rem;}.itinerary-header h4{margin:0;}</style>';
    if (get_option('vortex_ua_show_map', 'yes') === 'yes') {
        echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/><script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>';
    }
    ?>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <div class="bg-white">
        <div class="pt-6">
            <?php if (get_option('vortex_ua_show_breadcrumbs', 'yes') === 'yes') include plugin_dir_path(__FILE__) . 'single/nav.php'; ?>
            <?php include plugin_dir_path(__FILE__) . 'single/gallery.php'; ?>

            <article data-id="<?php echo esc_attr($activity_id); ?>" class="mx-auto max-w-2xl px-4 pb-16 pt-10 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto,auto,1fr] lg:gap-x-8 lg:px-8 lg:pb-24 lg:pt-16">
                <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
                    <?php
                    $post_id = get_the_ID();
                    $terms = wp_get_post_terms($post_id, 'activity_category');
                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            echo '<a href="' . esc_url(get_term_link($term->term_id)) . '">' . esc_html($term->name) . '</a><br>';
                        }
                    } else {
                        echo $locale_activities === 'en' ? 'No categories found' : 'Δεν βρέθηκαν κατηγορίες';
                    }
                    ?>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl"><?php echo esc_html($title); ?></h1>
                </div>

                <div class="mt-4 lg:row-span-3 lg:mt-0">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-yellow-300 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20"><path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/></svg>
                        <p class="ms-2 text-sm font-bold text-gray-900 dark:text-white"><?php echo esc_html($rating); ?></p>
                        <span class="w-1 h-1 mx-1.5 bg-gray-500 rounded-full dark:bg-gray-400"></span>
                        <?php if (get_option('vortex_ua_show_reviews', 'yes') === 'yes'): ?>
                        <a href="#unlimited-a-reviews" class="text-sm font-medium text-gray-900 underline hover:no-underline dark:text-white"><?php echo $locale_activities === 'en' ? 'Read Reviews' : 'Διαβάστε κριτικές'; ?></a>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($min_price)): ?>
                    <div class="mt-10">
                        <h2 class="text-sm font-medium text-gray-900"><?php echo $locale_activities === 'en' ? 'from' : 'από'; ?></h2>
                        <div class="text-3xl tracking-tight text-gray-900"><?php echo wp_kses_post($min_price); ?> €</div>
                    </div>
                    <?php endif; ?>

                    <?php include plugin_dir_path(__FILE__) . 'single/faq-popup.php'; ?>

                    <?php if (!empty($additional_info)): ?>
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900"><?php echo $locale_activities === 'en' ? 'Extra Information' : 'Επιπλέον Πληροφορίες'; ?></h3>
                        <div class="prose max-w-none mt-4 text-gray-700"><?php echo wp_kses_post($additional_info); ?></div>
                    </div>
                    <?php endif; ?>
                    <br>
                    <a href="#booktypesv" class="mt-4 px-4 py-2 vortex-ua-button text-white rounded"><?php echo $locale_activities === 'en' ? 'Book Now' : 'Ας ξεκινήσουμε'; ?></a>
                    <?php echo wp_kses_post(get_option('vortex_ua_custom_html_section_2', '')); ?>
                </div>

                <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
                    <?php
                    if (get_option('vortex_ua_show_read_more', 'yes') === 'yes') {
                        include plugin_dir_path(__FILE__) . 'single/perigrafi.php';
                    } else {
                        include plugin_dir_path(__FILE__) . 'single/perigrafi-without.php';
                    }

                    echo wp_kses_post(get_option('vortex_ua_custom_html_section_3', ''));

                    if (!empty($itineraries)): ?>
                    <div class="mt-10" id="booktypesv">
                        <div class="mt-4 space-y-4">
                            <?php foreach ($itineraries as $index => $itinerary): ?>
                            <div class="vortex-ua-itinerary-bg p-4 mb-4 rounded-lg shadow-md itinerary-container" data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>">
                                <div class="itinerary-header flex justify-between items-center cursor-pointer p-2" data-index="<?php echo $index; ?>">
                                    <h4 class="text-lg font-semibold"><?php echo esc_html($itinerary['title'] ?? $title); ?></h4>
                                    <div class="itinerary-price"><?php echo $locale_activities === 'en' ? 'From' : 'Από'; ?> <?php echo esc_html($itinerary['min_price'] ?? ''); ?> €</div>
                                </div>
                                <div class="itinerary-content <?php echo count($itineraries) > 1 ? 'hidden' : ''; ?> mt-4 p-4">
                                    <p><?php echo $locale_activities === 'en' ? 'Activity ID:' : 'ID δραστηριότητας:'; ?> <?php echo esc_html($itinerary['itinerary_id']); ?></p>
                                    <div class="prose max-w-none mb-2 mt-4 text-gray-700"><?php echo wp_kses_post($itinerary['description'] ?? ''); ?></div>
                                    <p><strong><?php echo $locale_activities === 'en' ? 'Minimum Persons:' : 'Ελάχιστα Άτομα:'; ?></strong> <?php echo esc_html($itinerary['min_persons'] ?? ''); ?></p>
                                    <p><strong><?php echo $locale_activities === 'en' ? 'Minimum Age:' : 'Ελάχιστη Ηλικία:'; ?></strong> <?php echo esc_html($itinerary['min_age'] ?? ''); ?></p>
                                    <p><strong><?php echo $locale_activities === 'en' ? 'Duration:' : 'Διάρκεια:'; ?></strong> <?php echo esc_html($itinerary['duration'] ?? ''); ?></p>
                                    <p><strong><?php echo $locale_activities === 'en' ? 'Active Months:' : 'Ενεργοί μήνες:'; ?></strong> <?php echo esc_html($active_months); ?></p>
                                    <?php include plugin_dir_path(__FILE__) . 'single/itinerary-we-speak.php'; ?>
                                    <?php include plugin_dir_path(__FILE__) . 'single/itinerary-included.php'; ?>
                                    <?php include plugin_dir_path(__FILE__) . 'single/itinerary-do-not-forget.php'; ?>
                                    <?php if (get_option('vortex_ua_show_map', 'yes') === 'yes') include plugin_dir_path(__FILE__) . 'single/map.php'; ?>
                                    <div class="cancellationModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                        <div class="bg-white p-6 rounded-lg shadow-lg">
                                            <h3 class="text-lg font-semibold mb-4"><?php echo $locale_activities === 'en' ? 'Cancellation Policy' : 'Πολιτική Ακύρωσης'; ?></h3>
                                            <div class="cancellationContent text-gray-700">
                                                <h3 class="text-lg"><?php echo esc_html($itinerary['cancellation_policy']['title']); ?></h3>
                                                <p class="text-sm"><?php echo wp_kses_post($itinerary['cancellation_policy']['description']); ?></p>
                                            </div>
                                            <button class="closeModalBtn mt-4 px-4 py-2 bg-black text-white rounded"><?php echo $locale_activities === 'en' ? 'Close' : 'Κλείσιμο'; ?></button>
                                        </div>
                                    </div>
                                    <button data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="bookNowBtn mt-4 px-4 py-2 vortex-ua-button text-white rounded"><?php echo $locale_activities === 'en' ? 'Book Now' : 'Κράτηση τώρα'; ?></button>
                                    <div class="bookingModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                        <div class="bg-white p-6 rounded-lg shadow-lg w-full h-full md:w-1/2 md:h-auto relative">
                                            <h3 class="text-lg font-semibold mb-4"><?php echo $locale_activities === 'en' ? 'You are 5 steps away!' : 'Είστε 5 βήματα μακριά!'; ?></h3>
                                            <p><?php echo $locale_activities === 'en' ? 'Total Price:' : 'Συνολική Τιμή:'; ?> <span class="booking-price"></span></p>
                                            <?php echo wp_kses_post(get_option('vortex_ua_custom_html_inside_booking', '')); ?>
                                            <div class="bookingContent text-gray-700">
                                                <div class="step1 booking-step">
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Select Date' : 'Επιλέξτε ημερομηνία'; ?></h4>
                                                    <div class="date-picker-container hidden">
                                                        <input type="text" id="datetime-<?php echo esc_attr($itinerary['itinerary_id']); ?>" data-itinerary-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="flatpickr-input mt-2 p-2 border rounded w-full" />
                                                    </div>
                                                    <button class="nextToStep2 mt-4 px-4 py-2 vortex-ua-button text-white rounded"><?php echo $locale_activities === 'en' ? 'Next' : 'Επόμενο'; ?></button>
                                                </div>
                                                <div class="step2 booking-step hidden">
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Select Time' : 'Επιλέξτε ώρα'; ?></h4>
                                                    <div class="time-slot-container mb-4 hidden">
                                                        <select id="timeslot-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="time-slot-select mt-2 p-2 border rounded w-full">
                                                            <option value=""><?php echo $locale_activities === 'en' ? 'Select' : 'Επιλέξτε'; ?></option>
                                                        </select>
                                                    </div>
                                                    <button class="backToStep1 mt-4 px-4 py-2 bg-gray-500 text-white rounded"><?php echo $locale_activities === 'en' ? 'Back' : 'Πίσω'; ?></button>
                                                    <button class="nextToStep3 mt-4 px-4 py-2 vortex-ua-button text-white rounded"><?php echo $locale_activities === 'en' ? 'Next' : 'Επόμενο'; ?></button>
                                                </div>
                                                <div class="step3 booking-step hidden">
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Persons & Extras' : 'Άτομα & Εξτρά'; ?></h4>
                                                    <div class="flex items-center space-x-4">
                                                        <button class="decrease-btn bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">-</button>
                                                        <span class="person-count text-2xl font-semibold">0</span>
                                                        <button class="increase-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+</button>
                                                    </div>
                                                    <h3 class="mt-4 extras-header"><?php echo $locale_activities === 'en' ? 'Extra Services' : 'Έξτρα Υπηρεσίες'; ?></h3>
                                                    <div class="facilities-container mb-4 hidden">
                                                        <select multiple id="facilities-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="facilities-select mt-2 p-2 border rounded w-full"></select>
                                                    </div>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            function checkAndToggleExtras() {
                                                                const extrasHeader = document.querySelector('.extras-header');
                                                                const facilitiesContainer = document.querySelector('.facilities-container');
                                                                const facilitiesSelect = facilitiesContainer.querySelector('select');
                                                                if (facilitiesSelect.options.length === 0) {
                                                                    extrasHeader.style.display = 'none';
                                                                    facilitiesContainer.style.display = 'none';
                                                                } else {
                                                                    extrasHeader.style.display = 'block';
                                                                    facilitiesContainer.style.display = 'block';
                                                                }
                                                            }
                                                            checkAndToggleExtras();
                                                        });
                                                    </script>
                                                    <button class="backToStep2 mt-4 px-4 py-2 bg-gray-500 text-white rounded"><?php echo $locale_activities === 'en' ? 'Back' : 'Πίσω'; ?></button>
                                                    <button class="nextToStep4 mt-4 px-4 py-2 vortex-ua-button text-white rounded"><?php echo $locale_activities === 'en' ? 'Next' : 'Επόμενο'; ?></button>
                                                </div>
                                                <div class="step4 booking-step hidden">
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Enter Your Details' : 'Εισάγετε τα στοιχεία σας'; ?></h4>
                                                    <input type="text" placeholder="<?php echo $locale_activities === 'en' ? 'First Name' : 'Όνομα'; ?>" name="customer_name" id="customer_name-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="mt-2 p-2 border rounded w-full" />
                                                    <input type="text" placeholder="<?php echo $locale_activities === 'en' ? 'Last Name' : 'Επίθετο'; ?>" name="customer_surname" id="customer_surname-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="mt-2 p-2 border rounded w-full" />
                                                    <input type="email" placeholder="Email" name="customer_email" id="customer_email-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="mt-2 p-2 border rounded w-full" />
                                                    <input type="number" placeholder="<?php echo $locale_activities === 'en' ? 'Phone' : 'Τηλέφωνο'; ?>" name="customer_phone" id="customer_phone-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="mt-2 p-2 border rounded w-full" />
                                                    <button class="backToStep3 mt-4 px-4 py-2 bg-gray-500 text-white rounded"><?php echo $locale_activities === 'en' ? 'Back' : 'Πίσω'; ?></button>
                                                    <button class="nextToStep5 mt-4 px-4 py-2 vortex-ua-button text-white rounded"><?php echo $locale_activities === 'en' ? 'Next' : 'Επόμενο'; ?></button>
                                                </div>
                                                <div class="step5 booking-step hidden">
                                                    <div class="mt-4">
                                                        <h5 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Booking Summary' : 'Σύνοψη Κράτησης'; ?></h5>
                                                        <p><strong><?php echo $locale_activities === 'en' ? 'Date:' : 'Ημερομηνία:'; ?></strong> <span id="summary-date"></span></p>
                                                        <p><strong><?php echo $locale_activities === 'en' ? 'Time:' : 'Ώρα:'; ?></strong> <span id="summary-time"></span></p>
                                                        <p><strong><?php echo $locale_activities === 'en' ? 'Persons:' : 'Άτομα:'; ?></strong> <span id="summary-persons"></span></p>
                                                        <p><strong><?php echo $locale_activities === 'en' ? 'Extra Services:' : 'Έξτρα Παροχές:'; ?></strong> <span id="summary-facilities"></span></p>
                                                        <p><strong><?php echo $locale_activities === 'en' ? 'First Name:' : 'Όνομα:'; ?></strong> <span id="summary-name"></span></p>
                                                        <p><strong><?php echo $locale_activities === 'en' ? 'Last Name:' : 'Επίθετο:'; ?></strong> <span id="summary-surname"></span></p>
                                                        <p><strong>Email:</strong> <span id="summary-email"></span></p>
                                                        <p><strong><?php echo $locale_activities === 'en' ? 'Phone:' : 'Τηλέφωνο:'; ?></strong> <span id="summary-phone"></span></p>
                                                    </div>
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Confirm Booking' : 'Επιβεβαίωση κράτησης'; ?></h4>
                                                    <p class="mt-2"><?php echo $locale_activities === 'en' ? 'Are you sure you want to proceed?' : 'Είστε σίγουροι ότι θέλετε να συνεχίσετε;'; ?></p>
                                                    <button class="backToStep4 mt-4 px-4 py-2 bg-gray-500 text-white rounded"><?php echo $locale_activities === 'en' ? 'Back' : 'Πίσω'; ?></button>
                                                    <button class="confirmBooking mt-4 px-4 py-2 vortex-ua-button text-white rounded" id="confirmBookingButton"><?php echo $locale_activities === 'en' ? 'Confirm Booking' : 'Πληρωμή Κράτησης'; ?></button>
                                                    <script type="text/javascript">
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            document.getElementById('confirmBookingButton').addEventListener('click', function(e) {
                                                                e.preventDefault();
                                                                var data = {
                                                                    action: 'send_booking_email',
                                                                    booking_nonce: '<?php echo wp_create_nonce('vortex_ua_book_notice'); ?>',
                                                                    customer_name: document.getElementById('customer_name-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>').value,
                                                                    customer_surname: document.getElementById('customer_surname-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>').value,
                                                                    customer_email: document.getElementById('customer_email-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>').value,
                                                                    customer_phone: document.getElementById('customer_phone-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>').value,
                                                                    activity_id: '<?php echo esc_js($activity_id); ?>',
                                                                    itinerary_id: '<?php echo esc_js($itinerary['itinerary_id']); ?>'
                                                                };
                                                                jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                                                                    if (response.success) {}
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                                <?php echo wp_kses_post(get_option('vortex_ua_custom_html_section_1', '')); ?>
                                            </div>
                                            <button class="closeBookingModalBtn absolute top-4 right-4 bg-gray-300 text-black px-2 py-1 rounded hover:bg-gray-400">X</button>
                                        </div>
                                    </div>
                                    <button class="mt-4 px-4 py-2 text-black text-sm rounded cancellation-button"><?php echo $locale_activities === 'en' ? 'Cancellation Policy' : 'Πολιτική Ακύρωσης'; ?></button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a id="vortex-ua-info-new-btn" class="mt-4 px-4 py-2"><?php echo $locale_activities === 'en' ? 'More Information' : 'Επιπλέον Πληροφορίες'; ?></a><br>
                    <a href="https://www.unlimited-adrenaline.gr/terms-of-use" target="_blank" class="mt-4 px-4 py-2"><?php echo $locale_activities === 'en' ? 'Terms of Use' : 'Όροι Χρήσης'; ?> ↗</a>
                    <?php echo wp_kses_post(get_option('vortex_ua_custom_html_section_4', '')); ?>
                    <?php endif; ?>
                    <?php if (get_option('vortex_ua_show_reviews', 'yes') === 'yes') echo do_shortcode('[webvortex_activity_reviews]'); ?>
                </div>
            </article>
        </div>
    </div>
    <?php include plugin_dir_path(__FILE__) . 'single/main-functions-js.php'; ?>
    <?php endwhile; endif; if (get_option('vortex_ua_show_headers', 'yes') === 'yes') get_footer(); ?>