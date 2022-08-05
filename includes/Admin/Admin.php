<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup Admin class.
 *
 * @package SimpleEventList\Admin
 * @since 1.0.0
 */

namespace SimpleEventList\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Admin Class.
 *
 * @class Admin
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * Admin Constructor
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		// Setup plugin help menu.
		new HelpMenu();

		// Setup Event Actions.
		new EventActions();
	}
}
