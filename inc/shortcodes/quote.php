<?php
	//
	// Special Quotes
	//
	function ci_shortcodes_get_documentation_quote() {
		return array(
			'quote' => array(
				'title'       => esc_html__( 'Quote', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Styled blockquote.', 'cssigniter-shortcodes' ),
				'aliases'     => array(),
				'attributes'  => array(
					'title'        => array(
						'title'   => esc_html__( 'Title', 'cssigniter-shortcodes' ),
						'values'  => '',
						'default' => '',
						'info'    => esc_html__( 'Citation of the quote.', 'cssigniter-shortcodes' ),
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
						'default' => 'fa-quote-left',
						'info'    => wp_kses( __( 'Icon codes should be prefixed with <strong>fa-</strong>, e.g. <strong>fa-gear</strong>', 'cssigniter-shortcodes' ), array( 'strong' => array() ) ),
					),
				),
				'examples'    => array(
					'[quote scheme="red" icon="fa-quote-right" title="Who said it"]What he/she said.[/quote]',
				),
			)
		);
	}
	function ci_shortcodes_quotes( $atts, $content = null, $tag ) {
		$atts = shortcode_atts( array(
			'title'        => '',
			'icon'         => 'fa-quote-left',
			'bg_color'     => '',
			'fg_color'     => '',
			'border_color' => 'transparent',
			'border_width' => '0',
			'corners'      => '',
			'scheme'       => '',
		), $atts, $tag );
		extract( $atts );

		$attr_classes = array( 'cisc-blockquote' );
		$attr_style   = array();

		$title = sanitize_text_field( $title );
		$icon  = sanitize_html_class( $icon );

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

		$markup = apply_filters( 'ci_shortcodes_quote_markup', '<div class="%1$s"><blockquote style="%2$s"><i class="fa %3$s"></i>%4$s<cite>%5$s</cite></blockquote></div>' );
		$output = sprintf( $markup,
			esc_attr( implode( ' ', array_unique( $attr_classes ) ) ),
			esc_attr( implode( ' ', array_unique( $attr_style ) ) ),
			esc_attr( $icon ),
			ci_shortcodes_format_content( $content ),
			$title
		);

		return $output;
	}
