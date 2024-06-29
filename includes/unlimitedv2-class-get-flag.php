<?php
function get_country_flag_by_language($language)
{
    $api_url = 'https://restcountries.com/v3.1/lang/' . urlencode($language);
    $response = file_get_contents($api_url);
    if ($response === FALSE) {
        return null;
    }
    $countries = json_decode($response, true);
    if (empty($countries)) {
        return null;
    }
    return $countries[0]['flags']['png'];
}