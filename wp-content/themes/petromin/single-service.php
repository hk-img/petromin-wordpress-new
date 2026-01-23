<?php
/**
 * Template Name: Service Detail
 */
get_header();

$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$images_url = $assets_url . '/img';

// Get ACF fields
$hero_description = get_field('hero_description') ?: 'Your car runs better when the service is predictable, not the problems. We focus on preventive care that protects performance, avoids surprises, and keeps your vehicle reliable between every start and stop.';
$problems_title = get_field('problems_title') ?: 'Tired of these?';
$problems = get_field('problems') ?: array();
$services_title = get_field('services_title') ?: "Here's what your car gets";
$services_included = get_field('services_included') ?: array();
$services_overview_note = get_field('services_overview_note') ?: "For a complete overview of what's covered, get in touch.";
$more_services_title = get_field('more_services_title') ?: 'More Services';
$more_services_button_text = get_field('more_services_button_text') ?: 'More Services';
$more_services_button_link = get_field('more_services_button_link') ?: '';
$savings_title = get_field('savings_title') ?: 'Your car gets the service. You get the savings.';
$savings_description = get_field('savings_description') ?: 'Check for perks before you book a visit.';
$savings_button_text = get_field('savings_button_text') ?: 'Know More';
$savings_button_link = get_field('savings_button_link') ?: '#';
$savings_image = get_field('savings_image');
$faq_title = get_field('faq_title') ?: 'It\'s best you know these.';

// Static CTA: Check Price (not managed via CMS)
$check_price_page = get_page_by_path('cost-estimator');
$check_price_link = $check_price_page ? get_permalink($check_price_page) : '#';

// Backend-only service images (for reuse on other pages). These are uploaded in the Service editor
// and intentionally NOT output on the single service front-end. Stored as ACF image fields (return ID).
$for_services_page_image = get_field('for_services_page_image'); // For Services Page Section's (webp, min 352x478)
$service_icon_image = get_field('service_icon'); // Service Icon (webp, min 100x100)
$home_page_service_image = get_field('home_page_service_image'); // Home Page Service Section's (webp, min 730x437)

// Default problems if empty
if (empty($problems)) {
    $problems = array(
        array('title' => 'Reduced Mileage'),
        array('title' => 'Oil & Coolant Warning Light On'),
        array('title' => 'Oil Leakage'),
        array('title' => 'Engine Misfires'),
        array('title' => 'Rough Engine Sounds'),
        array('title' => 'High Engine Vibrations'),
        array('title' => 'Low Drive Comfort'),
        array('title' => 'Reduced Mileage'),
    );
}

// Default services if empty
if (empty($services_included)) {
    $services_included = array(
        array('title' => 'Air Filter Change', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
        array('title' => 'Oil Filter Change', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
        array('title' => 'Engine Oil Replacement', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
        array('title' => 'Multi-Point Inspection', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
        array('title' => 'Fuel Filter Replacement', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
        array('title' => 'Cabin Filter Cleaning', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
        array('title' => 'Parts Cleaning / Parts Replacement (if needed)', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
        array('title' => 'Interior Vacuum Cleaning & Eco Wash', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
        array('title' => 'Door Lock & Hinges Lubrication', 'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.'),
    );
}

// Get blog posts for "Know More" section
$blog_posts_query = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 6,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
]);
$blog_posts = $blog_posts_query->posts;
wp_reset_postdata();

// Get other services for "More Services" carousel
$other_services_query = new WP_Query([
    'post_type' => 'service',
    'posts_per_page' => 8,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post__not_in' => [get_the_ID()], // Exclude current service
]);
$other_services = $other_services_query->posts;
wp_reset_postdata();

// Get icon based on type or image URL
function get_service_icon($icon_input) {
    $assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
    $images_url = $assets_url . '/img';
    
    // If it's a URL (from image upload), display it directly
    if (is_string($icon_input) && (strpos($icon_input, 'http') === 0 || strpos($icon_input, '/') === 0)) {
        return '<img fetchpriority="low" loading="lazy" src="' . esc_url($icon_input) . '" alt="icon" class="size-[3.75rem] object-contain">';
    }
    
    // Otherwise, use legacy icon type selection
    switch($icon_input) {
        case 'oil':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-[#CB122D]" viewBox="0 0 23 24" fill="none"><!-- Oil Icon SVG --></svg>';
        case 'warning':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-[#CB122D]" viewBox="0 0 24 24" fill="currentColor"><!-- Warning Icon SVG --></svg>';
        case 'vibration':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-[#CB122D]" viewBox="0 0 23 24" fill="none"><!-- Vibration Icon SVG --></svg>';
        case 'mileage':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-[#CB122D]" viewBox="0 0 24 24" fill="currentColor"><!-- Mileage Icon SVG --></svg>';
        case 'comfort':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-[#CB122D]" viewBox="0 0 23 24" fill="none"><!-- Comfort Icon SVG --></svg>';
        default: // engine
            return '<img fetchpriority="low" loading="lazy" src="' . esc_url($images_url . '/car_service_icon.webp') . '" alt="engine" class="size-full object-contain">';
    }
}
?>

<div class="heroSection w-full relative z-0 h-dvh">
    <div class="relative w-full h-full overflow-hidden before:absolute before:inset-0 before:[background:_linear-gradient(180deg,_rgba(0,_0,_0,_0)_41.83%,_#000000_100%)]">
        <?php if (has_post_thumbnail()) : ?>
        <img fetchpriority="high" loading="eager" decoding="async"
            src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'full')); ?>"
            class="size-full object-cover aspect-[1279/342]" alt="<?php echo esc_attr(get_the_title()); ?>"
            title="<?php echo esc_attr(get_the_title()); ?>">
        <?php else : ?>
        <img fetchpriority="high" loading="eager" decoding="async"
            src="<?php echo esc_url($images_url . '/car_services.webp'); ?>"
            class="size-full object-cover aspect-[1279/342]" alt="<?php echo esc_attr(get_the_title()); ?>"
            title="<?php echo esc_attr(get_the_title()); ?>">
        <?php endif; ?>

        <div class="view md:w-2/3 w-full absolute md:bottom-20 bottom-12 left-0 z-30">
            <div class="flex flex-col md:gap-y-10 gap-y-4">
                <h1
                    class="block xl:text-[6.813rem] lg:text-[6rem] md:text-[5.5rem] text-4xl text-balance text-[#FFFFFF] font-bold md:drop-shadow-[0_4px_6px_rgba(0,0,0,0.7)] !leading-[normal]">
                    <?php the_title(); ?>
                </h1>
                <div
                    class="md:text-lg text-base text-[#FFFFFF] text-balance md:font-semibold font-normal drop-shadow-[0_4px_6px_rgba(0,0,0,0.7)]">
                    <?php echo esc_html($hero_description); ?>
                </div>
                <div class="flex gap-4 w-full mt-1">
                    <a href="#carPopup"
                        class="px-5 flex space-x-3 items-center bg-[#FF8300] h-12 js-open-car-popup">
                        <span
                            class="flex items-center gap-1 lg:text-lg md:text-base text-sm md:font-bold font-semibold text-white">
                            Check Price
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.75rem] h-[0.75rem]" viewBox="0 0 14 20"
                                fill="none">
                                <path
                                    d="M13.5294 9.84344L6.92754 19.6791H0L2.20534 16.4006L6.60187 9.84344L2.20534 3.29018L0 0H6.92754L13.5294 9.84344Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </a>
                    <a href="<?php echo esc_url(!empty($more_services_button_link) ? $more_services_button_link : '#more-services'); ?>"
                        class="px-5 flex space-x-3 items-center bg-[#FF8300] h-12 <?php echo empty($more_services_button_link) ? 'js-scroll-to-more-services' : ''; ?>">
                        <span
                            class="flex items-center gap-1 lg:text-lg md:text-base text-sm md:font-bold font-semibold text-white">
                            <?php echo esc_html($more_services_button_text); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.75rem] h-[0.75rem]" viewBox="0 0 14 20"
                                fill="none">
                                <path
                                    d="M13.5294 9.84344L6.92754 19.6791H0L2.20534 16.4006L6.60187 9.84344L2.20534 3.29018L0 0H6.92754L13.5294 9.84344Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="w-full relative bg-white md:pt-[5.25rem] pt-[3rem] pb-[3.25rem]">
    <div class="view">
        <div class="w-full flex flex-col gap-1 md:gap-4">
            <h2
                class="relative xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] lg:leading-[3.75rem] font-bold text-[#000000]">
                <?php echo esc_html($problems_title); ?>
            </h2>
            <div class="bg-gradient-to-l from-[#CB122D] to-[#650916] w-[7.375rem] w-20 h-3 -skew-x-[22deg]"></div>
        </div>
        <div class="w-full relative pt-12">
            <div class="grid lg:grid-cols-4 md:grid-cols-3 grid-cols-1 gap-x-6 md:gap-x-16 md:gap-y-16 gap-y-8">
                <?php foreach ($problems as $problem) : ?>
                <div class="relative w-full flex items-center gap-3">
                    <span class="size-[3.75rem] flex justify-center items-center">
                        <?php echo get_service_icon($problem['icon']); ?>
                    </span>
                    <h3 class="font-medium text-[#121212] md:text-xl text-lg"><?php echo esc_html($problem['title']); ?>
                    </h3>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="w-full relative bg-white md:pb-[6.25rem] pb-[2rem]">
    <div class="view">
        <div class="w-full flex flex-col gap-1 md:gap-4">
            <h2
                class="relative xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] flex items-center  lg:leading-[3.75rem] font-bold text-[#000000]">
                <?php echo esc_html($services_title); ?>
                <span><img src="<?php echo esc_url($images_url . '/star_svg.webp'); ?>" class="object-contain size-10"
                        alt="star" title="star"></span>
            </h2>
            <div class="bg-gradient-to-l from-[#CB122D] to-[#650916] w-[7.375rem] w-20 h-3 -skew-x-[22deg]"></div>
        </div>
        <div class="w-full relative pt-12">
            <div class="grid lg:grid-cols-3 md:grid-cols-3 grid-cols-1 gap-6">
                <?php foreach ($services_included as $service) : 
                    $service_image = !empty($service['image']) ? wp_get_attachment_url($service['image']) : $images_url . '/car_get.webp';
                ?>
                <div
                    class="w-full relative overflow-hidden group duration-500 md:h-[32.813rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2 after:absolute after:inset-0 after:[background:_linear-gradient(180deg,_rgba(0,_0,_0,_0)_41.83%,_#000000_100%)] after:z-0 *:z-10">
                    <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($service_image); ?>"
                        alt="<?php echo esc_attr($service['title']); ?>"
                        title="<?php echo esc_attr($service['title']); ?>"
                        class="size-full object-cover aspect-[371/334]">
                    <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 p-6">
                        <h3
                            class="text-[#FFFFFF] lg:text-3xl md:text-2xl text-xl font-bold duration-300 group-hover:lg:text-[#CB122D]">
                            <?php echo esc_html($service['title']); ?>
                        </h3>
                        <p
                            class="text-[#FFFFFF] opacity-75 md:text-lg text-base duration-500 drop-shadow-[3px_3px_10px_rgba(0,0,0,0.9)]">
                            <?php echo esc_html($service['description']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <p class="text-[#000000] md:text-lg text-base font-normal pt-5">*<?php echo esc_html($services_overview_note); ?></p>
    </div>
</div>



<section id="more-services" class="w-full relative moreServices md:pb-[6.25rem] pb-[4rem] overflow-hidden">
    <div class="view md:pr-0">
        <div class="flex items-center justify-between md:pb-12 pb-6">
            <div class="w-full flex flex-col gap-1 md:gap-4">
                <h2
                    class="relative  xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] flex items-center font-bold text-[#000000] ">
                    <?php echo esc_html($more_services_title); ?>
                </h2>
                <div class="bg-gradient-to-l from-[#CB122D]  to-[#650916] w-[7.375rem] h-3 -skew-x-[22deg]">
                </div>
            </div>
            <div
                class=" md:flex items-center justify-start md:gap-2 hidden origin-bottom z-20 bg-[#CB122D] px-4 shadow-[-6px_6px_0px_-1px_rgba(0,0,0,0.9)] w-56 h-16 transition transform -skew-x-12 duration-150 ease-in-out has-[.swiper-next.swiper-button-lock]:!hidden -mr-[0.506rem]">
                <div class="swiper-prev cursor-pointer !pointer-events-auto !opacity-100">
                    <span>
                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/fi_19024510.webp"
                            class="text-white size-8 rotate-180 skew-x-12 invert brightness-0">
                    </span>
                </div>
                <div class="swiper-next cursor-pointer !pointer-events-auto !opacity-100">
                    <span>
                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/fi_19024510.webp"
                            class="text-white size-8 skew-x-12 invert brightness-0 mb-[0.188rem] ml-3">
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="view">
        <div
            class="swiper moreServicesSectionSwiper relative font-inter z-0 pt-6">
            <div class="swiper-wrapper h-auto">
                <?php if (!empty($other_services)) : ?>
                    <?php foreach ($other_services as $service) :
                        setup_postdata($service);
                        $sid = $service->ID;
                        $slide_img = petromin_get_acf_image_data(get_field('for_services_page_image', $sid), 'full', '', get_the_title($sid));
                        $icon_img = petromin_get_acf_image_data(get_field('service_icon', $sid), 'thumbnail', '', get_the_title($sid));
                        $service_description = get_field('hero_description', $sid) ?: 'Lorem ipsum dolor sit amet consectetur adipiscing elit.';
                    ?>
                    <div class="swiper-slide md:max-w-[35vw]">
                        <div
                            class="w-full relative overflow-hidden group duration-500 md:h-[32.813rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2 after:absolute after:inset-0 after:[background:_linear-gradient(180deg,_rgba(0,_0,_0,_0)_41.83%,_#000000_100%)] after:z-0 *:z-10">
                            <?php if (!empty($slide_img['url'])) : ?>
                            <img fetchpriority="low" loading="lazy"
                                src="<?php echo esc_url($slide_img['url']); ?>"
                                alt="<?php echo esc_attr(get_the_title($sid)); ?>"
                                title="<?php echo esc_attr(get_the_title($sid)); ?>"
                                class="size-full object-cover aspect-[92/105]">
                            <?php endif; ?>
                            <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 md:p-6 p-4">
                                <div>
                                    <?php if (!empty($icon_img['url'])) : ?>
                                    <img src="<?php echo esc_url($icon_img['url']); ?>"
                                        alt="<?php echo esc_attr(get_the_title($sid)); ?>"
                                        class="md:size-10 size-9">
                                    <?php endif; ?>
                                </div>
                                <h3
                                    class="text-[#FFFFFF] lg:text-3xl md:text-2xl text-xl font-bold duration-300 group-hover:lg:text-[#CB122D] line-clamp-2">
                                    <?php echo esc_html(get_the_title($sid)); ?>
                                </h3>
                                <p class="text-[#FFFFFF] opacity-75 md:text-lg text-base duration-500 drop-shadow-[3px_3px_10px_rgba(0,0,0,0.9)] line-clamp-2">
                                    <?php echo esc_html($service_description); ?>
                                </p>
                                <div class="flex justify-between items-center gap-2">
                                    <button
                                        class="hover:lg:bg-[#CB122D] w-full px-5 flex space-x-3 whitespace-nowrap items-center justify-center bg-[#FF8300] h-12 duration-300 js-open-car-popup">
                                        <span
                                            class="flex items-center gap-1 text-base md:font-bold font-semibold text-white">Check
                                            Price
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.75rem] h-[0.75rem]"
                                                viewBox="0 0 14 20" fill="none">
                                                <path
                                                    d="M13.5294 9.84344L6.92754 19.6791H0L2.20534 16.4006L6.60187 9.84344L2.20534 3.29018L0 0H6.92754L13.5294 9.84344Z"
                                                    fill="white"></path>
                                            </svg></span>
                                    </button>
                                    <a href="<?php echo esc_url(get_permalink($sid)); ?>"
                                        class="hover:lg:bg-[#CB122D] w-full px-5 flex space-x-3 whitespace-nowrap items-center justify-center bg-[#FF8300] h-12 duration-300">
                                        <span
                                            class="flex items-center gap-1 text-base md:font-bold font-semibold text-white">Know
                                            More
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.75rem] h-[0.75rem]"
                                                viewBox="0 0 14 20" fill="none">
                                                <path
                                                    d="M13.5294 9.84344L6.92754 19.6791H0L2.20534 16.4006L6.60187 9.84344L2.20534 3.29018L0 0H6.92754L13.5294 9.84344Z"
                                                    fill="white"></path>
                                            </svg></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div
    class="w-full overflow-hidden relative bg-white md:py-[3.25rem] py-[2rem] bg-gradient-to-l z-10 from-[#CB122D] to-[#650916]">
    <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($images_url . '/car_service_bannner.webp'); ?>"
        class="size-full object-contain object-bottom absolute bottom-0 inset-x-0 -z-10 aspect-[1297/434]"
        alt="<?php echo esc_attr($savings_title); ?>" title="<?php echo esc_attr($savings_title); ?>">
    <div class="view">
        <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 md:gap-12 gap-8 items-center">
            <div class="flex flex-col gap-4">
                <h2
                    class="relative xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] leading-tight font-bold text-[#FFFFFF]">
                    <?php echo esc_html($savings_title); ?>
                </h2>
                <p class="text-[#FFFFFF] lg:text-xl md:text-lg text-base"><?php echo esc_html($savings_description); ?>
                </p>
                <div class="flex w-full pt-3">
                    <a href="<?php echo esc_url($savings_button_link); ?>"
                        class="px-3 flex space-x-3 items-center bg-[#FF8300] h-10">
                        <span class="flex items-center gap-1 text-base font-semibold text-white">
                            <?php echo esc_html($savings_button_text); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.625rem] h-[0.625rem]" viewBox="0 0 10 15"
                                fill="none">
                                <path
                                    d="M1.62207 0.353577L8.53223 7.26373L8.70898 7.44147L1.62207 14.5284L0.353516 13.2598L6.17188 7.44147L0.530273 1.79889L0.353516 1.62213L1.62207 0.353577Z"
                                    fill="white" stroke="white" stroke-width="0.5" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="w-full md:ps-12">
                <?php if ($savings_image) : ?>
                <img fetchpriority="low" loading="lazy"
                    src="<?php echo esc_url(wp_get_attachment_url($savings_image)); ?>"
                    class="size-full object-contain aspect-[518/327]" alt="<?php echo esc_attr($savings_title); ?>"
                    title="<?php echo esc_attr($savings_title); ?>">
                <?php else : ?>
                <img fetchpriority="low" loading="lazy"
                    src="<?php echo esc_url($images_url . '/service_saving_banner.webp'); ?>"
                    class="size-full object-contain aspect-[518/327]" alt="<?php echo esc_attr($savings_title); ?>"
                    title="<?php echo esc_attr($savings_title); ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="w-full relative bg-white overflow-hidden pt-[5.25rem] md:pb-[5.25rem]">
    <div class="view md:!pr-0">
        <div class="w-full flex flex-col gap-1 md:gap-4">
            <h2
                class="relative xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] flex items-center lg:leading-[3.75rem] font-bold text-[#000000]">
                <?php echo esc_html($faq_title); ?>
            </h2>
            <div
                class="bg-gradient-to-l from-[#CB122D] to-[#650916] w-[7.375rem] w-20 h-3 -skew-x-[22deg] md:hidden block">
            </div>
        </div>
        <div class="w-full relative">
            <div class="swiper bestKnownSectionSwiper relative z-0 py-10 font-inter">
                <div class="swiper-wrapper">
                    <?php if ($blog_posts) : ?>
                    <?php foreach ($blog_posts as $post) : 
                            setup_postdata($post);
                            $post_image = get_the_post_thumbnail_url($post->ID, 'large') ?: $images_url . '/media_mention_img.webp';
                            $reading_time = calculate_reading_time(get_the_content());
                        ?>
                    <div class="swiper-slide !h-auto">
                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"
                            class="bg-[#F8F8F880] relative flex flex-col gap-3 border border-[#EFEFEF] p-4 h-full hover:shadow-lg duration-300 overflow-hidden group">
                            <h3 class="text-[#CB122D] lg:text-xl md:text-lg text-base font-bold line-clamp-2">
                                <?php echo esc_html(get_the_title($post->ID)); ?>
                            </h3>

                            <p class="lg:text-base md:text-sm text-xs text-[#475467] text-balance line-clamp-4">
                                <?php 
                                        $excerpt = get_the_excerpt($post->ID);
                                        if (empty($excerpt)) {
                                            $excerpt = wp_trim_words(get_the_content(null, false, $post->ID), 15);
                                        }
                                        echo esc_html($excerpt);
                                        ?>
                            </p>

                            <div class="w-full relative pt-2 mt-auto">
                                <span
                                    class="text-[#CB122D] font-bold lg:text-xl md:text-lg text-base flex items-center gap-2">
                                    Know More
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[0.75rem] h-[0.6875rem]"
                                            viewBox="0 0 14 14" fill="none">
                                            <path
                                                d="M0.833984 6.66683H12.5007M12.5007 6.66683L6.66732 0.833496M12.5007 6.66683L6.66732 12.5002"
                                                stroke="#CB122D" stroke-width="1.66667" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </span>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                    <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                    <div class="swiper-slide !h-auto">
                        <div class="bg-[#F8F8F880] relative flex flex-col gap-2 border border-[#EFEFEF] p-6 h-full">
                            <p class="text-[#475467]">No blog posts available.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php 
    $bestKnowSettings = petromin_get_swiper_settings('bestKnownSectionSwiper');
    ?>
    const bestKnowCarouselSwiper = new Swiper(".bestKnownSectionSwiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        speed: <?php echo esc_js($bestKnowSettings['speed']); ?>,
        autoplay: <?php echo $bestKnowSettings['autoplay'] ? '{
            delay: ' . esc_js($bestKnowSettings['delay']) . ',
            disableOnInteraction: false,
        }' : 'false'; ?>,
        breakpoints: {
            640: {
                slidesPerView: 2
            },
            1024: {
                slidesPerView: 3
            },
            1350: {
                slidesPerView: 4
            },
        },
    });
});
</script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php 
            $moreServiceSettings = petromin_get_swiper_settings('moreServicesSectionSwiper');
            ?>
            const swiper = new Swiper(".moreServicesSectionSwiper", {
                speed: <?php echo esc_js($moreServiceSettings['speed']); ?>,
                autoHeight: true,
                autoplay: <?php echo $moreServiceSettings['autoplay'] ? '{
                    delay: ' . esc_js($moreServiceSettings['delay']) . ',
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
                        spaceBetween: 6
                    },
                    480: {
                        slidesPerView: 1,
                        spaceBetween: 12
                    },
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 16
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    },
                    1350: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    },
                },
            });

            // If "More Services" CTA link is not set from CMS, scroll to this section
            document.querySelectorAll('.js-scroll-to-more-services').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    var target = document.getElementById('more-services');
                    if (!target) return;
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });
        });
    </script>

<?php get_footer(); ?>