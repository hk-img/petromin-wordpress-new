<?php


if (!function_exists('petromin_get_acf_image_data')) {
    function petromin_get_acf_image_data($image_field, $size = 'full', $fallback_url = '', $fallback_alt = '')
    {
        $image_id = 0;
        $url = '';
        $alt = '';

        if (is_array($image_field)) {
            if (!empty($image_field['ID'])) {
                $image_id = (int) $image_field['ID'];
            } elseif (!empty($image_field['id'])) {
                $image_id = (int) $image_field['id'];
            }

            if (!$url && !empty($image_field['url'])) {
                $url = $image_field['url'];
            }

            if (!$alt && !empty($image_field['alt'])) {
                $alt = $image_field['alt'];
            }
        } elseif (!empty($image_field)) {
            $image_id = (int) $image_field;
        }

        if ($image_id) {
            $resolved_url = wp_get_attachment_image_url($image_id, $size);

            if ($resolved_url) {
                $url = $resolved_url;
            }

            $meta_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

            if ($meta_alt) {
                $alt = $meta_alt;
            }
        }

        if (!$url && $fallback_url) {
            $url = $fallback_url;
        }

        if (!$alt && $fallback_alt) {
            $alt = $fallback_alt;
        }

        if (!$url) {
            return null;
        }

        return [
            'url' => $url,
            'alt' => $alt,
        ];
    }
}


function my_acf_google_map_api( $api ){
    $api['key'] = 'AIzaSyDC3RCcvMaCHd7VOf7hRhgceXDQ5cSFyGU';
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    };
    
    
    // Options Page add karo
    acf_add_options_page([
        'page_title' => 'Header Settings',
        'menu_title' => 'Header Settings',
        'menu_slug' => 'header-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
        'position' => 30
    ]);

    // ACF Field Group for Header Settings
    acf_add_local_field_group([
        'key' => 'group_header_settings',
        'title' => 'Header Settings',
        'fields' => [
            [
                'key' => 'field_header_logo',
                'label' => 'Header Logo',
                'name' => 'header_logo',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_desktop_logo',
                        'label' => 'Desktop Logo',
                        'name' => 'desktop_logo',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'instructions' => 'Recommended dimensions: 150x40px. Minimum dimensions: 75x20px. Allowed file type: .webp',
                        'default_value' => ['url' => get_template_directory_uri() . '/assets/img/petromin-logo-300x75-1.webp', 'alt' => 'Petromin Logo' ],
                        'mime_types' => 'webp'
                    ],
                    [
                        'key' => 'field_mobile_logo',
                        'label' => 'Mobile Logo',
                        'name' => 'mobile_logo',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'instructions' => 'Recommended dimensions: 100x30px. Minimum dimensions: 50x15px. Allowed file type: .webp',
                        'default_value' => ['url' => get_template_directory_uri() . '/assets/img/petromin-logo-300x75-1.webp', 'alt' => 'Petromin Logo' ],
                        'mime_types' => 'webp'
                    ],
                    [
                        'key' => 'field_logo_alt_text',
                        'label' => 'Logo Alt Text',
                        'name' => 'logo_alt_text',
                        'type' => 'text',
                        'default_value' => 'Petromin'
                    ],
                ],
            ],
            [
                'key' => 'field_navigation_menu',
                'label' => 'Navigation Menu',
                'name' => 'navigation_menu',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add Menu Item',
                'sub_fields' => [
                    [
                        'key' => 'field_menu_item_text',
                        'label' => 'Menu Text',
                        'name' => 'menu_text',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_menu_item_link',
                        'label' => 'Menu Link',
                        'name' => 'menu_link',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_menu_item_target',
                        'label' => 'Open in New Tab',
                        'name' => 'menu_item_target',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_user_menu',
                'label' => 'User Menu Settings',
                'name' => 'user_menu',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_login_link',
                        'label' => 'Login Link',
                        'name' => 'login_link',
                        'type' => 'text',
                        'default_value' => '#'
                    ],
                    [
                        'key' => 'field_signup_link',
                        'label' => 'Sign Up Link',
                        'name' => 'signup_link',
                        'type' => 'text',
                        'default_value' => '#'
                    ],
                ],
            ],
            [
                'key' => 'field_mobile_menu',
                'label' => 'Mobile Menu Items',
                'name' => 'mobile_menu',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add Mobile Menu Item',
                'sub_fields' => [
                    [
                        'key' => 'field_mobile_menu_text',
                        'label' => 'Menu Text',
                        'name' => 'mobile_menu_text',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_mobile_menu_link',
                        'label' => 'Menu Link',
                        'name' => 'mobile_menu_link',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'header-settings',
                ],
            ],
        ],
    ]);
    
    // About Us Page ACF Fields
    acf_add_local_field_group([
        'key' => 'group_about_us_page',
        'title' => 'About Us Page',
        'fields' => [
            [
                'key' => 'field_about_hero_section',
                'label' => 'Hero Section',
                'name' => 'hero_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_about_hero_title',
                        'label' => 'Heading',
                        'name' => 'hero_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_about_hero_image',
                        'label' => 'Background Image',
                        'name' => 'hero_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_about_hero_image_title',
                        'label' => 'Image Title Attribute',
                        'name' => 'hero_image_title',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_about_sidebar_media',
                'label' => 'Sidebar Media',
                'name' => 'roadmap_sidebar',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_about_sidebar_background',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_about_sidebar_car',
                        'label' => 'Foreground Image',
                        'name' => 'car_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                ],
            ],
            [
                'key' => 'field_about_intro',
                'label' => 'Introduction',
                'name' => 'about_intro',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_about_intro_heading',
                        'label' => 'Heading',
                        'name' => 'intro_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_about_intro_description',
                        'label' => 'Description',
                        'name' => 'intro_description',
                        'type' => 'textarea',
                        'new_lines' => 'br',
                    ],
                    [
                        'key' => 'field_about_intro_image',
                        'label' => 'Image',
                        'name' => 'intro_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                ],
            ],
            [
                'key' => 'field_about_journey',
                'label' => 'Journey Section',
                'name' => 'journey_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_about_journey_heading',
                        'label' => 'Heading',
                        'name' => 'journey_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_about_journey_slides',
                        'label' => 'Milestones',
                        'name' => 'slides',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Milestone',
                        'sub_fields' => [
                            [
                                'key' => 'field_about_journey_slide_year',
                                'label' => 'Year Label',
                                'name' => 'slide_year_label',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_about_journey_slide_description',
                                'label' => 'Description',
                                'name' => 'slide_description',
                                'type' => 'textarea',
                                'new_lines' => 'br',
                            ],
                            [
                                'key' => 'field_about_journey_slide_image',
                                'label' => 'Image',
                                'name' => 'slide_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_about_mvv',
                'label' => 'Mission, Vision & Values',
                'name' => 'mission_vision_values',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_about_vision_title',
                        'label' => 'Vision Title',
                        'name' => 'vision_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_about_vision_description',
                        'label' => 'Vision Description',
                        'name' => 'vision_description',
                        'type' => 'textarea',
                        'new_lines' => 'br',
                    ],
                    [
                        'key' => 'field_about_mission_title',
                        'label' => 'Mission Title',
                        'name' => 'mission_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_about_mission_description',
                        'label' => 'Mission Description',
                        'name' => 'mission_description',
                        'type' => 'textarea',
                        'new_lines' => 'br',
                    ],
                    [
                        'key' => 'field_about_values_title',
                        'label' => 'Values Title',
                        'name' => 'values_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_about_values_items',
                        'label' => 'Values',
                        'name' => 'values_items',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Value',
                        'sub_fields' => [
                            [
                                'key' => 'field_about_value_title',
                                'label' => 'Title',
                                'name' => 'value_title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_about_value_image',
                                'label' => 'Background Image',
                                'name' => 'value_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_about_value_icon',
                                'label' => 'Icon',
                                'name' => 'value_icon',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_about_excellence',
                'label' => 'Excellence Grid',
                'name' => 'excellence_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_about_excellence_heading',
                        'label' => 'Heading',
                        'name' => 'excellence_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_about_excellence_cards',
                        'label' => 'Cards',
                        'name' => 'cards',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Card',
                        'sub_fields' => [
                            [
                                'key' => 'field_about_excellence_card_title',
                                'label' => 'Title',
                                'name' => 'card_title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_about_excellence_card_description',
                                'label' => 'Description',
                                'name' => 'card_description',
                                'type' => 'textarea',
                                'new_lines' => 'br',
                            ],
                            [
                                'key' => 'field_about_excellence_card_image',
                                'label' => 'Image',
                                'name' => 'card_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_about_service_wheel',
                'label' => 'Service Wheel',
                'name' => 'service_wheel',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_about_wheel_heading',
                        'label' => 'Heading',
                        'name' => 'wheel_heading',
                        'type' => 'textarea',
                        'new_lines' => 'br',
                    ],
                    [
                        'key' => 'field_about_wheel_images',
                        'label' => 'Wheel Images',
                        'name' => 'wheel_images',
                        'type' => 'group',
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_about_wheel_desktop_image',
                                'label' => 'Desktop Image',
                                'name' => 'desktop_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_about_wheel_mobile_image',
                                'label' => 'Mobile Image',
                                'name' => 'mobile_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_about_wheel_services',
                        'label' => 'Services',
                        'name' => 'services',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Service',
                        'sub_fields' => [
                            [
                                'key' => 'field_about_wheel_service_title',
                                'label' => 'Title',
                                'name' => 'service_title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_about_wheel_service_description',
                                'label' => 'Description',
                                'name' => 'service_description',
                                'type' => 'textarea',
                                'new_lines' => 'br',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'about-us.php',
                ],
            ],
        ],
    ]);

    // Locate Us Page ACF Fields
    acf_add_local_field_group([
        'key' => 'group_locate_us_page',
        'title' => 'Locate Us Page',
        'fields' => [
            [
                'key' => 'field_locate_hero_section',
                'label' => 'Hero Section',
                'name' => 'hero_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_locate_hero_title',
                        'label' => 'Heading',
                        'name' => 'hero_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_locate_hero_image',
                        'label' => 'Background Image',
                        'name' => 'hero_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    // REMOVED: field_locate_hero_image_title - WordPress default alt text use karenge
                ],
            ],
            [
                'key' => 'field_locate_contact_section',
                'label' => 'Contact Information',
                'name' => 'contact_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_locate_registered_office_title',
                        'label' => 'Registered Office Title',
                        'name' => 'registered_office_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_locate_registered_office_address',
                        'label' => 'Registered Office Address',
                        'name' => 'registered_office_address',
                        'type' => 'textarea',
                    ],
                    [
                        'key' => 'field_locate_head_office_title',
                        'label' => 'Head Office Title',
                        'name' => 'head_office_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_locate_head_office_address',
                        'label' => 'Head Office Address',
                        'name' => 'head_office_address',
                        'type' => 'textarea',
                    ],
                    [
                        'key' => 'field_locate_station_hours_title',
                        'label' => 'Station Hours Title',
                        'name' => 'station_hours_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_locate_station_hours',
                        'label' => 'Station Hours Items',
                        'name' => 'station_hours',
                        'type' => 'repeater',
                        'layout' => 'table',
                        'button_label' => 'Add Hour Item',
                        'sub_fields' => [
                            [
                                'key' => 'field_locate_hour_item',
                                'label' => 'Hour Item',
                                'name' => 'hour_item',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_locate_contact_phone',
                        'label' => 'Contact Phone',
                        'name' => 'contact_phone',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_locate_contact_email',
                        'label' => 'Contact Email',
                        'name' => 'contact_email',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_locate_map_section',
                'label' => 'Map Section',
                'name' => 'map_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_locate_map_desktop_image',
                        'label' => 'Desktop Map Image',
                        'name' => 'desktop_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_locate_map_mobile_image',
                        'label' => 'Mobile Map Image',
                        'name' => 'mobile_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                ],
            ],
            [
                'key' => 'field_locate_service_centers',
                'label' => 'Service Centers',
                'name' => 'service_centers_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_locate_service_centers_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_locate_service_centers_items',
                        'label' => 'Service Centers',
                        'name' => 'centers',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Service Center',
                        'sub_fields' => [
                            [
                                'key' => 'field_locate_center_name',
                                'label' => 'Center Name',
                                'name' => 'name',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_locate_center_map_location',
                                'label' => 'Map Location',
                                'name' => 'map_location',
                                'type' => 'google_map',
                                'instructions' => 'Select location on map - address will be automatically used',
                                'required' => 1,
                            ],
                            [
                                'key' => 'field_locate_center_image',
                                'label' => 'Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            // REMOVED: field_locate_center_image_alt - WordPress default alt text use karenge
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_locate_faq_section',
                'label' => 'FAQ Section',
                'name' => 'faq_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_locate_faq_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_locate_faq_categories',
                        'label' => 'FAQ Categories',
                        'name' => 'categories',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add FAQ Category',
                        'sub_fields' => [
                            [
                                'key' => 'field_locate_faq_category_title',
                                'label' => 'Category Title',
                                'name' => 'title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_locate_faq_category_items',
                                'label' => 'FAQ Items',
                                'name' => 'items',
                                'type' => 'repeater',
                                'layout' => 'block',
                                'button_label' => 'Add FAQ Item',
                                'sub_fields' => [
                                    [
                                        'key' => 'field_locate_faq_question',
                                        'label' => 'Question',
                                        'name' => 'question',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'field_locate_faq_answer',
                                        'label' => 'Answer',
                                        'name' => 'answer',
                                        'type' => 'textarea',
                                    ],
                                    [
                                        'key' => 'field_locate_faq_active',
                                        'label' => 'Active by Default',
                                        'name' => 'active',
                                        'type' => 'true_false',
                                        'ui' => 1,
                                        'default_value' => 0,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'locate-us.php',
                ],
            ],
        ],
    ]);

    // News Room Page ACF Fields
    acf_add_local_field_group([
        'key' => 'group_news_room_page',
        'title' => 'News Room Page',
        'fields' => [
            [
                'key' => 'field_news_hero_section',
                'label' => 'Hero Section',
                'name' => 'hero_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_news_hero_title',
                        'label' => 'Heading',
                        'name' => 'hero_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_news_hero_image',
                        'label' => 'Background Image',
                        'name' => 'hero_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_news_hero_image_title',
                        'label' => 'Image Title Attribute',
                        'name' => 'hero_image_title',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_news_sidebar',
                'label' => 'Sidebar',
                'name' => 'sidebar_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_news_sidebar_about_text',
                        'label' => 'About Text',
                        'name' => 'about_text',
                        'type' => 'textarea',
                    ],
                    [
                        'key' => 'field_news_sidebar_categories',
                        'label' => 'Categories',
                        'name' => 'categories',
                        'type' => 'repeater',
                        'layout' => 'table',
                        'button_label' => 'Add Category',
                        'sub_fields' => [
                            [
                                'key' => 'field_news_category_name',
                                'label' => 'Category Name',
                                'name' => 'name',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_category_slug',
                                'label' => 'Category Slug',
                                'name' => 'slug',
                                'type' => 'text',
                                'instructions' => 'Should match section ID (e.g., media-mentions)',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_news_media_mentions',
                'label' => 'Media Mentions',
                'name' => 'media_mentions_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_news_media_mentions_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_news_media_mentions_items',
                        'label' => 'Media Mentions Items',
                        'name' => 'items',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Media Mention',
                        'sub_fields' => [
                            [
                                'key' => 'field_news_media_mention_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_media_mention_source',
                                'label' => 'Source',
                                'name' => 'source',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_media_mention_date',
                                'label' => 'Date',
                                'name' => 'date',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_media_mention_image',
                                'label' => 'Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_news_media_mention_link',
                                'label' => 'Link',
                                'name' => 'link',
                                'type' => 'url',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_news_press_releases',
                'label' => 'Press Releases',
                'name' => 'press_releases_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_news_press_releases_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_news_press_releases_items',
                        'label' => 'Press Releases Items',
                        'name' => 'items',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Press Release',
                        'sub_fields' => [
                            [
                                'key' => 'field_news_press_release_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_press_release_description',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                            ],
                            [
                                'key' => 'field_news_press_release_date',
                                'label' => 'Date',
                                'name' => 'date',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_press_release_pdf_link',
                                'label' => 'PDF Link',
                                'name' => 'pdf_link',
                                'type' => 'url',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_news_featured',
                'label' => 'Featured',
                'name' => 'featured_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_news_featured_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_news_featured_items',
                        'label' => 'Featured Items',
                        'name' => 'items',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Featured Item',
                        'sub_fields' => [
                            [
                                'key' => 'field_news_featured_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_featured_source',
                                'label' => 'Source',
                                'name' => 'source',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_featured_date',
                                'label' => 'Date',
                                'name' => 'date',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_featured_image',
                                'label' => 'Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_news_featured_link',
                                'label' => 'Link',
                                'name' => 'link',
                                'type' => 'url',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_news_events',
                'label' => 'Events',
                'name' => 'events_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_news_events_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_news_events_items',
                        'label' => 'Events Items',
                        'name' => 'items',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Event',
                        'sub_fields' => [
                            [
                                'key' => 'field_news_event_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_event_description',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                            ],
                            [
                                'key' => 'field_news_event_location',
                                'label' => 'Location',
                                'name' => 'location',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_event_date',
                                'label' => 'Date',
                                'name' => 'date',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_event_image',
                                'label' => 'Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_news_event_link',
                                'label' => 'Link',
                                'name' => 'link',
                                'type' => 'url',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_news_podcasts',
                'label' => 'Podcasts',
                'name' => 'podcasts_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_news_podcasts_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_news_podcasts_items',
                        'label' => 'Podcasts Items',
                        'name' => 'items',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Podcast',
                        'sub_fields' => [
                            [
                                'key' => 'field_news_podcast_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_news_podcast_description',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                            ],
                            [
                                'key' => 'field_news_podcast_video_url',
                                'label' => 'YouTube Video URL',
                                'name' => 'video_url',
                                'type' => 'url',
                            ],
                            [
                                'key' => 'field_news_podcast_link',
                                'label' => 'Link',
                                'name' => 'link',
                                'type' => 'url',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'news-room.php',
                ],
            ],
        ],
    ]);

    // ACF Field Group for Login Page
    acf_add_local_field_group([
        'key' => 'group_login_page',
        'title' => 'Login Page',
        'fields' => [
            [
                'key' => 'field_login_hero_section',
                'label' => 'Hero Section',
                'name' => 'hero_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_login_hero_title',
                        'label' => 'Heading',
                        'name' => 'hero_title',
                        'type' => 'text',
                        'default_value' => 'Welcome!'
                    ],
                    [
                        'key' => 'field_login_hero_subtitle',
                        'label' => 'Subheading',
                        'name' => 'hero_subtitle',
                        'type' => 'text',
                        'default_value' => 'Book your next service, track repairs, and get exclusive offers.'
                    ],
                    [
                        'key' => 'field_login_hero_image',
                        'label' => 'Background Image',
                        'name' => 'hero_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'mime_types' => 'webp',
                        'instructions' => 'Recommended dimensions: 1920x1080px. Minimum dimensions: 720x512px. Allowed file type: .webp'
                    ],
                ],
            ],
            [
                'key' => 'field_login_form_section',
                'label' => 'Login Form',
                'name' => 'form_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_login_placeholder_text',
                        'label' => 'Input Placeholder',
                        'name' => 'placeholder_text',
                        'type' => 'text',
                        'default_value' => 'Enter Email or Contact no'
                    ],
                    [
                        'key' => 'field_login_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'default_value' => 'Login'
                    ],
                    [
                        'key' => 'field_login_signup_text',
                        'label' => 'Sign Up Text',
                        'name' => 'signup_text',
                        'type' => 'text',
                        'default_value' => 'Don\'t have an account?'
                    ],
                    [
                        'key' => 'field_login_signup_link',
                        'label' => 'Sign Up Link',
                        'name' => 'signup_link',
                        'type' => 'page_link',
                        'post_type' => ['page'],
                        'default_value' => '#'
                    ],
                    [
                        'key' => 'field_login_signup_link_text',
                        'label' => 'Sign Up Link Text',
                        'name' => 'signup_link_text',
                        'type' => 'text',
                        'default_value' => 'Sign up'
                    ],
                    [
                        'key' => 'field_login_terms_text',
                        'label' => 'Terms & Conditions Text',
                        'name' => 'terms_text',
                        'type' => 'text',
                        'default_value' => 'By continuing, you agree to our Terms of Service and Privacy Policy'
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'login.php',
                ],
            ],
        ],
    ]);
});
