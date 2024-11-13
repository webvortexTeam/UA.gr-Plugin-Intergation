<?php 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@2.0.0/dist/full.css">
<style>
/* Fullscreen modal styles */
.ua-fullscreen-modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1000; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0, 0, 0, 0.9); /* Black with opacity */
  transition: opacity 0.3s ease-in-out;
  opacity: 0;
}

.ua-fullscreen-modal.show {
  display: block;
  opacity: 1;
}

.ua-fullscreen-modal-content {
  position: relative;
  margin: auto;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 90%;
  max-width: 1000px; /* Increased max-width for larger images */
  max-height: 80%; /* Ensures the content fits within the viewport */
  animation: zoomIn 0.6s;
}
.ua-fullscreen-modal-content img {
  width: 100%;
  height: auto;
  border-radius: 10px;
  max-height: 100vh; /* Ensure image height does not exceed viewport height */
  object-fit: contain; /* Preserve aspect ratio */
}

.ua-fullscreen-modal-close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #fff;
  font-size: 40px;
  font-weight: bold;
  transition: color 0.3s;
}

.ua-fullscreen-modal-close:hover,
.ua-fullscreen-modal-close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

.ua-fullscreen-modal-prev,
.ua-fullscreen-modal-next {
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  color: #fff;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
  background-color: rgba(0, 0, 0, 0.5);
  border: none;
  border-radius: 3px;
  transform: translateY(-50%);
}

.ua-fullscreen-modal-prev {
  left: 10px;
}

.ua-fullscreen-modal-next {
  right: 10px;
}

@keyframes zoomIn {
  from {transform: scale(0);}
  to {transform: scale(1);}
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .ua-fullscreen-modal-content {
    width: 100%;
    height: auto;
  }

  .ua-fullscreen-modal-content img {
    width: 100%;
    height: auto;
  }

  .ua-fullscreen-modal-close {
    font-size: 30px;
    top: 10px;
    right: 15px;
  }
}

/* Additional styles for hidden images */
.hidden-image {
  display: none;
}

.show-more-button {
  display: block;
  margin: 20px auto;
  padding: 10px 20px;
  background-color: #1a202c;
  color: #fff;
  text-align: center;
  cursor: pointer;
  border-radius: 5px;
}
</style>

<!-- Fullscreen Modal -->
<!-- Desktop -->
<div class="relative mx-auto mt-6 w-full h-96">
    <?php if (!empty($photos)) : ?>
        <div class="w-full h-full overflow-hidden rounded-lg relative">
            <!-- Carousel -->
            <div class="carousel rounded-box h-full">
                <?php foreach ($photos as $photo) : ?>
                    <div class="carousel-item h-full">
                        <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" 
                             alt="<?php echo esc_attr($photo['photo_title'] ?? ''); ?>" 
                             class="w-full h-full object-cover object-center clickable-image">
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- End Carousel -->
            <?php if (count($photos) > 1) : ?>
                <div class="absolute bottom-0 left-0 w-full text-center bg-black bg-opacity-50 text-white py-2 cursor-pointer" onclick="openSlideshow()">
                    <?php echo $locale_activities === 'en' ? 'See Photos' : 'Δείτε Φωτογραφίες'; ?>
                </div>
                <div id="UAfullscreenModal" class="ua-fullscreen-modal" style="z-index: 99999999;">
                    <span class="ua-fullscreen-modal-close">&times;</span>
                    <div class="ua-fullscreen-modal-content">
                        <div style="text-align: center;">
                            <img id="fullscreenImage" src="" alt="" style="display: block; margin: 0 auto;">
                            <span id="photoCaption" style="display: block; margin-top: 10px; font-size: 20px; color: white;">
                                <?php echo esc_attr($photos[0]['photo_title'] ?? ''); ?>
                            </span>
                        </div>
                        <button class="ua-fullscreen-modal-prev">&#10094;</button>
                        <button class="ua-fullscreen-modal-next">&#10095;</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="w-full h-full overflow-hidden rounded-lg relative">
            <img src="https://tailwindui.com/img/ecommerce-images/product-page-02-secondary-product-shot.jpg" alt="Placeholder image" class="w-full h-full object-cover object-center clickable-image">
        </div>
    <?php endif; ?>
</div>


<!-- Include DaisyUI JS for Carousel Functionality -->
<script src="https://cdn.jsdelivr.net/npm/tw-elements@latest/dist/js/index.min.js"></script>

<script>
// Get the modal and its elements
var modal = document.getElementById("UAfullscreenModal");
var modalImg = document.getElementById("fullscreenImage");
var images = document.querySelectorAll('.clickable-image');
var currentIndex = 0;
var caption = document.getElementById("photoCaption");

// Function to update the modal with the current image
function updateModal() {
  if (images.length === 0) return; // Guard against empty image list
  var currentImage = images[currentIndex];
  
  // Set image source and alt text
  modalImg.src = currentImage.src;
  modalImg.alt = currentImage.alt; // Fallback if alt is empty
    caption.textContent = currentImage.alt || '';

}

// Open the slideshow modal
function openSlideshow() {
  modal.classList.add('show');
  currentIndex = 0; // Reset to the first image
  updateModal();
}

// Initialize click events for images
images.forEach(function(image, index) {
  image.onclick = function() {
    console.log('Image clicked:', index);
    modal.classList.add('show');
    currentIndex = index;
    updateModal();
  }
});

// Close the modal
var closeModal = document.getElementsByClassName("ua-fullscreen-modal-close")[0];
closeModal.onclick = function() { 
  modal.classList.remove('show');
}

// Close the modal if clicking outside the image
modal.onclick = function(event) {
  if (event.target == modal) {
    modal.classList.remove('show');
  }
}

// Show previous image
document.querySelector('.ua-fullscreen-modal-prev').onclick = function() {
  currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
  updateModal();
};

// Show next image
document.querySelector('.ua-fullscreen-modal-next').onclick = function() {
  currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
  updateModal();
};
</script>
