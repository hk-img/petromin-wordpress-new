<?php
$footer_brand = function_exists('get_field') ? (get_field('footer_brand', 'option') ?: []) : [];
$footer_highlight = function_exists('get_field') ? (get_field('footer_highlight_box', 'option') ?: []) : [];
$footer_head_office = function_exists('get_field') ? (get_field('footer_head_office', 'option') ?: []) : [];
$footer_quick_links_raw = function_exists('get_field') ? (get_field('footer_quick_links', 'option') ?: []) : [];
$footer_other_links_raw = function_exists('get_field') ? (get_field('footer_other_links', 'option') ?: []) : [];
$footer_store_badges_raw = function_exists('get_field') ? (get_field('footer_store_badges', 'option') ?: []) : [];
$footer_social_links_raw = function_exists('get_field') ? (get_field('footer_social_links', 'option') ?: []) : [];
$footer_copyright = function_exists('get_field') ? (get_field('footer_copyright', 'option') ?: '') : '';

$default_footer_description = 'Petromin Express is India’s leading multibrand car service and repair network. In partnership with HPCL, we deliver a standardised, tech-enabled service experience across fully owned garages.';

$footer_logo_data = petromin_get_acf_image_data(
    $footer_brand['logo'] ?? null,
    'full',
    get_template_directory_uri() . '/assets/img/image-38.webp',
    'Petromin Express Logo'
);

$footer_description = trim((string) ($footer_brand['description'] ?? ''));
if ($footer_description === '') {
    $footer_description = $default_footer_description;
}

$default_info_columns = [
    [
        'title' => 'Station Hours',
        'lines' => [
            ['line_text' => '8 AM to 8 PM'],
            ['line_text' => 'Monday to Saturday'],
            ['line_text' => 'Open: 3rd & 4th Sunday'],
            ['line_text' => 'Closed: 1st & 2nd Sunday'],
        ],
    ],
    [
        'title' => 'Call Center',
        'lines' => [
            ['line_text' => '6 AM to 11 PM'],
            ['line_text' => 'Monday to Sunday'],
        ],
    ],
];

$info_columns_raw = $footer_highlight['info_columns'] ?? [];
$normalized_info_columns = [];

if (is_array($info_columns_raw)) {
    foreach ($info_columns_raw as $column) {
        if (!is_array($column)) {
            continue;
        }

        $column_title = trim((string) ($column['title'] ?? ''));
        $lines_raw = $column['lines'] ?? [];
        $lines = [];

        if (is_array($lines_raw)) {
            foreach ($lines_raw as $line) {
                $line_text = '';
                if (is_array($line)) {
                    $line_text = $line['line_text'] ?? '';
                } else {
                    $line_text = $line;
                }

                $line_text = trim((string) $line_text);

                if ($line_text === '') {
                    continue;
                }

                $lines[] = $line_text;
            }
        }

        if ($column_title === '' && empty($lines)) {
            continue;
        }

        $normalized_info_columns[] = [
            'title' => $column_title,
            'lines' => $lines,
        ];
    }
}

if (empty($normalized_info_columns)) {
    foreach ($default_info_columns as $default_column) {
        $lines = [];
        foreach ($default_column['lines'] as $line) {
            $lines[] = $line['line_text'];
        }
        $normalized_info_columns[] = [
            'title' => $default_column['title'],
            'lines' => $lines,
        ];
    }
}

$contact_phone = trim((string) ($footer_highlight['contact_phone'] ?? ''));
if ($contact_phone === '') {
    $contact_phone = '+91 86686 92000';
}

$contact_email = trim((string) ($footer_highlight['contact_email'] ?? ''));
if ($contact_email === '') {
    $contact_email = 'customercare.pe@petromin.in';
}

$head_office_title = trim((string) ($footer_head_office['title'] ?? ''));
if ($head_office_title === '') {
    $head_office_title = 'Head Office';
}

$head_office_address = trim((string) ($footer_head_office['address'] ?? ''));
if ($head_office_address === '') {
    $head_office_address = "Sai Brindhavan, Plot No. 40C, Door 1, 3rd Main Road
Kottur Gardens, Kotturpuram, Chennai, Tamil Nadu - 600085";
}

// COLUMN 1: Services (Dynamic from CPT)
$service_posts_footer = new WP_Query([
    'post_type' => 'service',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

$services_links = [];
if (!empty($service_posts_footer->posts)) {
    foreach ($service_posts_footer->posts as $service) {
        $services_links[] = [
            'text' => get_the_title($service->ID),
            'url' => get_permalink($service->ID),
            'target' => '_self',
        ];
    }
}

// COLUMN 1: Offers (Dynamic from CPT)
$offer_posts_footer = new WP_Query([
    'post_type' => 'offer',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

$offers_links = [];
if (!empty($offer_posts_footer->posts)) {
    foreach ($offer_posts_footer->posts as $offer) {
        $offers_links[] = [
            'text' => get_the_title($offer->ID),
            'url' => get_permalink($offer->ID),
            'target' => '_self',
        ];
    }
}
wp_reset_postdata();

// COLUMN 1: Quick Links (from CMS)
$quick_links = [];
if (is_array($footer_quick_links_raw)) {
    foreach ($footer_quick_links_raw as $link) {
        if (!is_array($link)) {
            continue;
        }
        $link_text = trim((string) ($link['link_text'] ?? ''));
        if ($link_text === '') {
            continue;
        }
        $quick_links[] = [
            'text' => $link_text,
            'url' => petromin_normalize_link($link['link_url'] ?? '', '#'),
            'target' => !empty($link['open_in_new_tab']) ? '_blank' : '_self',
        ];
    }
}

// COLUMN 2: Other Links (from CMS)
$other_links = [];
if (is_array($footer_other_links_raw)) {
    foreach ($footer_other_links_raw as $link) {
        if (!is_array($link)) {
            continue;
        }
        $link_text = trim((string) ($link['link_text'] ?? ''));
        if ($link_text === '') {
            continue;
        }
        $other_links[] = [
            'text' => $link_text,
            'url' => petromin_normalize_link($link['link_url'] ?? '', '#'),
            'target' => !empty($link['open_in_new_tab']) ? '_blank' : '_self',
        ];
    }
}

// Build normalized footer columns
$normalized_footer_columns = [
    [
        'column_title' => 'Services',
        'primary_links' => $services_links,
        'secondary_links' => $quick_links,
    ],
    [
        'column_title' => 'Latest Offers',
        'primary_links' => $offers_links,
        'secondary_links' => $other_links,
    ],
];

$default_store_badges = [
    [
        'badge_link' => '#',
        'badge_image' => [
            'url' => get_template_directory_uri() . '/assets/img/assets1.webp',
            'alt' => 'Google Play',
        ],
    ],
    [
        'badge_link' => '#',
        'badge_image' => [
            'url' => get_template_directory_uri() . '/assets/img/app_store_btn.webp',
            'alt' => 'App Store',
        ],
    ],
];

$store_badges_source = !empty($footer_store_badges_raw) && is_array($footer_store_badges_raw) ? $footer_store_badges_raw : $default_store_badges;
$normalized_store_badges = [];

foreach ($store_badges_source as $badge) {
    if (!is_array($badge)) {
        continue;
    }

    $badge_image_field = $badge['badge_image'] ?? null;
    $fallback_url = '';
    $fallback_alt = '';

    if (is_array($badge_image_field) && isset($badge_image_field['url'])) {
        $fallback_url = $badge_image_field['url'];
        $fallback_alt = $badge_image_field['alt'] ?? '';
    }

    $badge_image_data = petromin_get_acf_image_data($badge_image_field, 'full', $fallback_url, $fallback_alt);

    if (!$badge_image_data) {
        continue;
    }

    $badge_link = petromin_normalize_link($badge['badge_link'] ?? '', '#');

    $normalized_store_badges[] = [
        'link' => $badge_link,
        'image' => $badge_image_data,
    ];
}

$default_social_links = [
    ['platform' => 'x', 'url' => '#', 'open_in_new_tab' => true],
    ['platform' => 'linkedin', 'url' => '#', 'open_in_new_tab' => true],
    ['platform' => 'facebook', 'url' => '#', 'open_in_new_tab' => true],
    ['platform' => 'instagram', 'url' => '#', 'open_in_new_tab' => true],
    ['platform' => 'youtube', 'url' => '#', 'open_in_new_tab' => true],
];

$social_links_source = !empty($footer_social_links_raw) && is_array($footer_social_links_raw) ? $footer_social_links_raw : $default_social_links;
$normalized_social_links = [];

foreach ($social_links_source as $social_link) {
    if (!is_array($social_link)) {
        continue;
    }

    $platform = strtolower(trim((string) ($social_link['platform'] ?? '')));
    $icon_svg = petromin_get_social_icon_svg($platform);

    if ($icon_svg === '') {
        continue;
    }

    $url = petromin_normalize_link($social_link['url'] ?? '', '#');
    $target = !empty($social_link['open_in_new_tab']) ? '_blank' : '_self';

    $normalized_social_links[] = [
        'platform' => $platform,
        'icon' => $icon_svg,
        'url' => $url,
        'target' => $target,
    ];
}

if (empty($normalized_social_links)) {
    foreach ($default_social_links as $social_link) {
        $platform = $social_link['platform'];
        $icon_svg = petromin_get_social_icon_svg($platform);

        if ($icon_svg === '') {
            continue;
        }

        $normalized_social_links[] = [
            'platform' => $platform,
            'icon' => $icon_svg,
            'url' => petromin_normalize_link($social_link['url'], '#'),
            'target' => !empty($social_link['open_in_new_tab']) ? '_blank' : '_self',
        ];
    }
}

if ($footer_copyright === '') {
    $footer_copyright = '© 2025, Automini Car Services Pvt. Ltd.';
}

$contact_phone_href = '#';
if ($contact_phone !== '') {
    $phone_digits = preg_replace('/[^0-9+]/', '', $contact_phone);
    if (!empty($phone_digits)) {
        $contact_phone_href = 'tel:' . $phone_digits;
    }
}

$contact_email_href = '#';
if ($contact_email !== '') {
    $contact_email_href = stripos($contact_email, 'mailto:') === 0 ? $contact_email : 'mailto:' . $contact_email;
}

$arrow_icon_url = esc_url(get_template_directory_uri() . '/assets/img/fi_19024510.webp');

// Check if this is verify page template - don't render footer UI
$is_verify_page = is_page_template('verify.php');
$is_workstation_page = is_page_template('workstation.php');
$is_slot_page = is_page_template('slot.php');
$is_cost_estimator_page = is_page_template('cost-estimator.php');

// Get theme assets directory URL - needed for JavaScript even on verify page
$assets_img_url = get_template_directory_uri() . '/assets/img/';

// Get cost-estimator page URL - needed for JavaScript even on verify page
$cost_estimator_url = '';
$cost_estimator_pages = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'cost-estimator.php'
));
if (!empty($cost_estimator_pages)) {
    $cost_estimator_url = get_permalink($cost_estimator_pages[0]->ID);
} else {
    // Fallback: try to find by slug
    $cost_estimator_page = get_page_by_path('cost-estimator');
    if ($cost_estimator_page) {
        $cost_estimator_url = get_permalink($cost_estimator_page->ID);
    }
}
?>
<?php if (!$is_verify_page && !$is_workstation_page && !$is_slot_page) : ?>
<footer class="w-full bg-black relative text-white font-inter lg:py-8 py-4 z-10 overflow-x-hidden">
    <div class="py-12 relative view !pl-0">
        <div class="flex flex-col md:flex-row gap-12">
            <div class="md:w-3/5 w-full flex flex-col gap-8">
                <div class="flex flex-col items-start gap-5 lg:pl-[5rem] md:pl-[4rem] pl-[1rem]">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php if (!empty($footer_logo_data)) : ?>
                        <img src="<?php echo esc_url($footer_logo_data['url']); ?>"
                            alt="<?php echo esc_attr($footer_logo_data['alt']); ?>"
                            title="<?php echo esc_attr($footer_logo_data['alt']); ?>" class="w-auto h-16 object-contain"
                            loading="lazy" fetchpriority="low">
                        <?php endif; ?>
                    </a>
                    <p class="text-base text-white font-normal leading-relaxed max-w-md">
                        <?php echo nl2br(esc_html($footer_description)); ?>
                    </p>
                </div>

                <div
                    class="w-full lg:pl-[5rem] md:pl-[4rem] pl-[1rem] md:p-12 p-8 flex flex-col bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)] origin-top -skew-x-[18deg]">
                    <div class="flex items-center skew-x-[18deg] lg:pl-[3.5rem] md:pl-[3rem] pl-[4rem]">
                        <div class="flex flex-col justify-center gap-8 text-white">
                            <div class="flex md:flex-row flex-col md:gap-28 gap-y-6">
                                <?php foreach ($normalized_info_columns as $column) : ?>
                                <div>
                                    <?php if (!empty($column['title'])) : ?>
                                    <h3 class="font-medium md:text-base text-sm mb-2">
                                        <?php echo esc_html($column['title']); ?>
                                    </h3>
                                    <?php endif; ?>
                                    <?php if (!empty($column['lines'])) : ?>
                                    <div class="lg:text-xl text-base flex flex-col gap-y-1">
                                        <?php foreach ($column['lines'] as $line_index => $line_text) : ?>
                                        <span
                                            class="block <?php echo $line_index < 2 ? 'md:font-bold font-semibold' : ''; ?>">
                                            <?php echo esc_html($line_text); ?>
                                        </span>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div
                                class="mt-3 flex md:flex-row flex-col gap-1 2xl:text-xl lg:text-xl text-base font-bold">
                                <?php if ($contact_phone !== '') : ?>
                                <a href="<?php echo esc_url($contact_phone_href); ?>">
                                    <?php echo esc_html($contact_phone); ?>
                                </a>
                                <?php endif; ?>
                                <?php if ($contact_phone !== '' && $contact_email !== '') : ?>
                                <span class="md:inline hidden">|</span>
                                <?php endif; ?>
                                <?php if ($contact_email !== '') : ?>
                                <a href="<?php echo esc_url($contact_email_href); ?>"
                                    class="2xl:text-xl lg:text-xl text-sm underline hover:text-gray-200 md:pl-1">
                                    <?php echo esc_html($contact_email); ?>
                                </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>

                <?php if (!empty($head_office_address)) : ?>
                <div class="block md:hidden lg:pl-[5rem] md:pl-[4rem] pl-[1rem]">
                    <h3 class="font-semibold lg:mt-12 md:mt-6 mt-2 mb-1">
                        <?php echo esc_html($head_office_title); ?>
                    </h3>
                    <p class="text-sm">
                        <?php echo nl2br(esc_html($head_office_address)); ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <div class="md:w-2/5 w-full md:mt-20">
                <div class="flex">
                    <?php foreach ($normalized_footer_columns as $index => $column) : ?>
                    <div class="md:w-1/2 w-full <?php echo $index === 0 ? 'md:pl-0 pl-[1rem]' : ''; ?>">
                        <?php if (!empty($column['column_title'])) : ?>
                        <h3 class="font-semibold mb-2 text-lg">
                            <?php echo esc_html($column['column_title']); ?>
                        </h3>
                        <?php endif; ?>
                        <?php if (!empty($column['primary_links'])) : ?>
                        <ul class="flex flex-col md:text-base text-sm space-y-1 mb-4">
                            <?php foreach ($column['primary_links'] as $link) : ?>
                            <li>
                                <a href="<?php echo esc_url($link['url']); ?>" class="hover:text-gray-300 duration-300"
                                    target="<?php echo esc_attr($link['target']); ?>"
                                    <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                                    <?php echo esc_html($link['text']); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <?php if (!empty($column['secondary_links'])) : ?>
                        <div class="w-full flex">
                            <ul class="flex flex-col md:text-base text-sm gap-y-5">
                                <?php foreach ($column['secondary_links'] as $link) : ?>
                                <li class="font-semibold">
                                    <a href="<?php echo esc_url($link['url']); ?>"
                                        class="hover:text-gray-300 duration-300"
                                        target="<?php echo esc_attr($link['target']); ?>"
                                        <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                                        <?php echo esc_html($link['text']); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div
            class="flex md:items-end items-start md:flex-row flex-col justify-between pt-8 lg:pl-[5rem] md:pl-[4rem] pl-[1rem]">
            <div>
                <?php if (!empty($head_office_address)) : ?>
                <div class="md:block hidden">
                    <h3 class="font-semibold mb-1">
                        <?php echo esc_html($head_office_title); ?>
                    </h3>
                    <p class="text-sm">
                        <?php echo nl2br(esc_html($head_office_address)); ?>
                    </p>
                </div>
                <?php endif; ?>
                <div class="flex md:flex-row flex-col md:items-center items-start mt-10 gap-8 ">
                    <?php if (!empty($normalized_store_badges)) : ?>
                    <div class="flex items-center gap-3">
                        <?php foreach ($normalized_store_badges as $badge) : ?>
                        <a href="<?php echo esc_url($badge['link']); ?>" class="hover:scale-105 duration-300"
                            target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo esc_url($badge['image']['url']); ?>"
                                alt="<?php echo esc_attr($badge['image']['alt']); ?>"
                                title="<?php echo esc_attr($badge['image']['alt']); ?>"
                                class="w-auto h-10 object-contain" loading="lazy" fetchpriority="low">
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($normalized_social_links)) : ?>
                    <div class="flex gap-4 text-white social_links">
                        <?php foreach ($normalized_social_links as $social_link) : ?>
                        <a href="<?php echo esc_url($social_link['url']); ?>" class="hover:scale-75 duration-300"
                            title="<?php echo esc_attr(ucfirst($social_link['platform'])); ?>"
                            target="<?php echo esc_attr($social_link['target']); ?>"
                            <?php echo $social_link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                            <?php echo $social_link['icon']; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div
                class="lg:text-center lg:text-base text-sm lg:font-normal text-start font-bold text-white lg:py-4 pt-16">
                <?php echo esc_html($footer_copyright); ?>
            </div>
        </div>

    </div>
</footer>
<?php if (!$is_cost_estimator_page) : ?>
<!-- mobile button -->
<div id="mobileToggle" class="fixed right-0 top-1/2 -translate-y-1/2 z-30 lg:hidden">
    <button type="button"
        class="bg-[#650916] text-white text-xs md:text-sm tracking-wider uppercase italic font-medium font-inter px-2 py-3 tracking-wide transform -rotate-180  transition-all duration-300 flex items-center justify-center"
        style="writing-mode: vertical-rl; text-orientation: mixed;">
        GET INSTANT CAR SERVICE QUOTE
        <svg class="size-4 ms-2 rotate-90" stroke="currentColor" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M13.0001 7.82843V20H11.0001V7.82843L5.63614 13.1924L4.22192 11.7782L12.0001 4L19.7783 11.7782L18.3641 13.1924L13.0001 7.82843Z">
            </path>
        </svg>
    </button>
</div>
<!-- Desktop Button -->
<button id="desktopToggle"
    class="lg:flex items-center text-white hidden justify-between px-6 py-3 bg-gradient-to-l from-[#CB122D] to-[#650916] w-fit p-2 fixed bottom-0 right-32 z-40 <?php echo (is_page_template('cost-estimator.php')) ? '!hidden' : ''; ?>">
    <span class="text-base font-bold italic text-white uppercase">
        GET INSTANT CAR SERVICE QUOTE
    </span>
    <svg class="size-4 ms-2 rotate-180" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
        aria-hidden="true" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
    </svg>
</button>
<div id="carPopup" class="popup fixed top-auto bottom-0 lg:bottom-0 lg:left-auto lg:right-[7.3rem] md:right-[6.3rem] inset-x-5 max-md:w-auto z-50 w-full mx-auto max-h-[calc(100dvh-70px)] overflow-y-scroll scrollNone font-inter w-full lg:w-[23.375rem] md:w-[25rem] bg-[#CB122D] shadow-[0px_0px_-20px_0px_rgba(0,0,0,0.3)] flex flex-col lg:flex-row transform -translate-x-1/2 -translate-y-1/2 animate-slideUp opacity-100 pointer-events-auto <?php echo (is_page_template('cost-estimator.php') || (!is_front_page() && !is_home())) ? 'hidden' : ''; ?>">
    <input type="checkbox" id="toggle" class="hidden peer">
    <div class=" transition-all duration-500 ease-in-out w-full">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-3 bg-gradient-to-l from-[#CB122D] to-[#650916]">
            <span class="text-base font-bold italic text-white uppercase">
                INSTANT CAR SERVICE QUOTE
            </span>
            <label for="toggle" class="cursor-pointer text-lg font-bold italic text-white">
                <svg class="size-5 font-bold lg:rotate-0 -rotate-90" stroke="currentColor" fill="none"
                    stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" height="24" width="24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </label>
        </div>

        <div class="p-5">
            <?php
            // Note: $assets_img_url and $cost_estimator_url are already defined at the top of the file
            ?>
            <h2 class="mb-5 font-inter md:text-2xl lg:text-3xl  !leading-12 font-bold text-white">
                Expert car care at <span class="block">express speed.</span>
            </h2>
            <div class="flex flex-col gap-y-5">
                <!-- City Dropdown -->
                <div class="relative flex items-center">
                    <input type="text" placeholder="City" id="cityInput" readonly
                        class="w-full px-4 py-3 text-black bg-white border-none placeholder:text-black placeholder:text-base xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-pointer transition-colors duration-300" />

                    <span id="cityIcon" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <img src="<?php echo esc_url($assets_img_url . 'fi_19024510.webp'); ?>" alt="arrow-icon" class="xl:size-[1.313rem] size-4">
                    </span>

                    <div id="cityDropdown"
                        class="absolute top-full left-0 w-full bg-white border border-gray-200 z-40 overflow-hidden hidden">
                        <div class="grid grid-cols-2 gap-2 p-3 max-h-56 overflow-y-auto">
                            <?php
                            // Get locate-us page ID by template
                            $locate_us_page = get_pages(array(
                                'meta_key' => '_wp_page_template',
                                'meta_value' => 'locate-us.php',
                                'number' => 1,
                                'post_status' => 'publish'
                            ));
                            
                            $locate_us_page_id = !empty($locate_us_page) ? $locate_us_page[0]->ID : null;
                            
                            // Get service centers from locate-us page
                            $service_centers_field = [];
                            if ($locate_us_page_id && function_exists('get_field')) {
                                $service_centers_field = get_field('service_centers_section', $locate_us_page_id) ?: [];
                            }
                            
                            // Extract unique cities from service centres with their images
                            $cities_data = array();
                            if (!empty($service_centers_field['centers']) && is_array($service_centers_field['centers'])) {
                                foreach ($service_centers_field['centers'] as $center) {
                                    $city = trim($center['city'] ?? '');
                                    if (!empty($city) && !isset($cities_data[$city])) {
                                        // Get city image from ACF field
                                        $city_image_id = $center['city_image'] ?? null;
                                        $city_image_url = '';
                                        if ($city_image_id) {
                                            $city_image_data = petromin_get_acf_image_data($city_image_id, 'full', '', $city);
                                            $city_image_url = $city_image_data['url'] ?? '';
                                        }
                                        
                                        $cities_data[$city] = array(
                                            'name' => $city,
                                            'image' => $city_image_url
                                        );
                                    }
                                }
                            }
                            
                            // Sort cities alphabetically
                            uksort($cities_data, 'strcasecmp');
                            
                            // If no cities found, use default cities
                            if (empty($cities_data)) {
                                $cities_data = array(
                                    'Chennai' => array('name' => 'Chennai', 'image' => ''),
                                    'Bengaluru' => array('name' => 'Bengaluru', 'image' => '')
                                );
                            }
                            
                            // Render cities dynamically
                            $city_img_index = 0;
                            foreach ($cities_data as $city_data) {
                                $city = $city_data['name'];
                                $city_image_url = $city_data['image'];
                                
                                // Use city image from ACF if available, otherwise fallback to default pattern
                                if (!empty($city_image_url)) {
                                    $city_img = $city_image_url;
                                } else {
                                    // Fallback: Use city-img1.png, city-img2.png pattern
                                    $img_number = ($city_img_index % 2) + 1;
                                    $city_img = $assets_img_url . 'city-img' . $img_number . '.png';
                                }
                                $city_img_index++;
                            ?>
                            <div class="cursor-pointer group" data-value="<?php echo esc_attr($city); ?>">
                                <div class="relative rounded overflow-hidden">
                                    <img src="<?php echo esc_url($city_img); ?>" alt="<?php echo esc_attr($city); ?>"
                                        class="w-full h-48 xl:h-52 object-cover"
                                        onerror="this.src='<?php echo esc_url($assets_img_url . 'city-img1.png'); ?>';">
                                    <p class="absolute top-0 left-0 text-white font-semibold pt-2 pl-3 [text-shadow:0_0_8px_black,_0_0_8px_black]"><?php echo esc_html($city); ?></p>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Car Brand Dropdown -->
                <?php
                // Fetch car makes from API (server-side)
                $car_makes_api_url = 'https://ryehkyasumhivlakezjb.supabase.co/rest/v1/rpc/get_unique_car_makes';
                $car_makes_response = wp_remote_get($car_makes_api_url, array(
                    'timeout' => 15,
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'apikey' => 'sb_publishable_YqO5Tv3YM4BquKiCgHqs3w_8Wd7-trp'
                    )
                ));
                
                $car_makes = array();
                if (!is_wp_error($car_makes_response) && wp_remote_retrieve_response_code($car_makes_response) === 200) {
                    $car_makes_body = wp_remote_retrieve_body($car_makes_response);
                    $car_makes = json_decode($car_makes_body, true);
                    if (!is_array($car_makes)) {
                        $car_makes = array();
                    }
                }
                ?>
                <div class="relative">
                    <input type="text" placeholder="Car Brand" id="brandInput" readonly=""
                        class="w-full px-4 py-3 text-black bg-white border-none placeholder:text-black xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-pointer transition-colors duration-300">
                    <span id="brandIcon" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <img src="<?php echo esc_url($assets_img_url . 'fi_19024510.webp'); ?>" alt="arrow-icon" class="xl:size-[1.313rem] size-4">
                    </span>

                    <div id="brandDropdown"
                        class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden">
                        <div class="p-3">
                            <div class="flex items-center bg-gray-100 px-3 py-2 mb-2 rounded">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" id="brandSearch" placeholder="Search Car Brand"
                                    class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700" />
                            </div>

                            <div class="max-h-56 overflow-y-auto" id="brandList">
                                <div class="grid grid-cols-3 gap-4" id="brandListItems">
                                    <?php if (!empty($car_makes)) : ?>
                                        <?php foreach ($car_makes as $make) : 
                                            $car_make_name = isset($make['car_make']) ? $make['car_make'] : '';
                                            $car_make_logo = isset($make['car_make_logo_url']) && !empty($make['car_make_logo_url']) ? $make['car_make_logo_url'] : '';
                                            
                                            // Use Petromin logo if logo is not available
                                            $has_logo = !empty($car_make_logo);
                                            $logo_src = $has_logo ? esc_url($car_make_logo) : esc_url($assets_img_url . 'petromin-logo-300x75-1.webp');
                                            $fade_class = $has_logo ? '' : 'opacity-40';
                                        ?>
                                            <div class="cursor-pointer text-center" data-value="<?php echo esc_attr($car_make_name); ?>">
                                                <img src="<?php echo $logo_src; ?>" 
                                                    alt="<?php echo esc_attr($car_make_name); ?>" 
                                                    class="w-16 mx-auto mb-1 aspect-square object-contain <?php echo $fade_class; ?>"
                                                    loading="lazy" 
                                                    fetchpriority="low"
                                                    onerror="this.src='<?php echo esc_url($assets_img_url . 'petromin-logo-300x75-1.webp'); ?>'; this.classList.add('opacity-40');">
                                                <p class="text-xs"><?php echo esc_html($car_make_name); ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <!-- Fallback if API fails -->
                                        <div class="cursor-pointer text-center" data-value="Hyundai">
                                            <img src="<?php echo esc_url($assets_img_url . 'image-34.webp'); ?>" alt="Hyundai" class="w-16 mx-auto mb-1 aspect-square object-contain"
                                                loading="lazy" fetchpriority="low">
                                            <p class="text-xs">Hyundai</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Empty State -->
                                <div id="brandEmptyState" class="hidden text-center py-8">
                                    <p class="text-gray-500 text-sm">No car brands found</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Car Model Dropdown -->
                <div class="relative">
                    <input type="text" placeholder="Car Model (Select Car Brand first)" id="modelInput" readonly=""
                        class="w-full px-4 py-3 text-black/50 bg-white border-none placeholder:text-black/50 xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-not-allowed" disabled>
                    <span id="modelIcon" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <img src="<?php echo esc_url($assets_img_url . 'fi_19024510-1.webp'); ?>" alt="arrow-icon" class="xl:size-[1.313rem] size-4">
                    </span>

                    <div id="modelDropdown"
                        class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden">
                        <div class="p-3">
                            <div class="flex items-center bg-gray-100 px-3 py-2 mb-2 rounded">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" id="modelSearch" placeholder="Search Car Model"
                                    class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700" />
                            </div>

                            <div class="max-h-56 overflow-y-auto" id="modelList">
                                <div class="grid grid-cols-2 gap-4" id="modelListItems">
                                    <!-- Models will be loaded dynamically via AJAX -->
                                </div>
                                <!-- Empty State -->
                                <div id="modelEmptyState" class="text-center py-8">
                                    <p class="text-gray-500 text-sm">Select a car brand first</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fuel Type Dropdown -->
                <div class="relative">
                    <input type="text" placeholder="Fuel Type (Select Car Model first)" id="fuelInput" readonly=""
                        class="w-full px-4 py-3 text-black/50 bg-white border-none placeholder:text-black/50 xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-not-allowed" disabled>
                    <span id="fuelIcon" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <img src="<?php echo esc_url($assets_img_url . 'fi_19024510-1.webp'); ?>" alt="arrow-icon" class="xl:size-[1.313rem] size-4">
                    </span>

                    <div id="fuelType"
                        class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden">
                        <div class="p-3">
                            <div class="grid grid-cols-3 gap-4 max-h-56 overflow-y-auto" id="fuelList">
                                <div class="flex flex-col gap-2 items-center cursor-pointer text-center"
                                    data-value="Petrol">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        class="md:size-12 size-8 text-[#CB122D]" viewBox="0 0 23 24" fill="none">
                                        <g clip-path="url(#clip0_2808_4102)">
                                            <path
                                                d="M22.3773 20.1645C22.671 19.1058 23.4307 15.9298 23.3604 14.7287C23.2136 12.438 20.3409 6.96505 20.2323 6.75456C19.9068 6.01162 18.9939 5.69588 18.2661 6.04877C17.6086 6.36452 17.3213 7.1446 17.6086 7.81324L19.2428 11.4846H19.1343C18.2278 11.4846 17.5 12.1903 17.5 13.0695V15.1125C16.4786 15.9236 15.8657 17.1556 15.8657 18.431V20.1583C15.6104 20.3007 15.4316 20.5483 15.4316 20.8641V22.8019C15.4316 23.26 15.7955 23.6129 16.2679 23.6129H22.2305C22.7029 23.6129 23.0668 23.26 23.0668 22.8019V20.8641C23.0285 20.5483 22.7412 20.2264 22.3773 20.1583V20.1645ZM16.5935 18.5053C16.5935 17.3785 17.1361 16.3198 18.0873 15.645C18.1959 15.5769 18.2342 15.4716 18.2342 15.3602V13.1376C18.2342 12.6423 18.6683 12.2584 19.1407 12.2584C19.7216 12.2584 19.977 12.6113 20.0472 13.1376C20.0855 13.3852 20.0472 13.28 20.0472 16.1712C20.0472 16.3446 20.194 16.5241 20.4111 16.5241C20.6281 16.5241 20.775 16.3508 20.775 16.1712V13.1376C20.775 12.6794 20.5579 12.2213 20.1557 11.9056L18.2661 7.56559C18.1193 7.24985 18.2661 6.85981 18.5917 6.68646C18.9556 6.5131 19.3577 6.64931 19.5365 7.03935C19.5748 7.07649 22.4475 12.6113 22.5944 14.7658C22.6646 15.8617 21.8666 19.1058 21.6113 20.0592H16.5552V18.5053H16.5935ZM21.2155 22.3128C20.8899 22.3128 20.5962 22.028 20.5962 21.7123C20.5962 21.3965 20.8899 21.1117 21.2155 21.1117C21.541 21.1117 21.8347 21.3965 21.8347 21.7123C21.8347 22.028 21.5793 22.3128 21.2155 22.3128Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M7.50174 20.0895V18.3621C7.50174 17.093 6.8825 15.8548 5.86746 15.0437V13.0007C5.86746 12.0101 4.92264 11.3043 4.11827 11.4157L5.72063 7.71346C6.01429 7.04482 5.72063 6.26474 5.06309 5.94899C4.33532 5.5961 3.42881 5.91185 3.13515 6.61763C3.02662 6.85908 0.153867 12.332 0.000653554 14.5856C-0.0695694 15.7867 0.690115 18.9627 0.983774 20.0214C0.581589 20.0895 0.294313 20.4114 0.294313 20.7953V22.7331C0.294313 23.1912 0.658195 23.5441 1.1306 23.5441H7.09317C7.56558 23.5441 7.92946 23.1912 7.92946 22.7331V20.7953C7.92946 20.4795 7.75071 20.1947 7.49535 20.0895H7.50174ZM0.734802 14.6227C0.881632 12.5054 3.75439 6.97052 3.79269 6.89623C3.93952 6.54334 4.37363 6.36999 4.73751 6.58049C5.06309 6.72288 5.20992 7.10673 5.06309 7.42867L3.17345 11.7686C2.80957 12.0534 2.55421 12.5116 2.55421 13.0007V16.0343C2.55421 16.2076 2.70104 16.3872 2.9181 16.3872C3.13515 16.3872 3.28198 16.2138 3.28198 16.0343V13.0688C3.3522 12.4001 3.75439 12.1525 4.18849 12.1525C4.6992 12.1525 5.09501 12.5425 5.09501 13.0316V15.2542C5.09501 15.3595 5.13331 15.4647 5.24184 15.539C6.18665 16.2076 6.73567 17.2663 6.73567 18.3993V19.9533H1.67962C1.46257 18.9627 0.658195 15.7185 0.734802 14.6289V14.6227ZM2.15203 22.1697C1.82645 22.1697 1.53279 21.8849 1.53279 21.5691C1.53279 21.2534 1.82645 20.9686 2.15203 20.9686C2.47761 20.9686 2.77127 21.2534 2.77127 21.5691C2.73296 21.8849 2.47761 22.1697 2.15203 22.1697Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M5.93819 2.94642C6.08502 3.08882 6.30207 3.08882 6.4489 2.94642C6.59573 2.80403 6.59573 2.59353 6.4489 2.45113L5.42748 1.46056C5.28065 1.31816 5.06359 1.31816 4.91676 1.46056C4.76993 1.60295 4.76993 1.81345 4.91676 1.95584L5.93819 2.94642Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M6.80563 1.85092C6.84393 2.02427 7.06098 2.16666 7.23973 2.09856C7.41848 2.06142 7.56531 1.85092 7.49509 1.67757L7.13121 0.265997C7.0929 0.0926462 6.87585 -0.0497491 6.6971 0.018353C6.51835 0.0554996 6.37152 0.265997 6.44175 0.439348L6.80563 1.85092Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M3.64486 3.65165L5.10039 4.00454C5.27914 4.04169 5.50258 3.93644 5.5345 3.7569C5.56642 3.57736 5.46427 3.36686 5.27914 3.3359L3.82361 2.98301C3.64486 2.94587 3.42142 3.05111 3.38951 3.23066C3.31928 3.40401 3.45973 3.58355 3.64486 3.65165Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M17.3205 2.94618C17.4674 3.08857 17.6844 3.08857 17.8312 2.94618L18.8144 1.9556C18.9612 1.8132 18.9612 1.60271 18.8144 1.46031C18.6675 1.31792 18.4505 1.31792 18.3037 1.46031L17.2822 2.45089C17.1737 2.55614 17.1737 2.80378 17.3205 2.94618Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M16.5238 2.09852C16.7025 2.13567 16.926 2.03042 16.9579 1.85088L17.3218 0.439304C17.3601 0.265954 17.2515 0.0492651 17.0664 0.0183096C16.8813 -0.0126459 16.6642 0.0864117 16.6323 0.265954L16.2684 1.67752C16.1982 1.85088 16.3386 2.06756 16.5238 2.09852Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M18.2347 3.75806C18.273 3.93141 18.4901 4.07381 18.6688 4.0057L20.1244 3.65281C20.3031 3.61567 20.4499 3.40517 20.3797 3.23182C20.3414 3.05847 20.1243 2.91607 19.9456 2.98417L18.4901 3.33707C18.3113 3.37421 18.1645 3.54756 18.2347 3.75806Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M16.74 4.07335C16.74 3.75761 16.4846 3.50996 16.159 3.47282C14.0843 3.29947 12.9224 2.48224 12.3415 1.956C12.0861 1.70835 11.6839 1.70835 11.4669 1.956C10.8859 2.48224 9.71768 3.29947 7.64929 3.47282C7.32372 3.50996 7.06836 3.75761 7.06836 4.07335V7.67038C7.06836 9.15006 7.64929 10.5988 8.81755 11.5832C9.61554 12.2889 10.5987 12.7842 11.7286 13.1681C11.8754 13.2052 12.0223 13.2052 12.1308 13.1681C13.2607 12.8152 14.2375 12.2889 15.0419 11.5832C16.1718 10.5926 16.7527 9.15006 16.7527 7.67038V4.07335H16.74ZM11.901 10.0044C10.4837 10.0044 9.31549 8.87765 9.31549 7.49703C9.31549 6.11642 10.4774 4.98964 11.901 4.98964C13.3246 4.98964 14.4865 6.11642 14.4865 7.49703C14.4865 8.87765 13.3246 10.0044 11.901 10.0044Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M12.5913 6.61721C12.553 6.65435 11.538 7.63874 11.5699 7.60778C11.5316 7.57064 11.1358 7.18679 11.1677 7.21774C11.0209 7.07535 10.8038 7.07535 10.657 7.21774C10.5102 7.36014 10.5102 7.57064 10.657 7.71303L11.3145 8.35071C11.4614 8.49311 11.6784 8.49311 11.8253 8.35071L13.0956 7.11869C13.2425 6.97629 13.2425 6.76579 13.0956 6.6234C12.9488 6.481 12.7318 6.481 12.5849 6.6234L12.5913 6.61721Z"
                                                fill="currentColor"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2808_4102">
                                                <rect width="23" height="24" fill="currentColor"></rect>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <p class="md:text-sm text-xs font-normal">Petrol</p>
                                </div>
                                <div class="flex flex-col gap-2 items-center cursor-pointer text-center"
                                    data-value="Diesel">
                                    <svg width="48" height="48" class="md:size-12 size-8 text-[#CB122D]"
                                        viewBox="0 0 29 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M26.7607 29.4609V33.6641C26.7607 33.8157 26.6751 33.9515 26.5498 34.0234C26.4165 34.0874 26.2592 34.0791 26.1416 33.9912L24.0869 32.457L22.0557 33.9834C21.9851 34.0313 21.9065 34.0635 21.8203 34.0635H21.8125C21.75 34.0634 21.6953 34.0473 21.6406 34.0234C21.5073 33.9595 21.4288 33.8158 21.4287 33.6641V29.4609L22.4951 29.4131L23.3652 30.1162C23.5769 30.284 23.8281 30.372 24.0947 30.3721C24.3614 30.3721 24.6203 30.284 24.8242 30.1162L25.6943 29.4131L26.7607 29.4609ZM20.6211 31.7227V31.7139H20.6367L20.6211 31.7227ZM17.335 23.332C17.7505 23.5397 18.2131 23.708 18.707 23.8838L18.8877 24.3867L18.4961 25.4502C18.3081 25.9695 18.4956 26.5606 18.9502 26.8643L19.876 27.4951L20.1738 28.5898C20.2444 28.8615 20.4094 29.0853 20.6211 29.2451V31.7139H18.668V28.958C18.668 28.7344 18.4958 28.5577 18.2764 28.5576C18.0568 28.5576 17.8838 28.7343 17.8838 28.958V31.7139H5.65039V28.958C5.65039 28.7344 5.47814 28.5579 5.25879 28.5576C5.03921 28.5576 4.86621 28.7343 4.86621 28.958V31.7139H0.388672C0.169094 31.7139 -0.00390625 31.5382 -0.00390625 31.3145C-0.00380302 25.6016 2.52974 24.6983 4.75684 23.9072C5.27423 23.7235 5.76023 23.5477 6.19141 23.332C6.64625 23.7795 7.13231 24.2516 7.26562 24.3555C8.55172 25.3543 10.1208 25.8975 11.7598 25.8975C13.3986 25.8974 15.1006 25.3062 16.418 24.2275C16.5123 24.1554 16.9117 23.7554 17.335 23.332ZM23.8457 19.2969C23.9868 19.1852 24.191 19.1851 24.332 19.2969L25.4297 20.1836L26.833 20.1201C27.0134 20.1121 27.1785 20.2324 27.2256 20.4082L27.6016 21.79L28.7705 22.5811C28.9194 22.685 28.9817 22.877 28.9189 23.0527L28.4248 24.3945L28.9189 25.7373C28.9817 25.913 28.9194 26.1051 28.7705 26.209L27.6016 27L27.2256 28.3818C27.1785 28.5576 27.0134 28.6859 26.833 28.6699L25.4297 28.6064L24.332 29.4932C24.2615 29.549 24.175 29.581 24.0889 29.5811C24.0027 29.5811 23.9162 29.549 23.8457 29.4932L22.7471 28.6064L21.3438 28.6699C21.1634 28.6779 20.9992 28.5576 20.9521 28.3818L20.5752 27L19.4072 26.209C19.2582 26.1051 19.1951 25.9131 19.2578 25.7373L19.752 24.3945L19.2578 23.0527C19.1951 22.8769 19.2582 22.6849 19.4072 22.5811L20.5752 21.79L20.9521 20.4082C20.9992 20.2324 21.1634 20.1122 21.3438 20.1201L22.7471 20.1836L23.8457 19.2969ZM24.0889 21.3506C23.9165 21.3506 23.7669 21.4623 23.7119 21.6299L23.1631 23.3643H21.3672C21.2027 23.3643 21.054 23.476 20.999 23.6436C20.9441 23.8114 20.9985 23.9879 21.1396 24.0918L22.5908 25.1621L22.0342 26.8965C21.9795 27.0642 22.0338 27.2399 22.1748 27.3438C22.3159 27.4476 22.5044 27.4475 22.6377 27.3438L24.0889 26.2646L25.5391 27.3438C25.6018 27.3997 25.6882 27.4238 25.7666 27.4238L25.7822 27.4316C25.8606 27.4316 25.9392 27.3995 26.0098 27.3516C26.1508 27.2478 26.2061 27.072 26.1514 26.9043L25.5938 25.1699L27.0449 24.0996C27.1859 23.9958 27.2411 23.82 27.1865 23.6523C27.1316 23.4845 26.9821 23.3721 26.8096 23.3721H25.0137L24.4648 21.6299C24.4099 21.4624 24.2611 21.3507 24.0889 21.3506ZM24.3477 23.8838C24.4026 24.0514 24.5512 24.163 24.7236 24.1631H25.5859L24.8887 24.6826C24.7476 24.7864 24.6923 24.9622 24.7471 25.1299L25.0137 25.9688L24.3164 25.4502C24.2538 25.3943 24.1672 25.3702 24.0889 25.3701L24.0732 25.3623C23.995 25.3623 23.9162 25.3936 23.8457 25.4414L23.1475 25.9609L23.4141 25.1221C23.4689 24.9543 23.4136 24.7787 23.2725 24.6748L22.5752 24.1631H23.4375C23.61 24.1631 23.7595 24.0516 23.8145 23.8838L24.0811 23.0449L24.3477 23.8838ZM15.4688 20.7754C15.6647 21.8138 16.088 22.4529 16.6523 22.9004C16.3467 23.2038 16.0883 23.4597 16.041 23.5078C14.8491 24.5385 13.3437 25.0985 11.7676 25.0986C10.1914 25.0986 8.83441 24.5949 7.67383 23.6602C7.57972 23.5882 7.24257 23.252 6.88184 22.9004C7.44628 22.453 7.86941 21.8139 8.06543 20.7754C9.05343 21.7821 10.2927 22.4692 11.7197 22.4854H11.791C13.226 22.4853 14.4886 21.8061 15.4688 20.7754ZM18.7939 8.11035C19.5466 8.11056 20.1582 8.73402 20.1582 9.50098V10.1641C20.1582 10.931 19.5466 11.5545 18.7939 11.5547H18.4697C18.9393 12.1295 19.1663 12.8994 19.0527 13.6953C18.9038 14.7659 18.1822 15.5413 17.1865 15.7412C16.7082 17.587 15.9547 19.0972 15.0059 20.1279C14.0649 21.1506 12.9594 21.6943 11.7988 21.6943H11.7363C10.4973 21.6784 9.32016 21.0553 8.32422 19.8887C7.34396 18.746 6.59153 17.1233 6.14453 15.1816C5.02312 15.0458 4.21549 14.3506 4.04297 13.3438C3.94721 12.7628 4.09267 12.0872 4.55664 11.54C3.89074 11.4482 3.37601 10.8675 3.37598 10.1641V9.50098C3.37598 8.73402 3.98757 8.11056 4.74023 8.11035H18.7939ZM5.87012 11.5547C4.95213 11.9448 4.72718 12.6831 4.81934 13.208C4.91343 13.7673 5.39941 14.3752 6.47363 14.4072C6.654 14.4072 6.81138 14.543 6.85059 14.7188C7.64267 18.4342 9.56408 20.8548 11.752 20.8867H11.7988C13.8533 20.8866 15.6492 18.7458 16.4883 15.2783C16.5275 15.1106 16.6684 14.9907 16.833 14.9746C17.7582 14.8947 18.1822 14.1994 18.2764 13.5762C18.3756 12.8684 18.08 11.9425 17.1963 11.5547H5.87012ZM8.58984 7.32715H4.72363V7.31934C4.84125 6.12892 5.26506 4.99445 5.97852 4.01172C6.57449 3.18074 7.34339 2.50144 8.22949 1.99805L8.58984 7.32715ZM13.6406 0C13.9385 3.14753e-05 14.2288 0.119465 14.4092 0.327148C14.5503 0.486958 14.6133 0.679144 14.5977 0.878906L14.166 7.31934H9.37402L8.94336 0.871094C8.92768 0.671332 8.99846 0.471333 9.14746 0.311523C9.32774 0.111965 9.60949 0.000111431 9.89941 0H13.6406ZM15.3105 1.93359C16.1966 2.42895 16.9655 3.11619 17.5693 3.95508C18.283 4.95389 18.7066 6.09697 18.8164 7.31152H14.9502L15.3105 1.93359Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <p class=" md:text-sm text-xs font-normal">Diesel</p>
                                </div>
                                <div class="flex flex-col gap-2 items-center cursor-pointer text-center"
                                    data-value="CNG">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        class="md:size-12 size-8 text-[#CB122D]" viewBox="0 0 23 24" fill="none">
                                        <g clip-path="url(#clip0_2808_4102)">
                                            <path
                                                d="M22.3773 20.1645C22.671 19.1058 23.4307 15.9298 23.3604 14.7287C23.2136 12.438 20.3409 6.96505 20.2323 6.75456C19.9068 6.01162 18.9939 5.69588 18.2661 6.04877C17.6086 6.36452 17.3213 7.1446 17.6086 7.81324L19.2428 11.4846H19.1343C18.2278 11.4846 17.5 12.1903 17.5 13.0695V15.1125C16.4786 15.9236 15.8657 17.1556 15.8657 18.431V20.1583C15.6104 20.3007 15.4316 20.5483 15.4316 20.8641V22.8019C15.4316 23.26 15.7955 23.6129 16.2679 23.6129H22.2305C22.7029 23.6129 23.0668 23.26 23.0668 22.8019V20.8641C23.0285 20.5483 22.7412 20.2264 22.3773 20.1583V20.1645ZM16.5935 18.5053C16.5935 17.3785 17.1361 16.3198 18.0873 15.645C18.1959 15.5769 18.2342 15.4716 18.2342 15.3602V13.1376C18.2342 12.6423 18.6683 12.2584 19.1407 12.2584C19.7216 12.2584 19.977 12.6113 20.0472 13.1376C20.0855 13.3852 20.0472 13.28 20.0472 16.1712C20.0472 16.3446 20.194 16.5241 20.4111 16.5241C20.6281 16.5241 20.775 16.3508 20.775 16.1712V13.1376C20.775 12.6794 20.5579 12.2213 20.1557 11.9056L18.2661 7.56559C18.1193 7.24985 18.2661 6.85981 18.5917 6.68646C18.9556 6.5131 19.3577 6.64931 19.5365 7.03935C19.5748 7.07649 22.4475 12.6113 22.5944 14.7658C22.6646 15.8617 21.8666 19.1058 21.6113 20.0592H16.5552V18.5053H16.5935ZM21.2155 22.3128C20.8899 22.3128 20.5962 22.028 20.5962 21.7123C20.5962 21.3965 20.8899 21.1117 21.2155 21.1117C21.541 21.1117 21.8347 21.3965 21.8347 21.7123C21.8347 22.028 21.5793 22.3128 21.2155 22.3128Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M7.50174 20.0895V18.3621C7.50174 17.093 6.8825 15.8548 5.86746 15.0437V13.0007C5.86746 12.0101 4.92264 11.3043 4.11827 11.4157L5.72063 7.71346C6.01429 7.04482 5.72063 6.26474 5.06309 5.94899C4.33532 5.5961 3.42881 5.91185 3.13515 6.61763C3.02662 6.85908 0.153867 12.332 0.000653554 14.5856C-0.0695694 15.7867 0.690115 18.9627 0.983774 20.0214C0.581589 20.0895 0.294313 20.4114 0.294313 20.7953V22.7331C0.294313 23.1912 0.658195 23.5441 1.1306 23.5441H7.09317C7.56558 23.5441 7.92946 23.1912 7.92946 22.7331V20.7953C7.92946 20.4795 7.75071 20.1947 7.49535 20.0895H7.50174ZM0.734802 14.6227C0.881632 12.5054 3.75439 6.97052 3.79269 6.89623C3.93952 6.54334 4.37363 6.36999 4.73751 6.58049C5.06309 6.72288 5.20992 7.10673 5.06309 7.42867L3.17345 11.7686C2.80957 12.0534 2.55421 12.5116 2.55421 13.0007V16.0343C2.55421 16.2076 2.70104 16.3872 2.9181 16.3872C3.13515 16.3872 3.28198 16.2138 3.28198 16.0343V13.0688C3.3522 12.4001 3.75439 12.1525 4.18849 12.1525C4.6992 12.1525 5.09501 12.5425 5.09501 13.0316V15.2542C5.09501 15.3595 5.13331 15.4647 5.24184 15.539C6.18665 16.2076 6.73567 17.2663 6.73567 18.3993V19.9533H1.67962C1.46257 18.9627 0.658195 15.7185 0.734802 14.6289V14.6227ZM2.15203 22.1697C1.82645 22.1697 1.53279 21.8849 1.53279 21.5691C1.53279 21.2534 1.82645 20.9686 2.15203 20.9686C2.47761 20.9686 2.77127 21.2534 2.77127 21.5691C2.73296 21.8849 2.47761 22.1697 2.15203 22.1697Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M5.93819 2.94642C6.08502 3.08882 6.30207 3.08882 6.4489 2.94642C6.59573 2.80403 6.59573 2.59353 6.4489 2.45113L5.42748 1.46056C5.28065 1.31816 5.06359 1.31816 4.91676 1.46056C4.76993 1.60295 4.76993 1.81345 4.91676 1.95584L5.93819 2.94642Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M6.80563 1.85092C6.84393 2.02427 7.06098 2.16666 7.23973 2.09856C7.41848 2.06142 7.56531 1.85092 7.49509 1.67757L7.13121 0.265997C7.0929 0.0926462 6.87585 -0.0497491 6.6971 0.018353C6.51835 0.0554996 6.37152 0.265997 6.44175 0.439348L6.80563 1.85092Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M3.64486 3.65165L5.10039 4.00454C5.27914 4.04169 5.50258 3.93644 5.5345 3.7569C5.56642 3.57736 5.46427 3.36686 5.27914 3.3359L3.82361 2.98301C3.64486 2.94587 3.42142 3.05111 3.38951 3.23066C3.31928 3.40401 3.45973 3.58355 3.64486 3.65165Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M17.3205 2.94618C17.4674 3.08857 17.6844 3.08857 17.8312 2.94618L18.8144 1.9556C18.9612 1.8132 18.9612 1.60271 18.8144 1.46031C18.6675 1.31792 18.4505 1.31792 18.3037 1.46031L17.2822 2.45089C17.1737 2.55614 17.1737 2.80378 17.3205 2.94618Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M16.5238 2.09852C16.7025 2.13567 16.926 2.03042 16.9579 1.85088L17.3218 0.439304C17.3601 0.265954 17.2515 0.0492651 17.0664 0.0183096C16.8813 -0.0126459 16.6642 0.0864117 16.6323 0.265954L16.2684 1.67752C16.1982 1.85088 16.3386 2.06756 16.5238 2.09852Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M18.2347 3.75806C18.273 3.93141 18.4901 4.07381 18.6688 4.0057L20.1244 3.65281C20.3031 3.61567 20.4499 3.40517 20.3797 3.23182C20.3414 3.05847 20.1243 2.91607 19.9456 2.98417L18.4901 3.33707C18.3113 3.37421 18.1645 3.54756 18.2347 3.75806Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M16.74 4.07335C16.74 3.75761 16.4846 3.50996 16.159 3.47282C14.0843 3.29947 12.9224 2.48224 12.3415 1.956C12.0861 1.70835 11.6839 1.70835 11.4669 1.956C10.8859 2.48224 9.71768 3.29947 7.64929 3.47282C7.32372 3.50996 7.06836 3.75761 7.06836 4.07335V7.67038C7.06836 9.15006 7.64929 10.5988 8.81755 11.5832C9.61554 12.2889 10.5987 12.7842 11.7286 13.1681C11.8754 13.2052 12.0223 13.2052 12.1308 13.1681C13.2607 12.8152 14.2375 12.2889 15.0419 11.5832C16.1718 10.5926 16.7527 9.15006 16.7527 7.67038V4.07335H16.74ZM11.901 10.0044C10.4837 10.0044 9.31549 8.87765 9.31549 7.49703C9.31549 6.11642 10.4774 4.98964 11.901 4.98964C13.3246 4.98964 14.4865 6.11642 14.4865 7.49703C14.4865 8.87765 13.3246 10.0044 11.901 10.0044Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M12.5913 6.61721C12.553 6.65435 11.538 7.63874 11.5699 7.60778C11.5316 7.57064 11.1358 7.18679 11.1677 7.21774C11.0209 7.07535 10.8038 7.07535 10.657 7.21774C10.5102 7.36014 10.5102 7.57064 10.657 7.71303L11.3145 8.35071C11.4614 8.49311 11.6784 8.49311 11.8253 8.35071L13.0956 7.11869C13.2425 6.97629 13.2425 6.76579 13.0956 6.6234C12.9488 6.481 12.7318 6.481 12.5849 6.6234L12.5913 6.61721Z"
                                                fill="currentColor"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2808_4102">
                                                <rect width="23" height="24" fill="currentColor"></rect>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <p class="md:text-sm text-xs font-normal">CNG</p>
                                </div>
                                <div class="flex flex-col gap-2 items-center cursor-pointer text-center"
                                    data-value="EV">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        class="md:size-12 size-8 text-[#CB122D]" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M19 7H18V6C18 4.9 17.1 4 16 4H8C6.9 4 6 4.9 6 6V7H5C3.9 7 3 7.9 3 9V18C3 19.1 3.9 20 5 20H6V22H8V20H16V22H18V20H19C20.1 20 21 19.1 21 18V9C21 7.9 20.1 7 19 7ZM8 6H16V7H8V6ZM19 18H5V9H19V18Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M7 11H9V13H7V11ZM15 11H17V13H15V11Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <p class="md:text-sm text-xs font-normal">EV</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <!-- <div class="relative">
                    <input type="tel" placeholder="Contact Number"
                        class="w-full px-4 py-3 text-black bg-white border-none placeholder:text-black xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none">
                </div> -->
            </div>
            <!-- Button -->
            <button id="checkPricesBtn"
                class="w-fit font-inter py-3 px-3 mt-6 text-base font-bold text-white bg-[#FF8300] hover:bg-[#EE8311] focus:outline-none focus:ring-4 focus:ring-[#EE8311] flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed relative"
                disabled>
                <span id="checkPricesBtnText" class="flex items-center">
                    Check Prices
                    <span class="ml-2 font-bold text-xl">
                        <img src="<?php echo esc_url($assets_img_url . 'fi_19024510.webp'); ?>" alt="arrow-icon"
                            class="xl:size-[1.313rem] size-4 invert brightness-0">
                    </span>
                </span>
                <span id="checkPricesBtnLoader" class="hidden flex items-center justify-center">
                    <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                    <span>Processing...</span>
                </span>
            </button>
            <div class="grid grid-cols-3 gap-4 text-center text-white font-inter py-4">
                <div>
                    <div class="text-[1.375rem] font-bold !leading-8">18,000+</div>
                    <div class="text-[0.75rem] font-bold opacity-90">Car Serviced</div>
                </div>
                <div>
                    <div class="text-[1.375rem] font-bold !leading-8">4.9</div>
                    <div class="text-[0.75rem] font-bold opacity-90">Star Rating</div>
                </div>
                <div>
                    <div class="text-[1.375rem] font-bold !leading-8">20+</div>
                    <div class="text-[0.75rem] font-bold opacity-90">Checkpoints</div>
                </div>
            </div>
            <p class="text-[#FFFFFF] font-normal text-xs">Terms & Conditions : Lorem ipsum dolor sit amet
                consectetur adipiscing elit.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>



<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>



<script>
        jQuery(function ($) {
            var $popup = $("#carPopup"),
                $mobile = $("#mobileToggle"),
                $desk = $("#desktopToggle"),
                $chk = $("#toggle"),
                $inner = $popup.find(".w-full.lg\\:w-1\\/3, .w-full").first(),
                show = function () {
                    $popup
                        .removeClass("hidden")
                        .addClass("flex flex-col lg:flex-row animate-slideUp");
                    $inner.css({
                        maxHeight: "",
                        overflow: "",
                    });
                    $chk.prop("checked", false);
                },
                hide = function () {
                    $popup
                        .addClass("hidden")
                        .removeClass("flex flex-col lg:flex-row animate-slideUp");
                    $inner.css({
                        maxHeight: "0px",
                        overflow: "hidden",
                    });
                    $chk.prop("checked", false);
                },
                toggle = function () {
                    $popup.hasClass("hidden") ? show() : hide();
                };

            $mobile.add($desk).on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggle();
            });
            $('label[for="toggle"]').on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                hide();
            });
            $(document).on("click", function (e) {
                if (
                    !$popup.is(e.target) &&
                    $popup.has(e.target).length === 0 &&
                    !$(e.target).is($mobile.add($desk))
                )
                    hide();
            });
            
            // Show popup by default only on home page, hide on cost-estimator page
            <?php if (is_page_template('cost-estimator.php')) : ?>
            hide();
            <?php elseif (is_front_page() || is_home()) : ?>
            show();
            <?php else : ?>
            hide();
            <?php endif; ?>
        });
    </script>
    
    <script>
        // Get WordPress theme image URL for JavaScript
        var themeImgUrl = '<?php echo esc_js(get_template_directory_uri() . '/assets/img/fi_19024510.webp'); ?>';
        var assetsImgUrl = '<?php echo esc_js($assets_img_url); ?>';
        
        // Store all dropdown instances
        var allDropdowns = [];
        
        // Function to close all dropdowns except the current one
        function closeAllDropdowns(exceptDropdownId) {
            allDropdowns.forEach(function(dropdown) {
                if (dropdown.dropdownId !== exceptDropdownId) {
                    dropdown.$dropdown.addClass('hidden');
                    dropdown.$input.removeClass('open').css({
                        'background-color': '#fff',
                        'color': '#000'
                    });
                    if (dropdown.$icon && dropdown.$icon.length) {
                        dropdown.$icon.html(`
          <img src="` + themeImgUrl + `"
            alt="arrow-icon" class="lg:size-[21px] size-4">
        `);
                    }
                }
            });
        }
        
        // Function to enable/disable dropdown
        function setDropdownEnabled(inputId, enabled) {
            const $input = $('#' + inputId);
            const $icon = $('#' + inputId.replace('Input', 'Icon'));
            
            if (enabled) {
                $input.prop('disabled', false)
                    .removeClass('cursor-not-allowed text-black/50 placeholder:text-black/50')
                    .addClass('cursor-pointer text-black placeholder:text-black');
                // Remove fade color, set to black
                $input.css('color', '#000');
                
                // Change icon to enabled arrow (fi_19024510.webp)
                if ($icon.length) {
                    $icon.html(`
                        <img src="${assetsImgUrl}fi_19024510.webp" alt="arrow-icon" class="xl:size-[1.313rem] size-4">
                    `);
                }
            } else {
                $input.prop('disabled', true)
                    .removeClass('cursor-pointer text-black placeholder:text-black')
                    .addClass('cursor-not-allowed text-black/50 placeholder:text-black/50');
                // Set text color to fade (50% opacity)
                $input.css('color', 'rgba(0, 0, 0, 0.5)');
                // Clear value when disabled
                $input.val('');
                
                // Change icon to disabled arrow (fi_19024510-1.webp)
                if ($icon.length) {
                    $icon.html(`
                        <img src="${assetsImgUrl}fi_19024510-1.webp" alt="arrow-icon" class="xl:size-[1.313rem] size-4">
                    `);
                }
            }
        }
        
        // Function to fetch car models from API
        function fetchCarModels(carMake) {
            if (!carMake) {
                return;
            }
            
            const $modelListItems = $('#modelListItems');
            const $modelEmptyState = $('#modelEmptyState');
            const $modelInput = $('#modelInput');
            const $modelIcon = $('#modelIcon');
            
            // Clear previous model selection
            $modelInput.val('');
            setDropdownEnabled('modelInput', false);
            
            // Show loading state in input field
            $modelInput.attr('placeholder', 'Loading models...');
            $modelInput.css({
                'color': '#999',
                '--placeholder-color': '#999'
            });
            
            // Show spinner in icon area
            if ($modelIcon.length) {
                $modelIcon.html(`
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#CB122D]"></div>
                `);
            }
            
            // Prepare loading state for dropdown (when user opens it)
            $modelListItems.html(`
                <div class="col-span-2 text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div>
                    <p class="text-gray-500 text-sm mt-2">Loading models...</p>
                </div>
            `);
            $modelEmptyState.addClass('hidden');
            
            // Make AJAX call
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'get_car_models',
                    car_make: carMake
                },
                success: function(response) {
                    if (response.success && response.data && response.data.models) {
                        const models = response.data.models;
                        let html = '';
                        
                        if (models.length > 0) {
                            models.forEach(function(model) {
                                const modelName = model.car_model || '';
                                const modelImageUrl = model.car_model_image_url || '';
                                
                                if (modelName) {
                                    // Use model image if available, otherwise use Petromin logo (faded)
                                    const hasImage = modelImageUrl && modelImageUrl.trim() !== '';
                                    const imageSrc = hasImage ? modelImageUrl : (assetsImgUrl + 'petromin-logo-300x75-1.webp');
                                    const fadeClass = hasImage ? '' : 'opacity-40';
                                    
                                    html += `
                                        <div class="cursor-pointer text-center" data-value="${modelName}">
                                            <img src="${imageSrc}" 
                                                alt="${modelName}" 
                                                class="w-full h-24 object-contain mb-1 rounded ${fadeClass}" 
                                                loading="lazy" 
                                                fetchpriority="low"
                                                onerror="this.src='${assetsImgUrl}petromin-logo-300x75-1.webp'; this.classList.add('opacity-40');" />
                                            <p class="text-xs">${modelName}</p>
                                        </div>
                                    `;
                                }
                            });
                            
                            $modelListItems.html(html);
                            $modelEmptyState.addClass('hidden');
                            
                            // Enable model dropdown and restore placeholder
                            setDropdownEnabled('modelInput', true);
                            $modelInput.attr('placeholder', 'Car Model');
                            $modelInput.css({
                                'color': '#000',
                                '--placeholder-color': '#000'
                            });
                            // Icon is automatically updated by setDropdownEnabled
                        } else {
                            $modelListItems.html('');
                            $modelEmptyState.find('p').text('No car models found');
                            $modelEmptyState.removeClass('hidden');
                            
                            // Restore placeholder - icon is handled by setDropdownEnabled
                            $modelInput.attr('placeholder', 'Car Model (Select Car Brand first)');
                            $modelInput.css({
                                'color': 'rgba(0, 0, 0, 0.5)',
                                '--placeholder-color': 'rgba(0, 0, 0, 0.5)'
                            });
                            // Icon is automatically updated by setDropdownEnabled (disabled state)
                        }
                    } else {
                        $modelListItems.html('');
                        $modelEmptyState.find('p').text('No car models found');
                        $modelEmptyState.removeClass('hidden');
                        
                        // Restore placeholder - icon is handled by setDropdownEnabled
                        $modelInput.attr('placeholder', 'Car Model (Select Car Brand first)');
                        $modelInput.css({
                            'color': 'rgba(0, 0, 0, 0.5)',
                            '--placeholder-color': 'rgba(0, 0, 0, 0.5)'
                        });
                        // Icon is automatically updated by setDropdownEnabled (disabled state)
                    }
                },
                error: function() {
                    $modelListItems.html('');
                    $modelEmptyState.find('p').text('Failed to load car models');
                    $modelEmptyState.removeClass('hidden');
                    
                    // Restore placeholder - icon is handled by setDropdownEnabled
                    $modelInput.attr('placeholder', 'Car Model (Select Car Brand first)');
                    $modelInput.css({
                        'color': 'rgba(0, 0, 0, 0.5)',
                        '--placeholder-color': 'rgba(0, 0, 0, 0.5)'
                    });
                    // Icon is automatically updated by setDropdownEnabled (disabled state)
                }
            });
        }
        
        // Function to fetch fuel types from API
        function fetchFuelTypes(carMake, carModel) {
            if (!carMake || !carModel) {
                return;
            }
            
            const $fuelList = $('#fuelList');
            const $fuelInput = $('#fuelInput');
            const $fuelIcon = $('#fuelIcon');
            
            // Clear previous fuel type selection
            $fuelInput.val('');
            setDropdownEnabled('fuelInput', false);
            
            // Show loading state
            $fuelInput.attr('placeholder', 'Loading fuel types...');
            $fuelInput.css({
                'color': 'rgba(0, 0, 0, 0.5)',
                '--placeholder-color': 'rgba(0, 0, 0, 0.5)'
            });
            
            // Show spinner in icon area
            if ($fuelIcon.length) {
                $fuelIcon.html(`
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#CB122D]"></div>
                `);
            }
            
            // Prepare loading state for dropdown
            $fuelList.html(`
                <div class="col-span-3 text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div>
                    <p class="text-gray-500 text-sm mt-2">Loading fuel types...</p>
                </div>
            `);
            
            // Make AJAX call
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'get_fuel_types',
                    car_make: carMake,
                    car_model: carModel
                },
                success: function(response) {
                    if (response.success && response.data && response.data.fuel_types) {
                        const fuelTypes = response.data.fuel_types;
                        let html = '';
                        
                        if (fuelTypes.length > 0) {
                            // Default SVG (Petrol SVG) to use for all fuel types as static image
                            const defaultSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" class="md:size-12 size-8 text-[#CB122D]" viewBox="0 0 23 24" fill="none">
                                <g clip-path="url(#clip0_2808_4102)">
                                    <path d="M22.3773 20.1645C22.671 19.1058 23.4307 15.9298 23.3604 14.7287C23.2136 12.438 20.3409 6.96505 20.2323 6.75456C19.9068 6.01162 18.9939 5.69588 18.2661 6.04877C17.6086 6.36452 17.3213 7.1446 17.6086 7.81324L19.2428 11.4846H19.1343C18.2278 11.4846 17.5 12.1903 17.5 13.0695V15.1125C16.4786 15.9236 15.8657 17.1556 15.8657 18.431V20.1583C15.6104 20.3007 15.4316 20.5483 15.4316 20.8641V22.8019C15.4316 23.26 15.7955 23.6129 16.2679 23.6129H22.2305C22.7029 23.6129 23.0668 23.26 23.0668 22.8019V20.8641C23.0285 20.5483 22.7412 20.2264 22.3773 20.1583V20.1645ZM16.5935 18.5053C16.5935 17.3785 17.1361 16.3198 18.0873 15.645C18.1959 15.5769 18.2342 15.4716 18.2342 15.3602V13.1376C18.2342 12.6423 18.6683 12.2584 19.1407 12.2584C19.7216 12.2584 19.977 12.6113 20.0472 13.1376C20.0855 13.3852 20.0472 13.28 20.0472 16.1712C20.0472 16.3446 20.194 16.5241 20.4111 16.5241C20.6281 16.5241 20.775 16.3508 20.775 16.1712V13.1376C20.775 12.6794 20.5579 12.2213 20.1557 11.9056L18.2661 7.56559C18.1193 7.24985 18.2661 6.85981 18.5917 6.68646C18.9556 6.5131 19.3577 6.64931 19.5365 7.03935C19.5748 7.07649 22.4475 12.6113 22.5944 14.7658C22.6646 15.8617 21.8666 19.1058 21.6113 20.0592H16.5552V18.5053H16.5935ZM21.2155 22.3128C20.8899 22.3128 20.5962 22.028 20.5962 21.7123C20.5962 21.3965 20.8899 21.1117 21.2155 21.1117C21.541 21.1117 21.8347 21.3965 21.8347 21.7123C21.8347 22.028 21.5793 22.3128 21.2155 22.3128Z" fill="currentColor"></path>
                                    <path d="M7.50174 20.0895V18.3621C7.50174 17.093 6.8825 15.8548 5.86746 15.0437V13.0007C5.86746 12.0101 4.92264 11.3043 4.11827 11.4157L5.72063 7.71346C6.01429 7.04482 5.72063 6.26474 5.06309 5.94899C4.33532 5.5961 3.42881 5.91185 3.13515 6.61763C3.02662 6.85908 0.153867 12.332 0.000653554 14.5856C-0.0695694 15.7867 0.690115 18.9627 0.983774 20.0214C0.581589 20.0895 0.294313 20.4114 0.294313 20.7953V22.7331C0.294313 23.1912 0.658195 23.5441 1.1306 23.5441H7.09317C7.56558 23.5441 7.92946 23.1912 7.92946 22.7331V20.7953C7.92946 20.4795 7.75071 20.1947 7.49535 20.0895H7.50174ZM0.734802 14.6227C0.881632 12.5054 3.75439 6.97052 3.79269 6.89623C3.93952 6.54334 4.37363 6.36999 4.73751 6.58049C5.06309 6.72288 5.20992 7.10673 5.06309 7.42867L3.17345 11.7686C2.80957 12.0534 2.55421 12.5116 2.55421 13.0007V16.0343C2.55421 16.2076 2.70104 16.3872 2.9181 16.3872C3.13515 16.3872 3.28198 16.2138 3.28198 16.0343V13.0688C3.3522 12.4001 3.75439 12.1525 4.18849 12.1525C4.6992 12.1525 5.09501 12.5425 5.09501 13.0316V15.2542C5.09501 15.3595 5.13331 15.4647 5.24184 15.539C6.18665 16.2076 6.73567 17.2663 6.73567 18.3993V19.9533H1.67962C1.46257 18.9627 0.658195 15.7185 0.734802 14.6289V14.6227ZM2.15203 22.1697C1.82645 22.1697 1.53279 21.8849 1.53279 21.5691C1.53279 21.2534 1.82645 20.9686 2.15203 20.9686C2.47761 20.9686 2.77127 21.2534 2.77127 21.5691C2.73296 21.8849 2.47761 22.1697 2.15203 22.1697Z" fill="currentColor"></path>
                                    <path d="M5.93819 2.94642C6.08502 3.08882 6.30207 3.08882 6.4489 2.94642C6.59573 2.80403 6.59573 2.59353 6.4489 2.45113L5.42748 1.46056C5.28065 1.31816 5.06359 1.31816 4.91676 1.46056C4.76993 1.60295 4.76993 1.81345 4.91676 1.95584L5.93819 2.94642Z" fill="currentColor"></path>
                                    <path d="M6.80563 1.85092C6.84393 2.02427 7.06098 2.16666 7.23973 2.09856C7.41848 2.06142 7.56531 1.85092 7.49509 1.67757L7.13121 0.265997C7.0929 0.0926462 6.87585 -0.0497491 6.6971 0.018353C6.51835 0.0554996 6.37152 0.265997 6.44175 0.439348L6.80563 1.85092Z" fill="currentColor"></path>
                                    <path d="M3.64486 3.65165L5.10039 4.00454C5.27914 4.04169 5.50258 3.93644 5.5345 3.7569C5.56642 3.57736 5.46427 3.36686 5.27914 3.3359L3.82361 2.98301C3.64486 2.94587 3.42142 3.05111 3.38951 3.23066C3.31928 3.40401 3.45973 3.58355 3.64486 3.65165Z" fill="currentColor"></path>
                                    <path d="M17.3205 2.94618C17.4674 3.08857 17.6844 3.08857 17.8312 2.94618L18.8144 1.9556C18.9612 1.8132 18.9612 1.60271 18.8144 1.46031C18.6675 1.31792 18.4505 1.31792 18.3037 1.46031L17.2822 2.45089C17.1737 2.55614 17.1737 2.80378 17.3205 2.94618Z" fill="currentColor"></path>
                                    <path d="M16.5238 2.09852C16.7025 2.13567 16.926 2.03042 16.9579 1.85088L17.3218 0.439304C17.3601 0.265954 17.2515 0.0492651 17.0664 0.0183096C16.8813 -0.0126459 16.6642 0.0864117 16.6323 0.265954L16.2684 1.67752C16.1982 1.85088 16.3386 2.06756 16.5238 2.09852Z" fill="currentColor"></path>
                                    <path d="M18.2347 3.75806C18.273 3.93141 18.4901 4.07381 18.6688 4.0057L20.1244 3.65281C20.3031 3.61567 20.4499 3.40517 20.3797 3.23182C20.3414 3.05847 20.1243 2.91607 19.9456 2.98417L18.4901 3.33707C18.3113 3.37421 18.1645 3.54756 18.2347 3.75806Z" fill="currentColor"></path>
                                    <path d="M16.74 4.07335C16.74 3.75761 16.4846 3.50996 16.159 3.47282C14.0843 3.29947 12.9224 2.48224 12.3415 1.956C12.0861 1.70835 11.6839 1.70835 11.4669 1.956C10.8859 2.48224 9.71768 3.29947 7.64929 3.47282C7.32372 3.50996 7.06836 3.75761 7.06836 4.07335V7.67038C7.06836 9.15006 7.64929 10.5988 8.81755 11.5832C9.61554 12.2889 10.5987 12.7842 11.7286 13.1681C11.8754 13.2052 12.0223 13.2052 12.1308 13.1681C13.2607 12.8152 14.2375 12.2889 15.0419 11.5832C16.1718 10.5926 16.7527 9.15006 16.7527 7.67038V4.07335H16.74ZM11.901 10.0044C10.4837 10.0044 9.31549 8.87765 9.31549 7.49703C9.31549 6.11642 10.4774 4.98964 11.901 4.98964C13.3246 4.98964 14.4865 6.11642 14.4865 7.49703C14.4865 8.87765 13.3246 10.0044 11.901 10.0044Z" fill="currentColor"></path>
                                    <path d="M12.5913 6.61721C12.553 6.65435 11.538 7.63874 11.5699 7.60778C11.5316 7.57064 11.1358 7.18679 11.1677 7.21774C11.0209 7.07535 10.8038 7.07535 10.657 7.21774C10.5102 7.36014 10.5102 7.57064 10.657 7.71303L11.3145 8.35071C11.4614 8.49311 11.6784 8.49311 11.8253 8.35071L13.0956 7.11869C13.2425 6.97629 13.2425 6.76579 13.0956 6.6234C12.9488 6.481 12.7318 6.481 12.5849 6.6234L12.5913 6.61721Z" fill="currentColor"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_2808_4102">
                                        <rect width="23" height="24" fill="currentColor"></rect>
                                    </clipPath>
                                </defs>
                            </svg>`;
                            
                            fuelTypes.forEach(function(fuelType) {
                                const fuelTypeName = fuelType.fuel_type || '';
                                if (fuelTypeName) {
                                    // Escape HTML to prevent XSS and ensure proper display
                                    const escapedFuelTypeName = $('<div>').text(fuelTypeName).html();
                                    html += `
                                        <div class="flex flex-col gap-2 items-center cursor-pointer text-center w-full" data-value="${escapedFuelTypeName}">
                                            ${defaultSvg}
                                            <p class="md:text-sm text-xs font-normal break-words break-all leading-tight px-1 w-full whitespace-normal">${escapedFuelTypeName}</p>
                                        </div>
                                    `;
                                }
                            });
                            
                            $fuelList.html(html);
                            
                            // Enable fuel type dropdown and restore placeholder
                            $fuelInput.attr('placeholder', 'Fuel Type');
                            $fuelInput.css({
                                'color': '#000',
                                '--placeholder-color': 'rgba(0, 0, 0, 0.5)'
                            });
                            setDropdownEnabled('fuelInput', true);
                            
                            // Trigger validation check
                            if (typeof checkFormValidation === 'function') {
                                setTimeout(function() {
                                    checkFormValidation();
                                }, 100);
                            }
                        } else {
                            $fuelList.html('');
                            $fuelInput.attr('placeholder', 'No fuel types found');
                            $fuelInput.css({
                                'color': 'rgba(0, 0, 0, 0.5)',
                                '--placeholder-color': 'rgba(0, 0, 0, 0.5)'
                            });
                            setDropdownEnabled('fuelInput', false);
                        }
                    } else {
                        $fuelList.html('');
                        $fuelInput.attr('placeholder', 'No fuel types found');
                        $fuelInput.css({
                            'color': 'rgba(0, 0, 0, 0.5)',
                            '--placeholder-color': 'rgba(0, 0, 0, 0.5)'
                        });
                        setDropdownEnabled('fuelInput', false);
                    }
                },
                error: function() {
                    $fuelList.html('');
                    $fuelInput.attr('placeholder', 'Failed to load fuel types');
                    $fuelInput.css({
                        'color': 'rgba(0, 0, 0, 0.5)',
                        '--placeholder-color': 'rgba(0, 0, 0, 0.5)'
                    });
                    setDropdownEnabled('fuelInput', false);
                }
            });
        }
        
        function setupDropdown({
            inputId,
            dropdownId,
            itemSelector,
            searchId = null,
            iconId = null,
            enabled = true
        }) {
            const $input = $('#' + inputId);
            const $dropdown = $('#' + dropdownId);
            const $search = searchId ? $('#' + searchId) : null;
            const $icon = iconId ? $('#' + iconId) : null;

            // Store dropdown instance
            allDropdowns.push({
                dropdownId: dropdownId,
                inputId: inputId,
                $input: $input,
                $dropdown: $dropdown,
                $icon: $icon,
                enabled: enabled
            });
            
            // Set initial enabled state
            if (!enabled) {
                setDropdownEnabled(inputId, false);
            }

            // Input click toggle
            $input.on('click', function (e) {
                e.stopPropagation();
                
                // Don't open if disabled
                if ($input.prop('disabled')) {
                    return;
                }
                
                const isOpen = !$dropdown.hasClass('hidden');
                
                // Close all other dropdowns before opening this one
                if (!isOpen) {
                    closeAllDropdowns(dropdownId);
                }
                
                $dropdown.toggleClass('hidden');
                $input.toggleClass('open', !isOpen);

                // background + text color
                if (!isOpen) {
                    // When opening dropdown
                    $input.css({
                        'background-color': '#650916',
                        'color': '#fff',
                        '--placeholder-color': '#fff'
                    });
                } else {
                    // When closing dropdown - restore normal color if enabled, fade if disabled
                    if ($input.prop('disabled')) {
                        $input.css({
                            'background-color': '#fff',
                            'color': 'rgba(0, 0, 0, 0.5)'  // Fade color if disabled
                        });
                    } else {
                        $input.css({
                            'background-color': '#fff',
                            'color': '#000'  // Normal black if enabled
                        });
                    }
                }

                // icon change
                if ($icon.length) {
                    if (!isOpen) {
                        $icon.html(`
          <div class="relative flex items-center gap-1 text-white text-sm bg-[#ff8300] py-3 px-3 -right-4">
            <img src="` + themeImgUrl + `"
              alt="arrow-icon" class="rotate-180 lg:size-[21px] size-4 invert brightness-0">
            <span>Back</span>
          </div>
        `);
                    } else {
                        $icon.html(`
          <img src="` + themeImgUrl + `"
            alt="arrow-icon" class="lg:size-[21px] size-4">
        `);
                    }
                }
            });

            // Outside click close - close all dropdowns
            $(document).on('click', function (e) {
                // Check if click is outside all dropdowns
                var clickedOutside = true;
                allDropdowns.forEach(function(dropdown) {
                    if (dropdown.$dropdown.is(e.target) || 
                        dropdown.$input.is(e.target) || 
                        dropdown.$dropdown.has(e.target).length > 0) {
                        clickedOutside = false;
                    }
                });
                
                if (clickedOutside) {
                    closeAllDropdowns(null);
                }
            });

            // Dropdown item click (using event delegation for dynamic content)
            $dropdown.on('click', itemSelector, function () {
                const value = $(this).data('value');
                
                // Ensure text color is normal black when value is set
                $input.val(value).css({
                    'background-color': '#fff',
                    'color': '#000'  // Always black when value is selected
                });
                
                // Remove any fade color classes
                $input.removeClass('text-black/50').addClass('text-black');
                
                $dropdown.addClass('hidden');
                if ($icon.length) {
                    $icon.html(`
        <img src="` + themeImgUrl + `"
          alt="arrow-icon" class="lg:size-[21px] size-4">
      `);
                }
                
                // Enable next dropdown based on selection
                if (inputId === 'brandInput') {
                    // Car Brand selected - fetch and populate Car Models
                    const selectedBrand = value;
                    fetchCarModels(selectedBrand);
                    // Reset and disable Fuel Type
                    setDropdownEnabled('fuelInput', false);
                } else if (inputId === 'modelInput') {
                    // Car Model selected - fetch and populate Fuel Types
                    const selectedBrand = $('#brandInput').val();
                    const selectedModel = value;
                    fetchFuelTypes(selectedBrand, selectedModel);
                } else if (inputId === 'cityInput') {
                    // If city changes, reset brand, model, and fuel
                    // This is optional - you can remove if not needed
                }
                
                // Trigger validation check after value is set
                setTimeout(function() {
                    if (typeof checkFormValidation === 'function') {
                        checkFormValidation();
                    }
                }, 100);
            });

            // Search filter
            if ($search && $search.length) {
                $search.on('input', function () {
                    const term = $(this).val().toLowerCase();
                    let visibleCount = 0;
                    
                    $dropdown.find(itemSelector).each(function () {
                        const name = $(this).data('value').toLowerCase();
                        const isVisible = name.includes(term);
                        $(this).toggle(isVisible);
                        if (isVisible) {
                            visibleCount++;
                        }
                    });
                    
                    // Show/hide empty state based on search results
                    const $emptyState = $dropdown.find('#brandEmptyState, #modelEmptyState');
                    const $listItems = $dropdown.find('#brandListItems, #modelListItems');
                    
                    if ($emptyState.length && $listItems.length) {
                        if (visibleCount === 0 && term.length > 0) {
                            $listItems.hide();
                            $emptyState.removeClass('hidden');
                        } else {
                            $listItems.show();
                            $emptyState.addClass('hidden');
                        }
                    }
                });
            }
        }

        // Form validation function (global scope)
        var checkFormValidation = function() {
            const $checkPricesBtn = $('#checkPricesBtn');
            if (!$checkPricesBtn.length) return;
            
            const city = $('#cityInput').val().trim();
            const brand = $('#brandInput').val().trim();
            const model = $('#modelInput').val().trim();
            const fuel = $('#fuelInput').val().trim();
            
            // Check if all fields are filled and not disabled
            const cityFilled = city !== '' && !$('#cityInput').prop('disabled');
            const brandFilled = brand !== '' && !$('#brandInput').prop('disabled');
            const modelFilled = model !== '' && !$('#modelInput').prop('disabled');
            const fuelFilled = fuel !== '' && !$('#fuelInput').prop('disabled');
            
            const allFilled = cityFilled && brandFilled && modelFilled && fuelFilled;
            
            // Enable/disable button
            if (allFilled) {
                $checkPricesBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
            } else {
                $checkPricesBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
            }
        };
        
        // Initialize dropdowns
        $(document).ready(function () {
            setupDropdown({
                inputId: 'cityInput',
                dropdownId: 'cityDropdown',
                itemSelector: '[data-value]',
                iconId: 'cityIcon',
                enabled: true
            });

            setupDropdown({
                inputId: 'brandInput',
                dropdownId: 'brandDropdown',
                itemSelector: '[data-value]',
                searchId: 'brandSearch',
                iconId: 'brandIcon',
                enabled: true
            });

            setupDropdown({
                inputId: 'modelInput',
                dropdownId: 'modelDropdown',
                itemSelector: '[data-value]',
                searchId: 'modelSearch',
                iconId: 'modelIcon',
                enabled: false  // Disabled by default
            });
            
            setupDropdown({
                inputId: 'fuelInput',
                dropdownId: 'fuelType',
                itemSelector: '[data-value]',
                iconId: 'fuelIcon',
                enabled: false  // Disabled by default
            });
            
            // Form validation and button enable/disable
            const $checkPricesBtn = $('#checkPricesBtn');
            const costEstimatorUrl = '<?php echo esc_js($cost_estimator_url); ?>';
            
            // Check validation on any input change
            $('#cityInput, #brandInput, #modelInput, #fuelInput').on('input change', function() {
                checkFormValidation();
            });
            
            // Initial validation check
            checkFormValidation();
            
            // Handle Check Prices button click
            $checkPricesBtn.on('click', function(e) {
                e.preventDefault();
                
                if ($(this).prop('disabled')) {
                    return false;
                }
                
                const city = $('#cityInput').val().trim();
                const brand = $('#brandInput').val().trim();
                const model = $('#modelInput').val().trim();
                const fuel = $('#fuelInput').val().trim();
                
                // Show loader and hide button text
                const $btnText = $('#checkPricesBtnText');
                const $btnLoader = $('#checkPricesBtnLoader');
                if ($btnText.length) {
                    $btnText.addClass('hidden');
                }
                if ($btnLoader.length) {
                    $btnLoader.removeClass('hidden');
                }
                $checkPricesBtn.prop('disabled', true);
                
                // Build query string
                const params = new URLSearchParams();
                params.append('city', encodeURIComponent(city));
                params.append('brand', encodeURIComponent(brand));
                params.append('model', encodeURIComponent(model));
                params.append('fuel', encodeURIComponent(fuel));
                
                // Redirect to cost-estimator page with query parameters
                if (costEstimatorUrl) {
                    const redirectUrl = costEstimatorUrl + (costEstimatorUrl.includes('?') ? '&' : '?') + params.toString();
                    window.location.href = redirectUrl;
                } else {
                    console.error('Cost estimator page URL not found');
                    // Hide loader and show button text on error
                    if ($btnText.length) {
                        $btnText.removeClass('hidden');
                    }
                    if ($btnLoader.length) {
                        $btnLoader.addClass('hidden');
                    }
                    $checkPricesBtn.prop('disabled', false);
                    alert('Error: Cost estimator page not found. Please contact support.');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileToggle = document.getElementById('mobileToggle');
            const carPopup = document.getElementById('carPopup');

            // Only run if mobileToggle exists
            if (!mobileToggle) {
                return; // Exit if mobileToggle doesn't exist
            }

            // Only observe if carPopup also exists
            if (carPopup) {
                const observer = new MutationObserver(() => {
                    if (window.getComputedStyle(carPopup).display !== 'none') {
                        mobileToggle.classList.add('hidden'); // hide button
                    } else {
                        mobileToggle.classList.remove('hidden'); // show button again
                    }
                });

                observer.observe(carPopup, {
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            }
        });
    </script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $benefitSettings = petromin_get_swiper_settings('benefitsSectionSwiper');
    ?>
    const benefitSwiper = new Swiper(".benefitsSectionSwiper", {
        speed: <?php echo esc_js($benefitSettings['speed']); ?>,
        centeredSlides: true,
        autoplay: <?php echo $benefitSettings['autoplay'] ? '{
            delay: ' . esc_js($benefitSettings['delay']) . ',
            disableOnInteraction: false,
        }' : 'false'; ?>,
        spaceBetween: 24,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: {
                slidesPerView: 1.1,
                spaceBetween: 8
            },
            480: {
                slidesPerView: 1.07,
                spaceBetween: 8
            },
            640: {
                slidesPerView: 1.18,
                spaceBetween: 8
            },
            1024: {
                slidesPerView: 1.14,
                spaceBetween: 24
            },
            1350: {
                slidesPerView: 1.13,
                spaceBetween: 24
            },
            1536: {
                slidesPerView: 1.13,
                spaceBetween: 24
            },
            1897: {
                slidesPerView: 1.09,
                spaceBetween: 24
            },
            2529: {
                slidesPerView: 1.07,
                spaceBetween: 24
            },
        },
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $featureSettings = petromin_get_swiper_settings('timelineSectionSwiper');
    ?>
    const featureSwiper = new Swiper(".timelineSectionSwiper", {
        speed: <?php echo esc_js($featureSettings['speed']); ?>,
        autoplay: <?php echo $featureSettings['autoplay'] ? '{
            delay: ' . esc_js($featureSettings['delay']) . ',
            disableOnInteraction: false,
        }' : 'false'; ?>,
        spaceBetween: 30,
        loop: true,
        // autoHeight: true,
        centeredSlides: false,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: {
                slidesPerView: 1.2,
            },
            480: {},
            640: {
                slidesPerView: 2.2,
            },
            1024: {
                slidesPerView: 3.1,
            },
            1350: {
                slidesPerView: 3.1,
            },
        },
    });
});

document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $partnerSliderSettings = petromin_get_swiper_settings('partnersFooterSectionSwiper');
    ?>
    const partnerSliderSwiper = new Swiper(".partnersFooterSectionSwiper", {
        speed: <?php echo esc_js($partnerSliderSettings['speed']); ?>,
        autoplay: <?php echo $partnerSliderSettings['autoplay'] ? '{
            delay: ' . esc_js($partnerSliderSettings['delay']) . ',
            disableOnInteraction: false,
        }' : 'false'; ?>,
        spaceBetween: 24,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 20
            },
            480: {
                slidesPerView: 2.4,
                spaceBetween: 32
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 32
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 32
            },
            1350: {
                slidesPerView: 5.6,
                spaceBetween: 32
            },
        },
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $brandLeftSettings = petromin_get_swiper_settings('brandsSectionSwiperLeft');
    ?>
    // Left-to-right Swiper
    const swiperLeft = new Swiper(".brandsSectionSwiperLeft", {
        spaceBetween: 12,
        speed: <?php echo esc_js($brandLeftSettings['speed']); ?>,
        loop: true,
        autoplay: <?php echo $brandLeftSettings['autoplay'] ? '{
            delay: ' . esc_js($brandLeftSettings['delay']) . ',
            disableOnInteraction: false,
            reverseDirection: false,
        }' : 'false'; ?>,
        allowTouchMove: false, // optional: prevents manual drag
        breakpoints: {
            320: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            480: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 32
            },
            1024: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
            1350: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
        },
    });

    <?php 
    $brandLeft1Settings = petromin_get_swiper_settings('brandsSectionSwiperMobile');
    ?>
    // Left-to-right Swiper
    const swiperLeft1 = new Swiper(".brandsSectionSwiperMobile", {
        spaceBetween: 12,
        speed: <?php echo esc_js($brandLeft1Settings['speed']); ?>,
        loop: true,
        autoplay: <?php echo $brandLeft1Settings['autoplay'] ? '{
            delay: ' . esc_js($brandLeft1Settings['delay']) . ',
            disableOnInteraction: false,
            reverseDirection: false,
        }' : 'false'; ?>,
        allowTouchMove: false, // optional: prevents manual drag
        breakpoints: {
            320: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            480: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 32
            },
            1024: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
            1350: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
        },
    });
    <?php 
    $brandRightSettings = petromin_get_swiper_settings('brandsSectionSwiperRight');
    ?>
    // Right-to-left Swiper
    const swiperRight = new Swiper(".brandsSectionSwiperRight", {
        slidesPerView: 9.2,
        spaceBetween: 12,
        speed: <?php echo esc_js($brandRightSettings['speed']); ?>,
        loop: true,
        autoplay: <?php echo $brandRightSettings['autoplay'] ? '{
            delay: ' . esc_js($brandRightSettings['delay']) . ',
            disableOnInteraction: false,
            reverseDirection: true, // <-- key for opposite direction
        }' : 'false'; ?>,
        breakpoints: {
            320: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            480: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 32
            },
            1024: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
            1350: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
        },
        allowTouchMove: false,
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $testimonialSettings = petromin_get_swiper_settings('testimonialsSectionSwiper');
    ?>
    const testimonialSwiper = new Swiper(".testimonialsSectionSwiper", {
        speed: <?php echo esc_js($testimonialSettings['speed']); ?>,
        autoHeight: true,
        centeredSlides: true,
        autoplay: <?php echo $testimonialSettings['autoplay'] ? '{
            delay: ' . esc_js($testimonialSettings['delay']) . ',
            disableOnInteraction: false,
        }' : 'false'; ?>,
        spaceBetween: 24,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: {
                slidesPerView: 1.4,
                spaceBetween: 6
            },
            480: {
                slidesPerView: 1.5,
                spaceBetween: 12
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 16
            },
            1024: {
                slidesPerView: 3.5,
                spaceBetween: 24
            },
            1350: {
                slidesPerView: 3.7,
                spaceBetween: 24
            },
        },
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $partnerSettings = petromin_get_swiper_settings('partnersSectionSwiper');
    ?>
    const partnerSwiper = new Swiper(".partnersSectionSwiper", {
        loop: true,
        autoSlide: true,
        spaceBetween: 24,
        freeMode: true,
        freeModeMomentum: false,
        speed: <?php echo esc_js($partnerSettings['speed']); ?>,
        autoplay: <?php echo $partnerSettings['autoplay'] ? '{
            delay: ' . esc_js($partnerSettings['delay']) . ',
            disableOnInteraction: false,
        }' : 'false'; ?>,
        allowTouchMove: false,
        breakpoints: {
            320: {
                slidesPerView: 3.8,
                spaceBetween: 16
            },
            480: {
                slidesPerView: 3.8,
                spaceBetween: 16
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 16
            },
            1024: {
                slidesPerView: 9.2,
                spaceBetween: 16
            },
            1350: {
                slidesPerView: 9.2,
                spaceBetween: 24
            },
        },
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll(".m-tab");
    const tabContents = document.querySelectorAll(".cont-item");

    // Only run if both .m-tab and .cont-item classes exist
    if (tabLinks.length === 0 || tabContents.length === 0) {
        return; // Exit if elements don't exist
    }

    function resetTabs() {
        tabLinks.forEach(tab => {
            tab.className =
                "m-tab px-3 py-5 -my-5 lg:font-bold font-semibold text-base text-white";
            tab.innerHTML =
                `<span class="block text-base whitespace-nowrap lg:whitespace-wrap">${tab.innerText}</span>`;
        });
    }

    tabLinks.forEach(tab => {
        tab.addEventListener("click", function() {
            const target = this.dataset.tab;

            // Reset all tabs
            resetTabs();

            // Apply active styles to clicked tab
            this.className =
                "m-tab active relative px-3 py-5 -my-5 lg:font-bold font-semibold bg-gradient-to-l from-[#CB122D] to-[#650916] text-white -skew-x-[12deg]";
            this.innerHTML =
                `<span class="skew-x-[12deg] block whitespace-nowrap">${this.innerText}</span>`;

            // Hide all contents
            tabContents.forEach(c => c.classList.add("hidden"));

            // Show target content
            const content = document.querySelector(`.cont-item[data-content="${target}"]`);
            if (content) content.classList.remove("hidden");
        });
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const labels = Array.from(document.querySelectorAll(".label"));
    const arrow = document.getElementById("arrow");
    const container = document.getElementById("wheelContainer");

    // ✅ Check if required elements exist before proceeding
    if (!arrow || !container || labels.length === 0) {
        return; // Exit early if elements don't exist
    }

    let currentActive = null;

    // ✅ Arrow distance adjusted for mobile view
    function computeArrowDistance() {
        if (!container) return 0; // Safety check
        const rect = container.getBoundingClientRect();
        const radius = rect.width / 2;

        // 🔸 Push arrow slightly outward on mobile
        if (window.innerWidth <= 640) {
            return Math.round(radius * 1); // small outward shift for mobile
        } else {
            return Math.round(radius * 0.96); // normal for desktop
        }
    }

    function moveArrow(angle) {
        if (!arrow) return; // Safety check
        const distance = computeArrowDistance();
        arrow.style.transform = `translate(-50%, -50%) rotate(${angle}deg) translateY(-${distance}px)`;
    }

    function clearActiveLabels() {
        labels.forEach((lbl) => {
            lbl.classList.remove("text-[#CB122D]", "scale-110");
            const sub = lbl.querySelector(".subtext");
            if (sub) {
                sub.classList.remove("opacity-100", "translate-y-1");
                sub.classList.add("opacity-0", "translate-y-3");
            }
        });
    }

    function activateByIndex(index) {
        const target = document.querySelector(`.label[data-index="${index}"]`);
        if (!target) return;
        clearActiveLabels();
        target.classList.add("text-[#CB122D]", "scale-110");
        const sub = target.querySelector(".subtext");
        if (sub) {
            sub.classList.remove("opacity-0", "translate-y-3");
            sub.classList.add("opacity-100", "translate-y-1");
        }
        const angle = Number(target.dataset.angle) || 0;
        moveArrow(angle);
        currentActive = Number(index);
    }

    labels.forEach((label) => {
        const idx = Number(label.dataset.index);
        label.addEventListener("mouseenter", () => activateByIndex(idx));
        label.addEventListener("focus", () => activateByIndex(idx));
    });

    window.addEventListener("resize", () => {
        if (currentActive !== null) {
            activateByIndex(currentActive);
        } else {
            moveArrow(0);
        }
    });

    // ✅ Detect device width and activate accordingly
    if (window.innerWidth <= 640) {
        // Mobile view
        activateByIndex(1);
    } else {
        // Desktop view
        activateByIndex(0);
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $categorySliderSettings = petromin_get_swiper_settings('newsCategorySectionSwiper');
    ?>
    const categorySliderSwiper = new Swiper(".newsCategorySectionSwiper", {
        slidesPerView: "auto",
        spaceBetween: 20,
        speed: <?php echo esc_js($categorySliderSettings['speed']); ?>,
        autoplay: <?php echo $categorySliderSettings['autoplay'] ? '{
            delay: ' . esc_js($categorySliderSettings['delay']) . ',
            disableOnInteraction: false,
        }' : 'false'; ?>,
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
    });

    // Get buttons
    const prevBtn = document.querySelector(".swiper-prev");
    const nextBtn = document.querySelector(".swiper-next");

    // Only manipulate buttons if they exist
    if (prevBtn && nextBtn && categorySliderSwiper) {
        // Initially hide prev
        prevBtn.classList.add("opacity-0", "pointer-events-none");

        // Show/hide prev button based on active slide index
        categorySliderSwiper.on("slideChange", () => {
            if (categorySliderSwiper.realIndex === 0) {
                prevBtn.classList.add("opacity-0", "pointer-events-none");
            } else {
                prevBtn.classList.remove("opacity-0", "pointer-events-none");
            }
        });
    }
});
</script>

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5/dist/fancybox/fancybox.umd.js"></script>
<script>
Fancybox.bind('a[data-fancybox]', {
    on: {
        reveal: (fancybox, $slide) => {
            console.log('Fancybox revealed');
        },
    },
});
</script>

<?php wp_footer(); ?>

</body>

</html>