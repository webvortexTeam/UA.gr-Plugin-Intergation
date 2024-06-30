<?php
// Add menu item
add_action('admin_menu', 'activity_create_menu');

function activity_create_menu()
{
    $icon_svg_base64 = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMjI1LjAwMDAwMHB0IiBoZWlnaHQ9IjIyNS4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDIyNS4wMDAwMDAgMjI1LjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgo8bWV0YWRhdGE+CkNyZWF0ZWQgYnkgcG90cmFjZSAxLjEwLCB3cml0dGVuIGJ5IFBldGVyIFNlbGluZ2VyIDIwMDEtMjAxMQo8L21ldGFkYXRhPgo8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwyMjUuMDAwMDAwKSBzY2FsZSgwLjEwMDAwMCwtMC4xMDAwMDApIgpmaWxsPSIjMDAwMDAwIiBzdHJva2U9Im5vbmUiPgo8cGF0aCBkPSJNOTg1IDIxNDQgYy0xMSAtMyAtNDYgLTEwIC03OSAtMTYgLTUwIC0xMCAtNjAgLTE1IC02OCAtMzkgLTcgLTIxCi0xOSAtMjkgLTUzIC0zOCBsLTQ0IC0xMSAxMyAtMzMgYzE4IC00MyAxMiAtNTQgLTI5IC00OSAtMjggMyAtMzYgMTAgLTQ1IDM3Ci0xMCAzMSAtMTMgMzIgLTQ1IDI1IC0zMyAtNyAtNzcgLTM2IC0xODYgLTExOSAtNDQgLTMzIC00NyAtMzkgLTQyIC02OCA2IC0zOAotMyAtODMgLTE3IC04MyAtNiAwIC0xMCAxMCAtMTAgMjMgMCAyNiAtMTcgNDcgLTM3IDQ3IC0xMCAwIC0xMSAtNSAtNSAtMTcgMTEKLTIxIDUgLTQzIC0xMSAtNDMgLTE1IDAgLTg2IC0xMDUgLTEyNSAtMTg1IC0zNyAtNzYgLTU1IC0xMzEgLTU3IC0xNzQgLTEgLTI3CjQgLTM1IDI1IC00MyAyNCAtOSAyNSAtMTAgNSAtMjQgLTExIC04IC0yNiAtMTIgLTM1IC04IC04IDMgLTIxIDggLTI4IDExIC0xNAo2IC0xNiAtMTMgLTIgLTIyIDYgLTMgMTAgLTE5IDEwIC0zNSAwIC0xNiAtNCAtMzIgLTEwIC0zNSAtMTUgLTEwIC0xMiAtMjEzIDUKLTMwMSA4IC00NCAyMyAtMTA2IDMzIC0xMzkgbDE4IC02MCAxOSAyOSBjMTMgMjAgMTkgNDUgMTggODIgLTEgMzAgMiA1NCA3IDU0CjUgMCAxNSAzIDI0IDYgMTEgNCAxNiAwIDE2IC0xNSAwIC0xMyAxMSAtMjYgMjkgLTM2IDM3IC0xOSA0NyAtNSAyMSAyOCAtMjMKMjkgLTI1IDM3IC03IDM4IDE1IDAgMTgyIDIxOSAxNjcgMjE5IC0xMSAwIC0xMyAyOSAtNCA1NCAzIDkgMTUgMTYgMjUgMTYgMTAKMCAxOSA1IDE5IDExIDAgNiA3IDkgMTUgNSAxMSAtNCAzOSAyNyA5NyAxMDQgNDYgNjEgOTAgMTEwIDk5IDExMCA4IDAgMjYgMTEKMzggMjQgbDIyIDI0IC0zMSAyMiBjLTI0IDE3IC0yOSAyNSAtMTggMzEgNyA1IDIxIDYgMzAgMyAyNCAtNyAxNjMgMTc3IDE0MgoxODkgLTggNCAtMTQgMTcgLTE0IDI5IDAgMTYgMyAxOCAxMyAxMCAyMCAtMTcgNTAgLTQgNzUgMzMgMTkgMjcgMzEgMzQgNjUgMzcKMjkgNCA0OCAwIDYwIC0xMSAxNSAtMTIgMjAgLTEzIDI2IC0yIDYgMTAgMzAgMTIgODcgMTAgNzQgLTQgNzkgLTMgNzkgMTYgMAozNiAxNSA0OSAzNSAzMCA5IC04IDI0IC0xMyAzNCAtOSAxMiA0IDE2IDEgMTYgLTE1IDAgLTE0IDYgLTIxIDE5IC0yMSAyMyAwCjY2IC02OSA4NyAtMTQxIDEzIC00NCAyMSAtNTMgNTUgLTY4IDMyIC0xMyAzOSAtMjEgMzkgLTQ1IDAgLTMwIDEwNiAtMjMzIDE0MwotMjczIDE3IC0xOSAyNiAtMjEgNjUgLTE2IGw0NiA2IC0yMiAtMzUgYy0xMiAtMTkgLTIyIC00NiAtMjIgLTU4IDAgLTEyIDIzCi02NSA1MiAtMTE3IDQwIC03MiA2MCAtOTggODUgLTExMCAzOCAtMTggNDEgLTI4IDEyIC00NCBsLTIxIC0xMSAyNSAtMjYgYzEzCi0xNSAzNyAtNTEgNTMgLTc5IDE4IC0zMyAzNiAtNTMgNDcgLTUzIDEzIDAgMTYgLTYgMTIgLTIyIC0zIC0xMyAwIC0zMSA4IC00MgoxMiAtMTcgMTUgLTE0IDM1IDQwIDM0IDkxIDUxIDE2NyA2MiAyNzUgMTQgMTQzIC0xIDIyOSAtMzggMjE0IC0xOSAtNyAtNDIgMTMKLTQyIDM2IDAgMTMgOSAyMSAyOSAyNSAyNyA2IDI5IDkgMjYgNDggLTcgOTAgLTExMCAzMjEgLTEzOSAzMTAgLTcgLTMgLTE2IDIKLTE5IDEwIC00IDkgLTE2IDE2IC0yNyAxNiAtMjYgMCAtMjYgMjUgMCA0MyAxOCAxMiAxOCAxNiA2IDI2IC04IDcgLTIzIDExCi0zMyA5IC0xMiAtMiAtMjEgNyAtMjggMjkgLTE0IDQxIC02MCA5MCAtMTQ3IDE1NSAtMzggMjcgLTY4IDU0IC02OCA1OSAwIDE5Ci0yNyA4IC0zOCAtMTUgLTYgLTEzIC0yMiAtMjYgLTM3IC0yOCAtMjEgLTUgLTI1IC0yIC0xOSAxMSAxOCA0MCAxMCA2NSAtMjYKODMgLTU5IDI5IC0xNTYgNTkgLTIzOCA3NCAtNzAgMTIgLTI3OSAxNyAtMzI3IDh6IG00MTcgLTYyIGMxMTQgLTM0IDE0OCAtNTQKMTQ4IC04NiAwIC0xMiA5IC0zMSAxOSAtNDAgMTYgLTE1IDI1IC0xNiA1OSAtNiAzNyAxMiA0MSAxMSA4MyAtMTkgNTEgLTM3CjEzOSAtMTIwIDEzOSAtMTMxIDAgLTUgMTIgLTIyIDI2IC0zOSAxNCAtMTcgMjIgLTM2IDE5IC00MiAtNCAtNiAxOSAtMzUgNTMKLTY3IDYwIC01NyA4NSAtOTkgMTIzIC0yMTEgMjQgLTcwIDI0IC04MCAtNSAtMTIwIC0yMSAtMjggLTIzIC0zNyAtMTQgLTYyIDUKLTE2IDIyIC0zNSAzNiAtNDIgMjYgLTEyIDI3IC0xNiAzMCAtMTA3IDQgLTEzMCAtMzMgLTMxNiAtNjEgLTMwNyAtNyAxIC0zMQozMiAtNTQgNjcgLTMwIDQ1IC00MSA3MCAtMzcgODYgNSAxNyAtNSAzNCAtMzggNzEgLTIzIDI2IC00OSA1OSAtNTcgNzIgLTY2CjExNyAtNjQgMTEzIC01MiAxNDYgMjQgNjcgMTcgNzcgLTYzIDEwMSAtMjMgNyAtNDIgMzEgLTkyIDExOSAtMzUgNjAgLTY0IDExOQotNjQgMTMwIDAgMTEgLTIyIDQ0IC01MCA3MiAtMjcgMjggLTUwIDYwIC01MCA3MCAwIDEwIC0xOCA0NiAtNDAgODAgLTMwIDQ4Ci01MCA2OCAtODkgODggLTU5IDI5IC01NyAyOSAtOTYgMSAtMjYgLTE4IC00NCAtMjEgLTE1MiAtMjIgbC0xMjIgLTEgLTQzIC0zNApjLTI0IC0xOSAtNTUgLTQwIC03MCAtNDcgLTIzIC05IC0yOCAtMTggLTI4IC00NSAwIC00MiAtNzUgLTE0NiAtMTI3IC0xNzcKLTMyIC0xOSAtMzUgLTIzIC0yOSAtNTUgNiAtMjkgMyAtMzYgLTE4IC01MCAtMTMgLTkgLTU2IC01OCAtOTYgLTExMCAtNDMgLTU2Ci04MyAtOTggLTEwMSAtMTA1IC00MCAtMTcgLTY5IC01NiAtNjkgLTkzIDAgLTIwIC0xNyAtNTIgLTU2IC0xMDQgLTc0IC05OAotMTAwIC0xMjMgLTEyOSAtMTIzIC0xNCAwIC0zNCAtOSAtNDYgLTIxIC0yNyAtMjcgLTM3IC0xNiAtNDkgNTUgLTI0IDE1MiAtMgozMDIgNTIgMzQ2IDIyIDE3IDIyIDE4IDQgNDUgLTI1IDM3IC0xNyA4NyAyOCAxODEgNDAgODMgMTEzIDE5MyAxMzAgMTk1IDYgMQoyMCAwIDMyIDAgMjggLTMgNDcgMjMgNTMgNzIgNSAzNSAxNCA0OCA2NCA4OCAzMSAyNSA3MyA1NCA5MSA2NCBsMzUgMTkgMzgKLTI3IGMyMiAtMTUgNTAgLTI3IDYzIC0yNyAzMCAwIDYwIDI3IDYwIDU1IDAgMjkgODQgMTAwIDEzNiAxMTQgNjQgMTcgODIgMTkKMjM0IDE1IDEyNSAtMiAxNTggLTcgMjQyIC0zMnoiLz4KPHBhdGggZD0iTTExNDMgMTQxMyBjLTcgLTIgLTEzIC0xNiAtMTMgLTMwIDAgLTE0IC05IC0zNyAtMjAgLTUzIGwtMTkgLTI3IDI0Ci0xMSBjMzkgLTE4IDI4IC00NCAtMTggLTQ2IC0xMyAtMSAtMzIgLTE3IC00OCAtNDEgLTE1IC0yMiAtNDAgLTQ0IC01OCAtNTAKLTIxIC03IC01OCAtNDMgLTExNSAtMTE1IC03MCAtODYgLTg0IC0xMDkgLTc5IC0xMzAgNCAtMjEgMCAtMjggLTIxIC0zOCAtMTQKLTYgLTI2IC0xNyAtMjYgLTIzIDAgLTYgLTcgLTkgLTE1IC01IC05IDMgLTMxIC0xNSAtNjEgLTUyIC0zMSAtMzcgLTUzIC01NgotNjUgLTU0IC0yMCAzIC00NCAtMzIgLTM2IC01MiAzIC03IC0xNiAtNDAgLTQyIC03MiAtNTkgLTcyIC02OCAtOTQgLTQxIC05NAoyOSAwIDI3IC00NiAtMiAtNTMgLTEyIC00IC0yOCAtMiAtMzQgNCAtMTUgMTIgLTQwIDEgLTY3IC0yOSAtMTcgLTE4IC0xNSAtMjEKMzUgLTY4IDI5IC0yOCA4MyAtNzEgMTE5IC05NyAzNyAtMjYgNjkgLTU2IDcyIC02NyA3IC0yOSAyMSAtMjUgMzggMTIgMTkgNDIKMzUgNDYgNDIgMTAgOCAtNDAgMjggLTU3IDg5IC03NyAyOSAtMTAgNTggLTI0IDY1IC0zMiAxNSAtMTkgMjggMiAyNCAzOSAtMQoxNiAxIDI4IDYgMjggMTUgMCAzNCAtNDEgMjggLTYxIC00IC0xMyAtMiAtMTkgOCAtMTkgOSAwIDE3IDUgMTkgMTIgMyA3IDEyIDYKMzAgLTYgMjMgLTE1IDUwIC0xNyAxOTIgLTE1IDkxIDEgMTY2IDYgMTY2IDEwIDAgNSAtOCAyNCAtMTcgNDQgLTIwIDQxIC0xNQo0OSAyMSAzNiAxNiAtNyAyNiAtMTggMjYgLTMwIDAgLTExIDkgLTIzIDIwIC0yNiA0MCAtMTMgMjYwIDc1IDI2MCAxMDQgMCA5IDUKMjMgMTAgMzEgOCAxMyAxMyAxMyAzMCAyIDE4IC0xMSAyNyAtOCA3OCAzMiAzMSAyNCA3NCA2MiA5NSA4MyBsMzcgMzkgLTYwIDI5CmMtMzMgMTYgLTYwIDM0IC02MCA0MSAwIDE0IC0xMjggNyAtMTQ3IC04IC0xNCAtMTEgLTczIC0xIC03MyAxMiAwIDUgOSAxMSAxOQoxNSAxNiA1IDM5IDUzIDI5IDU5IC0yIDEgLTY4IDM1IC0xNDcgNzYgLTkwIDQ1IC0xNDYgNjkgLTE0OSA2MiAtMiAtNyAtMjAKLTEyIC0zOSAtMTIgLTI3IDAgLTM0IDQgLTMwIDE0IDQgOSAtMSAxNyAtMTEgMjAgLTkgMyAtMjQgMTggLTMzIDM0IC0xMCAxOAotMzggMzggLTgyIDU3IC0zNyAxNyAtNjcgMzIgLTY3IDM1IDAgMyAzMyAzIDczIC0xIDUzIC01IDc1IC00IDgyIDUgMTIgMTUgMzUKMTAgMzUgLTcgMCAtOCAyNyAtMTIgODYgLTEyIDgyIDAgODcgLTEgOTUgLTI0IDkgLTIzIDEwIC0yMyAzMCAtNSAxNCAxMyAzMwoxNyA2NCAxNSA0OCAtMyA1MyAzIDMwIDMzIC04IDExIC0xNSAzMCAtMTUgNDIgMCAxNyAtNCAyMSAtMjAgMTcgLTE0IC00IC0yMAowIC0yMCAxMiAwIDI4IC0xMTMgMjA4IC0xNDEgMjI0IC0xNSA4IC0zNSAyOCAtNDQgNDMgLTkgMTUgLTIyIDI4IC0yOSAyOCAtNwowIC0yMSAxNyAtMzAgMzkgLTE0IDMxIC0xNiA0MyAtNiA2MCA5IDE4IDcgMjggLTEwIDU2IC0yMCAzMyAtMzggNDAgLTY3IDI4egptNDcgLTkzIGMwIC01OCAxMyAtODEgODMgLTE1MSA2MiAtNjEgMTQ3IC0xODAgMTYxIC0yMjYgNCAtMTEgMTMgLTI3IDIxIC0zMwoyMCAtMTcgMTkgLTUwIC0xIC01MCAtMzggMCAtMjEwIDIzIC0yMjQgMzAgLTE5IDEwIC0xOTEgLTcgLTIwNCAtMjAgLTEyIC0xMgoxNiAtNDQgNjkgLTc4IDIyIC0xNCA1NiAtNDIgNzUgLTYyIDQ0IC00OCA3NSAtNjMgMTExIC01NiAyMiA0IDU1IC04IDEzOSAtNTAKMTE0IC01NyAxMzIgLTczIDExMCAtOTkgLTEwIC0xMiAtOSAtMjAgMyAtMzkgMTggLTI3IDYwIC0zOCA5NSAtMjUgNDAgMTUgOTUKMTAgMTQwIC0xMyAyMyAtMTMgNDIgLTI5IDQyIC0zNSAwIC0yOSAtOTMgLTEwMCAtMTQwIC0xMDggLTIyIC00IC01MCAtMjEgLTc1Ci00NSAtNDMgLTQxIC0xMTMgLTc2IC0xNzMgLTg2IC0zMSAtNSAtNDEgLTIgLTYxIDIwIC00MiA0NSAtOTQgMjcgLTk2IC0zMiBsMAotMjcgLTEzNCAtMyBjLTEyMSAtMiAtMTM3IC0xIC0xNjkgMTkgLTIwIDExIC00MiAzMCAtNTAgNDAgLTE3IDI0IC0zOCAyNCAtNTgKMCAtMjcgLTMxIC04MSAtMTIgLTEzMSA0NiAtMzggNDMgLTQ0IDQ3IC02MiAzNiAtMTIgLTcgLTI1IC0xMyAtMzEgLTEzIC0xNiAwCi0xMjQgNzggLTE2NSAxMjAgLTMzIDM0IC0zNyA0MiAtMjUgNTAgOCA1IDI0IDEwIDM2IDEwIDExIDAgMzEgMTEgNDMgMjQgMjAKMjEgMjIgMjkgMTMgNTUgLTkgMjcgLTcgMzUgMjkgODIgMjEgMjkgMzkgNTcgMzkgNjIgMCAxMyAxNTkgMTY1IDE3OSAxNzIgMjAKNiA1MSA1NiA1MSA4MSAwIDE0IDU2IDkzIDgwIDExNCAzIDMgMTkgMjIgMzUgNDMgMTUgMjEgNDIgNDUgNTkgNTQgMTcgOSA1OQo0MSA5MyA3MiA2MyA1NSA2MyA1NiA1NyA5OCAtOCA1MSAwIDc1IDIxIDY3IDEwIC00IDE1IC0xOSAxNSAtNDR6Ii8+CjwvZz4KPC9zdmc+Cg==';
    add_menu_page('UA Activities', 'UA Activities', 'administrator', 'activity_settings', 'activity_settings_page', $icon_svg_base64);
    add_action('admin_init', 'register_activity_settings');
}

function register_activity_settings()
{
    register_setting('activity-settings-group', 'activity_api_key');
    register_setting('activity-settings-group', 'activity_host_url');
    register_setting('activity-settings-group', 'activity_api_locale', array('default' => 'gr'));
    register_setting('activity-settings-group', 'activity_host_url_label');
    register_setting('activity-settings-group', 'activity_api_ok_host');
    register_setting('activity-settings-group', 'activity_api_fail_host');
    $base_url = home_url();

    if (get_option('activity_api_ok_host') === false) {
        update_option('activity_api_ok_host', $base_url . '/activity-booking-confirmed');
    }

    if (get_option('activity_api_fail_host') === false) {
        update_option('activity_api_fail_host', $base_url . '/activity-booking-failed');
    }
}

// Settings page
function activity_settings_page()
{
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ρυθμίσεις Unlimited Adrenaline</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="max-w-4xl mx-auto mt-12 p-8 bg-white rounded-lg shadow-lg">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2 mb-8 md:mb-0 flex flex-col items-center text-center md:text-left">
                <img style="border: none;" src="https://www.hosthub.com/wp-content/uploads/2023/08/Unlimited-Adrenaline-Horizontal-White-new2.png" alt="Logo" class="mb-4 w-3/4 md:w-full">
                <h1 class="text-xl font-semibold mb-2 text-red-600">Ρυθμίσεις Unlimited Adrenaline</h1>
                <hr class="mb-4 w-full">
                <p class="text-gray-600">Διαμορφώστε τις ρυθμίσεις δραστηριότητάς σας εδώ. Βεβαιωθείτε ότι έχετε συμπληρώσει όλα τα απαιτούμενα πεδία και επιλέξτε τις κατάλληλες επιλογές για να διασφαλίσετε ότι η διασύνδεση σας λειτουργεί σωστά.</p>
                 <p class="text-gray-600 font-bold">Quicklinks</p>
                <a class="text-red-600" href="/wp-admin/admin.php?page=import-activities">😊Εισαγωγή δραστηριοτήτων </a>
                <a class="text-red-600" href="/wp-admin/admin.php?page=import-activities">🎨Διαμόρφωση styling </a>
                <a class="text-red-600" href="/wp-admin/admin.php?page=import-activities">📄Documenation </a>
                <a class="text-red-600" href="https://www.webvortex.org/contact">🛠️Υποστήριξη </a>

            </div>
            <div class="md:w-1/2 bg-gray-100 p-6 rounded-lg">
                <form method="post" action="options.php" class="space-y-6">
                    <?php settings_fields('activity-settings-group'); ?>
                    <?php do_settings_sections('activity-settings-group'); ?>
                    <div class="space-y-4">
                        <div class="relative group">
                            <label for="activity_api_key" class="block text-gray-700">API Κλειδί
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Εισάγετε το API key που έχετε παραλάβει απο την πλατφόρμα μας">[?]</span>
                            </label>
                            <input type="text" id="activity_api_key" name="activity_api_key" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="●●●●●●●●●●●●" />
                            <div class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                Εισάγετε το API key που έχετε παραλάβει απο την πλατφόρμα μας
                            </div>
                        </div>
                        <div class="relative group">
                            <label for="activity_host_url" class="block text-gray-700">Σύνδεσμος API
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Εισάγετε το staging ή production URL που έχετε λάβει">[?]</span>
                            </label>
                            <input type="text" id="activity_host_url" name="activity_host_url" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="<?php echo esc_attr(get_option('activity_host_url')); ?>" />
                            <div class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                Εισάγετε το staging ή production URL που έχετε λάβει
                            </div>
                        </div>
                        <div class="relative group">
                            <label for="activity_host_url_label" class="block text-gray-700">Whitelabel ID
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Εισάγετε το whitelabel ID σας">[?]</span>
                            </label>
                            <input type="text" id="activity_host_url_label" name="activity_host_url_label" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="●●●" />
                            <div class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                Εισάγετε το whitelabel ID σας
                            </div>
                        </div>
                        <div class="relative group">
                            <label for="activity_api_locale" class="block text-gray-700">Γλώσσα Εισαγωγής
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Select API locale">[?]</span>
                            </label>
                            <select id="activity_api_locale" name="activity_api_locale" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="gr" <?php selected(get_option('activity_api_locale'), 'gr'); ?>>gr</option>
                                <option value="en" <?php selected(get_option('activity_api_locale'), 'en'); ?>>en</option>
                            </select>
                            <div class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                               Επιλέξετε την γλώσσα που θέλετε να γίνουν εισαγωγή οι δραστηριότητες.
                            </div>
                        </div>
                        <div class="relative group" style="display: none;">

                            <label for="activity_api_ok_host" class="block text-gray-700">Success Page URL
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Enter the success page URL">[?]</span>
                            </label>
                            <input type="url" id="activity_api_ok_host" name="activity_api_ok_host" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="<?php echo esc_attr(get_option('activity_api_ok_host')); ?>" />
                            <div class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                Enter the success page URL
                            </div>
                        </div>
                        <div class="relative group" style="display: none;">
                            <label for="activity_api_fail_host" class="block text-gray-700">Error Page URL
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Enter the error page URL">[?]</span>
                            </label>
                            <input type="url" id="activity_api_fail_host" name="activity_api_fail_host" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="<?php echo esc_attr(get_option('activity_api_fail_host')); ?>" />
                            <div class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                Enter the error page URL
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="submit" id="submit" class="bg-red-500 text-white py-3 px-6 rounded-md hover:bg-red-600 transition duration-200 ease-in-out">
                            Αποθήκευση Αλλαγών
                        </button>

                </form>
            </div>
        </div>
    </div>
</body>
</html>


    <?php
}