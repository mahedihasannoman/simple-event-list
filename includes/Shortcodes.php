<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup Shortcodes
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

use SimpleEventList\Shortcodes\SimpleEvents;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes Class.
 *
 * @class Shortcodes
 *
 * @since 1.0.0
 */
class Shortcodes {

	/**
	 * Init shortcodes
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		$shortcodes = array(
			'simple_events' => __CLASS__ . '::events',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}
	}

	/**
	 * Events shortcode
	 *
	 * @param array $atts Attributes.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function events( $atts ) {
		$atts = (array) $atts;

		$shortcode = new SimpleEvents( $atts );

		return $shortcode->get_content();
	}
}
