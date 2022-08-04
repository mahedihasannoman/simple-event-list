<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup REST APIs for this plugin
 *
 * @package SimpleEventList\REST
 * @since 1.0.0
 */

namespace SimpleEventList\REST;

defined( 'ABSPATH' ) || exit;

/**
 * Class for registering REST APIs.
 *
 * @class APIs
 *
 * @since 1.0.0
 */
class APIs {

	/**
	 * APIs Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'rest_init' ) );
	}

	/**
	 * Load REST Routes
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function rest_init() {
		$api_version = strtoupper( simple_event_list()->rest_version );

		$apis = array(
			__NAMESPACE__ . "\\{$api_version}\\Events", // Register events REST rounts.
		);

		foreach ( $apis as $api ) {
			if ( class_exists( $api ) ) {
				new $api();
			}
		}
	}
}
