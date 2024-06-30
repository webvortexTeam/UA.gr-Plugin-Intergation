<?php
function adrenaline_thank_you_template() {
    ob_start();
    ?>    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


    <div class="container mx-auto px-4 py-8 flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md">
            <div class="text-center mb-6">
                <svg class="w-12 h-12 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 4v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6a2 2 0 012-2h.586A1 1 0 019 11.414l1.707-1.707a1 1 0 011.414 0L14 11.414A1 1 0 0014.586 12H15a2 2 0 012 2z"></path>
                </svg>
                <h1 class="text-2xl font-bold text-gray-900">Thank You!</h1>
                <p class="text-gray-600">Your adrenaline booking was successful.</p>
            </div>
            <div class="text-center">
                <a href="<?php echo $baseurl; ?>/activities" class="inline-block px-6 py-2 text-sm font-medium leading-6 text-center text-white bg-blue-500 rounded-full shadow-md hover:bg-blue-600 focus:outline-none">
                    Back to Activities
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function adrenaline_failed_template() {
    ob_start();
    ?>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto px-4 py-8 flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md">
            <div class="text-center mb-6">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <h1 class="text-2xl font-bold text-gray-900">Failed</h1>
                <p class="text-gray-600">Your adrenaline booking failed. Please try again.</p>
            </div>
            <div class="text-center">
                <a href="<?php echo $baseurl; ?>/activities" class="inline-block px-6 py-2 text-sm font-medium leading-6 text-center text-white bg-red-500 rounded-full shadow-md hover:bg-red-600 focus:outline-none">
                    Back to Activities
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function adrenaline_filter_page_content($content) {
    if (is_page('thank-you-adrenaline') || is_page('failed-adrenaline')) {
        return $content;
    }
    return $content;
}