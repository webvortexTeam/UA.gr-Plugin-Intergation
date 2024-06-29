<?php
// Add menu item
add_action('admin_menu', 'activity_create_menu');

function activity_create_menu()
{
    add_menu_page('Activity Settings', 'Activity', 'administrator', __FILE__, 'activity_settings_page', 'dashicons-admin-generic');
    add_action('admin_init', 'register_activity_settings');
}

function register_activity_settings()
{
    register_setting('activity-settings-group', 'activity_api_key');
    register_setting('activity-settings-group', 'activity_host_url');
    register_setting('activity-settings-group', 'activity_api_locale', array('default' => 'ge'));
    register_setting('activity-settings-group', 'activity_host_url_label');
    register_setting('activity-settings-group', 'activity_api_ok_host');
    register_setting('activity-settings-group', 'activity_api_fail_host');

}

// Settings page
function activity_settings_page()
{
    ?>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="wrap">
        <div class="wrap max-w-4xl mx-auto mt-12 p-8 bg-white  rounded-lg flex flex-col md:flex-row">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <img src="https://www.hosthub.com/wp-content/uploads/2023/08/Unlimited-Adrenaline-Horizontal-White-new2.png"
                    alt="Logo" class="mb-4">
                <h1 class="text-3xl font-semibold mb-2 text-gray-800">Activity Settings</h1>
                <hr class="border-gray-300 mb-4" style="border-width: 1px;">
                <p class="text-gray-600">Configure your activity settings here. Make sure to fill in all the required fields
                    and select the appropriate options to ensure your application functions correctly.</p>
            </div>
            <div class="md:w-1/2 bg-gray-100 p-6 rounded-lg">
                <form method="post" action="options.php">
                    <?php settings_fields('activity-settings-group'); ?>
                    <?php do_settings_sections('activity-settings-group'); ?>
                    <table class="form-table w-full">
                        <tr valign="top" class="mb-6 relative group">
                            <th scope="row" class="text-left pr-6 py-3 text-gray-700">
                                API Key
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Enter your API Key">[?]</span>
                            </th>
                            <td>
                                <input type="text" name="activity_api_key"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    value="<?php echo esc_attr(get_option('activity_api_key')); ?>" />
                                <div
                                    class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                    Enter your API Key
                                </div>
                            </td>
                        </tr>
                        <tr valign="top" class="mb-6 relative group">
                            <th scope="row" class="text-left pr-6 py-3 text-gray-700">
                                API URL
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Enter your API URL">[?]</span>
                            </th>
                            <td>
                                <input type="text" name="activity_host_url"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    value="<?php echo esc_attr(get_option('activity_host_url')); ?>" />
                                <div
                                    class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                    Enter your API URL
                                </div>
                            </td>
                        </tr>
                        <tr valign="top" class="mb-6 relative group">
                            <th scope="row" class="text-left pr-6 py-3 text-gray-700">
                                API WHITELABEL
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Enter your API Whitelabel">[?]</span>
                            </th>
                            <td>
                                <input type="text" name="activity_host_url_label"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    value="<?php echo esc_attr(get_option('activity_host_url_label')); ?>" />
                                <div
                                    class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                    Enter your API Whitelabel
                                </div>
                            </td>
                        </tr>
                        <tr valign="top" class="mb-6 relative group">
                            <th scope="row" class="text-left pr-6 py-3 text-gray-700">
                                API Locale
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Select API locale">[?]</span>
                            </th>
                            <td>
                                <select name="activity_api_locale"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="ge" <?php selected(get_option('activity_api_locale'), 'ge'); ?>>ge
                                    </option>
                                    <option value="en" <?php selected(get_option('activity_api_locale'), 'en'); ?>>en
                                    </option>
                                </select>
                                <div
                                    class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                    Select API locale
                                </div>
                            </td>
                        </tr>
                        <tr valign="top" class="mb-6 relative group">
                            <th scope="row" class="text-left pr-6 py-3 text-gray-700">
                                Success Page URL
                                <span class="ml-2 text-gray-400 cursor-pointer"
                                    title="Enter the success page URL">[?]</span>
                            </th>
                            <td>
                                <input type="url" name="activity_api_ok_host"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    value="<?php echo esc_attr(get_option('activity_api_ok_host')); ?>" />
                                <div
                                    class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                    Enter the success page URL
                                </div>
                            </td>
                        </tr>
                        <tr valign="top" class="mb-6 relative group">
                            <th scope="row" class="text-left pr-6 py-3 text-gray-700">
                                Error Page URL
                                <span class="ml-2 text-gray-400 cursor-pointer" title="Enter the error page URL">[?]</span>
                            </th>
                            <td>
                                <input type="url" name="activity_api_fail_host"
                                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    value="<?php echo esc_attr(get_option('activity_api_fail_host')); ?>" />
                                <div
                                    class="absolute top-1/2 left-full ml-2 w-48 p-2 text-sm text-white bg-red-500 rounded-md shadow-lg hidden group-hover:block transform -translate-y-1/2">
                                    Enter the error page URL
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="text-center mt-8">
                        <?php submit_button('Save Settings', 'primary', 'submit', true, ['class' => 'bg-red-500 text-white py-3 px-6 rounded-md hover:bg-red-600 transition duration-200 ease-in-out']); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}