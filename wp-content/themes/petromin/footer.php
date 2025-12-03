<?php
$footer_brand = function_exists('get_field') ? (get_field('footer_brand', 'option') ?: []) : [];
$footer_highlight = function_exists('get_field') ? (get_field('footer_highlight_box', 'option') ?: []) : [];
$footer_head_office = function_exists('get_field') ? (get_field('footer_head_office', 'option') ?: []) : [];
$footer_quick_links_raw = function_exists('get_field') ? (get_field('footer_quick_links', 'option') ?: []) : [];
$footer_other_links_raw = function_exists('get_field') ? (get_field('footer_other_links', 'option') ?: []) : [];
$footer_store_badges_raw = function_exists('get_field') ? (get_field('footer_store_badges', 'option') ?: []) : [];
$footer_social_links_raw = function_exists('get_field') ? (get_field('footer_social_links', 'option') ?: []) : [];
$footer_copyright = function_exists('get_field') ? (get_field('footer_copyright', 'option') ?: '') : '';

$default_footer_description = 'Petromin Express is India’s leading multibrand car service and repair network. In partnership with HPCL, we deliver a standardised, tech-enabled service experience across fully owned garages.';

$footer_logo_data = petromin_get_acf_image_data(
    $footer_brand['logo'] ?? null,
    'full',
    get_template_directory_uri() . '/assets/img/image-38.webp',
    'Petromin Express Logo'
);

$footer_description = trim((string) ($footer_brand['description'] ?? ''));
if ($footer_description === '') {
    $footer_description = $default_footer_description;
}

$default_info_columns = [
    [
        'title' => 'Station Hours',
        'lines' => [
            ['line_text' => '8 AM to 8 PM'],
            ['line_text' => 'Monday to Saturday'],
            ['line_text' => 'Open: 3rd & 4th Sunday'],
            ['line_text' => 'Closed: 1st & 2nd Sunday'],
        ],
    ],
    [
        'title' => 'Call Center',
        'lines' => [
            ['line_text' => '6 AM to 11 PM'],
            ['line_text' => 'Monday to Sunday'],
        ],
    ],
];

$info_columns_raw = $footer_highlight['info_columns'] ?? [];
$normalized_info_columns = [];

if (is_array($info_columns_raw)) {
    foreach ($info_columns_raw as $column) {
        if (!is_array($column)) {
            continue;
        }

        $column_title = trim((string) ($column['title'] ?? ''));
        $lines_raw = $column['lines'] ?? [];
        $lines = [];

        if (is_array($lines_raw)) {
            foreach ($lines_raw as $line) {
                $line_text = '';
                if (is_array($line)) {
                    $line_text = $line['line_text'] ?? '';
                } else {
                    $line_text = $line;
                }

                $line_text = trim((string) $line_text);

                if ($line_text === '') {
                    continue;
                }

                $lines[] = $line_text;
            }
        }

        if ($column_title === '' && empty($lines)) {
            continue;
        }

        $normalized_info_columns[] = [
            'title' => $column_title,
            'lines' => $lines,
        ];
    }
}

if (empty($normalized_info_columns)) {
    foreach ($default_info_columns as $default_column) {
        $lines = [];
        foreach ($default_column['lines'] as $line) {
            $lines[] = $line['line_text'];
        }
        $normalized_info_columns[] = [
            'title' => $default_column['title'],
            'lines' => $lines,
        ];
    }
}

$contact_phone = trim((string) ($footer_highlight['contact_phone'] ?? ''));
if ($contact_phone === '') {
    $contact_phone = '+91 86686 92000';
}

$contact_email = trim((string) ($footer_highlight['contact_email'] ?? ''));
if ($contact_email === '') {
    $contact_email = 'customercare.pe@petromin.in';
}

$head_office_title = trim((string) ($footer_head_office['title'] ?? ''));
if ($head_office_title === '') {
    $head_office_title = 'Head Office';
}

$head_office_address = trim((string) ($footer_head_office['address'] ?? ''));
if ($head_office_address === '') {
    $head_office_address = "Sai Brindhavan, Plot No. 40C, Door 1, 3rd Main Road
Kottur Gardens, Kotturpuram, Chennai, Tamil Nadu - 600085";
}

// COLUMN 1: Services (Dynamic from CPT)
$service_posts_footer = new WP_Query([
    'post_type' => 'service',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

$services_links = [];
if (!empty($service_posts_footer->posts)) {
    foreach ($service_posts_footer->posts as $service) {
        $services_links[] = [
            'text' => get_the_title($service->ID),
            'url' => get_permalink($service->ID),
            'target' => '_self',
        ];
    }
}
wp_reset_postdata();

// COLUMN 1: Quick Links (from CMS)
$quick_links = [];
if (is_array($footer_quick_links_raw)) {
    foreach ($footer_quick_links_raw as $link) {
        if (!is_array($link)) {
            continue;
        }
        $link_text = trim((string) ($link['link_text'] ?? ''));
        if ($link_text === '') {
            continue;
        }
        $quick_links[] = [
            'text' => $link_text,
            'url' => petromin_normalize_link($link['link_url'] ?? '', '#'),
            'target' => !empty($link['open_in_new_tab']) ? '_blank' : '_self',
        ];
    }
}

// COLUMN 2: Latest Offers (Static - hardcoded)
$offers_links = [
    ['text' => 'PMS Service @Rs.2499', 'url' => '#', 'target' => '_self'],
    ['text' => 'Tyre Offer - Buy 4 + 1TMSS', 'url' => '#', 'target' => '_self'],
    ['text' => 'Full Body Car Paint Offer', 'url' => '#', 'target' => '_self'],
    ['text' => 'Dent & Paint Repair', 'url' => '#', 'target' => '_self'],
    ['text' => 'AC Gas Top-up & Inspection', 'url' => '#', 'target' => '_self'],
    ['text' => 'Express Car Service (@Rs.999)', 'url' => '#', 'target' => '_self'],
    ['text' => 'Petrofit', 'url' => '#', 'target' => '_self'],
    ['text' => 'Brake Pad Replacement', 'url' => '#', 'target' => '_self'],
    ['text' => 'AC Inspection @Rs.99', 'url' => '#', 'target' => '_self'],
    ['text' => 'Brake Inspection @Rs.99', 'url' => '#', 'target' => '_self'],
];

// COLUMN 2: Other Links (from CMS)
$other_links = [];
if (is_array($footer_other_links_raw)) {
    foreach ($footer_other_links_raw as $link) {
        if (!is_array($link)) {
            continue;
        }
        $link_text = trim((string) ($link['link_text'] ?? ''));
        if ($link_text === '') {
            continue;
        }
        $other_links[] = [
            'text' => $link_text,
            'url' => petromin_normalize_link($link['link_url'] ?? '', '#'),
            'target' => !empty($link['open_in_new_tab']) ? '_blank' : '_self',
        ];
    }
}

// Build normalized footer columns
$normalized_footer_columns = [
    [
        'column_title' => 'Services',
        'primary_links' => $services_links,
        'secondary_links' => $quick_links,
    ],
    [
        'column_title' => 'Latest Offers',
        'primary_links' => $offers_links,
        'secondary_links' => $other_links,
    ],
];

$default_store_badges = [
    [
        'badge_link' => '#',
        'badge_image' => [
            'url' => get_template_directory_uri() . '/assets/img/assets1.webp',
            'alt' => 'Google Play',
        ],
    ],
    [
        'badge_link' => '#',
        'badge_image' => [
            'url' => get_template_directory_uri() . '/assets/img/app_store_btn.webp',
            'alt' => 'App Store',
        ],
    ],
];

$store_badges_source = !empty($footer_store_badges_raw) && is_array($footer_store_badges_raw) ? $footer_store_badges_raw : $default_store_badges;
$normalized_store_badges = [];

foreach ($store_badges_source as $badge) {
    if (!is_array($badge)) {
        continue;
    }

    $badge_image_field = $badge['badge_image'] ?? null;
    $fallback_url = '';
    $fallback_alt = '';

    if (is_array($badge_image_field) && isset($badge_image_field['url'])) {
        $fallback_url = $badge_image_field['url'];
        $fallback_alt = $badge_image_field['alt'] ?? '';
    }

    $badge_image_data = petromin_get_acf_image_data($badge_image_field, 'full', $fallback_url, $fallback_alt);

    if (!$badge_image_data) {
        continue;
    }

    $badge_link = petromin_normalize_link($badge['badge_link'] ?? '', '#');

    $normalized_store_badges[] = [
        'link' => $badge_link,
        'image' => $badge_image_data,
    ];
}

$default_social_links = [
    ['platform' => 'x', 'url' => '#', 'open_in_new_tab' => true],
    ['platform' => 'linkedin', 'url' => '#', 'open_in_new_tab' => true],
    ['platform' => 'facebook', 'url' => '#', 'open_in_new_tab' => true],
    ['platform' => 'instagram', 'url' => '#', 'open_in_new_tab' => true],
    ['platform' => 'youtube', 'url' => '#', 'open_in_new_tab' => true],
];

$social_links_source = !empty($footer_social_links_raw) && is_array($footer_social_links_raw) ? $footer_social_links_raw : $default_social_links;
$normalized_social_links = [];

foreach ($social_links_source as $social_link) {
    if (!is_array($social_link)) {
        continue;
    }

    $platform = strtolower(trim((string) ($social_link['platform'] ?? '')));
    $icon_svg = petromin_get_social_icon_svg($platform);

    if ($icon_svg === '') {
        continue;
    }

    $url = petromin_normalize_link($social_link['url'] ?? '', '#');
    $target = !empty($social_link['open_in_new_tab']) ? '_blank' : '_self';

    $normalized_social_links[] = [
        'platform' => $platform,
        'icon' => $icon_svg,
        'url' => $url,
        'target' => $target,
    ];
}

if (empty($normalized_social_links)) {
    foreach ($default_social_links as $social_link) {
        $platform = $social_link['platform'];
        $icon_svg = petromin_get_social_icon_svg($platform);

        if ($icon_svg === '') {
            continue;
        }

        $normalized_social_links[] = [
            'platform' => $platform,
            'icon' => $icon_svg,
            'url' => petromin_normalize_link($social_link['url'], '#'),
            'target' => !empty($social_link['open_in_new_tab']) ? '_blank' : '_self',
        ];
    }
}

if ($footer_copyright === '') {
    $footer_copyright = '© 2025, Automini Car Services Pvt. Ltd.';
}

$contact_phone_href = '#';
if ($contact_phone !== '') {
    $phone_digits = preg_replace('/[^0-9+]/', '', $contact_phone);
    if (!empty($phone_digits)) {
        $contact_phone_href = 'tel:' . $phone_digits;
    }
}

$contact_email_href = '#';
if ($contact_email !== '') {
    $contact_email_href = stripos($contact_email, 'mailto:') === 0 ? $contact_email : 'mailto:' . $contact_email;
}

$arrow_icon_url = esc_url(get_template_directory_uri() . '/assets/img/fi_19024510.webp');
?>
<footer class="w-full bg-black relative text-white font-inter lg:py-8 py-4 z-10">
    <div class="py-12 relative view !pl-0">
        <div class="flex flex-col md:flex-row gap-12">
            <div class="md:w-3/5 w-full flex flex-col gap-8">
                <div class="flex flex-col items-start gap-5 lg:pl-[5rem] md:pl-[4rem] pl-[1rem]">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php if (!empty($footer_logo_data)) : ?>
                            <img src="<?php echo esc_url($footer_logo_data['url']); ?>"
                                 alt="<?php echo esc_attr($footer_logo_data['alt']); ?>"
                                 title="<?php echo esc_attr($footer_logo_data['alt']); ?>"
                                 class="w-auto h-16 object-contain"
                                 loading="lazy"
                                 fetchpriority="low">
                        <?php endif; ?>
                    </a>
                    <p class="text-base text-white font-normal leading-relaxed max-w-md">
                        <?php echo nl2br(esc_html($footer_description)); ?>
                    </p>
                </div>

                <div class="w-full lg:pl-[5rem] md:pl-[4rem] pl-[1rem] md:p-12 p-8 flex flex-col bg-[linear-gradient(268.6deg,_#CB122D_0.16%,_#650916_100%)] origin-top -skew-x-[18deg]">
                    <div class="flex items-center skew-x-[18deg] lg:pl-[3.5rem] md:pl-[3rem] pl-[4rem]">
                        <div class="flex flex-col justify-center gap-8 text-white">
                            <div class="flex md:flex-row flex-col md:gap-28 gap-y-6">
                                <?php foreach ($normalized_info_columns as $column) : ?>
                                    <div>
                                        <?php if (!empty($column['title'])) : ?>
                                            <h3 class="font-medium md:text-base text-sm mb-2">
                                                <?php echo esc_html($column['title']); ?>
                                            </h3>
                                        <?php endif; ?>
                                        <?php if (!empty($column['lines'])) : ?>
                                            <div class="lg:text-xl text-base flex flex-col gap-y-1">
                                                <?php foreach ($column['lines'] as $line_index => $line_text) : ?>
                                                    <span class="block <?php echo $line_index < 2 ? 'md:font-bold font-semibold' : ''; ?>">
                                                        <?php echo esc_html($line_text); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="mt-3 flex md:flex-row flex-col gap-1 2xl:text-xl lg:text-xl text-base font-bold">
                                <?php if ($contact_phone !== '') : ?>
                                    <a href="<?php echo esc_url($contact_phone_href); ?>">
                                        <?php echo esc_html($contact_phone); ?>
                                    </a>
                                <?php endif; ?>
                                <?php if ($contact_phone !== '' && $contact_email !== '') : ?>
                                    <span class="md:inline hidden">|</span>
                                <?php endif; ?>
                                <?php if ($contact_email !== '') : ?>
                                    <a href="<?php echo esc_url($contact_email_href); ?>"
                                       class="2xl:text-xl lg:text-xl text-sm underline hover:text-gray-200 md:pl-1">
                                        <?php echo esc_html($contact_email); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>

                <?php if (!empty($head_office_address)) : ?>
                    <div class="block md:hidden lg:pl-[5rem] md:pl-[4rem] pl-[1rem]">
                        <h3 class="font-semibold lg:mt-12 md:mt-6 mt-2 mb-1">
                            <?php echo esc_html($head_office_title); ?>
                        </h3>
                        <p class="text-sm">
                            <?php echo nl2br(esc_html($head_office_address)); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="md:w-2/5 w-full md:mt-20">
                <div class="flex">
                    <?php foreach ($normalized_footer_columns as $index => $column) : ?>
                        <div class="md:w-1/2 w-full <?php echo $index === 0 ? 'md:pl-0 pl-[1rem]' : ''; ?>">
                            <?php if (!empty($column['column_title'])) : ?>
                                <h3 class="font-semibold mb-2">
                                    <?php echo esc_html($column['column_title']); ?>
                                </h3>
                            <?php endif; ?>
                            <?php if (!empty($column['primary_links'])) : ?>
                                <ul class="flex flex-col text-sm space-y-1 mb-4">
                                    <?php foreach ($column['primary_links'] as $link) : ?>
                                        <li>
                                            <a href="<?php echo esc_url($link['url']); ?>"
                                               class="hover:text-gray-300 duration-300"
                                               target="<?php echo esc_attr($link['target']); ?>"
                                               <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                                                <?php echo esc_html($link['text']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <?php if (!empty($column['secondary_links'])) : ?>
                                <div class="w-full flex">
                                    <ul class="flex flex-col md:text-base text-sm gap-y-5">
                                        <?php foreach ($column['secondary_links'] as $link) : ?>
                                            <li class="font-semibold">
                                                <a href="<?php echo esc_url($link['url']); ?>"
                                                   class="hover:text-gray-300 duration-300"
                                                   target="<?php echo esc_attr($link['target']); ?>"
                                                   <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                                                    <?php echo esc_html($link['text']); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="flex md:items-end items-start md:flex-row flex-col justify-between pt-8 lg:pl-[5rem] md:pl-[4rem] pl-[1rem]">
            <div>
                <?php if (!empty($head_office_address)) : ?>
                    <div class="md:block hidden">
                        <h3 class="font-semibold mb-1">
                            <?php echo esc_html($head_office_title); ?>
                        </h3>
                        <p class="text-sm">
                            <?php echo nl2br(esc_html($head_office_address)); ?>
                        </p>
                    </div>
                <?php endif; ?>
                <div class="flex md:flex-row flex-col md:items-center items-start mt-10 lg:gap-8 gap-4">
                    <?php if (!empty($normalized_store_badges)) : ?>
                        <div class="flex items-center gap-3">
                            <?php foreach ($normalized_store_badges as $badge) : ?>
                                <a href="<?php echo esc_url($badge['link']); ?>"
                                   class="hover:scale-105 duration-300"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    <img src="<?php echo esc_url($badge['image']['url']); ?>"
                                         alt="<?php echo esc_attr($badge['image']['alt']); ?>"
                                         title="<?php echo esc_attr($badge['image']['alt']); ?>"
                                         class="w-auto h-10 object-contain"
                                         loading="lazy"
                                         fetchpriority="low">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($normalized_social_links)) : ?>
                        <div class="flex gap-4 text-white social_links">
                            <?php foreach ($normalized_social_links as $social_link) : ?>
                                <a href="<?php echo esc_url($social_link['url']); ?>"
                                   class="hover:scale-75 duration-300"
                                   title="<?php echo esc_attr(ucfirst($social_link['platform'])); ?>"
                                   target="<?php echo esc_attr($social_link['target']); ?>"
                                   <?php echo $social_link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                                    <?php echo $social_link['icon']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="lg:text-center lg:text-base text-sm lg:font-normal text-start font-bold text-white lg:py-4 pt-16">
                <?php echo esc_html($footer_copyright); ?>
            </div>
        </div>

    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
jQuery(function($) {
    var $popup = $('#carPopup'),
        $mobile = $('#mobileToggle'),
        $desk = $('#desktopToggle'),
        $chk = $('#toggle'),
        $inner = $popup.find('.w-full.lg\\:w-1\\/3, .w-full').first(),
        show = function() {
            $popup.removeClass('hidden').addClass('flex flex-col lg:flex-row animate-slideUp');
            $inner.css({
                maxHeight: '',
                overflow: ''
            });
            $chk.prop('checked', false);
        },
        hide = function() {
            $popup.addClass('hidden').removeClass('flex flex-col lg:flex-row animate-slideUp');
            $inner.css({
                maxHeight: '0px',
                overflow: 'hidden'
            });
            $chk.prop('checked', false);
        },
        toggle = function() {
            $popup.hasClass('hidden') ? show() : hide();
        };

    $mobile.add($desk).on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        toggle();
    });
    $('label[for="toggle"]').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        hide();
    });
    $(document).on('click', function(e) {
        if (!$popup.is(e.target) && $popup.has(e.target).length === 0 && !$(e.target).is($mobile.add(
                $desk))) hide();
    });
    show();
});
</script>


<script>
function setupDropdown({
    inputId,
    dropdownId,
    itemSelector,
    searchId = null,
    iconId = null
}) {
    const $input = $('#' + inputId);
    const $dropdown = $('#' + dropdownId);
    const $search = searchId ? $('#' + searchId) : null;
    const $icon = iconId ? $('#' + iconId) : null;

    // Input click toggle
    $input.on('click', function(e) {
        e.stopPropagation();
        const isOpen = !$dropdown.hasClass('hidden');
        $dropdown.toggleClass('hidden');
        $input.toggleClass('open', !isOpen);

        // background + text color
        if (!isOpen) {
            $input.css({
                'background-color': '#650916',
                'color': '#fff',
                '--placeholder-color': '#fff'
            });
        } else {
            $input.css({
                'background-color': '#fff',
                'color': '#000'
            });
        }

        // icon change
        if ($icon.length) {
            if (!isOpen) {
                $icon.html(`
          <div class="relative flex items-center gap-1 text-white text-sm bg-[#ff8300] py-3 px-3 -right-4">
            <img src="<?php echo $arrow_icon_url; ?>"
              alt="arrow-icon" class="rotate-180 lg:size-[21px] size-4 invert brightness-0">
            <span>Back</span>
          </div>
        `);
            } else {
                $icon.html(`
          <img src="<?php echo $arrow_icon_url; ?>"
            alt="arrow-icon" class="lg:size-[21px] size-4">
        `);
            }
        }
    });

    // Outside click close
    $(document).on('click', function(e) {
        if (!$dropdown.is(e.target) && !$input.is(e.target) && $dropdown.has(e.target).length === 0) {
            $dropdown.addClass('hidden');
            $input.removeClass('open').css({
                'background-color': '#fff',
                'color': '#000'
            });
            if ($icon.length) {
                $icon.html(`
          <img src="<?php echo $arrow_icon_url; ?>"
            alt="arrow-icon" class="lg:size-[21px] size-4">
        `);
            }
        }
    });

    // Dropdown item click
    $dropdown.find(itemSelector).on('click', function() {
        const value = $(this).data('value');
        $input.val(value).css({
            'background-color': '#fff',
            'color': '#000'
        });
        $dropdown.addClass('hidden');
        if ($icon.length) {
            $icon.html(`
        <img src="<?php echo $arrow_icon_url; ?>"
          alt="arrow-icon" class="lg:size-[21px] size-4">
      `);
        }
    });

    // Search filter
    if ($search && $search.length) {
        $search.on('input', function() {
            const term = $(this).val().toLowerCase();
            $dropdown.find(itemSelector).each(function() {
                const name = $(this).data('value').toLowerCase();
                $(this).toggle(name.includes(term));
            });
        });
    }
}

// Initialize dropdowns
$(document).ready(function() {
    setupDropdown({
        inputId: 'cityInput',
        dropdownId: 'cityDropdown',
        itemSelector: '[data-value]',
        iconId: 'cityIcon'
    });

    setupDropdown({
        inputId: 'brandInput',
        dropdownId: 'brandDropdown',
        itemSelector: '[data-value]',
        iconId: 'brandIcon'
    });

    setupDropdown({
        inputId: 'modelInput',
        dropdownId: 'modelDropdown',
        itemSelector: '[data-value]',
        iconId: 'modelIcon'
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileToggle');
    const carPopup = document.getElementById('carPopup');

    const observer = new MutationObserver(() => {
        if (window.getComputedStyle(carPopup).display !== 'none') {
            mobileToggle.classList.add('hidden'); // hide button
        } else {
            mobileToggle.classList.remove('hidden'); // show button again
        }
    });

    observer.observe(carPopup, {
        attributes: true,
        attributeFilter: ['style', 'class']
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const swiper = new Swiper(".benefitSwiper", {
        speed: 3000,
        centeredSlides: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        spaceBetween: 24,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: {
                slidesPerView: 1.1,
                spaceBetween: 8
            },
            480: {
                slidesPerView: 1.07,
                spaceBetween: 8
            },
            640: {
                slidesPerView: 1.18,
                spaceBetween: 8
            },
            1024: {
                slidesPerView: 1.14,
                spaceBetween: 24
            },
            1350: {
                slidesPerView: 1.13,
                spaceBetween: 24
            },
            1536: {
                slidesPerView: 1.13,
                spaceBetween: 24
            },
            1897: {
                slidesPerView: 1.09,
                spaceBetween: 24
            },
            2529: {
                slidesPerView: 1.07,
                spaceBetween: 24
            },
        },
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const swiper = new Swiper(".featureSwiper", {
        speed: 3000,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        spaceBetween: 30,
        loop: true,
        // autoHeight: true,
        centeredSlides: false,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: {
                slidesPerView: 1.2,
            },
            480: {},
            640: {
                slidesPerView: 2.2,
            },
            1024: {
                slidesPerView: 3.1,
            },
            1350: {
                slidesPerView: 3.1,
            },
        },
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const swiper = new Swiper(".partnerSlider", {
        speed: 3000,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        spaceBetween: 24,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 20
            },
            480: {
                slidesPerView: 2.4,
                spaceBetween: 32
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 32
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 32
            },
            1350: {
                slidesPerView: 5.6,
                spaceBetween: 32
            },
        },
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Left-to-right Swiper
    const swiperLeft = new Swiper(".brandSwiperLeft", {
        spaceBetween: 12,
        speed: 3000, // adjust speed for smoothness
        loop: true,
        autoplay: {
            delay: 0,
            disableOnInteraction: false,
            reverseDirection: false,
        },
        allowTouchMove: false, // optional: prevents manual drag
        breakpoints: {
            320: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            480: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 32
            },
            1024: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
            1350: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
        },
    });

    // Left-to-right Swiper
    const swiperLeft1 = new Swiper(".brandSwiperLeft1", {
        spaceBetween: 12,
        speed: 3000, // adjust speed for smoothness
        loop: true,
        autoplay: {
            delay: 0,
            disableOnInteraction: false,
            reverseDirection: false,
        },
        allowTouchMove: false, // optional: prevents manual drag
        breakpoints: {
            320: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            480: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 32
            },
            1024: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
            1350: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
        },
    });
    // Right-to-left Swiper
    const swiperRight = new Swiper(".brandSwiperRight", {
        slidesPerView: 9.2,
        spaceBetween: 12,
        speed: 3000,
        loop: true,
        autoplay: {
            delay: 0,
            disableOnInteraction: false,
            reverseDirection: true, // <-- key for opposite direction
        },
        breakpoints: {
            320: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            480: {
                slidesPerView: 3.8,
                spaceBetween: 8
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 32
            },
            1024: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
            1350: {
                slidesPerView: 9.2,
                spaceBetween: 32
            },
        },
        allowTouchMove: false,
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const swiper = new Swiper(".testimonialSwiper", {
        speed: 3000,
        autoHeight: true,
        centeredSlides: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        spaceBetween: 24,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            320: {
                slidesPerView: 1.4,
                spaceBetween: 6
            },
            480: {
                slidesPerView: 1.5,
                spaceBetween: 12
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 16
            },
            1024: {
                slidesPerView: 3.5,
                spaceBetween: 24
            },
            1350: {
                slidesPerView: 3.7,
                spaceBetween: 24
            },
        },
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const partnerSwiper = new Swiper(".partnerSwiper", {
        loop: true,
        autoSlide: true,
        spaceBetween: 24,
        freeMode: true,
        freeModeMomentum: false,
        speed: 5000,
        autoplay: {
            delay: 0,
            disableOnInteraction: false,
        },
        allowTouchMove: false,
        breakpoints: {
            320: {
                slidesPerView: 3.8,
                spaceBetween: 16
            },
            480: {
                slidesPerView: 3.8,
                spaceBetween: 16
            },
            640: {
                slidesPerView: 4.2,
                spaceBetween: 16
            },
            1024: {
                slidesPerView: 9.2,
                spaceBetween: 16
            },
            1350: {
                slidesPerView: 9.2,
                spaceBetween: 24
            },
        },
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll(".m-tab");
    const tabContents = document.querySelectorAll(".cont-item");

    function resetTabs() {
        tabLinks.forEach(tab => {
            tab.className =
                "m-tab px-3 py-5 -my-5 lg:font-bold font-semibold text-base text-white";
            tab.innerHTML =
                `<span class="block text-base whitespace-nowrap lg:whitespace-wrap">${tab.innerText}</span>`;
        });
    }

    tabLinks.forEach(tab => {
        tab.addEventListener("click", function() {
            const target = this.dataset.tab;

            // Reset all tabs
            resetTabs();

            // Apply active styles to clicked tab
            this.className =
                "m-tab active relative px-3 py-5 -my-5 lg:font-bold font-semibold bg-gradient-to-l from-[#CB122D] to-[#650916] text-white -skew-x-[12deg]";
            this.innerHTML =
                `<span class="skew-x-[12deg] block whitespace-nowrap">${this.innerText}</span>`;

            // Hide all contents
            tabContents.forEach(c => c.classList.add("hidden"));

            // Show target content
            const content = document.querySelector(`.cont-item[data-content="${target}"]`);
            if (content) content.classList.remove("hidden");
        });
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const labels = Array.from(document.querySelectorAll(".label"));
    const arrow = document.getElementById("arrow");
    const container = document.getElementById("wheelContainer");

    let currentActive = null;

    // ✅ Arrow distance adjusted for mobile view
    function computeArrowDistance() {
        const rect = container.getBoundingClientRect();
        const radius = rect.width / 2;

        // 🔸 Push arrow slightly outward on mobile
        if (window.innerWidth <= 640) {
            return Math.round(radius * 1); // small outward shift for mobile
        } else {
            return Math.round(radius * 0.96); // normal for desktop
        }
    }

    function moveArrow(angle) {
        const distance = computeArrowDistance();
        arrow.style.transform = `translate(-50%, -50%) rotate(${angle}deg) translateY(-${distance}px)`;
    }

    function clearActiveLabels() {
        labels.forEach((lbl) => {
            lbl.classList.remove("text-[#CB122D]", "scale-110");
            const sub = lbl.querySelector(".subtext");
            if (sub) {
                sub.classList.remove("opacity-100", "translate-y-1");
                sub.classList.add("opacity-0", "translate-y-3");
            }
        });
    }

    function activateByIndex(index) {
        const target = document.querySelector(`.label[data-index="${index}"]`);
        if (!target) return;
        clearActiveLabels();
        target.classList.add("text-[#CB122D]", "scale-110");
        const sub = target.querySelector(".subtext");
        if (sub) {
            sub.classList.remove("opacity-0", "translate-y-3");
            sub.classList.add("opacity-100", "translate-y-1");
        }
        const angle = Number(target.dataset.angle) || 0;
        moveArrow(angle);
        currentActive = Number(index);
    }

    labels.forEach((label) => {
        const idx = Number(label.dataset.index);
        label.addEventListener("mouseenter", () => activateByIndex(idx));
        label.addEventListener("focus", () => activateByIndex(idx));
    });

    window.addEventListener("resize", () => {
        if (currentActive !== null) {
            activateByIndex(currentActive);
        } else {
            moveArrow(0);
        }
    });

    // ✅ Detect device width and activate accordingly
    if (window.innerWidth <= 640) {
        // Mobile view
        activateByIndex(1);
    } else {
        // Desktop view
        activateByIndex(0);
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const swiper = new Swiper(".category-slider", {
        slidesPerView: "auto",
        spaceBetween: 20,
        speed: 700,
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
    });

    // Get buttons
    const prevBtn = document.querySelector(".swiper-prev");
    const nextBtn = document.querySelector(".swiper-next");

    // Initially hide prev
    prevBtn.classList.add("opacity-0", "pointer-events-none");

    // Show/hide prev button based on active slide index
    swiper.on("slideChange", () => {
        if (swiper.realIndex === 0) {
            prevBtn.classList.add("opacity-0", "pointer-events-none");
        } else {
            prevBtn.classList.remove("opacity-0", "pointer-events-none");
        }
    });
});
</script>

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5/dist/fancybox/fancybox.umd.js"></script>
<script>
    Fancybox.bind('a[data-fancybox]', {
        on: {
            reveal: (fancybox, $slide) => {
                console.log('Fancybox revealed');
            },
        },
    });
</script>

<?php wp_footer(); ?>

</body>

</html>