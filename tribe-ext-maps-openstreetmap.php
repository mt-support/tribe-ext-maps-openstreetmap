<?php
/**
 * Plugin Name:       The Events Calendar Extension: OpenStreetMap
 * Plugin URI:        https://theeventscalendar.com/extensions/---the-extension-article-url---/
 * GitHub Plugin URI: https://github.com/mt-support/tribe-ext-maps-openstreetmap
 * Description:       Replace all Google Maps functionality with that of OpenStreetMap, including map displays on single event pages and Events Calendar PRO's venue geocoding, Map View, and single venue pages.
 * Version:           1.0.0
 * Extension Class:   Tribe__Extension__OpenStreetMap
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

// Do not load unless Tribe Common is fully loaded and our class does not yet exist.
if (
	class_exists( 'Tribe__Extension' )
	&& ! class_exists( 'Tribe__Extension__OpenStreetMap' )
) {
	/**
	 * Extension main class, class begins loading on init() function.
	 */
	class Tribe__Extension__OpenStreetMap extends Tribe__Extension {

		/**
		 * Setup the Extension's properties.
		 *
		 * This always executes even if the required plugins are not present.
		 */
		public function construct() {
			$this->add_required_plugin( 'Tribe__Events__Main', '4.3' );
		}

		/**
		 * Extension initialization and hooks.
		 */
		public function init() {
			// Load plugin textdomain
			// Don't forget to generate the 'languages/tribe-ext-maps-openstreetmap.pot' file
			load_plugin_textdomain( 'tribe-ext-maps-openstreetmap', false, basename( dirname( __FILE__ ) ) . '/languages/' );

			/**
			 * All extensions require PHP 5.6+, following along with https://theeventscalendar.com/knowledgebase/php-version-requirement-changes/
			 */
			$php_required_version = '5.6';

			if ( version_compare( PHP_VERSION, $php_required_version, '<' ) ) {
				if (
					is_admin()
					&& current_user_can( 'activate_plugins' )
				) {
					$message = '<p>';

					$message .= sprintf( __( '%s requires PHP version %s or newer to work. Please contact your website host and inquire about updating PHP.', 'tribe-ext-maps-openstreetmap' ), $this->get_name(), $php_required_version );

					$message .= sprintf( ' <a href="%1$s">%1$s</a>', 'https://wordpress.org/about/requirements/' );

					$message .= '</p>';

					tribe_notice( $this->get_name(), $message, 'type=error' );
				}

				return;
			}

			// Insert filters and hooks here
			add_filter( 'thing_we_are_filtering', array( $this, 'my_custom_function' ) );
		}

		/**
		 * Include a docblock for every class method and property.
		 */
		public function my_custom_function() {
			// custom stuff
		}

	} // end class
} // end if class_exists check
