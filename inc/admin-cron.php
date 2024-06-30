<?php
if (!defined('ABSPATH')) exit;

function uac_cron_activation() {
    $enabled = get_option('uac_cron_enabled', 'no');
    if ($enabled === 'yes') {
        $schedule = get_option('uac_cron_schedule', 'daily');
        if (!wp_next_scheduled('uac_cron_event')) {
            wp_schedule_event(time() + 60, $schedule, 'uac_cron_event'); // Schedule to run in 1 minute to avoid immediate time issues
        }
    }
}

function uac_cron_deactivation() {
    $timestamp = wp_next_scheduled('uac_cron_event');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'uac_cron_event');
    }
}

add_filter('cron_schedules', function($schedules) {
    $schedules['every_minute_vortex'] = [
        'interval' => 60,
        'display'  => __('Every 60 Seconds')
    ];
    return $schedules;
});

// Cron Event Hook
add_action('uac_cron_event', 'unlimited_andrenaline_import_activities_cron');

function unlimited_andrenaline_import_activities_cron() {
    if (function_exists('unlimited_andrenaline_import_activities')) {
        unlimited_andrenaline_import_activities();
    }
}

// Settings Page
add_action('admin_menu', function() {
    add_submenu_page('activity_settings', 'Αυτοματοποίηση', 'Αυτοματοποίηση', 'manage_options', 'cron-activities', 'uac_cron_settings_page');
});

function uac_cron_settings_page() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Unlimited Adrenaline Cron Settings</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-gray-50 text-gray-900 font-sans">
        <div class="min-h-screen flex items-center justify-center">
            <div class="bg-white p-10 rounded-lg shadow-lg w-full max-w-2xl border border-red-500">
                <h1 class="text-3xl font-bold mb-6 text-center text-red-600">Ρυθμίσεις Cron</h1>
                <form method="post" action="options.php" class="space-y-6">
                    <?php
                    settings_fields('uac_cron_options');
                    do_settings_sections('uac-cron');
                    ?>
                    <p>* Η επιλογή 60 δευτερολέπτων πρέπει να επιλέγετε μόνο για δοκιμαστική χρήση και όχι για περισσότερο απο 2 λεπτά.</p>
                    <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-colors duration-300">Αποθήκευση επιλογών cron</button>
                </form>
                <h2 class="text-2xl font-semibold mt-8 mb-4 text-red-600">Log</h2>
                <pre class="bg-gray-100 p-4 rounded-lg border border-gray-300"><?php echo implode("\n", get_option('uac_cron_log', [])); ?></pre>
            </div>
        </div>
    </body>
    </html>
    <?php
}

add_action('admin_init', function() {
    register_setting('uac_cron_options', 'uac_cron_enabled');
    register_setting('uac_cron_options', 'uac_cron_schedule', [
        'type' => 'string',
        'default' => 'daily',
    ]);

    add_settings_section('uac_cron_main', '', function() {
        echo '<p>Αυτοματοποιήστε την ΑΝΑΝΕΩΣΗ ή ΕΙΣΑΓΩΓΗ των δραστηριοτήτων σας. <br></br> Βεβαιωθείτε οτι η λειτουργία CRON είναι ενεργοποιημένη στην εγκατάσταση σας.</p>';
    }, 'uac-cron');

    add_settings_field('uac_cron_enabled', 'Ενεργοποίηση Αυτοματοποίησης Cron', function() {
        $enabled = get_option('uac_cron_enabled', 'no');
        ?>
        <input type="checkbox" name="uac_cron_enabled" value="yes" <?php checked($enabled, 'yes'); ?> class="form-checkbox text-red-500"> Ενεργοποίηση
        <?php
    }, 'uac-cron', 'uac_cron_main');

    add_settings_field('uac_cron_schedule', 'Cron Schedule', function() {
        $schedule = get_option('uac_cron_schedule', 'daily');
        ?>
        <select name="uac_cron_schedule" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="every_minute_vortex" <?php selected($schedule, 'every_minute_vortex'); ?>>Κάθε 60 δευτερόλεπτα</option>
            <option value="hourly" <?php selected($schedule, 'hourly'); ?>>Κάθε 1 ώρα</option>
            <option value="twicedaily" <?php selected($schedule, 'twicedaily'); ?>>2 φορές την ημέρα</option>
            <option value="daily" <?php selected($schedule, 'daily'); ?>>Καθημερινά</option>
        </select>
        <?php
    }, 'uac-cron', 'uac_cron_main');
});


// Adjust Cron on Settings Update
add_action('update_option_uac_cron_enabled', 'uac_cron_settings_update', 10, 2);
add_action('update_option_uac_cron_schedule', 'uac_cron_settings_update', 10, 2);

function uac_cron_settings_update($old_value, $value) {
    $enabled = get_option('uac_cron_enabled', 'no');
    $schedule = get_option('uac_cron_schedule', 'daily');

    $timestamp = wp_next_scheduled('uac_cron_event');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'uac_cron_event');
    }

    if ($enabled === 'yes') {
        wp_schedule_event(time() + 60, $schedule, 'uac_cron_event');
    }
}