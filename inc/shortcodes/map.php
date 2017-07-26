<?php
	//
	// Google Maps
	//
	function ci_shortcodes_get_documentation_map() {
		return array(
			'map' => array(
				'title'       => esc_html__( 'Map', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Map with place marker.', 'cssigniter-shortcodes' ),
				'aliases'     => array( 'googlemap' ),
				'attributes'  => array(
					'lat'   => array(
						'title'   => esc_html__( 'Latitude', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( '-90 to 90', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => wp_kses( __( 'Can also be decimal, e.g. <em>1.2345</em>', 'cssigniter-shortcodes' ), array( 'em' => array() ) ),
					),
					'lon'   => array(
						'title'   => esc_html__( 'Longitude', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( '-180 to 180', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => wp_kses( __( 'Can also be decimal, e.g. <em>1.2345</em>', 'cssigniter-shortcodes' ), array( 'em' => array() ) ),
					),
					'zoom'  => array(
						'title'   => esc_html__( 'Zoom', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( '0 to 21', 'cssigniter-shortcodes' ),
						'default' => '8',
						'info'    => esc_html__( "Maximum zoom isn't supported everywhere.", 'cssigniter-shortcodes' ),
					),
					'title' => array(
						'title'   => esc_html__( 'Marker Title', 'cssigniter-shortcodes' ),
						'values'  => '',
						'default' => '',
						'info'    => esc_html__( 'Appears when hovering over the map marker.', 'cssigniter-shortcodes' ),
					),
					'text'  => array(
						'title'   => esc_html__( 'Marker Text', 'cssigniter-shortcodes' ),
						'values'  => '',
						'default' => '',
						'info'    => esc_html__( 'Appears when the map marker gets clicked.', 'cssigniter-shortcodes' ),
					),
				),
				'examples'    => array(
					'[map lat="40.7828649" lon="-73.9653549" zoom="16" title="Central Park" text="Lower Manhattan" /]',
				),
			)
		);
	}
	function _ci_shortcodes_googlemaps_back_compat( $atts ) {
		if ( ! empty( $atts['src'] ) ) {
			// Extract ll (lat lon) parameter.
			preg_match( '#(\?|&|&amp;)ll=(?<lat>(\d+)(\.\d+)?),(?<lon>(\d+)(\.\d+)?)#i', $atts['src'], $match );
			if ( ! empty( $match['lat'] ) && ! empty( $match['lon'] ) ) {
				$atts['lat'] = $match['lat'];
				$atts['lon'] = $match['lon'];
			}

			// Extract z (zoom) parameter.
			preg_match( '#(\?|&|&amp;)z=(?<zoom>\d+)#i', $atts['src'], $match );
			if ( ! empty( $match['zoom'] ) ) {
				$atts['zoom'] = $match['zoom'];
			}
		}

		return $atts;
	}

	function ci_shortcodes_googlemaps($atts, $content = null, $tag) {
		$atts = _ci_shortcodes_googlemaps_back_compat( $atts );

		$atts = shortcode_atts( array(
			'lat'   => '',
			'lon'   => '',
			'zoom'  => 8,
			'title' => '',
			'text'  => '',
		), $atts, $tag );
		extract( $atts );

		$lat = sanitize_text_field( $lat );
		if ( ! empty( $lat ) ) {
			if ( floatval( $lat ) < - 90 || floatval( $lat ) > 90 ) {
				$lat = 0;
			}
		}
		$lon = sanitize_text_field( $lon );
		if ( ! empty( $lon ) ) {
			if ( floatval( $lon ) < - 180 || floatval( $lon ) > 180 ) {
				$lon = 0;
			}
		}
		$zoom = intval( $zoom );
		if ( $zoom < 0 || $zoom > 21 ) {
			$zoom = 8;
		}

		$title = sanitize_text_field( $title );

		if ( ! empty( $content ) && ! is_null( $content ) ) {
			$text = $content;
		}
		$text = wp_kses_post( $text );

		$markup = apply_filters( 'ci_shortcodes_map_markup', '<div class="cisc-map-wrapper"><div id="%1$s" class="cisc-map" data-lat="%2$s" data-long="%3$s" data-zoom="%4$s" data-title="%5$s" data-tooltip="%6$s"></div></div>' );
		$output = sprintf( $markup,
			esc_attr( uniqid( 'map-' ) ),
			esc_attr( $lat ),
			esc_attr( $lon ),
			esc_attr( $zoom ),
			esc_attr( $title ),
			esc_attr( do_shortcode( $text ) )
		);

		return $output;
	}
