<?php 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$locale_activities = get_option('activity_api_locale', 'gr');
?>
<nav aria-label="Breadcrumb">
    <ol role="list" class="mx-auto flex max-w-2xl items-center space-x-2 px-4 sm:px-6 lg:max-w-7xl lg:px-8">
        <?php
        $show_logo = get_option('vortex_ua_show_logo', 'yes');
        $logo_url = get_option('vortex_ua_logo_url', 'https://www.webvortex.org/favicon.ico');
        
        if ($show_logo === 'yes') {
        ?>
            <img class="h-8 w-auto" src="<?php echo esc_url($logo_url); ?>" alt="<?php echo $locale_activities === 'en' ? 'Activity Logo' : 'Λογότυπο Δραστηριότητας'; ?>">
        <?php
        }
        ?>
        <br></br>
        <li>
            <div class="flex items-center">
                <a href="/" class="mr-2 text-sm font-medium text-gray-900">
                    <?php echo $locale_activities === 'en' ? 'Home' : 'Αρχική'; ?>
                </a>
                <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
                    <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                </svg>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <a href="/activity" class="mr-2 text-sm font-medium text-gray-900">
                    <?php echo $locale_activities === 'en' ? 'Activities' : 'Δραστηριότητες'; ?>
                </a>
                <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
                    <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                </svg>
            </div>
        </li>
        <li class="text-sm">
            <a href="#" aria-current="page" class="font-medium text-gray-500 hover:text-gray-600">
                <?php echo esc_html($title); ?>
            </a>
        </li>
    </ol>
</nav>
