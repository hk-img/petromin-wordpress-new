<?php
/**
 * 404 Page Template
 * 
 * This template is used when a page or post cannot be found (404 error)
 */
get_header();
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <!-- Main 404 Content -->
    <div class="container mx-auto px-4 py-20 md:py-32">
        <div class="flex flex-col items-center justify-center text-center">
            <!-- 404 Illustration -->
            <div class="mb-8 md:mb-12">
                <div class="relative h-32 md:h-48 mx-auto">
                    <div class="text-9xl md:text-[11.25rem] font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500 opacity-20 leading-none">
                        404
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-4">
                Oops! Page Not Found
            </h1>

            <p class="text-base md:text-lg text-gray-600 mb-8 max-w-2xl">
                We can't find the page you're looking for. It might have been moved, deleted, or the link might be broken. Let's get you back on track!
            </p>

            <!-- Search Box -->
            <div class="w-full max-w-xl mb-12 relative">
                <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="flex gap-2" id="searchForm">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            name="s"
                            id="searchInput"
                            placeholder="Search our site..."
                            value="<?php echo get_search_query(); ?>"
                            class="w-full px-6 py-3 rounded-lg border-2 border-gray-300 focus:border-red-500 focus:outline-none text-gray-900 placeholder-gray-500"
                            autocomplete="off"
                        />
                        <!-- Dropdown Menu -->
                        <div id="searchDropdown" class="absolute top-full left-0 right-0 mt-2 bg-white border-2 border-gray-300 rounded-lg shadow-lg hidden z-50 max-h-72 overflow-y-auto">
                            <div id="dropdownList" class="py-2"></div>
                        </div>
                    </div>
                    <button
                        type="submit"
                        id="searchButton"
                        class="px-8 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors duration-300"
                    >
                        Search
                    </button>
                </form>
            </div>

            <script>
                const searchInput = document.getElementById('searchInput');
                const searchDropdown = document.getElementById('searchDropdown');
                const dropdownList = document.getElementById('dropdownList');
                const searchForm = document.getElementById('searchForm');
                let allPages = [];
                let selectedIndex = -1;

                // Fetch all pages/posts on page load
                async function fetchPages() {
                    try {
                        const response = await fetch('<?php echo esc_url(rest_url('wp/v2/pages?per_page=100')); ?>', {
                            headers: {
                                'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
                            }
                        });
                        const pages = await response.json();
                        
                        const postsResponse = await fetch('<?php echo esc_url(rest_url('wp/v2/posts?per_page=100')); ?>', {
                            headers: {
                                'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
                            }
                        });
                        const posts = await postsResponse.json();
                        
                        allPages = [
                            ...(Array.isArray(pages) ? pages : []),
                            ...(Array.isArray(posts) ? posts : [])
                        ];
                    } catch (error) {
                        console.log('Error fetching pages:', error);
                    }
                }

                // Search and filter pages
                function filterPages(query) {
                    if (!query.trim()) {
                        searchDropdown.classList.add('hidden');
                        dropdownList.innerHTML = '';
                        return [];
                    }

                    const lowerQuery = query.toLowerCase();
                    const results = allPages.filter(page => 
                        page.title?.rendered?.toLowerCase().includes(lowerQuery) ||
                        page.excerpt?.rendered?.toLowerCase().includes(lowerQuery)
                    ).slice(0, 8); // Limit to 8 results

                    return results;
                }

                // Render dropdown
                function renderDropdown(results) {
                    const searchButton = document.getElementById('searchButton');
                    dropdownList.innerHTML = '';
                    selectedIndex = -1;

                    if (results.length === 0) {
                        dropdownList.innerHTML = '<div class="px-4 py-2 text-gray-500">No results found</div>';
                        searchDropdown.classList.remove('hidden');
                        // Disable button when no results
                        searchButton.disabled = true;
                        searchButton.classList.add('opacity-50', 'cursor-not-allowed');
                        return;
                    }

                    results.forEach((item, index) => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-2 hover:bg-red-50 cursor-pointer transition-colors search-item text-left border-b border-gray-200';
                        div.dataset.index = index;
                        div.dataset.url = item.link;
                        div.innerHTML = `
                            <div class="font-semibold text-gray-900">${item.title.rendered}</div>
                            <div class="text-sm text-gray-600 line-clamp-1">${item.excerpt?.rendered?.replace(/<[^>]*>/g, '') || 'No description'}</div>
                        `;
                        
                        div.addEventListener('click', () => {
                            window.location.href = item.link;
                        });

                        div.addEventListener('mouseenter', () => {
                            document.querySelectorAll('.search-item').forEach(el => el.classList.remove('bg-red-50'));
                            div.classList.add('bg-red-50');
                            selectedIndex = index;
                        });

                        dropdownList.appendChild(div);
                    });

                    searchDropdown.classList.remove('hidden');
                    // Enable button when results found
                    searchButton.disabled = false;
                    searchButton.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                // Input event listener
                searchInput.addEventListener('input', (e) => {
                    const query = e.target.value;
                    const searchButton = document.getElementById('searchButton');
                    
                    if (query.trim() === '') {
                        // Empty input - disable button
                        searchButton.disabled = true;
                        searchButton.classList.add('opacity-50', 'cursor-not-allowed');
                        searchDropdown.classList.add('hidden');
                        dropdownList.innerHTML = '';
                    } else {
                        // Non-empty input - filter and render
                        const results = filterPages(query);
                        renderDropdown(results);
                    }
                });

                // Keyboard navigation
                searchInput.addEventListener('keydown', (e) => {
                    const items = document.querySelectorAll('.search-item');
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                        items.forEach(el => el.classList.remove('bg-red-50'));
                        if (items[selectedIndex]) {
                            items[selectedIndex].classList.add('bg-red-50');
                            items[selectedIndex].scrollIntoView({ block: 'nearest' });
                        }
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, -1);
                        items.forEach(el => el.classList.remove('bg-red-50'));
                        if (selectedIndex >= 0 && items[selectedIndex]) {
                            items[selectedIndex].classList.add('bg-red-50');
                            items[selectedIndex].scrollIntoView({ block: 'nearest' });
                        }
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        // Only navigate if there are actual search items (not "No results found")
                        const actualItems = document.querySelectorAll('.search-item[data-url]');
                        
                        if (actualItems.length === 0) {
                            // No results found - don't navigate
                            return;
                        }
                        
                        if (selectedIndex >= 0 && items[selectedIndex]) {
                            // Navigate to selected item
                            window.location.href = items[selectedIndex].dataset.url;
                        } else {
                            // Navigate to first result
                            window.location.href = actualItems[0].dataset.url;
                        }
                    } else if (e.key === 'Escape') {
                        searchDropdown.classList.add('hidden');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('#searchInput') && !e.target.closest('#searchDropdown')) {
                        searchDropdown.classList.add('hidden');
                    }
                });

                // Focus event
                searchInput.addEventListener('focus', () => {
                    if (searchInput.value.trim()) {
                        searchDropdown.classList.remove('hidden');
                    }
                });

                // Prevent form submission with empty input
                searchForm.addEventListener('submit', (e) => {
                    const query = searchInput.value.trim();
                    if (query === '') {
                        e.preventDefault();
                        searchInput.focus();
                        return false;
                    }
                });

                // Initialize button state
                function initializeButtonState() {
                    const searchButton = document.getElementById('searchButton');
                    if (searchInput.value.trim() === '') {
                        searchButton.disabled = true;
                        searchButton.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        searchButton.disabled = false;
                        searchButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }

                // Initialize
                fetchPages();
                initializeButtonState();
            </script>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-3xl mb-12">
                <!-- Home Link -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="p-6 bg-white rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 border-l-4 border-red-500">
                    <div class="text-3xl mb-2">🏠</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Home</h3>
                    <p class="text-gray-600 text-sm">Back to homepage</p>
                </a>

                <!-- Services Link -->
                <?php
                $services_page = get_page_by_title('Services');
                if ($services_page) {
                    $services_url = get_permalink($services_page->ID);
                } else {
                    $services_url = home_url('/services/');
                }
                ?>
                <a href="<?php echo esc_url($services_url); ?>" class="p-6 bg-white rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 border-l-4 border-orange-500">
                    <div class="text-3xl mb-2">🔧</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Services</h3>
                    <p class="text-gray-600 text-sm">Explore our services</p>
                </a>

                <!-- Contact Link -->
                <a href="<?php echo esc_url(home_url('/locate-us')); ?>" class="p-6 bg-white rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 border-l-4 border-blue-500">
                    <div class="text-3xl mb-2">📞</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Contact</h3>
                    <p class="text-gray-600 text-sm">Get in touch with us</p>
                </a>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="px-8 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors duration-300">
                    ← Go to Homepage
                </a>
                <button onclick="history.back()" class="px-8 py-3 bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold rounded-lg transition-colors duration-300">
                    Go Back
                </button>
            </div>

            <!-- Popular Pages -->
            <div class="mt-16 pt-16 border-t border-gray-300 w-full max-w-2xl">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Popular Pages</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    // Get recent posts for suggestions
                    $recent_posts = get_posts([
                        'posts_per_page' => 4,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ]);

                    if (!empty($recent_posts)) {
                        foreach ($recent_posts as $post) {
                            echo '<a href="' . esc_url(get_permalink($post->ID)) . '" class="p-4 bg-white rounded-lg shadow hover:shadow-md hover:bg-red-50 transition-all duration-300 group">';
                            echo '<h3 class="text-gray-900 font-semibold group-hover:text-red-600 transition-colors duration-300 mb-2">' . esc_html($post->post_title) . '</h3>';
                            echo '<span class="text-red-500 font-medium text-sm">Read More →</span>';
                            echo '</a>';
                        }
                    } else {
                        echo '<p class="col-span-2 text-gray-600">No recent pages available.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA Section -->
    <div class="bg-gradient-to-r from-red-500 to-orange-500 py-12 md:py-16 mt-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Still Need Help?
            </h2>
            <p class="text-white text-lg mb-8 max-w-2xl mx-auto">
                Our team is here to assist you. Get in touch with us today!
            </p>
            <a href="<?php echo esc_url(home_url('/locate-us')); ?>" class="inline-block px-8 py-4 bg-white text-red-600 font-bold rounded-lg hover:bg-gray-100 transition-colors duration-300">
                Contact Us Today
            </a>
        </div>
    </div>
</div>

<?php
get_footer();
?>
