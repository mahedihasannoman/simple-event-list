<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup shortcode for Simple Events List
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList\Shortcodes;

use SimpleEventList\Model\Event;

defined( 'ABSPATH' ) || exit;

/**
 * SimpleEvents Shortcode Class.
 *
 * @class SimpleEvents
 *
 * @since 1.0.0
 */
class SimpleEvents {

	/**
	 * Attributes.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	protected $attributes = array();

	/**
	 * Constructor
	 * 
	 * @param array $attributes Shortcode attributes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct( $attributes = array() ) {
		$this->attributes = $attributes;
	}

	/**
	 * Get shortcode content
	 *
	 * @since 1.0.0
	 *
	 * @return string $html HTML content of the shortcode.
	 */
	public function get_content() {
		wp_enqueue_style( 'simple-event-list-frontend-css' );
		$template_path = SIMPLE_EVENT_LIST_ABSPATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'event-list.php';
		if ( ! file_exists( $template_path ) ) {
			return sprintf( '<p>%s</p>', esc_html__( 'Template does not exists.', 'simple-event-list' ) );
		}

		$events = Event::get_all();
		ob_start();
		include $template_path;
		return ob_get_clean();
	}
}
