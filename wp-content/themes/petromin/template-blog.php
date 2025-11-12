<?php
/* Template Name: Blog Page Template */
get_header();

$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$images_url = $assets_url . '/img';

// Get featured posts for slider
$featured_args = [
    'post_type' => 'post',
    'posts_per_page' => 3,
    'meta_query' => [
        [
            'key' => 'featured_post',
            'value' => '1',
            'compare' => '='
        ]
    ]
];
$featured_posts = get_posts($featured_args);

// If no featured posts, get latest posts
if (empty($featured_posts)) {
    $featured_args = [
        'post_type' => 'post',
        'posts_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC'
    ];
    $featured_posts = get_posts($featured_args);
}

// Get categories for filter
$categories = get_categories([
    'hide_empty' => true,
]);

// Main blog posts query
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = 6;

$blog_args = [
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'post_status' => 'publish'
];

// Check if category archive or filter is applied
$selected_category = '';
if (is_category()) {
    $queried_category = get_queried_object();
    if ($queried_category && !is_wp_error($queried_category) && !empty($queried_category->slug)) {
        $selected_category = $queried_category->slug;
    }
}
if (!$selected_category && isset($_GET['category'])) {
    $selected_category = sanitize_text_field($_GET['category']);
}
if ($selected_category) {
    $blog_args['category_name'] = $selected_category;
}

$blog_query = new WP_Query($blog_args);
?>

<section class="hero_section w-full relative h-dvh z-10">
    <div class="relative w-full h-full overflow-hidden">
        <div class="swiper blogHeroSlider relative">
            <div class="swiper-wrapper">
                <?php if ($featured_posts) : 
                    foreach ($featured_posts as $post) : 
                    setup_postdata($post);
                    
                    $featured_image = get_the_post_thumbnail_url($post->ID, 'full');
                    $fallback_image = $images_url . '/blog_hero_img.webp';
                    $categories = get_the_category($post->ID);
                    ?>
                    <div class="swiper-slide md:h-full h-dvh">
                        <img fetchpriority="high" decoding="async" loading="eager" 
                            src="<?php echo esc_url($featured_image ?: $fallback_image); ?>"
                            class="size-full object-cover" 
                            alt="<?php echo esc_attr(get_the_title()); ?>"
                            title="<?php echo esc_attr(get_the_title()); ?>">

                        <div class="view w-full absolute lg:bottom-56 md:bottom-56 bottom-20 left-0 z-30">
                            <div class="flex flex-col md:gap-y-8 gap-y-6">
                                <h1 class="block xl:text-5xl lg:text-4xl text-3xl text-balance text-white font-semibold !leading-tight md:drop-shadow-[0_4px_6px_rgba(0,0,0,0.7)]">
                                    <?php echo esc_html(get_the_title()); ?>
                                </h1>

                                <ul class="flex flex-wrap md:gap-5 gap-2 items-center position-relative z-30">
                                    <?php if ($categories) : 
                                        foreach ($categories as $category) : 
                                            if ($category->slug !== 'uncategorized') : ?>
                                                <li>
                                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                                       class="cursor-pointer w-auto md:px-6 px-4 flex space-x-3 items-center bg-[#FF8300] h-8">
                                                        <span class="flex items-center gap-2 md:text-base text-sm font-semibold text-white">
                                                            <?php echo esc_html($category->name); ?>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php endif;
                                        endforeach;
                                    endif; ?>
                                </ul>

                                <div class="flex md:justify-end md:items-end w-full">
                                    <a href="<?php the_permalink(); ?>" class="px-3 flex space-x-3 items-center bg-[#CB122D] h-10">
                                        <span class="flex items-center gap-1 text-base font-semibold text-white">
                                            Read More
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 15" fill="none">
                                                <path d="M1.62207 0.353577L8.53223 7.26373L8.70898 7.44147L1.62207 14.5284L0.353516 13.2598L6.17188 7.44147L0.530273 1.79889L0.353516 1.62213L1.62207 0.353577Z" 
                                                      fill="white" stroke="white" stroke-width="0.5" />
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; 
                    wp_reset_postdata();
                else : ?>
                    <!-- Fallback slide if no posts -->
                    <div class="swiper-slide md:h-full h-dvh">
                        <img fetchpriority="high" decoding="async" loading="eager" src="<?php echo esc_url($images_url . '/blog_hero_img.webp'); ?>"
                            class="size-full object-cover" alt="Petromin Express Blog" title="Petromin Express Blog">
                        <div class="view w-full absolute lg:bottom-56 md:bottom-56 bottom-20 left-0 z-30">
                            <div class="flex flex-col md:gap-y-8 gap-y-6">
                                <h1 class="block xl:text-5xl lg:text-4xl text-3xl text-balance text-white font-semibold !leading-tight md:drop-shadow-[0_4px_6px_rgba(0,0,0,0.7)]">
                                    Welcome to Petromin Express Blog
                                </h1>
                                <div class="flex md:justify-end md:items-end w-full">
                                    <button class="px-3 flex space-x-3 items-center bg-[#CB122D] h-10">
                                        <span class="flex items-center gap-1 text-base font-semibold text-white">
                                            Explore Blogs
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="view absolute h-full z-10 inset-0 w-full md:flex hidden justify-center items-center translate-y-[-6rem] pointer-events-none">
            <div class="flex w-full items-center justify-between duration-150 ease-in-out">
                <div class="swiper-prev !cursor-pointer !pointer-events-auto !opacity-100">
                    <span>
                        <svg class="md:size-10 size-6" xmlns="http://www.w3.org/2000/svg" width="27" height="41" viewBox="0 0 27 41" fill="none">
                            <path d="M-0.000417709 20.0601L13.12 40.1044H26.8877L22.5048 33.423L13.7673 20.0601L22.5048 6.7051L26.8877 0H13.12L-0.000417709 20.0601Z" fill="white" />
                        </svg>
                    </span>
                </div>
                <div class="swiper-next !cursor-pointer !pointer-events-auto !opacity-100">
                    <span>
                        <svg class="md:size-10 size-6" xmlns="http://www.w3.org/2000/svg" width="26" height="41" viewBox="0 0 26 41" fill="none">
                            <path d="M25.7191 20.0601L13.1691 40.1044H0L4.1923 33.423L12.55 20.0601L4.1923 6.7051L0 0H13.1691L25.7191 20.0601Z" fill="white" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="relative md:pt-28 pt-10 pb-10 md:pb-16">
    <div class="view flex flex-col gap-y-10">

        <!-- Blog Posts Grid -->
        <div class="grid md:grid-cols-3 grid-cols-1 gap-x-6 gap-y-20">
            <?php if ($blog_query->have_posts()) : 
                while ($blog_query->have_posts()) : 
                    $blog_query->the_post();
                    
                    $post_categories = get_the_category();
                    $reading_time = calculate_reading_time(get_the_content());
                    $post_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    $fallback_image = $images_url . '/media_mention_img.webp';
                    ?>
                    
                    <div class="relative w-full flex flex-col md:gap-y-4 gap-y-3 group">
                        <a href="<?php the_permalink(); ?>" class="w-full relative overflow-hidden duration-300">
                            <img fetchpriority="low" loading="lazy" 
                                src="<?php echo esc_url($post_image ?: $fallback_image); ?>"
                                class="size-full group-hover:lg:scale-125 duration-300 aspect-[445/278] object-cover" 
                                alt="<?php echo esc_attr(get_the_title()); ?>" 
                                title="<?php echo esc_attr(get_the_title()); ?>">
                        </a>
                        
                        <div class="text-[#637083] font-normal lg:text-base text-sm">
                            <?php 
                            $author = get_the_author();
                            if ($author) {
                                echo esc_html($author) . ' • ';
                            }
                            echo esc_html(get_the_date('F j, Y')) . ' • ' . $reading_time . ' Min Read'; 
                            ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="flex gap-2 items-baseline">
                            <h2 class="lg:text-xl md:text-lg text-base font-semibold text-[#121212] group-hover:lg:text-[#CB122D] duration-300">
                                <?php echo esc_html(get_the_title()); ?>
                            </h2>
                            <span class="translate-y-1">
                                <svg class="size-5 group-hover:lg:text-[#CB122D] duration-300" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 13 20" fill="none">
                                    <path d="M12.2789 9.69546L5.34274 19.3833H0L2 16.3833L6.5 9.69546L2 2.8833L0 0L5.34274 0L12.2789 9.69546Z" fill="currentColor" />
                                </svg>
                            </span>
                        </a>
                        
                        <p class="text-[#637083] md:text-base text-sm font-normal">
                            <?php 
                            $excerpt = get_the_excerpt();
                            if (empty($excerpt)) {
                                $excerpt = wp_trim_words(get_the_content(), 20);
                            }
                            echo esc_html($excerpt);
                            ?>
                        </p>
                        
                        <?php if ($post_categories) : ?>
                            <ul class="flex flex-wrap w-full items-center gap-3 pt-3 md:pb-0 pb-4">
                                <?php foreach ($post_categories as $category) : 
                                    if ($category->slug !== 'uncategorized') : ?>
                                        <li class="bg-[#FEF3E8] text-[#FF8300] p-3 font-medium md:text-base text-sm">
                                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                                                <?php echo esc_html($category->name); ?>
                                            </a>
                                        </li>
                                    <?php endif;
                                endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                <?php endwhile; 
                wp_reset_postdata();
            else : ?>
                <div class="col-span-3 text-center py-10">
                    <p class="text-[#637083] text-lg">No blog posts found.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More Button -->
        <?php if ($blog_query->max_num_pages > 1) : ?>
            <div class="w-full flex justify-center items-center">
                <button type="button" id="loadMoreBtn"
                    class="bg-white h-12 flex justify-center items-center font-medium text-base text-[#696A75] p-4 border border-[#696A754D] hover:lg:bg-[#CB122D] hover:lg:text-white hover:lg:border-white duration-300"
                    data-page="2" 
                    data-max-pages="<?php echo $blog_query->max_num_pages; ?>"
                    data-category="<?php echo esc_attr($selected_category); ?>">
                    Load More
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize Swiper
    const swiper = new Swiper(".blogHeroSlider", {
        slidesPerView: 1,
        loop: true,
        speed: 1000,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        spaceBetween: 0,
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: { slidesPerView: 1 },
            640: { slidesPerView: 1 },
            1024: { slidesPerView: 1 },
            1350: { slidesPerView: 1 },
        },
    });

    // Load More functionality
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const button = this;
            const page = parseInt(button.getAttribute('data-page'));
            const maxPages = parseInt(button.getAttribute('data-max-pages'));
            const category = button.getAttribute('data-category');
            
            button.disabled = true;
            button.textContent = 'Loading...';
            
            // AJAX request
            const data = new FormData();
            data.append('action', 'load_more_blog_posts');
            data.append('page', page);
            data.append('category', category);
            data.append('nonce', '<?php echo wp_create_nonce("load_more_blog_nonce"); ?>');
            
            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Append new posts
                    const grid = document.querySelector('.grid');
                    grid.insertAdjacentHTML('beforeend', data.data.html);
                    
                    // Update button
                    button.setAttribute('data-page', page + 1);
                    
                    // Hide button if no more pages
                    if (page + 1 > data.data.max_pages) {
                        button.style.display = 'none';
                    }
                } else {
                    console.error('Error loading more posts');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = 'Load More';
            });
        });
    }
});
</script>

<?php get_footer(); ?>
