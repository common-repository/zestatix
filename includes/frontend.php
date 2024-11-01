<?php
if ( !defined( 'ABSPATH' ) ) exit;

$frontend_zeStatix = new class {
	function __construct() {
		$this->url = urldecode( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

		$this->elements = DB_zeStatix::get_elements_by_url( $this->url );

		if ( count($this->elements) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'include_jquery' ]);

			add_action( 'wp_enqueue_scripts', [ $this, 'geoplugin' ] );

			add_action( 'wp_footer', [ $this, 'print_script' ] );
		}
	}

	function include_jquery() {
		wp_enqueue_script('jquery');
	}

	function print_script() { ?>
		<script>
			(($) => {
				'use strict';

				const ajax_url = '<?= admin_url( 'admin-ajax.php' ) ?>';

				const location = ( typeof geoplugin_countryName == 'function' && typeof geoplugin_city == 'function' ) ? escape_html( `${ geoplugin_countryName() }, ${ geoplugin_city() }` ) : '';

				const url = window.location.href.split( '//' )[ 1 ];

				const data = [];

				<?php if ( empty( DB_zeStatix::get_user_id() ) ) { ?>
					data.push( {
						action:	'add_user',
						location: location
					} );
				<?php } else if ( empty( DB_zeStatix::get_user_location_by_ip() ) ) { ?>
					if (location.length)
						data.push( {
							action:	'update_user_location',
							location: location
						} )
				<?php } ?>

				const elements = <?= json_encode($this->elements) ?>;

				for (const {id, selector, browser_width} of elements) {
					let node;

					try {
						node = $(selector)
					} catch {
						continue
					}

					if (!node.length)
						continue;

					if ( check_width( browser_width ) ) {
						data.push( {
							action: 'loaded_element',
							data: {
								id: id,
								url: url,
							}
						} );
					}

					$( document ).on( 'mousedown', selector, () => {
						if ( !check_width( browser_width ) )
							return;

						send_data( [ {
							action: 'add_event',
							data: {
								id: id,
								url: url,
								width: ( window.screen.width == window.outerWidth ) ? String( window.screen.width ) : String( window.screen.width + ' / ' + window.outerWidth ),
							}
						} ] )
					});
				}

				const send_data = ( data, async = true ) => {
					$.ajax( {
						type: 'POST',
						async: async,
						url: ajax_url,
						data: {
							action: 'data_zestatix',
							data: data
						},
					} )
				}

				if ( data.length ) send_data( data, false )

				function check_width( data ) {
					const { min, max } = data

					if (!min && !max) return true

					const window_width = document.documentElement.clientWidth;

					if ( ( max == 0 && min <= window_width )
							|| ( min == 0 && max >= window_width )
								|| ( min <= window_width && max >= window_width ) )
						return true;

					return false
				}

				function escape_html( text ) {
					if ( !text || !text.length ) {
						return '';
					} else {
						return text.replace( /&/g, '&amp;' )
							.replace( /</g, '&lt;' )
								.replace( />/g, '&gt;' )
									.replace( /"/g, '&quot;' )
										.replace( /'/g, '&#039;' )
					}
				}
			})(jQuery);
		</script>
	<?php }

	function geoplugin() {
		// geoPlugin is the easiest way for you to geolocate your visitors, allowing you to provide geolocalised content more relevant to their geographical location.
		// www.geoplugin.com

		wp_enqueue_script( 'geoplugin-for-zestatix', 'http://www.geoplugin.net/javascript.gp' );
	}
};
