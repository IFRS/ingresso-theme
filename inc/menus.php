<?php
// Registra os menus
register_nav_menus(
    array(
        'main' => 'Menu Principal',
    )
);

/* Customiza os menus para funcionarem com o Bulma */
add_filter( 'nav_menu_submenu_css_class', function( $classes ) {
    $classes[] = 'dropdown-menu';

    return $classes;
}, 10, 1 );

add_filter( 'nav_menu_css_class', function( $classes, $item, $args, $depth ) {
    if ($item->menu_item_parent != 0) {
        $classes[] = 'dropdown-item';
    } else {
        $classes[] = 'nav-item';
    }

    if ( array_search( 'menu-item-has-children', $classes ) && $item->menu_item_parent == 0) {
        $classes[] = 'dropdown';
    }

    return $classes;
}, 10, 4);

add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args, $depth ) {
    $atts['class'] = 'nav-link';

    if ( array_search('menu-item-has-children', $item->classes ) && $item->menu_item_parent == 0 ) {
        $atts['class'] = 'nav-link dropdown-toggle';
        $atts['role'] = 'button';
        $atts['data-bs-toggle'] = 'dropdown';
        $atts['aria-expanded'] = 'false';
    }

    return $atts;
}, 10, 4 );