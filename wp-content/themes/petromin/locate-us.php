<?php
/* Template Name: locate us page */
get_header();

$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$images_url = $assets_url . '/img';

$hero_defaults = [
    'title' => 'Get in touch with us.',
    'image' => [
        'url' => $images_url . '/locate_heroimg.webp',
        'alt' => 'Get in touch with us.',
    ],
];

$contact_defaults = [
    'registered_office' => [
        'title' => 'Registered Office',
        'address' => 'Automin Car Services Private Limited 672/476, Temple Tower Anna Salai, Nandanam Chennai, Tamil Nadu – 600035'
    ],
    'head_office' => [
        'title' => 'Head Office',
        'address' => 'Sai Brindhavan, Plot No. 40C, Door 1, 3rd Main Road, Kottur Gardens, Kotturpuram, Chennai, Tamil Nadu – 600085'
    ],
    'station_hours' => [
        'title' => 'Station Hours',
        'hours' => [
            '8 AM to 8 PM',
            'Monday to Saturday',
            'Open: 3rd & 4th Sunday | Closed: 1st & 2nd Sunday',
            'Call Centre Support Hours 6 AM to 11 PM'
        ],
        'phone' => '+91 86868 92000',
        'email' => 'customercare.pe@petromin.in'
    ]
];

$map_image_defaults = [
    'desktop' => [
        'url' => $images_url . '/locate_img.webp',
        'alt' => 'Location map'
    ],
    'mobile' => [
        'url' => $images_url . '/locate_img_mobile.webp',
        'alt' => 'Location map mobile'
    ]
];

$service_centers_defaults = [
    'heading' => 'Service centres near you.',
    'centers' => [
        [
            'name' => 'Peenya',
            'city' => 'Bengaluru',
            'address' => 'BDA, CA Site, NH 4, Industrial Suburb 2nd Stage, Peenya, Bengaluru, Karnataka 560058',
            'image' => [
                'url' => $images_url . '/service_center.webp',
                'alt' => 'Peenya Service Center'
            ],
            'map_link' => 'https://www.google.com/maps?q=13.012289,77.552406&z=14&output=embed',
        ],
        [
            'name' => 'Ullalu',
            'city' => 'Bengaluru',
            'address' => 'Ward No 130, Bbmp, Sy No 164/2, Vishveshwaraiah Layout, Kengeri Magadi, Ullal, Bengaluru, Karnataka 560056',
            'image' => [
                'url' => $images_url . '/service_center.webp',
                'alt' => 'Ullalu Service Center'
            ],
            'map_link' => 'https://www.google.com/maps?q=12.979844,77.478508&z=14&output=embed',
        ]
    ]
];

$faq_defaults = [
    'heading' => 'Drive away doubts',
    'categories' => [
        [
            'title' => 'Petromin Services',
            'items' => [
                [
                    'question' => 'What services do you provide?',
                    'answer' => 'Do you provide an upfront estimate before starting work?',
                    'active' => true
                ],
                [
                    'question' => 'Do you provide an upfront estimate before starting work?',
                    'answer' => 'Yes, we only use genuine spare parts for all repairs and replacements.',
                    'active' => false
                ]
            ]
        ],
        [
            'title' => 'About Petromin Express',
            'items' => [
                [
                    'question' => 'What is Petromin Express?',
                    'answer' => 'Petromin Express is a subsidiary of Petromin Corporation, a globally recognised Saudi Arabian automotive services company with decades of expertise, now expanding across multiple international markets, including India.',
                    'active' => true
                ],
                [
                    'question' => 'How long has Petromin Corporation been in business?',
                    'answer' => 'Petromin Corporation has over 50 years of legacy in the automotive industry, consistently delivering trusted, innovative, and sustainable car care solutions.',
                    'active' => false
                ]
            ]
        ]
    ]
];

if (!function_exists('petromin_extract_city_from_map')) {
    /**
     * Extract city name from Google Map address data
     */
    function petromin_extract_city_from_map($map_location)
    {
        if (empty($map_location) || !is_array($map_location)) {
            return '';
        }
        
        // Use the address from Google Map
        $address = $map_location['address'] ?? '';
        
        if (empty($address)) {
            return '';
        }
        
        return petromin_locate_extract_city($address);
    }
}

if (!function_exists('petromin_locate_extract_city')) {
    /**
     * Attempt to extract a city name from the provided address string.
     */
    function petromin_locate_extract_city($address)
    {
        if (empty($address) || !is_string($address)) {
            return '';
        }

        $parts = array_values(array_filter(array_map('trim', explode(',', $address)), static function ($value) {
            return $value !== '';
        }));

        $parts_count = count($parts);

        if ($parts_count >= 2) {
            return $parts[$parts_count - 2];
        }

        return $parts[0] ?? '';
    }
}

// Get ACF Fields
$hero_field = function_exists('get_field') ? (get_field('hero_section') ?: []) : [];
$hero_title = trim($hero_field['hero_title'] ?? '') ?: $hero_defaults['title'];
$hero_image_data = petromin_get_acf_image_data($hero_field['hero_image'] ?? null, 'full', $hero_defaults['image']['url'], $hero_defaults['image']['alt']);

$contact_field = function_exists('get_field') ? (get_field('contact_section') ?: []) : [];
$registered_office_title = trim($contact_field['registered_office_title'] ?? '') ?: $contact_defaults['registered_office']['title'];
$registered_office_address = trim($contact_field['registered_office_address'] ?? '') ?: $contact_defaults['registered_office']['address'];
$head_office_title = trim($contact_field['head_office_title'] ?? '') ?: $contact_defaults['head_office']['title'];
$head_office_address = trim($contact_field['head_office_address'] ?? '') ?: $contact_defaults['head_office']['address'];
$station_hours_title = trim($contact_field['station_hours_title'] ?? '') ?: $contact_defaults['station_hours']['title'];

// Fix station hours data processing
$station_hours_items = [];
if (!empty($contact_field['station_hours']) && is_array($contact_field['station_hours'])) {
    foreach ($contact_field['station_hours'] as $hour_item) {
        if (!empty($hour_item['hour_item'])) {
            $station_hours_items[] = $hour_item['hour_item'];
        }
    }
}
if (empty($station_hours_items)) {
    $station_hours_items = $contact_defaults['station_hours']['hours'];
}

$contact_phone = trim($contact_field['contact_phone'] ?? '') ?: $contact_defaults['station_hours']['phone'];
$contact_email = trim($contact_field['contact_email'] ?? '') ?: $contact_defaults['station_hours']['email'];

$map_field = function_exists('get_field') ? (get_field('map_section') ?: []) : [];
$map_desktop_image = petromin_get_acf_image_data($map_field['desktop_image'] ?? null, 'full', $map_image_defaults['desktop']['url'], $map_image_defaults['desktop']['alt']);
$map_mobile_image = petromin_get_acf_image_data($map_field['mobile_image'] ?? null, 'full', $map_image_defaults['mobile']['url'], $map_image_defaults['mobile']['alt']);

$service_centers_field = function_exists('get_field') ? (get_field('service_centers_section') ?: []) : [];
$service_centers_heading = trim($service_centers_field['section_heading'] ?? '') ?: $service_centers_defaults['heading'];

// Fix service centers data processing
$service_centers_items = [];
if (!empty($service_centers_field['centers']) && is_array($service_centers_field['centers'])) {
    $service_centers_items = $service_centers_field['centers'];
} else {
    $service_centers_items = $service_centers_defaults['centers'];
}

$faq_field = function_exists('get_field') ? (get_field('faq_section') ?: []) : [];
$faq_heading = trim($faq_field['section_heading'] ?? '') ?: $faq_defaults['heading'];

// Fix FAQ data processing
$faq_categories = [];
if (!empty($faq_field['categories']) && is_array($faq_field['categories'])) {
    $faq_categories = $faq_field['categories'];
} else {
    $faq_categories = $faq_defaults['categories'];
}

// Process service centers with fallbacks

$processed_centers = [];
foreach ($service_centers_items as $index => $center) {
    $fallback = $service_centers_defaults['centers'][$index] ?? $service_centers_defaults['centers'][0] ?? [];
    
    $name = trim($center['name'] ?? '') ?: ($fallback['name'] ?? 'Service Center');
    $map_location = $center['map_location'] ?? null;
    
    // Use Google Map address if available, otherwise fallback
    $address = '';
    if (!empty($map_location['address'])) {
        $address = trim($map_location['address']);
    } else {
        $address = trim($center['address'] ?? '') ?: ($fallback['address'] ?? '');
    }
    
    // Extract city from Google Map address
    $city = petromin_extract_city_from_map($map_location);
    
    // Generate map embed URL from Google Map coordinates
    $map_src = '';
    if (!empty($map_location['lat']) && !empty($map_location['lng'])) {
        $lat = (float) $map_location['lat'];
        $lng = (float) $map_location['lng'];
        $map_src = sprintf('https://www.google.com/maps?q=%s,%s&z=14&output=embed', $lat, $lng);
    }
    
    // Handle image
    $image_default = $fallback['image'] ?? [];
    $image = petromin_get_acf_image_data($center['image'] ?? null, 'full', $image_default['url'] ?? '', $image_default['alt'] ?? $name);
    
    $processed_centers[] = [
        'name' => $name,
        'address' => $address,
        'map_src' => $map_src,
        'city' => $city,
        'image' => $image,
        'map_location' => $map_location,
    ];
}

// Process FAQ categories
$processed_faq_categories = [];
foreach ($faq_categories as $category_index => $category) {
    $fallback_category = $faq_defaults['categories'][$category_index] ?? $faq_defaults['categories'][0] ?? [];
    
    $category_title = trim($category['title'] ?? '') ?: ($fallback_category['title'] ?? 'FAQ Category');
    $category_items = !empty($category['items']) && is_array($category['items']) ? $category['items'] : ($fallback_category['items'] ?? []);
    
    $processed_items = [];
    foreach ($category_items as $item_index => $item) {
        $fallback_item = $fallback_category['items'][$item_index] ?? [];
        
        $question = trim($item['question'] ?? '') ?: ($fallback_item['question'] ?? '');
        $answer = trim($item['answer'] ?? '') ?: ($fallback_item['answer'] ?? '');
        $active = isset($item['active']) ? (bool)$item['active'] : ($fallback_item['active'] ?? false);
        
        if ($question && $answer) {
            $processed_items[] = [
                'question' => $question,
                'answer' => $answer,
                'active' => $active
            ];
        }
    }
    
    if ($category_title && !empty($processed_items)) {
        $processed_faq_categories[] = [
            'title' => $category_title,
            'items' => $processed_items
        ];
    }
}

// Agar koi processed data empty hai to default data use karo
if (empty($processed_centers)) {
    foreach ($service_centers_defaults['centers'] as $center) {
        $processed_centers[] = [
            'name' => $center['name'],
            'city' => $center['city'] ?? petromin_locate_extract_city($center['address'] ?? ''),
            'address' => $center['address'],
            'map_link' => $center['map_link'],
            'image' => [
                'url' => $center['image']['url'],
                'alt' => $center['image']['alt']
            ],
            'map_location' => $center['map_location'] ?? null,
        ];
    }
}

/**
 * Build an embeddable Google Maps URL either from an iframe URL or a map field.
 */
if (!function_exists('petromin_locate_get_map_src')) {
    function petromin_locate_get_map_src($map_location)
    {
        if (is_array($map_location) && isset($map_location['lat'], $map_location['lng'])) {
            $lat = (float) $map_location['lat'];
            $lng = (float) $map_location['lng'];

            if ($lat || $lng) {
                return sprintf('https://www.google.com/maps?q=%s,%s&z=14&output=embed', $lat, $lng);
            }
        }

        return '';
    }
}
$city_options = [];
foreach ($processed_centers as $processed_center) {
    $city_name = trim($processed_center['city'] ?? '');

    if ($city_name !== '') {
        $city_options[$city_name] = $city_name;
    }
}

$city_options = array_unique(array_values($city_options));
sort($city_options); // Optional: sort cities alphabetically

// Set default city to empty to show all centers
$default_city = '';

// Get first center's map source for default display
$default_map_src = '';
if (!empty($processed_centers)) {
    $first_center = $processed_centers[0];
    $default_map_src = petromin_locate_get_map_src($first_center['map_location'] ?? null);
}

// If no map source from first center, try to get from any center
if ($default_map_src === '') {
    foreach ($processed_centers as $processed_center) {
        $map_src = petromin_locate_get_map_src($processed_center['map_location'] ?? null);

        if ($map_src !== '') {
            $default_map_src = $map_src;
            break;
        }
    }
}

if (empty($processed_faq_categories)) {
    $processed_faq_categories = $faq_defaults['categories'];
}
?>

<div class="hero_section w-full relative z-0 md:h-dvh max-sm:h-dvh">
    <div class="relative w-full h-full overflow-hidden">
        <?php if (!empty($hero_image_data)) : ?>
            <img fetchpriority="high" decoding="async" loading="eager" src="<?php echo esc_url($hero_image_data['url']); ?>"
                class="size-full object-cover" alt="<?php echo esc_attr($hero_image_data['alt']); ?>"
                title="<?php echo esc_attr($hero_image_data['alt']); ?>">
        <?php endif; ?>

        <div
            class="lg:w-[38.829rem] md:w-[36.829rem] w-[21.563rem] absolute lg:bottom-32 md:bottom-24 bottom-20 left-0 flex lg:py-8 py-5  px-8  bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)]  origin-top -skew-x-[18deg]">
            <div class="view flex items-center justify-center skew-x-[18deg] pr-0">
                <h1 class="xl:text-6xl md:text-5xl text-[2rem] text-balance text-white font-bold">
                    <?php echo esc_html($hero_title); ?></h1>
            </div>
        </div>
    </div>
</div>

<section class="bg-white pt-[1.875rem] md:pt-20 h-full">
    <div class="view flex w-full h-full flex-col md:flex-row lg:flex-row gap-[2rem] md:gap-[6.75rem]">
        <div class="flex flex-col pb-2 md:pb-0 w-full md:w-1/3 gap-y-8">
            <div class="flex gap-3 md:gap-5 flex-col">
                <h3 class="lg:text-4xl md:text-3xl text-2xl text-black font-bold"><?php echo esc_html($registered_office_title); ?></h3>
                <div
                    class="relative pb-4 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] lg:after:h-3 after:h-[10px] after:-skew-x-[18deg] after:left-0">
                </div>
                <p class="text-black text-balance font-medium text-base">
                    <?php echo esc_html($registered_office_address); ?>
                </p>
            </div>
            <div class="flex gap-3 md:gap-5 flex-col">
                <h3 class="lg:text-4xl md:text-3xl text-2xl text-black font-bold"><?php echo esc_html($head_office_title); ?></h3>
                <div
                    class="relative pb-4 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] lg:after:h-3 after:h-[10px] after:-skew-x-[18deg] after:left-0">
                </div>
                <p class="text-black text-balance font-medium text-base">
                    <?php echo esc_html($head_office_address); ?>
                </p>
            </div>
            <div class="flex gap-3 md:gap-5 flex-col">
                <h3 class="lg:text-4xl md:text-3xl text-2xl text-black font-bold"><?php echo esc_html($station_hours_title); ?></h3>
                <div
                    class="relative pb-4 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] lg:after:h-3 after:h-[10px] after:-skew-x-[18deg] after:left-0">
                </div>

                <div class="">
                    <?php foreach ($station_hours_items as $hour_item) : ?>
                        <p class="text-black text-balance font-medium text-base">
                            <?php echo esc_html($hour_item); ?>
                        </p>
                    <?php endforeach; ?>
                    <div class="flex items-center pt-3 md:pt-0 pb-1 gap-2">
                        <svg class="" width="16" height="17" viewBox="0 0 16 17" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.1055 4.36968L5.83707 3.76569C5.66155 3.37078 5.57379 3.17331 5.44254 3.0222C5.27805 2.83282 5.06364 2.69348 4.82378 2.62009C4.63239 2.56152 4.4163 2.56152 3.98414 2.56152C3.35194 2.56152 3.03584 2.56152 2.77048 2.68305C2.45791 2.8262 2.17562 3.13704 2.06315 3.46192C1.96767 3.73772 1.99502 4.02114 2.04972 4.58799C2.63194 10.6216 5.93988 13.9296 11.9735 14.5118C12.5404 14.5665 12.8238 14.5938 13.0996 14.4984C13.4245 14.3859 13.7353 14.1036 13.8785 13.791C14 13.5257 14 13.2096 14 12.5774C14 12.1452 14 11.9292 13.9414 11.7378C13.868 11.4979 13.7287 11.2835 13.5393 11.119C13.3882 10.9878 13.1908 10.9 12.7958 10.7244L12.1918 10.456C11.7642 10.266 11.5503 10.1709 11.333 10.1502C11.125 10.1304 10.9154 10.1596 10.7207 10.2354C10.5173 10.3146 10.3376 10.4644 9.97797 10.7641C9.6201 11.0623 9.44117 11.2114 9.2225 11.2913C9.02864 11.3622 8.77237 11.3884 8.56824 11.3583C8.3379 11.3244 8.16157 11.2301 7.80884 11.0416C6.7115 10.4552 6.10635 9.85003 5.51991 8.7527C5.33143 8.39996 5.23718 8.22363 5.20324 7.9933C5.17316 7.78916 5.19938 7.5329 5.2702 7.33903C5.35008 7.12038 5.49921 6.94143 5.79746 6.58352C6.09708 6.22398 6.24689 6.0442 6.3261 5.8408C6.4019 5.64615 6.43108 5.43646 6.4113 5.22851C6.39063 5.0112 6.29558 4.79736 6.1055 4.36968Z"
                                stroke="#CB122D" stroke-width="1.2" stroke-linecap="round" />
                        </svg>
                        <span class="text-black font-medium text-base"><?php echo esc_html($contact_phone); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_2808_1945)">
                                <path d="M14 4.06152L8 9.56152L2 4.06152" stroke="#CB122D" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M2 4.06152H14V12.5615C14 12.6941 13.9473 12.8213 13.8536 12.9151C13.7598 13.0088 13.6326 13.0615 13.5 13.0615H2.5C2.36739 13.0615 2.24021 13.0088 2.14645 12.9151C2.05268 12.8213 2 12.6941 2 12.5615V4.06152Z"
                                    stroke="#CB122D" stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M6.9093 8.56152L2.1543 12.9203" stroke="#CB122D" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.8448 12.9203L9.08984 8.56152" stroke="#CB122D" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_2808_1945">
                                    <rect width="16" height="16" fill="white" transform="translate(0 0.561523)" />
                                </clipPath>
                            </defs>
                        </svg>
                        <span class="text-black font-medium text-base"><?php echo esc_html($contact_email); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:w-2/3 w-full  md:h-[41.813rem] overflow-hidden">
            <?php if (!empty($map_desktop_image['url'])) : ?>
                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($map_desktop_image['url']); ?>" 
                    alt="<?php echo esc_attr($map_desktop_image['alt']); ?>" title="<?php echo esc_attr($map_desktop_image['alt']); ?>"
                    class="w-full h-full object-cover md:block hidden">
            <?php endif; ?>
            <?php if (!empty($map_mobile_image['url'])) : ?>
                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($map_mobile_image['url']); ?>" 
                    alt="<?php echo esc_attr($map_mobile_image['alt']); ?>" title="<?php echo esc_attr($map_mobile_image['alt']); ?>"
                    class="w-full h-full object-cover md:hidden block">
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="Service_centre bg-white  md:pt-[7.25rem] pt-[3.75rem] pb-10 md:pb-[4.625rem]  relative w-full ">
    <div class="view flex flex-col md:gap-y-12 gap-y-7">
        <div class="w-full relative">
            <h2 class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-2xl text-black font-bold"><?php echo esc_html($service_centers_heading); ?></h2>
            <div
                class="relative pt-[0.563rem] md:pt-[1.25rem] after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] lg:after:h-3 after:h-[10px] after:-skew-x-[18deg] after:left-0">
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="md:w-1/3 w-full relative flex flex-col gap-y-3 bg-white">
                <div
                    class="md:sticky md:border-0 border-b border-[#E0E5EB] flex flex-col gap-4 top-0 z-20 bg-white pt-3 pb-5 md:pb-2 px-0">
                    <?php if (!empty($city_options)) : ?>
                        <div class="flex flex-col gap-2">
                            <div class="relative">
                                <label class="sr-only" for="service-center-city">Select your city</label>
                                <select id="service-center-city" aria-label="Select your city" data-city-filter
                                    class="w-full appearance-none bg-white md:text-base text-sm font-medium text-[#000000] focus:outline-none focus:ring-0 border border-[#DCDFE6] px-3 md:px-5 h-[3.313rem] transition-colors">
                                    <option value="">Select your City</option>
                                    <?php foreach ($city_options as $city_option) : ?>
                                        <option value="<?php echo esc_attr($city_option); ?>">
                                            <?php echo esc_html($city_option); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <svg class="pointer-events-none absolute right-4 md:right-8 top-1/2 size-4 md:size-6 -translate-y-1/2 text-[#000000]"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Service Center Cards -->
                <div class="flex pt-1 flex-col gap-y-[0.938rem]">
                    <span class="text-base font-medium text-[#637083] md:hidden block" data-center-count>
                        Found <?php echo esc_html(count($processed_centers)); ?> service centers
                    </span>

                    <?php foreach ($processed_centers as $center) :
                        $center_city = trim($center['city'] ?? '');
                        $center_map_src = petromin_locate_get_map_src($center['map_location'] ?? null);
                        ?>
                        <div class="group bg-white border border-[#DCDFE6] hover:border-[#CB122D] pt-4 px-5 pb-8 md:p-4 overflow-hidden h-full duration-300"
                            data-service-center data-city="<?php echo esc_attr($center_city); ?>" 
                            data-map-src="<?php echo esc_url($center_map_src); ?>"
                            data-center-name="<?php echo esc_attr($center['name']); ?>">
                            <div class="w-full">
                                <?php if (!empty($center['image']['url'])) : ?>
                                    <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($center['image']['url']); ?>"
                                        alt="<?php echo esc_attr($center['image']['alt']); ?>" title="<?php echo esc_attr($center['image']['alt']); ?>"
                                        class="w-full h-full object-cover" />
                                <?php endif; ?>
                            </div>
                            <div class="py-4 space-y-2">
                                <h3 class="md:text-base text-lg font-semibold md:font-medium text-black"><?php echo esc_html($center['name']); ?></h3>
                                <div class="flex flex-col space-y-[0.688rem]">
                                    <div class="flex items-start justify-center gap-2">
                                        <svg class="size-4 shrink-0" width="16" height="16" viewBox="0 0 16 16"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13.3346 6.66634C13.3346 9.99501 9.64197 13.4617 8.40197 14.5323C8.28645 14.6192 8.14583 14.6662 8.0013 14.6662C7.85677 14.6662 7.71615 14.6192 7.60064 14.5323C6.36064 13.4617 2.66797 9.99501 2.66797 6.66634C2.66797 5.25185 3.22987 3.8953 4.23007 2.89511C5.23026 1.89491 6.58681 1.33301 8.0013 1.33301C9.41579 1.33301 10.7723 1.89491 11.7725 2.89511C12.7727 3.8953 13.3346 5.25185 13.3346 6.66634Z"
                                                stroke="#637083" stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M8 8.66699C9.10457 8.66699 10 7.77156 10 6.66699C10 5.56242 9.10457 4.66699 8 4.66699C6.89543 4.66699 6 5.56242 6 6.66699C6 7.77156 6.89543 8.66699 8 8.66699Z"
                                                stroke="#637083" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm font-normal text-[#637083]">
                                            <?php echo esc_html($center['address']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <button type="button" data-locate-btn aria-label="Locate <?php echo esc_attr($center['name']); ?> on map"
                                class="px-3 cursor-pointer  flex h-10 md:h-[1.563rem] w-[7.5rem] justify-center space-x-3 items-center bg-[#CB122D] md:bg-gradient-to-r md:from-[#CB122D] md:via-[#980D22] md:to-[#CB122D] md:-skew-x-[18deg]">
                                <span
                                    class="flex items-center gap-2 text-base font-semibold md:skew-x-[18deg] text-white">Locate
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13"
                                        fill="none">
                                        <path
                                            d="M7.91132 6.24682L3.44235 12.4887H0L1.28861 10.5558L4.18797 6.24682L1.28861 1.85772L0 0L3.44235 0L7.91132 6.24682Z"
                                            fill="white" />
                                    </svg></span>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="md:w-2/3 w-full">
                <div class="h-[20rem] md:h-[53.188rem] w-full">
                    <iframe data-map-frame title="Service center location map"
                        src="<?php echo esc_url($default_map_src ?: 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d5122760.8817607!2d10.454119350000001!3d51.17580699999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sde!4v1760714693105!5m2!1sen!2sde'); ?>"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const citySelect = document.querySelector('[data-city-filter]');
        const centerCards = Array.from(document.querySelectorAll('[data-service-center]'));
        const mapFrame = document.querySelector('[data-map-frame]');
        const countElement = document.querySelector('[data-center-count]');
        const rawDefaultMapSrc = mapFrame ? mapFrame.getAttribute('src') : '';

        if (!centerCards.length) {
            return;
        }

        function isValidMapSrc(src) {
            if (typeof src !== 'string') {
                return false;
            }

            const trimmedSrc = src.trim();
            return trimmedSrc !== '' && /^https?:\/\//.test(trimmedSrc);
        }

        const defaultMapSrc = isValidMapSrc(rawDefaultMapSrc) ? rawDefaultMapSrc.trim() : '';

        function updateMap(src) {
            if (!mapFrame || !isValidMapSrc(src)) {
                return;
            }

            const trimmedSrc = src.trim();

            if (mapFrame.getAttribute('src') !== trimmedSrc) {
                mapFrame.setAttribute('src', trimmedSrc);
            }
        }

        function setActiveCard(activeCard) {
            centerCards.forEach(function (card) {
                const isActive = card === activeCard;
                card.classList.toggle('border-[#CB122D]', isActive);
                card.classList.toggle('border-[#DCDFE6]', !isActive);
                card.setAttribute('data-active', isActive ? 'true' : 'false');
                
                // Update map when card becomes active
                if (isActive) {
                    const mapSrc = card.dataset.mapSrc || '';
                    if (isValidMapSrc(mapSrc)) {
                        updateMap(mapSrc);
                    }
                }
            });
        }

        function updateCountText(selectedCity) {
            if (!countElement) {
                return;
            }

            const visibleCards = centerCards.filter(function (card) {
                return !card.classList.contains('hidden');
            });
            const visibleCount = visibleCards.length;

            if (!visibleCount) {
                countElement.textContent = selectedCity ? `No service centers available in ${selectedCity}` : 'No service centers available';
                return;
            }

            const pluralSuffix = visibleCount === 1 ? '' : 's';

            if (selectedCity) {
                countElement.textContent = `Found ${visibleCount} service center${pluralSuffix} in ${selectedCity}`;
            } else {
                countElement.textContent = `Found ${visibleCount} service center${pluralSuffix}`;
            }
        }

        function showCardsForCity(selectedCity) {
            let firstVisibleCard = null;

            centerCards.forEach(function (card) {
                const cardCity = (card.dataset.city || '').trim();
                const isMatch = !selectedCity || cardCity === selectedCity;

                card.classList.toggle('hidden', !isMatch);
                card.setAttribute('aria-hidden', isMatch ? 'false' : 'true');

                if (isMatch && !firstVisibleCard) {
                    firstVisibleCard = card;
                }
            });

            updateCountText(selectedCity);

            if (firstVisibleCard) {
                setActiveCard(firstVisibleCard);
                const cardMapSrc = firstVisibleCard.dataset.mapSrc || '';
                if (isValidMapSrc(cardMapSrc)) {
                    updateMap(cardMapSrc);
                } else if (defaultMapSrc) {
                    updateMap(defaultMapSrc);
                }
            } else {
                setActiveCard(null);
                if (defaultMapSrc) {
                    updateMap(defaultMapSrc);
                }
            }
        }

        // Add click event listeners to all locate buttons
        centerCards.forEach(function (card) {
            const locateButton = card.querySelector('[data-locate-btn]');

            if (locateButton) {
                locateButton.addEventListener('click', function () {
                    if (card.classList.contains('hidden')) {
                        return;
                    }

                    setActiveCard(card);

                    const mapSrc = card.dataset.mapSrc || '';
                    if (isValidMapSrc(mapSrc)) {
                        updateMap(mapSrc);
                    } else if (defaultMapSrc) {
                        updateMap(defaultMapSrc);
                    }
                });
            }
        });

        if (citySelect) {
            citySelect.addEventListener('change', function (event) {
                const selectedCity = event.target.value.trim();
                showCardsForCity(selectedCity);
            });

            // Initialize with no city selected (show all centers)
            showCardsForCity('');
            
            // Set first card as active by default
            if (centerCards.length > 0) {
                setActiveCard(centerCards[0]);
            }
        } else {
            // If no city select, set first card as active
            const firstCard = centerCards[0];
            if (firstCard) {
                setActiveCard(firstCard);
                const mapSrc = firstCard.dataset.mapSrc || '';
                if (isValidMapSrc(mapSrc)) {
                    updateMap(mapSrc);
                } else if (defaultMapSrc) {
                    updateMap(defaultMapSrc);
                }
            }
            updateCountText('');
        }
    });
</script>

<section class="faq bg-white relative w-full pb-20">
    <div class="view flex flex-col md:gap-y-12 gap-y-8" id="faqAccordion">
        <div class="w-full relative">
            <h2 class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] text-black font-bold"><?php echo esc_html($faq_heading); ?></h2>
            <div
                class="relative pt-1.5 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] lg:after:h-3 after:h-[10px] after:-skew-x-[18deg] after:left-0">
            </div>
        </div>

        <div class="w-full relative flex flex-col md:gap-y-16 gap-y-12 mt-6">
            <?php foreach ($processed_faq_categories as $category) : ?>
                <div class="flex flex-col gap-6 md:gap-y-5 w-full">
                    <h3 class="lg:text-4xl md:text-3xl text-2xl text-[#000000] font-bold"><?php echo esc_html($category['title']); ?></h3>
                    <div class="grid md:grid-cols-2 gap-4 md:gap-5">
                        <?php foreach ($category['items'] as $item) : 
                            $is_active = $item['active'] ?? false;
                            $active_class = $is_active ? 'text-[#CB122D]' : 'text-gray-800';
                            $body_class = $is_active ? '' : 'hidden';
                            $icon_text = $is_active ? '−' : '+';
                        ?>
                            <div class="accordion-item border border-black">
                                <button
                                    class="accordion-header w-full px-6 py-4 flex justify-between items-center text-left font-semibold <?php echo $active_class; ?>">
                                    <span class="md:text-xl text-base font-semibold "><?php echo esc_html($item['question']); ?></span>
                                    <span
                                        class="shirnk-0 accordion-icon text-white bg-[#CB122D] size-6 flex items-center justify-center"><?php echo $icon_text; ?></span>
                                </button>
                                <div class="accordion-body px-6 pb-4 pt-2 text-base md:text-sm text-[#010101] font-normal <?php echo $body_class; ?>">
                                    <?php echo wp_kses_post(nl2br($item['answer'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    const headers = document.querySelectorAll('#faqAccordion .accordion-header');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const body = item.querySelector('.accordion-body');
            const icon = header.querySelector('.accordion-icon');

            const isActive = !body.classList.contains('hidden');

            // Close all
            document.querySelectorAll('#faqAccordion .accordion-body').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('#faqAccordion .accordion-icon').forEach(el => el.textContent = '+');
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

<?php get_footer(); ?>