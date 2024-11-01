<?php

$current_db = + get_option( 'zestatix_db_version' );

if ( DB_VERSION_ZESTATIX !== $current_db ) {
    require_once( INCLUDES_ZESTATIX . 'db_upgrade.php' );
}

load_plugin_textdomain( 'zestatix', false, 'zestatix/lang/' );

require_once( INCLUDES_ZESTATIX . 'html_settings.php' );

add_action( 'admin_footer', 'page_settings_js' );

function page_settings_js() { ?>
    <script>
        <?php require_once( INCLUDES_ZESTATIX . 'src/togglelayer.js' ) ?>
    </script>

    <?php require_once( INCLUDES_ZESTATIX . 'js_settings.php' );
}
