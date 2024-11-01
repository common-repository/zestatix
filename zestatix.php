<?php

/*
Plugin Name: zeStatix
Plugin URI: http://x9618502.beget.tech/
Description: zeStatix ​​is counter clicks for the specified HTML elements.
Version: 1.2
Text Domain: zestatix
Domain Path: /lang
Author: Mykola Shadrin
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( !defined( 'ABSPATH' ) ) exit;

$zeStatix = new class {
	public function __construct() {
		define( 'VERSION_ZESTATIX', '1.2' );

		define( 'DB_VERSION_ZESTATIX', 104);

		define( 'INCLUDES_ZESTATIX', plugin_dir_path( __FILE__ ) . 'includes/' );

		define( 'TOGGLE_ZESTATIX', +get_option( 'zestatix_toggle' ) );

		define( 'SELECT_ZESTATIX', get_option( 'zestatix_select' ) );

		add_action( 'wp_loaded', [ $this, 'load' ] );

		require_once( INCLUDES_ZESTATIX . 'functions.php' );

		require_once( INCLUDES_ZESTATIX . 'db.php' );

		require_once( INCLUDES_ZESTATIX . 'wp_ajax.php' );
	}

	public function load() {
		if ( defined( 'WP_ADMIN' ) ) {
			require_once( INCLUDES_ZESTATIX . 'admin.php' );
		}

		require_once( INCLUDES_ZESTATIX . 'router.php' );

		if (!$route = router()) {
			return;
		}

		require_once( INCLUDES_ZESTATIX . "{$route}.php" );
	}
};

register_activation_hook( __FILE__, 'install_zestatix' );

register_deactivation_hook( __FILE__, 'uninstall_zestatix' );

function install_zestatix() {
	if ( !current_user_can( 'activate_plugins' ) ) {
		return;
	}

	global $wpdb;

	$charset_collate = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci;';

	$create_tables = [];

	$create_tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_element (
		id INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		selector VARCHAR( 255 ) NOT NULL UNIQUE,
		browser_width VARCHAR( 100 ) NOT NULL,
		created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		tracked INT( 1 ) NOT NULL
	) {$charset_collate}";

	$create_tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_event (
		selector_id INT( 10 ) NOT NULL,
		user_id INT( 10 ) NOT NULL,
		url_id SMALLINT NOT NULL,
		device VARCHAR( 6 ) NOT NULL,
		width VARCHAR( 15 ) NOT NULL,
		event TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		FOREIGN KEY ( selector_id ) REFERENCES {$wpdb->prefix}zestatix_element ( id ) ON DELETE CASCADE
	) {$charset_collate}";

	$create_tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_url (
		id INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		url TEXT NOT NULL
	) {$charset_collate}";

	$create_tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_loaded (
		elem INT( 10 ) NOT NULL,
		user INT( 10 ) NOT NULL,
		url SMALLINT NOT NULL,
		FOREIGN KEY ( elem ) REFERENCES {$wpdb->prefix}zestatix_element ( id ) ON DELETE CASCADE
	) {$charset_collate}";

	$create_tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_user (
		id INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		ip VARCHAR( 40 ) NOT NULL,
		location VARCHAR( 50 ) NULL,
		login VARCHAR( 50 ) NULL,
		INDEX( ip ),
		INDEX( login )
	) {$charset_collate}";

	$create_tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_url_tracking (
		id INT( 10 ) NOT NULL,
		url TEXT NOT NULL,
		subdir INT( 1 ) NOT NULL,
		FOREIGN KEY ( id ) REFERENCES {$wpdb->prefix}zestatix_element ( id ) ON DELETE CASCADE
	) {$charset_collate}";

	foreach ( $create_tables as $table ) {
		$wpdb->query( $table );
	}

	update_option( 'zestatix_toggle', 1 );

	update_option( 'zestatix_db_version', 104 );
}

function uninstall_zestatix() {
	if ( !current_user_can( 'delete_plugins' ) ) {
		return;
	}
	global $wpdb;

	$delete_tables = [ 'zestatix_user',
		  'zestatix_url',
		  'zestatix_event',
		'zestatix_loaded',
		 'zestatix_url_tracking',
		 'zestatix_element' ];

	foreach ( $delete_tables as $table ) {
		$query = 'DROP TABLE IF EXISTS ' . $wpdb->prefix . $table;
		$wpdb->query( $query );
	}

	$delete_options = [
		'zestatix_db_version',
		'zestatix_toggle',
		'zestatix'
	];

	 foreach( $delete_options as $option ) {
		 delete_option( $option );
	 }

}

__( 'Click statistics for any selected element of the site page.', 'zestatix' );
