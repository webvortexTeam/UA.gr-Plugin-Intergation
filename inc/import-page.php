<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('admin_menu', 'unlimited_andrenaline_admin_menu');
function unlimited_andrenaline_admin_menu()
{
    add_submenu_page('activity_settings', 'Εισαγωγή δραστηριοτήτων', 'Εισαγωγή δραστηριοτήτων', 'manage_options', 'import-activities', 'unlimited_andrenaline_import_activities_page');
}

function unlimited_andrenaline_import_activities_page() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Εισαγωγή δραστηριοτήτων</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body class="bg-gray-50 text-gray-900 font-sans">
        <div class="min-h-screen flex items-center justify-center">
            <div class="bg-white p-10 rounded-lg shadow-lg w-full max-w-md border border-red-500">
                <h1 class="text-xl font-bold mb-6 text-center text-red-600">Εισαγωγή / Ανανέωση δραστηριοτήτων</h1>
                <button id="start-import" class="py-2 px-4 bg-red-500 hover:bg-red-600 rounded-lg text-white font-semibold transition-colors duration-300">ΕΙΣΑΓΩΓΗ / ΑΝΑΝΕΩΣΗ</button>
                
                <div id="import-progress" class="mt-6 hidden">
                    <p class="text-lg"><strong>Κατάσταση Εισαγωγής:</strong> <span id="progress-text">0%</span></p>
                </div>
            </div>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Trigger import when the button is clicked
                $('#start-import').on('click', function(e) {
                    e.preventDefault();
                    $('#import-progress').show();

                    // Step 1: Delete all activity posts
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'delete_all_activity_posts'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Step 2: Start the import process after deletion
                                processImport(0);
                            } else {
                                alert('An error occurred during deletion.');
                            }
                        }
                    });

                    function processImport(batch) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'unlimited_andrenaline_import_activities',
                                batch: batch
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#progress-text').text(response.data.progress + '%');
                                    if (response.data.next_batch) {
                                        processImport(response.data.next_batch);
                                    } else {
                                        alert('Ολοκληρώθηκε με επιτυχία!');
                                    }
                                } else {
                                    alert('An error occurred during the import.');
                                }
                            }
                        });
                    }
                });

                // Automatically trigger the import every hour (3600000 milliseconds)
                setInterval(function() {
                    $('#start-import').trigger('click');
                }, 3600000); // 1 hour
            });
        </script>

    </body>
    </html>
    <?php
}

function delete_all_activity_posts() {
    $activity_posts = get_posts(array(
        'post_type' => 'activity',
        'numberposts' => -1,
        'fields' => 'ids'
    ));

    foreach ($activity_posts as $post_id) {
        wp_delete_post($post_id, true);
    }

    wp_send_json_success(array('message' => 'All activity posts deleted.'));
}

add_action('wp_ajax_delete_all_activity_posts', 'delete_all_activity_posts');