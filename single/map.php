<div id="map-<?php echo $index; ?>" style="height: 400px; width: 100%; z-index: 0 !important;"></div>
<style>
#map {
    z-index: -1;
    position: relative;
}

.flashing-red-icon .flashing-red-point {
    width: 20px;
    height: 20px;
    background-color: red;
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

    itineraries.forEach((itinerary, index) => {
        const lat = parseFloat(itinerary.latitude);
        const lng = parseFloat(itinerary.longitude);

        if (!isNaN(lat) && !isNaN(lng)) {
            // Initialize the map for each itinerary
            const map = L.map(`map-${index}`).setView([lat, lng], 16);

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
        }
    });
});
</script>