<?php
/**
 * Setup metaboxes for Simple Events
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode Class.
 *
 * @class Shortcode
 *
 * @since 1.0.0
 */
class Shortcode {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'setup_shortcode' ) );
	}

	/**
	 * Setup shortcode hook
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup_shortcode() {
		add_shortcode( 'simple_events', array( $this, 'shortcode_callback' ) );
	}

	/**
	 * Shortcode callback function
	 *
	 * @param Array $attr Array of attributes.
	 *
	 * @since 1.0.0
	 *
	 * @return string $html HTML content of the shortcode.
	 */
	public function shortcode_callback( $attr ) {

		$template_path = SIMPLE_EVENT_LIST_ABSPATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'event-list.php';
		if ( ! file_exists( $template_path ) ) {
			return sprintf( '<p>%s</p>', esc_html__( 'Template does not exists.', 'simple-event-list' ) );
		}

		$events = sel_get_events();
		ob_start();
		include $template_path;
		return ob_get_clean();
	}
}