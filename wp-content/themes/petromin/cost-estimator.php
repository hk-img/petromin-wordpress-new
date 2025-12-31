<?php
/* Template Name: cost estimator page */
get_header();

?>

<div class="py-16">
    <?php
    // Get query parameters - decode first to preserve spaces, then sanitize
    $city = isset($_GET['city']) ? sanitize_text_field(urldecode($_GET['city'])) : '';
    $brand = isset($_GET['brand']) ? sanitize_text_field(urldecode($_GET['brand'])) : '';
    $model = isset($_GET['model']) ? sanitize_text_field(urldecode($_GET['model'])) : '';
    $fuel = isset($_GET['fuel']) ? sanitize_text_field(urldecode($_GET['fuel'])) : '';
    ?>
    
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-8">Cost Estimator</h1>
        
        <?php if ($city || $brand || $model || $fuel) : ?>
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4">Selected Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php if ($city) : ?>
                <div class="border-b pb-3">
                    <span class="text-gray-600 font-medium">City:</span>
                    <span class="ml-2 text-gray-900 font-semibold"><?php echo esc_html($city); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($brand) : ?>
                <div class="border-b pb-3">
                    <span class="text-gray-600 font-medium">Car Brand:</span>
                    <span class="ml-2 text-gray-900 font-semibold"><?php echo esc_html($brand); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($model) : ?>
                <div class="border-b pb-3">
                    <span class="text-gray-600 font-medium">Car Model:</span>
                    <span class="ml-2 text-gray-900 font-semibold"><?php echo esc_html($model); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($fuel) : ?>
                <div class="border-b pb-3">
                    <span class="text-gray-600 font-medium">Fuel Type:</span>
                    <span class="ml-2 text-gray-900 font-semibold"><?php echo esc_html($fuel); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else : ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <p class="text-yellow-800">No selection details found. Please go back and select your car details.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>