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

$zoomlevel = tribe_get_option( 'tribe_ext_openstreetmap_zoom_level_single' );

// Zoom levels 19 and 20 don't render the map.
$zoomlevel = $zoomlevel > 18 ? 18 : $zoomlevel;

$zoomcontrol = tribe_get_option( 'tribe_ext_openstreetmap_zoom_control' );

$mapheight = tribe_get_option( 'tribe_ext_openstreetmap_map_container_height' );

$venue_id = tribe_get_venue_id();

/* If Events Calendar PRO is active and we are using Lat and Lng then */
if ( class_exists( 'Tribe__Events__Pro__Geo_Loc' ) && tribe_get_option( 'tribe_ext_openstreetmap_use_lat_long' ) ) {
	$coords = tribe_get_coordinates( $venue_id );
	$address = 'lat=' . number_format((float)$coords['lat'], 2, '.', '') . ' lng=' . number_format((float)$coords['lng'], 2, '.', '');
	}
/* Otherwise use the address */
else {
	$address = tribe_get_address( $venue_id ) . ", " . tribe_get_zip( $venue_id ) . " " . tribe_get_city( $venue_id ) . ", " . tribe_get_country( $venue_id );
	$address = 'address="' . $address . '"';
	}

$shortcode  = '[leaflet-map ' . $address . ' zoom=' . $zoomlevel . ' zoomcontrol=' . $zoomcontrol . ' height=' . $mapheight . ']';
$shortcode .= '[leaflet-marker]';
var_dump($shortcode);
echo do_shortcode( $shortcode );
