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

// Process FAQ items similar to home page
$faq_processed_items = [];
if (!empty($faqs) && is_array($faqs)) {
    foreach ($faqs as $faq_item) {
        $faq_question = trim($faq_item['question'] ?? '');
        $faq_answer = trim($faq_item['answer'] ?? '');
        $faq_active = false; // Default to closed
        
        if ($faq_question !== '' && $faq_answer !== '') {
            $faq_processed_items[] = [
                'question' => $faq_question,
                'answer' => $faq_answer,
                'is_active' => $faq_active,
            ];
        }
    }
}

// Split FAQ items into two columns (like home page)
$faq_total_items = count($faq_processed_items);
$faq_first_column_count = ceil($faq_total_items / 2);
$faq_first_column_items = array_slice($faq_processed_items, 0, $faq_first_column_count);
$faq_second_column_items = array_slice($faq_processed_items, $faq_first_column_count);

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
                                    class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full px-4 pr-10 text-[#99A1AF] focus:outline-none focus:ring-0 focus:border-[#E5E7EB] cursor-pointer transition-colors duration-300 [@supports(-webkit-touch-callout:none)]:!bg-white" />
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
                                    class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full px-4 pr-10 text-[#99A1AF] focus:outline-none focus:ring-0 focus:border-[#E5E7EB] cursor-pointer transition-colors duration-300 [@supports(-webkit-touch-callout:none)]:!bg-white" />
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
                                                class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700 [@supports(-webkit-touch-callout:none)]:!bg-white" />
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
                                    class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full px-4 pr-10 text-[#99A1AF] focus:outline-none focus:ring-0 focus:border-[#E5E7EB] cursor-not-allowed transition-colors duration-300 [@supports(-webkit-touch-callout:none)]:!bg-white" disabled />
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
                                                class="bg-transparent flex-1 focus:outline-none text-sm text-gray-700 [@supports(-webkit-touch-callout:none)]:!bg-white" />
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
                                    class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full px-4 pr-10 text-[#99A1AF] focus:outline-none focus:ring-0 focus:border-[#E5E7EB] cursor-not-allowed transition-colors duration-300 [@supports(-webkit-touch-callout:none)]:!bg-white" disabled />
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

<?php if (!empty($faq_processed_items)): ?>
<section class="w-full relative md:pt-[4rem] pt-[3rem] md:pb-[6rem] pb-[2.625rem] font-inter">
    <div class="view" id="faqAccordion">
        <div class="flex items-center justify-between md:pb-6 pb-4">
            <h2
                class="relative text-[1.75rem] md:text-3xl lg:text-4xl 2xl:text-[3.125rem] 2xl:!leading-[3.313rem] !leading-12 font-inter font-bold text-black pr-2 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] lg:after:w-[6.75rem] after:w-20 lg:after:h-3 after:h-[0.625rem] after:-skew-x-[18deg] after:-bottom-6 after:left-0">
                    <?php echo esc_html($faqs_title); ?>
                </h2>
            </div>

        <div class="grid md:grid-cols-2 gap-6 pt-16">
            <!-- First Column -->
            <div class="space-y-5">
                <?php foreach ($faq_first_column_items as $index => $faq_item): ?>
                <?php
                    $faq_is_active = $faq_item['is_active'];
                    $faq_item_classes = 'accordion-item border border-black';
                    $faq_header_classes = 'accordion-header w-full px-6 py-4 flex justify-between items-center text-left font-semibold ' . ($faq_is_active ? 'text-[#CB122D]' : 'text-gray-800');
                    $faq_icon_text = $faq_is_active ? '−' : '+';
                    $faq_body_classes = 'accordion-body px-6 pb-4 pt-2 text-sm text-[#010101] font-normal' . ($faq_is_active ? '' : ' hidden');
                    ?>
                <div class="<?php echo esc_attr($faq_item_classes); ?>">
                    <button class="<?php echo esc_attr($faq_header_classes); ?>">
                        <span><?php echo esc_html($faq_item['question']); ?></span>
                        <span
                            class="accordion-icon text-white bg-[#CB122D] size-6 flex items-center justify-center"><?php echo esc_html($faq_icon_text); ?></span>
                            </button>
                    <div class="<?php echo esc_attr($faq_body_classes); ?>">
                        <?php echo nl2br(esc_html($faq_item['answer'])); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
            </div>

            <!-- Second Column -->
            <div class="space-y-5">
                <?php foreach ($faq_second_column_items as $index => $faq_item): ?>
                <?php
                    $faq_is_active = $faq_item['is_active'];
                    $faq_item_classes = 'accordion-item border border-black';
                    $faq_header_classes = 'accordion-header w-full px-6 py-4 flex justify-between items-center text-left font-semibold ' . ($faq_is_active ? 'text-[#CB122D]' : 'text-gray-800');
                    $faq_icon_text = $faq_is_active ? '−' : '+';
                    $faq_body_classes = 'accordion-body px-6 pb-4 pt-2 text-sm text-[#010101] font-normal' . ($faq_is_active ? '' : ' hidden');
                    ?>
                <div class="<?php echo esc_attr($faq_item_classes); ?>">
                    <button class="<?php echo esc_attr($faq_header_classes); ?>">
                        <span><?php echo esc_html($faq_item['question']); ?></span>
                        <span
                            class="accordion-icon text-white bg-[#CB122D] size-6 flex items-center justify-center"><?php echo esc_html($faq_icon_text); ?></span>
                    </button>
                    <div class="<?php echo esc_attr($faq_body_classes); ?>">
                        <?php echo nl2br(esc_html($faq_item['answer'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
const headers = document.querySelectorAll('#faqAccordion .accordion-header');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
        const body = item.querySelector('.accordion-body');
        const icon = header.querySelector('.accordion-icon');

            const isActive = !body.classList.contains('hidden');

            // Close all
        document.querySelectorAll('#faqAccordion .accordion-body').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('#faqAccordion .accordion-icon').forEach(el => el.textContent = '+');
        document.querySelectorAll('#faqAccordion .accordion-header').forEach(el => {
                el.classList.remove('text-[#CB122D]');
                el.classList.add('text-gray-800');
            });

            // Reopen only if it was not active
            if (!isActive) {
                body.classList.remove('hidden');
                icon.textContent = '−';
            header.classList.remove('text-gray-800');
                header.classList.add('text-[#CB122D]');
        }
    });
});
</script>
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
                        // Fuel type image (assets/img/fuelTypeImage.webp)
                        const fuelTypeImg = '<img src="' + assetsImgUrl + 'fuelTypeImage.webp" alt="" class="md:w-12 md:h-12 w-8 h-8 object-contain">';
                        
                        fuelTypes.forEach(function(fuelType) {
                            const fuelTypeName = fuelType.fuel_type || '';
                            if (fuelTypeName) {
                                const escapedFuelTypeName = $('<div>').text(fuelTypeName).html();
                                html += `
                                    <div class="flex flex-col gap-2 items-center cursor-pointer text-center w-full" data-value="${escapedFuelTypeName}">
                                        ${fuelTypeImg}
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
