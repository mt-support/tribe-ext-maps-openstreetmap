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
$zoomlevel = tribe_get_option( 'tribe_ext_openstreetmap_zoom_level_map' );

// Zoom levels 19 and 20 don't render the map.
$zoomlevel = $zoomlevel > 18 ? 18 : $zoomlevel;

$zoomcontrol = tribe_get_option( 'tribe_ext_openstreetmap_zoom_control' );

$mapheight = tribe_get_option( 'tribe_ext_openstreetmap_default_map_view_height' );

$center = esc_html( tribe_get_option( 'tribe_ext_openstreetmap_default_map_address' ) );

$shortcode  = '[leaflet-map address="' . $center . '" zoomcontrol=' . $zoomcontrol . ' zoom=' . $zoomlevel . ' height=' . $mapheight . ']';

echo do_shortcode( $shortcode );
