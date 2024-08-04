<?php
if (!defined('ABSPATH')) exit;

class Webvortex_Gallery_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'webvortex_gallery_widget';
    }

    public function get_title() {
        return __('Gallery με Tailwind', 'webvortex-elementor-widgets');
    }

    public function get_icon() {
        return 'eicon-gallery-masonry';
    }

    public function get_categories() {
        return ['unlimited_andrenaline'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Περιεχόμενο', 'webvortex-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Στήλες', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '2' => __('2 Στήλες', 'webvortex-elementor-widgets'),
                    '3' => __('3 Στήλες', 'webvortex-elementor-widgets'),
                    '4' => __('4 Στήλες', 'webvortex-elementor-widgets'),
                    '5' => __('5 Στήλες', 'webvortex-elementor-widgets'),
                ],
                'default' => '3',
            ]
        );

        $this->add_control(
            'gallery_spacing',
            [
                'label' => __('Διάστημα Γκαλερί', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-gallery-item' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'lightbox',
            [
                'label' => __('Ενεργοποίηση Lightbox', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Ναι', 'webvortex-elementor-widgets'),
                'label_off' => __('Όχι', 'webvortex-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Add more customizable controls
        $this->add_control(
            'image_border_radius',
            [
                'label' => __('Ακτίνα Περιγράμματος Εικόνας', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-gallery-item img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label' => __('Διαφάνεια Εικόνας', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-gallery-item img' => 'opacity: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => __('Margin', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => __('Padding', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .webvortex-gallery-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => __('Background Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .webvortex-gallery-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $post_id = get_the_ID();

        if (!$post_id) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $columns = $settings['columns'];
        $spacing = $settings['gallery_spacing']['size'];
        $lightbox = $settings['lightbox'] === 'yes';

        $photos = get_field('photos', $post_id);

        if (!empty($photos)) :
            echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@2.0.0/dist/full.css">';
            echo '<script src="https://cdn.tailwindcss.com"></script>';
            echo '<style>
                .ua-fullscreen-modal {
                    display: none;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0, 0, 0, 0.9);
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
                </style>';

            echo '<div id="UAfullscreenModal" class="ua-fullscreen-modal">
                <span class="ua-fullscreen-modal-close">&times;</span>
                <div class="ua-fullscreen-modal-content">
                    <img id="fullscreenImage" src="" alt="Fullscreen Image">
                </div>
            </div>';

            echo '<div class="webvortex-gallery hidden lg:grid mx-auto mt-6 max-w-2xl sm:px-6 lg:max-w-7xl lg:grid-cols-' . esc_attr($columns) . ' lg:gap-x-8 lg:px-8">';
            foreach ($photos as $index => $photo) :
                $full_url = esc_url($photo['full_url']);
                $thumb_url = esc_url($photo['thumb_url']);
                $title = esc_attr($photo['photo_title']);
                echo '<div class="webvortex-gallery-item aspect-h-4 aspect-w-3 overflow-hidden rounded-lg">
                    <img src="' . $full_url . '" alt="' . $title . '" class="h-full w-full object-cover object-center clickable-image">
                </div>';
            endforeach;
            echo '</div>';

            echo '<div class="block lg:hidden">
                <div class="carousel carousel-vertical rounded-box h-96">';
            foreach ($photos as $photo) :
                $full_url = esc_url($photo['full_url']);
                $title = esc_attr($photo['photo_title']);
                echo '<div class="carousel-item h-full">
                    <img src="' . $full_url . '" alt="' . $title . '" class="h-full w-full object-cover object-center clickable-image">
                </div>';
            endforeach;
            echo '</div></div>';

            echo '<script src="https://cdn.jsdelivr.net/npm/tw-elements@latest/dist/js/index.min.js"></script>';
            echo '<script>
                var modal = document.getElementById("UAfullscreenModal");
                var modalImg = document.getElementById("fullscreenImage");
                var images = document.querySelectorAll(".clickable-image");
                images.forEach(function(image) {
                    image.onclick = function() {
                        modal.style.display = "block";
                        modal.classList.add("show");
                        modalImg.src = this.src;
                    }
                });
                var span = document.getElementsByClassName("ua-fullscreen-modal-close")[0];
                span.onclick = function() { 
                    modal.style.display = "none";
                    modal.classList.remove("show");
                }
                modal.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                        modal.classList.remove("show");
                    }
                }
                </script>';
        endif;
    }

    protected function _content_template() {
        ?>
        <#
        var columns = settings.columns;
        var spacing = settings.gallery_spacing.size;
        var lightbox = settings.lightbox === 'yes';
        var photos = <?php echo json_encode(get_field('photos')); ?>;

        if (photos) {
            #>
            <div id="UAfullscreenModal" class="ua-fullscreen-modal">
                <span class="ua-fullscreen-modal-close">&times;</span>
                <div class="ua-fullscreen-modal-content">
                    <img id="fullscreenImage" src="" alt="Fullscreen Image">
                </div>
            </div>

            <div class="webvortex-gallery hidden lg:grid mx-auto mt-6 max-w-2xl sm:px-6 lg:max-w-7xl lg:grid-cols-{{ columns }} lg:gap-x-8 lg:px-8">
                <# _.each(photos, function(photo) {
                    var fullUrl = photo.full_url;
                    var title = photo.photo_title;
                    #>
                    <div class="webvortex-gallery-item aspect-h-4 aspect-w-3 overflow-hidden rounded-lg">
                        <img src="{{ fullUrl }}" alt="{{ title }}" class="h-full w-full object-cover object-center clickable-image">
                    </div>
                    <#
                }); #>
            </div>

            <div class="block lg:hidden">
                <div class="carousel carousel-vertical rounded-box h-96">
                    <# _.each(photos, function(photo) {
                        var fullUrl = photo.full_url;
                        var title = photo.photo_title;
                        #>
                        <div class="carousel-item h-full">
                            <img src="{{ fullUrl }}" alt="{{ title }}" class="h-full w-full object-cover object-center clickable-image">
                        </div>
                        <#
                    }); #>
                </div>
            </div>
            <#
        } #>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Webvortex_Gallery_Widget());
?>
