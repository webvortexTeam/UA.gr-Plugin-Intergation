<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Webvortex_Reviews_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'webvortex_reviews_widget';
    }

    public function get_title() {
        return __('Κριτικές UA', 'webvortex-elementor-widgets');
    }

    public function get_icon() {
        return 'eicon-star';
    }

    public function get_categories() {
        return ['unlimited_andrenaline'];
    }

    protected function _register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'webvortex-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => __('Show Icon', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'webvortex-elementor-widgets'),
                'label_off' => __('Hide', 'webvortex-elementor-widgets'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon', 'webvortex-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition' => [
                    'show_icon' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography Section
        $this->start_controls_section(
            'typography_section',
            [
                'label' => __('Typography', 'webvortex-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'webvortex-elementor-widgets'),
                'selector' => '{{WRAPPER}} .reviews-section h2',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'review_typography',
                'label' => __('Review Typography', 'webvortex-elementor-widgets'),
                'selector' => '{{WRAPPER}} .review-item-ua p',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = get_the_ID();
        $locale_activities = get_option('activity_api_locale', 'gr');
        // Get itineraries and reviews
        $itineraries = get_field('itineraries', $post_id);
        $all_reviews = [];

        if (!empty($itineraries)) {
            foreach ($itineraries as $itinerary) {
                if (!empty($itinerary['ratings'])) {
                    foreach ($itinerary['ratings'] as $review) {
                        $all_reviews[] = $review;
                    }
                }
            }
        }

        if (!empty($all_reviews)) {
            echo '<div id="unlimited-a-reviews" class="reviews-section bg-white p-6 rounded-lg">';
            echo '<h2 class="text-2xl font-bold mb-4">' . ($locale_activities === 'en' ? 'Reviews' : 'Κριτικές') . '</h2>';
            echo '<div id="reviews-container-ua" class="space-y-4">';
            foreach ($all_reviews as $index => $review) {
                echo '<div class="review-item-ua p-4 border rounded-lg ' . ($index >= 5 ? 'hidden' : '') . '">';
                if ($settings['show_icon'] === 'yes' && !empty($settings['icon'])) {
                    echo '<div class="flex items-center mb-2">';
                    \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
                    echo '<svg class="w-4 h-4 text-yellow-300 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20"><path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/></svg>';
                    echo '<p class="ms-2 text-sm font-bold text-gray-900 dark:text-white">' . esc_html($review['score']) . '</p>';
                    echo '<span class="w-1 h-1 mx-1.5 bg-gray-500 rounded-full dark:bg-gray-400"></span>';
                    echo '<p class="text-sm font-medium text-gray-900 dark:text-white">' . esc_html($review['fullname']) . '</p>';
                    echo '</div>';
                }
                echo '<p class="text-sm text-gray-700">' . wp_kses_post($review['text']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
            echo '<div id="pagination-review-ua" class="mt-4 flex justify-center space-x-2"></div>';
            echo '</div>';
        } else {
            echo '<p class="text-gray-700">' . ($locale_activities === 'en' ? 'No reviews available' : 'Δεν υπάρχουν κριτικές') . '</p>';
        }
        ?>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reviewsPerPageUA = 5;
            const reviewsContainer = document.getElementById('reviews-container-ua');
            const reviews = reviewsContainer.getElementsByClassName('review-item-ua');
            const totalPages = Math.ceil(reviews.length / reviewsPerPageUA);

            function showPage(page) {
                const start = (page - 1) * reviewsPerPageUA;
                const end = start + reviewsPerPageUA;

                for (let i = 0; i < reviews.length; i++) {
                    reviews[i].classList.add('hidden');
                }

                for (let i = start; i < end && i < reviews.length; i++) {
                    reviews[i].classList.remove('hidden');
                }
            }

            function createPagination() {
                const paginationContainer = document.getElementById('pagination-review-ua');

                for (let i = 1; i <= totalPages; i++) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.classList.add('pagination-button', 'px-2', 'py-1', 'border', 'rounded');
                    button.addEventListener('click', function () {
                        showPage(i);
                        updateActiveButton(i);
                    });
                    paginationContainer.appendChild(button);
                }
            }

            function updateActiveButton(activePage) {
                const buttons = document.querySelectorAll('.pagination-button');
                buttons.forEach(button => {
                    button.classList.remove('bg-blue-500', 'text-white');
                    button.classList.add('bg-gray-200', 'text-gray-700');
                });

                buttons[activePage - 1].classList.add('bg-blue-500', 'text-white');
                buttons[activePage - 1].classList.remove('bg-gray-200', 'text-gray-700');
            }

            if (totalPages > 1) {
                createPagination();
                showPage(1);  // Show the first page by default
                updateActiveButton(1);  // Highlight the first page button
            }
        });
        </script>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
        var locale_activities = '<?php echo get_option('activity_api_locale', 'gr'); ?>';
        var all_reviews = <?php echo json_encode(get_field('reviews')); ?>;

        if (!_.isEmpty(all_reviews)) { #>
            <div id="unlimited-a-reviews" class="reviews-section bg-white p-6 rounded-lg">
                <h2 class="text-2xl font-bold mb-4">{{ locale_activities === 'en' ? 'Reviews' : 'Κριτικές' }}</h2>
                <div id="reviews-container-ua" class="space-y-4">
                    <# _.each(all_reviews, function(review, index) { #>
                        <div class="review-item-ua p-4 border rounded-lg {{ index >= 5 ? 'hidden' : '' }}">
                            <# if (settings.show_icon === 'yes' && settings.icon) { #>
                                <svg class="w-4 h-4 text-yellow-300 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                </svg>
                            
                            <# } #>
                                <div class="flex items-center mb-2">
                                    <i class="{{ settings.icon.value }}" aria-hidden="true"></i>
                                    <p class="ms-2 text-sm font-bold text-gray-900 dark:text-white">{{ review.score }}</p>
                                    <span class="w-1 h-1 mx-1.5 bg-gray-500 rounded-full dark:bg-gray-400"></span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ review.fullname }}</p>
                                </div>
                            <p class="text-sm text-gray-700">{{ review.text }}</p>
                        </div>
                    <# }); #>
                </div>
                <div id="pagination-review-ua" class="mt-4 flex justify-center space-x-2"></div>
            </div>
        <# } else { #>
            <p class="text-gray-700">{{ locale_activities === 'en' ? 'No reviews available' : 'Δεν υπάρχουν κριτικές' }}</p>
        <# } #>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Webvortex_Reviews_Widget());
?>
