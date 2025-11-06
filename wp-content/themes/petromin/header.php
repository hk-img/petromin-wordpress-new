<!DOCTYPE html>
<html <?php language_attributes(); ?> class="md:text-[1vw]">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    
    <?php if (has_site_icon()): ?>
        <link rel="icon" href="<?php echo get_site_icon_url(); ?>" />
    <?php endif; ?>

    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>

    <!-- Preload fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- WordPress head -->
    <?php wp_head(); ?>
</head>

<body <?php body_class('!font-inter'); ?>>


<?php
// Get ACF Header Data
$header_logo_field = function_exists('get_field') ? (get_field('header_logo', 'option') ?: []) : [];
$navigation_menu = function_exists('get_field') ? (get_field('navigation_menu', 'option') ?: []) : [];
$user_menu = function_exists('get_field') ? (get_field('user_menu', 'option') ?: []) : [];
$mobile_menu = function_exists('get_field') ? (get_field('mobile_menu', 'option') ?: []) : [];

// Logo Data
$desktop_logo_data = petromin_get_acf_image_data($header_logo_field['desktop_logo'] ?? null, 'full', get_template_directory_uri() . '/assets/img/petromin-logo-300x75-1.webp', $header_logo_field['logo_alt_text'] ?? 'Petromin Logo');
$mobile_logo_data = petromin_get_acf_image_data($header_logo_field['mobile_logo'] ?? null, 'full', get_template_directory_uri() . '/assets/img/petromin-logo-300x75-1.webp', $header_logo_field['logo_alt_text'] ?? 'Petromin Logo');

// User Menu Links
$login_link = $user_menu['login_link'] ?? '#';
$signup_link = $user_menu['signup_link'] ?? '#';

// Default navigation menu items if ACF is empty
$default_nav_items = [
    ['menu_text' => 'About Us', 'menu_link' => '#', 'menu_item_target' => false],
    ['menu_text' => 'Services', 'menu_link' => '#', 'menu_item_target' => false],
    ['menu_text' => 'Latest offers', 'menu_link' => '#', 'menu_item_target' => false],
    ['menu_text' => 'Blogs', 'menu_link' => '#', 'menu_item_target' => false],
    ['menu_text' => 'Newsroom', 'menu_link' => '#', 'menu_item_target' => false],
    ['menu_text' => 'PETROMINit!', 'menu_link' => '#', 'menu_item_target' => false],
    ['menu_text' => 'Locate Us', 'menu_link' => '#', 'menu_item_target' => false],
];

// Default mobile menu items if ACF is empty  
$default_mobile_items = [
    ['mobile_menu_text' => 'About Us', 'mobile_menu_link' => 'about'],
    ['mobile_menu_text' => 'Services', 'mobile_menu_link' => 'about'],
    ['mobile_menu_text' => 'Latest offers', 'mobile_menu_link' => '#'],
    ['mobile_menu_text' => 'Blogs', 'mobile_menu_link' => '#'],
    ['mobile_menu_text' => 'Newsroom', 'mobile_menu_link' => 'javascript:;'],
    ['mobile_menu_text' => 'PETROMINit!', 'mobile_menu_link' => 'javascript:;'],
    ['mobile_menu_text' => 'locate us', 'mobile_menu_link' => 'javascript:;'],
    ['mobile_menu_text' => 'Contact Us', 'mobile_menu_link' => 'javascript:;'],
];

// Use ACF data or defaults
$nav_items = !empty($navigation_menu) ? $navigation_menu : $default_nav_items;
$mobile_items = !empty($mobile_menu) ? $mobile_menu : $default_mobile_items;
?>

    <header class="w-full top-0 right-0 lg:bg-transparent bg-white font-poppins fixed z-40 xl:h-20">
        <div class="w-full relative flex justify-between items-center lg:px-0 px-4">
            <div
                class="xl:w-1/6 lg:w-1/5 md: h-[4.125rem] relative lg:flex lg:flex-col items-center py-5 bg-white hidden -skew-x-[18deg] origin-top">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="lg:w-auto pl-3 w-auto flex items-center skew-x-[18deg] h-full">
                    <?php if (!empty($desktop_logo_data)) : ?>
                        <img src="<?php echo esc_url($desktop_logo_data['url']); ?>" 
                             alt="<?php echo esc_attr($desktop_logo_data['alt']); ?>" 
                             title="<?php echo esc_attr($desktop_logo_data['alt']); ?>" 
                             class="lg:w-44 w-auto h-auto" loading="eager" fetchpriority="high" decoding="async">
                    <?php endif; ?>
                </a>
            </div>

            <div class="flex lg:hidden bg-white">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="lg:w-auto w-auto flex items-center py-2">
                    <?php if (!empty($mobile_logo_data)) : ?>
                        <img src="<?php echo esc_url($mobile_logo_data['url']); ?>" 
                             alt="<?php echo esc_attr($mobile_logo_data['alt']); ?>" 
                             title="<?php echo esc_attr($mobile_logo_data['alt']); ?>" 
                             width="w-146px" height="200" class="w-[9.125rem]" loading="lazy" fetchpriority="low">
                    <?php endif; ?>
                </a>
            </div>

            <div
                class="xl:w-10/12 lg:w-4/5 h-[4.125rem] bg-white relative lg:flex lg:flex-row flex-col  items-center justify-start hidden -skew-x-[18deg] lg:px-[1.875rem] origin-bottom">
                <div class="w-full flex justify-between items-center skew-x-[18deg]">
                    <ul class="nav-menu flex items-center 2xl:gap-14 xl:gap-8 gap-x-6 justify-between w-full">
                        <?php foreach ($nav_items as $nav_item) : 
                            $menu_text = $nav_item['menu_text'] ?? '';
                            $menu_link = $nav_item['menu_link'] ?? '#';
                            $target = $nav_item['menu_item_target'] ?? false ? '_blank' : '_self';
                        ?>
                            <li>
                                <a href="<?php echo esc_url($menu_link); ?>" 
                                   target="<?php echo esc_attr($target); ?>"
                                   class="text-[#000000] xl:text-sm text-xs font-medium whitespace-nowrap uppercase hover:text-[#CB122D] transition-colors">
                                    <?php echo esc_html($menu_text); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li>
                            <div class="user_submit relative group/cs">
                                <a href="#"
                                    class="border border-[#CB122D] flex items-center justify-center transition-all text-white py-[0.563rem] px-[0.688rem] cursor-pointer duration-500 group-hover/cs:bg-[#CB122D]">
                                    <svg class="text-[#CB122D] size-[23px] group-hover/cs:text-white"
                                        stroke="currentColor" fill="currentColor" viewBox="0 0 448 512"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z" />
                                    </svg>
                                </a>
                                <ul
                                    class="bg-[#cb122d] w-40 absolute top-[3.2rem] -left-28 p-4 opacity-0 z-40 transition-all translate-y-4 shadow-[0px_7px_10px_-1px_rgba(0,0,0,0.2)] invisible group-hover/cs:visible group-hover/cs:opacity-100">
                                    <li
                                        class="relative bg-white py-2 px-6 text-black text-sm font-normal flex items-center justify-center mb-3 hover:bg-gray-100 transition-colors">
                                        <a href="<?php echo esc_url($login_link); ?>" class="uppercase">Log in</a>
                                    </li>
                                    <li
                                        class="relative bg-white py-2 px-6 text-black text-sm font-normal flex items-center justify-center hover:bg-gray-100 transition-colors">
                                        <a href="<?php echo esc_url($signup_link); ?>" class="uppercase">Sign Up</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex items-center lg:gap-4 lg:hidden">
                <div class="flex items-center">
                    <div class="user_submit relative group/dd">
                        <a href="#"
                            class="border border-[#CB122D] flex items-center justify-center transition-all text-white py-[0.5rem] px-[0.438rem] cursor-pointer duration-500 group-hover/dd:bg-[#CB122D]">
                            <svg class="text-[#CB122D] size-[17px] group-hover/dd:text-[#ffffff]" stroke="currentColor"
                                fill="currentColor" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z" />
                            </svg>
                        </a>
                        <ul
                            class="bg-[#cb122d] w-40 absolute top-[2.2rem] -left-20 p-4 opacity-0 z-40 transition-all translate-y-4 shadow-[0px_7px_10px_-1px_rgba(0,0,0,0.2)] invisible group-hover/dd:visible group-hover/dd:opacity-100">
                            <li
                                class="relative bg-white py-2 px-4 text-black text-sm font-normal flex items-center justify-center mb-3 hover:bg-gray-100 transition-colors">
                                <a href="<?php echo esc_url($login_link); ?>" class="uppercase">Log in</a>
                            </li>
                            <li
                                class="relative bg-white py-2 px-4 text-black text-sm font-normal flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <a href="<?php echo esc_url($signup_link); ?>" class="uppercase">Sign Up</a>
                            </li>
                        </ul>
                    </div>
                    <label for="sideToggle"
                        class="inline-flex items-center justify-center rounded-md p-2.5 text-black group-[]/nav:text-white">
                        <span class="sr-only">Open menu</span>
                        <svg class="size-8" stroke="currentColor" fill="currentColor" stroke-width="0"
                            viewBox="0 0 20 20" aria-hidden="true" height="200px" width="200px"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75Zm0 10.5a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75ZM2 10a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 2 10Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </label>
                </div>
            </div>
        </div>
    </header>

    <input type="checkbox" class="peer/sideToggle hidden" name="sideToggle" id="sideToggle">
    <label for="sideToggle" class="fixed inset-0 -z-[1011] peer-checked/sideToggle:opacity-100 opacity-0 duration-100">
    </label>

    <div
        class="fixed flex flex-col h-full inset-y-0 right-0 z-[1011] w-full overflow-y-auto bg-[#051320] select-none text-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-theme1/10 peer-checked/sideToggle:translate-x-0 peer-checked/sideToggle:opacity-100 translate-x-full opacity-0 duration-300">
        <div class="flex items-center justify-between">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="-ml-1.5 cursor-pointer">
                <span class="sr-only"></span>
                <?php if (!empty($mobile_logo_data)) : ?>
                    <img src="<?php echo esc_url($mobile_logo_data['url']); ?>" 
                         alt="<?php echo esc_attr($mobile_logo_data['alt']); ?>" 
                         title="<?php echo esc_attr($mobile_logo_data['alt']); ?>" 
                         width="174" height="48" class="h-12 w-auto" loading="lazy" fetchpriority="low">
                <?php endif; ?>
            </a>
            <label for="sideToggle" type="button" class="-m-2.5 rounded-md p-2.5 *:text-white">
                <span class="sr-only">Close menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </label>
        </div>
        <div class="mt-6 flow-root flex-fill -mr-4 pr-4">
            <div class="-my-6 divide-y divide-white">
                <div class="space-y-2 py-6">
                    <div class="flex flex-col gap-5 items-center">
                        <div class="flex flex-col w-full gap-y-5">
                            <?php foreach ($mobile_items as $index => $mobile_item) : 
                                $mobile_text = $mobile_item['mobile_menu_text'] ?? '';
                                $mobile_link = $mobile_item['mobile_menu_link'] ?? '#';
                                $menu_id = 'mmMenu' . $index;
                            ?>
                                <div class="relative flex flex-col gap-y-3">
                                    <input class="peer/mm hidden" type="checkbox" name="mmMenu" id="<?php echo esc_attr($menu_id); ?>">
                                    <label for="<?php echo esc_attr($menu_id); ?>"
                                        class="text-white text-sm font-medium lg:py-4 py-2 px-1 uppercase tracking-[0.063rem] cursor-pointer hover:text-[#CB122D] transition-colors">
                                        <a href="<?php echo esc_url($mobile_link); ?>"><?php echo esc_html($mobile_text); ?></a>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>