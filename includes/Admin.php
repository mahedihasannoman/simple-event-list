<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup Admin class.
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

use SimpleEventList\Admin\EventActions;
use SimpleEventList\Admin\HelpMenu;

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
	 * Initiate Admin
	 *
	 * @since 1.0.0
	 *
	 * @static
	 *
	 * @return void
	 */
	public static function init() {
		// Setup plugin help menu.
		new HelpMenu();

		// Setup Event Actions.
		new EventActions();
	}
}
