<?php
/**
 * Template Name: Activity Template
 * Template Post Type: activity
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

if (have_posts()):

    // function unlimited_adrenaline_enqueue_styles_scripts()
    // {
    //     wp_enqueue_style('flatpickr-styles', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
    //     wp_enqueue_script('flatpickr-script', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), null, true);
    //     wp_enqueue_script('jquery');
    //     wp_enqueue_script('jquery-ui-dialog');
    //     wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    // }
    // add_action('wp_enqueue_scripts', 'unlimited_adrenaline_enqueue_styles_scripts');

    while (have_posts()):
        the_post();

        // Retrieve ACF fields
        $title = get_the_title();
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
        ?>
        <script src="https://cdn.tailwindcss.com"></script>

        <div class="bg-white">
            <div class="pt-6">

                <?php include plugin_dir_path(__FILE__) . 'single/nav.php'; ?>
                <!-- Image gallery -->
                <?php include plugin_dir_path(__FILE__) . 'single/gallery.php'; ?>


                <!-- Product info -->
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
                                <h2 class="text-sm font-medium text-gray-900">from</h2>
                                <div class="text-3xl tracking-tight text-gray-900">
                                    <?php echo wp_kses_post($min_price); ?> EUR
                                </div>
                            </div>
                        <?php endif; ?>
                        <h2 class="sr-only">Activity information</h2>
                        <p class="text-2xl tracking-tight text-gray-900"><?php echo esc_html($rating); ?> Stars</p>

                        <!-- Details -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Details</h3>
                            <div class="mt-4 space-y-2 text-sm text-gray-700">
                                <p>
                                    <stron g>Active Months:</strong> <?php echo esc_html($active_months); ?>
                                </p>
                                <p><strong>Categories:</strong> <?php echo esc_html($category_ids); ?></p>
                            </div>
                        </div>

                        <?php if (!empty($additional_info)): ?>
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
                                <div class="prose max-w-none mt-4 text-gray-700">
                                    <?php echo wp_kses_post($additional_info); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <br></br>
                        <a href="#booktypesv" class="mt-4 px-4 py-2 bg-black text-white rounded">Book Now</a>

                    </div>

                    <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
                        <!-- Description -->
                        <div>
                            <h3 class="sr-only">Description</h3>
                            <div class="space-y-6 text-base text-gray-900">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        </div>

                        <?php if (!empty($itineraries)): ?>
                            <div class="mt-10" id="booktypesv">
                                <h3 class="text-sm font-medium text-gray-900">Itineraries</h3>
                                <div class="mt-4 space-y-4">
                                    <?php foreach ($itineraries as $itinerary): ?>
                                        <div class="bg-gray-100 p-4 rounded-lg shadow-md itinerary-container"
                                            data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>">
                                            <h4 class="text-lg font-semibold"><?php echo esc_html($itinerary['title'] ?? ''); ?></h4>
                                            <div class="prose max-w-none mb-2 mt-4 text-gray-700">
                                                <?php echo wp_kses_post($itinerary['description'] ?? ''); ?>
                                            </div>
                                            <p><strong>Level:</strong> <?php echo esc_html($itinerary['difficulty'] ?? ''); ?></p>
                                            <p><strong>Price:</strong> <?php echo esc_html($itinerary['min_price'] ?? ''); ?> EUR
                                                <?php echo esc_html($itinerary['booking_type'] ?? ''); ?>
                                            </p>
                                            <p><strong>Min Age:</strong> <?php echo esc_html($itinerary['min_age'] ?? ''); ?></p>
                                            <p><strong>Duration:</strong> <?php echo esc_html($itinerary['duration'] ?? ''); ?></p>
                                            <div class="mt-4">
                                                <h5 class="text-lg font-semibold">We Speak</h5>
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
                                                    <h5 class="text-lg font-semibold">Included</h5>
                                                    <ul class="list-disc list-inside">
                                                        <?php foreach ($itinerary['details']['included'] as $included): ?>
                                                            <li><?php echo esc_html($included['title']); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($itinerary['details']['do_not_forget'])): ?>
                                                <div class="mt-4">
                                                    <h5 class="text-lg font-semibold">Do Not Forget</h5>
                                                    <ul class="list-disc list-inside">
                                                        <?php foreach ($itinerary['details']['do_not_forget'] as $do_not_forget): ?>
                                                            <li><?php echo esc_html($do_not_forget['title']); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                            <div id="cancellationModal"
                                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                                <div class="bg-white p-6 rounded-lg shadow-lg">
                                                    <h3 class="text-lg font-semibold mb-4">Cancellation Policy</h3>
                                                    <div id="cancellationContent" class="text-gray-700">

                                                        <h3><?php echo esc_html($itinerary['policy_title']); ?></h3>
                                                        <p><?php echo esc_html($itinerary['policy_title']); ?></p>

                                                    </div>
                                                    <button id="closeModalBtn"
                                                        class="mt-4 px-4 py-2 bg-black text-white rounded">Close</button>
                                                </div>
                                            </div>
                                            <button id="bookNowBtn" data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                class="mt-4 px-4 py-2 bg-black text-white rounded">Book Now</button>

                                            <div id="bookingModal"
                                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                                <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 relative">
                                                    <h3 class="text-lg font-semibold mb-4">Book Now</h3>
                                                    <p>Price: <span id="booking-price"><?php echo wp_kses_post($min_price); ?></span>
                                                        </p> <!-- #1 change price as per booking/person -->
                                                    <div id="bookingContent" class="text-gray-700">
                                                        <div id="step1" class="booking-step">
                                                            <h4 class="text-lg font-semibold">Choose Date</h4>

                                                            <div class="date-picker-container hidden">
                                                                <input type="text"
                                                                    id="datetime-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                    class="flatpickr-input mt-2 p-2 border rounded w-full" />
                                                            </div>

                                                            <!-- calendar  -->
                                                            <button id="nextToStep2"
                                                                class="mt-4 px-4 py-2 bg-black text-white rounded">Next</button>
                                                        </div>
                                                        <div id="step2" class="booking-step hidden">
                                                            <h4 class="text-lg font-semibold">Choose Time</h4> <!-- time slots -->

                                                            <div class="time-slot-container mb-4 hidden">
                                                                <select
                                                                    id="timeslot-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                    class="time-slot-select mt-2 p-2 border rounded w-full">
                                                                    <option value="">Select a time slot</option>
                                                                </select>
                                                            </div>

                                                            <button id="backToStep1"
                                                                class="mt-4 px-4 py-2 bg-gray-500 text-white rounded">Back</button>
                                                            <button id="nextToStep3"
                                                                class="mt-4 px-4 py-2 bg-black text-white rounded">Next</button>
                                                        </div>
                                                        <div id="step3" class="booking-step hidden">
                                                            <h4 class="text-lg font-semibold">Persons & Extras</h4>
                                                            <!-- visual price change per person at #1 && include in api call -->
                                                            <div class="flex items-center space-x-4">
                                                                <button id="decrease-btn"
                                                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">-</button>
                                                                <span id="person-count" class="text-2xl font-semibold">1</span>
                                                                <button id="increase-btn"
                                                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+</button>
                                                            </div>
                                                            <h3 class="mt-4">Extras</h3>
                                                            <!-- visual price change at #1 && include in api call -->
                                                            <div class="facilities-container mb-4 hidden">
                                                                <select multiple
                                                                    id="facilities-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>"
                                                                    class="facilities-select mt-2 p-2 border rounded w-full">
                                                                </select>
                                                            </div>

                                                            <button id="backToStep2"
                                                                class="mt-4 px-4 py-2 bg-gray-500 text-white rounded">Back</button>
                                                            <button id="nextToStep4"
                                                                class="mt-4 px-4 py-2 bg-black text-white rounded">Next</button>
                                                        </div>
                                                        <div id="step4" class="booking-step hidden">
                                                            <h4 class="text-lg font-semibold">Enter Details</h4>
                                                            <!-- send to wordpress admin email -->
                                                            <input type="text" placeholder="Name" name="customer_name"
                                                                class="mt-2 p-2 border rounded w-full" />
                                                            <input type="text" placeholder="Surname" name="customer_surname"
                                                                class="mt-2 p-2 border rounded w-full" />
                                                            <input type="email" placeholder="Email" name="customer_email"
                                                                class="mt-2 p-2 border rounded w-full" />
                                                            <input type="text" placeholder="Phone Number" name="customer_phone"
                                                                class="mt-2 p-2 border rounded w-full" />
                                                            <button id="backToStep3"
                                                                class="mt-4 px-4 py-2 bg-gray-500 text-white rounded">Back</button>
                                                            <button id="nextToStep5"
                                                                class="mt-4 px-4 py-2 bg-black text-white rounded">Next</button>
                                                        </div>
                                                        <div id="step5" class="booking-step hidden">
                                                            <h4 class="text-lg font-semibold">Confirm Booking</h4>
                                                            <p class="mt-2">Are you sure you want to proceed?</p>
                                                            <button id="backToStep4"
                                                                class="mt-4 px-4 py-2 bg-gray-500 text-white rounded">Back</button>
                                                            <button id="confirmBooking"
                                                                class="mt-4 px-4 py-2 bg-black text-white rounded">Pay</button><!-- send to payment page -->
                                                        </div>
                                                    </div>
                                                    <button id="closeBookingModalBtn"
                                                        class="absolute top-4 right-4 bg-gray-300 text-black px-2 py-1 rounded hover:bg-gray-400">X</button>
                                                </div>
                                            </div>

                                            <button class="mt-4 px-4 py-2 text-black text-sm rounded cancellation-button">Cancellation
                                                Policy</button>

                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>


                        <?php if (!empty($reviews)): ?>
                            <div class="mt-10">
                                <h2 class="text-sm font-medium text-gray-900">Reviews</h2>
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
                const cancellationModal = document.getElementById('cancellationModal');
                const cancellationContent = document.getElementById('cancellationContent');
                const closeModalBtn = document.getElementById('closeModalBtn');

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
                        cancellationContent.innerHTML = itineraryCancellation;
                        cancellationModal.classList.remove('hidden');
                    });
                });

                closeModalBtn.addEventListener('click', function () {
                    cancellationModal.classList.add('hidden');
                });

                window.addEventListener('click', function (event) {
                    if (event.target == cancellationModal) {
                        cancellationModal.classList.add('hidden');
                    }
                });
            });
            document.addEventListener('DOMContentLoaded', function () {
                const steps = ['step1', 'step2', 'step3', 'step4', 'step5'];
                let currentStep = 0;
                let personCount = 1;

                const showStep = (step) => {
                    steps.forEach((s, i) => {
                        document.getElementById(s).classList.toggle('hidden', i !== step);
                    });
                };

                const updatePricing = (itineraryId) => {
                    const itineraryContainer = jQuery('.itinerary-container[data-id="' + itineraryId + '"]');

                    const selectedFacilities = itineraryContainer.find('.facilities-select').val();
                    const selectedDate = itineraryContainer.find('.date-picker-container').find('.flatpickr-input').val();
                    const selectedTimeSlot = itineraryContainer.find('.time-slot-select').val();
                    const personCount = parseInt(itineraryContainer.find('#person-count').text());

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
                                document.getElementById('booking-price').innerText = response.data.totalPrice + ' EUR';
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
                    const personCount = parseInt(itineraryContainer.find('#person-count').text());

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

                document.getElementById('bookNowBtn').addEventListener('click', (e) => {
                    let activityId = jQuery(e.target).closest('article').data('id');
                    let itineraryId = jQuery(e.target).data('id');

                    const datePickerContainer = jQuery(e.target).parent().find('.date-picker-container')
                    const timeSlotContainer = jQuery(e.target).parent().find('.time-slot-container');
                    const facilitiesContainer = jQuery(e.target).parent().find('.facilities-container');

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
                            if (response.success) {
                                let availableDates = [];
                                let timeSlots = {};
                                let facilities = response.data.facilities;

                                Object.entries(response.data.dates).forEach(function ([date, dateInfo]) {
                                    if (dateInfo && typeof dateInfo === "object" && dateInfo.available) {
                                        availableDates.push(date);
                                        timeSlots[date] = dateInfo.availabilityTimes;
                                    }
                                });

                                jQuery('#datetime-' + itineraryId).flatpickr({
                                    enableTime: false,
                                    dateFormat: "Y-m-d",
                                    enable: availableDates,
                                    onChange: function (selectedDates, dateStr, instance) {
                                        if (timeSlots[dateStr]) {
                                            timeSlotContainer.find('.time-slot-select').html('<option value="">Select a time slot</option>');
                                            timeSlots[dateStr].forEach(function (slot) {
                                                timeSlotContainer.find('.time-slot-select').append('<option value="' + slot.timeId + '">' + slot.startTime + '</option>');
                                            });
                                            timeSlotContainer.removeClass('hidden');
                                        } else {
                                            timeSlotContainer.addClass('hidden');
                                        }
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
                                console.log('Error: ' + response.data);
                            }
                        },
                        error: function (error) {
                            console.log('AJAX Error: ', error);
                        }
                    });

                    document.getElementById('bookingModal').classList.remove('hidden');
                    showStep(0);
                });

                document.getElementById('closeBookingModalBtn').addEventListener('click', () => {
                    document.getElementById('bookingModal').classList.add('hidden');
                });

                window.addEventListener('click', (event) => {
                    if (event.target === document.getElementById('bookingModal')) {
                        document.getElementById('bookingModal').classList.add('hidden');
                    }
                });

                document.getElementById('nextToStep2').addEventListener('click', () => {
                    currentStep = 1;
                    showStep(currentStep);
                });

                document.getElementById('nextToStep3').addEventListener('click', () => {
                    currentStep = 2;
                    showStep(currentStep);
                });

                document.getElementById('nextToStep4').addEventListener('click', () => {
                    currentStep = 3;
                    showStep(currentStep);
                });

                document.getElementById('nextToStep5').addEventListener('click', () => {
                    currentStep = 4;
                    showStep(currentStep);
                });

                document.getElementById('backToStep1').addEventListener('click', () => {
                    currentStep = 0;
                    showStep(currentStep);
                });

                document.getElementById('backToStep2').addEventListener('click', () => {
                    currentStep = 1;
                    showStep(currentStep);
                });

                document.getElementById('backToStep3').addEventListener('click', () => {
                    currentStep = 2;
                    showStep(currentStep);
                });

                document.getElementById('backToStep4').addEventListener('click', () => {
                    currentStep = 3;
                    showStep(currentStep);
                });

                document.getElementById('confirmBooking').addEventListener('click', (e) => {
                    const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
                    const itineraryId = itineraryContainer.data('id');

                    const customerDetails = {
                        name: itineraryContainer.find('input[name="customer_name"]').val(),
                        surname: itineraryContainer.find('input[name="customer_surname"]').val(),
                        email: itineraryContainer.find('input[name="customer_email"]').val(),
                        phone: itineraryContainer.find('input[name="customer_phone"]').val()
                    };

                    document.getElementById('bookingModal').classList.add('hidden');

                    checkout(itineraryId, customerDetails);
                });

                document.getElementById('increase-btn').addEventListener('click', (e) => {
                    personCount++;
                    document.getElementById('person-count').textContent = personCount;

                    const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
                    const itineraryId = itineraryContainer.data('id');

                    updatePricing(itineraryId);
                });

                document.getElementById('decrease-btn').addEventListener('click', () => {
                    if (personCount > 1) {
                        personCount--;
                        document.getElementById('person-count').textContent = personCount;

                        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
                        const itineraryId = itineraryContainer.data('id');

                        updatePricing(itineraryId);
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                jQuery('.flatpickr-input').flatpickr({
                    enableTime: false,
                    dateFormat: "Y-m-d",
                });
            });
        </script>
        <?php
    endwhile;
endif;

get_footer();
?>