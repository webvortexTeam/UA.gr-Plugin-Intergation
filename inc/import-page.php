<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
add_action('admin_menu', 'unlimited_andrenaline_admin_menu');
function unlimited_andrenaline_admin_menu()
{
    add_submenu_page('activity_settings', 'Εισαγωγή δραστηριοτήτων', 'Εισαγωγή δραστηριοτήτων', 'manage_options', 'import-activities', 'unlimited_andrenaline_import_activities_page');
    
}

function unlimited_andrenaline_import_activities_page()
{
    ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Εισαγωγή δραστηριοτήτων</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        </head>
        <body class="bg-gray-50 text-gray-900 font-sans">
            <div class="min-h-screen flex items-center justify-center">
                <div class="bg-white p-10 rounded-lg shadow-lg w-full max-w-md border border-red-500">
                    <h1 class="text-xl font-bold mb-6 text-center text-red-600">Εισαγωγή / Ανανέωση δραστηριοτήτων</h1>
                    <form method="post" action="" class="flex flex-col">
                        <input type="hidden" name="unlimited_andrenaline_import_activities" value="1">
                        <button type="submit" class="py-2 px-4 bg-red-500 hover:bg-red-600 rounded-lg text-white font-semibold transition-colors duration-300">ΕΙΣΑΓΩΓΗ / ΑΝΑΝΕΩΣΗ</button>
                    </form>
                    <?php
                    if (isset($_POST['unlimited_andrenaline_import_activities'])) {
                        unlimited_andrenaline_import_activities();
                    }
                    ?>
                    <div id="import-progress" class="mt-6">
                        <p class="text-lg"><strong>Δραστηριότητες που βρέθηκαν:</strong> <span id="activities-found" class="text-red-500"><?php echo get_option('activities_found', 0); ?></span></p>
                    </div>
                </div>
            </div>
        </body>
        </html>

    <?php
}