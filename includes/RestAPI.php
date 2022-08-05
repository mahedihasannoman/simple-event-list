<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Load REST APIs for this plugin
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

defined( 'ABSPATH' ) || exit;

/**
 * Load REST APIs.
 *
 * @class RestAPI
 *
 * @since 1.0.0
 */
class RestAPI {

	/**
	 * Initiate RestAPI
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'rest_api_init', array( static::class, 'load_routes' ) );
	}

	/**
	 * Load REST Routes
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function load_routes() {
		$api_version = strtoupper( simple_event_list()->rest_version );

		$apis = array(
			__NAMESPACE__ . "\\REST\\{$api_version}\\Events", // Register events REST rounts.
		);

		foreach ( $apis as $api ) {
			if ( class_exists( $api ) ) {
				new $api();
			}
		}
	}
}
