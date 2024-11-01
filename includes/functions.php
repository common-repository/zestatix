<?php

function sanitizer_zestatix( $value ) {
    $sanitized = json_decode(
        sanitize_post(
            json_encode(
                wp_unslash( $value )
            ),
        'db' ),
    true );

    return $sanitized;
}

function is_frontend_zestatix() {
    return !is_customize_preview() && !defined( 'WP_ADMIN' ) && !SELECT_ZESTATIX;
}

function is_select_zestatix() {
    $login = wp_get_current_user()->user_login;

    $select = ($login && $login === SELECT_ZESTATIX) ? 1 : 0;

    return $select;
}