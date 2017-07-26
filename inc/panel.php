<?php
// Build the admin panel

if ( is_admin() ) {
	add_action( 'admin_menu', 'ci_shortcodes_menu' );
	add_action( 'admin_init', 'ci_shortcodes_register_settings' );
}

function ci_shortcodes_register_settings() {
	register_setting( 'ci_shortcodes_plugin_settings', CI_SHORTCODES_PLUGIN_OPTIONS, 'ci_shortcodes_settings_validate' );
}

function ci_shortcodes_menu() {
	add_options_page( esc_html__( 'CSSIgniter Shortcodes Options', 'cssigniter-shortcodes' ), esc_html__( 'CSSIgniter Shortcodes', 'cssigniter-shortcodes' ), 'manage_options', CI_SHORTCODES_PLUGIN_OPTIONS, 'ci_shortcodes_plugin_options' );
}

function ci_shortcodes_get_default_settings() {
	return apply_filters( 'ci_shortcodes_default_settings', array(
		'headings_default_level' => 2,
		'compatibility'          => 'no',
		'google_maps_api_enable' => '',
		'google_maps_api_key'    => '',
	) );
}

function ci_shortcodes_settings_validate( $settings ) {
	$settings = (array) $settings;

	if ( ! empty( $settings['headings_default_level'] ) ) {
		$val = intval( $settings['headings_default_level'] );
		if ( $val < 1 || $val > 6 ) {
			$settings['headings_default_level'] = 2;
		} else {
			$settings['headings_default_level'] = $val;
		}
	} else {
		$settings['headings_default_level'] = 2;
	}

	if ( ! empty( $settings['compatibility'] ) ) {
		$val = $settings['compatibility'];
		if ( $val != 'enabled' ) {
			$settings['compatibility'] = 'no';
		}
	} else {
		$settings['compatibility'] = 'no';
	}


	if ( ! empty( $settings['google_maps_api_enable'] ) && $settings['google_maps_api_enable'] != 'enabled' ) {
		$settings['google_maps_api_enable'] = '';
	}

	if ( ! empty( $settings['google_maps_api_key'] ) ) {
		$settings['google_maps_api_key'] = sanitize_text_field( $settings['google_maps_api_key'] );
	} else {
		if ( function_exists( 'ci_setting' ) && ci_setting( 'google_maps_api_key' ) != '' ) {
			$settings['google_maps_api_key'] = ci_setting( 'google_maps_api_key' );
		} else {
			$settings['google_maps_api_key'] = '';
		}
	}
	$settings['google_maps_api_key'] = sanitize_text_field( $settings['google_maps_api_key'] );

	return $settings;

}

function ci_shortcodes_plugin_options() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'cssigniter-shortcodes' ) );
	}

	$cishort_options = ci_shortcodes_settings_validate( get_option( CI_SHORTCODES_PLUGIN_OPTIONS ) );

	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'CSSIgniter Shortcodes - Settings', 'cssigniter-shortcodes' ); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'ci_shortcodes_plugin_settings' ); ?>
	
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Headings', 'cssigniter-shortcodes' ); ?></th>
					<td>
						<fieldset>
							<select name="<?php echo esc_attr( CI_SHORTCODES_PLUGIN_OPTIONS ); ?>[headings_default_level]">
								<?php for ( $i = 1; $i <= 6; $i ++ ): ?>
									<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $i, $cishort_options['headings_default_level'] ); ?> ><?php echo esc_html( 'H' . $i ); ?></option>
								<?php endfor; ?>
							</select>
						</fieldset>
						<p class="description"><?php echo wp_kses( __( 'Select the default heading level if one is not provided explicitly in the shortcode, e.g. <strong>[h]A title[/h]</strong>', 'cssigniter-shortcodes' ), array( 'strong' => array() ) ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Maps API', 'cssigniter-shortcodes' ); ?></th>
					<td>
						<fieldset>
							<ul>
								<li>
									<label for="google_maps_api_enable">
										<input type="checkbox" value="enabled" id="google_maps_api_enable" name="<?php echo esc_attr( CI_SHORTCODES_PLUGIN_OPTIONS ); ?>[google_maps_api_enable]" <?php checked( 'enabled', $cishort_options['google_maps_api_enable'] ); ?> />
										<?php esc_html_e( 'Load Google Maps API.', 'cssigniter-shortcodes' ); ?>
									</label>

									<p class="description"><?php echo wp_kses( __( 'The Google Maps API must be loaded only once on your website. Since many themes and/or plugins might load the API themselves, you may have to turn this option on or off for your maps to work properly.', 'cssigniter-shortcodes' ), array( 'strong' => array() ) ); ?></p>
								</li>
								<li>
									<label for="google_maps_api_key"><?php esc_html_e( 'API Key:', 'cssigniter-shortcodes' ); ?>
										<input id="google_maps_api_key" name="<?php echo esc_attr( CI_SHORTCODES_PLUGIN_OPTIONS ); ?>[google_maps_api_key]" value="<?php echo esc_attr( $cishort_options['google_maps_api_key'] ); ?>" type="text">
									</label>

									<p class="description"><?php echo wp_kses( sprintf( __( 'Paste here your Google Maps API Key. Maps will <strong>not</strong> be displayed without an API key. You need to issue a key from <a href="%1$s" target="_blank">Google Accounts</a>, and make sure the <strong>Google Maps JavaScript API</strong> is enabled. For instructions on issuing an API key, <a href="%2$s" target="_blank">read this article</a>.', 'cssigniter-shortcodes' ),
											'https://code.google.com/apis/console/',
											'http://www.cssigniter.com/docs/article/generate-a-google-maps-api-key/'
									), array( 'a' => array( 'href' => true, 'target' => true ) ) ); ?></p>
								</li>
							</ul>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Compatibility Mode', 'cssigniter-shortcodes' ); ?></th>
					<td>
						<fieldset>
							<label for="compatibility">
								<input type="checkbox" value="enabled" id="compatibility" name="<?php echo esc_attr( CI_SHORTCODES_PLUGIN_OPTIONS ); ?>[compatibility]" <?php checked( 'enabled', $cishort_options['compatibility'] ); ?> />
								<?php esc_html_e( 'Enable Compatibility Mode.', 'cssigniter-shortcodes' ); ?>
							</label>
						</fieldset>
						<p class="description"><?php echo wp_kses( __( 'When this box is checked, all shortcodes registered by this plugin, will need a "ci-" prefix. For example, <strong>[button]</strong> becomes <strong>[ci-button]</strong>, etc.', 'cssigniter-shortcodes' ), array( 'strong' => array() ) ); ?></p>
					</td>					
				</tr>
			</table>
	
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes', 'cssigniter-shortcodes' ) ?>"/>
			</p>
		</form>

		<h2><?php esc_html_e( 'Cheatsheet', 'cssigniter-shortcodes' ); ?></h2>
		<table class="cisc-usage-table">
			<thead>
				<tr>
					<td><?php echo esc_html_x( 'Shortcode', 'cheatsheet column title', 'cssigniter-shortcodes' ); ?></td>
					<td><?php echo esc_html_x( 'Attributes', 'cheatsheet column title', 'cssigniter-shortcodes' ); ?></td>
					<td><?php echo esc_html_x( 'Examples', 'cheatsheet column title', 'cssigniter-shortcodes' ); ?></td>
				</tr>
			</thead>
			<tbody>
				<?php
					$docs_array = array_merge(
						ci_shortcodes_get_documentation_accordion(),
						ci_shortcodes_get_documentation_box(),
						ci_shortcodes_get_documentation_button(),
						ci_shortcodes_get_documentation_grid(),
						ci_shortcodes_get_documentation_heading(),
						ci_shortcodes_get_documentation_list(),
						ci_shortcodes_get_documentation_map(),
						ci_shortcodes_get_documentation_quote(),
						ci_shortcodes_get_documentation_separator(),
						ci_shortcodes_get_documentation_slider(),
						ci_shortcodes_get_documentation_tabs(),
						ci_shortcodes_get_documentation_tooltip()
					);
				?>
				<?php foreach ( $docs_array as $shortcode => $doc ): ?>
					<tr valign="top">
						<th scope="row">
							<h3><?php echo $doc['title']; ?></h3>
							<p class="description"><?php echo $doc['description']; ?></p>
							<p class="shortcode"><span><?php esc_html_e( 'Shortcode:', 'cssigniter-shortcodes' ); ?></span> <?php echo $shortcode; ?></p>
							<?php if ( ! empty( $doc['aliases'] ) ): ?>
								<p class="aliases"><span><?php esc_html_e( 'Aliases:', 'cssigniter-shortcodes' ); ?></span> <?php echo implode( ', ', $doc['aliases'] ); ?></p>
							<?php endif; ?>
						</th>
						<td>
							<ul class="attributes">
								<?php foreach ( $doc['attributes'] as $att_name => $att ): ?>
									<li>
										<code><?php echo $att_name; ?></code> - <strong><?php echo $att['title']; ?></strong>
										<ul>
											<?php if ( ! empty( $att['info'] ) ): ?>
												<li class="info"><?php echo $att['info']; ?></li>
											<?php endif; ?>
											<?php if ( ! empty( $att['values'] ) ): ?>
												<li><span><?php esc_html_e( 'Valid values:', 'cssigniter-shortcodes' ); ?></span> <?php echo $att['values']; ?></li>
											<?php endif; ?>
											<?php if ( ! empty( $att['default'] ) ): ?>
												<li><span><?php esc_html_e( 'Default:', 'cssigniter-shortcodes' ); ?></span> <?php echo $att['default']; ?></li>
											<?php endif; ?>
										</ul>
									</li>
								<?php endforeach; ?>
							</ul>
						</td>
						<td>
							<?php foreach( $doc['examples'] as $example ): ?>
								<pre class="cisc-code"><?php echo $example; ?></pre>							
							<?php endforeach; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}
