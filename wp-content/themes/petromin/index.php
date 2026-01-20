<?php
/* Template Name: home page */
get_header();

$assets_url = trailingslashit(trailingslashit(get_template_directory_uri()) . 'assets');
$images_url = trailingslashit($assets_url . 'img');

$hero_defaults = [
    'video' => [
        'url' => $assets_url . 'videos/heroVideo.mp4',
        'mime_type' => 'video/mp4',
    ],
    'headline_prefix' => 'Redefining',
    'headline_highlight' => 'EXPRESS',
    'headline_suffix' => 'car care across India.',
    'headline_suffix_primary' => 'car care',
    'headline_suffix_secondary' => 'across India.',
    'features' => [
        [
            'title' => 'Honest',
            'subtitle' => 'Pricing',
            'icon' => [
                'url' => $images_url . 'Isolation_Mode-1.webp',
                'alt' => 'Honest Pricing icon',
            ],
        ],
        [
            'title' => 'Genuine',
            'subtitle' => 'Parts',
            'icon' => [
                'url' => $images_url . 'genuine-part.webp',
                'alt' => 'Genuine Parts icon',
            ],
        ],
        [
            'title' => 'Certified',
            'subtitle' => 'Technicians',
            'icon' => [
                'url' => $images_url . 'technician.webp',
                'alt' => 'Certified Technicians icon',
            ],
        ],
        [
            'title' => 'Warranty',
            'subtitle' => 'Coverage',
            'icon' => [
                'url' => $images_url . 'coverage.webp',
                'alt' => 'Warranty Coverage icon',
            ],
        ],
    ],
];

$services_defaults = [
    'heading' => 'Everything your vehicle needs and more.',
    'tabs' => [
        [
            'label' => 'Car Service',
            'icon' => [
                'url' => $images_url . 'car-service.webp',
                'alt' => 'Car Service icon',
            ],
            'heading' => 'Car Service',
            'description' => 'Ensure your car runs seamlessly with routine care built around consistency. We maintain every system with precision to preserve performance, reliability, and peace of mind on every drive.',
            'highlight' => 'Get dealership-level service, minus premium costs.',
            'primary_button' => [
                'label' => 'Book Now',
                'url' => '#',
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => '#',
                'target' => '_self',
            ],
            'image' => [
                'url' => $images_url . 'ChatGPT-Image-Aug-22-2025-09_09_08-PM-2.webp',
                'alt' => 'Petromin service illustration',
            ],
        ],
        [
            'label' => 'Battery Service',
            'icon' => [
                'url' => $images_url . 'car-service.webp',
                'alt' => 'Battery Service icon',
            ],
            'heading' => 'Battery Service',
            'description' => 'Revive your car’s power with a battery service that tests, cleans, and optimises performance. From voltage checks to terminal care, every start stays instant and reliable.',
            'highlight' => 'Power every start with confidence at the best price.',
            'primary_button' => [
                'label' => 'Book Now',
                'url' => '#',
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => '#',
                'target' => '_self',
            ],
            'image' => [
                'url' => $images_url . 'ChatGPT-Image-Aug-22-2025-09_09_08-PM-2.webp',
                'alt' => 'Battery service illustration',
            ],
        ],
        [
            'label' => 'Tyre Care',
            'icon' => [
                'url' => $images_url . 'car-service.webp',
                'alt' => 'Tyre Care icon',
            ],
            'heading' => 'Tyre Care',
            'description' => 'Your tyres carry more than just weight - they carry safety. We assess tread depth, alignment, and balance to enhance control, extend lifespan, and keep every journey steady.',
            'highlight' => 'Extend tyre life with care that proves its worth.',
            'primary_button' => [
                'label' => 'Book Now',
                'url' => '#',
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => '#',
                'target' => '_self',
            ],
            'image' => [
                'url' => $images_url . 'ChatGPT-Image-Aug-22-2025-09_09_08-PM-2.webp',
                'alt' => 'Tyre care illustration',
            ],
        ],
        [
            'label' => 'AC Service',
            'icon' => [
                'url' => $images_url . 'car-service.webp',
                'alt' => 'AC Service icon',
            ],
            'heading' => 'AC Service',
            'description' => 'Keep your drives cool with an AC service that’s precise, complete, and reliable. We inspect components, clean filters, and recharge refrigerant to maintain smooth, consistent cooling.',
            'highlight' => 'Stay cool and refreshed with unbeatable service value.',
            'primary_button' => [
                'label' => 'Book Now',
                'url' => '#',
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => '#',
                'target' => '_self',
            ],
            'image' => [
                'url' => $images_url . 'ChatGPT-Image-Aug-22-2025-09_09_08-PM-2.webp',
                'alt' => 'AC service illustration',
            ],
        ],
        [
            'label' => 'Eco Car Wash',
            'icon' => [
                'url' => $images_url . 'car-service.webp',
                'alt' => 'Eco Car Wash icon',
            ],
            'heading' => 'Eco Car Wash',
            'description' => 'Maintain your car’s shine responsibly. With eco-friendly products and precision washing, we clean thoroughly while protecting paint and conserving water.',
            'highlight' => 'Clean smarter with eco-care designed for great value.',
            'primary_button' => [
                'label' => 'Book Now',
                'url' => '#',
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => '#',
                'target' => '_self',
            ],
            'image' => [
                'url' => $images_url . 'ChatGPT-Image-Aug-22-2025-09_09_08-PM-2.webp',
                'alt' => 'Eco car wash illustration',
            ],
        ],
        [
            'label' => 'Headlight Polish',
            'icon' => [
                'url' => $images_url . 'car-service.webp',
                'alt' => 'Headlight Polish icon',
            ],
            'heading' => 'Headlight Polish',
            'description' => 'Dull headlights don’t just age your car - they limit what you see. We restore lens clarity and beam strength, bringing back safe, confident visibility on every drive.',
            'highlight' => 'Restore visibility with expert care and transparent pricing.',
            'primary_button' => [
                'label' => 'Book Now',
                'url' => '#',
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => '#',
                'target' => '_self',
            ],
            'image' => [
                'url' => $images_url . 'ChatGPT-Image-Aug-22-2025-09_09_08-PM-2.webp',
                'alt' => 'Headlight polish illustration',
            ],
        ],
        [
            'label' => 'Body Shop',
            'icon' => [
                'url' => $images_url . 'car-service.webp',
                'alt' => 'Body Shop icon',
            ],
            'heading' => 'Body Shop',
            'description' => 'Bring back your car’s original finish with expert repair and detailing. From dents and scratches to full paint restoration, every panel is refined for lasting beauty and reliability.',
            'highlight' => 'Restore your car’s form through quality that earns its price.',
            'primary_button' => [
                'label' => 'Book Now',
                'url' => '#',
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => '#',
                'target' => '_self',
            ],
            'image' => [
                'url' => $images_url . 'ChatGPT-Image-Aug-22-2025-09_09_08-PM-2.webp',
                'alt' => 'Body shop illustration',
            ],
        ],
        [
            'label' => 'Engine Care',
            'icon' => [
                'url' => $images_url . 'car-service.webp',
                'alt' => 'Engine Care icon',
            ],
            'heading' => 'Engine Care',
            'description' => 'Good performance starts deep within the engine. We service, clean, and protect vital components to keep it running smoothly, efficiently, and with lasting power.',
            'highlight' => 'Drive in for expert care that delivers real value.',
            'primary_button' => [
                'label' => 'Book Now',
                'url' => '#',
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => '#',
                'target' => '_self',
            ],
            'image' => [
                'url' => $images_url . 'ChatGPT-Image-Aug-22-2025-09_09_08-PM-2.webp',
                'alt' => 'Engine care illustration',
            ],
        ],
    ],
];

$timeline_defaults = [
    'heading' => 'A standard of quality throughout history.',
    'navigation_icon' => [
        'url' => $images_url . 'fi_19024510.webp',
        'alt' => 'Navigation arrow icon',
    ],
    'slides' => [
        // [
        //     'year' => '1968',
        //     'image' => [
        //         'url' => $images_url . 'image-42.webp',
        //         'alt' => 'JV incorporated (G.M., Mobil/Mobil Petromin); Petrolube brand launched',
        //     ],
        //     'description' => 'JV incorporated (G.M., Mobil/Mobil Petromin); Petrolube brand launched',
        // ],
        // [
        //     'year' => '1969',
        //     'image' => [
        //         'url' => $images_url . 'image-2.webp',
        //         'alt' => 'Petrolube brand continued its expansion',
        //     ],
        //     'description' => 'Petrolube brand continued its expansion',
        // ],
        // [
        //     'year' => '1992',
        //     'image' => [
        //         'url' => $images_url . 'image-42.webp',
        //         'alt' => 'Entered the Egypt market',
        //     ],
        //     'description' => 'Entered the Egypt market',
        // ]
    ],
];

$partner_highlights_defaults = [
    'items' => [
        'Trusted HPCL Partner',
        'OEM-Grade Standards',
        'Express Turnaround Time',
        'OEM-Grade Standards',
        'Express Turnaround Time',
        'OEM-Grade Standards',
        'Trusted HPCL Partner',
        'OEM-Grade Standards',
        'Trusted HPCL Partner',
        'Express Turnaround Time',
        'Trusted HPCL Partner',
        'Express Turnaround Time',
    ],
    'icon' => [
        'url' => $images_url . 'arrowIcon.webp',
        'alt' => 'Highlight arrow icon',
    ],
];



$hero_field = function_exists('get_field') ? (get_field('hero_section') ?: []) : [];


$services_field = function_exists('get_field') ? (get_field('services_tabs_section') ?: []) : [];
$timeline_field = function_exists('get_field') ? (get_field('timeline_section') ?: []) : [];
$partner_highlights_field = function_exists('get_field') ? (get_field('partner_highlights_section') ?: []) : [];

$hero_video_field = $hero_field['background_video'] ?? null;
$hero_video_url = '';
$hero_video_type = '';

if (is_array($hero_video_field)) {
    if (!empty($hero_video_field['url'])) {
        $hero_video_url = $hero_video_field['url'];
    }

    if (!empty($hero_video_field['mime_type'])) {
        $hero_video_type = $hero_video_field['mime_type'];
    }
} elseif (is_string($hero_video_field)) {
    $hero_video_url = trim($hero_video_field);
}

if ($hero_video_url === '') {
    $hero_video_url = $hero_defaults['video']['url'];
}

if ($hero_video_type === '') {
    $hero_video_type = $hero_defaults['video']['mime_type'];
}

$hero_headline_prefix = trim($hero_field['headline_prefix'] ?? '');
if ($hero_headline_prefix === '') {
    $hero_headline_prefix = $hero_defaults['headline_prefix'];
}

$hero_headline_highlight = trim($hero_field['headline_highlight'] ?? '');
if ($hero_headline_highlight === '') {
    $hero_headline_highlight = $hero_defaults['headline_highlight'];
}

$hero_headline_suffix = trim($hero_field['headline_suffix'] ?? '');
if ($hero_headline_suffix === '') {
    $hero_headline_suffix = $hero_defaults['headline_suffix'];
}

$hero_headline_suffix_primary = trim($hero_field['headline_suffix_primary'] ?? '');
$hero_headline_suffix_secondary = trim($hero_field['headline_suffix_secondary'] ?? '');

if ($hero_headline_suffix_primary === '' && $hero_headline_suffix_secondary === '') {
    if ($hero_headline_suffix !== '') {
        $hero_headline_suffix_primary = $hero_headline_suffix;
        $hero_headline_suffix_secondary = '';
    } else {
        $hero_headline_suffix_primary = $hero_defaults['headline_suffix_primary'];
        $hero_headline_suffix_secondary = $hero_defaults['headline_suffix_secondary'];
    }
}

$hero_features_field = $hero_field['features'] ?? [];
$hero_features = [];
$source_features = [];

if (is_array($hero_features_field) && !empty($hero_features_field)) {
    $source_features = $hero_features_field;
} else {
    $source_features = $hero_defaults['features'];
}

foreach ($source_features as $index => $feature_field) {
    $default_feature = $hero_defaults['features'][$index] ?? [
        'title' => '',
        'subtitle' => '',
        'icon' => [
            'url' => '',
            'alt' => '',
        ],
    ];

    $title = '';
    if (isset($feature_field['feature_title'])) {
        $title = trim($feature_field['feature_title']);
    } elseif (isset($feature_field['title'])) {
        $title = trim($feature_field['title']);
    }

    if ($title === '') {
        $title = $default_feature['title'] ?? '';
    }

    $subtitle = '';
    if (isset($feature_field['feature_subtitle'])) {
        $subtitle = trim($feature_field['feature_subtitle']);
    } elseif (isset($feature_field['subtitle'])) {
        $subtitle = trim($feature_field['subtitle']);
    }

    if ($subtitle === '') {
        $subtitle = $default_feature['subtitle'] ?? '';
    }

    $icon_field = $feature_field['feature_icon'] ?? ($feature_field['icon'] ?? null);
    $icon_default = $default_feature['icon'] ?? ['url' => '', 'alt' => ''];
    $icon_alt_fallback = $icon_default['alt'] ?? ($title !== '' ? $title : $hero_headline_prefix);
    $icon_data = petromin_get_acf_image_data($icon_field, 'full', $icon_default['url'] ?? '', $icon_alt_fallback);

    if (!$icon_data && !empty($icon_default['url'])) {
        $icon_data = $icon_default;
    }

    if ($title === '' && $subtitle !== '') {
        $title = $subtitle;
        $subtitle = '';
    }

    if ($title === '' && $subtitle === '' && !$icon_data) {
        continue;
    }

    $hero_features[] = [
        'title' => $title,
        'subtitle' => $subtitle,
        'icon' => $icon_data,
    ];
}

$services_heading = trim($services_field['heading'] ?? '');
if ($services_heading === '') {
    $services_heading = $services_defaults['heading'];
}

$services_tabs = []; // ACF tabs repeater removed; tabs are populated from 'service' posts only.

// Prefer sourcing services from published 'service' posts (if any) so the home page reflects Service posts.
// We use the ACF fields stored on each service post: 'service_icon' and 'home_page_service_image'.
$service_posts_query = new WP_Query([
    'post_type' => 'service',
    'posts_per_page' => 12,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

$service_posts = $service_posts_query->posts;
if (!empty($service_posts)) {
    $posts_tabs = [];
    foreach ($service_posts as $sp) {
        setup_postdata($sp);
        $post_id = $sp->ID;
        $label = get_the_title($post_id);
        $heading = $label;
        // Prefer the full post content for home page display; fallback to excerpt/trimmed content.
        $raw_content = get_post_field('post_content', $post_id);
        if (!empty($raw_content)) {
            // Apply content filters so shortcodes and autop are handled.
            $description = apply_filters('the_content', $raw_content);
        } else {
            $description = get_the_excerpt($post_id) ?: wp_trim_words($raw_content, 20);
        }

        // Hero description (ACF field on service post) - show under the title/heading on the home page
        $hero_description_field = get_field('hero_description', $post_id);
        $hero_description = '';
        if (is_string($hero_description_field) && trim($hero_description_field) !== '') {
            $hero_description = trim($hero_description_field);
        }

        // Resolve service icon and home page image (ACF fields added to service post)
        $icon_data = petromin_get_acf_image_data(get_field('service_icon', $post_id), 'thumbnail', '', $label);
        $home_image_data = petromin_get_acf_image_data(get_field('home_page_service_image', $post_id), 'full', '', $label);

        // Highlight text (ACF field on service post) - show highlighted text between description and buttons
        $highlight_field = get_field('highlight', $post_id);
        $highlight = '';
        if (is_string($highlight_field) && trim($highlight_field) !== '') {
            $highlight = trim($highlight_field);
        }

        // Build a tab entry similar to existing structure
        $posts_tabs[] = [
            'label' => $label,
            'heading' => $heading,
            'hero_description' => $hero_description,
            'description' => $description,
            'highlight' => $highlight,
            'icon' => $icon_data ?: ['url' => '', 'alt' => $label],
            'image' => $home_image_data ?: ['url' => '', 'alt' => $label],
            'primary_button' => [
                'label' => 'Book Now',
                'url' => get_permalink($post_id),
                'target' => '_self',
            ],
            'secondary_button' => [
                'label' => 'Know More',
                'url' => get_permalink($post_id),
                'target' => '_self',
            ],
        ];
    }
    wp_reset_postdata();

    if (!empty($posts_tabs)) {
        $services_tabs = $posts_tabs;
    }
}

$timeline_heading = trim($timeline_field['heading'] ?? '');
if ($timeline_heading === '') {
    $timeline_heading = $timeline_defaults['heading'];
}

$timeline_nav_icon_data = petromin_get_acf_image_data($timeline_field['navigation_icon'] ?? null, 'full', $timeline_defaults['navigation_icon']['url'], $timeline_defaults['navigation_icon']['alt']);
if (!$timeline_nav_icon_data) {
    $timeline_nav_icon_data = $timeline_defaults['navigation_icon'];
}

$timeline_slides = function_exists('petromin_get_milestones') ? petromin_get_milestones() : [];

// Fallback: if no milestone CPT entries exist, keep existing ACF-based defaults (if present).
if (empty($timeline_slides)) {
    $timeline_slides_input = is_array($timeline_field['slides'] ?? null) ? $timeline_field['slides'] : [];
    // Reuse the old builder logic locally as a small fallback (keeps UI unchanged).
    $build_timeline_slides = static function (array $source, array $default_slides) use ($timeline_defaults, $timeline_heading) {
        $slides = [];
        foreach ($source as $index => $slide_field) {
            $default_slide = $default_slides[$index] ?? ($timeline_defaults['slides'][$index] ?? ['year' => '', 'image' => ['url' => '', 'alt' => ''], 'description' => '']);
            $year = trim($slide_field['year'] ?? ($slide_field['timeline_year'] ?? '')) ?: ($default_slide['year'] ?? '');
            $description = trim($slide_field['description'] ?? ($slide_field['slide_description'] ?? '')) ?: ($default_slide['description'] ?? '');
            $image_default = $default_slide['image'] ?? ['url' => '', 'alt' => $description ?: $year];
            $image_alt_fallback = $image_default['alt'] ?? ($description ?: $year ?: $timeline_heading);
            $image_data = petromin_get_acf_image_data($slide_field['image'] ?? null, 'full', $image_default['url'], $image_alt_fallback);
            if (!$image_data && !empty($image_default['url'])) {
                $image_data = $image_default;
            }
            if ($year === '' && !$image_data && $description === '') {
                continue;
            }
            $slides[] = ['year' => $year, 'image' => $image_data, 'description' => $description];
        }
        return $slides;
    };

    $timeline_slides = $build_timeline_slides($timeline_slides_input, $timeline_defaults['slides']);
    if (empty($timeline_slides)) {
        $timeline_slides = $build_timeline_slides($timeline_defaults['slides'], $timeline_defaults['slides']);
    }
}

$partner_highlights_icon = petromin_get_acf_image_data($partner_highlights_field['icon'] ?? null, 'full', $partner_highlights_defaults['icon']['url'], $partner_highlights_defaults['icon']['alt']);
if (!$partner_highlights_icon) {
    $partner_highlights_icon = $partner_highlights_defaults['icon'];
}

$partner_highlights_items = [];
$partner_items_input = is_array($partner_highlights_field['items'] ?? null) ? $partner_highlights_field['items'] : [];
if (!empty($partner_items_input)) {
    foreach ($partner_items_input as $item) {
        if (is_array($item)) {
            $text = trim($item['item_text'] ?? ($item['text'] ?? ''));
        } else {
            $text = trim((string) $item);
        }

        if ($text !== '') {
            $partner_highlights_items[] = $text;
        }
    }
}

if (empty($partner_highlights_items)) {
    $partner_highlights_items = $partner_highlights_defaults['items'];
}


// Get the digital checkup section data
$digital_checkup_field = function_exists('get_field') ? (get_field('digital_checkup_section') ?: []) : [];

// Default values
$digital_checkup_defaults = [
    'heading_prefix' => 'Get a',
    'heading_highlight' => 'FREE',
    'heading_suffix' => 'full digital <br />car health check-up',
    'button_text' => 'Get It Now',
    'button_link' => '#',
    'original_price' => 'Originally ₹249*',
    'main_image' => [
        'url' => $images_url . 'get.webp',
        'alt' => 'Digital Car Health Check-up',
    ],
    'background_desktop' => [
        'url' => $images_url . 'Group-65-1-scaled-1.webp',
        'alt' => 'Background pattern',
    ],
    'background_mobile' => [
        'url' => $images_url . 'Group-76.webp',
        'alt' => 'Background pattern mobile',
    ],
    'icon_image' => [
        'url' => $images_url . 'Isolation_Mode_2.webp',
        'alt' => 'Car Health Check Icon',
    ],
    'check_icon' => [
        'url' => $images_url . 'check_list.webp',
        'alt' => 'Yellow Check Icon',
    ],
    'features' => [
        ['feature_text' => '40+ checkpoints'],
        ['feature_text' => 'Instant report to your inbox'],
        ['feature_text' => 'Save on future repairs'],
        ['feature_text' => 'Improve your car\'s resale value'],
        ['feature_text' => 'Early issue detection with tech insights'],
    ],
];

// Process the data
$digital_checkup_heading_prefix = trim($digital_checkup_field['heading_prefix'] ?? '');
if ($digital_checkup_heading_prefix === '') {
    $digital_checkup_heading_prefix = $digital_checkup_defaults['heading_prefix'];
}

$digital_checkup_heading_highlight = trim($digital_checkup_field['heading_highlight'] ?? '');
if ($digital_checkup_heading_highlight === '') {
    $digital_checkup_heading_highlight = $digital_checkup_defaults['heading_highlight'];
}

$digital_checkup_heading_suffix = trim($digital_checkup_field['heading_suffix'] ?? '');
if ($digital_checkup_heading_suffix === '') {
    $digital_checkup_heading_suffix = $digital_checkup_defaults['heading_suffix'];
}

$digital_checkup_button_text = trim($digital_checkup_field['button_text'] ?? '');
if ($digital_checkup_button_text === '') {
    $digital_checkup_button_text = $digital_checkup_defaults['button_text'];
}

$digital_checkup_button_link = trim($digital_checkup_field['button_link'] ?? '');
if ($digital_checkup_button_link === '') {
    $digital_checkup_button_link = $digital_checkup_defaults['button_link'];
}

$digital_checkup_original_price = trim($digital_checkup_field['original_price'] ?? '');
if ($digital_checkup_original_price === '') {
    $digital_checkup_original_price = $digital_checkup_defaults['original_price'];
}

// Process images
$main_image_data = petromin_get_acf_image_data($digital_checkup_field['main_image'] ?? null, 'full', $digital_checkup_defaults['main_image']['url'], $digital_checkup_defaults['main_image']['alt']);
$digital_checkup_bg_desktop_data = petromin_get_acf_image_data($digital_checkup_field['background_desktop'] ?? null, 'full', $digital_checkup_defaults['background_desktop']['url'], $digital_checkup_defaults['background_desktop']['alt']);
$digital_checkup_bg_mobile_data = petromin_get_acf_image_data($digital_checkup_field['background_mobile'] ?? null, 'full', $digital_checkup_defaults['background_mobile']['url'], $digital_checkup_defaults['background_mobile']['alt']);
$icon_data = petromin_get_acf_image_data($digital_checkup_field['icon_image'] ?? null, 'full', $digital_checkup_defaults['icon_image']['url'], $digital_checkup_defaults['icon_image']['alt']);
$check_icon_data = petromin_get_acf_image_data($digital_checkup_field['check_icon'] ?? null, 'full', $digital_checkup_defaults['check_icon']['url'], $digital_checkup_defaults['check_icon']['alt']);

// Process features
$features_input = is_array($digital_checkup_field['features'] ?? null) ? $digital_checkup_field['features'] : [];
$digital_checkup_features = [];

if (!empty($features_input)) {
    foreach ($features_input as $feature) {
        $text = trim($feature['feature_text'] ?? '');
        if ($text !== '') {
            $digital_checkup_features[] = $text;
        }
    }
}

if (empty($digital_checkup_features)) {
    foreach ($digital_checkup_defaults['features'] as $default_feature) {
        $digital_checkup_features[] = $default_feature['feature_text'];
    }
}

// Get the brands section data
$brands_field = function_exists('get_field') ? (get_field('brands_section') ?: []) : [];

// Default values with all original brand images
$brands_defaults = [
    'heading' => 'Expert care for every make and model.',
    'left_slider' => [
        ['image' => ['url' => $images_url . 'image-8.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-9.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-11.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-12.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-13-1.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-15.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-16.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-18.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-20.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-21.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-22.webp', 'alt' => 'Brand'], 'name' => ''],
    ],
    'right_slider' => [
        ['image' => ['url' => $images_url . 'image-25.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-26.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-27.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-28.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-30.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-31.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-32.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-33.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-34.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-22.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-25.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-26.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-27.webp', 'alt' => 'Brand'], 'name' => ''],
    ],
    'mobile_slider' => [
        ['image' => ['url' => $images_url . 'image-15.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-28.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-11.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-18.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-20.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-33.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-26.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-13-1.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-8.webp', 'alt' => 'Brand'], 'name' => ''],
        ['image' => ['url' => $images_url . 'image-27.webp', 'alt' => 'Brand'], 'name' => ''],
    ],
];

// Process the data
$brands_heading = trim($brands_field['heading'] ?? '');
if ($brands_heading === '') {
    $brands_heading = $brands_defaults['heading'];
}

// Process left slider brands
$left_brands_input = is_array($brands_field['left_slider'] ?? null) ? $brands_field['left_slider'] : [];
$left_brands = [];

if (!empty($left_brands_input)) {
    foreach ($left_brands_input as $brand) {
        $image_data = petromin_get_acf_image_data($brand['image'] ?? null, 'full', '', 'Brand');
        $name = trim($brand['name'] ?? '');
        
        if ($image_data) {
            $left_brands[] = [
                'image' => $image_data,
                'name' => $name,
            ];
        }
    }
}

if (empty($left_brands)) {
    foreach ($brands_defaults['left_slider'] as $default_brand) {
        $left_brands[] = [
            'image' => $default_brand['image'],
            'name' => $default_brand['name'],
        ];
    }
}

// Process right slider brands
$right_brands_input = is_array($brands_field['right_slider'] ?? null) ? $brands_field['right_slider'] : [];
$right_brands = [];

if (!empty($right_brands_input)) {
    foreach ($right_brands_input as $brand) {
        $image_data = petromin_get_acf_image_data($brand['image'] ?? null, 'full', '', 'Brand');
        $name = trim($brand['name'] ?? '');
        
        if ($image_data) {
            $right_brands[] = [
                'image' => $image_data,
                'name' => $name,
            ];
        }
    }
}

if (empty($right_brands)) {
    foreach ($brands_defaults['right_slider'] as $default_brand) {
        $right_brands[] = [
            'image' => $default_brand['image'],
            'name' => $default_brand['name'],
        ];
    }
}

// Process mobile slider brands
$mobile_brands_input = is_array($brands_field['mobile_slider'] ?? null) ? $brands_field['mobile_slider'] : [];
$mobile_brands = [];

if (!empty($mobile_brands_input)) {
    foreach ($mobile_brands_input as $brand) {
        $image_data = petromin_get_acf_image_data($brand['image'] ?? null, 'full', '', 'Brand');
        $name = trim($brand['name'] ?? '');
        
        if ($image_data) {
            $mobile_brands[] = [
                'image' => $image_data,
                'name' => $name,
            ];
        }
    }
}

if (empty($mobile_brands)) {
    foreach ($brands_defaults['mobile_slider'] as $default_brand) {
        $mobile_brands[] = [
            'image' => $default_brand['image'],
            'name' => $default_brand['name'],
        ];
    }
}
// Get the app section data
$app_field = function_exists('get_field') ? (get_field('app_section') ?: []) : [];

// Default values
$app_defaults = [
    'heading_line1' => 'All things car.',
    'heading_line2' => 'One tap away.',
    'features' => [
        ['feature_text' => 'One-tap car service booking'],
        ['feature_text' => 'Up to 5X rewards on every visit'],
        ['feature_text' => 'Service history, simplified & stored'],
        ['feature_text' => 'Locate nearby centres instantly'],
        ['feature_text' => 'Chat with us on WhatsApp'],
        ['feature_text' => 'Refer, earn, redeem exclusive rewards'],
    ],
    'play_store_image' => [
        'url' => $images_url . 'assets1.webp',
        'alt' => 'Get it on Google Play',
    ],
    'play_store_link' => '#',
    'app_store_image' => [
        'url' => $images_url . 'app_store_btn.webp',
        'alt' => 'Download on the App Store',
    ],
    'app_store_link' => '#',
    'video' => [
        'url' => get_template_directory_uri() . '/assets/videos/app_promo.mp4',
        'mime_type' => 'video/mp4',
    ],
    'background_desktop' => [
        'url' => $images_url . 'Group-66-scaled.webp',
        'alt' => 'Background pattern',
    ],
    'background_mobile' => [
        'url' => $images_url . 'Frame-18.webp',
        'alt' => 'Background pattern mobile',
    ],
    'feature_icon' => [
        'url' => $images_url . 'Group-1-2.webp',
        'alt' => 'Feature icon',
    ],
];

// Process the data
$app_heading_line1 = trim($app_field['heading_line1'] ?? '');
if ($app_heading_line1 === '') {
    $app_heading_line1 = $app_defaults['heading_line1'];
}

$app_heading_line2 = trim($app_field['heading_line2'] ?? '');
if ($app_heading_line2 === '') {
    $app_heading_line2 = $app_defaults['heading_line2'];
}

// Process features
$features_input = is_array($app_field['features'] ?? null) ? $app_field['features'] : [];
$app_features = [];

if (!empty($features_input)) {
    foreach ($features_input as $feature) {
        $text = trim($feature['feature_text'] ?? '');
        if ($text !== '') {
            $app_features[] = $text;
        }
    }
}

if (empty($app_features)) {
    foreach ($app_defaults['features'] as $default_feature) {
        $app_features[] = $default_feature['feature_text'];
    }
}

// Process links and buttons
$app_play_store_link = trim($app_field['play_store_link'] ?? '');
if ($app_play_store_link === '') {
    $app_play_store_link = $app_defaults['play_store_link'];
}

$app_app_store_link = trim($app_field['app_store_link'] ?? '');
if ($app_app_store_link === '') {
    $app_app_store_link = $app_defaults['app_store_link'];
}

// Process video
$video_field = $app_field['video'] ?? null;
$video_url = '';
$video_type = '';

if (is_array($video_field)) {
    if (!empty($video_field['url'])) {
        $video_url = $video_field['url'];
    }
    if (!empty($video_field['mime_type'])) {
        $video_type = $video_field['mime_type'];
    }
} elseif (is_string($video_field)) {
    $video_url = trim($video_field);
}

if ($video_url === '') {
    $video_url = $app_defaults['video']['url'];
}

if ($video_type === '') {
    $video_type = $app_defaults['video']['mime_type'];
}

// Process images
$play_store_image_data = petromin_get_acf_image_data($app_field['play_store_image'] ?? null, 'full', $app_defaults['play_store_image']['url'], $app_defaults['play_store_image']['alt']);
$app_store_image_data = petromin_get_acf_image_data($app_field['app_store_image'] ?? null, 'full', $app_defaults['app_store_image']['url'], $app_defaults['app_store_image']['alt']);
$app_bg_desktop_data = petromin_get_acf_image_data($app_field['background_desktop'] ?? null, 'full', $app_defaults['background_desktop']['url'], $app_defaults['background_desktop']['alt']);
$app_bg_mobile_data = petromin_get_acf_image_data($app_field['background_mobile'] ?? null, 'full', $app_defaults['background_mobile']['url'], $app_defaults['background_mobile']['alt']);
$feature_icon_data = petromin_get_acf_image_data($app_field['feature_icon'] ?? null, 'full', $app_defaults['feature_icon']['url'], $app_defaults['feature_icon']['alt']);

// Get the testimonials section data
$testimonials_field = function_exists('get_field') ? (get_field('testimonials_section') ?: []) : [];

// Default values
$testimonials_defaults = [
    'heading' => 'Your trust drive us.',
    'nav_icon' => [
        'url' => $images_url . 'fi_19024510.webp',
        'alt' => 'Navigation arrow',
    ],
    'slides' => [
        [
            'slide_type' => 'text',
            'rating' => 5,
            'testimonial_text' => '“Had a good support by this branch manager Mr. Yuvraj and even initial call from the telecaller. This service chain seems to be genuine and trustable. All the best for becoming a most trustable automobile service brand in Chennai and more”',
            'author_name' => 'Arun Madhusudanan',
        ],
        [
            'slide_type' => 'text',
            'rating' => 5,
            'testimonial_text' => '“Had a good support by this branch manager Mr. Yuvraj and even initial call from the telecaller. This service chain seems to be genuine and trustable. All the best for becoming a most trustable automobile service brand in Chennai and more”',
            'author_name' => 'Arun Madhusudanan',
        ],
        [
            'slide_type' => 'video',
            'video_image' => [
                'url' => $images_url . 'image-36.webp',
                'alt' => 'Testimonial Video',
            ],
            'video_url' => '#',
        ],
        [
            'slide_type' => 'video',
            'video_image' => [
                'url' => $images_url . 'image-36.webp',
                'alt' => 'Testimonial Video',
            ],
            'video_url' => '#',
        ],
        [
            'slide_type' => 'text',
            'rating' => 5,
            'testimonial_text' => '“Had a good support by this branch manager Mr. Yuvraj and even initial call from the telecaller. This service chain seems to be genuine and trustable. All the best for becoming a most trustable automobile service brand in Chennai and more”',
            'author_name' => 'Arun Madhusudanan',
        ],
        [
            'slide_type' => 'text',
            'rating' => 5,
            'testimonial_text' => '“Had a good support by this branch manager Mr. Yuvraj and even initial call from the telecaller. This service chain seems to be genuine and trustable. All the best for becoming a most trustable automobile service brand in Chennai and more”',
            'author_name' => 'Arun Madhusudanan',
        ],
        [
            'slide_type' => 'video',
            'video_image' => [
                'url' => $images_url . 'image-36.webp',
                'alt' => 'Testimonial Video',
            ],
            'video_url' => '#',
        ],
        [
            'slide_type' => 'video',
            'video_image' => [
                'url' => $images_url . 'image-36.webp',
                'alt' => 'Testimonial Video',
            ],
            'video_url' => '#',
        ],
    ],
];

// Process the data
$testimonials_heading = trim($testimonials_field['heading'] ?? '');
if ($testimonials_heading === '') {
    $testimonials_heading = $testimonials_defaults['heading'];
}

// Process navigation icon
$nav_icon_data = petromin_get_acf_image_data($testimonials_field['nav_icon'] ?? null, 'full', $testimonials_defaults['nav_icon']['url'], $testimonials_defaults['nav_icon']['alt']);

// Process slides
$slides_input = is_array($testimonials_field['slides'] ?? null) ? $testimonials_field['slides'] : [];
$slides = [];

if (!empty($slides_input)) {
    foreach ($slides_input as $slide) {
        $slide_type = $slide['slide_type'] ?? 'text';
        
        if ($slide_type === 'text') {
            $rating = intval($slide['rating'] ?? 5);
            $testimonial_text = trim($slide['testimonial_text'] ?? '');
            $author_name = trim($slide['author_name'] ?? '');
            
            if ($testimonial_text !== '') {
                $slides[] = [
                    'type' => 'text',
                    'rating' => $rating,
                    'text' => $testimonial_text,
                    'author' => $author_name,
                ];
            }
        } elseif ($slide_type === 'video') {
            $video_image_data = petromin_get_acf_image_data($slide['video_image'] ?? null, 'full', $testimonials_defaults['slides'][2]['video_image']['url'], $testimonials_defaults['slides'][2]['video_image']['alt']);
            $testimonial_video_url = trim($slide['video_url'] ?? '#');
            
            if ($video_image_data) {
                $slides[] = [
                    'type' => 'video',
                    'image' => $video_image_data,
                    'video_url' => $testimonial_video_url,
                ];
            }
        }
    }
}

if (empty($slides)) {
    foreach ($testimonials_defaults['slides'] as $default_slide) {
        if ($default_slide['slide_type'] === 'text') {
            $slides[] = [
                'type' => 'text',
                'rating' => $default_slide['rating'],
                'text' => $default_slide['testimonial_text'],
                'author' => $default_slide['author_name'],
            ];
        } else {
            $slides[] = [
                'type' => 'video',
                'image' => $default_slide['video_image'],
                'video_url' => $default_slide['video_url'],
            ];
        }
    }
}

// Function to render star rating
function render_star_rating($rating) {
    $stars = '';
    for ($i = 0; $i < 5; $i++) {
        $stars .= '<span>';
        if ($i < $rating) {
            $stars .= '<svg class="text-[#FF8300] lg:size-7 size-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="200px" width="200px" xmlns="http://www.w3.org/2000/svg">
                        <path fill="none" d="M0 0h24v24H0z"></path>
                        <path fill="none" d="M0 0h24v24H0z"></path>
                        <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                    </svg>';
        } else {
            $stars .= '<svg class="text-[#FF8300] lg:size-7 size-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="200px" width="200px" xmlns="http://www.w3.org/2000/svg">
                        <path fill="none" d="M0 0h24v24H0z"></path>
                        <path fill="none" d="M0 0h24v24H0z"></path>
                        <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                    </svg>';
        }
        $stars .= '</span>';
    }
    return $stars;
}

// Get the FAQ section data with unique variable names
$faq_section_data = function_exists('get_field') ? (get_field('faq_section') ?: []) : [];

// Default values with unique variable names
$faq_default_values = [
    'heading' => 'FAQs',
    'faq_items' => [
        [
            'question' => 'What types of vehicles do you service?',
            'answer' => 'We provide complete maintenance and repair services for two-wheelers, cars, SUVs, and light commercial vehicles across all major brands in India.',
            'is_active' => true,
        ],
        [
            'question' => 'Do you use genuine spare parts?',
            'answer' => 'Yes, we only use genuine spare parts for all repairs and replacements.',
            'is_active' => false,
        ],
        [
            'question' => 'How can I book a service appointment?',
            'answer' => 'You can book a service appointment through our website, mobile app, or by calling our service center.',
            'is_active' => false,
        ],
        [
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept all major credit/debit cards, UPI, net banking, and cash payments.',
            'is_active' => false,
        ],
        [
            'question' => 'Do you offer any warranty on services?',
            'answer' => 'Yes, our services come with a warranty depending on the type of service performed.',
            'is_active' => false,
        ],
        [
            'question' => 'How long does a typical service take?',
            'answer' => 'A typical service takes 2–4 hours depending on the type of work required.',
            'is_active' => false,
        ],
    ],
];

// Process the data with unique variable names
$faq_heading_text = trim($faq_section_data['heading'] ?? '');
if ($faq_heading_text === '') {
    $faq_heading_text = $faq_default_values['heading'];
}

// Process FAQ items with unique variable names
$faq_items_input = is_array($faq_section_data['faq_items'] ?? null) ? $faq_section_data['faq_items'] : [];
$faq_processed_items = [];

if (!empty($faq_items_input)) {
    foreach ($faq_items_input as $faq_item) {
        $faq_question = trim($faq_item['question'] ?? '');
        $faq_answer = trim($faq_item['answer'] ?? '');
        $faq_active = (bool) ($faq_item['is_active'] ?? false);
        
        if ($faq_question !== '' && $faq_answer !== '') {
            $faq_processed_items[] = [
                'question' => $faq_question,
                'answer' => $faq_answer,
                'is_active' => $faq_active,
            ];
        }
    }
}

if (empty($faq_processed_items)) {
    $faq_processed_items = $faq_default_values['faq_items'];
}

// Split FAQ items into two columns
$faq_total_items = count($faq_processed_items);
$faq_first_column_count = ceil($faq_total_items / 2);
$faq_first_column_items = array_slice($faq_processed_items, 0, $faq_first_column_count);
$faq_second_column_items = array_slice($faq_processed_items, $faq_first_column_count);


?>
<section class="heroSection w-full relative z-0 md:h-dvh h-full">
    <div class="relative w-full h-full overflow-hidden">
        <?php if ($hero_video_url !== ''): ?>
        <video autoplay muted loop playsinline
            class="absolute inset-0 w-full h-full object-cover md:object-left object-left">
            <source src="<?php echo esc_url($hero_video_url); ?>" type="<?php echo esc_attr($hero_video_type); ?>">
            <?php esc_html_e('Your browser does not support the video tag.', 'petromin'); ?>
        </video>
        <?php endif; ?>

        <div class="w-full relative z-10">
            <div class="view max-md:pl-0 flex flex-col max-md:gap-y-16">
                <div class="px-6 mb-8 md:mb-8 pt-16 md:pt-24">
                    <h1
                        class="drop-shadow-[0_4px_8px_rgba(0,0,0,0.6)] lg:text-6xl md:text-5xl text-[2.625rem] mt-6 font-bold italic md:!leading-[4.688rem] w-fit text-white md:block hidden">
                        <?php if ($hero_headline_prefix !== ''): ?>
                        <?php echo esc_html($hero_headline_prefix); ?>
                        <?php endif; ?>
                        <?php if ($hero_headline_highlight !== ''): ?>
                        <span
                            class="relative px-4 !pr-7 backdrop-blur-[0.125rem] -skew-x-[16deg]  before:absolute before:inset-0 before:bg-gradient-to-l before:from-[#CB122D] before:via-[#CB122D] before:to-[#650916] before:-skew-x-[16deg] before:-z-10 before:lg:h-[4.688rem] before:h-[3.688rem] before:flex before:justify-center before:items-center before:top-auto">
                            <?php echo esc_html($hero_headline_highlight); ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($hero_headline_suffix !== ''): ?>
                        <span class="block"><?php echo esc_html($hero_headline_suffix); ?></span>
                        <?php endif; ?>
                    </h1>
                    <h1
                        class="text-[2.625rem] mt-6 font-bold italic leading-[3.2rem] whitespace-nowrap text-white block md:hidden">
                        <?php if ($hero_headline_prefix !== ''): ?>
                        <?php echo esc_html($hero_headline_prefix); ?>
                        <?php endif; ?>
                        <?php if ($hero_headline_highlight !== ''): ?>
                        <span
                            class="relative block px-4 !pr-7 mt-4 w-fit drop-shadow-[0_4px_8px_rgba(0,0,0,0.6)]  bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] -skew-x-[16deg] -z-10 h-[4.688rem] flex justify-center items-center">
                            <span class="skew-x-[16deg]"><?php echo esc_html($hero_headline_highlight); ?></span>
                        </span>
                        <?php endif; ?>
                        <?php if ($hero_headline_suffix_primary !== ''): ?>
                        <?php echo esc_html($hero_headline_suffix_primary); ?>
                        <?php endif; ?>
                        <?php if ($hero_headline_suffix_secondary !== ''): ?>
                        <span class="block"><?php echo esc_html($hero_headline_suffix_secondary); ?></span>
                        <?php endif; ?>
                    </h1>
                </div>
                <?php if (!empty($hero_features)): ?>
                <div
                    class="absolute services_sec md:flex hidden w-fit lg:py-5 py-3  px-8  bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)] top-auto bottom-[-7rem] left-0 origin-top -skew-x-[18deg]">
                    <div class="view w-fit flex items-center justify-center skew-x-[18deg] pr-0">
                        <?php foreach ($hero_features as $feature): ?>
                        <div class="flex flex-col lg:flex-row items-center gap-2 mx-3">
                            <?php if (!empty($feature['icon']['url'])): ?>
                            <img src="<?php echo esc_url($feature['icon']['url']); ?>"
                                alt="<?php echo esc_attr($feature['icon']['alt']); ?>"
                                title="<?php echo esc_attr($feature['icon']['alt']); ?>" class="size-8" loading="lazy"
                                fetchpriority="low">
                            <?php endif; ?>
                            <div class="text-base text-white font-bold font-inter leading-tight">
                                <?php if ($feature['title'] !== ''): ?>
                                <?php echo esc_html($feature['title']); ?>
                                <?php endif; ?>
                                <?php if ($feature['subtitle'] !== ''): ?>
                                <span class="block"><?php echo esc_html($feature['subtitle']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div
                    class="bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)] relative feature-box1 flex md:hidden py-6 px-6 max-w-fit shadow-xl mb-12">
                    <div class="flex flex-col items-start justify-start gap-y-4">
                        <?php $hero_features_count = count($hero_features); ?>
                        <?php foreach ($hero_features as $feature_index => $feature): ?>
                        <div
                            class="flex items-start gap-2 me-3 <?php echo $feature_index < $hero_features_count - 1 ? ' me-3' : ''; ?>">
                            <?php if (!empty($feature['icon']['url'])): ?>
                            <img src="<?php echo esc_url($feature['icon']['url']); ?>"
                                alt="<?php echo esc_attr($feature['icon']['alt']); ?>"
                                title="<?php echo esc_attr($feature['icon']['alt']); ?>" class="size-8" loading="lazy"
                                fetchpriority="low">
                            <?php endif; ?>
                            <p class="text-base text-white font-bold font-inter">
                                <?php if ($feature['title'] !== ''): ?>
                                <?php echo esc_html($feature['title']); ?>
                                <?php endif; ?>
                                <?php if ($feature['subtitle'] !== ''): ?>
                                <span class="block"><?php echo esc_html($feature['subtitle']); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

        </div>
    </div>

</section>


<?php
$home_offers = petromin_get_offers([
    'posts_per_page' => 8,
    'order' => 'DESC'
]);

if (!empty($home_offers)):
?>
<section class="w-full relative offerSection lg:pt-16 pt-[3.75rem] pb-3.313rem] overflow-hidden">
    <div class="view md:pr-0">
        <div class="flex items-center justify-between md:pb-16 pb-12">
            <h2
                class="text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-inter font-bold text-black pr-2 relative z-30 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] lg:after:w-[6.75rem] after:w-20 lg:after:h-3 after:h-[0.625rem] after:-skew-x-[18deg] after:-bottom-6 after:left-0">
                Latest Offers
            </h2>
            <div
                class=" md:flex items-center justify-start hidden origin-bottom z-20 bg-[#CB122D] px-4 shadow-[-6px_6px_0px_-1px_rgba(0,0,0,0.9)] w-56 h-16 transition transform -skew-x-12 duration-150 ease-in-out has-[.swiper-next.swiper-button-lock]:!hidden -mr-[0.506rem]">
                <div class="swiper-prev cursor-pointer !opacity-100 !pointer-events-auto">
                    <span>
                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/fi_19024510.webp"
                            class="text-white size-8 rotate-180 skew-x-12 invert brightness-0" alt="arrow icon"
                            title="arrow icon">
                    </span>
                </div>
                <div class="swiper-next cursor-pointer !opacity-100 !pointer-events-auto">
                    <span>
                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/fi_19024510.webp"
                            class="text-white size-8 skew-x-12 invert brightness-0 mb-[0.188rem] ml-3"
                            alt="arrow icon" title="arrow icon">
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full relative">
        <div class="swiper latestOfferSwiper relative z-0 font-inter">
            <div class="swiper-wrapper py-5 md:py-10 !h-auto">
                <?php
                $home_offers = petromin_get_offers([
                    'posts_per_page' => 8,
                    'order' => 'DESC'
                ]);

                if (!empty($home_offers)):
                    foreach ($home_offers as $offer):
                ?>
                <div class="swiper-slide !h-auto lg:max-w-[33vw] md:max-w-[40vw] max-w-[90vw] transform transition-transform duration-500 ease-out will-change-transform scale-95 blur-[0.1rem] [&.swiper-slide-active]:scale-110 [&.swiper-slide-active]:blur-[0] [&.swiper-slide-active]:z-30">
                    <a href="<?php echo esc_url($offer['url']); ?>" class="w-full block">
                        <div class="w-full h-full bg-gradient-to-l from-[#CB122D] to-[#650916] p-2 relative overflow-hidden h-full group duration-500">
                            <img fetchpriority="low" loading="lazy" 
                                src="<?php echo esc_url($offer['image']['url']); ?>" 
                                width="334" height="334" 
                                alt="<?php echo esc_attr($offer['image']['alt'] ?: $offer['title']); ?>" 
                                title="<?php echo esc_attr($offer['image']['alt'] ?: $offer['title']); ?>"
                                class="w-full h-full object-cover aspect-square" />
                            <div class="w-full flex md:flex-row flex-col md:justify-between justify-center items-center gap-2 py-4">
                                <p class="text-white md:font-bold md:text-base text-sm max-sm:text-center line-clamp-2">
                                    <?php echo esc_html($offer['short_description'] ?: $offer['title']); ?>
                                </p>
                                <div class="shrink-0">
                                    <button class="px-5 flex space-x-3 items-center bg-[#FF8300] h-12">
                                        <span class="flex items-center gap-1 text-base md:font-bold font-semibold text-white">
                                            <?php echo esc_html($offer['button_text'] ?: 'Learn more'); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 14 20" fill="none">
                                                <path d="M13.5294 9.84344L6.92754 19.6791H0L2.20534 16.4006L6.60187 9.84344L2.20534 3.29018L0 0H6.92754L13.5294 9.84344Z" fill="white"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
                    endforeach;
                else:
                ?>
                <div class="swiper-slide !h-auto">
                    <div class="w-full h-full bg-gray-200 p-2 flex items-center justify-center">
                        <p class="text-gray-600">No offers available</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($services_tabs)): ?>
<section class="tabSection relative font-inter overflow-hidden lg:pt-[6.188rem] pt-[6.688rem] lg:block hidden">
    <div class="relative view mb-6">
        <div class="flex items-center justify-between md:pb-6 pb-4">
            <?php if ($services_heading !== ''): ?>
            <h2
                class="text-[1.75rem] md:text-3xl lg:text-4xl/12 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-inter font-bold text-black pr-2">
                <?php echo esc_html($services_heading); ?>
            </h2>
            <?php endif; ?>
        </div>
    </div>
    <div
        class="w-fit relative py-2 z-30 before:absolute before:w-full flex justify-center items-center before:top-auto
            before:bg-gradient-to-r before:h-[3.063rem] before:from-[#000000] before:to-[#000000] before:left-0 before:origin-top before:-skew-x-[18deg]">
        <nav id="tabs-nav" class="view flex items-center relative flex-nowrap gap-3 w-fit min-w-full z-10 !pr-5"
            style="scrollbar-width:none;">
            <?php foreach ($services_tabs as $tab_index => $tab): ?>
            <?php
                $tab_number = $tab_index + 1;
                $is_active = $tab_index === 0;
                $button_classes = 'tab-btn md:px-4 py-5 -my-5 lg:font-bold font-semibold text-lg text-white h-16';
                $inner_classes = 'block text-lg';

                if ($is_active) {
                    $button_classes .= 'tab-btn active relative lg:px-4 py-5 px-3 -my-5 lg:font-bold font-semibold bg-gradient-to-l h-16 from-[#CB122D] via-[#9b2133] to-[#CB122D] text-white -skew-x-[18deg]';
                    $inner_classes = 'skew-x-[18deg] block text-lg';
                }
                ?>
            <button data-tab="<?php echo esc_attr($tab_number); ?>" class="<?php echo esc_attr($button_classes); ?>">
                <span class="<?php echo esc_attr($inner_classes); ?>">
                    <?php echo esc_html($tab['label']); ?>
                </span>
            </button>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="relative tab-content view">
        <?php foreach ($services_tabs as $tab_index => $tab): ?>
        <?php
            $tab_number = $tab_index + 1;
            $content_classes = 'content-item flex lg:flex-row flex-col items-center justify-between py-10';
            if ($tab_index !== 0) {
                $content_classes .= ' hidden';
            }

            $icon_url = $tab['icon']['url'] ?? '';
            $icon_alt = $tab['icon']['alt'] ?? '';
            $image_url = $tab['image']['url'] ?? '';
            $image_alt = $tab['image']['alt'] ?? '';
            $primary_button = $tab['primary_button'] ?? [];
            $secondary_button = $tab['secondary_button'] ?? [];
            $has_primary = !empty($primary_button['label']);
            $has_secondary = !empty($secondary_button['label']);
            ?>
        <div class="<?php echo esc_attr($content_classes); ?>" data-content="<?php echo esc_attr($tab_number); ?>">
            <div class="lg:w-2/5 w-full z-10 pe-0 lg:pe-12 pb-8 lg:pb-0">
                <div class="flex flex-col items-start lg:mb-5 mb-2">
                    <?php if ($icon_url !== ''): ?>
                    <span
                        class="bg-gradient-to-l from-[#CB122D] p-3 to-[#650916] -skew-x-[18deg] mb-5 flex items-center justify-center w-[6.125rem] h-[3.75rem]">
                        <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($icon_alt); ?>"
                            title="<?php echo esc_attr($icon_alt); ?>" class="size-[2.688rem] skew-x-[18deg]"
                            loading="lazy" fetchpriority="low">
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($tab['heading'])): ?>
                    <h3 class="text-2xl md:text-3xl lg:text-[2.5rem] font-bold text-black -ms-3">
                        <?php echo esc_html($tab['heading']); ?>
                    </h3>
                    <?php endif; ?>
                </div>
                <?php if (!empty($tab['hero_description'])): ?>
                <p class="text-black text-base font-normal mb-5 -ms-3">
                    <?php echo nl2br(esc_html($tab['hero_description'])); ?>
                </p>
                <?php endif; ?>
                <?php if (!empty($tab['description'])): ?>
                <div class="text-black text-base font-normal mb-4 -ms-3">
                    <?php echo wp_kses_post($tab['description']); ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($tab['highlight'])): ?>
                <p class="text-[1.375rem] font-bold text-black mb-5 -ms-3">
                    <?php echo esc_html($tab['highlight']); ?>
                </p>
                <?php endif; ?>
                <?php if ($has_primary || $has_secondary): ?>
                <div class="flex items-center lg:pb-10 space-x-4 -ms-3">
                    <?php if ($has_primary): ?>
                    <a href="<?php echo esc_url($primary_button['url'] ?? '#'); ?>"
                        target="<?php echo esc_attr($primary_button['target'] ?? '_self'); ?>"
                        class="bg-[#CB122D] flex items-center text-base hover:bg-red-800 text-white font-semibold py-[0.625rem] px-[0.625rem] transition duration-200">
                        <?php echo esc_html($primary_button['label']); ?>
                        <svg class="size-4 text-white ms-2" stroke="currentColor" fill="none" stroke-width="0"
                            viewBox="0 0 24 24" height="200" width="200" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.5858 6.34317L12 4.92896L19.0711 12L12 19.0711L10.5858 17.6569L16.2427 12L10.5858 6.34317Z"
                                fill="currentColor"></path>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($has_secondary): ?>
                    <a href="<?php echo esc_url($secondary_button['url'] ?? '#'); ?>"
                        target="<?php echo esc_attr($secondary_button['target'] ?? '_self'); ?>"
                        class="bg-[#FF8300] hover:bg-orange-600 flex items-center text-base text-white font-semibold py-[0.625rem] px-[0.625rem] transition duration-200">
                        <?php echo esc_html($secondary_button['label']); ?>
                        <svg class="size-4 text-white ms-2" stroke="currentColor" fill="none" stroke-width="0"
                            viewBox="0 0 24 24" height="200" width="200" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.5858 6.34317L12 4.92896L19.0711 12L12 19.0711L10.5858 17.6569L16.2427 12L10.5858 6.34317Z"
                                fill="currentColor"></path>
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="lg:w-3/5 w-full flex relative z-10">
                <?php if ($image_url !== ''): ?>
                <img src="<?php echo esc_url($image_url); ?>" width="730" height="475"
                    class="object-contain md:object-bottom object-center lg:aspect-[730/475] aspect-[700/475] py-5"
                    loading="lazy" fetchpriority="low" alt="<?php echo esc_attr($image_alt); ?>"
                    title="<?php echo esc_attr($image_alt); ?>">
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="absolute right-0 bottom-0 top-0 w-full md:flex flex-col hidden">
            <img loading="eager" fetchpriority="high" decoding="async"
                src="<?php echo $assets_url ?>img/Group-64-1-scaled.webp" width="730" height="475"
                class="absolute w-full object-cover object-center lg:-top-6 bottom-0 lg:h-full h-72">
        </div>
        <div class="absolute flex flex-col left-0 md:bottom-0 max-sm:top-0 md:hidden">
            <img loading="eager" fetchpriority="high" decoding="async"
                src="<?php echo $assets_url ?>img/Frame-24-1.webp" width="730" height="475" alt="Background"
                class="w-full h-full object-cover object-right">
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($services_tabs)): ?>
<section class="tabSection relative font-inter pt-[6.688rem] block lg:hidden">
    <div class="view">
        <div class="flex items-center justify-between pb-0.5">
            <?php if ($services_heading !== ''): ?>
            <h2
                class="text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-inter font-bold text-black pr-2">
                <?php echo esc_html($services_heading); ?>
            </h2>
            <?php endif; ?>
        </div>
    </div>

    <div class="relative py-8 font-inter overflow-x-auto">
        <nav id="mobile-tab"
            class="w-fit flex items-center justify-between lg:gap-x-3 relative py-3 bg-black shadow-lg z-10 ps-6 h-[2.938rem]">
            <?php foreach ($services_tabs as $tab_index => $tab): ?>
            <?php
                    $tab_number = $tab_index + 1;
                    $is_active = $tab_index === 0;
                    $button_classes = 'm-tab px-3 py-5 h-16 -my-5 lg:font-bold font-semibold text-base text-white whitespace-nowrap lg:whitespace-wrap';
                    $inner_classes = 'block text-base whitespace-nowrap lg:whitespace-wrap';

                    if ($is_active) {
                        $button_classes .= ' active relative bg-gradient-to-l from-[#CB122D] to-[#650916] -skew-x-[12deg] h-16';
                        $inner_classes = 'skew-x-[12deg] block text-base whitespace-nowrap lg:whitespace-wrap';
                    }
                    ?>
            <button data-tab="<?php echo esc_attr($tab_number); ?>" class="<?php echo esc_attr($button_classes); ?>">
                <span class="<?php echo esc_attr($inner_classes); ?>"><?php echo esc_html($tab['label']); ?></span>
            </button>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="relative mtab-content view px-[1.875rem] pt-[0.9rem]">
        <?php foreach ($services_tabs as $tab_index => $tab): ?>
        <?php
                $tab_number = $tab_index + 1;
                $content_classes = 'cont-item flex lg:flex-row flex-col items-center';
                if ($tab_index !== 0) {
                    $content_classes .= ' hidden';
                }

                $icon_url = $tab['icon']['url'] ?? '';
                $icon_alt = $tab['icon']['alt'] ?? '';
                $image_url = $tab['image']['url'] ?? '';
                $image_alt = $tab['image']['alt'] ?? '';
                $primary_button = $tab['primary_button'] ?? [];
                $secondary_button = $tab['secondary_button'] ?? [];
                $has_primary = !empty($primary_button['label']);
                $has_secondary = !empty($secondary_button['label']);
                ?>
        <div class="<?php echo esc_attr($content_classes); ?>" data-content="<?php echo esc_attr($tab_number); ?>">
            <div class="lg:w-2/5 w-full z-10 pe-0 lg:pe-12 pb-8 lg:pb-0">
                <div class="flex flex-col items-start lg:mb-5 mb-2">
                    <?php if ($icon_url !== ''): ?>
                    <span
                        class="bg-gradient-to-l from-[#CB122D] to-[#650916] -skew-x-[18deg] mb-[1.125rem] flex items-center justify-center w-[6.125rem] h-[3.75rem]">
                        <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($icon_alt); ?>"
                            title="<?php echo esc_attr($icon_alt); ?>" class="size-[2.688rem] skew-x-[18deg]"
                            loading="lazy" fetchpriority="low">
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($tab['heading'])): ?>
                    <h3 class="text-2xl md:text-3xl lg:text-[2.5rem] font-bold text-black -ms-3">
                        <?php echo esc_html($tab['heading']); ?>
                    </h3>
                    <?php endif; ?>
                </div>
                <?php if (!empty($tab['hero_description'])): ?>
                <p class="text-[#475467] text-base mb-2 -ms-3">
                    <?php echo nl2br(esc_html($tab['hero_description'])); ?>
                </p>
                <?php endif; ?>
                <?php if (!empty($tab['highlight'])): ?>
                <p class="text-[1.375rem] font-bold text-black mb-[2.25rem] -ms-3">
                    <?php echo esc_html($tab['highlight']); ?>
                </p>
                <?php endif; ?>
                <?php if ($has_primary || $has_secondary): ?>
                <div class="flex items-center lg:pb-10 space-x-4 -ms-3">
                    <?php if ($has_primary): ?>
                    <a href="<?php echo esc_url($primary_button['url'] ?? '#'); ?>"
                        target="<?php echo esc_attr($primary_button['target'] ?? '_self'); ?>"
                        class="bg-[#CB122D] flex items-center text-base hover:bg-red-800 text-white font-semibold py-[0.625rem] px-[0.625rem] transition duration-200">
                        <?php echo esc_html($primary_button['label']); ?>
                        <svg class="size-4 text-white ms-2" stroke="currentColor" fill="none" stroke-width="0"
                            viewBox="0 0 24 24" height="200" width="200" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.5858 6.34317L12 4.92896L19.0711 12L12 19.0711L10.5858 17.6569L16.2427 12L10.5858 6.34317Z"
                                fill="currentColor"></path>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($has_secondary): ?>
                    <a href="<?php echo esc_url($secondary_button['url'] ?? '#'); ?>"
                        target="<?php echo esc_attr($secondary_button['target'] ?? '_self'); ?>"
                        class="bg-[#FF8300] hover:bg-orange-600 flex items-center text-base text-white font-semibold py-[0.625rem] px-[0.625rem] transition duration-200">
                        <?php echo esc_html($secondary_button['label']); ?>
                        <svg class="size-4 text-white ms-2" stroke="currentColor" fill="none" stroke-width="0"
                            viewBox="0 0 24 24" height="200" width="200" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.5858 6.34317L12 4.92896L19.0711 12L12 19.0711L10.5858 17.6569L16.2427 12L10.5858 6.34317Z"
                                fill="currentColor"></path>
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="lg:w-3/5 w-full flex relative z-10">
                <?php if ($image_url !== ''): ?>
                <img src="<?php echo esc_url($image_url); ?>" width="730" height="475"
                    class="object-contain md:object-bottom object-center lg:aspect-[730/475] aspect-[700/475]"
                    loading="lazy" fetchpriority="low" alt="<?php echo esc_attr($image_alt); ?>"
                    title="<?php echo esc_attr($image_alt); ?>">
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($timeline_slides)): ?>
<section class="w-full relative overflow-hidden md:pt-12 pt-[5.063rem]">
    <div class="view md:pr-0">
        <div class="flex items-center justify-between pb-10">
            <?php if ($timeline_heading !== ''): ?>
            <h2
                class="text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-inter font-bold text-black pr-2">
                <?php echo esc_html($timeline_heading); ?>
            </h2>
            <?php endif; ?>
            <?php if (!empty($timeline_nav_icon_data['url'])): ?>
            <div
                class=" md:flex items-center justify-start hidden origin-bottom z-20 bg-[#CB122D] px-4 shadow-[-6px_6px_0px_-1px_rgba(0,0,0,0.9)] md:w-56 w-44 md:h-16 h-10 transition transform -skew-x-12 duration-150 ease-in-out -mr-[0.506rem]">
                <div class="swiper-prev cursor-pointer !opacity-100 !pointer-events-auto">
                    <span>
                        <img src="<?php echo esc_url($timeline_nav_icon_data['url']); ?>"
                            class="text-white md:size-8 size-5 rotate-180 skew-x-12 invert brightness-0"
                            alt="<?php echo esc_attr($timeline_nav_icon_data['alt']); ?>"
                            title="<?php echo esc_attr($timeline_nav_icon_data['alt']); ?>">
                    </span>
                </div>
                <div class="swiper-next cursor-pointer !opacity-100 !pointer-events-auto">
                    <span>
                        <img src="<?php echo esc_url($timeline_nav_icon_data['url']); ?>"
                            class="text-white md:size-8 size-5 skew-x-12 invert brightness-0 mb-[0.188rem] ml-3"
                            alt="<?php echo esc_attr($timeline_nav_icon_data['alt']); ?>"
                            title="<?php echo esc_attr($timeline_nav_icon_data['alt']); ?>">
                    </span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="w-full relative">
        <div class="swiper timelineSectionSwiper relative z-0 py-10 font-inter">
            <div class="swiper-wrapper px-8">
                <?php foreach ($timeline_slides as $slide): ?>
                <div class="swiper-slide !h-auto">
                    <div
                        class="relative flex flex-col w-full h-full bg-gradient-to-l from-[#E5E5E5] to-[#E5E5E5] -skew-x-[18deg] py-8">

                        <?php if (!empty($slide['year'])): ?>
                        <div
                            class="absolute -top-8 -left-4 lg:py-3 lg:px-10 py-3 px-7 font-bold bg-gradient-to-l from-[#CB122D] to-[#650916] text-white lg:-skew-x-[6deg] skew-x-[6deg]">
                            <span
                                class="lg:skew-x-[20deg] skew-x-[10deg] block 2xl:text-[1.5rem] xl:text-2xl lg:text-2xl text-xl 2xl:!leading-[3.063rem]">
                                <?php echo esc_html($slide['year']); ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <div class="flex flex-col items-start skew-x-[18deg] w-full pt-6">
                            <div class="md:pl-14 pl-10 max-w-[90%]">
                                <?php if (!empty($slide['image']['url'])): ?>
                                <img loading="eager" fetchpriority="high" decoding="async"
                                    src="<?php echo esc_url($slide['image']['url']); ?>"
                                    class="lg:h-[7.938rem] lg:w-[13.899rem] h-[5.938rem] w-[9.938rem] object-cover object-center aspect-[89/51]"
                                    width="178" height="102" alt="<?php echo esc_attr($slide['image']['alt']); ?>"
                                    title="<?php echo esc_attr($slide['image']['alt']); ?>">
                                <?php endif; ?>
                                <?php if (!empty($slide['description'])): ?>
                                <div
                                    class="2xl:text-xl xl:text-xl lg:text-lg text-base text-balance font-medium text-black mx-auto pt-6">
                                    <?php echo nl2br(esc_html($slide['description'])); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($partner_highlights_items)): ?>
<section class="w-full relative overflow-hidden lg:py-[4.063rem] pt-[3.125rem] pb-[3.375rem]">
    <div class="w-full flex items-center justify-between relative bg-gradient-to-l from-white to-[rgba(255,255,255,0)]
            before:absolute before:inset-y-0 before:left-0 lg:before:w-[12.5rem] before:w-16
            before:bg-gradient-to-r
            before:from-[#FFFFFF] before:to-[#ffffff00]
            before:z-20 before:pointer-events-none after:absolute after:inset-y-0 after:right-0 lg:after:w-[12.5rem]
            after:bg-gradient-to-l after:w-16
            after:from-[#FFFFFF] after:to-[#ffffff00]
            after:z-20 after:pointer-events-none">
        <div class="swiper partnersSectionSwiper relative">
            <div class="swiper-wrapper !ease-linear flex items-center">
                <?php foreach ($partner_highlights_items as $item_text): ?>
                <div class="swiper-slide flex items-center gap-4 justify-between min-w-max">
                    <div class="text-lg font-bold text-black font-inter whitespace-nowrap">
                        <?php echo esc_html($item_text); ?>
                    </div>
                    <?php if (!empty($partner_highlights_icon['url'])): ?>
                    <img src="<?php echo esc_url($partner_highlights_icon['url']); ?>"
                        class="w-[1.438rem] h-[1.063rem] flex-shrink-0"
                        alt="<?php echo esc_attr($partner_highlights_icon['alt']); ?>"
                        title="<?php echo esc_attr($partner_highlights_icon['alt']); ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="w-full relative font-inter  py-12">
    <?php if ($digital_checkup_bg_desktop_data && !empty($digital_checkup_bg_desktop_data['url'])): ?>
    <div
        class="absolute right-0 h-full md:bottom-0 md:top-auto top-0 justify-center items-center w-full md:flex flex-col z-0 overflow-hidden hidden">
        <img loading="eager" fetchpriority="high" decoding="async"
            src="<?php echo esc_url($digital_checkup_bg_desktop_data['url']); ?>"
            alt="<?php echo esc_attr($digital_checkup_bg_desktop_data['alt']); ?>" width="108" height="108"
            class="w-full object-cover object-center h-full" />
    </div>
    <?php endif; ?>
    <?php if ($digital_checkup_bg_mobile_data && !empty($digital_checkup_bg_mobile_data['url'])): ?>
    <div class="w-full  flex-col absolute right-0 top-0 bottom-auto flex md:hidden h-full">
        <img loading="eager" fetchpriority="high" decoding="async"
            src="<?php echo esc_url($digital_checkup_bg_mobile_data['url']); ?>"
            alt="<?php echo esc_attr($digital_checkup_bg_mobile_data['alt']); ?>" width="430" height="604"
            class="w-full h-full object-cover object-right" />
    </div>
    <?php endif; ?>
    <div class="view">
        <div class="relative flex justify-between md:flex-row flex-col lg:gap-y-6">
            <div
                class="xl:w-1/2 lg:w-1/2 md:w-1/2 w-full flex items-center relative z-10 md:h-full lg:pt-10 md:pt-14 pt-6">
                <div class="flex flex-col gap-y-12">
                    <h2 class="text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem]
                            font-bold italic text-black text-start whitespace-nowrap
                            !leading-tight">
                        <?php if ($digital_checkup_heading_prefix !== ''): ?>
                        <?php echo esc_html($digital_checkup_heading_prefix); ?>
                        <?php endif; ?>

                        <?php if ($digital_checkup_heading_highlight !== ''): ?>
                        <span class="relative italic inline-block px-3 py-1.5 text-white">
                            <span
                                class="absolute inset-0 bg-gradient-to-l from-[#CB122D] to-[#650916] -z-10 -skew-x-[12deg]"></span>
                            <?php echo esc_html($digital_checkup_heading_highlight); ?>
                        </span>
                        <?php endif; ?>

                        <?php if ($digital_checkup_heading_suffix !== ''): ?>
                        <span class=""><?php echo wp_kses($digital_checkup_heading_suffix, ['br' => []]); ?></span>
                        <?php endif; ?>
                    </h2>
                    <div class="flex items-center md:gap-5 gap-3">
                        <a href="<?php echo esc_url($digital_checkup_button_link); ?>"
                            class="bg-[#CB122D] flex items-center lg:text-base text-sm hover:bg-red-800 text-white font-semibold py-[0.625rem] px-3 transition duration-200">
                            <?php echo esc_html($digital_checkup_button_text); ?>
                            <svg class="size-6 text-white ms-2" stroke="currentColor" fill="none" stroke-width="0"
                                viewBox="0 0 24 24" height="200px" width="200px" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.5858 6.34317L12 4.92896L19.0711 12L12 19.0711L10.5858 17.6569L16.2427 12L10.5858 6.34317Z"
                                    fill="currentColor"></path>
                            </svg>
                        </a>
                        <span
                            class="2xl:text-2xl lg:text-xl text-lg font-bold italic"><?php echo esc_html($digital_checkup_original_price); ?></span>
                    </div>
                </div>
            </div>

            <div class="xl:w-1/2 lg:w-1/2 md:w-1/2 w-full flex items-center relative z-10">
                <?php if ($main_image_data && !empty($main_image_data['url'])): ?>
                <img loading="eager" fetchpriority="high" decoding="async"
                    src="<?php echo esc_url($main_image_data['url']); ?>"
                    alt="<?php echo esc_attr($main_image_data['alt']); ?>"
                    class="object-contain md:object-bottom object-center aspect-[775/516]">
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="view pl-0 md:absolute z-30 md:-bottom-[2rem] max-sm:h-full max-sm:-mb-[4.75rem]">
        <div
            class="lg:pl-[5rem] md:pl-[4rem] pl-[3rem] md:pt-11 pt-8 md:pr-[5.5rem] pr-[2rem] md:pb-[4.125rem] pb-[2.125rem] relative w-fit bg-gradient-to-r from-[#000000] to-[#414141]  z-[99] origin-top -skew-x-[14deg] ">
            <div class="relative flex items-start gap-5 skew-x-[14deg] ">
                <?php if ($icon_data && !empty($icon_data['url'])): ?>
                <img loading="eager" fetchpriority="high" decoding="async"
                    src="<?php echo esc_url($icon_data['url']); ?>" alt="<?php echo esc_attr($icon_data['alt']); ?>"
                    width="108" height="108" class="w-auto object-cover lg:h-[10rem] h-[5rem] aspect-square" />
                <?php endif; ?>

                <ul class="flex flex-col gap-y-[0.375rem]">
                    <?php foreach ($digital_checkup_features as $feature): ?>
                    <li class="flex items-center gap-x-3">
                        <?php if ($check_icon_data && !empty($check_icon_data['url'])): ?>
                        <img src="<?php echo esc_url($check_icon_data['url']); ?>"
                            alt="<?php echo esc_attr($check_icon_data['alt']); ?>"
                            class="w-5 h-5 md:w-6 md:h-6 flex-shrink-0" loading="lazy" fetchpriority="low">
                        <?php endif; ?>
                        <span
                            class="text-white md:text-sm text-xs font-semibold"><?php echo esc_html($feature); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="w-full relative overflow-hidden md:pt-[6.8rem] pt-[5rem]">
    <div class="view max-sm:mb-8">
        <div class="flex items-center justify-between">
            <h2
                class="relative text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 z-30 font-inter font-bold text-black pr-2 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] lg:after:w-[6.75rem] after:w-20 lg:after:h-3 after:h-[0.625rem] after:-skew-x-[18deg] after:-bottom-6 after:left-0">
                <?php echo esc_html($brands_heading); ?>
            </h2>
        </div>
    </div>
    <div class="bg-gradient-to-l from-white to-[rgba(255,255,255,0)] 
            before:absolute before:inset-y-0 before:left-0 lg:before:w-[13.375] before:w-16 
            before:bg-gradient-to-r 
            before:from-[#FFFFFF] before:to-[#ffffff00]
            before:z-20 before:pointer-events-none after:absolute after:inset-y-0 after:right-0 lg:after:w-w-[13.375] 
            after:bg-gradient-to-l after:w-16
            after:from-[#FFFFFF] after:to-[#ffffff00]
            after:z-20 after:pointer-events-none">

        <!-- Left Slider (Desktop) -->
        <?php if (!empty($left_brands)): ?>
        <div class="swiper brandsSectionSwiperLeft relative z-0 lg:pt-[4.5rem]">
            <div class="w-full swiper-wrapper flex !ease-linear">
                <?php foreach ($left_brands as $brand): ?>
                <div class="swiper-slide">
                    <img loading="eager" fetchpriority="high" decoding="async"
                        src="<?php echo esc_url($brand['image']['url']); ?>"
                        alt="<?php echo esc_attr($brand['image']['alt']); ?>" width="108" height="108"
                        class="w-32 aspect-[8/9]" />
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Right Slider (Desktop) -->
        <?php if (!empty($right_brands)): ?>
        <div class="swiper brandsSectionSwiperRight relative z-0">
            <div class="w-full swiper-wrapper !ease-linear flex">
                <?php foreach ($right_brands as $brand): ?>
                <div class="swiper-slide">
                    <img loading="eager" fetchpriority="high" decoding="async"
                        src="<?php echo esc_url($brand['image']['url']); ?>"
                        alt="<?php echo esc_attr($brand['image']['alt']); ?>" width="108" height="108"
                        class="w-32 aspect-[8/9]" />
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Mobile Slider -->
        <?php if (!empty($mobile_brands)): ?>
        <div class="swiper brandsSectionSwiperMobile relative z-0 lg:hidden block">
            <div class="w-full swiper-wrapper !ease-linear flex">
                <?php foreach ($mobile_brands as $brand): ?>
                <div class="swiper-slide">
                    <img loading="eager" fetchpriority="high" decoding="async"
                        src="<?php echo esc_url($brand['image']['url']); ?>"
                        alt="<?php echo esc_attr($brand['image']['alt']); ?>" width="108" height="108"
                        class="w-32 aspect-[8/9]" />
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<section
    class="w-full relative overflow-hidden z-30 font-inter md:mt-[6.5rem] md:pt-0 pt-[2.5rem] mt-[3rem] md:pb-20 pb-10">
    <div class="view">
        <div class="relative flex items-center justify-between md:flex-row flex-wrap flex-col gap-y-6 h-full">
            <div class="md:w-5/12 w-full flex items-center relative z-30">
                <div class="flex flex-col gap-y-7 relative z-30">
                    <h2
                        class="text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-bold whitespace-nowrap text-black">
                        <?php echo esc_html($app_heading_line1); ?> <span class="block">
                            <?php echo esc_html($app_heading_line2); ?>
                        </span>
                    </h2>
                    <ul class="space-y-3">
                        <?php foreach ($app_features as $feature): ?>
                        <li class="flex items-center gap-x-3">
                            <span class="inline-block flex items-center justify-center">
                                <?php if ($feature_icon_data && !empty($feature_icon_data['url'])): ?>
                                <img loading="eager" fetchpriority="high" decoding="async"
                                    src="<?php echo esc_url($feature_icon_data['url']); ?>"
                                    alt="<?php echo esc_attr($feature_icon_data['alt']); ?>" width="337" height="484"
                                    class="size-6" />
                                <?php endif; ?>
                            </span>
                            <span class="text-black text-sm font-semibold"><?php echo esc_html($feature); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="lg:flex items-center gap-3 hidden">
                        <?php if ($play_store_image_data && !empty($play_store_image_data['url'])): ?>
                        <span>
                            <a href="<?php echo esc_url($app_play_store_link); ?>" target="_blank">
                                <img loading="eager" fetchpriority="high" decoding="async"
                                    src="<?php echo esc_url($play_store_image_data['url']); ?>"
                                    alt="<?php echo esc_attr($play_store_image_data['alt']); ?>" width="108"
                                    height="108" class="w-auto h-10 object-contain" />
                            </a>
                        </span>
                        <?php endif; ?>
                        <?php if ($app_store_image_data && !empty($app_store_image_data['url'])): ?>
                        <span>
                            <a href="<?php echo esc_url($app_app_store_link); ?>" target="_blank">
                                <img src="<?php echo esc_url($app_store_image_data['url']); ?>"
                                    alt="<?php echo esc_attr($app_store_image_data['alt']); ?>"
                                    class="w-auto h-10 object-contain" loading="lazy" fetchpriority="low">
                            </a>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div
                class="md:w-7/12 w-full flex items-center justify-center relative z-10 bg-transparent text-white md:pt-10">
                <?php if ($video_url !== ''): ?>
                <video width="100%" height="100%" class="w-full h-auto aspect-[16/9]" controls autoplay muted loop playsinline>
                    <source src=" <?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($video_type); ?>">
                    Your browser does not support the video tag.
                </video>
                <?php endif; ?>
            </div>
        </div>
        <div class="relative z-10 flex items-center gap-3 lg:hidden pt-6 pb-3">
            <?php if ($play_store_image_data && !empty($play_store_image_data['url'])): ?>
            <span>
                <a href="<?php echo esc_url($app_play_store_link); ?>" target="_blank">
                    <img loading="eager" fetchpriority="high" decoding="async"
                        src="<?php echo esc_url($play_store_image_data['url']); ?>"
                        alt="<?php echo esc_attr($play_store_image_data['alt']); ?>" width="108" height="108"
                        class="w-auto md:h-10 h-8 object-contain" />
                </a>
            </span>
            <?php endif; ?>
            <?php if ($app_store_image_data && !empty($app_store_image_data['url'])): ?>
            <span>
                <a href="<?php echo esc_url($app_app_store_link); ?>" target="_blank">
                    <img src="<?php echo esc_url($app_store_image_data['url']); ?>"
                        alt="<?php echo esc_attr($app_store_image_data['alt']); ?>"
                        class="w-auto md:h-10 h-8 object-contain" loading="lazy" fetchpriority="low">
                </a>
            </span>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($app_bg_desktop_data && !empty($app_bg_desktop_data['url'])): ?>
    <div class="absolute inset-0 w-full h-full -z-10 md:flex hidden">
        <img loading="eager" fetchpriority="high" decoding="async"
            src="<?php echo esc_url($app_bg_desktop_data['url']); ?>"
            alt="<?php echo esc_attr($app_bg_desktop_data['alt']); ?>"
            class="w-full h-full object-cover object-right" />
    </div>
    <?php endif; ?>

    <?php if ($app_bg_mobile_data && !empty($app_bg_mobile_data['url'])): ?>
    <div class="absolute inset-0 w-full h-full -z-10 flex md:hidden">
        <img loading="eager" fetchpriority="high" decoding="async"
            src="<?php echo esc_url($app_bg_mobile_data['url']); ?>"
            alt="<?php echo esc_attr($app_bg_mobile_data['alt']); ?>"
            class="w-full h-full object-cover object-center" />
    </div>
    <?php endif; ?>
</section>

<section class="w-full relative testimSection lg:pt-[6.75rem] pt-[6.5rem] pb-[2.625rem] overflow-hidden">
    <div class="view md:pr-0">
        <div class="flex items-center justify-between md:pb-12 pb-6">
            <h2
                class="text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-inter font-bold text-black pr-2">
                <?php echo esc_html($testimonials_heading); ?>
            </h2>
            <?php if ($nav_icon_data && !empty($nav_icon_data['url'])): ?>
            <div
                class="md:flex items-center justify-start hidden origin-bottom z-20 bg-[#CB122D] px-4 shadow-[-6px_6px_0px_-1px_rgba(0,0,0,0.9)] md:w-56 w-44 md:h-16 h-10 transition transform -skew-x-12 duration-150 ease-in-out -mr-[0.506rem]">
                <div class="swiper-prev cursor-pointer !opacity-100 !pointer-events-auto">
                    <span>
                        <img src="<?php echo esc_url($nav_icon_data['url']); ?>"
                            class="text-white md:size-8 size-5 rotate-180 skew-x-12 invert brightness-0"
                            alt="<?php echo esc_attr($nav_icon_data['alt']); ?>">
                    </span>
                </div>
                <div class="swiper-next cursor-pointer !opacity-100 !pointer-events-auto">
                    <span>
                        <img src="<?php echo esc_url($nav_icon_data['url']); ?>"
                            class="text-white md:size-8 size-5 skew-x-12 invert brightness-0 mb-[0.188rem] ml-3"
                            alt="<?php echo esc_attr($nav_icon_data['alt']); ?>">
                    </span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($slides)): ?>
    <div class="swiper testimonialsSectionSwiper relative font-inter z-0 pt-6">
        <div class="swiper-wrapper h-auto">
            <?php foreach ($slides as $slide): ?>
            <?php if ($slide['type'] === 'text'): ?>
            <div
                class="swiper-slide bg-[#E3E3E3] lg:py-[2.438rem] lg:px-[2.313rem] py-[2.125rem] px-[1.5rem] overflow-hidden group">
                <div class="relative h-full">
                    <div class="flex flex-col">
                        <div class="flex lg:mb-6 mb-2">
                            <?php echo render_star_rating($slide['rating']); ?>
                        </div>
                        <p class="2xl:text-2xl text-[0.938rem] text-[#000000] lg:mb-14 mb-6">
                            <?php echo nl2br(esc_html($slide['text'])); ?>
                        </p>
                        <?php if (!empty($slide['author'])): ?>
                        <h4 class="font-semibold text-[#CB122D] 2xl:text-xl lg:text-base">
                            <?php echo esc_html($slide['author']); ?></h4>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="swiper-slide flex flex-col items-stretch bg-white overflow-hidden group relative h-full">
                <div class="relative h-full">
                    <?php if (!empty($slide['image']['url'])): ?>
                    <img loading="eager" fetchpriority="high" decoding="async"
                        src="<?php echo esc_url($slide['image']['url']); ?>" width="337" height="410"
                        alt="<?php echo esc_attr($slide['image']['alt']); ?>"
                        class="w-full h-full object-cover aspect-[337/410]" />
                    <?php endif; ?>
                    <div
                        class="absolute inset-0 flex justify-center items-center opacity-100 group-hover:opacity-100 transition">
                        <a href="<?php echo esc_url($slide['video_url']); ?>" data-fancybox="testimonials"
                            data-width="640" data-height="480"
                            class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-white text-white shadow-lg hover:bg-white hover:text-red-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                class="w-6 h-6">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<section class="w-full relative md:pt-[4rem] pt-[3rem] md:pb-[6rem] pb-[2.625rem] font-inter">
    <div class="view" id="faqAccordion">
        <div class="flex items-center justify-between md:pb-6 pb-4">
            <h2
                class="relative text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-inter font-bold text-black pr-2 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] lg:after:w-[6.75rem] after:w-20 lg:after:h-3 after:h-[0.625rem] after:-skew-x-[18deg] after:-bottom-6 after:left-0">
                <?php echo esc_html($faq_heading_text); ?>
            </h2>
        </div>

        <div class="grid md:grid-cols-2 gap-6 pt-16">
            <!-- First Column -->
            <div class="space-y-5">
                <?php foreach ($faq_first_column_items as $index => $faq_item): ?>
                <?php
                    $faq_is_active = $faq_item['is_active'];
                    $faq_item_classes = 'accordion-item border border-black';
                    $faq_header_classes = 'accordion-header w-full px-6 py-4 flex justify-between items-center text-left font-semibold ' . ($faq_is_active ? 'text-[#CB122D]' : 'text-gray-800');
                    $faq_icon_text = $faq_is_active ? '−' : '+';
                    $faq_body_classes = 'accordion-body px-6 pb-4 pt-2 text-sm text-[#010101] font-normal' . ($faq_is_active ? '' : ' hidden');
                    ?>
                <div class="<?php echo esc_attr($faq_item_classes); ?>">
                    <button class="<?php echo esc_attr($faq_header_classes); ?>">
                        <span><?php echo esc_html($faq_item['question']); ?></span>
                        <span
                            class="accordion-icon text-white bg-[#CB122D] size-6 flex items-center justify-center"><?php echo esc_html($faq_icon_text); ?></span>
                    </button>
                    <div class="<?php echo esc_attr($faq_body_classes); ?>">
                        <?php echo nl2br(esc_html($faq_item['answer'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Second Column -->
            <div class="space-y-5">
                <?php foreach ($faq_second_column_items as $index => $faq_item): ?>
                <?php
                    $faq_is_active = $faq_item['is_active'];
                    $faq_item_classes = 'accordion-item border border-black';
                    $faq_header_classes = 'accordion-header w-full px-6 py-4 flex justify-between items-center text-left font-semibold ' . ($faq_is_active ? 'text-[#CB122D]' : 'text-gray-800');
                    $faq_icon_text = $faq_is_active ? '−' : '+';
                    $faq_body_classes = 'accordion-body px-6 pb-4 pt-2 text-sm text-[#010101] font-normal' . ($faq_is_active ? '' : ' hidden');
                    ?>
                <div class="<?php echo esc_attr($faq_item_classes); ?>">
                    <button class="<?php echo esc_attr($faq_header_classes); ?>">
                        <span><?php echo esc_html($faq_item['question']); ?></span>
                        <span
                            class="accordion-icon text-white bg-[#CB122D] size-6 flex items-center justify-center"><?php echo esc_html($faq_icon_text); ?></span>
                    </button>
                    <div class="<?php echo esc_attr($faq_body_classes); ?>">
                        <?php echo nl2br(esc_html($faq_item['answer'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<div
    class="whatsapp-icon flex items-center text-white bg-[#29A71A] rounded-full w-fit p-2 fixed bottom-10 right-4 z-40">
    <a href="#" class="">
        <svg class="size-10" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512"
            height="200px" width="200px" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z">
            </path>
        </svg>
    </a>
</div>



<?php get_footer(); ?>

<script>
const headers = document.querySelectorAll('#faqAccordion .accordion-header');

headers.forEach(header => {
    header.addEventListener('click', () => {
        const item = header.parentElement;
        const body = item.querySelector('.accordion-body');
        const icon = header.querySelector('.accordion-icon');

        const isActive = !body.classList.contains('hidden');

        // Close all
        document.querySelectorAll('#faqAccordion .accordion-body').forEach(el => el.classList.add(
            'hidden'));
        document.querySelectorAll('#faqAccordion .accordion-icon').forEach(el => el.textContent =
            '+');
        document.querySelectorAll('#faqAccordion .accordion-header').forEach(el => {
            el.classList.remove('text-[#CB122D]');
            el.classList.add('text-gray-800');
        });

        // Reopen only if it was not active
        if (!isActive) {
            body.classList.remove('hidden');
            icon.textContent = '−';
            header.classList.add('text-[#CB122D]');
            header.classList.remove('text-gray-800');
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabsContainer = document.getElementById('tabs-nav');
    const tabLinks = document.querySelectorAll(".tab-btn");
    const tabContents = document.querySelectorAll(".content-item");

    function resetTabs(clicked) {
        tabLinks.forEach(tab => {
            if (tab !== clicked) { // don’t reset the one that was clicked
                tab.className = "tab-btn px-4 py-5 -my-5 h-16 text-lg font-bold text-white";
                tab.innerHTML = tab.innerText;
            }
        });
    }

    tabsContainer.addEventListener("click", function(e) {
        const clicked = e.target.closest(".tab-btn");
        if (!clicked) return;

        const target = clicked.dataset.tab;

        // Reset other tabs
        resetTabs(clicked);

        // Reset content
        tabContents.forEach(c => c.classList.add("hidden"));

        // Apply active styles to clicked
        clicked.className =
            "tab-btn active relative z-10 px-4 py-5 h-16 text-lg -my-5 font-bold bg-gradient-to-l from-[#CB122D] via-[#9b2133] to-[#CB122D] text-white -skew-x-[18deg]";
        clicked.innerHTML =
            `<span class="skew-x-[18deg] block ">${clicked.innerText}</span>`;

        // Show correct content
        document.querySelector(`.content-item[data-content="${target}"]`).classList.remove(
            "hidden");
    });
});
</script>


    <script> document.addEventListener("DOMContentLoaded", function () {
        <?php 
        $latestSaveSettings = petromin_get_swiper_settings('latestOfferSwiper');
        ?>
        const swiper = new Swiper(".latestOfferSwiper", {
                speed: <?php echo esc_js($latestSaveSettings['speed']); ?>,
                autoplay: <?php echo $latestSaveSettings['autoplay'] ? '{
                    delay: ' . esc_js($latestSaveSettings['delay']) . ',
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                }' : 'false'; ?>,
                spaceBetween: 30,
                loop: true,
                centeredSlides: true,
                autoHeight: true,
                pagination: { el: ".swiper-pagination", clickable: true, },
                navigation: { nextEl: ".swiper-next", prevEl: ".swiper-prev", },
                breakpoints: {
                    320: { slidesPerView: 1.5, },
                    480: { slidesPerView: 3.3, },
                    640: { slidesPerView: 3.5, },
                    1024: { slidesPerView: 3.3, },
                    1350: { slidesPerView: 3.4, },
                },
            });
        }); 
    </script>