<?php
/*
 * Plugin Name: WP Developer Profiles
 * Plugin URI: http://www.itsananderson.com/plugins/dev-profiles/
 * Description: Save and activate theme and plugin profiles for different projects
 * Plugin Author: Will Anderson
 * Author URI: http://www.itsananderson.com/
 */

class WP_Dev_Profiles {

	const DEV_PROFILES_OPTION_NAME = 'wp_dev_profiles';

	public static function start() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
	}

	public static function add_menu() {
		add_management_page( __( 'Developer Profiles' ), __( 'Developer Profiles' ), 'administrator', __FILE__, array( __CLASS__, 'dev_profile_page' ) );
	}

	public static function dev_profile_page() {
		$profiles = get_option( self::DEV_PROFILES_OPTION_NAME, array());
		if ( isset( $_POST['create-dev-profile'] ) ) {
			$profiles[$_POST['profile-name']] = array(
				'template' => get_option( 'template', 'Default' ),
				'stylesheet' => get_option( 'stylesheet', 'default' ),
				'plugins' => get_option( 'active_plugins', array( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) ) ) // TODO: probably a better way to get the plugin slug
			);
			update_option( self::DEV_PROFILES_OPTION_NAME, $profiles);
			$message = "Profile '{$_POST['profile-name']}' successfully created";
		} elseif ( isset( $_POST['enable-dev-profile'] ) ) {
			if ('' == $_POST['select-profile']) {
				$message = "Oops, you need to select a Dev Profile before it can be enabled";
			} else {
				$profile = $profiles[$_POST['select-profile']];
				switch_theme( $profile['stylesheet'] );

				$currently_active_plugins = get_option( 'active_plugins', array() );

				foreach ( $currently_active_plugins as $active_plugin ) {
					if ( !in_array( $active_plugin, $profile['plugins'] ) ) {
						deactivate_plugins( $active_plugin, true );
					}
				}

				foreach ( $profile['plugins'] as $plugin ) {
					if ( !in_array( $plugin, $currently_active_plugins ) ) {
						activate_plugin( $plugin, '', false, true );
					}
				}
				$message = "Dev profile '{$_POST['select-profile']}' successfully enabled";
			}
		}
		include plugin_dir_path( __FILE__ ) . 'views/profiles.php';
	}
}

WP_Dev_Profiles::start();