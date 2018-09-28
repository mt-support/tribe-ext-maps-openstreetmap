<?php

namespace Tribe\Extensions\Maps_OSM;

// Do not load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Settings' ) ) {
	/**
	 * Do the Settings (remove Google Maps, add OSM's).
	 */
	class Settings {

		/**
		 * The Settings Helper class.
		 *
		 * @var Settings_Helper
		 */
		protected $settings_helper = '';

		/**
		 * The prefix for our settings keys.
		 *
		 * @var string
		 */
		protected $opts_prefix = 'tribe_ext_osm_';

		public function __construct() {
			$this->settings_helper = new Settings_Helper();

			// Remove settings specific to Google Maps
			add_action( 'admin_init', [ $this, 'remove_settings' ] );

			// Add settings specific to OSM
			add_action( 'admin_init', [ $this, 'add_settings' ] );
		}

		/**
		 * Removes the settings from Events > Settings > General tab > "Map Settings" section
		 * that are specific to Google Maps.
		 */
		public function remove_settings() {
			// TODO: remove "Map Settings" section
			//$this->settings_helper->remove_field( 'tribeEventsDisplayTitle', 'general' );
			// "Enable Google Maps" checkbox
			$this->settings_helper->remove_field( 'embedGoogleMaps', 'general' );
			// "Map view search distance limit" (default of 25)
			$this->settings_helper->remove_field( 'geoloc_default_geofence', 'general' );
			// "Google Maps default zoom level" (0-21, default of 10)
			$this->settings_helper->remove_field( 'embedGoogleMapsZoom', 'general' );
		}

		private function get_osm_intro_text() {
			$result = '<h3>' . esc_html_x( 'OpenStreetMap (OSM) Setup', 'Settings header', PLUGIN_TEXT_DOMAIN ) . '</h3>';
			$result .= '<div style="margin-left: 20px;">';
			$result .= '<p>' . esc_html_x( 'Acknowledgements: Deactivate and uninstall this extension if you do not agree to the following terms:', 'Settings', PLUGIN_TEXT_DOMAIN );
			$result .= '<ul style="list-style: disc; margin-left: 40px;">';
			$result .= '<li>' . sprintf( _x( '<a href="%s" target="_blank">OpenStreetMap</a>', 'Settings', PLUGIN_TEXT_DOMAIN ), 'https://wiki.osmfoundation.org/wiki/Licence' ) . '</li>';
			$result .= '<li>' . sprintf( _x( '<a href="%s" target="_blank">Nominatim usage policy</a> - open source search (geolocation) based on OpenStreetMap data', 'Settings', PLUGIN_TEXT_DOMAIN ), 'https://operations.osmfoundation.org/policies/nominatim/' ) . '</li>';
			$result .= '<li>' . sprintf( _x( '<a href="%s" target="_blank">Nominatim</a> (OSM geocoding service)', 'Settings', PLUGIN_TEXT_DOMAIN ), 'https://operations.osmfoundation.org/policies/nominatim/' ) . '</li>';
			$result .= '</ul>';
			$result .= '<small><em>';
			$result .= esc_html_x( 'All trademarks are owned by their respective entities, not by Modern Tribe.', 'Settings', PLUGIN_TEXT_DOMAIN );
			$result .= '<br>';
			$result .= esc_html_x( 'All links open in a new window.', 'Settings', PLUGIN_TEXT_DOMAIN );
			$result .= '</em></small>';
			$result .= '</p>';
			$result .= '</div>';

			return $result;
		}

		/**
		 * Adds the setting field to Events > Settings > General tab
		 * The setting will appear above the "End of day cutoff" setting
		 * (below the "Single event URL slug" setting)
		 */
		public function add_settings() {
			$fields = [
				$this->opts_prefix . 'Maps_OSM'  => [
					'type' => 'html',
					'html' => $this->get_osm_intro_text(),
				],
				$this->opts_prefix . 'a_setting' => [ // TODO
					'type'            => 'text',
					'label'           => esc_html__( 'xxx try this', PLUGIN_TEXT_DOMAIN ),
					'tooltip'         => sprintf( esc_html__( 'Enter your custom URL, including "http://" or "https://", for example %s.', PLUGIN_TEXT_DOMAIN ), '<code>https://mydomain.com/events/</code>' ),
					'validation_type' => 'html',
				]
			];

			$this->settings_helper->add_fields(
				$fields,
				'general',
				'tribeEventsMiscellaneousTitle',
				true
			);
		}

	} // class
} // class_exists
