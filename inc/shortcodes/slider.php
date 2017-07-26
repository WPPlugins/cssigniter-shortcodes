<?php
	//
	// Slider
	//
	function ci_shortcodes_get_documentation_slider() {
		return array(
			'slider' => array(
				'title'       => esc_html__( 'Slider', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Custom content slider.', 'cssigniter-shortcodes' ),
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
				),
				'examples'    => array(
					'[slider scheme="blue" corners="10px"][slide]Content 1[/slide][slide]Content 2[/slide][/slider]',
				),
			),
			'slide' => array(
				'title'       => esc_html__( 'Slide', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Individual slider slide.', 'cssigniter-shortcodes' ),
				'aliases'     => array(),
				'attributes'  => array(),
				'examples'    => array(
					'[slider scheme="blue" corners="10px"][slide]Content 1[/slide][slide]Content 2[/slide][/slider]',
				),
			)
		);
	}
	function ci_shortcodes_slider( $atts, $content = null, $tag ) {

		$atts = shortcode_atts( array(
			'bg_color'     => '',
			'fg_color'     => '',
			'border_color' => '',
			'border_width' => '',
			'corners'      => '',
			'scheme'       => '',
		), $atts, $tag );
		extract( $atts );

		static $slider_id = 0;
		$slider_id ++;

		$attr_classes = array( 'cisc-slider', 'cisc-flexslider' );
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

		$markup = apply_filters( 'ci_shortcodes_slider_markup', '<div id="cisc-slider-%1$s" class="%2$s" style="%3$s"><ul class="cisc-slides">%4$s</ul></div>' );
		$output = sprintf( $markup,
			$slider_id,
			esc_attr( implode( ' ', $attr_classes ) ),
			esc_attr( implode( ' ', $attr_style ) ),
			do_shortcode( $content )
		);

		ob_start();
		?>
		<!-- CI Shortcodes Slider Styles -->
		<style type="text/css"><?php
			if ( ! empty( $border_color ) ) {
				echo '#cisc-slider-' . $slider_id . ' .cisc-flex-control-paging li a.cisc-flex-active { background-color: ' . $border_color . '; }' . PHP_EOL;
			}
		?></style><?php
		$css = ob_get_clean();


		return $css . $output;
	}

	function ci_shortcodes_slide( $atts, $content = null, $tag ) {
		//$atts = shortcode_atts( array(
		//	'attribute' => 'value',
		//), $atts, $tag );

		$markup = apply_filters( 'ci_shortcodes_slide_markup', '<li><div class="cisc-slide-content">%s</div></li>' );
		$output = sprintf( $markup,
			do_shortcode( $content )
		);

		return $output;
	}