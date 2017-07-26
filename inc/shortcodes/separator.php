<?php
	//
	// Separator
	//
	function ci_shortcodes_get_documentation_separator() {
		return array(
			'separator' => array(
				'title'       => esc_html__( 'Separator', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Styled separator.', 'cssigniter-shortcodes' ),
				'aliases'     => array( 'hr' ),
				'attributes'  => array(
					'type'        => array(
						'title'   => esc_html__( 'Type', 'cssigniter-shortcodes' ),
						'values'  => 'solid, dotted, dashed, double, groove, ridge, inset, outset',
						'default' => 'solid',
						'info'    => '',
					),
					'color'     => array(
						'title'   => esc_html__( 'Color', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'width'     => array(
						'title'   => esc_html__( 'Width', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS length value.', 'cssigniter-shortcodes' ),
						'default' => '100%',
						'info'    => '',
					),
					'height'     => array(
						'title'   => esc_html__( 'Height', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS length value.', 'cssigniter-shortcodes' ),
						'default' => '3px',
						'info'    => '',
					),
					'scheme'       => array(
						'title'   => esc_html__( 'Color Scheme', 'cssigniter-shortcodes' ),
						'values'  => implode( ', ', ci_shortcodes_get_default_color_schemes() ),
						'default' => '',
						'info'    => '',
					),
					'center'         => array(
						'title'   => esc_html__( 'Center Align', 'cssigniter-shortcodes' ),
						'values'  => wp_kses( __( 'Simply type <em>center</em> without a value.', 'cssigniter-shortcodes' ), array( 'em' => array() ) ),
						'default' => '',
						'info'    => '',
					),
				),
				'examples'    => array(
					'[separator scheme="red" type="dashed" width="50%" center /]',
					'[hr color="#00ff00" type="dotted" width="200px" /]',
				),
			)
		);
	}
	function ci_shortcodes_hr( $atts, $content = null, $tag ) {
		$centered = array_search( 'center', (array) $atts, true );
		if ( $centered !== false && is_numeric( $centered ) ) {
			$atts['center'] = true;
		}

		$atts = shortcode_atts( array(
			'type'   => 'solid',
			'color'  => '',
			'width'  => '100%',
			'height' => '3px',
			'scheme' => '',
			'center' => '',
		), $atts, $tag );
		extract( $atts );

		$attr_classes = array( 'cisc-separator' );
		$attr_style   = array();

		if ( ! empty( $type )
		     && in_array( $type, array(
					'solid',
					'dotted',
					'dashed',
					'double',
					'groove',
					'ridge',
					'inset',
					'outset'
				) )
		) {
			$attr_style[] = 'border-bottom-style: ' . $type . ';';
		}
		$color = _ci_shortcodes_sanitize_hex_color( $color );
		if ( ! empty( $color ) ) {
			$attr_style[] = 'border-bottom-color: ' . $color . ';';
		}
		$width = _ci_shortcodes_sanitize_css_unit_value( $width );
		if ( ! empty( $width ) ) {
			$attr_style[] = 'width: ' . $width . ';';
		}
		$height = _ci_shortcodes_sanitize_css_unit_value( $height, array( 'medium', 'thin', 'thick', 'initial', 'inherit' ) );
		if ( ! empty( $height ) ) {
			$attr_style[] = 'border-bottom-width: ' . $height . ';';
		}
		$scheme = sanitize_html_class( $scheme );
		if ( ! empty( $scheme ) ) {
			$attr_classes[] = 'cisc-shortcode-scheme-' . $scheme;
		}

		if ( in_array( $center, array( 'on', 'true', true ) ) ) {
			$attr_style[] = 'margin-left: auto; margin-right: auto;';
		}

		$markup = apply_filters( 'ci_shortcodes_separator_markup', '<hr class="%1$s" style="%2$s">' );
		$output = sprintf( $markup,
			esc_attr( implode( ' ', $attr_classes ) ),
			esc_attr( implode( ' ', $attr_style ) )
		);

		return $output;
	}
