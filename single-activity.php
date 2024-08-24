
<?php

if (!defined('ABSPATH')) exit;

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

    $button_color = get_option('vortex_ua_button_color', '#FA345B');
    
    $itinerary_bg_color = get_option('vortex_ua_itinerary_bg_color', '#f6f9fc');

    echo '<style>.vortex-ua-button{background-color:' . esc_attr($button_color) . ';}.vortex-ua-itinerary-bg{background-color:' . esc_attr($itinerary_bg_color) . ';}.itinerary-container{margin-bottom:1rem;border-radius:0.5rem;}.itinerary-header{padding:0.75rem 1rem;cursor:pointer;border-bottom:1px solid #e5e7eb;}.itinerary-content{padding:1rem;}.itinerary-price{font-size:1rem;}.itinerary-header h4{margin:0;}</style>';
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
    $term_links = array();
    foreach ($terms as $term) {
$term_links[] = esc_html($term->name);
    }
    echo '<div style="background-color: #EEEEEE; border-radius: 25px; color: ' . esc_attr($button_color) . '; padding: 5px 10px; display: inline-block; margin: 5px 0; text-align: center; margin-left: auto; margin-right: auto; width: fit-content;">' . implode(', ', $term_links) . '</div>';
} else {
    // Use $button_color for the span element as well
    echo '<span style="background-color: #EEEEEE; border-radius: 25px; color: ' . esc_attr($button_color) . '; padding: 5px 10px; display: inline-block; text-align: center; margin-left: auto; margin-right: auto; width: fit-content;">' . ($locale_activities === 'en' ? 'No categories found' : 'Δεν βρέθηκαν κατηγορίες') . '</span>';
}
?>
<div class="flex flex-col">
  <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl"><?php echo esc_html($title); ?></h1>
  <div class="relative mt-1 flex items-center">
    <span class="text-gray-500 font-semibold text-sm">
      <?php echo $locale_activities === 'en' ? 'Activity ID:' : 'ID Δραστηριότητας:'; ?> <?php echo $activity_id; ?>
    </span>
  </div>
</div>

 

                </div>

<div class="container" style="display: flex; flex-direction: column; align-items: center; position: sticky; top: 40px; z-index: 9999 !important;">
    <div class="mt-2 lg:row-span-3 lg:mt-0" style="background-color: #EEEEEE; border-radius: 25px; padding: 20px; width: 100%; max-width: 412px; height: auto; margin-top: 10px;">
        <!-- First div content -->
        <?php if (!empty($min_price)): ?>
        <div class="mt-2 items-right ">
            <h2 class="text-sm text-gray-900 " style="display: inline;"><?php echo $locale_activities === 'en' ? 'from' : 'από'; ?></h2>
            <div class="text-md tracking-tight text-gray-900" style="display: inline; font-size: 1.5rem; margin-left: 5px;"><?php echo wp_kses_post($min_price); ?> €</div>
            <span style="display: inline;" class="text-sm text-gray-900"><?php echo $locale_activities === 'en' ? ' / person' : ' / άτομο'; ?></span>
        </div>
        <?php endif; ?>

        <?php include plugin_dir_path(__FILE__) . 'single/faq-popup.php'; ?>

        <?php if (!empty($additional_info)): ?>
        <div class="mt-4">
            <h3 class="text-lg font-medium text-gray-900"><?php echo $locale_activities === 'en' ? 'FAQ' : 'Συχνές Ερωτήσεις'; ?></h3>
            <div class="prose max-w-none mt-2 text-gray-700"><?php echo wp_kses_post($additional_info); ?></div>
        </div>
        <?php endif; ?>
        <br>
        <a href="#booktypesv" class="mt-2 px-4 py-2" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: block; width: 100%; text-align: center; margin: 0 auto;"><?php echo $locale_activities === 'en' ? 'Book Now' : 'Κράτηση'; ?></a>
        <br>
        <div class="flex items-center">
            <svg class="w-4 h-4 text-yellow-300 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20"><path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/></svg>
            <p class="ms-2 text-sm font-bold text-gray-900 dark:text-white"><?php echo esc_html($rating); ?></p>
            <span class="w-1 h-1 mx-1.5 bg-gray-500 rounded-full dark:bg-gray-400"></span>
            <?php if (get_option('vortex_ua_show_reviews', 'yes') === 'yes'): ?>
            <a href="#unlimited-a-reviews" class="text-sm font-medium text-gray-900 underline hover:no-underline dark:text-white"><?php echo $locale_activities === 'en' ? 'Read Reviews' : 'Διαβάστε κριτικές'; ?></a>
            <?php endif; ?>
        </div>
        <?php echo wp_kses_post(get_option('vortex_ua_custom_html_section_2', '')); ?>
    </div>

<div class="mt-2 lg:row-span-3 lg:mt-0" style="background-color: #EEEEEE; border-radius: 25px; padding: 20px; width: 100%; max-width: 412px; height: auto; margin-top: 10px;">
    
    <h2 class="text-2xl text-gray-900" style="display: inline; margin-right: 5px;">
        <?php echo $locale_activities === 'en' ? 'Useful' : 'Χρήσιμες'; ?>
    </h2>
    <h2 class="text-2xl text-gray-900" style="display: inline; color: <?php echo $button_color;?>; margin-right: 5px;">
        <?php echo $locale_activities === 'en' ? 'Information' : 'Συμβουλές'; ?>
    </h2>

    <a href="#" id="openUseFullInfoPopUpBtn" class="mt-2 px-4 py-2" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;">
        <?php echo $locale_activities === 'en' ? 'More' : 'Περισσότερα'; ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="margin-left: 8px;">
            <path fill="white" d="m12 16l-6-6h12z" />
        </svg>
    </a>

<div id="UseFullInfoPopUp"
     class="useFullInfoPopUpModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden"
     style="z-index: 99999 !important; 
            max-width: 100%; 
            max-height: 100%; 
            padding: 20px; 
            overflow: auto; 
            /* Mobile responsiveness */ 
            @media (max-width: 768px) { 
                padding: 10px; 
            }">    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-semibold mb-4 text-center">
            <?php echo $locale_activities === 'en' ? 'Useful Information' : 'Χρήσιμες Συμβουλές'; ?>
        </h3>
        <ul class="useFullInfoPopUpContent text-gray-700 list-disc pl-5">
            <li>
                <?php echo $locale_activities === 'en' ? 'Many activities are weather dependent, so it is recommended to contact the company before the activity.' : 'Πολλές δραστηριότητες βασίζονται στις καιρικές συνθήκες, προτείνεται λοιπόν να επικοινωνήσετε με την επιχείρηση πριν την δραστηριότητα.'; ?>
            </li>
            <li>
                <?php echo $locale_activities === 'en' ? 'Some activities require an availability check before your booking is confirmed.' : 'Μερικές δραστηριότητες χρειάζονται έλεγχο διαθεσιμότητας πριν επιβεβαιωθεί η κράτηση σας.'; ?>
            </li>
            <li>
                <?php echo $locale_activities === 'en' ? 'After your reservation, it is enough to provide your details to the partner company to be able to carry out your activity.' : 'Μετά την κράτησή σας, αρκεί να δώσετε τα στοιχεία σας στην συνεργαζόμενη επιχείρηση για μπορέσετε να πραγματοποιήσετε τη δραστηριότητά σας.'; ?>
            </li>
            <li>
                <?php echo $locale_activities === 'en' ? 'However, it is recommended that you have the booking confirmation in digital or printed form.' : 'Παρ’ όλα αυτά, προτείνεται να έχετε σε ψηφιακή ή εκτυπωμένη μορφή την επιβεβαίωση της κράτησης.'; ?>
            </li>
            <li>
                <?php echo $locale_activities === 'en' ? 'Carefully read the benefits offered by the activity to avoid possible extra charges from additional optional services provided.' : 'Διαβάστε προσεκτικά τις παροχές που προσφέρει η δραστηριότητα για να αποφύγετε πιθανόν έξτρα χρεώσεις από επιπλέον προαιρετικές παρεχόμενες υπηρεσίες.'; ?>
            </li>
            <li>
                <?php echo $locale_activities === 'en' ? 'Please read the cancellation policy carefully before booking the activity.' : 'Διαβάστε προσεκτικά την πολιτική ακύρωσης πριν την κράτηση της δραστηριότητας.'; ?>
            </li>
        </ul>
        <button id="closeUseFullInfoPopUpBtn" class="mt-4 px-4 py-2 bg-black text-white rounded block mx-auto">
            <?php echo $locale_activities === 'en' ? 'Close' : 'Κλείσιμο'; ?>
        </button>
    </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openUseFullInfoPopUpBtn = document.getElementById('openUseFullInfoPopUpBtn');
    const closeUseFullInfoPopUpBtn = document.getElementById('closeUseFullInfoPopUpBtn');
    const useFullInfoPopUp = document.getElementById('UseFullInfoPopUp');

    openUseFullInfoPopUpBtn.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default action of the anchor tag
        useFullInfoPopUp.classList.remove('hidden');
    });

    closeUseFullInfoPopUpBtn.addEventListener('click', function() {
        useFullInfoPopUp.classList.add('hidden');
    });

    // Close the popup if clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === useFullInfoPopUp) {
            useFullInfoPopUp.classList.add('hidden');
        }
    });
});


</script>

</div>




                <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6" id="booktypesv">
                    <?php
                    if (get_option('vortex_ua_show_read_more', 'yes') === 'yes') {
                        include plugin_dir_path(__FILE__) . 'single/perigrafi.php';
                    } else {
                        include plugin_dir_path(__FILE__) . 'single/perigrafi-without.php';
                    }

                    echo wp_kses_post(get_option('vortex_ua_custom_html_section_3', ''));

                    if (!empty($itineraries)): ?>
                    <div class="mt-10">
                        <div class="mt-4 space-y-4">
                            <?php foreach ($itineraries as $index => $itinerary): ?>
                            <div class="vortex-ua-itinerary-bg p-4 mb-4 rounded-lg shadow-md itinerary-container" style="<?php if (wp_is_mobile()) { echo ''; } else { echo 'position: relative; top: -190px;'; } ?>" data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>">


          
    



            <a class="itinerary-header flex flex-col items-center cursor-pointer p-2" data-index="<?php echo $index; ?>" style="display: flex; flex-direction: column; align-items: center; cursor: pointer; padding: 2px;">
    <div class="header-content" style="display: flex; width: 100%; justify-content: space-between; align-items: center;">
        <h4 class="text-lg font-semibold" style="margin: 0;">
            <?php echo esc_html($itinerary['title'] ?? $title); ?>
        </h4>
        <span class="itinerary-price" style="background-color: #000000; color: #FFFFFF; border-radius: 12px; padding: 2px 6px; font-size: 13px;">
            <?php echo $locale_activities === 'en' ? 'From' : 'Από'; ?> <?php echo esc_html($itinerary['min_price'] ?? ''); ?> €
        </span>
    </div>
    <div class="open-for-more" style="text-align: center; width: 100%; font-size: 12px; color: #666; margin-top: 4px;">
       <?php echo $locale_activities === 'en' ? 'Show More' : 'Εμφάνιση'; ?>
    </div>
</a>

                                
                                <div class="itinerary-content <?php echo count($itineraries) > 1 ? 'hidden' : ''; ?> mt-4 p-4">
<button 
    data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>"  class="bookNowBtn mt-2 px-4 py-2" 
style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: block; width: 100%; text-align: center; margin: 0 auto;">
    <?php echo $locale_activities === 'en' ? 'Book Now' : 'Κράτηση'; ?>
</button>
                                    <div class="prose max-w-none mb-2 mt-4 text-gray-700"><?php echo wp_kses_post($itinerary['description'] ?? ''); ?></div>
                                   
                                        <div style="display: flex; align-items: center;">
                                            <div style="display: flex; align-items: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                            <path fill="black" d="M6.5 7.5a2.25 2.25 0 1 0 0-4.5a2.25 2.25 0 0 0 0 4.5m0-1a1.25 1.25 0 1 1 0-2.5a1.25 1.25 0 0 1 0 2.5M3 9a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v4.5a3.5 3.5 0 1 1-7 0zm1 0v4.5a2.5 2.5 0 0 0 5 0V9zm5.626-2.857a3.2 3.2 0 0 1-.396.87c.413.048.787.22 1.084.48q.091.006.186.007a2.25 2.25 0 1 0-1.312-4.078c.192.283.341.598.438.935a1.25 1.25 0 1 1 0 1.787m-.127 10.712A3.5 3.5 0 0 0 14 13.5V9a1 1 0 0 0-1-1h-2.267c.17.294.268.636.268 1h2v4.5a2.5 2.5 0 0 1-2.75 2.488q-.321.482-.75.867m4.127-10.712a3.2 3.2 0 0 1-.396.87c.413.048.787.22 1.084.48q.091.006.186.007a2.25 2.25 0 1 0-1.312-4.078c.192.283.341.598.438.935a1.25 1.25 0 1 1 0 1.787m-.127 10.712q.478.143 1 .145a3.5 3.5 0 0 0 3.5-3.5V9a1 1 0 0 0-1-1h-2.267c.17.294.268.636.268 1h2v4.5a2.5 2.5 0 0 1-2.75 2.488q-.321.482-.75.867" />
                                        </svg>        <p> <?php echo esc_html($itinerary['min_persons'] ?? ''); ?></p>
                                            </div>
                                            <div style="margin: 0 10px;">|</div>
                                            <div style="display: flex; align-items: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                            <path fill="black" d="M92 136a8 8 0 1 1 8-8a8 8 0 0 1-8 8m72-16a8 8 0 1 0 8 8a8 8 0 0 0-8-8m-10.13 44.62a49 49 0 0 1-51.74 0a4 4 0 0 0-4.26 6.76a57 57 0 0 0 60.26 0a4 4 0 1 0-4.26-6.76M228 128A100 100 0 1 1 128 28a100.11 100.11 0 0 1 100 100m-8 0a92.11 92.11 0 0 0-90.06-92C116.26 54.07 116 71.83 116 72a12 12 0 0 0 24 0a4 4 0 0 1 8 0a20 20 0 0 1-40 0c0-.78.16-17.31 12-35.64A92 92 0 1 0 220 128" />
                                        </svg>        <p><?php echo esc_html($itinerary['min_age'] ?? ''); ?></p>
                                            </div>
                                            <div style="margin: 0 10px;">|</div>
                                            <div style="display: flex; align-items: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                                            <path fill="none" stroke="black" stroke-miterlimit="10" stroke-width="32" d="M256 64C150 64 64 150 64 256s86 192 192 192s192-86 192-192S362 64 256 64Z" />
                                            <path fill="none" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 128v144h96" />
                                        </svg>      <p><?php echo esc_html($itinerary['duration'] ?? ''); ?></p>
                                            </div>
                                            <div style="margin: 0 10px;">|</div>
                                            <div style="display: flex; align-items: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                            <path fill="black" d="M208 36h-28V24a4 4 0 0 0-8 0v12H84V24a4 4 0 0 0-8 0v12H48a12 12 0 0 0-12 12v160a12 12 0 0 0 12 12h160a12 12 0 0 0 12-12V48a12 12 0 0 0-12-12M48 44h28v12a4 4 0 0 0 8 0V44h88v12a4 4 0 0 0 8 0V44h28a4 4 0 0 1 4 4v36H44V48a4 4 0 0 1 4-4m160 168H48a4 4 0 0 1-4-4V92h168v116a4 4 0 0 1-4 4m-100-92v64a4 4 0 0 1-8 0v-57.53l-10.21 5.11a4 4 0 0 1-3.58-7.16l16-8A4 4 0 0 1 108 120m60 28l-24 32h24a4 4 0 0 1 0 8h-32a4 4 0 0 1-3.2-6.4l28.78-38.37A11.88 11.88 0 0 0 164 136a12 12 0 0 0-22.4-6a4 4 0 0 1-6.92-4A20 20 0 0 1 172 136a19.8 19.8 0 0 1-4 12" />
                                        </svg>       <p> <?php echo esc_html($active_months); ?></p>
                                            </div>
                                                                                        <div style="margin: 0 10px;">|</div>

                                         <div style="display: flex; align-items: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
	<path fill="currentColor" d="M3 16h2v5H3zm4-3h2v8H7zm4-3h2v11h-2z" />
</svg>     <p> <?php echo esc_html($itinerary['difficulty'] ?? ''); ?></p>
                                            </div>
                                        </div>

                                    <?php include plugin_dir_path(__FILE__) . 'single/itinerary-we-speak.php'; ?>
                                    <?php include plugin_dir_path(__FILE__) . 'single/itinerary-included.php'; ?>
                                    <?php include plugin_dir_path(__FILE__) . 'single/itinerary-do-not-forget.php'; ?>
                                    <?php if (get_option('vortex_ua_show_map', 'yes') === 'yes') include plugin_dir_path(__FILE__) . 'single/map.php'; ?>
                                    <div id="cancellationPolicyUAPopup" class="cancellationModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" style="z-index: 9999 !important;">
                                        <div class="bg-white p-6 rounded-lg shadow-lg">
                                            <h3 class="text-lg font-semibold mb-4"><?php echo $locale_activities === 'en' ? 'Cancellation Policy' : 'Πολιτική Ακύρωσης'; ?></h3>
                                            <div class="cancellationContent text-gray-700">
                                                <h3 class="text-lg"><?php echo esc_html($itinerary['cancellation_policy']['title']); ?></h3>
                                                <p class="text-sm"><?php echo wp_kses_post($itinerary['cancellation_policy']['description']); ?></p>
                                            </div>
                                            <button class="closeModalBtn mt-4 px-4 py-2 bg-black text-white rounded"><?php echo $locale_activities === 'en' ? 'Close' : 'Κλείσιμο'; ?></button>
                                        </div>
                                    </div>
 <div class="bookingModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" 
                style="z-index: 9999;" data-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>">
                                                        <div class="bg-white p-6 rounded-lg shadow-lg w-full h-full md:w-1/2 md:h-auto relative">
                                            <h3 class="text-lg font-semibold mb-4"><?php echo $locale_activities === 'en' ? 'You are a few steps away!' : 'Είστε λίγα βήματα μακριά!'; ?></h3>

                                            <p><?php echo $locale_activities === 'en' ? 'Total Price:' : 'Συνολική Τιμή:'; ?> <span class="booking-price"></span></p>
                                            <?php echo wp_kses_post(get_option('vortex_ua_custom_html_inside_booking', '')); ?>
                                            <div class="bookingContent text-gray-700">
                                                <div class="step1 booking-step">
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Select Date' : 'Επιλέξτε ημερομηνία'; ?></h4>
                                                    <div class="date-picker-container hidden">
                                                        <input type="text" id="datetime-<?php echo esc_attr($itinerary['itinerary_id']); ?>" data-itinerary-id="<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="flatpickr-input mt-2 p-2 border rounded w-full" />
                                                    </div>
                                                    <button class="nextToStep2 mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;"><?php echo $locale_activities === 'en' ? 'Next' : 'Επόμενο'; ?></button>
                                                </div>
                                                <div class="step2 booking-step hidden">
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Select Time' : 'Επιλέξτε ώρα'; ?></h4>
                                                    <div class="time-slot-container mb-4 hidden">
                                                        <select id="timeslot-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="time-slot-select mt-2 p-2 border rounded w-full">
                                                            <option value=""><?php echo $locale_activities === 'en' ? 'Select' : 'Επιλέξτε'; ?></option>
                                                        </select>
                                                    </div>
                                                    <button class="backToStep1 mt-4 px-4 py-2 bg-gray-500 text-white rounded" style="color: #FFFFFF; background-color: black; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;"><?php echo $locale_activities === 'en' ? 'Back' : 'Πίσω'; ?></button>
                                                    <button class="nextToStep3 mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;"><?php echo $locale_activities === 'en' ? 'Next' : 'Επόμενο'; ?></button>
                                                </div>
                                                <div class="step3 booking-step hidden">
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Persons & Extras' : 'Άτομα & Εξτρά'; ?></h4>
                                                    <div class="flex items-center space-x-4">
                                                        <button class="decrease-btn "><svg width="15" height="4" viewBox="0 0 25 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M23.6111 3.38886H1.38889C0.62963 3.38886 0 2.75923 0 1.99997C0 1.24071 0.62963 0.611084 1.38889 0.611084H23.6111C24.3704 0.611084 25 1.24071 25 1.99997C25 2.75923 24.3704 3.38886 23.6111 3.38886Z" fill="#162020"/>
                                                        </svg>
                                                        </button>
                                                         <span class="person-count text-2xl font-semibold">0</span>
                                                        <button class="increase-btn "><svg width="15" height="16" viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M23.6111 14.3889H1.38889C0.62963 14.3889 0 13.7592 0 13C0 12.2407 0.62963 11.6111 1.38889 11.6111H23.6111C24.3704 11.6111 25 12.2407 25 13C25 13.7592 24.3704 14.3889 23.6111 14.3889Z" fill="#162020"/>
                                                        <path d="M12.5002 25.5C11.741 25.5 11.1113 24.8704 11.1113 24.1111V1.88889C11.1113 1.12963 11.741 0.5 12.5002 0.5C13.2595 0.5 13.8891 1.12963 13.8891 1.88889V24.1111C13.8891 24.8704 13.2595 25.5 12.5002 25.5Z" fill="#162020"/>
                                                        </svg>
                                                        </button>
                                                    </div>
                                                    <h3 class="mt-4 extras-header"><?php echo $locale_activities === 'en' ? 'Extra Services' : 'Έξτρα Υπηρεσίες'; ?></h3>
                                                    <div class="facilities-container mb-4"></div>
                                                    <button class="backToStep2 mt-4 px-4 py-2 bg-gray-500 text-white rounded" style="color: #FFFFFF; background-color: black; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;"><?php echo $locale_activities === 'en' ? 'Back' : 'Πίσω'; ?></button>
                                                    <button class="nextToStep4 mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;"><?php echo $locale_activities === 'en' ? 'Next' : 'Επόμενο'; ?></button>
                                                </div>
                                                <div class="step4 booking-step hidden">
                                                    <h4 class="text-lg font-semibold"><?php echo $locale_activities === 'en' ? 'Enter Your Details' : 'Εισάγετε τα στοιχεία σας'; ?></h4>
                                                    <input type="text" placeholder="<?php echo $locale_activities === 'en' ? 'First Name' : 'Όνομα'; ?>" name="customer_name" id="customer_name-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="mt-2 p-2 border rounded w-full" />
                                                    <input type="text" placeholder="<?php echo $locale_activities === 'en' ? 'Last Name' : 'Επίθετο'; ?>" name="customer_surname" id="customer_surname-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="mt-2 p-2 border rounded w-full" />
                                                    <input type="email" placeholder="Email" name="customer_email" id="customer_email-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="mt-2 p-2 border rounded w-full" />
                                                    <input type="number" placeholder="<?php echo $locale_activities === 'en' ? 'Phone' : 'Τηλέφωνο'; ?>" name="customer_phone" id="customer_phone-<?php echo esc_attr($activity_id); ?>-<?php echo esc_attr($itinerary['itinerary_id']); ?>" class="mt-2 p-2 border rounded w-full" />
                                                    <button class="backToStep3 mt-4 px-4 py-2 bg-gray-500 text-white rounded" style="color: #FFFFFF; background-color: black; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;"><?php echo $locale_activities === 'en' ? 'Back' : 'Πίσω'; ?></button>
                                                    <button class="confirmBooking mt-4 px-4 py-2 vortex-ua-button text-white rounded" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;" id="confirmBookingButton"><?php echo $locale_activities === 'en' ? 'Confirm Booking' : 'Πληρωμή Κράτησης'; ?></button>
                                                </div>


                                                <?php echo wp_kses_post(get_option('vortex_ua_custom_html_section_1', '')); ?>
                                            </div>
                                            <button class="closeBookingModalBtn absolute top-4 right-4 bg-gray-300 text-black px-2 py-1 rounded hover:bg-gray-400" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 25px;">X</button>
                                        </div>
                                    </div>
                                        <?php include plugin_dir_path(__FILE__) . 'single/location.php'; ?>

                                    <button id="cancellationPolicyUA" class="mt-4 px-4 py-2 text-black text-sm rounded cancellation-button"><?php echo $locale_activities === 'en' ? 'Cancellation Policy' : 'Πολιτική Ακύρωσης'; ?></button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a id="vortex-ua-info-new-btn" class="mt-4 px-4 py-2" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;"><?php echo $locale_activities === 'en' ? ' FAQ’s↗' : 'Συχνές Ερωτήσεις↗'; ?></a><br>
<!-- Link to open the new popup -->
<a href="#" id="openPolicyTermsUABtn" class="mt-4 px-4 py-2" style="color: #FFFFFF; background-color: <?php echo $button_color;?>; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; width: 100%; text-align: center;">
    <?php echo $locale_activities === 'en' ? 'Terms of Use' : 'Όροι Χρήσης'; ?> ↗
</a>

<!-- New Popup -->
<div id="PolicyTermsUAPopup" class="policyTermsUAModal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" style="z-index: 9999 !important;">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-semibold mb-4">
            <?php echo $locale_activities === 'en' ? 'Terms of Use' : 'Όροι Χρήσης'; ?>
        </h3>
        <div class="policyTermsUAContent text-gray-700">
            <!-- Insert the content for the Terms of Use here -->
            <p class="text-sm">   <?php include plugin_dir_path(__FILE__) . 'single/terms.php'; ?>
</p>
        </div>
        <button id="closePolicyTermsUABtn" class="mt-4 px-4 py-2 bg-black text-white rounded">
            <?php echo $locale_activities === 'en' ? 'Close' : 'Κλείσιμο'; ?>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openPolicyTermsUABtn = document.getElementById('openPolicyTermsUABtn');
    const closePolicyTermsUABtn = document.getElementById('closePolicyTermsUABtn');
    const policyTermsUAPopup = document.getElementById('PolicyTermsUAPopup');

    openPolicyTermsUABtn.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default action of the anchor tag
        policyTermsUAPopup.classList.remove('hidden');
    });

    closePolicyTermsUABtn.addEventListener('click', function() {
        policyTermsUAPopup.classList.add('hidden');
    });

    // Close the popup if clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === policyTermsUAPopup) {
            policyTermsUAPopup.classList.add('hidden');
        }
    });
});
</script>
                    <?php echo wp_kses_post(get_option('vortex_ua_custom_html_section_4', '')); ?>
                    <?php endif; ?>
                    <?php if (get_option('vortex_ua_show_reviews', 'yes') === 'yes')     include plugin_dir_path(__FILE__) . 'single/reviews.php'; ?>
                </div>
            </article>
        </div>
    </div>
    <?php include plugin_dir_path(__FILE__) . 'single/main-functions-js.php'; ?>
    <?php endwhile; endif; if (get_option('vortex_ua_show_headers', 'yes') === 'yes') get_footer(); ?>