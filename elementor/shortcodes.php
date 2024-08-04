<?php
if (!defined('ABSPATH')) exit;

class Webvortex_Activity_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'webvortex_activity_widget';
    }

    public function get_title() {
        return __('Πληροφορίες UA', 'webvortex-elementor-widgets');
    }

    public function get_icon() {
        return 'eicon-post';
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
            'field_to_display',
            [
                'label' => __('Πεδίο για εμφάνιση', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'title' => __('Τίτλος', 'webvortex-elementor-widgets'),
                    'activity_id' => __('ID Δραστηριότητας', 'webvortex-elementor-widgets'),
                    'provider_id' => __('ID Παρόχου', 'webvortex-elementor-widgets'),
                    'rating' => __('Βαθμολογία', 'webvortex-elementor-widgets'),
                    'active_months' => __('Ενεργοί Μήνες', 'webvortex-elementor-widgets'),
                    'category_ids' => __('IDs Κατηγοριών', 'webvortex-elementor-widgets'),
                    'duration' => __('Διάρκεια', 'webvortex-elementor-widgets'),
                    'description' => __('Περιγραφή', 'webvortex-elementor-widgets'),
                    'meeting_point' => __('Σημείο Συνάντησης', 'webvortex-elementor-widgets'),
                    'meeting_time' => __('Ώρα Συνάντησης', 'webvortex-elementor-widgets'),
                    'additional_info' => __('Επιπλέον Πληροφορίες', 'webvortex-elementor-widgets'),
                ],
                'default' => 'title',
            ]
        );

        $this->add_control(
            'prefix_text',
            [
                'label' => __('Προθεματικό Κείμενο', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Εισάγετε προθεματικό κείμενο', 'webvortex-elementor-widgets'),
            ]
        );

        $this->add_control(
            'suffix_text',
            [
                'label' => __('Επιθηματικό Κείμενο', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Εισάγετε επιθηματικό κείμενο', 'webvortex-elementor-widgets'),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Χρώμα Κειμένου', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .webvortex-activity-field' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __('Τυπογραφία', 'webvortex-elementor-widgets'),
                'selector' => '{{WRAPPER}} .webvortex-activity-field',
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Χρώμα Φόντου', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .webvortex-activity-field' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Ακτίνα Περιγράμματος', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .webvortex-activity-field' => 'border-radius: {{SIZE}}{{UNIT}};',
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
        $field_to_display = $settings['field_to_display'];
        $prefix_text = $settings['prefix_text'];
        $suffix_text = $settings['suffix_text'];

        $fields = [
            'title' => get_field('title', $post_id),
            'activity_id' => get_field('activity_id', $post_id),
            'provider_id' => get_field('provider_id', $post_id),
            'rating' => get_field('rating', $post_id),
            'active_months' => get_field('active_months', $post_id),
            'category_ids' => get_field('category_ids', $post_id),
            'duration' => get_field('duration', $post_id),
            'description' => get_field('description', $post_id),
            'meeting_point' => get_field('meeting_point', $post_id),
            'meeting_time' => get_field('meeting_time', $post_id),
            'additional_info' => get_field('additional_info', $post_id),
        ];

        echo '<div class="webvortex-activity-field">';
        if ($field_to_display == 'description') {
            echo wp_kses_post($prefix_text . $fields['description'] . $suffix_text);
        } elseif (array_key_exists($field_to_display, $fields)) {
            echo esc_html($prefix_text . $fields[$field_to_display] . $suffix_text);
        } else {
            echo __('Το πεδίο δεν είναι διαθέσιμο', 'webvortex-elementor-widgets');
        }
        echo '</div>';
    }

    protected function _content_template() {
        ?>
        <#
        var fieldToDisplay = settings.field_to_display;
        var prefixText = settings.prefix_text;
        var suffixText = settings.suffix_text;
        var fields = {
            'title': '<?php echo get_field('title'); ?>',
            'activity_id': '<?php echo get_field('activity_id'); ?>',
            'provider_id': '<?php echo get_field('provider_id'); ?>',
            'rating': '<?php echo get_field('rating'); ?>',
            'active_months': '<?php echo get_field('active_months'); ?>',
            'category_ids': '<?php echo get_field('category_ids'); ?>',
            'duration': '<?php echo get_field('duration'); ?>',
            'description': '<?php echo get_field('description'); ?>',
            'meeting_point': '<?php echo get_field('meeting_point'); ?>',
            'meeting_time': '<?php echo get_field('meeting_time'); ?>',
            'additional_info': '<?php echo get_field('additional_info'); ?>',
        };

        if (fieldToDisplay === 'description') {
            #>
            <div class="webvortex-activity-field">{{{ prefixText }}}{{{ fields.description }}}{{{ suffixText }}}</div>
            <# 
        } else if (fields[fieldToDisplay]) { 
            #>
            <div class="webvortex-activity-field">{{{ prefixText }}}{{{ fields[fieldToDisplay] }}}{{{ suffixText }}}</div>
            <# 
        } else { 
            #>
            <div class="webvortex-activity-field"><?php echo __('Το πεδίο δεν είναι διαθέσιμο', 'webvortex-elementor-widgets'); ?></div>
            <#
        }
        #>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Webvortex_Activity_Widget());
?>
