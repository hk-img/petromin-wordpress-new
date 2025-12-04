<?php

if (!function_exists('petromin_theme_setup')) {
    /**
     * Register theme supports needed for the blog editor (featured images, title tag, etc.).
     */
    function petromin_theme_setup()
    {
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
    }
}
add_action('after_setup_theme', 'petromin_theme_setup');

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

    acf_add_options_page([
        'page_title' => 'Swiper Settings',
        'menu_title' => 'Swiper Settings',
        'menu_slug' => 'swiper-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
        'position' => 32,
        'icon_url' => 'dashicons-admin-settings',
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
    // ACF Field Group for Footer Settings
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
                'key' => 'field_footer_quick_links',
                'label' => 'Quick Links (Below Services)',
                'name' => 'footer_quick_links',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Quick Link',
                'instructions' => 'Add links that appear below the Services column (About Us, Blogs, Locate Us, Privacy Policy, etc.)',
                'sub_fields' => [
                    [
                        'key' => 'field_footer_quick_link_text',
                        'label' => 'Link Text',
                        'name' => 'link_text',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_footer_quick_link_url',
                        'label' => 'Link URL',
                        'name' => 'link_url',
                        'type' => 'page_link',
                    ],
                    [
                        'key' => 'field_footer_quick_link_target',
                        'label' => 'Open in New Tab',
                        'name' => 'open_in_new_tab',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                    ],
                ],
            ],
            [
                'key' => 'field_footer_other_links',
                'label' => 'Other Links (Below Latest Offers)',
                'name' => 'footer_other_links',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Other Link',
                'instructions' => 'Add links that appear below the Latest Offers column (Newsroom, Terms & Conditions, Refund Policy, etc.)',
                'sub_fields' => [
                    [
                        'key' => 'field_footer_other_link_text',
                        'label' => 'Link Text',
                        'name' => 'link_text',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_footer_other_link_url',
                        'label' => 'Link URL',
                        'name' => 'link_url',
                        'type' => 'page_link',
                    ],
                    [
                        'key' => 'field_footer_other_link_target',
                        'label' => 'Open in New Tab',
                        'name' => 'open_in_new_tab',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
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
                        'type' => 'page_link',
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
                        'type' => 'page_link',
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

    // Swiper Settings ACF Fields - Fixed Fields for All Swipers
    acf_add_local_field_group([
        'key' => 'group_swiper_settings',
        'title' => 'Swiper Settings',
        'fields' => [
            // Latest Offers Swiper
            [
                'key' => 'field_swiper_latest_offers',
                'label' => 'Latest Offers Swiper',
                'name' => 'swiper_latest_offers',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_latest_offers_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_latest_offers_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_latest_offers_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Timeline Section Swiper
            [
                'key' => 'field_swiper_timeline',
                'label' => 'Timeline Section Swiper',
                'name' => 'swiper_timeline',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_timeline_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_timeline_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_timeline_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Partners Section Swiper
            [
                'key' => 'field_swiper_partners',
                'label' => 'Partners Section Swiper',
                'name' => 'swiper_partners',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_partners_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_partners_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_partners_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Partners Footer Section Swiper
            [
                'key' => 'field_swiper_partners_footer',
                'label' => 'Partners Footer Section Swiper',
                'name' => 'swiper_partners_footer',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_partners_footer_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_partners_footer_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_partners_footer_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Brands Section Swiper Left
            [
                'key' => 'field_swiper_brands_left',
                'label' => 'Brands Section Swiper (Left)',
                'name' => 'swiper_brands_left',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_brands_left_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_brands_left_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 0,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_brands_left_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Brands Section Swiper Right
            [
                'key' => 'field_swiper_brands_right',
                'label' => 'Brands Section Swiper (Right)',
                'name' => 'swiper_brands_right',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_brands_right_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_brands_right_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 0,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_brands_right_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Brands Section Swiper Mobile
            [
                'key' => 'field_swiper_brands_mobile',
                'label' => 'Brands Section Swiper (Mobile)',
                'name' => 'swiper_brands_mobile',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_brands_mobile_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_brands_mobile_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 0,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_brands_mobile_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Testimonials Section Swiper
            [
                'key' => 'field_swiper_testimonials',
                'label' => 'Testimonials Section Swiper',
                'name' => 'swiper_testimonials',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_testimonials_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_testimonials_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_testimonials_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Benefits Section Swiper
            [
                'key' => 'field_swiper_benefits',
                'label' => 'Benefits Section Swiper',
                'name' => 'swiper_benefits',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_benefits_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_benefits_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_benefits_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // More Services Section Swiper
            [
                'key' => 'field_swiper_more_services',
                'label' => 'More Services Section Swiper',
                'name' => 'swiper_more_services',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_more_services_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_more_services_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_more_services_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Best Known Section Swiper
            [
                'key' => 'field_swiper_best_known',
                'label' => 'Best Known Section Swiper',
                'name' => 'swiper_best_known',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_best_known_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_best_known_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_best_known_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Our Services Section Swiper
            [
                'key' => 'field_swiper_our_services',
                'label' => 'Our Services Section Swiper',
                'name' => 'swiper_our_services',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_our_services_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_our_services_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_our_services_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // Blog Hero Section Swiper
            [
                'key' => 'field_swiper_blog_hero',
                'label' => 'Blog Hero Section Swiper',
                'name' => 'swiper_blog_hero',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_blog_hero_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 800,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_blog_hero_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_blog_hero_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
            // News Category Section Swiper
            [
                'key' => 'field_swiper_news_category',
                'label' => 'News Category Section Swiper',
                'name' => 'swiper_news_category',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_swiper_news_category_speed',
                        'label' => 'Speed (ms)',
                        'name' => 'speed',
                        'type' => 'range',
                        'default_value' => 700,
                        'min' => 100,
                        'max' => 5000,
                        'step' => 100,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_news_category_delay',
                        'label' => 'Autoplay Delay (ms)',
                        'name' => 'delay',
                        'type' => 'range',
                        'default_value' => 3000,
                        'min' => 0,
                        'max' => 10000,
                        'step' => 500,
                        'append' => 'ms',
                        'wrapper' => ['width' => '40%'],
                    ],
                    [
                        'key' => 'field_swiper_news_category_autoplay',
                        'label' => 'Enable Autoplay',
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'wrapper' => ['width' => '20%'],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'swiper-settings',
                ],
            ],
        ],
    ]);

    // Home Page ACF Fields
    acf_add_local_field_group([
        'key' => 'group_home_page',
        'title' => 'Home Page',
        'fields' => [
            [
                'key' => 'field_home_hero_section',
                'label' => 'Hero Section',
                'name' => 'hero_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_home_hero_background_video',
                        'label' => 'Background Video',
                        'name' => 'background_video',
                        'type' => 'file',
                        'return_format' => 'array',
                        'library' => 'all',
                        'mime_types' => 'mp4,webm,ogv',
                    ],
                    [
                        'key' => 'field_home_hero_headline_prefix',
                        'label' => 'Headline Prefix',
                        'name' => 'headline_prefix',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_home_hero_headline_highlight',
                        'label' => 'Headline Highlight',
                        'name' => 'headline_highlight',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_home_hero_headline_suffix',
                        'label' => 'Headline Suffix',
                        'name' => 'headline_suffix',
                        'type' => 'text',
                        'instructions' => 'Optional overall suffix text. If left blank you can manage primary/secondary lines below.',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_home_hero_headline_suffix_primary',
                        'label' => 'Headline Suffix (Primary Line)',
                        'name' => 'headline_suffix_primary',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_home_hero_headline_suffix_secondary',
                        'label' => 'Headline Suffix (Secondary Line)',
                        'name' => 'headline_suffix_secondary',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_home_hero_features',
                        'label' => 'Feature Badges',
                        'name' => 'features',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Feature',
                        'sub_fields' => [
                            [
                                'key' => 'field_home_hero_feature_title',
                                'label' => 'Title',
                                'name' => 'feature_title',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_home_hero_feature_subtitle',
                                'label' => 'Subtitle',
                                'name' => 'feature_subtitle',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_home_hero_feature_icon',
                                'label' => 'Icon',
                                'name' => 'feature_icon',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_home_services_tabs_section',
                'label' => 'Services Tabs Section',
                'name' => 'services_tabs_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_home_services_tabs_heading',
                        'label' => 'Heading',
                        'name' => 'heading',
                        'type' => 'text',
                    ],
                ],
            ],
            // Timeline Section: keep editable heading and navigation icon in ACF (slides moved to CPT 'milestone')
            [
                'key' => 'field_home_timeline_section',
                'label' => 'Timeline Section',
                'name' => 'timeline_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_home_timeline_heading',
                        'label' => 'Heading',
                        'name' => 'heading',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_home_timeline_navigation_icon',
                        'label' => 'Navigation Icon',
                        'name' => 'navigation_icon',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                ],
            ],
            [
                'key' => 'field_home_partner_highlights_section',
                'label' => 'Partner Highlights Section',
                'name' => 'partner_highlights_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_home_partner_highlights_icon',
                        'label' => 'Highlight Icon',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_home_partner_highlights_items',
                        'label' => 'Highlight Items',
                        'name' => 'items',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Highlight',
                        'sub_fields' => [
                            [
                                'key' => 'field_home_partner_highlights_item_text',
                                'label' => 'Text',
                                'name' => 'item_text',
                                'type' => 'text',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_home_digital_checkup_section',
                'label' => 'Digital Car Health Check-up Section',
                'name' => 'digital_checkup_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_digital_checkup_heading_prefix',
                        'label' => 'Heading Prefix (Before highlighted text)',
                        'name' => 'heading_prefix',
                        'type' => 'text',
                        'default_value' => 'Get a',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_digital_checkup_heading_highlight',
                        'label' => 'Heading Highlight Text',
                        'name' => 'heading_highlight',
                        'type' => 'text',
                        'default_value' => 'FREE',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_digital_checkup_heading_suffix',
                        'label' => 'Heading Suffix (After highlighted text)',
                        'name' => 'heading_suffix',
                        'type' => 'text',
                        'default_value' => 'full digital <br />car health check-up',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_digital_checkup_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'default_value' => 'Get It Now',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_digital_checkup_button_link',
                        'label' => 'Button Link',
                        'name' => 'button_link',
                        'type' => 'page_link',
                        'default_value' => '#',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_digital_checkup_original_price',
                        'label' => 'Original Price Text',
                        'name' => 'original_price',
                        'type' => 'text',
                        'default_value' => 'Originally ₹249*',
                    ],
                    [
                        'key' => 'field_digital_checkup_main_image',
                        'label' => 'Main Car Image',
                        'name' => 'main_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_digital_checkup_background_desktop',
                        'label' => 'Desktop Background Image',
                        'name' => 'background_desktop',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_digital_checkup_background_mobile',
                        'label' => 'Mobile Background Image',
                        'name' => 'background_mobile',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_digital_checkup_icon_image',
                        'label' => 'Icon Image',
                        'name' => 'icon_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_digital_checkup_features',
                        'label' => 'Features List',
                        'name' => 'features',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Feature',
                        'sub_fields' => [
                            [
                                'key' => 'field_digital_checkup_feature_text',
                                'label' => 'Feature Text',
                                'name' => 'feature_text',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_digital_checkup_check_icon',
                        'label' => 'Check List Icon',
                        'name' => 'check_icon',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                ],
            ],
            [
                'key' => 'field_home_brands_section',
                'label' => 'Brands Section',
                'name' => 'brands_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_brands_heading',
                        'label' => 'Section Heading',
                        'name' => 'heading',
                        'type' => 'text',
                        'default_value' => 'Expert care for every make and model.',
                    ],
                    [
                        'key' => 'field_brands_left_slider',
                        'label' => 'Left Slider Brands',
                        'name' => 'left_slider',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Brand',
                        'sub_fields' => [
                            [
                                'key' => 'field_brand_image',
                                'label' => 'Brand Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_brand_name',
                                'label' => 'Brand Name',
                                'name' => 'name',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_brands_right_slider',
                        'label' => 'Right Slider Brands',
                        'name' => 'right_slider',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Brand',
                        'sub_fields' => [
                            [
                                'key' => 'field_brand_image_right',
                                'label' => 'Brand Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_brand_name_right',
                                'label' => 'Brand Name',
                                'name' => 'name',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_brands_mobile_slider',
                        'label' => 'Mobile Slider Brands',
                        'name' => 'mobile_slider',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Brand',
                        'sub_fields' => [
                            [
                                'key' => 'field_brand_image_mobile',
                                'label' => 'Brand Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_brand_name_mobile',
                                'label' => 'Brand Name',
                                'name' => 'name',
                                'type' => 'text',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_home_app_section',
                'label' => 'App Promotion Section',
                'name' => 'app_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_app_heading_line1',
                        'label' => 'Heading Line 1',
                        'name' => 'heading_line1',
                        'type' => 'text',
                        'default_value' => 'All things car.',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_app_heading_line2',
                        'label' => 'Heading Line 2',
                        'name' => 'heading_line2',
                        'type' => 'text',
                        'default_value' => 'One tap away.',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                    ],
                    [
                        'key' => 'field_app_features',
                        'label' => 'App Features',
                        'name' => 'features',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Feature',
                        'sub_fields' => [
                            [
                                'key' => 'field_app_feature_text',
                                'label' => 'Feature Text',
                                'name' => 'feature_text',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'key' => 'field_app_play_store_image',
                        'label' => 'Play Store Button Image',
                        'name' => 'play_store_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'wrapper' => [
                            'width' => '25%',
                        ],
                    ],
                    [
                        'key' => 'field_app_play_store_link',
                        'label' => 'Play Store Link',
                        'name' => 'play_store_link',
                        'type' => 'page_link',
                        'default_value' => '#',
                        'wrapper' => [
                            'width' => '25%',
                        ],
                    ],
                    [
                        'key' => 'field_app_app_store_image',
                        'label' => 'App Store Button Image',
                        'name' => 'app_store_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'wrapper' => [
                            'width' => '25%',
                        ],
                    ],
                    [
                        'key' => 'field_app_app_store_link',
                        'label' => 'App Store Link',
                        'name' => 'app_store_link',
                        'type' => 'page_link',
                        'default_value' => '#',
                        'wrapper' => [
                            'width' => '25%',
                        ],
                    ],
                    [
                        'key' => 'field_app_video',
                        'label' => 'Promo Video',
                        'name' => 'video',
                        'type' => 'file',
                        'return_format' => 'array',
                        'library' => 'all',
                        'mime_types' => 'mp4,webm,ogv',
                    ],
                    [
                        'key' => 'field_app_background_desktop',
                        'label' => 'Desktop Background Image',
                        'name' => 'background_desktop',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_app_background_mobile',
                        'label' => 'Mobile Background Image',
                        'name' => 'background_mobile',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_app_feature_icon',
                        'label' => 'Feature List Icon',
                        'name' => 'feature_icon',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                ],
            ],
            [
                'key' => 'field_home_testimonials_section',
                'label' => 'Testimonials Section',
                'name' => 'testimonials_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_testimonials_heading',
                        'label' => 'Section Heading',
                        'name' => 'heading',
                        'type' => 'text',
                        'default_value' => 'Your trust drive us.',
                    ],
                    [
                        'key' => 'field_testimonials_nav_icon',
                        'label' => 'Navigation Arrow Icon',
                        'name' => 'nav_icon',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ],
                    [
                        'key' => 'field_testimonials_slides',
                        'label' => 'Testimonial Slides',
                        'name' => 'slides',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Testimonial Slide',
                        'instructions' => 'Note: Please add minimum 3 slides to ensure the swiper displays properly in loop mode with centered slides enabled.',
                        'sub_fields' => [
                            [
                                'key' => 'field_testimonial_type',
                                'label' => 'Slide Type',
                                'name' => 'slide_type',
                                'type' => 'select',
                                'choices' => [
                                    'text' => 'Text Testimonial',
                                    'video' => 'Video Testimonial',
                                ],
                                'default_value' => 'text',
                                'ui' => 1,
                            ],
                            [
                                'key' => 'field_testimonial_rating',
                                'label' => 'Rating (1-5 stars)',
                                'name' => 'rating',
                                'type' => 'number',
                                'min' => 1,
                                'max' => 5,
                                'default_value' => 5,
                                'conditional_logic' => [
                                    [
                                        [
                                            'field' => 'field_testimonial_type',
                                            'operator' => '==',
                                            'value' => 'text',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'key' => 'field_testimonial_text',
                                'label' => 'Testimonial Text',
                                'name' => 'testimonial_text',
                                'type' => 'textarea',
                                'new_lines' => 'br',
                                'conditional_logic' => [
                                    [
                                        [
                                            'field' => 'field_testimonial_type',
                                            'operator' => '==',
                                            'value' => 'text',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'key' => 'field_testimonial_author',
                                'label' => 'Author Name',
                                'name' => 'author_name',
                                'type' => 'text',
                                'conditional_logic' => [
                                    [
                                        [
                                            'field' => 'field_testimonial_type',
                                            'operator' => '==',
                                            'value' => 'text',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'key' => 'field_testimonial_video_image',
                                'label' => 'Video Thumbnail Image',
                                'name' => 'video_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                                'conditional_logic' => [
                                    [
                                        [
                                            'field' => 'field_testimonial_type',
                                            'operator' => '==',
                                            'value' => 'video',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'key' => 'field_testimonial_video_url',
                                'label' => 'Video URL',
                                'name' => 'video_url',
                                'type' => 'url',
                                'conditional_logic' => [
                                    [
                                        [
                                            'field' => 'field_testimonial_type',
                                            'operator' => '==',
                                            'value' => 'video',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'field_home_faq_section',
                'label' => 'FAQ Section',
                'name' => 'faq_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_faq_heading',
                        'label' => 'Section Heading',
                        'name' => 'heading',
                        'type' => 'text',
                        'default_value' => 'FAQs',
                    ],
                    [
                        'key' => 'field_faq_items',
                        'label' => 'FAQ Items',
                        'name' => 'faq_items',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add FAQ Item',
                        'sub_fields' => [
                            [
                                'key' => 'field_faq_question',
                                'label' => 'Question',
                                'name' => 'question',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_faq_answer',
                                'label' => 'Answer',
                                'name' => 'answer',
                                'type' => 'textarea',
                                'new_lines' => 'br',
                            ],
                            [
                                'key' => 'field_faq_is_active',
                                'label' => 'Open by Default',
                                'name' => 'is_active',
                                'type' => 'true_false',
                                'ui' => 1,
                                'default_value' => 0,
                                'instructions' => 'If enabled, this FAQ will be open when the page loads',
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
                    'value' => 'index.php',
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
            // Journey Section: keep editable heading in ACF (slides moved to CPT 'milestone')
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
                                'key' => 'field_locate_center_country',
                                'label' => 'Country',
                                'name' => 'country',
                                'type' => 'text',
                                'readonly' => 1,
                                'instructions' => 'Auto-filled from map location',
                                'wrapper' => [
                                    'width' => '33.333%',
                                    'class' => 'acf-hidden-field'
                                ]
                            ],
                            [
                                'key' => 'field_locate_center_state',
                                'label' => 'State',
                                'name' => 'state',
                                'type' => 'text',
                                'readonly' => 1,
                                'instructions' => 'Auto-filled from map location',
                                'wrapper' => [
                                    'width' => '33.333%',
                                    'class' => 'acf-hidden-field'
                                ]
                            ],
                            [
                                'key' => 'field_locate_center_city',
                                'label' => 'City',
                                'name' => 'city',
                                'type' => 'text',
                                'readonly' => 1,
                                'instructions' => 'Auto-filled from map location',
                                'wrapper' => [
                                    'width' => '33.333%',
                                    'class' => 'acf-hidden-field'
                                ]
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
                                'type' => 'page_link',
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
                                'type' => 'page_link',
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
                                'type' => 'page_link',
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
                                'type' => 'page_link',
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
                                'type' => 'page_link',
                            ],
                            [
                                'key' => 'field_news_podcast_link',
                                'label' => 'Link',
                                'name' => 'link',
                                'type' => 'page_link',
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
                'type' => 'page_link',
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
    
    // Services Page ACF Fields
    acf_add_local_field_group([
        'key' => 'group_services_page',
        'title' => 'Services Page',
        'fields' => [
            [
                'key' => 'field_services_hero_section',
                'label' => 'Hero Section',
                'name' => 'hero_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_services_hero_image',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'instructions' => 'Recommended dimensions: 1920x1080px. Allowed file type: .webp'
                    ],
                    [
                        'key' => 'field_services_hero_heading_prefix',
                        'label' => 'Heading Prefix',
                        'name' => 'heading_prefix',
                        'type' => 'text',
                        'default_value' => 'All-round expert',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_services_hero_heading_highlight',
                        'label' => 'Heading Highlight Text',
                        'name' => 'heading_highlight',
                        'type' => 'text',
                        'default_value' => 'car care',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_services_hero_heading_suffix',
                        'label' => 'Heading Suffix',
                        'name' => 'heading_suffix',
                        'type' => 'text',
                        'default_value' => 'all at one place.',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_services_hero_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'new_lines' => 'br',
                        'default_value' => 'Choose your services to get started.<br>We\'ll adapt the care plan to your car\'s real-time condition.'
                    ]
                ],
            ],
            [
                'key' => 'field_services_our_services_section',
                'label' => 'Our Services Section',
                'name' => 'our_services_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_services_section_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                        'default_value' => 'Our Services'
                    ],
                    [
                        'key' => 'field_services_navigation_icons',
                        'label' => 'Navigation Icons',
                        'name' => 'navigation_icons',
                        'type' => 'group',
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_services_left_arrow_icon',
                                'label' => 'Left Arrow Icon',
                                'name' => 'left_arrow_icon',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ],
                            [
                                'key' => 'field_services_right_arrow_icon',
                                'label' => 'Right Arrow Icon',
                                'name' => 'right_arrow_icon',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                            ]
                        ]
                    ],
                ],
            ],
            [
                'key' => 'field_services_features_section',
                'label' => 'Features Section',
                'name' => 'features_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_services_features_heading',
                        'label' => 'Heading',
                        'name' => 'heading',
                        'type' => 'text',
                        'default_value' => 'Feel the Petromin Express difference.'
                    ],
                    [
                        'key' => 'field_services_features_list',
                        'label' => 'Features List',
                        'name' => 'features_list',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add Feature',
                        'sub_fields' => [
                            [
                                'key' => 'field_service_feature_text',
                                'label' => 'Feature Text',
                                'name' => 'feature_text',
                                'type' => 'text',
                            ]
                        ]
                    ],
                    [
                        'key' => 'field_services_features_image',
                        'label' => 'Right Side Image',
                        'name' => 'features_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'instructions' => 'Recommended dimensions: 600x400px. Allowed file type: .webp'
                    ]
                ],
            ],
            [
                'key' => 'field_services_app_section',
                'label' => 'App Promotion Section',
                'name' => 'app_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_services_app_trusted_text',
                        'label' => 'Trusted By Text',
                        'name' => 'trusted_text',
                        'type' => 'text',
                        'default_value' => 'Trusted by 1 lakh+ car owners across India'
                    ],
                    [
                        'key' => 'field_services_app_user_images',
                        'label' => 'User Profile Images',
                        'name' => 'user_images',
                        'type' => 'repeater',
                        'layout' => 'table',
                        'button_label' => 'Add User Image',
                        'sub_fields' => [
                            [
                                'key' => 'field_service_user_image',
                                'label' => 'User Image',
                                'name' => 'user_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'thumbnail',
                            ]
                        ],
                        'max' => 3
                    ],
                    [
                        'key' => 'field_services_app_heading_line1',
                        'label' => 'Heading Line 1',
                        'name' => 'heading_line1',
                        'type' => 'text',
                        'default_value' => 'Tap.',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_services_app_heading_line2',
                        'label' => 'Heading Line 2',
                        'name' => 'heading_line2',
                        'type' => 'text',
                        'default_value' => 'Track.',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_services_app_heading_line3',
                        'label' => 'Heading Line 3',
                        'name' => 'heading_line3',
                        'type' => 'text',
                        'default_value' => 'Take control.',
                        'wrapper' => [
                            'width' => '33.333%',
                        ],
                    ],
                    [
                        'key' => 'field_services_app_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'text',
                        'default_value' => 'Install now and enjoy exclusive servicing offers and discounts.'
                    ],
                    [
                        'key' => 'field_services_app_store_badges',
                        'label' => 'App Store Badges',
                        'name' => 'app_store_badges',
                        'type' => 'group',
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'key' => 'field_services_play_store_image',
                                'label' => 'Play Store Badge',
                                'name' => 'play_store_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                                'wrapper' => [
                                    'width' => '25%',
                                ],
                            ],
                            [
                                'key' => 'field_services_play_store_link',
                                'label' => 'Play Store Link',
                                'name' => 'play_store_link',
                                'type' => 'url',
                                'default_value' => '#',
                                'wrapper' => [
                                    'width' => '25%',
                                ],
                            ],
                            [
                                'key' => 'field_services_app_store_image',
                                'label' => 'App Store Badge',
                                'name' => 'app_store_image',
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                                'wrapper' => [
                                    'width' => '25%',
                                ],
                            ],
                            [
                                'key' => 'field_services_app_store_link',
                                'label' => 'App Store Link',
                                'name' => 'app_store_link',
                                'type' => 'url',
                                'default_value' => '#',
                                'wrapper' => [
                                    'width' => '25%',
                                ],
                            ]
                        ]
                    ],
                    [
                        'key' => 'field_services_app_phone_image',
                        'label' => 'Phone Hand Image',
                        'name' => 'phone_image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'instructions' => 'Image of hand holding phone with app'
                    ]
                ],
            ],
            [
                'key' => 'field_services_faq_section',
                'label' => 'FAQ Section',
                'name' => 'faq_section',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_services_faq_heading',
                        'label' => 'Section Heading',
                        'name' => 'section_heading',
                        'type' => 'text',
                        'default_value' => 'Commonly Asked Questions'
                    ],
                    [
                        'key' => 'field_services_faq_items',
                        'label' => 'FAQ Items',
                        'name' => 'faq_items',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => 'Add FAQ Item',
                        'sub_fields' => [
                            [
                                'key' => 'field_service_faq_question',
                                'label' => 'Question',
                                'name' => 'question',
                                'type' => 'text',
                            ],
                            [
                                'key' => 'field_service_faq_answer',
                                'label' => 'Answer',
                                'name' => 'answer',
                                'type' => 'textarea',
                                'new_lines' => 'br',
                            ],
                            [
                                'key' => 'field_service_faq_is_active',
                                'label' => 'Open by Default',
                                'name' => 'is_active',
                                'type' => 'true_false',
                                'ui' => 1,
                                'default_value' => 0,
                                'instructions' => 'If enabled, this FAQ will be open when the page loads'
                            ]
                        ]
                    ]
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'services.php',
                ],
            ],
        ],
    ]);

    acf_add_local_field_group([
        'key' => 'group_service_details',
        'title' => 'Service Details',
        'fields' => array(
            // Hero Section
            array(
                'key' => 'field_service_hero_description',
                'label' => 'Hero Description',
                'name' => 'hero_description',
                'type' => 'textarea',
                'instructions' => 'Description below the main title',
                'required' => 0,
                'default_value' => '',
            ),
            array(
                'key' => 'field_highlight',
                'label' => 'Highlight Text',
                'name' => 'highlight',
                'type' => 'text',
                'instructions' => 'Optional highlighted text to display between description and buttons on home page service section',
                'default_value' => '',
            ),
            array(
                'key' => 'field_service_button_text',
                'label' => 'Button Text',
                'name' => 'button_text',
                'type' => 'text',
                'default_value' => 'Add to list',
                'wrapper' => [
                    'width' => '50%',
                ]
            ),
            array(
                'key' => 'field_service_button_link',
                'label' => 'Button Link',
                'name' => 'button_link',
                'type' => 'page_link',
                'instructions' => 'Select the page to link to',
                'wrapper' => [
                    'width' => '50%',
                ]
            ),
            
            // Problems Section
            array(
                'key' => 'field_problems_title',
                'label' => 'Problems Section Title',
                'name' => 'problems_title',
                'type' => 'text',
                'default_value' => 'Tired of these?',
                'instructions' => 'Title for the problems section',
            ),
            array(
                'key' => 'field_service_problems',
                'label' => 'Common Problems',
                'name' => 'problems',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Add Problem',
                'sub_fields' => array(
                    array(
                        'key' => 'field_problem_title',
                        'label' => 'Problem Title',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_problem_icon',
                        'label' => 'Icon Image',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                        'mime_types' => 'webp,png,svg,jpg,jpeg',
                        'instructions' => 'Upload custom icon image. Supported formats: webp, png, svg, jpg',
                    ),
                ),
            ),
            
            // Services Included
            array(
                'key' => 'field_services_title',
                'label' => 'Services Section Title',
                'name' => 'services_title',
                'type' => 'text',
                'default_value' => "Here's what your car gets",
                'instructions' => 'Title for the services section',
            ),
            array(
                'key' => 'field_service_included',
                'label' => 'Services Included',
                'name' => 'services_included',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Add Service',
                'sub_fields' => array(
                    array(
                        'key' => 'field_service_title',
                        'label' => 'Service Title',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_service_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'default_value' => '',
                    ),
                    array(
                        'key' => 'field_service_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ),
                ),
            ),
            array(
                'key' => 'field_services_overview_note',
                'label' => 'Services Overview Note',
                'name' => 'services_overview_note',
                'type' => 'text',
                'default_value' => "For a complete overview of what\'s covered, get in touch.",
                'instructions' => 'Overview Note for the services section',
            ),
            
            // Savings Section
            array(
                'key' => 'field_savings_title',
                'label' => 'Savings Section Title',
                'name' => 'savings_title',
                'type' => 'text',
                'default_value' => '',
            ),
            array(
                'key' => 'field_savings_description',
                'label' => 'Savings Description',
                'name' => 'savings_description',
                'type' => 'textarea',
                'default_value' => '',
            ),
            array(
                'key' => 'field_savings_button_text',
                'label' => 'Savings Button Text',
                'name' => 'savings_button_text',
                'type' => 'text',
                'default_value' => 'Know More',
                'wrapper' => array(
                    'width' => '50%',
                ),
            ),
            array(
                'key' => 'field_savings_button_link',
                'label' => 'Savings Button Link',
                'name' => 'savings_button_link',
                'type' => 'page_link',
                'instructions' => 'Select the page to link to',
                'wrapper' => array(
                    'width' => '50%',
                ),
            ),
            array(
                'key' => 'field_savings_image',
                'label' => 'Savings Image',
                'name' => 'savings_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ),
            // Additional backend-only images for cross-site service usage
            array(
                'key' => 'field_for_services_page_image',
                'label' => "For Services Page Section's",
                'name' => 'for_services_page_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
                'instructions' => 'Upload a .webp image (recommended / minimum size: 352x478). This image is stored as post meta for reuse on other pages. It will not be shown on the single service frontend.',
                'mime_types' => 'webp',
            ),
            array(
                'key' => 'field_service_icon',
                'label' => 'Service Icon',
                'name' => 'service_icon',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
                'instructions' => 'Upload a .webp icon image (minimum size: 100x100). This is intended for use in lists/cards on other pages. It will not be displayed on the single service page.',
                'mime_types' => 'webp',
            ),
            array(
                'key' => 'field_home_page_service_image',
                'label' => "Home Page Service Section's",
                'name' => 'home_page_service_image',
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
                'instructions' => 'Upload a .webp image (recommended / minimum size: 730x437). This image is stored for display in the home page service sections. It will not be shown on the single service page.',
                'mime_types' => 'webp',
            ),
            
            // FAQ Section
            array(
                'key' => 'field_faq_title',
                'label' => 'Blog Section Title',
                'name' => 'faq_title',
                'type' => 'text',
                'default_value' => 'It\'s best you know these.',
                'instructions' => 'Title for the blog section',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'service',
                ),
            ),
        ),
    ]);

    // ACF fields for Milestone CPT - small editor fields to manage year and image for each milestone.
    acf_add_local_field_group([
        'key' => 'group_milestone_fields',
        'title' => 'Milestone Fields',
        'fields' => [
            [
                'key' => 'field_milestone_year',
                'label' => 'Year / Label',
                'name' => 'milestone_year',
                'type' => 'text',
                'instructions' => 'Optional year or short label for the milestone (falls back to post title).',
            ],
            [
                'key' => 'field_milestone_description',
                'label' => 'Milestone Description',
                'name' => 'milestone_description',
                'type' => 'textarea',
                'new_lines' => 'br',
                'instructions' => 'Short description shown under the year in the slider. You can also use the post content; this field takes precedence.',
            ],
            [
                'key' => 'field_milestone_image',
                'label' => 'Milestone Image',
                'name' => 'milestone_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'milestone',
                ],
            ],
        ],
    ]);

    // ACF Field Group for Offers
    acf_add_local_field_group([
        'key' => 'group_offer_fields',
        'title' => 'Offer Details',
        'fields' => [
            [
                'key' => 'field_offer_image',
                'label' => 'Offer Image',
                'name' => 'offer_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'instructions' => 'Upload a high-quality image for the offer',
            ],
            [
                'key' => 'field_offer_short_description',
                'label' => 'Short Description',
                'name' => 'offer_short_description',
                'type' => 'text',
                'instructions' => 'Brief description for carousel/listings (max 150 chars)',
            ],
            [
                'key' => 'field_offer_starting_price',
                'label' => 'Starting Price',
                'name' => 'offer_starting_price',
                'type' => 'text',
                'instructions' => 'e.g., 1,399 (without currency symbol)',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_offer_price_currency',
                'label' => 'Currency Symbol',
                'name' => 'offer_price_currency',
                'type' => 'text',
                'default_value' => '₹',
                'instructions' => 'Currency symbol to display (default: ₹)',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_offer_suitable_for_title',
                'label' => 'Best Suited For Section Title',
                'name' => 'offer_suitable_for_title',
                'type' => 'text',
                'default_value' => 'Best suited for',
                'instructions' => 'Title for the Best Suited For section',
            ],
            [
                'key' => 'field_offer_suitable_for',
                'label' => 'Best Suited For (Repeater)',
                'name' => 'offer_suitable_for',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add Category',
                'sub_fields' => [
                    [
                        'key' => 'field_suitable_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_suitable_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                    ],
                ],
            ],
            [
                'key' => 'field_offer_terms_conditions_title',
                'label' => 'Terms & Conditions Title',
                'name' => 'offer_terms_conditions_title',
                'type' => 'text',
                'default_value' => '*Terms & Conditions',
                'instructions' => 'Title for Terms & Conditions section',
            ],
            [
                'key' => 'field_offer_terms_conditions_content',
                'label' => 'Terms & Conditions Content',
                'name' => 'offer_terms_conditions_content',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'full',
                'instructions' => 'Rich text editor for Terms & Conditions content',
            ],
            [
                'key' => 'field_offer_faqs_title',
                'label' => 'FAQs Section Title',
                'name' => 'offer_faqs_title',
                'type' => 'text',
                'default_value' => 'Commonly Asked Questions',
                'instructions' => 'Title for the FAQs section',
            ],
            [
                'key' => 'field_offer_faqs',
                'label' => 'Frequently Asked Questions',
                'name' => 'offer_faqs',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add FAQ',
                'sub_fields' => [
                    [
                        'key' => 'field_faq_question',
                        'label' => 'Question',
                        'name' => 'question',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_faq_answer',
                        'label' => 'Answer',
                        'name' => 'answer',
                        'type' => 'textarea',
                    ],
                ],
            ],
            [
                'key' => 'field_offer_button_text',
                'label' => 'Button Text',
                'name' => 'offer_button_text',
                'type' => 'text',
                'default_value' => 'Learn more',
                'instructions' => 'Text for the "Learn more" button',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'offer',
                ],
            ],
        ],
    ]);

    // ACF Field Group for Offers Page
    acf_add_local_field_group([
        'key' => 'group_offers_page',
        'title' => 'Offers Page',
        'fields' => [
            [
                'key' => 'field_offers_page_note',
                'label' => 'Important Note',
                'name' => '',
                'type' => 'message',
                'message' => 'Note: Please add minimum 3 offers (as separate Offer posts) to ensure the Latest Offers carousel displays properly in loop mode with centered slides enabled.',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ],
            // Hero Section
            [
                'key' => 'field_offers_hero_background',
                'label' => 'Hero Background Image',
                'name' => 'offers_hero_background',
                'type' => 'image',
                'return_format' => 'array',
                'instructions' => 'Background image for the hero section',
            ],
            [
                'key' => 'field_offers_hero_heading',
                'label' => 'Hero Heading',
                'name' => 'offers_hero_heading',
                'type' => 'text',
                'instructions' => 'Main heading for offers section',
            ],
            // Offers Intro Section
            [
                'key' => 'field_offers_intro_heading',
                'label' => 'Offers Intro Heading',
                'name' => 'offers_intro_heading',
                'type' => 'text',
                'instructions' => 'Heading like "Save more"',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_offers_intro_title',
                'label' => 'Offers Intro Title',
                'name' => 'offers_intro_title',
                'type' => 'text',
                'instructions' => 'Main title like "on professional, trusted car care"',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_offers_intro_description',
                'label' => 'Offers Intro Description',
                'name' => 'offers_intro_description',
                'type' => 'textarea',
                'instructions' => 'Description for offers section',
            ],
            // Journey Section
            [
                'key' => 'field_journey_section_heading_prefix',
                'label' => 'Journey Section Heading Prefix',
                'name' => 'journey_section_heading_prefix',
                'type' => 'text',
                'instructions' => 'e.g., "Your car\'s"',
                'wrapper' => [
                    'width' => '33.333%',
                ],
            ],
            [
                'key' => 'field_journey_section_heading_highlight',
                'label' => 'Journey Section Heading Highlight',
                'name' => 'journey_section_heading_highlight',
                'type' => 'text',
                'instructions' => 'e.g., " journey"',
                'wrapper' => [
                    'width' => '33.333%',
                ],
            ],
            [
                'key' => 'field_journey_section_heading_suffix',
                'label' => 'Journey Section Heading Suffix',
                'name' => 'journey_section_heading_suffix',
                'type' => 'text',
                'instructions' => 'e.g., " at Petromin Express"',
                'wrapper' => [
                    'width' => '33.333%',
                ],
            ],
            [
                'key' => 'field_journey_section_description',
                'label' => 'Journey Section Description',
                'name' => 'journey_section_description',
                'type' => 'textarea',
                'instructions' => 'Description for journey section',
            ],
            [
                'key' => 'field_journey_items',
                'label' => 'Journey Steps',
                'name' => 'journey_items',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add Journey Step',
                'sub_fields' => [
                    [
                        'key' => 'field_journey_title',
                        'label' => 'Step Title',
                        'name' => 'journey_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_journey_image',
                        'label' => 'Step Image',
                        'name' => 'journey_image',
                        'type' => 'image',
                        'return_format' => 'array',
                    ],
                ],
            ],
            // App Section
            [
                'key' => 'field_app_section_heading_line1',
                'label' => 'App Section Heading Line 1',
                'name' => 'app_section_heading_line1',
                'type' => 'text',
                'instructions' => 'e.g., "Car care,"',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_app_section_heading_line2',
                'label' => 'App Section Heading Line 2',
                'name' => 'app_section_heading_line2',
                'type' => 'text',
                'instructions' => 'e.g., "now smarter"',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_app_section_description',
                'label' => 'App Section Description',
                'name' => 'app_section_description',
                'type' => 'textarea',
            ],
            [
                'key' => 'field_app_section_image',
                'label' => 'App Section Image',
                'name' => 'app_section_image',
                'type' => 'image',
                'return_format' => 'array',
            ],
            // App Contact Section Fields
            [
                'key' => 'field_app_contact_placeholder',
                'label' => 'Contact Input Placeholder',
                'name' => 'app_contact_placeholder',
                'type' => 'text',
                'default_value' => 'Enter Contact Number',
                'instructions' => 'Placeholder text for contact input field',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_app_button_text',
                'label' => 'App Button Text',
                'name' => 'app_button_text',
                'type' => 'text',
                'default_value' => 'Get App Link',
                'instructions' => 'Text for the app download button',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_app_google_link',
                'label' => 'Google Play Store Link',
                'name' => 'app_google_link',
                'type' => 'url',
                'instructions' => 'URL to Google Play Store',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_app_google_image',
                'label' => 'Google Play Store Button Image',
                'name' => 'app_google_image',
                'type' => 'image',
                'return_format' => 'array',
                'instructions' => 'Image for Google Play Store button',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_app_apple_link',
                'label' => 'Apple App Store Link',
                'name' => 'app_apple_link',
                'type' => 'url',
                'instructions' => 'URL to Apple App Store',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
            [
                'key' => 'field_app_apple_image',
                'label' => 'Apple App Store Button Image',
                'name' => 'app_apple_image',
                'type' => 'image',
                'return_format' => 'array',
                'instructions' => 'Image for Apple App Store button',
                'wrapper' => [
                    'width' => '50%',
                ]
            ],
        ],
        // 'location' => [
        //     [
        //         [
        //             'param' => 'options_page',
        //             'operator' => '==',
        //             'value' => 'offers_page_options',
        //         ],
        //     ],
        // ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'latest-offers.php',
                ],
            ],
        ],
    ]);

});



// Calculate reading time
function calculate_reading_time($content) {
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute
    return max(1, $reading_time); // Minimum 1 minute
}

// Add featured post meta box
function add_featured_post_meta_box() {
    add_meta_box(
        'featured_post_meta_box',
        'Featured Post',
        'featured_post_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_featured_post_meta_box');

function featured_post_meta_box_callback($post) {
    $featured = get_post_meta($post->ID, 'featured_post', true);
    wp_nonce_field('featured_post_nonce', 'featured_post_nonce_field');
    ?>
    <label for="featured_post">
        <input type="checkbox" name="featured_post" id="featured_post" value="1" <?php checked($featured, '1'); ?> />
        Mark as Featured Post
    </label>
    <?php
}

function save_featured_post_meta($post_id) {
    if (!isset($_POST['featured_post_nonce_field']) || 
        !wp_verify_nonce($_POST['featured_post_nonce_field'], 'featured_post_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $featured = isset($_POST['featured_post']) ? '1' : '0';
    update_post_meta($post_id, 'featured_post', $featured);
}
add_action('save_post', 'save_featured_post_meta');

// AJAX handler for load more
function load_more_blog_posts() {
    check_ajax_referer('load_more_blog_nonce', 'nonce');
    
    $page = intval($_POST['page']);
    $category = sanitize_text_field($_POST['category']);
    $posts_per_page = 6;
    
    $args = [
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish'
    ];
    
    if ($category) {
        $args['category_name'] = $category;
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $post_categories = get_the_category();
            $reading_time = calculate_reading_time(get_the_content());
            $post_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
            $images_url = $assets_url . '/img';
            $fallback_image = $images_url . '/media_mention_img.webp';
            ?>
            
            <div class="relative w-full flex flex-col md:gap-y-4 gap-y-3 group">
                <a href="<?php the_permalink(); ?>" class="w-full relative overflow-hidden duration-300">
                    <img fetchpriority="low" loading="lazy" 
                        src="<?php echo esc_url($post_image ?: $fallback_image); ?>"
                        class="size-full group-hover:lg:scale-125 duration-300" 
                        alt="<?php echo esc_attr(get_the_title()); ?>" 
                        title="<?php echo esc_attr(get_the_title()); ?>">
                </a>
                
                <div class="text-[#637083] font-normal lg:text-base text-sm">
                    <?php 
                    $author = get_the_author();
                    if ($author) {
                        echo esc_html($author) . ' • ';
                    }
                    echo esc_html(get_the_date('F j, Y')) . ' • ' . $reading_time . ' Min Read'; 
                    ?>
                </div>
                
                <a href="<?php the_permalink(); ?>" class="flex gap-2 items-baseline">
                    <h2 class="lg:text-xl md:text-lg text-base font-semibold text-[#121212] group-hover:lg:text-[#CB122D] duration-300">
                        <?php echo esc_html(get_the_title()); ?>
                    </h2>
                    <span>
                        <svg class="size-5 group-hover:lg:text-[#CB122D] duration-300" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 13 20" fill="none">
                            <path d="M12.2789 9.69546L5.34274 19.3833H0L2 16.3833L6.5 9.69546L2 2.8833L0 0L5.34274 0L12.2789 9.69546Z" fill="currentColor" />
                        </svg>
                    </span>
                </a>
                
                <p class="text-[#637083] md:text-base text-sm font-normal">
                    <?php 
                    $excerpt = get_the_excerpt();
                    if (empty($excerpt)) {
                        $excerpt = wp_trim_words(get_the_content(), 20);
                    }
                    echo esc_html($excerpt);
                    ?>
                </p>
                
                <?php if ($post_categories) : ?>
                    <ul class="flex flex-wrap w-full items-center gap-3 pt-3 md:pb-0 pb-4">
                        <?php foreach ($post_categories as $category) : 
                            if ($category->slug !== 'uncategorized') : ?>
                                <li class="bg-[#FEF3E8] text-[#FF8300] p-3 font-medium md:text-base text-sm">
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                </li>
                            <?php endif;
                        endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            
            <?php
        }
        wp_reset_postdata();
    }
    
    $html = ob_get_clean();
    
    wp_send_json_success([
        'html' => $html,
        'max_pages' => $query->max_num_pages
    ]);
}
add_action('wp_ajax_load_more_blog_posts', 'load_more_blog_posts');
add_action('wp_ajax_nopriv_load_more_blog_posts', 'load_more_blog_posts');

/**
 * Ensure the permalink structure uses /blog/%postname%/ so single posts live under the Blog path.
 */
function petromin_ensure_blog_permalink_structure() {
    if (!function_exists('get_option') || !function_exists('update_option')) {
        return;
    }

    $desired_structure = '/blog/%postname%/';
    $current_structure = get_option('permalink_structure');

    $needs_flush = false;
    if ($current_structure !== $desired_structure) {
        update_option('permalink_structure', $desired_structure);
        $needs_flush = true;
    }

    $desired_category_base = 'blog/category';
    $current_category_base = get_option('category_base');

    if ($current_category_base !== $desired_category_base) {
        update_option('category_base', $desired_category_base);
        $needs_flush = true;
    }

    if ($needs_flush) {
        flush_rewrite_rules(false);
    }
}
add_action('init', 'petromin_ensure_blog_permalink_structure');

// Enable featured images
add_theme_support('post-thumbnails');

// Add excerpt support
add_post_type_support('post', 'excerpt');




// Register Custom Post Type for Services
function create_service_post_type() {
    $labels = array(
        'name'                  => 'Services',
        'singular_name'         => 'Service',
        'menu_name'             => 'Services',
        'name_admin_bar'        => 'Service',
        'archives'              => 'Service Archives',
        'attributes'            => 'Service Attributes',
        'parent_item_colon'     => 'Parent Service:',
        'all_items'             => 'All Services',
        'add_new_item'          => 'Add New Service',
        'add_new'               => 'Add New',
        'new_item'              => 'New Service',
        'edit_item'             => 'Edit Service',
        'update_item'           => 'Update Service',
        'view_item'             => 'View Service',
        'view_items'            => 'View Services',
        'search_items'          => 'Search Service',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in Trash',
        'featured_image'        => 'Hero Image',
        'set_featured_image'    => 'Set hero image',
        'remove_featured_image' => 'Remove hero image',
        'use_featured_image'    => 'Use as hero image',
        'insert_into_item'      => 'Insert into service',
        'uploaded_to_this_item' => 'Uploaded to this service',
        'items_list'            => 'Services list',
        'items_list_navigation' => 'Services list navigation',
        'filter_items_list'     => 'Filter services list',
    );
    
    $args = array(
        'label'                 => 'Service',
        'description'           => 'Car and Bike Services',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'taxonomies'            => array('service_category'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-admin-tools',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array(
            'slug' => 'services',
            'with_front' => false
        ),
    );
    
    register_post_type('service', $args);
}
add_action('init', 'create_service_post_type', 0);

// Register Custom Taxonomy for Service Categories
function create_service_taxonomy() {
    $labels = array(
        'name'                       => 'Service Categories',
        'singular_name'              => 'Service Category',
        'menu_name'                  => 'Categories',
        'all_items'                  => 'All Categories',
        'parent_item'                => 'Parent Category',
        'parent_item_colon'          => 'Parent Category:',
        'new_item_name'              => 'New Category Name',
        'add_new_item'               => 'Add New Category',
        'edit_item'                  => 'Edit Category',
        'update_item'                => 'Update Category',
        'view_item'                  => 'View Category',
        'separate_items_with_commas' => 'Separate categories with commas',
        'add_or_remove_items'        => 'Add or remove categories',
        'choose_from_most_used'      => 'Choose from the most used',
        'popular_items'              => 'Popular Categories',
        'search_items'               => 'Search Categories',
        'not_found'                  => 'Not Found',
        'no_terms'                   => 'No categories',
        'items_list'                 => 'Categories list',
        'items_list_navigation'      => 'Categories list navigation',
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'service-category'),
    );
    
    register_taxonomy('service_category', array('service'), $args);
}
add_action('init', 'create_service_taxonomy', 0);

// Flush rewrite rules on theme activation
function flush_rewrite_rules_on_activation() {
    create_service_post_type();
    create_service_taxonomy();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');







// Disable services archive page
function disable_services_archive($query) {
    if (!is_admin() && is_post_type_archive('service') && $query->is_main_query()) {
        $query->set('post_type', 'none');
        $query->set_404();
        status_header(404);
    }
}
add_action('pre_get_posts', 'disable_services_archive');

// Change services archive slug to avoid conflict
function change_services_archive_slug($args, $post_type) {
    if ($post_type === 'service') {
        $args['has_archive'] = false; // Completely disable archive
        // Or change archive slug if you want to keep it
        // $args['has_archive'] = 'our-services';
    }
    return $args;
}
add_filter('register_post_type_args', 'change_services_archive_slug', 10, 2);

// Flush rewrite rules again
function reflush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('init', 'reflush_rewrite_rules');

// Register a simple CPT to store timeline/journey milestones so both home and about pages
// can source the same content for their swipers.
function create_milestone_post_type() {
    $labels = array(
        'name' => 'Milestones',
        'singular_name' => 'Milestone',
        'menu_name' => 'Milestones',
        'name_admin_bar' => 'Milestone',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Milestone',
        'new_item' => 'New Milestone',
        'edit_item' => 'Edit Milestone',
        'view_item' => 'View Milestone',
        'all_items' => 'All Milestones',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'supports' => array('page-attributes','custom-fields'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'milestones', 'with_front' => false),
        'show_in_rest' => true,
    );

    register_post_type('milestone', $args);
}
add_action('init', 'create_milestone_post_type', 0);

/**
 * Helper to fetch milestone posts and return normalized slide array used by the swipers.
 * Each item: ['year'=>string, 'description'=>string, 'image'=>['url'=>..., 'alt'=>...]]
 */
function petromin_get_milestones(array $args = []) {
    $defaults = [
        'post_type' => 'milestone',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order date',
        'order' => 'ASC',
    ];

    $query_args = array_merge($defaults, $args);
    $q = new WP_Query($query_args);
    $slides = [];
    if (!empty($q->posts)) {
        foreach ($q->posts as $p) {
            $post_id = $p->ID;
            // Allow an optional ACF field 'milestone_year' or fallback to title
            $year = function_exists('get_field') ? trim((string) get_field('milestone_year', $post_id) ?: '') : '';
            if ($year === '') {
                $year = get_the_title($post_id);
            }

            // Prefer ACF 'milestone_description' (allows controlled editor input). Fallback to post content.
            $acf_description = function_exists('get_field') ? get_field('milestone_description', $post_id) : null;
            if ($acf_description !== null && $acf_description !== '') {
                $description = trim((string) $acf_description);
            } else {
                $description_raw = get_post_field('post_content', $post_id) ?: '';
                $description = trim($description_raw);
            }

            // Prefer ACF image field 'milestone_image' if present, otherwise use featured image
            $acf_image_field = function_exists('get_field') ? get_field('milestone_image', $post_id) : null;
            if ($acf_image_field) {
                $image = petromin_get_acf_image_data($acf_image_field, 'full', '', $year ?: '');
            } else {
                $image = petromin_get_acf_image_data(get_post_thumbnail_id($post_id), 'full', '', $year ?: '');
            }

            $slides[] = [
                'year' => $year,
                'description' => $description,
                'image' => $image ?: ['url' => '', 'alt' => ''],
            ];
        }
        wp_reset_postdata();
    }

    return $slides;
}

/**
 * Customize admin list columns for Milestone CPT
 * Replace the default Title column with a Milestone Year column
 */
add_filter('manage_milestone_posts_columns', function ($columns) {
    $new = [];
    // keep the checkbox
    if (isset($columns['cb'])) {
        $new['cb'] = $columns['cb'];
    }

    // Add our Milestone Year column (shows ACF 'milestone_year' or fallbacks)
    $new['milestone_year'] = 'Milestone Year';

    // preserve the date column if present
    if (isset($columns['date'])) {
        $new['date'] = $columns['date'];
    }

    return $new;
}, 10, 1);

add_action('manage_milestone_posts_custom_column', function ($column, $post_id) {
    if ($column === 'milestone_year') {
        $year = '';

        // Prefer ACF field if available
        if (function_exists('get_field')) {
            $acf_year = get_field('milestone_year', $post_id);
            if (!empty($acf_year)) {
                $year = trim((string) $acf_year);
            }
        }

        // Fallback to post title (if any)
        if ($year === '') {
            $title = get_the_title($post_id);
            if (!empty($title)) {
                $year = $title;
            }
        }

        // Final fallback: use post date
        if ($year === '') {
            $year = get_post_field('post_date', $post_id);
        }

        echo esc_html($year);
    }
}, 10, 2);

// Make the column sortable by meta (optional - helps if you store year in meta)
add_filter('manage_edit-milestone_sortable_columns', function ($columns) {
    $columns['milestone_year'] = 'milestone_year';
    return $columns;
});

// Adjust query when sorting by milestone_year (meta key)
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');
    if ($orderby === 'milestone_year') {
        // Sort by meta value (milestone_year). If meta not present, fallback is not handled here.
        $query->set('meta_key', 'milestone_year');
        $query->set('orderby', 'meta_value');
    }
});

// ============================================
// OFFER CUSTOM POST TYPE & ACF FIELDS
// ============================================

/**
 * Register Custom Post Type for Offers
 */
function create_offer_post_type() {
    $labels = array(
        'name'                  => 'Offers',
        'singular_name'         => 'Offer',
        'menu_name'             => 'Offers',
        'name_admin_bar'        => 'Offer',
        'archives'              => 'Offer Archives',
        'attributes'            => 'Offer Attributes',
        'parent_item_colon'     => 'Parent Offer:',
        'all_items'             => 'All Offers',
        'add_new_item'          => 'Add New Offer',
        'add_new'               => 'Add New',
        'new_item'              => 'New Offer',
        'edit_item'             => 'Edit Offer',
        'update_item'           => 'Update Offer',
        'view_item'             => 'View Offer',
        'view_items'            => 'View Offers',
        'search_items'          => 'Search Offer',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in Trash',
        'featured_image'        => 'Offer Image',
        'set_featured_image'    => 'Set offer image',
        'remove_featured_image' => 'Remove offer image',
        'use_featured_image'    => 'Use as offer image',
        'insert_into_item'      => 'Insert into offer',
        'uploaded_to_this_item' => 'Uploaded to this offer',
        'items_list'            => 'Offers list',
        'items_list_navigation' => 'Offers list navigation',
        'filter_items_list'     => 'Filter offers list',
    );
    
    $args = array(
        'label'                 => 'Offer',
        'description'           => 'Car Service Offers. Note: For the Latest Offers carousel (with centered slides), please ensure at least 3 offers are published.',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-tag',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array(
            'slug' => 'offers',
            'with_front' => false
        ),
    );
    
    register_post_type('offer', $args);
}
add_action('init', 'create_offer_post_type', 0);

/**
 * Helper function to fetch offers with all details
 * Returns an array of offers with normalized data
 */
function petromin_get_offers(array $args = []) {
    $defaults = [
        'post_type' => 'offer',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order date',
        'order' => 'ASC',
    ];

    $query_args = array_merge($defaults, $args);
    $q = new WP_Query($query_args);
    $offers = [];

    if (!empty($q->posts)) {
        foreach ($q->posts as $p) {
            $offer_image = petromin_get_acf_image_data(
                get_field('offer_image', $p->ID),
                'large',
                get_the_post_thumbnail_url($p->ID, 'large')
            );

            $offers[] = [
                'id'                        => $p->ID,
                'title'                     => get_the_title($p->ID),
                'slug'                      => $p->post_name,
                'url'                       => get_permalink($p->ID),
                'short_description'         => get_field('offer_short_description', $p->ID) ?: wp_trim_words($p->post_content, 15),
                'image'                     => $offer_image,
                'starting_price'            => get_field('offer_starting_price', $p->ID),
                'price_currency'            => get_field('offer_price_currency', $p->ID) ?: '₹',
                'button_text'               => get_field('offer_button_text', $p->ID) ?: 'Learn more',
                'suitable_for'              => get_field('offer_suitable_for', $p->ID),
                'terms_conditions_title'    => get_field('offer_terms_conditions_title', $p->ID) ?: '*Terms & Conditions',
                'terms_conditions_content'  => get_field('offer_terms_conditions_content', $p->ID),
                'faqs'                      => get_field('offer_faqs', $p->ID),
            ];
        }
        wp_reset_postdata();
    }

    return $offers;
}

/**
 * Auto-fill country, state, and city from Google Maps Geocoding API when map_location is saved
 * This hook triggers when map_location field is updated in a repeater
 */
add_filter('acf/update_value/name=map_location', 'petromin_update_location_fields', 10, 3);
function petromin_update_location_fields($value, $post_id, $field) {
    // Only process if we have lat and lng
    if (empty($value) || !is_array($value) || empty($value['lat']) || empty($value['lng'])) {
        return $value;
    }
    
    $lat = (float) $value['lat'];
    $lng = (float) $value['lng'];
    
    // Get Google Maps API key from ACF settings or filter
    $api_key = '';
    $api_config = apply_filters('acf/fields/google_map/api', []);
    if (is_array($api_config) && !empty($api_config['key'])) {
        $api_key = $api_config['key'];
    } elseif (!empty(acf_get_setting('google_api_key'))) {
        $api_key = acf_get_setting('google_api_key');
    }
    
    if (empty($api_key)) {
        return $value; // Can't proceed without API key
    }
    
    // Extract location data from Google Maps API
    $location_data = petromin_get_location_from_coordinates($lat, $lng, $api_key);
    
    if (empty($location_data)) {
        return $value;
    }
    
    // Get the field name to determine the repeater row
    // Field name format in repeater can be: centers_0_map_location, centers_1_map_location, etc.
    // Or it might be in the $_POST data
    $field_name = $field['name'] ?? '';
    $row_index = null;
    
    // Try to extract row index from field name
    if (preg_match('/centers[_\[](\d+)[_\]]/', $field_name, $matches)) {
        $row_index = (int) $matches[1];
    } elseif (isset($_POST['acf']) && is_array($_POST['acf'])) {
        // Try to find the row index from POST data
        foreach ($_POST['acf'] as $key => $data) {
            if (is_array($data) && isset($data['centers'])) {
                foreach ($data['centers'] as $idx => $center_data) {
                    if (isset($center_data['map_location']['lat']) && 
                        abs((float)$center_data['map_location']['lat'] - $lat) < 0.0001 &&
                        isset($center_data['map_location']['lng']) &&
                        abs((float)$center_data['map_location']['lng'] - $lng) < 0.0001) {
                        $row_index = $idx;
                        break 2;
                    }
                }
            }
        }
    }
    
    // If we found the row index, update the fields
    if ($row_index !== null) {
        // Update the fields for this specific row using field keys
        $field_key = $field['key'] ?? '';
        $parent_field = acf_get_field($field['parent'] ?? '');
        
        if ($parent_field && $parent_field['type'] === 'repeater') {
            // Get the repeater field
            $repeater_key = $parent_field['key'] ?? '';
            
            // Find country, state, city field keys in the same repeater
            $country_field = acf_get_field('field_locate_center_country');
            $state_field = acf_get_field('field_locate_center_state');
            $city_field = acf_get_field('field_locate_center_city');
            
            if ($country_field && !empty($location_data['country'])) {
                update_field($repeater_key . '_' . $row_index . '_' . $country_field['name'], $location_data['country'], $post_id);
            }
            if ($state_field && !empty($location_data['state'])) {
                update_field($repeater_key . '_' . $row_index . '_' . $state_field['name'], $location_data['state'], $post_id);
            }
            if ($city_field && !empty($location_data['city'])) {
                update_field($repeater_key . '_' . $row_index . '_' . $city_field['name'], $location_data['city'], $post_id);
            }
        } else {
            // Fallback: use field name pattern
            $row_prefix = 'service_centers_section_centers_' . $row_index . '_';
            
            if (!empty($location_data['country'])) {
                update_field($row_prefix . 'country', $location_data['country'], $post_id);
            }
            if (!empty($location_data['state'])) {
                update_field($row_prefix . 'state', $location_data['state'], $post_id);
            }
            if (!empty($location_data['city'])) {
                update_field($row_prefix . 'city', $location_data['city'], $post_id);
            }
        }
    }
    
    return $value;
}

/**
 * Helper function to get location data from coordinates using Google Maps Geocoding API
 */
function petromin_get_location_from_coordinates($lat, $lng, $api_key) {
    // Call Google Maps Geocoding API
    $geocode_url = sprintf(
        'https://maps.googleapis.com/maps/api/geocode/json?latlng=%s,%s&key=%s',
        urlencode($lat),
        urlencode($lng),
        urlencode($api_key)
    );
    
    $response = wp_remote_get($geocode_url, [
        'timeout' => 10,
        'sslverify' => true,
    ]);
    
    if (is_wp_error($response)) {
        return [];
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (empty($data['results']) || $data['status'] !== 'OK') {
        return [];
    }
    
    // Extract address components from the first result
    $result = $data['results'][0];
    $address_components = $result['address_components'] ?? [];
    
    $country = '';
    $state = '';
    $city = '';
    
    foreach ($address_components as $component) {
        $types = $component['types'] ?? [];
        $long_name = $component['long_name'] ?? '';
        
        // Extract country
        if (in_array('country', $types) && empty($country)) {
            $country = $long_name;
        }
        
        // Extract state (administrative_area_level_1)
        if (in_array('administrative_area_level_1', $types) && empty($state)) {
            $state = $long_name;
        }
        
        // Extract city (locality or administrative_area_level_2)
        if (in_array('locality', $types) && empty($city)) {
            $city = $long_name;
        } elseif (in_array('administrative_area_level_2', $types) && empty($city)) {
            $city = $long_name;
        }
    }
    
    return [
        'country' => $country,
        'state' => $state,
        'city' => $city,
    ];
}

/**
 * Alternative approach: Use acf/save_post to update location fields after save
 * This is a backup method in case the update_value filter doesn't work
 */
add_action('acf/save_post', 'petromin_save_location_fields_on_post_save', 20);
function petromin_save_location_fields_on_post_save($post_id) {
    // Skip autosaves and revisions
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }
    
    // Only process on locate us page template
    $template = get_page_template_slug($post_id);
    if ($template !== 'locate-us.php') {
        return;
    }
    
    $service_centers = get_field('service_centers_section', $post_id);
    
    if (empty($service_centers['centers']) || !is_array($service_centers['centers'])) {
        return;
    }
    
    // Get Google Maps API key from ACF settings or filter
    $api_key = '';
    $api_config = apply_filters('acf/fields/google_map/api', []);
    if (is_array($api_config) && !empty($api_config['key'])) {
        $api_key = $api_config['key'];
    } elseif (!empty(acf_get_setting('google_api_key'))) {
        $api_key = acf_get_setting('google_api_key');
    }
    
    if (empty($api_key)) {
        return;
    }
    
    // Process each center
    foreach ($service_centers['centers'] as $index => $center) {
        $map_location = $center['map_location'] ?? null;
        
        if (empty($map_location) || !is_array($map_location) || 
            empty($map_location['lat']) || empty($map_location['lng'])) {
            continue;
        }
        
        $lat = (float) $map_location['lat'];
        $lng = (float) $map_location['lng'];
        
        // Always fetch and update location data when map_location exists
        // This ensures fields are updated even if location changes
        $location_data = petromin_get_location_from_coordinates($lat, $lng, $api_key);
        
        if (!empty($location_data)) {
            // Update fields using the correct field name pattern
            $row_prefix = 'service_centers_section_centers_' . $index . '_';
            
            if (!empty($location_data['country'])) {
                update_field($row_prefix . 'country', $location_data['country'], $post_id);
            }
            if (!empty($location_data['state'])) {
                update_field($row_prefix . 'state', $location_data['state'], $post_id);
            }
            if (!empty($location_data['city'])) {
                update_field($row_prefix . 'city', $location_data['city'], $post_id);
            }
        }
    }
}

/**
 * Disable offers archive page
 */
function disable_offers_archive($query) {
    if (!is_admin() && is_post_type_archive('offer') && $query->is_main_query()) {
        $query->set('post_type', 'none');
        $query->set_404();
        status_header(404);
    }
}
add_action('pre_get_posts', 'disable_offers_archive');

/**
 * Get swiper settings from ACF options
 * 
 * @param string $swiper_class The swiper class name (without dot, e.g., 'latestOfferSwiper')
 * @return array Returns array with speed, delay, and autoplay settings
 */
if (!function_exists('petromin_get_swiper_settings')) {
    function petromin_get_swiper_settings($swiper_class) {
        // Mapping of swiper class names to ACF field names
        $swiper_field_mapping = [
            'latestOfferSwiper' => 'swiper_latest_offers',
            'timelineSectionSwiper' => 'swiper_timeline',
            'partnersSectionSwiper' => 'swiper_partners',
            'partnersFooterSectionSwiper' => 'swiper_partners_footer',
            'brandsSectionSwiperLeft' => 'swiper_brands_left',
            'brandsSectionSwiperRight' => 'swiper_brands_right',
            'brandsSectionSwiperMobile' => 'swiper_brands_mobile',
            'testimonialsSectionSwiper' => 'swiper_testimonials',
            'benefitsSectionSwiper' => 'swiper_benefits',
            'moreServicesSectionSwiper' => 'swiper_more_services',
            'bestKnownSectionSwiper' => 'swiper_best_known',
            'ourServicesSectionSwiper' => 'swiper_our_services',
            'blogHeroSectionSwiper' => 'swiper_blog_hero',
            'newsCategorySectionSwiper' => 'swiper_news_category',
        ];
        
        // Get the field name for this swiper
        $field_name = $swiper_field_mapping[$swiper_class] ?? null;
        
        if (!$field_name) {
            // Return defaults if swiper not found in mapping
            return [
                'speed' => 800,
                'delay' => 3000,
                'autoplay' => true,
            ];
        }
        
        // Get settings from ACF options
        $settings = get_field($field_name, 'option');
        
        if (empty($settings) || !is_array($settings)) {
            // Return defaults if no settings found
            return [
                'speed' => 800,
                'delay' => 3000,
                'autoplay' => true,
            ];
        }
        
        // Return settings with defaults as fallback
        return [
            'speed' => (int)($settings['speed'] ?? 800),
            'delay' => (int)($settings['delay'] ?? 3000),
            'autoplay' => (bool)($settings['autoplay'] ?? true),
        ];
    }
}

/**
 * Hide country, state, and city fields in ACF admin panel
 */
add_action('acf/input/admin_head', 'petromin_hide_location_fields');
function petromin_hide_location_fields() {
    ?>
    <style>
        /* Hide country, state, and city fields in ACF admin */
        .acf-field[data-name="country"],
        .acf-field[data-name="state"],
        .acf-field[data-name="city"] {
            display: none !important;
        }
        
        /* Alternative: Hide using field key if data-name doesn't work */
        .acf-field[data-key="field_locate_center_country"],
        .acf-field[data-key="field_locate_center_state"],
        .acf-field[data-key="field_locate_center_city"] {
            display: none !important;
        }
    </style>
    <?php
}

/**
 * Change offers archive slug to avoid conflicts
 */
function change_offers_archive_slug($args, $post_type) {
    if ($post_type === 'offer') {
        $args['has_archive'] = false;
    }
    return $args;
}
add_filter('register_post_type_args', 'change_offers_archive_slug', 10, 2);

/**
 * Flush rewrite rules for offers on theme activation
 */
function flush_rewrite_rules_for_offers() {
    create_offer_post_type();
    flush_rewrite_rules();
}
add_action('init', 'flush_rewrite_rules_for_offers', 1);