<?php
/**
 * Template used for maps embedded within single events and venues.
 * This is a template override that replaces Google Maps with Open Street Map
 *
 *     [your-theme]/tribe-events/modules/map.php
 *
 * @version 4.6.19
 *
 * @var $index
 * @var $width
 * @var $height
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$zoomlevel = tribe_get_option( 'tribe_ext_zoom_level_single' );

// Zoom levels 19 and 20 don't render the map.
$zoomlevel = $zoomlevel > 18 ? 18 : $zoomlevel;

$zoomcontrol = tribe_get_option( 'tribe_ext_openstreetmap_zoom_control' );

$venue_id = tribe_get_venue_id();
$address = tribe_get_address( $venue_id ) . ", " . tribe_get_zip( $venue_id ) . " " . tribe_get_city( $venue_id ) . ", " . tribe_get_country( $venue_id );

$shortcode  = '[leaflet-map address="' . $address . '" zoom=' . $zoomlevel . ' zoomcontrol=' . $zoomcontrol . ']';
$shortcode .= '[leaflet-marker]';

echo do_shortcode( $shortcode );
