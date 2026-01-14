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
                    <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Select Date & Time</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Your selected date and time slot</p>
                        </div>
                        <div class="w-full bg-[#F1FAF1] border border-[#D1EAD1] p-6 flex justify-between items-center md:rounded-none rounded-lg">
                            <div class="flex items-center gap-3">
                                <span>
                                    <img src="<?php echo esc_url($img_url); ?>success-check-icon.svg" alt="success check" class="size-9" />
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div id="selectedDateAndTime" class="text-base text-[#2F2F2F] font-semibold empty:hidden">-</div>
                                    <div id="selectedTimeSlot" class="text-[#637083] font-normal text-xs empty:hidden"></div>
                                </div>
                            </div>
                            <a href="" id="changeDateAndTimeBtn" class="text-[#6B6B6B] font-medium text-sm duration-300 hover:underline">Change</a>
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
    <button type="button" class="w-1/2 bg-[#AFAFAF] w-full rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D] disabled:bg-gray-400 disabled:cursor-not-allowed disabled" disabled="true">Confirm Booking</button>
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
                // Format date from d-m-Y to readable format
                const dateParts = cart.selected_date.split('-');
                if (dateParts.length === 3) {
                    const day = parseInt(dateParts[0], 10);
                    const month = parseInt(dateParts[1], 10) - 1; // Month is 0-indexed
                    const year = parseInt(dateParts[2], 10);
                    const dateObj = new Date(year, month, day);
                    
                    // Format date as "DD Month YYYY" (e.g., "15 January 2024")
                    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    const formattedDate = day + ' ' + monthNames[month] + ' ' + year;
                    
                    if (selectedDateAndTimeEl) {
                        selectedDateAndTimeEl.textContent = formattedDate;
                    }
                } else {
                    if (selectedDateAndTimeEl) {
                        selectedDateAndTimeEl.textContent = cart.selected_date;
                    }
                }
                
                // Display time slot
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
</script>

<?php get_footer(); ?>