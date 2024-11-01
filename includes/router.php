<?php

function router() {
    $route = false;

    if ( is_select_zestatix() ) {
        $route = 'select';
    }

    elseif ( is_frontend_zestatix() ) {
        $route = 'frontend';
    }

    return $route;
}