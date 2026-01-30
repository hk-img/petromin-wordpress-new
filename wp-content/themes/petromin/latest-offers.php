<?php
/* Template Name: offers page */
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
        'heading' => 'Latest Offers',
    ],
    'intro' => [
        'heading' => 'Save more',
        'title' => 'on professional, trusted car care',
        'description' => 'Get the best deals on car servicing, repairs, and maintenance. Limited-time discounts available now!',
    ],
    'journey' => [
        'headingPrefix' => 'Your car\'s',
        'headingHighlight' => ' journey',
        'headingSuffix' => ' at Petromin Express',
        'description' => 'Here\'s how we ensure your car is safer, stronger and ready for the road.',
        'items' => [
            ['number' => '01', 'title' => 'Comprehensive inspection'],
            ['number' => '02', 'title' => 'Transparent diagnosis'],
            ['number' => '03', 'title' => 'Expert servicing'],
            ['number' => '04', 'title' => 'Quality assurance'],
            ['number' => '05', 'title' => 'On time delivery & support'],
        ],
    ],
    'app' => [
        'heading_line1' => 'Car care,',
        'heading_line2' => 'now smarter',
        'description' => 'Download the PETROMINIt! app to book car services, track your vehicle\'s health, and access exclusive offers – all from your phone.',
        'image' => [
            'url' => $images_url . '/latest_carcare.webp',
            'alt' => 'App Section',
        ],
    ],
];

// Get ACF data with fallbacks
$hero_bg = petromin_get_acf_image_data(get_field('offers_hero_background'), 'full', $defaults['hero']['background_image']['url'], $defaults['hero']['background_image']['alt']);
if (!$hero_bg) $hero_bg = $defaults['hero']['background_image'];

$hero_heading = get_field('offers_hero_heading') ?: $defaults['hero']['heading'];

$intro_heading = get_field('offers_intro_heading') ?: $defaults['intro']['heading'];
$intro_title = get_field('offers_intro_title') ?: $defaults['intro']['title'];
$intro_description = get_field('offers_intro_description') ?: $defaults['intro']['description'];

$journey_heading_prefix = get_field('journey_section_heading_prefix') ?: $defaults['journey']['headingPrefix'];
$journey_heading_highlight = get_field('journey_section_heading_highlight') ?: $defaults['journey']['headingHighlight'];
$journey_heading_suffix = get_field('journey_section_heading_suffix') ?: $defaults['journey']['headingSuffix'];
$journey_description = get_field('journey_section_description') ?: $defaults['journey']['description'];

$journey_items_field = get_field('journey_items');
$journey_items = [];

if (!empty($journey_items_field) && is_array($journey_items_field)) {
    foreach ($journey_items_field as $index => $item) {
        $number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        $title = $item['journey_title'] ?? '';
        $image_data = petromin_get_acf_image_data($item['journey_image'] ?? null, 'full', '', $title ?: 'Journey Step');
        
        if (!empty($title)) {
            $journey_items[] = [
                'number' => $number,
                'title' => $title,
                'image' => $image_data ?: ['url' => '', 'alt' => ''],
            ];
        }
    }
}

// Use defaults if no items
if (empty($journey_items)) {
    $journey_items = $defaults['journey']['items'];
}

$app_heading_line1 = get_field('app_section_heading_line1') ?: $defaults['app']['heading_line1'];
$app_heading_line2 = get_field('app_section_heading_line2') ?: $defaults['app']['heading_line2'];
$app_description = get_field('app_section_description') ?: $defaults['app']['description'];
$app_image = petromin_get_acf_image_data(get_field('app_section_image'), 'full', $defaults['app']['image']['url'], $defaults['app']['image']['alt']);
if (!$app_image) $app_image = $defaults['app']['image'];

$app_contact_placeholder = get_field('app_contact_placeholder') ?: 'Enter Contact Number';
$app_button_text = get_field('app_button_text') ?: 'Get App Link';
$app_google_link = get_field('app_google_link') ?: '';
$app_apple_link = get_field('app_apple_link') ?: '';

$app_google_image = petromin_get_acf_image_data(get_field('app_google_image'), 'full', $assets_url . '/img/serviceGoogle.webp', 'Google Play Store');
if (!$app_google_image) $app_google_image = ['url' => $assets_url . '/img/serviceGoogle.webp', 'alt' => 'Google Play Store'];

$app_apple_image = petromin_get_acf_image_data(get_field('app_apple_image'), 'full', $assets_url . '/img/serviceApple.webp', 'Apple App Store');
if (!$app_apple_image) $app_apple_image = ['url' => $assets_url . '/img/serviceApple.webp', 'alt' => 'Apple App Store'];

?>

    <div class="hero_section w-full relative z-0 md:h-[30rem] h-[16rem]">
        <div class="relative w-full h-full overflow-hidden after:absolute after:inset-0 after:bg-[linear-gradient(180deg,_#00000000_0%,_#000000d6_100%)] after:z-0 *:z-10">
            <img fetchpriority="high" decoding="async" loading="eager" src="<?php echo esc_url($hero_bg['url']); ?>"
                class="size-full object-cover aspect-[1279/334]" width="1279" height="334"
                alt="<?php echo esc_attr($hero_bg['alt']); ?>" title="<?php echo esc_attr($hero_bg['alt']); ?>">

            <div
                class="lg:w-[40.625rem] md:w-[32rem] w-[19.563rem] absolute md:bottom-24 bottom-20 left-0 flex lg:py-8 py-3.5 lg:px-8 md:px-4 px-4 bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)]  origin-top -skew-x-[18deg]">
                <div class="  flex items-center justify-center skew-x-[18deg] md:pl-20 pl-4">
                    <h1
                        class="xl:text-6xl lg:text-5xl md:text-4xl sm:text-3xl text-3xl text-white font-bold text-balance lg:!leading-[4.5rem]">
                        <?php echo esc_html($hero_heading); ?></h1>
                </div>
            </div>
        </div>
    </div>

    <?php
    $offers = petromin_get_offers([
        'posts_per_page' => 10,
        'order' => 'DESC'
    ]);

    if (!empty($offers)):
    ?>
    <section class="bg-white relative md:pt-[4.875rem] overflow-hidden pt-9">
        <div class="view flex flex-col md:pr-0">
            <div class="flex items-end justify-between">
                <div class="flex flex-col gap-y-4">
                    <div class="w-full relative mb-3">
                        <h2
                            class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-2xl text-black font-bold leading-tight">
                            <span class="text-[#CB122D] md:block"><?php echo esc_html($intro_heading); ?></span>
                            <?php echo esc_html($intro_title); ?>
                        </h2>
                        <div
                            class="relative md:pt-4 pt-1.5 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] md:after:h-3 after:h-[0.688rem] after:-skew-x-[18deg] after:left-0">
                        </div>
                    </div>
                    <p class="md:font-medium font-normal text-base md:text-lg text-black">
                        <?php echo esc_html($intro_description); ?>
                    </p>
                </div>
                <div
                    class="flex items-center justify-start md:gap-2 origin-bottom z-20 bg-[#CB122D] px-4 shadow-[-0.375rem_0.375rem_0_-0.0625rem_rgba(0,0,0,0.9)] md:w-56 w-32 md:h-16 h-10 transition transform -skew-x-12 duration-150 ease-in-out md:-mr-[0.506rem] -mr-7 shrink-0 mb-2">
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
        <div class="w-full relative py-10">
            <div class="swiper latestOfferSwiper relative z-0 py-5 md:py-10 font-inter">
                <div class="swiper-wrapper  md:py-10">
                    <?php
                    $offers = petromin_get_offers([
                        'posts_per_page' => 10,
                        'order' => 'DESC'
                    ]);

                    if (!empty($offers)):
                        foreach ($offers as $offer):
                    ?>
                    <div class="swiper-slide !h-auto transform transition-transform duration-500 ease-out will-change-transform scale-95 blur-[0.1rem] [&.swiper-slide-active]:scale-110 [&.swiper-slide-active]:blur-[0] [&.swiper-slide-active]:z-30 md:max-w-[33vw]">
                        <a href="<?php echo esc_url($offer['url']); ?>" class="w-full block">
                            <div class="w-full h-full bg-gradient-to-l from-[#CB122D] to-[#650916] p-2 relative overflow-hidden group duration-500">
                                <img fetchpriority="low" loading="lazy" 
                                    src="<?php echo esc_url($offer['image']['url']); ?>" 
                                    width="334" height="334" 
                                    alt="<?php echo esc_attr($offer['image']['alt'] ?: $offer['title']); ?>" 
                                    title="<?php echo esc_attr($offer['image']['alt'] ?: $offer['title']); ?>"
                                    class="w-full h-full object-cover aspect-square" />
                                <div class="w-full flex flex-row justify-between items-center gap-2 py-4">
                                    <p class="text-white md:font-bold md:text-base text-[0.65rem] line-clamp-2">
                                        <?php echo esc_html($offer['short_description'] ?: $offer['title']); ?>
                                    </p>
                                    <div class="shrink-0">
                                        <button class="md:px-5 px-2 flex space-x-3 items-center bg-[#FF8300] md:h-12 h-7">
                                            <span class="flex items-center gap-1 md:text-base text-[0.6rem] md:font-bold font-semibold text-white">
                                                <?php echo esc_html($offer['button_text'] ?: 'Learn more'); ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.75rem] h-[0.75rem]" viewBox="0 0 14 20" fill="none">
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
                            <p class="text-gray-600">No offers available at the moment</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="bg-white h-full md:pt-24 md:pb-[7.5rem] pb-[4rem]">
        <div class="view w-full flex flex-wrap md:gap-0 gap-14 md:justify-between">
            <div class="md:w-2/5 w-full">
                <div class="flex flex-col gap-y-3 md:sticky md:top-20">
                    <div class="w-full relative mb-3">
                        <h2
                            class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-2xl text-black font-bold leading-tight">

                            <?php echo esc_html($journey_heading_prefix); ?> <span class="text-[#CB122D]"><?php echo esc_html($journey_heading_highlight); ?></span> <?php echo esc_html($journey_heading_suffix); ?>
                        </h2>
                        <div
                            class="relative md:pt-4 pt-1.5 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] md:after:h-3 after:h-[0.688rem] after:-skew-x-[18deg] after:left-0">
                        </div>
                    </div>
                    <p class="md:font-medium font-normal text-base md:text-lg text-black">
                        <?php echo esc_html($journey_description); ?>
                    </p>
                </div>
            </div>
            <div class="md:w-3/5 w-full h-full space-y-7 md:pl-20">
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-x-6 md:gap-y-6 gap-y-6">
                    <?php 
                    $journey_count = count($journey_items);
                    $is_odd = ($journey_count % 2 !== 0);
                    $index = 0;
                    foreach ($journey_items as $journey): 
                        $index++;
                        $is_last = ($index === $journey_count);
                        $additional_class = ($is_odd && $is_last) ? ' md:col-span-2' : '';
                    ?>
                    <div class="w-full relative overflow-hidden group duration-500 md:h-[26.063rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2<?php echo esc_attr($additional_class); ?> after:absolute after:inset-0 after:[background:_linear-gradient(180deg,_rgba(0,_0,_0,_0)_41.83%,_#000000_100%)] after:z-0 *:z-10">
                        <?php if (!empty($journey['image']['url'])): ?>
                        <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($journey['image']['url']); ?>" width="304" height="334"
                            alt="<?php echo esc_attr($journey['image']['alt'] ?: $journey['title']); ?>" title="<?php echo esc_attr($journey['image']['alt'] ?: $journey['title']); ?>"
                            class="size-full object-cover aspect-[152/167]">
                        <?php else: ?>
                        <img fetchpriority="low" loading="lazy" src="<?php echo get_template_directory_uri() ?>/assets/img/car_get.webp" width="304" height="334"
                            alt="<?php echo esc_attr($journey['title']); ?>" title="<?php echo esc_attr($journey['title']); ?>"
                            class="size-full object-cover aspect-[152/167]">
                        <?php endif; ?>

                        <div class=" absolute top-1 left-3 text-[7.563rem] md:text-[7.75rem] duration-300 font-extrabold text-[#ffffffa8] text-stroke
                                        drop-shadow-[0.125rem_0_0_#ffffff40] 
                                        drop-shadow-[-0.125rem_0_0_white]
                                        drop-shadow-[0_0.125rem_0_white] 
                                        drop-shadow-[0_-0.125rem_0_white] 

                                     ">
                            <?php echo esc_html($journey['number']); ?>
                        </div>

                        <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 p-6">
                            <h3
                                class="text-[#FFFFFF] md:text-[2.125rem] text-[2rem] font-semibold duration-300 group-hover:lg:text-[#CB122D]">
                                <?php echo esc_html($journey['title']); ?>
                            </h3>

                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>


            </div>
        </div>

    </section>

    <section class=" h-full relative">
        <div class=" w-full view flex md:flex-row md:gap-0 gap-16 flex-col relative pr-0">

            <div class="md:w-1/2 w-full h-full flex flex-col relative xl:top-60 lg:top-32 md:top-20 top-5 pr-4 md:pr-0">
                <div class="w-full flex flex-col gap-y-3">
                    <h2
                        class="xl:text-[3.125rem] lg:text-[3rem] md:text-[3rem] text-[2.625rem] text-black font-bold leading-tight">

                        <?php echo esc_html($app_heading_line1); ?>
                        <span class="text-[#CB122D] block"><?php echo esc_html($app_heading_line2); ?> </span>
                    </h2>
                    <p class="md:font-medium font-normal text-base md:text-lg max-w-2xl text-black text-balance">
                        <?php echo esc_html($app_description); ?>
                    </p>

                </div>

                <form id="appDownloadForm" class="flex w-full max-w-md border border-[#B5B5B54A] mt-[2.375rem] overflow-hidden rounded-lg">
                    <!-- Success/Error Messages -->
                    <div id="appFormMessage" class="hidden fixed top-20 left-1/2 transform -translate-x-1/2 z-50 p-4 rounded-lg text-sm font-medium max-w-md"></div>
                    
                    <div class="flex items-center gap-1 bg-[#F8F8F8] border-r border-[#E5E5E5] px-3 shrink-0">
                        <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($images_url . '/indiaFlag.webp'); ?>" class="w-4 h-auto" alt="India Flag">
                        <span class="md:text-sm text-xs font-medium text-[#2F2F2F]">+91</span>
                    </div>
                    
                    <input type="tel" id="appPhoneInput" name="phone_number" inputmode="numeric" pattern="[6-9][0-9]{9}" maxlength="10" 
                        placeholder="<?php echo esc_attr($app_contact_placeholder); ?>"
                        class="flex-1 py-2.5 pl-4 md:text-base text-xs font-normal bg-white placeholder:text-[#000000A3] bg-transparent outline-none text-[#000000A3] border-0 focus:ring-0 min-w-0"
                        required />

                    <button type="submit" id="appSubmitBtn"
                        class="bg-[#FF8300] text-white md:text-base text-xs text-nowrap font-bold px-2 flex items-center gap-1 md:gap-2.5 disabled:bg-gray-400 disabled:cursor-not-allowed relative">
                        <span id="appSubmitBtnText"><?php echo esc_html($app_button_text); ?></span>
                        <span id="appSubmitBtnLoader" class="hidden flex items-center justify-center">
                            <svg class="animate-spin md:size-5 size-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span id="appSubmitBtnIcon" class="">
                            <svg class="shrink-0 size-4" viewBox="0 0 11 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 8.00315L5.63239 16H0L1.79304 13.3344L5.36761 8.00315L1.79304 2.67506L0 0H5.63239L11 8.00315Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </button>
                </form>

                <div class="flex items-center gap-2 md:gap-4 mt-6 md:mt-7">
                    <?php if (!empty($app_google_link)): ?>
                    <a href="<?php echo esc_url($app_google_link); ?>" target="_blank" rel="noopener noreferrer" class="md:h-[2.625rem] w-28 md:w-[7.875rem]">
                        <img class="w-full h-full object-fill" src="<?php echo esc_url($app_google_image['url']); ?>" alt="<?php echo esc_attr($app_google_image['alt']); ?>" title="<?php echo esc_attr($app_google_image['alt']); ?>">
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($app_apple_link)): ?>
                    <a href="<?php echo esc_url($app_apple_link); ?>" target="_blank" rel="noopener noreferrer" class="md:h-[2.625rem] w-28 md:w-[7.875rem]">
                        <img class="w-full h-full object-fill" src="<?php echo esc_url($app_apple_image['url']); ?>" alt="<?php echo esc_attr($app_apple_image['alt']); ?>" title="<?php echo esc_attr($app_apple_image['alt']); ?>">
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class=" md:w-1/2 w-full ">
                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($app_image['url']); ?>" width="608" height="573" 
                    alt="<?php echo esc_attr($app_image['alt']); ?>"
                    class="w-full h-full object-bottom object-contain aspect-[608/573]">
            </div>
        </div>
        <div
            class="w-full absolute bottom-0 bg-gradient-to-r from-[#FFFFFF] bottom-5 to-[#E5E5E5] h-full md:h-[35.625rem] -z-10">
        </div>

    </section>

<?php get_footer(); ?>

<script> 
    document.addEventListener("DOMContentLoaded", function () {
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

<script>
// App Download Form - SMS sent via single template (link in template only)
(function() {
    'use strict';
    
    const appForm = document.getElementById('appDownloadForm');
    const appPhoneInput = document.getElementById('appPhoneInput');
    const appSubmitBtn = document.getElementById('appSubmitBtn');
    const appSubmitBtnText = document.getElementById('appSubmitBtnText');
    const appSubmitBtnLoader = document.getElementById('appSubmitBtnLoader');
    const appSubmitBtnIcon = document.getElementById('appSubmitBtnIcon');
    const appFormMessage = document.getElementById('appFormMessage');
    
    if (!appForm || !appPhoneInput || !appSubmitBtn) {
        return; // Exit if elements not found
    }
    
    // Get AJAX URL and nonce
    const ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';
    const appDownloadNonce = '<?php echo wp_create_nonce("app_download_nonce"); ?>';
    
    // Function to show message
    function showMessage(message, isError = false) {
        if (!appFormMessage) return;
        
        appFormMessage.textContent = message;
        appFormMessage.className = isError 
            ? 'fixed top-20 left-1/2 transform -translate-x-1/2 z-50 p-4 rounded-lg text-sm font-medium max-w-md bg-red-100 border border-red-400 text-red-700'
            : 'fixed top-20 left-1/2 transform -translate-x-1/2 z-50 p-4 rounded-lg text-sm font-medium max-w-md bg-green-100 border border-green-400 text-green-700';
        appFormMessage.classList.remove('hidden');
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            appFormMessage.classList.add('hidden');
        }, 5000);
    }
    
    // Function to validate phone number
    function validatePhoneNumber(phone) {
        if (!phone || phone.length !== 10) {
            return false;
        }
        return /^[6-9][0-9]{9}$/.test(phone);
    }
    
    // Handle form submission
    appForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const phone = appPhoneInput.value.trim();
        
        // Validate phone number
        if (!validatePhoneNumber(phone)) {
            showMessage('Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9', true);
            appPhoneInput.focus();
            return;
        }
        
        // Show loader and disable button
        if (appSubmitBtnText) {
            appSubmitBtnText.classList.add('hidden');
        }
        if (appSubmitBtnIcon) {
            appSubmitBtnIcon.classList.add('hidden');
        }
        if (appSubmitBtnLoader) {
            appSubmitBtnLoader.classList.remove('hidden');
        }
        appSubmitBtn.disabled = true;
        appPhoneInput.disabled = true;
        
        // Prepare form data (single template from CMS; no device/link sent)
        const formData = new FormData();
        formData.append('action', 'send_app_download_otp');
        formData.append('nonce', appDownloadNonce);
        formData.append('mobile', phone);
        
        // Send AJAX request
        fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                const successMsg = (data.data && data.data.message) ? data.data.message : 'App download link sent successfully! Please check your mobile number.';
                showMessage(successMsg, false);
                // Reset form
                appPhoneInput.value = '';
            } else {
                const errorMsg = (data && data.data && data.data.message) 
                    ? data.data.message 
                    : 'Failed to send app link. Please try again.';
                showMessage(errorMsg, true);
            }
        })
        .catch(error => {
            console.error('Error sending app link:', error);
            showMessage('Failed to send app link. Please try again.', true);
        })
        .finally(() => {
            // Hide loader and enable button
            if (appSubmitBtnText) {
                appSubmitBtnText.classList.remove('hidden');
            }
            if (appSubmitBtnIcon) {
                appSubmitBtnIcon.classList.remove('hidden');
            }
            if (appSubmitBtnLoader) {
                appSubmitBtnLoader.classList.add('hidden');
            }
            appSubmitBtn.disabled = false;
            appPhoneInput.disabled = false;
        });
    });
    
    // Allow only numbers in phone input
    appPhoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Prevent paste of non-numeric content
    appPhoneInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const numbersOnly = paste.replace(/[^0-9]/g, '');
        this.value = numbersOnly.substring(0, 10);
    });
})();
</script>