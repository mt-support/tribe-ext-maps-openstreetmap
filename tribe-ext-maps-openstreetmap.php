<?php
/**
 * Plugin Name:       The Events Calendar Extension: OpenStreetMap
 * Plugin URI:        https://theeventscalendar.com/extensions/openstreetmap/
 * GitHub Plugin URI: https://github.com/mt-support/tribe-ext-maps-openstreetmap
 * Description:       Replace most Google Maps functionality with that of OpenStreetMap, including map displays on single event pages, and Events Calendar PRO's Map View, and single venue pages. <strong>This extension requires the <a href="plugin-install.php?s=Leaflet+Map&tab=search&type=term">Leaflet Map</a> plugin.</strong> Special thanks to Gerd Weyhing for the <a href="https://woyng.com/the-events-calendar-mit-openstreetmaps-statt-google-maps/" target="_blank">inspiration</a>. Note: This extension doesn't yet support venue geocoding.
 * Version:           1.0.0
 * Extension Class:   Tribe\Extensions\OpenStreetMap\Main
 * Author:            Modern Tribe, Inc.
 * Author URI:        http://m.tri.be/1971
 * License:           GPL version 3 or any later version
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tribe-ext-openstreetmap
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

namespace Tribe\Extensions\OpenStreetMap;

use Tribe__Autoloader;
use Tribe__Dependency;
use Tribe__Extension;
use Tribe__Settings;

/**
 * Define Constants
 */

if ( ! defined( __NAMESPACE__ . '\NS' ) ) {
	define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );
}

if ( ! defined( NS . 'PLUGIN_TEXT_DOMAIN' ) ) {
	// `Tribe\Extensions\Example\PLUGIN_TEXT_DOMAIN` is defined
	define( NS . 'PLUGIN_TEXT_DOMAIN', 'tribe-ext-openstreetmap' );
}

// Do not load unless Tribe Common is fully loaded and our class does not yet exist.
if (
	class_exists( 'Tribe__Extension' )
	&& ! class_exists( NS . 'Main' )
) {
	/**
	 * Extension main class, class begins loading on init() function.
	 */
	class Main extends Tribe__Extension {

		/**
		 * @var Tribe__Autoloader
		 */
		private $class_loader;

		/**
		 * @var Settings
		 */
		private $settings;

		/**
		 * Custom options prefix (without trailing underscore).
		 *
		 * Should leave blank unless you want to set it to something custom, such as if migrated from old extension.
		 */
		private $opts_prefix = '';

		/**
		 * Is Events Calendar PRO active. If yes, we will add some extra functionality.
		 *
		 * @return bool
		 */
		public $ecp_active = true;

		/**
		 * The list of The Events Calendar's template files to override with
		 * which of this plugin's template files.
		 *
		 * @return array
		 */
		private function templates() {
			return array(
				'modules/map.php'            => 'src/views/modules/open-street-map.php',
				'pro/map/gmap-container.php' => 'src/views/pro/map/osmap-container.php',
			);
		}

		/**
		 * Setup the Extension's properties.
		 *
		 * This always executes even if the required plugins are not present.
		 */
		public function construct() {
			// Dependency requirements and class properties can be defined here.

			/**
			 * Examples:
			 * All these version numbers are the ones on or after November 16, 2016, but you could remove the version
			 * number, as it's an optional parameter. Know that your extension code will not run at all (we won't even
			 * get this far) if you are not running The Events Calendar 4.3.3+ or Event Tickets 4.3.3+, as that is where
			 * the Tribe__Extension class exists, which is what we are extending.
			 *
			 * If using `tribe()`, such as with `Tribe__Dependency`, require TEC/ET version 4.4+ (January 9, 2017).
			 */
			$this->add_required_plugin( 'Tribe__Events__Main', '4.9.10' );

			// Conditionally-require Events Calendar PRO. If it is active, run an extra bit of code.
			add_action( 'tribe_plugins_loaded', [ $this, 'detect_tec_pro' ], 0 );

			//$this->set_url( 'https://theeventscalendar.com/extensions/openstreetmap/' );
		}

		/**
		 * Check required plugins after all Tribe plugins have loaded.
		 *
		 * Useful for conditionally-requiring a Tribe plugin, whether to add extra functionality
		 * or require a certain version but only if it is active.
		 */
		public function detect_tec_pro() {
			/** @var Tribe__Dependency $dep */
			$dep = tribe( Tribe__Dependency::class );

			if ( $dep->is_plugin_active( 'Tribe__Events__Pro__Main' ) ) {
				$this->add_required_plugin( 'Tribe__Events__Pro__Main' );
				$this->ecp_active = true;
			}
		}

		/**
		 * Get Settings instance.
		 *
		 * @return Settings
		 */
		private function get_settings() {
			if ( empty( $this->settings ) ) {
				$this->settings = new Settings( $this->opts_prefix );
			}

			return $this->settings;
		}

		/**
		 * Extension initialization and hooks.
		 */
		public function init() {
			// Load plugin textdomain
			// Don't forget to generate the 'languages/tribe-ext-extension-template.pot' file
			load_plugin_textdomain( PLUGIN_TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );

			if ( ! $this->php_version_check() ) {
				return;
			}

			$this->class_loader();

			$this->get_settings();

			// If Leaflet Map plugin is active, then do the magic.
			if ( class_exists( \Leaflet_Map::class ) ) {
				add_action( 'init', array( $this, 'common_setup' ) );
			}
			else {
				$this->missing_plugin_message();
			}
		}

		/**
		 * Check if we have a sufficient version of PHP. Admin notice if we don't and user should see it.
		 *
		 * @link https://theeventscalendar.com/knowledgebase/php-version-requirement-changes/ All extensions require PHP 5.6+.
		 *
		 * @link https://secure.php.net/manual/en/migration70.new-features.php
		 * 7.0: Return Types, Scalar Type Hints, Spaceship Operator, Constant Arrays Using define(), Anonymous Classes, intdiv(), and preg_replace_callback_array()
		 *
		 * @return bool
		 */
		private function php_version_check() {
			$php_required_version = '7.0';

			if ( version_compare( PHP_VERSION, $php_required_version, '<' ) ) {
				if (
					is_admin()
					&& current_user_can( 'activate_plugins' )
				) {
					$message = '<p>';
					$message .= sprintf( __( '%s requires PHP version %s or newer to work. Please contact your website host and inquire about updating PHP.', PLUGIN_TEXT_DOMAIN ), $this->get_name(), $php_required_version );
					$message .= sprintf( ' <a href="%1$s">%1$s</a>', 'https://wordpress.org/about/requirements/' );
					$message .= '</p>';
					tribe_notice( PLUGIN_TEXT_DOMAIN . '-php-version', $message, [ 'type' => 'error' ] );
				}

				return false;
			}

			return true;
		}

		/**
		 * Use Tribe Autoloader for all class files within this namespace in the 'src' directory.
		 *
		 * @return Tribe__Autoloader
		 */
		public function class_loader() {
			if ( empty( $this->class_loader ) ) {
				$this->class_loader = new Tribe__Autoloader;
				$this->class_loader->set_dir_separator( '\\' );
				$this->class_loader->register_prefix(
					NS,
					__DIR__ . DIRECTORY_SEPARATOR . 'src'
				);
			}

			$this->class_loader->register_autoloader();

			return $this->class_loader;
		}

		/**
		 * Do the things to override the templates.
		 */
		public function common_setup() {
			$this->setup_templates();
		}

		/**
		 * Filters templates to use our overrides.
		 */
		private function setup_templates() {
			foreach ( $this->templates() as $template => $new_template ) {
				add_filter( 'tribe_get_template_part_path_' . $template, function ( $file, $slug, $name ) use ( $new_template ) {
					// Return the path for our file.
					return plugin_dir_path( __FILE__ ) . $new_template;
				}, 10, 3 );
			}
		}

		/**
		 * TODO: Testing Hello World. Delete this for your new extension.
		 */
		public function missing_plugin_message() {
			$message = sprintf( '<p>&#9888; Please note, %s requires the %s plugin to work.</p>', '<strong>' . $this->get_name() . '</strong>', '<a href="https://wordpress.org/plugins/leaflet-map/" target="_blank">Leaflet Map &#x1F5D7;</a>' );
			$message .= sprintf( '<p>%s to go to the Plugins &gt; Add new page to download and install Leaflet Map.</p>', '<a href="plugin-install.php?s=Leaflet+Map&tab=search&type=term">Click here</a>' );
			tribe_notice( PLUGIN_TEXT_DOMAIN, $message, [ 'type' => 'warning' ] );
		}

	} // end class
} // end if class_exists check
