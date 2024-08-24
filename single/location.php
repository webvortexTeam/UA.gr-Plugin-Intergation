<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$button_color = get_option('vortex_ua_button_color', '#FA345B');
$locale_activities = get_option('activity_api_locale', 'gr');

// Determine the address to use
$address = '';
$latitude = !empty($itinerary['latitude']) ? $itinerary['latitude'] : '';
$longitude = !empty($itinerary['longitude']) ? $itinerary['longitude'] : '';

if (!empty($itinerary['meetingPointAdress'])) {
    $address = $itinerary['meetingPointAdress'];
} elseif (!empty($itinerary['meetingPointArea'])) {
    $address = $itinerary['meetingPointArea'];
} elseif (!empty($itinerary['activityArea'])) {
    $address = $itinerary['activityArea'];
}

// Create the Google Maps URL for directions
if (!empty($latitude) && !empty($longitude)) {
    $maps_url = 'https://www.google.com/maps/search/?api=1&query=' . $latitude . ',' . $longitude;
} elseif (!empty($address)) {
    $maps_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
} else {
    $maps_url = '#';
}

?>

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
                    background: <?php echo esc_attr($button_color); ?>;
                    opacity: 1; 
                    position: absolute; 
                    top: 10px; 
                    left: 10px; 
                    border-radius: 50%;"></div>
        </div>
        
        <h2 class="text-2xl text-gray-900" style="margin-right: 5px;"><?php echo $locale_activities === 'en' ? 'Meeting' : 'Σημείο'; ?></h2>
        <h2 class="text-2xl text-gray-900" style="color: <?php echo esc_attr($button_color); ?>; margin-right: 5px;"><?php echo $locale_activities === 'en' ? 'Point' : 'συνάντησης'; ?></h2>
    </div>

    <style>
        .what-is-included-vortex {
            list-style-type: none;
            padding: 0;
        }
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
        .what-is-included-vortex .section {
            margin-bottom: 15px;
        }

        .what-is-included-vortex .section h3 {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 5px;
        }

        .what-is-included-vortex .section p {
            font-size: 1em;
            color: #666;
            margin: 0;
        }
    </style>

    <div class="what-is-included-vortex">
        <?php if (!empty($itinerary['meetingPointArea']) || !empty($itinerary['meetingPointAdress']) || !empty($itinerary['notes']) || !empty($itinerary['activityArea'])): ?>        
            <?php if (!empty($itinerary['meetingPointArea'])): ?>
                <div class="section">
                    <h3><?php echo $locale_activities === 'en' ? 'Meeting Point Area' : 'Περιοχή Συνάντησης'; ?></h3>
                    <p><?php echo esc_html($itinerary['meetingPointArea']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($itinerary['meetingPointAdress'])): ?>
                <div class="section">
                    <h3><?php echo $locale_activities === 'en' ? 'Meeting Point Address' : 'Διεύθυνση Συνάντησης'; ?></h3>
                    <p><?php echo esc_html($itinerary['meetingPointAdress']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($itinerary['notes'])): ?>
                <div class="section">
                    <h3><?php echo $locale_activities === 'en' ? 'Notes' : 'Σημειώσεις'; ?></h3>
                    <p><?php echo esc_html($itinerary['notes']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($itinerary['activityArea'])): ?>
                <div class="section">
                    <h3><?php echo $locale_activities === 'en' ? 'Activity Area' : 'Περιοχή Δραστηριότητας'; ?></h3>
                    <p><?php echo esc_html($itinerary['activityArea']); ?></p>
                </div>
            <?php endif; ?>

        <?php endif; ?>
        
    <?php if (!empty($maps_url)): ?>
        <div class="mt-4">
            <a href="<?php echo esc_url($maps_url); ?>" target="_blank" style="
                    display: inline-block; 
                    padding: 10px 10px; 
                    background-color: <?php echo esc_attr($button_color); ?>; 
                    color: white; 
                    text-decoration: none; 
                    border-radius: 25px;">
                <?php echo $locale_activities === 'en' ? 'Get Directions' : 'Λήψη Οδηγιών'; ?>
            </a>
        </div>
    <?php endif; ?>
    </div>
</div>
