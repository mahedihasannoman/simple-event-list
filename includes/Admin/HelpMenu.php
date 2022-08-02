<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup admin menu class.
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList\Admin;

use SimpleEventList\PostTypes\SampleEvent;

defined( 'ABSPATH' ) || exit;

/**
 * Admin Menu Class.
 *
 * @class Menu
 *
 * @since 1.0.0
 */
class HelpMenu {

	/**
	 * Parent menu slug
	 *
	 * @var string
	 */
	private $parent;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->parent = 'edit.php?post_type=' . SampleEvent::post_type();
		add_action( 'admin_menu', array( $this, 'add_submenu' ) );
	}

	/**
	 * Add the help submenu
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_submenu() {
		add_submenu_page(
			$this->parent,
			__( 'Help', 'simple-event-list' ),
			__( 'Help', 'simple-event-list' ),
			'manage_options',
			'simple-event-help',
			array( $this, 'render' )
		);
	}

	/**
	 * Render the help page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render() {
		$template_path = SIMPLE_EVENT_LIST_ABSPATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'help.php';
		if ( file_exists( $template_path ) ) {
			include_once $template_path;
		}
	}
}
