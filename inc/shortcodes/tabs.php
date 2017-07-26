<?php
	//
	// Tabs
	//
	function ci_shortcodes_get_documentation_tabs() {
		return array(
			'tabs' => array(
				'title'       => esc_html__( 'Tabs', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Tab group.', 'cssigniter-shortcodes' ),
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
						'title'   => esc_html__( 'Border Color', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid CSS color.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'border_width'    => array(
						'title'   => esc_html__( 'Border Width', 'cssigniter-shortcodes' ),
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
					'[tabs scheme="orange" active_bg_color="fuchsia"][tab title="Tab 1"]Content 1[/tab][tab title="Tab 2"]Content 2[/tab][/tabs]',
				),
			),
			'tab'  => array(
				'title'       => esc_html__( 'Tab', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Individual tab.', 'cssigniter-shortcodes' ),
				'aliases'     => array(),
				'attributes'  => array(
					'title' => array(
						'title'   => esc_html__( 'Tab title', 'cssigniter-shortcodes' ),
						'values'  => '',
						'default' => '',
						'info'    => '',
					),
				),
				'examples'    => array(
					'[tabs scheme="orange" active_bg_color="fuchsia"][tab title="Tab 1"]Content 1[/tab][tab title="Tab 2"]Content 2[/tab][/tabs]',
				),
			)
		);
	}

	$_ci_shortcodes_tabs = array();
	function ci_shortcodes_tabs( $atts, $content = null, $tag ) {
		global $_ci_shortcodes_tabs;
		$_ci_shortcodes_tabs = array();

		static $tabs_id = 0;
		$tabs_id ++;

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

		$attr_classes = array( 'cisc-tabs' );
		$attr_style   = array();

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
		<!-- CI Shortcodes Tabs Styles -->
		<style type="text/css">
			<?php
				if ( ! empty( $border_color ) || ! empty( $border_width ) ) {
					echo '#tabs' . $tabs_id . ' .cisc-tabs-wrap { ';
					echo empty( $border_color ) ? '' : 'border-color: ' . $border_color . '; ';
					echo empty( $border_width ) ? '' : 'border-width: ' . $border_width . '; ';
					echo '}' . PHP_EOL;
				}
				if ( ! empty( $bg_color ) || ! empty( $fg_color ) ) {
					echo '#tabs' . $tabs_id . ' .cisc-tab-content { ';
					echo empty( $fg_color ) ? '' : 'color: ' . $fg_color . '; ';
					echo empty( $bg_color ) ? '' : 'background-color: ' . $bg_color . '; ';
					echo '}' . PHP_EOL;
				}
				if ( ! empty( $tab_bg_color ) || ! empty( $tab_fg_color ) || ! empty( $corners ) ) {
					echo '#tabs' . $tabs_id . ' .cisc-tabs-nav a { ';
					echo empty( $tab_fg_color ) ? '' : 'color: ' . $tab_fg_color . '; ';
					echo empty( $tab_bg_color ) ? '' : 'background-color: ' . $tab_bg_color . '; ';
					if ( ! empty( $corners ) ) {
						// If there is only one value, we apply it only to the top left and right corners of the tabs.
						// This is to ease usage of the shortcode, although it's a bit counter-intuitive.
						// Users who may want all corners to be rounded, must use four values, e.g.: 10px 10px 10px 10px
						$corners_array = explode( ' ', $corners );
						if ( count( $corners_array ) > 1 ) {
							echo 'border-radius: ' . $corners . '; ';
						} else {
							echo 'border-radius: ' . $corners . ' ' . $corners . ' 0 0; ';
						}
					}
					echo '}' . PHP_EOL;
				}

				if ( ! empty( $active_bg_color ) || ! empty( $active_fg_color ) ) {
					echo '#tabs' . $tabs_id . ' .cisc-tabs-nav a.cisc-active, ';
					echo '#tabs' . $tabs_id . ' .cisc-tabs-nav a:hover { ';
					echo empty( $active_fg_color ) ? '' : 'color: ' . $active_fg_color . '; ';
					echo empty( $active_bg_color ) ? '' : 'background-color: ' . $active_bg_color . '; ';
					echo '}' . PHP_EOL;
				}
			?>
		</style><?php
		$css = ob_get_clean();

		do_shortcode( $content );

		$tab_nav = '';
		$i       = 0;
		foreach ( $_ci_shortcodes_tabs as $key => $tab ) {
			$markup = apply_filters( 'ci_shortcodes_tab_navigation_markup', '<li><a href="#tab%1$s-%2$s" %3$s>%4$s</a></li>' );
			$tab_nav .= sprintf( $markup,
				$tabs_id,
				$key,
				$i == 0 ? 'class="cisc-active"' : '',
				$tab['title']
			);
			$i ++;
		}
		$tab_content = '';
		foreach ( $_ci_shortcodes_tabs as $key => $tab ) {
			$markup = apply_filters( 'ci_shortcodes_tab_markup', '<div id="tab%1$s-%2$s" class="cisc-tab-content">%3$s</div>' );
			$tab_content .= sprintf( $markup,
				$tabs_id,
				$key,
				$tab['content']
			);
		}
		$markup = apply_filters( 'ci_shortcodes_tab_group_markup', '<div id="%1$s" class="%2$s" style="%3$s"><ul class="cisc-tabs-nav">%4$s</ul><div class="cisc-tabs-wrap">%5$s</div></div>' );
		$output = sprintf( $markup,
			'tabs' . $tabs_id,
			esc_attr( implode( ' ', $attr_classes ) ),
			esc_attr( implode( ' ', $attr_style ) ),
			$tab_nav,
			$tab_content
		);

		return $css . $output;
	}
	function ci_shortcodes_tab( $atts, $content = null, $tag ) {
		global $_ci_shortcodes_tabs;

		$atts = shortcode_atts( array(
			'title' => ''
		), $atts, $tag );
		extract( $atts );

		$_ci_shortcodes_tabs[] = array(
			'title'   => sanitize_text_field( $title ),
			'content' => do_shortcode( $content )
		);
	}
