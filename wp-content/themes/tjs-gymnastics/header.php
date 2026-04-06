<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <nav class="nav-wrap" aria-label="<?php _e('Main navigation', 'tjs-gymnastics'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="<?php bloginfo('name'); ?>" class="logo-img" width="120" height="44">
        </a>
        <div class="nav-overlay"></div>
        <div class="nav-links">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => '',
                'fallback_cb'    => false,
                'items_wrap'     => '%3$s',
                'link_before'    => '',
                'link_after'     => '',
                'walker'         => new TJS_Walker_Nav_Menu()
            ));
            ?>
        </div>
        <button class="nav-toggle" aria-label="<?php _e('Toggle menu', 'tjs-gymnastics'); ?>" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </nav>
</header>

<main>
