# Simple Event List #
**Contributors:** mahedihasannoman  
**Tags:** events, events-listing  
**Requires at least:** 4.5  
**Tested up to:** 6.0.1  
**Requires PHP:** 5.6  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

A Very simple event listing plugin for WordPress.

## Description ##

This is a very simple event listing plugin for WordPress. You can import events via WP CLI commands and show the event lising on a page or post via shortcode.

Also, It does provide a REST API endpoint to export the data as a JSON format.

## Installation ##

Installation is super easy, just follow the steps below.

1. Download the plugin `Releases` section of right side
2. Go to `Dashboard > Plugins > Add New` page
3. Upload `simple-event-list.zip`
4. Activate the plugin
5. After that, go to `Simple Events > Help` page from left menu to see what feature offers this plugin

That's it.

## Import Data ##

To import data, just run the following WP CLI command in terminal `wp simple-events import`

## Show Data ##

To show data, Just add the shortcode to any pages or posts `[simple-events]`

## Export Data ##

To export data, You can use the following REST API endpoint `{GET} /wp-json/simple-event-list/v2/events`

## Changelog ##

### 1.0.0 ###
* Initial release