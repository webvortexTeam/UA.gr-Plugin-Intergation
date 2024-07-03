<?php
/**
 * Template Name: Activity Template
 * Template Post Type: activity
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (function_exists('elementor_theme_do_location') && elementor_theme_do_location('header')) {
    elementor_theme_do_location('header');
} else {
    get_header();
}

if (have_posts()):

    while (have_posts()):
        the_post();

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

        $meeting_point = get_field('meeting_point');
        $meeting_time = get_field('meeting_time');
        $additional_info = get_field('additional_info');
        $min_price = null;
        if (have_rows('field_webvortex_itineraries')):
            while (have_rows('field_webvortex_itineraries')):
                the_row();
                $price = get_sub_field('field_webvortex_itinerary_min_price');
                if ($price !== '' && ($min_price === null || $price < $min_price)) {
                    $min_price = $price;
                }
            endwhile;
        endif;

        $reviews = get_field('reviews');

        $button_color = get_option('vortex_ua_button_color', '#000000');
        $itinerary_bg_color = get_option('vortex_ua_itinerary_bg_color', '#f6f9fc');
echo '<style type="text/css">
    .vortex-ua-button {
        background-color: ' . esc_attr($button_color) . ';
    }
    
    .vortex-ua-itinerary-bg {
        background-color: ' . esc_attr($itinerary_bg_color) . ';
    }
    .itinerary-container {
        margin-bottom: 1rem; /* Add margin between itineraries */
        border-radius: 0.5rem; /* Rounded corners */
            z-index: 9900099;

    }
    .itinerary-header {
        padding: 0.75rem 1rem; /* Padding for the header */
        cursor: pointer; /* Pointer cursor for clickable header */
        border-bottom: 1px solid #e5e7eb; /* Border between header and content */
    }
    .itinerary-content {
        padding: 1rem; /* Padding for the content */
    }
    .itinerary-price {
        font-size: 1rem; /* Font size for the price */
    }
    .itinerary-header h4 {
        margin: 0; /* Remove margin from header title */
    }
</style>';
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<?php 
                $show_map = get_option('vortex_ua_show_map', 'yes');

                if ($show_map === 'yes') {
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<?php                }

                ?> 

        <div class="bg-white">
            <div class="pt-6">

                <?php 
                $show_breadcrumbs = get_option('vortex_ua_show_breadcrumbs', 'yes');

                if ($show_breadcrumbs === 'yes') {
                    include plugin_dir_path(__FILE__) . 'single/nav.php';
                }

                ?>  
                <?php include plugin_dir_path(__FILE__) . 'single/gallery.php'; ?>


                <article data-id="<?php echo esc_attr($activity_id); ?>"
                    class="mx-auto max-w-2xl px-4 pb-16 pt-10 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto,auto,1fr] lg:gap-x-8 lg:px-8 lg:pb-24 lg:pt-16">
                    <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl"><?php echo esc_html($title); ?>
                        </h1>
                    </div>

                    <!-- Options -->
                    <div class="mt-4 lg:row-span-3 lg:mt-0">
                        <?php if (!empty($min_price)): ?>
                            <div class="mt-10">
                                <h2 class="text-sm font-medium text-gray-900">από</h2>
                                <div class="text-3xl tracking-tight text-gray-900">
                                    <?php echo wp_kses_post($min_price); ?> €
                                </div>
                            </div>
                        <?php endif; ?>
                        <h2 class="sr-only">Πληροφορίες Δραστηριότητας</h2>
                        <p class="text-2xl tracking-tight text-gray-900"><?php echo esc_html($rating); ?> Αστέρια</p>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Πληροφορίες</h3>
                            <div class="mt-4 space-y-2 text-sm text-gray-700">
                                <p>
                                    <stron g>Ενεργοί μήνες:</strong> <?php echo esc_html($active_months); ?>
                                </p>
                            </div>
                        </div>
                        <?php include plugin_dir_path(__FILE__) . 'single/faq-popup.php'; ?>


                        <?php if (!empty($additional_info)): ?>
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900">Επιπλέον Πληροφορίες</h3>
                                <div class="prose max-w-none mt-4 text-gray-700">
                                    <?php echo wp_kses_post($additional_info); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <br></br>
                        <a href="#booktypesv" class="mt-4 px-4 py-2 vortex-ua-button text-white rounded">Κράτηση τώρα</a>

                    </div>

                    <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
                    <?php include plugin_dir_path(__FILE__) . 'single/perigrafi.php'; ?>

<?php if (!empty($itineraries)): ?>
    <div class="mt-10" id="booktypesv">
        <div class="mt-4 space-y-4">
            <?php foreach ($itineraries as $index => $itinerary): ?>
                <div class="vortex-ua-itinerary-bg p-4 mb-4 rounded-lg shadow-md itinerary-container"
                    data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>">
                    <div class="itinerary-header flex justify-between items-center cursor-pointer p-2" data-index="<?php echo $index; ?>">
                        <h4 class="text-lg font-semibold">
                            <?php  echo esc_html($itinerary['title'] ?? $title); ?>
                        </h4>
                        <div class="itinerary-price">Από <?php echo esc_html($itinerary['min_price'] ?? ''); ?> €</div>
                    </div>
                    <div class="itinerary-content <?php echo count($itineraries) > 1 ? 'hidden' : ''; ?> mt-4 p-4">
                        <div class="prose max-w-none mb-2 mt-4 text-gray-700">
                            <?php echo wp_kses_post($itinerary['description'] ?? ''); ?>
                        </div>
                        <p><strong>Ελάχιστη Ηλικία:</strong> <?php echo esc_html($itinerary['min_age'] ?? ''); ?></p>
                        <p><strong>Διάρκεια:</strong> <?php echo esc_html($itinerary['duration'] ?? ''); ?></p>
                        <?php include plugin_dir_path(__FILE__) . 'single/itinerary-we-speak.php'; ?>

                                            <?php include plugin_dir_path(__FILE__) . 'single/itinerary-included.php'; ?>
                                            <?php include plugin_dir_path(__FILE__) . 'single/itinerary-do-not-forget.php'; ?>
                           <?php 
                $show_map = get_option('vortex_ua_show_map', 'yes');

                if ($show_map === 'yes') {
                    include plugin_dir_path(__FILE__) . 'single/map.php';
                }

                ?>  
            

                                            <div
                                                class="cancellationModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                                <div class="bg-white p-6 rounded-lg shadow-lg">
                                                    <h3 class="text-lg font-semibold mb-4">Πολιτική Ακύρωσης</h3>
                                                    <div class="cancellationContent" class="text-gray-700">
                                                        <h3 class="text-lg"><?php echo esc_html($itinerary['cancellation_policy']['title']); ?></h3>
                                                                <p class="text-sm"><?php echo wp_kses_post($itinerary['cancellation_policy']['description']); ?></p>
           
                                                    </div>
                                                    <button
                                                        class="closeModalBtn mt-4 px-4 py-2 bg-black text-white rounded">Κλείσιμο</button>
                                                </div>
                                            </div>
                                            <button data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                class="bookNowBtn mt-4 px-4 py-2 vortex-ua-button text-white rounded">Κράτηση τώρα</button>

                                                <div class="bookingModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                                    <div class="bg-white p-6 rounded-lg shadow-lg w-full h-full md:w-1/2 md:h-auto relative">
                                                
                                                    <h3 class="text-lg font-semibold mb-4">Είστε 5 βήματα μακριά!</h3>
                                                    <p>Συνολική Τιμή: <span
                                                            class="booking-price"><?php echo wp_kses_post($itinerary['min_price']); ?></span>
                                                        </p> <!-- #1 change price as per booking/person -->
                                                    <div class="bookingContent text-gray-700">
                                                        <div class="step1 booking-step">
                                                            <h4 class="text-lg font-semibold">Επιλέξτε ημερομηνία</h4>

                                                            <div class="date-picker-container hidden">
                                                                <input type="text" id="datetime-<?php echo esc_attr($itinerary['itinerary_id']); ?>" data-itinerary-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="flatpickr-input mt-2 p-2 border rounded w-full" />
                                                            </div>

                                                            <!-- calendar  -->
                                                            <button
                                                                class="nextToStep2 mt-4 px-4 py-2 vortex-ua-button text-white rounded">Επόμενο</button>
                                                        </div>
                                                        <div class="step2 booking-step hidden">
                                                            <h4 class="text-lg font-semibold">Επιλέξτε ώρα</h4> <!-- time slots -->

                                                            <div class="time-slot-container mb-4 hidden">
                                                                <select
                                                                    id="timeslot-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                    class="time-slot-select mt-2 p-2 border rounded w-full">
                                                                    <option value="">Επιλέξτε</option>
                                                                </select>
                                                            </div>

                                                            <button
                                                                class="backToStep1 mt-4 px-4 py-2 bg-gray-500 text-white rounded">Πίσω</button>
                                                            <button
                                                                class="nextToStep3 mt-4 px-4 py-2 vortex-ua-button text-white rounded">Επόμενο</button>
                                                        </div>
                                                        <div class="step3 booking-step hidden">
                                                            <h4 class="text-lg font-semibold">Άτομα & Εξτρά</h4>
                                                            <!-- visual price change per person at #1 && include in api call -->
                                                            <div class="flex items-center space-x-4">
                                                                <button
                                                                    class="decrease-btn bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">-</button>
                                                                <span class="person-count text-2xl font-semibold">1</span>
                                                                <button
                                                                    class="increase-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+</button>
                                                            </div>
                                                            <h3 class="mt-4 extras-header">Έξτρα Υπηρεσίες</h3>
                                                                <!-- visual price change at #1 && include in api call -->
                                                                <div class="facilities-container mb-4 hidden">
                                                                    <select multiple
                                                                        id="facilities-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                        class="facilities-select mt-2 p-2 border rounded w-full">
                                                                    </select>
                                                                </div>
                                                                <script>document.addEventListener('DOMContentLoaded', function() {
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

                                                            <button
                                                                class="backToStep2 mt-4 px-4 py-2 bg-gray-500 text-white rounded">Πίσω</button>
                                                            <button
                                                                class="nextToStep4 mt-4 px-4 py-2 vortex-ua-button text-white rounded">Επόμενο</button>
                                                        </div>
                                                        <div class="step4 booking-step hidden">
                                                            <h4 class="text-lg font-semibold">Εισάγετε τα στοιχεία σας</h4>
                                                            <!-- send to wordpress admin email -->
                                                            <input type="text" placeholder="Όνομα" name="customer_name"
                                                                id="customer_name-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                class="mt-2 p-2 border rounded w-full" />
                                                            <input type="text" placeholder="Επίθετο" name="customer_surname"
                                                                id="customer_surname-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                class="mt-2 p-2 border rounded w-full" />
                                                            <input type="email" placeholder="Email" name="customer_email"
                                                                id="customer_email-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                class="mt-2 p-2 border rounded w-full" />
                                                            <input type="number" placeholder="Τηλέφωνο" name="customer_phone"
                                                                id="customer_phone-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                class="mt-2 p-2 border rounded w-full" />
                                                            <button
                                                                class="backToStep3 mt-4 px-4 py-2 bg-gray-500 text-white rounded">Πίσω</button>
                                                            <button
                                                                class="nextToStep5 mt-4 px-4 py-2 vortex-ua-button text-white rounded">Επόμενο</button>
                                                        </div>
                                                        <div class="step5 booking-step hidden">

                                                            <div class="mt-4">
                                                                <h5 class="text-lg font-semibold">Σύνοψη Κράτησης</h5>
                                                                <p><strong>Ημερομηνία:</strong> <span id="summary-date"></span></p>
                                                                <p><strong>Ώρα:</strong> <span id="summary-time"></span></p>
                                                                <p><strong>Άτομα:</strong> <span id="summary-persons"></span></p>
                                                                <p><strong>Εγκαταστάσεις:</strong> <span id="summary-facilities"></span></p>
                                                                <p><strong>Όνομα:</strong> <span id="summary-name"></span></p>
                                                                <p><strong>Επίθετο:</strong> <span id="summary-surname"></span></p>
                                                                <p><strong>Email:</strong> <span id="summary-email"></span></p>
                                                                <p><strong>Τηλέφωνο:</strong> <span id="summary-phone"></span></p>
                                                            </div>
                                                                <h4 class="text-lg font-semibold">Επιβεβαίωση κράτησης</h4>
                                                            <p class="mt-2">Είστε σίγουροι ότι θέλετε να συνεχίσετε;</p>
                                                            <button class="backToStep4 mt-4 px-4 py-2 bg-gray-500 text-white rounded">Πίσω</button>
                                                            <button class="confirmBooking mt-4 px-4 py-2 vortex-ua-button text-white rounded">Πληρωμή Κράτησης</button><!-- send to payment page -->
                                                        </div>
                                                    </div>
                                                    <button
                                                        class="closeBookingModalBtn absolute top-4 right-4 bg-gray-300 text-black px-2 py-1 rounded hover:bg-gray-400">X</button>
                                                </div>
                                            </div>

                                            <button class="mt-4 px-4 py-2 text-black text-sm rounded cancellation-button">Πολιτική Ακύρωσης</button>
                                        </div>

                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <a id="vortex-ua-info-new-btn" class="mt-4 px-4 py-2">Απορίες & FAQ</a>

                            </div>
                        <?php endif; ?>

                        <?php include plugin_dir_path(__FILE__) . 'single/reviews.php'; ?>

                
                    </div>
                </article>
            </div>
        </div>
       <?php include plugin_dir_path(__FILE__) . 'single/main-functions-js.php'; ?>
        <?php
    endwhile;
endif;

get_footer();
?>