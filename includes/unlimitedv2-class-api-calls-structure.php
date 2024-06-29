<?php
function unlimited_adrenaline_remote_call($method, $api_request, $body, $default = null)
{
    $api_locale = get_option('activity_api_locale');
    $api_host = get_option('activity_host_url');
    $api_key = get_option('activity_api_key');

    if ($method === 'get') {
        $json_data = wp_json_encode($body);

        $url = $api_host . $api_request;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_data),
                'Accept: application/json',
                'apiKey: ' . $api_key,
                'Accept-Language: ' . $api_locale,
            )
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $response = curl_exec($ch);

        return json_decode($response, true);
    } elseif ($method === 'post') {
        $response = wp_remote_post(
            $api_host . $api_request,
            array(
                'body' => wp_json_encode($body),
                'timeout' => 10,
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'apiKey' => $api_key,
                    'Accept-Language' => $api_locale,
                ),
                // 'data_format' => 'body',
            )
        );

        if (is_wp_error($response)) {
            return $default;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data)) {
            return $default;
        }

        return $data;
    } else {
        return $default;
    }
}