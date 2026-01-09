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
    }
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
                            <p class="text-[#6B6B6B] text-sm font-medium">We'll send you an OTP to verify your number</p>
                        </div>
                        <div class="w-full bg-[#F1FAF1] border border-[#D1EAD1] p-6 flex justify-between items-center md:rounded-none rounded-lg">
                            <div class="flex items-center gap-3">
                                <span>
                                    <img src="<?php echo esc_url($img_url); ?>success-check-icon.svg" alt="success check" class="size-9" />
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div class="text-base text-[#2F2F2F] font-semibold">Mobile Verified Successfully</div>
                                    <div class="text-[#637083] font-normal text-xs">+91 4323423423</div>
                                </div>
                            </div>
                            <a href="" class="text-[#6B6B6B] font-medium text-sm duration-300 hover:underline">Change</a>
                        </div>
                    </div>
                    <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Select Service Center</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Choose the nearest location for your service</p>
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
                                    $is_checked = $index === 0 ? 'checked' : '';
                            ?>
                            <label for="<?php echo esc_attr($service_id); ?>" class="group/s cursor-pointer w-full relative border border-[#E5E5E5] has-[:checked]:border-[#CB122D] p-4 bg-white flex justify-between gap-2 md:rounded-none rounded-lg service-center-item" data-center-name="<?php echo esc_attr(strtolower($center_name)); ?>" data-center-city="<?php echo esc_attr(strtolower($center_city)); ?>">
                                <input type="radio" name="service" id="<?php echo esc_attr($service_id); ?>" class="hidden" value="<?php echo esc_attr($center_name); ?>" <?php echo $is_checked; ?>>
                                <div class="flex flex-col gap-y-3">
                                    <h3 class="text-[#2F2F2F] group-has-[:checked]/s:text-[#CB122D] font-semibold text-base"><?php echo esc_html($center_name); ?></h3>
                                    <?php if (!empty($center_city)) : ?>
                                    <div class="text-sm text-[#6B6B6B] font-normal"><?php echo esc_html($center_city); ?></div>
                                    <?php endif; ?>
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1 text-sm text-[#AFAFAF] group-has-[:checked]/s:text-[#CB122D]">
                                            <span>
                                                <img src="<?php echo esc_url($img_url); ?>location-pin-icon.svg" alt="location" class="size-[0.75rem]" />
                                            </span>
                                            2.5 km
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
                                <div id="bookingVehicleName" class="text-[#2F2F2F] font-bold text-sm">-</div>
                                <div id="bookingVehicleFuel" class="font-normal text-sm text-[#6B6B6B]">-</div>
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
                                <span>
                                    <img src="<?php echo esc_url($img_url); ?>info-icon-disclaimer.svg" alt="info" class="size-[0.813rem]" />
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

<div class="view h-[4.938rem] group/check  fixed bottom-0 inset-x-0  md:hidden flex justify-between items-center bg-white border border-[#E5E5E5] shadow-[0_-0.25rem_1rem_0_#00000014]">
    <label for="price" class="cursor-pointer w-1/2 flex items-center font-bold text-base text-[#C8102E] gap-2">
        <input type="checkbox" name="priceCheck" id="price" class="hidden" />
        <span id="mobileTotalAmount">₹0</span>
        <span class="group-has-[:checked]/check:rotate-180 duration-500">
            <img src="<?php echo esc_url($img_url); ?>dropdown-arrow-down.svg" alt="dropdown" width="11" height="6" />
        </span>
        <div class="view bg-white w-full duartion-300 group-has-[#price:checked]/check:flex hidden py-6 flex-col gap-y-4 absolute bottom-full inset-x-0 shadow-[0_-0.25rem_1rem_0_#00000014] border-t border-[#E5E5E5]">
            <div class="flex flex-col gap-2">
                <div class="text-[#AFAFAF] text-xs font-bold uppercase">Vehicle</div>
                <div id="mobileVehicleName" class="text-[#2F2F2F] font-bold text-sm uppercase">-</div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="text-[#AFAFAF] text-xs font-bold uppercase">Services</div>
                <div id="mobileServicesList" class="flex flex-col gap-2">
                    <!-- Services will be populated dynamically -->
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="text-[#AFAFAF] text-xs font-bold uppercase">Location</div>
                <div id="mobileLocation" class="text-[#2F2F2F] font-normal text-sm">-</div>
            </div>
        </div>
    </label>
    <a href="" class="w-1/2 bg-[#AFAFAF] w-full rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D]">Confirm Booking</a>
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
</script>

<?php get_footer(); ?>