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

$zoom = tribe_get_option( 'embedGoogleMapsZoom' );
$zoom = $zoom > 18 ? 18 : $zoom;
$venue_id = tribe_get_venue_id();
$address = tribe_get_address( $venue_id ) . ", " . tribe_get_zip( $venue_id ) . " " . tribe_get_city( $venue_id ) . ", " . tribe_get_country( $venue_id );

$shortcode  = '[leaflet-map address="' . $address . '" zoom=' . $zoom . ' zoomcontrol]';
$shortcode .= '[leaflet-marker]';

echo do_shortcode( $shortcode );
