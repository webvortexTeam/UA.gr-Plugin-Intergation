<?php
function adrenaline_thank_you_template() {
    ob_start();
    ?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes success-animation {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        .animate-success {
            animation: success-animation 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8">
            <div class="text-center animate-success">
                <svg class="mx-auto h-16 w-16 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 4V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2z" />
                </svg>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Payment Successful</h2>
                <p class="mt-2 text-sm text-gray-600">Thank you for your purchase!</p>
            </div>
            <div class="mt-8">
                <a href="<?php echo $baseurl; ?>/" class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition duration-200 transform hover:scale-105">Return to Home</a>
            </div>
        </div>
    </div>
</body>
</html>

    <?php
    return ob_get_clean();
}

function adrenaline_failed_template() {
    ob_start();
    ?>
  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes failure-animation {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        .animate-failure {
            animation: failure-animation 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8">
            <div class="text-center animate-failure">
                <svg class="mx-auto h-16 w-16 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Payment Failed</h2>
                <p class="mt-2 text-sm text-gray-600">Sorry, your transaction could not be processed.</p>
            </div>
            <div class="mt-8">
                <a href="<?php echo $baseurl; ?>/" class="w-full bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 transition duration-200 transform hover:scale-105">Return to Home</a>
            </div>
        </div>
    </div>
</body>
</html>

    <?php
    return ob_get_clean();
}

function adrenaline_filter_page_content($content) {
    if (is_page('thank-you-adrenaline') || is_page('failed-adrenaline')) {
        return $content;
    }
    return $content;
}