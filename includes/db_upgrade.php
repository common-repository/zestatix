<?php
	if ( !defined( 'ABSPATH' && !current_user_can( 'edit_plugins' ) ) )
		exit;

	if ( empty( $current_db ) || $current_db < 101 ) db_101_zestatix();

	function db_101_zestatix() {
		global $wpdb;

		$wpdb->query( "ALTER TABLE {$wpdb->prefix}zestatix_event ADD width VARCHAR( 25 )" );

		$current_db = 101;
	}

	if ( $current_db < 102 ) db_102_zestatix();

	function db_102_zestatix() {
		global $wpdb;

	  $charset_collate = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci';

		$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_loaded (
				elem BIGINT( 20 ) NOT NULL,
				user INT( 10 ) NOT NULL,
				url SMALLINT NOT NULL,
				FOREIGN KEY ( elem ) REFERENCES {$wpdb->prefix}zestatix_element ( id ) ON DELETE CASCADE
			) {$charset_collate}" );

		$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_location (
				id BIGINT( 20 ) NOT NULL,
				country VARCHAR( 25 ) NOT NULL
			) {$charset_collate}" );

		$query = $wpdb->get_results( "SELECT id, country FROM {$wpdb->prefix}zestatix_user" );

		foreach ( $query as $row ) {
			$wpdb->insert(
				$wpdb->prefix . 'zestatix_location',
				array( 'id' => $row->id, 'country' => $row->country ),
				array( '%d', '%s' )
			);
		}

		$wpdb->query( "ALTER TABLE {$wpdb->prefix}zestatix_user DROP COLUMN country" );

		$current_db = 102;
	}

	if ( $current_db < 103 ) db_103_zestatix();

	function db_103_zestatix() {
		global $wpdb;

		$charset_collate = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci';

		$wpdb->query( "ALTER TABLE {$wpdb->prefix}zestatix_element DROP COLUMN tracking" );

		$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zestatix_url_tracking (
			id BIGINT( 20 ) NOT NULL,
			url TEXT NOT NULL,
			subdir INT( 1 ) NOT NULL,
			FOREIGN KEY ( id ) REFERENCES {$wpdb->prefix}zestatix_element ( id ) ON DELETE CASCADE
		) {$charset_collate}" );

		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}zestatix_location" );

		$wpdb->query( "ALTER TABLE {$wpdb->prefix}zestatix_user
			ADD location VARCHAR( 50 ) NOT NULL,
			ADD login VARCHAR( 50 ) NOT NULL" );

		$wpdb->update( $wpdb->prefix.'zestatix_user', [ 'name' => 'unknown' ], [ 'name' => '' ], [ '%s' ] );

		$current_db = 103;
	}

	if ( $current_db < 104 ) db_104_zestatix();

	function db_104_zestatix() {
		global $wpdb;

		$queries[] = "ALTER TABLE {$wpdb->prefix}zestatix_url MODIFY COLUMN id INT(10) AUTO_INCREMENT";

		$queries[] = "ALTER TABLE {$wpdb->prefix}zestatix_user MODIFY COLUMN location VARCHAR(50) NULL";

		$queries[] = "ALTER TABLE {$wpdb->prefix}zestatix_user MODIFY COLUMN login VARCHAR(50) NULL";

		$queries[] = "UPDATE {$wpdb->prefix}zestatix_user SET login = '' WHERE login = 'unknown'";

		$queries[] = "UPDATE {$wpdb->prefix}zestatix_user SET location = '' WHERE location = 'unknown'";

		$queries[] = "CREATE INDEX idx_login ON {$wpdb->prefix}zestatix_user( login )";

		$queries[] = "CREATE INDEX idx_ip ON {$wpdb->prefix}zestatix_user( ip )";

		foreach( $queries as $query ) {
			$wpdb->query( $query );
		}

		$current_db = 104;
	}

	update_option( 'zestatix_db_version', $current_db );
?>
