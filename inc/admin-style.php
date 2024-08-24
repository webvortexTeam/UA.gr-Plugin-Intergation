<?php
add_action('admin_menu', function() {
    add_submenu_page('activity_settings', 'Styling', 'Styling', 'manage_options', 'styling_settings', function() {
        ?>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <div class="wrap bg-white p-10 rounded-lg shadow-lg max-w-2xl mx-auto mt-12 border border-red-500">
            <h1 class="text-3xl font-bold mb-6 text-center text-red-600">Το δικό σας Styling</h1>
            <form action="options.php" method="post" class="space-y-6">
                <?php
                settings_fields('vortex_ua_styling_settings');
                do_settings_sections('vortex_ua_styling_settings');
                ?>
                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-colors duration-300">
                    <?php _e('Αποθήκευση Style', 'unlimited-andrenaline-v2'); ?>
                </button>
            </form>
            <a href="https://github.com/webvortexTeam/UA.gr-Plugin-Intergation/raw/main/imagesRepo/template-activity.zip">Λήψη elementor template</a>
        </div>
        <?php
    });
});

add_action('admin_init', function() {
    $settings = [
        'vortex_ua_show_breadcrumbs', 'vortex_ua_show_logo', 'vortex_ua_custom_html_inside_booking', 'vortex_ua_custom_html_section_1', 'vortex_ua_custom_html_section_2', 'vortex_ua_custom_html_section_3', 'vortex_ua_custom_html_section_4', 'vortex_ua_show_read_more', 'vortex_ua_show_reviews', 'vortex_ua_show_map', 'vortex_ua_logo_url', 
        'vortex_ua_button_color',  'vortex_ua_show_headers', 'vortex_ua_selected_header', 'vortex_ua_walpaper_url', 'vortex_ua_itinerary_bg_color'
    ];
    foreach ($settings as $setting) {
        register_setting('vortex_ua_styling_settings', $setting);
    }

    add_settings_section('vortex_ua_styling_section', '', function() {
        echo '<p>Ρυθμίστε το styling του plugin</p>';
    }, 'vortex_ua_styling_settings');

    add_settings_field('vortex_ua_show_breadcrumbs', 'Εμφάνιση Breadcrumbs', function() {
        $value = get_option('vortex_ua_show_breadcrumbs', 'yes');
        echo '<div class="flex items-center">';
        echo '<div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">';
        echo '<input type="checkbox" id="vortex_ua_show_breadcrumbs" name="vortex_ua_show_breadcrumbs" value="yes"' . checked('yes', $value, false) . ' class="vortex-toggle-ua-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />';
        echo '<label for="vortex_ua_show_breadcrumbs" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>';
        echo '</div>';
        echo '</div>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');
 add_settings_field('vortex_ua_selected_header', 'Επιλογή Header', function() {
        $selected_header = get_option('vortex_ua_selected_header');

        // Fetch headers from Elementor
        $elementor_headers = get_posts(array(
            'post_type' => 'elementor_library',
            'tax_query' => array(
                array(
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => 'header',
                ),
            ),
        ));

        // Fetch headers from WPBakery
        $wpbakery_headers = get_posts(array(
            'post_type' => 'wpb_vc_templates',
            'meta_query' => array(
                array(
                    'key' => '_wpb_vc_js_status',
                    'value' => 'header',
                    'compare' => '=',
                ),
            ),
        ));

        // Fetch headers from Beaver Builder
        $beaver_builder_headers = get_posts(array(
            'post_type' => 'fl-builder-template',
            'meta_query' => array(
                array(
                    'key' => '_fl_builder_template_type',
                    'value' => 'header',
                    'compare' => '=',
                ),
            ),
        ));

        // Combine all headers
        $all_headers = array_merge($elementor_headers, $wpbakery_headers, $beaver_builder_headers);

        // Display the dropdown
        echo '<select name="vortex_ua_selected_header" class="regular-text">';
        foreach ($all_headers as $header) {
            echo '<option value="' . esc_attr($header->ID) . '" ' . selected($selected_header, $header->ID, false) . '>' . esc_html($header->post_title) . '</option>';
        }
        echo '</select>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_show_headers', 'Εμφάνιση Header & Footer', function() {
        $value = get_option('vortex_ua_show_headers', 'yes');
        echo '<div class="flex items-center">';
        echo '<div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">';
        echo '<input type="checkbox" id="vortex_ua_show_headers" name="vortex_ua_show_headers" value="yes"' . checked('yes', $value, false) . ' class="vortex-toggle-ua-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />';
        echo '<label for="vortex_ua_show_headers" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>';
        echo '</div>';
        echo '</div>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_show_read_more', 'Εμφάνιση Read More Περιγραφής', function() {
        $value = get_option('vortex_ua_show_read_more', 'yes');
        echo '<div class="flex items-center">';
        echo '<div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">';
        echo '<input type="checkbox" id="vortex_ua_show_read_more" name="vortex_ua_show_read_more" value="yes"' . checked('yes', $value, false) . ' class="vortex-toggle-ua-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />';
        echo '<label for="vortex_ua_show_read_more" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>';
        echo '</div>';
        echo '</div>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_show_reviews', 'Εμφάνιση Κριτικών', function() {
        $value = get_option('vortex_ua_show_reviews', 'yes');
        echo '<div class="flex items-center">';
        echo '<div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">';
        echo '<input type="checkbox" id="vortex_ua_show_reviews" name="vortex_ua_show_reviews" value="yes"' . checked('yes', $value, false) . ' class="vortex-toggle-ua-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />';
        echo '<label for="vortex_ua_show_reviews" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>';
        echo '</div>';
        echo '</div>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_show_map', 'Εμφάνιση Χάρτη Συνάντησης', function() {
        $value = get_option('vortex_ua_show_map', 'yes');
        echo '<div class="flex items-center">';
        echo '<div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">';
        echo '<input type="checkbox" id="vortex_ua_show_map" name="vortex_ua_show_map" value="yes"' . checked('yes', $value, false) . ' class="vortex-toggle-ua-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />';
        echo '<label for="vortex_ua_show_map" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>';
        echo '</div>';
        echo '</div>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_show_logo', 'Εμφάνιση Λογότυπου', function() {
        $value = get_option('vortex_ua_show_logo', 'yes');
        echo '<div class="flex items-center">';
        echo '<div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">';
        echo '<input type="checkbox" id="vortex_ua_show_logo" name="vortex_ua_show_logo" value="yes"' . checked('yes', $value, false) . ' class="vortex-toggle-ua-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />';
        echo '<label for="vortex_ua_show_logo" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>';
        echo '</div>';
        echo '</div>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_logo_url', 'Λογότυπο', function() {
        $value = get_option('vortex_ua_logo_url', 'https://www.webvortex.org/favicon.ico');
        echo '<input type="text" id="vortex_ua_logo_url" name="vortex_ua_logo_url" value="' . esc_attr($value) . '" class="regular-text border rounded p-2" />';
        echo '<br><img id="logo_preview" src="' . esc_url($value) . '" style="max-width:150px; margin-top:10px;" />';
        echo '<button id="upload_logo_button" class="button">Ανεβάστε Λογότυπο</button>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');
    add_settings_field('vortex_ua_walpaper_url', 'WallPaper', function() {
        $value = get_option('vortex_ua_walpaper_url', 'https://wallpapercat.com/w/full/4/c/2/17001-3840x2160-desktop-4k-mountain-wallpaper.jpg');
        echo '<input type="text" id="vortex_ua_walpaper_url" name="vortex_ua_walpaper_url" value="' . esc_attr($value) . '" class="regular-text border rounded p-2" />';
        echo '<br><img id="logo_preview" src="' . esc_url($value) . '" style="max-width:150px; margin-top:10px;" />';
        echo '<button id="upload_logo_button" class="button">Ανεβάστε Wallpaper</button>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');
    add_settings_field('vortex_ua_button_color', 'Χρώμα Brand', function() {
        $value = get_option('vortex_ua_button_color', '#FA345B');
        echo '<input type="text" name="vortex_ua_button_color" value="' . esc_attr($value) . '" class="vortex-color-picker-field border rounded p-2" data-coloris />';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_itinerary_bg_color', 'Itinerary Background Χρώμα', function() {
        $value = get_option('vortex_ua_itinerary_bg_color', '#f6f9fc');
        echo '<input type="text" name="vortex_ua_itinerary_bg_color" value="' . esc_attr($value) . '" class="vortex-color-picker-field border rounded p-2" data-coloris />';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_custom_html_inside_booking', 'HTML Booking above', function() {
        $value = get_option('vortex_ua_custom_html_inside_booking', '');
        echo '<textarea id="vortex_ua_custom_html_inside_booking" name="vortex_ua_custom_html_inside_booking" rows="10" class="large-text code">'. esc_textarea($value) .'</textarea>';
        echo '<p>Εισάγετε το HTML που θέλετε να εμφανίζεται στο πάνω μέρος πάνω του booking popup.</p>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_custom_html_section_1', 'HTML Booking below', function() {
        $value = get_option('vortex_ua_custom_html_section_1', '');
        echo '<textarea id="vortex_ua_custom_html_section_1" name="vortex_ua_custom_html_section_1" rows="10" class="large-text code">'. esc_textarea($value) .'</textarea>';
        echo '<p>Εισάγετε το HTML που θέλετε να εμφανίζεται στο κάτω μέρος πάνω του booking popup.</p>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_custom_html_section_2', 'HTML Below Ας ξεκινήσουμε', function() {
        $value = get_option('vortex_ua_custom_html_section_2', '');
        echo '<textarea id="vortex_ua_custom_html_section_2" name="vortex_ua_custom_html_section_2" rows="10" class="large-text code">'. esc_textarea($value) .'</textarea>';
        echo '<p>Εισάγετε το HTML που θέλετε να εμφανίζεται στο κάτω μέρος πάνω του κουμπιού Ας ξεκινήσουμε.</p>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_custom_html_section_3', 'HTML Above Itineraries', function() {
        $value = get_option('vortex_ua_custom_html_section_3', '');
        echo '<textarea id="vortex_ua_custom_html_section_3" name="vortex_ua_custom_html_section_3" rows="10" class="large-text code">'. esc_textarea($value) .'</textarea>';
        echo '<p>Εισάγετε το HTML που θέλετε να εμφανίζεται στο πάνω μέρος των Itinerary.</p>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_custom_html_section_4', 'HTML Below Itineraries', function() {
        $value = get_option('vortex_ua_custom_html_section_4', '');
        echo '<textarea id="vortex_ua_custom_html_section_4" name="vortex_ua_custom_html_section_4" rows="10" class="large-text code">'. esc_textarea($value) .'</textarea>';
        echo '<p>Εισάγετε το HTML που θέλετε να εμφανίζεται στο κάτω μέρος των Itinerary.</p>';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

});

add_action('admin_enqueue_scripts', function($hook_suffix) {
    wp_enqueue_script('medium-editor-js', 'https://cdn.jsdelivr.net/npm/medium-editor@latest/dist/js/medium-editor.min.js', [], null, true);
    wp_enqueue_style('medium-editor-css', 'https://cdn.jsdelivr.net/npm/medium-editor@latest/dist/css/medium-editor.min.css');

    wp_enqueue_style('coloris-css', 'https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css');
    wp_enqueue_script('coloris-js', 'https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js', [], null, true);

    wp_enqueue_media();

    // Inline script for initializing MediumEditor and Coloris
    wp_add_inline_script('coloris-js', '
        document.addEventListener("DOMContentLoaded", function() {
            Coloris({
                el: ".vortex-color-picker-field",
                theme: "default",
                themeMode: "light",
                alpha: true,
                format: "hex"
            });

            jQuery("#upload_logo_button").on("click", function(e) {
                e.preventDefault();
                var custom_uploader = wp.media({
                    title: "Επιλογή λογότυπου",
                    button: { text: "Αυτό το λογότυπο" },
                    multiple: false
                }).on("select", function() {
                    var attachment = custom_uploader.state().get("selection").first().toJSON();
                    jQuery("#vortex_ua_logo_url").val(attachment.url);
                    jQuery("#logo_preview").attr("src", attachment.url);
                }).open();
            });

            var editorOptions = {
                toolbar: {
                    allowMultiParagraphSelection: true,
                    buttons: ["bold", "italic", "underline", "anchor", "h2", "h3", "quote", "html"],
                    diffLeft: 0,
                    diffTop: -10,
                    firstButtonClass: "medium-editor-button-first",
                    lastButtonClass: "medium-editor-button-last",
                    relativeContainer: null,
                    standardizeSelectionStart: false,
                    static: false,
                    align: "center",
                    sticky: false,
                    updateOnEmptySelection: false
                },
                placeholder: {
                    text: "Εισάγετε το html content σας εδώ"
                }
            };

            var editor = new MediumEditor("#vortex_ua_custom_html_inside_booking", editorOptions);
            var editor1 = new MediumEditor("#vortex_ua_custom_html_section_1", editorOptions);
            var editor2 = new MediumEditor("#vortex_ua_custom_html_section_2", editorOptions);
            var editor3 = new MediumEditor("#vortex_ua_custom_html_section_3", editorOptions);
            var editor4 = new MediumEditor("#vortex_ua_custom_html_section_4", editorOptions);
        });
    ');
});

?>
