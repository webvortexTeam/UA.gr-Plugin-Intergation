<?php
if (!defined('ABSPATH')) exit;

class Custom_Gallery_Widget_Vortex extends \Elementor\Widget_Base {

    public function get_name() {
        return 'Custom_Gallery_Widget_Vortex';
    }

    public function get_title() {
        return __('Gallery Απλό', 'custom-elementor-widgets');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['unlimited_andrenaline'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'custom-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'simple' => __('Simple', 'custom-elementor-widgets'),
                    'slideshow' => __('Slideshow', 'custom-elementor-widgets'),
                ],
                'default' => 'simple',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '2' => __('2 Columns', 'custom-elementor-widgets'),
                    '3' => __('3 Columns', 'custom-elementor-widgets'),
                    '4' => __('4 Columns', 'custom-elementor-widgets'),
                ],
                'default' => '3',
                'condition' => [
                    'layout' => 'simple',
                ],
            ]
        );

        $this->add_control(
            'spacing',
            [
                'label' => __('Spacing', 'custom-elementor-widgets'),
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
                    '{{WRAPPER}} .webvortex-simple-gallery-item' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout' => 'simple',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-simple-gallery-item img, {{WRAPPER}} .webvortex-slideshow-gallery-item img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'opacity',
            [
                'label' => __('Image Opacity', 'custom-elementor-widgets'),
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
                    '{{WRAPPER}} .webvortex-simple-gallery-item img, {{WRAPPER}} .webvortex-slideshow-gallery-item img' => 'opacity: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => __('Margin', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-simple-gallery, {{WRAPPER}} .webvortex-slideshow-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => __('Padding', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-simple-gallery, {{WRAPPER}} .webvortex-slideshow-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-elementor-widgets'),
                'label_off' => __('No', 'custom-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'layout' => 'slideshow',
                ],
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed (ms)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'default' => 3000,
                'condition' => [
                    'layout' => 'slideshow',
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows',
            [
                'label' => __('Navigation Arrows', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-elementor-widgets'),
                'label_off' => __('No', 'custom-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'layout' => 'slideshow',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .webvortex-slideshow-gallery .arrow' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'layout' => 'slideshow',
                    'arrows' => 'yes',
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
        $layout = $settings['layout'];
        $columns = $settings['columns'];
        $spacing = $settings['spacing']['size'];
        $border_radius = $settings['border_radius']['size'];
        $opacity = $settings['opacity']['size'];
        $autoplay = $settings['autoplay'];
        $autoplay_speed = $settings['autoplay_speed'];
        $arrows = $settings['arrows'];
        $arrow_color = $settings['arrow_color'];

        $photos = get_field('photos', $post_id); // Assumes ACF field 'photos'

        if (!empty($photos)) {
            if ($layout == 'simple') {
                echo '<div class="webvortex-simple-gallery" style="display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: ' . esc_attr($spacing) . 'px;">';
                foreach ($photos as $photo) {
                    $full_url = esc_url($photo['full_url']);
                    $thumb_url = esc_url($photo['thumb_url']);
                    $title = esc_attr($photo['photo_title']);
                    echo '<div class="webvortex-simple-gallery-item" style="padding: ' . esc_attr($spacing) . 'px;">
                        <img src="' . $full_url . '" alt="' . $title . '" style="width: 100%; height: auto; border-radius: ' . esc_attr($border_radius) . 'px; opacity: ' . esc_attr($opacity) . '%;">
                    </div>';
                }
                echo '</div>';
            } else if ($layout == 'slideshow') {
                echo '<div class="webvortex-slideshow-gallery">';
                foreach ($photos as $photo) {
                    $full_url = esc_url($photo['full_url']);
                    $thumb_url = esc_url($photo['thumb_url']);
                    $title = esc_attr($photo['photo_title']);
                    echo '<div class="webvortex-slideshow-gallery-item">
                        <img src="' . $full_url . '" alt="' . $title . '" style="width: 100%; height: auto; border-radius: ' . esc_attr($border_radius) . 'px; opacity: ' . esc_attr($opacity) . '%;">
                    </div>';
                }
                if ($arrows === 'yes') {
                    echo '<div class="arrow prev" style="color: ' . esc_attr($arrow_color) . ';">&#10094;</div>';
                    echo '<div class="arrow next" style="color: ' . esc_attr($arrow_color) . ';">&#10095;</div>';
                }
                echo '</div>';
            }
        }
        ?>

        <?php if ($layout == 'slideshow') : ?>
        <style>
            .webvortex-slideshow-gallery {
                position: relative;
                width: 100%;
                overflow: hidden;
            }

            .webvortex-slideshow-gallery-item {
                position: absolute;
                width: 100%;
                opacity: 0;
                transition: opacity 1s ease-in-out;
            }

            .webvortex-slideshow-gallery-item.active {
                opacity: 1;
                position: relative;
            }

            .arrow {
                position: absolute;
                top: 50%;
                font-size: 24px;
                cursor: pointer;
                z-index: 100;
                transform: translateY(-50%);
                user-select: none;
            }

            .arrow.prev {
                left: 10px;
            }

            .arrow.next {
                right: 10px;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slides = document.querySelectorAll('.webvortex-slideshow-gallery-item');
                const prev = document.querySelector('.arrow.prev');
                const next = document.querySelector('.arrow.next');
                let currentSlide = 0;
                let autoplay = <?php echo json_encode($autoplay); ?>;
                let autoplaySpeed = <?php echo json_encode($autoplay_speed); ?>;

                function showSlide(index) {
                    slides.forEach((slide, i) => {
                        slide.classList.toggle('active', i === index);
                    });
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                }

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    showSlide(currentSlide);
                }

                showSlide(currentSlide);

                if (autoplay === 'yes') {
                    setInterval(nextSlide, autoplaySpeed);
                }

                if (prev && next) {
                    prev.addEventListener('click', prevSlide);
                    next.addEventListener('click', nextSlide);
                }
            });
        </script>
        <?php endif;
    }

    protected function _content_template() {
        ?>
        <#
        var layout = settings.layout;
        var columns = settings.columns;
        var spacing = settings.spacing.size;
        var border_radius = settings.border_radius.size;
        var opacity = settings.opacity.size;
        var autoplay = settings.autoplay;
        var autoplay_speed = settings.autoplay_speed;
        var arrows = settings.arrows;
        var arrow_color = settings.arrow_color;
        var photos = <?php echo json_encode(get_field('photos')); ?>;

        if (photos) {
            if (layout == 'simple') {
                #>
                <div class="webvortex-simple-gallery" style="display: grid; grid-template-columns: repeat({{ columns }}, 1fr); gap: {{ spacing }}px;">
                    <# _.each(photos, function(photo) { #>
                        <div class="webvortex-simple-gallery-item" style="padding: {{ spacing }}px;">
                            <img src="{{ photo.full_url }}" alt="{{ photo.photo_title }}" style="width: 100%; height: auto; border-radius: {{ border_radius }}px; opacity: {{ opacity }}%;">
                        </div>
                    <# }); #>
                </div>
                <#
            } else if (layout == 'slideshow') {
                #>
                <div class="webvortex-slideshow-gallery">
                    <# _.each(photos, function(photo) { #>
                        <div class="webvortex-slideshow-gallery-item">
                            <img src="{{ photo.full_url }}" alt="{{ photo.photo_title }}" style="width: 100%; height: auto; border-radius: {{ border_radius }}px; opacity: {{ opacity }}%;">
                        </div>
                    <# }); #>
                    <# if (arrows === 'yes') { #>
                        <div class="arrow prev" style="color: {{ arrow_color }};">&#10094;</div>
                        <div class="arrow next" style="color: {{ arrow_color }};">&#10095;</div>
                    <# } #>
                </div>
                <#
            }
        } #>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Custom_Gallery_Widget_Vortex());
?>
