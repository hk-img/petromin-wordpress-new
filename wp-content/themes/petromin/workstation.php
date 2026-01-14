<?php
/* Template Name: workstation page */
get_header();

// Get theme assets directory URL for images
$img_url = get_template_directory_uri() . '/assets/img/';

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

?>
<style>
/* Hide page content initially until validation passes */
body.workstation-page {
    visibility: hidden;
    opacity: 0;
}
body.workstation-page.validation-passed {
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
    
    // Check if service center is already selected - if yes, redirect to slot page immediately
    const currentCart = getCart();
    if (currentCart && currentCart.service_center) {
        // Service center already selected, redirect to slot page immediately without showing page
        if (slotPageUrl && slotPageUrl !== '') {
            window.location.replace(slotPageUrl);
            return; // Exit early, don't proceed with page initialization
        }
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
    
    // Add class to body for workstation page
    if (document.body) {
        document.body.classList.add('workstation-page');
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('workstation-page');
        });
    }
    
    // Check validation immediately
    if (!validateVehicleData()) {
        // Redirect immediately without showing content
        window.location.replace(costEstimatorUrl);
    } else {
        // Show content if validation passes
        if (document.body) {
            document.body.classList.add('validation-passed');
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                document.body.classList.add('validation-passed');
            });
        }
        
        // Trigger location request after validation passes
        // Use a flag to indicate validation has passed and location can be requested
        window.workstationValidationPassed = true;
    }
    
    // Additional check on page visibility change (when user navigates back)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page became visible - check again if service center is selected
            const currentCart = getCart();
            if (currentCart && currentCart.service_center) {
                if (slotPageUrl && slotPageUrl !== '') {
                    window.location.replace(slotPageUrl);
                }
            }
        }
    });
    
    // Also check on focus event (when user comes back to tab)
    window.addEventListener('focus', function() {
        const currentCart = getCart();
        if (currentCart && currentCart.service_center) {
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
                    <div class="border-t border-dashed border-[#696B79] w-16"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-gray-400 font-medium text-sm tracking-wide">
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
    <a href="" id="changeMobileNumberBtn" class="flex items-center gap-4 uppercase text-[#121212] text-lg font-medium">
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
                                    <div class="text-base text-[#2F2F2F] font-semibold">Mobile Verified <span class="max-md:hidden">Successfully</span></div>
                                    <div id="verifiedPhoneNumber" class="text-[#637083] font-normal text-xs">+91 -</div>
                                </div>
                            </div>
                            <a href="" id="changeMobileNumberBtn" class="text-[#6B6B6B] font-medium text-sm duration-300 hover:underline">Change</a>
                        </div>
                    </div>
                    <div id="selectServiceCenterSection" class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Select Service Center</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Select the service center nearest to you</p>
                        </div>
                        <!-- Location Permission Blocked Message -->
                        <div id="locationPermissionBlockedMsg" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-yellow-800 mb-1">Location Permission Blocked</h3>
                                    <p class="text-sm text-yellow-700 mb-2">Location access has been blocked in your browser settings. To enable distance calculation, please reset the permission:</p>
                                    <div id="browserSpecificInstructions" class="text-sm text-yellow-700 mb-3">
                                        <!-- Instructions will be populated dynamically based on browser -->
                                    </div>
                                    <div class="flex gap-3 items-center mt-3">
                                        <button type="button" id="copySettingsUrlBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            📋 Copy Settings URL to Clipboard
                                        </button>
                                        <button type="button" onclick="if(typeof hidePermissionBlockedMessage === 'function') { hidePermissionBlockedMessage(); } else { this.closest('#locationPermissionBlockedMsg').classList.add('hidden'); }" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline">Dismiss</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative w-full">
                            <input type="text" id="serviceCenterSearch" class="w-full h-[2.875rem] text-[#0A0A0A80] pl-12 text-sm font-normal border border-[#E5E5E5] md:rounded-none rounded-lg" placeholder="Search by location or area">
                            <button type="button" class="absolute top-3 left-3">
                                <img src="<?php echo esc_url($img_url); ?>search-icon.svg" alt="search" class="size-[1.125rem]" />
                            </button>
                        </div>

                        <div class="flex flex-col gap-y-4" id="serviceCentersList">
                            <?php
                            // Get locate-us page ID by template
                            $locate_us_page = get_pages(array(
                                'meta_key' => '_wp_page_template',
                                'meta_value' => 'locate-us.php',
                                'number' => 1,
                                'post_status' => 'publish'
                            ));
                            
                            $locate_us_page_id = !empty($locate_us_page) ? $locate_us_page[0]->ID : null;
                            
                            // Get service centers from locate-us page (same way as locate-us.php does)
                            $service_centers_field = [];
                            if ($locate_us_page_id && function_exists('get_field')) {
                                $service_centers_field = get_field('service_centers_section', $locate_us_page_id) ?: [];
                            }
                            
                            $service_centers_items = [];
                            
                            if (!empty($service_centers_field['centers']) && is_array($service_centers_field['centers'])) {
                                $service_centers_items = $service_centers_field['centers'];
                            }
                            
                            if (!empty($service_centers_items)) :
                                foreach ($service_centers_items as $index => $center) :
                                    $center_name = trim($center['name'] ?? '');
                                    $center_city = trim($center['city'] ?? '');
                                    
                                    // Skip if name is empty
                                    if (empty($center_name)) {
                                        continue;
                                    }
                                    
                                    $service_id = 'service_' . ($index + 1);
                                    // No default selection - user must click to select
                                    $is_checked = '';
                            ?>
                            <?php
                            // Get map location coordinates
                            $map_location = $center['map_location'] ?? null;
                            $center_lat = null;
                            $center_lng = null;
                            
                            if (!empty($map_location) && is_array($map_location)) {
                                if (isset($map_location['lat']) && isset($map_location['lng'])) {
                                    $center_lat = (float) $map_location['lat'];
                                    $center_lng = (float) $map_location['lng'];
                                }
                            }
                            
                            // Build data attributes string
                            $data_attrs = 'data-center-name="' . esc_attr(strtolower($center_name)) . '" data-center-city="' . esc_attr(strtolower($center_city)) . '"';
                            if ($center_lat !== null && $center_lng !== null) {
                                $data_attrs .= ' data-center-lat="' . esc_attr($center_lat) . '" data-center-lng="' . esc_attr($center_lng) . '"';
                            }
                            ?>
                            <label for="<?php echo esc_attr($service_id); ?>" class="group/s cursor-pointer w-full relative border border-[#E5E5E5] has-[:checked]:border-[#CB122D] p-4 bg-white flex justify-between gap-2 md:rounded-none rounded-lg service-center-item" <?php echo $data_attrs; ?>>
                                <input type="radio" name="service" id="<?php echo esc_attr($service_id); ?>" class="hidden" value="<?php echo esc_attr($center_name); ?>" <?php echo $is_checked; ?>>
                                <!-- Loader Overlay -->
                                <div class="service-center-loader-overlay absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-50 hidden md:rounded-none rounded-lg">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div>
                                        <p class="text-gray-600 text-sm font-medium">Processing...</p>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-y-3">
                                    <h3 class="text-[#2F2F2F] group-has-[:checked]/s:text-[#CB122D] font-semibold text-base"><?php echo esc_html($center_name); ?></h3>
                                    <?php if (!empty($center_city)) : ?>
                                    <div class="text-sm text-[#6B6B6B] font-normal"><?php echo esc_html($center_city); ?></div>
                                    <?php endif; ?>
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1 text-sm text-[#AFAFAF] group-has-[:checked]/s:text-[#CB122D] distance-container">
                                            <span>
                                                <img src="<?php echo esc_url($img_url); ?>location-pin-icon.svg" alt="location" class="size-[0.75rem]" />
                                            </span>
                                            <span class="distance-text">-</span>
                                            <button type="button" class="get-distance-btn hidden text-xs text-[#CB122D] font-medium underline hover:no-underline ml-1" style="display: none;">Get Distance</button>
                                        </div>
                                        <div class="flex items-center gap-1 text-sm text-[#AFAFAF] group-has-[:checked]/s:text-[#CB122D]">
                                            <span>
                                                <img src="<?php echo esc_url($img_url); ?>clock-icon.svg" alt="time" class="size-[0.75rem]" />
                                            </span>
                                            9:00 AM - 7:00 PM
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a href="" class="inline-block duration-500 hover:scale-110 opacity-20 group-has-[:checked]/s:opacity-100">
                                        <img src="<?php echo esc_url($img_url); ?>arrow-right-icon.svg" alt="arrow right" class="size-[1.125rem]" />
                                    </a>
                                </div>
                            </label>
                            <?php
                                endforeach;
                            else :
                            ?>
                            <div class="text-center text-[#6B6B6B] text-sm py-4">No service centers available.</div>
                            <?php endif; ?>
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
    <button type="button" class="w-1/2 bg-[#AFAFAF] w-full rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D] disabled:bg-gray-400 disabled:cursor-not-allowed disabled" disabled="true">Confirm Booking</button>
</div>

<script>
// Booking Summary and Service Center Functionality
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
        const verifiedPhoneEl = document.getElementById('verifiedPhoneNumber');
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
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            populateBookingSummary();
            populateVerifiedPhoneNumber();
        });
    } else {
        populateBookingSummary();
        populateVerifiedPhoneNumber();
    }
})();

// Service Center Filter by City from SessionStorage and Search Functionality
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
    
    // Function to get city from sessionStorage
    function getCityFromSessionStorage() {
        const cart = getCart();
        if (cart && cart.vehicle && cart.vehicle.city) {
            return cart.vehicle.city.trim();
        }
        return null;
    }
    
    const searchInput = document.getElementById('serviceCenterSearch');
    const centerItems = document.querySelectorAll('.service-center-item');
    
    if (!searchInput || centerItems.length === 0) {
        return; // Exit if elements don't exist
    }
    
    function filterCenters(searchTerm, filterByCity) {
        const term = searchTerm.toLowerCase().trim();
        const cityFilter = filterByCity ? filterByCity.toLowerCase().trim() : null;
        let visibleCount = 0;
        
        centerItems.forEach(function(item) {
            const centerName = (item.dataset.centerName || '').toLowerCase();
            const centerCity = (item.dataset.centerCity || '').toLowerCase();
            
            // First filter by city if city is provided
            let cityMatches = true;
            if (cityFilter) {
                cityMatches = centerCity === cityFilter;
            }
            
            // Then filter by search term
            const searchMatches = term === '' || 
                          centerName.includes(term) || 
                          centerCity.includes(term);
            
            // Show item only if both filters match
            if (cityMatches && searchMatches) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show message if no results
        const listContainer = document.getElementById('serviceCentersList');
        if (listContainer) {
            let noResultsMsg = listContainer.querySelector('.no-results-message');
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'no-results-message text-center text-[#6B6B6B] text-sm py-4';
                    listContainer.appendChild(noResultsMsg);
                }
                if (cityFilter && term === '') {
                    noResultsMsg.textContent = 'No service centers found in ' + filterByCity + '.';
                } else if (cityFilter) {
                    noResultsMsg.textContent = 'No service centers found in ' + filterByCity + ' matching your search.';
                } else if (term !== '') {
                    noResultsMsg.textContent = 'No service centers found matching your search.';
                } else {
                    noResultsMsg.textContent = 'No service centers available.';
                }
                noResultsMsg.style.display = 'block';
            } else if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
        }
    }
    
    // Get city from sessionStorage
    const selectedCity = getCityFromSessionStorage();
    
    // Add search input event listener
    searchInput.addEventListener('input', function(e) {
        filterCenters(e.target.value, selectedCity);
    });
    
    // Initial filter on page load (filter by city if available, then apply search if any)
    filterCenters(searchInput.value, selectedCity);
})();

// Service Center Selection Handler - Save to sessionStorage and Redirect to Slot Page
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const slotPageUrl = '<?php echo esc_js($slot_page_url); ?>';
    
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
    
    // Function to update mobile location display
    function updateMobileLocation(centerName, centerCity) {
        const mobileLocationEl = document.getElementById('mobileLocation');
        if (mobileLocationEl) {
            if (centerName && centerCity) {
                mobileLocationEl.textContent = centerName + ' - ' + centerCity;
            } else if (centerName) {
                mobileLocationEl.textContent = centerName;
            } else if (centerCity) {
                mobileLocationEl.textContent = centerCity;
            } else {
                mobileLocationEl.textContent = '-';
            }
        }
    }
    
    // Handle service center radio button selection
    function handleServiceCenterSelection() {
        const radioButtons = document.querySelectorAll('input[name="service"][type="radio"]');
        
        radioButtons.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // Get the parent label which has data attributes
                    const label = this.closest('.service-center-item');
                    if (label) {
                        // Show loader overlay on this specific service center item
                        const loaderOverlay = label.querySelector('.service-center-loader-overlay');
                        if (loaderOverlay) {
                            loaderOverlay.classList.remove('hidden');
                        }
                        
                        // Disable pointer events on all service center items to prevent multiple clicks
                        const allServiceItems = document.querySelectorAll('.service-center-item');
                        allServiceItems.forEach(function(item) {
                            item.style.pointerEvents = 'none';
                            item.style.cursor = 'not-allowed';
                        });
                        
                        const centerName = label.getAttribute('data-center-name') || '';
                        const centerCity = label.getAttribute('data-center-city') || '';
                        const centerLat = label.getAttribute('data-center-lat') || '';
                        const centerLng = label.getAttribute('data-center-lng') || '';
                        
                        // Get actual display name (not lowercased) from the h3 element
                        const nameElement = label.querySelector('h3');
                        const displayName = nameElement ? nameElement.textContent.trim() : centerName;
                        
                        // Get cart from sessionStorage
                        let cart = getCart();
                        if (!cart) {
                            cart = { vehicle: {}, items: [] };
                        }
                        
                        // Save selected service center to cart
                        cart.service_center = {
                            name: displayName,
                            city: centerCity,
                            lat: centerLat,
                            lng: centerLng
                        };
                        
                        // Save to sessionStorage
                        saveCart(cart);
                        
                        // Update mobile location display immediately
                        updateMobileLocation(displayName, centerCity);
                        
                        // Redirect to slot page immediately using replace to prevent back navigation
                        if (slotPageUrl && slotPageUrl !== '') {
                            window.location.replace(slotPageUrl);
                        } else {
                            console.error('Slot page URL not found');
                            // Hide loader if redirect fails
                            if (loaderOverlay) {
                                loaderOverlay.classList.add('hidden');
                            }
                            // Re-enable pointer events
                            allServiceItems.forEach(function(item) {
                                item.style.pointerEvents = '';
                                item.style.cursor = '';
                            });
                        }
                    }
                }
            });
        });
    }
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleServiceCenterSelection);
    } else {
        handleServiceCenterSelection();
    }
})();

// Dynamic Distance Calculation using Geolocation and Google Maps Distance Matrix API
(function() {
    'use strict';
    
    const GOOGLE_MAPS_API_KEY = '<?php echo esc_js(apply_filters("acf/fields/google_map/api", [])["key"] ?? "AIzaSyDC3RCcvMaCHd7VOf7hRhgceXDQ5cSFyGU"); ?>';
    
    // Function to calculate straight-line distance using Haversine formula (fallback)
    function calculateStraightLineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the Earth in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c; // Distance in kilometers
        return distance * 1000; // Convert to meters
    }
    
    // Function to format distance
    function formatDistance(distanceInMeters) {
        const distanceKm = distanceInMeters / 1000;
        if (distanceKm < 1) {
            // If less than 1 km, show in meters
            return Math.round(distanceInMeters) + ' m';
        } else {
            // Show in km with one decimal place
            return distanceKm.toFixed(1) + ' km';
        }
    }
    
    // Function to get road distance using Google Maps Distance Matrix API
    function getRoadDistance(userLat, userLng, centerLat, centerLng, callback) {
        const origin = userLat + ',' + userLng;
        const destination = centerLat + ',' + centerLng;
        const url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' + 
                    encodeURIComponent(origin) + 
                    '&destinations=' + 
                    encodeURIComponent(destination) + 
                    '&mode=driving&units=metric&key=' + 
                    GOOGLE_MAPS_API_KEY;
        
        fetch(url)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(function(data) {
                // Check API response status
                if (data.status !== 'OK') {
                    callback(new Error('API Error: ' + (data.error_message || data.status)), null);
                    return;
                }
                
                // Check if we have rows and elements
                if (!data.rows || !data.rows[0] || !data.rows[0].elements || !data.rows[0].elements[0]) {
                    callback(new Error('Invalid API response structure'), null);
                    return;
                }
                
                const element = data.rows[0].elements[0];
                
                // Check element status
                if (element.status === 'OK') {
                    if (element.distance && element.distance.value) {
                        const distanceInMeters = element.distance.value;
                        callback(null, distanceInMeters);
                    } else {
                        callback(new Error('Distance value not found in response'), null);
                    }
                } else if (element.status === 'ZERO_RESULTS') {
                    callback(new Error('No route found between locations'), null);
                } else if (element.status === 'NOT_FOUND') {
                    callback(new Error('Origin or destination not found'), null);
                } else {
                    callback(new Error('Distance calculation failed: ' + element.status), null);
                }
            })
            .catch(function(error) {
                console.error('Fetch error:', error);
                callback(error, null);
            });
    }
    
    // Function to update distances for all service centers
    function updateDistances(userLat, userLng) {
        // Get all service centers (including hidden ones)
        const serviceCenters = Array.from(document.querySelectorAll('.service-center-item'));
        
        if (serviceCenters.length === 0) {
            console.warn('No service centers found');
            return;
        }
        
        // Filter centers with valid coordinates
        const validCenters = serviceCenters.filter(function(center) {
            const centerLatStr = center.getAttribute('data-center-lat');
            const centerLngStr = center.getAttribute('data-center-lng');
            const distanceElement = center.querySelector('.distance-text');
            return centerLatStr && centerLngStr && distanceElement;
        });
        
        if (validCenters.length === 0) {
            console.warn('No service centers with valid coordinates found');
            return;
        }
        
        // Show loading state
        validCenters.forEach(function(center) {
            const distanceElement = center.querySelector('.distance-text');
            if (distanceElement) {
                distanceElement.textContent = 'Calculating...';
            }
        });
        
        // Process centers one by one to avoid rate limiting
        let currentIndex = 0;
        
        function processNextCenter() {
            if (currentIndex >= validCenters.length) {
                console.log('Updated distances for all service centers');
                return;
            }
            
            const center = validCenters[currentIndex];
            const centerLatStr = center.getAttribute('data-center-lat');
            const centerLngStr = center.getAttribute('data-center-lng');
            const distanceElement = center.querySelector('.distance-text');
            
            const centerLat = parseFloat(centerLatStr);
            const centerLng = parseFloat(centerLngStr);
            
            if (isNaN(centerLat) || isNaN(centerLng)) {
                if (distanceElement) {
                    distanceElement.textContent = '-';
                }
                currentIndex++;
                processNextCenter();
                return;
            }
            
            // Get road distance from Google Maps API
            getRoadDistance(userLat, userLng, centerLat, centerLng, function(error, distanceInMeters) {
                if (error) {
                    // If road distance fails, use straight-line distance as fallback
                    console.warn('Road distance failed, using straight-line distance:', error.message);
                    const straightLineDistance = calculateStraightLineDistance(userLat, userLng, centerLat, centerLng);
                    if (distanceElement) {
                        // Show straight-line distance with an indicator (approximate)
                        distanceElement.textContent = '~' + formatDistance(straightLineDistance);
                    }
                } else if (distanceInMeters !== null) {
                    if (distanceElement) {
                        distanceElement.textContent = formatDistance(distanceInMeters);
                    }
                } else {
                    // Fallback to straight-line if no result
                    const straightLineDistance = calculateStraightLineDistance(userLat, userLng, centerLat, centerLng);
                    if (distanceElement) {
                        distanceElement.textContent = '~' + formatDistance(straightLineDistance);
                    }
                }
                
                currentIndex++;
                // Add small delay between requests to avoid rate limiting
                setTimeout(processNextCenter, 300);
            });
        }
        
        // Start processing
        processNextCenter();
    }
    
    // Flag to prevent multiple simultaneous requests
    let isLocationRequestInProgress = false;
    let permissionBlockedMessageShown = false;
    
    // Function to check if permission is permanently blocked by browser
    // Returns a Promise that resolves to true if blocked, false otherwise
    function isPermissionPermanentlyBlocked() {
        return new Promise(function(resolve) {
            if ('permissions' in navigator) {
                navigator.permissions.query({ name: 'geolocation' }).then(function(result) {
                    // If state is 'denied', it means permission is permanently blocked
                    if (result.state === 'denied') {
                        resolve(true);
                    } else {
                        // State is either 'prompt' (can request) or 'granted' (already allowed)
                        resolve(false);
                    }
                }).catch(function(err) {
                    // Permissions API not supported or error
                    console.warn('Permissions API not supported:', err);
                    // If API is not available, we can't determine, so return false
                    resolve(false);
                });
            } else {
                // Permissions API not supported
                resolve(false);
            }
        });
    }
    
    // Function to show permission blocked message
    function showPermissionBlockedMessage() {
        const msgElement = document.getElementById('locationPermissionBlockedMsg');
        if (msgElement) {
            // Populate browser-specific instructions before showing
            populateBrowserInstructions();
            msgElement.classList.remove('hidden');
            permissionBlockedMessageShown = true;
        }
    }
    
    // Function to hide permission blocked message
    function hidePermissionBlockedMessage() {
        const msgElement = document.getElementById('locationPermissionBlockedMsg');
        if (msgElement) {
            msgElement.classList.add('hidden');
            permissionBlockedMessageShown = false;
        }
    }
    
    // Function to detect browser and get settings URL and instructions
    function getBrowserInfo() {
        const userAgent = navigator.userAgent.toLowerCase();
        const isChrome = userAgent.indexOf('chrome') > -1 && userAgent.indexOf('edge') === -1 && userAgent.indexOf('edg/') === -1;
        const isEdge = userAgent.indexOf('edge') > -1 || userAgent.indexOf('edg/') > -1;
        const isFirefox = userAgent.indexOf('firefox') > -1;
        const isSafari = userAgent.indexOf('safari') > -1 && userAgent.indexOf('chrome') === -1 && userAgent.indexOf('edge') === -1;
        const isOpera = userAgent.indexOf('opr/') > -1 || userAgent.indexOf('opera') > -1;
        const isBrave = userAgent.indexOf('brave') > -1;
        
        let instructions = '';
        
        if (isChrome) {
            instructions = '<ol class="list-decimal list-inside space-y-1">' +
                '<li>Look at the address bar (top of browser, where website URL is shown)</li>' +
                '<li>Click on the 🔒 lock icon or ⓘ information icon on the LEFT side</li>' +
                '<li>Click on "Site settings" or "Permissions"</li>' +
                '<li>Scroll down and find "Location"</li>' +
                '<li>Click on "Location" and select "Allow" OR click "Clear & Reset"</li>' +
                '<li>Refresh this page (F5 or Reload button)</li>' +
                '</ol>';
        } else if (isEdge) {
            instructions = '<ol class="list-decimal list-inside space-y-1">' +
                '<li>Look at the address bar (top of browser, where website URL is shown)</li>' +
                '<li>Click on the 🔒 lock icon or ⓘ information icon on the LEFT side</li>' +
                '<li>Click on "Site settings" or "Permissions"</li>' +
                '<li>Scroll down and find "Location"</li>' +
                '<li>Click on "Location" and select "Allow" OR click "Clear & Reset"</li>' +
                '<li>Refresh this page (F5 or Reload button)</li>' +
                '</ol>';
        } else if (isFirefox) {
            instructions = '<ol class="list-decimal list-inside space-y-1">' +
                '<li>Look at the address bar (top of browser, where website URL is shown)</li>' +
                '<li>Click on the 🔒 lock icon or ⓘ information icon on the LEFT side</li>' +
                '<li>Click on "More Information" or "Connection Secure"</li>' +
                '<li>Go to the "Permissions" tab</li>' +
                '<li>Find "Access your location" and click "Clear" OR change dropdown to "Allow"</li>' +
                '<li>Refresh this page (F5 or Reload button)</li>' +
                '</ol>';
        } else if (isSafari) {
            instructions = '<ol class="list-decimal list-inside space-y-1">' +
                '<li>Click "Safari" in the menu bar (top of screen)</li>' +
                '<li>Click "Settings" or "Preferences" (⌘,)</li>' +
                '<li>Click the "Websites" tab</li>' +
                '<li>Click "Location" in the left sidebar</li>' +
                '<li>Find this website in the list and change permission to "Ask" or "Allow"</li>' +
                '<li>Refresh this page (⌘R)</li>' +
                '</ol>';
        } else if (isOpera) {
            instructions = '<ol class="list-decimal list-inside space-y-1">' +
                '<li>Look at the address bar (top of browser, where website URL is shown)</li>' +
                '<li>Click on the 🔒 lock icon or ⓘ information icon on the LEFT side</li>' +
                '<li>Click on "Site settings" or "Permissions"</li>' +
                '<li>Scroll down and find "Location"</li>' +
                '<li>Click on "Location" and select "Allow" OR click "Clear & Reset"</li>' +
                '<li>Refresh this page (F5 or Reload button)</li>' +
                '</ol>';
        } else if (isBrave) {
            instructions = '<ol class="list-decimal list-inside space-y-1">' +
                '<li>Look at the address bar (top of browser, where website URL is shown)</li>' +
                '<li>Click on the 🔒 lock icon or ⓘ information icon on the LEFT side</li>' +
                '<li>Click on "Site settings" or "Permissions"</li>' +
                '<li>Scroll down and find "Location"</li>' +
                '<li>Click on "Location" and select "Allow" OR click "Clear & Reset"</li>' +
                '<li>Refresh this page (F5 or Reload button)</li>' +
                '</ol>';
        } else {
            instructions = '<ol class="list-decimal list-inside space-y-1">' +
                '<li>Look at the address bar (top of browser, where website URL is shown)</li>' +
                '<li>Click on the 🔒 lock/security icon or ⓘ information icon on the LEFT side</li>' +
                '<li>Look for "Site settings", "Permissions", or "Privacy" options</li>' +
                '<li>Find "Location" or "Geolocation" settings</li>' +
                '<li>Reset or allow location permissions for this site</li>' +
                '<li>Refresh this page (F5 or Reload button)</li>' +
                '</ol>';
        }
        
        return {
            instructions: instructions
        };
    }
    
    // Function to populate browser-specific instructions in the warning message
    function populateBrowserInstructions() {
        const instructionsContainer = document.getElementById('browserSpecificInstructions');
        if (instructionsContainer) {
            const browserInfo = getBrowserInfo();
            instructionsContainer.innerHTML = browserInfo.instructions;
        }
    }
    
    // Function to show "Get Distance" buttons when permission is denied
    function showGetDistanceButtons() {
        const distanceContainers = document.querySelectorAll('.distance-container');
        distanceContainers.forEach(function(container) {
            const distanceText = container.querySelector('.distance-text');
            const getDistanceBtn = container.querySelector('.get-distance-btn');
            
            if (distanceText && getDistanceBtn) {
                // Always reset text to dash first if it's calculating
                if (distanceText.textContent === 'Calculating...' || distanceText.textContent.trim() === '' || distanceText.textContent.includes('Calculating')) {
                    distanceText.textContent = '-';
                    distanceText.style.display = 'inline';
                }
                
                // Show button and hide text (always show button if text is dash or empty)
                if (distanceText.textContent === '-' || distanceText.textContent.trim() === '') {
                    // Ensure text is hidden first
                    distanceText.style.display = 'none';
                    // Show button
                    getDistanceBtn.style.display = 'inline-block';
                    getDistanceBtn.classList.remove('hidden');
                }
            }
        });
    }
    
    // Function to hide "Get Distance" buttons
    function hideGetDistanceButtons() {
        const getDistanceButtons = document.querySelectorAll('.get-distance-btn');
        getDistanceButtons.forEach(function(btn) {
            const container = btn.closest('.distance-container');
            const distanceText = container ? container.querySelector('.distance-text') : null;
            
            if (distanceText) {
                btn.style.display = 'none';
                btn.classList.add('hidden');
                distanceText.style.display = 'inline';
            }
        });
    }
    
    // Function to request location directly from user gesture (button click)
    function requestLocationFromButtonClick() {
        // Prevent multiple simultaneous requests
        if (isLocationRequestInProgress) {
            return;
        }
        
        if (!navigator.geolocation) {
            // Geolocation is not supported
            console.warn('Geolocation is not supported by this browser.');
            showGetDistanceButtons();
            return;
        }
        
        // Set flag to prevent duplicate requests
        isLocationRequestInProgress = true;
        
        // Request user's location directly from user gesture
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Success callback
                isLocationRequestInProgress = false;
                
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                
                console.log('User location:', userLat, userLng);
                
                // Hide any visible "Get Distance" buttons
                hideGetDistanceButtons();
                
                // Update distances immediately
                updateDistances(userLat, userLng);
            },
            function(error) {
                // Error callback - handle all error cases (This is from button click)
                isLocationRequestInProgress = false;
                
                // Only log error once, don't spam console
                if (error.code === error.PERMISSION_DENIED) {
                    console.warn('Location permission denied or blocked');
                    
                    // Check if permission is permanently blocked by browser
                    // This is called from button click, so show warning only if permanently blocked
                    isPermissionPermanentlyBlocked().then(function(isBlocked) {
                        if (isBlocked) {
                            // Permission is permanently blocked - show warning message immediately
                            showPermissionBlockedMessage();
                        } else {
                            // Permission is just denied but not permanently blocked
                            // Hide any previously shown warning
                            hidePermissionBlockedMessage();
                        }
                    });
                } else {
                    console.error('Error getting location:', error.code, error.message);
                    // Hide warning for other errors
                    hidePermissionBlockedMessage();
                }
                
                // Always reset calculating text first
                const distanceContainers = document.querySelectorAll('.distance-container');
                distanceContainers.forEach(function(container) {
                    const distanceText = container.querySelector('.distance-text');
                    const getDistanceBtn = container.querySelector('.get-distance-btn');
                    
                    if (distanceText) {
                        // Reset to dash if it was calculating
                        if (distanceText.textContent === 'Calculating...') {
                            distanceText.textContent = '-';
                            distanceText.style.display = 'inline';
                        }
                    }
                    
                    // Hide button if it's somehow visible
                    if (getDistanceBtn) {
                        getDistanceBtn.style.display = 'none';
                        getDistanceBtn.classList.add('hidden');
                    }
                });
                
                // Show "Get Distance" buttons immediately for all error cases
                showGetDistanceButtons();
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0 // Don't use cache for button-triggered requests
            }
        );
    }
    
    // Function to handle "Get Distance" button click
    function handleGetDistanceClick(e) {
        if (!e || typeof e.preventDefault !== 'function') {
            // If event object is not valid, create a synthetic event behavior
            e = { preventDefault: function() {}, stopPropagation: function() {} };
        } else {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Prevent multiple simultaneous requests
        if (isLocationRequestInProgress) {
            return;
        }
        
        // Hide all buttons temporarily
        hideGetDistanceButtons();
        
        // Show calculating state for all distance containers
        const distanceContainers = document.querySelectorAll('.distance-container');
        distanceContainers.forEach(function(container) {
            const distanceText = container.querySelector('.distance-text');
            if (distanceText) {
                // Set to calculating if it's dash or empty
                if (distanceText.textContent === '-' || distanceText.textContent.trim() === '' || distanceText.textContent === 'Get Distance') {
                    distanceText.textContent = 'Calculating...';
                    distanceText.style.display = 'inline';
                }
            }
        });
        
        // Request location directly from user gesture (button click)
        // Call immediately without delay to ensure it's triggered by user gesture
        requestLocationFromButtonClick();
    }
    
    // Function to get user location and update distances (for initial page load)
    function getLocationAndUpdateDistances() {
        if (!navigator.geolocation) {
            // Geolocation is not supported
            console.warn('Geolocation is not supported by this browser.');
            showGetDistanceButtons();
            return;
        }
        
        // Request user's location
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Success callback
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                
                console.log('User location:', userLat, userLng);
                
                // Hide any visible "Get Distance" buttons
                hideGetDistanceButtons();
                
                // Update distances immediately
                updateDistances(userLat, userLng);
            },
            function(error) {
                // Error callback (for page load - don't show warning here, only on button click)
                if (error.code === error.PERMISSION_DENIED) {
                    console.warn('Location permission denied on page load');
                    // Don't show warning on initial page load, only when button is clicked
                    hidePermissionBlockedMessage();
                } else {
                    console.error('Error getting location:', error.code, error.message);
                }
                
                // Always reset calculating text first
                const distanceContainers = document.querySelectorAll('.distance-container');
                distanceContainers.forEach(function(container) {
                    const distanceText = container.querySelector('.distance-text');
                    if (distanceText && distanceText.textContent === 'Calculating...') {
                        distanceText.textContent = '-';
                        distanceText.style.display = 'inline';
                    }
                });
                
                // Show "Get Distance" buttons immediately for all error cases
                showGetDistanceButtons();
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 60000 // Cache for 1 minute
            }
        );
    }
    
    // Add click event listeners to all "Get Distance" buttons using event delegation
    let buttonClickHandlerAttached = false;
    
    function attachGetDistanceButtonListeners() {
        // Only attach delegation listener once
        if (!buttonClickHandlerAttached) {
            // Use event delegation for dynamically shown buttons
            document.addEventListener('click', function(e) {
                const button = e.target.closest('.get-distance-btn');
                if (button && !isLocationRequestInProgress) {
                    // Call handler with proper event object
                    handleGetDistanceClick(e);
                }
            }, true); // Use capture phase to catch event early
            
            buttonClickHandlerAttached = true;
        }
    }
    
    // Attach event listener to "Copy Settings URL" button
    function attachSettingsButtonListener() {
        const copyBtn = document.getElementById('copySettingsUrlBtn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                copyBrowserSettingsUrl();
            });
        }
    }
    
    // Function to copy browser settings URL to clipboard
    function copyBrowserSettingsUrl() {
        // Detect browser and get settings URL
        const userAgent = navigator.userAgent.toLowerCase();
        const isChrome = userAgent.indexOf('chrome') > -1 && userAgent.indexOf('edge') === -1 && userAgent.indexOf('edg/') === -1;
        const isEdge = userAgent.indexOf('edge') > -1 || userAgent.indexOf('edg/') > -1;
        const isFirefox = userAgent.indexOf('firefox') > -1;
        const isOpera = userAgent.indexOf('opr/') > -1 || userAgent.indexOf('opera') > -1;
        const isBrave = userAgent.indexOf('brave') > -1;
        
        // Get browser-specific settings URL
        let settingsUrl = '';
        if (isChrome) {
            settingsUrl = 'chrome://settings/content/location';
        } else if (isEdge) {
            settingsUrl = 'edge://settings/content/location';
        } else if (isFirefox) {
            settingsUrl = 'about:preferences#privacy';
        } else if (isOpera) {
            settingsUrl = 'opera://settings/content/location';
        } else if (isBrave) {
            settingsUrl = 'brave://settings/content/location';
        }
        
        if (settingsUrl === '') {
            alert('Settings URL not available for this browser. Please follow the manual instructions above.');
            return;
        }
        
        const copyBtn = document.getElementById('copySettingsUrlBtn');
        
        // Copy to clipboard
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(settingsUrl).then(function() {
                // Update button to show success
                if (copyBtn) {
                    copyBtn.textContent = '✅ URL Copied! Paste in address bar';
                    copyBtn.style.backgroundColor = '#10b981';
                    
                    // Show help text
                    const msgElement = document.getElementById('locationPermissionBlockedMsg');
                    if (msgElement) {
                        let helpText = msgElement.querySelector('.url-help-text');
                        if (!helpText) {
                            helpText = document.createElement('div');
                            helpText.className = 'url-help-text mt-2 text-xs text-yellow-700 font-medium';
                            msgElement.appendChild(helpText);
                        }
                        helpText.textContent = '✅ URL copied! Paste "' + settingsUrl + '" in your browser\'s address bar and press Enter.';
                        
                        // Remove help text after 5 seconds
                        setTimeout(function() {
                            if (helpText && helpText.parentNode) {
                                helpText.parentNode.removeChild(helpText);
                            }
                        }, 5000);
                    }
                    
                    // Reset button after 3 seconds
                    setTimeout(function() {
                        if (copyBtn) {
                            copyBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> 📋 Copy Settings URL to Clipboard';
                            copyBtn.style.backgroundColor = '';
                        }
                    }, 3000);
                }
            }).catch(function(err) {
                console.error('Failed to copy URL:', err);
                // Fallback to prompt if clipboard API fails
                prompt('Please copy this URL and paste it in your browser address bar:', settingsUrl);
            });
        } else {
            // Fallback for browsers without clipboard API
            prompt('Please copy this URL and paste it in your browser address bar:', settingsUrl);
        }
    }
    
    // Initialize on page load - wait for DOM and other scripts
    function initDistanceCalculation() {
        // Attach button listeners first
        attachGetDistanceButtonListeners();
        
        // Attach settings button listener
        attachSettingsButtonListener();
        
        // Check if validation has passed before requesting location
        if (window.workstationValidationPassed) {
            // Request location immediately when page loads
            // Modern browsers allow location request on page load for the first time
            getLocationAndUpdateDistances();
        } else {
            // Wait for validation to pass
            setTimeout(initDistanceCalculation, 100);
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Small delay to ensure validation script and other scripts have run
            setTimeout(initDistanceCalculation, 200);
        });
    } else {
        // DOM already loaded
        setTimeout(initDistanceCalculation, 200);
    }
})();

// Prevent back button navigation to verify page
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
    
    const cart = getCart();
    if (cart && cart.phone_verified) {
        // User has verified phone, prevent going back to verify page
        // Remove verify page from history by replacing it
        if (window.history && window.history.replaceState) {
            // Replace any verify page entry in history with current page
            const currentUrl = window.location.href;
            if (verifyPageUrl && currentUrl.includes(verifyPageUrl.split('/').pop())) {
                // If somehow on verify page, redirect immediately
                return;
            }
            
            // Push a new state to prevent back navigation to verify page
            history.pushState({ page: 'workstation' }, '', window.location.href);
            
            // Listen for popstate event (back/forward button)
            window.addEventListener('popstate', function(event) {
                // Check if trying to go back to verify page
                const currentCart = getCart();
                if (currentCart && currentCart.phone_verified) {
                    // Phone is verified, don't allow going back to verify page
                    // Push forward again to stay on current page
                    history.pushState({ page: 'workstation' }, '', window.location.href);
                    
                    // Also check URL and redirect if somehow on verify page
                    if (verifyPageUrl && window.location.href.includes(verifyPageUrl.split('/').pop())) {
                        window.location.replace(window.location.href.replace(verifyPageUrl.split('/').pop(), 'workstation'));
                    }
                }
            });
        }
    }
})();

// Handle Change button click to remove verified phone and redirect to verify page
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
    
    function handleChangeButtonClick() {
        // Get Change button by ID
        const changeMobileBtn = document.getElementById('changeMobileNumberBtn');
        
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
        document.addEventListener('DOMContentLoaded', handleChangeButtonClick);
    } else {
        handleChangeButtonClick();
    }
})();

// Smooth scroll to Select Service Center section after page load
(function() {
    'use strict';
    
    function scrollToServiceCenterSection() {
        const serviceCenterSection = document.getElementById('selectServiceCenterSection');
        if (serviceCenterSection) {
            // Calculate offset to account for fixed header
            const headerOffset = 100; // Adjust this value based on your header height
            const elementPosition = serviceCenterSection.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            
            // Smooth scroll
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }
    
    // Scroll to service center section when page is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            scrollToServiceCenterSection();
        });
    } else {
        scrollToServiceCenterSection();
    }
})();
</script>

<?php get_footer(); ?>