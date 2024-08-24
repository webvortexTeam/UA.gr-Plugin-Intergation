<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$button_color = get_option('vortex_ua_button_color', '#FA345B');
$locale_activities = get_option('activity_api_locale', 'gr');
?>
<div class="description-container" style="<?php if (wp_is_mobile()) { echo ''; } else { echo 'position: relative; top: -190px;'; } ?>">

            <h2 class="text-2xl text-gray-900" style="display: inline; margin-right: 5px;"><?php echo $locale_activities === 'en' ? 'Activity' : 'Περιγραφή'; ?></h2>
        <h2 class="text-2xl text-gray-900" style="display: inline; color: <?php echo $button_color;?>; margin-right: 5px;"><?php echo $locale_activities === 'en' ? 'description' : 'δραστηριότητας'; ?></h2>

    <div id="vortex-ua-description-container" class="relative max-h-[200px] overflow-hidden">
        <div id="description" class="text-base text-gray-900">
            <?php echo wp_kses_post($description); ?>
        </div>
        <div id="fadeEffect" class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent"></div>
    </div>
    <div id="readMoreContainer" class="flex justify-center">
        <a id="vortexReadMoreUA" class="mt-4 underline" style="display: none;">
            <?php echo $locale_activities === 'en' ? 'Read more...' : 'Διαβάστε περισσότερα...'; ?>
        </a>
    </div>
</div>

<style>
@media (max-width: 1023px) {
    .description-container {
        position: relative;
        top: 0; /* Reset the top value for mobile devices */
    }
}
</style>






<script>
    document.addEventListener('DOMContentLoaded', function () {
        const descriptionElement = document.getElementById('description');
        const descriptionContainer = document.getElementById('vortex-ua-description-container');
        const vortexReadMoreUA = document.getElementById('vortexReadMoreUA');
        const maxLength = 500;

        if (descriptionElement.innerText.length > maxLength) {
            const originalText = descriptionElement.innerHTML;
            const trimmedText = originalText.substring(0, maxLength) + '...';
            descriptionElement.innerHTML = trimmedText;

            vortexReadMoreUA.style.display = 'inline-block';
            document.getElementById('fadeEffect').style.display = 'block';

            vortexReadMoreUA.addEventListener('click', function () {
                descriptionElement.innerHTML = originalText;
                vortexReadMoreUA.style.display = 'none';
                document.getElementById('fadeEffect').style.display = 'none';
                descriptionContainer.style.maxHeight = 'none';
            });
        }
    });
</script>

<style>
    #vortexReadMoreUA:hover {
        color: <?php echo $button_color;?>; /* Tailwind's blue-700 */
    }
    #vortex-ua-description-container {
        max-height: 12rem; /* Adjust the height as needed */
    }
    #fadeEffect {
        display: none;
    }
</style>
