<?php
function _ci_shortcodes_get_fontawesome_icon_code( $icon_name ) {
	static $file = true;

	// Read file only when $file is true. false means the file could not be read.
	if ( $file === true ) {
		$file = @file_get_contents( CI_SHORTCODES_ABS_DIR . 'src/css/font-awesome.css' );
	}
	if ( $file === false ) {
		return false;
	}

	preg_match( '#\.' . preg_quote( $icon_name, '#' ) . '\:before.*?\{.*?content\:.*?"\\\\(.*?)".*?\}#is', $file, $match );
	if ( ! empty( $match[1] ) ) {
		return $match[1];
	}

	return false;

}

function ci_shortcodes_format_content( $content ) {
	$content = wptexturize( $content );
	$content = convert_smilies( $content );
	$content = convert_chars( $content );
	$content = wpautop( $content );
	$content = shortcode_unautop( $content );
	$content = do_shortcode( $content );

	return $content;
}

function _ci_shortcodes_sanitize_0_255( $val ) {
	if ( intval( $val ) > 255 ) {
		$val = 255;
	} elseif ( intval( $val ) < 0 ) {
		$val = 0;
	}

	return intval( $val );
}

function _ci_shortcodes_sanitize_0_100_percent( $val ) {
	$val = str_replace( '%', '', $val );
	if ( floatval( $val ) > 100 ) {
		$val = 100;
	} elseif ( floatval( $val ) < 0 ) {
		$val = 0;
	}

	return floatval( $val ) . '%';
}

function _ci_shortcodes_sanitize_0_360_degrees( $val ) {
	if ( floatval( $val ) > 360 ) {
		$val = 360;
	} elseif ( floatval( $val ) < 0 ) {
		$val = 0;
	}

	return floatval( $val );
}

function _ci_shortcodes_sanitize_0_1_opacity( $val ) {
	if ( floatval( $val ) > 1 ) {
		$val = 1;
	} elseif ( floatval( $val ) < 0 ) {
		$val = 0;
	}

	return floatval( $val );
}

/**
 * Returns a sanitized hex color code.
 *
 * @param string $str The color string to be sanitized.
 * @param bool $return_hash Whether to return the color code prepended by a hash.
 * @param string $return_fail The value to return on failure.
 * @return string A valid hex color code on success, an empty string on failure.
 */
function _ci_shortcodes_sanitize_hex_color( $str, $return_hash = true, $return_fail = '' ) {
	// Allow keywords and predefined colors
	if ( in_array( $str, array( 'transparent', 'initial', 'inherit', 'black', 'silver', 'gray', 'grey', 'white', 'maroon', 'red', 'purple', 'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal', 'aqua', 'orange', 'aliceblue', 'antiquewhite', 'aquamarine', 'azure', 'beige', 'bisque', 'blanchedalmond', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgrey', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'greenyellow', 'grey', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow', 'limegreen', 'linen', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'oldlace', 'olivedrab', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'whitesmoke', 'yellowgreen', 'rebeccapurple' ) ) ) {
		return $str;
	}

	// Not a predefined color. Let's see if it's a color function.
	preg_match( '/rgb\(\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*\)/', $str, $rgb_matches );
	if( !empty( $rgb_matches ) && count($rgb_matches) == 4 ) {
		for( $i = 1; $i < 4; $i++ ) {
			if ( strpos( $rgb_matches[ $i ], '%' ) !== false ) {
				$rgb_matches[ $i ] = _ci_shortcodes_sanitize_0_100_percent( $rgb_matches[ $i ] );
			} else {
				$rgb_matches[ $i ] = _ci_shortcodes_sanitize_0_255( $rgb_matches[ $i ] );
			}
		}

		return sprintf( 'rgb(%s, %s, %s)', $rgb_matches[1], $rgb_matches[2], $rgb_matches[3] );
	}

	preg_match( '/rgba\(\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1}\.?\d*\%?)\s*\)/', $str, $rgba_matches );
	if( !empty( $rgba_matches ) && count($rgba_matches) == 5 ) {
		for( $i = 1; $i < 4; $i++ ) {
			if ( strpos( $rgba_matches[ $i ], '%' ) !== false ) {
				$rgba_matches[ $i ] = _ci_shortcodes_sanitize_0_100_percent( $rgba_matches[ $i ] );
			} else {
				$rgba_matches[ $i ] = _ci_shortcodes_sanitize_0_255( $rgba_matches[ $i ] );
			}
		}
		$rgba_matches[4] = _ci_shortcodes_sanitize_0_1_opacity( $rgba_matches[ $i ] );
		return sprintf( 'rgba(%s, %s, %s, %s)', $rgba_matches[1], $rgba_matches[2], $rgba_matches[3], $rgba_matches[4] );
	}

	preg_match( '/hsl\(\s*(\d{1,3}\.?\d*)\s*,\s*(\d{1,3}\.?\d*\%)\s*,\s*(\d{1,3}\.?\d*\%)\s*\)/', $str, $hsl_matches );
	if( !empty( $hsl_matches ) && count($hsl_matches) == 4 ) {
		$hsl_matches[1] = _ci_shortcodes_sanitize_0_360_degrees( $hsl_matches[1] );
		$hsl_matches[2] = _ci_shortcodes_sanitize_0_100_percent( $hsl_matches[2] );
		$hsl_matches[3] = _ci_shortcodes_sanitize_0_100_percent( $hsl_matches[3] );

		return sprintf( 'hsl(%s, %s, %s)', $hsl_matches[1], $hsl_matches[2], $hsl_matches[3] );
	}

	preg_match( '/hsla\(\s*(\d{1,3}\.?\d*)\s*,\s*(\d{1,3}\.?\d*\%)\s*,\s*(\d{1,3}\.?\d*\%)\s*,\s*(\d{1}\.?\d*\%?)\s*\)/', $str, $hsla_matches );
	if( !empty( $hsla_matches ) && count($hsla_matches) == 5 ) {
		$hsla_matches[1] = _ci_shortcodes_sanitize_0_360_degrees( $hsla_matches[1] );
		$hsla_matches[2] = _ci_shortcodes_sanitize_0_100_percent( $hsla_matches[2] );
		$hsla_matches[3] = _ci_shortcodes_sanitize_0_100_percent( $hsla_matches[3] );
		$hsla_matches[4] = _ci_shortcodes_sanitize_0_1_opacity( $hsla_matches[4] );

		return sprintf( 'hsla(%s, %s, %s, %s)', $hsla_matches[1], $hsla_matches[2], $hsla_matches[3], $hsla_matches[4] );
	}

	// Not a color function either. Let's see if it's a hex color.

	// Include the hash if not there.
	// The regex below depends on in.
	if ( substr( $str, 0, 1 ) != '#' ) {
		$str = '#' . $str;
	}

	preg_match( '/(#)([0-9a-fA-F]{6})/', $str, $matches );

	if ( count( $matches ) == 3 ) {
		if ( $return_hash ) {
			return $matches[1] . $matches[2];
		} else {
			return $matches[2];
		}
	}

	return $return_fail;
}

function _ci_shortcodes_sanitize_border_width( $width ) {
	$width          = trim( $width );
	$subparts       = explode( ' ', $width );
	$subparts_count = count( $subparts );
	for ( $i = 0; $i < $subparts_count; $i ++ ) {
		$subparts[ $i ] = _ci_shortcodes_sanitize_css_unit_value( $subparts[ $i ], array(
			'thin',
			'medium',
			'thick',
			'initial',
			'inherit'
		) );
	}
	$subparts = array_values( array_filter( $subparts, 'strlen' ) ); // removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
	$width    = implode( ' ', $subparts );

	return $width;
}

function _ci_shortcodes_sanitize_padding( $width ) {
	$width          = trim( $width );
	$subparts       = explode( ' ', $width );
	$subparts_count = count( $subparts );
	for ( $i = 0; $i < $subparts_count; $i ++ ) {
		$subparts[ $i ] = _ci_shortcodes_sanitize_css_unit_value( $subparts[ $i ] );
	}
	$subparts = array_values( array_filter( $subparts, 'strlen' ) ); // removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
	$width    = implode( ' ', $subparts );

	return $width;
}

function _ci_shortcodes_sanitize_border_radius( $width ) {
	$width       = trim( $width );
	$original    = $width;
	$width       = explode( '/', $width );
	$width       = array_map( 'trim', $width );
	$parts_count = count( $width );
	if ( $parts_count > 1 ) {
		for ( $p = 0; $p < $parts_count; $p ++ ) {
			$subparts       = explode( ' ', $width[ $p ] );
			$subparts_count = count( $subparts );
			for ( $i = 0; $i < $subparts_count; $i ++ ) {
				$subparts[ $i ] = _ci_shortcodes_sanitize_css_unit_value( $subparts[ $i ], array(
						'inherit',
						'initial'
					) );
			}
			$subparts    = array_values( array_filter( $subparts, 'strlen' ) ); // removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
			$width[ $p ] = implode( ' ', $subparts );
		}
		$width = array_values( array_filter( $width, 'strlen' ) ); // removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
		$width = implode( ' / ', $width );
	} else {
		$width          = $original;
		$subparts       = explode( ' ', $width );
		$subparts_count = count( $subparts );
		for ( $i = 0; $i < $subparts_count; $i ++ ) {
			$subparts[ $i ] = _ci_shortcodes_sanitize_css_unit_value( $subparts[ $i ], array(
				'inherit',
				'initial'
			) );
		}
		$subparts = array_values( array_filter( $subparts, 'strlen' ) ); // removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
		$width    = implode( ' ', $subparts );
	}

	return $width;
}


function _ci_shortcodes_sanitize_css_unit_value( $value, $additional_valid_values = array() ) {
	if ( in_array( $value, $additional_valid_values ) ) {
		return $value;
	}
	preg_match( '#^(\d+)(\.\d+)?(px|em|rem|ex|ch|vh|vw|vmin|vmax|mm|cm|in|pt|pc|%)?$#i', $value, $match );
	if ( isset( $match[1] ) && $match[1] !== '' && $match[1] !== false && intval( $match[1] ) == 0 && empty( $match[2] ) ) {
		return '0px';
	}
	if ( ! empty( $match[1] ) || ! empty( $match[2] ) ) {
		$new_value = $match[1] . $match[2];
		if ( ! empty( $match[3] ) ) {
			$new_value .= $match[3];
		}

		return $new_value;
	}

	return '';
}

