# The Events Calendar Extension: OpenStreetMap

Contributors: ModernTribe  
Donate link: http://m.tri.be/29  
Tags: events, calendar, ical, export  
Requires at least: 4.9  
Tested up to: 5.3  
Requires PHP: 7.0  
Stable tag: 1.0.0  
License: GPL version 3 or any later version  
License URI: https://www.gnu.org/licenses/gpl-3.0.html

## Description 

The extension replaces most of the Google Maps functionality with that of OpenStreetMap, including map displays on single event pages, and Events Calendar PRO's Map View, and single venue pages.

This extension requires the [Leaflet Map](https://wordpress.org/plugins/leaflet-map/) plugin by [bozdoz](https://profiles.wordpress.org/bozdoz/). Special thanks to Gerd Weyhing for the [inspiration](https://woyng.com/the-events-calendar-mit-openstreetmaps-statt-google-maps/) and Hans-Gerd Gerhards for bringing it to our attention.

**Note 1: This extension doesn't yet support venue geocoding.** You can still use a Google Maps API key for that, which you have to enter at `Events > Settings > APIs`.

**Note 2: This extension works only with the 'classic' views.** As you might have heard we are redesigning the calendar views. This extension does not yet support the new views, which are in beta phase at the time of publishing.

## Installation

Install and activate like any other plugin!

* You can upload the plugin zip file via the *Plugins ‣ Add New* screen
* You can unzip the plugin and then upload to your plugin directory (typically _wp-content/plugins)_ via FTP
* Once it has been installed or uploaded, simply visit the main plugin list and activate it

## Frequently Asked Questions

## How do I use this?

The plugin will add the following settings to the `Events > Settings > General` page.  

**Map Settings section:** the settings here have an effect on the single event page and on the venue page*.  

* Enable zoom control - Show or hide the zoom control buttons on the map on the single event page and on the venue page.
* Default zoom level - Sets the default zoom level for the map on the single event page and on the venue page.
* Default height of map - Sets the height of the map container on the single event page and on the venue page.

*Note: The venue page and the usage of latitude and longitude requires [Events Calendar PRO](http://m.tri.be/k0).

**Map View Settings section:** these settings affect only the Map view, which requires [Events Calendar PRO](http://m.tri.be/k0).

* Default zoom level for Map view - Sets the default zoom level for the map on the Map view page.
* Default height of map on Map view - Sets the height of the map container on the Map view page.
* Default Map view address - The address where the map on the Map view should be centered on. Required format: Street and House Number, ZIP City, County / State (optional), Country.

## Where can I find more extensions?

Please visit our [extension library](https://theeventscalendar.com/extensions/) to learn about our complete range of extensions for The Events Calendar and its associated plugins.

### What if I experience problems?

We're always interested in your feedback and our [premium forums](https://theeventscalendar.com/support-forums/) are the best place to flag any issues. Do note, however, that the degree of support we provide for extensions like this one tends to be very limited.

## Changelog

### [1.0.0] 2019-11-20 

* Initial release
