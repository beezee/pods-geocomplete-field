<?php
/*
Plugin Name: Pods GeoComplete
Plugin URI: https://github.com/beezee/pods_geocomplete
Description: Description
Version: 0.0.1
Author: beezeee
Author URI: https://github.com/beezee
Text Domain: pods-geo-field
License: GPL v2 or later
*/

/**
 * Copyright (c) YEAR Brian Zeligson. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Define constants
 *
 * @since 0.0.2
 */
define( 'PODS_GEO_FIELD_SLUG', plugin_basename( __FILE__ ) );
define( 'PODS_GEO_FIELD_URL', plugin_dir_url( __FILE__ ) );
define( 'PODS_GEO_FIELD_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Pods_GeoComplete class
 *
 * @class Pods_GeoComplete The class that holds the entire Pods_Extend plugin
 *
 * @since 0.0.1
 */
class Pods_GeoComplete {

	/**
	 * Field Class Instance
	 *
	 * @var pods_geo_field instance
	 * @since 0.0.1
	 */
	public $field;

	/**
	 * Constructor for the Pods_GeoComplete class
	 *
	 * Sets up all the appropriate hooks and actions
	 * within the plugin.
	 *
	 * @since 0.0.1
	 */
	public function __construct() {

		// Localize our plugin
		add_action( 'init', array( $this, 'localization_setup' ) );

		/**
		 * Scripts/ Styles
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		/**
		 * Register Geo Field type 
		 *
		 */
		PodsForm::register_field_type('geo', 
			join(DIRECTORY_SEPARATOR, array(PODS_GEO_FIELD_DIR, 'classes', 'podsfield_geo.php')));	

	}

	/**
	 * Initializes the Pods_GeoComplete() class
	 *
	 * Checks for an existing Pods_GeoComplete() instance
	 * and if it doesn't find one, creates it.
	 *
	 * @since 0.0.1
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new Pods_GeoComplete();
		}

		return $instance;

	}

	/**
	 * Initialize plugin for localization
	 *
	 * @since 0.0.1
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'pods-geo-field', false, trailingslashit( PODS_GEO_FIELD_URL ) . '/languages/' );
		
	}

	/**
	 * Enqueue admin scripts
	 *
	 * Allows plugin assets to be loaded.
	 *
	 * @since 0.0.1
	 */
	public function admin_enqueue_scripts() {

		/**
		 * All admin styles goes here
		 */
		wp_enqueue_style( 'pods-geo-field-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );

		/**
		 * All admin scripts goes here
		 */
		wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places');
		wp_enqueue_script('geocomplete', plugins_url('js/vendor/jquery.geocomplete.min.js', __FILE__), array('google-maps'));
		wp_enqueue_script( 'pods-geo-field-admin-scripts', plugins_url( 'js/admin.js', __FILE__ ), array( 'geocomplete' ), false, true );
		
	}

} // Pods_GeoComplete

/**
 * Initialize class, if Pods is active.
 *
 * @since 0.0.1
 */
add_action( 'plugins_loaded', 'pods_geo_field_safe_activate');
function pods_geo_field_safe_activate() {
	if ( defined( 'PODS_VERSION' ) ) {
		$GLOBALS[ 'Pods_GeoComplete' ] = Pods_GeoComplete::init();
	}

}


/**
 * Throw admin nag if Pods isn't activated.
 *
 * Will only show on the plugins page.
 *
 * @since 0.0.1
 */
add_action( 'admin_notices', 'pods_geo_field_admin_notice_pods_not_active' );
function pods_geo_field_admin_notice_pods_not_active() {

	if ( ! defined( 'PODS_VERSION' ) ) {

		//use the global pagenow so we can tell if we are on plugins admin page
		global $pagenow;
		if ( $pagenow == 'plugins.php' ) {
			?>
			<div class="updated">
				<p><?php _e( 'You have activated Pods GeoComplete, but not the core Pods plugin.', 'pods_geo_field' ); ?></p>
			</div>
		<?php

		} //endif on the right page
	} //endif Pods is not active

}

/**
 * Throw admin nag if Pods minimum version is not met
 *
 * Will only show on the Pods admin page
 *
 * @since 0.0.1
 */
add_action( 'admin_notices', 'pods_geo_field_admin_notice_pods_min_version_fail' );
function pods_geo_field_admin_notice_pods_min_version_fail() {

	if ( defined( 'PODS_VERSION' ) ) {

		//set minimum supported version of Pods.
		$minimum_version = '2.3.18';

		//check if Pods version is greater than or equal to minimum supported version for this plugin
		if ( version_compare(  $minimum_version, PODS_VERSION ) >= 0) {

			//create $page variable to check if we are on pods admin page
			$page = pods_v('page','get', false, true );

			//check if we are on Pods Admin page
			if ( $page === 'pods' ) {
				?>
				<div class="updated">
					<p><?php _e( 'Pods GeoComplete, requires Pods version '.$minimum_version.' or later. Current version of Pods is '.PODS_VERSION, 'pods_geo_field' ); ?></p>
				</div>
			<?php

			} //endif on the right page
		} //endif version compare
	} //endif Pods is not active

}
