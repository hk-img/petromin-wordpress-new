
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
            <img src="http://46.101.222.112/petromin/wp-content/themes/custom-theme/assets/img/fi_19024510.webp"
              alt="arrow-icon" class="rotate-180 lg:size-[21px] size-4 invert brightness-0">
            <span>Back</span>
          </div>
        `);
            } else {
                $icon.html(`
          <img src="http://46.101.222.112/petromin/wp-content/themes/custom-theme/assets/img/fi_19024510.webp"
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
          <img src="http://46.101.222.112/petromin/wp-content/themes/custom-theme/assets/img/fi_19024510.webp"
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
        <img src="http://46.101.222.112/petromin/wp-content/themes/custom-theme/assets/img/fi_19024510.webp"
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
        centeredSlides: true,
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

<?php wp_footer(); ?>

</body>

</html>