<?php
function unlimited_andrenaline_import_activities()
{
    $whitelabelid = get_option('activity_host_url_label');
    $api_host = get_option('activity_host_url');
    $api_key = get_option('activity_api_key');

    $endpoint = "activity";
    $url = $api_host . $endpoint;

    $url = add_query_arg(
        array(
            'whiteLabelId' => $whitelabelid
        ),
        $url
    );

    $args = array(
        'headers' => array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'apiKey' => $api_key,
            'Accept-Language' => 'en'
        )
    );

    $response = wp_remote_get($url, $args);

    if (is_wp_error($response)) {
        echo "Something went wrong: " . $response->get_error_message();
        return;
    }

    $response_body = wp_remote_retrieve_body($response);
    $data = json_decode($response_body, true);

    if (empty($data)) {
        echo "No activities found.";
        return;
    }

    $activities_found = count($data);
    update_option('activities_found', $activities_found);

    $batch_size = 5;
    $batches = array_chunk($data, $batch_size);
    $activities_imported = 0;

    foreach ($batches as $batch) {
        foreach ($batch as $activity) {
            $existing_post = get_posts(
                array(
                    'post_type' => 'activity',
                    'meta_key' => 'field_webvortex_activity_id',
                    'meta_value' => $activity['id'],
                )
            );

            if ($existing_post) {
                $post_id = $existing_post[0]->ID;
                wp_update_post(
                    array(
                        'ID' => $post_id,
                        'post_title' => wp_strip_all_tags($activity['id'] . ' - ' . $activity['title']),
                    )
                );
            } else {
                $post_id = wp_insert_post(
                    array(
                        'post_title' => wp_strip_all_tags($activity['id'] . ' - ' . $activity['title']),
                        'post_type' => 'activity',
                        'post_status' => 'publish'
                    )
                );
            }

            if ($post_id && !is_wp_error($post_id)) {
                update_field('field_webvortex_activity_id', $activity['id'], $post_id);
                update_field('field_webvortex_title', $activity['title'], $post_id);
                update_field('field_webvortex_provider_id', $activity['providerId'], $post_id);
                update_field('field_webvortex_rating', $activity['rating'], $post_id);
                update_field('field_webvortex_active_months', $activity['activeMonths'], $post_id);
                update_field('field_webvortex_category_ids', implode(', ', $activity['categoryIds']), $post_id);
                update_field('field_webvortex_description', $activity['description'], $post_id);

                // Photos
                if (!empty($activity['photos'])) {
                    $photos = array();
                    foreach ($activity['photos'] as $photo) {
                        $photos[] = array(
                            'field_webvortex_photo_id' => $photo['id'],
                            'field_webvortex_photo_full' => $photo['full'],
                            'field_webvortex_photo_thumb' => $photo['thumb'],
                            'field_webvortex_photo_title' => $photo['title']
                        );
                    }
                    update_field('field_webvortex_photos', $photos, $post_id);
                }

                // Itineraries
                if (!empty($activity['itineraries'])) {
                    $itineraries = array();
                    foreach ($activity['itineraries'] as $itinerary) {
                        $included = array();
                        if (!empty($itinerary['details']['included'])) {
                            foreach ($itinerary['details']['included'] as $item) {
                                $included[] = array(
                                    'field_webvortex_included_id' => $item['id'],
                                    'field_webvortex_included_title' => $item['title']
                                );
                            }
                        }

                        $do_not_forget = array();
                        if (!empty($itinerary['details']['doNotForget'])) {
                            foreach ($itinerary['details']['doNotForget'] as $item) {
                                $do_not_forget[] = array(
                                    'field_webvortex_do_not_forget_id' => $item['id'],
                                    'field_webvortex_do_not_forget_title' => $item['title']
                                );
                            }
                        }

                        $ratings = array();
                        if (!empty($itinerary['ratings'])) {
                            foreach ($itinerary['ratings'] as $rating) {
                                $ratings[] = array(
                                    'field_webvortex_rating_score' => $rating['score'],
                                    'field_webvortex_rating_text' => $rating['text'],
                                    'field_webvortex_rating_fullname' => $rating['fullname']
                                );
                            }
                        }

                        $languages = array();
                        if (!empty($itinerary['spokenLanguages'])) {
                            foreach ($itinerary['spokenLanguages'] as $language) {
                                $languages[] = array(
                                    'field_webvortex_language_id' => $language['id'],
                                    'field_webvortex_language_acrn' => $language['acrn'],
                                    'field_webvortex_language_title' => $language['title']
                                );
                            }
                        }

                        $itineraries[] = array(
                            'field_webvortex_itinerary_id' => $itinerary['id'],
                            'field_webvortex_itinerary_title' => $itinerary['title'],
                            'field_webvortex_itinerary_description' => $itinerary['description'],
                            'field_webvortex_itinerary_level' => $itinerary['level'],
                            'field_webvortex_itinerary_details' => array(
                                'field_webvortex_itinerary_included' => $included,
                                'field_webvortex_itinerary_do_not_forget' => $do_not_forget
                            ),
                            'field_webvortex_itinerary_ratings' => $ratings,
                            'field_webvortex_itinerary_min_age' => $itinerary['min_age'],
                            'field_webvortex_itinerary_spoken_languages' => $languages,
                            'field_webvortex_itinerary_booking_type' => $itinerary['bookingType'],
                            'field_webvortex_itinerary_transport' => $itinerary['transport'],
                            'field_webvortex_itinerary_allow_multiple_facilities' => $itinerary['allowSelectionOfMultipleFacilities'],
                            'field_webvortex_itinerary_accommodation' => $itinerary['accommodation'],
                            'field_webvortex_itinerary_min_persons' => $itinerary['min_persons'],
                            'field_webvortex_itinerary_min_price' => $itinerary['min_price'],
                            'field_webvortex_cancellation_policy' => array(
                                'field_webvortex_policy_id' => $itinerary['cancellationPolicy']['id'],
                                'field_webvortex_policy_charge' => $itinerary['cancellationPolicy']['charge'],
                                'field_webvortex_policy_days_before_1' => $itinerary['cancellationPolicy']['days_before_1'],
                                'field_webvortex_policy_days_before_2' => $itinerary['cancellationPolicy']['days_before_2'],
                                'field_webvortex_policy_title' => $itinerary['cancellationPolicy']['title'],
                                'field_webvortex_policy_description' => $itinerary['cancellationPolicy']['description']
                            ),
                            'field_webvortex_itinerary_is_event' => $itinerary['isEvent'],
                            'field_webvortex_itinerary_difficulty' => $itinerary['difficulty'],
                            'field_webvortex_itinerary_latitude' => $itinerary['latitude'],
                            'field_webvortex_itinerary_longitude' => $itinerary['longitude'],
                            'field_webvortex_itinerary_duration' => $itinerary['duration']
                        );
                    }
                    update_field('field_webvortex_itineraries', $itineraries, $post_id);
                }

                // Increment activities imported count
                $activities_imported++;
            }
        }
    }

    update_option('activities_imported', $activities_imported);
    echo "Import completed. Total activities imported: $activities_imported";
}