<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$button_color = get_option('vortex_ua_button_color', '#FA345B');
$locale_activities = get_option('activity_api_locale', 'gr');
?>
<?php if (!empty($itinerary['details']['do_not_forget'])): ?>

    <div class="mt-4">
 <div style="display: flex; align-items: center;">
    <div style="
            width: 40px; 
            height: 40px; 
            background-repeat: no-repeat; 
            background-position: center center; 
            background-size: cover; 
            opacity: 1; 
            position: relative; 
            border-radius: 50%; 
            overflow: hidden; 
            margin-right: 10px;">
                
        <div style="
                width: 40px; 
                height: 40px; 
                background: rgba(217, 217, 217, 1); 
                opacity: 1; 
                position: relative; 
                border-radius: 50%;"></div>
                
        <div style="
                width: 20px; 
                height: 20px; 
                background: <?php echo $button_color;?>;
                opacity: 1; 
                position: absolute; 
                top: 10px; 
                left: 10px; 
                border-radius: 50%;"></div>
    </div>
    <h2 class="text-2xl text-gray-900" style="margin-right: 5px;"><?php echo $locale_activities === 'en' ? 'Do not' : 'Να μην'; ?></h2>
    <h2 class="text-2xl text-gray-900" style="color: <?php echo $button_color;?>; margin-right: 5px;"><?php echo $locale_activities === 'en' ? 'forget' : 'ξεχάσω'; ?></h2>
</div>


<style>
    .what-is-included-vortex {
        list-style-type: none;
        padding: 0;
    }
/* Add this CSS to a <style> block or a stylesheet */
.what-is-included-vortex {
    width: 100%;
    max-width: 500px;
    background: rgba(238, 238, 238, 1);
    padding: 10px;
    margin: 30px 0;
    border-radius: 20px; /* Slightly rounded corners */
    overflow: hidden;
    list-style: none; /* Remove default list styling */
    display: flex;
    flex-direction: column; /* Stack items vertically */
    align-items: flex-start; /* Align items to the start of the container */
}

.what-is-included-vortex li {
    width: 100%; /* Full width of the container */
    text-align: left; /* Center text horizontally */
}

</style>

<ul class="what-is-included-vortex">
    <?php foreach ($itinerary['details']['do_not_forget'] as $included): ?>
        <?php
        // Truncate the title if it's longer than 30 characters
        $title = esc_html($included['title']);
        ?>
        <li>
          •<?php echo $title; ?>
        </li>
    <?php endforeach; ?>
</ul>



    </div>

<?php endif; ?>
