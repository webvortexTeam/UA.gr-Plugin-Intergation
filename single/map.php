<?php
ob_start();

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$button_color = get_option('vortex_ua_button_color', '#FA345B');

?>
<br>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
    <svg width="107" height="107" viewBox="0 0 107 107" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="40.5" cy="40.5" r="47" transform="matrix(-1 0 0 1 94 13)" stroke="<?php echo $button_color;?>" stroke-width="13"/>
        <path d="M60.375 53.5C60.375 57.2904 57.2904 60.375 53.5 60.375C49.7096 60.375 46.625 57.2904 46.625 53.5C46.625 49.7096 49.7096 46.625 53.5 46.625C57.2904 46.625 60.375 49.7096 60.375 53.5ZM81 53.5C81 54.7673 79.9733 55.7917 78.7083 55.7917H76.3021C75.2227 66.6015 66.6015 75.225 55.7917 76.3021V78.7083C55.7917 79.9756 54.765 81 53.5 81C52.235 81 51.2083 79.9756 51.2083 78.7083V76.3021C40.3985 75.2227 31.775 66.6015 30.6979 55.7917H28.2917C27.0267 55.7917 26 54.7673 26 53.5C26 52.2327 27.0267 51.2083 28.2917 51.2083H30.6979C31.775 40.3985 40.3985 31.775 51.2083 30.6979V28.2917C51.2083 27.0244 52.235 26 53.5 26C54.765 26 55.7917 27.0244 55.7917 28.2917V30.6979C66.6015 31.7773 75.225 40.3985 76.3021 51.2083H78.7083C79.9733 51.2083 81 52.2327 81 53.5ZM64.9583 53.5C64.9583 47.1819 59.8181 42.0417 53.5 42.0417C47.1819 42.0417 42.0417 47.1819 42.0417 53.5C42.0417 59.8181 47.1819 64.9583 53.5 64.9583C59.8181 64.9583 64.9583 59.8181 64.9583 53.5Z" fill="#222222"/>
    </svg>

</div>
<br>


<div id="map-<?php echo $index; ?>" style="height: 400px; width: 100%; z-index: 0 !important; border-radius: 25px;"></div>
<style>
#map {
    z-index: -1;
    position: relative;
}

.flashing-red-icon .flashing-red-point {
    width: 20px;
    height: 20px;
    background-color: <?php echo $button_color;?>;
    border-radius: 50%;
    animation: flash 1s infinite;
    
}

@keyframes flash {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const itineraries = <?php echo json_encode($itineraries); ?>;
    const geocodeApiUrl = 'https://nominatim.openstreetmap.org/reverse?format=json&lat={lat}&lon={lng}';
    const maps = {}; // Object to keep track of map instances

    itineraries.forEach((itinerary, index) => {
        const lat = parseFloat(itinerary.latitude);
        const lng = parseFloat(itinerary.longitude);

        if (!isNaN(lat) && !isNaN(lng)) {
            const mapId = `map-${index}`;
            
            if (!maps[mapId]) { // Only create a new map if it doesn't already exist
                // Initialize the map for each itinerary
                const map = L.map(mapId).setView([lat, lng], 16);

                // Add CartoDB Positron tile layer
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    subdomains: 'abcd',
                    maxZoom: 59
                }).addTo(map);

                // Create a custom flashing red icon
                const flashingRedIcon = L.divIcon({
                    className: 'flashing-red-icon',
                    html: '<div class="flashing-red-point"></div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                // Add the marker with the custom icon to the map
                const marker = L.marker([lat, lng], { icon: flashingRedIcon }).addTo(map);

                // Zoom effect on load
                map.on('load', function () {
                    setTimeout(() => {
                        map.setZoom(28);
                    }, 500); // delay for zoom effect
                });

                // Fetch the address and show it in a popup
                fetch(geocodeApiUrl.replace('{lat}', lat).replace('{lng}', lng))
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.address) {
                            const address = data.address;
                            const displayName = `${address.road || ''}, ${address.city || address.town || address.village || ''}, ${address.country || ''}, ${address.postcode || ''}`.replace(/^,|,$/g, '');
                            marker.bindPopup(displayName).openPopup();
                        } else {
                            marker.bindPopup('Η διεύθυνση δεν βρέθηκε.').openPopup();
                        }
                    })
                    .catch(error => {
                        marker.bindPopup('Error fetching address').openPopup();
                        console.error('Error fetching address:', error);
                    });

                maps[mapId] = map; // Store the map instance
            }
        }
    });
});

</script>
<?php
ob_end_flush();
?>