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
?>

<section class="body_paint_sec md:pt-32 pt-20 md:pb-[6.25rem] pb-[4rem]">
    <div class="view w-full relative">
        <div class="w-full flex flex-col gap-1 md:gap-4 mb-8">
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
                            <div class="w-full relative">
                                <label class="block mb-2 text-base font-medium">City</label>
                                <div class="relative w-full">
                                    <select class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full
                                        px-4 pr-10
                                        text-[#99A1AF]
                                        appearance-none
                                        focus:outline-none focus:ring-0 focus:border-[#E5E7EB]">

                                        <option value="" disabled selected class="text-[#99A1AF]">Select City</option>
                                        <option value="1" class="text-black">Mumbai</option>
                                        <option value="2" class="text-black">Delhi</option>
                                        <option value="3" class="text-black">Jaipur</option>
                                    </select>
                                    <span class="absolute right-4 top-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7"
                                            fill="none">
                                            <path d="M0.833008 0.833008L5.83101 5.83101L10.829 0.833008"
                                                stroke="#99A1AF" stroke-width="1.666" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full relative">
                                <label class="block mb-2 text-base font-medium">Car Brand</label>
                                <div class="relative w-full">
                                    <select class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full
                                        px-4 pr-10
                                        text-[#99A1AF]
                                        appearance-none
                                        focus:outline-none focus:ring-0 focus:border-[#E5E7EB]">

                                        <option value="" disabled selected class="text-[#99A1AF]">Select Brand</option>
                                        <option value="1" class="text-black">Mumbai</option>
                                        <option value="2" class="text-black">Delhi</option>
                                        <option value="3" class="text-black">Jaipur</option>
                                    </select>
                                    <span class="absolute right-4 top-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7"
                                            fill="none">
                                            <path d="M0.833008 0.833008L5.83101 5.83101L10.829 0.833008"
                                                stroke="#99A1AF" stroke-width="1.666" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full relative">
                                <label class="block mb-2 text-base font-medium">Car Model</label>
                                <div class="relative w-full">
                                    <select class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full
                                        px-4 pr-10
                                        text-[#99A1AF]
                                        appearance-none
                                        focus:outline-none focus:ring-0 focus:border-[#E5E7EB]">

                                        <option value="" disabled selected class="text-[#99A1AF]">Select Model</option>
                                        <option value="1" class="text-black">Mumbai</option>
                                        <option value="2" class="text-black">Delhi</option>
                                        <option value="3" class="text-black">Jaipur</option>
                                    </select>
                                    <span class="absolute right-4 top-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7"
                                            fill="none">
                                            <path d="M0.833008 0.833008L5.83101 5.83101L10.829 0.833008"
                                                stroke="#99A1AF" stroke-width="1.666" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full relative">
                                <label class="block mb-2 text-base font-medium">Fuel Type</label>
                                <div class="relative w-full">
                                    <select class="bg-[#F8F8F8] text-base font-normal border border-[#E5E7EB] rounded h-[2.994rem] w-full
                                        px-4 pr-10
                                        text-[#99A1AF]
                                        appearance-none
                                        focus:outline-none focus:ring-0 focus:border-[#E5E7EB]">

                                        <option value="" disabled selected class="text-[#99A1AF]">Select Fuel Type
                                        </option>
                                        <option value="1" class="text-black">Mumbai</option>
                                        <option value="2" class="text-black">Delhi</option>
                                        <option value="3" class="text-black">Jaipur</option>
                                    </select>
                                    <span class="absolute right-4 top-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7"
                                            fill="none">
                                            <path d="M0.833008 0.833008L5.83101 5.83101L10.829 0.833008"
                                                stroke="#99A1AF" stroke-width="1.666" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full relative">
                                <div class="text-[#6A7282] text-base font-normal">
                                    Starting from <span class="text-[#CB122D] text-lg font-semibold"> ₹1,399*</span>
                                </div>
                            </div>

                            <button type="button"
                                class="w-full bg-[#FF8300] font-bold text-base text-white h-11 flex justify-center items-center gap-3 hover:bg-[#CB122D] duration-300">
                                Book Now
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="9" height="14" viewBox="0 0 11 16"
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
