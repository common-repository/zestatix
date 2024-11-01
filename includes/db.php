<?php

if ( !defined( 'ABSPATH' ) ) exit;

class DB_zeStatix {
	public static function loaded_element( $data ) {
		global $wpdb;

		$wpdb->insert( $wpdb->prefix . 'zestatix_loaded',
			array( 'elem' => $data[ 'id' ], 'user' => self::get_user_id(), 'url' => self::get_url_id( $data[ 'url' ] ) ),
			array( '%d', '%d', '%d' )
		);
	}

	public static function add_event( $data ) {
		global $wpdb;

		$device = ( wp_is_mobile() ) ? 'mobile' : 'PC';

		$wpdb->insert( $wpdb->prefix . 'zestatix_event',
			array( 'selector_id' => $data['id'], 'user_id' => self::get_user_id(), 'url_id' => self::get_url_id( $data[ 'url' ] ), 'device' => $device, 'width' => $data['width'] ),
			array( '%d', '%d', '%d', '%s', '%s' )
		);
	}

	public static function get_all_selectors() {
		if ( !current_user_can( 'edit_plugins' ) ) return;

		global $wpdb;

		$selectors = [];

		$query = $wpdb->get_results( "SELECT selector FROM {$wpdb->prefix}zestatix_element" );

		foreach ( $query as $selector ) {
			$selectors[] = $selector->selector;
		}

		return $selectors;
	}

	public static function add_element( $element ) {
		if ( !current_user_can( 'edit_plugins' ) ) return;

		global $wpdb;

		$wpdb->query(
			$wpdb->prepare( "INSERT INTO {$wpdb->prefix}zestatix_element ( selector, browser_width, tracked ) VALUE ( %s, %s, %d )", $element['selector'], serialize( $element['browser_width'] ), 1 )
		);

		foreach( $element['track_on'] as $url => $arr ) {
			$wpdb->query(
				$wpdb->prepare( "INSERT INTO {$wpdb->prefix}zestatix_url_tracking ( id, url, subdir ) VALUE ( %d, %s, %d )", self::get_id_selector( $element['selector'] ), $url, $arr[ 'subdirectories' ] )
			);
		}
	}

	public static function update_elements( $post ) {
		if ( !current_user_can( 'edit_plugins' ) ) return;

		global $wpdb;

		$data_selectors = self::get_all_selectors();

		$post_selectors = [];

		$wpdb->query( "TRUNCATE {$wpdb->prefix}zestatix_url_tracking" );

		foreach ( $post as $element ) {
			if ( !$element['selector'] ) {
				break;
			}

			if ( !in_array( $element['selector'], $data_selectors ) ) {
				$wpdb->query(
					$wpdb->prepare( "INSERT INTO {$wpdb->prefix}zestatix_element ( selector, browser_width, tracked ) VALUE ( %s, %s, %d )", $element['selector'], serialize( $element['browser_width'] ), $element['tracked'] )
				);
			} else {
				$wpdb->query(
					$wpdb->prepare( "UPDATE {$wpdb->prefix}zestatix_element SET browser_width = %s, tracked = %s WHERE selector = %s", serialize( $element['browser_width'] ), $element['tracked'], $element['selector'] )
				);
			}

			foreach( $element['track_on'] as $url => $arr ) {
				$wpdb->query(
					$wpdb->prepare( "INSERT INTO {$wpdb->prefix}zestatix_url_tracking ( id, url, subdir ) VALUE ( %d, %s, %d )", self::get_id_selector( $element['selector'] ), $url, $arr[ 'subdirectories' ] )
				);
			}

			$post_selectors[] = $element['selector'];
		}

		foreach ( $data_selectors as $selector ) {
			if ( !in_array( $selector, $post_selectors ) ) {
				$wpdb->delete( $wpdb->prefix . 'zestatix_element', array( 'selector' => $selector ) );
			}
		}
	}

	public static function get_elements_by_url( $url ) {
		global $wpdb;

		$results = $wpdb->get_results( $wpdb->prepare("
			SELECT DISTINCT
				{$wpdb->prefix}zestatix_url_tracking.id
			FROM
				{$wpdb->prefix}zestatix_url_tracking
			INNER JOIN
				{$wpdb->prefix}zestatix_element
			ON
				{$wpdb->prefix}zestatix_url_tracking.id = {$wpdb->prefix}zestatix_element.id
			WHERE
				tracked = 1
			AND ( ( url = %s AND subdir = 1 )
				OR ( url = %s )
				OR ( subdir = 1 AND %s REGEXP CONCAT( '^', url ) )
			)",
		home_url( '/' ), $url, $url ) );

		$current_element = [];

		if ( $results ) {
			$ids = [];

			foreach( $results as $row ) {
				$ids[] = $row->id;
			}

			$current_element = self::get_element_by_id( $ids );
		}

		return $current_element;
	}

	public static function get_element_by_id( $ids ) {
		global $wpdb;

		$formats = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

		$query = $wpdb->get_results( $wpdb->prepare( "SELECT id, selector, browser_width FROM {$wpdb->prefix}zestatix_element WHERE id IN ( $formats )", $ids ) );

		$results = [];

		if ( $query ) {
			foreach ( $query as $row ) {
				$results[] = [
					'id'            => $row->id,
					'selector'      => $row->selector,
					'browser_width' => unserialize( $row->browser_width )
				];
			}
		}

		return $results;
	}

	public static function get_details_click() {
		if ( !current_user_can( 'edit_plugins' ) ) return;

		global $wpdb;

		$results = self::get_loaded();

		$query = $wpdb->get_results(
			"SELECT
				{$wpdb->prefix}zestatix_event.device,
				{$wpdb->prefix}zestatix_event.width,
				{$wpdb->prefix}zestatix_event.event,
				{$wpdb->prefix}zestatix_element.selector,
				{$wpdb->prefix}zestatix_element.created,
				{$wpdb->prefix}zestatix_user.ip,
				{$wpdb->prefix}zestatix_user.location,
				{$wpdb->prefix}zestatix_user.login,
				{$wpdb->prefix}zestatix_url.url
			FROM
				{$wpdb->prefix}zestatix_event
			INNER JOIN
				{$wpdb->prefix}zestatix_element
			ON
				{$wpdb->prefix}zestatix_event.selector_id = {$wpdb->prefix}zestatix_element.id
			INNER JOIN
				{$wpdb->prefix}zestatix_user
			ON
				{$wpdb->prefix}zestatix_event.user_id = {$wpdb->prefix}zestatix_user.id
			INNER JOIN
				{$wpdb->prefix}zestatix_url
			ON
				{$wpdb->prefix}zestatix_event.url_id = {$wpdb->prefix}zestatix_url.id
			" );

		if ( $query ) {
			foreach( $query as $row ) {
				if ( empty( $login = $row->login ) ) $login = 'unknown';

				if ( empty( $location = $row->location ) ) $location = 'unknown';

				$results[$row->selector]['clicks'][] = ['date' => $row->event, 'ip' => $row->ip, 'url' => $row->url, 'location' => $location, 'login' => $login, 'device' => $row->device, 'width' => $row->width ];
			}
		}

		return $results;
	}

	public static function get_loaded() {
		if ( !current_user_can( 'edit_plugins' ) ) return;

		global $wpdb;

		$loadeds = $wpdb->get_results(
			"SELECT
				{$wpdb->prefix}zestatix_element.selector,
				{$wpdb->prefix}zestatix_element.created,
				{$wpdb->prefix}zestatix_loaded.user,
				{$wpdb->prefix}zestatix_loaded.url
			FROM
				{$wpdb->prefix}zestatix_loaded
			INNER JOIN
				{$wpdb->prefix}zestatix_element
			ON
				{$wpdb->prefix}zestatix_loaded.elem = {$wpdb->prefix}zestatix_element.id"
		);

		$results = [];

		if( $loadeds ) {
			foreach ( $loadeds as $row ) {
				if( !isset( $results[$row->selector]['loaded']['users'] ) || !in_array( $row->user, $results[$row->selector]['loaded']['users'] ) ) {
					$results[$row->selector]['loaded']['users'][] = $row->user;
				}

				if( !isset( $results[$row->selector]['loaded']['urls'] ) || !in_array( $row->url, $results[$row->selector]['loaded']['urls'] ) ) {
					$results[$row->selector]['loaded']['urls'][] = $row->url;
				}

				if( !isset( $results[$row->selector]['created'] ) ) {
					$results[$row->selector]['created'] = $row->created;
				}

				if( !isset( $results[$row->selector]['loaded']['count'] ) ) {
					$results[$row->selector]['loaded']['count'] = 1;
				} else {
					$results[$row->selector]['loaded']['count']++;
				}
			}
		}

		return $results;
	}

	public static function get_id_selector( $selector ) {
		if ( !current_user_can( 'edit_plugins' ) ) return;

		global $wpdb;

		$id = $wpdb->get_var(
			$wpdb->prepare( "SELECT id FROM {$wpdb->prefix}zestatix_element WHERE selector = %s LIMIT 1", $selector ) );

		return $id;
	}

	public static function get_url_id( $url ) {
		global $wpdb;

		$find_url = self::find_url_id( $url );

		if ( empty( $find_url ) ) {
			$wpdb->insert(
				$wpdb->prefix . 'zestatix_url',
				array( 'url' => $url ),
				'%s'
			 );

			 $find_url = $wpdb->insert_id;
	 	}

		return $find_url;
	}

	public static function find_url_id( $url ) {
		global $wpdb;

		$query = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}zestatix_url WHERE url = %s LIMIT 1", $url ) );

		return $query;
	}

	public static function add_user( $location = '' ) {
		global $wpdb;

		$login = wp_get_current_user()->user_login;

		$wpdb->insert( $wpdb->prefix . 'zestatix_user',
			array( 'ip' => $_SERVER['REMOTE_ADDR'], 'location' => $location, 'login' => $login ),
			array( '%s', '%s', '%s' )
		);

		return $wpdb->insert_id;
	}

	public static function get_user_id() {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}zestatix_user WHERE login = %s OR ip = %s AND login = %s LIMIT 1", wp_get_current_user()->user_login, $_SERVER['REMOTE_ADDR'], '' ) );
	}

	public static function update_user_location( $location ) {
		global $wpdb;

		$wpdb->update( $wpdb->prefix . 'zestatix_user', [ 'location' => $location ], [ 'ip' => $_SERVER['REMOTE_ADDR'] ], [ '%s', '%s' ] );
	}

	public static function get_user_location_by_ip() {
		global $wpdb;

		$query = $wpdb->get_var( $wpdb->prepare( "SELECT location FROM {$wpdb->prefix}zestatix_user WHERE ip = %s LIMIT 1", $_SERVER['REMOTE_ADDR'] ) );

		return $query;
	}

	public static function del_data_selector( $selector ) {
		if ( !current_user_can( 'edit_plugins' ) ) return;

		global $wpdb;

		$id = self::get_id_selector( $selector );

		$wpdb->delete( "{$wpdb->prefix}zestatix_event", array( 'selector_id' => $id ) );

		$wpdb->delete( "{$wpdb->prefix}zestatix_loaded", array( 'elem' => $id ) );
	}
}
