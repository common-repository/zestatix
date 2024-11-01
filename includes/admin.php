<?php

add_action( 'admin_menu', 'menu_link' );

add_filter( 'plugin_action_links', 'link_settings', 10, 2 );

function link_settings( $links, $file ) {
    if ( strpos( $file, 'zestatix.php' ) == false )
        return $links;

    $link_settings = '<a href="plugins.php?page=zestatix.php">' . __( 'Settings', 'zestatix' ) . '</a>';

    array_unshift( $links, $link_settings );

    return $links;
}

function menu_link() {
    add_submenu_page( 'plugins.php', 'zeStatix', 'zeStatix', 'edit_plugins', 'zestatix', 'page_setting' );
}

function page_setting() {
    include_once( INCLUDES_ZESTATIX . 'settings.php' );
}