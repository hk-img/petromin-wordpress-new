<?php
/**
 * Single Offer Template - Dynamic
 */
get_header();

// Get current offer data
$offer_id = get_the_ID();
$offer_title = get_the_title();
$offer_image = petromin_get_acf_image_data(
    get_field('offer_image', $offer_id),
    'large',
    get_the_post_thumbnail_url($offer_id, 'large')
);
$starting_price = get_field('offer_starting_price', $offer_id);
$price_currency = get_field('offer_price_currency', $offer_id) ?: '₹';
$suitable_for_title = get_field('offer_suitable_for_title', $offer_id) ?: 'Best suited for';
$suitable_for = get_field('offer_suitable_for', $offer_id);
$terms_conditions_title = get_field('offer_terms_conditions_title', $offer_id) ?: '*Terms & Conditions';
$terms_conditions_content = get_field('offer_terms_conditions_content', $offer_id);
$faqs_title = get_field('offer_faqs_title', $offer_id) ?: 'Commonly Asked Questions';
$faqs = get_field('offer_faqs', $offer_id);

// Get assets URL for dropdowns
$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$assets_img_url = $assets_url . '/img/';

// Get cities data (same as footer.php)
$locate_us_page = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'locate-us.php',
    'number' => 1,
    'post_status' => 'publish'
));

$locate_us_page_id = !empty($locate_us_page) ? $locate_us_page[0]->ID : null;

$service_centers_field = [];
if ($locate_us_page_id && function_exists('get_field')) {
    $service_centers_field = get_field('service_centers_section', $locate_us_page_id) ?: [];
}

$cities_data = array();
if (!empty($service_centers_field['centers']) && is_array($service_centers_field['centers'])) {
    foreach ($service_centers_field['centers'] as $center) {
        $city = trim($center['city'] ?? '');
        if (!empty($city) && !isset($cities_data[$city])) {
            $city_image_id = $center['city_image'] ?? null;
            $city_image_url = '';
            if ($city_image_id) {
                $city_image_data = petromin_get_acf_image_data($city_image_id, 'full', '', $city);
                $city_image_url = $city_image_data['url'] ?? '';
            }
            
            $cities_data[$city] = array(
                'name' => $city,
                'image' => $city_image_url
            );
        }
    }
}

uksort($cities_data, 'strcasecmp');

if (empty($cities_data)) {
    $cities_data = array(
        'Chennai' => array('name' => 'Chennai', 'image' => ''),
        'Bengaluru' => array('name' => 'Bengaluru', 'image' => '')
    );
}

// Get car makes from API (same as footer.php)
$supabase_api_key = defined('SUPABASE_API_KEY') ? SUPABASE_API_KEY : '';
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

<section class="body_paint_sec md:pt-32 pt-20 md:pb-[6.25rem] pb-[4rem]">
    <div class="view w-full relative">
        <div class="w-full flex flex-col gap-3 md:gap-4 mb-8">
            <h1 class="relative xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-4xl lg:leading-[3.75rem] font-bold text-[#000000]">
                <?php echo esc_html($offer_title); ?>
            </h1>
            <div class="bg-gradient-to-l from-[#CB122D] to-[#650916] w-[7.375rem] h-3 -skew-x-[22deg]"></div>
        </div>
        <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-6 items-stretch">
            <div class="w-full relative flex flex-col gap-y-8">
                <div class="size-full">
                    <img fetchpriority="high" decoding="async" loading="eager" 
                        src="<?php echo esc_url($offer_image['url']); ?>"
                        class="size-full object-cover aspect-square" 
                        alt="<?php echo esc_attr($offer_image['alt'] ?: $offer_title); ?>"
                        title="<?php echo esc_attr($offer_image['alt'] ?: $offer_title); ?>">
                </div>
            </div>
            <div class="relative w-ful bg-[#FFFFFF] border border-[#E5E7EB] shadow-[0_4px_12px_0_#0000000F]">
                <div class="text-white  bg-gradient-to-l from-[#CB122D] to-[#650916] text-balance px-6 py-4 uppercase">
                    <h2 class="text-white md:text-lg text-md font-semibold italic">Get this offer today</h2>
                </div>
                <div class="py-8 px-6">
                    <form action="" class="flex flex-col gap-y-8">
                        <!-- City Dropdown -->
                        <div class="w-full relative">
                            <label class="block mb-2 text-base font-medium">City</label>
                            <div class="relative w-full">
                                <input type="text" placeholder="Select City" id="offerCityInput" readonly
                                    class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full px-4 pr-10 text-[#99A1AF] focus:outline-none focus:ring-0 focus:border-[#E5E7EB] cursor-pointer transition-colors duration-300" />
                                <span id="offerCityIcon" class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer">
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div id="offerCityDropdown" class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden shadow-lg rounded mt-1">
                                    <div class="grid grid-cols-2 gap-2 p-3 max-h-56 overflow-y-auto">
                                        <?php
                                        $city_img_index = 0;
                                        foreach ($cities_data as $city_data) {
                                            $city = $city_data['name'];
                                            $city_image_url = $city_data['image'];
                                            
                                            if (!empty($city_image_url)) {
                                                $city_img = $city_image_url;
                                            } else {
                                                $img_number = ($city_img_index % 2) + 1;
                                                $city_img = $assets_img_url . 'city-img' . $img_number . '.png';
                                            }
                                            $city_img_index++;
                                        ?>
                                        <div class="cursor-pointer group" data-value="<?php echo esc_attr($city); ?>">
                                            <div class="relative rounded overflow-hidden">
                                                <img src="<?php echo esc_url($city_img); ?>" alt="<?php echo esc_attr($city); ?>"
                                                    class="w-full h-48 xl:h-52 object-cover"
                                                    onerror="this.src='<?php echo esc_url($assets_img_url . 'city-img1.png'); ?>';">
                                                <p class="absolute top-0 left-0 text-white font-semibold pt-2 pl-3 [text-shadow:0_0_8px_black,_0_0_8px_black]"><?php echo esc_html($city); ?></p>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Car Brand Dropdown -->
                        <div class="w-full relative">
                            <label class="block mb-2 text-base font-medium">Car Brand</label>
                            <div class="relative w-full">
                                <input type="text" placeholder="Select Brand" id="offerBrandInput" readonly
                                    class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full px-4 pr-10 text-[#99A1AF] focus:outline-none focus:ring-0 focus:border-[#E5E7EB] cursor-pointer transition-colors duration-300" />
                                <span id="offerBrandIcon" class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer">
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div id="offerBrandDropdown" class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden shadow-lg rounded mt-1">
                                    <div class="p-3">
                                        <div class="flex items-center bg-gray-100 px-3 py-2 mb-2 rounded">
                                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input type="text" id="offerBrandSearch" placeholder="Search Car Brand"
                                                class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700" />
                                        </div>
                                        <div class="max-h-56 overflow-y-auto" id="offerBrandList">
                                            <div class="grid grid-cols-3 gap-4" id="offerBrandListItems">
                                                <?php if (!empty($car_makes)) : ?>
                                                    <?php foreach ($car_makes as $make) : 
                                                        $car_make_name = isset($make['car_make']) ? $make['car_make'] : '';
                                                        $car_make_logo = isset($make['car_make_logo_url']) && !empty($make['car_make_logo_url']) ? $make['car_make_logo_url'] : '';
                                                        $has_logo = !empty($car_make_logo);
                                                        $logo_src = $has_logo ? esc_url($car_make_logo) : esc_url($assets_img_url . 'petromin-logo-300x75-1.webp');
                                                        $fade_class = $has_logo ? '' : 'opacity-40';
                                                    ?>
                                                    <div class="cursor-pointer text-center" data-value="<?php echo esc_attr($car_make_name); ?>">
                                                        <img src="<?php echo $logo_src; ?>" 
                                                            alt="<?php echo esc_attr($car_make_name); ?>" 
                                                            class="w-16 mx-auto mb-1 aspect-square object-contain <?php echo $fade_class; ?>"
                                                            loading="lazy" 
                                                            fetchpriority="low"
                                                            onerror="this.src='<?php echo esc_url($assets_img_url . 'petromin-logo-300x75-1.webp'); ?>'; this.classList.add('opacity-40');">
                                                        <p class="text-xs"><?php echo esc_html($car_make_name); ?></p>
                                                    </div>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <div class="cursor-pointer text-center" data-value="Hyundai">
                                                        <img src="<?php echo esc_url($assets_img_url . 'image-34.webp'); ?>" alt="Hyundai" class="w-16 mx-auto mb-1 aspect-square object-contain" loading="lazy" fetchpriority="low">
                                                        <p class="text-xs">Hyundai</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div id="offerBrandEmptyState" class="hidden text-center py-8">
                                                <p class="text-gray-500 text-sm">No car brands found</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Car Model Dropdown -->
                        <div class="w-full relative">
                            <label class="block mb-2 text-base font-medium">Car Model</label>
                            <div class="relative w-full">
                                <input type="text" placeholder="Select Model (Select Car Brand first)" id="offerModelInput" readonly
                                    class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full px-4 pr-10 text-[#99A1AF] focus:outline-none focus:ring-0 focus:border-[#E5E7EB] cursor-not-allowed transition-colors duration-300" disabled />
                                <span id="offerModelIcon" class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer">
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div id="offerModelDropdown" class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden shadow-lg rounded mt-1">
                                    <div class="p-3">
                                        <div class="flex items-center bg-gray-100 px-3 py-2 mb-2 rounded">
                                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input type="text" id="offerModelSearch" placeholder="Search Car Model"
                                                class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700" />
                                        </div>
                                        <div class="max-h-56 overflow-y-auto" id="offerModelList">
                                            <div class="grid grid-cols-2 gap-4" id="offerModelListItems">
                                                <!-- Models will be loaded dynamically via AJAX -->
                                            </div>
                                            <div id="offerModelEmptyState" class="text-center py-8">
                                                <p class="text-gray-500 text-sm">Select a car brand first</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fuel Type Dropdown -->
                        <div class="w-full relative">
                            <label class="block mb-2 text-base font-medium">Fuel Type</label>
                            <div class="relative w-full">
                                <input type="text" placeholder="Select Fuel Type (Select Car Model first)" id="offerFuelInput" readonly
                                    class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full px-4 pr-10 text-[#99A1AF] focus:outline-none focus:ring-0 focus:border-[#E5E7EB] cursor-not-allowed transition-colors duration-300" disabled />
                                <span id="offerFuelIcon" class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer">
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div id="offerFuelType" class="absolute top-full left-0 w-full bg-white border border-gray-200 z-50 overflow-hidden hidden shadow-lg rounded mt-1">
                                    <div class="p-3">
                                        <div class="grid grid-cols-3 gap-4 max-h-56 overflow-y-auto" id="offerFuelList">
                                            <!-- Fuel types will be loaded dynamically via AJAX -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full relative">
                            <div class="text-[#6A7282] text-base font-normal">
                                Starting from <span class="text-[#CB122D] text-lg font-semibold"><?php 
                                    // Clean and convert starting_price to float
                                    $clean_price = $starting_price;
                                    if (is_string($clean_price)) {
                                        $clean_price = str_replace(',', '', $clean_price);
                                    }
                                    $clean_price = floatval($clean_price ?: 1399);
                                    echo esc_html($price_currency . number_format($clean_price, 0)); 
                                ?>*</span>
                            </div>
                        </div>

                        <button type="button" id="offerBookNowBtn"
                            class="w-full bg-[#FF8300] font-bold text-base text-white h-11 flex justify-center items-center gap-3 hover:bg-[#CB122D] duration-300 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Book Now
                            <span><svg xmlns="http://www.w3.org/2000/svg" class="w-[0.563rem] h-[0.875rem]" viewBox="0 0 11 16"
                                    fill="none">
                                    <path
                                        d="M11 8.00315L5.63239 16H0L1.79304 13.3344L5.36761 8.00315L1.79304 2.67506L0 0H5.63239L11 8.00315Z"
                                        fill="white" />
                                </svg></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($suitable_for)): ?>
<section class="w-full relative bg-white  md:pb-[5.25rem] pb-[2rem]">
    <div class="view">
        <div class="w-full flex flex-col gap-1 md:gap-4">
            <h2 class="relative xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] lg:leading-[3.75rem] font-bold text-[#000000]">
                <?php echo esc_html($suitable_for_title); ?>
            </h2>
            <div class="bg-gradient-to-l from-[#CB122D] to-[#650916] w-[7.375rem] h-3 -skew-x-[22deg]"></div>
        </div>
        <div class="w-full relative md:pt-12 ">
            <div class="grid lg:grid-cols-4 md:grid-cols-4 grid-cols-1 md:gap-12 gap-8">
                <?php foreach ($suitable_for as $index => $item): 
                    $number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                ?>
                <div class="w-full relative group duration-500 h-full hover:lg:-translate-y-2">
                    <div class="number-outline text-[6.25rem] -mb-[4rem]">
                        <?php echo esc_html($number); ?>
                    </div>
                    <div class="flex flex-col gap-y-3 relative z-30">
                        <h3 class="bg-gradient-to-l from-[#CB122D] to-[#650916] text-balance text-transparent bg-clip-text xl:text-2xl lg:text-2xl md:text-xl text-lg font-semibold duration-300">
                            <?php echo esc_html($item['title']); ?>
                        </h3>
                        <p class="text-[#14293C] text-sm font-normal duration-500 ">
                            <?php echo esc_html($item['description']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($terms_conditions_content)): ?>
<section class="w-full relative bg-white  md:pb-[5.25rem] pb-[2rem] md:block hidden">
    <div class="view">
        <div class="flex w-full flex-col gap-4">
            <div class="font-bold text-[#000000] md:text-xl text-lg"><?php echo esc_html($terms_conditions_title); ?></div>
            <div class="w-full relative flex flex-col gap-y-1 prose max-w-none">
                <?php echo wp_kses_post($terms_conditions_content); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($faqs)): ?>
<section class="commonly bg-white relative w-full pb-20">
    <div class="view flex flex-col md:gap-y-12 gap-y-8" id="commonlyAccordion">
        <div class="w-full relative ">
            <div class="w-full flex flex-col gap-y-3">
                <h2 class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-[1.75rem] text-black font-bold">
                    <?php echo esc_html($faqs_title); ?>
                </h2>
                <div class="bg-gradient-to-l from-[#CB122D] to-[#650916] w-[7.375rem] h-3 -skew-x-[22deg]"></div>
            </div>
            <div class="w-full relative flex flex-col md:gap-y-16 gap-y-12 pt-10">
                <div class="flex flex-col gap-6 md:gap-y-5 w-full">
                    <div class="grid md:grid-cols-2 gap-4 md:gap-5">
                        <?php foreach ($faqs as $index => $faq): 
                            $is_first = $index === 0;
                        ?>
                        <div class="accordion-item border border-black">
                            <button class="commonly-header w-full px-6 py-4 flex justify-between items-center text-left font-semibold <?php echo $is_first ? 'text-[#CB122D]' : 'text-gray-800'; ?>">
                                <span class="md:text-xl text-base font-semibold "><?php echo esc_html($faq['question']); ?></span>
                                <span class="shirnk-0 commonly-icon text-white bg-[#CB122D] size-6 flex items-center justify-center"><?php echo $is_first ? '−' : '+'; ?></span>
                            </button>
                            <div class="commonly-body <?php echo !$is_first ? 'hidden' : ''; ?> px-6 pb-4 pt-2 text-base md:text-sm text-[#010101] font-normal">
                                <?php echo esc_html($faq['answer']); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
jQuery(function ($) {
    // Get WordPress theme image URL for JavaScript
    var themeImgUrl = '<?php echo esc_js(get_template_directory_uri() . '/assets/img/fi_19024510.webp'); ?>';
    var assetsImgUrl = '<?php echo esc_js($assets_img_url); ?>';
    
    // SVG arrow icon (default state)
    var defaultArrowSvg = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#6B6B6B" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" /></svg>';
    
    // SVG arrow icon (disabled state)
    var disabledArrowSvg = '<svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none"><path d="M4 5.99805L7.99846 9.99651L11.9969 5.99805" stroke="#999999" stroke-width="1.33282" stroke-linecap="round" stroke-linejoin="round" opacity="0.5" /></svg>';
    
    // Store all dropdown instances for offer form
    var offerAllDropdowns = [];
    
    // Function to close all dropdowns except the current one
    function closeAllOfferDropdowns(exceptDropdownId) {
        offerAllDropdowns.forEach(function(dropdown) {
            if (dropdown.dropdownId !== exceptDropdownId) {
                dropdown.$dropdown.addClass('hidden');
                dropdown.$input.removeClass('open');
                // Keep default background and arrow - no changes needed
            }
        });
    }
    
    // Function to enable/disable dropdown
    function setOfferDropdownEnabled(inputId, enabled) {
        const $input = $('#' + inputId);
        const $icon = $('#' + inputId.replace('Input', 'Icon'));
        
        if (enabled) {
            $input.prop('disabled', false)
                .removeClass('cursor-not-allowed')
                .addClass('cursor-pointer')
                .css('color', '#000');
            
            if ($icon.length) {
                $icon.html(defaultArrowSvg);
            }
        } else {
            $input.prop('disabled', true)
                .removeClass('cursor-pointer')
                .addClass('cursor-not-allowed')
                .css('color', '#99A1AF')
                .val('');
            
            if ($icon.length) {
                $icon.html(disabledArrowSvg);
            }
        }
    }
    
    // Function to fetch car models from API
    function fetchOfferCarModels(carMake) {
        if (!carMake) return;
        
        const $modelListItems = $('#offerModelListItems');
        const $modelEmptyState = $('#offerModelEmptyState');
        const $modelInput = $('#offerModelInput');
        const $modelIcon = $('#offerModelIcon');
        
        $modelInput.val('');
        setOfferDropdownEnabled('offerModelInput', false);
        $modelInput.attr('placeholder', 'Loading models...');
        
        if ($modelIcon.length) {
            $modelIcon.html('<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#CB122D]"></div>');
        }
        
        $modelListItems.html(`
            <div class="col-span-2 text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div>
                <p class="text-gray-500 text-sm mt-2">Loading models...</p>
            </div>
        `);
        $modelEmptyState.addClass('hidden');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'get_car_models',
                car_make: carMake
            },
            success: function(response) {
                if (response.success && response.data && response.data.models) {
                    const models = response.data.models;
                    let html = '';
                    
                    if (models.length > 0) {
                        models.forEach(function(model) {
                            const modelName = model.car_model || '';
                            const modelImageUrl = model.car_model_image_url || '';
                            const hasImage = modelImageUrl && modelImageUrl.trim() !== '';
                            const imageSrc = hasImage ? modelImageUrl : (assetsImgUrl + 'petromin-logo-300x75-1.webp');
                            const fadeClass = hasImage ? '' : 'opacity-40';
                            
                            if (modelName) {
                                html += `
                                    <div class="cursor-pointer text-center" data-value="${modelName}">
                                        <img src="${imageSrc}" 
                                            alt="${modelName}" 
                                            class="w-full h-24 object-contain mb-1 rounded ${fadeClass}" 
                                            loading="lazy" 
                                            fetchpriority="low"
                                            onerror="this.src='${assetsImgUrl}petromin-logo-300x75-1.webp'; this.classList.add('opacity-40');" />
                                        <p class="text-xs">${modelName}</p>
                                    </div>
                                `;
                            }
                        });
                        
                        $modelListItems.html(html);
                        $modelEmptyState.addClass('hidden');
                        setOfferDropdownEnabled('offerModelInput', true);
                        $modelInput.attr('placeholder', 'Select Model');
                    } else {
                        $modelListItems.html('');
                        $modelEmptyState.find('p').text('No car models found');
                        $modelEmptyState.removeClass('hidden');
                        $modelInput.attr('placeholder', 'Select Model (Select Car Brand first)');
                    }
                } else {
                    $modelListItems.html('');
                    $modelEmptyState.find('p').text('No car models found');
                    $modelEmptyState.removeClass('hidden');
                    $modelInput.attr('placeholder', 'Select Model (Select Car Brand first)');
                }
            },
            error: function() {
                $modelListItems.html('');
                $modelEmptyState.find('p').text('Failed to load car models');
                $modelEmptyState.removeClass('hidden');
                $modelInput.attr('placeholder', 'Select Model (Select Car Brand first)');
                // Restore icon on error
                setOfferDropdownEnabled('offerModelInput', false);
            }
        });
    }
    
    // Function to fetch fuel types from API
    function fetchOfferFuelTypes(carMake, carModel) {
        if (!carMake || !carModel) return;
        
        const $fuelList = $('#offerFuelList');
        const $fuelInput = $('#offerFuelInput');
        const $fuelIcon = $('#offerFuelIcon');
        
        $fuelInput.val('');
        setOfferDropdownEnabled('offerFuelInput', false);
        $fuelInput.attr('placeholder', 'Loading fuel types...');
        
        if ($fuelIcon.length) {
            $fuelIcon.html('<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#CB122D]"></div>');
        }
        
        $fuelList.html(`
            <div class="col-span-3 text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#CB122D]"></div>
                <p class="text-gray-500 text-sm mt-2">Loading fuel types...</p>
            </div>
        `);
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'get_fuel_types',
                car_make: carMake,
                car_model: carModel
            },
            success: function(response) {
                if (response.success && response.data && response.data.fuel_types) {
                    const fuelTypes = response.data.fuel_types;
                    let html = '';
                    
                    if (fuelTypes.length > 0) {
                        const defaultSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="md:size-12 size-8 text-[#CB122D]" viewBox="0 0 23 24" fill="none">
                            <g clip-path="url(#clip0_2808_4102)">
                                <path d="M22.3773 20.1645C22.671 19.1058 23.4307 15.9298 23.3604 14.7287C23.2136 12.438 20.3409 6.96505 20.2323 6.75456C19.9068 6.01162 18.9939 5.69588 18.2661 6.04877C17.6086 6.36452 17.3213 7.1446 17.6086 7.81324L19.2428 11.4846H19.1343C18.2278 11.4846 17.5 12.1903 17.5 13.0695V15.1125C16.4786 15.9236 15.8657 17.1556 15.8657 18.431V20.1583C15.6104 20.3007 15.4316 20.5483 15.4316 20.8641V22.8019C15.4316 23.26 15.7955 23.6129 16.2679 23.6129H22.2305C22.7029 23.6129 23.0668 23.26 23.0668 22.8019V20.8641C23.0285 20.5483 22.7412 20.2264 22.3773 20.1583V20.1645ZM16.5935 18.5053C16.5935 17.3785 17.1361 16.3198 18.0873 15.645C18.1959 15.5769 18.2342 15.4716 18.2342 15.3602V13.1376C18.2342 12.6423 18.6683 12.2584 19.1407 12.2584C19.7216 12.2584 19.977 12.6113 20.0472 13.1376C20.0855 13.3852 20.0472 13.28 20.0472 16.1712C20.0472 16.3446 20.194 16.5241 20.4111 16.5241C20.6281 16.5241 20.775 16.3508 20.775 16.1712V13.1376C20.775 12.6794 20.5579 12.2213 20.1557 11.9056L18.2661 7.56559C18.1193 7.24985 18.2661 6.85981 18.5917 6.68646C18.9556 6.5131 19.3577 6.64931 19.5365 7.03935C19.5748 7.07649 22.4475 12.6113 22.5944 14.7658C22.6646 15.8617 21.8666 19.1058 21.6113 20.0592H16.5552V18.5053H16.5935ZM21.2155 22.3128C20.8899 22.3128 20.5962 22.028 20.5962 21.7123C20.5962 21.3965 20.8899 21.1117 21.2155 21.1117C21.541 21.1117 21.8347 21.3965 21.8347 21.7123C21.8347 22.028 21.5793 22.3128 21.2155 22.3128Z" fill="currentColor"></path>
                                <path d="M7.50174 20.0895V18.3621C7.50174 17.093 6.8825 15.8548 5.86746 15.0437V13.0007C5.86746 12.0101 4.92264 11.3043 4.11827 11.4157L5.72063 7.71346C6.01429 7.04482 5.72063 6.26474 5.06309 5.94899C4.33532 5.5961 3.42881 5.91185 3.13515 6.61763C3.02662 6.85908 0.153867 12.332 0.000653554 14.5856C-0.0695694 15.7867 0.690115 18.9627 0.983774 20.0214C0.581589 20.0895 0.294313 20.4114 0.294313 20.7953V22.7331C0.294313 23.1912 0.658195 23.5441 1.1306 23.5441H7.09317C7.56558 23.5441 7.92946 23.1912 7.92946 22.7331V20.7953C7.92946 20.4795 7.75071 20.1947 7.49535 20.0895H7.50174ZM0.734802 14.6227C0.881632 12.5054 3.75439 6.97052 3.79269 6.89623C3.93952 6.54334 4.37363 6.36999 4.73751 6.58049C5.06309 6.72288 5.20992 7.10673 5.06309 7.42867L3.17345 11.7686C2.80957 12.0534 2.55421 12.5116 2.55421 13.0007V16.0343C2.55421 16.2076 2.70104 16.3872 2.9181 16.3872C3.13515 16.3872 3.28198 16.2138 3.28198 16.0343V13.0688C3.3522 12.4001 3.75439 12.1525 4.18849 12.1525C4.6992 12.1525 5.09501 12.5425 5.09501 13.0316V15.2542C5.09501 15.3595 5.13331 15.4647 5.24184 15.539C6.18665 16.2076 6.73567 17.2663 6.73567 18.3993V19.9533H1.67962C1.46257 18.9627 0.658195 15.7185 0.734802 14.6289V14.6227ZM2.15203 22.1697C1.82645 22.1697 1.53279 21.8849 1.53279 21.5691C1.53279 21.2534 1.82645 20.9686 2.15203 20.9686C2.47761 20.9686 2.77127 21.2534 2.77127 21.5691C2.73296 21.8849 2.47761 22.1697 2.15203 22.1697Z" fill="currentColor"></path>
                                <path d="M5.93819 2.94642C6.08502 3.08882 6.30207 3.08882 6.4489 2.94642C6.59573 2.80403 6.59573 2.59353 6.4489 2.45113L5.42748 1.46056C5.28065 1.31816 5.06359 1.31816 4.91676 1.46056C4.76993 1.60295 4.76993 1.81345 4.91676 1.95584L5.93819 2.94642Z" fill="currentColor"></path>
                                <path d="M6.80563 1.85092C6.84393 2.02427 7.06098 2.16666 7.23973 2.09856C7.41848 2.06142 7.56531 1.85092 7.49509 1.67757L7.13121 0.265997C7.0929 0.0926462 6.87585 -0.0497491 6.6971 0.018353C6.51835 0.0554996 6.37152 0.265997 6.44175 0.439348L6.80563 1.85092Z" fill="currentColor"></path>
                                <path d="M3.64486 3.65165L5.10039 4.00454C5.27914 4.04169 5.50258 3.93644 5.5345 3.7569C5.56642 3.57736 5.46427 3.36686 5.27914 3.3359L3.82361 2.98301C3.64486 2.94587 3.42142 3.05111 3.38951 3.23066C3.31928 3.40401 3.45973 3.58355 3.64486 3.65165Z" fill="currentColor"></path>
                                <path d="M17.3205 2.94618C17.4674 3.08857 17.6844 3.08857 17.8312 2.94618L18.8144 1.9556C18.9612 1.8132 18.9612 1.60271 18.8144 1.46031C18.6675 1.31792 18.4505 1.31792 18.3037 1.46031L17.2822 2.45089C17.1737 2.55614 17.1737 2.80378 17.3205 2.94618Z" fill="currentColor"></path>
                                <path d="M16.5238 2.09852C16.7025 2.13567 16.926 2.03042 16.9579 1.85088L17.3218 0.439304C17.3601 0.265954 17.2515 0.0492651 17.0664 0.0183096C16.8813 -0.0126459 16.6642 0.0864117 16.6323 0.265954L16.2684 1.67752C16.1982 1.85088 16.3386 2.06756 16.5238 2.09852Z" fill="currentColor"></path>
                                <path d="M18.2347 3.75806C18.273 3.93141 18.4901 4.07381 18.6688 4.0057L20.1244 3.65281C20.3031 3.61567 20.4499 3.40517 20.3797 3.23182C20.3414 3.05847 20.1243 2.91607 19.9456 2.98417L18.4901 3.33707C18.3113 3.37421 18.1645 3.54756 18.2347 3.75806Z" fill="currentColor"></path>
                                <path d="M16.74 4.07335C16.74 3.75761 16.4846 3.50996 16.159 3.47282C14.0843 3.29947 12.9224 2.48224 12.3415 1.956C12.0861 1.70835 11.6839 1.70835 11.4669 1.956C10.8859 2.48224 9.71768 3.29947 7.64929 3.47282C7.32372 3.50996 7.06836 3.75761 7.06836 4.07335V7.67038C7.06836 9.15006 7.64929 10.5988 8.81755 11.5832C9.61554 12.2889 10.5987 12.7842 11.7286 13.1681C11.8754 13.2052 12.0223 13.2052 12.1308 13.1681C13.2607 12.8152 14.2375 12.2889 15.0419 11.5832C16.1718 10.5926 16.7527 9.15006 16.7527 7.67038V4.07335H16.74ZM11.901 10.0044C10.4837 10.0044 9.31549 8.87765 9.31549 7.49703C9.31549 6.11642 10.4774 4.98964 11.901 4.98964C13.3246 4.98964 14.4865 6.11642 14.4865 7.49703C14.4865 8.87765 13.3246 10.0044 11.901 10.0044Z" fill="currentColor"></path>
                                <path d="M12.5913 6.61721C12.553 6.65435 11.538 7.63874 11.5699 7.60778C11.5316 7.57064 11.1358 7.18679 11.1677 7.21774C11.0209 7.07535 10.8038 7.07535 10.657 7.21774C10.5102 7.36014 10.5102 7.57064 10.657 7.71303L11.3145 8.35071C11.4614 8.49311 11.6784 8.49311 11.8253 8.35071L13.0956 7.11869C13.2425 6.97629 13.2425 6.76579 13.0956 6.6234C12.9488 6.481 12.7318 6.481 12.5849 6.6234L12.5913 6.61721Z" fill="currentColor"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_2808_4102">
                                    <rect width="23" height="24" fill="currentColor"></rect>
                                </clipPath>
                            </defs>
                        </svg>`;
                        
                        fuelTypes.forEach(function(fuelType) {
                            const fuelTypeName = fuelType.fuel_type || '';
                            if (fuelTypeName) {
                                const escapedFuelTypeName = $('<div>').text(fuelTypeName).html();
                                html += `
                                    <div class="flex flex-col gap-2 items-center cursor-pointer text-center w-full" data-value="${escapedFuelTypeName}">
                                        ${defaultSvg}
                                        <p class="md:text-sm text-xs font-normal break-words break-all leading-tight px-1 w-full whitespace-normal">${escapedFuelTypeName}</p>
                                    </div>
                                `;
                            }
                        });
                        
                        $fuelList.html(html);
                        setOfferDropdownEnabled('offerFuelInput', true);
                        $fuelInput.attr('placeholder', 'Select Fuel Type');
                    } else {
                        $fuelList.html('');
                        $fuelInput.attr('placeholder', 'No fuel types found');
                        setOfferDropdownEnabled('offerFuelInput', false);
                    }
                } else {
                    $fuelList.html('');
                    $fuelInput.attr('placeholder', 'No fuel types found');
                    setOfferDropdownEnabled('offerFuelInput', false);
                }
            },
            error: function() {
                $fuelList.html('');
                $fuelInput.attr('placeholder', 'Failed to load fuel types');
                setOfferDropdownEnabled('offerFuelInput', false);
                // Icon is already restored by setOfferDropdownEnabled
            }
        });
    }
    
    function setupOfferDropdown({
        inputId,
        dropdownId,
        itemSelector,
        searchId = null,
        iconId = null,
        enabled = true
    }) {
        const $input = $('#' + inputId);
        const $dropdown = $('#' + dropdownId);
        const $search = searchId ? $('#' + searchId) : null;
        const $icon = iconId ? $('#' + iconId) : null;

        offerAllDropdowns.push({
            dropdownId: dropdownId,
            inputId: inputId,
            $input: $input,
            $dropdown: $dropdown,
            $icon: $icon,
            enabled: enabled
        });
        
        if (!enabled) {
            setOfferDropdownEnabled(inputId, false);
        }

        $input.on('click', function (e) {
            e.stopPropagation();
            
            if ($input.prop('disabled')) {
                return;
            }
            
            const isOpen = !$dropdown.hasClass('hidden');
            
            if (!isOpen) {
                closeAllOfferDropdowns(dropdownId);
            }
            
            $dropdown.toggleClass('hidden');
        });

        $(document).on('click', function (e) {
            var clickedOutside = true;
            offerAllDropdowns.forEach(function(dropdown) {
                if (dropdown.$dropdown.is(e.target) || 
                    dropdown.$input.is(e.target) || 
                    dropdown.$dropdown.has(e.target).length > 0) {
                    clickedOutside = false;
                }
            });
            
            if (clickedOutside) {
                closeAllOfferDropdowns(null);
            }
        });

        $dropdown.on('click', itemSelector, function () {
            const value = $(this).data('value');
            
            $input.val(value);
            $dropdown.addClass('hidden');
            // Keep default background and arrow - no changes needed
            
            if (inputId === 'offerBrandInput') {
                const selectedBrand = value;
                fetchOfferCarModels(selectedBrand);
                setOfferDropdownEnabled('offerFuelInput', false);
            } else if (inputId === 'offerModelInput') {
                const selectedBrand = $('#offerBrandInput').val();
                const selectedModel = value;
                fetchOfferFuelTypes(selectedBrand, selectedModel);
            }
            
            setTimeout(function() {
                if (typeof checkOfferFormValidation === 'function') {
                    checkOfferFormValidation();
                }
            }, 100);
        });

        if ($search && $search.length) {
            $search.on('input', function () {
                const term = $(this).val().toLowerCase();
                let visibleCount = 0;
                
                $dropdown.find(itemSelector).each(function () {
                    const name = $(this).data('value').toLowerCase();
                    const isVisible = name.includes(term);
                    $(this).toggle(isVisible);
                    if (isVisible) {
                        visibleCount++;
                    }
                });
                
                const $emptyState = $dropdown.find('#offerBrandEmptyState, #offerModelEmptyState');
                const $listItems = $dropdown.find('#offerBrandListItems, #offerModelListItems');
                
                if ($emptyState.length && $listItems.length) {
                    if (visibleCount === 0 && term.length > 0) {
                        $listItems.hide();
                        $emptyState.removeClass('hidden');
                    } else {
                        $listItems.show();
                        $emptyState.addClass('hidden');
                    }
                }
            });
        }
    }

    // Form validation function
    var checkOfferFormValidation = function() {
        const $bookNowBtn = $('#offerBookNowBtn');
        if (!$bookNowBtn.length) return;
        
        const city = $('#offerCityInput').val().trim();
        const brand = $('#offerBrandInput').val().trim();
        const model = $('#offerModelInput').val().trim();
        const fuel = $('#offerFuelInput').val().trim();
        
        const cityFilled = city !== '' && !$('#offerCityInput').prop('disabled');
        const brandFilled = brand !== '' && !$('#offerBrandInput').prop('disabled');
        const modelFilled = model !== '' && !$('#offerModelInput').prop('disabled');
        const fuelFilled = fuel !== '' && !$('#offerFuelInput').prop('disabled');
        
        const allFilled = cityFilled && brandFilled && modelFilled && fuelFilled;
        
        if (allFilled) {
            $bookNowBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        } else {
            $bookNowBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        }
    };
    
    // Initialize dropdowns
    $(document).ready(function () {
        setupOfferDropdown({
            inputId: 'offerCityInput',
            dropdownId: 'offerCityDropdown',
            itemSelector: '[data-value]',
            iconId: 'offerCityIcon',
            enabled: true
        });

        setupOfferDropdown({
            inputId: 'offerBrandInput',
            dropdownId: 'offerBrandDropdown',
            itemSelector: '[data-value]',
            searchId: 'offerBrandSearch',
            iconId: 'offerBrandIcon',
            enabled: true
        });

        setupOfferDropdown({
            inputId: 'offerModelInput',
            dropdownId: 'offerModelDropdown',
            itemSelector: '[data-value]',
            searchId: 'offerModelSearch',
            iconId: 'offerModelIcon',
            enabled: false
        });
        
        setupOfferDropdown({
            inputId: 'offerFuelInput',
            dropdownId: 'offerFuelType',
            itemSelector: '[data-value]',
            iconId: 'offerFuelIcon',
            enabled: false
        });
        
        $('#offerCityInput, #offerBrandInput, #offerModelInput, #offerFuelInput').on('input change', function() {
            checkOfferFormValidation();
        });
        
        checkOfferFormValidation();
        
        // Handle Book Now button click
        $('#offerBookNowBtn').on('click', function(e) {
            e.preventDefault();
            
            if ($(this).prop('disabled')) {
                return false;
            }
            
            const city = $('#offerCityInput').val().trim();
            const brand = $('#offerBrandInput').val().trim();
            const model = $('#offerModelInput').val().trim();
            const fuel = $('#offerFuelInput').val().trim();
            
            // Get cost-estimator page URL
            const costEstimatorUrl = '<?php 
                $cost_estimator_pages = get_pages(array(
                    "meta_key" => "_wp_page_template",
                    "meta_value" => "cost-estimator.php"
                ));
                if (!empty($cost_estimator_pages)) {
                    echo esc_js(get_permalink($cost_estimator_pages[0]->ID));
                } else {
                    $cost_estimator_page = get_page_by_path("cost-estimator");
                    if ($cost_estimator_page) {
                        echo esc_js(get_permalink($cost_estimator_page->ID));
                    } else {
                        echo esc_js("#");
                    }
                }
            ?>';
            
            // Build query string
            const params = new URLSearchParams();
            params.append('city', encodeURIComponent(city));
            params.append('brand', encodeURIComponent(brand));
            params.append('model', encodeURIComponent(model));
            params.append('fuel', encodeURIComponent(fuel));
            
            if (costEstimatorUrl && costEstimatorUrl !== '#') {
                const redirectUrl = costEstimatorUrl + (costEstimatorUrl.includes('?') ? '&' : '?') + params.toString();
                window.location.href = redirectUrl;
            } else {
                alert('Error: Cost estimator page not found. Please contact support.');
            }
        });
    });
});
</script>

<script>
    const headers = document.querySelectorAll('#commonlyAccordion .commonly-header');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const body = item.querySelector('.commonly-body');
            const icon = header.querySelector('.commonly-icon');

            const isActive = !body.classList.contains('hidden');

            // Close all
            document.querySelectorAll('#commonlyAccordion .commonly-body').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('#commonlyAccordion .commonly-icon').forEach(el => el.textContent = '+');
            document.querySelectorAll('#commonlyAccordion .commonly-header').forEach(el => {
                el.classList.remove('text-[#CB122D]');
                el.classList.add('text-gray-800');
            });

            // Reopen only if it was not active
            if (!isActive) {
                body.classList.remove('hidden');
                icon.textContent = '−';
                header.classList.add('text-[#CB122D]');
                header.classList.remove('text-gray-800');
            }
        });
    });
</script>
