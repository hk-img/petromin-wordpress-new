<?php
/* Template Name: Services Page */
get_header();

$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$images_url = $assets_url . '/img';

// Default values
$defaults = [
    'hero' => [
        'background_image' => [
            'url' => $images_url . '/service_hero.webp',
            'alt' => 'Petromin Express, on the go.',
        ],
        'heading_prefix' => 'All-round expert',
        'heading_highlight' => 'car care',
        'heading_suffix' => 'all at one place.',
        'description' => 'Choose your services to get started.<br>We\'ll adapt the care plan to your car\'s real-time condition.',
    ],
    'our_services' => [
        'section_heading' => 'Our Services',
        'navigation_icons' => [
            'left_arrow_icon' => [
                'url' => $images_url . '/left_chev.svg',
                'alt' => 'left arrow',
            ],
            'right_arrow_icon' => [
                'url' => $images_url . '/right_chev.svg',
                'alt' => 'right arrow',
            ],
        ],
        'slides' => [
            [
                'slide_image' => ['url' => $images_url . '/ourService.webp', 'alt' => 'car services'],
                'service_icon' => ['url' => $images_url . '/coverage.webp', 'alt' => 'Car Service'],
                'service_title' => 'Car Service',
                'service_link' => '#',
            ],
            [
                'slide_image' => ['url' => $images_url . '/ourService.webp', 'alt' => 'Battery Service'],
                'service_icon' => ['url' => $images_url . '/coverage.webp', 'alt' => 'Battery Service'],
                'service_title' => 'Battery Service',
                'service_link' => '#',
            ],
            // Add more default slides as needed
        ],
    ],
    'features' => [
        'heading' => 'Feel the Petromin Express difference.',
        'features_list' => [
            ['feature_text' => 'Hassle-Free Servicing'],
            ['feature_text' => 'Prompt Maintenance'],
            ['feature_text' => 'Multi-Brand Care'],
            ['feature_text' => 'Responsive Customer Support'],
            ['feature_text' => 'Sustainable Operations'],
        ],
        'features_image' => [
            'url' => $images_url . '/serviceFeel.webp',
            'alt' => 'Petromin Express',
        ],
    ],
    'app' => [
        'trusted_text' => 'Trusted by 1 lakh+ car owners across India',
        'user_images' => [
            ['user_image' => ['url' => $images_url . '/serviceTap1.webp', 'alt' => 'a']],
            ['user_image' => ['url' => $images_url . '/serviceTap2.webp', 'alt' => 'b']],
            ['user_image' => ['url' => $images_url . '/serviceTap3.webp', 'alt' => 'c']],
        ],
        'heading_line1' => 'Tap.',
        'heading_line2' => 'Track.',
        'heading_line3' => 'Take control.',
        'description' => 'Install now and enjoy exclusive servicing offers and discounts.',
        'app_store_badges' => [
            'play_store_image' => ['url' => $images_url . '/serviceGoogle.webp', 'alt' => 'Google Play'],
            'play_store_link' => '#',
            'app_store_image' => ['url' => $images_url . '/serviceApple.webp', 'alt' => 'App Store'],
            'app_store_link' => '#',
        ],
        'phone_image' => [
            'url' => $images_url . '/serviceHand.webp',
            'alt' => 'app on phone',
        ],
    ],
    'faq' => [
        'section_heading' => 'Commonly Asked Questions',
        'faq_items' => [
            [
                'question' => 'What services do you provide?',
                'answer' => 'Do you provide an upfront estimate before starting work?',
                'is_active' => true,
            ],
            [
                'question' => 'Is there a warranty on servicing?',
                'answer' => 'Yes, we only use genuine spare parts for all repairs and replacements.',
                'is_active' => false,
            ],
            // Add more default FAQs
        ],
    ],
];

// Get ACF data with fallbacks
$hero_field = function_exists('get_field') ? (get_field('hero_section') ?: []) : [];
$our_services_field = function_exists('get_field') ? (get_field('our_services_section') ?: []) : [];
$features_field = function_exists('get_field') ? (get_field('features_section') ?: []) : [];
$app_field = function_exists('get_field') ? (get_field('app_section') ?: []) : [];
$faq_field = function_exists('get_field') ? (get_field('faq_section') ?: []) : [];

// Process data with fallbacks
$hero_data = [
    'background_image' => petromin_get_acf_image_data($hero_field['background_image'] ?? null, 'full', $defaults['hero']['background_image']['url'], $defaults['hero']['background_image']['alt']),
    'heading_prefix' => $hero_field['heading_prefix'] ?? $defaults['hero']['heading_prefix'],
    'heading_highlight' => $hero_field['heading_highlight'] ?? $defaults['hero']['heading_highlight'],
    'heading_suffix' => $hero_field['heading_suffix'] ?? $defaults['hero']['heading_suffix'],
    'description' => $hero_field['description'] ?? $defaults['hero']['description'],
];

$our_services_data = [
    'section_heading' => $our_services_field['section_heading'] ?? $defaults['our_services']['section_heading'],
    'navigation_icons' => [
        'left_arrow_icon' => petromin_get_acf_image_data($our_services_field['navigation_icons']['left_arrow_icon'] ?? null, 'full', $defaults['our_services']['navigation_icons']['left_arrow_icon']['url'], $defaults['our_services']['navigation_icons']['left_arrow_icon']['alt']),
        'right_arrow_icon' => petromin_get_acf_image_data($our_services_field['navigation_icons']['right_arrow_icon'] ?? null, 'full', $defaults['our_services']['navigation_icons']['right_arrow_icon']['url'], $defaults['our_services']['navigation_icons']['right_arrow_icon']['alt']),
    ],
    'slides' => $defaults['our_services']['slides'],
];

// Prefer sourcing slides from published 'service' posts so this section reflects Service posts
$service_posts_query = new WP_Query([
    'post_type' => 'service',
    'posts_per_page' => 12,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

$service_posts = $service_posts_query->posts;
if (!empty($service_posts)) {
    $posts_slides = [];
    foreach ($service_posts as $sp) {
        setup_postdata($sp);
        $pid = $sp->ID;

        // Use the ACF 'For Services Page Section's' image as the slide image
        $slide_img = petromin_get_acf_image_data(get_field('for_services_page_image', $pid), 'full', '', get_the_title($pid));
        // Use the ACF service icon
        $icon_img = petromin_get_acf_image_data(get_field('service_icon', $pid), 'thumbnail', '', get_the_title($pid));
        // Get the hero description from the service post
        $service_description = get_field('hero_description', $pid) ?: 'Lorem ipsum dolor sit amet consectetur adipiscing elit.';

        $posts_slides[] = [
            'slide_image' => $slide_img ?: ['url' => '', 'alt' => get_the_title($pid)],
            'service_icon' => $icon_img ?: ['url' => '', 'alt' => get_the_title($pid)],
            'service_title' => get_the_title($pid),
            'service_description' => $service_description,
            'service_link' => get_permalink($pid),
        ];
    }
    wp_reset_postdata();

    if (!empty($posts_slides)) {
        $our_services_data['slides'] = $posts_slides;
    }
}

$features_data = [
    'heading' => $features_field['heading'] ?? $defaults['features']['heading'],
    'features_list' => !empty($features_field['features_list']) ? $features_field['features_list'] : $defaults['features']['features_list'],
    'features_image' => petromin_get_acf_image_data($features_field['features_image'] ?? null, 'full', $defaults['features']['features_image']['url'], $defaults['features']['features_image']['alt']),
];

$app_data = [
    'trusted_text' => $app_field['trusted_text'] ?? $defaults['app']['trusted_text'],
    'user_images' => [],
    'heading_line1' => $app_field['heading_line1'] ?? $defaults['app']['heading_line1'],
    'heading_line2' => $app_field['heading_line2'] ?? $defaults['app']['heading_line2'],
    'heading_line3' => $app_field['heading_line3'] ?? $defaults['app']['heading_line3'],
    'description' => $app_field['description'] ?? $defaults['app']['description'],
    'app_store_badges' => [
        'play_store_image' => petromin_get_acf_image_data($app_field['app_store_badges']['play_store_image'] ?? null, 'full', $defaults['app']['app_store_badges']['play_store_image']['url'], $defaults['app']['app_store_badges']['play_store_image']['alt']),
        'play_store_link' => $app_field['app_store_badges']['play_store_link'] ?? $defaults['app']['app_store_badges']['play_store_link'],
        'app_store_image' => petromin_get_acf_image_data($app_field['app_store_badges']['app_store_image'] ?? null, 'full', $defaults['app']['app_store_badges']['app_store_image']['url'], $defaults['app']['app_store_badges']['app_store_image']['alt']),
        'app_store_link' => $app_field['app_store_badges']['app_store_link'] ?? $defaults['app']['app_store_badges']['app_store_link'],
    ],
    'phone_image' => petromin_get_acf_image_data($app_field['phone_image'] ?? null, 'full', $defaults['app']['phone_image']['url'], $defaults['app']['phone_image']['alt']),
];

// Process user images
$user_images = !empty($app_field['user_images']) ? $app_field['user_images'] : $defaults['app']['user_images'];
foreach ($user_images as $index => $user_image) {
    $default_user = $defaults['app']['user_images'][$index] ?? $defaults['app']['user_images'][0];
    $app_data['user_images'][] = [
        'user_image' => petromin_get_acf_image_data($user_image['user_image'] ?? null, 'full', $default_user['user_image']['url'], $default_user['user_image']['alt']),
    ];
}

$faq_data = [
    'section_heading' => $faq_field['section_heading'] ?? $defaults['faq']['section_heading'],
    'faq_items' => !empty($faq_field['faq_items']) ? $faq_field['faq_items'] : $defaults['faq']['faq_items'],
];
?>

<!-- Hero Section -->
<div class="hero_section w-full relative z-0 md:min-h-dvh h-dvh md:!h-auto">
    <div class="relative w-full h-full overflow-hidden">
        <?php if (!empty($hero_data['background_image']['url'])) : ?>
        <img fetchpriority="high" decoding="async" loading="eager"
            src="<?php echo esc_url($hero_data['background_image']['url']); ?>"
            class="size-full object-cover lg:aspect-[1279/614]"
            alt="<?php echo esc_attr($hero_data['background_image']['alt']); ?>"
            title="<?php echo esc_attr($hero_data['background_image']['alt']); ?>">
        <?php endif; ?>

        <div class="absolute inset-0 bg-[linear-gradient(180deg,_#00000000_0%,_#000000d6_100%)]"></div>
        <div class="absolute flex flex-col lg:bottom-32 md:bottom-24 bottom-20 left-0 view text-white">
            <h1
                class="xl:text-6xl md:text-5xl text-[2.625rem] font-bold leading-tight flex md:w-[52rem] flex-wrap items-center gap-2">
                <?php echo esc_html($hero_data['heading_prefix']); ?>
                <span
                    class="bg-gradient-to-l from-[#CB122D] to-[#650916] md:ml-2 md:h-[4.688rem] md:w-[18.25rem] inline-block px-2 flex justify-center -skew-x-[18deg]">
                    <span class="skew-x-[18deg]"><?php echo esc_html($hero_data['heading_highlight']); ?></span>
                </span>
                <span class="md:block sm:inline-block"><?php echo esc_html($hero_data['heading_suffix']); ?></span>
            </h1>

            <p class="text-[#FFFFFF] mt-4 font-normal text-lg md:text-[1.375rem]">
                <?php echo wp_kses_post($hero_data['description']); ?>
            </p>
        </div>
    </div>
</div>

<!-- New Our Services Section -->
<div class="w-full relative bg-white md:py-[4.25rem] py-[2rem]">
    <div class="view">
        <div class="w-full flex flex-col gap-1 md:gap-1">
            <h2
                class="relative  xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] font-bold text-[#000000] ">
                <?php echo esc_html($our_services_data['section_heading']); ?>
            </h2>
            <div class="bg-gradient-to-l from-[#CB122D]  to-[#650916] w-[7.375rem] h-3 -skew-x-[22deg]">
            </div>
        </div>
        <div class="w-full relative pt-12">
            <div class="grid lg:grid-cols-3 md:grid-cols-3 grid-cols-1 gap-6">
                <?php foreach ($our_services_data['slides'] as $slide) : ?>
                
                <div
                    class="w-full relative overflow-hidden group duration-500 md:h-[32.813rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2 after:absolute after:inset-0 after:[background:_linear-gradient(180deg,_rgba(0,_0,_0,_0)_41.83%,_#000000_100%)] after:z-0 *:z-10">
                    <?php if (!empty($slide['slide_image']['url'])) : ?>
                    <img fetchpriority="low" loading="lazy"
                        src="<?php echo esc_url($slide['slide_image']['url']); ?>"
                        alt="<?php echo esc_attr($slide['slide_image']['alt']); ?>"
                        title="<?php echo esc_attr($slide['slide_image']['alt']); ?>"
                        class="size-full object-cover aspect-[50/60]" />
                    <?php endif; ?>
                    <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 p-6">
                        <div>
                            <?php if (!empty($slide['service_icon']['url'])) : ?>
                            <img src="<?php echo esc_url($slide['service_icon']['url']); ?>"
                                alt="<?php echo esc_attr($slide['service_icon']['alt']); ?>"
                                class="w-8 h-8">
                            <?php endif; ?>
                        </div>
                        <h3
                            class="text-[#FFFFFF] lg:text-3xl md:text-2xl text-xl font-bold duration-300 group-hover:lg:text-[#CB122D]">
                            <?php echo esc_html($slide['service_title']); ?>
                        </h3>
                        <p class="text-[#FFFFFF] opacity-75 md:text-lg text-base duration-500 
        drop-shadow-[3px_3px_10px_rgba(0,0,0,0.9)] line-clamp-2">
                            <?php echo wp_kses_post($slide['service_description']); ?>
                        </p>
                        <div class="flex justify-between items-center gap-2">
                            <button
                                class="hover:lg:bg-[#CB122D] w-full px-5 flex space-x-3 items-center justify-center bg-[#FF8300] h-12 duration-300">
                                <span
                                    class="flex items-center gap-1 text-sm md:text-base md:font-bold font-semibold text-white">Check
                                    Price
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.75rem] h-[0.75rem]"
                                        viewBox="0 0 14 20" fill="none">
                                        <path
                                            d="M13.5294 9.84344L6.92754 19.6791H0L2.20534 16.4006L6.60187 9.84344L2.20534 3.29018L0 0H6.92754L13.5294 9.84344Z"
                                            fill="white"></path>
                                    </svg>
                                </span>
                            </button>
                            <a href="<?php echo esc_url($slide['service_link']); ?>"
                                class="hover:lg:bg-[#CB122D] w-full px-5 flex space-x-3 items-center justify-center bg-[#FF8300] h-12 duration-300">
                                <span
                                    class="flex items-center gap-1 text-sm md:text-base md:font-bold font-semibold text-white">Know
                                    More
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.75rem] h-[0.75rem]"
                                        viewBox="0 0 14 20" fill="none">
                                        <path
                                            d="M13.5294 9.84344L6.92754 19.6791H0L2.20534 16.4006L6.60187 9.84344L2.20534 3.29018L0 0H6.92754L13.5294 9.84344Z"
                                            fill="white"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
            </div>
        </div>


    </div>
</div>
<!-- End New Our Services Section -->

<!-- Features Section -->
<section
    class="w-full bg-white z-10 relative overflow-hidden py-12 bg-gradient-to-r h-full from-[#ffffff] to-[#e5e5e5]">
    <div class="view">
        <div class="flex flex-wrap items-center">
            <div class="md:w-[40%] w-full relative">
                <div class="flex flex-col">
                    <h2
                        class="text-[1.75rem] max-w-xl sm:text-4xl md:text-[3.125rem] font-bold text-black text-balance !leading-tight">
                        <?php echo esc_html($features_data['heading']); ?>
                    </h2>

                    <ul class="md:mt-14 mt-7 space-y-4">
                        <?php foreach ($features_data['features_list'] as $feature) : ?>
                        <li class="flex items-center gap-3 md:text-[1.188rem] text-sm font-semibold text-black">
                            <!-- check icon -->
                            <svg class="shrink-0 size-8" viewBox="0 0 32 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.6854 0.789007C15.9303 0.789007 16.1616 0.938649 16.2296 1.18352L17.005 3.46894C17.073 3.673 17.2635 3.82264 17.4811 3.84984C17.8484 3.90426 18.2021 3.95867 18.5558 4.04029C18.5966 4.04029 18.651 4.0539 18.6919 4.0539C18.8687 4.0539 19.0319 3.98588 19.1408 3.84984L20.6644 1.98614C20.7732 1.8501 20.9365 1.76848 21.1133 1.76848C21.1813 1.76848 21.2494 1.76847 21.3174 1.80929C21.984 2.06776 22.6233 2.38065 23.2491 2.72074C23.4668 2.84318 23.5756 3.08804 23.5212 3.33291L23.045 5.69995C23.0042 5.91761 23.0859 6.13526 23.2627 6.2713C23.5484 6.48896 23.8205 6.73383 24.0925 6.9787C24.2014 7.08753 24.351 7.14195 24.487 7.14195C24.5551 7.14195 24.6231 7.14194 24.6911 7.10113L26.9493 6.25771C26.9493 6.25771 27.0853 6.2169 27.1534 6.2169C27.3166 6.2169 27.4799 6.28491 27.5887 6.42094C28.0376 6.9787 28.4321 7.56366 28.7994 8.17582C28.9218 8.39348 28.8946 8.65196 28.7314 8.84241L27.1398 10.6517C26.9901 10.8149 26.9629 11.0462 27.0445 11.2503C27.1806 11.5904 27.303 11.9305 27.4118 12.2842C27.4799 12.5018 27.6567 12.6514 27.8744 12.6923L30.2414 13.0868C30.4863 13.1276 30.6767 13.318 30.7175 13.5629C30.7175 13.6173 30.7311 13.6717 30.7447 13.7125C30.84 14.3655 30.8808 15.0185 30.8944 15.6579C30.8944 15.9027 30.7447 16.134 30.4999 16.2156L28.2145 16.991C28.0104 17.0591 27.8608 17.2495 27.8335 17.4672C27.7791 17.8345 27.7247 18.1882 27.6431 18.5419C27.5887 18.7595 27.6703 18.9772 27.8335 19.1268L29.6973 20.6504C29.8877 20.8001 29.9557 21.0721 29.8741 21.2898C29.6156 21.9564 29.3027 22.5958 28.9627 23.2215C28.8674 23.3984 28.6634 23.5072 28.4729 23.5072C28.4321 23.5072 28.3913 23.5072 28.3641 23.5072L25.997 23.0311C25.997 23.0311 25.929 23.0311 25.8882 23.0311C25.7114 23.0311 25.5345 23.1127 25.4257 23.2623C25.208 23.548 24.9632 23.8201 24.7183 24.0922C24.5687 24.2554 24.5142 24.4867 24.5959 24.6907L25.4393 26.949C25.5209 27.1802 25.4529 27.4387 25.2624 27.5883C24.7047 28.0372 24.1197 28.4318 23.5076 28.7991C23.4259 28.8535 23.3171 28.8807 23.2219 28.8807C23.0859 28.8807 22.9498 28.8263 22.841 28.731L21.0317 27.1394C20.9229 27.0442 20.7868 27.0034 20.6508 27.0034C20.5692 27.0034 20.5011 27.017 20.4195 27.0442C20.0794 27.1802 19.7393 27.3026 19.3856 27.4115C19.168 27.4795 19.0183 27.6563 18.9775 27.874L18.583 30.241C18.5422 30.4859 18.3518 30.6764 18.1069 30.7172C18.0525 30.7172 17.9981 30.7308 17.9573 30.7444C17.3043 30.8396 16.6513 30.8804 16.0119 30.894C15.7671 30.894 15.5358 30.7444 15.4678 30.4995L14.6924 28.2141C14.6243 28.01 14.4339 27.8604 14.2162 27.8332C13.8489 27.7788 13.4952 27.7244 13.1415 27.6427C13.1007 27.6427 13.0463 27.6291 13.0055 27.6291C12.8287 27.6291 12.6654 27.6972 12.5566 27.8332L11.033 29.6969C10.9241 29.8329 10.7609 29.9146 10.584 29.9146C10.516 29.9146 10.448 29.9146 10.38 29.8738C9.7134 29.6153 9.07403 29.3024 8.44826 28.9623C8.2306 28.8399 8.12177 28.595 8.17618 28.3501L8.65231 25.9831C8.69312 25.7654 8.6115 25.5478 8.43466 25.4117C8.14898 25.1941 7.8769 24.9492 7.60483 24.7043C7.496 24.5955 7.34636 24.5411 7.21032 24.5411C7.1423 24.5411 7.07428 24.5411 7.00626 24.5819L4.74805 25.4253C4.74805 25.4253 4.61201 25.4661 4.54399 25.4661C4.38075 25.4661 4.2175 25.3981 4.10867 25.2621C3.65975 24.7043 3.26524 24.1194 2.89794 23.5072C2.77551 23.2896 2.80272 23.0311 2.96596 22.8406L4.5576 21.0313C4.70724 20.8681 4.73445 20.6368 4.65282 20.4328C4.51679 20.0927 4.39435 19.7526 4.28552 19.3989C4.2175 19.1812 4.04066 19.0316 3.823 18.9908L1.45595 18.5963C1.21108 18.5555 1.02063 18.365 0.97982 18.1201C0.97982 18.0657 0.966217 18.0113 0.952613 17.9705C0.857387 17.3175 0.816576 16.6645 0.802972 16.0252C0.802972 15.7803 0.952613 15.549 1.19748 15.4674L3.4829 14.692C3.68696 14.624 3.8366 14.4335 3.86381 14.2159C3.91822 13.8486 3.97264 13.4949 4.05426 13.1412C4.10867 12.9235 4.02705 12.7059 3.86381 12.5562L2.0001 11.0326C1.80965 10.883 1.74163 10.6109 1.82325 10.3932C2.08172 9.72665 2.39461 9.08727 2.7347 8.4615C2.82993 8.28465 3.03398 8.17582 3.22443 8.17582C3.26524 8.17582 3.30606 8.17582 3.33326 8.17582L5.70031 8.65195C5.70031 8.65195 5.76833 8.65195 5.80914 8.65195C5.98599 8.65195 6.16283 8.57033 6.27166 8.42069C6.48932 8.13501 6.73419 7.86294 6.97906 7.59086C7.1287 7.42762 7.18311 7.19636 7.10149 6.99231L6.25806 4.73409C6.17644 4.50282 6.24446 4.24435 6.43491 4.09471C6.99266 3.64579 7.57762 3.25128 8.18979 2.88398C8.27141 2.82957 8.38024 2.80236 8.47547 2.80236C8.6115 2.80236 8.74754 2.85678 8.85637 2.952L10.6657 4.54363C10.7745 4.63886 10.9105 4.67967 11.0466 4.67967C11.1282 4.67967 11.1962 4.66607 11.2778 4.63886C11.6179 4.50282 11.958 4.38039 12.3117 4.27156C12.5294 4.20355 12.679 4.02669 12.7198 3.80903L13.1143 1.44199C13.1551 1.19712 13.3456 1.00667 13.5905 0.965862C13.6449 0.965862 13.6993 0.952253 13.7401 0.938649C14.3931 0.843423 15.0461 0.802611 15.6854 0.789007ZM15.6854 0H15.6718C14.9916 0 14.2979 0.0680119 13.6177 0.163238L13.4544 0.190451C12.8695 0.285677 12.4069 0.734592 12.3117 1.31955L11.9444 3.56417C11.6587 3.65939 11.3595 3.75462 11.0874 3.87706L9.37331 2.38065C9.12844 2.16299 8.80196 2.04056 8.47547 2.04056C8.2306 2.04056 7.99934 2.10857 7.79528 2.23101C7.15591 2.61191 6.53014 3.03362 5.94518 3.50975C5.48265 3.87705 5.31941 4.48922 5.52346 5.04698L6.32608 7.18276C6.12202 7.41402 5.91797 7.64528 5.72752 7.87654L3.49651 7.42763C3.40128 7.41402 3.31966 7.40041 3.22443 7.40041C2.7347 7.40041 2.28578 7.65888 2.04091 8.0806C1.67361 8.73358 1.34712 9.41377 1.07505 10.1076C0.857387 10.6517 1.02063 11.2775 1.48316 11.6448L3.23804 13.0868C3.17002 13.386 3.1156 13.6853 3.07479 13.9846L0.925405 14.7192C0.367653 14.9097 -0.0132508 15.4402 0.000352944 16.0388C0.000352944 16.719 0.0683715 17.4127 0.163597 18.0929L0.190805 18.2562C0.286031 18.8411 0.734953 19.3037 1.31991 19.3989L3.56453 19.7662C3.65975 20.0519 3.75498 20.3511 3.87741 20.6232L2.381 22.3373C1.98649 22.7862 1.93208 23.4256 2.23136 23.9289C2.61227 24.5683 3.03398 25.1941 3.51011 25.779C3.76858 26.1055 4.14949 26.2824 4.5712 26.2824C4.73444 26.2824 4.89769 26.2552 5.04733 26.2007L7.18311 25.3981C7.41438 25.6022 7.64564 25.8062 7.8769 25.9831L7.42798 28.2141C7.31915 28.7991 7.57762 29.3704 8.09456 29.6697C8.74754 30.037 9.42773 30.3635 10.1215 30.6356C10.2712 30.69 10.448 30.7308 10.6112 30.7308C11.0194 30.7308 11.4003 30.5539 11.6587 30.2274L13.1007 28.4726C13.3728 28.5406 13.6857 28.595 13.9986 28.6358L14.7332 30.7852C14.9236 31.3429 15.4406 31.7103 16.0255 31.7103C16.7193 31.7103 17.4131 31.6422 18.0933 31.547L18.2565 31.5198C18.8415 31.4246 19.2904 30.9756 19.3992 30.3907L19.7665 28.1461C20.0522 28.0508 20.3515 27.9556 20.6236 27.8332L22.3376 29.3296C22.5825 29.5473 22.909 29.6697 23.2355 29.6697C23.4804 29.6697 23.7116 29.6017 23.9157 29.4792C24.5551 29.0983 25.1808 28.663 25.7658 28.2005C26.2283 27.8332 26.3916 27.221 26.1875 26.6633L25.3849 24.5275C25.5889 24.2962 25.793 24.065 25.9834 23.8337L28.2145 24.2826C28.3097 24.2962 28.3913 24.3098 28.4865 24.3098C28.9763 24.3098 29.4252 24.0514 29.67 23.6296C30.0373 22.9767 30.3638 22.2965 30.6359 21.6027C30.8536 21.0585 30.6903 20.4328 30.2278 20.0655L28.4729 18.6235C28.5409 18.3242 28.5954 18.0249 28.6362 17.7256L30.7856 16.991C31.3433 16.8006 31.7242 16.27 31.7106 15.6715C31.7106 14.9913 31.6426 14.2975 31.5474 13.6173L31.5202 13.4541C31.4249 12.8691 30.976 12.4066 30.391 12.3114L28.1464 11.9441C28.0512 11.6584 27.956 11.3591 27.8335 11.087L29.33 9.37296C29.7245 8.92403 29.7789 8.28465 29.4796 7.78131C29.0987 7.14194 28.677 6.51617 28.2008 5.93121C27.9424 5.60472 27.5615 5.42788 27.1398 5.42788C26.9765 5.42788 26.8133 5.45508 26.6636 5.5095L24.5278 6.31212C24.2966 6.10807 24.0653 5.90401 23.8341 5.72716L24.283 3.49615C24.3918 2.91119 24.1333 2.33984 23.6164 2.04056C22.9634 1.67326 22.2832 1.34676 21.5894 1.07468C21.4398 1.02027 21.263 0.979459 21.0997 0.979459C20.6916 0.979459 20.3107 1.15631 20.0522 1.4828L18.6102 3.23768C18.3382 3.16966 18.0253 3.11524 17.7124 3.07443L16.9778 0.925053C16.7873 0.367301 16.2704 0 15.6854 0Z"
                                    fill="url(#paint0_linear_3616_1849)" />
                                <path
                                    d="M17.2805 21.2357C16.3282 22.188 14.791 22.188 13.8388 21.2357L7.97557 15.3725C7.58106 14.978 7.58106 14.3386 7.97557 13.9441L9.98892 11.9308C10.3834 11.5363 11.0228 11.5363 11.4173 11.9308L15.5664 16.0799L27.1704 4.47593C27.5649 4.08143 28.2043 4.08143 28.5988 4.47593L30.6121 6.48929C31.0067 6.8838 31.0067 7.52317 30.6121 7.91768L17.2805 21.2493V21.2357Z"
                                    fill="black" />
                                <defs>
                                    <linearGradient id="paint0_linear_3616_1849" x1="31.711" y1="2.18689" x2="-0.702135"
                                        y2="2.97901" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#CB122D" />
                                        <stop offset="1" stop-color="#650916" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <?php echo esc_html($feature['feature_text']); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="md:w-[60%] relative w-full pl-0 md:pl-12 mt-10 md:mt-0 z-30">
                <div class="flex justify-center items-center">
                    <?php if (!empty($features_data['features_image']['url'])) : ?>
                    <img src="<?php echo esc_url($features_data['features_image']['url']); ?>"
                        alt="<?php echo esc_attr($features_data['features_image']['alt']); ?>"
                        class="size-full object-cover rounded-sm aspect-[]" />
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div
        class="absolute right-0 md:top-0 md:bottom-auto top-auto bottom-0 md:w-[41.188rem] w-[8.5rem] -z-10 transform origin-bottom md:h-full h-[18rem] -skew-x-[12deg] bg-gradient-to-r from-[#8b0f15] to-[#CB122D]">
    </div>
</section>

<!-- App Section -->
<section class="w-full taptrack bg-white pt-[6.313rem] md:pt-[3.938rem]">
    <div
        class="view relative before:absolute before:bottom-0 before:left-1/2 before:-translate-x-1/2 before:w-full before:h-28 before:bg-gradient-to-t before:from-[#FBFCFD] before:to-[#FBFCFD00]">
        <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-[4.5rem] md:gap-8">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex -space-x-3">
                        <?php foreach ($app_data['user_images'] as $user_image) : ?>
                        <?php if (!empty($user_image['user_image']['url'])) : ?>
                        <img src="<?php echo esc_url($user_image['user_image']['url']); ?>"
                            alt="<?php echo esc_attr($user_image['user_image']['alt']); ?>"
                            class="w-9 h-9 rounded-full z-30 relative object-cover">
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-sm text-gray-500"><?php echo esc_html($app_data['trusted_text']); ?></p>
                </div>

                <h2 class="text-[2.625rem] md:text-[4rem] font-bold md:font-medium text-[#141414] !leading-tight">
                    <span class="block"><?php echo esc_html($app_data['heading_line1']); ?></span>
                    <span class="block"><?php echo esc_html($app_data['heading_line2']); ?></span>
                    <span class="block"><?php echo esc_html($app_data['heading_line3']); ?></span>
                </h2>
                <p class="md:text-xl text-base font-normal text-[#14141499]">
                    <?php echo esc_html($app_data['description']); ?>
                </p>
                <div class="flex items-center gap-6 mt-3">
                    <?php if (!empty($app_data['app_store_badges']['play_store_image']['url'])) : ?>
                    <a href="<?php echo esc_url($app_data['app_store_badges']['play_store_link']); ?>">
                        <img class="md:h-[4rem] h-[3.125rem] aspect-[154/51]"
                            src="<?php echo esc_url($app_data['app_store_badges']['play_store_image']['url']); ?>"
                            alt="<?php echo esc_attr($app_data['app_store_badges']['play_store_image']['alt']); ?>">
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($app_data['app_store_badges']['app_store_image']['url'])) : ?>
                    <a href="<?php echo esc_url($app_data['app_store_badges']['app_store_link']); ?>">
                        <img class="md:h-[4rem] h-[3.125rem] aspect-[154/51]"
                            src="<?php echo esc_url($app_data['app_store_badges']['app_store_image']['url']); ?>"
                            alt="<?php echo esc_attr($app_data['app_store_badges']['app_store_image']['alt']); ?>">
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex justify-center">
                <div class="md:h-[43.688rem]">
                    <?php if (!empty($app_data['phone_image']['url'])) : ?>
                    <img src="<?php echo esc_url($app_data['phone_image']['url']); ?>"
                        alt="<?php echo esc_attr($app_data['phone_image']['alt']); ?>"
                        class="w-full h-full object-contain aspect-[422/559]">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="commonly bg-white relative w-full pt-[5.813rem] pb-20">
    <div class="view flex flex-col md:gap-y-12 gap-y-8" id="commonlyAccordion">
        <div class="w-full relative">
            <h2 class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] text-black font-bold">
                <?php echo esc_html($faq_data['section_heading']); ?>
            </h2>
            <div
                class="relative pt-3 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] md:after:h-3 after:h-[0.688rem] after:-skew-x-[18deg] after:left-0">
            </div>
        </div>

        <div class="w-full relative flex flex-col md:gap-y-16 gap-y-12">
            <div class="flex flex-col gap-6 md:gap-y-5 w-full">
                <div class="grid md:grid-cols-2 gap-4 md:gap-5">
                    <?php foreach ($faq_data['faq_items'] as $index => $faq) : ?>
                    <div class="accordion-item border border-black">
                        <button
                            class="commonly-header w-full px-6 py-4 flex justify-between items-center text-left font-semibold <?php echo $faq['is_active'] ? 'text-[#CB122D]' : 'text-gray-800'; ?>">
                            <span
                                class="md:text-xl text-base font-semibold"><?php echo esc_html($faq['question']); ?></span>
                            <span
                                class="shirnk-0 commonly-icon text-white bg-[#CB122D] size-6 flex items-center justify-center">
                                <?php echo $faq['is_active'] ? '−' : '+'; ?>
                            </span>
                        </button>
                        <div
                            class="commonly-body px-6 pb-4 pt-2 text-base md:text-sm text-[#010101] font-normal <?php echo $faq['is_active'] ? '' : 'hidden'; ?>">
                            <?php echo wp_kses_post(nl2br($faq['answer'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>


<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $ourServiceSettings = petromin_get_swiper_settings('ourServicesSectionSwiper');
    ?>
    const ourServiceCarouselSwiper = new Swiper(".ourServicesSectionSwiper", {
        speed: <?php echo esc_js($ourServiceSettings['speed']); ?>,
        autoplay: <?php echo $ourServiceSettings['autoplay'] ? '{
            delay: ' . esc_js($ourServiceSettings['delay']) . ',
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        }' : 'false'; ?>,
        spaceBetween: 30,
        loop: true,
        // autoHeight: true,

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
                slidesPerView: 1.3,
            },
            480: {
                slidesPerView: 3.3,
            },
            640: {
                slidesPerView: 3.5,
            },
            1024: {
                slidesPerView: 3.8,
            },
            1350: {
                slidesPerView: 3.9,
            },
        },
    });
});
</script>

<script>
const headers = document.querySelectorAll('#commonlyAccordion .commonly-header');

headers.forEach(header => {
    header.addEventListener('click', () => {
        const item = header.parentElement;
        const body = item.querySelector('.commonly-body');
        const icon = header.querySelector('.commonly-icon');

        const isActive = !body.classList.contains('hidden');

        // Close all
        document.querySelectorAll('#commonlyAccordion .commonly-body').forEach(el => el.classList.add(
            'hidden'));
        document.querySelectorAll('#commonlyAccordion .commonly-icon').forEach(el => el.textContent =
            '+');
        document.querySelectorAll('#commonlyAccordion .commonly-header').forEach(el => {
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