<?php
	//
	// Columns
	//
	function ci_shortcodes_get_documentation_grid() {
		return array(
			'row'    => array(
				'title'       => esc_html__( 'Grid - Rows', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Row that contains columns.', 'cssigniter-shortcodes' ),
				'aliases'     => array( '_row', '__row' ),
				'attributes'  => array(),
				'examples'    => array(
					'[row][column desktop="6"]First column[/column][column desktop="6"]Second column[/column][/row]',
				),
			),
			'column' => array(
				'title'       => esc_html__( 'Grid - Columns', 'cssigniter-shortcodes' ),
				'description' => esc_html__( 'Column, contained in a row.', 'cssigniter-shortcodes' ),
				'aliases'     => array( '_column', '__column', 'col', '_col', '__col' ),
				'attributes'  => array(
					'id'      => array(
						'title'   => esc_html__( 'ID', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid HTML ID.', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'class'   => array(
						'title'   => esc_html__( 'Class', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( 'Any valid HTML class(es).', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'desktop' => array(
						'title'   => esc_html__( 'Desktop breakpoint', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( '1 to 12', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'tablet'  => array(
						'title'   => esc_html__( 'Tablet breakpoint', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( '1 to 12', 'cssigniter-shortcodes' ),
						'default' => '',
						'info'    => '',
					),
					'mobile'  => array(
						'title'   => esc_html__( 'Mobile breakpoint', 'cssigniter-shortcodes' ),
						'values'  => esc_html__( '1 to 12', 'cssigniter-shortcodes' ),
						'default' => '12',
						'info'    => '',
					),
				),
				'examples'    => array(
					'[row][col desktop="6"]Column A[/col][col desktop="6"]Column B[/col][/row]',
					'[row][col desktop="6"][_row][_col desktop="4"]Col A1[/_col][_col desktop="8"]Col A2[/_col][/_row][/col][col desktop="8"][_row][_col desktop="4"]Col B1[/_col][_col desktop="6"]Col B2[/_col][/_row][/col][/row]',
				),
			),
		);
	}

	function ci_shortcodes_row( $atts, $content = null, $tag ) {
		$markup = apply_filters( 'ci_shortcodes_row_markup', '<div class="cisc-row">%s</div>' );
		$output = sprintf( $markup,
			do_shortcode( $content )
		);

		return $output;
	}

	function _ci_shortcodes_columns_back_compat( $atts, $tag ) {
		switch ( $tag ) {
			case 'one_half':
			case 'one_half_last':
				$atts['desktop'] = '6';
				$atts['tablet']  = '6';
				$atts['mobile']  = '12';
				break;
			case 'one_third':
			case 'one_third_last':
				$atts['desktop'] = '4';
				$atts['tablet']  = '4';
				$atts['mobile']  = '12';
				break;
			case 'two_thirds':
			case 'two_thirds_last':
				$atts['desktop'] = '8';
				$atts['tablet']  = '8';
				$atts['mobile']  = '12';
				break;
		}

		return $atts;
	}

	function ci_shortcodes_columns( $atts, $content = null, $tag ) {
		$atts = _ci_shortcodes_columns_back_compat( $atts, $tag );

		$atts = shortcode_atts( array(
			'id'      => '',
			'class'   => '',
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '12',
		), $atts );
		extract( $atts );

		$id = sanitize_html_class( $id );

		$attr_classes = array();

		$class = array_map( 'sanitize_html_class', explode( ' ', trim( $class ) ) );
		if ( ! empty( $class ) ) {
			$attr_classes = array_merge( $attr_classes, $class );
		}

		$desktop = intval( $desktop );
		if ( $desktop >= 1 && $desktop <= 12 ) {
			$attr_classes[] = 'cisc-col-md-' . $desktop;
		}
		$tablet = intval( $tablet );
		if ( $tablet >= 1 && $tablet <= 12 ) {
			$attr_classes[] = 'cisc-col-sm-' . $tablet;
		}
		$mobile = intval( $mobile );
		if ( $mobile >= 1 && $mobile <= 12 ) {
			$attr_classes[] = 'cisc-col-xs-' . $mobile;
		}

		$markup = apply_filters( 'ci_shortcodes_column_markup', '<div %1$s class="%2$s">%3$s</div>' );
		$output = sprintf( $markup,
			empty( $id ) ? '' : sprintf( 'id="%s"', esc_attr( $id ) ),
			esc_attr( implode( ' ', array_unique( $attr_classes ) ) ),
			do_shortcode( $content )
		);

		return $output;
	}
