<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * WP_CLI setup
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

use SimpleEventList\CLI\ImportEvents;

defined( 'ABSPATH' ) || exit;

/**
 * Main CLIRunner Class.
 *
 * @class CLIRunner
 *
 * @since 1.0.0
 */
class CLIRunner {

	/**
	 * Initiate the CLIRunner
	 *
	 * @since 1.0.0
	 *
	 * @static
	 *
	 * @return void
	 */
	public static function init() {
		\WP_CLI::add_hook( 'after_wp_load', array( ImportEvents::class, 'register_commands' ) );
	}
}
