<div>
    <h3 class="sr-only">Περιγραφή</h3>
    <div id="vortex-ua-description-container" class="relative max-h-[200px] overflow-hidden">
        <div id="description" class="space-y-6 text-base text-gray-900">
            <?php echo wp_kses_post($description); ?>
        </div>
        <div id="fadeEffect" class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent"></div>
    </div>
    <div id="readMoreContainer" class="flex justify-center">
        <a id="vortexReadMoreUA" class="mt-4 underline" style="display: none;">Διαβάστε περισσότερα...</a>
    </div>
</div>

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
        color: #1e40af; /* Tailwind's blue-700 */
    }
    #vortex-ua-description-container {
        max-height: 12rem; /* Adjust the height as needed */
    }
    #fadeEffect {
        display: none;
    }
</style>


