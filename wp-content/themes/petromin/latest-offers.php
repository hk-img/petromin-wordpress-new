<?php
/* Template Name: offers page */
get_header();

?>

    <div class="hero_section w-full relative z-0 md:h-[26.125rem] h-[13.188rem]">
        <div class="relative w-full h-full overflow-hidden">
            <img fetchpriority="high" decoding="async" loading="eager" src="img/service_hero.webp"
                class="size-full object-cover aspect-[1279/334]" width="1279" height="334"
                alt="Petromin Express, on the go." title="Petromin Express, on the go.">

            <div
                class="lg:w-[40.625rem] md:w-[32rem] w-[19.563rem] absolute lg:bottom-32 md:bottom-24 bottom-20 left-0 flex lg:py-8 py-3.5 lg:px-8 md:px-4 px-4 bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)]  origin-top -skew-x-[18deg]">
                <div class="  flex items-center justify-center skew-x-[18deg] md:pl-20 pl-4">
                    <h1
                        class="xl:text-6xl lg:text-5xl md:text-4xl sm:text-3xl text-3xl text-white font-bold text-balance lg:!leading-[4.5rem]">
                        Latest Offers</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-white relative md:pt-[4.875rem] overflow-hidden pt-9">
        <div class="view flex flex-col md:pr-0">
            <div class="flex items-center justify-between">
                <div class="flex flex-col gap-y-4">
                    <div class="w-full relative mb-3">
                        <h2
                            class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-2xl text-black font-bold leading-tight">
                            <span class="text-[#CB122D] md:block"> Save more</span>
                            on professional, trusted car care
                        </h2>
                        <div
                            class="relative md:pt-4 pt-1.5 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] md:after:h-3 after:h-[0.688rem] after:-skew-x-[18deg] after:left-0">
                        </div>
                    </div>
                    <p class="md:font-medium font-normal text-base md:text-lg text-black">
                        Get the best deals on car servicing, repairs, and maintenance. Limited-time discounts available
                        now!
                    </p>
                </div>
                <div
                    class=" md:flex items-center justify-start hidden origin-bottom z-20 bg-[#CB122D] px-4 shadow-[-6px_6px_0px_-1px_rgba(0,0,0,0.9)] w-56 h-16 transition transform -skew-x-12 duration-150 ease-in-out">
                    <div class="swiper-prev cursor-pointer">
                        <span>
                            <img src="img/fi_19024510.webp"
                                class="text-white size-8 rotate-180 skew-x-12 invert brightness-0" alt="arrow icon"
                                title="arrow icon">
                        </span>
                    </div>
                    <div class="swiper-next cursor-pointer">
                        <span>
                            <img src="img/fi_19024510.webp"
                                class="text-white size-8 skew-x-12 invert brightness-0 mb-[0.188rem] ml-3"
                                alt="arrow icon" title="arrow icon">
                        </span>
                    </div>
                </div>
            </div>
            <div class="w-full relative py-10">
                <div class="swiper latestSaveCarousel relative z-0 py-5 md:py-10 font-inter">
                    <div class="swiper-wrapper  md:py-10">
                        <?php
                        $offers = petromin_get_offers([
                            'posts_per_page' => 10,
                            'order' => 'DESC'
                        ]);

                        if (!empty($offers)):
                            foreach ($offers as $offer):
                        ?>
                        <div class="swiper-slide !h-auto transform transition-transform duration-500 ease-out will-change-transform scale-95 blur-[0.1rem] [&.swiper-slide-active]:scale-110 [&.swiper-slide-active]:blur-[0] [&.swiper-slide-active]:z-30">
                            <a href="<?php echo esc_url($offer['url']); ?>" class="w-full block">
                                <div class="w-full h-full bg-gradient-to-l from-[#CB122D] to-[#650916] p-2 relative overflow-hidden h-full group duration-500">
                                    <img fetchpriority="low" loading="lazy" 
                                        src="<?php echo esc_url($offer['image']['url']); ?>" 
                                        width="334" height="334" 
                                        alt="<?php echo esc_attr($offer['image']['alt'] ?: $offer['title']); ?>" 
                                        title="<?php echo esc_attr($offer['image']['alt'] ?: $offer['title']); ?>"
                                        class="w-full h-full object-cover aspect-square" />
                                    <div class="w-full flex md:flex-row flex-col md:justify-between justify-center items-center gap-2 py-4">
                                        <p class="text-white md:font-bold md:text-base text-sm max-sm:text-center">
                                            <?php echo esc_html($offer['short_description'] ?: $offer['title']); ?>
                                        </p>
                                        <div class="shrink-0">
                                            <button class="px-5 flex space-x-3 items-center bg-[#FF8300] h-12">
                                                <span class="flex items-center gap-1 text-base md:font-bold font-semibold text-white">
                                                    Learn more
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 14 20" fill="none">
                                                        <path d="M13.5294 9.84344L6.92754 19.6791H0L2.20534 16.4006L6.60187 9.84344L2.20534 3.29018L0 0H6.92754L13.5294 9.84344Z" fill="white"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                            endforeach;
                        else:
                        ?>
                        <div class="swiper-slide !h-auto">
                            <div class="w-full h-full bg-gray-200 p-2 flex items-center justify-center">
                                <p class="text-gray-600">No offers available at the moment</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

    </section>

    <section class="bg-white h-full md:pt-24 md:pb-[7.5rem]  pb-[4rem] overflow-hidden">
        <div class="view w-full flex flex-wrap md:gap-0 gap-14 md:justify-between">
            <div class="md:w-2/5 w-full">
                <div class="flex flex-col gap-y-3 md:sticky md:top-4">
                    <div class="w-full relative mb-3">
                        <h2
                            class="xl:text-[3.125rem] lg:-[3rem] md:text-[3rem] text-2xl text-black font-bold leading-tight">

                            Your car’s <span class="text-[#CB122D]">journey </span> at Petromin Express.
                        </h2>
                        <div
                            class="relative md:pt-4 pt-1.5 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[6.75rem] md:after:h-3 after:h-[0.688rem] after:-skew-x-[18deg] after:left-0">
                        </div>
                    </div>
                    <p class="md:font-medium font-normal text-base md:text-lg text-black">
                        Here’s how we ensure your car is safer, stronger and ready for the road.
                    </p>
                </div>
            </div>
            <div class="md:w-3/5 w-full h-full space-y-7 md:pl-20">
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-x-6 md:gap-y-6 gap-y-6">
                    <div
                        class="w-full relative overflow-hidden group duration-500 md:h-[26.063rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2">
                        <img fetchpriority="low" loading="lazy" src="img/car_get.webp" width="304" height="334"
                            alt="Air Filter Change" title="Air Filter Change"
                            class="size-full object-cover aspect-[152/167]">

                        <div class=" absolute top-1 left-3 text-[7.563rem] md:text-[7.75rem] duration-300 font-extrabold text-[#ffffffa8] text-stroke
                                        drop-shadow-[2px_0_0_#ffffff40] 
                                        drop-shadow-[-2px_0_0_white]
                                        drop-shadow-[0_2px_0_white] 
                                        drop-shadow-[0_-2px_0_white] 

                                     ">
                            01
                        </div>

                        <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 p-6">
                            <h3
                                class="text-[#FFFFFF] md:text-[2.125rem] text-[2rem] font-semibold duration-300 group-hover:lg:text-[#CB122D]">
                                Comprehensive inspection
                            </h3>

                        </div>
                    </div>
                    <div
                        class="w-full relative overflow-hidden group duration-500 md:h-[26.063rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2">
                        <img fetchpriority="low" loading="lazy" src="img/car_get.webp" width="304" height="334"
                            alt="Air Filter Change" title="Air Filter Change"
                            class="size-full object-cover aspect-[152/167]">

                        <div class=" absolute top-1 left-3 text-[7.563rem] md:text-[7.75rem] duration-300 font-extrabold text-[#ffffffa8] text-stroke
                                        drop-shadow-[2px_0_0_#ffffff40] 
                                        drop-shadow-[-2px_0_0_white]
                                        drop-shadow-[0_2px_0_white] 
                                        drop-shadow-[0_-2px_0_white] 

                                     ">
                            02
                        </div>

                        <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 p-6">
                            <h3
                                class="text-[#FFFFFF] md:text-[2.125rem] text-[2rem] font-semibold duration-300 group-hover:lg:text-[#CB122D]">
                                Transparent diagnosis
                            </h3>

                        </div>
                    </div>
                    <div
                        class="w-full relative overflow-hidden group duration-500 md:h-[26.063rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2">
                        <img fetchpriority="low" loading="lazy" src="img/car_get.webp" width="304" height="334"
                            alt="Air Filter Change" title="Air Filter Change"
                            class="size-full object-cover aspect-[152/167]">

                        <div class=" absolute top-1 left-3 text-[7.563rem] md:text-[7.75rem] duration-300 font-extrabold text-[#ffffffa8] text-stroke
                                        drop-shadow-[2px_0_0_#ffffff40] 
                                        drop-shadow-[-2px_0_0_white]
                                        drop-shadow-[0_2px_0_white] 
                                        drop-shadow-[0_-2px_0_white] 

                                     ">
                            03
                        </div>

                        <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 p-6">
                            <h3
                                class="text-[#FFFFFF] md:text-[2.125rem] text-[2rem] font-semibold duration-300 group-hover:lg:text-[#CB122D]">
                                Expert servicing
                            </h3>

                        </div>
                    </div>
                    <div
                        class="w-full relative overflow-hidden group duration-500 md:h-[26.063rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2">
                        <img fetchpriority="low" loading="lazy" src="img/car_get.webp" width="304" height="334"
                            alt="Air Filter Change" title="Air Filter Change"
                            class="size-full object-cover aspect-[152/167]">

                        <div class=" absolute top-1 left-3 text-[7.563rem] md:text-[7.75rem] duration-300 font-extrabold text-[#ffffffa8] text-stroke
                                        drop-shadow-[2px_0_0_#ffffff40] 
                                        drop-shadow-[-2px_0_0_white]
                                        drop-shadow-[0_2px_0_white] 
                                        drop-shadow-[0_-2px_0_white] 

                                     ">
                            04
                        </div>

                        <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 p-6">
                            <h3
                                class="text-[#FFFFFF] md:text-[2.125rem] text-[2rem] font-semibold duration-300 group-hover:lg:text-[#CB122D]">
                                Quality assurance

                            </h3>

                        </div>
                    </div>
                    <div
                        class="w-full md:col-span-2 relative overflow-hidden group duration-500 md:h-[26.063rem] h-full before:absolute before:inset-0 before:bg-[#0000004a] before:w-full before:size-full before:lg:opacity-0 before:duration-500 hover:lg:before:opacity-100 hover:lg:-translate-y-2">
                        <img fetchpriority="low" loading="lazy" src="img/car_get.webp" width="304" height="334"
                            alt="Air Filter Change" title="Air Filter Change"
                            class="size-full object-cover aspect-[152/167]">

                        <div class=" absolute top-1 left-3 text-[7.563rem] md:text-[7.75rem] duration-300 font-extrabold text-[#ffffffa8] text-stroke
                                        drop-shadow-[2px_0_0_#ffffff40] 
                                        drop-shadow-[-2px_0_0_white]
                                        drop-shadow-[0_2px_0_white] 
                                        drop-shadow-[0_-2px_0_white] 

                                     ">
                            05
                        </div>

                        <div class="absolute bottom-0 left-0 flex flex-col gap-y-3 duration-500 p-6">
                            <h3
                                class="text-[#FFFFFF] md:text-[2.125rem] text-[2rem] font-semibold duration-300 group-hover:lg:text-[#CB122D]">
                                On time delivery & support
                            </h3>

                        </div>
                    </div>
                </div>


            </div>
        </div>

    </section>

    <section class=" h-full relative">
        <div class=" w-full view flex md:flex-row md:gap-0 gap-16 flex-col relative pr-0">

            <div class="md:w-1/2 w-full h-full flex flex-col relative md:top-60 top-5 pr-4 md:pr-0">
                <div class="w-full flex flex-col gap-y-3">
                    <h2
                        class="xl:text-[3.125rem] lg:text-[3rem] md:text-[3rem] text-[2.625rem] text-black font-bold leading-tight">

                        Car care,
                        <span class="text-[#CB122D] block">now smarter </span>
                    </h2>
                    <p class="md:font-medium font-normal text-base md:text-lg max-w-2xl text-black text-balance">
                        Download the PETROMINIt! app to book car services, track your vehicle’s health, and access
                        exclusive offers – all from your phone.
                    </p>

                </div>

                <div class="flex w-full max-w-md border border-[#B5B5B54A] mt-[2.375rem]  overflow-hidden">

                    <input type="text" placeholder="Enter Contact Number"
                        class="flex-1 py-2.5 pl-4 text-base font-normal bg-white placeholder:text-[#000000A3] bg-transparent outline-none text-[#000000A3]" />


                    <button
                        class="bg-[#FF8300] text-white text-base text-nowrap font-bold px-2 flex items-center gap-1 md:gap-2.5">
                        Get App Link
                        <span class="">
                            <svg class="shrink-0 size-4" width="11" height="16" viewBox="0 0 11 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 8.00315L5.63239 16H0L1.79304 13.3344L5.36761 8.00315L1.79304 2.67506L0 0H5.63239L11 8.00315Z"
                                    fill="white" />
                            </svg>

                        </span>
                    </button>

                </div>

                <div class="flex items-center gap-2 md:gap-4 mt-6 md:mt-7">
                    <a href="" class="md:h-[2.625rem] w-28 md:w-[7.875rem]">
                        <img class="w-full h-full object-cover" src="img/serviceGoogle.webp" alt="google" title="">
                    </a>
                    <a href="" class="md:h-[2.625rem] w-28 md:w-[7.875rem]">
                        <img class="w-full h-full object-cover" src="img/serviceApple.webp" alt="appstore" title="">
                    </a>
                </div>
            </div>

            <div class=" md:w-1/2 w-full ">
                <img fetchpriority="low" loading="lazy" src="img/latest_carcare.webp" width="608" height="573" alt=""
                    class="w-full h-full object-bottom object-contain aspect-[608/573]">
            </div>
        </div>
        <div
            class="w-full absolute bottom-0 bg-gradient-to-r from-[#FFFFFF] bottom-5 to-[#E5E5E5] h-full md:h-[35.625rem] -z-10">
        </div>

    </section>

<?php get_footer(); ?>

<script> 
    document.addEventListener("DOMContentLoaded", function () {
        const swiper = new Swiper(".latestSaveCarousel", {
            speed: 800,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            spaceBetween: 30,
            loop: true,
            centeredSlides: true,
            autoHeight: true,
            pagination: { el: ".swiper-pagination", clickable: true, },
            navigation: { nextEl: ".swiper-next", prevEl: ".swiper-prev", },
            breakpoints: {
                320: { slidesPerView: 1.5, },
                480: { slidesPerView: 3.3, },
                640: { slidesPerView: 3.5, },
                1024: { slidesPerView: 3.3, },
                1350: { slidesPerView: 3.4, },
            },
        });
    }); 
</script>