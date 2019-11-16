<?php
/**
 * Map View Override
 * This override will add the Open Street Map instead of Google Maps to the map view.
 *
 * This template is in your own theme at [your-theme]/tribe-events/pro/map/gmap-container.php
 *
 * @package TribeEventsCalendar
 * @version 4.4.28
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$shortcode  = '[leaflet-map zoomcontrol zoom=3]';
echo do_shortcode( $shortcode );
