<?php
/**
 * Plugin Name:       The Events Calendar Extension: OpenStreetMap
 * Plugin URI:        https://theeventscalendar.com/extensions/---the-extension-article-url---/
 * GitHub Plugin URI: https://github.com/mt-support/tribe-ext-maps-openstreetmap
 * Description:       Replace Google Maps with OpenStreetMap (OSM), including map displays on single event pages and Events Calendar PRO's venue geocoding, Map View, and single venue pages.
 * Version:           1.0.0
 * Extension Class:   Tribe\Extensions\Maps_OSM\Maps_OpenStreetMap
 * GitHub Plugin URI: https://github.com/mt-support/extension-template
 * Author:            Modern Tribe, Inc.
 * Author URI:        http://m.tri.be/1971
 * License:           GPL version 3 or any later version
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tribe-ext-maps-openstreetmap
 *
 *     This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *     GNU General Public License for more details.
 */

/**
 * TODO: require TEC version 18.16? https://central.tri.be/issues/114865
 * TODO: Fix geolocation data
 * TODO: event single
 * TODO: venue single
 * TODO: map view
 * TODO: import settings
 * TODO: geolocation
 * TODO: https://wiki.osmfoundation.org/wiki/Licence/Licence_and_Legal_FAQ#What_do_you_mean_by_.22Attribution.22.3F
 * TODO: extension article
 * TODO:
 * TODO:
 * TODO:
 * TODO:
 */

namespace Tribe\Extensions\Maps_OSM;

use Tribe__Autoloader;
use Tribe__Dependency;
use Tribe__Extension;

/**
 * Define Constants
 */

if ( ! defined( __NAMESPACE__ . '\NS' ) ) {
	define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );
}

if ( ! defined( NS . 'PLUGIN_TEXT_DOMAIN' ) ) {
	// `Tribe\Extensions\Maps_OSM\PLUGIN_TEXT_DOMAIN` is defined
	define( NS . 'PLUGIN_TEXT_DOMAIN', 'tribe-ext-maps-openstreetmap' );
	$x = PLUGIN_TEXT_DOMAIN;
}

// Do not load unless Tribe Common is fully loaded and our class does not yet exist.
if (
	class_exists( 'Tribe__Extension' )
	&& ! class_exists( 'Tribe\Extensions\Maps_OSM\Maps_OpenStreetMap' )
) {
	/**
	 * Extension main class, class begins loading on init() function.
	 */
	class Maps_OpenStreetMap extends Tribe__Extension {

		/** @var Tribe__Autoloader */
		private $class_loader;

		/**
		 * Is Events Calendar PRO active. If yes, we will add some extra functionality.
		 *
		 * @return bool
		 */
		public $ecp_active = false;

		/**
		 * Setup the Extension's properties.
		 *
		 * This always executes even if the required plugins are not present.
		 */
		public function construct() {
			$this->add_required_plugin( 'Tribe__Events__Main', '4.3' );
			add_action( 'tribe_plugins_loaded', array( $this, 'detect_ecp' ), 0 );
		}

		/**
		 * Check required plugins after all Tribe plugins have loaded.
		 */
		public function detect_ecp() {
			if ( Tribe__Dependency::instance()->is_plugin_active( 'Tribe__Events__Pro__Main' ) ) {
				$this->add_required_plugin( 'Tribe__Events__Pro__Main', '4.3.1' );
				$this->ecp_active = true;
			}
		}

		/**
		 * Extension initialization and hooks.
		 */
		public function init() {
			// Load plugin textdomain
			load_plugin_textdomain( PLUGIN_TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );

			if ( ! $this->php_version_check() ) {
				return;
			}

			$this->class_loader();

			if ( is_admin() ) {
				new Settings();
			}
		}

		/**
		 * Check if we have a sufficient version of PHP. Admin notice if we don't and user should see it.
		 *
		 * @link https://theeventscalendar.com/knowledgebase/php-version-requirement-changes/
		 *
		 * @return bool
		 */
		private function php_version_check() {
			$php_required_version = '5.6';

			if ( version_compare( PHP_VERSION, $php_required_version, '<' ) ) {
				if (
					is_admin()
					&& current_user_can( 'activate_plugins' )
				) {
					$message = '<p>';

					$message .= sprintf( __( '%s requires PHP version %s or newer to work. Please contact your website host and inquire about updating PHP.', PLUGIN_TEXT_DOMAIN ), $this->get_name(), $php_required_version );

					$message .= sprintf( ' <a href="%1$s">%1$s</a>', 'https://wordpress.org/about/requirements/' );

					$message .= '</p>';

					tribe_notice( $this->get_name(), $message, 'type=error' );
				}

				return false;
			}

			return true;
		}

		/**
		 *
		 *
		 * @return Tribe__Autoloader
		 */
		public function class_loader() {
			if ( empty( $this->class_loader ) ) {
				$this->class_loader = new Tribe__Autoloader;
				$this->class_loader->set_dir_separator( '\\' );
				$this->class_loader->register_prefix(
					'Tribe\Extensions\Maps_OSM\\',
					__DIR__ . DIRECTORY_SEPARATOR . 'src'
				);
			}

			$this->class_loader->register_autoloader();

			return $this->class_loader;
		}
	} // end class
} // end if class_exists check
