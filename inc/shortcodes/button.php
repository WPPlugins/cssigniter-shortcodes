<?php
	//
	// Buttons
	//
	function ci_shortcodes_get_documentation_button() {
		return array(
			'button' => array(
				'title'       => esc_html__( 'Button', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Styled button.', 'cssigniter-shortcodes' ),
				'aliases'     => array(),
				'attributes'  => array(
					'url'     => array(
						'title'   => esc_html__( 'Link URL', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid URL.', 'cssigniter-shortcodes' ),
						'default' => '#',
						'info'    => '',
					),
					'target'     => array(
						'title'   => esc_html__( 'Frame Target', 'cssigniter-shortcodes' ),
						'values'  => '_blank, _self, _parent, _top',
						'default' => '',
						'info'    => '',
					),
					'size'     => array(
						'title'   => esc_html__( 'Button Size', 'cssigniter-shortcodes' ),
						'values'  => 'small, medium, large',
						'default' => 'medium',
						'info'    => '',
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
						'default' => 'transparent',
						'info'    => '',
					),
					'border_width' => array(
						'title'   => esc_html__( 'Border Width', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS border width declaration.', 'cssigniter-shortcodes' ),
						'default' => '0',
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
					'[button url="http://example.com"]A simple button[/button]',
					'[button url="http://example.com" scheme="green" border_color="black" border_width="1px" icon="fa-shopping-cart" spin]A messed up button[/button]',
				),
			)
		);
	}
	function _ci_shortcodes_buttons_back_compat( $atts ) {
		// Re-map old predefined colors to the new color schemes of the same name.
		if ( ! empty( $atts['color'] ) && empty( $atts['scheme'] ) ) {
			if ( in_array( $atts['color'], array( 'grey', 'blue', 'white', 'yellow', 'green', 'red', 'black', 'purple' ) ) ) {
				$atts['scheme'] = $atts['color'];
			} else {
				unset( $atts['color'] );
			}
		}

		// Re-map old predefined button types to icons.
		if ( ! empty( $atts['type'] ) && empty( $atts['icon'] ) ) {
			$btn_icn = array(
				'download' => 'fa-download',
				'check'    => 'fa-check',
				'envelope' => 'fa-envelope',
				'cancel'   => 'fa-ban',
				'cart'     => 'fa-shopping-cart',
			);

			if ( isset( $btn_icn[ $atts['type'] ] ) ) {
				$atts['icon'] = $btn_icn[ $atts['type'] ];
			} else {
				unset( $atts['type'] );
			}
		}

		return $atts;
	}
	function ci_shortcodes_buttons( $atts, $content = null, $tag ) {
		$atts = _ci_shortcodes_buttons_back_compat( $atts );

		$has_spin = array_search( 'spin', (array) $atts, true );
		if ( $has_spin !== false && is_numeric( $has_spin ) ) {
			$atts['spin_icon'] = true;
		}

		$atts = shortcode_atts( array(
			'url'          => '#',
			'target'       => '',
			'size'         => '',
			'bg_color'     => '',
			'fg_color'     => '',
			'border_color' => 'transparent',
			'border_width' => '0',
			'corners'      => '',
			'icon'         => '',
			'spin_icon'    => '',
			'scheme'       => '',
		), $atts, $tag );
		extract( $atts );

		$attr_classes    = array( 'cisc-button' );
		$attr_style      = array();
		$span_attr_style = array();

		if ( empty( $size ) || ! in_array( $size, array( 'small', 'medium', 'large' ) ) ) {
			$size = '';
		} else {
			if ( $size != 'medium' ) {
				$attr_classes[] = 'cisc-button-' . $size;
			}
		}

		if ( ! in_array( $target, array( '_blank', '_self', '_parent', '_top' ) ) ) {
			$target = '';
		}

		$bg_color = _ci_shortcodes_sanitize_hex_color( $bg_color );
		if ( ! empty( $bg_color ) ) {
			$attr_style[] = 'background-color: ' . $bg_color . ';';
		}
		$fg_color = _ci_shortcodes_sanitize_hex_color( $fg_color );
		if ( ! empty( $fg_color ) ) {
			$span_attr_style[] = 'color: ' . $fg_color . ';';
		}
		$border_color = _ci_shortcodes_sanitize_hex_color( $border_color );
		if ( ! empty( $border_color ) ) {
			$attr_style[] = 'border-color: ' . $border_color . ';';
			$attr_style[] = 'border-style: solid;';
		}
		$border_width = _ci_shortcodes_sanitize_border_width( $border_width );
		if ( ! empty( $border_width ) ) {
			$attr_style[] = 'border-width: ' . $border_width . ';';
			$attr_style[] = 'border-style: solid;';
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

		$markup = apply_filters( 'ci_shortcodes_button_markup', '<a href="%1$s" class="%2$s" style="%3$s" %4$s><span style="%5$s">%6$s%7$s</span></a>' );
		$output = sprintf( $markup,
			esc_url( $atts['url'] ),
			esc_attr( implode( ' ', array_unique( $attr_classes ) ) ),
			esc_attr( implode( ' ', array_unique( $attr_style ) ) ),
			empty( $target ) ? '' : sprintf( 'target="%s"', $target ),
			esc_attr( implode( ' ', array_unique( $span_attr_style ) ) ),
			empty( $icon ) ? '' : sprintf( '<i class="fa %s%s"></i>',
				esc_attr( $icon ),
				$spin_icon === false ? '' : esc_attr( ' fa-spin' )
			),
			$content
		);

		return $output;
	}
