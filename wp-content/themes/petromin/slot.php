<?php
/* Template Name: slot page */
get_header();

// Get theme assets directory URL for images
$img_url = get_template_directory_uri() . '/assets/img/';

?>

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
                        <span class="text-gray-400 font-medium text-sm tracking-wide">
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
                        <span class="text-[#20BD99] font-medium text-sm tracking-wide border-b-2 border-[#20BD99]">
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
                        <div class="w-full bg-[#F1FAF1] border border-[#D1EAD1] p-6 flex justify-between items-center md:rounded-none rounded-lg">
                            <div class="flex items-center gap-3">
                                <span>
                                    <img src="<?php echo esc_url($img_url); ?>success-check-icon.svg" alt="success check" class="size-9" />
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div class="text-base text-[#2F2F2F] font-semibold">Petromin Express - Indiranagar</div>
                                    <div class="text-[#637083] font-normal text-xs">Indiranagar • 2.5 km</div>
                                </div>
                            </div>
                            <a href="" class="text-[#6B6B6B] font-medium text-sm duration-300 hover:underline">Change</a>
                        </div>
                    </div>
                    <div class="w-full md:p-8 p-4 md:rounded-none rounded-xl flex flex-col gap-y-6 bg-white border border-[#E5E5E5] shadow-[0_0.125rem_0.25rem_-0.125rem_#0000001A]">
                        <div class="flex flex-col gap-y-2">
                            <h2 class="text-[#2F2F2F] font-semibold lg:text-xl text-lg">Select Date & Time</h2>
                            <p class="text-[#6B6B6B] text-sm font-medium">Choose your preferred slot</p>
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            <label for="" class="block text-sm font-bold text-[#2F2F2F]">Select Date</label>
                            <div class="grid lg:grid-cols-5 md:grid-cols-4 grid-cols-3 gap-4 group/dateSelect">
                                <label for="dateCheck1" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[4.188rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="date" id="dateCheck1" class="hidden" checked>
                                    <div class="flex flex-col gap-1 justify-center items-center">
                                        <div class="md:text-xl text-lg font-bold text-[#2F2F2F]">21</div>
                                        <div class="text-[#6B6B6B] font-medium text-xs">FRI</div>
                                    </div>
                                </label>
                                <label for="dateCheck2" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[4.188rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="date" id="dateCheck2" class="hidden">
                                    <div class="flex flex-col gap-1 justify-center items-center">
                                        <div class="md:text-xl text-lg font-bold text-[#2F2F2F]">22</div>
                                        <div class="text-[#6B6B6B] font-medium text-xs">FRI</div>
                                    </div>
                                </label>
                                <label for="dateCheck3" class="group/p md:rounded-none rounded-lg bg-[#F3F3F3] cursor-pointer border border-[#E5E5E5] w-full h-[4.188rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="date" id="dateCheck3" class="hidden">
                                    <div class="flex flex-col gap-1 justify-center items-center">
                                        <div class="md:text-xl text-lg font-bold text-[#2F2F2F]">23</div>
                                        <div class="text-[#6B6B6B] font-medium text-xs">SUN</div>
                                    </div>
                                </label>
                                <label for="dateCheck4" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[4.188rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="date" id="dateCheck4" class="hidden">
                                    <div class="flex flex-col gap-1 justify-center items-center">
                                        <div class="md:text-xl text-lg font-bold text-[#2F2F2F]">24</div>
                                        <div class="text-[#6B6B6B] font-medium text-xs">MON</div>
                                    </div>
                                </label>
                                <label for="dateCheck5" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[4.188rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="date" id="dateCheck5" class="hidden">
                                    <div class="flex flex-col gap-1 justify-center items-center">
                                        <div class="md:text-xl text-lg font-bold text-[#2F2F2F]">25</div>
                                        <div class="text-[#6B6B6B] font-medium text-xs">TUE</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            <label for="" class="flex items-center gap-2 text-sm font-bold text-[#2F2F2F] ">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <g clip-path="url(#clip0_4118_5225)">
                                            <path d="M8 1.33301V6.66584" stroke="#FF8300" stroke-width="1.33321"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3.28516 7.28613L4.22507 8.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M1.33203 11.999H2.66524" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M13.332 11.9989H14.6652" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12.7133 7.28613L11.7734 8.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M14.6641 14.665H1.33203" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M5.33203 3.99942L7.99845 1.33301L10.6649 3.99942"
                                                stroke="#FF8300" stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10.6649 11.9984C10.6649 11.2913 10.3839 10.6131 9.88389 10.113C9.38384 9.61296 8.70562 9.33203 7.99845 9.33203C7.29127 9.33203 6.61306 9.61296 6.11301 10.113C5.61296 10.6131 5.33203 11.2913 5.33203 11.9984"
                                                stroke="#FF8300" stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_4118_5225">
                                                <rect width="15.9985" height="15.9985" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                                Early Morning
                            </label>
                            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4">
                                <label for="morningTimeCheck1" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="morningTime" id="morningTimeCheck1" class="hidden" checked>
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">08:00 - 09:00 AM</div>
                                </label>
                                <label for="morningTimeCheck2" class="group/p md:rounded-none rounded-lg bg-[#F7F7F7] cursor-pointer border border-[#EDEDED] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="morningTime" id="morningTimeCheck2" class="hidden">
                                    <div class="text-sm font-medium text-[#AFAFAF] group-has-[:checked]/p:text-[#CB122D]">09:00 - 10:00 AM</div>
                                </label>
                                <label for="morningTimeCheck3" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="morningTime" id="morningTimeCheck3" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">10:00 - 11:00 AM</div>
                                </label>
                                <label for="morningTimeCheck4" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="morningTime" id="morningTimeCheck4" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">11:00 AM - 12:00 PM</div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            <label for="" class="flex items-center gap-2 text-sm font-bold text-[#2F2F2F]">
                                <span>
                                    <svg class="size-4" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_4118_5247)">
                                            <path
                                                d="M7.99845 10.6658C9.47107 10.6658 10.6649 9.47204 10.6649 7.99942C10.6649 6.5268 9.47107 5.33301 7.99845 5.33301C6.52583 5.33301 5.33203 6.5268 5.33203 7.99942C5.33203 9.47204 6.52583 10.6658 7.99845 10.6658Z"
                                                stroke="#FF8300" stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M8 1.33301V2.66622" stroke="#FF8300" stroke-width="1.33321"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8 13.332V14.6652" stroke="#FF8300" stroke-width="1.33321"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3.28516 3.28613L4.22507 4.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M11.7734 11.7725L12.7133 12.7124" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M1.33203 7.99902H2.66524" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M13.332 7.99927H14.6652" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M4.22507 11.7725L3.28516 12.7124" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12.7133 3.28613L11.7734 4.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_4118_5247">
                                                <rect width="15.9985" height="15.9985" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                                Afternoon
                            </label>
                            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4 group/timeCheck">
                                <label for="afternoonTimeCheck1" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="afternoonTime" id="afternoonTimeCheck1" class="hidden" checked>
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">12:00 - 01:00 PM</div>
                                </label>
                                <label for="afternoonTimeCheck2" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="afternoonTime" id="afternoonTimeCheck2" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">01:00 - 02:00 PM</div>
                                </label>
                                <label for="afternoonTimeCheck3" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="afternoonTime" id="afternoonTimeCheck3" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">02:00 - 03:00 PM</div>
                                </label>
                                <label for="afternoonTimeCheck4" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="afternoonTime" id="afternoonTimeCheck4" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">03:00 - 04:00 PM</div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            <label for="" class="flex items-center gap-2 text-sm font-bold text-[#2F2F2F]">
                                <span>
                                    <svg class="size-4" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_4118_5270)">
                                            <path d="M8 6.66584V1.33301" stroke="#FF8300" stroke-width="1.33321"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3.28516 7.28613L4.22507 8.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M1.33203 11.999H2.66524" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M13.332 11.9989H14.6652" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12.7133 7.28613L11.7734 8.22604" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M14.6641 14.665H1.33203" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M10.6649 4L7.99845 6.66642L5.33203 4" stroke="#FF8300"
                                                stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10.6649 11.9984C10.6649 11.2913 10.3839 10.6131 9.88389 10.113C9.38384 9.61296 8.70562 9.33203 7.99845 9.33203C7.29127 9.33203 6.61306 9.61296 6.11301 10.113C5.61296 10.6131 5.33203 11.2913 5.33203 11.9984"
                                                stroke="#FF8300" stroke-width="1.33321" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_4118_5270">
                                                <rect width="15.9985" height="15.9985" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                                Evening
                            </label>
                            <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4 group/timeCheck">
                                <label for="eveningTimeCheck1" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="eveningTime" id="eveningTimeCheck1" class="hidden" checked>
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">04:00 - 05:00 PM</div>
                                </label>
                                <label for="eveningTimeCheck2" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:bg-[#FFF0F0] has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="eveningTime" id="eveningTimeCheck2" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">05:00 - 06:00 PM</div>
                                </label>
                                <label for="eveningTimeCheck3" class="group/p md:rounded-none rounded-lg bg-white cursor-pointer border border-[#E5E5E5] w-full h-[2.688rem] flex flex-col justify-center items-center has-[:checked]:border-[#C8102E] has-[:checked]:shadow-[0_0_0_1_#C8102E]">
                                    <input type="radio" name="eveningTime" id="eveningTimeCheck3" class="hidden">
                                    <div class="text-sm font-medium text-[#2F2F2F] group-has-[:checked]/p:text-[#CB122D]">06:00 - 07:00 PM</div>
                                </label>
                            </div>
                        </div>
                        <a href="" class="w-1/2 bg-[#6B6B6B] w-full rounded-lg h-[2.875rem] md:hidden flex justify-center items-center text-sm font-bold text-white duration-500 hover:bg-[#CB122D]">Continue to Payment</a>
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
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', populateBookingSummary);
    } else {
        populateBookingSummary();
    }
})();
</script>

<?php get_footer(); ?>