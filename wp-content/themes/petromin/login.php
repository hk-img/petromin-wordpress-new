<?php
/* Template Name: login page */
get_header();

$assets_url = trailingslashit(get_template_directory_uri()) . 'assets';
$images_url = $assets_url . '/img';

$hero_defaults = [
    'title' => 'Welcome!',
    'subtitle' => 'Book your next service, track repairs, and get exclusive offers.',
    'image' => [
        'url' => $images_url . '/login_img.webp',
        'alt' => 'Petromin Express, on the go.',
    ],
    'form' => [
        'placeholder' => 'Enter Email or Contact no',
        'button_text' => 'Login',
        'signup_text' => 'Don\'t have an account?',
        'signup_link' => '#',
        'signup_link_text' => 'Sign up',
        'terms_text' => 'By continuing, you agree to our Terms of Service and Privacy Policy'
    ]
];

// Get ACF Fields
$hero_field = function_exists('get_field') ? (get_field('hero_section') ?: []) : [];
$hero_title = trim($hero_field['hero_title'] ?? '') ?: $hero_defaults['title'];
$hero_subtitle = trim($hero_field['hero_subtitle'] ?? '') ?: $hero_defaults['subtitle'];
$hero_image_data = petromin_get_acf_image_data($hero_field['hero_image'] ?? null, 'full', $hero_defaults['image']['url'], $hero_defaults['image']['alt']);

$form_field = function_exists('get_field') ? (get_field('form_section') ?: []) : [];
$form_placeholder = trim($form_field['placeholder_text'] ?? '') ?: $hero_defaults['form']['placeholder'];
$form_button_text = trim($form_field['button_text'] ?? '') ?: $hero_defaults['form']['button_text'];
$form_signup_text = trim($form_field['signup_text'] ?? '') ?: $hero_defaults['form']['signup_text'];
$form_signup_link = trim($form_field['signup_link'] ?? '') ?: $hero_defaults['form']['signup_link'];
$form_signup_link_text = trim($form_field['signup_link_text'] ?? '') ?: $hero_defaults['form']['signup_link_text'];
$form_terms_text = trim($form_field['terms_text'] ?? '') ?: $hero_defaults['form']['terms_text'];

// Handle form submission
if ($_POST['login_submit'] ?? false) {
    $username = sanitize_text_field($_POST['username'] ?? '');
    // Add your login logic here
}
?>

<div class="hero_form w-full relative z-0 h-dvh">
    <div class="relative w-full h-full overflow-hidden">
        <?php if (!empty($hero_image_data)) : ?>
            <img fetchpriority="high" decoding="async" loading="eager" src="<?php echo esc_url($hero_image_data['url']); ?>"
                class="size-full object-cover lg:object-left-top" alt="<?php echo esc_attr($hero_image_data['alt']); ?>"
                title="<?php echo esc_attr($hero_image_data['alt']); ?>">
        <?php endif; ?>

        <div class="absolute inset-0 h-full flex justify-center items-center w-full">
            <div class="xl:w-1/4 lg:w-1/3 md:w-1/3 w-full w-full relative p-3">
                <div class="header bg-gradient-to-l from-[#CB122D] to-[#650916] p-5 text-center">
                    <h1 class="text-white uppercase font-bold text-lg"><?php echo esc_html($hero_title); ?></h1>
                </div>
                
                <form method="post" class="w-full bg-[#CB122D] relative flex flex-col gap-y-5 p-5">
                    <?php if ($hero_subtitle) : ?>
                        <h2 class="text-white font-bold lg:text-lg md:text-base text-sm text-center"><?php echo esc_html($hero_subtitle); ?></h2>
                    <?php endif; ?>
                    
                    <div class="w-full">
                        <input type="text" name="username"
                            class="text-[#000000] w-full bg-white font-normal h-11 md:text-base text-sm pl-3"
                            placeholder="<?php echo esc_attr($form_placeholder); ?>" required>
                    </div>
                    
                    <button type="submit" name="login_submit" value="1"
                        class="flex md:text-lg text-base font-bold items-center gap-2 bg-[#FF8300] text-white h-11 justify-center w-full">
                        <?php echo esc_html($form_button_text); ?> 
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="18" viewBox="0 0 12 18" fill="none">
                                <path
                                    d="M11.8388 8.61351L6.0619 17.2202H0L1.92977 14.3513L5.77692 8.61351L1.92977 2.87912L0 7.24792e-05H6.0619L11.8388 8.61351Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </button>
                    
                    <?php if ($form_signup_text) : ?>
                        <div class="mt-2 text-sm text-white font-normal text-center ">
                            <?php echo esc_html($form_signup_text); ?> 
                            <?php if ($form_signup_link && $form_signup_link_text) : ?>
                                <a href="<?php echo esc_url($form_signup_link); ?>" class="font-medium underline" aria-label="<?php echo esc_attr($form_signup_link_text); ?>">
                                    <?php echo esc_html($form_signup_link_text); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($form_terms_text) : ?>
                        <div class="md:hidden block md:mt-2 md:mt-4 md:pt-0 pt-4 text-xs text-[#FFFFFFCC] font-normal text-center md:border-0 border-t border-[#FFFFFF33]">
                            <?php echo esc_html($form_terms_text); ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>