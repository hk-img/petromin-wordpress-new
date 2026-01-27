<?php
/**
 * The template for displaying all single posts
 */
get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();

$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$images_url = $assets_url . '/img';
?>

<section class="pb-8 pt-16 md:mt-16 mt-3">
    <div class="view flex md:flex-row flex-col gap-[2.688rem] md:gap-24">
        <div class="flex md:w-2/3 flex-col md:gap-y-8 gap-y-[1.438rem]">
            <div class="flex flex-col gap-5 md:pb-0">
                <!-- Back to Blogs Link -->
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"
                    class="flex gap-1.5 md:hidden text-sm font-medium item-center">
                    <div class="size-5">
                        <img src="<?php echo esc_url($images_url . '/blog_arrow.webp'); ?>" alt="arrow"
                            class="w-full object-cover h-full">
                    </div>
                    Back to Blogs
                </a>

                <div class="flex flex-col gap-4">
                    <h1
                        class="lg:text-4xl md:text-3xl text-[1.75rem] font-semibold leading-tight md:leading-10 text-[#181A2A]">
                        <?php the_title(); ?>
                    </h1>

                    <!-- Excerpt/Description -->
                    <?php if (has_excerpt()) : ?>
                    <p class="text-[#3B3C4A] font-normal leading-6 md:leading-8 lg:text-xl md:text-lg text-base">
                        <?php echo esc_html(get_the_excerpt()); ?>
                    </p>
                    <?php else : ?>
                    <p class="text-[#3B3C4A] font-normal leading-6 md:leading-8 lg:text-xl md:text-lg text-base">
                        <?php echo esc_html(wp_trim_words(get_the_content())); ?>
                    </p>
                    <?php endif; ?>

                    <!-- Author, Date & Reading Time -->
                    <div class="flex items-center gap-4 text-sm text-gray-500 md:pt-1">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-gray-400">
                                <?php 
                                $author_avatar = get_avatar(get_the_author_meta('ID'), 28, '', '', ['class' => 'w-full h-full rounded-full']);
                                if ($author_avatar) {
                                    echo $author_avatar;
                                } else {
                                    echo '<img src="' . esc_url($images_url . '/blog_person.webp') . '" alt="' . esc_attr(get_the_author()) . '" class="w-full h-full rounded-full">';
                                }
                                ?>
                            </div>
                            <div class="md:text-sm text-xs font-normal text-[#696A75]">
                                <?php 
                                echo esc_html(get_the_author()) . ' • ' . 
                                     esc_html(get_the_date('F j, Y')) . ' • ' . 
                                     calculate_reading_time(get_the_content()) . ' Min Read'; 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <?php 
                $categories = get_the_category();
                if ($categories) : ?>
                <div class="flex flex-wrap md:border-0 border-b border-[#E0E5EB] md:pb-0 pb-8 gap-2.5 pt-2.5">
                    <?php foreach ($categories as $category) : 
                            if ($category->slug !== 'uncategorized') : ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                        class="bg-[#FF8300] text-white text-xs md:text-sm font-semibold px-3 py-2 md:p-2.5 hover:bg-[#e67600] transition-colors">
                        <?php echo esc_html($category->name); ?>
                    </a>
                    <?php endif;
                        endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Featured Image/Banner -->
            <?php if (has_post_thumbnail()) : ?>
            <div class="md:h-[35.188rem] h-[18.75rem] overflow-hidden rounded-lg">
                <img src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>"
                    alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover">
            </div>
            <?php else : ?>
            <div class="md:h-[35.188rem] h-[18.75rem] overflow-hidden rounded-lg">
                <img src="<?php echo esc_url($images_url . '/blog_banner.webp'); ?>"
                    alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover">
            </div>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="max-w-none wp-block-post-content">
                <?php the_content(); ?>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="md:w-1/3 w-full">
            <div class="flex flex-col gap-y-8 sticky top-24">
                <div class="w-full flex flex-col gap-y-4">
                    <div class="w-full relative">
                        <h2 class="md:text-3xl md:block hidden text-[1.75rem] text-black font-semibold">
                            Check This Out
                        </h2>
                        <h2 class="md:text-3xl md:hidden block text-[1.75rem] text-black font-semibold">
                            It's best you know these
                        </h2>
                        <div
                            class="relative pt-3 after:absolute after:bg-gradient-to-l from-[#CB122D] via-[#CB122D] to-[#650916] after:w-[7.438rem] md:after:h-2.5 after:h-[0.688rem] after:-skew-x-[18deg] after:left-0">
                        </div>
                    </div>

                    <!-- Related Posts -->
                    <?php
                    $related_args = [
                        'post_type' => 'post',
                        'posts_per_page' => 4,
                        'post__not_in' => [get_the_ID()],
                        'orderby' => 'rand',
                        'post_status' => 'publish'
                    ];
                    $related_posts = new WP_Query($related_args);
                    
                    if ($related_posts->have_posts()) : ?>
                    <div class="flex flex-col pt-9 md:pt-3 gap-6 md:gap-4">
                        <?php while ($related_posts->have_posts()) : 
                                $related_posts->the_post();
                                
                                $post_image = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                                $fallback_images = petromin_fallbacks_enabled() ? [
                                    $images_url . '/checkThis1.webp',
                                    $images_url . '/checkThis2.webp',
                                    $images_url . '/checkThis3.webp',
                                    $images_url . '/checkThis4.webp'
                                ] : [];
                                $random_fallback = !empty($fallback_images) ? $fallback_images[array_rand($fallback_images)] : '';
                                ?>

                        <div class="flex gap-4 group cursor-pointer"
                            onclick="window.location.href='<?php the_permalink(); ?>'">
                            <div class="w-[6.188rem] shrink-0 h-[6.188rem] rounded-lg overflow-hidden">
                                <?php if ($post_image || $random_fallback): ?>
                                <img src="<?php echo esc_url($post_image ?: $random_fallback); ?>"
                                    alt="<?php echo esc_attr(get_the_title()); ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col justify-between flex-1">
                                <span
                                    class="md:text-lg text-base text-balance font-semibold text-[#181A2A] group-hover:text-[#CB122D] transition-colors">
                                    <?php echo esc_html(wp_trim_words(get_the_title(), 8)); ?>
                                </span>
                                <div
                                    class="flex flex-row items-center gap-1.5 text-[#637083] text-xs md:text-sm font-normal mt-2">
                                    <?php echo esc_html(get_the_date('F j, Y')) . ' . ' . calculate_reading_time(get_the_content()) . ' Min Read'; ?>
                                    <span>
                                        <svg class="size-4 shrink-0" viewBox="0 0 16 16"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.66406 4.66479H11.3281V11.3288" stroke="#CB122D"
                                                stroke-width="1.33281" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M4.66406 11.3288L11.3281 4.66479" stroke="#CB122D"
                                                stroke-width="1.33281" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; 
                            wp_reset_postdata(); ?>
                    </div>
                    <?php else : ?>
                    <!-- Fallback Related Posts -->
                    <!-- <div class="flex flex-col pt-9 md:pt-3 gap-6 md:gap-4">
                            <?php 
                            $fallback_posts = [
                                [
                                    'title' => '5 Signs Your Car Needs Immediate Service',
                                    'image' => 'checkThis2.webp',
                                    'date' => 'August 18, 2025',
                                    'time' => '3'
                                ],
                                [
                                    'title' => 'Understanding Your Vehicle\'s Engine Health',
                                    'image' => 'checkThis1.webp',
                                    'date' => 'August 18, 2025', 
                                    'time' => '3'
                                ],
                                [
                                    'title' => 'Preventive Maintenance: Save Money in the Long Run',
                                    'image' => 'checkThis3.webp',
                                    'date' => 'August 18, 2025',
                                    'time' => '3'
                                ],
                                [
                                    'title' => 'Genuine Parts vs Aftermarket: What You Need to Know',
                                    'image' => 'checkThis4.webp', 
                                    'date' => 'August 18, 2025',
                                    'time' => '3'
                                ]
                            ];
                            
                            foreach ($fallback_posts as $post) : ?>
                                <div class="flex gap-4">
                                    <div class="w-[6.188rem] shrink-0 h-[6.188rem] rounded-lg overflow-hidden">
                                        <img src="<?php echo esc_url($images_url . '/' . $post['image']); ?>" 
                                             alt="<?php echo esc_attr($post['title']); ?>" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex flex-col justify-between flex-1">
                                        <span class="md:text-lg text-base text-balance font-semibold text-[#181A2A]">
                                            <?php echo esc_html($post['title']); ?>
                                        </span>
                                        <div class="flex flex-row items-center gap-1.5 text-[#637083] text-xs md:text-sm font-normal mt-2">
                                            <?php echo esc_html($post['date']) . ' . ' . esc_html($post['time']) . ' Min Read'; ?>
                                            <span>
                                                <svg class="size-4 shrink-0" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.66406 4.66479H11.3281V11.3288" stroke="#CB122D" stroke-width="1.33281" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M4.66406 11.3288L11.3281 4.66479" stroke="#CB122D" stroke-width="1.33281" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div> -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
    endwhile;
endif;

get_footer();
?>