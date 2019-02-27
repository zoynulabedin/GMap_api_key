add_filter( 'clean_url', 'keendevs_find_add_key', 99, 3 );

function keendevs_find_add_key( $url, $original_url, $_context ) {
	$key = get_option( 'keendevs_google_map_api_key' );

	if ( ! $key ) {
		return $url;
	}

	if ( strstr( $url, "maps.google.com/maps/api/js" ) !== false || strstr( $url, "maps.googleapis.com/maps/api/js" ) !== false ) {// it's a Google maps url

		if ( strstr( $url, "key=" ) === false ) {// it needs a key
			$url = add_query_arg( 'key', $key, $url );
			$url = str_replace( "&#038;", "&amp;", $url ); // or $url = $original_url
		}

	}

	return $url;
}

add_action( 'admin_menu', 'keendevs_add_admin_menu' );

function keendevs_add_admin_menu() {
	add_submenu_page( 'options-general.php', 'Google API KEY', 'Google API KEY', 'manage_options', 'gmaps-api-key', 'keendevs_add_admin_menu_html' );
}

function keendevs_add_admin_menu_html() {
	add_thickbox();
	$updated = false;
	if ( isset( $_POST['keendevs_google_map_api_key'] ) ) {
		$key     = esc_attr( $_POST['keendevs_google_map_api_key'] );
		$updated = update_option( 'keendevs_google_map_api_key', $key );
	}

	if ( $updated ) {
		echo '<div class="updated fade"><p><strong>' . __( 'Key Updated!', 'gmaps-api-key' ) . '</strong></p></div>';

	}
	?>
	<div class="wrap">


		<form method="post" action="options-general.php?page=gmaps-api-key">
			<label for="keendevs_google_map_api_key"><?php _e( 'Enter Google Maps API KEY', 'gmaps-api-key' ); ?></label>
			<input title="<?php _e( 'Add Google Maps API KEY', 'gmaps-api-key' ); ?>" type="text"
			       name="keendevs_google_map_api_key" id="keendevs_google_map_api_key"
			       placeholder="<?php _e( 'Enter your API KEY here', 'gmaps-api-key' ); ?>"
			       style="padding: 6px; width:50%; display: block;"
			       value="<?php echo esc_attr( get_option( 'keendevs_google_map_api_key' ) ); ?>"/>

			<?php

			submit_button();

			?>
		</form>

	</div>
	<?php
}

function keendevs_show_geodirectory_offer() {
	if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'gmaps-api-key' ) {

		if ( defined( 'GEODIRECTORY_VERSION' ) || get_option( 'geodirectory_db_version' ) ) {
			return;
		}
	
	}
}

add_action( 'admin_notices', 'keendevs_show_geodirectory_offer' );