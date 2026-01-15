<?php
/* Template Name: booking confirmed page */
get_header();

// Check if booking_id is present in URL parameter
$booking_id = isset($_GET['booking_id']) ? sanitize_text_field($_GET['booking_id']) : '';

// If booking_id is not present, redirect to homepage
if (empty($booking_id)) {
    wp_safe_redirect(home_url('/'));
    exit;
}

// Get theme assets directory URL for images
$img_url = get_template_directory_uri() . '/assets/img/';

// Get current page ID for ACF fields
$page_id = get_the_ID();

// Get ACF fields with fallback defaults
$confirmation_image = get_field('confirmation_image', $page_id);
$confirmation_title = get_field('confirmation_title', $page_id) ?: 'Booking Confirmed';
$confirmation_description = get_field('confirmation_description', $page_id) ?: 'A member of our team will reach out shortly to reconfirm your slot and share the next steps. Thank you for choosing us — we appreciate your time and trust.';
$need_anything_title = get_field('need_anything_title', $page_id) ?: 'Need anything else today?';

// Get browse section group fields
$browse_section = get_field('browse_section', $page_id);
$browse_section = is_array($browse_section) ? $browse_section : [];
$browse_text = $browse_section['browse_text'] ?? 'Browse';
$services_link_text = $browse_section['services_link_text'] ?? 'services,';
$services_link_url = !empty($browse_section['services_link_url']) ? $browse_section['services_link_url'] : home_url('/services');
$offers_link_text = $browse_section['offers_link_text'] ?? 'Latest offers,';
$offers_link_url = !empty($browse_section['offers_link_url']) ? $browse_section['offers_link_url'] : home_url('/offers');
$experts_link_text = $browse_section['experts_link_text'] ?? 'And Experts';
$experts_link_url = !empty($browse_section['experts_link_url']) ? $browse_section['experts_link_url'] : home_url('/experts');
$available_text = $browse_section['available_text'] ?? 'available in your city.';

// Get homepage button group fields
$homepage_button = get_field('homepage_button', $page_id);
$homepage_button = is_array($homepage_button) ? $homepage_button : [];
$homepage_button_text = $homepage_button['homepage_button_text'] ?? 'Go to Homepage';
$homepage_url = !empty($homepage_button['homepage_button_url']) ? $homepage_button['homepage_button_url'] : esc_url(home_url('/'));

// Get confirmation image URL
$confirmation_image_url = '';
if ($confirmation_image) {
    if (is_array($confirmation_image) && isset($confirmation_image['url'])) {
        $confirmation_image_url = $confirmation_image['url'];
    } elseif (is_numeric($confirmation_image)) {
        $confirmation_image_url = wp_get_attachment_image_url($confirmation_image, 'full');
    } elseif (is_string($confirmation_image)) {
        $confirmation_image_url = $confirmation_image;
    }
}
// Fallback to default image if no ACF image
if (empty($confirmation_image_url)) {
    $confirmation_image_url = $img_url . 'booking-confirmed.gif';
}

?>
<div class="bg-white w-full relative md:py-20 py-12">
    <div class="view">
        <div class="w-full relative flex flex-col items-center justify-center gap-y-6">
            <div class="w-full flex justify-center items-center overflow-hidden -mt-10">
                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($confirmation_image_url); ?>" class="object-contain w-auto h-52 scale-150" alt="<?php echo esc_attr($confirmation_title); ?>" title="<?php echo esc_attr($confirmation_title); ?>">
            </div>
            <div class="text-[#000000A3] font-semibold text-lg uppercase -mt-10 z-10 relative">
                <?php echo esc_html($confirmation_title); ?>
            </div>
            <h1 id="bookingIdDisplay" class="text-[#CB122D] font-semibold text-2xl">Your Booking ID #PET-47291</h1>
            <p class="md:w-8/12 mx-auto text-center text-[#000000A3] md:text-lg text-base font-semibold capitalize">
                <?php echo esc_html($confirmation_description); ?>
            </p>
            <div class="pt-2 flex flex-col text-center">
                <div class="font-semibold text-lg text-[#000000] capitalize">
                    <?php echo esc_html($need_anything_title); ?>
                </div>
                <div class="font-semibold text-lg text-[#000000] capitalize">
                    <?php echo esc_html($browse_text); ?> <a href="<?php echo esc_url($services_link_url); ?>" class="bg-gradient-to-br from-[#CB122D] to-[#650916] bg-clip-text text-transparent hover:underline"><?php echo esc_html($services_link_text); ?></a> <a href="<?php echo esc_url($offers_link_url); ?>" class="bg-gradient-to-br from-[#CB122D] to-[#650916] bg-clip-text text-transparent hover:underline"><?php echo esc_html($offers_link_text); ?></a> <a href="<?php echo esc_url($experts_link_url); ?>" class="bg-gradient-to-br from-[#CB122D] to-[#650916] bg-clip-text text-transparent hover:underline"><?php echo esc_html($experts_link_text); ?></a> <?php echo esc_html($available_text); ?>
                </div>
            </div>
            <a href="<?php echo esc_url($homepage_url); ?>" class="text-white bg-[#C8102E] h-[3.188rem] flex justify-center items-center gap-2 px-8 font-bold text-lg hover:bg-[#650916] duration-500 transition-colors">
                <?php echo esc_html($homepage_button_text); ?>
                <span>
                    <img src="<?php echo esc_url($img_url); ?>homepage-arrow-icon.svg" alt="arrow" class="size-5" width="20" height="20">
                </span>
            </a>
        </div>
    </div>
</div>

<script>
// Display Booking ID from URL parameter
(function() {
    'use strict';
    
    // Get Booking ID from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const bookingId = urlParams.get('booking_id');
    
    const bookingIdDisplay = document.getElementById('bookingIdDisplay');
    if (bookingIdDisplay) {
        if (bookingId) {
            bookingIdDisplay.textContent = 'Your Booking ID #' + bookingId;
        }
        // If no Booking ID in URL, keep the default text
    }
})();
</script>

<?php get_footer(); ?>