<?php
if (!defined('ABSPATH')) exit;

class Webvortex_Itineraries_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'webvortex_itineraries_widget';
    }

    public function get_title() {
        return __('Itineraries Display', 'webvortex-elementor-widgets');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['unlimited_andrenaline'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'webvortex-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_map',
            [
                'label' => __('Show Map', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'webvortex-elementor-widgets'),
                'label_off' => __('Hide', 'webvortex-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'map_location',
            [
                'label' => __('Map Location', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'top' => __('Top', 'webvortex-elementor-widgets'),
                    'bottom' => __('Bottom', 'webvortex-elementor-widgets'),
                ],
                'default' => 'bottom',
                'condition' => [
                    'show_map' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .webvortex-itineraries' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => __('Background Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .webvortex-itineraries' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'font_size',
            [
                'label' => __('Font Size', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-itineraries' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => __('Padding', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-itineraries' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => __('Margin', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-itineraries' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Button Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .webvortex-itineraries .bookNowBtn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
$this->add_control(
    'decrease_button_color',
    [
        'label' => __('Decrease Button Color', 'webvortex-elementor-widgets'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .decrease-btn' => 'background-color: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'decrease_button_hover_color',
    [
        'label' => __('Decrease Button Hover Color', 'webvortex-elementor-widgets'),
        'type' => \Elementor\Controls_Manager::COLOR,
    ]
);

$this->add_control(
    'increase_button_color',
    [
        'label' => __('Increase Button Color', 'webvortex-elementor-widgets'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .increase-btn' => 'background-color: {{VALUE}};',
        ],
    ]
);

$this->add_control(
    'increase_button_hover_color',
    [
        'label' => __('Increase Button Hover Color', 'webvortex-elementor-widgets'),
        'type' => \Elementor\Controls_Manager::COLOR,
    ]
);


        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .webvortex-itineraries .bookNowBtn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Custom Icon', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'section_type',
            [
                'label' => __('Section Type', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'description' => __('Description', 'webvortex-elementor-widgets'),
                    'do_not_forget' => __('Do Not Forget', 'webvortex-elementor-widgets'),
                    'included' => __('Included', 'webvortex-elementor-widgets'),
                    'we_speak' => __('We Speak', 'webvortex-elementor-widgets'),
                ],
                'default' => 'description',
            ]
        );

        $repeater->add_control(
            'section_title',
            [
                'label' => __('Section Title', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Section Title', 'webvortex-elementor-widgets'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'sections',
            [
                'label' => __('Sections', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'section_type' => 'description',
                        'section_title' => __('Description', 'webvortex-elementor-widgets'),
                    ],
                ],
                'title_field' => '{{{ section_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', [], null, true);
        wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

        $settings = $this->get_settings_for_display();

        $post_id = get_the_ID();
        $title = get_field('title', $post_id);
        $activity_id = get_field('activity_id', $post_id);
        $rating = get_field('rating', $post_id);
        $active_months = get_field('active_months', $post_id);
        $duration = get_field('duration', $post_id);
        $description = get_field('description', $post_id);
        $itineraries = get_field('itineraries', $post_id);
        $locale_activities = get_option('activity_api_locale', 'gr');
        $min_price = null;

        if (have_rows('field_webvortex_itineraries', $post_id)) {
            while (have_rows('field_webvortex_itineraries', $post_id)) {
                the_row();
                $price = get_sub_field('field_webvortex_itinerary_min_price');
                if ($price !== '' && ($min_price === null || $price < $min_price)) {
                    $min_price = $price;
                }
            }
        }

        echo '<div class="webvortex-itineraries" style="color: ' . esc_attr($settings['text_color']) . '; background-color: ' . esc_attr($settings['bg_color']) . '; font-size: ' . esc_attr($settings['font_size']['size']) . esc_attr($settings['font_size']['unit']) . '; padding: ' . esc_attr($settings['padding']['top']) . esc_attr($settings['padding']['unit']) . ' ' . esc_attr($settings['padding']['right']) . esc_attr($settings['padding']['unit']) . ' ' . esc_attr($settings['padding']['bottom']) . esc_attr($settings['padding']['unit']) . ' ' . esc_attr($settings['padding']['left']) . esc_attr($settings['padding']['unit']) . '; margin: ' . esc_attr($settings['margin']['top']) . esc_attr($settings['margin']['unit']) . ' ' . esc_attr($settings['margin']['right']) . esc_attr($settings['margin']['unit']) . ' ' . esc_attr($settings['margin']['bottom']) . esc_attr($settings['margin']['unit']) . ' ' . esc_attr($settings['margin']['left']) . esc_attr($settings['margin']['unit']) . ';">';
        if (!empty($itineraries)) {
            echo '<div class="mt-10" id="booktypesv">';
            echo '<div class="mt-4 space-y-4">';
            foreach ($itineraries as $index => $itinerary) {
                echo '<div class="vortex-ua-itinerary-bg p-4 mb-4 rounded-lg shadow-md itinerary-container" data-id="' . esc_attr($itinerary['itinerary_id']) . '">';
                echo '<div class="itinerary-header flex justify-between items-center cursor-pointer p-2" data-index="' . $index . '">';
                echo '<h4 class="text-lg font-semibold">' . esc_html($itinerary['title'] ?? $title) . '</h4>';
                echo '<div class="itinerary-price">' . ($locale_activities === 'en' ? 'From' : 'Από') . ' ' . esc_html($itinerary['min_price'] ?? '') . ' €</div>';
                echo '</div>';
                echo '<div class="itinerary-content ' . (count($itineraries) > 1 ? 'hidden' : '') . ' mt-4 p-4">';
                echo '<p>' . ($locale_activities === 'en' ? 'Activity ID:' : 'ID δραστηριότητας:') . ' ' . esc_html($itinerary['itinerary_id']) . '</p>';
                echo '<div class="prose max-w-none mb-2 mt-4 text-gray-700">' . wp_kses_post($itinerary['description'] ?? '') . '</div>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Minimum Persons:' : 'Ελάχιστα Άτομα:') . '</strong> ' . esc_html($itinerary['min_persons'] ?? '') . '</p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Minimum Age:' : 'Ελάχιστη Ηλικία:') . '</strong> ' . esc_html($itinerary['min_age'] ?? '') . '</p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Duration:' : 'Διάρκεια:') . '</strong> ' . esc_html($itinerary['duration'] ?? '') . '</p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Active Months:' : 'Ενεργοί μήνες:') . '</strong> ' . esc_html($active_months) . '</p>';

                if ($settings['show_map'] === 'yes' && $settings['map_location'] === 'top') {
                    include plugin_dir_path(__FILE__) . '../single/map.php';
                }
                include plugin_dir_path(__FILE__) . '../single/main-functions-js.php';

                $sections = $settings['sections'];
                foreach ($sections as $section) {
                    switch ($section['section_type']) {
                        case 'description':
                            echo '<div class="itinerary-description">';
                            echo '<h4>' . esc_html($section['section_title']) . '</h4>';
                            echo wp_kses_post($itinerary['description'] ?? '');
                            echo '</div>';
                            break;
                        case 'do_not_forget':
                            echo '<div class="itinerary-do-not-forget">';
                            echo '<h4>' . esc_html($section['section_title']) . '</h4>';
                            include plugin_dir_path(__FILE__) . '../single/itinerary-do-not-forget.php';
                            echo '</div>';
                            break;
                        case 'included':
                            echo '<div class="itinerary-included">';
                            echo '<h4>' . esc_html($section['section_title']) . '</h4>';
                            include plugin_dir_path(__FILE__) . '../single/itinerary-included.php';
                            echo '</div>';
                            break;
                        case 'we_speak':
                            echo '<div class="itinerary-we-speak">';
                            echo '<h4>' . esc_html($section['section_title']) . '</h4>';
                            include plugin_dir_path(__FILE__) . '../single/itinerary-we-speak.php';
                            echo '</div>';
                            break;
                    }
                }

                if ($settings['show_map'] === 'yes' && $settings['map_location'] === 'bottom') {
                    include plugin_dir_path(__FILE__) . '../single/map.php';
                }

                echo '<button data-id="' . esc_attr($itinerary['itinerary_id']) . '" class="bookNowBtn mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="background-color: ' . esc_attr($settings['button_color']) . '; color: ' . esc_attr($settings['button_text_color']) . ';">' . ($locale_activities === 'en' ? 'Book Now' : 'Κράτηση τώρα') . '</button>';
                echo '<div class="bookingModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">';
                echo '<div class="bg-white p-6 rounded-lg shadow-lg w-full h-full md:w-1/2 md:h-auto relative">';
                echo '<h3 class="text-lg font-semibold mb-4">' . ($locale_activities === 'en' ? 'You are 5 steps away!' : 'Είστε 5 βήματα μακριά!') . '</h3>';
                echo '<p>' . ($locale_activities === 'en' ? 'Total Price:' : 'Συνολική Τιμή:') . ' <span class="booking-price"></span></p>';
                echo wp_kses_post(get_option('vortex_ua_custom_html_inside_booking', ''));
                echo '<div class="bookingContent text-gray-700">';
                echo '<div class="step1 booking-step">';
                echo '<h4 class="text-lg font-semibold">' . ($locale_activities === 'en' ? 'Select Date' : 'Επιλέξτε ημερομηνία') . '</h4>';
                echo '<div class="date-picker-container hidden">';
                echo '<input type="text" id="datetime-' . esc_attr($itinerary['itinerary_id']) . '" data-itinerary-id="' . esc_attr($itinerary['itinerary_id']) . '" class="flatpickr-input mt-2 p-2 border rounded w-full" />';
                echo '</div>';
                echo '<button class="nextToStep2 mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="background-color: ' . esc_attr($settings['button_color']) . '; color: ' . esc_attr($settings['button_text_color']) . ';">' . ($locale_activities === 'en' ? 'Next' : 'Επόμενο') . '</button>';
                echo '</div>';
                echo '<div class="step2 booking-step hidden">';
                echo '<h4 class="text-lg font-semibold">' . ($locale_activities === 'en' ? 'Select Time' : 'Επιλέξτε ώρα') . '</h4>';
                echo '<div class="time-slot-container mb-4 hidden">';
                echo '<select id="timeslot-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '" class="time-slot-select mt-2 p-2 border rounded w-full">';
                echo '<option value="">' . ($locale_activities === 'en' ? 'Select' : 'Επιλέξτε') . '</option>';
                echo '</select>';
                echo '</div>';
                echo '<button class="backToStep1 mt-4 px-4 py-2 bg-gray-500 text-white rounded">' . ($locale_activities === 'en' ? 'Back' : 'Πίσω') . '</button>';
                echo '<button class="nextToStep3 mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="background-color: ' . esc_attr($settings['button_color']) . '; color: ' . esc_attr($settings['button_text_color']) . ';">' . ($locale_activities === 'en' ? 'Next' : 'Επόμενο') . '</button>';
                echo '</div>';
                echo '<div class="step3 booking-step hidden">';
                echo '<h4 class="text-lg font-semibold">' . ($locale_activities === 'en' ? 'Persons & Extras' : 'Άτομα & Εξτρά') . '</h4>';
                echo '<div class="flex items-center space-x-4">';
echo '<style>
    .webvortex-itineraries .decrease-btn:hover {
        background-color: ' . esc_attr($settings['decrease_button_hover_color']) . ';
    }
    .webvortex-itineraries .increase-btn:hover {
        background-color: ' . esc_attr($settings['increase_button_hover_color']) . ';
    }
</style>';

echo '<button class="decrease-btn text-white px-4 py-2 rounded" style="background-color: ' . esc_attr($settings['decrease_button_color']) . ';">-</button>';
echo '<span class="person-count text-2xl font-semibold">0</span>';
echo '<button class="increase-btn text-white px-4 py-2 rounded" style="background-color: ' . esc_attr($settings['increase_button_color']) . ';">+</button>';


                echo '</div>';
                echo '<h3 class="mt-4 extras-header">' . ($locale_activities === 'en' ? 'Extra Services' : 'Έξτρα Υπηρεσίες') . '</h3>';
                echo '<div class="facilities-container mb-4 hidden">';
                echo '<select multiple id="facilities-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '" class="facilities-select mt-2 p-2 border rounded w-full"></select>';
                echo '</div>';
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo 'function checkAndToggleExtras() {';
                echo 'const extrasHeader = document.querySelector(".extras-header");';
                echo 'const facilitiesContainer = document.querySelector(".facilities-container");';
                echo 'const facilitiesSelect = facilitiesContainer.querySelector("select");';
                echo 'if (facilitiesSelect.options.length === 0) {';
                echo 'extrasHeader.style.display = "none";';
                echo 'facilitiesContainer.style.display = "none";';
                echo '} else {';
                echo 'extrasHeader.style.display = "block";';
                echo 'facilitiesContainer.style.display = "block";';
                echo '}';
                echo '}';
                echo 'checkAndToggleExtras();';
                echo '});';
                echo '</script>';
                echo '<button class="backToStep2 mt-4 px-4 py-2 bg-gray-500 text-white rounded">' . ($locale_activities === 'en' ? 'Back' : 'Πίσω') . '</button>';
                echo '<button class="nextToStep4 mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="background-color: ' . esc_attr($settings['button_color']) . '; color: ' . esc_attr($settings['button_text_color']) . ';">' . ($locale_activities === 'en' ? 'Next' : 'Επόμενο') . '</button>';
                echo '</div>';
                echo '<div class="step4 booking-step hidden">';
                echo '<h4 class="text-lg font-semibold">' . ($locale_activities === 'en' ? 'Enter Your Details' : 'Εισάγετε τα στοιχεία σας') . '</h4>';
                echo '<input type="text" placeholder="' . ($locale_activities === 'en' ? 'First Name' : 'Όνομα') . '" name="customer_name" id="customer_name-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '" class="mt-2 p-2 border rounded w-full" />';
                echo '<input type="text" placeholder="' . ($locale_activities === 'en' ? 'Last Name' : 'Επίθετο') . '" name="customer_surname" id="customer_surname-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '" class="mt-2 p-2 border rounded w-full" />';
                echo '<input type="email" placeholder="Email" name="customer_email" id="customer_email-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '" class="mt-2 p-2 border rounded w-full" />';
                echo '<input type="number" placeholder="' . ($locale_activities === 'en' ? 'Phone' : 'Τηλέφωνο') . '" name="customer_phone" id="customer_phone-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '" class="mt-2 p-2 border rounded w-full" />';
                echo '<button class="backToStep3 mt-4 px-4 py-2 bg-gray-500 text-white rounded">' . ($locale_activities === 'en' ? 'Back' : 'Πίσω') . '</button>';
                echo '<button class="nextToStep5 mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="background-color: ' . esc_attr($settings['button_color']) . '; color: ' . esc_attr($settings['button_text_color']) . ';">' . ($locale_activities === 'en' ? 'Next' : 'Επόμενο') . '</button>';
                echo '</div>';
                echo '<div class="step5 booking-step hidden">';
                echo '<div class="mt-4">';
                echo '<h5 class="text-lg font-semibold">' . ($locale_activities === 'en' ? 'Booking Summary' : 'Σύνοψη Κράτησης') . '</h5>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Date:' : 'Ημερομηνία:') . '</strong> <span id="summary-date"></span></p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Time:' : 'Ώρα:') . '</strong> <span id="summary-time"></span></p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Persons:' : 'Άτομα:') . '</strong> <span id="summary-persons"></span></p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Extra Services:' : 'Έξτρα Παροχές:') . '</strong> <span id="summary-facilities"></span></p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'First Name:' : 'Όνομα:') . '</strong> <span id="summary-name"></span></p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Last Name:' : 'Επίθετο:') . '</strong> <span id="summary-surname"></span></p>';
                echo '<p><strong>Email:</strong> <span id="summary-email"></span></p>';
                echo '<p><strong>' . ($locale_activities === 'en' ? 'Phone:' : 'Τηλέφωνο:') . '</strong> <span id="summary-phone"></span></p>';
                echo '</div>';
                echo '<h4 class="text-lg font-semibold">' . ($locale_activities === 'en' ? 'Confirm Booking' : 'Επιβεβαίωση κράτησης') . '</h4>';
                echo '<p class="mt-2">' . ($locale_activities === 'en' ? 'Are you sure you want to proceed?' : 'Είστε σίγουροι ότι θέλετε να συνεχίσετε;') . '</p>';
                echo '<button class="backToStep4 mt-4 px-4 py-2 bg-gray-500 text-white rounded">' . ($locale_activities === 'en' ? 'Back' : 'Πίσω') . '</button>';
                echo '<button class="confirmBooking mt-4 px-4 py-2 vortex-ua-button text-white rounded" id="confirmBookingButton">' . ($locale_activities === 'en' ? 'Confirm Booking' : 'Πληρωμή Κράτησης') . '</button>';
                echo '<script type="text/javascript">';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo 'document.getElementById("confirmBookingButton").addEventListener("click", function(e) {';
                echo 'e.preventDefault();';
                echo 'var data = {';
                echo 'action: "send_booking_email",';
                echo 'booking_nonce: "' . wp_create_nonce('vortex_ua_book_notice') . '",';
                echo 'customer_name: document.getElementById("customer_name-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '").value,';
                echo 'customer_surname: document.getElementById("customer_surname-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '").value,';
                echo 'customer_email: document.getElementById("customer_email-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '").value,';
                echo 'customer_phone: document.getElementById("customer_phone-' . esc_attr($activity_id) . '-' . esc_attr($itinerary['itinerary_id']) . '").value,';
                echo 'activity_id: "' . esc_js($activity_id) . '",';
                echo 'itinerary_id: "' . esc_js($itinerary['itinerary_id']) . '"';
                echo '};';
                echo 'jQuery.post("' . admin_url('admin-ajax.php') . '", data, function(response) {';
                echo 'if (response.success) {}';
                echo '});';
                echo '});';
                echo '});';
                echo '</script>';
                echo '</div>';
                echo wp_kses_post(get_option('vortex_ua_custom_html_section_1', ''));
                echo '</div>';
                echo '<button class="closeBookingModalBtn absolute top-4 right-4 bg-gray-300 text-black px-2 py-1 rounded hover:bg-gray-400">X</button>';
                echo '</div>';
                echo '</div>';
                echo '<button class="mt-4 px-4 py-2 text-black text-sm rounded cancellation-button">' . ($locale_activities === 'en' ? 'Cancellation Policy' : 'Πολιτική Ακύρωσης') . '</button>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    protected function _content_template() {
        ?>
        <#
        var locale_activities = '<?php echo get_option('activity_api_locale', 'gr'); ?>';
        var itineraries = <?php echo json_encode(get_field('itineraries')); ?>;
        var title = settings.title;
        var activity_id = settings.activity_id;
        var active_months = settings.active_months;
        var duration = settings.duration;
        var description = settings.description;
        var min_price = settings.min_price;
        var rating = settings.rating;

        if (itineraries) { #>
            <div class="webvortex-itineraries">
                <div class="mt-10" id="booktypesv">
                    <div class="mt-4 space-y-4">
                        <# _.each(itineraries, function(itinerary, index) { #>
                            <div class="vortex-ua-itinerary-bg p-4 mb-4 rounded-lg shadow-md itinerary-container" data-id="{{ itinerary.itinerary_id }}">
                                <div class="itinerary-header flex justify-between items-center cursor-pointer p-2" data-index="{{ index }}">
                                    <h4 class="text-lg font-semibold">{{ itinerary.title || title }}</h4>
                                    <div class="itinerary-price">{{ locale_activities === 'en' ? 'From' : 'Από' }} {{ itinerary.min_price }} €</div>
                                </div>
                                <div class="itinerary-content {{ itineraries.length > 1 ? 'hidden' : '' }} mt-4 p-4">
                                    <p>{{ locale_activities === 'en' ? 'Activity ID:' : 'ID δραστηριότητας:' }} {{ itinerary.itinerary_id }}</p>
                                    <div class="prose max-w-none mb-2 mt-4 text-gray-700">{{{ itinerary.description }}}</div>
                                    <p><strong>{{ locale_activities === 'en' ? 'Minimum Persons:' : 'Ελάχιστα Άτομα:' }}</strong> {{ itinerary.min_persons }}</p>
                                    <p><strong>{{ locale_activities === 'en' ? 'Minimum Age:' : 'Ελάχιστη Ηλικία:' }}</strong> {{ itinerary.min_age }}</p>
                                    <p><strong>{{ locale_activities === 'en' ? 'Duration:' : 'Διάρκεια:' }}</strong> {{ itinerary.duration }}</p>
                                    <p><strong>{{ locale_activities === 'en' ? 'Active Months:' : 'Ενεργοί μήνες:' }}</strong> {{ active_months }}</p>
                                    <div class="booking-modal" data-id="{{ itinerary.itinerary_id }}">
                                        <div class="booking-content">
                                            <h4 class="text-lg font-semibold">{{ locale_activities === 'en' ? 'Booking Summary' : 'Σύνοψη Κράτησης' }}</h4>
                                            <p>{{ locale_activities === 'en' ? 'Total Price:' : 'Συνολική Τιμή:' }} <span class="booking-price"></span></p>
                                            <div class="booking-steps">
                                                <div class="step step1">
                                                    <h4>{{ locale_activities === 'en' ? 'Select Date' : 'Επιλέξτε ημερομηνία' }}</h4>
                                                    <input type="text" class="date-picker" data-itinerary-id="{{ itinerary.itinerary_id }}">
                                                    <button class="next-step" style="background-color: settings.button_color; color: settings.button_text_color;">{{ locale_activities === 'en' ? 'Next' : 'Επόμενο' }}</button>
                                                </div>
                                                <div class="step step2">
                                                    <h4>{{ locale_activities === 'en' ? 'Select Time' : 'Επιλέξτε ώρα' }}</h4>
                                                    <select class="time-slot-select" data-itinerary-id="{{ itinerary.itinerary_id }}">
                                                        <option value="">{{ locale_activities === 'en' ? 'Select' : 'Επιλέξτε' }}</option>
                                                    </select>
                                                    <button class="previous-step">{{ locale_activities === 'en' ? 'Back' : 'Πίσω' }}</button>
                                                    <button class="next-step" style="background-color: settings.button_color; color: settings.button_text_color;">{{ locale_activities === 'en' ? 'Next' : 'Επόμενο' }}</button>
                                                </div>
                                                <div class="step step3">
                                                    <h4>{{ locale_activities === 'en' ? 'Persons & Extras' : 'Άτομα & Εξτρά' }}</h4>
                                                    <div class="person-count-container">
                                                        <button class="decrease-person">{{ '-' }}</button>
                                                        <span class="person-count">0</span>
                                                        <button class="increase-person">{{ '+' }}</button>
                                                    </div>
                                                    <div class="extra-services">
                                                        <h4>{{ locale_activities === 'en' ? 'Extra Services' : 'Έξτρα Υπηρεσίες' }}</h4>
                                                        <select multiple class="extra-services-select" data-itinerary-id="{{ itinerary.itinerary_id }}"></select>
                                                    </div>
                                                    <button class="previous-step">{{ locale_activities === 'en' ? 'Back' : 'Πίσω' }}</button>
                                                    <button class="next-step" style="background-color: settings.button_color; color: settings.button_text_color;">{{ locale_activities === 'en' ? 'Next' : 'Επόμενο' }}</button>
                                                </div>
                                                <div class="step step4">
                                                    <h4>{{ locale_activities === 'en' ? 'Enter Your Details' : 'Εισάγετε τα στοιχεία σας' }}</h4>
                                                    <input type="text" placeholder="{{ locale_activities === 'en' ? 'First Name' : 'Όνομα' }}" class="customer-first-name">
                                                    <input type="text" placeholder="{{ locale_activities === 'en' ? 'Last Name' : 'Επίθετο' }}" class="customer-last-name">
                                                    <input type="email" placeholder="Email" class="customer-email">
                                                    <input type="tel" placeholder="{{ locale_activities === 'en' ? 'Phone' : 'Τηλέφωνο' }}" class="customer-phone">
                                                    <button class="previous-step">{{ locale_activities === 'en' ? 'Back' : 'Πίσω' }}</button>
                                                    <button class="next-step">{{ locale_activities === 'en' ? 'Next' : 'Επόμενο' }}</button>
                                                </div>
                                                <div class="step step5">
                                                    <h4>{{ locale_activities === 'en' ? 'Confirm Booking' : 'Επιβεβαίωση κράτησης' }}</h4>
                                                    <p>{{ locale_activities === 'en' ? 'Are you sure you want to proceed?' : 'Είστε σίγουροι ότι θέλετε να συνεχίσετε;' }}</p>
                                                    <button class="previous-step">{{ locale_activities === 'en' ? 'Back' : 'Πίσω' }}</button>
                                                    <button class="confirm-booking">{{ locale_activities === 'en' ? 'Confirm Booking' : 'Πληρωμή Κράτησης' }}</button>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="close-booking-modal">X</button>
                                    </div>
                                    <button class="book-now-btn">{{ locale_activities === 'en' ? 'Book Now' : 'Κράτηση τώρα' }}</button>
                                </div>
                            </div>
                        <# }); #>
                    </div>
                </div>
            </div>
        <# } #>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Webvortex_Itineraries_Widget());
?>
