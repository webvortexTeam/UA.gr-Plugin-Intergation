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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

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
                        <div id="vortex-ua-info-new-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                            <div class="bg-white p-6 rounded-lg shadow-lg">
                                <h3 class="text-lg font-semibold mb-4">Απορίες & FAQ</h3>
                                <div class="new-popup-content text-gray-700">
                                    <p>test</p>
                                </div>
                                <a id="close-vortex-ua-info-new-btn" class="mt-4 px-4 py-2 rounded">Κλείσιμο</a>
                            </div>
                        </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const newPopupBtn = document.getElementById('vortex-ua-info-new-btn');
    const newPopupModal = document.getElementById('vortex-ua-info-new-modal');
    const closeNewPopupBtn = document.getElementById('close-vortex-ua-info-new-btn');

    newPopupBtn.addEventListener('click', function () {
        newPopupModal.classList.remove('hidden');
    });

    closeNewPopupBtn.addEventListener('click', function () {
        newPopupModal.classList.add('hidden');
    });

    // Optional: Close the modal when clicking outside of it
    newPopupModal.addEventListener('click', function (event) {
        if (event.target === newPopupModal) {
            newPopupModal.classList.add('hidden');
        }
    });
});
</script>

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
                            <a id="vortex-ua-info-new-btn" class="mt-4 px-4 py-2">Απορίες & FAQ</a>

                    </div>

                    <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
                        <!-- Description -->
                        <div>
                            <h3 class="sr-only">Περιγραφή</h3>
                            <div class="space-y-6 text-base text-gray-900">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        </div>
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
                                            <?php if (!empty($itinerary['details']['do_not_forget'])): ?>
                                                <div class="mt-4">
                                                    <h5 class="text-lg font-semibold">Μην ξεχάσετε</h5>
                                                    <ul class="list-disc list-inside">
                                                        <?php foreach ($itinerary['details']['do_not_forget'] as $do_not_forget): ?>
                                                            <li><?php echo esc_html($do_not_forget['title']); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
            <div id="map-<?php echo $index; ?>" style="height: 400px; width: 100%; z-index: 0 !important;"></div>
<style>
#map {
    z-index: -1;
    position: relative;
}

.flashing-red-icon .flashing-red-point {
    width: 20px;
    height: 20px;
    background-color: red;
    border-radius: 50%;
    animation: flash 1s infinite;
    
}

@keyframes flash {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const itineraries = <?php echo json_encode($itineraries); ?>;
    const geocodeApiUrl = 'https://nominatim.openstreetmap.org/reverse?format=json&lat={lat}&lon={lng}';

    itineraries.forEach((itinerary, index) => {
        const lat = parseFloat(itinerary.latitude);
        const lng = parseFloat(itinerary.longitude);

        if (!isNaN(lat) && !isNaN(lng)) {
            // Initialize the map for each itinerary
            const map = L.map(`map-${index}`).setView([lat, lng], 16);

            // Add CartoDB Positron tile layer
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                subdomains: 'abcd',
                maxZoom: 59
            }).addTo(map);

            // Create a custom flashing red icon
            const flashingRedIcon = L.divIcon({
                className: 'flashing-red-icon',
                html: '<div class="flashing-red-point"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            // Add the marker with the custom icon to the map
            const marker = L.marker([lat, lng], { icon: flashingRedIcon }).addTo(map);

            // Zoom effect on load
            map.on('load', function () {
                setTimeout(() => {
                    map.setZoom(28);
                }, 500); // delay for zoom effect
            });

            // Fetch the address and show it in a popup
            fetch(geocodeApiUrl.replace('{lat}', lat).replace('{lng}', lng))
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const address = data.address;
                        const displayName = `${address.road || ''}, ${address.city || address.town || address.village || ''}, ${address.country || ''}, ${address.postcode || ''}`.replace(/^,|,$/g, '');
                        marker.bindPopup(displayName).openPopup();
                    } else {
                        marker.bindPopup('Η διεύθυνση δεν βρέθηκε.').openPopup();
                    }
                })
                .catch(error => {
                    marker.bindPopup('Error fetching address').openPopup();
                    console.error('Error fetching address:', error);
                });
        }
    });
});
</script>

                                            <div
                                                class="cancellationModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                                <div class="bg-white p-6 rounded-lg shadow-lg">
                                                    <h3 class="text-lg font-semibold mb-4">Πολιτική Ακύρωσης</h3>
                                                    <div class="cancellationContent" class="text-gray-700">
                                                        <h3 class="text-lg"><?php echo esc_html($itinerary['cancellation_policy']['title']); ?></h3>
                                                                <p class="text-sm"><?php echo wp_kses_post($itinerary['cancellation_policy']['description']); ?></p>
           
                                                    </div>
                                                    <button
                                                        class="closeModalBtn mt-4 px-4 py-2 bg-black text-white rounded">Close</button>
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
                            </div>
                        <?php endif; ?>


                        <?php if (!empty($reviews)): ?>
                            <div class="mt-10">
                                <h2 class="text-sm font-medium text-gray-900">Κριτικές</h2>
                                <div class="mt-4 prose max-w-none text-gray-700">
                                    <?php echo wp_kses_post($reviews); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        </div>
        <script>
         document.addEventListener('DOMContentLoaded', function () {
                const showMoreBtn = document.getElementById('show-more-btn');
                const photoItems = document.querySelectorAll('.photo-item');
                const accordionTitles = document.querySelectorAll('.accordion-title');
                const cancellationButtons = document.querySelectorAll('.cancellation-button');
     const itineraryHeaders = document.querySelectorAll('.itinerary-header');
    const itinerariesCount = document.querySelectorAll('.itinerary-container').length;

    itineraryHeaders.forEach(header => {
        header.addEventListener('click', function () {
            const index = this.getAttribute('data-index');
            const content = this.nextElementSibling;
            const allContents = document.querySelectorAll('.itinerary-content');

            if (itinerariesCount > 1) {
                allContents.forEach((item, i) => {
                    if (i != index) {
                        item.classList.add('hidden');
                    }
                });
                content.classList.toggle('hidden');
            }
        });
    });

    // Automatically open the first itinerary if there's only one
    if (itinerariesCount === 1) {
        document.querySelector('.itinerary-content').classList.remove('hidden');
    }
                if (showMoreBtn) {
                    showMoreBtn.addEventListener('click', function () {
                        photoItems.forEach((item, index) => {
                            if (index >= 6) {
                                item.classList.toggle('hidden');
                            }
                        });

                        showMoreBtn.innerText = showMoreBtn.innerText === 'Show More' ? 'Show Less' : 'Show More';
                    });
                }

                accordionTitles.forEach(title => {
                    title.addEventListener('click', function () {
                        const content = this.nextElementSibling;
                        content.classList.toggle('hidden');
                        this.querySelector('.accordion-icon').innerText = content.classList.contains('hidden') ? '+' : '-';
                    });
                });

                cancellationButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const itineraryCancellation = this.getAttribute('data-cancellation');

                        const iContainer = jQuery(this).closest('.itinerary-container');
                        const cancellationModal = iContainer.find('.cancellationModal');

                        const cancellationContent = cancellationModal.find('.cancellationContent');
                        cancellationContent.innerHTML = itineraryCancellation;

                        cancellationModal.removeClass('hidden');
                    });
                });

                jQuery('.closeModalBtn').on('click', function () {
                    const iContainer = jQuery(this).closest('.itinerary-container');

                    const cancellationModal = iContainer.find('.cancellationModal');

                    cancellationModal.addClass('hidden');
                });
            });
document.addEventListener('DOMContentLoaded', function () {

    const steps = ['step1', 'step2', 'step3', 'step4', 'step5'];
    let currentStep = 0;
    let personCount = 1;

    const showStep = (step, itineraryId) => {
        const itineraryContainer = jQuery('.itinerary-container[data-id="' + itineraryId + '"]');
        steps.forEach((s, i) => {
            if (i === step) {
                itineraryContainer.find('.' + s).removeClass('hidden');
            } else {
                itineraryContainer.find('.' + s).addClass('hidden');
            }
        });
    };

    const updatePricing = (itineraryId) => {
        const itineraryContainer = jQuery('.itinerary-container[data-id="' + itineraryId + '"]');
        const selectedFacilities = itineraryContainer.find('.facilities-select').val();
        const selectedDate = itineraryContainer.find('.date-picker-container').find('.flatpickr-input').val();
        const selectedTimeSlot = itineraryContainer.find('.time-slot-select').val();
        const personCount = parseInt(itineraryContainer.find('.person-count').text());
        const step4button = itineraryContainer.find('.nextToStep4');

        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'get_latest_pricing',
                itinerary_id: itineraryId,
                person_count: personCount,
                facilities: selectedFacilities,
                date: selectedDate,
                time_slot: selectedTimeSlot,
            },
            success: function (response) {
                if (response.success) {
                    const error = response.data.error ?? null;
                    const price = response.data.totalPrice ? response.data.totalPrice + ' €' : null;

                    if(error || !price) {
                        step4button.attr('disabled', true);
                    } else {
                        step4button.attr('disabled', false);
                    }

                    itineraryContainer.find('.booking-price').text(price ?? error ?? 'Error');
                } else {
                    console.log('Error: ' + response.data);
                }
            },
            error: function (error) {
                console.log('AJAX Error: ', error);
            }
        });
    };

    const checkout = (itineraryId, customerDetails) => {
        const itineraryContainer = jQuery('.itinerary-container[data-id="' + itineraryId + '"]');
        const selectedFacilities = itineraryContainer.find('.facilities-select').val();
        const selectedDate = itineraryContainer.find('.date-picker-container').find('.flatpickr-input').val();
        const selectedTimeSlot = itineraryContainer.find('.time-slot-select').val();
        const personCount = parseInt(itineraryContainer.find('.person-count').text());

        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'checkout',
                itinerary_id: itineraryId,
                person_count: personCount,
                facilities: selectedFacilities,
                date: selectedDate,
                time_slot: selectedTimeSlot,
                customer_details: customerDetails
            },
            success: function (response) {
                if (response.success) {
                   // console.log('Success: ', response);
                    window.location.href = response.data.paymentUrl;
                } else {
                    console.log('Error: ' + response.data);
                }
            },
            error: function (error) {
                console.log('AJAX Error: ', error);
            }
        });
    };

const fetchAvailability = (itineraryId, startDate, endDate, calendarInstance) => {
    // Ensure start date is today or later
    const today = new Date();
    if (startDate < today) {
        startDate = today;
    }

    const formattedStartDate = startDate.toISOString().split('T')[0];
    const formattedEndDate = endDate.toISOString().split('T')[0];

    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'get_availability',
            itinerary_id: itineraryId,
            start_date: formattedStartDate,
            end_date: formattedEndDate,
        },
        success: function (response) {
            if (response.success && response.data && response.data.dates) {
                let availableDates = [];
                let timeSlots = {};

                Object.entries(response.data.dates).forEach(function ([date, dateInfo]) {
                    if (dateInfo && typeof dateInfo === "object" && dateInfo.available) {
                        availableDates.push(date);
                        timeSlots[date] = dateInfo.availabilityTimes;
                    }
                });

                calendarInstance.set('enable', availableDates);

                calendarInstance.config.onChange.push(function(selectedDates, dateStr, instance) {
                    const timeSlotSelect = jQuery(instance.element).closest('.itinerary-container').find('.time-slot-select');
                    if (timeSlots[dateStr]) {
                        timeSlotSelect.html('<option value="">Επιλέξτε ώρα</option>');
                        timeSlots[dateStr].forEach(function (slot) {
                            timeSlotSelect.append('<option value="' + slot.timeId + '">' + slot.startTime + '</option>');
                        });
                        timeSlotSelect.closest('.time-slot-container').removeClass('hidden');
                    } else {
                        timeSlotSelect.closest('.time-slot-container').addClass('hidden');
                    }
                });
            } else {
                console.log('Error: ', response.data);
            }
        },
        error: function (error) {
            console.log('AJAX Error: ', error);
        }
    });
};





jQuery('.bookNowBtn').on('click', (e) => {
    let activityId = jQuery(e.target).closest('article').data('id');
    let itineraryId = jQuery(e.target).data('id');

    const datePickerContainer = jQuery(e.target).parent().find('.date-picker-container');
    const timeSlotContainer = jQuery(e.target).parent().find('.time-slot-container');
    const facilitiesContainer = jQuery(e.target).parent().find('.facilities-container');

    const bookingModal = jQuery(e.target).parent().find('.bookingModal');

    datePickerContainer.addClass('hidden');
    timeSlotContainer.addClass('hidden');
    facilitiesContainer.addClass('hidden');

    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'get_availability',
            itinerary_id: itineraryId,
            activity_id: activityId,
        },
        success: function (response) {
            if (response.success && response.data && response.data.dates) {
                let availableDates = [];
                let timeSlots = {};
                let facilities = response.data.facilities;

                Object.entries(response.data.dates).forEach(function ([date, dateInfo]) {
                    if (dateInfo && typeof dateInfo === "object" && dateInfo.available) {
                        availableDates.push(date);
                        timeSlots[date] = dateInfo.availabilityTimes;
                    }
                });

                const calendarInstance = jQuery('#datetime-' + itineraryId).flatpickr({
    enableTime: false,
    dateFormat: "Y-m-d",
    defaultDate: new Date(),  // Set default date to today
    enable: availableDates,
    onChange: function(selectedDates, dateStr, instance) {
        const timeSlotSelect = jQuery(instance.element).closest('.itinerary-container').find('.time-slot-select');
        if (timeSlots[dateStr]) {
            timeSlotSelect.html('<option value="">Επιλέξτε ώρα</option>');
            timeSlots[dateStr].forEach(function (slot) {
                timeSlotSelect.append('<option value="' + slot.timeId + '">' + slot.startTime + '</option>');
            });
            timeSlotSelect.closest('.time-slot-container').removeClass('hidden');
        } else {
            timeSlotSelect.closest('.time-slot-container').addClass('hidden');
        }
    },
    onMonthChange: function(selectedDates, dateStr, instance) {
        const itineraryId = instance.element.closest('.itinerary-container').dataset.id;
        let startDate = new Date(instance.currentYear, instance.currentMonth, 1);  // Always set start date to the 1st of the month
        const endDate = new Date(instance.currentYear, instance.currentMonth + 1, 0);

        // Ensure start date is today or later
        const today = new Date();
        if (startDate < today) {
            startDate = today;
        }

        fetchAvailability(itineraryId, startDate, endDate, instance);
    }
});



                datePickerContainer.removeClass('hidden');

                facilities.forEach(function (facility) {
                    facilitiesContainer.find('.facilities-select').append('<option value="' + facility.id + '">' + facility.title + '</option>');
                });

                facilitiesContainer.removeClass('hidden');

                facilitiesContainer.find('.facilities-select').on('change', function () {
                    updatePricing(itineraryId);
                });
            } else {
                console.log('Error: ', response.data);
            }
        },
        error: function (error) {
            console.log('AJAX Error: ', error);
        }
    });

    bookingModal.removeClass('hidden');
    showStep(0, itineraryId);
});


    jQuery('.closeBookingModalBtn').on('click', (e) => {
        jQuery(e.target).closest('.bookingModal').addClass('hidden');
    });

    jQuery('.nextToStep2').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        const selectedDate = itineraryContainer.find('.flatpickr-input').val();

        if (!selectedDate) {
            alert('Παρακαλώ επιλέξτε μια ημερομηνία ή επιστρέψτε πίσω.');
            return;
        }

        currentStep = 1;
        showStep(currentStep, itineraryId);
    });

    jQuery('.nextToStep3').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        const selectedTimeSlot = itineraryContainer.find('.time-slot-select').val();

        if (!selectedTimeSlot) {
            alert('Παρακαλώ επιλέξτε ώρα ή επιστρέψτε πίσω.');
            return;
        }

        currentStep = 2;
        showStep(currentStep, itineraryId);
    });

    jQuery('.nextToStep4').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        currentStep = 3;
        showStep(currentStep, itineraryId);
    });

    jQuery('.nextToStep5').on('click', (e) => {
       const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        // Fetch and display summary details
        const selectedDate = itineraryContainer.find('.flatpickr-input').val();
        const selectedTimeSlot = itineraryContainer.find('.time-slot-select option:selected').text();
        const personCount = itineraryContainer.find('.person-count').text();
        const selectedFacilities = itineraryContainer.find('.facilities-select option:selected').map(function() {
            return jQuery(this).text();
        }).get().join(', ');

        const customerName = itineraryContainer.find('input[name="customer_name"]').val();
        const customerSurname = itineraryContainer.find('input[name="customer_surname"]').val();
        const customerEmail = itineraryContainer.find('input[name="customer_email"]').val();
        const customerPhone = itineraryContainer.find('input[name="customer_phone"]').val();

        jQuery('#summary-date').text(selectedDate);
        jQuery('#summary-time').text(selectedTimeSlot);
        jQuery('#summary-persons').text(personCount);
        jQuery('#summary-facilities').text(selectedFacilities || 'Καμία');
        jQuery('#summary-name').text(customerName);
        jQuery('#summary-surname').text(customerSurname);
        jQuery('#summary-email').text(customerEmail);
        jQuery('#summary-phone').text(customerPhone);

        if (!customerName || !customerSurname || !customerEmail || !customerPhone) {
            alert('Παρακαλώ συμπληρώστε όλα τα στοιχεία σας');
            return;
        }

        currentStep = 4;
        showStep(currentStep, itineraryId);
    });

    jQuery('.backToStep1').on('click', (e) => {
        const itineraryId = jQuery(e.target).closest('.itinerary-container').data('id');

        currentStep = 0;
        showStep(currentStep, itineraryId);
    });

    jQuery('.backToStep2').on('click', (e) => {
        const itineraryId = jQuery(e.target).closest('.itinerary-container').data('id');

        currentStep = 1;
        showStep(currentStep, itineraryId);
    });

    jQuery('.backToStep3').on('click', (e) => {
        const itineraryId = jQuery(e.target).closest('.itinerary-container').data('id');

        currentStep = 2;
        showStep(currentStep, itineraryId);
    });

    jQuery('.backToStep4').on('click', (e) => {
        const itineraryId = jQuery(e.target).closest('.itinerary-container').data('id');

        currentStep = 3;
        showStep(currentStep, itineraryId);
    });

    jQuery('.confirmBooking').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        const customerDetails = {
            name: itineraryContainer.find('input[name="customer_name"]').val(),
            surname: itineraryContainer.find('input[name="customer_surname"]').val(),
            email: itineraryContainer.find('input[name="customer_email"]').val(),
            phone: itineraryContainer.find('input[name="customer_phone"]').val()
        };

        itineraryContainer.find('.bookingModal').addClass('hidden');

        checkout(itineraryId, customerDetails);
    });

    jQuery('.increase-btn').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        personCount++;
        itineraryContainer.find('.person-count').text(personCount);

        updatePricing(itineraryId);
    });

    jQuery('.decrease-btn').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        if (personCount > 1) {
            personCount--;
            itineraryContainer.find('.person-count').text(personCount);

            updatePricing(itineraryId);
        }
    });
});

        </script>
        <?php
    endwhile;
endif;

get_footer();
?>