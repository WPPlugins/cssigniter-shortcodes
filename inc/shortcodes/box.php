<?php
	//
	// Boxes
	//
	function ci_shortcodes_get_documentation_box() {
		return array(
			'box' => array(
				'title'       => esc_html__( 'Box', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Styled box / container.', 'cssigniter-shortcodes' ),
				'aliases'     => array(),
				'attributes'  => array(
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
				),
				'examples'    => array(
					'[box scheme="red" icon="fa-ban"]My error box[/h]',
					'[box border_color="pink" border_width="2px" corners="8px / 16px"]My strange box[/h]',
				),
			)
		);
	}
	function _ci_shortcodes_boxes_back_compat( $atts ) {
		// Re-map old predefined types to the new color schemes and icons.
		if ( ! empty( $atts['type'] ) ) {
			switch ( $atts['type'] ) {
				case 'info':
					$atts['scheme'] = 'blue';
					$atts['icon']   = 'fa-info-circle';
					break;
				case 'warning':
					$atts['scheme'] = 'yellow';
					$atts['icon']   = 'fa-exclamation-triangle';
					break;
				case 'download':
					$atts['scheme'] = 'grey';
					$atts['icon']   = 'fa-download';
					break;
				case 'error':
					$atts['scheme'] = 'red';
					$atts['icon']   = 'fa-ban';
					break;
				case 'success':
					$atts['scheme'] = 'green';
					$atts['icon']   = 'fa-check';
					break;
				case 'normal':
				default:
			}
		}

		return $atts;
	}
	function ci_shortcodes_boxes( $atts, $content = null, $tag ) {
		$atts = _ci_shortcodes_boxes_back_compat( $atts );

		$has_spin = array_search( 'spin', (array) $atts, true );
		if ( $has_spin !== false && is_numeric( $has_spin ) ) {
			$atts['spin_icon'] = true;
		}

		$atts = shortcode_atts( array(
			'bg_color'     => '',
			'fg_color'     => '',
			'border_color' => '',
			'border_width' => '',
			'corners'      => '',
			'icon'         => '',
			'spin_icon'    => '',
			'scheme'       => '',
		), $atts, $tag );
		extract( $atts );

		$attr_classes = array( 'cisc-box' );
		$attr_style   = array();

		$bg_color = _ci_shortcodes_sanitize_hex_color( $bg_color );
		if ( ! empty( $bg_color ) ) {
			$attr_style[] = 'background-color: ' . $bg_color . ';';
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
		$scheme = sanitize_html_class( $scheme );
		if ( ! empty( $scheme ) ) {
			$attr_classes[] = 'cisc-shortcode-scheme-' . $scheme;
		}

		$icon      = sanitize_html_class( $icon );
		$spin_icon = $spin_icon == 'true' || $spin_icon == 'spin' || $spin_icon == true ? true : false;

		$markup = apply_filters( 'ci_shortcodes_box_markup', '<div class="%1$s" style="%2$s">%3$s<div class="cisc-box-content">%4$s</div></div>' );
		$output = sprintf( $markup,
			esc_attr( implode( ' ', $attr_classes ) ),
			esc_attr( implode( ' ', $attr_style ) ),
			empty( $icon ) ? '' : sprintf( '<i class="fa %s%s"></i>',
				esc_attr( $icon ),
				$spin_icon === false ? '' : esc_attr( ' fa-spin' )
			),
			do_shortcode( $content )
		);

		return $output;
	}
