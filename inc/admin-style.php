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
        'vortex_ua_show_breadcrumbs', 'vortex_ua_show_logo', 'vortex_ua_logo_url', 
        'vortex_ua_button_color', 'vortex_ua_itinerary_bg_color'
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

    add_settings_field('vortex_ua_button_color', 'Χρώμα Background Κύριου Κουμπιού', function() {
        $value = get_option('vortex_ua_button_color', '#000000');
        echo '<input type="text" name="vortex_ua_button_color" value="' . esc_attr($value) . '" class="vortex-color-picker-field border rounded p-2" data-coloris />';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');

    add_settings_field('vortex_ua_itinerary_bg_color', 'Itinerary Background Χρώμα', function() {
        $value = get_option('vortex_ua_itinerary_bg_color', '#f6f9fc');
        echo '<input type="text" name="vortex_ua_itinerary_bg_color" value="' . esc_attr($value) . '" class="vortex-color-picker-field border rounded p-2" data-coloris />';
    }, 'vortex_ua_styling_settings', 'vortex_ua_styling_section');
});
add_action('admin_enqueue_scripts', function($hook_suffix) {
    
    wp_enqueue_style('coloris-css', 'https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css');
    wp_enqueue_script('coloris-js', 'https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js', [], false, true);
    
    wp_enqueue_media();
    
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
        });
    ');
});

