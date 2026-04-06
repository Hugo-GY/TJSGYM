<?php
/**
 * Template Name: Club Kit Page
 */
get_header();

$hero_title = get_field('hero_title') ?: 'Club <em>Kit</em>';
?>

<section class="page-hero" aria-label="<?php _e('Club kit overview', 'tjs-gymnastics'); ?>">
    <div class="container">
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
    </div>
</section>

<section class="club-kit-featured section" aria-label="<?php _e('TJ\'s Gym Club Kit', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="club-kit-featured-layout">
            <div class="club-kit-featured-header">
                <span class="section-label"><?php _e('Exclusive Club Kit', 'tjs-gymnastics'); ?></span>
                <h2><?php _e('TJ\'s Gym Club <em>Kit</em>', 'tjs-gymnastics'); ?></h2>
                <p><?php _e('Our purple leotard and shorts are exclusive TJ\'s Gym Club items and are available to buy directly through the club.', 'tjs-gymnastics'); ?></p>
                <p><?php _e('Please speak to a member of staff for details.', 'tjs-gymnastics'); ?></p>
            </div>
            
            <div class="club-kit-featured-grid">
                <article class="club-kit-featured-card">
                    <div class="club-kit-featured-image-wrap">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/club-kit/leotard.png'); ?>" alt="<?php _e('TJ\'s Gym Club purple leotard', 'tjs-gymnastics'); ?>" class="club-kit-featured-image" loading="lazy">
                    </div>
                    <h3><?php _e('Purple Leotard', 'tjs-gymnastics'); ?></h3>
                </article>
                
                <article class="club-kit-featured-card">
                    <div class="club-kit-featured-image-wrap">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/club-kit/shorts.jpg'); ?>" alt="<?php _e('TJ\'s Gym Club purple shorts', 'tjs-gymnastics'); ?>" class="club-kit-featured-image" loading="lazy">
                    </div>
                    <h3><?php _e('Purple Shorts', 'tjs-gymnastics'); ?></h3>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="kit-supplier-section section" aria-label="<?php _e('Supplier information', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="kit-supplier-layout">
            <div class="kit-supplier-copy">
                <span class="section-label"><?php _e('Club Shop', 'tjs-gymnastics'); ?></span>
                <h2 class="kit-supplier-title"><?php _e('Club <em>Shop</em>', 'tjs-gymnastics'); ?></h2>
                <p><?php _e('Additional TJ\'s branded clothing and accessories are available online through our Print My Kit shop.', 'tjs-gymnastics'); ?></p>
                <div class="kit-actions">
                    <a href="https://tjs.printmykit.co.uk/" class="btn btn-magenta" target="_blank" rel="noopener"><?php _e('Visit The Club Shop', 'tjs-gymnastics'); ?></a>
                </div>
            </div>
            
            <div class="kit-brand-card">
                <img src="https://tjs.printmykit.co.uk/rshared/ssc/i/riq/10851356/200/200/t/0/0/PMK-logo2025%20transparent.png?1749132563" alt="Print My Kit" class="kit-supplier-logo" loading="lazy">
            </div>
        </div>
    </div>
</section>

<section class="kit-products section" aria-label="<?php _e('Featured products', 'tjs-gymnastics'); ?>">
    <div class="container">
        <span class="section-label"><?php _e('Club Collection', 'tjs-gymnastics'); ?></span>
        <h2 class="kit-products-title"><?php _e('All 16 current pieces from the <em>shop</em>', 'tjs-gymnastics'); ?></h2>
        
        <div class="kit-products-grid">
            <?php
            $products = array(
                array('name' => "TJ's T-Shirt", 'img' => '20889191', 'url' => 'https://tjs.printmykit.co.uk/shop/view_product/20889191/TJ-s-T-Shirt'),
                array('name' => "TJ's T-Shirt - Kids", 'img' => '20889196', 'url' => 'https://tjs.printmykit.co.uk/shop/view_product/20889196/TJ-s-T-Shirt-Kids'),
                array('name' => "TJ's Sweatshirt", 'img' => '20889181', 'url' => 'https://tjs.printmykit.co.uk/shop/view_product/20889181/TJ-s-Sweatshirt'),
                array('name' => "TJ's Sweatshirt - Kids", 'img' => '20889186', 'url' => 'https://tjs.printmykit.co.uk/shop/view_product/20889186/TJ-s-Sweatshirt-Kids'),
                array('name' => "TJ's Boys Shorts", 'img' => '20935746', 'url' => 'https://tjs.printmykit.co.uk/shop/view_product/20935746/TJ-s-Boys-Shorts'),
                array('name' => "TJ's Girls Shorts", 'img' => '20935736', 'url' => 'https://tjs.printmykit.co.uk/shop/view_product/20935736/TJ-s-Girls-Shorts'),
            );
            
            foreach ($products as $product):
            ?>
                <a class="kit-product-card" href="<?php echo esc_url($product['url']); ?>" target="_blank" rel="noopener">
                    <div class="kit-product-image-wrap">
                        <img src="https://tjs.printmykit.co.uk/ssc/i/decorated_product_listing_image_quality/<?php echo esc_attr($product['img']); ?>/400/400/FFFFFF/1/0/product.jpg" alt="<?php echo esc_attr($product['name']); ?>" class="kit-product-image" loading="lazy">
                    </div>
                    <h3><?php echo esc_html($product['name']); ?></h3>
                    <span class="kit-product-link"><?php _e('View Product', 'tjs-gymnastics'); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
