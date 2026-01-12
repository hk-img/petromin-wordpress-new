<?php
/* Template Name: verify page */
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

?>
<style>
/* Hide page content initially until validation passes */
body.verify-page {
    visibility: hidden;
    opacity: 0;
}
body.verify-page.validation-passed {
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
    const workstationUrl = '<?php echo esc_js($workstation_page_url); ?>';
    
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
    
    // Check if phone is already verified - if yes, redirect to workstation immediately
    const currentCart = getCart();
    if (currentCart && currentCart.phone_verified) {
        // Phone already verified, redirect to workstation immediately without showing page
        if (workstationUrl && workstationUrl !== '') {
            window.location.replace(workstationUrl);
            return; // Exit early, don't proceed with page initialization
        }
    }
    
    // Add class to body for verify page
    if (document.body) {
        document.body.classList.add('verify-page');
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('verify-page');
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
    }
    
    // Additional check on page visibility change (when user navigates back)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page became visible - check again if phone is verified
            const currentCart = getCart();
            if (currentCart && currentCart.phone_verified) {
                if (workstationUrl && workstationUrl !== '') {
                    window.location.replace(workstationUrl);
                }
            }
        }
    });
    
    // Also check on focus event (when user comes back to tab)
    window.addEventListener('focus', function() {
        const currentCart = getCart();
        if (currentCart && currentCart.phone_verified) {
            if (workstationUrl && workstationUrl !== '') {
                window.location.replace(workstationUrl);
            }
        }
    });
})();
</script>

<header class="w-full md:flex hidden justify-center items-center top-0 right-0  bg-white font-poppins fixed z-40 h-24 border-b border-[#E5E7EB]">
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
                    <div class="border-t border-dashed border-[#696B79] w-16"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-gray-400 font-medium text-sm tracking-wide">
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
    <a href="javascript:void(0);" id="backButton" class="flex items-center gap-4 uppercase text-[#121212] text-lg font-medium">
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
                <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                    <div class="flex flex-col gap-y-2">
                        <h1 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Verify Mobile Number</h1>
                        <p class="text-[#6B6B6B] text-sm font-medium">We'll send you an OTP to verify your number</p>
                    </div>
                    <form id="otpVerificationForm" class="w-full flex flex-col gap-y-6">
                        <!-- Success/Error Messages -->
                        <div id="otpMessage" class="hidden p-4 rounded-lg text-sm font-medium"></div>
                        
                        <div id="mobileNumberSection" class="flex flex-col gap-2 w-full">
                            <label class="block text-sm font-semibold text-[#2F2F2F]">Mobile Number</label>
                            <div class="flex items-center gap-3">
                                <div class="w-20 shrink-0 flex md:rounded-none rounded-lg justify-center gap-1 bg-[#F8F8F8] border border-[#E5E5E5] items-center md:text-base text-sm font-medium text-[#2F2F2F] h-[2.875rem]">
                                    <span>
                                        <img fetchpriority="low" loading="lazy" src="<?php echo $img_url; ?>indiaFlag.webp" class="w-4 h-auto" alt="">
                                    </span>
                                    +91
                                </div>
                                <div class="w-full">
                                    <input type="tel" id="mobileNumberInput" name="mobile_number" inputmode="numeric" pattern="[6-9][0-9]{9}" maxlength="10" class="bg-white !border !border-solid md:rounded-none rounded-lg !border-[#E5E5E5] focus:!border-red-500 h-[2.875rem] w-full text-[#0A0A0A80] p-6" placeholder="Enter 10 digit mobile number" value="" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="sendOtpBtn" class="w-full flex justify-center items-center bg-[#C1122C] h-[2.875rem] text-white font-semibold md:rounded-none rounded-lg text-base hover:bg-[#650916] duration-500 disabled:bg-gray-400 disabled:cursor-not-allowed relative">
                            <span id="sendOtpBtnText">Send OTP</span>
                            <span id="sendOtpBtnLoader" class="hidden absolute inset-0 flex items-center justify-center">
                                <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                <span>Sending...</span>
                            </span>
                        </button>
                        
                        <div id="otpSection" class="flex flex-col gap-2 w-full hidden">
                            <label class="block text-sm font-semibold text-[#2F2F2F]">Enter OTP</label>
                            <div class="flex items-center gap-3" id="otpInputContainer">
                                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-input sm:size-[3.25rem] w-full aspect-[1/1] md:rounded-none rounded-lg text-center text-lg font-bold bg-white !border !border-solid border-[#E5E5E5] text-[#0A0A0A] focus:!border-red-500" data-otp-index="0" />
                                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-input sm:size-[3.25rem] w-full aspect-[1/1] md:rounded-none rounded-lg text-center text-lg font-bold bg-white !border !border-solid border-[#E5E5E5] text-[#0A0A0A] focus:!border-red-500" data-otp-index="1" />
                                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-input sm:size-[3.25rem] w-full aspect-[1/1] md:rounded-none rounded-lg text-center text-lg font-bold bg-white !border !border-solid border-[#E5E5E5] text-[#0A0A0A] focus:!border-red-500" data-otp-index="2" />
                                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-input sm:size-[3.25rem] w-full aspect-[1/1] md:rounded-none rounded-lg text-center text-lg font-bold bg-white !border !border-solid border-[#E5E5E5] text-[#0A0A0A] focus:!border-red-500" data-otp-index="3" />
                                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-input sm:size-[3.25rem] w-full aspect-[1/1] md:rounded-none rounded-lg text-center text-lg font-bold bg-white !border !border-solid border-[#E5E5E5] text-[#0A0A0A] focus:!border-red-500" data-otp-index="4" />
                                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-input sm:size-[3.25rem] w-full aspect-[1/1] md:rounded-none rounded-lg text-center text-lg font-bold bg-white !border !border-solid border-[#E5E5E5] text-[#0A0A0A] focus:!border-red-500" data-otp-index="5" />
                            </div>
                            <div class="text-start text-[#6B6B6B] text-sm font-normal">
                                <span id="resendOtpText" class="hidden cursor-pointer hover:text-[#C1122C]" onclick="resendOTP()">Resend OTP</span>
                                <span id="resendOtpTimer">Resend OTP in <span id="timerCount">120</span>s</span>
                            </div>
                        </div>
                        <button type="button" id="verifyOtpBtn" class="w-full flex justify-center items-center bg-[#C1122C] h-[2.875rem] text-white font-semibold md:rounded-none rounded-lg text-base hover:bg-[#650916] duration-500 disabled:bg-gray-400 disabled:cursor-not-allowed hidden relative">
                            <span id="verifyOtpBtnText">Verify OTP</span>
                            <span id="verifyOtpBtnLoader" class="hidden absolute inset-0 flex items-center justify-center">
                                <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                <span>Verifying...</span>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="md:w-[30%] w-full md:block hidden">
                <div class="w-full flex flex-col bg-white shadow-[0_0.125rem_0.25rem_-0.125rem_#919191] border border-[#E5E5E5] md:sticky md:top-24">
                    <div class="w-full flex items-center h-[3.125rem] p-6 bg-gradient-to-l from-[#CB122D] to-[#650916] md:text-lg text-base font-bold  text-white">
                        Booking Summary
                    </div>
                    <div class="flex flex-col gap-y-6 bg-white p-6">
                        <div class="w-full flex flex-row">
                            <div class="w-1/3 text-[#A6A6A6] uppercase text-xs font-semibold">Vehicle</div>
                            <div class="w-2/3 flex flex-col gap-y-1">
                                <div id="bookingVehicleName" class="text-[#2F2F2F] font-bold text-sm empty:hidden"></div>
                                <div id="bookingVehicleFuel" class="font-normal text-sm text-[#6B6B6B] empty:hidden"></div>
                            </div>
                        </div>
                        <div class="border-t border-[#EFEFEF] pt-6">
                            <div class="w-full flex flex-row ">
                                <div class="w-1/3 text-[#A6A6A6] uppercase text-xs font-semibold">Services</div>
                                <div id="bookingServicesList" class="w-2/3 flex flex-col gap-y-3">
                                    <!-- Services will be populated dynamically -->
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-[#EFEFEF] pt-6">
                            <p id="bookingDisclaimer" class="text-xs bg-[#FF83000D] p-4 font-medium flex gap-2 border border-[#DF7300] text-[#DF7300]">
                                <span class="inline-flex">
                                    <img src="<?php echo $img_url; ?>info-icon.svg" alt="info icon" class="size-[0.813rem] shrink-0" />
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
            <img src="<?php echo $img_url; ?>dropdown-arrow.svg" alt="dropdown arrow" class="size-[0.688rem]" />
        </span>
        <div class="view bg-white w-full duartion-300 group-has-[#price:checked]/check:flex hidden py-6 flex-col gap-y-4 absolute bottom-full inset-x-0 shadow-[0_-0.25rem_1rem_0_#00000014] border-t border-[#E5E5E5] max-h-[calc(100dvh-154px)] overflow-y-auto">
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
        </div>
    </label>
    <button type="button" class="w-1/2 bg-[#AFAFAF] w-full rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D] disabled:bg-gray-400 disabled:cursor-not-allowed disabled" disabled="true">Confirm Booking</button>
</div>

<script>
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
        
        // Location (Mobile)
        const mobileLocationEl = document.getElementById('mobileLocation');
        if (mobileLocationEl) {
            if (cart && cart.vehicle && cart.vehicle.city) {
                mobileLocationEl.textContent = cart.vehicle.city;
            } else {
                mobileLocationEl.textContent = '-';
            }
        }
    }
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', populateBookingSummary);
    } else {
        populateBookingSummary();
    }
})();

// OTP Input Management
(function() {
    'use strict';
    
    const otpInputs = document.querySelectorAll('.otp-input');
    
    if (otpInputs.length === 0) return;
    
    // Check if all inputs are empty
    function areAllInputsEmpty() {
        return Array.from(otpInputs).every(input => input.value === '');
    }
    
    // Get first empty input index
    function getFirstEmptyIndex() {
        for (let i = 0; i < otpInputs.length; i++) {
            if (otpInputs[i].value === '') {
                return i;
            }
        }
        return -1; // All filled
    }
    
    // Handle paste event
    function handlePaste(e) {
        e.preventDefault();
        const pastedData = (e.clipboardData || window.clipboardData).getData('text');
        const numbers = pastedData.replace(/\D/g, ''); // Remove non-numeric
        
        if (numbers.length === 0) return;
        
        // Start from first empty input or current input
        const startIndex = areAllInputsEmpty() ? 0 : Array.from(otpInputs).indexOf(e.target);
        
        // Fill inputs with pasted numbers
        for (let i = 0; i < numbers.length && (startIndex + i) < otpInputs.length; i++) {
            otpInputs[startIndex + i].value = numbers[i];
        }
        
        // Focus on next empty input or last input
        const nextEmptyIndex = getFirstEmptyIndex();
        if (nextEmptyIndex !== -1 && nextEmptyIndex < otpInputs.length) {
            otpInputs[nextEmptyIndex].focus();
        } else {
            otpInputs[otpInputs.length - 1].focus();
        }
    }
    
    // Handle input event
    function handleInput(e) {
        const currentInput = e.target;
        const currentIndex = parseInt(currentInput.getAttribute('data-otp-index'));
        
        // Allow only numeric input
        const value = currentInput.value.replace(/\D/g, '');
        currentInput.value = value;
        
        // If value entered, move to next input
        if (value && currentIndex < otpInputs.length - 1) {
            otpInputs[currentIndex + 1].focus();
        }
    }
    
    // Handle keydown event
    function handleKeyDown(e) {
        const currentInput = e.target;
        const currentIndex = parseInt(currentInput.getAttribute('data-otp-index'));
        
        // Handle backspace/delete
        if (e.key === 'Backspace' || e.key === 'Delete') {
            if (currentInput.value === '' && currentIndex > 0) {
                // If current is empty and we're not at first input, move to previous and clear it
                otpInputs[currentIndex - 1].value = '';
                otpInputs[currentIndex - 1].focus();
            } else {
                // Clear current input
                currentInput.value = '';
            }
            e.preventDefault();
        }
        
        // Handle arrow keys
        if (e.key === 'ArrowLeft' && currentIndex > 0) {
            otpInputs[currentIndex - 1].focus();
            e.preventDefault();
        }
        if (e.key === 'ArrowRight' && currentIndex < otpInputs.length - 1) {
            otpInputs[currentIndex + 1].focus();
            e.preventDefault();
        }
    }
    
    // Handle focus event
    function handleFocus(e) {
        const currentInput = e.target;
        const currentIndex = parseInt(currentInput.getAttribute('data-otp-index'));
        
        // Check if there's any empty field before the current field
        let hasEmptyBefore = false;
        for (let i = 0; i < currentIndex; i++) {
            if (otpInputs[i].value === '') {
                hasEmptyBefore = true;
                break;
            }
        }
        
        // If there's an empty field before current, focus on first empty field instead
        if (hasEmptyBefore) {
            const firstEmptyIndex = getFirstEmptyIndex();
            if (firstEmptyIndex !== -1 && firstEmptyIndex < otpInputs.length) {
                e.preventDefault();
                setTimeout(() => {
                    otpInputs[firstEmptyIndex].focus();
                }, 0);
                return;
            }
        }
        
        // If all inputs are empty and user clicks in middle, focus first empty
        if (areAllInputsEmpty()) {
            const firstEmptyIndex = getFirstEmptyIndex();
            if (firstEmptyIndex !== -1 && firstEmptyIndex < otpInputs.length) {
                if (firstEmptyIndex !== currentIndex) {
                    e.preventDefault();
                    setTimeout(() => {
                        otpInputs[firstEmptyIndex].focus();
                    }, 0);
                    return;
                }
            }
        }
        
        // Select all text in current input for easy replacement
        currentInput.select();
    }
    
    // Attach event listeners to all OTP inputs
    otpInputs.forEach(input => {
        input.addEventListener('paste', handlePaste);
        input.addEventListener('input', handleInput);
        input.addEventListener('keydown', handleKeyDown);
        input.addEventListener('focus', handleFocus);
        
        // Prevent non-numeric input and check for empty fields before
        input.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            if (!/[0-9]/.test(char)) {
                e.preventDefault();
                return;
            }
            
            // Check if there's any empty field before current
            const currentIndex = parseInt(e.target.getAttribute('data-otp-index'));
            let hasEmptyBefore = false;
            for (let i = 0; i < currentIndex; i++) {
                if (otpInputs[i].value === '') {
                    hasEmptyBefore = true;
                    break;
                }
            }
            
            // If there's empty field before, prevent input and focus first empty
            if (hasEmptyBefore) {
                e.preventDefault();
                const firstEmptyIndex = getFirstEmptyIndex();
                if (firstEmptyIndex !== -1 && firstEmptyIndex < otpInputs.length) {
                    otpInputs[firstEmptyIndex].focus();
                    // Set the value in first empty field
                    otpInputs[firstEmptyIndex].value = char;
                    // Move to next if not last
                    if (firstEmptyIndex < otpInputs.length - 1) {
                        setTimeout(() => {
                            otpInputs[firstEmptyIndex + 1].focus();
                        }, 0);
                    }
                }
            }
        });
    });
    
    // Handle click on container - focus first empty input
    const otpContainer = document.getElementById('otpInputContainer');
    if (otpContainer) {
        otpContainer.addEventListener('click', function(e) {
            // Only handle if click is directly on container, not on an input
            if (e.target === otpContainer) {
                const firstEmptyIndex = getFirstEmptyIndex();
                if (firstEmptyIndex !== -1 && firstEmptyIndex < otpInputs.length) {
                    otpInputs[firstEmptyIndex].focus();
                }
            }
        });
    }
})();

// Mobile Number Input Management for Indian Numbers
(function() {
    'use strict';
    
    const mobileInput = document.getElementById('mobileNumberInput');
    if (!mobileInput) return;
    
    // Function to clean and format Indian mobile number
    function cleanIndianNumber(value) {
        // Remove all non-numeric characters
        let cleaned = value.replace(/\D/g, '');
        
        // Remove +91 or 91 from the start if present
        if (cleaned.startsWith('91')) {
            cleaned = cleaned.substring(2);
        }
        
        // Only keep first 10 digits (Indian mobile numbers are 10 digits)
        cleaned = cleaned.substring(0, 10);
        
        return cleaned;
    }
    
    // Handle paste event
    function handleMobilePaste(e) {
        e.preventDefault();
        const pastedData = (e.clipboardData || window.clipboardData).getData('text');
        const cleaned = cleanIndianNumber(pastedData);
        mobileInput.value = cleaned;
        
        // Move cursor to end
        setTimeout(() => {
            mobileInput.setSelectionRange(cleaned.length, cleaned.length);
        }, 0);
    }
    
    // Handle input event
    function handleMobileInput(e) {
        const currentValue = e.target.value;
        const cleaned = cleanIndianNumber(currentValue);
        
        // Only update if value changed (to avoid cursor jumping)
        if (cleaned !== currentValue) {
            const cursorPosition = e.target.selectionStart;
            mobileInput.value = cleaned;
            
            // Try to maintain cursor position relative to content
            const newPosition = Math.min(cursorPosition, cleaned.length);
            setTimeout(() => {
                mobileInput.setSelectionRange(newPosition, newPosition);
            }, 0);
        }
        
        // Validate if number starts with 6, 7, 8, or 9 (valid Indian mobile number prefixes)
        if (cleaned.length > 0 && !/^[6-9]/.test(cleaned)) {
            // Remove invalid first digit
            const validNumber = cleaned.substring(1);
            mobileInput.value = validNumber;
        }
    }
    
    // Handle keypress to prevent non-numeric input
    function handleMobileKeyPress(e) {
        const char = String.fromCharCode(e.which || e.keyCode);
        
        // Allow: backspace, delete, tab, escape, enter, and arrow keys
        if ([8, 9, 27, 13, 46, 35, 36, 37, 38, 39, 40].indexOf(e.keyCode || e.which) !== -1 ||
            (e.keyCode === 65 && e.ctrlKey === true) || // Allow Ctrl+A
            (e.keyCode === 67 && e.ctrlKey === true) || // Allow Ctrl+C
            (e.keyCode === 86 && e.ctrlKey === true) || // Allow Ctrl+V
            (e.keyCode === 88 && e.ctrlKey === true) || // Allow Ctrl+X
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;
        }
        
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
        
        // Check if first digit is valid (6-9 for Indian numbers)
        if (mobileInput.value.length === 0 && !/[6-9]/.test(char)) {
            e.preventDefault();
        }
    }
    
    // Attach event listeners
    mobileInput.addEventListener('paste', handleMobilePaste);
    mobileInput.addEventListener('input', handleMobileInput);
    mobileInput.addEventListener('keypress', handleMobileKeyPress);
    
    // Also handle the initial value cleanup if any
    if (mobileInput.value) {
        mobileInput.value = cleanIndianNumber(mobileInput.value);
    }
})();

// MSG91 OTP Integration
(function() {
    'use strict';
    
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const sendOtpBtnText = document.getElementById('sendOtpBtnText');
    const sendOtpBtnLoader = document.getElementById('sendOtpBtnLoader');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const verifyOtpBtnText = document.getElementById('verifyOtpBtnText');
    const verifyOtpBtnLoader = document.getElementById('verifyOtpBtnLoader');
    const otpSection = document.getElementById('otpSection');
    const mobileNumberSection = document.getElementById('mobileNumberSection');
    const mobileInput = document.getElementById('mobileNumberInput');
    const otpMessage = document.getElementById('otpMessage');
    const otpInputs = document.querySelectorAll('.otp-input');
    let resendTimer = null;
    let timerCount = 120;
    
    // Get nonce value - check if it exists, if not create a placeholder
    const otpNonce = '<?php echo wp_create_nonce("otp_nonce"); ?>';
    const ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';
    
    // Function to show message
    function showMessage(message, isError = false) {
        otpMessage.textContent = message;
        otpMessage.className = 'p-4 rounded-lg text-sm font-medium ' + (isError ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-green-100 text-green-700 border border-green-300');
        otpMessage.classList.remove('hidden');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            otpMessage.classList.add('hidden');
        }, 5000);
    }
    
    // Function to start resend timer
    function startResendTimer() {
        timerCount = 120;
        const timerCountEl = document.getElementById('timerCount');
        const resendOtpText = document.getElementById('resendOtpText');
        const resendOtpTimer = document.getElementById('resendOtpTimer');
        
        resendOtpText.classList.add('hidden');
        resendOtpTimer.classList.remove('hidden');
        
        if (resendTimer) {
            clearInterval(resendTimer);
        }
        
        resendTimer = setInterval(function() {
            timerCount--;
            if (timerCountEl) {
                timerCountEl.textContent = timerCount;
            }
            
            if (timerCount <= 0) {
                clearInterval(resendTimer);
                resendOtpText.classList.remove('hidden');
                resendOtpTimer.classList.add('hidden');
            }
        }, 1000);
    }
    
    // Function to get OTP from inputs
    function getOTP() {
        let otp = '';
        otpInputs.forEach(input => {
            otp += input.value;
        });
        return otp;
    }
    
    // Function to clear OTP inputs
    function clearOTPInputs() {
        otpInputs.forEach(input => {
            input.value = '';
        });
    }
    
    // Function to send OTP
    function sendOTP() {
        const mobile = mobileInput.value.trim();
        
        if (!mobile || mobile.length !== 10 || !/^[6-9][0-9]{9}$/.test(mobile)) {
            showMessage('Please enter a valid 10-digit mobile number', true);
            mobileInput.focus();
            return;
        }
        
        // Show loader and disable button
        if (sendOtpBtnText) {
            sendOtpBtnText.classList.add('hidden');
        }
        if (sendOtpBtnLoader) {
            sendOtpBtnLoader.classList.remove('hidden');
        }
        sendOtpBtn.disabled = true;
        
        // AJAX call to send OTP
        const formData = new FormData();
        formData.append('action', 'send_otp');
        formData.append('nonce', otpNonce);
        formData.append('mobile', mobile);
        
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
                const message = (data.data && data.data.message) ? data.data.message : 'OTP sent successfully!';
                showMessage(message, false);
                
                // Reset loader state before hiding button
                if (sendOtpBtnText) {
                    sendOtpBtnText.classList.remove('hidden');
                }
                if (sendOtpBtnLoader) {
                    sendOtpBtnLoader.classList.add('hidden');
                }
                sendOtpBtn.disabled = false;
                
                // Hide mobile number section
                if (mobileNumberSection) {
                    mobileNumberSection.classList.add('hidden');
                }
                if (sendOtpBtn) {
                    sendOtpBtn.classList.add('hidden');
                }
                
                // Show OTP section
                otpSection.classList.remove('hidden');
                verifyOtpBtn.classList.remove('hidden');
                startResendTimer();
                
                // Focus first OTP input
                if (otpInputs.length > 0) {
                    setTimeout(() => {
                        otpInputs[0].focus();
                    }, 100);
                }
            } else {
                const errorMsg = (data && data.data && data.data.message) ? data.data.message : 'Failed to send OTP. Please try again.';
                showMessage(errorMsg, true);
                // Reset loader and re-enable button on error
                if (sendOtpBtnText) {
                    sendOtpBtnText.classList.remove('hidden');
                }
                if (sendOtpBtnLoader) {
                    sendOtpBtnLoader.classList.add('hidden');
                }
                sendOtpBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred. Please try again. Error: ' + error.message, true);
            // Reset loader and re-enable button on error
            if (sendOtpBtnText) {
                sendOtpBtnText.classList.remove('hidden');
            }
            if (sendOtpBtnLoader) {
                sendOtpBtnLoader.classList.add('hidden');
            }
            if (sendOtpBtn) {
                sendOtpBtn.disabled = false;
            }
        });
    }
    
    // Function to verify OTP
    function verifyOTP() {
        const mobile = mobileInput.value.trim();
        const otp = getOTP();
        const workstationUrl = '<?php echo esc_js($workstation_page_url); ?>';
        
        if (!mobile || mobile.length !== 10) {
            showMessage('Please enter a valid mobile number', true);
            return;
        }
        
        if (otp.length !== 6) {
            showMessage('Please enter complete 6-digit OTP', true);
            otpInputs[0].focus();
            return;
        }
        
        // Show loader and disable button
        if (verifyOtpBtnText) {
            verifyOtpBtnText.classList.add('hidden');
        }
        if (verifyOtpBtnLoader) {
            verifyOtpBtnLoader.classList.remove('hidden');
        }
        verifyOtpBtn.disabled = true;
        
        // Disable all OTP input fields during verification
        otpInputs.forEach(function(input) {
            input.disabled = true;
        });
        
        // AJAX call to verify OTP
        const formData = new FormData();
        formData.append('action', 'verify_otp');
        formData.append('nonce', otpNonce);
        formData.append('mobile', mobile);
        formData.append('otp', otp);
        
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
                // Hide loader and show success in button text
                if (verifyOtpBtnLoader) {
                    verifyOtpBtnLoader.classList.add('hidden');
                }
                if (verifyOtpBtnText) {
                    verifyOtpBtnText.textContent = 'Verified ✓';
                    verifyOtpBtnText.classList.remove('hidden');
                }
                // Change button styling to success (green)
                if (verifyOtpBtn) {
                    verifyOtpBtn.classList.remove('bg-[#C1122C]', 'hover:bg-[#650916]');
                    verifyOtpBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                    verifyOtpBtn.disabled = true; // Keep disabled to prevent multiple clicks
                }
                
                // Don't clear OTP inputs on success - keep them visible until redirect
                // This prevents the inputs from appearing empty during redirect delay
                
                // Save verified phone number to sessionStorage
                try {
                    const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
                    let cart = cartData ? JSON.parse(cartData) : { vehicle: {}, items: [] };
                    
                    // Save verified phone number
                    cart.verified_phone = mobile;
                    cart.phone_verified = true;
                    
                    sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
                    
                    // Save booking data to WordPress database
                    const saveBookingFormData = new FormData();
                    saveBookingFormData.append('action', 'save_booking_data');
                    saveBookingFormData.append('nonce', otpNonce);
                    saveBookingFormData.append('booking_data', JSON.stringify(cart));
                    
                    fetch(ajaxUrl, {
                        method: 'POST',
                        body: saveBookingFormData
                    })
                    .then(response => response.json())
                    .then(saveData => {
                        if (saveData && saveData.success) {
                            console.log('Booking saved successfully with ID:', saveData.data.booking_id);
                            // Optionally save booking ID to sessionStorage
                            if (saveData.data && saveData.data.booking_id) {
                                try {
                                    cart.booking_id = saveData.data.booking_id;
                                    sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
                                } catch (e) {
                                    console.error('Error saving booking ID:', e);
                                }
                            }
                        } else {
                            console.error('Failed to save booking:', saveData);
                        }
                    })
                    .catch(error => {
                        console.error('Error saving booking data:', error);
                        // Don't block user flow even if save fails
                    });
                } catch (e) {
                    console.error('Error saving verified phone:', e);
                }
                
                // Redirect to workstation page after short delay using replace to prevent back navigation
                setTimeout(() => {
                    if (workstationUrl && workstationUrl !== '') {
                        // Use replace instead of href to prevent verify page from being in history
                        window.location.replace(workstationUrl);
                    } else {
                        console.error('Workstation page URL not found');
                        showMessage('Verification successful! Please continue.', false);
                        // Re-enable OTP input fields if redirect failed
                        otpInputs.forEach(function(input) {
                            input.disabled = false;
                        });
                        // Reset loader if redirect failed
                        if (verifyOtpBtnText) {
                            verifyOtpBtnText.classList.remove('hidden');
                        }
                        if (verifyOtpBtnLoader) {
                            verifyOtpBtnLoader.classList.add('hidden');
                        }
                        if (verifyOtpBtn) {
                            verifyOtpBtn.disabled = false;
                        }
                    }
                }, 1500);
            } else {
                const errorMsg = (data && data.data && data.data.message) ? data.data.message : 'Invalid OTP. Please try again.';
                showMessage(errorMsg, true);
                // Only clear OTP inputs on error, not on success
                clearOTPInputs();
                // Re-enable OTP input fields on error
                otpInputs.forEach(function(input) {
                    input.disabled = false;
                });
                if (otpInputs.length > 0) {
                    otpInputs[0].focus();
                }
                // Reset loader and re-enable button on error
                if (verifyOtpBtnText) {
                    verifyOtpBtnText.classList.remove('hidden');
                }
                if (verifyOtpBtnLoader) {
                    verifyOtpBtnLoader.classList.add('hidden');
                }
                if (verifyOtpBtn) {
                    verifyOtpBtn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred. Please try again. Error: ' + error.message, true);
            // Re-enable OTP input fields on error
            otpInputs.forEach(function(input) {
                input.disabled = false;
            });
            // Reset loader and re-enable button on error
            if (verifyOtpBtnText) {
                verifyOtpBtnText.classList.remove('hidden');
            }
            if (verifyOtpBtnLoader) {
                verifyOtpBtnLoader.classList.add('hidden');
            }
            if (verifyOtpBtn) {
                verifyOtpBtn.disabled = false;
            }
        });
    }
    
    // Global function for resend OTP
    window.resendOTP = function() {
        sendOTP();
    };
    
    // Back button handler - redirect to previous page
    const backButton = document.getElementById('backButton');
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            e.preventDefault();
            // Use browser history to go back, or fallback to cost-estimator page
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Fallback: redirect to cost-estimator page if no history
                const costEstimatorUrl = '<?php echo esc_js($cost_estimator_page_url); ?>';
                if (costEstimatorUrl && costEstimatorUrl !== '') {
                    window.location.href = costEstimatorUrl;
                }
            }
        });
    }
    
    // Event listeners
    if (sendOtpBtn) {
        sendOtpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sendOTP();
        });
    }
    
    if (verifyOtpBtn) {
        verifyOtpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            verifyOTP();
        });
    }
    
    // Auto-submit OTP when all fields are filled
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            if (input.value && index === otpInputs.length - 1) {
                // Last input filled, check if all are filled
                const otp = getOTP();
                if (otp.length === 6) {
                    // Auto verify after short delay
                    setTimeout(() => {
                        verifyOTP();
                    }, 500);
                }
            }
        });
    });
})();
</script>

<?php get_footer(); ?>