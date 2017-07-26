<?php
	//
	// Lists
	//
	function ci_shortcodes_get_documentation_list() {
		return array(
			'list' => array(
				'title'       => esc_html__( 'List', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Styled unordered lists.', 'cssigniter-shortcodes' ),
				'aliases'     => array(),
				'attributes'  => array(
					'scheme' => array(
						'title'   => esc_html__( 'Color Scheme', 'cssigniter-shortcodes' ),
						'values'  => implode( ', ', ci_shortcodes_get_default_color_schemes() ),
						'default' => '',
						'info'    => '',
					),
					'icon'   => array(
						'title'   => esc_html__( 'Icon', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid font-awesome icon code.', 'cssigniter-shortcodes' ),
						'default' => 'fa-circle',
						'info'    => wp_kses( __( 'Icon codes should be prefixed with <strong>fa-</strong>, e.g. <strong>fa-gear</strong>', 'cssigniter-shortcodes' ), array( 'strong' => array() ) ),
					),
					'color'  => array(
						'title'   => esc_html__( 'Icon Color', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
				),
				'examples'    => array(
					esc_html( '[list]<ul><li>Item 1</li><li>Item 2</li></ul>[/list]' ),
					esc_html( '[list scheme="brown" icon="fa-check" color="rgba(0, 100, 255, 0.5)"]<ul><li>Item 1</li><li>Item 2</li></ul>[/list]' ),
				),
			)
		);
	}
	function _ci_shortcodes_lists_back_compat( $atts ) {
		if ( ! empty( $atts['type'] ) ) {
			switch ( $atts['type'] ) {
				case 'check':
					$atts['icon'] = 'fa-check';
					break;
				case 'cross':
					$atts['icon'] = 'fa-close';
					break;
				default:
					$atts['icon'] = 'fa-circle';
			}
		}

		return $atts;
	}
	function ci_shortcodes_lists( $atts, $content=null, $tag ) {
		$atts = _ci_shortcodes_lists_back_compat( $atts );

		$atts = shortcode_atts( array(
			'icon'   => 'fa-circle',
			'color'  => '',
			'scheme' => '',
		), $atts, $tag );
		extract( $atts );

		static $list_id = 0;
		$list_id ++;

		$attr_classes = array( 'cisc-list' );

		$icon_code = false;
		if ( ! empty( $icon ) ) {
			$icon_code = _ci_shortcodes_get_fontawesome_icon_code( $icon );
		}

		$color = _ci_shortcodes_sanitize_hex_color( $color );

		$scheme = sanitize_html_class( $scheme );
		if ( ! empty( $scheme ) ) {
			$attr_classes[] = 'cisc-shortcode-scheme-' . $scheme;
		}

		$css = '';
		if ( ! empty( $icon_code ) || ! empty( $color ) ) {
			ob_start();
			?>
			<!-- CI Shortcodes List Styles -->
			<style type="text/css"><?php
				if ( ! empty( $icon_code ) ) {
					echo '#cisc-list-' . $list_id . ' li:before { content: "\\' . $icon_code . '"; }' . PHP_EOL;
				}
				if ( ! empty( $color ) ) {
					echo '#cisc-list-' . $list_id . ' li:before { color: ' . $color . '; }' . PHP_EOL;
				}
			?></style><?php
			$css = ob_get_clean();
		}

		$markup = apply_filters( 'ci_shortcodes_list_markup', '<div id="cisc-list-%1$s" class="%2$s">%3$s</div>' );
		$output = sprintf( $markup,
			esc_attr( $list_id ),
			esc_attr( implode( ' ', $attr_classes ) ),
			do_shortcode( $content )
		);

		return $css . $output;
	}
