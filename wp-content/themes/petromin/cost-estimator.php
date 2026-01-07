<?php
/* Template Name: cost estimator page */
get_header();

// Get query parameters - decode first to preserve spaces, then sanitize
$city = isset($_GET['city']) ? sanitize_text_field(urldecode($_GET['city'])) : '';
$brand = isset($_GET['brand']) ? sanitize_text_field(urldecode($_GET['brand'])) : '';
$model = isset($_GET['model']) ? sanitize_text_field(urldecode($_GET['model'])) : '';
$fuel = isset($_GET['fuel']) ? sanitize_text_field(urldecode($_GET['fuel'])) : '';

// Get theme assets directory URL for images
$img_url = get_template_directory_uri() . '/assets/img/';

// Fetch service categories from API
$categories_api_url = 'https://ryehkyasumhivlakezjb.supabase.co/rest/v1/rpc/get_unique_service_category';
$categories_response = wp_remote_get($categories_api_url, array(
    'timeout' => 15,
    'headers' => array(
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'apikey' => 'sb_publishable_YqO5Tv3YM4BquKiCgHqs3w_8Wd7-trp'
    )
));

$service_categories = array();
if (!is_wp_error($categories_response) && wp_remote_retrieve_response_code($categories_response) === 200) {
    $categories_body = wp_remote_retrieve_body($categories_response);
    $categories_data = json_decode($categories_body, true);
    if (is_array($categories_data)) {
        $service_categories = $categories_data;
    }
}

// Function to fetch services for a category with filters
function get_services_by_category($category, $car_make = '', $car_model = '', $fuel_type = '') {
    $api_url = 'https://ryehkyasumhivlakezjb.supabase.co/rest/v1/public_services_by_category?service_category=eq.' . urlencode($category);
    
    // Add filters to API URL if parameters are provided
    if (!empty($car_make)) {
        $api_url .= '&car_make=eq.' . urlencode($car_make);
    }
    if (!empty($car_model)) {
        $api_url .= '&car_model=eq.' . urlencode($car_model);
    }
    if (!empty($fuel_type)) {
        $api_url .= '&fuel_type=eq.' . urlencode($fuel_type);
    }
    
    $response = wp_remote_get($api_url, array(
        'timeout' => 15,
        'headers' => array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'apikey' => 'sb_publishable_YqO5Tv3YM4BquKiCgHqs3w_8Wd7-trp'
        )
    ));
    
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $body = wp_remote_retrieve_body($response);
        $services = json_decode($body, true);
        return is_array($services) ? $services : array();
    }
    return array();
}

// Generate slug from category name for IDs
function get_category_slug($category_name) {
    return strtolower(str_replace(array(' ', '&'), array('', ''), $category_name));
}

// Fetch car makes from API for form dropdown
$car_makes_api_url = 'https://ryehkyasumhivlakezjb.supabase.co/rest/v1/rpc/get_unique_car_makes';
$car_makes_response = wp_remote_get($car_makes_api_url, array(
    'timeout' => 15,
    'headers' => array(
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'apikey' => 'sb_publishable_YqO5Tv3YM4BquKiCgHqs3w_8Wd7-trp'
    )
));

$car_makes = array();
if (!is_wp_error($car_makes_response) && wp_remote_retrieve_response_code($car_makes_response) === 200) {
    $car_makes_body = wp_remote_retrieve_body($car_makes_response);
    $car_makes = json_decode($car_makes_body, true);
    if (!is_array($car_makes)) {
        $car_makes = array();
    }
}

?>

<section class="bg-white md:py-20 py-20">
    <div class="w-full flex flex-col gap-y-6">
        <div class="w-full group/services">
            <div class="max-md:pb-3 border-b border-[#E5E7EB]">
                <div class="view w-full flex whitespace-nowrap max-md:overflow-x-auto">
                    <?php 
                    if (!empty($service_categories)) {
                        $first_category = true;
                        foreach ($service_categories as $index => $category_item) {
                            $category_name = isset($category_item['service_category']) ? $category_item['service_category'] : '';
                            if (empty($category_name)) continue;
                            
                            $category_slug = get_category_slug($category_name);
                            $category_id = $category_slug . 'Service';
                            ?>
                            <label for="<?php echo esc_attr($category_id); ?>"
                                class="group/serviceTab cursor-pointer py-4 px-10 flex flex-col items-center gap-3 justify-center text-sm font-medium border-b-4 border-transparent has-[:checked]:border-[#980D22] has-[:checked]:font-bold has-[:checked]:text-[#CB122D]">
                                <input type="radio" name="services" id="<?php echo esc_attr($category_id); ?>" class="hidden" <?php echo $first_category ? 'checked' : ''; ?> />
                                <span
                                    class="bg-gradient-to-br from-[#F3F4F6] to-[#F3F4F6] group-has-[:checked]/serviceTab:from-[#CB122D] group-has-[:checked]/serviceTab:to-[#980D22] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A] size-[3.438rem] rounded-full flex justify-center items-center">
                                    <img src="<?php echo esc_url($img_url . 'carServiceIcon.webp'); ?>" 
                                        class="brightness-[0.4] group-has-[:checked]/serviceTab:brightness-[1] size-7" 
                                        alt="<?php echo esc_attr($category_name . ' Icon'); ?>" 
                                        width="28" 
                                        height="28" />
                                </span>
                                <?php echo esc_html($category_name); ?>
                            </label>
                            <?php 
                            $first_category = false;
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="view w-full pt-12">
                <div class="flex md:flex-row flex-col gap-6 relative">
                    <div class="md:w-[70%] w-full">
                        <!-- Tab content -->
                        <?php 
                        if (!empty($service_categories)) {
                            foreach ($service_categories as $cat_index => $category_item) {
                                $category_name = isset($category_item['service_category']) ? $category_item['service_category'] : '';
                                if (empty($category_name)) continue;
                                
                                $category_slug = get_category_slug($category_name);
                                $category_id = $category_slug . 'Service';
                                
                                // Fetch services for this category with filters from URL params
                                $services = get_services_by_category($category_name, $brand, $model, $fuel);
                                ?>
                                <div class="group-has-[#<?php echo esc_attr($category_id); ?>:checked]/services:flex hidden h-full">
                                    <div class="flex flex-col md:gap-y-12 gap-y-8 w-full">
                                        <?php 
                                        // Check if all required fields are selected
                                        $all_fields_selected = !empty($brand) && !empty($model) && !empty($fuel);
                                        
                                        if (!$all_fields_selected) {
                                            // Show empty state if fields are not selected
                                            ?>
                                            <div class="w-full flex flex-col items-center justify-center py-16 px-4">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Select Your Vehicle</h3>
                                                    <p class="text-sm text-gray-500 mb-4">Please select Brand, Model, and Fuel Type to view available services.</p>
                                                </div>
                                            </div>
                                            <?php
                                        } elseif (!empty($services)) {
                                            foreach ($services as $service) {
                                                $service_name = isset($service['service_name']) ? $service['service_name'] : '';
                                                $price = isset($service['price']) ? $service['price'] : '';
                                                $currency = isset($service['currency']) ? $service['currency'] : 'INR';
                                                $service_details = isset($service['service_details']) && is_array($service['service_details']) ? $service['service_details'] : array();
                                                $disclaimer = isset($service['disclaimer']) ? $service['disclaimer'] : 'This is an estimated price, Final price may vary based on your car model and condition.';
                                                $service_id = isset($service['id']) ? $service['id'] : md5($service_name . $price . $category_name);
                                                
                                                // Prepare complete service data for sessionStorage
                                                $service_data_json = json_encode($service);
                                                ?>
                                                <div class="w-full flex flex-col gap-y-6 md:rounded-none rounded-lg bg-white border border-[#E5E7EB] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]" data-service-id="<?php echo esc_attr($service_id); ?>">
                                                    <div class="flex md:flex-row flex-col gap-3">
                                                        <div class="md:w-1/4 w-full relative">
                                                            <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($img_url . 'ImageWithFallback.webp'); ?>"
                                                                class="size-full md:rounded-none rounded-lg aspect-square"
                                                                width="189" height="189" alt="" title="">
                                                            <div class="absolute -top-[3.2rem] -left-[3rem]">
                                                                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($img_url . 'limitedOffer.webp'); ?>"
                                                                    class="h-[14.75rem] w-auto object-contain" alt="" title="">
                                                            </div>
                                                        </div>
                                                        <div class="md:w-3/4 w-full">
                                                            <div class="flex flex-col gap-y-6 p-4">
                                                                <h2 class="font-bold lg:text-xl text-lg text-[#121212]">
                                                                    <?php echo esc_html($service_name); ?>
                                                                </h2>
                                                                <ul class="flex gap-2 items-center flex-wrap">
                                                                    <li
                                                                        class="border border-[#DF7300] bg-[#FF83000D] rounded-md py-2 px-3 flex gap-1 items-center font-medium text-[#DF7300] text-xs">
                                                                        <span>
                                                                            <img src="<?php echo esc_url($img_url . 'estimatedTimeIcon.svg'); ?>" 
                                                                                class="size-[0.875rem]" 
                                                                                width="14" 
                                                                                height="14" 
                                                                                alt="Estimated Time Icon" />
                                                                        </span>
                                                                        Estimated Time is 4 Hours
                                                                    </li>
                                                                    <li
                                                                        class="border border-[#DF7300] bg-[#FF83000D] rounded-md py-2 px-3 flex gap-1 items-center font-medium text-[#DF7300] text-xs">
                                                                        <span>
                                                                            <img src="<?php echo esc_url($img_url . 'warrnetyIcon.svg'); ?>" 
                                                                                class="size-[0.875rem]" 
                                                                                width="14" 
                                                                                height="14" 
                                                                                alt="Warrnety Icon" />
                                                                        </span>
                                                                        3 Month Warrnety
                                                                    </li>
                                                                    <li
                                                                        class="border border-[#DF7300] bg-[#FF83000D] rounded-md py-2 px-3 flex gap-1 items-center font-medium text-[#DF7300] text-xs">
                                                                        <span>
                                                                            <img src="<?php echo esc_url($img_url . 'thumbsUpIcon.svg'); ?>" 
                                                                                class="size-[0.875rem]" 
                                                                                width="14" 
                                                                                height="14" 
                                                                                alt="Recommended Icon" />
                                                                        </span>
                                                                        Recommended every 10,000 KM
                                                                    </li>
                                                                </ul>
                                                                <ul class="flex flex-wrap justify-between gap-y-3 items-center">
                                                                    <?php 
                                                                    if (!empty($service_details)) {
                                                                        // Generate unique ID for this service's details
                                                                        $service_details_id = 'service-details-' . md5($service_name . serialize($service_details));
                                                                        $detail_count = 0;
                                                                        foreach ($service_details as $detail) {
                                                                            $is_hidden = $detail_count >= 5;
                                                                            ?>
                                                                            <li
                                                                                class="service-detail-item md:w-1/2 w-full flex items-center gap-2 font-normal text-xs <?php echo $is_hidden ? 'hidden' : ''; ?>"
                                                                                data-service-details="<?php echo esc_attr($service_details_id); ?>">
                                                                                <span>
                                                                                    <img src="<?php echo esc_url($img_url . 'checkeddIcon.svg'); ?>" 
                                                                                        class="size-5" 
                                                                                        width="20" 
                                                                                        height="20" 
                                                                                        alt="Check Icon" />
                                                                                </span>
                                                                                <?php echo esc_html($detail); ?>
                                                                            </li>
                                                                            <?php 
                                                                            $detail_count++;
                                                                        }
                                                                        if (count($service_details) > 5) {
                                                                            ?>
                                                                            <li class="md:w-1/2 w-full">
                                                                                <a href="javascript:void(0);" 
                                                                                    class="view-more-btn text-xs text-[#CB122D] underline" 
                                                                                    data-service-details="<?php echo esc_attr($service_details_id); ?>">
                                                                                    View More
                                                                                </a>
                                                                                <a href="javascript:void(0);" 
                                                                                    class="view-less-btn text-xs text-[#CB122D] underline hidden" 
                                                                                    data-service-details="<?php echo esc_attr($service_details_id); ?>">
                                                                                    View Less
                                                                                </a>
                                                                            </li>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-full flex flex-col gap-y-2 p-6 border-t border-[#E5E7EB]">
                                                        <div class="flex justify-between gap-3 items-center">
                                                            <div class="flex flex-col gap-y-2">
                                                                <div class="flex items-center md:gap-6 gap-4">
                                                                    <?php if (!empty($price)) : ?>
                                                                    <div
                                                                        class="text-[#121212] lg:text-2xl md:text-xl text-base font-bold">
                                                                        <?php echo esc_html($currency === 'INR' ? '₹' : $currency) . ' ' . esc_html(number_format($price)); ?>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <button type="button" 
                                                                    class="add-to-cart-btn flex flex-row-reverse items-center gap-2 text-sm font-semibold text-white bg-[#CB122D] h-[2.5rem] px-3 py-2 border border-transparent hover:bg-[#650916] duration-500"
                                                                    data-service-id="<?php echo esc_attr($service_id); ?>"
                                                                    data-service-data="<?php echo esc_attr($service_data_json); ?>">
                                                                    <span>
                                                                        <img class="size-[0.875rem]"  width="14" height="14" src="<?php echo esc_url($img_url . 'plusWhiteIcon.svg'); ?>" alt="Add to Cart Icon" />
                                                                    </span>
                                                                    Add to Cart
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <p class="text-xs text-[#000000] font-normal flex items-center gap-2">
                                                            <span>
                                                                <img src="<?php echo esc_url($img_url . 'estimatedIcon.svg'); ?>" 
                                                                    class="size-[0.875rem]" 
                                                                    width="14" 
                                                                    height="14" 
                                                                    alt="Estimated Icon" />
                                                            </span>
                                                            <?php echo esc_html($disclaimer); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php 
                                            }
                                        } else {
                                            // Show empty state if no services found but fields are selected
                                            ?>
                                            <div class="w-full flex flex-col items-center justify-center py-16 px-4">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Services Found</h3>
                                                    <p class="text-sm text-gray-500">No services available for the selected vehicle in this category.</p>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php 
                            }
                        }
                        ?>
                    </div>
                    <div class="md:w-[30%] w-full md:block hidden">
                        <div
                            class="w-full flex flex-col bg-white shadow-[0_0.125rem_0.25rem_-0.125rem_#919191] border border-[#E5E5E5] md:sticky top-6">
                            <div
                                class="w-full flex items-center h-[3.125rem] p-6 bg-gradient-to-l  from-[#CB122D] to-[#650916] lg:text-xl md:text-lg text-base font-bold italic text-white uppercase">
                                Your Cart
                            </div>
                            <div id="cartContentSection" class="flex flex-col gap-y-6 bg-white p-6">
                                <div id="vehicleDisplaySection" class="w-full flex flex-row justify-between bg-white border border-[#EFEFEF] p-6">
                                    <div class="flex flex-row gap-2">
                                        <span class="bg-gradient-to-br from-[#CB122D] to-[#980D22] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A] size-[3.438rem] rounded-full flex justify-center items-center">
                                            <img src="<?php echo esc_url($img_url . 'carServiceIcon.webp'); ?>" alt="<?php echo esc_attr(!empty($brand) ? $brand . ' Logo' : 'Vehicle'); ?>" class="size-[1.75rem]" width="28" height="28" />
                                        </span>
                                        <div class="flex flex-col gap-y-1">
                                            <div class="text-[#A6A6A6] uppercase text-xs font-semibold">Your Vehicle</div>
                                            <div class="text-[#1A1A1A] lg:text-lg md:text-md text-base font-semibold">
                                                <?php
                                                    $vehicle_title = trim($brand . ' ' . $model);
                                                    echo esc_html($vehicle_title !== '' ? $vehicle_title : 'Vehicle not selected');
                                                ?>
                                            </div>
                                            <div class="text-[#6F6F6F] text-sm font-nromal">
                                                <?php
                                                    $fuel_label = !empty($fuel) ? $fuel : 'Fuel not selected';
                                                    echo esc_html($fuel_label);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" id="changeVehicleBtn"
                                        class="bg-transparent p-0 m-0  border-0 text-[#CB122D] font-semibold underline md:text-sm text-xs hover:text-[#650916] duration-500">Click
                                        to change
                                    </a>
                                </div>

                                <div id="cartItemsContainer" class="border-t border-[#EFEFEF] pt-6 flex flex-col gap-4">
                                    <!-- Cart items will be loaded dynamically from sessionStorage -->
                                </div>
                                <div id="cartSubtotalSection" class="border-t border-[#EFEFEF] pt-6 hidden">
                                    <div
                                        class="w-full flex flex-col gap-y-2 justify-between bg-white border border-[#EFEFEF] p-6">
                                        <div class="w-full flex justify-between items-center gap-2">
                                            <div class="text-[#444444] text-sm font-medium">
                                                Subtotal (<span id="cartItemCount">0</span> service<span id="cartItemCountPlural">s</span>)
                                            </div>
                                            <div id="cartSubtotal" class="text-[#121212] lg:text-lg md:text-md text-base font-bold">
                                                ₹0
                                            </div>
                                        </div>
                                        <p class="text-[#AFAFAF] text-xs font-normal">* Final price may vary after
                                            diagnosis</p>
                                    </div>
                                </div>
                                <div id="checkoutButtonSection" class="w-full hidden">
                                    <a href=""
                                        class="h-[3.438rem] w-full bg-[#CB122D] text-base flex justify-center items-center text-white font-bold hover:bg-[#650916] duration-500">Proceed
                                        to Checkout</a>
                                </div>
                            </div>
                            <div id="vehicleFormSection" class="flex flex-col gap-y-6 bg-white md:p-6 hidden">
                                <div
                                    class="w-full md:flex hidden flex-row justify-between bg-white px-6 pt-6">
                                    <div class="flex flex-row gap-2">
                                        <span
                                            class="bg-[#F3F3F3] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A] size-[3.438rem] rounded-full flex justify-center items-center">
                                            <svg class="size-5" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.8302 14.1639H17.4965C17.9964 14.1639 18.3297 13.8306 18.3297 13.3307V10.8311C18.3297 10.0813 17.7465 9.41473 17.0799 9.24809C15.5802 8.8315 13.3306 8.33159 13.3306 8.33159C13.3306 8.33159 12.2475 7.16513 11.4976 6.41526C11.081 6.08199 10.5811 5.83203 9.99787 5.83203H4.16557C3.66566 5.83203 3.24907 6.16531 2.99911 6.5819L1.83265 8.99813C1.72232 9.31993 1.66602 9.65777 1.66602 9.99796V13.3307C1.66602 13.8306 1.99929 14.1639 2.4992 14.1639H4.16557" stroke="#6B6B6B" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M5.83239 15.8305C6.7527 15.8305 7.49876 15.0845 7.49876 14.1642C7.49876 13.2439 6.7527 12.4978 5.83239 12.4978C4.91207 12.4978 4.16602 13.2439 4.16602 14.1642C4.16602 15.0845 4.91207 15.8305 5.83239 15.8305Z" stroke="#6B6B6B" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7.49902 14.1641H12.4981" stroke="#6B6B6B" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M14.1644 15.8308C15.0847 15.8308 15.8308 15.0847 15.8308 14.1644C15.8308 13.2441 15.0847 12.498 14.1644 12.498C13.2441 12.498 12.498 13.2441 12.498 14.1644C12.498 15.0847 13.2441 15.8308 14.1644 15.8308Z" stroke="#6B6B6B" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                
                                        </span>
                                        <div class="flex flex-col gap-y-1">
                                            <div class="text-[#A6A6A6] uppercase text-xs font-semibold">Your Vehicle
                                            </div>
                                            <div
                                                class="text-[#1A1A1A] lg:text-lg md:text-md text-base font-semibold">
                                                <?php
                                                    $vehicle_title = trim($brand . ' ' . $model);
                                                    echo esc_html($vehicle_title !== '' ? $vehicle_title : 'Vehicle not selected');
                                                ?></div>
                                            <div class="text-[#6F6F6F] text-sm font-nromal">
                                                <?php
                                                    $fuel_label = !empty($fuel) ? $fuel : 'Fuel not selected';
                                                    echo esc_html($fuel_label);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" id="cancelVehicleBtn"
                                            class="bg-transparent p-0 m-0  border-0 text-[#CB122D] font-semibold hover:underline md:text-sm text-xs hover:text-[#650916] duration-500">Cancel
                                        </button>
                                    </div>
                                </div>
                                <form action="" class="flex flex-col gap-y-6 border-t border-[#EFEFEF] pt-6">
                                    <!-- Car Brand Dropdown -->
                                    <div class="w-full relative">
                                        <label for=""
                                            class="font-semibold md:text-base text-sm text-[#6B6B6B]">Brand</label>
                                        <div class="relative mt-2">
                                            <input type="text" placeholder="Car Brand" id="vehicleBrandInput" readonly=""
                                                value="<?php echo esc_attr($brand); ?>"
                                                class="w-full px-4 py-3 text-black bg-white border border-[#E3E3E3] placeholder:text-black xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-pointer md:rounded-none rounded-lg">
                                            <span id="vehicleBrandIcon"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16"
                                                    height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805"
                                                        stroke="#6B6B6B" stroke-width="1.33282"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>

                                        <div id="vehicleBrandDropdown"
                                            class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden">
                                            <div class="p-3">
                                                <div class="flex items-center bg-gray-100 px-3 py-2 mb-2 rounded">
                                                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none"
                                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    <input type="text" id="vehicleBrandSearch"
                                                        placeholder="Search Car Brand"
                                                        class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700" />
                                                </div>

                                                <div class="grid grid-cols-3 gap-4 max-h-56 overflow-y-auto"
                                                    id="vehicleBrandList">
                                                    <?php if (!empty($car_makes)) : ?>
                                                        <?php foreach ($car_makes as $make) : 
                                                            $car_make_name = isset($make['car_make']) ? $make['car_make'] : '';
                                                            $car_make_logo = isset($make['car_make_logo_url']) && !empty($make['car_make_logo_url']) ? $make['car_make_logo_url'] : '';
                                                            
                                                            // Use fallback logo if logo is not available
                                                            $has_logo = !empty($car_make_logo);
                                                            $logo_src = $has_logo ? esc_url($car_make_logo) : esc_url($img_url . 'petromin-logo-300x75-1.webp');
                                                            $fade_class = $has_logo ? '' : 'opacity-40';
                                                        ?>
                                                            <div class="cursor-pointer text-center" data-value="<?php echo esc_attr($car_make_name); ?>">
                                                                <img src="<?php echo $logo_src; ?>" 
                                                                    alt="<?php echo esc_attr($car_make_name); ?>" 
                                                                    class="w-16 mx-auto mb-1 aspect-square object-contain <?php echo $fade_class; ?>"
                                                                    loading="lazy" 
                                                                    fetchpriority="low"
                                                                    onerror="this.src='<?php echo esc_url($img_url . 'petromin-logo-300x75-1.webp'); ?>'; this.classList.add('opacity-40');">
                                                                <p class="text-xs"><?php echo esc_html($car_make_name); ?></p>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- Empty State -->
                                                <div id="vehicleBrandEmptyState" class="hidden text-center py-8">
                                                    <p class="text-gray-500 text-sm">No car brands found</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Car Model Dropdown -->
                                    <div class="w-full relative">
                                        <label for=""
                                            class="font-semibold md:text-base text-sm text-[#6B6B6B]">Model</label>
                                        <div class="relative mt-2">
                                            <input type="text" placeholder="Car Model (Select Car Brand first)"
                                                id="vehicleModelInput" readonly=""
                                                value="<?php echo esc_attr($model); ?>"
                                                class="w-full px-4 py-3 text-gray-800 bg-white border border-[#E3E3E3] placeholder:text-black/50 xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-pointer md:rounded-none rounded-lg" disabled>
                                            <span id="vehicleModelIcon"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16"
                                                    height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805"
                                                        stroke="#6B6B6B" stroke-width="1.33282"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>

                                        <div id="vehicleModelDropdown"
                                            class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden">
                                            <div class="p-3">
                                                <div class="flex items-center bg-gray-100 px-3 py-2 mb-2 rounded">
                                                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none"
                                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    <input type="text" id="vehicleModelSearch"
                                                        placeholder="Search Car Model"
                                                        class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700" />
                                                </div>

                                                <div class="grid grid-cols-2 gap-4 max-h-56 overflow-y-auto"
                                                    id="vehicleModelList">
                                                    <!-- Models will be loaded dynamically via AJAX -->
                                                </div>
                                                <!-- Empty State -->
                                                <div id="vehicleModelEmptyState" class="hidden text-center py-8">
                                                    <p class="text-gray-500 text-sm">No car models found</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fuel Type Dropdown -->
                                    <div class="w-full relative">
                                        <label for="" class="font-semibold md:text-base text-sm text-[#6B6B6B]">Fuel
                                            Type</label>
                                        <div class="relative mt-2">
                                            <input type="text" placeholder="Fuel Type" id="vehicleFuelInput" readonly=""
                                                value="<?php echo esc_attr($fuel); ?>"
                                                class="w-full px-4 py-3 text-gray-800 bg-white border border-[#E3E3E3] placeholder:text-black/50 xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-pointer md:rounded-none rounded-lg" disabled>
                                            <span id="vehicleFuelIcon"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16"
                                                    height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805"
                                                        stroke="#6B6B6B" stroke-width="1.33282"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>

                                        <div id="vehicleFuelType"
                                            class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden">
                                            <div class="p-3">
                                                <div class="grid grid-cols-3 gap-4 max-h-56 overflow-y-auto"
                                                    id="vehicleFuelList">
                                                    <div class="flex flex-col gap-2 items-center cursor-pointer text-center"
                                                        data-value="Petrol">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="48"
                                                            height="48" class="md:size-12 size-8 text-[#CB122D]"
                                                            viewBox="0 0 23 24" fill="none">
                                                            <g clip-path="url(#clip0_2808_4102)">
                                                                <path
                                                                    d="M22.3773 20.1645C22.671 19.1058 23.4307 15.9298 23.3604 14.7287C23.2136 12.438 20.3409 6.96505 20.2323 6.75456C19.9068 6.01162 18.9939 5.69588 18.2661 6.04877C17.6086 6.36452 17.3213 7.1446 17.6086 7.81324L19.2428 11.4846H19.1343C18.2278 11.4846 17.5 12.1903 17.5 13.0695V15.1125C16.4786 15.9236 15.8657 17.1556 15.8657 18.431V20.1583C15.6104 20.3007 15.4316 20.5483 15.4316 20.8641V22.8019C15.4316 23.26 15.7955 23.6129 16.2679 23.6129H22.2305C22.7029 23.6129 23.0668 23.26 23.0668 22.8019V20.8641C23.0285 20.5483 22.7412 20.2264 22.3773 20.1583V20.1645ZM16.5935 18.5053C16.5935 17.3785 17.1361 16.3198 18.0873 15.645C18.1959 15.5769 18.2342 15.4716 18.2342 15.3602V13.1376C18.2342 12.6423 18.6683 12.2584 19.1407 12.2584C19.7216 12.2584 19.977 12.6113 20.0472 13.1376C20.0855 13.3852 20.0472 13.28 20.0472 16.1712C20.0472 16.3446 20.194 16.5241 20.4111 16.5241C20.6281 16.5241 20.775 16.3508 20.775 16.1712V13.1376C20.775 12.6794 20.5579 12.2213 20.1557 11.9056L18.2661 7.56559C18.1193 7.24985 18.2661 6.85981 18.5917 6.68646C18.9556 6.5131 19.3577 6.64931 19.5365 7.03935C19.5748 7.07649 22.4475 12.6113 22.5944 14.7658C22.6646 15.8617 21.8666 19.1058 21.6113 20.0592H16.5552V18.5053H16.5935ZM21.2155 22.3128C20.8899 22.3128 20.5962 22.028 20.5962 21.7123C20.5962 21.3965 20.8899 21.1117 21.2155 21.1117C21.541 21.1117 21.8347 21.3965 21.8347 21.7123C21.8347 22.028 21.5793 22.3128 21.2155 22.3128Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M7.50174 20.0895V18.3621C7.50174 17.093 6.8825 15.8548 5.86746 15.0437V13.0007C5.86746 12.0101 4.92264 11.3043 4.11827 11.4157L5.72063 7.71346C6.01429 7.04482 5.72063 6.26474 5.06309 5.94899C4.33532 5.5961 3.42881 5.91185 3.13515 6.61763C3.02662 6.85908 0.153867 12.332 0.000653554 14.5856C-0.0695694 15.7867 0.690115 18.9627 0.983774 20.0214C0.581589 20.0895 0.294313 20.4114 0.294313 20.7953V22.7331C0.294313 23.1912 0.658195 23.5441 1.1306 23.5441H7.09317C7.56558 23.5441 7.92946 23.1912 7.92946 22.7331V20.7953C7.92946 20.4795 7.75071 20.1947 7.49535 20.0895H7.50174ZM0.734802 14.6227C0.881632 12.5054 3.75439 6.97052 3.79269 6.89623C3.93952 6.54334 4.37363 6.36999 4.73751 6.58049C5.06309 6.72288 5.20992 7.10673 5.06309 7.42867L3.17345 11.7686C2.80957 12.0534 2.55421 12.5116 2.55421 13.0007V16.0343C2.55421 16.2076 2.70104 16.3872 2.9181 16.3872C3.13515 16.3872 3.28198 16.2138 3.28198 16.0343V13.0688C3.3522 12.4001 3.75439 12.1525 4.18849 12.1525C4.6992 12.1525 5.09501 12.5425 5.09501 13.0316V15.2542C5.09501 15.3595 5.13331 15.4647 5.24184 15.539C6.18665 16.2076 6.73567 17.2663 6.73567 18.3993V19.9533H1.67962C1.46257 18.9627 0.658195 15.7185 0.734802 14.6289V14.6227ZM2.15203 22.1697C1.82645 22.1697 1.53279 21.8849 1.53279 21.5691C1.53279 21.2534 1.82645 20.9686 2.15203 20.9686C2.47761 20.9686 2.77127 21.2534 2.77127 21.5691C2.73296 21.8849 2.47761 22.1697 2.15203 22.1697Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M5.93819 2.94642C6.08502 3.08882 6.30207 3.08882 6.4489 2.94642C6.59573 2.80403 6.59573 2.59353 6.4489 2.45113L5.42748 1.46056C5.28065 1.31816 5.06359 1.31816 4.91676 1.46056C4.76993 1.60295 4.76993 1.81345 4.91676 1.95584L5.93819 2.94642Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M6.80563 1.85092C6.84393 2.02427 7.06098 2.16666 7.23973 2.09856C7.41848 2.06142 7.56531 1.85092 7.49509 1.67757L7.13121 0.265997C7.0929 0.0926462 6.87585 -0.0497491 6.6971 0.018353C6.51835 0.0554996 6.37152 0.265997 6.44175 0.439348L6.80563 1.85092Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M3.64486 3.65165L5.10039 4.00454C5.27914 4.04169 5.50258 3.93644 5.5345 3.7569C5.56642 3.57736 5.46427 3.36686 5.27914 3.3359L3.82361 2.98301C3.64486 2.94587 3.42142 3.05111 3.38951 3.23066C3.31928 3.40401 3.45973 3.58355 3.64486 3.65165Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M17.3205 2.94618C17.4674 3.08857 17.6844 3.08857 17.8312 2.94618L18.8144 1.9556C18.9612 1.8132 18.9612 1.60271 18.8144 1.46031C18.6675 1.31792 18.4505 1.31792 18.3037 1.46031L17.2822 2.45089C17.1737 2.55614 17.1737 2.80378 17.3205 2.94618Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M16.5238 2.09852C16.7025 2.13567 16.926 2.03042 16.9579 1.85088L17.3218 0.439304C17.3601 0.265954 17.2515 0.0492651 17.0664 0.0183096C16.8813 -0.0126459 16.6642 0.0864117 16.6323 0.265954L16.2684 1.67752C16.1982 1.85088 16.3386 2.06756 16.5238 2.09852Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M18.2347 3.75806C18.273 3.93141 18.4901 4.07381 18.6688 4.0057L20.1244 3.65281C20.3031 3.61567 20.4499 3.40517 20.3797 3.23182C20.3414 3.05847 20.1243 2.91607 19.9456 2.98417L18.4901 3.33707C18.3113 3.37421 18.1645 3.54756 18.2347 3.75806Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M16.74 4.07335C16.74 3.75761 16.4846 3.50996 16.159 3.47282C14.0843 3.29947 12.9224 2.48224 12.3415 1.956C12.0861 1.70835 11.6839 1.70835 11.4669 1.956C10.8859 2.48224 9.71768 3.29947 7.64929 3.47282C7.32372 3.50996 7.06836 3.75761 7.06836 4.07335V7.67038C7.06836 9.15006 7.64929 10.5988 8.81755 11.5832C9.61554 12.2889 10.5987 12.7842 11.7286 13.1681C11.8754 13.2052 12.0223 13.2052 12.1308 13.1681C13.2607 12.8152 14.2375 12.2889 15.0419 11.5832C16.1718 10.5926 16.7527 9.15006 16.7527 7.67038V4.07335H16.74ZM11.901 10.0044C10.4837 10.0044 9.31549 8.87765 9.31549 7.49703C9.31549 6.11642 10.4774 4.98964 11.901 4.98964C13.3246 4.98964 14.4865 6.11642 14.4865 7.49703C14.4865 8.87765 13.3246 10.0044 11.901 10.0044Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M12.5913 6.61721C12.553 6.65435 11.538 7.63874 11.5699 7.60778C11.5316 7.57064 11.1358 7.18679 11.1677 7.21774C11.0209 7.07535 10.8038 7.07535 10.657 7.21774C10.5102 7.36014 10.5102 7.57064 10.657 7.71303L11.3145 8.35071C11.4614 8.49311 11.6784 8.49311 11.8253 8.35071L13.0956 7.11869C13.2425 6.97629 13.2425 6.76579 13.0956 6.6234C12.9488 6.481 12.7318 6.481 12.5849 6.6234L12.5913 6.61721Z"
                                                                    fill="currentColor"></path>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_2808_4102">
                                                                    <rect width="23" height="24"
                                                                        fill="currentColor"></rect>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                        <p class="md:text-sm text-xs font-normal">Petrol</p>
                                                    </div>
                                                    <div class="flex flex-col gap-2 items-center cursor-pointer text-center"
                                                        data-value="Petrol">
                                                        <svg width="48" height="48"
                                                            class="md:size-12 size-8 text-[#CB122D]"
                                                            viewBox="0 0 29 34" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M26.7607 29.4609V33.6641C26.7607 33.8157 26.6751 33.9515 26.5498 34.0234C26.4165 34.0874 26.2592 34.0791 26.1416 33.9912L24.0869 32.457L22.0557 33.9834C21.9851 34.0313 21.9065 34.0635 21.8203 34.0635H21.8125C21.75 34.0634 21.6953 34.0473 21.6406 34.0234C21.5073 33.9595 21.4288 33.8158 21.4287 33.6641V29.4609L22.4951 29.4131L23.3652 30.1162C23.5769 30.284 23.8281 30.372 24.0947 30.3721C24.3614 30.3721 24.6203 30.284 24.8242 30.1162L25.6943 29.4131L26.7607 29.4609ZM20.6211 31.7227V31.7139H20.6367L20.6211 31.7227ZM17.335 23.332C17.7505 23.5397 18.2131 23.708 18.707 23.8838L18.8877 24.3867L18.4961 25.4502C18.3081 25.9695 18.4956 26.5606 18.9502 26.8643L19.876 27.4951L20.1738 28.5898C20.2444 28.8615 20.4094 29.0853 20.6211 29.2451V31.7139H18.668V28.958C18.668 28.7344 18.4958 28.5577 18.2764 28.5576C18.0568 28.5576 17.8838 28.7343 17.8838 28.958V31.7139H5.65039V28.958C5.65039 28.7344 5.47814 28.5579 5.25879 28.5576C5.03921 28.5576 4.86621 28.7343 4.86621 28.958V31.7139H0.388672C0.169094 31.7139 -0.00390625 31.5382 -0.00390625 31.3145C-0.00380302 25.6016 2.52974 24.6983 4.75684 23.9072C5.27423 23.7235 5.76023 23.5477 6.19141 23.332C6.64625 23.7795 7.13231 24.2516 7.26562 24.3555C8.55172 25.3543 10.1208 25.8975 11.7598 25.8975C13.3986 25.8974 15.1006 25.3062 16.418 24.2275C16.5123 24.1554 16.9117 23.7554 17.335 23.332ZM23.8457 19.2969C23.9868 19.1852 24.191 19.1851 24.332 19.2969L25.4297 20.1836L26.833 20.1201C27.0134 20.1121 27.1785 20.2324 27.2256 20.4082L27.6016 21.79L28.7705 22.5811C28.9194 22.685 28.9817 22.877 28.9189 23.0527L28.4248 24.3945L28.9189 25.7373C28.9817 25.913 28.9194 26.1051 28.7705 26.209L27.6016 27L27.2256 28.3818C27.1785 28.5576 27.0134 28.6859 26.833 28.6699L25.4297 28.6064L24.332 29.4932C24.2615 29.549 24.175 29.581 24.0889 29.5811C24.0027 29.5811 23.9162 29.549 23.8457 29.4932L22.7471 28.6064L21.3438 28.6699C21.1634 28.6779 20.9992 28.5576 20.9521 28.3818L20.5752 27L19.4072 26.209C19.2582 26.1051 19.1951 25.9131 19.2578 25.7373L19.752 24.3945L19.2578 23.0527C19.1951 22.8769 19.2582 22.6849 19.4072 22.5811L20.5752 21.79L20.9521 20.4082C20.9992 20.2324 21.1634 20.1122 21.3438 20.1201L22.7471 20.1836L23.8457 19.2969ZM24.0889 21.3506C23.9165 21.3506 23.7669 21.4623 23.7119 21.6299L23.1631 23.3643H21.3672C21.2027 23.3643 21.054 23.476 20.999 23.6436C20.9441 23.8114 20.9985 23.9879 21.1396 24.0918L22.5908 25.1621L22.0342 26.8965C21.9795 27.0642 22.0338 27.2399 22.1748 27.3438C22.3159 27.4476 22.5044 27.4475 22.6377 27.3438L24.0889 26.2646L25.5391 27.3438C25.6018 27.3997 25.6882 27.4238 25.7666 27.4238L25.7822 27.4316C25.8606 27.4316 25.9392 27.3995 26.0098 27.3516C26.1508 27.2478 26.2061 27.072 26.1514 26.9043L25.5938 25.1699L27.0449 24.0996C27.1859 23.9958 27.2411 23.82 27.1865 23.6523C27.1316 23.4845 26.9821 23.3721 26.8096 23.3721H25.0137L24.4648 21.6299C24.4099 21.4624 24.2611 21.3507 24.0889 21.3506ZM24.3477 23.8838C24.4026 24.0514 24.5512 24.163 24.7236 24.1631H25.5859L24.8887 24.6826C24.7476 24.7864 24.6923 24.9622 24.7471 25.1299L25.0137 25.9688L24.3164 25.4502C24.2538 25.3943 24.1672 25.3702 24.0889 25.3701L24.0732 25.3623C23.995 25.3623 23.9162 25.3936 23.8457 25.4414L23.1475 25.9609L23.4141 25.1221C23.4689 24.9543 23.4136 24.7787 23.2725 24.6748L22.5752 24.1631H23.4375C23.61 24.1631 23.7595 24.0516 23.8145 23.8838L24.0811 23.0449L24.3477 23.8838ZM15.4688 20.7754C15.6647 21.8138 16.088 22.4529 16.6523 22.9004C16.3467 23.2038 16.0883 23.4597 16.041 23.5078C14.8491 24.5385 13.3437 25.0985 11.7676 25.0986C10.1914 25.0986 8.83441 24.5949 7.67383 23.6602C7.57972 23.5882 7.24257 23.252 6.88184 22.9004C7.44628 22.453 7.86941 21.8139 8.06543 20.7754C9.05343 21.7821 10.2927 22.4692 11.7197 22.4854H11.791C13.226 22.4853 14.4886 21.8061 15.4688 20.7754ZM18.7939 8.11035C19.5466 8.11056 20.1582 8.73402 20.1582 9.50098V10.1641C20.1582 10.931 19.5466 11.5545 18.7939 11.5547H18.4697C18.9393 12.1295 19.1663 12.8994 19.0527 13.6953C18.9038 14.7659 18.1822 15.5413 17.1865 15.7412C16.7082 17.587 15.9547 19.0972 15.0059 20.1279C14.0649 21.1506 12.9594 21.6943 11.7988 21.6943H11.7363C10.4973 21.6784 9.32016 21.0553 8.32422 19.8887C7.34396 18.746 6.59153 17.1233 6.14453 15.1816C5.02312 15.0458 4.21549 14.3506 4.04297 13.3438C3.94721 12.7628 4.09267 12.0872 4.55664 11.54C3.89074 11.4482 3.37601 10.8675 3.37598 10.1641V9.50098C3.37598 8.73402 3.98757 8.11056 4.74023 8.11035H18.7939ZM5.87012 11.5547C4.95213 11.9448 4.72718 12.6831 4.81934 13.208C4.91343 13.7673 5.39941 14.3752 6.47363 14.4072C6.654 14.4072 6.81138 14.543 6.85059 14.7188C7.64267 18.4342 9.56408 20.8548 11.752 20.8867H11.7988C13.8533 20.8866 15.6492 18.7458 16.4883 15.2783C16.5275 15.1106 16.6684 14.9907 16.833 14.9746C17.7582 14.8947 18.1822 14.1994 18.2764 13.5762C18.3756 12.8684 18.08 11.9425 17.1963 11.5547H5.87012ZM8.58984 7.32715H4.72363V7.31934C4.84125 6.12892 5.26506 4.99445 5.97852 4.01172C6.57449 3.18074 7.34339 2.50144 8.22949 1.99805L8.58984 7.32715ZM13.6406 0C13.9385 3.14753e-05 14.2288 0.119465 14.4092 0.327148C14.5503 0.486958 14.6133 0.679144 14.5977 0.878906L14.166 7.31934H9.37402L8.94336 0.871094C8.92768 0.671332 8.99846 0.471333 9.14746 0.311523C9.32774 0.111965 9.60949 0.000111431 9.89941 0H13.6406ZM15.3105 1.93359C16.1966 2.42895 16.9655 3.11619 17.5693 3.95508C18.283 4.95389 18.7066 6.09697 18.8164 7.31152H14.9502L15.3105 1.93359Z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                        <p class=" md:text-sm text-xs font-normal">Petrol</p>
                                                    </div>
                                                    <div class="flex flex-col gap-2 items-center cursor-pointer text-center"
                                                        data-value="Petrol">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="48"
                                                            height="48" class="md:size-12 size-8 text-[#CB122D]"
                                                            viewBox="0 0 23 24" fill="none">
                                                            <g clip-path="url(#clip0_2808_4102)">
                                                                <path
                                                                    d="M22.3773 20.1645C22.671 19.1058 23.4307 15.9298 23.3604 14.7287C23.2136 12.438 20.3409 6.96505 20.2323 6.75456C19.9068 6.01162 18.9939 5.69588 18.2661 6.04877C17.6086 6.36452 17.3213 7.1446 17.6086 7.81324L19.2428 11.4846H19.1343C18.2278 11.4846 17.5 12.1903 17.5 13.0695V15.1125C16.4786 15.9236 15.8657 17.1556 15.8657 18.431V20.1583C15.6104 20.3007 15.4316 20.5483 15.4316 20.8641V22.8019C15.4316 23.26 15.7955 23.6129 16.2679 23.6129H22.2305C22.7029 23.6129 23.0668 23.26 23.0668 22.8019V20.8641C23.0285 20.5483 22.7412 20.2264 22.3773 20.1583V20.1645ZM16.5935 18.5053C16.5935 17.3785 17.1361 16.3198 18.0873 15.645C18.1959 15.5769 18.2342 15.4716 18.2342 15.3602V13.1376C18.2342 12.6423 18.6683 12.2584 19.1407 12.2584C19.7216 12.2584 19.977 12.6113 20.0472 13.1376C20.0855 13.3852 20.0472 13.28 20.0472 16.1712C20.0472 16.3446 20.194 16.5241 20.4111 16.5241C20.6281 16.5241 20.775 16.3508 20.775 16.1712V13.1376C20.775 12.6794 20.5579 12.2213 20.1557 11.9056L18.2661 7.56559C18.1193 7.24985 18.2661 6.85981 18.5917 6.68646C18.9556 6.5131 19.3577 6.64931 19.5365 7.03935C19.5748 7.07649 22.4475 12.6113 22.5944 14.7658C22.6646 15.8617 21.8666 19.1058 21.6113 20.0592H16.5552V18.5053H16.5935ZM21.2155 22.3128C20.8899 22.3128 20.5962 22.028 20.5962 21.7123C20.5962 21.3965 20.8899 21.1117 21.2155 21.1117C21.541 21.1117 21.8347 21.3965 21.8347 21.7123C21.8347 22.028 21.5793 22.3128 21.2155 22.3128Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M7.50174 20.0895V18.3621C7.50174 17.093 6.8825 15.8548 5.86746 15.0437V13.0007C5.86746 12.0101 4.92264 11.3043 4.11827 11.4157L5.72063 7.71346C6.01429 7.04482 5.72063 6.26474 5.06309 5.94899C4.33532 5.5961 3.42881 5.91185 3.13515 6.61763C3.02662 6.85908 0.153867 12.332 0.000653554 14.5856C-0.0695694 15.7867 0.690115 18.9627 0.983774 20.0214C0.581589 20.0895 0.294313 20.4114 0.294313 20.7953V22.7331C0.294313 23.1912 0.658195 23.5441 1.1306 23.5441H7.09317C7.56558 23.5441 7.92946 23.1912 7.92946 22.7331V20.7953C7.92946 20.4795 7.75071 20.1947 7.49535 20.0895H7.50174ZM0.734802 14.6227C0.881632 12.5054 3.75439 6.97052 3.79269 6.89623C3.93952 6.54334 4.37363 6.36999 4.73751 6.58049C5.06309 6.72288 5.20992 7.10673 5.06309 7.42867L3.17345 11.7686C2.80957 12.0534 2.55421 12.5116 2.55421 13.0007V16.0343C2.55421 16.2076 2.70104 16.3872 2.9181 16.3872C3.13515 16.3872 3.28198 16.2138 3.28198 16.0343V13.0688C3.3522 12.4001 3.75439 12.1525 4.18849 12.1525C4.6992 12.1525 5.09501 12.5425 5.09501 13.0316V15.2542C5.09501 15.3595 5.13331 15.4647 5.24184 15.539C6.18665 16.2076 6.73567 17.2663 6.73567 18.3993V19.9533H1.67962C1.46257 18.9627 0.658195 15.7185 0.734802 14.6289V14.6227ZM2.15203 22.1697C1.82645 22.1697 1.53279 21.8849 1.53279 21.5691C1.53279 21.2534 1.82645 20.9686 2.15203 20.9686C2.47761 20.9686 2.77127 21.2534 2.77127 21.5691C2.73296 21.8849 2.47761 22.1697 2.15203 22.1697Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M5.93819 2.94642C6.08502 3.08882 6.30207 3.08882 6.4489 2.94642C6.59573 2.80403 6.59573 2.59353 6.4489 2.45113L5.42748 1.46056C5.28065 1.31816 5.06359 1.31816 4.91676 1.46056C4.76993 1.60295 4.76993 1.81345 4.91676 1.95584L5.93819 2.94642Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M6.80563 1.85092C6.84393 2.02427 7.06098 2.16666 7.23973 2.09856C7.41848 2.06142 7.56531 1.85092 7.49509 1.67757L7.13121 0.265997C7.0929 0.0926462 6.87585 -0.0497491 6.6971 0.018353C6.51835 0.0554996 6.37152 0.265997 6.44175 0.439348L6.80563 1.85092Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M3.64486 3.65165L5.10039 4.00454C5.27914 4.04169 5.50258 3.93644 5.5345 3.7569C5.56642 3.57736 5.46427 3.36686 5.27914 3.3359L3.82361 2.98301C3.64486 2.94587 3.42142 3.05111 3.38951 3.23066C3.31928 3.40401 3.45973 3.58355 3.64486 3.65165Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M17.3205 2.94618C17.4674 3.08857 17.6844 3.08857 17.8312 2.94618L18.8144 1.9556C18.9612 1.8132 18.9612 1.60271 18.8144 1.46031C18.6675 1.31792 18.4505 1.31792 18.3037 1.46031L17.2822 2.45089C17.1737 2.55614 17.1737 2.80378 17.3205 2.94618Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M16.5238 2.09852C16.7025 2.13567 16.926 2.03042 16.9579 1.85088L17.3218 0.439304C17.3601 0.265954 17.2515 0.0492651 17.0664 0.0183096C16.8813 -0.0126459 16.6642 0.0864117 16.6323 0.265954L16.2684 1.67752C16.1982 1.85088 16.3386 2.06756 16.5238 2.09852Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M18.2347 3.75806C18.273 3.93141 18.4901 4.07381 18.6688 4.0057L20.1244 3.65281C20.3031 3.61567 20.4499 3.40517 20.3797 3.23182C20.3414 3.05847 20.1243 2.91607 19.9456 2.98417L18.4901 3.33707C18.3113 3.37421 18.1645 3.54756 18.2347 3.75806Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M16.74 4.07335C16.74 3.75761 16.4846 3.50996 16.159 3.47282C14.0843 3.29947 12.9224 2.48224 12.3415 1.956C12.0861 1.70835 11.6839 1.70835 11.4669 1.956C10.8859 2.48224 9.71768 3.29947 7.64929 3.47282C7.32372 3.50996 7.06836 3.75761 7.06836 4.07335V7.67038C7.06836 9.15006 7.64929 10.5988 8.81755 11.5832C9.61554 12.2889 10.5987 12.7842 11.7286 13.1681C11.8754 13.2052 12.0223 13.2052 12.1308 13.1681C13.2607 12.8152 14.2375 12.2889 15.0419 11.5832C16.1718 10.5926 16.7527 9.15006 16.7527 7.67038V4.07335H16.74ZM11.901 10.0044C10.4837 10.0044 9.31549 8.87765 9.31549 7.49703C9.31549 6.11642 10.4774 4.98964 11.901 4.98964C13.3246 4.98964 14.4865 6.11642 14.4865 7.49703C14.4865 8.87765 13.3246 10.0044 11.901 10.0044Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M12.5913 6.61721C12.553 6.65435 11.538 7.63874 11.5699 7.60778C11.5316 7.57064 11.1358 7.18679 11.1677 7.21774C11.0209 7.07535 10.8038 7.07535 10.657 7.21774C10.5102 7.36014 10.5102 7.57064 10.657 7.71303L11.3145 8.35071C11.4614 8.49311 11.6784 8.49311 11.8253 8.35071L13.0956 7.11869C13.2425 6.97629 13.2425 6.76579 13.0956 6.6234C12.9488 6.481 12.7318 6.481 12.5849 6.6234L12.5913 6.61721Z"
                                                                    fill="currentColor"></path>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_2808_4102">
                                                                    <rect width="23" height="24"
                                                                        fill="currentColor"></rect>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                        <p class="md:text-sm text-xs font-normal">Petrol</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div
                                    class="bg-[#F9F9F9]  border border-[#EFEFEF] text-[#6B6B6B] font-normal text-xs p-4">
                                    Updating your vehicle will refresh service availability and pricing.
                                </div>
                                    <div
                                        class="w-full relative h-[4.563rem] md:p-0 p-6 flex justify-center items-center">
                                        <button type="button" id="confirmVehicleUpdateBtn"
                                            class="h-[3.438rem] md:rounded-none rounded-lg w-full bg-[#1A1A1A] text-base flex justify-center items-center text-white font-bold hover:bg-[#650916] duration-500">
                                            Confirm Vehicle Update
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="mobileCartSummary" class="view h-[4.938rem] group/check w-full fixed bottom-0 inset-x-0 md:hidden flex justify-between items-center gap-2 bg-white border border-[#E5E5E5] shadow-[0_-0.25rem_1rem_0_#00000014] hidden">
    <div class="flex flex-col w-full">
        <div class="text-[#637083] text-xs font-normal">
            <span id="mobileCartItemCount">0</span> <span id="mobileCartItemText">item</span>
        </div>
        <div id="mobileCartTotal" class="text-[#121212] text-lg font-bold">₹0</div>
    </div>
    <div class="w-full flex justify-end">
        <a href="#cartContentSection" class="bg-[#CB122D] w-fit px-8 rounded-2xl h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#650916]">
            View Cart</a>
    </div>
</div>


<div class="md:!hidden hidden cart_modal bg-white size-full group/cartPopup fixed inset-0 z-[100000]  duration-500 flex flex-col items-center">
    <div class=" border-y z-30 border-[#E5E5E5] p-6 md:hidden block relative w-full bg-white ">
        <button type="button"  id="closePopover" class="flex items-center justify-start gap-4 uppercase text-[#121212] text-lg font-medium">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="15" viewBox="0 0 9 15" fill="none">
                    <path d="M6.99902 13.412L1 7.41299L6.99902 1.41397" stroke="#121212" stroke-width="1.99967"
                        stroke-linecap="square" stroke-linejoin="round" />
                </svg>
            </span>
            Your Cart
        </button>
    </div>
    <div class="flex-1 w-full p-6 overflow-y-auto overflow-x-hidden"> 
        <div class="w-full flex flex-col gap-y-6">
            <div class="w-full flex flex-row justify-between bg-white border border-[#EFEFEF] rounded-xl shadow-[0_0.125rem_0.25rem_-0.125rem_rgba(0,0,0,0.10)] p-3">
                <div class="flex flex-row items-center gap-4">
                    <span class="">
                        <svg class="size-10" width="28" height="28" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.6425 10.2383H7.78641C7.51744 10.2379 7.2552 10.3224 7.03692 10.4795C6.81865 10.6367 6.65542 10.8586 6.57043 11.1138L4.73047 16.6382H16.6425V10.2383Z" fill="url(#paint0_linear_4351_1471)"/>
                            <path d="M1.92124 15.3594C1.7515 15.3594 1.58872 15.4268 1.4687 15.5468C1.34868 15.6668 1.28125 15.8296 1.28125 15.9994C1.28125 16.1691 1.34868 16.3319 1.4687 16.4519C1.58872 16.5719 1.7515 16.6393 1.92124 16.6393H3.20121V15.9994C3.20121 15.8296 3.13378 15.6668 3.01376 15.5468C2.89374 15.4268 2.73096 15.3594 2.56122 15.3594H1.92124Z" fill="url(#paint1_linear_4351_1471)"/>
                            <path d="M1.92124 28.7977H16.6409V26.8778H9.60108C9.43134 26.8778 9.26856 26.8104 9.14854 26.6903C9.02852 26.5703 8.96109 26.4075 8.96109 26.2378C8.96109 26.0681 9.02852 25.9053 9.14854 25.7853C9.26856 25.6652 9.43134 25.5978 9.60108 25.5978H16.6409V23.6779H8.96109C8.62162 23.6779 8.29606 23.543 8.05601 23.303C7.81597 23.0629 7.68112 22.7373 7.68112 22.3979C7.68112 21.7189 7.95083 21.0678 8.43091 20.5877C8.91099 20.1076 9.56213 19.8379 10.2411 19.8379H16.6409V17.918H4.01847L1.59164 19.3733C1.49722 19.4308 1.41911 19.5115 1.36477 19.6077C1.31042 19.7039 1.28167 19.8125 1.28125 19.923V28.1578C1.28125 28.3275 1.34868 28.4903 1.4687 28.6103C1.58872 28.7303 1.7515 28.7977 1.92124 28.7977ZM5.12117 26.8778H3.8412C3.67146 26.8778 3.50868 26.8104 3.38866 26.6903C3.26864 26.5703 3.20121 26.4075 3.20121 26.2378C3.20121 26.0681 3.26864 25.9053 3.38866 25.7853C3.50868 25.6652 3.67146 25.5978 3.8412 25.5978H5.12117C5.29091 25.5978 5.45369 25.6652 5.57371 25.7853C5.69373 25.9053 5.76116 26.0681 5.76116 26.2378C5.76116 26.4075 5.69373 26.5703 5.57371 26.6903C5.45369 26.8104 5.29091 26.8778 5.12117 26.8778ZM2.56122 21.1179C2.56122 20.6087 2.7635 20.1203 3.12357 19.7603C3.48363 19.4002 3.97198 19.1979 4.48118 19.1979C4.99039 19.1979 5.47874 19.4002 5.8388 19.7603C6.19886 20.1203 6.40114 20.6087 6.40114 21.1179V22.3979C6.40114 22.9071 6.19886 23.3954 5.8388 23.7555C5.47874 24.1156 4.99039 24.3178 4.48118 24.3178C3.97198 24.3178 3.48363 24.1156 3.12357 23.7555C2.7635 23.3954 2.56122 22.9071 2.56122 22.3979V21.1179Z" fill="url(#paint2_linear_4351_1471)"/>
                            <path d="M4.47983 23.0404C4.64957 23.0404 4.81235 22.973 4.93237 22.853C5.05239 22.7329 5.11982 22.5702 5.11982 22.4004V21.1205C5.11982 20.9507 5.05239 20.7879 4.93237 20.6679C4.81235 20.5479 4.64957 20.4805 4.47983 20.4805C4.3101 20.4805 4.14731 20.5479 4.02729 20.6679C3.90727 20.7879 3.83984 20.9507 3.83984 21.1205V22.4004C3.83984 22.5702 3.90727 22.7329 4.02729 22.853C4.14731 22.973 4.3101 23.0404 4.47983 23.0404Z" fill="url(#paint3_linear_4351_1471)"/>
                            <path d="M30.7202 7.0392H29.933C29.8114 7.03936 29.6924 7.00489 29.5897 6.93983C29.487 6.87478 29.405 6.78182 29.3532 6.67185C29.1569 6.36995 28.8835 6.12609 28.5612 5.9656C28.2388 5.80512 27.8794 5.73386 27.5202 5.75923C27.1625 5.73309 26.8044 5.80359 26.4833 5.96336C26.1622 6.12313 25.8899 6.36628 25.695 6.66737C25.6438 6.77838 25.5618 6.87239 25.4588 6.93829C25.3559 7.00418 25.2362 7.0392 25.1139 7.0392H24.3203V8.31917H30.7202V7.0392Z" fill="url(#paint4_linear_4351_1471)"/>
                            <path d="M3.19858 31.9981H5.75853C5.92826 31.9981 6.09105 31.9307 6.21107 31.8106C6.33109 31.6906 6.39851 31.5278 6.39851 31.3581V30.0781H2.55859V31.3581C2.55859 31.5278 2.62602 31.6906 2.74604 31.8106C2.86606 31.9307 3.02885 31.9981 3.19858 31.9981Z" fill="url(#paint5_linear_4351_1471)"/>
                            <path d="M8.95703 22.3972H16.6369V21.1172H10.237C9.89753 21.1172 9.57197 21.252 9.33193 21.4921C9.09189 21.7321 8.95703 22.0577 8.95703 22.3972Z" fill="url(#paint6_linear_4351_1471)"/>
                            <path d="M35.8415 8.32031H32.0016C32.0016 8.65978 31.8667 8.98535 31.6267 9.22539C31.3866 9.46543 31.0611 9.60029 30.7216 9.60029H24.3217C23.9823 9.60029 23.6567 9.46543 23.4167 9.22539C23.1766 8.98535 23.0418 8.65978 23.0418 8.32031H19.2018C18.8624 8.32031 18.5368 8.45517 18.2968 8.69521C18.0567 8.93525 17.9219 9.26082 17.9219 9.60029V31.3598C17.9219 31.6993 18.0567 32.0249 18.2968 32.2649C18.5368 32.505 18.8624 32.6398 19.2018 32.6398H35.8415C36.181 32.6398 36.5065 32.505 36.7466 32.2649C36.9866 32.0249 37.1215 31.6993 37.1215 31.3598V9.60029C37.1215 9.26082 36.9866 8.93525 36.7466 8.69521C36.5065 8.45517 36.181 8.32031 35.8415 8.32031ZM25.4942 25.3152L22.9343 29.1551C22.8821 29.2343 22.8131 29.3009 22.7321 29.3502C22.651 29.3994 22.5601 29.4301 22.4658 29.4399C22.444 29.4399 22.4229 29.4431 22.4018 29.4431C22.2321 29.443 22.0693 29.3756 21.9493 29.2556L20.6693 27.9756C20.5528 27.8549 20.4883 27.6932 20.4897 27.5254C20.4912 27.3576 20.5585 27.1971 20.6771 27.0784C20.7958 26.9598 20.9563 26.8925 21.1241 26.891C21.2919 26.8896 21.4536 26.9541 21.5743 27.0706L22.3026 27.799L24.4293 24.608C24.4759 24.5381 24.5359 24.478 24.6057 24.4312C24.6756 24.3845 24.7539 24.352 24.8364 24.3355C24.9188 24.319 25.0037 24.319 25.0861 24.3353C25.1686 24.3516 25.247 24.3841 25.3169 24.4307C25.3868 24.4773 25.4469 24.5373 25.4937 24.6072C25.5404 24.677 25.5729 24.7554 25.5894 24.8378C25.6059 24.9202 25.6059 25.0051 25.5896 25.0876C25.5733 25.17 25.5408 25.2484 25.4942 25.3184V25.3152ZM25.4942 18.9153L22.9343 22.7552C22.8817 22.8339 22.8124 22.9 22.7313 22.9487C22.6502 22.9974 22.5593 23.0275 22.4651 23.0368C22.4434 23.0368 22.4223 23.04 22.4011 23.04C22.2314 23.04 22.0687 22.9725 21.9487 22.8525L20.6687 21.5725C20.5521 21.4518 20.4876 21.2902 20.4891 21.1224C20.4905 20.9545 20.5578 20.794 20.6765 20.6754C20.7952 20.5567 20.9557 20.4894 21.1235 20.488C21.2913 20.4865 21.4529 20.551 21.5736 20.6676L22.3019 21.3959L24.4286 18.2049C24.4753 18.135 24.5352 18.0749 24.6051 18.0282C24.6749 17.9814 24.7533 17.9489 24.8357 17.9324C24.9182 17.916 25.003 17.9159 25.0855 17.9322C25.1679 17.9486 25.2464 17.981 25.3163 18.0276C25.3862 18.0743 25.4463 18.1342 25.493 18.2041C25.5398 18.2739 25.5723 18.3523 25.5888 18.4347C25.6052 18.5172 25.6053 18.602 25.589 18.6845C25.5726 18.7669 25.5408 18.8454 25.4942 18.9153ZM25.4942 12.5154L22.9343 16.3553C22.8821 16.4346 22.8131 16.5012 22.7321 16.5504C22.651 16.5997 22.5601 16.6303 22.4658 16.6401C22.444 16.6401 22.4229 16.6401 22.4018 16.6401C22.2321 16.6401 22.0693 16.5727 21.9493 16.4526L20.6693 15.1727C20.5528 15.0519 20.4883 14.8903 20.4897 14.7225C20.4912 14.5547 20.5585 14.3942 20.6771 14.2755C20.7958 14.1568 20.9563 14.0895 21.1241 14.0881C21.2919 14.0866 21.4536 14.1511 21.5743 14.2677L22.3026 14.996L24.4293 11.805C24.4759 11.7351 24.5359 11.6751 24.6057 11.6283C24.6756 11.5816 24.7539 11.549 24.8364 11.5326C24.9188 11.5161 25.0037 11.516 25.0861 11.5324C25.1686 11.5487 25.247 11.5811 25.3169 11.6278C25.3868 11.6744 25.4469 11.7344 25.4937 11.8042C25.5404 11.8741 25.5729 11.9525 25.5894 12.0349C25.6059 12.1173 25.6059 12.2022 25.5896 12.2846C25.5733 12.3671 25.5408 12.4455 25.4942 12.5154ZM31.2451 29.4399H27.5217C27.3519 29.4399 27.1892 29.3724 27.0691 29.2524C26.9491 29.1324 26.8817 28.9696 26.8817 28.7999C26.8817 28.6302 26.9491 28.4674 27.0691 28.3474C27.1892 28.2273 27.3519 28.1599 27.5217 28.1599H31.2451C31.4149 28.1599 31.5776 28.2273 31.6977 28.3474C31.8177 28.4674 31.8851 28.6302 31.8851 28.7999C31.8851 28.9696 31.8177 29.1324 31.6977 29.2524C31.5776 29.3724 31.4149 29.4399 31.2451 29.4399ZM33.2816 26.8799H27.5217C27.3519 26.8799 27.1892 26.8125 27.0691 26.6925C26.9491 26.5725 26.8817 26.4097 26.8817 26.2399C26.8817 26.0702 26.9491 25.9074 27.0691 25.7874C27.1892 25.6674 27.3519 25.6 27.5217 25.6H33.2816C33.4513 25.6 33.6141 25.6674 33.7341 25.7874C33.8541 25.9074 33.9215 26.0702 33.9215 26.2399C33.9215 26.4097 33.8541 26.5725 33.7341 26.6925C33.6141 26.8125 33.4513 26.8799 33.2816 26.8799ZM26.8817 22.4C26.8817 22.2303 26.9491 22.0675 27.0691 21.9475C27.1892 21.8275 27.3519 21.76 27.5217 21.76H31.2451C31.4149 21.76 31.5776 21.8275 31.6977 21.9475C31.8177 22.0675 31.8851 22.2303 31.8851 22.4C31.8851 22.5698 31.8177 22.7325 31.6977 22.8526C31.5776 22.9726 31.4149 23.04 31.2451 23.04H27.5217C27.3519 23.04 27.1892 22.9726 27.0691 22.8526C26.9491 22.7325 26.8817 22.5698 26.8817 22.4ZM33.2816 20.4801H27.5217C27.3519 20.4801 27.1892 20.4126 27.0691 20.2926C26.9491 20.1726 26.8817 20.0098 26.8817 19.8401C26.8817 19.6703 26.9491 19.5076 27.0691 19.3875C27.1892 19.2675 27.3519 19.2001 27.5217 19.2001H33.2816C33.4513 19.2001 33.6141 19.2675 33.7341 19.3875C33.8541 19.5076 33.9215 19.6703 33.9215 19.8401C33.9215 20.0098 33.8541 20.1726 33.7341 20.2926C33.6141 20.4126 33.4513 20.4801 33.2816 20.4801ZM26.8817 16.0002C26.8817 15.8304 26.9491 15.6676 27.0691 15.5476C27.1892 15.4276 27.3519 15.3602 27.5217 15.3602H31.2451C31.4149 15.3602 31.5776 15.4276 31.6977 15.5476C31.8177 15.6676 31.8851 15.8304 31.8851 16.0002C31.8851 16.1699 31.8177 16.3327 31.6977 16.4527C31.5776 16.5727 31.4149 16.6401 31.2451 16.6401H27.5217C27.3519 16.6401 27.1892 16.5727 27.0691 16.4527C26.9491 16.3327 26.8817 16.1699 26.8817 16.0002ZM33.2816 14.0802H27.5217C27.3519 14.0802 27.1892 14.0128 27.0691 13.8927C26.9491 13.7727 26.8817 13.6099 26.8817 13.4402C26.8817 13.2705 26.9491 13.1077 27.0691 12.9877C27.1892 12.8676 27.3519 12.8002 27.5217 12.8002H33.2816C33.4513 12.8002 33.6141 12.8676 33.7341 12.9877C33.8541 13.1077 33.9215 13.2705 33.9215 13.4402C33.9215 13.6099 33.8541 13.7727 33.7341 13.8927C33.6141 14.0128 33.4513 14.0802 33.2816 14.0802Z" fill="url(#paint7_linear_4351_1471)"/>
                            <defs>
                            <linearGradient id="paint0_linear_4351_1471" x1="16.6425" y1="10.6796" x2="4.4846" y2="11.2327" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#CB122D"/>
                            <stop offset="1" stop-color="#650916"/>
                            </linearGradient>
                            <linearGradient id="paint1_linear_4351_1471" x1="3.20121" y1="15.4476" x2="1.2402" y2="15.5195" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#CB122D"/>
                            <stop offset="1" stop-color="#650916"/>
                            </linearGradient>
                            <linearGradient id="paint2_linear_4351_1471" x1="16.6409" y1="18.6683" x2="0.95046" y2="19.2096" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#CB122D"/>
                            <stop offset="1" stop-color="#650916"/>
                            </linearGradient>
                            <linearGradient id="paint3_linear_4351_1471" x1="5.11982" y1="20.657" x2="3.81092" y2="20.673" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#CB122D"/>
                            <stop offset="1" stop-color="#650916"/>
                            </linearGradient>
                            <linearGradient id="paint4_linear_4351_1471" x1="30.7202" y1="5.93082" x2="24.1989" y2="6.3284" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#CB122D"/>
                            <stop offset="1" stop-color="#650916"/>
                            </linearGradient>
                            <linearGradient id="paint5_linear_4351_1471" x1="6.39851" y1="30.2105" x2="2.48059" y2="30.402" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#CB122D"/>
                            <stop offset="1" stop-color="#650916"/>
                            </linearGradient>
                            <linearGradient id="paint6_linear_4351_1471" x1="16.6369" y1="21.2055" x2="8.94761" y2="22.3329" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#CB122D"/>
                            <stop offset="1" stop-color="#650916"/>
                            </linearGradient>
                            <linearGradient id="paint7_linear_4351_1471" x1="37.1215" y1="9.9975" x2="17.4923" y2="10.3762" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#CB122D"/>
                            <stop offset="1" stop-color="#650916"/>
                            </linearGradient>
                            </defs>
                            </svg>                      
                    </span>
                    <div class="flex flex-col gap-y-1">
                        <div class="text-[#A6A6A6] uppercase text-xs font-semibold">Your Vehicle
                        </div>
                        <div class="text-[#1A1A1A] lg:text-lg md:text-md text-base font-semibold">
                            Hyundai i20</div>
                        <div class="text-[#6F6F6F] text-sm font-nromal">Diesel</div>
                    </div>
                </div>
                <div>
                    <button type="button" id="carChangePopupBtn" class="bg-transparent p-0 m-0  border-0 text-[#CB122D] font-semibold underline md:text-sm text-xs hover:text-[#650916] duration-500">Click
                        to change
                    </a>
                </div>
            </div>
            <div class="w-full flex flex-col gap-y-3 bg-white border border-[#EFEFEF] rounded-xl shadow-[0_0.125rem_0.25rem_-0.125rem_rgba(0,0,0,0.10)]">
                <div class="p-3 flex flex-col gap-y-3">
                <div class="flex justify-between">
                    <div class="flex gap-2">
                        <div>
                            <img fetchpriority="low" loading="lazy" src="img/premium-car-service.webp"
                            class="size-20 object-cover  rounded-lg aspect-square"
                            width="" height="" alt="" title="">
                        </div>
                        <div class="flex flex-col gap-2 justify-between">
                            <div class="text-[#1A1A1A] lg:text-lg md:text-md text-base font-semibold">
                                Premium Car Service</div>
                            <div class="flex items-center gap-3">
                                <!-- <div class="text-[#637083] font-nromal text-base line-through">
                                    ₹4,999
                                </div> -->
                                <div class="text-[#121212]  text-md font-bold">
                                    ₹2,999</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="" class="bg-transparent p-0 m-0  border-0 text-[#CB122D]s hover:text-[#650916] duration-500">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <g clip-path="url(#clip0_4145_2767)">
                                    <path d="M16.875 4.375H3.125" stroke="#E44C62" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8.125 8.125V13.125" stroke="#E44C62" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M11.875 8.125V13.125" stroke="#E44C62" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M15.625 4.375V16.25C15.625 16.4158 15.5592 16.5747 15.4419 16.6919C15.3247 16.8092 15.1658 16.875 15 16.875H5C4.83424 16.875 4.67527 16.8092 4.55806 16.6919C4.44085 16.5747 4.375 16.4158 4.375 16.25V4.375" stroke="#E44C62" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M13.125 4.375V3.125C13.125 2.79348 12.9933 2.47554 12.7589 2.24112C12.5245 2.0067 12.2065 1.875 11.875 1.875H8.125C7.79348 1.875 7.47554 2.0067 7.24112 2.24112C7.0067 2.47554 6.875 2.79348 6.875 3.125V4.375" stroke="#E44C62" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_4145_2767">
                                        <rect width="20" height="20" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="border-t border-[#F0F0F0] px-3 pt-3">
                    <a href="" class="flex items-center gap-2 text-xs font-medium text-[#000000A3] text-center justify-center">
                        View Details
                        <span><svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.666016 0.666443L4.66464 4.66506L8.66326 0.666443" stroke="black" stroke-opacity="0.64" stroke-width="1.33287" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            </span>
                        </a>
                </div>
                </div>
                <!-- view details part -->
                <div class="w-full border-t border-[#F0F0F0] p-3 hidden">
                    <h2 class="text-[#121212] text-sm font-semibold text-start pb-2">What's included:</h2>
                    <ul class="flex flex-col gap-y-3">
                        <li class="w-full flex items-center gap-2 font-normal text-sm text-[#121212]">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M9.64565 0.485347C9.79623 0.485347 9.93844 0.577369 9.98027 0.727948L10.4571 2.13335C10.4989 2.25884 10.616 2.35086 10.7499 2.36759C10.9758 2.40105 11.1933 2.43451 11.4108 2.4847C11.4359 2.4847 11.4693 2.49307 11.4944 2.49307C11.6032 2.49307 11.7036 2.45124 11.7705 2.36759L12.7074 1.22152C12.7743 1.13786 12.8747 1.08766 12.9835 1.08766C13.0253 1.08766 13.0671 1.08766 13.109 1.11276C13.5189 1.27171 13.9121 1.46412 14.2969 1.67325C14.4307 1.74854 14.4976 1.89912 14.4642 2.0497L14.1714 3.5053C14.1463 3.63914 14.1965 3.77299 14.3052 3.85665C14.4809 3.99049 14.6482 4.14108 14.8155 4.29166C14.8825 4.35858 14.9745 4.39205 15.0581 4.39205C15.1 4.39205 15.1418 4.39204 15.1836 4.36694L16.5723 3.84829C16.5723 3.84829 16.6559 3.82319 16.6978 3.82319C16.7982 3.82319 16.8985 3.86501 16.9655 3.94867C17.2415 4.29165 17.4841 4.65137 17.71 5.02782C17.7853 5.16167 17.7686 5.32062 17.6682 5.43773L16.6894 6.55034C16.5974 6.65073 16.5807 6.79294 16.6308 6.91843C16.7145 7.12756 16.7898 7.3367 16.8567 7.55421C16.8985 7.68806 17.0073 7.78007 17.1411 7.80517L18.5967 8.04777C18.7473 8.07287 18.8644 8.18998 18.8895 8.34056C18.8895 8.37402 18.8979 8.40749 18.9063 8.43258C18.9648 8.83413 18.9899 9.23567 18.9983 9.62885C18.9983 9.77943 18.9063 9.92165 18.7557 9.97184L17.3503 10.4487C17.2248 10.4905 17.1328 10.6076 17.116 10.7415C17.0826 10.9673 17.0491 11.1848 16.9989 11.4023C16.9655 11.5362 17.0157 11.67 17.116 11.7621L18.2621 12.699C18.3792 12.791 18.4211 12.9583 18.3709 13.0922C18.2119 13.5021 18.0195 13.8953 17.8104 14.2801C17.7518 14.3888 17.6263 14.4558 17.5092 14.4558C17.4841 14.4558 17.459 14.4558 17.4423 14.4558L15.9867 14.163C15.9867 14.163 15.9449 14.163 15.9198 14.163C15.811 14.163 15.7023 14.2132 15.6354 14.3052C15.5015 14.4808 15.3509 14.6482 15.2003 14.8155C15.1083 14.9159 15.0749 15.0581 15.1251 15.1835L15.6437 16.5722C15.6939 16.7144 15.6521 16.8734 15.535 16.9654C15.192 17.2415 14.8323 17.4841 14.4558 17.7099C14.4056 17.7434 14.3387 17.7601 14.2801 17.7601C14.1965 17.7601 14.1128 17.7267 14.0459 17.6681L12.9333 16.6893C12.8664 16.6308 12.7827 16.6057 12.6991 16.6057C12.6489 16.6057 12.607 16.6141 12.5568 16.6308C12.3477 16.7144 12.1386 16.7897 11.9211 16.8566C11.7872 16.8985 11.6952 17.0072 11.6701 17.1411L11.4275 18.5967C11.4024 18.7473 11.2853 18.8644 11.1347 18.8895C11.1012 18.8895 11.0678 18.8978 11.0427 18.9062C10.6411 18.9648 10.2396 18.9899 9.84642 18.9982C9.69584 18.9982 9.55363 18.9062 9.5118 18.7556L9.03497 17.3502C8.99314 17.2247 8.87602 17.1327 8.74217 17.116C8.5163 17.0825 8.2988 17.0491 8.0813 16.9989C8.0562 16.9989 8.02274 16.9905 7.99764 16.9905C7.88889 16.9905 7.78851 17.0323 7.72158 17.116L6.78465 18.2621C6.71772 18.3457 6.61733 18.3959 6.50858 18.3959C6.46676 18.3959 6.42493 18.3959 6.3831 18.3708C5.97319 18.2119 5.58001 18.0195 5.1952 17.8103C5.06135 17.735 4.99443 17.5844 5.02789 17.4339L5.32068 15.9783C5.34578 15.8444 5.29558 15.7106 5.18683 15.6269C5.01116 15.4931 4.84385 15.3425 4.67654 15.1919C4.60961 15.125 4.51759 15.0915 4.43394 15.0915C4.39211 15.0915 4.35028 15.0915 4.30845 15.1166L2.91978 15.6353C2.91978 15.6353 2.83612 15.6604 2.7943 15.6604C2.69391 15.6604 2.59352 15.6186 2.5266 15.5349C2.25054 15.1919 2.00794 14.8322 1.78207 14.4558C1.70678 14.3219 1.72351 14.163 1.8239 14.0458L2.80266 12.9332C2.89468 12.8328 2.91141 12.6906 2.86122 12.5651C2.77757 12.356 2.70228 12.1469 2.63535 11.9294C2.59352 11.7955 2.48477 11.7035 2.35092 11.6784L0.895326 11.4358C0.744747 11.4107 0.62763 11.2936 0.602534 11.143C0.602534 11.1095 0.594168 11.0761 0.585803 11.051C0.527244 10.6494 0.502148 10.2479 0.493782 9.85472C0.493782 9.70414 0.585803 9.56193 0.736382 9.51173L2.14179 9.0349C2.26727 8.99307 2.35929 8.87596 2.37602 8.74211C2.40948 8.51624 2.44295 8.29873 2.49314 8.08123C2.5266 7.94738 2.47641 7.81353 2.37602 7.72151L1.22995 6.78457C1.11283 6.69255 1.071 6.52525 1.1212 6.3914C1.28014 5.98149 1.47255 5.58831 1.68168 5.20349C1.74024 5.09474 1.86573 5.02782 1.98284 5.02782C2.00794 5.02782 2.03304 5.02782 2.04977 5.02782L3.50537 5.32061C3.50537 5.32061 3.54719 5.32061 3.57229 5.32061C3.68104 5.32061 3.78979 5.27042 3.85672 5.1784C3.99056 5.00272 4.14114 4.83541 4.29172 4.6681C4.38374 4.56772 4.41721 4.42551 4.36701 4.30002L3.84835 2.91135C3.79816 2.76913 3.83999 2.61019 3.9571 2.51817C4.30009 2.2421 4.65981 1.99951 5.03625 1.77364C5.08645 1.74017 5.15337 1.72345 5.21193 1.72345C5.29558 1.72345 5.37924 1.75691 5.44616 1.81547L6.55878 2.79423C6.6257 2.85279 6.70935 2.87788 6.79301 2.87788C6.8432 2.87788 6.88503 2.86952 6.93522 2.85279C7.14436 2.76913 7.3535 2.69385 7.571 2.62692C7.70485 2.58509 7.79687 2.47634 7.82197 2.34249L8.06457 0.886895C8.08966 0.736315 8.20678 0.6192 8.35736 0.594103C8.39082 0.594103 8.42428 0.585734 8.44938 0.577368C8.85093 0.51881 9.25247 0.493713 9.64565 0.485347ZM9.64565 0.000152588H9.63728C9.21901 0.000152588 8.79237 0.041976 8.37409 0.100535L8.27371 0.117269C7.91399 0.175828 7.62956 0.451885 7.571 0.811602L7.34513 2.19191C7.16946 2.25047 6.98542 2.30903 6.81811 2.38432L5.76405 1.46412C5.61347 1.33027 5.4127 1.25498 5.21193 1.25498C5.06135 1.25498 4.91914 1.29681 4.79365 1.3721C4.40047 1.60633 4.01566 1.86566 3.65594 2.15845C3.37152 2.38432 3.27113 2.76077 3.39661 3.10376L3.89018 4.41714C3.7647 4.55935 3.63921 4.70156 3.5221 4.84378L2.15015 4.56772C2.09159 4.55935 2.0414 4.55099 1.98284 4.55099C1.68168 4.55099 1.40562 4.70993 1.25504 4.96926C1.02917 5.37081 0.828402 5.78909 0.661092 6.21573C0.527244 6.55035 0.62763 6.93516 0.912058 7.16103L1.99121 8.04777C1.94938 8.23181 1.91592 8.41585 1.89082 8.5999L0.569072 9.05163C0.226086 9.16875 -0.00814847 9.495 0.00021704 9.86308C0.00021704 10.2814 0.0420446 10.708 0.100603 11.1263L0.117334 11.2267C0.175893 11.5864 0.451955 11.8708 0.811671 11.9294L2.19198 12.1552C2.25054 12.3309 2.3091 12.515 2.38439 12.6823L1.46418 13.7363C1.22158 14.0124 1.18812 14.4056 1.37216 14.7151C1.60639 15.1083 1.86573 15.4931 2.15852 15.8528C2.31746 16.0536 2.5517 16.1623 2.81103 16.1623C2.91141 16.1623 3.0118 16.1456 3.10382 16.1121L4.41721 15.6186C4.55942 15.744 4.70163 15.8695 4.84385 15.9783L4.56778 17.3502C4.50086 17.7099 4.65981 18.0613 4.97769 18.2453C5.37924 18.4712 5.79751 18.672 6.22416 18.8393C6.31618 18.8727 6.42493 18.8978 6.52531 18.8978C6.77628 18.8978 7.01051 18.7891 7.16946 18.5883L8.0562 17.5092C8.22351 17.551 8.41592 17.5844 8.60833 17.6095L9.06006 18.9313C9.17718 19.2743 9.49507 19.5002 9.85479 19.5002C10.2814 19.5002 10.7081 19.4583 11.1263 19.3998L11.2267 19.383C11.5864 19.3245 11.8625 19.0484 11.9294 18.6887L12.1553 17.3084C12.331 17.2498 12.515 17.1913 12.6823 17.116L13.7364 18.0362C13.887 18.17 14.0877 18.2453 14.2885 18.2453C14.4391 18.2453 14.5813 18.2035 14.7068 18.1282C15.1 17.894 15.4848 17.6263 15.8445 17.3419C16.1289 17.116 16.2293 16.7395 16.1038 16.3965L15.6103 15.0832C15.7357 14.941 15.8612 14.7987 15.9783 14.6565L17.3503 14.9326C17.4088 14.941 17.459 14.9493 17.5176 14.9493C17.8188 14.9493 18.0948 14.7904 18.2454 14.531C18.4713 14.1295 18.672 13.7112 18.8393 13.2846C18.9732 12.95 18.8728 12.5651 18.5884 12.3393L17.5092 11.4525C17.5511 11.2685 17.5845 11.0845 17.6096 10.9004L18.9314 10.4487C19.2744 10.3316 19.5086 10.0053 19.5002 9.63722C19.5002 9.21894 19.4584 8.7923 19.3998 8.37402L19.3831 8.27364C19.3245 7.91392 19.0485 7.62949 18.6888 7.57093L17.3085 7.34507C17.2499 7.16939 17.1913 6.98535 17.116 6.81804L18.0363 5.76399C18.2789 5.48793 18.3123 5.09474 18.1283 4.78522C17.894 4.39204 17.6347 4.00723 17.3419 3.64751C17.183 3.44674 16.9487 3.33799 16.6894 3.33799C16.589 3.33799 16.4886 3.35472 16.3966 3.38818L15.0832 3.88175C14.941 3.75627 14.7988 3.63078 14.6566 3.52203L14.9327 2.15009C14.9996 1.79037 14.8406 1.43902 14.5227 1.25498C14.1212 1.02911 13.7029 0.828332 13.2763 0.661022C13.1843 0.62756 13.0755 0.602464 12.9751 0.602464C12.7242 0.602464 12.4899 0.711218 12.331 0.91199L11.4442 1.99114C11.2769 1.94931 11.0845 1.91585 10.8921 1.89075L10.4404 0.569008C10.3233 0.226022 10.0054 0.000152588 9.64565 0.000152588Z" fill="url(#paint0_linear_4118_862)"></path>
                                    <path d="M10.6259 13.06C10.0403 13.6456 9.09499 13.6456 8.50941 13.06L4.90387 9.45447C4.66127 9.21187 4.66127 8.8187 4.90387 8.5761L6.14197 7.338C6.38457 7.0954 6.77775 7.0954 7.02035 7.338L9.57183 9.88948L16.7076 2.7537C16.9502 2.5111 17.3434 2.5111 17.586 2.7537L18.8241 3.99179C19.0667 4.23439 19.0667 4.62758 18.8241 4.87018L10.6259 13.0684V13.06Z" fill="black"></path>
                                    <defs>
                                        <linearGradient id="paint0_linear_4118_862" x1="19.5004" y1="1.34497" x2="-0.431773" y2="1.83207" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D"></stop>
                                            <stop offset="1" stop-color="#650916"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                            Full Body Respray
                        </li>
                        <li class="w-full flex items-center gap-2 font-normal text-sm text-[#121212]">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M9.64565 0.485347C9.79623 0.485347 9.93844 0.577369 9.98027 0.727948L10.4571 2.13335C10.4989 2.25884 10.616 2.35086 10.7499 2.36759C10.9758 2.40105 11.1933 2.43451 11.4108 2.4847C11.4359 2.4847 11.4693 2.49307 11.4944 2.49307C11.6032 2.49307 11.7036 2.45124 11.7705 2.36759L12.7074 1.22152C12.7743 1.13786 12.8747 1.08766 12.9835 1.08766C13.0253 1.08766 13.0671 1.08766 13.109 1.11276C13.5189 1.27171 13.9121 1.46412 14.2969 1.67325C14.4307 1.74854 14.4976 1.89912 14.4642 2.0497L14.1714 3.5053C14.1463 3.63914 14.1965 3.77299 14.3052 3.85665C14.4809 3.99049 14.6482 4.14108 14.8155 4.29166C14.8825 4.35858 14.9745 4.39205 15.0581 4.39205C15.1 4.39205 15.1418 4.39204 15.1836 4.36694L16.5723 3.84829C16.5723 3.84829 16.6559 3.82319 16.6978 3.82319C16.7982 3.82319 16.8985 3.86501 16.9655 3.94867C17.2415 4.29165 17.4841 4.65137 17.71 5.02782C17.7853 5.16167 17.7686 5.32062 17.6682 5.43773L16.6894 6.55034C16.5974 6.65073 16.5807 6.79294 16.6308 6.91843C16.7145 7.12756 16.7898 7.3367 16.8567 7.55421C16.8985 7.68806 17.0073 7.78007 17.1411 7.80517L18.5967 8.04777C18.7473 8.07287 18.8644 8.18998 18.8895 8.34056C18.8895 8.37402 18.8979 8.40749 18.9063 8.43258C18.9648 8.83413 18.9899 9.23567 18.9983 9.62885C18.9983 9.77943 18.9063 9.92165 18.7557 9.97184L17.3503 10.4487C17.2248 10.4905 17.1328 10.6076 17.116 10.7415C17.0826 10.9673 17.0491 11.1848 16.9989 11.4023C16.9655 11.5362 17.0157 11.67 17.116 11.7621L18.2621 12.699C18.3792 12.791 18.4211 12.9583 18.3709 13.0922C18.2119 13.5021 18.0195 13.8953 17.8104 14.2801C17.7518 14.3888 17.6263 14.4558 17.5092 14.4558C17.4841 14.4558 17.459 14.4558 17.4423 14.4558L15.9867 14.163C15.9867 14.163 15.9449 14.163 15.9198 14.163C15.811 14.163 15.7023 14.2132 15.6354 14.3052C15.5015 14.4808 15.3509 14.6482 15.2003 14.8155C15.1083 14.9159 15.0749 15.0581 15.1251 15.1835L15.6437 16.5722C15.6939 16.7144 15.6521 16.8734 15.535 16.9654C15.192 17.2415 14.8323 17.4841 14.4558 17.7099C14.4056 17.7434 14.3387 17.7601 14.2801 17.7601C14.1965 17.7601 14.1128 17.7267 14.0459 17.6681L12.9333 16.6893C12.8664 16.6308 12.7827 16.6057 12.6991 16.6057C12.6489 16.6057 12.607 16.6141 12.5568 16.6308C12.3477 16.7144 12.1386 16.7897 11.9211 16.8566C11.7872 16.8985 11.6952 17.0072 11.6701 17.1411L11.4275 18.5967C11.4024 18.7473 11.2853 18.8644 11.1347 18.8895C11.1012 18.8895 11.0678 18.8978 11.0427 18.9062C10.6411 18.9648 10.2396 18.9899 9.84642 18.9982C9.69584 18.9982 9.55363 18.9062 9.5118 18.7556L9.03497 17.3502C8.99314 17.2247 8.87602 17.1327 8.74217 17.116C8.5163 17.0825 8.2988 17.0491 8.0813 16.9989C8.0562 16.9989 8.02274 16.9905 7.99764 16.9905C7.88889 16.9905 7.78851 17.0323 7.72158 17.116L6.78465 18.2621C6.71772 18.3457 6.61733 18.3959 6.50858 18.3959C6.46676 18.3959 6.42493 18.3959 6.3831 18.3708C5.97319 18.2119 5.58001 18.0195 5.1952 17.8103C5.06135 17.735 4.99443 17.5844 5.02789 17.4339L5.32068 15.9783C5.34578 15.8444 5.29558 15.7106 5.18683 15.6269C5.01116 15.4931 4.84385 15.3425 4.67654 15.1919C4.60961 15.125 4.51759 15.0915 4.43394 15.0915C4.39211 15.0915 4.35028 15.0915 4.30845 15.1166L2.91978 15.6353C2.91978 15.6353 2.83612 15.6604 2.7943 15.6604C2.69391 15.6604 2.59352 15.6186 2.5266 15.5349C2.25054 15.1919 2.00794 14.8322 1.78207 14.4558C1.70678 14.3219 1.72351 14.163 1.8239 14.0458L2.80266 12.9332C2.89468 12.8328 2.91141 12.6906 2.86122 12.5651C2.77757 12.356 2.70228 12.1469 2.63535 11.9294C2.59352 11.7955 2.48477 11.7035 2.35092 11.6784L0.895326 11.4358C0.744747 11.4107 0.62763 11.2936 0.602534 11.143C0.602534 11.1095 0.594168 11.0761 0.585803 11.051C0.527244 10.6494 0.502148 10.2479 0.493782 9.85472C0.493782 9.70414 0.585803 9.56193 0.736382 9.51173L2.14179 9.0349C2.26727 8.99307 2.35929 8.87596 2.37602 8.74211C2.40948 8.51624 2.44295 8.29873 2.49314 8.08123C2.5266 7.94738 2.47641 7.81353 2.37602 7.72151L1.22995 6.78457C1.11283 6.69255 1.071 6.52525 1.1212 6.3914C1.28014 5.98149 1.47255 5.58831 1.68168 5.20349C1.74024 5.09474 1.86573 5.02782 1.98284 5.02782C2.00794 5.02782 2.03304 5.02782 2.04977 5.02782L3.50537 5.32061C3.50537 5.32061 3.54719 5.32061 3.57229 5.32061C3.68104 5.32061 3.78979 5.27042 3.85672 5.1784C3.99056 5.00272 4.14114 4.83541 4.29172 4.6681C4.38374 4.56772 4.41721 4.42551 4.36701 4.30002L3.84835 2.91135C3.79816 2.76913 3.83999 2.61019 3.9571 2.51817C4.30009 2.2421 4.65981 1.99951 5.03625 1.77364C5.08645 1.74017 5.15337 1.72345 5.21193 1.72345C5.29558 1.72345 5.37924 1.75691 5.44616 1.81547L6.55878 2.79423C6.6257 2.85279 6.70935 2.87788 6.79301 2.87788C6.8432 2.87788 6.88503 2.86952 6.93522 2.85279C7.14436 2.76913 7.3535 2.69385 7.571 2.62692C7.70485 2.58509 7.79687 2.47634 7.82197 2.34249L8.06457 0.886895C8.08966 0.736315 8.20678 0.6192 8.35736 0.594103C8.39082 0.594103 8.42428 0.585734 8.44938 0.577368C8.85093 0.51881 9.25247 0.493713 9.64565 0.485347ZM9.64565 0.000152588H9.63728C9.21901 0.000152588 8.79237 0.041976 8.37409 0.100535L8.27371 0.117269C7.91399 0.175828 7.62956 0.451885 7.571 0.811602L7.34513 2.19191C7.16946 2.25047 6.98542 2.30903 6.81811 2.38432L5.76405 1.46412C5.61347 1.33027 5.4127 1.25498 5.21193 1.25498C5.06135 1.25498 4.91914 1.29681 4.79365 1.3721C4.40047 1.60633 4.01566 1.86566 3.65594 2.15845C3.37152 2.38432 3.27113 2.76077 3.39661 3.10376L3.89018 4.41714C3.7647 4.55935 3.63921 4.70156 3.5221 4.84378L2.15015 4.56772C2.09159 4.55935 2.0414 4.55099 1.98284 4.55099C1.68168 4.55099 1.40562 4.70993 1.25504 4.96926C1.02917 5.37081 0.828402 5.78909 0.661092 6.21573C0.527244 6.55035 0.62763 6.93516 0.912058 7.16103L1.99121 8.04777C1.94938 8.23181 1.91592 8.41585 1.89082 8.5999L0.569072 9.05163C0.226086 9.16875 -0.00814847 9.495 0.00021704 9.86308C0.00021704 10.2814 0.0420446 10.708 0.100603 11.1263L0.117334 11.2267C0.175893 11.5864 0.451955 11.8708 0.811671 11.9294L2.19198 12.1552C2.25054 12.3309 2.3091 12.515 2.38439 12.6823L1.46418 13.7363C1.22158 14.0124 1.18812 14.4056 1.37216 14.7151C1.60639 15.1083 1.86573 15.4931 2.15852 15.8528C2.31746 16.0536 2.5517 16.1623 2.81103 16.1623C2.91141 16.1623 3.0118 16.1456 3.10382 16.1121L4.41721 15.6186C4.55942 15.744 4.70163 15.8695 4.84385 15.9783L4.56778 17.3502C4.50086 17.7099 4.65981 18.0613 4.97769 18.2453C5.37924 18.4712 5.79751 18.672 6.22416 18.8393C6.31618 18.8727 6.42493 18.8978 6.52531 18.8978C6.77628 18.8978 7.01051 18.7891 7.16946 18.5883L8.0562 17.5092C8.22351 17.551 8.41592 17.5844 8.60833 17.6095L9.06006 18.9313C9.17718 19.2743 9.49507 19.5002 9.85479 19.5002C10.2814 19.5002 10.7081 19.4583 11.1263 19.3998L11.2267 19.383C11.5864 19.3245 11.8625 19.0484 11.9294 18.6887L12.1553 17.3084C12.331 17.2498 12.515 17.1913 12.6823 17.116L13.7364 18.0362C13.887 18.17 14.0877 18.2453 14.2885 18.2453C14.4391 18.2453 14.5813 18.2035 14.7068 18.1282C15.1 17.894 15.4848 17.6263 15.8445 17.3419C16.1289 17.116 16.2293 16.7395 16.1038 16.3965L15.6103 15.0832C15.7357 14.941 15.8612 14.7987 15.9783 14.6565L17.3503 14.9326C17.4088 14.941 17.459 14.9493 17.5176 14.9493C17.8188 14.9493 18.0948 14.7904 18.2454 14.531C18.4713 14.1295 18.672 13.7112 18.8393 13.2846C18.9732 12.95 18.8728 12.5651 18.5884 12.3393L17.5092 11.4525C17.5511 11.2685 17.5845 11.0845 17.6096 10.9004L18.9314 10.4487C19.2744 10.3316 19.5086 10.0053 19.5002 9.63722C19.5002 9.21894 19.4584 8.7923 19.3998 8.37402L19.3831 8.27364C19.3245 7.91392 19.0485 7.62949 18.6888 7.57093L17.3085 7.34507C17.2499 7.16939 17.1913 6.98535 17.116 6.81804L18.0363 5.76399C18.2789 5.48793 18.3123 5.09474 18.1283 4.78522C17.894 4.39204 17.6347 4.00723 17.3419 3.64751C17.183 3.44674 16.9487 3.33799 16.6894 3.33799C16.589 3.33799 16.4886 3.35472 16.3966 3.38818L15.0832 3.88175C14.941 3.75627 14.7988 3.63078 14.6566 3.52203L14.9327 2.15009C14.9996 1.79037 14.8406 1.43902 14.5227 1.25498C14.1212 1.02911 13.7029 0.828332 13.2763 0.661022C13.1843 0.62756 13.0755 0.602464 12.9751 0.602464C12.7242 0.602464 12.4899 0.711218 12.331 0.91199L11.4442 1.99114C11.2769 1.94931 11.0845 1.91585 10.8921 1.89075L10.4404 0.569008C10.3233 0.226022 10.0054 0.000152588 9.64565 0.000152588Z" fill="url(#paint0_linear_4118_862)"></path>
                                    <path d="M10.6259 13.06C10.0403 13.6456 9.09499 13.6456 8.50941 13.06L4.90387 9.45447C4.66127 9.21187 4.66127 8.8187 4.90387 8.5761L6.14197 7.338C6.38457 7.0954 6.77775 7.0954 7.02035 7.338L9.57183 9.88948L16.7076 2.7537C16.9502 2.5111 17.3434 2.5111 17.586 2.7537L18.8241 3.99179C19.0667 4.23439 19.0667 4.62758 18.8241 4.87018L10.6259 13.0684V13.06Z" fill="black"></path>
                                    <defs>
                                        <linearGradient id="paint0_linear_4118_862" x1="19.5004" y1="1.34497" x2="-0.431773" y2="1.83207" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D"></stop>
                                            <stop offset="1" stop-color="#650916"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                            Full Body Respray
                        </li>
                        <li class="w-full flex items-center gap-2 font-normal text-sm text-[#121212]">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M9.64565 0.485347C9.79623 0.485347 9.93844 0.577369 9.98027 0.727948L10.4571 2.13335C10.4989 2.25884 10.616 2.35086 10.7499 2.36759C10.9758 2.40105 11.1933 2.43451 11.4108 2.4847C11.4359 2.4847 11.4693 2.49307 11.4944 2.49307C11.6032 2.49307 11.7036 2.45124 11.7705 2.36759L12.7074 1.22152C12.7743 1.13786 12.8747 1.08766 12.9835 1.08766C13.0253 1.08766 13.0671 1.08766 13.109 1.11276C13.5189 1.27171 13.9121 1.46412 14.2969 1.67325C14.4307 1.74854 14.4976 1.89912 14.4642 2.0497L14.1714 3.5053C14.1463 3.63914 14.1965 3.77299 14.3052 3.85665C14.4809 3.99049 14.6482 4.14108 14.8155 4.29166C14.8825 4.35858 14.9745 4.39205 15.0581 4.39205C15.1 4.39205 15.1418 4.39204 15.1836 4.36694L16.5723 3.84829C16.5723 3.84829 16.6559 3.82319 16.6978 3.82319C16.7982 3.82319 16.8985 3.86501 16.9655 3.94867C17.2415 4.29165 17.4841 4.65137 17.71 5.02782C17.7853 5.16167 17.7686 5.32062 17.6682 5.43773L16.6894 6.55034C16.5974 6.65073 16.5807 6.79294 16.6308 6.91843C16.7145 7.12756 16.7898 7.3367 16.8567 7.55421C16.8985 7.68806 17.0073 7.78007 17.1411 7.80517L18.5967 8.04777C18.7473 8.07287 18.8644 8.18998 18.8895 8.34056C18.8895 8.37402 18.8979 8.40749 18.9063 8.43258C18.9648 8.83413 18.9899 9.23567 18.9983 9.62885C18.9983 9.77943 18.9063 9.92165 18.7557 9.97184L17.3503 10.4487C17.2248 10.4905 17.1328 10.6076 17.116 10.7415C17.0826 10.9673 17.0491 11.1848 16.9989 11.4023C16.9655 11.5362 17.0157 11.67 17.116 11.7621L18.2621 12.699C18.3792 12.791 18.4211 12.9583 18.3709 13.0922C18.2119 13.5021 18.0195 13.8953 17.8104 14.2801C17.7518 14.3888 17.6263 14.4558 17.5092 14.4558C17.4841 14.4558 17.459 14.4558 17.4423 14.4558L15.9867 14.163C15.9867 14.163 15.9449 14.163 15.9198 14.163C15.811 14.163 15.7023 14.2132 15.6354 14.3052C15.5015 14.4808 15.3509 14.6482 15.2003 14.8155C15.1083 14.9159 15.0749 15.0581 15.1251 15.1835L15.6437 16.5722C15.6939 16.7144 15.6521 16.8734 15.535 16.9654C15.192 17.2415 14.8323 17.4841 14.4558 17.7099C14.4056 17.7434 14.3387 17.7601 14.2801 17.7601C14.1965 17.7601 14.1128 17.7267 14.0459 17.6681L12.9333 16.6893C12.8664 16.6308 12.7827 16.6057 12.6991 16.6057C12.6489 16.6057 12.607 16.6141 12.5568 16.6308C12.3477 16.7144 12.1386 16.7897 11.9211 16.8566C11.7872 16.8985 11.6952 17.0072 11.6701 17.1411L11.4275 18.5967C11.4024 18.7473 11.2853 18.8644 11.1347 18.8895C11.1012 18.8895 11.0678 18.8978 11.0427 18.9062C10.6411 18.9648 10.2396 18.9899 9.84642 18.9982C9.69584 18.9982 9.55363 18.9062 9.5118 18.7556L9.03497 17.3502C8.99314 17.2247 8.87602 17.1327 8.74217 17.116C8.5163 17.0825 8.2988 17.0491 8.0813 16.9989C8.0562 16.9989 8.02274 16.9905 7.99764 16.9905C7.88889 16.9905 7.78851 17.0323 7.72158 17.116L6.78465 18.2621C6.71772 18.3457 6.61733 18.3959 6.50858 18.3959C6.46676 18.3959 6.42493 18.3959 6.3831 18.3708C5.97319 18.2119 5.58001 18.0195 5.1952 17.8103C5.06135 17.735 4.99443 17.5844 5.02789 17.4339L5.32068 15.9783C5.34578 15.8444 5.29558 15.7106 5.18683 15.6269C5.01116 15.4931 4.84385 15.3425 4.67654 15.1919C4.60961 15.125 4.51759 15.0915 4.43394 15.0915C4.39211 15.0915 4.35028 15.0915 4.30845 15.1166L2.91978 15.6353C2.91978 15.6353 2.83612 15.6604 2.7943 15.6604C2.69391 15.6604 2.59352 15.6186 2.5266 15.5349C2.25054 15.1919 2.00794 14.8322 1.78207 14.4558C1.70678 14.3219 1.72351 14.163 1.8239 14.0458L2.80266 12.9332C2.89468 12.8328 2.91141 12.6906 2.86122 12.5651C2.77757 12.356 2.70228 12.1469 2.63535 11.9294C2.59352 11.7955 2.48477 11.7035 2.35092 11.6784L0.895326 11.4358C0.744747 11.4107 0.62763 11.2936 0.602534 11.143C0.602534 11.1095 0.594168 11.0761 0.585803 11.051C0.527244 10.6494 0.502148 10.2479 0.493782 9.85472C0.493782 9.70414 0.585803 9.56193 0.736382 9.51173L2.14179 9.0349C2.26727 8.99307 2.35929 8.87596 2.37602 8.74211C2.40948 8.51624 2.44295 8.29873 2.49314 8.08123C2.5266 7.94738 2.47641 7.81353 2.37602 7.72151L1.22995 6.78457C1.11283 6.69255 1.071 6.52525 1.1212 6.3914C1.28014 5.98149 1.47255 5.58831 1.68168 5.20349C1.74024 5.09474 1.86573 5.02782 1.98284 5.02782C2.00794 5.02782 2.03304 5.02782 2.04977 5.02782L3.50537 5.32061C3.50537 5.32061 3.54719 5.32061 3.57229 5.32061C3.68104 5.32061 3.78979 5.27042 3.85672 5.1784C3.99056 5.00272 4.14114 4.83541 4.29172 4.6681C4.38374 4.56772 4.41721 4.42551 4.36701 4.30002L3.84835 2.91135C3.79816 2.76913 3.83999 2.61019 3.9571 2.51817C4.30009 2.2421 4.65981 1.99951 5.03625 1.77364C5.08645 1.74017 5.15337 1.72345 5.21193 1.72345C5.29558 1.72345 5.37924 1.75691 5.44616 1.81547L6.55878 2.79423C6.6257 2.85279 6.70935 2.87788 6.79301 2.87788C6.8432 2.87788 6.88503 2.86952 6.93522 2.85279C7.14436 2.76913 7.3535 2.69385 7.571 2.62692C7.70485 2.58509 7.79687 2.47634 7.82197 2.34249L8.06457 0.886895C8.08966 0.736315 8.20678 0.6192 8.35736 0.594103C8.39082 0.594103 8.42428 0.585734 8.44938 0.577368C8.85093 0.51881 9.25247 0.493713 9.64565 0.485347ZM9.64565 0.000152588H9.63728C9.21901 0.000152588 8.79237 0.041976 8.37409 0.100535L8.27371 0.117269C7.91399 0.175828 7.62956 0.451885 7.571 0.811602L7.34513 2.19191C7.16946 2.25047 6.98542 2.30903 6.81811 2.38432L5.76405 1.46412C5.61347 1.33027 5.4127 1.25498 5.21193 1.25498C5.06135 1.25498 4.91914 1.29681 4.79365 1.3721C4.40047 1.60633 4.01566 1.86566 3.65594 2.15845C3.37152 2.38432 3.27113 2.76077 3.39661 3.10376L3.89018 4.41714C3.7647 4.55935 3.63921 4.70156 3.5221 4.84378L2.15015 4.56772C2.09159 4.55935 2.0414 4.55099 1.98284 4.55099C1.68168 4.55099 1.40562 4.70993 1.25504 4.96926C1.02917 5.37081 0.828402 5.78909 0.661092 6.21573C0.527244 6.55035 0.62763 6.93516 0.912058 7.16103L1.99121 8.04777C1.94938 8.23181 1.91592 8.41585 1.89082 8.5999L0.569072 9.05163C0.226086 9.16875 -0.00814847 9.495 0.00021704 9.86308C0.00021704 10.2814 0.0420446 10.708 0.100603 11.1263L0.117334 11.2267C0.175893 11.5864 0.451955 11.8708 0.811671 11.9294L2.19198 12.1552C2.25054 12.3309 2.3091 12.515 2.38439 12.6823L1.46418 13.7363C1.22158 14.0124 1.18812 14.4056 1.37216 14.7151C1.60639 15.1083 1.86573 15.4931 2.15852 15.8528C2.31746 16.0536 2.5517 16.1623 2.81103 16.1623C2.91141 16.1623 3.0118 16.1456 3.10382 16.1121L4.41721 15.6186C4.55942 15.744 4.70163 15.8695 4.84385 15.9783L4.56778 17.3502C4.50086 17.7099 4.65981 18.0613 4.97769 18.2453C5.37924 18.4712 5.79751 18.672 6.22416 18.8393C6.31618 18.8727 6.42493 18.8978 6.52531 18.8978C6.77628 18.8978 7.01051 18.7891 7.16946 18.5883L8.0562 17.5092C8.22351 17.551 8.41592 17.5844 8.60833 17.6095L9.06006 18.9313C9.17718 19.2743 9.49507 19.5002 9.85479 19.5002C10.2814 19.5002 10.7081 19.4583 11.1263 19.3998L11.2267 19.383C11.5864 19.3245 11.8625 19.0484 11.9294 18.6887L12.1553 17.3084C12.331 17.2498 12.515 17.1913 12.6823 17.116L13.7364 18.0362C13.887 18.17 14.0877 18.2453 14.2885 18.2453C14.4391 18.2453 14.5813 18.2035 14.7068 18.1282C15.1 17.894 15.4848 17.6263 15.8445 17.3419C16.1289 17.116 16.2293 16.7395 16.1038 16.3965L15.6103 15.0832C15.7357 14.941 15.8612 14.7987 15.9783 14.6565L17.3503 14.9326C17.4088 14.941 17.459 14.9493 17.5176 14.9493C17.8188 14.9493 18.0948 14.7904 18.2454 14.531C18.4713 14.1295 18.672 13.7112 18.8393 13.2846C18.9732 12.95 18.8728 12.5651 18.5884 12.3393L17.5092 11.4525C17.5511 11.2685 17.5845 11.0845 17.6096 10.9004L18.9314 10.4487C19.2744 10.3316 19.5086 10.0053 19.5002 9.63722C19.5002 9.21894 19.4584 8.7923 19.3998 8.37402L19.3831 8.27364C19.3245 7.91392 19.0485 7.62949 18.6888 7.57093L17.3085 7.34507C17.2499 7.16939 17.1913 6.98535 17.116 6.81804L18.0363 5.76399C18.2789 5.48793 18.3123 5.09474 18.1283 4.78522C17.894 4.39204 17.6347 4.00723 17.3419 3.64751C17.183 3.44674 16.9487 3.33799 16.6894 3.33799C16.589 3.33799 16.4886 3.35472 16.3966 3.38818L15.0832 3.88175C14.941 3.75627 14.7988 3.63078 14.6566 3.52203L14.9327 2.15009C14.9996 1.79037 14.8406 1.43902 14.5227 1.25498C14.1212 1.02911 13.7029 0.828332 13.2763 0.661022C13.1843 0.62756 13.0755 0.602464 12.9751 0.602464C12.7242 0.602464 12.4899 0.711218 12.331 0.91199L11.4442 1.99114C11.2769 1.94931 11.0845 1.91585 10.8921 1.89075L10.4404 0.569008C10.3233 0.226022 10.0054 0.000152588 9.64565 0.000152588Z" fill="url(#paint0_linear_4118_862)"></path>
                                    <path d="M10.6259 13.06C10.0403 13.6456 9.09499 13.6456 8.50941 13.06L4.90387 9.45447C4.66127 9.21187 4.66127 8.8187 4.90387 8.5761L6.14197 7.338C6.38457 7.0954 6.77775 7.0954 7.02035 7.338L9.57183 9.88948L16.7076 2.7537C16.9502 2.5111 17.3434 2.5111 17.586 2.7537L18.8241 3.99179C19.0667 4.23439 19.0667 4.62758 18.8241 4.87018L10.6259 13.0684V13.06Z" fill="black"></path>
                                    <defs>
                                        <linearGradient id="paint0_linear_4118_862" x1="19.5004" y1="1.34497" x2="-0.431773" y2="1.83207" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D"></stop>
                                            <stop offset="1" stop-color="#650916"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                            Full Body Respray
                        </li>
                        <li class="w-full flex items-center gap-2 font-normal text-sm text-[#121212]">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M9.64565 0.485347C9.79623 0.485347 9.93844 0.577369 9.98027 0.727948L10.4571 2.13335C10.4989 2.25884 10.616 2.35086 10.7499 2.36759C10.9758 2.40105 11.1933 2.43451 11.4108 2.4847C11.4359 2.4847 11.4693 2.49307 11.4944 2.49307C11.6032 2.49307 11.7036 2.45124 11.7705 2.36759L12.7074 1.22152C12.7743 1.13786 12.8747 1.08766 12.9835 1.08766C13.0253 1.08766 13.0671 1.08766 13.109 1.11276C13.5189 1.27171 13.9121 1.46412 14.2969 1.67325C14.4307 1.74854 14.4976 1.89912 14.4642 2.0497L14.1714 3.5053C14.1463 3.63914 14.1965 3.77299 14.3052 3.85665C14.4809 3.99049 14.6482 4.14108 14.8155 4.29166C14.8825 4.35858 14.9745 4.39205 15.0581 4.39205C15.1 4.39205 15.1418 4.39204 15.1836 4.36694L16.5723 3.84829C16.5723 3.84829 16.6559 3.82319 16.6978 3.82319C16.7982 3.82319 16.8985 3.86501 16.9655 3.94867C17.2415 4.29165 17.4841 4.65137 17.71 5.02782C17.7853 5.16167 17.7686 5.32062 17.6682 5.43773L16.6894 6.55034C16.5974 6.65073 16.5807 6.79294 16.6308 6.91843C16.7145 7.12756 16.7898 7.3367 16.8567 7.55421C16.8985 7.68806 17.0073 7.78007 17.1411 7.80517L18.5967 8.04777C18.7473 8.07287 18.8644 8.18998 18.8895 8.34056C18.8895 8.37402 18.8979 8.40749 18.9063 8.43258C18.9648 8.83413 18.9899 9.23567 18.9983 9.62885C18.9983 9.77943 18.9063 9.92165 18.7557 9.97184L17.3503 10.4487C17.2248 10.4905 17.1328 10.6076 17.116 10.7415C17.0826 10.9673 17.0491 11.1848 16.9989 11.4023C16.9655 11.5362 17.0157 11.67 17.116 11.7621L18.2621 12.699C18.3792 12.791 18.4211 12.9583 18.3709 13.0922C18.2119 13.5021 18.0195 13.8953 17.8104 14.2801C17.7518 14.3888 17.6263 14.4558 17.5092 14.4558C17.4841 14.4558 17.459 14.4558 17.4423 14.4558L15.9867 14.163C15.9867 14.163 15.9449 14.163 15.9198 14.163C15.811 14.163 15.7023 14.2132 15.6354 14.3052C15.5015 14.4808 15.3509 14.6482 15.2003 14.8155C15.1083 14.9159 15.0749 15.0581 15.1251 15.1835L15.6437 16.5722C15.6939 16.7144 15.6521 16.8734 15.535 16.9654C15.192 17.2415 14.8323 17.4841 14.4558 17.7099C14.4056 17.7434 14.3387 17.7601 14.2801 17.7601C14.1965 17.7601 14.1128 17.7267 14.0459 17.6681L12.9333 16.6893C12.8664 16.6308 12.7827 16.6057 12.6991 16.6057C12.6489 16.6057 12.607 16.6141 12.5568 16.6308C12.3477 16.7144 12.1386 16.7897 11.9211 16.8566C11.7872 16.8985 11.6952 17.0072 11.6701 17.1411L11.4275 18.5967C11.4024 18.7473 11.2853 18.8644 11.1347 18.8895C11.1012 18.8895 11.0678 18.8978 11.0427 18.9062C10.6411 18.9648 10.2396 18.9899 9.84642 18.9982C9.69584 18.9982 9.55363 18.9062 9.5118 18.7556L9.03497 17.3502C8.99314 17.2247 8.87602 17.1327 8.74217 17.116C8.5163 17.0825 8.2988 17.0491 8.0813 16.9989C8.0562 16.9989 8.02274 16.9905 7.99764 16.9905C7.88889 16.9905 7.78851 17.0323 7.72158 17.116L6.78465 18.2621C6.71772 18.3457 6.61733 18.3959 6.50858 18.3959C6.46676 18.3959 6.42493 18.3959 6.3831 18.3708C5.97319 18.2119 5.58001 18.0195 5.1952 17.8103C5.06135 17.735 4.99443 17.5844 5.02789 17.4339L5.32068 15.9783C5.34578 15.8444 5.29558 15.7106 5.18683 15.6269C5.01116 15.4931 4.84385 15.3425 4.67654 15.1919C4.60961 15.125 4.51759 15.0915 4.43394 15.0915C4.39211 15.0915 4.35028 15.0915 4.30845 15.1166L2.91978 15.6353C2.91978 15.6353 2.83612 15.6604 2.7943 15.6604C2.69391 15.6604 2.59352 15.6186 2.5266 15.5349C2.25054 15.1919 2.00794 14.8322 1.78207 14.4558C1.70678 14.3219 1.72351 14.163 1.8239 14.0458L2.80266 12.9332C2.89468 12.8328 2.91141 12.6906 2.86122 12.5651C2.77757 12.356 2.70228 12.1469 2.63535 11.9294C2.59352 11.7955 2.48477 11.7035 2.35092 11.6784L0.895326 11.4358C0.744747 11.4107 0.62763 11.2936 0.602534 11.143C0.602534 11.1095 0.594168 11.0761 0.585803 11.051C0.527244 10.6494 0.502148 10.2479 0.493782 9.85472C0.493782 9.70414 0.585803 9.56193 0.736382 9.51173L2.14179 9.0349C2.26727 8.99307 2.35929 8.87596 2.37602 8.74211C2.40948 8.51624 2.44295 8.29873 2.49314 8.08123C2.5266 7.94738 2.47641 7.81353 2.37602 7.72151L1.22995 6.78457C1.11283 6.69255 1.071 6.52525 1.1212 6.3914C1.28014 5.98149 1.47255 5.58831 1.68168 5.20349C1.74024 5.09474 1.86573 5.02782 1.98284 5.02782C2.00794 5.02782 2.03304 5.02782 2.04977 5.02782L3.50537 5.32061C3.50537 5.32061 3.54719 5.32061 3.57229 5.32061C3.68104 5.32061 3.78979 5.27042 3.85672 5.1784C3.99056 5.00272 4.14114 4.83541 4.29172 4.6681C4.38374 4.56772 4.41721 4.42551 4.36701 4.30002L3.84835 2.91135C3.79816 2.76913 3.83999 2.61019 3.9571 2.51817C4.30009 2.2421 4.65981 1.99951 5.03625 1.77364C5.08645 1.74017 5.15337 1.72345 5.21193 1.72345C5.29558 1.72345 5.37924 1.75691 5.44616 1.81547L6.55878 2.79423C6.6257 2.85279 6.70935 2.87788 6.79301 2.87788C6.8432 2.87788 6.88503 2.86952 6.93522 2.85279C7.14436 2.76913 7.3535 2.69385 7.571 2.62692C7.70485 2.58509 7.79687 2.47634 7.82197 2.34249L8.06457 0.886895C8.08966 0.736315 8.20678 0.6192 8.35736 0.594103C8.39082 0.594103 8.42428 0.585734 8.44938 0.577368C8.85093 0.51881 9.25247 0.493713 9.64565 0.485347ZM9.64565 0.000152588H9.63728C9.21901 0.000152588 8.79237 0.041976 8.37409 0.100535L8.27371 0.117269C7.91399 0.175828 7.62956 0.451885 7.571 0.811602L7.34513 2.19191C7.16946 2.25047 6.98542 2.30903 6.81811 2.38432L5.76405 1.46412C5.61347 1.33027 5.4127 1.25498 5.21193 1.25498C5.06135 1.25498 4.91914 1.29681 4.79365 1.3721C4.40047 1.60633 4.01566 1.86566 3.65594 2.15845C3.37152 2.38432 3.27113 2.76077 3.39661 3.10376L3.89018 4.41714C3.7647 4.55935 3.63921 4.70156 3.5221 4.84378L2.15015 4.56772C2.09159 4.55935 2.0414 4.55099 1.98284 4.55099C1.68168 4.55099 1.40562 4.70993 1.25504 4.96926C1.02917 5.37081 0.828402 5.78909 0.661092 6.21573C0.527244 6.55035 0.62763 6.93516 0.912058 7.16103L1.99121 8.04777C1.94938 8.23181 1.91592 8.41585 1.89082 8.5999L0.569072 9.05163C0.226086 9.16875 -0.00814847 9.495 0.00021704 9.86308C0.00021704 10.2814 0.0420446 10.708 0.100603 11.1263L0.117334 11.2267C0.175893 11.5864 0.451955 11.8708 0.811671 11.9294L2.19198 12.1552C2.25054 12.3309 2.3091 12.515 2.38439 12.6823L1.46418 13.7363C1.22158 14.0124 1.18812 14.4056 1.37216 14.7151C1.60639 15.1083 1.86573 15.4931 2.15852 15.8528C2.31746 16.0536 2.5517 16.1623 2.81103 16.1623C2.91141 16.1623 3.0118 16.1456 3.10382 16.1121L4.41721 15.6186C4.55942 15.744 4.70163 15.8695 4.84385 15.9783L4.56778 17.3502C4.50086 17.7099 4.65981 18.0613 4.97769 18.2453C5.37924 18.4712 5.79751 18.672 6.22416 18.8393C6.31618 18.8727 6.42493 18.8978 6.52531 18.8978C6.77628 18.8978 7.01051 18.7891 7.16946 18.5883L8.0562 17.5092C8.22351 17.551 8.41592 17.5844 8.60833 17.6095L9.06006 18.9313C9.17718 19.2743 9.49507 19.5002 9.85479 19.5002C10.2814 19.5002 10.7081 19.4583 11.1263 19.3998L11.2267 19.383C11.5864 19.3245 11.8625 19.0484 11.9294 18.6887L12.1553 17.3084C12.331 17.2498 12.515 17.1913 12.6823 17.116L13.7364 18.0362C13.887 18.17 14.0877 18.2453 14.2885 18.2453C14.4391 18.2453 14.5813 18.2035 14.7068 18.1282C15.1 17.894 15.4848 17.6263 15.8445 17.3419C16.1289 17.116 16.2293 16.7395 16.1038 16.3965L15.6103 15.0832C15.7357 14.941 15.8612 14.7987 15.9783 14.6565L17.3503 14.9326C17.4088 14.941 17.459 14.9493 17.5176 14.9493C17.8188 14.9493 18.0948 14.7904 18.2454 14.531C18.4713 14.1295 18.672 13.7112 18.8393 13.2846C18.9732 12.95 18.8728 12.5651 18.5884 12.3393L17.5092 11.4525C17.5511 11.2685 17.5845 11.0845 17.6096 10.9004L18.9314 10.4487C19.2744 10.3316 19.5086 10.0053 19.5002 9.63722C19.5002 9.21894 19.4584 8.7923 19.3998 8.37402L19.3831 8.27364C19.3245 7.91392 19.0485 7.62949 18.6888 7.57093L17.3085 7.34507C17.2499 7.16939 17.1913 6.98535 17.116 6.81804L18.0363 5.76399C18.2789 5.48793 18.3123 5.09474 18.1283 4.78522C17.894 4.39204 17.6347 4.00723 17.3419 3.64751C17.183 3.44674 16.9487 3.33799 16.6894 3.33799C16.589 3.33799 16.4886 3.35472 16.3966 3.38818L15.0832 3.88175C14.941 3.75627 14.7988 3.63078 14.6566 3.52203L14.9327 2.15009C14.9996 1.79037 14.8406 1.43902 14.5227 1.25498C14.1212 1.02911 13.7029 0.828332 13.2763 0.661022C13.1843 0.62756 13.0755 0.602464 12.9751 0.602464C12.7242 0.602464 12.4899 0.711218 12.331 0.91199L11.4442 1.99114C11.2769 1.94931 11.0845 1.91585 10.8921 1.89075L10.4404 0.569008C10.3233 0.226022 10.0054 0.000152588 9.64565 0.000152588Z" fill="url(#paint0_linear_4118_862)"></path>
                                    <path d="M10.6259 13.06C10.0403 13.6456 9.09499 13.6456 8.50941 13.06L4.90387 9.45447C4.66127 9.21187 4.66127 8.8187 4.90387 8.5761L6.14197 7.338C6.38457 7.0954 6.77775 7.0954 7.02035 7.338L9.57183 9.88948L16.7076 2.7537C16.9502 2.5111 17.3434 2.5111 17.586 2.7537L18.8241 3.99179C19.0667 4.23439 19.0667 4.62758 18.8241 4.87018L10.6259 13.0684V13.06Z" fill="black"></path>
                                    <defs>
                                        <linearGradient id="paint0_linear_4118_862" x1="19.5004" y1="1.34497" x2="-0.431773" y2="1.83207" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D"></stop>
                                            <stop offset="1" stop-color="#650916"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                            Full Body Respray
                        </li>
                        <li class="w-full flex items-center gap-2 font-normal text-sm text-[#121212]">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M9.64565 0.485347C9.79623 0.485347 9.93844 0.577369 9.98027 0.727948L10.4571 2.13335C10.4989 2.25884 10.616 2.35086 10.7499 2.36759C10.9758 2.40105 11.1933 2.43451 11.4108 2.4847C11.4359 2.4847 11.4693 2.49307 11.4944 2.49307C11.6032 2.49307 11.7036 2.45124 11.7705 2.36759L12.7074 1.22152C12.7743 1.13786 12.8747 1.08766 12.9835 1.08766C13.0253 1.08766 13.0671 1.08766 13.109 1.11276C13.5189 1.27171 13.9121 1.46412 14.2969 1.67325C14.4307 1.74854 14.4976 1.89912 14.4642 2.0497L14.1714 3.5053C14.1463 3.63914 14.1965 3.77299 14.3052 3.85665C14.4809 3.99049 14.6482 4.14108 14.8155 4.29166C14.8825 4.35858 14.9745 4.39205 15.0581 4.39205C15.1 4.39205 15.1418 4.39204 15.1836 4.36694L16.5723 3.84829C16.5723 3.84829 16.6559 3.82319 16.6978 3.82319C16.7982 3.82319 16.8985 3.86501 16.9655 3.94867C17.2415 4.29165 17.4841 4.65137 17.71 5.02782C17.7853 5.16167 17.7686 5.32062 17.6682 5.43773L16.6894 6.55034C16.5974 6.65073 16.5807 6.79294 16.6308 6.91843C16.7145 7.12756 16.7898 7.3367 16.8567 7.55421C16.8985 7.68806 17.0073 7.78007 17.1411 7.80517L18.5967 8.04777C18.7473 8.07287 18.8644 8.18998 18.8895 8.34056C18.8895 8.37402 18.8979 8.40749 18.9063 8.43258C18.9648 8.83413 18.9899 9.23567 18.9983 9.62885C18.9983 9.77943 18.9063 9.92165 18.7557 9.97184L17.3503 10.4487C17.2248 10.4905 17.1328 10.6076 17.116 10.7415C17.0826 10.9673 17.0491 11.1848 16.9989 11.4023C16.9655 11.5362 17.0157 11.67 17.116 11.7621L18.2621 12.699C18.3792 12.791 18.4211 12.9583 18.3709 13.0922C18.2119 13.5021 18.0195 13.8953 17.8104 14.2801C17.7518 14.3888 17.6263 14.4558 17.5092 14.4558C17.4841 14.4558 17.459 14.4558 17.4423 14.4558L15.9867 14.163C15.9867 14.163 15.9449 14.163 15.9198 14.163C15.811 14.163 15.7023 14.2132 15.6354 14.3052C15.5015 14.4808 15.3509 14.6482 15.2003 14.8155C15.1083 14.9159 15.0749 15.0581 15.1251 15.1835L15.6437 16.5722C15.6939 16.7144 15.6521 16.8734 15.535 16.9654C15.192 17.2415 14.8323 17.4841 14.4558 17.7099C14.4056 17.7434 14.3387 17.7601 14.2801 17.7601C14.1965 17.7601 14.1128 17.7267 14.0459 17.6681L12.9333 16.6893C12.8664 16.6308 12.7827 16.6057 12.6991 16.6057C12.6489 16.6057 12.607 16.6141 12.5568 16.6308C12.3477 16.7144 12.1386 16.7897 11.9211 16.8566C11.7872 16.8985 11.6952 17.0072 11.6701 17.1411L11.4275 18.5967C11.4024 18.7473 11.2853 18.8644 11.1347 18.8895C11.1012 18.8895 11.0678 18.8978 11.0427 18.9062C10.6411 18.9648 10.2396 18.9899 9.84642 18.9982C9.69584 18.9982 9.55363 18.9062 9.5118 18.7556L9.03497 17.3502C8.99314 17.2247 8.87602 17.1327 8.74217 17.116C8.5163 17.0825 8.2988 17.0491 8.0813 16.9989C8.0562 16.9989 8.02274 16.9905 7.99764 16.9905C7.88889 16.9905 7.78851 17.0323 7.72158 17.116L6.78465 18.2621C6.71772 18.3457 6.61733 18.3959 6.50858 18.3959C6.46676 18.3959 6.42493 18.3959 6.3831 18.3708C5.97319 18.2119 5.58001 18.0195 5.1952 17.8103C5.06135 17.735 4.99443 17.5844 5.02789 17.4339L5.32068 15.9783C5.34578 15.8444 5.29558 15.7106 5.18683 15.6269C5.01116 15.4931 4.84385 15.3425 4.67654 15.1919C4.60961 15.125 4.51759 15.0915 4.43394 15.0915C4.39211 15.0915 4.35028 15.0915 4.30845 15.1166L2.91978 15.6353C2.91978 15.6353 2.83612 15.6604 2.7943 15.6604C2.69391 15.6604 2.59352 15.6186 2.5266 15.5349C2.25054 15.1919 2.00794 14.8322 1.78207 14.4558C1.70678 14.3219 1.72351 14.163 1.8239 14.0458L2.80266 12.9332C2.89468 12.8328 2.91141 12.6906 2.86122 12.5651C2.77757 12.356 2.70228 12.1469 2.63535 11.9294C2.59352 11.7955 2.48477 11.7035 2.35092 11.6784L0.895326 11.4358C0.744747 11.4107 0.62763 11.2936 0.602534 11.143C0.602534 11.1095 0.594168 11.0761 0.585803 11.051C0.527244 10.6494 0.502148 10.2479 0.493782 9.85472C0.493782 9.70414 0.585803 9.56193 0.736382 9.51173L2.14179 9.0349C2.26727 8.99307 2.35929 8.87596 2.37602 8.74211C2.40948 8.51624 2.44295 8.29873 2.49314 8.08123C2.5266 7.94738 2.47641 7.81353 2.37602 7.72151L1.22995 6.78457C1.11283 6.69255 1.071 6.52525 1.1212 6.3914C1.28014 5.98149 1.47255 5.58831 1.68168 5.20349C1.74024 5.09474 1.86573 5.02782 1.98284 5.02782C2.00794 5.02782 2.03304 5.02782 2.04977 5.02782L3.50537 5.32061C3.50537 5.32061 3.54719 5.32061 3.57229 5.32061C3.68104 5.32061 3.78979 5.27042 3.85672 5.1784C3.99056 5.00272 4.14114 4.83541 4.29172 4.6681C4.38374 4.56772 4.41721 4.42551 4.36701 4.30002L3.84835 2.91135C3.79816 2.76913 3.83999 2.61019 3.9571 2.51817C4.30009 2.2421 4.65981 1.99951 5.03625 1.77364C5.08645 1.74017 5.15337 1.72345 5.21193 1.72345C5.29558 1.72345 5.37924 1.75691 5.44616 1.81547L6.55878 2.79423C6.6257 2.85279 6.70935 2.87788 6.79301 2.87788C6.8432 2.87788 6.88503 2.86952 6.93522 2.85279C7.14436 2.76913 7.3535 2.69385 7.571 2.62692C7.70485 2.58509 7.79687 2.47634 7.82197 2.34249L8.06457 0.886895C8.08966 0.736315 8.20678 0.6192 8.35736 0.594103C8.39082 0.594103 8.42428 0.585734 8.44938 0.577368C8.85093 0.51881 9.25247 0.493713 9.64565 0.485347ZM9.64565 0.000152588H9.63728C9.21901 0.000152588 8.79237 0.041976 8.37409 0.100535L8.27371 0.117269C7.91399 0.175828 7.62956 0.451885 7.571 0.811602L7.34513 2.19191C7.16946 2.25047 6.98542 2.30903 6.81811 2.38432L5.76405 1.46412C5.61347 1.33027 5.4127 1.25498 5.21193 1.25498C5.06135 1.25498 4.91914 1.29681 4.79365 1.3721C4.40047 1.60633 4.01566 1.86566 3.65594 2.15845C3.37152 2.38432 3.27113 2.76077 3.39661 3.10376L3.89018 4.41714C3.7647 4.55935 3.63921 4.70156 3.5221 4.84378L2.15015 4.56772C2.09159 4.55935 2.0414 4.55099 1.98284 4.55099C1.68168 4.55099 1.40562 4.70993 1.25504 4.96926C1.02917 5.37081 0.828402 5.78909 0.661092 6.21573C0.527244 6.55035 0.62763 6.93516 0.912058 7.16103L1.99121 8.04777C1.94938 8.23181 1.91592 8.41585 1.89082 8.5999L0.569072 9.05163C0.226086 9.16875 -0.00814847 9.495 0.00021704 9.86308C0.00021704 10.2814 0.0420446 10.708 0.100603 11.1263L0.117334 11.2267C0.175893 11.5864 0.451955 11.8708 0.811671 11.9294L2.19198 12.1552C2.25054 12.3309 2.3091 12.515 2.38439 12.6823L1.46418 13.7363C1.22158 14.0124 1.18812 14.4056 1.37216 14.7151C1.60639 15.1083 1.86573 15.4931 2.15852 15.8528C2.31746 16.0536 2.5517 16.1623 2.81103 16.1623C2.91141 16.1623 3.0118 16.1456 3.10382 16.1121L4.41721 15.6186C4.55942 15.744 4.70163 15.8695 4.84385 15.9783L4.56778 17.3502C4.50086 17.7099 4.65981 18.0613 4.97769 18.2453C5.37924 18.4712 5.79751 18.672 6.22416 18.8393C6.31618 18.8727 6.42493 18.8978 6.52531 18.8978C6.77628 18.8978 7.01051 18.7891 7.16946 18.5883L8.0562 17.5092C8.22351 17.551 8.41592 17.5844 8.60833 17.6095L9.06006 18.9313C9.17718 19.2743 9.49507 19.5002 9.85479 19.5002C10.2814 19.5002 10.7081 19.4583 11.1263 19.3998L11.2267 19.383C11.5864 19.3245 11.8625 19.0484 11.9294 18.6887L12.1553 17.3084C12.331 17.2498 12.515 17.1913 12.6823 17.116L13.7364 18.0362C13.887 18.17 14.0877 18.2453 14.2885 18.2453C14.4391 18.2453 14.5813 18.2035 14.7068 18.1282C15.1 17.894 15.4848 17.6263 15.8445 17.3419C16.1289 17.116 16.2293 16.7395 16.1038 16.3965L15.6103 15.0832C15.7357 14.941 15.8612 14.7987 15.9783 14.6565L17.3503 14.9326C17.4088 14.941 17.459 14.9493 17.5176 14.9493C17.8188 14.9493 18.0948 14.7904 18.2454 14.531C18.4713 14.1295 18.672 13.7112 18.8393 13.2846C18.9732 12.95 18.8728 12.5651 18.5884 12.3393L17.5092 11.4525C17.5511 11.2685 17.5845 11.0845 17.6096 10.9004L18.9314 10.4487C19.2744 10.3316 19.5086 10.0053 19.5002 9.63722C19.5002 9.21894 19.4584 8.7923 19.3998 8.37402L19.3831 8.27364C19.3245 7.91392 19.0485 7.62949 18.6888 7.57093L17.3085 7.34507C17.2499 7.16939 17.1913 6.98535 17.116 6.81804L18.0363 5.76399C18.2789 5.48793 18.3123 5.09474 18.1283 4.78522C17.894 4.39204 17.6347 4.00723 17.3419 3.64751C17.183 3.44674 16.9487 3.33799 16.6894 3.33799C16.589 3.33799 16.4886 3.35472 16.3966 3.38818L15.0832 3.88175C14.941 3.75627 14.7988 3.63078 14.6566 3.52203L14.9327 2.15009C14.9996 1.79037 14.8406 1.43902 14.5227 1.25498C14.1212 1.02911 13.7029 0.828332 13.2763 0.661022C13.1843 0.62756 13.0755 0.602464 12.9751 0.602464C12.7242 0.602464 12.4899 0.711218 12.331 0.91199L11.4442 1.99114C11.2769 1.94931 11.0845 1.91585 10.8921 1.89075L10.4404 0.569008C10.3233 0.226022 10.0054 0.000152588 9.64565 0.000152588Z" fill="url(#paint0_linear_4118_862)"></path>
                                    <path d="M10.6259 13.06C10.0403 13.6456 9.09499 13.6456 8.50941 13.06L4.90387 9.45447C4.66127 9.21187 4.66127 8.8187 4.90387 8.5761L6.14197 7.338C6.38457 7.0954 6.77775 7.0954 7.02035 7.338L9.57183 9.88948L16.7076 2.7537C16.9502 2.5111 17.3434 2.5111 17.586 2.7537L18.8241 3.99179C19.0667 4.23439 19.0667 4.62758 18.8241 4.87018L10.6259 13.0684V13.06Z" fill="black"></path>
                                    <defs>
                                        <linearGradient id="paint0_linear_4118_862" x1="19.5004" y1="1.34497" x2="-0.431773" y2="1.83207" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#CB122D"></stop>
                                            <stop offset="1" stop-color="#650916"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                            Full Body Respray
                        </li>
                    
                    </ul>
                </div>
            </div>
        <div class="w-full flex flex-col items-start gap-2 bg-[#FAFAFA] border border-[#EFEFEF] rounded-xl shadow-[0_0.125rem_0.25rem_-0.125rem_rgba(0,0,0,0.10)] p-3">
                <div class="flex flex-col gap-2 w-full text-start">
                    <h2 class="text-[#121212] font-bold text-base">Price Breakdown</h2>
                    <div class="w-full flex justify-between items-center gap-2">
                        <div class="text-[#6B6B6B] text-sm font-medium">
                            Subtotal (1 service)</div>
                        <div class="text-[#121212] text-base font-bold">
                            ₹2,999
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-3 w-full text-start border-t border-[#E5E5E5] pt-3">
                    <div class="w-full flex justify-between items-center gap-2">
                        <h2 class="text-[#121212] font-bold text-base">Total</h2>
                        <div class="text-[#CB122D] text-xl font-bold">
                            ₹3,998
                        </div>
                    </div>
                    <p class="text-sm text-[#000000] font-normal flex gap-2">
                        <span>
                            <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 14 14" fill="none">
                                <g clip-path="url(#clip0_4118_902)">
                                    <path d="M6.99858 12.8272C10.2187 12.8272 12.8292 10.2168 12.8292 6.99662C12.8292 3.77647 10.2187 1.16602 6.99858 1.16602C3.77842 1.16602 1.16797 3.77647 1.16797 6.99662C1.16797 10.2168 3.77842 12.8272 6.99858 12.8272Z" stroke="black" stroke-width="1.16612" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6.99609 4.66406V6.99631" stroke="black" stroke-width="1.16612" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6.99609 9.32812H7.00192" stroke="black" stroke-width="1.16612" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_4118_902">
                                        <rect width="13.9935" height="13.9935" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </span>
                        This is an estimated price, Final price may vary based on your car
                        model and condition.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div
        class="h-[4.938rem] p-6 w-full flex justify-between items-center gap-2 bg-white border border-[#E5E5E5] shadow-[0_-0.25rem_1rem_0_#00000014]">
        <div class="flex flex-col w-full">
            <div class="text-[#637083] text-xs font-normal">1 item</div>
            <div class="text-[#121212] text-lg font-bold">₹2,999</div>
        </div>
        <div class="w-full flex justify-end">
            <a href=""
                class="bg-[#CB122D] w-fit px-8 rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D]">
                Checkout</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helper functions
    function $(selector) {
        return document.querySelector(selector);
    }
    
    function $$(selector) {
        return document.querySelectorAll(selector);
    }
    
    // Show/Hide functionality for vehicle sections
    const changeVehicleBtn = $('#changeVehicleBtn');
    const cancelVehicleBtn = $('#cancelVehicleBtn');
    const cartContentSection = $('#cartContentSection');
    const vehicleFormSection = $('#vehicleFormSection');
    
    if (changeVehicleBtn) {
        changeVehicleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (cartContentSection) cartContentSection.classList.add('hidden');
            vehicleFormSection.classList.remove('hidden');
        });
    }
    
    if (cancelVehicleBtn) {
        cancelVehicleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            vehicleFormSection.classList.add('hidden');
            if (cartContentSection) cartContentSection.classList.remove('hidden');
        });
    }
    
    // Get current page URL for updating query params
    const currentUrl = window.location.href.split('?')[0];
    
    // Function to set dropdown enabled/disabled state
    function setDropdownEnabled(inputId, enabled) {
        const input = $('#' + inputId);
        if (!input) return;
        
        if (enabled) {
            input.disabled = false;
            input.classList.remove('cursor-not-allowed');
            input.classList.add('cursor-pointer');
            input.style.color = '#000';
            input.style.backgroundColor = '#fff';
        } else {
            input.disabled = true;
            input.classList.remove('cursor-pointer');
            input.classList.add('cursor-not-allowed');
            input.style.color = 'rgba(0, 0, 0, 0.5)';
            input.style.backgroundColor = '#fff';
        }
    }
    
    // Function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Function to fetch car models from API
    function fetchVehicleModels(carMake) {
        if (!carMake) {
            return;
        }
        
        const modelList = $('#vehicleModelList');
        const modelInput = $('#vehicleModelInput');
        const modelIcon = $('#vehicleModelIcon');
        
        if (!modelList || !modelInput || !modelIcon) return;
        
        // Clear previous model selection
        modelInput.value = '';
        setDropdownEnabled('vehicleModelInput', false);
        
        // Show loading state
        modelInput.placeholder = 'Loading models...';
        modelIcon.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#CB122D]"></div>';
        
        modelList.innerHTML = '<div class="col-span-2 text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div><p class="text-gray-500 text-sm mt-2">Loading models...</p></div>';
        
        // Make AJAX call using fetch
        const formData = new FormData();
        formData.append('action', 'get_car_models');
        formData.append('car_make', carMake);
        
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            if (response.success && response.data.models && response.data.models.length > 0) {
                let html = '';
                response.data.models.forEach(function(model) {
                    const modelName = model.car_model || '';
                    if (modelName) {
                        const escapedModelName = escapeHtml(modelName);
                        const modelImage = model.car_model_image_url || '<?php echo esc_js($img_url . "car-brand.webp"); ?>';
                        html += `
                            <div class="cursor-pointer text-center" data-value="${escapedModelName}">
                                <img src="${modelImage}" alt="${escapedModelName}" class="w-full h-24 object-cover mb-1 rounded" loading="lazy" fetchpriority="low" onerror="this.src='<?php echo esc_js($img_url . "car-brand.webp"); ?>';" />
                                <p class="text-xs">${escapedModelName}</p>
                            </div>
                        `;
                    }
                });
                modelList.innerHTML = html;
                modelInput.placeholder = 'Car Model';
                setDropdownEnabled('vehicleModelInput', true);
            } else {
                modelList.innerHTML = '<div class="col-span-2 text-center py-8"><p class="text-gray-500 text-sm">No models found</p></div>';
                modelInput.placeholder = 'No models found';
            }
            modelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
        })
        .catch(function() {
            modelList.innerHTML = '<div class="col-span-2 text-center py-8"><p class="text-gray-500 text-sm">Failed to load models</p></div>';
            modelInput.placeholder = 'Failed to load models';
            modelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
        });
    }
    
    // Function to fetch fuel types from API
    function fetchVehicleFuelTypes(carMake, carModel) {
        if (!carMake || !carModel) {
            return;
        }
        
        const fuelList = $('#vehicleFuelList');
        const fuelInput = $('#vehicleFuelInput');
        const fuelIcon = $('#vehicleFuelIcon');
        
        if (!fuelList || !fuelInput || !fuelIcon) return;
        
        // Clear previous fuel type selection
        fuelInput.value = '';
        setDropdownEnabled('vehicleFuelInput', false);
        
        // Show loading state
        fuelInput.placeholder = 'Loading fuel types...';
        fuelIcon.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#CB122D]"></div>';
        
        fuelList.innerHTML = '<div class="col-span-3 text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div><p class="text-gray-500 text-sm mt-2">Loading fuel types...</p></div>';
        
        // Make AJAX call using fetch
        const formData = new FormData();
        formData.append('action', 'get_fuel_types');
        formData.append('car_make', carMake);
        formData.append('car_model', carModel);
        
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            if (response.success && response.data.fuel_types && response.data.fuel_types.length > 0) {
                let html = '';
                const defaultSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" class="md:size-12 size-8 text-[#CB122D]" viewBox="0 0 23 24" fill="none"><g clip-path="url(#clip0_2808_4102)"><path d="M22.3773 20.1645C22.671 19.1058 23.4307 15.9298 23.3604 14.7287C23.2136 12.438 20.3409 6.96505 20.2323 6.75456C19.9068 6.01162 18.9939 5.69588 18.2661 6.04877C17.6086 6.36452 17.3213 7.1446 17.6086 7.81324L19.2428 11.4846H19.1343C18.2278 11.4846 17.5 12.1903 17.5 13.0695V15.1125C16.4786 15.9236 15.8657 17.1556 15.8657 18.431V20.1583C15.6104 20.3007 15.4316 20.5483 15.4316 20.8641V22.8019C15.4316 23.26 15.7955 23.6129 16.2679 23.6129H22.2305C22.7029 23.6129 23.0668 23.26 23.0668 22.8019V20.8641C23.0285 20.5483 22.7412 20.2264 22.3773 20.1583V20.1645ZM16.5935 18.5053C16.5935 17.3785 17.1361 16.3198 18.0873 15.645C18.1959 15.5769 18.2342 15.4716 18.2342 15.3602V13.1376C18.2342 12.6423 18.6683 12.2584 19.1407 12.2584C19.7216 12.2584 19.977 12.6113 20.0472 13.1376C20.0855 13.3852 20.0472 13.28 20.0472 16.1712C20.0472 16.3446 20.194 16.5241 20.4111 16.5241C20.6281 16.5241 20.775 16.3508 20.775 16.1712V13.1376C20.775 12.6794 20.5579 12.2213 20.1557 11.9056L18.2661 7.56559C18.1193 7.24985 18.2661 6.85981 18.5917 6.68646C18.9556 6.5131 19.3577 6.64931 19.5365 7.03935C19.5748 7.07649 22.4475 12.6113 22.5944 14.7658C22.6646 15.8617 21.8666 19.1058 21.6113 20.0592H16.5552V18.5053H16.5935ZM21.2155 22.3128C20.8899 22.3128 20.5962 22.028 20.5962 21.7123C20.5962 21.3965 20.8899 21.1117 21.2155 21.1117C21.541 21.1117 21.8347 21.3965 21.8347 21.7123C21.8347 22.028 21.5793 22.3128 21.2155 22.3128Z" fill="currentColor"></path><path d="M7.50174 20.0895V18.3621C7.50174 17.093 6.8825 15.8548 5.86746 15.0437V13.0007C5.86746 12.0101 4.92264 11.3043 4.11827 11.4157L5.72063 7.71346C6.01429 7.04482 5.72063 6.26474 5.06309 5.94899C4.33532 5.5961 3.42881 5.91185 3.13515 6.61763C3.02662 6.85908 0.153867 12.332 0.000653554 14.5856C-0.0695694 15.7867 0.690115 18.9627 0.983774 20.0214C0.581589 20.0895 0.294313 20.4114 0.294313 20.7953V22.7331C0.294313 23.1912 0.658195 23.5441 1.1306 23.5441H7.09317C7.56558 23.5441 7.92946 23.1912 7.92946 22.7331V20.7953C7.92946 20.4795 7.75071 20.1947 7.49535 20.0895H7.50174ZM0.734802 14.6227C0.881632 12.5054 3.75439 6.97052 3.79269 6.89623C3.93952 6.54334 4.37363 6.36999 4.73751 6.58049C5.06309 6.72288 5.20992 7.10673 5.06309 7.42867L3.17345 11.7686C2.80957 12.0534 2.55421 12.5116 2.55421 13.0007V16.0343C2.55421 16.2076 2.70104 16.3872 2.9181 16.3872C3.13515 16.3872 3.28198 16.2138 3.28198 16.0343V13.0688C3.3522 12.4001 3.75439 12.1525 4.18849 12.1525C4.6992 12.1525 5.09501 12.5425 5.09501 13.0316V15.2542C5.09501 15.3595 5.13331 15.4647 5.24184 15.539C6.18665 16.2076 6.73567 17.2663 6.73567 18.3993V19.9533H1.67962C1.46257 18.9627 0.658195 15.7185 0.734802 14.6289V14.6227ZM2.15203 22.1697C1.82645 22.1697 1.53279 21.8849 1.53279 21.5691C1.53279 21.2534 1.82645 20.9686 2.15203 20.9686C2.47761 20.9686 2.77127 21.2534 2.77127 21.5691C2.73296 21.8849 2.47761 22.1697 2.15203 22.1697Z" fill="currentColor"></path><path d="M5.93819 2.94642C6.08502 3.08882 6.30207 3.08882 6.4489 2.94642C6.59573 2.80403 6.59573 2.59353 6.4489 2.45113L5.42748 1.46056C5.28065 1.31816 5.06359 1.31816 4.91676 1.46056C4.76993 1.60295 4.76993 1.81345 4.91676 1.95584L5.93819 2.94642Z" fill="currentColor"></path><path d="M6.80563 1.85092C6.84393 2.02427 7.06098 2.16666 7.23973 2.09856C7.41848 2.06142 7.56531 1.85092 7.49509 1.67757L7.13121 0.265997C7.0929 0.0926462 6.87585 -0.0497491 6.6971 0.018353C6.51835 0.0554996 6.37152 0.265997 6.44175 0.439348L6.80563 1.85092Z" fill="currentColor"></path><path d="M3.64486 3.65165L5.10039 4.00454C5.27914 4.04169 5.50258 3.93644 5.5345 3.7569C5.56642 3.57736 5.46427 3.36686 5.27914 3.3359L3.82361 2.98301C3.64486 2.94587 3.42142 3.05111 3.38951 3.23066C3.31928 3.40401 3.45973 3.58355 3.64486 3.65165Z" fill="currentColor"></path><path d="M17.3205 2.94618C17.4674 3.08857 17.6844 3.08857 17.8312 2.94618L18.8144 1.9556C18.9612 1.8132 18.9612 1.60271 18.8144 1.46031C18.6675 1.31792 18.4505 1.31792 18.3037 1.46031L17.2822 2.45089C17.1737 2.55614 17.1737 2.80378 17.3205 2.94618Z" fill="currentColor"></path><path d="M16.5238 2.09852C16.7025 2.13567 16.926 2.03042 16.9579 1.85088L17.3218 0.439304C17.3601 0.265954 17.2515 0.0492651 17.0664 0.0183096C16.8813 -0.0126459 16.6642 0.0864117 16.6323 0.265954L16.2684 1.67752C16.1982 1.85088 16.3386 2.06756 16.5238 2.09852Z" fill="currentColor"></path><path d="M18.2347 3.75806C18.273 3.93141 18.4901 4.07381 18.6688 4.0057L20.1244 3.65281C20.3031 3.61567 20.4499 3.40517 20.3797 3.23182C20.3414 3.05847 20.1243 2.91607 19.9456 2.98417L18.4901 3.33707C18.3113 3.37421 18.1645 3.54756 18.2347 3.75806Z" fill="currentColor"></path><path d="M16.74 4.07335C16.74 3.75761 16.4846 3.50996 16.159 3.47282C14.0843 3.29947 12.9224 2.48224 12.3415 1.956C12.0861 1.70835 11.6839 1.70835 11.4669 1.956C10.8859 2.48224 9.71768 3.29947 7.64929 3.47282C7.32372 3.50996 7.06836 3.75761 7.06836 4.07335V7.67038C7.06836 9.15006 7.64929 10.5988 8.81755 11.5832C9.61554 12.2889 10.5987 12.7842 11.7286 13.1681C11.8754 13.2052 12.0223 13.2052 12.1308 13.1681C13.2607 12.8152 14.2375 12.2889 15.0419 11.5832C16.1718 10.5926 16.7527 9.15006 16.7527 7.67038V4.07335H16.74ZM11.901 10.0044C10.4837 10.0044 9.31549 8.87765 9.31549 7.49703C9.31549 6.11642 10.4774 4.98964 11.901 4.98964C13.3246 4.98964 14.4865 6.11642 14.4865 7.49703C14.4865 8.87765 13.3246 10.0044 11.901 10.0044Z" fill="currentColor"></path><path d="M12.5913 6.61721C12.553 6.65435 11.538 7.63874 11.5699 7.60778C11.5316 7.57064 11.1358 7.18679 11.1677 7.21774C11.0209 7.07535 10.8038 7.07535 10.657 7.21774C10.5102 7.36014 10.5102 7.57064 10.657 7.71303L11.3145 8.35071C11.4614 8.49311 11.6784 8.49311 11.8253 8.35071L13.0956 7.11869C13.2425 6.97629 13.2425 6.76579 13.0956 6.6234C12.9488 6.481 12.7318 6.481 12.5849 6.6234L12.5913 6.61721Z" fill="currentColor"></path></g><defs><clipPath id="clip0_2808_4102"><rect width="23" height="24" fill="currentColor"></rect></clipPath></defs></svg>`;
                    
                response.data.fuel_types.forEach(function(fuelType) {
                    const fuelTypeName = fuelType.fuel_type || '';
                    if (fuelTypeName) {
                        const escapedFuelTypeName = escapeHtml(fuelTypeName);
                        html += `
                            <div class="flex flex-col gap-2 items-center cursor-pointer text-center" data-value="${escapedFuelTypeName}">
                                ${defaultSvg}
                                <p class="md:text-sm text-xs font-normal">${escapedFuelTypeName}</p>
                            </div>
                        `;
                    }
                });
                fuelList.innerHTML = html;
                fuelInput.placeholder = 'Fuel Type';
                setDropdownEnabled('vehicleFuelInput', true);
            } else {
                fuelList.innerHTML = '<div class="col-span-3 text-center py-8"><p class="text-gray-500 text-sm">No fuel types found</p></div>';
                fuelInput.placeholder = 'No fuel types found';
            }
            fuelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
        })
        .catch(function() {
            fuelList.innerHTML = '<div class="col-span-3 text-center py-8"><p class="text-gray-500 text-sm">Failed to load fuel types</p></div>';
            fuelInput.placeholder = 'Failed to load fuel types';
            fuelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
        });
    }
    
    // Setup dropdowns
    const allVehicleDropdowns = [];
    
    function closeAllVehicleDropdowns(exceptId) {
        allVehicleDropdowns.forEach(function(dropdown) {
            if (dropdown.dropdownId !== exceptId) {
                dropdown.dropdown.classList.add('hidden');
                dropdown.input.style.backgroundColor = '#fff';
                dropdown.input.style.color = dropdown.input.disabled ? 'rgba(0, 0, 0, 0.5)' : '#000';
            }
        });
    }
    
    function setupVehicleDropdown({inputId, dropdownId, itemSelector, searchId = null, iconId = null, enabled = true}) {
        const input = $('#' + inputId);
        const dropdown = $('#' + dropdownId);
        const search = searchId ? $('#' + searchId) : null;
        const icon = iconId ? $('#' + iconId) : null;
        
        if (!input || !dropdown) return;
        
        allVehicleDropdowns.push({
            dropdownId: dropdownId,
            inputId: inputId,
            input: input,
            dropdown: dropdown,
            icon: icon,
            enabled: enabled
        });
        
        if (!enabled) {
            setDropdownEnabled(inputId, false);
        }
        
        // Input click toggle
        input.addEventListener('click', function(e) {
            e.stopPropagation();
            if (input.disabled) {
                return;
            }
            
            const isOpen = !dropdown.classList.contains('hidden');
            if (!isOpen) {
                closeAllVehicleDropdowns(dropdownId);
            }
            
            dropdown.classList.toggle('hidden');
            
            // if (!isOpen) {
            //     input.style.backgroundColor = '#650916';
            //     input.style.color = '#fff';
            // } else {
            //     input.style.backgroundColor = '#fff';
            //     input.style.color = '#000';
            // }
        });
        
        // Outside click close
        document.addEventListener('click', function(e) {
            var clickedOutside = true;
            allVehicleDropdowns.forEach(function(dropdownItem) {
                if (dropdownItem.dropdown.contains(e.target) || 
                    dropdownItem.input === e.target || 
                    dropdownItem.dropdown === e.target) {
                    clickedOutside = false;
                }
            });
            
            if (clickedOutside) {
                closeAllVehicleDropdowns(null);
            }
        });
        
        // Dropdown item click (event delegation)
        dropdown.addEventListener('click', function(e) {
            const clickedItem = e.target.closest(itemSelector);
            if (!clickedItem) return;
            
            const value = clickedItem.getAttribute('data-value');
            input.value = value;
            input.style.backgroundColor = '#fff';
            input.style.color = '#000';
            dropdown.classList.add('hidden');
            
            // Enable next dropdown based on selection
            if (inputId === 'vehicleBrandInput') {
                fetchVehicleModels(value);
                setDropdownEnabled('vehicleFuelInput', false);
            } else if (inputId === 'vehicleModelInput') {
                const selectedBrand = $('#vehicleBrandInput').value;
                fetchVehicleFuelTypes(selectedBrand, value);
            }
        });
        
        // Search filter
        if (search) {
            search.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const items = dropdown.querySelectorAll(itemSelector);
                items.forEach(function(item) {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.indexOf(searchTerm) > -1 ? '' : 'none';
                });
            });
        }
    }
    
    // Initialize dropdowns
    setupVehicleDropdown({
        inputId: 'vehicleBrandInput',
        dropdownId: 'vehicleBrandDropdown',
        itemSelector: '[data-value]',
        searchId: 'vehicleBrandSearch',
        iconId: 'vehicleBrandIcon',
        enabled: true
    });
    
    setupVehicleDropdown({
        inputId: 'vehicleModelInput',
        dropdownId: 'vehicleModelDropdown',
        itemSelector: '[data-value]',
        searchId: 'vehicleModelSearch',
        iconId: 'vehicleModelIcon',
        enabled: <?php echo !empty($brand) ? 'true' : 'false'; ?>
    });
    
    setupVehicleDropdown({
        inputId: 'vehicleFuelInput',
        dropdownId: 'vehicleFuelType',
        itemSelector: '[data-value]',
        iconId: 'vehicleFuelIcon',
        enabled: <?php echo (!empty($brand) && !empty($model)) ? 'true' : 'false'; ?>
    });
    
    // If brand is already selected, fetch models
    <?php if (!empty($brand)) : ?>
        fetchVehicleModels('<?php echo esc_js($brand); ?>');
    <?php endif; ?>
    
    // If model is already selected, fetch fuel types
    <?php if (!empty($brand) && !empty($model)) : ?>
        fetchVehicleFuelTypes('<?php echo esc_js($brand); ?>', '<?php echo esc_js($model); ?>');
    <?php endif; ?>
    
    // Handle Confirm Vehicle Update button
    const confirmVehicleUpdateBtn = $('#confirmVehicleUpdateBtn');
    if (confirmVehicleUpdateBtn) {
        confirmVehicleUpdateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const brand = $('#vehicleBrandInput').value.trim();
            const model = $('#vehicleModelInput').value.trim();
            const fuel = $('#vehicleFuelInput').value.trim();
            const city = '<?php echo esc_js($city); ?>';
            
            if (!brand || !model || !fuel) {
                alert('Please select Brand, Model, and Fuel Type');
                return;
            }
            
            // Clear cart when vehicle is updated
            clearCart();
            
            // Build query string
            const params = new URLSearchParams();
            if (city) params.append('city', encodeURIComponent(city));
            params.append('brand', encodeURIComponent(brand));
            params.append('model', encodeURIComponent(model));
            params.append('fuel', encodeURIComponent(fuel));
            
            // Redirect to same page with updated query params
            const redirectUrl = currentUrl + '?' + params.toString();
            window.location.href = redirectUrl;
        });
    }
    
    // View More/Less functionality for service details
    const viewMoreButtons = document.querySelectorAll('.view-more-btn');
    const viewLessButtons = document.querySelectorAll('.view-less-btn');
    
    viewMoreButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const serviceDetailsId = this.getAttribute('data-service-details');
            const hiddenDetails = document.querySelectorAll('.service-detail-item[data-service-details="' + serviceDetailsId + '"].hidden');
            const viewMoreBtn = document.querySelector('.view-more-btn[data-service-details="' + serviceDetailsId + '"]');
            const viewLessBtn = document.querySelector('.view-less-btn[data-service-details="' + serviceDetailsId + '"]');
            
            // Show all hidden details
            hiddenDetails.forEach(function(detail) {
                detail.classList.remove('hidden');
            });
            
            // Toggle buttons
            if (viewMoreBtn) viewMoreBtn.classList.add('hidden');
            if (viewLessBtn) viewLessBtn.classList.remove('hidden');
        });
    });
    
    viewLessButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const serviceDetailsId = this.getAttribute('data-service-details');
            const allDetails = document.querySelectorAll('.service-detail-item[data-service-details="' + serviceDetailsId + '"]');
            const viewMoreBtn = document.querySelector('.view-more-btn[data-service-details="' + serviceDetailsId + '"]');
            const viewLessBtn = document.querySelector('.view-less-btn[data-service-details="' + serviceDetailsId + '"]');
            
            // Hide details after first 5
            allDetails.forEach(function(detail, index) {
                if (index >= 5) {
                    detail.classList.add('hidden');
                }
            });
            
            // Toggle buttons
            if (viewMoreBtn) viewMoreBtn.classList.remove('hidden');
            if (viewLessBtn) viewLessBtn.classList.add('hidden');
        });
    });
    
    // Cart functionality with sessionStorage
    const CART_STORAGE_KEY = 'cost_estimator_cart';
    const imgUrl = '<?php echo esc_js($img_url); ?>';
    
    // Get current vehicle info for cart validation
    const currentVehicle = {
        brand: '<?php echo esc_js($brand); ?>',
        model: '<?php echo esc_js($model); ?>',
        fuel: '<?php echo esc_js($fuel); ?>',
        city: '<?php echo esc_js($city); ?>'
    };
    
    // Function to get cart from sessionStorage
    function getCart() {
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                const cart = JSON.parse(cartData);
                // Validate cart against current vehicle
                if (cart.vehicle && 
                    cart.vehicle.brand === currentVehicle.brand &&
                    cart.vehicle.model === currentVehicle.model &&
                    cart.vehicle.fuel === currentVehicle.fuel) {
                    return cart;
                } else {
                    // Clear cart if vehicle changed
                    clearCart();
                    return { vehicle: currentVehicle, items: [] };
                }
            }
        } catch (e) {
            console.error('Error loading cart:', e);
        }
        return { vehicle: currentVehicle, items: [] };
    }
    
    // Function to save cart to sessionStorage
    function saveCart(cart) {
        try {
            cart.vehicle = currentVehicle;
            sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {
            console.error('Error saving cart:', e);
        }
    }
    
    // Function to clear cart
    function clearCart() {
        sessionStorage.removeItem(CART_STORAGE_KEY);
    }
    
    // Function to add service to cart
    function addToCart(serviceId, serviceData) {
        const cart = getCart();
        
        // Check if service already exists
        const existingIndex = cart.items.findIndex(item => item.id === serviceId);
        if (existingIndex === -1) {
            // Parse service data if it's a string
            let serviceObj = serviceData;
            if (typeof serviceData === 'string') {
                try {
                    serviceObj = JSON.parse(serviceData);
                } catch (e) {
                    console.error('Error parsing service data:', e);
                    return;
                }
            }
            
            // Save complete service data
            cart.items.push({
                id: serviceId,
                ...serviceObj // Spread all service properties
            });
            saveCart(cart);
            renderCart();
            updateButtonStates();
        }
    }
    
    // Function to remove service from cart
    function removeFromCart(serviceId) {
        const cart = getCart();
        cart.items = cart.items.filter(item => item.id !== serviceId);
        saveCart(cart);
        renderCart();
        updateButtonStates();
    }
    
    // Function to check if service is in cart
    function isServiceInCart(serviceId) {
        const cart = getCart();
        return cart.items.some(item => item.id === serviceId);
    }
    
    // Function to update button states (Add to Cart / Added)
    function updateButtonStates() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        const cart = getCart();
        
        addToCartButtons.forEach(function(btn) {
            const serviceId = btn.getAttribute('data-service-id');
            const isInCart = cart.items.some(item => item.id === serviceId);
            
            if (isInCart) {
                // Change to "Added" button
                btn.className = 'add-to-cart-btn flex items-center gap-2 text-sm font-semibold text-[#4CAF50] bg-[#F1FAF1] h-[2.5rem] px-3 py-2 border border-transparent hover:border-[#4CAF50] duration-500';
                btn.innerHTML = `
                    <span>
                        <svg class="size-[1.125rem]" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path d="M16.6599 4.99792L7.497 14.1609L3.33203 9.99589" stroke="#4CAF50" stroke-width="2.49898" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    Added
                `;
            } else {
                // Change to "Add to Cart" button
                btn.className = 'add-to-cart-btn flex flex-row-reverse items-center gap-2 text-sm font-semibold text-white bg-[#CB122D] h-[2.5rem] px-3 py-2 border border-transparent hover:bg-[#650916] duration-500';
                btn.innerHTML = `
                    <span>
                        <img class="size-[0.875rem]" width="14" height="14" src="${imgUrl}plusWhiteIcon.svg" alt="Add to Cart Icon" />
                    </span>
                    Add to Cart
                `;
            }
        });
    }
    
    // Function to format price
    function formatPrice(price, currency) {
        const symbol = currency === 'INR' ? '₹' : currency;
        return symbol + ' ' + parseFloat(price).toLocaleString('en-IN');
    }
    
    // Function to render cart
    function renderCart() {
        const cart = getCart();
        const cartItemsContainer = $('#cartItemsContainer');
        const cartItemCount = $('#cartItemCount');
        const cartItemCountPlural = $('#cartItemCountPlural');
        const cartSubtotal = $('#cartSubtotal');
        
        if (!cartItemsContainer) return;
        
        // Clear existing items
        cartItemsContainer.innerHTML = '';
        
        // Render cart items
        if (cart.items.length > 0) {
            cart.items.forEach(function(item) {
                // Use service_name if available, otherwise fallback to name
                const serviceName = item.service_name || item.name || 'Service';
                const servicePrice = item.price || 0;
                const serviceCurrency = item.currency || 'INR';
                
                const itemHtml = `
                    <div class="w-full flex flex-row justify-between bg-white border border-[#EFEFEF] p-6" data-cart-item-id="${item.id}">
                        <div class="flex flex-col gap-2">
                            <div class="text-[#1A1A1A] lg:text-lg md:text-md text-base font-semibold">
                                ${escapeHtml(serviceName)}
                            </div>
                            <div class="flex items-center md:gap-6 gap-4">
                                <div class="text-[#121212] lg:text-lg md:text-md text-base font-bold">
                                    ${formatPrice(servicePrice, serviceCurrency)}
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="remove-cart-item bg-transparent p-0 m-0 border-0 text-[#CB122D] hover:text-[#650916] duration-500 inline-flex" data-service-id="${item.id}">
                                <img src="${imgUrl}deleteIcon.svg" alt="Remove Icon" class="size-5 shrink-0" width="20" height="20" />
                            </button>
                        </div>
                    </div>
                `;
                cartItemsContainer.innerHTML += itemHtml;
            });
        } else {
            // Show empty cart state
            cartItemsContainer.innerHTML = `
                
                                    <div class="border-t border-[#EFEFEF] pt-6">
                                       <div class="w-full flex flex-col gap-y-4 text-center mx-auto py-6">
                                           <div class="bg-[#F7F7F7] size-20 rounded-full flex items-center justify-center mx-auto">
                                                <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M20.1749 7.58028C19.9001 7.86066 19.7462 8.23762 19.7462 8.63024C19.7462 9.02285 19.9001 9.39981 20.1749 9.68019L22.5748 12.0801C22.8552 12.3549 23.2322 12.5089 23.6248 12.5089C24.0174 12.5089 24.3944 12.3549 24.6748 12.0801L29.3336 7.42278C29.8135 6.9398 30.628 7.0928 30.808 7.74977C31.2612 9.39802 31.2356 11.1412 30.7342 12.7755C30.2328 14.4097 29.2763 15.8673 27.9767 16.9778C26.6771 18.0882 25.0881 18.8056 23.3957 19.0459C21.7033 19.2862 19.9774 19.0396 18.42 18.3348L6.55548 30.1994C5.95877 30.7959 5.14953 31.1309 4.30579 31.1308C3.46205 31.1306 2.65293 30.7953 2.05641 30.1986C1.4599 29.6019 1.12486 28.7927 1.125 27.9489C1.12514 27.1052 1.46045 26.2961 2.05716 25.6995L13.9217 13.835C13.2169 12.2777 12.9703 10.5518 13.2106 8.85935C13.4509 7.16692 14.1683 5.57793 15.2788 4.27833C16.3892 2.97873 17.8469 2.02227 19.4811 1.52086C21.1153 1.01945 22.8585 0.993842 24.5068 1.44702C25.1637 1.62702 25.3167 2.43998 24.8352 2.92296L20.1749 7.58028Z" stroke="#AFAFAF" stroke-width="2.24991" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>                                                 
                                           </div>
                                          <div class="w-full flex flex-col gap-y-1">
                                            <h3 class="text-[#6F6F6F] text-sm font-normal">
                                                Your cart is empty
                                               </h3>
                                               <p class="text-[#AFAFAF] text-xs font-normal">Add services to get started</p>
                                          </div>
                                       </div>
                                    </div>
            `;
        }
        
        // Update count and subtotal
        const itemCount = cart.items.length;
        const subtotal = cart.items.reduce((sum, item) => sum + (item.price || 0), 0);
        
        // Show/hide subtotal section and checkout button based on cart items
        const cartSubtotalSection = $('#cartSubtotalSection');
        const checkoutButtonSection = $('#checkoutButtonSection');
        const mobileCartSummary = $('#mobileCartSummary');
        const mobileCartItemCount = $('#mobileCartItemCount');
        const mobileCartItemText = $('#mobileCartItemText');
        const mobileCartTotal = $('#mobileCartTotal');
        
        if (itemCount > 0) {
            // Show subtotal and checkout sections
            if (cartSubtotalSection) cartSubtotalSection.classList.remove('hidden');
            if (checkoutButtonSection) checkoutButtonSection.classList.remove('hidden');
            if (mobileCartSummary) mobileCartSummary.classList.remove('hidden');
            
            // Update count and subtotal
            if (cartItemCount) {
                cartItemCount.textContent = itemCount;
            }
            if (cartItemCountPlural) {
                cartItemCountPlural.textContent = itemCount === 1 ? '' : 's';
            }
            if (cartSubtotal) {
                cartSubtotal.textContent = formatPrice(subtotal, 'INR');
            }
            
            // Update mobile cart summary
            if (mobileCartItemCount) {
                mobileCartItemCount.textContent = itemCount;
            }
            if (mobileCartItemText) {
                mobileCartItemText.textContent = itemCount === 1 ? 'item' : 'items';
            }
            if (mobileCartTotal) {
                mobileCartTotal.textContent = formatPrice(subtotal, 'INR');
            }
        } else {
            // Hide subtotal and checkout sections
            if (cartSubtotalSection) cartSubtotalSection.classList.add('hidden');
            if (checkoutButtonSection) checkoutButtonSection.classList.add('hidden');
            if (mobileCartSummary) mobileCartSummary.classList.add('hidden');
        }
        
        // Add event listeners to remove buttons
        const removeButtons = document.querySelectorAll('.remove-cart-item');
        removeButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const serviceId = this.getAttribute('data-service-id');
                removeFromCart(serviceId);
            });
        });
    }
    
    // Add to Cart button functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-service-id');
            const serviceData = this.getAttribute('data-service-data');
            
            if (!serviceId || !serviceData) {
                console.error('Missing service data');
                return;
            }
            
            // Check if already in cart
            const cart = getCart();
            const isInCart = cart.items.some(item => item.id === serviceId);
            
            if (isInCart) {
                // Remove from cart
                removeFromCart(serviceId);
            } else {
                // Add to cart
                addToCart(serviceId, serviceData);
            }
        });
    });
    
    
    // Initial cart render and button states update
    renderCart();
    updateButtonStates();
});
</script>

<?php get_footer(); ?>