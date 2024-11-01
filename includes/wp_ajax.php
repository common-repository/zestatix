<?php

add_action( 'wp_ajax_clear_history_zestatix', 'clear_history' );

add_action( 'wp_ajax_data_settings_zestatix', 'data_settings' );

add_action( 'wp_ajax_nopriv_data_zestatix', 'data_front' );

add_action( 'wp_ajax_data_zestatix', 'data_front' );

add_action( 'wp_ajax_set_select_zestatix', 'data_select' );

add_action( 'wp_ajax_get_select_zestatix', 'get_data_select' );

add_action( 'wp_ajax_save_select_zestatix', 'save_select' );

add_action( 'wp_ajax_exit_select_zestatix', 'exit_select' );

function data_settings() {
    if ( !empty( $_POST[ 'settings' ] ) ) {
        $settings = sanitizer_zestatix( $_POST[ 'settings' ] );

        DB_zeStatix::update_elements( $settings );

        update_option( 'zestatix', $settings );
    }

    if ( !empty( $_POST[ 'toggler' ] ) ) {
        update_option( 'zestatix_toggle', +$_POST[ 'toggler' ] );
    }

    if ( !empty( $_POST[ 'select' ] ) ) {
        update_option( 'zestatix_select', wp_get_current_user()->user_login );
    }

}

function clear_history() {
    $selector = sanitizer_zestatix( $_POST[ 'selector' ] );

    if ( strlen( $selector ) ) {
        DB_zeStatix::del_data_selector( $selector );
    }
}

function data_front() {
    $data = sanitizer_zestatix( $_POST[ 'data' ] );

    foreach ( $data as $value ) {
        switch ( $value[ 'action' ] ) {
            case 'add_event':
                DB_zeStatix::add_event( sanitizer_zestatix( $value[ 'data' ] ) );

                break;
            case 'add_user':
                DB_zeStatix::add_user( sanitizer_zestatix( $value[ 'location' ] ) );

                break;
            case 'update_user_location':
                DB_zeStatix::update_user_location( sanitizer_zestatix( $value[ 'location' ] ) );

                break;
            case 'loaded_element':
                DB_zeStatix::loaded_element( sanitizer_zestatix( $value[ 'data' ] ) );

                break;
        }
    }

    die();
}

function data_select() {
    update_option( 'zestatix_data_select', sanitizer_zestatix( $_POST[ 'panel_data' ] ) );
}

function get_data_select() {
    echo ( $data = get_option( 'zestatix_data_select' ) ) ?: $data;

    die;
}

function save_select() {
    $selected = sanitizer_zestatix( $_POST[ 'data' ] );

    if ( $selected[ 'selector' ] ) {
        $data = get_option( 'zestatix' ) ?: [];

        array_push( $data, $selected );

        update_option( 'zestatix', $data );

        DB_zeStatix::add_element( $selected );
    }

    exit_select();

    die;
}

function exit_select() {
    delete_option( 'zestatix_select' );

    delete_option( 'zestatix_data_select' );
}