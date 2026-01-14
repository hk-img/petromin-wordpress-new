<?php
/* Template Name: booking confirmed page */
get_header();

// Get theme assets directory URL for images
$img_url = get_template_directory_uri() . '/assets/img/';

// Get homepage URL
$homepage_url = esc_url(home_url('/'));

?>
<div class="bg-white w-full relative md:py-20 py-12">
    <div class="view">
        <div class="w-full relative flex flex-col items-center justify-center gap-y-6">
            <div class="w-full flex justify-center items-center overflow-hidden">
                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($img_url); ?>booking-confirmed.gif" class="object-contain w-auto h-52 scale-150" alt="Booking Confirmed" title="Booking Confirmed">
            </div>
            <div class="text-[#000000A3] font-semibold text-lg uppercase -mt-10 z-10 relative">
                Booking Confirmed
            </div>
            <h1 id="bookingIdDisplay" class="text-[#CB122D] font-semibold text-2xl">Your Booking ID #PET-47291</h1>
            <p class="md:w-8/12 mx-auto text-center text-[#000000A3] md:text-lg text-base font-semibold capitalize">
                A member of our team will reach out shortly to reconfirm your slot and share the next steps. Thank you for choosing us — we appreciate your time and trust.
            </p>
            <div class="pt-2 flex flex-col text-center">
                <div class="font-semibold text-lg text-[#000000] capitalize">Need anything else today?</div>
                <div class="font-semibold text-lg text-[#000000] capitalize">
                    Browse <a href="<?php echo esc_url(home_url('/services')); ?>" class="bg-gradient-to-br from-[#CB122D] to-[#650916] bg-clip-text text-transparent hover:underline">services,</a> <a href="<?php echo esc_url(home_url('/offers')); ?>" class="bg-gradient-to-br from-[#CB122D] to-[#650916] bg-clip-text text-transparent hover:underline">Latest offers,</a> <a href="<?php echo esc_url(home_url('/experts')); ?>" class="bg-gradient-to-br from-[#CB122D] to-[#650916] bg-clip-text text-transparent hover:underline">And Experts</a> available in your city.
                </div>
            </div>
            <a href="<?php echo esc_url($homepage_url); ?>" class="text-white bg-[#C8102E] h-[3.188rem] flex justify-center items-center gap-2 px-8 font-bold text-lg hover:bg-[#650916] duration-500 transition-colors">
                Go to Homepage
                <span>
                    <img src="<?php echo esc_url($img_url); ?>homepage-arrow-icon.svg" alt="arrow" class="size-5" width="20" height="20">
                </span>
            </a>
        </div>
    </div>
</div>

<script>
// Display booking ID from sessionStorage if available (before it was cleared)
(function() {
    'use strict';
    
    // Try to get booking ID from URL parameter or other source
    // Since sessionStorage is cleared, we can check URL params or use a fallback
    const urlParams = new URLSearchParams(window.location.search);
    const bookingId = urlParams.get('booking_id');
    
    const bookingIdDisplay = document.getElementById('bookingIdDisplay');
    if (bookingIdDisplay) {
        if (bookingId) {
            bookingIdDisplay.textContent = 'Your Booking ID #' + bookingId;
        }
        // If no booking ID in URL, keep the default text
    }
})();
</script>

<?php get_footer(); ?>