<?php
/**
 * Custom Walker for Navigation Menu
 */

class TJS_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav-link';
        
        // Add active class for current page
        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $classes[] = 'active';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<a' . $id . $class_names . ' href="' . esc_url($item->url) . '">';
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        $output .= apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
        
        $output .= '</a>';
    }
}
