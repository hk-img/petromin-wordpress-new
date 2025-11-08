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

if (!function_exists('petromin_normalize_link')) {
    function petromin_normalize_link($link, $fallback = '#')
    {
        if (is_array($link)) {
            if (!empty($link['url'])) {
                $link = $link['url'];
            } elseif (!empty($link['ID'])) {
                $link = get_permalink($link['ID']);
            } elseif (!empty($link['id'])) {
                $link = get_permalink($link['id']);
            }
        }

        if (is_numeric($link)) {
            $link = get_permalink($link);
        }

        if (!is_string($link)) {
            $link = '';
        }

        $link = trim($link);

        if ($link === '') {
            return $fallback;
        }

        $special_protocols = ['mailto:', 'tel:', 'javascript:'];
        foreach ($special_protocols as $protocol) {
            if (stripos($link, $protocol) === 0) {
                return $link;
            }
        }

        $parsed_link = wp_parse_url($link);

        if ($parsed_link === false) {
            return $fallback;
        }

        // Already relative URLs should be returned as-is.
        if (empty($parsed_link['host']) && empty($parsed_link['scheme'])) {
            return $link;
        }

        $site_url_parts = wp_parse_url(home_url());

        if (!is_array($site_url_parts)) {
            return $link;
        }

        $hosts_match = isset($parsed_link['host'], $site_url_parts['host'])
            && strcasecmp($parsed_link['host'], $site_url_parts['host']) === 0;

        if (!$hosts_match) {
            return $link;
        }

        $relative_path = $parsed_link['path'] ?? '/';

        if ($relative_path === '') {
            $relative_path = '/';
        }

        // Remove the site path from the URL if WordPress is installed in a subdirectory.
        if (!empty($site_url_parts['path'])) {
            $site_path = rtrim($site_url_parts['path'], '/');

            if ($site_path !== '' && strpos($relative_path, $site_path) === 0) {
                $relative_path = substr($relative_path, strlen($site_path));
                if ($relative_path === '') {
                    $relative_path = '/';
                }
            }
        }

        $query = isset($parsed_link['query']) ? '?' . $parsed_link['query'] : '';
        $fragment = isset($parsed_link['fragment']) ? '#' . $parsed_link['fragment'] : '';

        $normalized = $relative_path . $query . $fragment;

        return $normalized !== '' ? $normalized : $fallback;
    }
}


if (!function_exists('petromin_get_social_icon_svg')) {
    function petromin_get_social_icon_svg($platform)
    {
        switch (strtolower(trim((string) $platform))) {
            case 'twitter':
            case 'x':
                return '<svg class="size-5 text-white" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"></path></svg>';
            case 'linkedin':
                return '<svg class="size-6 text-white" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M417.2 64H96.8C79.3 64 64 76.6 64 93.9V415c0 17.4 15.3 32.9 32.8 32.9h320.3c17.6 0 30.8-15.6 30.8-32.9V93.9C448 76.6 434.7 64 417.2 64zM183 384h-55V213h55v171zm-25.6-197h-.4c-17.6 0-29-13.1-29-29.5 0-16.7 11.7-29.5 29.7-29.5s29 12.7 29.4 29.5c0 16.4-11.4 29.5-29.7 29.5zM384 384h-55v-93.5c0-22.4-8-37.7-27.9-37.7-15.2 0-24.2 10.3-28.2 20.3-1.5 3.6-1.9 8.5-1.9 13.5V384h-55V213h55v23.8c8-11.4 20.5-27.8 49.6-27.8 36.1 0 63.4 23.8 63.4 75.1V384z"></path></svg>';
            case 'facebook':
                return '<svg class="size-5 text-white" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg"><path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"></path></svg>';
            case 'instagram':
                return '<svg class="size-6 text-white" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13.0281 2.00073C14.1535 2.00259 14.7238 2.00855 15.2166 2.02322L15.4107 2.02956C15.6349 2.03753 15.8561 2.04753 16.1228 2.06003C17.1869 2.1092 17.9128 2.27753 18.5503 2.52503C19.2094 2.7792 19.7661 3.12253 20.3219 3.67837C20.8769 4.2342 21.2203 4.79253 21.4753 5.45003C21.7219 6.0867 21.8903 6.81337 21.9403 7.87753C21.9522 8.1442 21.9618 8.3654 21.9697 8.58964L21.976 8.78373C21.9906 9.27647 21.9973 9.84686 21.9994 10.9723L22.0002 11.7179C22.0003 11.809 22.0003 11.903 22.0003 12L22.0002 12.2821L21.9996 13.0278C21.9977 14.1532 21.9918 14.7236 21.9771 15.2163L21.9707 15.4104C21.9628 15.6347 21.9528 15.8559 21.9403 16.1225C21.8911 17.1867 21.7219 17.9125 21.4753 18.55C21.2211 19.2092 20.8769 19.7659 20.3219 20.3217C19.7661 20.8767 19.2069 21.22 18.5503 21.475C17.9128 21.7217 17.1869 21.89 16.1228 21.94C15.8561 21.9519 15.6349 21.9616 15.4107 21.9694L15.2166 21.9757C14.7238 21.9904 14.1535 21.997 13.0281 21.9992L12.2824 22C12.1913 22 12.0973 22 12.0003 22L11.7182 22L10.9725 21.9993C9.8471 21.9975 9.27672 21.9915 8.78397 21.9768L8.58989 21.9705C8.36564 21.9625 8.14444 21.9525 7.87778 21.94C6.81361 21.8909 6.08861 21.7217 5.45028 21.475C4.79194 21.2209 4.23444 20.8767 3.67861 20.3217C3.12278 19.7659 2.78028 19.2067 2.52528 18.55C2.27778 17.9125 2.11028 17.1867 2.06028 16.1225C2.0484 15.8559 2.03871 15.6347 2.03086 15.4104L2.02457 15.2163C2.00994 14.7236 2.00327 14.1532 2.00111 13.0278L2.00098 10.9723C2.00284 9.84686 2.00879 9.27647 2.02346 8.78373L2.02981 8.58964C2.03778 8.3654 2.04778 8.1442 2.06028 7.87753C2.10944 6.81253 2.27778 6.08753 2.52528 5.45003C2.77944 4.7917 3.12278 4.2342 3.67861 3.67837C4.23444 3.12253 4.79278 2.78003 5.45028 2.52503C6.08778 2.27753 6.81278 2.11003 7.87778 2.06003C8.14444 2.04816 8.36564 2.03847 8.58989 2.03062L8.78397 2.02433C9.27672 2.00969 9.8471 2.00302 10.9725 2.00086L13.0281 2.00073ZM12.0003 7.00003C9.23738 7.00003 7.00028 9.23956 7.00028 12C7.00028 14.7629 9.23981 17 12.0003 17C14.7632 17 17.0003 14.7605 17.0003 12C17.0003 9.23713 14.7607 7.00003 12.0003 7.00003ZM12.0003 9.00003C13.6572 9.00003 15.0003 10.3427 15.0003 12C15.0003 13.6569 13.6576 15 12.0003 15C10.3434 15 9.00028 13.6574 9.00028 12C9.00028 10.3431 10.3429 9.00003 12.0003 9.00003ZM17.2503 5.50003C16.561 5.50003 16.0003 6.05994 16.0003 6.74918C16.0003 7.43843 16.5602 7.9992 17.2503 7.9992C17.9395 7.9992 18.5003 7.4393 18.5003 6.74918C18.5003 6.05994 17.9386 5.49917 17.2503 5.50003Z"></path></svg>';
            case 'youtube':
                return '<svg class="size-6 text-white" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9.522,15.553 L9.52125,8.80975 L16.00575,12.193 L9.522,15.553 Z M23.76,7.64125 C23.76,7.64125 23.52525,5.9875 22.806,5.25925 C21.89325,4.303 20.87025,4.2985 20.4015,4.243 C17.043,4 12.00525,4 12.00525,4 L11.99475,4 C11.99475,4 6.957,4 3.5985,4.243 C3.129,4.2985 2.10675,4.303 1.19325,5.25925 C0.474,5.9875 0.24,7.64125 0.24,7.64125 C0.24,7.64125 0,9.58375 0,11.5255 L0,13.3465 C0,15.289 0.24,17.23075 0.24,17.23075 C0.24,17.23075 0.474,18.8845 1.19325,19.61275 C2.10675,20.569 3.306,20.539 3.84,20.63875 C5.76,20.82325 12,20.88025 12,20.88025 C12,20.88025 17.043,20.87275 20.4015,20.62975 C20.87025,20.5735 21.89325,20.569 22.806,19.61275 C23.52525,18.8845 23.76,17.23075 23.76,17.23075 C23.76,17.23075 24,15.289 24,13.3465 L24,11.5255 C24,9.58375 23.76,7.64125 23.76,7.64125 L23.76,7.64125 Z"></path></svg>';
            default:
                return '';
        }
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

    acf_add_options_page([
        'page_title' => 'Footer Settings',
        'menu_title' => 'Footer Settings',
        'menu_slug' => 'footer-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
        'position' => 31
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
                        'type' => 'page_link',
                        'post_type' => '',
                        'allow_null' => 1,
                        'allow_archives' => 1,
                        'return_format' => 'url',
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
                        'type' => 'page_link',
                        'post_type' => '',
                        'allow_null' => 1,
                        'allow_archives' => 1,
                        'return_format' => 'url',
                        'default_value' => ''
                    ],
                    [
                        'key' => 'field_signup_link',
                        'label' => 'Sign Up Link',
                        'name' => 'signup_link',
                        'type' => 'page_link',
                        'post_type' => '',
                        'allow_null' => 1,
                        'allow_archives' => 1,
                        'return_format' => 'url',
                        'default_value' => ''
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
                        'type' => 'page_link',
                        'post_type' => '',
                        'allow_null' => 1,
                        'allow_archives' => 1,
                        'return_format' => 'url',
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

    acf_add_local_field_group([
        'key' => 'group_footer_settings',
        'title' => 'Footer Settings',
        'fields' => [
            [
                'key' => 'field_footer_brand',
                'label' => 'Brand Section',
                'name' => 'footer_brand',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_footer_logo',
                        'label' => 'Footer Logo',
                        'name' => 'logo',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'mime_types' => 'webp,png,svg',
                        'instructions' => 'Upload the footer logo. Recommended format: .webp',
                    ],
                    [
                        'key' => 'field_footer_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'new_lines' => 'br',
                        'rows' => 3,
                    ],
                ],
            ],
            [
                'key' => 'field_footer_highlight',
                'label' => 'Highlight Box',
                'name' => 'footer_highlight_box',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_footer_info_columns',
                        'label' => 'Info Columns',
                        'name' => 'info_columns',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Info Column',
                        'sub_fields' => [
                            [
                                'key' => 'field_footer_info_column_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                                'wrapper' => [
                                    'width' => '50%',
                                ],
                            ],
                            [
                                'key' => 'field_footer_info_lines',
                                'label' => 'Lines',
                                'name' => 'lines',
                                'type' => 'repeater',
                                'layout' => 'table',
                                'button_label' => 'Add Line',
                                'sub_fields' => [
                                    [
                                        'key' => 'field_footer_info_line_text',
                                        'label' => 'Text',
                                        'name' => 'line_text',
                                        'type' => 'text',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_footer_contact_phone',
                        'label' => 'Contact Phone',
                        'name' => 'contact_phone',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_footer_contact_email',
                        'label' => 'Contact Email',
                        'name' => 'contact_email',
                        'type' => 'email',
                    ],
                ],
            ],
            [
                'key' => 'field_footer_head_office_group',
                'label' => 'Head Office',
                'name' => 'footer_head_office',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_footer_head_office_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_footer_head_office_address',
                        'label' => 'Address',
                        'name' => 'address',
                        'type' => 'textarea',
                        'new_lines' => 'br',
                        'rows' => 4,
                    ],
                ],
            ],
            [
                'key' => 'field_footer_columns',
                'label' => 'Link Columns',
                'name' => 'footer_link_columns',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add Column',
                'sub_fields' => [
                    [
                        'key' => 'field_footer_column_title',
                        'label' => 'Column Title',
                        'name' => 'column_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_footer_primary_links',
                        'label' => 'Primary Links',
                        'name' => 'primary_links',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Link',
                        'sub_fields' => [
                            [
                                'key' => 'field_footer_primary_link_text',
                                'label' => 'Link Text',
                                'name' => 'link_text',
                                'type' => 'text',
                                'wrapper' => [
                                    'width' => '40%',
                                ],
                            ],
                            [
                                'key' => 'field_footer_primary_link_url',
                                'label' => 'Link URL',
                                'name' => 'link_url',
                                'type' => 'url',
                                'wrapper' => [
                                    'width' => '40%',
                                ],
                            ],
                            [
                                'key' => 'field_footer_primary_link_target',
                                'label' => 'Open in New Tab',
                                'name' => 'open_in_new_tab',
                                'type' => 'true_false',
                                'ui' => 1,
                                'wrapper' => [
                                    'width' => '20%',
                                ],
                                'default_value' => 0,
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_footer_secondary_links',
                        'label' => 'Secondary Links',
                        'name' => 'secondary_links',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Secondary Link',
                        'sub_fields' => [
                            [
                                'key' => 'field_footer_secondary_link_text',
                                'label' => 'Link Text',
                                'name' => 'link_text',
                                'type' => 'text',
                                'wrapper' => [
                                    'width' => '40%',
                                ],
                            ],
                            [
                                'key' => 'field_footer_secondary_link_url',
                                'label' => 'Link URL',
                                'name' => 'link_url',
                                'type' => 'url',
                                'wrapper' => [
                                    'width' => '40%',
                                ],
                            ],
                            [
                                'key' => 'field_footer_secondary_link_target',
                                'label' => 'Open in New Tab',
                                'name' => 'open_in_new_tab',
                                'type' => 'true_false',
                                'ui' => 1,
                                'wrapper' => [
                                    'width' => '20%',
                                ],
                                'default_value' => 0,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_footer_store_badges',
                'label' => 'Store Badges',
                'name' => 'footer_store_badges',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Badge',
                'sub_fields' => [
                    [
                        'key' => 'field_footer_store_badge_image',
                        'label' => 'Badge Image',
                        'name' => 'badge_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'mime_types' => 'webp,png,svg',
                    ],
                    [
                        'key' => 'field_footer_store_badge_link',
                        'label' => 'Badge Link',
                        'name' => 'badge_link',
                        'type' => 'url',
                    ],
                ],
            ],
            [
                'key' => 'field_footer_social_links',
                'label' => 'Social Links',
                'name' => 'footer_social_links',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Social Link',
                'sub_fields' => [
                    [
                        'key' => 'field_footer_social_platform',
                        'label' => 'Platform',
                        'name' => 'platform',
                        'type' => 'select',
                        'choices' => [
                            'x' => 'X (Twitter)',
                            'twitter' => 'Twitter',
                            'linkedin' => 'LinkedIn',
                            'facebook' => 'Facebook',
                            'instagram' => 'Instagram',
                            'youtube' => 'YouTube',
                        ],
                        'allow_null' => 0,
                        'ui' => 1,
                    ],
                    [
                        'key' => 'field_footer_social_url',
                        'label' => 'Profile URL',
                        'name' => 'url',
                        'type' => 'url',
                    ],
                    [
                        'key' => 'field_footer_social_new_tab',
                        'label' => 'Open in New Tab',
                        'name' => 'open_in_new_tab',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 1,
                    ],
                ],
            ],
            [
                'key' => 'field_footer_copyright',
                'label' => 'Copyright Text',
                'name' => 'footer_copyright',
                'type' => 'text',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'footer-settings',
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
                                'type' => 'date_picker',
                                'display_format' => 'F j, Y',
                                'return_format' => 'F j, Y',
                                'first_day' => 0,
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
                                'type' => 'date_picker',
                                'display_format' => 'F j, Y',
                                'return_format' => 'F j, Y',
                                'first_day' => 0,
                            ],
                            [
                                'key' => 'field_news_press_release_pdf_link',
                                'label' => 'Read More Link',
                                'name' => 'pdf_link',
                                'type' => 'url',
                                'instructions' => 'Provide the destination for the Read More button.',
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
                                'type' => 'date_picker',
                                'display_format' => 'F j, Y',
                                'return_format' => 'F j, Y',
                                'first_day' => 0,
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
                                'type' => 'date_picker',
                                'display_format' => 'F j, Y',
                                'return_format' => 'F j, Y',
                                'first_day' => 0,
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
                        'key' => 'field_news_podcasts_display_section',
                        'label' => 'Display Podcasts Section',
                        'name' => 'display_section',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'instructions' => 'Enable to show the Podcasts section on the page.',
                    ],
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
                'key' => 'field_login_redirect_url',
                'label' => 'External Login Redirect URL',
                'name' => 'redirect_url',
                'type' => 'url',
                'instructions' => 'When provided, visitors are automatically redirected to this URL instead of the on-page form.',
            ],
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
