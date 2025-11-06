<?php
/* Template Name: about us page */
get_header();

$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$images_url = $assets_url . '/img';
$icons_url  = $images_url . '/icons';

$hero_defaults = [
    'title' => 'Get the best car service in India.',
    'image' => [
        'url' => $images_url . '/about_us_hero_img.webp',
        'alt' => 'Get the best car service in India.',
    ],
];

$sidebar_defaults = [
    'background' => [
        'url' => $images_url . '/about_us_roadmap.webp',
        'alt' => '',
    ],
    'car' => [
        'url' => $images_url . '/about_car.webp',
        'alt' => 'Petromin Express car illustration',
    ],
];

$intro_defaults = [
    'heading' => 'Your #1 choice for quality repairs and maintenance.',
    'description' => 'As India’s largest company-owned, company-operated (COCO) car service network, our culture of ownership ensures your car receives unmatched attention. Every technician owns their service bay, treating your vehicle with care and dedication at every step.',
    'image' => [
        'url' => $images_url . '/about_mask.webp',
        'alt' => 'Your #1 choice for quality repairs and maintenance.',
    ],
];

$journey_defaults = [
    [
        'slide_year_label' => '2022',
        'slide_description' => 'Four Petromin Express stations launched in Chennai – OMR, Central, T. Nagar, and Whites Road',
        'slide_image' => [
            'url' => $images_url . '/image-42.webp',
            'alt' => 'Four Petromin Express stations launched in Chennai – OMR, Central, T. Nagar, and Whites Road',
        ],
    ],
    [
        'slide_year_label' => 'August 2023',
        'slide_description' => 'Petromin Express stations launched at Kattankulathur, Sholinganallur, and Tambaram',
        'slide_image' => [
            'url' => $images_url . '/image-2.webp',
            'alt' => 'Petromin Express stations launched at Kattankulathur, Sholinganallur, and Tambaram',
        ],
    ],
    [
        'slide_year_label' => 'September 2023',
        'slide_description' => 'HP Petromin Express stations launched in Avadi, Kattupakkam, Kilpauk, and Pallikaranai',
        'slide_image' => [
            'url' => $images_url . '/image-42.webp',
            'alt' => 'HP Petromin Express stations launched in Avadi, Kattupakkam, Kilpauk, and Pallikaranai',
        ],
    ],
    [
        'slide_year_label' => 'October 2023',
        'slide_description' => 'First HP Petromin Express station launched in Bangalore – Peenya',
        'slide_image' => [
            'url' => $images_url . '/image-2.webp',
            'alt' => 'First HP Petromin Express station launched in Bangalore – Peenya',
        ],
    ],
    [
        'slide_year_label' => 'November 2023',
        'slide_description' => 'Six HP Petromin Express stations launched across Bangalore',
        'slide_image' => [
            'url' => $images_url . '/image-42.webp',
            'alt' => 'Six HP Petromin Express stations launched across Bangalore',
        ],
    ],
    [
        'slide_year_label' => 'December 2023',
        'slide_description' => 'HP Petromin Express station launched in Madhavaram, Chennai',
        'slide_image' => [
            'url' => $images_url . '/image-2.webp',
            'alt' => 'HP Petromin Express station launched in Madhavaram, Chennai',
        ],
    ],
    [
        'slide_year_label' => 'January 2024',
        'slide_description' => 'HP Petromin Express station launched in Kasturba Road, Bangalore',
        'slide_image' => [
            'url' => $images_url . '/image-42.webp',
            'alt' => 'HP Petromin Express station launched in Kasturba Road, Bangalore',
        ],
    ],
    [
        'slide_year_label' => 'October 2025',
        'slide_description' => 'Two new Petromin Express stations launched in Kovur and Pallavaram',
        'slide_image' => [
            'url' => $images_url . '/image-2.webp',
            'alt' => 'Two new Petromin Express stations launched in Kovur and Pallavaram',
        ],
    ],
];

$mvv_defaults = [
    'vision_title' => 'Vision',
    'vision_description' => 'To redefine car care by delivering dealership-level expertise with the trust, convenience, and transparency customers deserve.',
    'mission_title' => 'Mission',
    'mission_description' => 'We strive to make every car service experience simple, reliable, and fair, combining modern technology with skilled technicians to ensure valued service and greater peace of mind.',
    'values_title' => 'Values',
    'values_items' => [
        [
            'value_title' => 'Honest Pricing',
            'value_image' => [
                'url' => $images_url . '/honest_pricing.webp',
                'alt' => 'Honest Pricing',
            ],
            'value_icon' => [
                'url' => $icons_url . '/icon-honest-pricing.svg',
                'alt' => 'Honest Pricing icon',
            ],
        ],
        [
            'value_title' => 'Certified Technicians',
            'value_image' => [
                'url' => $images_url . '/certified-technicians.webp',
                'alt' => 'Certified Technicians',
            ],
            'value_icon' => [
                'url' => $icons_url . '/icon-certified-technicians.svg',
                'alt' => 'Certified Technicians icon',
            ],
        ],
        [
            'value_title' => 'Genuine Parts',
            'value_image' => [
                'url' => $images_url . '/genuine-parts.webp',
                'alt' => 'Genuine Parts',
            ],
            'value_icon' => [
                'url' => $icons_url . '/icon-genuine-parts.svg',
                'alt' => 'Genuine Parts icon',
            ],
        ],
        [
            'value_title' => 'Warranty Coverage',
            'value_image' => [
                'url' => $images_url . '/warranty-coverage.webp',
                'alt' => 'Warranty Coverage',
            ],
            'value_icon' => [
                'url' => $icons_url . '/icon-warranty-coverage.svg',
                'alt' => 'Warranty Coverage icon',
            ],
        ],
    ],
];

$excellence_defaults = [
    [
        'card_title' => 'Customer Convenience',
        'card_description' => 'Flexible scheduling, comfortable waiting areas, and same-day service options.',
        'card_image' => [
            'url' => $images_url . '/customer_convenience.webp',
            'alt' => 'Customer Convenience',
        ],
    ],
    [
        'card_title' => 'Trusted Reputation',
        'card_description' => 'Decades of satisfied customers and strong community relationships built on reliability.',
        'card_image' => [
            'url' => $images_url . '/trusted_reputation.webp',
            'alt' => 'Trusted Reputation',
        ],
    ],
    [
        'card_title' => 'Clear Communication',
        'card_description' => 'Detailed service explanations in simple terms throughout the entire repair process.',
        'card_image' => [
            'url' => $images_url . '/clear_communication.webp',
            'alt' => 'Clear Communication',
        ],
    ],
    [
        'card_title' => 'Honest Pricing',
        'card_description' => 'Transparent estimates, no hidden costs, and value-driven service you can trust.',
        'card_image' => [
            'url' => $images_url . '/honest_pricing.webp',
            'alt' => 'Honest Pricing',
        ],
    ],
];

$wheel_defaults = [
    'heading' => "Expert hands\nfor every journey",
    'wheel_images' => [
        'desktop' => [
            'url' => $images_url . '/expert_hands_circle.webp',
            'alt' => 'Expert hands service wheel',
        ],
        'mobile' => [
            'url' => $images_url . '/expert_hands_circle_mobile_full.webp',
            'alt' => 'Expert hands service wheel mobile',
        ],
    ],
    'services' => [
        [
            'service_title' => 'Car Service',
            'service_description' => 'Stay road-ready with proactive inspections & upkeep',
        ],
        [
            'service_title' => 'Battery Service',
            'service_description' => 'Reliable starts ensured with testing & replacements',
        ],
        [
            'service_title' => 'Tyre Care',
            'service_description' => 'Optimize safety & performance with rotations, alignments & replacements',
        ],
        [
            'service_title' => 'AC Service',
            'service_description' => 'Cool comfort with system checks & refills',
        ],
        [
            'service_title' => 'Headlight Polish',
            'service_description' => 'Shine bright with expert headlight restoration',
        ],
        [
            'service_title' => 'Body Shop',
            'service_description' => "Restore your car's appearance with expert repairs",
        ],
        [
            'service_title' => 'Engine Care',
            'service_description' => 'Ensure smooth performance with routine maintenance & diagnostics',
        ],
        [
            'service_title' => 'Eco Car Wash',
            'service_description' => 'Pamper your car with gentle, thorough cleaning',
        ],
    ],
];

$hero_field = function_exists('get_field') ? (get_field('hero_section') ?: []) : [];
$hero_title = trim($hero_field['hero_title'] ?? '') ?: $hero_defaults['title'];
$hero_image_data = petromin_get_acf_image_data($hero_field['hero_image'] ?? null, 'full', $hero_defaults['image']['url'], $hero_defaults['image']['alt']);
$hero_image_title = trim($hero_field['hero_image_title'] ?? '') ?: ($hero_image_data['alt'] ?? $hero_defaults['image']['alt']);

$sidebar_field = function_exists('get_field') ? (get_field('roadmap_sidebar') ?: []) : [];
$sidebar_background = petromin_get_acf_image_data($sidebar_field['background_image'] ?? null, 'full', $sidebar_defaults['background']['url'], $sidebar_defaults['background']['alt']);
$sidebar_car = petromin_get_acf_image_data($sidebar_field['car_image'] ?? null, 'full', $sidebar_defaults['car']['url'], $sidebar_defaults['car']['alt']);

$intro_field = function_exists('get_field') ? (get_field('about_intro') ?: []) : [];
$intro_heading = trim($intro_field['intro_heading'] ?? '') ?: $intro_defaults['heading'];
$intro_description_text = trim($intro_field['intro_description'] ?? '') ?: $intro_defaults['description'];
$intro_description_html = wp_kses_post(nl2br(esc_html($intro_description_text)));
$intro_image = petromin_get_acf_image_data($intro_field['intro_image'] ?? null, 'full', $intro_defaults['image']['url'], $intro_defaults['image']['alt']);

$journey_field = function_exists('get_field') ? (get_field('journey_section') ?: []) : [];
$journey_heading = trim($journey_field['journey_heading'] ?? '') ?: 'A standard of quality throughout history.';
$journey_rows = !empty($journey_field['slides']) && is_array($journey_field['slides']) ? $journey_field['slides'] : [];
$journey_count = max(count($journey_rows), count($journey_defaults));
if ($journey_count === 0) {
    $journey_count = count($journey_defaults);
}
$journey_slides = [];
for ($i = 0; $i < $journey_count; $i++) {
    $fallback = $journey_defaults[$i] ?? [];
    $row = $journey_rows[$i] ?? [];
    $year = trim($row['slide_year_label'] ?? '') ?: ($fallback['slide_year_label'] ?? '');
    $description = trim($row['slide_description'] ?? '') ?: ($fallback['slide_description'] ?? '');
    $image_default = $fallback['slide_image'] ?? [];
    $image = petromin_get_acf_image_data($row['slide_image'] ?? null, 'full', $image_default['url'] ?? '', $image_default['alt'] ?? '');

    if (!$year && !$description && !$image) {
        continue;
    }

    $journey_slides[] = [
        'year' => $year,
        'description' => $description,
        'image' => $image,
    ];
}

$mvv_field = function_exists('get_field') ? (get_field('mission_vision_values') ?: []) : [];
$vision_title = trim($mvv_field['vision_title'] ?? '') ?: $mvv_defaults['vision_title'];
$vision_description_text = trim($mvv_field['vision_description'] ?? '') ?: $mvv_defaults['vision_description'];
$vision_description_html = wp_kses_post(nl2br(esc_html($vision_description_text)));
$mission_title = trim($mvv_field['mission_title'] ?? '') ?: $mvv_defaults['mission_title'];
$mission_description_text = trim($mvv_field['mission_description'] ?? '') ?: $mvv_defaults['mission_description'];
$mission_description_html = wp_kses_post(nl2br(esc_html($mission_description_text)));
$values_title = trim($mvv_field['values_title'] ?? '') ?: $mvv_defaults['values_title'];
$values_rows = !empty($mvv_field['values_items']) && is_array($mvv_field['values_items']) ? $mvv_field['values_items'] : [];
$values_count = max(count($values_rows), count($mvv_defaults['values_items']));
if ($values_count === 0) {
    $values_count = count($mvv_defaults['values_items']);
}
$values_items = [];
for ($i = 0; $i < $values_count; $i++) {
    $fallback = $mvv_defaults['values_items'][$i] ?? [];
    $row = $values_rows[$i] ?? [];
    $title = trim($row['value_title'] ?? '') ?: ($fallback['value_title'] ?? '');
    $bg_default = $fallback['value_image'] ?? [];
    $icon_default = $fallback['value_icon'] ?? [];
    $background = petromin_get_acf_image_data($row['value_image'] ?? null, 'full', $bg_default['url'] ?? '', $bg_default['alt'] ?? $title);
    $icon = petromin_get_acf_image_data($row['value_icon'] ?? null, 'full', $icon_default['url'] ?? '', $icon_default['alt'] ?? $title);

    if (!$title && !$background) {
        continue;
    }

    $values_items[] = [
        'title' => $title,
        'background' => $background,
        'icon' => $icon,
    ];
}

$excellence_field = function_exists('get_field') ? (get_field('excellence_section') ?: []) : [];
$excellence_heading = trim($excellence_field['excellence_heading'] ?? '') ?: 'Excellence in every mile.';
$excellence_rows = !empty($excellence_field['cards']) && is_array($excellence_field['cards']) ? $excellence_field['cards'] : [];
$excellence_count = max(count($excellence_rows), count($excellence_defaults));
if ($excellence_count === 0) {
    $excellence_count = count($excellence_defaults);
}
$excellence_cards = [];
for ($i = 0; $i < $excellence_count; $i++) {
    $fallback = $excellence_defaults[$i] ?? [];
    $row = $excellence_rows[$i] ?? [];
    $title = trim($row['card_title'] ?? '') ?: ($fallback['card_title'] ?? '');
    $description = trim($row['card_description'] ?? '') ?: ($fallback['card_description'] ?? '');
    $image_default = $fallback['card_image'] ?? [];
    $image = petromin_get_acf_image_data($row['card_image'] ?? null, 'full', $image_default['url'] ?? '', $image_default['alt'] ?? $title);

    if (!$title && !$description && !$image) {
        continue;
    }

    $excellence_cards[] = [
        'title' => $title,
        'description_html' => wp_kses_post(nl2br(esc_html($description))),
        'image' => $image,
    ];
}

$wheel_field = function_exists('get_field') ? (get_field('service_wheel') ?: []) : [];
$wheel_heading_text = trim($wheel_field['wheel_heading'] ?? '') ?: $wheel_defaults['heading'];
$wheel_heading_html = wp_kses_post(nl2br(esc_html($wheel_heading_text)));
$wheel_images_field = $wheel_field['wheel_images'] ?? [];
$wheel_desktop_image = petromin_get_acf_image_data($wheel_images_field['desktop_image'] ?? null, 'full', $wheel_defaults['wheel_images']['desktop']['url'], $wheel_defaults['wheel_images']['desktop']['alt']);
$wheel_mobile_image = petromin_get_acf_image_data($wheel_images_field['mobile_image'] ?? null, 'full', $wheel_defaults['wheel_images']['mobile']['url'], $wheel_defaults['wheel_images']['mobile']['alt']);
$wheel_rows = !empty($wheel_field['services']) && is_array($wheel_field['services']) ? $wheel_field['services'] : [];
$wheel_count = max(count($wheel_rows), count($wheel_defaults['services']));
if ($wheel_count === 0) {
    $wheel_count = count($wheel_defaults['services']);
}
$wheel_services = [];
for ($i = 0; $i < $wheel_count; $i++) {
    $fallback = $wheel_defaults['services'][$i] ?? [];
    $row = $wheel_rows[$i] ?? [];
    $title = trim($row['service_title'] ?? '') ?: ($fallback['service_title'] ?? '');
    $description = trim($row['service_description'] ?? '') ?: ($fallback['service_description'] ?? '');

    $wheel_services[] = [
        'title' => $title,
        'description' => $description,
    ];
}

$wheel_desktop_positions = [
    ['class' => 'label sm:!block !hidden absolute xl:top-[-22%] lg:top-[-22%] md:top-[-22%] top-[-30%] left-1/2 -translate-x-1/2 text-center font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[10rem] sm:w-[11rem] md:w-[13rem] leading-snug', 'index' => 0, 'angle' => 0],
    ['class' => 'label sm:!block hidden absolute md:top-[-3%] md:right-[-26%] top-[-12%] right-[-30%] text-center font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[10rem] sm:w-[11rem] md:w-[13rem] leading-snug', 'index' => 2, 'angle' => 45],
    ['class' => 'label sm:!block hidden absolute top-1/2 md:right-[-44%] right-[-48%] -translate-y-1/2 text-center font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[10rem] sm:w-[11rem] md:w-[13rem] leading-snug', 'index' => 4, 'angle' => 90],
    ['class' => 'label sm:!block !hidden absolute bottom-[-8%] md:right-[-22%] right-[-35%] text-center font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[10rem] sm:w-[11rem] md:w-[13rem] leading-snug', 'index' => 6, 'angle' => 135],
    ['class' => 'label  sm:!block hidden absolute md:bottom-[-28%] bottom-[-28%] left-1/2 -translate-x-1/2 text-center font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[10rem] sm:w-[11rem] md:w-[13rem] leading-snug', 'index' => 13, 'angle' => 180],
    ['class' => 'label sm:!block hidden absolute bottom-[-8%] xl:left-[-26%] lg:left-[-26%] md:left-[-26%] left-[-38%] text-center font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[10rem] sm:w-[11rem] md:w-[13rem] leading-snug', 'index' => 14, 'angle' => 225],
    ['class' => 'label sm:!block hidden absolute top-1/2 xl:left-[-38%] lg:left-[-42%] md:left-[-42%] left-[-50%] -translate-y-1/2 text-center font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[10rem] sm:w-[11rem] md:w-[13rem] leading-snug', 'index' => 15, 'angle' => 270],
    ['class' => 'label sm:!block hidden absolute md:top-[-3%] top-[-12%] md:left-[-26%] left-[-37%] text-center font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[10rem] sm:w-[11rem] md:w-[13rem] leading-snug', 'index' => 16, 'angle' => 315],
];

$wheel_mobile_positions = [
    ['class' => 'label sm:!hidden block absolute top-[-32%] left-[56%] text-start font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[11rem] leading-snug', 'index' => 1, 'angle' => 10],
    ['class' => 'label sm:!hidden block absolute top-[-9%] right-[-20%] text-start font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[11rem] leading-snug', 'index' => 3, 'angle' => 35],
    ['class' => 'label sm:!hidden block absolute top-[19%] right-[-40%] -translate-y-1/2 text-start font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[11rem] leading-snug', 'index' => 5, 'angle' => 55],
    ['class' => 'label sm:!hidden block absolute top-[34%] right-[-50%] text-start font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[11rem] leading-snug', 'index' => 7, 'angle' => 78],
    ['class' => 'label sm:!hidden block absolute top-[53%] right-[-50%] text-start font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[11rem] leading-snug', 'index' => 8, 'angle' => 100],
    ['class' => 'label sm:!hidden block absolute top-[72%] right-[-44%] text-start font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[11rem] leading-snug', 'index' => 9, 'angle' => 120],
    ['class' => 'label sm:!hidden block absolute bottom-[-16%] right-[-20%] text-start font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[11rem] leading-snug', 'index' => 10, 'angle' => 145],
    ['class' => 'label sm:!hidden block absolute bottom-[-36%] left-[56%] text-start font-bold text-[#000000] transition-all duration-300 ease-in-out text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl w-[11rem] leading-snug', 'index' => 12, 'angle' => 170],
];

$left_arrow_icon = $images_url . '/left_chev.svg';
$right_arrow_icon = $images_url . '/right_chev.svg';
?>

<div class="heroSection w-full relative z-0 h-dvh ">
    <div class="relative w-full h-full overflow-hidden">
        <?php if (!empty($hero_image_data)) : ?>
        <img fetchpriority="high" loading="eager" decoding="async" src="<?php echo esc_url($hero_image_data['url']); ?>"
            class="size-full object-cover" alt="<?php echo esc_attr($hero_image_data['alt']); ?>"
            title="<?php echo esc_attr($hero_image_title); ?>">
        <?php endif; ?>

        <div
            class="lg:w-[42.688rem] md:w-[34rem] w-[24.125rem] absolute lg:bottom-32 md:bottom-24 bottom-20 left-0 flex py-8 lg:px-8 px-4 bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)]  origin-top -skew-x-[18deg]">
            <div class="view flex items-center justify-center skew-x-[18deg] pr-0">
                <?php if ($hero_title) : ?>
                <h1 class="xl:text-6xl lg:text-5xl md:text-4xl text-3xl text-white font-bold">
                    <?php echo esc_html($hero_title); ?></h1>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<section class="bg-white h-full">
    <div class="w-full flex justify-between">
        <div class="md:w-1/5 w-full md:block hidden relative bg-cover bg-center py-28"
            <?php if (!empty($sidebar_background['url'])) : ?>style="background-image:url('<?php echo esc_url($sidebar_background['url']); ?>');"
            <?php endif; ?>>
            <span class="sticky top-32 ">
                <?php if (!empty($sidebar_car['url'])) : ?>
                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($sidebar_car['url']); ?>"
                    class="w-full object-contain" alt="<?php echo esc_attr($sidebar_car['alt']); ?>">
                <?php endif; ?>
            </span>
        </div>
        <div class="md:w-4/5 w-full h-full pt-28">
            <!-- about sec 1 start -->
            <div class="w-full about_us relative pb-[4.438rem] md:pb-[8.25rem]">
                <div class="view w-full pr-0">
                    <div class="flex justify-between md:items-end flex-wrap gap-y-7">
                        <div class="md:w-1/2 w-full md:pr-12">
                            <div class="w-full flex flex-col md:gap-y-8 gap-y-6">
                                <div class="w-full flex flex-col gap-4 md:gap-6">
                                    <?php if ($intro_heading) : ?>
                                    <h2
                                        class="relative xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-2xl lg:leading-[3.75rem] font-bold text-[#000000] ">
                                        <?php echo esc_html($intro_heading); ?></h2>
                                    <?php endif; ?>
                                    <div
                                        class="bg-gradient-to-l from-[#CB122D]  to-[#650916] w-[7.375rem] w-20 h-3 -skew-x-[18deg]">
                                    </div>
                                </div>
                                <p class="font-normal pt- md:text-base text-sm text-[#000000]">
                                    <?php echo $intro_description_html; ?></p>
                            </div>
                        </div>
                        <div class="md:w-1/2 w-full">
                            <div class="w-full relative">
                                <?php if (!empty($intro_image['url'])) : ?>
                                <img fetchpriority="low" loading="lazy"
                                    src="<?php echo esc_url($intro_image['url']); ?>"
                                    alt="<?php echo esc_attr($intro_image['alt']); ?>"
                                    title="<?php echo esc_attr($intro_image['alt']); ?>" class="size-full">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- about sec 1 end -->
            <!-- Journey  sec 2 start -->

            <div class="w-full relative journey overflow-hidden pb-[7.25rem] md:pb-[11.438rem]">
                <div class="view md:pr-0">
                    <div class="flex items-center justify-between pb-[3.25rem] md:pb-[6.563rem]">
                        <?php if ($journey_heading) : ?>
                        <h2
                            class="text-2xl md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-inter font-bold text-black pr-2">
                            <?php echo esc_html($journey_heading); ?>
                        </h2>
                        <?php endif; ?>
                        <div
                            class="flex items-center justify-start  origin-bottom z-20 bg-[#CB122D] px-4 shadow-[-6px_6px_0px_-1px_rgba(0,0,0,0.9)] w-56 h-16 transition transform -skew-x-12 duration-150 ease-in-out">
                            <div class="swiper-prev cursor-pointer !opacity-100 !pointer-events-auto">
                                <span>
                                    <img fetchpriority="low" loading="lazy"
                                        src="<?php echo esc_url($left_arrow_icon); ?>"
                                        class="text-white size-8 skew-x-12 invert brightness-0" alt="left arrow"
                                        title="left arrow">
                                </span>
                            </div>
                            <div class="swiper-next cursor-pointer">
                                <span>
                                    <img fetchpriority="low" loading="lazy"
                                        src="<?php echo esc_url($right_arrow_icon); ?>"
                                        class="text-white size-8 skew-x-12 invert brightness-0 mb-[0.188] ml-3"
                                        alt="right arrow" title="right arrow">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full relative">
                    <div class="swiper featureSwiper relative z-0 py-10 font-inter">
                        <div class="swiper-wrapper ">
                            <?php foreach ($journey_slides as $slide) : ?>
                            <div class="swiper-slide !h-auto">
                                <div
                                    class="relative flex flex-col w-full h-full bg-gradient-to-l from-[#E5E5E5] to-[#E5E5E5] -skew-x-[18deg] py-10">

                                    <div
                                        class="absolute -top-8 -left-4 lg:py-3 lg:px-10 py-3 px-7 font-bold bg-gradient-to-l from-[#CB122D] to-[#650916] text-white lg:-skew-x-[6deg] skew-x-[6deg]">
                                        <span
                                            class="lg:skew-x-[20deg] skew-x-[10deg] block 2xl:text-[1.5rem] xl:text-2xl lg:text-2xl text-xl 2xl:!leading-[3.063rem]">
                                            <?php echo esc_html($slide['year']); ?>
                                        </span>
                                    </div>

                                    <div class="flex flex-col items-start skew-x-[18deg] w-full">
                                        <div class="md:pl-14 pl-10 max-w-[90%]">
                                            <?php if (!empty($slide['image']['url'])) : ?>
                                            <img fetchpriority="low" loading="lazy"
                                                src="<?php echo esc_url($slide['image']['url']); ?>"
                                                class="lg:h-44 w-auto h-32 object-contain object-center aspect-square"
                                                alt="<?php echo esc_attr($slide['image']['alt']); ?>"
                                                title="<?php echo esc_attr($slide['image']['alt']); ?>">
                                            <?php endif; ?>

                                            <div
                                                class="2xl:text-xl xl:text-xl lg:text-lg text-base font-medium text-black  mx-auto ">
                                                <?php echo esc_html($slide['description']); ?>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Journey  sec 2 end -->
            <!-- missionVission  sec 3 start -->
            <div class="w-full relative bg-gradient-to-r from-[#E5E5E5] to-[#fff] mb-[7.125rem] md:mb-[8.063rem]">
                <div class="view pt-[4.188rem] md:pt-[4.438rem] pb-[3.813rem] md:pb-[5.688rem]">
                    <div class="w-full flex justify-between flex-wrap  gap-y-8">
                        <div class="lg:w-1/3 md:w-1/2 w-full">
                            <div class="w-full relative flex flex-col gap-y-4 md:gap-y-8 md:p-5">
                                <div class="pt-2.5 md:pb-0 pb-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72"
                                        class="lg:size-[4.5rem] md:size-[4.2rem] size-[3.75rem]" viewBox="0 0 72 72"
                                        fill="none">
                                        <g clip-path="url(#clip0_vision)">
                                            <path d="M0 21.1816H6.3457V25.4004H0V21.1816Z" fill="url(#paint0_vision)" />
                                            <path d="M65.6543 21.1816H72V25.4004H65.6543V21.1816Z"
                                                fill="url(#paint1_vision)" />
                                            <path
                                                d="M60.625 39.9453L62.7344 36.2919L68.2298 39.4647L66.1204 43.1182L60.625 39.9453Z"
                                                fill="url(#paint2_vision)" />
                                            <path
                                                d="M3.76953 7.11719L5.87891 3.46375L11.3743 6.6366L9.26491 10.29L3.76953 7.11719Z"
                                                fill="url(#paint3_vision)" />
                                            <path
                                                d="M3.76758 39.4648L9.26296 36.292L11.3723 39.9454L5.87695 43.1183L3.76758 39.4648Z"
                                                fill="url(#paint4_vision)" />
                                            <path
                                                d="M60.625 6.63672L66.1204 3.46387L68.2298 7.1173L62.7344 10.2902L60.625 6.63672Z"
                                                fill="url(#paint5_vision)" />
                                            <path
                                                d="M36.3219 0.00225C36.2121 0.00084375 36.1034 0 35.9937 0C29.8893 0 24.1403 2.31722 19.7749 6.54469C15.3299 10.8495 12.8218 16.6489 12.7125 22.8742C12.6176 28.2952 14.4249 33.6056 17.8012 37.8274C20.9952 41.8212 23.3328 46.1888 24.7678 50.8359H47.2318C48.6684 46.1739 50.9981 41.8164 54.1747 37.8577C57.474 33.7461 59.2912 28.5729 59.2912 23.291C59.2912 17.1259 56.9059 11.3147 52.5748 6.92803C48.2476 2.54531 42.4757 0.0856406 36.3219 0.00225ZM38.5272 43.7002L33.8907 42.8071V33.2128L25.6963 32.5323L24.0291 29.603L33.4729 10.2171L38.1094 11.1102V20.7045L46.3038 21.3848L47.971 24.3145L38.5272 43.7002Z"
                                                fill="url(#paint6_vision)" />
                                            <path
                                                d="M33.8909 18.9922L29.2148 28.591L38.1097 29.3297V34.9244L42.7857 25.3255L33.8909 24.587V18.9922Z"
                                                fill="url(#paint7_vision)" />
                                            <path d="M25.418 55.0547H46.582V59.8359H25.418V55.0547Z"
                                                fill="url(#paint8_vision)" />
                                            <path d="M25.418 66.528L30.89 72H41.11L46.582 66.528V64.0547H25.418V66.528Z"
                                                fill="url(#paint9_vision)" />
                                        </g>
                                        <defs>
                                            <linearGradient id="paint0_vision" x1="6.3457" y1="21.4726" x2="-0.135621"
                                                y2="21.7108" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint1_vision" x1="72" y1="21.4726" x2="65.5187"
                                                y2="21.7108" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint2_vision" x1="63.1134" y1="36.5107" x2="61.0173"
                                                y2="40.2813" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint3_vision" x1="6.25789" y1="3.68257" x2="4.16179"
                                                y2="7.45318" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint4_vision" x1="9.40843" y1="36.544" x2="3.91472"
                                                y2="39.9909" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint5_vision" x1="66.2659" y1="3.71583" x2="60.7721"
                                                y2="7.1628" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint6_vision" x1="59.2912" y1="3.5059" x2="11.673"
                                                y2="4.57219" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint7_vision" x1="42.7857" y1="20.091" x2="28.9121"
                                                y2="20.3797" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint8_vision" x1="46.582" y1="55.3844" x2="25.1868"
                                                y2="57.6988" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint9_vision" x1="46.582" y1="64.6026" x2="25.0278"
                                                y2="66.0057" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <clipPath id="clip0_vision">
                                                <rect width="72" height="72" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="w-full pt- md:pt-0 flex flex-col gap-4 md:gap-[1.563rem]">
                                    <h3
                                        class="relative xl:text-[2.5rem] lg:-[2.3rem] md:text-[2rem] text-2xl lg:leading-[3.75rem] font-bold text-[#000000] ">
                                        <?php echo esc_html($vision_title); ?></h3>
                                    <div
                                        class="bg-gradient-to-l from-[#650916]  to-[#650916] w-[7.375rem] w-20 h-3 -skew-x-[18deg]">
                                    </div>
                                </div>
                                <p class="md:text-base text-sm text-[#000000] font-normal ">
                                    <?php echo $vision_description_html; ?>
                                </p>
                            </div>
                        </div>
                        <div class="lg:w-1/3 md:w-1/2 w-full flex md:justify-end">
                            <div class="w-full relative flex flex-col gap-y-8 md:p-5">
                                <div class="pt-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72"
                                        class="lg:size-[4.5rem] md:size-[4.2rem] size-[3.75rem]" viewBox="0 0 72 72"
                                        fill="none">
                                        <path
                                            d="M36 19.7031C27.0139 19.7031 19.7031 27.0138 19.7031 36C19.7031 44.9862 27.0139 52.2969 36 52.2969C44.9861 52.2969 52.2969 44.9861 52.2969 36C52.2969 32.266 51.0337 28.8221 48.9135 26.0709L45.3475 29.6369C46.5864 31.4511 47.3125 33.6422 47.3125 36C47.3125 42.2377 42.2377 47.3125 36 47.3125C29.7623 47.3125 24.6876 42.2377 24.6876 36C24.6876 29.7623 29.7623 24.6876 36 24.6876C38.3586 24.6876 40.5501 25.414 42.3647 26.6537L45.9305 23.0878C43.1792 20.9668 39.7346 19.7031 36 19.7031Z"
                                            fill="url(#paint0_mission)" />
                                        <path
                                            d="M10.5 36C10.5 50.0608 21.9393 61.4999 36 61.4999C50.0606 61.4999 61.4999 50.0608 61.4999 36C61.4999 31.4153 60.2831 27.1098 58.1564 23.3883L53.7044 21.2799L51.9149 23.0693C54.7894 26.6006 56.5156 31.1023 56.5156 36C56.5156 47.3123 47.3124 56.5156 36 56.5156C24.6875 56.5156 15.4843 47.3123 15.4843 36C15.4843 24.6877 24.6877 15.4843 36 15.4843C40.8983 15.4843 45.4005 17.2108 48.932 20.0861L50.7213 18.2968L48.6136 13.8465C44.8918 11.7182 40.5859 10.5 36 10.5C21.9391 10.5 10.5 21.9391 10.5 36Z"
                                            fill="url(#paint1_mission)" />
                                        <path
                                            d="M34.5096 34.5085L39.2965 29.7216C38.3108 29.202 37.1895 28.9062 35.9999 28.9062C32.0885 28.9062 28.9062 32.0885 28.9062 35.9999C28.9062 39.9114 32.0885 43.0936 35.9999 43.0936C39.9114 43.0936 43.0936 39.9114 43.0936 35.9999C43.0936 34.8111 42.7982 33.6903 42.2791 32.7051L37.4927 37.4915L34.5096 34.5085Z"
                                            fill="url(#paint2_mission)" />
                                        <path
                                            d="M62.8054 9.19546L60.7548 4.86548L52.7969 12.8234L54.8475 17.1533L59.1775 19.204L67.1354 11.2461L62.8054 9.19546Z"
                                            fill="url(#paint3_mission)" />
                                        <path
                                            d="M62.2562 22.0918C64.465 26.2439 65.7188 30.9779 65.7188 36C65.7188 52.387 52.387 65.7187 36.0001 65.7187C19.6133 65.7187 6.2813 52.387 6.2813 36C6.2813 19.613 19.613 6.2813 36 6.2813C41.0227 6.2813 45.7573 7.53553 49.9098 9.74489L54.5313 5.12339C48.9922 1.78495 42.6338 0 36 0C26.3841 0 17.3437 3.7447 10.5441 10.5441C3.7447 17.3437 0 26.3841 0 36C0 45.6159 3.7447 54.6563 10.5441 61.4559C17.3437 68.2553 26.3841 72 36 72C45.6159 72 54.6563 68.2553 61.4559 61.4559C68.2553 54.6563 72 45.6159 72 36C72 29.3669 70.2153 23.0091 66.8776 17.4704L62.2562 22.0918Z"
                                            fill="url(#paint4_mission)" />
                                        <defs>
                                            <linearGradient id="paint0_mission" x1="52.2969" y1="21.951" x2="18.9814"
                                                y2="22.7651" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint1_mission" x1="61.4999" y1="14.0172" x2="9.37077"
                                                y2="15.2911" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint2_mission" x1="43.0936" y1="29.8847" x2="28.5921"
                                                y2="30.2391" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint3_mission" x1="67.1354" y1="5.85434" x2="52.4794"
                                                y2="6.21249" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                            <linearGradient id="paint4_mission" x1="72" y1="4.96547" x2="-1.5942"
                                                y2="6.76393" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#CB122D" />
                                                <stop offset="1" stop-color="#650916" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="w-full flex flex-col gap-[1.563rem]">
                                    <h3
                                        class="relative xl:text-[2.5rem] lg:-[2.3rem] md:text-[2rem] text-2xl lg:leading-[3.75rem] font-bold text-[#000000] ">
                                        <?php echo esc_html($mission_title); ?></h3>
                                    <div
                                        class="bg-gradient-to-l from-[#650916]  to-[#650916] w-[7.375rem] w-20 h-3 -skew-x-[18deg]">
                                    </div>
                                </div>
                                <p class="md:text-base text-sm text-[#000000] font-normal ">
                                    <?php echo $mission_description_html; ?>
                                </p>
                            </div>
                        </div>
                        <div class="w-full relative flex flex-col  gap-y-4 md:gap-y-8 md:p-5">
                            <div class="pt-2.5 md:pb-0 pb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72"
                                    class="lg:size-[4.5rem] md:size-[4.2rem] size-[3.75rem]" viewBox="0 0 72 72"
                                    fill="none">
                                    <g clip-path="url(#clip0_values)">
                                        <path d="M50.2819 39.7539L39.7812 71.8511L71.9993 39.7539H50.2819Z"
                                            fill="url(#paint0_values)" />
                                        <path d="M27.4805 35.3246H44.5583L36.0867 22.9131L27.4805 35.3246Z"
                                            fill="url(#paint1_values)" />
                                        <path d="M33.4051 19.0059H14.8242L23.471 33.3323L33.4051 19.0059Z"
                                            fill="url(#paint2_values)" />
                                        <path d="M11.3636 21.8428L0.355469 35.3249H19.5007L11.3636 21.8428Z"
                                            fill="url(#paint3_values)" />
                                        <path d="M0 39.7539L32.3674 71.9999L21.8181 39.7539H0Z"
                                            fill="url(#paint4_values)" />
                                        <path d="M36.0502 69.0118L45.6218 39.7539H26.4785L36.0502 69.0118Z"
                                            fill="url(#paint5_values)" />
                                        <path d="M57.1808 19.0059H38.7812L48.5875 33.373L57.1808 19.0059Z"
                                            fill="url(#paint6_values)" />
                                        <path d="M71.6449 35.3253L60.6421 21.8496L52.582 35.3253H71.6449Z"
                                            fill="url(#paint7_values)" />
                                        <path d="M33.8926 0H38.1085V5.37033H33.8926V0Z" fill="url(#paint8_values)" />
                                        <path
                                            d="M19.2983 8.41309L22.9649 10.5316L20.2828 15.1722L16.6162 13.0536L19.2983 8.41309Z"
                                            fill="url(#paint9_values)" />
                                        <path
                                            d="M52.8164 8.41309L55.4985 13.0536L51.8319 15.1722L49.1498 10.5316L52.8164 8.41309Z"
                                            fill="url(#paint10_values)" />
                                    </g>
                                    <defs>
                                        <linearGradient id="paint0_values" x1="64.8443" y1="45.8483" x2="36.8938"
                                            y2="69.1158" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint1_values" x1="41.6301" y1="33.5338" x2="28.0663"
                                            y2="24.6987" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint2_values" x1="31.0252" y1="30.3087" x2="17.4614"
                                            y2="21.4736" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint3_values" x1="15.2815" y1="32.9597" x2="1.71769"
                                            y2="24.1246" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint4_values" x1="26.5011" y1="62.7618" x2="6.39693"
                                            y2="42.6577" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint5_values" x1="42.4355" y1="58.8562" x2="28.8717"
                                            y2="50.0211" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint6_values" x1="53.6552" y1="31.4086" x2="40.0914"
                                            y2="22.5735" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint7_values" x1="65.8106" y1="32.3664" x2="52.2468"
                                            y2="23.5313" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint8_values" x1="36.0005" y1="4.20018" x2="35.9997"
                                            y2="1.54861" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint9_values" x1="20.7147" y1="13.5658" x2="18.1269"
                                            y2="10.0805" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <linearGradient id="paint10_values" x1="52.1317" y1="10.0805" x2="49.5439"
                                            y2="13.5658" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D" />
                                            <stop offset="1" stop-color="#650916" />
                                        </linearGradient>
                                        <clipPath id="clip0_values">
                                            <rect width="72" height="72" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="w-full flex flex-col gap-4 md:gap-[1.563rem]">
                                <h3
                                    class="relative xl:text-[2.5rem] lg:-[2.3rem] md:text-[2rem] text-2xl lg:leading-[3.75rem] font-bold text-[#000000] ">
                                    <?php echo esc_html($values_title); ?></h3>
                                <div
                                    class="bg-gradient-to-l from-[#650916]  to-[#650916] w-[7.375rem] w-20 h-3 -skew-x-[18deg]">
                                </div>
                            </div>
                            <div
                                class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-2 pt-5 md:pt-0.5 gap-x-4 gap-y-[1.563rem] md:gap-[1.313rem]">
                                <?php foreach ($values_items as $value_item) :
                                        $background = $value_item['background'] ?? null;
                                        $icon = $value_item['icon'] ?? null;
                                        $title = $value_item['title'] ?? '';
                                        if (!$background) {
                                            continue;
                                        }
                                        ?>
                                <div class="w-full relative overflow-hidden h-full group duration-300">
                                    <img fetchpriority="low" loading="lazy"
                                        src="<?php echo esc_url($background['url']); ?>"
                                        alt="<?php echo esc_attr($background['alt']); ?>"
                                        title="<?php echo esc_attr($background['alt']); ?>"
                                        class="size-full object-cover">
                                    <div
                                        class="absolute bottom-0 left-0 w-full h-1/4 bg-gradient-to-t from-[#CB122D] to-[#CB122D00]">
                                    </div>

                                    <div class="absolute left-0 bottom-10 pr-3 duration-300 group-hover:lg:bottom-12">
                                        <h4
                                            class="text-white lg:text-sm md:text-sm text-xs font-bold px-5 h-10 md:w-[13.800rem] w-[10.063rem] flex justify-start items-center bg-gradient-to-l from-[#CB122D] to-[#650916] group-hover:lg:from-[#000] group-hover:lg:to-[#000] duration-300 origin-top -skew-x-[16deg] ">
                                            <div class="skew-x-[16deg] flex items-center gap-1">
                                                <?php if (!empty($icon['url'])) : ?>
                                                <span>
                                                    <img src="<?php echo esc_url($icon['url']); ?>"
                                                        alt="<?php echo esc_attr($icon['alt'] ?: $title); ?>"
                                                        class="h-4 w-4 object-contain">
                                                </span>
                                                <?php endif; ?>
                                                <?php echo esc_html($title); ?>
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- missionVission  sec 3 end -->
            <!-- excellence  sec 4 start -->
            <div class="w-full relative bg-white pb-[6.063rem]">
                <div class="view">
                    <div class="flex flex-col pb-9 md:pb-14">
                        <?php if ($excellence_heading) : ?>
                        <h2
                            class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-2xl lg:leading-[3.75rem] font-bold text-black ">
                            <?php echo esc_html($excellence_heading); ?>
                        </h2>
                        <?php endif; ?>
                    </div>
                    <div class="w-full relative">
                        <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4 md:gap-7">
                            <?php foreach ($excellence_cards as $card) :
                                    $card_image = $card['image'] ?? null;
                                    ?>
                            <div
                                class="w-full relative overflow-hidden group duration-500 md:h-[26.063rem] h-full before:absolute before:inset-x-0 before:bottom-0 before:bg-gradient-to-t before:from-[#891d1deb] before:via-[#dd161ec7] before:to-[#ef604508] before:w-full before:h-1/2 before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100">
                                <?php if (!empty($card_image['url'])) : ?>
                                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($card_image['url']); ?>"
                                    alt="<?php echo esc_attr($card_image['alt']); ?>"
                                    title="<?php echo esc_attr($card_image['alt']); ?>" class="size-full object-cover">
                                <?php endif; ?>
                                <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500">
                                    <div
                                        class="w-max pr-3 duration-500 lg:translate-y-12 group-hover:lg:-translate-y-2">
                                        <h4
                                            class="text-white lg:text-xl md:text-lg text-base font-bold md:px-5 px-6 pr-2 h-12 md:w-[19.188rem] w-[14.438rem] flex justify-start items-center lg:bg-gradient-to-l lg:from-[#CB122D] lg:to-[#650916] group-hover:lg:from-[#000] group-hover:lg:to-[#000] bg-[#000000] duration-300 origin-top -skew-x-[16deg] ">
                                            <div class="skew-x-[16deg] ">
                                                <?php echo esc_html($card['title']); ?>
                                            </div>

                                        </h4>
                                    </div>
                                    <div
                                        class="lg:opacity-0 group-hover:lg:opacity-100 relative lg:px-8 px-6 pt-1 lg:pb-12 pb-8 duration-500 ">
                                        <p
                                            class=" text-white md:text-base text-sm duration-500 drop-shadow-[3px_3px_10px_rgba(0,0,0,0.9)]">
                                            <?php echo $card['description_html']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
            <!-- excellence  sec 4 end -->
            <!-- circle hover sec 5 start -->
            <div class="w-full">
                <div class="sm:hidden view flex-col flex pb-6">
                    <h2
                        class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-2xl lg:leading-[3.75rem] font-bold text-black ">
                        <?php echo $wheel_heading_html; ?>
                    </h2>
                </div>
                <div class="w-full md:pt-36 md:pb-52 pt-40 pb-60 bg-white overflow-hidden">

                    <div id="wheelContainer"
                        class="relative sm:mx-auto sm:translate-x-0 translate-x-[-50%] sm:w-[22rem] md:w-[26rem] lg:w-[30rem] xl:w-[35rem] aspect-square flex items-center justify-center z-30">
                        <!-- Circle Image -->
                        <div class="relative w-full h-full sm:block hidden">
                            <?php if (!empty($wheel_desktop_image['url'])) : ?>
                            <img src="<?php echo esc_url($wheel_desktop_image['url']); ?>"
                                alt="<?php echo esc_attr($wheel_desktop_image['alt']); ?>"
                                class="w-full h-full rounded-full object-contain" />
                            <?php endif; ?>
                        </div>
                        <div class="relative w-full h-full sm:hidden block">
                            <?php if (!empty($wheel_mobile_image['url'])) : ?>
                            <img src="<?php echo esc_url($wheel_mobile_image['url']); ?>"
                                alt="<?php echo esc_attr($wheel_mobile_image['alt']); ?>"
                                class="w-full h-full rounded-full object-cover" />
                            <?php endif; ?>
                        </div>

                        <!-- Orange Arrow -->
                        <div id="arrow"
                            class="pointer-events-none absolute w-0 h-0 border-l-[1rem] sm:border-l-[1.2rem] md:border-l-[1.5rem] border-r-[1rem] sm:border-r-[1.2rem] md:border-r-[1.5rem] border-b-[1.2rem] sm:border-b-[1.5rem] md:border-b-[1.8rem] border-l-transparent border-r-transparent border-b-[#f57c00] left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 transition-transform duration-500 ease-out -z-10"
                            style="transform-origin: 50% 50%; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                        </div>

                        <!-- Labels -->
                        <div id="labels" class="absolute inset-0 flex items-center justify-center pointer-events-auto">
                            <?php foreach ($wheel_desktop_positions as $index => $position) :
                                    $service = $wheel_services[$index] ?? null;
                                    if (empty($service['title']) && empty($service['description'])) {
                                        continue;
                                    }
                                    ?>
                            <button data-index="<?php echo esc_attr($position['index']); ?>"
                                data-angle="<?php echo esc_attr($position['angle']); ?>"
                                class="<?php echo esc_attr($position['class']); ?>">
                                <?php echo esc_html($service['title']); ?>
                                <?php if (!empty($service['description'])) : ?>
                                <span
                                    class="subtext block text-[#5F5F5F] font-normal text-[0.75rem] sm:text-[0.875rem] leading-[1.2rem] mt-1 opacity-0 translate-y-3 transition-all duration-300 ease-in-out">
                                    <?php echo esc_html($service['description']); ?>
                                </span>
                                <?php endif; ?>
                            </button>
                            <?php endforeach; ?>

                            <?php foreach ($wheel_mobile_positions as $index => $position) :
                                    $service = $wheel_services[$index] ?? null;
                                    if (empty($service['title']) && empty($service['description'])) {
                                        continue;
                                    }
                                    ?>
                            <button data-index="<?php echo esc_attr($position['index']); ?>"
                                data-angle="<?php echo esc_attr($position['angle']); ?>"
                                class="<?php echo esc_attr($position['class']); ?>">
                                <?php echo esc_html($service['title']); ?>
                                <?php if (!empty($service['description'])) : ?>
                                <span
                                    class="subtext block text-[#5F5F5F] font-normal text-[0.75rem] sm:text-[0.875rem] leading-[1.2rem] mt-1 !opacity-100 !translate-y-0 transition-all duration-300 ease-in-out">
                                    <?php echo esc_html($service['description']); ?>
                                </span>
                                <?php endif; ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- circle hover sec 5 end -->

        </div>
    </div>

</section>

<?php get_footer(); ?>