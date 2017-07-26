<?php
	//
	// Accordions
	//
	function ci_shortcodes_get_documentation_accordion() {
		return array(
			'accordion' => array(
				'title'       => esc_html__( 'Accordion', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Accordion-style tabs.', 'cssigniter-shortcodes' ),
				'aliases'     => array(),
				'attributes'  => array(
					'bg_color'        => array(
						'title'   => esc_html__( 'Background Color', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'fg_color'        => array(
						'title'   => esc_html__( 'Foreground Color', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'tab_bg_color'    => array(
						'title'   => esc_html__( "Tabs' Background Color", 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'tab_fg_color'    => array(
						'title'   => esc_html__( "Tabs' Foreground Color", 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'active_bg_color' => array(
						'title'   => esc_html__( "Active Tab's Background Color", 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'active_fg_color' => array(
						'title'   => esc_html__( "Active Tab's Foreground Color", 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'border_color'    => array(
						'title'   => esc_html__( "Accordion's Border Color", 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'border_width'    => array(
						'title'   => esc_html__( "Accordion's Border Width", 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS border width declaration.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'corners'         => array(
						'title'   => esc_html__( 'Corners / Border Radius', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS border radius declaration.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'scheme'          => array(
						'title'   => esc_html__( 'Color Scheme', 'cssigniter-shortcodes' ),
						'values'  => implode( ', ', ci_shortcodes_get_default_color_schemes() ),
						'default' => '',
						'info'    => '',
					),
				),
				'examples'    => array(
					'[accordion scheme="blue" corners="10px"][accordion_tab title="Tab 1"]Content 1[/accordion_tab]' . PHP_EOL . '[accordion_tab title="Tab 2" default]Content 2[/accordion_tab][/accordion]',
				),
			),
			'accordion_tab' => array(
				'title'       => esc_html__( 'Accordion Tab', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Individual accordion tabs.', 'cssigniter-shortcodes' ),
				'aliases'     => array(),
				'attributes'  => array(
					'title'   => array(
						'title'   => esc_html__( 'Tab Title', 'cssigniter-shortcodes' ),
						'values'  => '',
						'default' => '',
						'info'    => '',
					),
					'default' => array(
						'title'   => esc_html__( 'Default Tab', 'cssigniter-shortcodes' ),
						'values'  => wp_kses( __( 'Simply type <em>default</em> without a value.', 'cssigniter-shortcodes' ), array( 'em' => array() ) ),
						'default' => '',
						'info'    => esc_html__( 'At maximum, only one tab per accordion must make use of this attribute.', 'cssigniter-shortcodes' ),
					),
				),
				'examples'    => array(
					'[accordion scheme="blue" corners="10px"][accordion_tab title="Tab 1"]Content 1[/accordion_tab][accordion_tab title="Tab 2" default]Content 2[/accordion_tab][/accordion]',
				),
			)
		);
	}
	function ci_shortcodes_accordion( $atts, $content = null, $tag ) {
		static $tabs_id = 0;
		$tabs_id++;

		$atts = shortcode_atts( array(
			'bg_color'        => '',
			'fg_color'        => '',
			'tab_bg_color'    => '',
			'tab_fg_color'    => '',
			'active_bg_color' => '',
			'active_fg_color' => '',
			'border_color'    => '',
			'border_width'    => '',
			'corners'         => '',
			'scheme'          => '',
		), $atts, $tag );
		extract( $atts );

		$attr_classes = array( 'cisc-accordion' );

		$bg_color        = _ci_shortcodes_sanitize_hex_color( $bg_color );
		$fg_color        = _ci_shortcodes_sanitize_hex_color( $fg_color );
		$tab_bg_color    = _ci_shortcodes_sanitize_hex_color( $tab_bg_color );
		$tab_fg_color    = _ci_shortcodes_sanitize_hex_color( $tab_fg_color );
		$active_bg_color = _ci_shortcodes_sanitize_hex_color( $active_bg_color );
		$active_fg_color = _ci_shortcodes_sanitize_hex_color( $active_fg_color );
		$border_color    = _ci_shortcodes_sanitize_hex_color( $border_color );
		$border_width    = _ci_shortcodes_sanitize_border_width( $border_width );
		$corners         = _ci_shortcodes_sanitize_border_radius( $corners );

		$scheme = sanitize_html_class( $scheme );
		if ( ! empty( $scheme ) ) {
			$attr_classes[] = 'cisc-shortcode-scheme-' . $scheme;
		}


		ob_start();
		?>
		<!-- CI Shortcodes Accordion Styles -->
		<style type="text/css">
			<?php
				if ( ! empty( $border_color ) || ! empty( $border_width ) || ! empty( $bg_color ) || ! empty( $fg_color ) ) {
					echo '#cisc-accordion-' . $tabs_id . ' .cisc-accordion-panel-content { ';
					echo empty( $fg_color ) ? '' : 'color: ' . $fg_color . '; ';
					echo empty( $bg_color ) ? '' : 'background-color: ' . $bg_color . '; ';
					echo empty( $border_color ) ? '' : 'border-color: ' . $border_color . '; ';
					echo empty( $border_width ) ? '' : 'border-width: ' . $border_width . '; ';
					echo '}' . PHP_EOL;
				}
				if ( ! empty( $tab_bg_color ) || ! empty( $tab_fg_color ) || ! empty( $corners ) ) {
					echo '#cisc-accordion-' . $tabs_id . ' .cisc-accordion-panel-title a { ';
					echo empty( $tab_fg_color ) ? '' : 'color: ' . $tab_fg_color . '; ';
					echo empty( $tab_bg_color ) ? '' : 'background-color: ' . $tab_bg_color . '; ';
					if ( ! empty( $corners ) ) {
						echo 'border-radius: ' . $corners . '; ';
					}
					echo '}' . PHP_EOL;
				}

				if ( ! empty( $active_bg_color ) || ! empty( $active_fg_color ) ) {
					echo '#cisc-accordion-' . $tabs_id . ' .cisc-accordion-panel-title a.cisc-active, ';
					echo '#cisc-accordion-' . $tabs_id . ' .cisc-accordion-panel-title a:hover { ';
					echo empty( $active_fg_color ) ? '' : 'color: ' . $active_fg_color . '; ';
					echo empty( $active_bg_color ) ? '' : 'background-color: ' . $active_bg_color . '; ';
					echo '}' . PHP_EOL;
				}
			?>
		</style><?php
		$css = ob_get_clean();

		$markup = apply_filters( 'ci_shortcodes_accordion_markup', '<div id="%1$s" class="%2$s">%3$s</div>' );
		$output = sprintf( $markup,
			esc_attr( 'cisc-accordion-' . $tabs_id ),
			esc_attr( implode( ' ', $attr_classes ) ),
			do_shortcode( $content )
		);

		return $css . $output;
	}

	function ci_shortcodes_accordion_tab( $atts, $content = null, $tag ) {
		$is_default = array_search( 'default', (array) $atts, true );
		if ( $is_default !== false && is_numeric( $is_default ) ) {
			$atts['default'] = 'on';
		}

		$atts = shortcode_atts( array(
			'title'   => '',
			'default' => '',
		), $atts, $tag );
		extract( $atts );

		if ( $default == 'on' ) {
			$default = true;
		}

		$markup = apply_filters( 'ci_shortcodes_accordion_tab_markup', '<div class="cisc-accordion-panel %1$s"><div class="cisc-accordion-panel-title"><a href="#">%2$s</a></div><div class="cisc-accordion-panel-wrap"><div class="cisc-accordion-panel-content">%3$s</div></div></div>' );
		$output = sprintf( $markup,
			! $default ? '' : 'cisc-open',
			sanitize_text_field( $title ),
			do_shortcode( $content )
		);

		return $output;
	}
