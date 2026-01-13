<?php
/* Template Name: slot page */
get_header();

// Get theme assets directory URL for images
$img_url = get_template_directory_uri() . '/assets/img/';

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

// Get cost estimator page URL
function get_cost_estimator_page_url() {
    // Try different template name formats
    $template_names = array('cost-estimator.php', 'page-cost-estimator.php');
    
    foreach ($template_names as $template_name) {
        $cost_estimator_page = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $template_name,
            'number' => 1
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
    
    return home_url('/cost-estimator');
}
$cost_estimator_page_url = get_cost_estimator_page_url();

?>
<style>
/* Hide page content initially until validation passes */
body.slot-page {
    visibility: hidden;
    opacity: 0;
}
body.slot-page.validation-passed {
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
    
    // Validate service center data
    function validateServiceCenterData() {
        const cart = getCart();
        return cart && cart.service_center;
    }
    
    // Add class to body for slot page
    if (document.body) {
        document.body.classList.add('slot-page');
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('slot-page');
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
    
    // Then check if service center is missing - redirect to workstation
    if (!validateServiceCenterData()) {
        // Redirect immediately without showing content
        window.location.replace(workstationPageUrl);
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
            } else if (!validateServiceCenterData()) {
                if (workstationPageUrl && workstationPageUrl !== '') {
                    window.location.replace(workstationPageUrl);
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
        } else if (!validateServiceCenterData()) {
            if (workstationPageUrl && workstationPageUrl !== '') {
                window.location.replace(workstationPageUrl);
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
                    <div class="border-t border-dashed border-[#696B79] w-16"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-gray-400 font-medium text-sm tracking-wide">
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
    <a href="" id="changeServiceCenterBtn" class="flex items-center gap-4 uppercase text-[#121212] text-lg font-medium">
        <span>
            <img src="<?php echo $img_url; ?>back-arrow.svg" alt="back arrow" class="w-[9px] h-[15px]" />
        </span>
        CHECKOUT
    </a>
</div>


<section class="bg-white md:py-20 py-6 pb-20">
    <div class="view w-full md:pt-12">
        <div class="flex md:flex-row flex-col gap-6 relative">
            <div class="md:w-[70%] w-full">
                <div class="flex flex-col gap-y-6">
                    <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Verify Mobile Number</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Your mobile number has been verified successfully</p>
                        </div>
                        <div class="w-full bg-[#F1FAF1] border border-[#D1EAD1] p-6 flex justify-between items-center md:rounded-none rounded-lg">
                            <div class="flex items-center gap-3">
                                <span>
                                    <img src="<?php echo esc_url($img_url); ?>success-check-icon.svg" alt="success check" class="size-9" />
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div class="text-base text-[#2F2F2F] font-semibold">Mobile Verified Successfully</div>
                                    <div id="verifiedPhoneNumberSlot" class="text-[#637083] font-normal text-xs">+91 -</div>
                                </div>
                            </div>
                            <a href="" id="changeMobileNumberBtnSlot" class="text-[#6B6B6B] font-medium text-sm duration-300 hover:underline">Change</a>
                        </div>
                    </div>
                    <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Select Service Center</h2>
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
                    <div id="selectDateTimeSection" class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Select Date & Time</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Choose your preferred slot</p>
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            <label for="" class="block text-sm font-bold text-[#2F2F2F]">Select Date</label>
                        <!-- Date Picker Input Field -->
                        <div class="relative w-full [&_.flatpickr-wrapper]:w-full [&_.flatpickr-calendar]:w-full [&_.flatpickr-calendar]:top-[calc(100%_+_12px)] [&_.flatpickr-calendar]:rounded-none [&_.flatpickr-calendar]:p-6 [&_.flatpickr-calendar]:before:hidden [&_.flatpickr-calendar]:after:hidden [&_.flatpickr-month]:order-first [&_.flatpickr-month]:w-auto [&_.flatpickr-current-month]:pt-0 [&_.flatpickr-current-month]:w-auto [&_.flatpickr-current-month]:left-0 [&_.cur-month]:!ml-0 [&_.cur-month]:!bg-white [&_.cur-month]:text-[#2F2F2F] [&_.cur-month]:md:text-lg [&_.cur-month]:text-base [&_.cur-month]:font-bold [&_.numInputWrapper]:bg-white [&_.numInput.cur-year]:text-[#2F2F2F] [&_.numInput.cur-year]:md:text-lg [&_.numInput.cur-year]:text-base [&_.numInput.cur-year]:font-bold [&_.numInputWrapper_.arrowUp]:border-[#000000] [&_.numInputWrapper_.arrowUp]:opacity-100 [&_.numInputWrapper_.arrowUp]:border-none [&_.numInputWrapper_.arrowDown]:border-[#000000] [&_.numInputWrapper_.arrowDown]:opacity-100 [&_.numInputWrapper_.arrowDown]:border-none [&_.flatpickr-prev-month]:relative [&_.flatpickr-next-month]:relative [&_.flatpickr-innerContainer_.flatpickr-rContainer]:w-full [&_.flatpickr-innerContainer_.flatpickr-rContainer_.flatpickr-days]:w-full [&_.flatpickr-rContainer_.dayContainer]:w-full [&_.flatpickr-rContainer_.dayContainer]:gap-1.5 [&_.flatpickr-rContainer_.dayContainer]:max-w-full [&_.dayContainer_.flatpickr-day]:max-w-full [&_.dayContainer_.flatpickr-day]:w-[16%] [&_.dayContainer_.flatpickr-day]:!flex-none [&_.dayContainer_.flatpickr-day]:rounded-none [&_.dayContainer_.flatpickr-day]:bg-[#F8F8F8] [&_.dayContainer_.flatpickr-day.today]:border-[#C8102E] [&_.dayContainer_.flatpickr-day.today]:bg-[#FFF0F0] [&_.dayContainer_.flatpickr-day.today]:text-[#C8102E] [&_.dayContainer_.flatpickr-day.selected]:!bg-[#C8102E] [&_.dayContainer_.flatpickr-day.selected]:!text-white [&_.dayContainer_.flatpickr-day.selected]:!border-[#C8102E]" id="serviceDatePickerWrapper">
                            <label for="serviceDateInput" class="sr-only">Choose a date for your service</label>
                            <input
                                type="text"
                                id="serviceDateInput"
                                name="serviceDateInput"
                                class="w-full h-[3.25rem] calendar-input placeholder:text-[#AFAFAF] sm:text-sm text-xs font-normal text-[#2F2F2F] ring-1 ring-inset ring-[#E5E5E5] focus:ring-2 focus:ring-[#2F2F2F] outline-none border-none pl-12"
                                placeholder="Choose a date for your service"
                                readonly
                                autocomplete="off"
                            />
                            <!-- Calendar Icon, visually similar to your screenshot -->
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 pointer-events-none">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_4810_1110)">
                                        <path d="M6.66504 0.713623V4.0461" stroke="#AFAFAF" stroke-width="1.66624"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.3301 0.713623V4.0461" stroke="#AFAFAF" stroke-width="1.66624"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                        d="M15.8289 2.37988H4.16526C3.24502 2.37988 2.49902 3.12588 2.49902 4.04612V15.7098C2.49902 16.63 3.24502 17.376 4.16526 17.376H15.8289C16.7492 17.376 17.4952 16.63 17.4952 15.7098V4.04612C17.4952 3.12588 16.7492 2.37988 15.8289 2.37988Z"
                                        stroke="#AFAFAF" stroke-width="1.66624" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.49902 7.37866H17.4952" stroke="#AFAFAF" stroke-width="1.66624"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4810_1110">
                                        <rect width="19.9949" height="19.9949" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </div>
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            <label for="" class="flex items-center gap-2 text-sm font-bold text-[#2F2F2F] ">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <g clip-path="url(#clip0_4118_5225)">
                                            <path d="M8 1.33301V6.66584" stroke="#FF8300" stroke-width="1.33321"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3.28516 7.28613L4.22507 8.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M1.33203 11.999H2.66524" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M13.332 11.9989H14.6652" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12.7133 7.28613L11.7734 8.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M14.6641 14.665H1.33203" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M5.33203 3.99942L7.99845 1.33301L10.6649 3.99942"
                                                stroke="#FF8300" stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10.6649 11.9984C10.6649 11.2913 10.3839 10.6131 9.88389 10.113C9.38384 9.61296 8.70562 9.33203 7.99845 9.33203C7.29127 9.33203 6.61306 9.61296 6.11301 10.113C5.61296 10.6131 5.33203 11.2913 5.33203 11.9984"
                                                stroke="#FF8300" stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_4118_5225">
                                                <rect width="15.9985" height="15.9985" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                                Early Morning
                            </label>
                            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4">
                                <label for="morningTimeCheck1" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="morningTime" id="morningTimeCheck1" class="hidden" checked>
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">08:00 - 09:00 AM</div>
                                </label>
                                <label for="morningTimeCheck2" class="group/p md:rounded-none rounded-lg bg-[#F7F7F7] cursor-pointer border border-[#EDEDED] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="morningTime" id="morningTimeCheck2" class="hidden">
                                    <div class="text-sm font-medium text-[#AFAFAF] group-has-[:checked]/p:text-[#CB122D]">09:00 - 10:00 AM</div>
                                </label>
                                <label for="morningTimeCheck3" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="morningTime" id="morningTimeCheck3" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">10:00 - 11:00 AM</div>
                                </label>
                                <label for="morningTimeCheck4" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="morningTime" id="morningTimeCheck4" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">11:00 AM - 12:00 PM</div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            <label for="" class="flex items-center gap-2 text-sm font-bold text-[#2F2F2F]">
                                <span>
                                    <svg class="size-4" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_4118_5247)">
                                            <path
                                                d="M7.99845 10.6658C9.47107 10.6658 10.6649 9.47204 10.6649 7.99942C10.6649 6.5268 9.47107 5.33301 7.99845 5.33301C6.52583 5.33301 5.33203 6.5268 5.33203 7.99942C5.33203 9.47204 6.52583 10.6658 7.99845 10.6658Z"
                                                stroke="#FF8300" stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M8 1.33301V2.66622" stroke="#FF8300" stroke-width="1.33321"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8 13.332V14.6652" stroke="#FF8300" stroke-width="1.33321"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3.28516 3.28613L4.22507 4.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M11.7734 11.7725L12.7133 12.7124" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M1.33203 7.99902H2.66524" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M13.332 7.99927H14.6652" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M4.22507 11.7725L3.28516 12.7124" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12.7133 3.28613L11.7734 4.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_4118_5247">
                                                <rect width="15.9985" height="15.9985" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                                Afternoon
                            </label>
                            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4 group/timeCheck">
                                <label for="afternoonTimeCheck1" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="afternoonTime" id="afternoonTimeCheck1" class="hidden" checked>
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">12:00 - 01:00 PM</div>
                                </label>
                                <label for="afternoonTimeCheck2" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="afternoonTime" id="afternoonTimeCheck2" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">01:00 - 02:00 PM</div>
                                </label>
                                <label for="afternoonTimeCheck3" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="afternoonTime" id="afternoonTimeCheck3" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">02:00 - 03:00 PM</div>
                                </label>
                                <label for="afternoonTimeCheck4" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="afternoonTime" id="afternoonTimeCheck4" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">03:00 - 04:00 PM</div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            <label for="" class="flex items-center gap-2 text-sm font-bold text-[#2F2F2F]">
                                <span>
                                    <svg class="size-4" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_4118_5270)">
                                            <path d="M8 6.66584V1.33301" stroke="#FF8300" stroke-width="1.33321"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3.28516 7.28613L4.22507 8.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M1.33203 11.999H2.66524" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M13.332 11.9989H14.6652" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12.7133 7.28613L11.7734 8.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M14.6641 14.665H1.33203" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M10.6649 4L7.99845 6.66642L5.33203 4" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10.6649 11.9984C10.6649 11.2913 10.3839 10.6131 9.88389 10.113C9.38384 9.61296 8.70562 9.33203 7.99845 9.33203C7.29127 9.33203 6.61306 9.61296 6.11301 10.113C5.61296 10.6131 5.33203 11.2913 5.33203 11.9984"
                                                stroke="#FF8300" stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_4118_5270">
                                                <rect width="15.9985" height="15.9985" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                                Evening
                            </label>
                            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4 group/timeCheck">
                                <label for="eveningTimeCheck1" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="eveningTime" id="eveningTimeCheck1" class="hidden" checked>
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">04:00 - 05:00 PM</div>
                                </label>
                                <label for="eveningTimeCheck2" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="eveningTime" id="eveningTimeCheck2" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">05:00 - 06:00 PM</div>
                                </label>
                                <label for="eveningTimeCheck3" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="eveningTime" id="eveningTimeCheck3" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">06:00 - 07:00 PM</div>
                                </label>
                            </div>
                        </div>
                        <a href="" class="w-1/2 bg-[#6B6B6B] w-full rounded-lg h-[2.875rem] md:hidden flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D]">Continue to Payment</a>
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
                                <div id="bookingServiceCenterName" class="text-[#2F2F2F] font-bold text-sm">
                                    -
                                </div>
                                <div id="bookingServiceCenterCity" class="font-normal text-sm text-[#6B6B6B] empty:hidden"></div>
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
                    </div>
                </div>
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
        </div>
    </label>
    <a href="" class="w-1/2 bg-[#AFAFAF] w-full rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D]">Confirm Booking</a>
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
                
                // Populate service center name (format: "name - city" or just "name")
                if (centerNameEl) {
                    if (centerName && centerCity) {
                        centerNameEl.textContent = centerName;
                    } else if (centerName) {
                        centerNameEl.textContent = centerName;
                    } else {
                        centerNameEl.textContent = '-';
                    }
                }
                
                // Populate location (format: "city • distance" - for now just city, distance can be added later)
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
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            populateBookingSummary();
            populateVerifiedPhoneNumber();
            populateSelectedServiceCenter();
        });
    } else {
        populateBookingSummary();
        populateVerifiedPhoneNumber();
        populateSelectedServiceCenter();
    }
})();

// Prevent back button navigation to workstation page
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
    
    const cart = getCart();
    if (cart && cart.service_center) {
        // User has selected service center, prevent going back to workstation page
        // Push a new state to prevent back navigation to workstation page
        if (window.history && window.history.replaceState) {
            history.pushState({ page: 'slot' }, '', window.location.href);
            
            // Listen for popstate event (back/forward button)
            window.addEventListener('popstate', function(event) {
                // Check if trying to go back to workstation page
                const currentCart = getCart();
                if (currentCart && currentCart.service_center) {
                    // Service center is selected, don't allow going back to workstation page
                    // Push forward again to stay on current page
                    history.pushState({ page: 'slot' }, '', window.location.href);
                    
                    // Also check URL and redirect if somehow on workstation page
                    if (workstationPageUrl && window.location.href.includes(workstationPageUrl.split('/').pop())) {
                        window.location.replace(window.location.href.replace(workstationPageUrl.split('/').pop(), 'slot'));
                    }
                }
            });
        }
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
    
    function handleChangeButtonClick() {
        // Get Change button by ID
        const changeServiceCenterBtn = document.getElementById('changeServiceCenterBtn');
        
        if (changeServiceCenterBtn) {
            changeServiceCenterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove service center from sessionStorage
                let cart = getCart();
                if (cart) {
                    delete cart.service_center;
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
        document.addEventListener('DOMContentLoaded', handleChangeButtonClick);
    } else {
        handleChangeButtonClick();
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

// Smooth scroll to Select Date & Time section after page load
(function() {
    'use strict';
    
    function scrollToDateTimeSection() {
        const dateTimeSection = document.getElementById('selectDateTimeSection');
        if (dateTimeSection) {
            // Calculate offset to account for fixed header
            const headerOffset = 100; // Adjust this value based on your header height
            const elementPosition = dateTimeSection.getBoundingClientRect().top;
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
            setTimeout(scrollToDateTimeSection, 500);
        });
    } else {
        setTimeout(scrollToDateTimeSection, 500);
    }
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only init if not already done
    if (!window.__serviceDatePickerInitialized) {
        window.__serviceDatePickerInitialized = true;

        // Helper: Find first 2 Sundays for current shown month
        function getFirstTwoSundays(year, month) {
            // month: 0-indexed!
            let firstSundays = [];
            for (let d = 1; d <= 31 && firstSundays.length < 2; d++) {
                let date = new Date(year, month, d);
                if (date.getMonth() !== month) break;
                if (date.getDay() === 0) { // 0 = Sunday
                    firstSundays.push(date);
                }
            }
            return firstSundays.map(dt =>
                dt.toISOString().slice(0,10)
            );
        }

        // Find the input
        const serviceDateInput = document.getElementById('serviceDateInput');
        if (serviceDateInput && window.flatpickr) {
            flatpickr(serviceDateInput, {
                dateFormat: "d-m-Y",
                disable: [
                    function(date) {
                        const today = new Date();
                        today.setHours(0,0,0,0);
                        // Normalize the date parameter to compare only date part
                        const dateToCheck = new Date(date);
                        dateToCheck.setHours(0,0,0,0);
                        // Disable all dates < today (past dates only, today is allowed)
                        if (dateToCheck < today) return true;

                        // Disable first 2 Sundays
                        // For each month, compute first 2 Sundays:
                        const year = date.getFullYear();
                        const month = date.getMonth();
                        const currentMonthSundays = getFirstTwoSundays(year, month);
                        const dateISO = date.toISOString().slice(0,10);
                        if (currentMonthSundays.includes(dateISO)) return true;

                        return false; // everything else enabled
                    }
                ],
                minDate: "today", // allow today and future dates
                allowInput: false,
                static: true,
                monthSelectorType: "static",
                onOpen: function(selectedDates, dateStr, instance) {
                    setTimeout(function() {
                        // Add calendar icon highlight or whatever else UI needs
                    }, 0);
                }
            });
        }
    }
});
</script>

<?php get_footer(); ?>