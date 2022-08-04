<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup Assets
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

use SimpleEventList\PostTypes\SampleEvent;

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
	 * Asset file suffix. i.e .min.css
	 *
	 * @var string
	 */
	private $suffix;

	/**
	 * Version of the script or style
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->suffix  = $GLOBALS['simple_event_list']->minified_asset_suffix();
		$this->version = $GLOBALS['simple_event_list']->version;
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
	}

	/**
	 * Register admin styles/scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_scripts() {
		if ( $this->is_admin_page() ) {
			wp_register_style(
				'simple-event-list-admin-css',
				SIMPLE_EVENT_LIST_PLUGIN_URL . "assets/css/admin{$this->suffix}.css",
				array(),
				$this->version,
				'all'
			);
			wp_enqueue_style( 'simple-event-list-admin-css' );
		}

	}

	/**
	 * Check if it is an admin pages of simple event list.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	private function is_admin_page() {

		$current_screen = get_current_screen();
		if ( SampleEvent::post_type() === $current_screen->post_type ) {
			return true;
		}
		return false;
	}

	/**
	 * Register frontend styles/scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function frontend_scripts() {
		wp_register_style(
			'simple-event-list-frontend-css',
			SIMPLE_EVENT_LIST_PLUGIN_URL . "assets/css/frontend{$this->suffix}.css",
			array(),
			$this->version,
			'all'
		);
	}
}
