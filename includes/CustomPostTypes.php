<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup Custom post types
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

use SimpleEventList\PostTypes\SimpleEvent;

defined( 'ABSPATH' ) || exit;

/**
 * CustomPostTypes Class.
 *
 * @class CustomPostTypes
 *
 * @since 1.0.0
 */
class CustomPostTypes {

	/**
	 * Init custom post types
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		new SimpleEvent();
	}
}
