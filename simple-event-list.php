<?php
/**
 * Plugin Name:     Simple Event List
 * Plugin URI:      https://exmaple.com
 * Description:     A Very simple event listing plugin for WordPress.
 * Author:          Md. Mahedi Hasan
 * Author URI:      https://www.linkedin.com/in/mahedihasannoman/
 * Text Domain:     simple-event-list
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Simple_Event_List
 */

// Your code starts here.

use SimpleEventList\Simple_Event_List;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'SIMPLE_EVENT_LIST_PLUGIN_FILE' ) ) {
	define( 'SIMPLE_EVENT_LIST_PLUGIN_FILE', __FILE__ );
}

// Load core packages and the autoloader.
require __DIR__ . '/vendor/autoload.php';

/**
 * Returns the main instance of simple_event_list.
 *
 * @since [SE_LIST_VERSION]
 *
 * @return Simple_Event_List
 */
function simple_event_list() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid.
	return Simple_Event_List::instance();
}

// Global for backwards compatibility.
$GLOBALS['se_list'] = simple_event_list();
