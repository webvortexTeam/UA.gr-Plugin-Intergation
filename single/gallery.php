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
  margin: auto;
  display: block;
  width: 90%;
  max-width: 700px;
  max-height: 90%;
  animation: zoomIn 0.6s;
}

.ua-fullscreen-modal-content img {
  width: 100%;
  height: auto;
  border-radius: 10px;
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

</style>

<!-- Fullscreen Modal -->
<div id="UAfullscreenModal" class="ua-fullscreen-modal">
  <span class="ua-fullscreen-modal-close">&times;</span>
  <div class="ua-fullscreen-modal-content">
    <img id="fullscreenImage" src="" alt="Fullscreen Image">
  </div>
</div>

<!-- Desktop -->
<div class="hidden lg:grid mx-auto mt-6 max-w-2xl sm:px-6 lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8">
    <?php if (!empty($photos)) : ?>
        <?php foreach ($photos as $index => $photo) : ?>
            <?php if ($index === 0) : ?>
                <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg">
                    <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center clickable-image">
                </div>
            <?php elseif ($index === 1 || $index === 2) : ?>
                <?php if ($index === 1) : ?>
                    <div class="grid gap-y-8">
                <?php endif; ?>
                <div class="aspect-h-2 aspect-w-3 overflow-hidden rounded-lg">
                    <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center clickable-image">
                </div>
                <?php if ($index === 2) : ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="aspect-h-5 aspect-w-4 sm:overflow-hidden sm:rounded-lg">
                    <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center clickable-image">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg">
            <img src="https://tailwindui.com/img/ecommerce-images/product-page-02-secondary-product-shot.jpg" alt="Placeholder image" class="h-full w-full object-cover object-center clickable-image">
        </div>
    <?php endif; ?>
</div>

<!-- Mobile -->
<div class="block lg:hidden">
    <div class="carousel carousel-vertical rounded-box h-96">
        <?php if (!empty($photos)) : ?>
            <?php foreach ($photos as $photo) : ?>
                <div class="carousel-item h-full">
                    <img src="<?php echo esc_url($photo['full_url'] ?? ''); ?>" alt="<?php echo esc_attr($photo['title'] ?? ''); ?>" class="h-full w-full object-cover object-center clickable-image">
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="carousel-item h-full">
                <img src="https://tailwindui.com/img/ecommerce-images/product-page-02-secondary-product-shot.jpg" alt="Placeholder image" class="h-full w-full object-cover object-center clickable-image">
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Include DaisyUI JS for Carousel Functionality -->
<script src="https://cdn.jsdelivr.net/npm/tw-elements@latest/dist/js/index.min.js"></script>

<script>
// Get the modal
var modal = document.getElementById("UAfullscreenModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var modalImg = document.getElementById("fullscreenImage");
var images = document.querySelectorAll('.clickable-image');

images.forEach(function(image) {
  image.onclick = function() {
    modal.style.display = "block";
    modal.classList.add('show');
    modalImg.src = this.src;
  }
});

var span = document.getElementsByClassName("ua-fullscreen-modal-close")[0];

span.onclick = function() { 
  modal.style.display = "none";
  modal.classList.remove('show');
}

modal.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    modal.classList.remove('show');
  }
}

</script>
