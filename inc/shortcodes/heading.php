<?php
	//
	// Headings
	//
	function ci_shortcodes_get_documentation_heading() {
		$cishort_options = get_option( CI_SHORTCODES_PLUGIN_OPTIONS );

		return array(
			'h' => array(
				'title'       => esc_html__( 'Heading', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Styled heading.', 'cssigniter-shortcodes' ),
				'aliases'     => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ),
				'attributes'  => array(
					'level'        => array(
						'title'   => esc_html__( 'Heading Level', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( '1 to 6', 'cssigniter-shortcodes' ),
						'default' => sprintf( __( 'User configurable. Currently: %s', 'cssigniter-shortcodes' ), $cishort_options['headings_default_level'] ),
						'info'    => wp_kses( __( 'This is only used with the <strong>[h]</strong> syntax. <strong>[h1]</strong> to <strong>[h6]</strong> set this automatically.', 'cssigniter-shortcodes' ), array( 'strong' => array() ) ),
					),
					'bg_color'     => array(
						'title'   => esc_html__( 'Background Color', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'fg_color'     => array(
						'title'   => esc_html__( 'Foreground Color', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'border_color' => array(
						'title'   => esc_html__( 'Border Color', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'border_width' => array(
						'title'   => esc_html__( 'Border Width', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS border width declaration.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'corners'      => array(
						'title'   => esc_html__( 'Corners / Border Radius', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS border radius declaration.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'scheme'       => array(
						'title'   => esc_html__( 'Color Scheme', 'cssigniter-shortcodes' ),
						'values'  => implode( ', ', ci_shortcodes_get_default_color_schemes() ),
						'default' => '',
						'info'    => '',
					),
					'icon'         => array(
						'title'   => esc_html__( 'Icon', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid font-awesome icon code.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => wp_kses( __( 'Icon codes should be prefixed with <strong>fa-</strong>, e.g. <strong>fa-gear</strong>', 'cssigniter-shortcodes' ), array( 'strong' => array() ) ),
					),
					'spin'         => array(
						'title'   => esc_html__( 'Spinning Icon', 'cssigniter-shortcodes' ),
						'values'  => wp_kses( __( 'Simply type <em>spin</em> without a value.', 'cssigniter-shortcodes' ), array( 'em' => array() ) ),
						'default' => '',
						'info'    => '',
					),
					'padding'      => array(
						'title'   => esc_html__( 'Padding', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS padding declaration.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
				),
				'examples'    => array(
					'[h level="3" scheme="red"]My Heading[/h]',
					'[h2 icon="fa-gear" spin bg_color="#45a78f"]Bigger Heading[/h2]',
				),
			)
		);
	}

	function ci_shortcodes_headings( $atts, $content = null, $tag ) {

		$has_spin = array_search( 'spin', (array) $atts, true );
		if ( $has_spin !== false && is_numeric( $has_spin ) ) {
			$atts['spin_icon'] = true;
		}

		if ( in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ) {
			$atts['level'] = str_replace( 'h', '', $tag );
		}

		$cishort_options = get_option( CI_SHORTCODES_PLUGIN_OPTIONS );

		$atts = shortcode_atts( array(
			'level'        => $cishort_options['headings_default_level'],
			'bg_color'     => '',
			'fg_color'     => '',
			'border_color' => '',
			'border_width' => '',
			'corners'      => '',
			'icon'         => '',
			'spin_icon'    => '',
			'scheme'       => '',
			'padding'      => '',
		), $atts, $tag );
		extract( $atts );

		$attr_classes = array( 'cisc-heading' );
		$attr_style   = array();

		$level = intval( $level );
		if ( $level < 1 || $level > 6 ) {
			$level = $cishort_options['headings_default_level'];
		}
		$bg_color = _ci_shortcodes_sanitize_hex_color( $bg_color );
		if ( ! empty( $bg_color ) ) {
			$attr_style[] = 'background-color: ' . $bg_color . ';';
			if ( empty( $padding ) ) {
				$padding = '0.2em 0.6em';
			}
		}
		$fg_color = _ci_shortcodes_sanitize_hex_color( $fg_color );
		if ( ! empty( $fg_color ) ) {
			$attr_style[] = 'color: ' . $fg_color . ';';
		}
		$border_color = _ci_shortcodes_sanitize_hex_color( $border_color );
		if ( ! empty( $border_color ) ) {
			$attr_style[] = 'border-color: ' . $border_color . ';';
		}
		$border_width = _ci_shortcodes_sanitize_border_width( $border_width );
		if ( ! empty( $border_width ) ) {
			$attr_style[] = 'border-width: ' . $border_width . ';';
		}
		$corners = _ci_shortcodes_sanitize_border_radius( $corners );
		if ( ! empty( $corners ) ) {
			$attr_style[] = 'border-radius: ' . $corners . ';';
		}
		$padding = _ci_shortcodes_sanitize_padding( $padding );
		if ( ! empty( $padding ) ) {
			$attr_style[] = 'padding: ' . $padding . ';';
		}
		$scheme = sanitize_html_class( $scheme );
		if ( ! empty( $scheme ) ) {
			$attr_classes[] = 'cisc-shortcode-scheme-' . $scheme;
		}

		$icon      = sanitize_html_class( $icon );
		$spin_icon = $spin_icon == 'true' || $spin_icon == 'spin' || $spin_icon == true ? true : false;

		$markup = apply_filters( 'ci_shortcodes_heading_markup', '<h%1$s class="%2$s" style="%3$s">%4$s%5$s</h%1$s>' );
		$output = sprintf( $markup,
			$level,
			esc_attr( implode( ' ', $attr_classes ) ),
			esc_attr( implode( ' ', $attr_style ) ),
			empty( $icon ) ? '' : sprintf( '<i class="fa %s%s"></i>',
				esc_attr( $icon ),
				$spin_icon === false ? '' : esc_attr( ' fa-spin' )
			),
			$content
		);

		return $output;
	}
