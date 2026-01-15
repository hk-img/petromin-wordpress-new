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

// Get verify page URL
function get_verify_page_url() {
    // Try different template name formats
    $template_names = array('verify.php', 'page-verify.php');
    
    foreach ($template_names as $template_name) {
        $verify_page = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $template_name,
            'number' => 1
        ));
        if (!empty($verify_page)) {
            return get_permalink($verify_page[0]->ID);
        }
    }
    
    // Fallback: try to find page by slug or title
    $verify_page = get_page_by_path('verify');
    if ($verify_page) {
        return get_permalink($verify_page->ID);
    }
    
    return '#';
}
$verify_page_url = get_verify_page_url();

// Get Supabase API key from wp-config.php constant
$supabase_api_key = defined('SUPABASE_API_KEY') ? SUPABASE_API_KEY : '';

// Fetch service categories from API
$categories_api_url = 'https://ryehkyasumhivlakezjb.supabase.co/rest/v1/rpc/get_unique_service_category';
$categories_response = wp_remote_get($categories_api_url, array(
    'timeout' => 15,
    'headers' => array(
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'apikey' => $supabase_api_key
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
    // Get Supabase API key from wp-config.php constant
    $supabase_api_key = defined('SUPABASE_API_KEY') ? SUPABASE_API_KEY : '';
    
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
            'apikey' => $supabase_api_key
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
        'apikey' => $supabase_api_key
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

<section class="bg-white md:py-14 pt-5 pb-24 max-md:overflow-hidden">
    <div class="w-full flex flex-col gap-y-6">
        <div class="w-full group/services">
            <div class="md:border-b md:border-[#E5E7EB] max-md:shadow-[0px_2px_4px_-2px_#0000001A,0px_4px_6px_-1px_#0000001A]">
                <div class="view w-full flex whitespace-nowrap max-md:overflow-x-auto max-md:gap-5">
                    <?php 
                    if (!empty($service_categories)) {
                        $first_category = true;
                        foreach ($service_categories as $index => $category_item) {
                            $category_name = isset($category_item['service_category']) ? $category_item['service_category'] : '';
                            if (empty($category_name)) continue;
                            
                            $category_slug = get_category_slug($category_name);
                            $category_id = $category_slug . 'Service';
                            ?>
                            <label for="<?php echo esc_attr($category_id); ?>" class="group/serviceTab cursor-pointer md:py-4 py-2 md:px-10 px-2 flex flex-col items-center md:gap-3 gap-1 justify-center text-sm font-medium border-b-4 border-transparent has-[:checked]:border-[#980D22] has-[:checked]:font-bold has-[:checked]:text-[#CB122D]">
                                <input type="radio" name="services" id="<?php echo esc_attr($category_id); ?>" class="hidden" <?php echo $first_category ? 'checked' : ''; ?> />
                                <span class="bg-gradient-to-br from-[#F3F4F6] to-[#F3F4F6] group-has-[:checked]/serviceTab:from-[#CB122D] group-has-[:checked]/serviceTab:to-[#980D22] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A] size-[3.438rem] rounded-full flex justify-center items-center">
                                    <img src="<?php echo esc_url($img_url . 'carServiceIcon.webp'); ?>" class="brightness-[0.4] group-has-[:checked]/serviceTab:brightness-[1] size-7" alt="<?php echo esc_attr($category_name . ' Icon'); ?>" width="28" height="28" />
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
            <div class="view w-full lg:pt-12 md:pt-8 pt-5">
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
                                
                                // Fetch vendor distinct services data for warranty, recommended_timeline, is_offer, and estimated_completion_time
                                $vendor_services_api_url = 'https://ryehkyasumhivlakezjb.supabase.co/rest/v1/vendor_distinct_services?service_category=eq.' . urlencode($category_name) . '&select=service_name,warranty,recommended_timeline,is_active,is_offer,estimated_completion_time';
                                $vendor_services_response = wp_remote_get($vendor_services_api_url, array(
                                    'timeout' => 15,
                                    'headers' => array(
                                        'Content-Type' => 'application/json',
                                        'Accept' => 'application/json',
                                        'apikey' => $supabase_api_key
                                    )
                                ));
                                $vendor_services_lookup = array();
                                
                                if (!is_wp_error($vendor_services_response) && wp_remote_retrieve_response_code($vendor_services_response) === 200) {
                                    $vendor_services_data = json_decode(wp_remote_retrieve_body($vendor_services_response), true);
                                    if (is_array($vendor_services_data)) {
                                        foreach ($vendor_services_data as $vendor_service) {
                                            $vendor_service_name = isset($vendor_service['service_name']) ? $vendor_service['service_name'] : '';
                                            if (!empty($vendor_service_name)) {
                                                $vendor_services_lookup[$vendor_service_name] = $vendor_service;
                                            }
                                        }
                                    }
                                }
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
                                                
                                                // Get vendor service data for warranty, recommended_timeline, is_offer, and estimated_completion_time
                                                $vendor_service_data = isset($vendor_services_lookup[$service_name]) ? $vendor_services_lookup[$service_name] : null;
                                                $service_warranty_raw = ($vendor_service_data && isset($vendor_service_data['warranty'])) ? $vendor_service_data['warranty'] : 'No Warranty';
                                                
                                                // Format warranty text - if it already contains "Warranty" or is "No Warranty", use as is, otherwise add "Warranty" suffix
                                                $service_warranty = $service_warranty_raw;
                                                if (stripos($service_warranty_raw, 'warranty') === false) {
                                                    $service_warranty = $service_warranty_raw . ' Warranty';
                                                }
                                                
                                                $service_recommended_timeline = ($vendor_service_data && isset($vendor_service_data['recommended_timeline'])) ? $vendor_service_data['recommended_timeline'] : 'As Required';
                                                $service_estimated_time = ($vendor_service_data && isset($vendor_service_data['estimated_completion_time']) && !empty($vendor_service_data['estimated_completion_time'])) ? $vendor_service_data['estimated_completion_time'] : '4 Hours';
                                                $service_is_offer = ($vendor_service_data && isset($vendor_service_data['is_offer'])) ? (bool)$vendor_service_data['is_offer'] : false;
                                                $service_is_active = ($vendor_service_data && isset($vendor_service_data['is_active'])) ? (bool)$vendor_service_data['is_active'] : true;
                                                $service_card_class = 'w-full flex flex-col md:rounded-none rounded-lg bg-white border border-[#E5E7EB] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]';
                                                if (!$service_is_active) {
                                                    $service_card_class .= ' grayscale opacity-50 pointer-events-none';
                                                }
                                                ?>
                                                <div class="<?php echo esc_attr($service_card_class); ?>" data-service-id="<?php echo esc_attr($service_id); ?>">
                                                    <div class="flex md:flex-row flex-col md:gap-3">
                                                        <div class="md:w-1/4 w-full relative">
                                                            <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($img_url . 'ImageWithFallback.webp'); ?>"
                                                                class="size-full md:rounded-none rounded-lg aspect-square"
                                                                width="189" height="189" alt="" title="">
                                                            <?php if ($service_is_offer) : ?>
                                                            <div class="absolute md:-top-[3.2rem] -top-[1.188rem] md:-left-[3rem] -left-[1.25rem]">
                                                                <img fetchpriority="low" loading="lazy" src="<?php echo esc_url($img_url . 'limitedOffer.webp'); ?>"
                                                                    class="md:h-[14.75rem] h-[5.875rem] w-auto object-contain" alt="" title="">
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="md:w-3/4 w-full">
                                                            <div class="flex flex-col md:gap-y-6 gap-y-3 p-4">
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
                                                                        Estimated Time is <?php echo esc_html($service_estimated_time); ?>
                                                                    </li>
                                                                    <li
                                                                        class="border border-[#DF7300] bg-[#FF83000D] rounded-md py-2 px-3 flex gap-1 items-center font-medium text-[#DF7300] text-xs">
                                                                        <span>
                                                                            <img src="<?php echo esc_url($img_url . 'warrnetyIcon.svg'); ?>" 
                                                                                class="size-[0.875rem]" 
                                                                                width="14" 
                                                                                height="14" 
                                                                                alt="Warranty Icon" />
                                                                        </span>
                                                                        <?php echo esc_html($service_warranty); ?>
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
                                                                        Recommended <?php echo esc_html($service_recommended_timeline); ?>
                                                                    </li>
                                                                </ul>
                                                                <ul class="flex flex-wrap justify-between md:gap-y-3 gap-y-2 items-center">
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
                                                    <div class="w-full flex flex-col gap-y-2 md:p-6 py-3 px-6 border-t border-[#E5E7EB]">
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
                                                                    class="add-to-cart-btn flex flex-row-reverse items-center gap-2 text-sm font-semibold text-white bg-[#CB122D] h-[2.5rem] px-3 py-2 border border-transparent hover:bg-[#650916] duration-500 max-md:rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-400"
                                                                    data-service-id="<?php echo esc_attr($service_id); ?>"
                                                                    data-service-data="<?php echo esc_attr($service_data_json); ?>"
                                                                    <?php if (!$service_is_active) : ?>disabled<?php endif; ?>>
                                                                    <span>
                                                                        <img class="size-[0.875rem]"  width="14" height="14" src="<?php echo esc_url($img_url . 'plusWhiteIcon.svg'); ?>" alt="Add to Cart Icon" />
                                                                    </span>
                                                                    Add to Cart
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <p class="text-xs md:text-[#000000] text-[#8B8B8B] font-normal flex gap-2">
                                                            <span class="inline-flex pt-1">
                                                                <img src="<?php echo esc_url($img_url . 'estimatedIcon.svg'); ?>" 
                                                                    class="size-[0.875rem] shrink-0 max-md:opacity-50" 
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
                            class="w-full flex flex-col bg-white shadow-[0_0.125rem_0.25rem_-0.125rem_#919191] border border-[#E5E5E5] md:sticky top-20 h-[calc(100dvh-90px)] overflow-y-auto overflow-x-hidden">
                            <div
                                class="w-full flex items-center h-[3.125rem] p-6 bg-gradient-to-l  from-[#CB122D] to-[#650916] lg:text-xl md:text-lg text-base font-bold italic text-white uppercase sticky top-0 z-10">
                                Your Cart
                            </div>
                            <div id="cartContentSection" class="flex flex-col gap-y-6 bg-white p-6">
                                <div id="vehicleDisplaySection" class="w-full flex flex-row justify-between bg-white border border-[#EFEFEF] p-6">
                                    <div class="flex flex-row gap-2">
                                        <span id="vehicleDisplayImageSpan" class="bg-gradient-to-br from-[#CB122D] to-[#980D22] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A] size-[3.438rem] rounded-full flex justify-center items-center group/model [&.active]:bg-white [&.active]:[background-image:none]">
                                            <img id="vehicleDisplayImage" src="<?php echo esc_url($img_url . 'carServiceIcon.webp'); ?>" alt="<?php echo esc_attr(!empty($brand) ? $brand . ' Logo' : 'Vehicle'); ?>" class="size-[1.75rem] group-[.active]/model:size-full group-[.active]/model:object-contain" width="28" height="28" />
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
                                <div id="checkoutButtonSection" class="w-full hidden sticky bottom-6 z-10 before:absolute before:-inset-x-2 before:bottom-0 before:h-6 before:translate-y-full before:bg-white after:absolute after:-inset-x-2 after:top-0 after:h-2 after:-translate-y-full after:bg-gradient-to-t from-white to-transparent">
                                    <button type="button" id="desktopProceedToCheckoutBtn"
                                        class="h-[3.438rem] w-full bg-[#CB122D] text-base flex justify-center items-center text-white font-bold hover:bg-[#650916] duration-500 disabled:bg-gray-400 disabled:cursor-not-allowed relative">
                                        <span id="desktopProceedToCheckoutBtnText">Proceed to Checkout</span>
                                        <span id="desktopProceedToCheckoutBtnLoader" class="hidden absolute inset-0 flex items-center justify-center">
                                            <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                            <span>Processing...</span>
                                        </span>
                                    </button>
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
                                        class="w-full md:p-0 p-6 flex justify-center items-center sticky bottom-6 z-10 before:absolute before:-inset-x-2 before:bottom-0 before:h-6 before:translate-y-full before:bg-white after:absolute after:-inset-x-2 after:top-0 after:h-2 after:-translate-y-full after:bg-gradient-to-t from-white to-transparent">
                                        <button type="button" id="confirmVehicleUpdateBtn"
                                            class="h-[3.438rem] md:rounded-none rounded-lg w-full bg-[#1A1A1A] text-base flex justify-center items-center text-white font-bold hover:bg-[#650916] duration-500 disabled:bg-gray-400 disabled:cursor-not-allowed relative">
                                            <span id="confirmVehicleUpdateBtnText">Confirm Vehicle Update</span>
                                            <span id="confirmVehicleUpdateBtnLoader" class="hidden absolute inset-0 flex items-center justify-center">
                                                <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                                <span>Processing...</span>
                                            </span>
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

<div id="mobileCartSummary" class="view h-[4.938rem] group/check w-full fixed z-20 bottom-0 inset-x-0 md:hidden flex justify-between items-center gap-2 bg-white border border-[#E5E5E5] shadow-[0_-0.25rem_1rem_0_#00000014]">
    <div class="flex flex-col w-full">
        <div class="text-[#637083] text-xs font-normal">
            <span id="mobileCartItemCount">0</span> <span id="mobileCartItemText">item</span>
        </div>
        <div id="mobileCartTotal" class="text-[#121212] text-lg font-bold">₹0</div>
    </div>
    <div class="w-full flex justify-end">
        <button type="button" id="viewCartBtn" class="bg-[#CB122D] w-fit px-8 rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#650916]">
            View Cart
        </button>
    </div>
</div>


<div class="md:!hidden hidden cart_modal bg-white size-full group/cartPopup fixed inset-0 z-[100000] duration-500 flex flex-col items-center">
    <div class="border-y z-30 border-[#E5E5E5] p-6 md:hidden block relative w-full bg-white">
        <button type="button" id="closePopover" class="flex items-center justify-start gap-4 uppercase text-[#121212] text-lg font-medium">
            <span>
                <img src="<?php echo esc_url($img_url . 'mobileCartBackArrow.svg'); ?>" class="w-[0.563rem] h-[0.938rem]" width="9" height="15" alt="Back Arrow" />
            </span>
            Your Cart
        </button>
    </div>
    <div class="flex-1 w-full p-6 overflow-y-auto overflow-x-hidden"> 
        <div class="w-full flex flex-col gap-y-6">
            <div class="w-full flex flex-row justify-between bg-white border border-[#EFEFEF] rounded-xl shadow-[0_0.125rem_0.25rem_-0.125rem_rgba(0,0,0,0.10)] p-3">
                <div class="flex flex-row items-center gap-4">
                    <span id="mobileVehicleDisplayImageSpan">
                        <img id="mobileVehicleDisplayImage" src="<?php echo esc_url($img_url . 'mobileCartVehicleIcon.svg'); ?>" class="size-10 object-contain" width="28" height="28" alt="Vehicle Icon" />                      
                    </span>
                    <div class="flex flex-col gap-y-1">
                        <div class="text-[#A6A6A6] uppercase text-xs font-semibold">Your Vehicle</div>
                        <div id="mobileCartVehicleName" class="text-[#1A1A1A] lg:text-lg md:text-md text-base font-semibold">
                            <?php
                                $vehicle_title = trim($brand . ' ' . $model);
                                echo esc_html($vehicle_title !== '' ? $vehicle_title : 'Vehicle not selected');
                            ?>
                        </div>
                        <div id="mobileCartVehicleFuel" class="text-[#6F6F6F] text-sm font-nromal">
                            <?php
                                $fuel_label = !empty($fuel) ? $fuel : 'Fuel not selected';
                                echo esc_html($fuel_label);
                            ?>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" id="carChangePopupBtn" class="bg-transparent p-0 m-0  border-0 text-[#CB122D] font-semibold underline md:text-sm text-xs hover:text-[#650916] duration-500">Click to change
                    </button>
                </div>
            </div>
            <div id="mobileCartItemsContainer" class="w-full flex flex-col gap-y-4">
                <!-- Cart items will be loaded dynamically from sessionStorage -->
            </div>
        </div>
        <div id="mobileCartPriceBreakdown" class="w-full flex flex-col items-start gap-2 bg-[#FAFAFA] border border-[#EFEFEF] rounded-xl shadow-[0_0.125rem_0.25rem_-0.125rem_rgba(0,0,0,0.10)] p-3 mt-4 hidden">
            <div class="flex flex-col gap-2 w-full text-start">
                <h2 class="text-[#121212] font-bold text-base">Price Breakdown</h2>
                <div class="w-full flex justify-between items-center gap-2">
                    <div class="text-[#6B6B6B] text-sm font-medium">Subtotal (<span id="mobileCartSubtotalCount">0</span> service<span id="mobileCartSubtotalCountPlural">s</span>)</div>
                    <div id="mobileCartSubtotal" class="text-[#121212] text-base font-bold">₹0</div>
                </div>
            </div>
            <div class="flex flex-col gap-3 w-full text-start border-t border-[#E5E5E5] pt-3">
                <div class="w-full flex justify-between items-center gap-2">
                    <h2 class="text-[#121212] font-bold text-base">Total</h2>
                    <div id="mobileCartTotalPrice" class="text-[#CB122D] text-xl font-bold">₹0</div>
                </div>
                <p class="text-sm text-[#000000] font-normal flex gap-2">
                    <span class="inline-flex">
                        <img src="<?php echo esc_url($img_url . 'mobileCartInfoIcon.svg'); ?>" class="size-6 shrink-0" width="24" height="24" alt="Info Icon" />
                    </span>
                    This is an estimated price, Final price may vary based on your car
                    model and condition.
                </p>
            </div>
        </div>
    </div>
    <div class="h-[4.938rem] p-6 w-full flex justify-between items-center gap-2 bg-white border border-[#E5E5E5] shadow-[0_-0.25rem_1rem_0_#00000014]">
        <div class="flex flex-col w-full">
            <div class="text-[#637083] text-xs font-normal"><span id="mobileCartFooterItemCount">0</span> item<span id="mobileCartFooterItemCountPlural">s</span></div>
            <div id="mobileCartFooterTotal" class="text-[#121212] text-lg font-bold">₹0</div>
        </div>
        <div class="w-full flex justify-end">
            <button type="button" id="mobileProceedToCheckoutBtn" class="bg-[#CB122D] w-fit px-8 rounded-lg h-[2.875rem] flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D] disabled:bg-gray-400 disabled:cursor-not-allowed relative" disabled>
                <span id="mobileProceedToCheckoutBtnText">Checkout</span>
                <span id="mobileProceedToCheckoutBtnLoader" class="hidden flex items-center justify-center">
                    <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                    <span class="text-sm">Processing...</span>
                </span>
            </button>
        </div>
    </div>
</div>


<div id="mobileVehicleFormModal" class="md:!hidden hidden cart_modal bg-white size-full group/cartPopup fixed inset-0 z-[100000]  duration-500 flex flex-col items-center">
        <div class=" border-y z-30 border-[#E5E5E5] p-6 md:hidden block relative w-full bg-white ">
          <button type="button"  id="closeCarFormPopover" class="flex items-center justify-start gap-4 uppercase text-[#121212] text-lg font-medium">
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
        <div class="flex flex-col gap-y-6">
            <form action="" class="flex flex-col gap-y-6 border-t border-[#EFEFEF] pt-6">
                <!-- Car Brand Dropdown -->
                <div class="w-full relative">
                    <label for=""
                        class="font-semibold md:text-base text-sm text-[#6B6B6B]">Brand</label>
                    <div class="relative mt-2">
                        <input type="text" placeholder="Car Brand" id="mobileVehicleBrandInput" readonly=""
                            value="<?php echo esc_attr($brand); ?>"
                            class="w-full px-4 py-3 text-black bg-white border border-[#E3E3E3] placeholder:text-black xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-pointer md:rounded-none rounded-lg">
                        <span id="mobileVehicleBrandIcon"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805"
                                    stroke="#6B6B6B" stroke-width="1.33282"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>

                    <div id="mobileVehicleBrandDropdown"
                        class="absolute top-full left-0 w-full bg-white border border-gray-200 z-[100001] overflow-hidden hidden mt-1">
                        <div class="p-3">
                            <div class="flex items-center bg-gray-100 px-3 py-2 mb-2 rounded">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" id="mobileVehicleBrandSearch"
                                    placeholder="Search Car Brand"
                                    class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700" />
                            </div>

                            <div class="grid grid-cols-3 gap-4 max-h-56 overflow-y-auto"
                                id="mobileVehicleBrandList">
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
                            <div id="mobileVehicleBrandEmptyState" class="hidden text-center py-8">
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
                            id="mobileVehicleModelInput" readonly=""
                            value="<?php echo esc_attr($model); ?>"
                            class="w-full px-4 py-3 text-gray-800 bg-white border border-[#E3E3E3] placeholder:text-black/50 xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-pointer md:rounded-none rounded-lg" disabled>
                        <span id="mobileVehicleModelIcon"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805"
                                    stroke="#6B6B6B" stroke-width="1.33282"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>

                    <div id="mobileVehicleModelDropdown"
                        class="absolute top-full left-0 w-full bg-white border border-gray-200 z-[100001] overflow-hidden hidden mt-1">
                        <div class="p-3">
                            <div class="flex items-center bg-gray-100 px-3 py-2 mb-2 rounded">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" id="mobileVehicleModelSearch"
                                    placeholder="Search Car Model"
                                    class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700" />
                            </div>

                            <div class="grid grid-cols-2 gap-4 max-h-56 overflow-y-auto"
                                id="mobileVehicleModelList">
                                <!-- Models will be loaded dynamically via AJAX -->
                            </div>
                            <!-- Empty State -->
                            <div id="mobileVehicleModelEmptyState" class="hidden text-center py-8">
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
                        <input type="text" placeholder="Fuel Type" id="mobileVehicleFuelInput" readonly=""
                            value="<?php echo esc_attr($fuel); ?>"
                            class="w-full px-4 py-3 text-gray-800 bg-white border border-[#E3E3E3] placeholder:text-black/50 xl:placeholder:text-base xl:placeholder:text-sm focus:outline-none cursor-pointer md:rounded-none rounded-lg" disabled>
                        <span id="mobileVehicleFuelIcon"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805"
                                    stroke="#6B6B6B" stroke-width="1.33282"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>

                    <div id="mobileVehicleFuelType"
                        class="absolute top-full left-0 w-full bg-white border border-gray-200 z-[100001] overflow-hidden hidden mt-1">
                        <div class="p-3">
                            <div class="grid grid-cols-3 gap-4 max-h-56 overflow-y-auto"
                                id="mobileVehicleFuelList">
                                <!-- Fuel types will be loaded dynamically via AJAX -->
                            </div>
                            <!-- Empty State -->
                            <div id="mobileVehicleFuelEmptyState" class="hidden text-center py-8">
                                <p class="text-gray-500 text-sm">No fuel types found</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="bg-[#F9F9F9] border border-[#EFEFEF] text-[#6B6B6B] font-normal text-xs p-4 rounded-lg">
                                        Updating your vehicle will refresh service availability and pricing.
                                    </div> 
        </div>
        </div>
          <div
          class="h-[4.938rem] p-6 w-full flex justify-center items-center gap-2 bg-white border border-[#E5E5E5] shadow-[0_-0.25rem_1rem_0_#00000014]">
          <button type="button" id="mobileConfirmVehicleUpdateBtn"
          class="h-[3.438rem] md:rounded-none rounded-lg w-full bg-[#CB122D] text-base flex justify-center items-center text-white font-bold hover:bg-[#650916] duration-500 disabled:bg-gray-400 disabled:cursor-not-allowed relative">
          <span id="mobileConfirmVehicleUpdateBtnText">Confirm Vehicle Update</span>
          <span id="mobileConfirmVehicleUpdateBtnLoader" class="hidden absolute inset-0 flex items-center justify-center">
              <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
              <span>Processing...</span>
          </span>
      </button>
      </div>
      </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Define CART_STORAGE_KEY constant at the top of the script
    const CART_STORAGE_KEY = 'cost_estimator_cart';
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
    
    // Function to update vehicle display image
    function updateVehicleDisplayImage(imageUrl) {
        const defaultImage = '<?php echo esc_js($img_url . "carServiceIcon.webp"); ?>';
        const defaultMobileImage = '<?php echo esc_js($img_url . "mobileCartVehicleIcon.svg"); ?>';
        
        // Desktop vehicle image
        const vehicleImage = document.getElementById('vehicleDisplayImage');
        const vehicleImageSpan = document.getElementById('vehicleDisplayImageSpan');
        
        // Mobile vehicle image
        const mobileVehicleImage = document.getElementById('mobileVehicleDisplayImage');
        const mobileVehicleImageSpan = document.getElementById('mobileVehicleDisplayImageSpan');
        
        // Check if image is from API (not default/empty)
        const isApiImage = imageUrl && imageUrl.trim() !== '' && imageUrl !== '<?php echo esc_js($img_url . "car-brand.webp"); ?>' && imageUrl !== defaultImage;
        
        // Update desktop vehicle image
        if (vehicleImage && vehicleImageSpan) {
            // Use the provided image URL or default if empty/invalid
            const finalImageUrl = isApiImage ? imageUrl : defaultImage;
            vehicleImage.src = finalImageUrl;
            
            // Add or remove 'active' class based on whether image is from API
            if (isApiImage) {
                vehicleImageSpan.classList.add('active');
            } else {
                vehicleImageSpan.classList.remove('active');
            }
            
            vehicleImage.onerror = function() {
                this.src = defaultImage;
                // Remove active class if image fails to load
                vehicleImageSpan.classList.remove('active');
            };
        }
        
        // Update mobile vehicle image
        if (mobileVehicleImage && mobileVehicleImageSpan) {
            // Use the provided image URL or default if empty/invalid
            const finalMobileImageUrl = isApiImage ? imageUrl : defaultMobileImage;
            mobileVehicleImage.src = finalMobileImageUrl;
            
            // Add or remove 'active' class based on whether image is from API
            if (isApiImage) {
                mobileVehicleImageSpan.classList.add('active');
            } else {
                mobileVehicleImageSpan.classList.remove('active');
            }
            
            mobileVehicleImage.onerror = function() {
                this.src = defaultMobileImage;
                // Remove active class if image fails to load
                mobileVehicleImageSpan.classList.remove('active');
            };
        }
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
                            <div class="cursor-pointer text-center" data-value="${escapedModelName}" data-image="${escapeHtml(modelImage)}">
                                <img src="${modelImage}" alt="${escapedModelName}" class="w-full h-24 object-cover mb-1 rounded" loading="lazy" fetchpriority="low" onerror="this.src='<?php echo esc_js($img_url . "car-brand.webp"); ?>';" />
                                <p class="text-xs">${escapedModelName}</p>
                            </div>
                        `;
                    }
                });
                modelList.innerHTML = html;
                modelInput.placeholder = 'Car Model';
                setDropdownEnabled('vehicleModelInput', true);
                
                // If model is already selected from URL params, update the image
                const currentModel = '<?php echo esc_js($model); ?>';
                if (currentModel) {
                    const modelItem = modelList.querySelector('[data-value="' + escapeHtml(currentModel) + '"]');
                    if (modelItem) {
                        const modelImage = modelItem.getAttribute('data-image');
                        if (modelImage) {
                            updateVehicleDisplayImage(modelImage);
                        }
                    }
                }
            } else {
                modelList.innerHTML = '<div class="col-span-2 text-center py-8"><p class="text-gray-500 text-sm">No models found</p></div>';
                modelInput.placeholder = 'No models found';
            }
            modelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
            // Update button state after models are loaded
            updateConfirmButtonState();
        })
        .catch(function() {
            modelList.innerHTML = '<div class="col-span-2 text-center py-8"><p class="text-gray-500 text-sm">Failed to load models</p></div>';
            modelInput.placeholder = 'Failed to load models';
            modelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
            // Update button state on error
            updateConfirmButtonState();
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
            // Update button state after fuel types are loaded
            updateConfirmButtonState();
        })
        .catch(function() {
            fuelList.innerHTML = '<div class="col-span-3 text-center py-8"><p class="text-gray-500 text-sm">Failed to load fuel types</p></div>';
            fuelInput.placeholder = 'Failed to load fuel types';
            fuelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
            // Update button state on error
            updateConfirmButtonState();
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
                // Clear model and fuel when brand changes
                $('#vehicleModelInput').value = '';
                $('#vehicleFuelInput').value = '';
                // Reset vehicle image to default when brand changes
                updateVehicleDisplayImage('<?php echo esc_js($img_url . "carServiceIcon.webp"); ?>');
            } else if (inputId === 'vehicleModelInput') {
                const selectedBrand = $('#vehicleBrandInput').value;
                fetchVehicleFuelTypes(selectedBrand, value);
                // Clear fuel when model changes
                $('#vehicleFuelInput').value = '';
                // Update vehicle image when model is selected
                const modelImage = clickedItem.getAttribute('data-image');
                if (modelImage) {
                    updateVehicleDisplayImage(modelImage);
                } else {
                    updateVehicleDisplayImage('<?php echo esc_js($img_url . "carServiceIcon.webp"); ?>');
                }
            }
            
            // Update button state after value change
            updateConfirmButtonState();
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
    
    // Function to pre-select dropdowns from sessionStorage or URL parameters
    function preSelectVehicleDropdowns() {
        // First check sessionStorage
        let selectedBrand = '';
        let selectedModel = '';
        let selectedFuel = '';
        
        try {
            const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
            if (cartData) {
                const cart = JSON.parse(cartData);
                if (cart.vehicle) {
                    selectedBrand = cart.vehicle.brand || '';
                    selectedModel = cart.vehicle.model || '';
                    selectedFuel = cart.vehicle.fuel || '';
                }
            }
        } catch (e) {
            console.error('Error reading sessionStorage:', e);
        }
        
        // Fallback to URL parameters if sessionStorage doesn't have data
        if (!selectedBrand) {
            selectedBrand = '<?php echo esc_js($brand); ?>';
        }
        if (!selectedModel) {
            selectedModel = '<?php echo esc_js($model); ?>';
        }
        if (!selectedFuel) {
            selectedFuel = '<?php echo esc_js($fuel); ?>';
        }
        
        // Pre-select brand if available
        if (selectedBrand) {
            const brandInput = $('#vehicleBrandInput');
            if (brandInput) {
                brandInput.value = selectedBrand;
                brandInput.style.backgroundColor = '#fff';
                brandInput.style.color = '#000';
                
                // Fetch models for the selected brand
                fetchVehicleModels(selectedBrand);
                
                // After models are loaded, pre-select model if available
                if (selectedModel) {
                    // Check if models are already loaded, otherwise wait for them
                    const checkModelLoaded = () => {
                        const modelInput = $('#vehicleModelInput');
                        const modelList = $('#vehicleModelList');
                        if (modelInput && modelList && modelList.querySelector('[data-value]')) {
                            modelInput.value = selectedModel;
                            modelInput.style.backgroundColor = '#fff';
                            modelInput.style.color = '#000';
                            
                            // Update vehicle image if model item exists
                            const modelItem = modelList.querySelector('[data-value="' + escapeHtml(selectedModel) + '"]');
                            if (modelItem) {
                                const modelImage = modelItem.getAttribute('data-image');
                                if (modelImage) {
                                    updateVehicleDisplayImage(modelImage);
                                }
                            }
                            
                            // Fetch fuel types for the selected brand and model
                            fetchVehicleFuelTypes(selectedBrand, selectedModel);
                            
                            // After fuel types are loaded, pre-select fuel if available
                            if (selectedFuel) {
                                const checkFuelLoaded = () => {
                                    const fuelInput = $('#vehicleFuelInput');
                                    const fuelList = $('#vehicleFuelList');
                                    if (fuelInput && fuelList && fuelList.querySelector('[data-value]')) {
                                        fuelInput.value = selectedFuel;
                                        fuelInput.style.backgroundColor = '#fff';
                                        fuelInput.style.color = '#000';
                                        updateConfirmButtonState();
                                    } else {
                                        setTimeout(checkFuelLoaded, 100);
                                    }
                                };
                                setTimeout(checkFuelLoaded, 100);
                            }
                        } else {
                            setTimeout(checkModelLoaded, 100);
                        }
                    };
                    setTimeout(checkModelLoaded, 100);
                }
            }
        }
    }
    
    // If brand is already selected, fetch models
    <?php if (!empty($brand)) : ?>
        fetchVehicleModels('<?php echo esc_js($brand); ?>');
    <?php endif; ?>
    
    // If model is already selected, fetch fuel types
    <?php if (!empty($brand) && !empty($model)) : ?>
        fetchVehicleFuelTypes('<?php echo esc_js($brand); ?>', '<?php echo esc_js($model); ?>');
    <?php endif; ?>
    
    // Pre-select dropdowns from sessionStorage or URL parameters on page load
    preSelectVehicleDropdowns();
    
    // Function to update button state based on dropdown selections
    function updateConfirmButtonState() {
        const brand = $('#vehicleBrandInput').value.trim();
        const model = $('#vehicleModelInput').value.trim();
        const fuel = $('#vehicleFuelInput').value.trim();
        
        const confirmVehicleUpdateBtn = $('#confirmVehicleUpdateBtn');
        if (confirmVehicleUpdateBtn) {
            if (brand && model && fuel) {
                confirmVehicleUpdateBtn.disabled = false;
            } else {
                confirmVehicleUpdateBtn.disabled = true;
            }
        }
    }
    
    // Handle Confirm Vehicle Update button
    const confirmVehicleUpdateBtn = $('#confirmVehicleUpdateBtn');
    const confirmVehicleUpdateBtnText = document.getElementById('confirmVehicleUpdateBtnText');
    const confirmVehicleUpdateBtnLoader = document.getElementById('confirmVehicleUpdateBtnLoader');
    
    if (confirmVehicleUpdateBtn) {
        confirmVehicleUpdateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const brand = $('#vehicleBrandInput').value.trim();
            const model = $('#vehicleModelInput').value.trim();
            const fuel = $('#vehicleFuelInput').value.trim();
            const city = '<?php echo esc_js($city); ?>';
            
            // Don't proceed if any field is empty (button should be disabled anyway)
            if (!brand || !model || !fuel) {
                return;
            }
            
            // Show loader and disable button
            if (confirmVehicleUpdateBtnText) {
                confirmVehicleUpdateBtnText.classList.add('hidden');
            }
            if (confirmVehicleUpdateBtnLoader) {
                confirmVehicleUpdateBtnLoader.classList.remove('hidden');
            }
            confirmVehicleUpdateBtn.disabled = true;
            
            // Clear cart when vehicle is updated
            clearCart();
            
            // Build query string
            const params = new URLSearchParams();
            if (city) params.append('city', encodeURIComponent(city));
            params.append('brand', encodeURIComponent(brand));
            params.append('model', encodeURIComponent(model));
            params.append('fuel', encodeURIComponent(fuel));
            
            // Redirect immediately after showing loader
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
    // CART_STORAGE_KEY is already defined at the top of DOMContentLoaded
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
                btn.className = 'add-to-cart-btn flex items-center gap-2 text-sm font-semibold text-[#4CAF50] bg-[#F1FAF1] h-[2.5rem] px-3 py-2 border border-transparent hover:border-[#4CAF50] duration-500 max-md:rounded-lg';
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
                btn.className = 'add-to-cart-btn flex flex-row-reverse items-center gap-2 text-sm font-semibold text-white bg-[#CB122D] h-[2.5rem] px-3 py-2 border border-transparent hover:bg-[#650916] duration-500 max-md:rounded-lg';
                btn.innerHTML = `
                    <span>
                        <img class="size-[0.875rem]" width="14" height="14" src="${imgUrl}plusWhiteIcon.svg" alt="Add to Cart Icon" />
                    </span>
                    Add to Cart
                `;
            }
        });
        
        // Update mobile checkout button state
        const mobileProceedToCheckoutBtn = document.getElementById('mobileProceedToCheckoutBtn');
        if (mobileProceedToCheckoutBtn) {
            if (cart.items && cart.items.length > 0) {
                // Enable button if at least one service is in cart
                mobileProceedToCheckoutBtn.disabled = false;
            } else {
                // Disable button if no services in cart
                mobileProceedToCheckoutBtn.disabled = true;
            }
        }
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
            
            // Update mobile cart summary to 0 when cart is empty
            if (mobileCartItemCount) {
                mobileCartItemCount.textContent = 0;
            }
            if (mobileCartItemText) {
                mobileCartItemText.textContent = 'item';
            }
            if (mobileCartTotal) {
                mobileCartTotal.textContent = formatPrice(0, 'INR');
            }
        }
        
        // Render mobile cart popup
        renderMobileCart();
        
        // Add event listeners to remove buttons
        const removeButtons = document.querySelectorAll('.remove-cart-item');
        removeButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const serviceId = this.getAttribute('data-service-id');
                removeFromCart(serviceId);
            });
        });
    }
    
    // Function to render mobile cart popup
    function renderMobileCart() {
        const cart = getCart();
        const mobileCartItemsContainer = $('#mobileCartItemsContainer');
        const mobileCartVehicleName = $('#mobileCartVehicleName');
        const mobileCartVehicleFuel = $('#mobileCartVehicleFuel');
        const mobileCartSubtotalCount = $('#mobileCartSubtotalCount');
        const mobileCartSubtotalCountPlural = $('#mobileCartSubtotalCountPlural');
        const mobileCartSubtotal = $('#mobileCartSubtotal');
        const mobileCartTotalPrice = $('#mobileCartTotalPrice');
        const mobileCartFooterItemCount = $('#mobileCartFooterItemCount');
        const mobileCartFooterItemCountPlural = $('#mobileCartFooterItemCountPlural');
        const mobileCartFooterTotal = $('#mobileCartFooterTotal');
        
        if (!mobileCartItemsContainer) return;
        
        // Update vehicle info
        if (mobileCartVehicleName) {
            const vehicleTitle = (cart.vehicle.brand || '') + ' ' + (cart.vehicle.model || '');
            mobileCartVehicleName.textContent = vehicleTitle.trim() || 'Vehicle not selected';
        }
        if (mobileCartVehicleFuel) {
            mobileCartVehicleFuel.textContent = cart.vehicle.fuel || 'Fuel not selected';
        }
        
        // Clear existing items
        mobileCartItemsContainer.innerHTML = '';
        
        // Render cart items
        if (cart.items.length > 0) {
            cart.items.forEach(function(item) {
                const serviceName = item.service_name || item.name || 'Service';
                const servicePrice = item.price || 0;
                const serviceCurrency = item.currency || 'INR';
                const serviceImage = item.service_image_url || item.image_url || imgUrl + 'car-brand.webp';
                const serviceDetails = item.service_details || [];
                
                // Generate service details HTML
                let detailsHtml = '';
                if (serviceDetails && serviceDetails.length > 0) {
                    serviceDetails.forEach(function(detail) {
                        const detailText = detail.detail || detail || '';
                        if (detailText) {
                            detailsHtml += `
                                <li class="w-full flex items-center gap-2 font-normal text-sm text-[#121212]">
                                    <span>
                                        <img src="${imgUrl}checkeddIcon.svg" class="size-5" width="20" height="20" alt="Check Icon" />
                                    </span>
                                    ${escapeHtml(detailText)}
                                </li>
                            `;
                        }
                    });
                }
                
                const itemHtml = `
                    <div class="w-full flex flex-col bg-white border border-[#EFEFEF] rounded-xl shadow-[0_0.125rem_0.25rem_-0.125rem_rgba(0,0,0,0.10)]" data-cart-item-id="${item.id}">
                        <div class="p-3 flex flex-col gap-y-3">
                            <div class="flex justify-between">
                                <div class="flex gap-2">
                                    <div>
                                        <img fetchpriority="low" loading="lazy" src="${serviceImage}" class="size-20 object-contain bg-gray-100 rounded-lg aspect-square" alt="${escapeHtml(serviceName)}" onerror="this.src='${imgUrl}car-brand.webp';" />
                                    </div>
                                    <div class="flex flex-col gap-2 justify-between">
                                        <div class="text-[#1A1A1A] lg:text-lg md:text-md text-base font-semibold">${escapeHtml(serviceName)}</div>
                                        <div class="flex items-center gap-3">
                                            <div class="text-[#121212] text-md font-bold">${formatPrice(servicePrice, serviceCurrency)}</div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="remove-mobile-cart-item bg-transparent p-0 m-0 border-0 text-[#CB122D] hover:text-[#650916] duration-500" data-service-id="${item.id}">
                                        <img src="${imgUrl}mobileCartDeleteIcon.svg" class="size-5" width="20" height="20" alt="Delete Icon" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        ${detailsHtml ? `
                        <div class="border-t border-[#F0F0F0] px-3 py-3">
                            <a href="javascript:void(0);" class="mobile-view-details-btn flex items-center gap-2 text-xs font-medium text-[#000000A3] text-center justify-center" data-service-id="${item.id}">
                                View Details
                                <span>
                                    <img src="${imgUrl}mobileCartViewDetailsArrow.svg" width="10" height="6" alt="View Details Arrow" />
                                </span>
                            </a>
                        </div>
                        <div class="mobile-service-details w-full border-t border-[#F0F0F0] p-3 hidden" data-service-id="${item.id}">
                            <h2 class="text-[#121212] text-sm font-semibold text-start pb-2">What's included:</h2>
                            <ul class="flex flex-col gap-y-3">
                                ${detailsHtml}
                            </ul>
                        </div>
                        ` : ''}
                    </div>
                `;
                mobileCartItemsContainer.innerHTML += itemHtml;
            });
        } else {
            // Show empty cart state
            mobileCartItemsContainer.innerHTML = `
                <div class="w-full flex flex-col gap-y-4 text-center mx-auto py-6">
                    <div class="bg-[#F7F7F7] size-20 rounded-full flex items-center justify-center mx-auto">
                        <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.1749 7.58028C19.9001 7.86066 19.7462 8.23762 19.7462 8.63024C19.7462 9.02285 19.9001 9.39981 20.1749 9.68019L22.5748 12.0801C22.8552 12.3549 23.2322 12.5089 23.6248 12.5089C24.0174 12.5089 24.3944 12.3549 24.6748 12.0801L29.3336 7.42278C29.8135 6.9398 30.628 7.0928 30.808 7.74977C31.2612 9.39802 31.2356 11.1412 30.7342 12.7755C30.2328 14.4097 29.2763 15.8673 27.9767 16.9778C26.6771 18.0882 25.0881 18.8056 23.3957 19.0459C21.7033 19.2862 19.9774 19.0396 18.42 18.3348L6.55548 30.1994C5.95877 30.7959 5.14953 31.1309 4.30579 31.1308C3.46205 31.1306 2.65293 30.7953 2.05641 30.1986C1.4599 29.6019 1.12486 28.7927 1.125 27.9489C1.12514 27.1052 1.46045 26.2961 2.05716 25.6995L13.9217 13.835C13.2169 12.2777 12.9703 10.5518 13.2106 8.85935C13.4509 7.16692 14.1683 5.57793 15.2788 4.27833C16.3892 2.97873 17.8469 2.02227 19.4811 1.52086C21.1153 1.01945 22.8585 0.993842 24.5068 1.44702C25.1637 1.62702 25.3167 2.43998 24.8352 2.92296L20.1749 7.58028Z" stroke="#AFAFAF" stroke-width="2.24991" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="w-full flex flex-col gap-y-1">
                        <h3 class="text-[#6F6F6F] text-sm font-normal">Your cart is empty</h3>
                        <p class="text-[#AFAFAF] text-xs font-normal">Add services to get started</p>
                    </div>
                </div>
            `;
        }
        
        // Update mobile cart price breakdown
        const itemCount = cart.items.length;
        const subtotal = cart.items.reduce((sum, item) => sum + (item.price || 0), 0);
        const mobileCartPriceBreakdown = $('#mobileCartPriceBreakdown');
        
        if (itemCount > 0) {
            // Show price breakdown section
            if (mobileCartPriceBreakdown) {
                mobileCartPriceBreakdown.classList.remove('hidden');
            }
            
            if (mobileCartSubtotalCount) {
                mobileCartSubtotalCount.textContent = itemCount;
            }
            if (mobileCartSubtotalCountPlural) {
                mobileCartSubtotalCountPlural.textContent = itemCount === 1 ? '' : 's';
            }
            if (mobileCartSubtotal) {
                mobileCartSubtotal.textContent = formatPrice(subtotal, 'INR');
            }
            if (mobileCartTotalPrice) {
                mobileCartTotalPrice.textContent = formatPrice(subtotal, 'INR');
            }
            if (mobileCartFooterItemCount) {
                mobileCartFooterItemCount.textContent = itemCount;
            }
            if (mobileCartFooterItemCountPlural) {
                mobileCartFooterItemCountPlural.textContent = itemCount === 1 ? '' : 's';
            }
            if (mobileCartFooterTotal) {
                mobileCartFooterTotal.textContent = formatPrice(subtotal, 'INR');
            }
        } else {
            // Hide price breakdown section when cart is empty
            if (mobileCartPriceBreakdown) {
                mobileCartPriceBreakdown.classList.add('hidden');
            }
            
            // Reset footer values to 0
            if (mobileCartFooterItemCount) {
                mobileCartFooterItemCount.textContent = 0;
            }
            if (mobileCartFooterItemCountPlural) {
                mobileCartFooterItemCountPlural.textContent = 's';
            }
            if (mobileCartFooterTotal) {
                mobileCartFooterTotal.textContent = formatPrice(0, 'INR');
            }
        }
        
        // Add event listeners to mobile remove buttons
        const removeMobileButtons = document.querySelectorAll('.remove-mobile-cart-item');
        removeMobileButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const serviceId = this.getAttribute('data-service-id');
                removeFromCart(serviceId);
            });
        });
        
        // Add event listeners to mobile view details buttons
        const viewDetailsButtons = document.querySelectorAll('.mobile-view-details-btn');
        viewDetailsButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const serviceId = this.getAttribute('data-service-id');
                const detailsSection = document.querySelector('.mobile-service-details[data-service-id="' + serviceId + '"]');
                if (detailsSection) {
                    detailsSection.classList.toggle('hidden');
                }
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
    
    
    // Mobile cart popup open/close functionality
    const cartModal = $('.cart_modal');
    const viewCartBtn = $('#viewCartBtn');
    const closePopover = $('#closePopover');
    
    // Open mobile cart popup
    if (viewCartBtn && cartModal) {
        viewCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            cartModal.classList.remove('hidden');
        });
    }
    
    // Close mobile cart popup
    if (closePopover && cartModal) {
        closePopover.addEventListener('click', function(e) {
            e.preventDefault();
            cartModal.classList.add('hidden');
        });
    }
    
    // Mobile Vehicle Form Popup Functionality
    const mobileVehicleFormModal = $('#mobileVehicleFormModal');
    const carChangePopupBtn = $('#carChangePopupBtn');
    const closeCarFormPopover = $('#closeCarFormPopover');
    
    // Open mobile vehicle form popup
    if (carChangePopupBtn && mobileVehicleFormModal) {
        carChangePopupBtn.addEventListener('click', function(e) {
            e.preventDefault();
            mobileVehicleFormModal.classList.remove('hidden');
            // Initialize mobile vehicle dropdowns when popup opens
            initializeMobileVehicleDropdowns();
        });
    }
    
    // Close mobile vehicle form popup
    if (closeCarFormPopover && mobileVehicleFormModal) {
        closeCarFormPopover.addEventListener('click', function(e) {
            e.preventDefault();
            mobileVehicleFormModal.classList.add('hidden');
        });
    }
    
    // Function to fetch mobile vehicle models
    function fetchMobileVehicleModels(carMake) {
        if (!carMake) {
            return;
        }
        
        const modelList = $('#mobileVehicleModelList');
        const modelInput = $('#mobileVehicleModelInput');
        const modelIcon = $('#mobileVehicleModelIcon');
        const modelEmptyState = $('#mobileVehicleModelEmptyState');
        
        if (!modelList || !modelInput || !modelIcon || !modelEmptyState) return;
        
        // Clear previous model selection
        modelInput.value = '';
        setDropdownEnabled('mobileVehicleModelInput', false);
        setDropdownEnabled('mobileVehicleFuelInput', false);
        $('#mobileVehicleFuelInput').value = '';
        
        // Show loading state
        modelInput.placeholder = 'Loading models...';
        modelIcon.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#CB122D]"></div>';
        
        modelList.innerHTML = '<div class="col-span-2 text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div><p class="text-gray-500 text-sm mt-2">Loading models...</p></div>';
        modelEmptyState.classList.add('hidden');
        
        // Make AJAX call
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
                            <div class="cursor-pointer text-center" data-value="${escapedModelName}" data-image="${escapeHtml(modelImage)}">
                                <img src="${modelImage}" alt="${escapedModelName}" class="w-full h-24 object-cover mb-1 rounded" loading="lazy" fetchpriority="low" onerror="this.src='<?php echo esc_js($img_url . "car-brand.webp"); ?>';" />
                                <p class="text-xs">${escapedModelName}</p>
                            </div>
                        `;
                    }
                });
                modelList.innerHTML = html;
                modelInput.placeholder = 'Car Model';
                setDropdownEnabled('mobileVehicleModelInput', true);
                
                // If model is already selected from URL params, update the image
                const currentModel = '<?php echo esc_js($model); ?>';
                if (currentModel) {
                    const modelItem = modelList.querySelector('[data-value="' + escapeHtml(currentModel) + '"]');
                    if (modelItem) {
                        const modelImage = modelItem.getAttribute('data-image');
                        if (modelImage) {
                            updateVehicleDisplayImage(modelImage);
                        }
                    }
                }
            } else {
                modelList.innerHTML = '';
                modelEmptyState.classList.remove('hidden');
                modelInput.placeholder = 'No models found';
            }
            modelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
            // Update mobile button state after models are loaded
            updateMobileConfirmButtonState();
        })
        .catch(function() {
            modelList.innerHTML = '';
            modelEmptyState.classList.remove('hidden');
            modelInput.placeholder = 'Failed to load models';
            modelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
            // Update mobile button state on error
            updateMobileConfirmButtonState();
        });
    }
    
    // Function to fetch mobile vehicle fuel types
    function fetchMobileVehicleFuelTypes(carMake, carModel) {
        if (!carMake || !carModel) {
            return;
        }
        
        const fuelList = $('#mobileVehicleFuelList');
        const fuelInput = $('#mobileVehicleFuelInput');
        const fuelIcon = $('#mobileVehicleFuelIcon');
        
        if (!fuelList || !fuelInput || !fuelIcon) return;
        
        // Clear previous fuel type selection
        fuelInput.value = '';
        setDropdownEnabled('mobileVehicleFuelInput', false);
        
        // Show loading state
        fuelInput.placeholder = 'Loading fuel types...';
        fuelIcon.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#CB122D]"></div>';
        
        fuelList.innerHTML = '<div class="col-span-3 text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div><p class="text-gray-500 text-sm mt-2">Loading fuel types...</p></div>';
        
        // Make AJAX call
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
                setDropdownEnabled('mobileVehicleFuelInput', true);
            } else {
                fuelList.innerHTML = '';
                fuelInput.placeholder = 'No fuel types found';
            }
            fuelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
            // Update mobile button state after fuel types are loaded
            updateMobileConfirmButtonState();
        })
        .catch(function() {
            fuelList.innerHTML = '';
            fuelInput.placeholder = 'Failed to load fuel types';
            fuelIcon.innerHTML = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
            // Update mobile button state on error
            updateMobileConfirmButtonState();
        });
    }
    
    // Setup mobile vehicle dropdowns
    const allMobileVehicleDropdowns = [];
    
    function closeAllMobileVehicleDropdowns(exceptId) {
        allMobileVehicleDropdowns.forEach(function(dropdown) {
            if (dropdown.dropdownId !== exceptId) {
                dropdown.dropdown.classList.add('hidden');
            }
        });
    }
    
    function setupMobileVehicleDropdown({inputId, dropdownId, itemSelector, searchId = null, iconId = null, enabled = true}) {
        const input = $('#' + inputId);
        const dropdown = $('#' + dropdownId);
        const search = searchId ? $('#' + searchId) : null;
        const icon = iconId ? $('#' + iconId) : null;
        
        if (!input || !dropdown) return;
        
        allMobileVehicleDropdowns.push({
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
                closeAllMobileVehicleDropdowns(dropdownId);
            }
            
            dropdown.classList.toggle('hidden');
        });
        
        // Icon click toggle
        if (icon) {
            icon.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                if (input.disabled) {
                    return;
                }
                
                const isOpen = !dropdown.classList.contains('hidden');
                if (!isOpen) {
                    closeAllMobileVehicleDropdowns(dropdownId);
                }
                
                dropdown.classList.toggle('hidden');
            });
        }
        
        // Outside click close
        document.addEventListener('click', function(e) {
            var clickedOutside = true;
            allMobileVehicleDropdowns.forEach(function(dropdownItem) {
                if (dropdownItem.dropdown.contains(e.target) || 
                    dropdownItem.input === e.target || 
                    dropdownItem.dropdown === e.target ||
                    (dropdownItem.icon && dropdownItem.icon.contains(e.target))) {
                    clickedOutside = false;
                }
            });
            
            if (clickedOutside) {
                closeAllMobileVehicleDropdowns(null);
            }
        });
        
        // Dropdown item click
        dropdown.addEventListener('click', function(e) {
            const clickedItem = e.target.closest(itemSelector);
            if (!clickedItem) return;
            
            const value = clickedItem.getAttribute('data-value');
            input.value = value;
            dropdown.classList.add('hidden');
            
            // Enable next dropdown based on selection
            if (inputId === 'mobileVehicleBrandInput') {
                fetchMobileVehicleModels(value);
                setDropdownEnabled('mobileVehicleModelInput', true);
                setDropdownEnabled('mobileVehicleFuelInput', false);
                $('#mobileVehicleModelInput').value = '';
                $('#mobileVehicleFuelInput').value = '';
                // Reset vehicle image to default when brand changes
                updateVehicleDisplayImage('<?php echo esc_js($img_url . "carServiceIcon.webp"); ?>');
            } else if (inputId === 'mobileVehicleModelInput') {
                const selectedBrand = $('#mobileVehicleBrandInput').value;
                fetchMobileVehicleFuelTypes(selectedBrand, value);
                setDropdownEnabled('mobileVehicleFuelInput', true);
                $('#mobileVehicleFuelInput').value = '';
                // Update vehicle image when model is selected
                const modelImage = clickedItem.getAttribute('data-image');
                if (modelImage) {
                    updateVehicleDisplayImage(modelImage);
                } else {
                    updateVehicleDisplayImage('<?php echo esc_js($img_url . "carServiceIcon.webp"); ?>');
                }
            }
            
            // Update mobile button state after value change
            updateMobileConfirmButtonState();
        });
        
        // Search filter
        if (search) {
            search.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            search.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const items = dropdown.querySelectorAll(itemSelector);
                let foundItems = 0;
                items.forEach(function(item) {
                    const text = item.textContent.toLowerCase();
                    if (text.indexOf(searchTerm) > -1) {
                        item.style.display = '';
                        foundItems++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                const emptyState = $('#' + dropdownId.replace('Dropdown', 'EmptyState'));
                if (emptyState) {
                    if (foundItems === 0) {
                        emptyState.classList.remove('hidden');
                    } else {
                        emptyState.classList.add('hidden');
                    }
                }
            });
        }
    }
    
    function initializeMobileVehicleDropdowns() {
        // Clear previous dropdown instances
        allMobileVehicleDropdowns.length = 0;
        
        setupMobileVehicleDropdown({
            inputId: 'mobileVehicleBrandInput',
            dropdownId: 'mobileVehicleBrandDropdown',
            itemSelector: '[data-value]',
            searchId: 'mobileVehicleBrandSearch',
            iconId: 'mobileVehicleBrandIcon',
            enabled: true
        });
        
        setupMobileVehicleDropdown({
            inputId: 'mobileVehicleModelInput',
            dropdownId: 'mobileVehicleModelDropdown',
            itemSelector: '[data-value]',
            searchId: 'mobileVehicleModelSearch',
            iconId: 'mobileVehicleModelIcon',
            enabled: <?php echo !empty($brand) ? 'true' : 'false'; ?>
        });
        
        setupMobileVehicleDropdown({
            inputId: 'mobileVehicleFuelInput',
            dropdownId: 'mobileVehicleFuelType',
            itemSelector: '[data-value]',
            iconId: 'mobileVehicleFuelIcon',
            enabled: <?php echo (!empty($brand) && !empty($model)) ? 'true' : 'false'; ?>
        });
        
        // Function to pre-select mobile dropdowns from sessionStorage or URL parameters
        function preSelectMobileVehicleDropdowns() {
            // First check sessionStorage
            let selectedBrand = '';
            let selectedModel = '';
            let selectedFuel = '';
            
            try {
                const cartData = sessionStorage.getItem(CART_STORAGE_KEY);
                if (cartData) {
                    const cart = JSON.parse(cartData);
                    if (cart.vehicle) {
                        selectedBrand = cart.vehicle.brand || '';
                        selectedModel = cart.vehicle.model || '';
                        selectedFuel = cart.vehicle.fuel || '';
                    }
                }
            } catch (e) {
                console.error('Error reading sessionStorage:', e);
            }
            
            // Fallback to URL parameters if sessionStorage doesn't have data
            if (!selectedBrand) {
                selectedBrand = '<?php echo esc_js($brand); ?>';
            }
            if (!selectedModel) {
                selectedModel = '<?php echo esc_js($model); ?>';
            }
            if (!selectedFuel) {
                selectedFuel = '<?php echo esc_js($fuel); ?>';
            }
            
            // Pre-select brand if available
            if (selectedBrand) {
                const brandInput = $('#mobileVehicleBrandInput');
                if (brandInput) {
                    brandInput.value = selectedBrand;
                    brandInput.style.backgroundColor = '#fff';
                    brandInput.style.color = '#000';
                    
                    // Fetch models for the selected brand
                    fetchMobileVehicleModels(selectedBrand);
                    
                    // After models are loaded, pre-select model if available
                    if (selectedModel) {
                        // Check if models are already loaded, otherwise wait for them
                        const checkMobileModelLoaded = () => {
                            const modelInput = $('#mobileVehicleModelInput');
                            const modelList = $('#mobileVehicleModelList');
                            if (modelInput && modelList && modelList.querySelector('[data-value]')) {
                                modelInput.value = selectedModel;
                                modelInput.style.backgroundColor = '#fff';
                                modelInput.style.color = '#000';
                                
                                // Update vehicle image if model item exists
                                const modelItem = modelList.querySelector('[data-value="' + escapeHtml(selectedModel) + '"]');
                                if (modelItem) {
                                    const modelImage = modelItem.getAttribute('data-image');
                                    if (modelImage) {
                                        updateVehicleDisplayImage(modelImage);
                                    }
                                }
                                
                                // Fetch fuel types for the selected brand and model
                                fetchMobileVehicleFuelTypes(selectedBrand, selectedModel);
                                
                                // After fuel types are loaded, pre-select fuel if available
                                if (selectedFuel) {
                                    const checkMobileFuelLoaded = () => {
                                        const fuelInput = $('#mobileVehicleFuelInput');
                                        const fuelList = $('#mobileVehicleFuelList');
                                        if (fuelInput && fuelList && fuelList.querySelector('[data-value]')) {
                                            fuelInput.value = selectedFuel;
                                            fuelInput.style.backgroundColor = '#fff';
                                            fuelInput.style.color = '#000';
                                            updateMobileConfirmButtonState();
                                        } else {
                                            setTimeout(checkMobileFuelLoaded, 100);
                                        }
                                    };
                                    setTimeout(checkMobileFuelLoaded, 100);
                                }
                            } else {
                                setTimeout(checkMobileModelLoaded, 100);
                            }
                        };
                        setTimeout(checkMobileModelLoaded, 100);
                    }
                }
            }
        }
        
        // If brand is already selected, fetch models
        <?php if (!empty($brand)) : ?>
            fetchMobileVehicleModels('<?php echo esc_js($brand); ?>');
        <?php endif; ?>
        
        // If model is already selected, fetch fuel types
        <?php if (!empty($brand) && !empty($model)) : ?>
            fetchMobileVehicleFuelTypes('<?php echo esc_js($brand); ?>', '<?php echo esc_js($model); ?>');
        <?php endif; ?>
        
        // Pre-select mobile dropdowns from sessionStorage or URL parameters on page load
        preSelectMobileVehicleDropdowns();
        
        // Update button state after dropdowns are initialized
        updateMobileConfirmButtonState();
    }
    
    // Function to update mobile button state based on dropdown selections (must be defined before initializeMobileVehicleDropdowns)
    function updateMobileConfirmButtonState() {
        const brand = $('#mobileVehicleBrandInput').value.trim();
        const model = $('#mobileVehicleModelInput').value.trim();
        const fuel = $('#mobileVehicleFuelInput').value.trim();
        
        const mobileConfirmVehicleUpdateBtn = $('#mobileConfirmVehicleUpdateBtn');
        if (mobileConfirmVehicleUpdateBtn) {
            if (brand && model && fuel) {
                mobileConfirmVehicleUpdateBtn.disabled = false;
            } else {
                mobileConfirmVehicleUpdateBtn.disabled = true;
            }
        }
    }
    
    // Handle Mobile Confirm Vehicle Update button
    const mobileConfirmVehicleUpdateBtn = $('#mobileConfirmVehicleUpdateBtn');
    const mobileConfirmVehicleUpdateBtnText = document.getElementById('mobileConfirmVehicleUpdateBtnText');
    const mobileConfirmVehicleUpdateBtnLoader = document.getElementById('mobileConfirmVehicleUpdateBtnLoader');
    
    if (mobileConfirmVehicleUpdateBtn) {
        mobileConfirmVehicleUpdateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const brand = $('#mobileVehicleBrandInput').value.trim();
            const model = $('#mobileVehicleModelInput').value.trim();
            const fuel = $('#mobileVehicleFuelInput').value.trim();
            const city = '<?php echo esc_js($city); ?>';
            
            // Don't proceed if any field is empty (button should be disabled anyway)
            if (!brand || !model || !fuel) {
                return;
            }
            
            // Show loader and disable button
            if (mobileConfirmVehicleUpdateBtnText) {
                mobileConfirmVehicleUpdateBtnText.classList.add('hidden');
            }
            if (mobileConfirmVehicleUpdateBtnLoader) {
                mobileConfirmVehicleUpdateBtnLoader.classList.remove('hidden');
            }
            mobileConfirmVehicleUpdateBtn.disabled = true;
            
            // Clear cart when vehicle is updated
            clearCart();
            
            // Build query string
            const params = new URLSearchParams();
            if (city) params.append('city', encodeURIComponent(city));
            params.append('brand', encodeURIComponent(brand));
            params.append('model', encodeURIComponent(model));
            params.append('fuel', encodeURIComponent(fuel));
            
            // Close popup
            if (mobileVehicleFormModal) {
                mobileVehicleFormModal.classList.add('hidden');
            }
            
            // Redirect immediately
            const redirectUrl = currentUrl + '?' + params.toString();
            window.location.href = redirectUrl;
        });
    }
    
    // Handle Desktop Proceed to Checkout button
    const desktopProceedToCheckoutBtn = document.getElementById('desktopProceedToCheckoutBtn');
    const desktopProceedToCheckoutBtnText = document.getElementById('desktopProceedToCheckoutBtnText');
    const desktopProceedToCheckoutBtnLoader = document.getElementById('desktopProceedToCheckoutBtnLoader');
    const verifyPageUrl = '<?php echo esc_js($verify_page_url); ?>';
    
    if (desktopProceedToCheckoutBtn) {
        desktopProceedToCheckoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loader and disable button
            if (desktopProceedToCheckoutBtnText) {
                desktopProceedToCheckoutBtnText.classList.add('hidden');
            }
            if (desktopProceedToCheckoutBtnLoader) {
                desktopProceedToCheckoutBtnLoader.classList.remove('hidden');
            }
            desktopProceedToCheckoutBtn.disabled = true;
            
            // Save current URL with query params to sessionStorage before redirecting
            const currentUrl = window.location.href;
            try {
                sessionStorage.setItem('cost_estimator_previous_url', currentUrl);
            } catch (e) {
                console.error('Error saving URL to sessionStorage:', e);
            }
            
            // Redirect immediately after showing loader
            if (verifyPageUrl && verifyPageUrl !== '') {
                window.location.href = verifyPageUrl;
            } else {
                console.error('Verify page URL not found');
                // Re-enable button on error
                if (desktopProceedToCheckoutBtnText) {
                    desktopProceedToCheckoutBtnText.classList.remove('hidden');
                }
                if (desktopProceedToCheckoutBtnLoader) {
                    desktopProceedToCheckoutBtnLoader.classList.add('hidden');
                }
                desktopProceedToCheckoutBtn.disabled = false;
            }
        });
    }
    
    // Handle Mobile Proceed to Checkout button
    const mobileProceedToCheckoutBtn = document.getElementById('mobileProceedToCheckoutBtn');
    const mobileProceedToCheckoutBtnText = document.getElementById('mobileProceedToCheckoutBtnText');
    const mobileProceedToCheckoutBtnLoader = document.getElementById('mobileProceedToCheckoutBtnLoader');
    
    if (mobileProceedToCheckoutBtn) {
        mobileProceedToCheckoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loader and disable button
            if (mobileProceedToCheckoutBtnText) {
                mobileProceedToCheckoutBtnText.classList.add('hidden');
            }
            if (mobileProceedToCheckoutBtnLoader) {
                mobileProceedToCheckoutBtnLoader.classList.remove('hidden');
            }
            mobileProceedToCheckoutBtn.disabled = true;
            
            // Save current URL with query params to sessionStorage before redirecting
            const currentUrl = window.location.href;
            try {
                sessionStorage.setItem('cost_estimator_previous_url', currentUrl);
            } catch (e) {
                console.error('Error saving URL to sessionStorage:', e);
            }
            
            // Redirect immediately after showing loader
            if (verifyPageUrl && verifyPageUrl !== '') {
                window.location.href = verifyPageUrl;
            } else {
                console.error('Verify page URL not found');
                // Re-enable button on error
                if (mobileProceedToCheckoutBtnText) {
                    mobileProceedToCheckoutBtnText.classList.remove('hidden');
                }
                if (mobileProceedToCheckoutBtnLoader) {
                    mobileProceedToCheckoutBtnLoader.classList.add('hidden');
                }
                mobileProceedToCheckoutBtn.disabled = false;
            }
        });
    }
    
    // Save service category to sessionStorage when category tab is selected
    const serviceCategoryRadios = document.querySelectorAll('input[name="services"][type="radio"]');
    serviceCategoryRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.checked) {
                // Get category name from the label text
                const label = this.closest('label');
                if (label) {
                    const categoryName = label.textContent.trim();
                    if (categoryName) {
                        // Save to cart
                        const cart = getCart();
                        cart.service_category = categoryName;
                        saveCart(cart);
                    }
                }
            }
        });
    });
    
    // Save initial selected category on page load
    const initialSelectedCategory = document.querySelector('input[name="services"][type="radio"]:checked');
    if (initialSelectedCategory) {
        const label = initialSelectedCategory.closest('label');
        if (label) {
            const categoryName = label.textContent.trim();
            if (categoryName) {
                const cart = getCart();
                cart.service_category = categoryName;
                saveCart(cart);
            }
        }
    }
    
    // Initial cart render and button states update
    renderCart();
    updateButtonStates();
});
</script>

<?php get_footer(); ?>