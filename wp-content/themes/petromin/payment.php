<?php
/* Template Name: payment page */
get_header();

// Get theme assets directory URL for images
$img_url = get_template_directory_uri() . '/assets/img/';

// Get slot page URL
function get_slot_page_url() {
    // Try to find page by slug 'slot'
    $slot_page = get_page_by_path('slot');
    if ($slot_page) {
        return get_permalink($slot_page->ID);
    }
    
    // Try to find page by template name
    $slot_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'slot.php',
        'number' => 1,
        'post_status' => 'publish'
    ));
    if (!empty($slot_pages)) {
        return get_permalink($slot_pages[0]->ID);
    }
    
    return home_url('/slot');
}
$slot_page_url = get_slot_page_url();

// Get verify page URL
function get_verify_page_url() {
    // Try to find page by slug 'verify'
    $verify_page = get_page_by_path('verify');
    if ($verify_page) {
        return get_permalink($verify_page->ID);
    }
    
    // Try to find page by template name
    $verify_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'verify.php',
        'number' => 1,
        'post_status' => 'publish'
    ));
    if (!empty($verify_pages)) {
        return get_permalink($verify_pages[0]->ID);
    }
    
    return home_url('/verify');
}
$verify_page_url = get_verify_page_url();

// Get workstation page URL
function get_workstation_page_url() {
    // Try to find page by slug 'workstation'
    $workstation_page = get_page_by_path('workstation');
    if ($workstation_page) {
        return get_permalink($workstation_page->ID);
    }
    
    // Try to find page by template name
    $workstation_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'workstation.php',
        'number' => 1,
        'post_status' => 'publish'
    ));
    if (!empty($workstation_pages)) {
        return get_permalink($workstation_pages[0]->ID);
    }
    
    return home_url('/workstation');
}
$workstation_page_url = get_workstation_page_url();

// Get cost-estimator page URL
function get_cost_estimator_page_url() {
    // Try different template name formats
    $template_names = array('cost-estimator.php', 'page-cost-estimator.php');
    
    foreach ($template_names as $template_name) {
        $cost_estimator_page = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $template_name,
            'number' => 1,
            'post_status' => 'publish'
        ));
        if (!empty($cost_estimator_page)) {
            return get_permalink($cost_estimator_page[0]->ID);
        }
    }
    
    // Fallback: try to find page by slug or title
    $cost_estimator_page = get_page_by_path('cost-estimator');
    if ($cost_estimator_page) {
        return get_permalink($cost_estimator_page->ID);
    }
    
    return home_url('/');
}
$cost_estimator_page_url = get_cost_estimator_page_url();

?>
<style>
/* Hide page content initially until validation passes */
body.payment-page {
    visibility: hidden;
    opacity: 0;
}
body.payment-page.validation-passed {
    visibility: visible;
    opacity: 1;
    transition: opacity 0.3s ease-in;
}
</style>
<script>
// Immediate validation before page renders
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const costEstimatorUrl = '<?php echo esc_js($cost_estimator_page_url); ?>';
    const workstationPageUrl = '<?php echo esc_js($workstation_page_url); ?>';
    const slotPageUrl = '<?php echo esc_js($slot_page_url); ?>';
    
    // Function to get cart from sessionStorage
    function getCart() {
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                return JSON.parse(cartData);
            }
        } catch (e) {
            return null;
        }
        return null;
    }
    
    // Validate required vehicle data
    function validateVehicleData() {
        const cart = getCart();
        
        // Check if cart exists
        if (!cart || !cart.vehicle) {
            return false;
        }
        
        // Check for required fields: brand, model, fuel
        const brand = cart.vehicle.brand && cart.vehicle.brand.trim() !== '';
        const model = cart.vehicle.model && cart.vehicle.model.trim() !== '';
        const fuel = cart.vehicle.fuel && cart.vehicle.fuel.trim() !== '';
        
        // Return true only if all three are present
        return brand && model && fuel;
    }
    
    // Validate phone verification
    function validatePhoneVerification() {
        const cart = getCart();
        return cart && cart.phone_verified === true;
    }
    
    // Validate service center data
    function validateServiceCenterData() {
        const cart = getCart();
        return cart && cart.service_center;
    }
    
    // Validate date and time slot
    function validateDateAndTimeSlot() {
        const cart = getCart();
        return cart && cart.selected_date && cart.selected_time_slot;
    }
    
    // Add class to body for payment page
    if (document.body) {
        document.body.classList.add('payment-page');
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('payment-page');
        });
    }
    
    // Check validation immediately
    const cart = getCart();
    
    // First check if vehicle data is missing - redirect to cost-estimator
    if (!validateVehicleData()) {
        // Redirect immediately without showing content
        window.location.replace(costEstimatorUrl);
        return; // Exit early
    }
    
    // Then check if phone is not verified - redirect to verify
    if (!validatePhoneVerification()) {
        // Redirect immediately without showing content
        const verifyPageUrl = '<?php echo esc_js($verify_page_url); ?>';
        if (verifyPageUrl && verifyPageUrl !== '') {
            window.location.replace(verifyPageUrl);
        }
        return; // Exit early
    }
    
    // Then check if service center is missing - redirect to workstation
    if (!validateServiceCenterData()) {
        // Redirect immediately without showing content
        window.location.replace(workstationPageUrl);
        return; // Exit early
    }
    
    // Then check if date and time slot are missing - redirect to slot
    if (!validateDateAndTimeSlot()) {
        // Redirect immediately without showing content
        window.location.replace(slotPageUrl);
        return; // Exit early
    }
    
    // If all validations pass, show content
    if (document.body) {
        document.body.classList.add('validation-passed');
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('validation-passed');
        });
    }
    
    // Additional check on page visibility change (when user navigates back)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page became visible - check again
            const currentCart = getCart();
            
            if (!validateVehicleData()) {
                if (costEstimatorUrl && costEstimatorUrl !== '') {
                    window.location.replace(costEstimatorUrl);
                }
            } else if (!validatePhoneVerification()) {
                const verifyPageUrl = '<?php echo esc_js($verify_page_url); ?>';
                if (verifyPageUrl && verifyPageUrl !== '') {
                    window.location.replace(verifyPageUrl);
                }
            } else if (!validateServiceCenterData()) {
                if (workstationPageUrl && workstationPageUrl !== '') {
                    window.location.replace(workstationPageUrl);
                }
            } else if (!validateDateAndTimeSlot()) {
                if (slotPageUrl && slotPageUrl !== '') {
                    window.location.replace(slotPageUrl);
                }
            }
        }
    });
    
    // Additional check on window focus (when user switches back to tab)
    window.addEventListener('focus', function() {
        const currentCart = getCart();
        
        if (!validateVehicleData()) {
            if (costEstimatorUrl && costEstimatorUrl !== '') {
                window.location.replace(costEstimatorUrl);
            }
        } else if (!validatePhoneVerification()) {
            const verifyPageUrl = '<?php echo esc_js($verify_page_url); ?>';
            if (verifyPageUrl && verifyPageUrl !== '') {
                window.location.replace(verifyPageUrl);
            }
        } else if (!validateServiceCenterData()) {
            if (workstationPageUrl && workstationPageUrl !== '') {
                window.location.replace(workstationPageUrl);
            }
        } else if (!validateDateAndTimeSlot()) {
            if (slotPageUrl && slotPageUrl !== '') {
                window.location.replace(slotPageUrl);
            }
        }
    });
})();
</script>

<header class="w-full md:flex hidden justify-center items-center top-0 right-0  bg-white font-poppins fixed z-40 h-24 border-b border-[#E5E5E5]">
    <div class="view w-full relative flex justify-between items-center">
        <div class="md:w-1/4 h-[4.125rem] relative flex  py-5 bg-white -skew-x-[18deg] origin-top">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="lg:w-auto w-auto flex items-center skew-x-[18deg] h-full">
                <img src="<?php echo $img_url; ?>petromin-logo-300x75-1.webp" alt="desktop logo" title="desktop logo" id="dynamicImage" class="w-44 h-auto" loading="eager" fetchpriority="high" decodic="async">
            </a>
        </div>
        <div class="w-auto h-[4.125rem] bg-white relative lg:flex lg:flex-row flex-col  items-center justify-center hidden  lg:px-[1.875rem] origin-bottom">
            <div class="flex items-center justify-center">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col items-center">
                        <span class="text-[#20BD99] font-medium text-sm tracking-wide border-b-2 border-[#20BD99]">
                            VERIFY
                        </span>
                    </div>
                    <div class="border-t border-dashed border-[#20BD99] w-16"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-[#20BD99] font-medium text-sm tracking-wide border-b-2 border-[#20BD99]">
                            WORKSTATION
                        </span>
                    </div>
                    <div class="border-t border-dashed border-[#20BD99] w-16"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-[#20BD99] font-medium text-sm tracking-wide border-b-2 border-[#20BD99]">
                            SLOT
                        </span>
                    </div>
                    <div class="border-t border-dashed border-[#20BD99] w-16"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-[#20BD99] font-medium text-sm tracking-wide border-b-2 border-[#20BD99]">
                            PAYMENT
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-1/4 relative flex justify-end">
            <img fetchpriority="low" loading="lazy" src="<?php echo $img_url; ?>secureImg.webp" class="w-40 h-auto object-contain" alt="">
        </div>
    </div>
</header>

<div class="view border-y z-30 border-[#E5E5E5] py-6 md:hidden block md:relative sticky top-0 inset-x-0 bg-white">
    <a href="" class="flex items-center gap-4 uppercase text-[#121212] text-lg font-medium">
        <span>
            <img src="<?php echo $img_url; ?>back-arrow.svg" alt="back arrow" class="w-[0.5625rem] h-[0.9375rem]" />
        </span>
        CHECKOUT
    </a>
</div>


<section class="bg-white md:py-14 pt-6 pb-24">
    <div class="view w-full md:pt-12">
        <div class="flex md:flex-row flex-col gap-6 relative">
            <div class="md:w-[70%] w-full">
                <div class="flex flex-col gap-y-6">
                    <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Verified Mobile Number</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Your mobile number has been verified successfully</p>
                        </div>
                        <div class="w-full bg-[#F1FAF1] border border-[#D1EAD1] p-6 flex justify-between items-center md:rounded-none rounded-lg">
                            <div class="flex items-center gap-3">
                                <span>
                                    <img src="<?php echo esc_url($img_url); ?>success-check-icon.svg" alt="success check" class="size-9" />
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div class="text-base text-[#2F2F2F] font-semibold">Mobile Verified <span class="max-md:hidden">Successfully</span></div>
                                    <div id="verifiedPhoneNumberSlot" class="text-[#637083] font-normal text-xs">+91 -</div>
                                </div>
                            </div>
                            <a href="" id="changeMobileNumberBtnSlot" class="text-[#6B6B6B] font-medium text-sm duration-300 hover:underline">Change</a>
                        </div>
                    </div>
                    <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Selected Service Center</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Your selected service center location</p>
                        </div>
                        <div class="w-full bg-[#F1FAF1] border border-[#D1EAD1] p-6 flex justify-between items-center md:rounded-none rounded-lg">
                            <div class="flex items-center gap-3">
                                <span>
                                    <img src="<?php echo esc_url($img_url); ?>success-check-icon.svg" alt="success check" class="size-9" />
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div id="selectedServiceCenterName" class="text-base text-[#2F2F2F] font-semibold empty:hidden"></div>
                                    <div id="selectedServiceCenterLocation" class="text-[#637083] font-normal text-xs empty:hidden"></div>
                                </div>
                            </div>
                            <a href="" id="changeServiceCenterBtn" class="text-[#6B6B6B] font-medium text-sm duration-300 hover:underline">Change</a>
                        </div>
                    </div>
                    <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Selected Date & Time</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Your selected date and time slot</p>
                        </div>
                        <div class="w-full bg-[#F1FAF1] border border-[#D1EAD1] p-6 flex justify-between items-center md:rounded-none rounded-lg">
                            <div class="flex items-center gap-3">
                                <span>
                                    <img src="<?php echo esc_url($img_url); ?>success-check-icon.svg" alt="success check" class="size-9" />
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div id="selectedDateAndTime" class="text-base text-[#2F2F2F] font-semibold empty:hidden"></div>
                                    <div id="selectedTimeSlot" class="text-[#637083] font-normal text-xs empty:hidden"></div>
                                </div>
                            </div>
                            <a href="" id="changeDateAndTimeBtn" class="text-[#6B6B6B] font-medium text-sm duration-300 hover:underline">Change</a>
                        </div>
                    </div>
                    <div id="choosePaymentMethodSection" class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Choose Payment Method</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Select how you'd like to pay for your service</p>
                        </div>
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
                            <label for="paymentMethod1" class="w-full border border-[#E5E5E5] cursor-pointer hover:md:border-[#6B6B6B] has-[:checked]:!border-[#CB122D] has-[:checked]:!border-2 has-[:checked]:bg-[#FFF0F0]/10 p-6 flex flex-col gap-y-4 md:rounded-none rounded-lg">
                                <input type="radio" name="paymentMethod" id="paymentMethod1" class="hidden" value="Pay at Service Center" />
                                <div>
                                    <svg class="size-7" width="28" height="28" viewBox="0 0 27 28" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.0261 8.16459V4.66541C21.0261 4.35607 20.9095 4.05939 20.702 3.84065C20.4945 3.62191 20.213 3.49902 19.9196 3.49902H5.53439C4.94744 3.49902 4.38453 3.7448 3.96949 4.18228C3.55445 4.61976 3.32129 5.21311 3.32129 5.8318C3.32129 6.4505 3.55445 7.04385 3.96949 7.48133C4.38453 7.91881 4.94744 8.16459 5.53439 8.16459H22.1327C22.4261 8.16459 22.7076 8.28747 22.9151 8.50621C23.1226 8.72495 23.2392 9.02163 23.2392 9.33098V13.9965M23.2392 13.9965H19.9196C19.3326 13.9965 18.7697 14.2423 18.3547 14.6798C17.9396 15.1173 17.7064 15.7106 17.7064 16.3293C17.7064 16.948 17.9396 17.5414 18.3547 17.9788C18.7697 18.4163 19.3326 18.6621 19.9196 18.6621H23.2392C23.5327 18.6621 23.8141 18.5392 24.0217 18.3205C24.2292 18.1017 24.3458 17.8051 24.3458 17.4957V15.1629C24.3458 14.8536 24.2292 14.5569 24.0217 14.3382C23.8141 14.1194 23.5327 13.9965 23.2392 13.9965Z"
                                            stroke="#6B6B6B" stroke-width="1.74959" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M3.32129 5.83203V22.1615C3.32129 22.7802 3.55445 23.3735 3.96949 23.811C4.38453 24.2485 4.94744 24.4943 5.53439 24.4943H22.1327C22.4261 24.4943 22.7076 24.3714 22.9151 24.1527C23.1226 23.9339 23.2392 23.6372 23.2392 23.3279V18.6623"
                                            stroke="#6B6B6B" stroke-width="1.74959" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-[#222222]">Pay at Service Center</h3>
                                <p class="text-sm font-normal text-[#555555]">Cash, Card, or UPI at the service center</p>
                                <div class="bg-[#F1FAF1] w-fit text-[#3DA683] py-2 px-4 font-medium rounded-xl">Full amount payable at service center</div>
                                <div class="w-full relative flex items-center gap-3 border-t border-[#F0F0F0] pt-3">
                                    <div class="flex items-center gap-1 text-[#999999] font-medium text-sm">
                                        <span>
                                            <svg class="size-4" width="16" height="16" viewBox="0 0 16 16"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_4118_5478)">
                                                    <path
                                                        d="M13.3274 3.33203H2.66485C1.92876 3.33203 1.33203 3.92876 1.33203 4.66485V11.329C1.33203 12.0651 1.92876 12.6618 2.66485 12.6618H13.3274C14.0635 12.6618 14.6602 12.0651 14.6602 11.329V4.66485C14.6602 3.92876 14.0635 3.33203 13.3274 3.33203Z"
                                                        stroke="#999999" stroke-width="0.999616"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M1.33203 6.66406H14.6602" stroke="#999999"
                                                        stroke-width="0.999616" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_4118_5478">
                                                        <rect width="15.9939" height="15.9939" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </span>
                                        UPI
                                    </div>
                                    <div class="flex items-center gap-1 text-[#999999] font-medium text-sm">
                                        <span>
                                            <svg class="size-4" width="16" height="16" viewBox="0 0 16 16"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_4118_5478)">
                                                    <path
                                                        d="M13.3274 3.33203H2.66485C1.92876 3.33203 1.33203 3.92876 1.33203 4.66485V11.329C1.33203 12.0651 1.92876 12.6618 2.66485 12.6618H13.3274C14.0635 12.6618 14.6602 12.0651 14.6602 11.329V4.66485C14.6602 3.92876 14.0635 3.33203 13.3274 3.33203Z"
                                                        stroke="#999999" stroke-width="0.999616"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M1.33203 6.66406H14.6602" stroke="#999999"
                                                        stroke-width="0.999616" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_4118_5478">
                                                        <rect width="15.9939" height="15.9939" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </span>
                                        Card
                                    </div>
                                    <div class="flex items-center gap-1 text-[#999999] font-medium text-sm">
                                        <span>
                                            <svg class="size-4" width="16" height="16" viewBox="0 0 16 16"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.6626 4.66467V2.66543C12.6626 2.48869 12.5924 2.31919 12.4674 2.19421C12.3424 2.06923 12.1729 1.99902 11.9962 1.99902H3.33282C2.97933 1.99902 2.64033 2.13945 2.39037 2.3894C2.14042 2.63935 2 2.97836 2 3.33184C2 3.68533 2.14042 4.02434 2.39037 4.27429C2.64033 4.52424 2.97933 4.66467 3.33282 4.66467H13.329C13.5057 4.66467 13.6752 4.73488 13.8002 4.85985C13.9252 4.98483 13.9954 5.15433 13.9954 5.33108V7.99672M13.9954 7.99672H11.9962C11.6427 7.99672 11.3037 8.13714 11.0537 8.38709C10.8038 8.63705 10.6633 8.97605 10.6633 9.32954C10.6633 9.68303 10.8038 10.022 11.0537 10.272C11.3037 10.5219 11.6427 10.6624 11.9962 10.6624H13.9954C14.1721 10.6624 14.3416 10.5921 14.4666 10.4672C14.5916 10.3422 14.6618 10.1727 14.6618 9.99595V8.66313C14.6618 8.48639 14.5916 8.31688 14.4666 8.19191C14.3416 8.06693 14.1721 7.99672 13.9954 7.99672Z"
                                                    stroke="#999999" stroke-width="0.999616" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M2 3.33203V12.6618C2 13.0153 2.14042 13.3543 2.39037 13.6042C2.64033 13.8542 2.97933 13.9946 3.33282 13.9946H13.329C13.5057 13.9946 13.6752 13.9244 13.8002 13.7994C13.9252 13.6744 13.9954 13.5049 13.9954 13.3282V10.6625"
                                                    stroke="#999999" stroke-width="0.999616" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        Wallet
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:w-[30%] w-full md:block hidden">
                <div class="w-full flex flex-col bg-white shadow-[0_0.125rem_0.25rem_-0.125rem_#919191] border border-[#E5E5E5] md:sticky md:top-24">
                    <div class="w-full flex items-center h-[3.125rem] p-6 bg-gradient-to-l  from-[#CB122D] to-[#650916]  md:text-lg text-base font-bold  text-white">Booking Summary</div>
                    <div class="flex flex-col gap-y-6 bg-white p-6">
                        <div class="w-full flex flex-row">
                            <div class="w-1/3 text-[#A6A6A6] uppercase  text-xs font-semibold">Vehicle</div>
                            <div class="w-2/3 flex flex-col gap-y-1">
                                <div id="bookingVehicleName" class="text-[#2F2F2F] font-bold text-sm empty:hidden"></div>
                                <div id="bookingVehicleFuel" class="font-normal text-sm text-[#6B6B6B] empty:hidden"></div>
                            </div>
                        </div>
                        <div class="border-t border-[#EFEFEF] pt-6">
                            <div class="w-full flex flex-row ">
                                <div class="w-1/3 text-[#A6A6A6] uppercase  text-xs font-semibold">Services</div>
                                <div id="bookingServicesList" class="w-2/3 flex flex-col gap-y-3">
                                    <!-- Services will be populated dynamically -->
                                </div>
                            </div>
                        </div>
                        <div class="w-full flex flex-row border-t border-[#EFEFEF] pt-6">
                            <div class="w-1/3 text-[#A6A6A6] uppercase text-xs font-semibold">Location
                            </div>
                            <div class="w-2/3 flex flex-col gap-y-1">
                                <div id="bookingServiceCenterName" class="text-[#2F2F2F] font-bold text-sm empty:hidden"></div>
                                <div id="bookingServiceCenterCity" class="font-normal text-sm text-[#6B6B6B] empty:hidden"></div>
                            </div>
                        </div>
                        <div class="w-full flex flex-row border-t border-[#EFEFEF] pt-6">
                            <div class="w-1/3 text-[#A6A6A6] uppercase text-xs font-semibold">Date & Time</div>
                            <div class="w-2/3 flex flex-col gap-y-1">
                                <div id="bookingDate" class="text-[#2F2F2F] font-bold text-sm empty:hidden"></div>
                                <div id="bookingTimeSlot" class="font-normal text-sm text-[#6B6B6B] empty:hidden"></div>
                            </div>
                        </div>
                        <div id="bookingPaymentMethod" class="w-full flex flex-row border-t border-[#EFEFEF] pt-6 hidden">
                            <div class="w-1/3 text-[#A6A6A6] uppercase text-xs font-semibold">Payment</div>
                            <div class="w-2/3 flex flex-col gap-y-1">
                                <div id="bookingPaymentMethodText" class="text-[#2F2F2F] font-bold text-sm empty:hidden">-</div>
                            </div>
                        </div>
                        <div class="border-t border-[#EFEFEF] pt-6">
                            <p id="bookingDisclaimer" class="text-xs  bg-[#FF83000D] p-4 font-medium flex  gap-2 border border-[#DF7300] text-[#DF7300]">
                                <span class="inline-flex">
                                    <img src="<?php echo esc_url($img_url); ?>info-icon-disclaimer.svg" alt="info" class="size-[0.813rem] shrink-0" />
                                </span>
                                <span id="disclaimerText">This is an estimated price, Final price may vary based on your car model and condition.</span>
                            </p>
                        </div>
                        <div class="">
                            <div class="w-full flex flex-col gap-y-2 justify-between">
                                <div class="w-full flex justify-between items-center gap-2">
                                    <div class="text-[#2F2F2F] text-sm font-bold">Total Amount</div>
                                    <div id="bookingTotalAmount" class="text-[#C8102E] lg:text-2xl md:text-xl text-lg text-base font-bold">₹0</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-[#EFEFEF] pt-6">
                            <button type="button" id="confirmBookingBtnDesktop" class="w-full flex justify-center items-center gap-2 bg-[#AFAFAF] h-[2.875rem] text-white font-semibold md:rounded-none rounded-lg text-base hover:bg-[#CB122D] duration-500 disabled:bg-gray-400 disabled:cursor-not-allowed relative" disabled="true">
                                <span id="confirmBookingBtnDesktopText" class="flex items-center gap-2">
                                    Confirm Booking
                                    <svg class="size-[1.125rem]" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.75 9H14.25" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9 3.75L14.25 9L9 14.25" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span id="confirmBookingBtnDesktopLoader" class="hidden absolute inset-0 flex items-center justify-center gap-2">
                                    <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                    <span>Processing...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="footer bg-white border-t border-[#E5E7EB] py-8 w-full md:block hidden">
    <div class="view">
        <div class="w-full flex justify-between items-center gap-2">
            <div class="w-1/2 relative">
                <img src="<?php echo esc_url($img_url); ?>footerPayImg.webp" class="" alt="" title="">
            </div>
            <div class="w-1/2 flex items-center justify-end gap-1">
                <a href="" class="text-sm font-medium #121212 duration-300 hover:underline">Need Help?</a>
                <a href="" class="text-sm font-medium #121212 duration-300 hover:underline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<div class="view h-[4.938rem] group/check fixed bottom-0 inset-x-0 md:hidden flex justify-between items-center bg-white border border-[#E5E5E5] shadow-[0_-0.25rem_1rem_0_#00000014]">
    <label for="price" class="cursor-pointer w-1/2 flex items-center font-bold text-base text-[#C8102E] gap-2">
        <input type="checkbox" name="priceCheck" id="price" class="hidden" />
        <span id="mobileTotalAmount">₹0</span>
        <span class="group-has-[:checked]/check:rotate-180 duration-500">
            <img src="<?php echo esc_url($img_url); ?>dropdown-arrow-down.svg" alt="dropdown" width="11" height="6" />
        </span>
        <div class="view bg-white w-full duartion-300 group-has-[#price:checked]/check:flex hidden py-6 flex-col gap-y-4 absolute bottom-full inset-x-0 shadow-[0_-0.25rem_1rem_0_#00000014] border-t border-[#E5E5E5]">
            <div class="flex flex-col gap-2">
                <div class="text-[#AFAFAF] text-xs font-bold uppercase">Vehicle</div>
                <div id="mobileVehicleName" class="text-[#2F2F2F] font-bold text-sm uppercase empty:hidden"></div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="text-[#AFAFAF] text-xs font-bold uppercase">Services</div>
                <div id="mobileServicesList" class="flex flex-col gap-2">
                    <!-- Services will be populated dynamically -->
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="text-[#AFAFAF] text-xs font-bold uppercase">Location</div>
                <div id="mobileLocation" class="text-[#2F2F2F] font-normal text-sm empty:hidden"></div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="text-[#AFAFAF] text-xs font-bold uppercase">Date & Time</div>
                <div id="mobileDateAndTime" class="text-[#2F2F2F] font-normal text-sm empty:hidden"></div>
            </div>
            <div id="mobilePaymentMethod" class="flex flex-col gap-2 hidden">
                <div class="text-[#AFAFAF] text-xs font-bold uppercase">Payment</div>
                <div id="mobilePaymentMethodText" class="text-[#2F2F2F] font-normal text-sm empty:hidden">-</div>
            </div>
        </div>
    </label>
    <button type="button" id="confirmBookingBtnMobile" class="w-1/2 bg-[#AFAFAF] w-full rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D] disabled:bg-gray-400 disabled:cursor-not-allowed relative" disabled="true">
        <span id="confirmBookingBtnMobileText">Confirm Booking</span>
        <span id="confirmBookingBtnMobileLoader" class="hidden absolute inset-0 flex items-center justify-center gap-2">
            <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
            <span class="text-xs">Processing...</span>
        </span>
    </button>
</div>

<script>
// Booking Summary Functionality - Populate from sessionStorage
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const imgUrl = '<?php echo esc_js($img_url); ?>';
    
    // Function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Function to format price
    function formatPrice(price, currency) {
        const numPrice = parseFloat(price || 0);
        if (isNaN(numPrice)) return '₹ 0';
        const symbol = currency === 'INR' ? '₹' : currency;
        return symbol + ' ' + numPrice.toLocaleString('en-IN');
    }
    
    // Function to get cart from sessionStorage
    function getCart() {
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                return JSON.parse(cartData);
            }
        } catch (e) {
            console.error('Error loading cart:', e);
        }
        return null;
    }
    
    // Function to populate booking summary
    function populateBookingSummary() {
        const cart = getCart();
        
        // Populate vehicle information (Desktop)
        const vehicleNameEl = document.getElementById('bookingVehicleName');
        const vehicleFuelEl = document.getElementById('bookingVehicleFuel');
        if (vehicleNameEl && vehicleFuelEl) {
            if (cart && cart.vehicle) {
                const vehicleName = (cart.vehicle.brand || '') + ' ' + (cart.vehicle.model || '');
                vehicleNameEl.textContent = vehicleName.trim() || '-';
                vehicleFuelEl.textContent = cart.vehicle.fuel || '-';
            } else {
                vehicleNameEl.textContent = '-';
                vehicleFuelEl.textContent = '-';
            }
        }
        
        // Populate services list (Desktop)
        const servicesListEl = document.getElementById('bookingServicesList');
        if (servicesListEl) {
            servicesListEl.innerHTML = '';
            if (cart && cart.items && cart.items.length > 0) {
                cart.items.forEach(function(item) {
                    const serviceName = item.service_name || item.name || 'Service';
                    const servicePrice = item.price || 0;
                    const serviceCurrency = item.currency || 'INR';
                    
                    const serviceItem = document.createElement('div');
                    serviceItem.className = 'flex justify-between items-start w-full gap-2';
                    serviceItem.innerHTML = `
                        <div class="text-[#2F2F2F] font-medium text-sm">${escapeHtml(serviceName)}</div>
                        <div class="text-[#2F2F2F] font-bold text-sm whitespace-nowrap">${formatPrice(servicePrice, serviceCurrency)}</div>
                    `;
                    servicesListEl.appendChild(serviceItem);
                });
            }
        }
        
        // Calculate and populate total amount (Desktop)
        const totalAmountEl = document.getElementById('bookingTotalAmount');
        if (totalAmountEl) {
            let total = 0;
            if (cart && cart.items && cart.items.length > 0) {
                cart.items.forEach(function(item) {
                    const price = parseFloat(item.price || 0);
                    if (!isNaN(price)) {
                        total += price;
                    }
                });
                const currency = cart.items[0].currency || 'INR';
                totalAmountEl.textContent = formatPrice(total, currency);
            } else {
                totalAmountEl.textContent = '₹ 0';
            }
        }
        
        // Populate service center location (Desktop)
        const bookingServiceCenterNameEl = document.getElementById('bookingServiceCenterName');
        const bookingServiceCenterCityEl = document.getElementById('bookingServiceCenterCity');
        if (bookingServiceCenterNameEl && bookingServiceCenterCityEl) {
            if (cart && cart.service_center) {
                const centerName = cart.service_center.name || '';
                const centerCity = cart.service_center.city || '';
                
                bookingServiceCenterNameEl.textContent = centerName || '-';
                bookingServiceCenterCityEl.textContent = centerCity || '-';
            } else {
                bookingServiceCenterNameEl.textContent = '-';
                bookingServiceCenterCityEl.textContent = '-';
            }
        }
        
        // Populate disclaimer
        const disclaimerTextEl = document.getElementById('disclaimerText');
        if (disclaimerTextEl) {
            if (cart && cart.items && cart.items.length > 0 && cart.items[0].disclaimer) {
                disclaimerTextEl.textContent = cart.items[0].disclaimer;
            } else {
                disclaimerTextEl.textContent = 'This is an estimated price, Final price may vary based on your car model and condition.';
            }
        }
        
        // Populate mobile version
        // Vehicle name
        const mobileVehicleNameEl = document.getElementById('mobileVehicleName');
        if (mobileVehicleNameEl) {
            if (cart && cart.vehicle) {
                const vehicleName = (cart.vehicle.brand || '') + ' ' + (cart.vehicle.model || '');
                mobileVehicleNameEl.textContent = (vehicleName.trim() || '-').toUpperCase();
            } else {
                mobileVehicleNameEl.textContent = '-';
            }
        }
        
        // Services list (Mobile)
        const mobileServicesListEl = document.getElementById('mobileServicesList');
        if (mobileServicesListEl) {
            mobileServicesListEl.innerHTML = '';
            if (cart && cart.items && cart.items.length > 0) {
                cart.items.forEach(function(item) {
                    const serviceName = item.service_name || item.name || 'Service';
                    const servicePrice = item.price || 0;
                    const serviceCurrency = item.currency || 'INR';
                    
                    const serviceItem = document.createElement('div');
                    serviceItem.className = 'flex justify-between w-full gap-2';
                    serviceItem.innerHTML = `
                        <div class="text-[#2F2F2F] font-normal text-sm">${escapeHtml(serviceName)}</div>
                        <div class="text-[#0A0A0A] font-bold text-sm">${formatPrice(servicePrice, serviceCurrency)}</div>
                    `;
                    mobileServicesListEl.appendChild(serviceItem);
                });
            }
        }
        
        // Total amount (Mobile)
        const mobileTotalAmountEl = document.getElementById('mobileTotalAmount');
        if (mobileTotalAmountEl) {
            let total = 0;
            if (cart && cart.items && cart.items.length > 0) {
                cart.items.forEach(function(item) {
                    const price = parseFloat(item.price || 0);
                    if (!isNaN(price)) {
                        total += price;
                    }
                });
                const currency = cart.items[0].currency || 'INR';
                mobileTotalAmountEl.textContent = formatPrice(total, currency);
            } else {
                mobileTotalAmountEl.textContent = '₹ 0';
            }
        }
        
        // Location (Mobile) - Show selected service center or city
        const mobileLocationEl = document.getElementById('mobileLocation');
        if (mobileLocationEl) {
            if (cart && cart.service_center) {
                // Show "centre name - city" format
                const centerName = cart.service_center.name || '';
                const centerCity = cart.service_center.city || '';
                if (centerName && centerCity) {
                    mobileLocationEl.textContent = centerName + ' - ' + centerCity;
                } else if (centerName) {
                    mobileLocationEl.textContent = centerName;
                } else if (centerCity) {
                    mobileLocationEl.textContent = centerCity;
                } else {
                    mobileLocationEl.textContent = '-';
                }
            } else if (cart && cart.vehicle && cart.vehicle.city) {
                mobileLocationEl.textContent = cart.vehicle.city;
            } else {
                mobileLocationEl.textContent = '-';
            }
        }
        
        // Date and Time (Desktop) - Populate from sessionStorage
        const bookingDateEl = document.getElementById('bookingDate');
        const bookingTimeSlotEl = document.getElementById('bookingTimeSlot');
        if (bookingDateEl || bookingTimeSlotEl) {
            if (cart && cart.selected_date && cart.selected_time_slot) {
                // Format date from d-m-Y to "DD MONTH, DAY" format (e.g., "23 MAY, SUN")
                const dateParts = cart.selected_date.split('-');
                if (dateParts.length === 3) {
                    const day = parseInt(dateParts[0], 10);
                    const month = parseInt(dateParts[1], 10) - 1; // Month is 0-indexed
                    const year = parseInt(dateParts[2], 10);
                    const dateObj = new Date(year, month, day);
                    
                    // Month names in uppercase
                    const monthNames = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
                    
                    // Day names in uppercase (short form)
                    const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
                    
                    // Get day of week (0 = Sunday, 1 = Monday, etc.)
                    const dayOfWeek = dateObj.getDay();
                    
                    // Format date as "DD MONTH, DAY" (e.g., "23 MAY, SUN")
                    const formattedDate = day + ' ' + monthNames[month] + ', ' + dayNames[dayOfWeek];
                    
                    if (bookingDateEl) {
                        bookingDateEl.textContent = formattedDate;
                    }
                } else {
                    if (bookingDateEl) {
                        bookingDateEl.textContent = cart.selected_date;
                    }
                }
                
                // Display time slot
                if (bookingTimeSlotEl) {
                    bookingTimeSlotEl.textContent = cart.selected_time_slot;
                }
            } else {
                if (bookingDateEl) {
                    bookingDateEl.textContent = '-';
                }
                if (bookingTimeSlotEl) {
                    bookingTimeSlotEl.textContent = '-';
                }
            }
        }
        
        // Date and Time (Mobile) - Populate from sessionStorage
        const mobileDateAndTimeEl = document.getElementById('mobileDateAndTime');
        if (mobileDateAndTimeEl) {
            if (cart && cart.selected_date && cart.selected_time_slot) {
                // Format date from d-m-Y to "DD DAY" format (e.g., "22 SAT")
                const dateParts = cart.selected_date.split('-');
                if (dateParts.length === 3) {
                    const day = parseInt(dateParts[0], 10);
                    const month = parseInt(dateParts[1], 10) - 1; // Month is 0-indexed
                    const year = parseInt(dateParts[2], 10);
                    const dateObj = new Date(year, month, day);
                    
                    // Day names in uppercase (short form)
                    const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
                    
                    // Get day of week (0 = Sunday, 1 = Monday, etc.)
                    const dayOfWeek = dateObj.getDay();
                    
                    // Format date and time as "DD DAY • TIME" (e.g., "22 SAT • 12:00 - 01:00 PM")
                    const formattedDateAndTime = day + ' ' + dayNames[dayOfWeek] + ' • ' + cart.selected_time_slot;
                    
                    mobileDateAndTimeEl.textContent = formattedDateAndTime;
                } else {
                    mobileDateAndTimeEl.textContent = cart.selected_date + ' • ' + (cart.selected_time_slot || '');
                }
            } else {
                mobileDateAndTimeEl.textContent = '-';
            }
        }
    }
    
    // Function to populate verified phone number
    function populateVerifiedPhoneNumber() {
        const verifiedPhoneEl = document.getElementById('verifiedPhoneNumberSlot');
        if (verifiedPhoneEl) {
            const cart = getCart();
            if (cart && cart.verified_phone) {
                const phoneNumber = cart.verified_phone.toString().trim();
                if (phoneNumber) {
                    verifiedPhoneEl.textContent = '+91 ' + phoneNumber;
                } else {
                    verifiedPhoneEl.textContent = '+91 -';
                }
            } else {
                verifiedPhoneEl.textContent = '+91 -';
            }
        }
    }
    
    // Function to populate selected service center details
    function populateSelectedServiceCenter() {
        const centerNameEl = document.getElementById('selectedServiceCenterName');
        const centerLocationEl = document.getElementById('selectedServiceCenterLocation');
        
        if (centerNameEl || centerLocationEl) {
            const cart = getCart();
            
            if (cart && cart.service_center) {
                const centerName = cart.service_center.name || '';
                const centerCity = cart.service_center.city || '';
                
                // Populate service center name
                if (centerNameEl) {
                    if (centerName && centerCity) {
                        centerNameEl.textContent = centerName;
                    } else if (centerName) {
                        centerNameEl.textContent = centerName;
                    } else {
                        centerNameEl.textContent = '-';
                    }
                }
                
                // Populate location
                if (centerLocationEl) {
                    if (centerCity) {
                        centerLocationEl.textContent = centerCity;
                    } else {
                        centerLocationEl.textContent = '-';
                    }
                }
            } else {
                // No service center selected
                if (centerNameEl) {
                    centerNameEl.textContent = '-';
                }
                if (centerLocationEl) {
                    centerLocationEl.textContent = '-';
                }
            }
        }
    }
    
    // Function to populate selected date and time slot
    function populateSelectedDateAndTime() {
        const cart = getCart();
        const selectedDateAndTimeEl = document.getElementById('selectedDateAndTime');
        const selectedTimeSlotEl = document.getElementById('selectedTimeSlot');
        
        if (selectedDateAndTimeEl || selectedTimeSlotEl) {
            if (cart && cart.selected_date && cart.selected_time_slot) {
                // Format date from d-m-Y to "DD MONTH, DAY" format (e.g., "23 MAY, SUN")
                const dateParts = cart.selected_date.split('-');
                if (dateParts.length === 3) {
                    const day = parseInt(dateParts[0], 10);
                    const month = parseInt(dateParts[1], 10) - 1; // Month is 0-indexed
                    const year = parseInt(dateParts[2], 10);
                    const dateObj = new Date(year, month, day);
                    
                    // Month names in uppercase
                    const monthNames = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
                    
                    // Day names in uppercase (short form)
                    const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
                    
                    // Get day of week (0 = Sunday, 1 = Monday, etc.)
                    const dayOfWeek = dateObj.getDay();
                    
                    // Format date as "DD MONTH, DAY" (e.g., "23 MAY, SUN")
                    const formattedDate = day + ' ' + monthNames[month] + ', ' + dayNames[dayOfWeek];
                    
                    if (selectedDateAndTimeEl) {
                        selectedDateAndTimeEl.textContent = formattedDate;
                    }
                } else {
                    if (selectedDateAndTimeEl) {
                        selectedDateAndTimeEl.textContent = cart.selected_date;
                    }
                }
                
                // Display time slot (already in correct format "12:00 - 01:00 PM")
                if (selectedTimeSlotEl) {
                    selectedTimeSlotEl.textContent = cart.selected_time_slot;
                }
            } else {
                if (selectedDateAndTimeEl) {
                    selectedDateAndTimeEl.textContent = '-';
                }
                if (selectedTimeSlotEl) {
                    selectedTimeSlotEl.textContent = '-';
                }
            }
        }
    }
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            populateBookingSummary();
            populateVerifiedPhoneNumber();
            populateSelectedServiceCenter();
            populateSelectedDateAndTime();
        });
    } else {
        populateBookingSummary();
        populateVerifiedPhoneNumber();
        populateSelectedServiceCenter();
        populateSelectedDateAndTime();
    }
})();

// Handle Change button click to remove service center and redirect to workstation page
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const workstationPageUrl = '<?php echo esc_js($workstation_page_url); ?>';
    
    function getCart() {
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                return JSON.parse(cartData);
            }
        } catch (e) {
            console.error('Error loading cart:', e);
        }
        return null;
    }
    
    function saveCart(cart) {
        try {
            sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {
            console.error('Error saving cart:', e);
        }
    }
    
    function handleChangeServiceCenterButtonClick() {
        // Get Change button by ID
        const changeServiceCenterBtn = document.getElementById('changeServiceCenterBtn');
        
        if (changeServiceCenterBtn) {
            changeServiceCenterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove service center from sessionStorage
                let cart = getCart();
                if (cart) {
                    delete cart.service_center;
                    // Also remove date and time slot since service center changed
                    delete cart.selected_date;
                    delete cart.selected_time_slot;
                    saveCart(cart);
                }
                
                // Redirect to workstation page using replace to prevent back navigation
                if (workstationPageUrl && workstationPageUrl !== '') {
                    window.location.replace(workstationPageUrl);
                } else {
                    console.error('Workstation page URL not found');
                }
            });
        }
    }
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleChangeServiceCenterButtonClick);
    } else {
        handleChangeServiceCenterButtonClick();
    }
})();

// Handle Change button click for mobile verification - redirect to verify page
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const verifyPageUrl = '<?php echo esc_js($verify_page_url); ?>';
    
    function getCart() {
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                return JSON.parse(cartData);
            }
        } catch (e) {
            console.error('Error loading cart:', e);
        }
        return null;
    }
    
    function saveCart(cart) {
        try {
            sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {
            console.error('Error saving cart:', e);
        }
    }
    
    function handleMobileChangeButtonClick() {
        // Get Change button by ID
        const changeMobileBtn = document.getElementById('changeMobileNumberBtnSlot');
        
        if (changeMobileBtn) {
            changeMobileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove verified phone from sessionStorage
                let cart = getCart();
                if (cart) {
                    delete cart.verified_phone;
                    delete cart.phone_verified;
                    // Also remove service center, date and time slot since phone changed
                    delete cart.service_center;
                    delete cart.selected_date;
                    delete cart.selected_time_slot;
                    saveCart(cart);
                }
                
                // Redirect to verify page using replace to prevent back navigation
                if (verifyPageUrl && verifyPageUrl !== '') {
                    window.location.replace(verifyPageUrl);
                } else {
                    console.error('Verify page URL not found');
                }
            });
        }
    }
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleMobileChangeButtonClick);
    } else {
        handleMobileChangeButtonClick();
    }
})();

// Handle Change button click for date and time - redirect to slot page
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const slotPageUrl = '<?php echo esc_js($slot_page_url); ?>';
    
    function getCart() {
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                return JSON.parse(cartData);
            }
        } catch (e) {
            console.error('Error loading cart:', e);
        }
        return null;
    }
    
    function saveCart(cart) {
        try {
            sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {
            console.error('Error saving cart:', e);
        }
    }
    
    function handleChangeDateAndTimeButtonClick() {
        // Get Change button by ID
        const changeDateAndTimeBtn = document.getElementById('changeDateAndTimeBtn');
        
        if (changeDateAndTimeBtn) {
            changeDateAndTimeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove date and time slot from sessionStorage
                let cart = getCart();
                if (cart) {
                    delete cart.selected_date;
                    delete cart.selected_time_slot;
                    saveCart(cart);
                }
                
                // Redirect to slot page using replace to prevent back navigation
                if (slotPageUrl && slotPageUrl !== '') {
                    window.location.replace(slotPageUrl);
                } else {
                    console.error('Slot page URL not found');
                }
            });
        }
    }
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleChangeDateAndTimeButtonClick);
    } else {
        handleChangeDateAndTimeButtonClick();
    }
})();

// Prevent back button navigation to slot page
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const slotPageUrl = '<?php echo esc_js($slot_page_url); ?>';
    
    function getCart() {
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                return JSON.parse(cartData);
            }
        } catch (e) {
            console.error('Error loading cart:', e);
        }
        return null;
    }
    
    const cart = getCart();
    if (cart && cart.selected_date && cart.selected_time_slot) {
        // User has selected date and time slot, prevent going back to slot page
        // Push a new state to prevent back navigation to slot page
        if (window.history && window.history.replaceState) {
            history.pushState({ page: 'payment' }, '', window.location.href);
            
            // Listen for popstate event (back/forward button)
            window.addEventListener('popstate', function(event) {
                // Check if trying to go back to slot page
                const currentCart = getCart();
                if (currentCart && currentCart.selected_date && currentCart.selected_time_slot) {
                    // Date and time slot are selected, don't allow going back to slot page
                    // Push forward again to stay on current page
                    history.pushState({ page: 'payment' }, '', window.location.href);
                    
                    // Also check URL and redirect if somehow on slot page
                    if (slotPageUrl && window.location.href.includes(slotPageUrl.split('/').pop())) {
                        window.location.replace(window.location.href.replace(slotPageUrl.split('/').pop(), 'payment'));
                    }
                }
            });
        }
    }
})();

// Payment Method Selection and Button Functionality
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    
    // Function to get cart from sessionStorage
    function getCart() {
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                return JSON.parse(cartData);
            }
        } catch (e) {
            console.error('Error loading cart:', e);
        }
        return null;
    }
    
    // Function to save cart to sessionStorage
    function saveCart(cart) {
        try {
            sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {
            console.error('Error saving cart:', e);
        }
    }
    
    // Function to enable buttons
    function enableButtons() {
        const desktopBtn = document.getElementById('confirmBookingBtnDesktop');
        const mobileBtn = document.getElementById('confirmBookingBtnMobile');
        
        if (desktopBtn) {
            desktopBtn.disabled = false;
            desktopBtn.classList.remove('bg-[#AFAFAF]');
            desktopBtn.classList.add('bg-[#C1122C]');
        }
        
        if (mobileBtn) {
            mobileBtn.disabled = false;
            mobileBtn.classList.remove('bg-[#AFAFAF]');
            mobileBtn.classList.add('bg-[#CB122D]');
        }
    }
    
    // Function to disable buttons
    function disableButtons() {
        const desktopBtn = document.getElementById('confirmBookingBtnDesktop');
        const mobileBtn = document.getElementById('confirmBookingBtnMobile');
        
        if (desktopBtn) {
            desktopBtn.disabled = true;
            desktopBtn.classList.remove('bg-[#C1122C]');
            desktopBtn.classList.add('bg-[#AFAFAF]');
        }
        
        if (mobileBtn) {
            mobileBtn.disabled = true;
            mobileBtn.classList.remove('bg-[#CB122D]');
            mobileBtn.classList.add('bg-[#AFAFAF]');
        }
    }
    
    // Function to show payment method in booking summary
    function showPaymentMethod(paymentMethod) {
        const desktopPaymentEl = document.getElementById('bookingPaymentMethod');
        const desktopPaymentTextEl = document.getElementById('bookingPaymentMethodText');
        const mobilePaymentEl = document.getElementById('mobilePaymentMethod');
        const mobilePaymentTextEl = document.getElementById('mobilePaymentMethodText');
        
        if (desktopPaymentEl && desktopPaymentTextEl) {
            desktopPaymentTextEl.textContent = paymentMethod;
            desktopPaymentEl.classList.remove('hidden');
        }
        
        if (mobilePaymentEl && mobilePaymentTextEl) {
            mobilePaymentTextEl.textContent = paymentMethod;
            mobilePaymentEl.classList.remove('hidden');
        }
    }
    
    // Function to hide payment method in booking summary
    function hidePaymentMethod() {
        const desktopPaymentEl = document.getElementById('bookingPaymentMethod');
        const mobilePaymentEl = document.getElementById('mobilePaymentMethod');
        
        if (desktopPaymentEl) {
            desktopPaymentEl.classList.add('hidden');
        }
        
        if (mobilePaymentEl) {
            mobilePaymentEl.classList.add('hidden');
        }
    }
    
    // Handle payment method radio button selection
    function handlePaymentMethodSelection() {
        const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
        
        paymentRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const paymentMethod = this.value || 'Pay at Service Center';
                    
                    // Save payment method to sessionStorage
                    let cart = getCart();
                    if (!cart) {
                        cart = { vehicle: {}, items: [] };
                    }
                    cart.payment_method = paymentMethod;
                    saveCart(cart);
                    
                    // Enable buttons
                    enableButtons();
                    
                    // Show payment method in booking summary
                    showPaymentMethod(paymentMethod);
                }
            });
        });
    }
    
    // Handle confirm booking button click
    function handleConfirmBooking() {
        const desktopBtn = document.getElementById('confirmBookingBtnDesktop');
        const mobileBtn = document.getElementById('confirmBookingBtnMobile');
        const desktopBtnText = document.getElementById('confirmBookingBtnDesktopText');
        const mobileBtnText = document.getElementById('confirmBookingBtnMobileText');
        const desktopBtnLoader = document.getElementById('confirmBookingBtnDesktopLoader');
        const mobileBtnLoader = document.getElementById('confirmBookingBtnMobileLoader');
        
        function showLoader() {
            // Show loader and hide text for desktop button
            if (desktopBtnText) desktopBtnText.classList.add('hidden');
            if (desktopBtnLoader) desktopBtnLoader.classList.remove('hidden');
            if (desktopBtn) {
                desktopBtn.disabled = true;
                desktopBtn.style.pointerEvents = 'none';
            }
            
            // Show loader and hide text for mobile button
            if (mobileBtnText) mobileBtnText.classList.add('hidden');
            if (mobileBtnLoader) mobileBtnLoader.classList.remove('hidden');
            if (mobileBtn) {
                mobileBtn.disabled = true;
                mobileBtn.style.pointerEvents = 'none';
            }
        }
        
        function hideLoader() {
            // Hide loader and show text for desktop button
            if (desktopBtnText) desktopBtnText.classList.remove('hidden');
            if (desktopBtnLoader) desktopBtnLoader.classList.add('hidden');
            if (desktopBtn) {
                desktopBtn.disabled = false;
                desktopBtn.style.pointerEvents = '';
            }
            
            // Hide loader and show text for mobile button
            if (mobileBtnText) mobileBtnText.classList.remove('hidden');
            if (mobileBtnLoader) mobileBtnLoader.classList.add('hidden');
            if (mobileBtn) {
                mobileBtn.disabled = false;
                mobileBtn.style.pointerEvents = '';
            }
        }
        
        function redirectToBookingConfirmed(bookingId) {
            // Get booking confirmed page URL
            const bookingConfirmedUrl = '<?php echo esc_url(home_url('/booking-confirmed')); ?>';
            
            // Clear sessionStorage
            try {
                sessionStorage.removeItem(CART_STORAGE_KEY);
                sessionStorage.removeItem('cost_estimator_previous_url');
            } catch (e) {
                // Error clearing sessionStorage
            }
            
            // Redirect to booking confirmed page with Booking ID as URL parameter
            if (bookingConfirmedUrl) {
                const url = new URL(bookingConfirmedUrl);
                if (bookingId) {
                    url.searchParams.set('booking_id', bookingId);
                }
                window.location.href = url.toString();
            } else {
                // Hide loader if redirect fails
                hideLoader();
            }
        }
        
        function confirmBooking() {
            // Show loader
            showLoader();
            
            // Get cart data
            const cart = getCart();
            if (!cart) {
                hideLoader();
                alert('Booking data not found. Please try again.');
                return;
            }
            
            // Get nonce and ajax URL
            const otpNonce = '<?php echo wp_create_nonce("otp_nonce"); ?>';
            const ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';
            
            // Prepare booking data
            const bookingData = {
                vehicle: cart.vehicle || {},
                items: cart.items || [],
                verified_phone: cart.verified_phone || '',
                phone_verified: cart.phone_verified || false,
                service_center: cart.service_center || {},
                selected_date: cart.selected_date || '',
                selected_time_slot: cart.selected_time_slot || '',
                payment_method: cart.payment_method || 'Pay at Service Center',
                service_category: cart.service_category || ''
            };
            
            // Call LeadSquared API
            const formData = new FormData();
            formData.append('action', 'confirm_booking_with_leadsquared');
            formData.append('nonce', otpNonce);
            formData.append('booking_data', JSON.stringify(bookingData));
            
            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success) {
                    const bookingId = data.data && data.data.booking_id ? data.data.booking_id : null;
                    // Redirect to booking confirmed page with Booking ID
                    redirectToBookingConfirmed(bookingId);
                } else {
                    // Hide loader on error
                    hideLoader();
                    const errorMsg = (data && data.data && data.data.message) ? data.data.message : 'Failed to confirm booking. Please try again.';
                    alert(errorMsg);
                }
            })
            .catch(error => {
                // Hide loader on error
                hideLoader();
                console.error('Error confirming booking:', error);
                alert('An error occurred. Please try again.');
            });
        }
        
        if (desktopBtn) {
            desktopBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!this.disabled) {
                    confirmBooking();
                }
            });
        }
        
        if (mobileBtn) {
            mobileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!this.disabled) {
                    confirmBooking();
                }
            });
        }
    }
    
    // Restore payment method selection on page load
    function restorePaymentMethod() {
        const cart = getCart();
        
        if (cart && cart.payment_method) {
            // Find and check the radio button with matching value
            const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
            paymentRadios.forEach(function(radio) {
                if (radio.value === cart.payment_method) {
                    radio.checked = true;
                    // Trigger change event to enable buttons and show payment method
                    radio.dispatchEvent(new Event('change'));
                }
            });
        }
    }
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            handlePaymentMethodSelection();
            handleConfirmBooking();
            restorePaymentMethod();
        });
    } else {
        handlePaymentMethodSelection();
        handleConfirmBooking();
        restorePaymentMethod();
    }
})();
</script>

<script>
// Smooth scroll to Choose Payment Method section after page load
(function() {
    'use strict';
    
    function scrollToPaymentMethodSection() {
        const paymentMethodSection = document.getElementById('choosePaymentMethodSection');
        if (paymentMethodSection) {
            // Calculate offset to account for fixed header
            const headerOffset = 100; // Adjust this value based on your header height
            const elementPosition = paymentMethodSection.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            
            // Smooth scroll
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }
    
    // Wait for page to fully load and then scroll
    // Small delay to ensure all content is rendered
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(scrollToPaymentMethodSection, 500);
        });
    } else {
        setTimeout(scrollToPaymentMethodSection, 500);
    }
})();
</script>

<?php get_footer(); ?>