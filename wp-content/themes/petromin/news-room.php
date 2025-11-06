<?php
/* Template Name: news room page */
get_header();

$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$images_url = $assets_url . '/img';

$hero_defaults = [
    'title' => 'Petromin Express, on the go.',
    'image' => [
        'url' => $images_url . '/newsroom_hero_img.webp',
        'alt' => 'Petromin Express, on the go.',
    ],
];

$sidebar_defaults = [
    'about_text' => 'Petromin Express newsroom features the latest media mentions, press releases, and company updates.',
    'categories' => [
        ['name' => 'Media Mentions', 'slug' => 'media-mentions'],
        ['name' => 'Press Releases', 'slug' => 'press-releases'],
        ['name' => 'Featured', 'slug' => 'featured'],
        ['name' => 'Events', 'slug' => 'events'],
        ['name' => 'Podcasts', 'slug' => 'podcasts'],
    ]
];

$media_mentions_defaults = [
    [
        'title' => 'Petromin Express Revolutionizes Car Care Industry',
        'source' => 'Times of India',
        'date' => 'August 20, 2025',
        'image' => [
            'url' => $images_url . '/media_mention_img.webp',
            'alt' => 'Petromin Express Revolutionizes Car Care Industry',
        ],
        'link' => '#'
    ]
];

$press_releases_defaults = [
    [
        'title' => 'Petromin Express Announces Expansion into New Markets',
        'description' => 'Strategic growth initiative to serve more customers across the region with premium automotive services.',
        'date' => 'August 20, 2025',
        'pdf_link' => '#'
    ]
];

$featured_defaults = [
    [
        'title' => 'Petromin Express Revolutionizes Car Care Industry',
        'source' => 'Times of India',
        'date' => 'August 20, 2025',
        'image' => [
            'url' => $images_url . '/media_mention_img.webp',
            'alt' => 'Petromin Express Revolutionizes Car Care Industry',
        ],
        'link' => '#'
    ]
];

$events_defaults = [
    [
        'title' => 'Petromin Express Revolutionizes Car Care Industry',
        'description' => 'Join industry leaders and partners at our flagship event! Don\'t miss out—register today to secure your seat and be part of this exciting opportunity.',
        'location' => 'Hyderabad, India',
        'date' => 'August 20, 2025',
        'image' => [
            'url' => $images_url . '/event_cover_img.webp',
            'alt' => 'Petromin Express Revolutionizes Car Care Industry',
        ],
        'link' => '#'
    ]
];

$podcasts_defaults = [
    [
        'title' => 'Mr. Ashnut Chopra in the The Founder\'s Dream Podcast',
        'description' => 'In a recent podcast, Mr. Ashnut shares why car servicing isn\'t an expense it\'s an investment in your vehicle\'s performance and safety.',
        'video_url' => 'https://www.youtube.com/embed/VGFpV3Qj4as',
        'link' => '#'
    ]
];

// Get ACF Fields
$hero_field = function_exists('get_field') ? (get_field('hero_section') ?: []) : [];
$hero_title = trim($hero_field['hero_title'] ?? '') ?: $hero_defaults['title'];
$hero_image_data = petromin_get_acf_image_data($hero_field['hero_image'] ?? null, 'full', $hero_defaults['image']['url'], $hero_defaults['image']['alt']);
$hero_image_title = trim($hero_field['hero_image_title'] ?? '') ?: ($hero_image_data['alt'] ?? $hero_defaults['image']['alt']);

$sidebar_field = function_exists('get_field') ? (get_field('sidebar_section') ?: []) : [];
$sidebar_about_text = trim($sidebar_field['about_text'] ?? '') ?: $sidebar_defaults['about_text'];
$sidebar_categories = !empty($sidebar_field['categories']) && is_array($sidebar_field['categories']) ? $sidebar_field['categories'] : $sidebar_defaults['categories'];

$media_mentions_field = function_exists('get_field') ? (get_field('media_mentions_section') ?: []) : [];
$media_mentions_heading = trim($media_mentions_field['section_heading'] ?? '') ?: 'Media Mentions';
$media_mentions_items = !empty($media_mentions_field['items']) && is_array($media_mentions_field['items']) ? $media_mentions_field['items'] : array_fill(0, 6, $media_mentions_defaults[0]);

$press_releases_field = function_exists('get_field') ? (get_field('press_releases_section') ?: []) : [];
$press_releases_heading = trim($press_releases_field['section_heading'] ?? '') ?: 'Press Releases';
$press_releases_items = !empty($press_releases_field['items']) && is_array($press_releases_field['items']) ? $press_releases_field['items'] : array_fill(0, 3, $press_releases_defaults[0]);

$featured_field = function_exists('get_field') ? (get_field('featured_section') ?: []) : [];
$featured_heading = trim($featured_field['section_heading'] ?? '') ?: 'Featured';
$featured_items = !empty($featured_field['items']) && is_array($featured_field['items']) ? $featured_field['items'] : array_fill(0, 6, $featured_defaults[0]);

$events_field = function_exists('get_field') ? (get_field('events_section') ?: []) : [];
$events_heading = trim($events_field['section_heading'] ?? '') ?: 'Events';
$events_items = !empty($events_field['items']) && is_array($events_field['items']) ? $events_field['items'] : array_fill(0, 3, $events_defaults[0]);

$podcasts_field = function_exists('get_field') ? (get_field('podcasts_section') ?: []) : [];
$podcasts_heading = trim($podcasts_field['section_heading'] ?? '') ?: 'Podcasts';
$podcasts_items = !empty($podcasts_field['items']) && is_array($podcasts_field['items']) ? $podcasts_field['items'] : array_fill(0, 2, $podcasts_defaults[0]);

// Process items with fallbacks
function process_news_items($items, $defaults) {
    $processed = [];
    foreach ($items as $item) {
        $fallback = is_array($defaults) ? $defaults[0] : $defaults;
        
        $title = trim($item['title'] ?? '') ?: ($fallback['title'] ?? '');
        $description = trim($item['description'] ?? '') ?: ($fallback['description'] ?? '');
        $source = trim($item['source'] ?? '') ?: ($fallback['source'] ?? '');
        $date = trim($item['date'] ?? '') ?: ($fallback['date'] ?? '');
        $location = trim($item['location'] ?? '') ?: ($fallback['location'] ?? '');
        $link = trim($item['link'] ?? '') ?: ($fallback['link'] ?? '');
        $pdf_link = trim($item['pdf_link'] ?? '') ?: ($fallback['pdf_link'] ?? '');
        $video_url = trim($item['video_url'] ?? '') ?: ($fallback['video_url'] ?? '');
        
        // Handle image
        $image_default = $fallback['image'] ?? [];
        $image = petromin_get_acf_image_data($item['image'] ?? null, 'full', $image_default['url'] ?? '', $image_default['alt'] ?? $title);
        
        $processed[] = [
            'title' => $title,
            'description' => $description,
            'source' => $source,
            'date' => $date,
            'location' => $location,
            'link' => $link,
            'pdf_link' => $pdf_link,
            'video_url' => $video_url,
            'image' => $image,
        ];
    }
    return $processed;
}

$media_mentions_processed = process_news_items($media_mentions_items, $media_mentions_defaults);
$featured_processed = process_news_items($featured_items, $featured_defaults);
$events_processed = process_news_items($events_items, $events_defaults);
$podcasts_processed = process_news_items($podcasts_items, $podcasts_defaults);

// For press releases (different structure)
$press_releases_processed = [];
foreach ($press_releases_items as $item) {
    $fallback = $press_releases_defaults[0];
    
    $title = trim($item['title'] ?? '') ?: $fallback['title'];
    $description = trim($item['description'] ?? '') ?: $fallback['description'];
    $date = trim($item['date'] ?? '') ?: $fallback['date'];
    $pdf_link = trim($item['pdf_link'] ?? '') ?: $fallback['pdf_link'];
    
    $press_releases_processed[] = [
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'pdf_link' => $pdf_link,
    ];
}
?>

<div class="hero_section w-full relative z-0 md:h-dvh h-[23rem]">
    <div class="relative w-full h-full overflow-hidden">
        <?php if (!empty($hero_image_data)) : ?>
            <img fetchpriority="high" decoding="async" loading="eager" src="<?php echo esc_url($hero_image_data['url']); ?>"
                class="size-full object-cover" alt="<?php echo esc_attr($hero_image_data['alt']); ?>"
                title="<?php echo esc_attr($hero_image_title); ?>">
        <?php endif; ?>

        <div
            class="lg:w-[46.438rem] md:w-[32rem] w-[24.125rem] absolute lg:bottom-32 md:bottom-24 bottom-8 left-0 flex lg:py-8 py-5 lg:px-8 md:px-4 px-4 bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)]  origin-top -skew-x-[18deg]">
            <div class="view flex items-center justify-center skew-x-[18deg] pr-0">
                <h1
                    class="xl:text-6xl lg:text-5xl md:text-4xl sm:text-3xl text-3xl text-white font-bold text-balance lg:!leading-[4.5rem]">
                    <?php echo esc_html($hero_title); ?></h1>
            </div>
        </div>
    </div>
</div>

<section class="bg-white h-full md:py-16 py-8">
    <div class="view w-full flex flex-wrap justify-between">
        <!-- Sidebar -->
        <div class="md:w-1/5 w-full">
            <div class="flex flex-col gap-y-8 sticky top-20">
                <div class="w-full flex flex-col gap-y-4">
                    <div
                        class="relative uppercase text-sm text-[#121212] font-bold border-b-2 border-[#E0E5EB] pb-2 md:pb-2.5">
                        About
                    </div>
                    <p class="text-[#637083] text-sm  font-normal"><?php echo esc_html($sidebar_about_text); ?></p>
                </div>
                <div class="w-full md:flex flex-col gap-y-4  hidden">
                    <div
                        class="relative uppercase text-sm text-[#121212] font-bold border-b-2 border-[#E0E5EB] pb-2.5 ">
                        Categories
                    </div>
                    <ul class="text-sm font-normal flex flex-col gap-4 " id="categoryNav">
                        <?php foreach ($sidebar_categories as $index => $category) : 
                            $name = $category['name'] ?? '';
                            $slug = $category['slug'] ?? sanitize_title($name);
                        ?>
                            <li class="text-[#637083] category-nav-item" data-target="<?php echo esc_attr($slug); ?>">
                                <?php echo esc_html($name); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mobile Category Slider -->
        <div class="w-full relative mt-8 mb-8 pb-4 border-b border-[#E0E5EB] md:hidden block">
            <div class="flex items-center gap-3 group/cat">
                <!-- Prev Button -->
                <div
                    class="swiper-prev cursor-pointer transition-opacity duration-300">
                    <span
                        class="border border-[#E0E5EB] bg-white shadow-[0_0_7px_0_#c5c2c2] size-7 rounded-full flex justify-center items-center">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24"
                            width="16px" height="16px" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.28 5.22a.75.75 0 0 1 0 1.06L9.56 12l5.72 5.72a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.75.75 0 0 1 1.06 0Z">
                            </path>
                        </svg>
                    </span>
                </div>

                <!-- Slider -->
                <div class="swiper category-slider relative">
                    <div class="swiper-wrapper">
                        <?php foreach ($sidebar_categories as $category) : 
                            $name = $category['name'] ?? '';
                            $slug = $category['slug'] ?? sanitize_title($name);
                        ?>
                            <div class="swiper-slide !w-auto">
                                <div class="text-sm text-[#637083] mobile-category-nav" data-target="<?php echo esc_attr($slug); ?>">
                                    <?php echo esc_html($name); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Next Button -->
                <div class="swiper-next cursor-pointer">
                    <span
                        class="border border-[#E0E5EB] bg-white shadow-[0_0_7px_0_#c5c2c2] size-7 rounded-full flex justify-center items-center">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24"
                            height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.72 18.78a.75.75 0 0 1 0-1.06L14.44 12 8.72 6.28a.751.751 0 0 1 .018-1.042.751.751 0 0 1 1.042-.018l6.25 6.25a.75.75 0 0 1 0 1.06l-6.25 6.25a.75.75 0 0 1-1.06 0Z">
                            </path>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:w-4/5 w-full h-full md:pl-20">
            <div class="w-full flex flex-col md:gap-y-16 gap-y-12">

                <!-- Media Mentions Section -->
                <section id="media-mentions" class="w-full relative flex flex-col gap-y-8 md:gap-y-6 news-section">
                    <h2 class="lg:text-4xl md:text-3xl text-2xl font-bold text-[#121212] border-b-2 border-[#E0E5EB] group-hover:lg:text-[#D4111E] pb-4">
                        <?php echo esc_html($media_mentions_heading); ?>
                    </h2>

                    <div class="grid md:grid-cols-3 grid-cols-1 gap-y-8 md:gap-x-[1.938rem] md:gap-y-[1.563rem]">
                        <?php foreach ($media_mentions_processed as $item) : ?>
                            <div class="relative w-full flex flex-col gap-y-4 md:gap-y-3 group">
                                <?php if (!empty($item['image']['url'])) : ?>
                                    <div class="w-full relative overflow-hidden duration-300">
                                        <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($item['image']['url']); ?>"
                                            class="size-full group-hover:lg:scale-125 duration-300" 
                                            alt="<?php echo esc_attr($item['image']['alt']); ?>" 
                                            title="<?php echo esc_attr($item['image']['alt']); ?>">
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="md:text-lg leading-7 text-base font-semibold text-[#121212] group-hover:lg:text-[#D4111E] duration-300">
                                    <?php echo esc_html($item['title']); ?>
                                </h3>
                                
                                <div class="flex justify-between items-center gap-2 w-full border-b-2 border-[#E0E5EB] pb-3 group-hover:lg:border-[#D4111E] duration-300">
                                    <span class="text-[#637083] text-sm font-medium"><?php echo esc_html($item['source']); ?></span>
                                    <span class="text-[#637083] text-sm font-medium"><?php echo esc_html($item['date']); ?></span>
                                </div>
                                
                                <?php if (!empty($item['link'])) : ?>
                                    <a href="<?php echo esc_url($item['link']); ?>" aria-label="<?php echo esc_attr($item['title']); ?>" class="absolute inset-0"></a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Press Releases Section -->
                <section id="press-releases" class="w-full relative flex flex-col gap-y-8 news-section">
                    <h2 class="lg:text-4xl md:text-3xl text-2xl font-bold text-[#121212] border-b-2 border-[#E0E5EB] pb-4">
                        <?php echo esc_html($press_releases_heading); ?>
                    </h2>
                    
                    <div class="w-full flex flex-col gap-y-8">
                        <?php foreach ($press_releases_processed as $item) : ?>
                            <div class="w-full border-b-2 border-[#E0E5EB] pb-11 md:pb-6">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex flex-col gap-y-3">
                                        <h3 class="lg:text-xl text-lg font-semibold text-[#121212]">
                                            <?php echo esc_html($item['title']); ?>
                                        </h3>
                                        <p class="text-[#637083] text-sm font-normal"><?php echo esc_html($item['description']); ?></p>
                                        <div class="flex justify-between items-center pt-1">
                                            <?php if (!empty($item['pdf_link'])) : ?>
                                                <a href="<?php echo esc_url($item['pdf_link']); ?>" 
                                                   class="text-[#D4111E] text-sm font-medium flex items-center gap-1"
                                                   aria-label="download pdf">
                                                    <span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                            <path d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                                                stroke="#D4111E" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M4.66602 6.66675L7.99935 10.0001L11.3327 6.66675"
                                                                stroke="#D4111E" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M8 10V2" stroke="#D4111E" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                    Download PDF
                                                </a>
                                            <?php endif; ?>
                                            <div class="text-[#637083] text-sm font-normal md:hidden block">
                                                <?php echo esc_html($item['date']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-[#637083] text-sm font-normal md:block hidden">
                                        <?php echo esc_html($item['date']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Featured Section -->
                <section id="featured" class="w-full relative flex flex-col gap-y-8 md:gap-y-6 news-section">
                    <h2 class="lg:text-4xl md:text-3xl text-2xl font-bold text-[#121212] border-b-2 border-[#E0E5EB] pb-4">
                        <?php echo esc_html($featured_heading); ?>
                    </h2>

                    <div class="grid md:grid-cols-3 grid-cols-1 md:gap-x-[1.938rem] gap-y-8 md:gap-y-[1.563rem]">
                        <?php foreach ($featured_processed as $item) : ?>
                            <div class="relative w-full flex flex-col gap-y-3 group">
                                <?php if (!empty($item['image']['url'])) : ?>
                                    <div class="w-full relative overflow-hidden duration-300">
                                        <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($item['image']['url']); ?>"
                                            class="size-full group-hover:lg:scale-125 duration-300" 
                                            alt="<?php echo esc_attr($item['image']['alt']); ?>" 
                                            title="<?php echo esc_attr($item['image']['alt']); ?>">
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="md:text-lg leading-7 text-base font-semibold text-[#121212] group-hover:lg:text-[#D4111E] duration-300">
                                    <?php echo esc_html($item['title']); ?>
                                </h3>
                                
                                <div class="flex justify-between items-center gap-2 w-full border-b-2 border-[#E0E5EB] pb-3 group-hover:lg:border-[#D4111E] duration-300">
                                    <span class="text-[#637083] text-sm font-medium"><?php echo esc_html($item['source']); ?></span>
                                    <span class="text-[#637083] text-sm font-medium"><?php echo esc_html($item['date']); ?></span>
                                </div>
                                
                                <?php if (!empty($item['link'])) : ?>
                                    <a href="<?php echo esc_url($item['link']); ?>" aria-label="<?php echo esc_attr($item['title']); ?>" class="absolute inset-0"></a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Events Section -->
                <section id="events" class="w-full relative flex flex-col gap-y-8 md:gap-y-6 md:pt-0 pt-4 news-section">
                    <h2 class="lg:text-4xl md:text-3xl text-2xl font-bold text-[#121212] border-b-2 border-[#E0E5EB] pb-4">
                        <?php echo esc_html($events_heading); ?>
                    </h2>

                    <div class="grid md:grid-cols-3 grid-cols-1 gap-y-8 md:gap-x-[1.938rem] md:gap-y-[1.563rem]">
                        <?php foreach ($events_processed as $item) : ?>
                            <div class="relative w-full flex flex-col gap-y-3 group duration-300">
                                <?php if (!empty($item['image']['url'])) : ?>
                                    <div class="w-full relative overflow-hidden duration-300">
                                        <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($item['image']['url']); ?>"
                                            class="group-hover:lg:scale-125 duration-300" 
                                            alt="<?php echo esc_attr($item['image']['alt']); ?>" 
                                            title="<?php echo esc_attr($item['image']['alt']); ?>">
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="md:text-lg leading-7 text-base font-semibold text-[#121212] group-hover:lg:text-[#D4111E] duration-300">
                                    <?php echo esc_html($item['title']); ?>
                                </h3>
                                
                                <p class="text-[#637083] text-sm font-normal"><?php echo esc_html($item['description']); ?></p>
                                
                                <div class="flex justify-between items-center gap-2 w-full border-b-2 border-[#E0E5EB] group-hover:lg:border-[#D4111E] duration-300 pb-3">
                                    <div class="text-[#637083] text-sm font-medium flex items-center gap-1">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="md:size-4" viewBox="0 0 16 16" fill="none">
                                                <path d="M13.3327 6.66683C13.3327 9.9955 9.64002 13.4622 8.40002 14.5328C8.2845 14.6197 8.14388 14.6667 7.99935 14.6667C7.85482 14.6667 7.7142 14.6197 7.59868 14.5328C6.35868 13.4622 2.66602 9.9955 2.66602 6.66683C2.66602 5.25234 3.22792 3.89579 4.22811 2.89559C5.22831 1.8954 6.58486 1.3335 7.99935 1.3335C9.41384 1.3335 10.7704 1.8954 11.7706 2.89559C12.7708 3.89579 13.3327 5.25234 13.3327 6.66683Z"
                                                    stroke="#637083" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 8.66675C9.10457 8.66675 10 7.77132 10 6.66675C10 5.56218 9.10457 4.66675 8 4.66675C6.89543 4.66675 6 5.56218 6 6.66675C6 7.77132 6.89543 8.66675 8 8.66675Z"
                                                    stroke="#637083" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        <?php echo esc_html($item['location']); ?>
                                    </div>
                                    <div class="text-[#637083] text-sm font-medium"><?php echo esc_html($item['date']); ?></div>
                                </div>
                                
                                <?php if (!empty($item['link'])) : ?>
                                    <a href="<?php echo esc_url($item['link']); ?>" class="cursor-pointer absolute inset-0" aria-label="<?php echo esc_attr($item['title']); ?>"></a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Podcasts Section -->
                <section id="podcasts" class="w-full relative flex flex-col gap-y-8 md:gap-y-6 news-section">
                    <h2 class="lg:text-4xl md:text-3xl text-2xl font-bold text-[#121212] border-b-2 border-[#E0E5EB] pb-4 md:pb-3">
                        <?php echo esc_html($podcasts_heading); ?>
                    </h2>

                    <div class="grid md:grid-cols-2 grid-cols-1 gap-6 md:gap-[1.625rem]">
                        <?php foreach ($podcasts_processed as $item) : ?>
                            <div class="relative w-full flex flex-col gap-y-3 group duration-300">
                                <div class="w-full relative overflow-hidden duration-300">
                                    <!-- Video -->
                                    <?php if (!empty($item['video_url'])) : ?>
                                        <iframe class="w-full" 
                                            src="<?php echo esc_url($item['video_url']); ?>?rel=0&modestbranding=1&controls=0&showinfo=0&enablejsapi=1"
                                            title="<?php echo esc_attr($item['title']); ?>" frameborder="0" height="269"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                                        </iframe>

                                        <!-- Custom Play Button Overlay -->
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 cursor-pointer transition-opacity hover:bg-opacity-50">
                                            <svg width="80" height="70" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <!-- Rounded Rectangle -->
                                                <rect x="2" y="2" width="116" height="76" rx="12" fill="#FF0000" stroke="#FF0000" stroke-width="3" />
                                                <!-- Play Triangle -->
                                                <path d="M48 28L48 52L68 40L48 28Z" fill="white" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="md:text-lg text-base font-semibold text-[#121212] group-hover:lg:text-[#D4111E] duration-300">
                                    <?php echo esc_html($item['title']); ?>
                                </h3>
                                
                                <p class="text-[#637083] text-sm font-normal"><?php echo esc_html($item['description']); ?></p>

                                <?php if (!empty($item['link'])) : ?>
                                    <a href="<?php echo esc_url($item['link']); ?>" class="cursor-pointer absolute inset-0" aria-label="<?php echo esc_attr($item['title']); ?>"></a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Navigation Active State
    const sections = document.querySelectorAll('.news-section');
    const navItems = document.querySelectorAll('.category-nav-item');
    const mobileNavItems = document.querySelectorAll('.mobile-category-nav');
    
    function setActiveCategory() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.clientHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                current = sectionId;
            }
        });

        // Update desktop nav
        navItems.forEach(item => {
            item.classList.remove('text-[#CB122D]');
            if (item.getAttribute('data-target') === current) {
                item.classList.add('text-[#CB122D]');
            }
        });

        // Update mobile nav
        mobileNavItems.forEach(item => {
            item.classList.remove('text-[#CB122D]');
            if (item.getAttribute('data-target') === current) {
                item.classList.add('text-[#CB122D]');
            }
        });
    }

    window.addEventListener('scroll', setActiveCategory);
    setActiveCategory(); // Initial call

    // Mobile category slider navigation
    mobileNavItems.forEach(item => {
        item.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                const offsetTop = targetSection.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Desktop category navigation
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                const offsetTop = targetSection.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Initialize Swiper for mobile category slider
    if (typeof Swiper !== 'undefined') {
        const categorySwiper = new Swiper('.category-slider', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-next',
                prevEl: '.swiper-prev',
            },
        });
    }
});
</script>

<?php get_footer(); ?>