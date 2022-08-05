<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup Assets
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

defined( 'ABSPATH' ) || exit;

/**
 * Assets Class.
 *
 * @class Assets
 *
 * @since 1.0.0
 */
class Assets {

	/**
	 * Initiate Assets
	 *
	 * @since 1.0.0
	 *
	 * @static
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( static::class, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( static::class, 'frontend_scripts' ) );
	}

	/**
	 * Register admin styles/scripts
	 *
	 * @since 1.0.0
	 *
	 * @static
	 *
	 * @return void
	 */
	public static function admin_scripts() {
		wp_register_style(
			'simple-event-list-admin-css',
			SIMPLE_EVENT_LIST_PLUGIN_URL . 'assets/css/admin' . simple_event_list()->minified_asset_suffix() . '.css',
			array(),
			simple_event_list()->version,
			'all'
		);
	}

	/**
	 * Register frontend styles/scripts
	 *
	 * @since 1.0.0
	 *
	 * @static
	 *
	 * @return void
	 */
	public static function frontend_scripts() {
		wp_register_style(
			'simple-event-list-frontend-css',
			SIMPLE_EVENT_LIST_PLUGIN_URL . 'assets/css/frontend' . simple_event_list()->minified_asset_suffix() . '.css',
			array(),
			simple_event_list()->version,
			'all'
		);
	}
}
