<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * SimpleEventList setup
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

use SimpleEventList\CLI\ImportEvents;
use SimpleEventList\Admin\EventMetaboxes;
use SimpleEventList\Admin\HelpMenu;
use SimpleEventList\REST\APIs;

defined( 'ABSPATH' ) || exit;

/**
 * Main SimpleEventList Class.
 *
 * @class SimpleEventList
 *
 * @since 1.0.0
 */
final class SimpleEventList {

	/**
	 * SimpleEventList version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Plugin slug
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $slug = 'simple-event-list';

	/**
	 * REST API version
	 *
	 * @var string
	 */
	public $rest_version = 'v2';

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var SimpleEventList
	 */
	protected static $instance = null;

	/**
	 * Main SimpleEventList Instance.
	 *
	 * Ensures only one instance of SimpleEventList is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return SimpleEventList - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * SimpleEventList Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Define Simple Event List Constants.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function define_constants() {
		$this->define( 'SIMPLE_EVENT_LIST_ABSPATH', dirname( SIMPLE_EVENT_LIST_PLUGIN_FILE ) );
		$this->define( 'SIMPLE_EVENT_LIST_PLUGIN_BASENAME', plugin_basename( SIMPLE_EVENT_LIST_PLUGIN_FILE ) );
		$this->define( 'SIMPLE_EVENT_LIST_PLUGIN_URL', plugin_dir_url( SIMPLE_EVENT_LIST_PLUGIN_FILE ) );
		$this->define( 'SIMPLE_EVENT_LIST_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @since 1.0.0
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 *
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Get the correct filename suffix for minified assets.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function minified_asset_suffix() {
		$ext = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		return $ext;
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function includes() {
		if ( $this->is_request( 'admin' ) ) {
			new EventMetaboxes();
			new HelpMenu();
		}

		// Register CLI.
		if ( $this->is_request( 'cli' ) ) {
			\WP_CLI::add_command( 'simple-events', new ImportEvents() );
		}

		// Shortcodes.
		if ( $this->is_request( 'frontend' ) ) {
			Shortcodes::init();
		}

		// Load REST routes.
		if ( $this->is_request( 'rest' ) ) {
			new APIs();
		}

		// Register custom post types.
		CustomPostTypes::init();

		// Register Assets.
		new Assets();
	}

	/**
	 * When WP has loaded all plugins, trigger the `simple_event_list_hook` hook.
	 *
	 * This ensures `simple_event_list_hook` is called only after all other plugins
	 * are loaded, to avoid issues caused by plugin directory naming changing
	 * the load order.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function on_plugins_loaded() {
		do_action( 'simple_event_list_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ), -1 );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Returns true if the request is a non-legacy REST API request.
	 *
	 * Legacy REST requests should still run some extra code for backwards compatibility.
	 *
	 * @todo: replace this function once core WP function is available: https://core.trac.wordpress.org/ticket/42061.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_rest_api_request() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}
		$request_uri = $_SERVER['REQUEST_URI']; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$rest_prefix         = trailingslashit( rest_get_url_prefix() );
		$is_rest_api_request = ( false !== strpos( $request_uri, $rest_prefix ) );

		// Check if plain permalink.
		if ( ! $is_rest_api_request ) {
			$is_rest_api_request = ( false !== strpos( $request_uri, 'rest_route' ) );
		}

		return apply_filters( 'simple_event_list_is_rest_api_request', $is_rest_api_request );
	}

	/**
	 * What type of request is this?
	 *
	 * @since 1.0.0
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 *
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'cli':
				return defined( 'WP_CLI' );
			case 'rest':
				return $this->is_rest_api_request();
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! $this->is_rest_api_request();
		}
	}

	/**
	 * Load Localisation files.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = determine_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'simple-event-list' );

		unload_textdomain( 'simple-event-list' );
		load_textdomain( 'simple-event-list', WP_LANG_DIR . '/simple-event-list/simple-event-list-' . $locale . '.mo' );
		load_plugin_textdomain( 'simple-event-list', false, plugin_basename( dirname( SIMPLE_EVENT_LIST_PLUGIN_FILE ) ) . '/i18n/languages' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		wc_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'simple-event-list' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'simple-event-list' ), '1.0.0' );
	}
}
