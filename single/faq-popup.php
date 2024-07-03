<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<div id="vortex-ua-info-new-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                            <div class="bg-white p-6 rounded-lg shadow-lg">
                                <h3 class="text-lg font-semibold mb-4">Απορίες & FAQ</h3>
                                <div class="new-popup-content text-gray-700">
                                    <p>test</p>
                                </div>
                                <a id="close-vortex-ua-info-new-btn" class="mt-4 px-4 py-2 rounded">Κλείσιμο</a>
                            </div>
                        </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const newPopupBtn = document.getElementById('vortex-ua-info-new-btn');
    const newPopupModal = document.getElementById('vortex-ua-info-new-modal');
    const closeNewPopupBtn = document.getElementById('close-vortex-ua-info-new-btn');

    newPopupBtn.addEventListener('click', function () {
        newPopupModal.classList.remove('hidden');
    });

    closeNewPopupBtn.addEventListener('click', function () {
        newPopupModal.classList.add('hidden');
    });

    // Optional: Close the modal when clicking outside of it
    newPopupModal.addEventListener('click', function (event) {
        if (event.target === newPopupModal) {
            newPopupModal.classList.add('hidden');
        }
    });
});
</script>