<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup admin menu class.
 *
 * @package SimpleEventList\Admin
 * @since 1.0.0
 */

namespace SimpleEventList\Admin;

use SimpleEventList\PostTypes\SimpleEvent;

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
		$this->parent = 'edit.php?post_type=' . SimpleEvent::post_type();
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
		$help = add_submenu_page(
			$this->parent,
			__( 'Help', 'simple-event-list' ),
			__( 'Help', 'simple-event-list' ),
			'manage_options',
			'simple-event-help',
			array( $this, 'render' )
		);

		// Add styles and scripts.
		add_action( $help, array( $this, 'enqueue_script' ) );
	}

	/**
	 * Enqueue scripts/styles for help page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_script() {
		wp_enqueue_style( 'simple-event-list-admin-css' );
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
