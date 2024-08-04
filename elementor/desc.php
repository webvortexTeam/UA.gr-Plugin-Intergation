<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Webvortex_Description_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'webvortex_description_widget';
    }

    public function get_title() {
        return __('Description Display', 'webvortex-elementor-widgets');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['unlimited_andrenaline'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'webvortex-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'max_length',
            [
                'label' => __('Max Length', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 500,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'read_more_color',
            [
                'label' => __('Read More Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #vortexReadMoreUA' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'read_more_hover_color',
            [
                'label' => __('Read More Hover Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #vortexReadMoreUA:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => __('Background Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #vortex-ua-description-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'fade_effect_color',
            [
                'label' => __('Fade Effect Color', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #fadeEffect' => 'background: linear-gradient(to top, {{VALUE}}, transparent);',
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
        $description = get_field('description', $post_id);
        $locale_activities = get_locale() === 'en_US' ? 'en' : 'el';

        ?>
        <div>
            <h3 class="sr-only">
                <?php echo $locale_activities === 'en' ? 'Description' : 'Περιγραφή'; ?>
            </h3>
            <div id="vortex-ua-description-container" class="relative max-h-[200px] overflow-hidden">
                <div id="description" class="space-y-6 text-base text-gray-900">
                    <?php echo ($description); ?>
                </div>
                <div id="fadeEffect" class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent"></div>
            </div>
            <div id="readMoreContainer" class="flex justify-center">
                <a id="vortexReadMoreUA" class="mt-4 underline" style="display: none;">
                    <?php echo $locale_activities === 'en' ? 'Read more...' : 'Διαβάστε περισσότερα...'; ?>
                </a>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const descriptionElement = document.getElementById('description');
                const descriptionContainer = document.getElementById('vortex-ua-description-container');
                const vortexReadMoreUA = document.getElementById('vortexReadMoreUA');
                const maxLength = <?php echo esc_js($settings['max_length']); ?>;

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
                color: <?php echo esc_attr($settings['read_more_hover_color']); ?>;
            }
            #vortex-ua-description-container {
                max-height: 12rem; /* Adjust the height as needed */
                background-color: <?php echo esc_attr($settings['bg_color']); ?>;
            }
            #fadeEffect {
                display: none;
                background: linear-gradient(to top, <?php echo esc_attr($settings['fade_effect_color']); ?>, transparent);
            }
        </style>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
        var locale_activities = settings.locale === 'en_US' ? 'en' : 'el';
        var description = settings.description;
        var max_length = settings.max_length;
        #>
        <div>
            <h3 class="sr-only">
                {{{ locale_activities === 'en' ? 'Description' : 'Περιγραφή' }}}
            </h3>
            <div id="vortex-ua-description-container" class="relative max-h-[200px] overflow-hidden">
                <div id="description" class="space-y-6 text-base text-gray-900">
                   {{{ description }}}
                </div>
                <div id="fadeEffect" class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent"></div>
            </div>
            <div id="readMoreContainer" class="flex justify-center">
                <a id="vortexReadMoreUA" class="mt-4 underline" style="display: none;">
                    {{{ locale_activities === 'en' ? 'Read more...' : 'Διαβάστε περισσότερα...' }}}
                </a>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const descriptionElement = document.getElementById('description');
                const descriptionContainer = document.getElementById('vortex-ua-description-container');
                const vortexReadMoreUA = document.getElementById('vortexReadMoreUA');
                const maxLength = {{{ max_length }}};

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
                color: {{{ settings.read_more_hover_color }}};
            }
            #vortex-ua-description-container {
                max-height: 12rem; /* Adjust the height as needed */
                background-color: {{{ settings.bg_color }}};
            }
            #fadeEffect {
                display: none;
                background: linear-gradient(to top, {{{ settings.fade_effect_color }}}, transparent);
            }
        </style>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Webvortex_Description_Widget());
?>
