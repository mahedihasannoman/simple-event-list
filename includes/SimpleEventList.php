<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * SimpleEventList setup
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

use SimpleEventList\Cli\ImportEvents;
use SimpleEventList\Admin\EventMetaboxes;
use SimpleEventList\Admin\HelpMenu;
use SimpleEventList\PostTypes\SampleEvent as RegisterEvent;
use SimpleEventList\Shortcodes\SimpleEvents;

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

		// CLI Request.
		if ( $this->is_request( 'cli' ) ) {
			\WP_CLI::add_command( 'simple-events', new ImportEvents() );
		}

		// Shortcode.
		if ( $this->is_request( 'frontend' ) ) {
			new SimpleEvents();
		}

		// Register Simple Event post type.
		new RegisterEvent();
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

		$rest_prefix         = trailingslashit( rest_get_url_prefix() );
		$is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

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
	 * Get the plugin url.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', SIMPLE_EVENT_LIST_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( SIMPLE_EVENT_LIST_PLUGIN_FILE ) );
	}

	/**
	 * Get Ajax URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Return the API URL for a given request.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $request Requested endpoint.
	 * @param bool|null $ssl     If should use SSL, null if should auto detect. Default: null.
	 *
	 * @return string
	 */
	public function api_request_url( $request, $ssl = null ) {
		if ( is_null( $ssl ) ) {
			$scheme = wp_parse_url( home_url(), PHP_URL_SCHEME );
		} elseif ( $ssl ) {
			$scheme = 'https';
		} else {
			$scheme = 'http';
		}

		if ( strstr( get_option( 'permalink_structure' ), '/index.php/' ) ) {
			$api_request_url = trailingslashit( home_url( '/index.php/simple-event-list-api/' . $request, $scheme ) );
		} elseif ( get_option( 'permalink_structure' ) ) {
			$api_request_url = trailingslashit( home_url( '/simple-event-list-api/' . $request, $scheme ) );
		} else {
			$api_request_url = add_query_arg( 'simple-event-list-api', $request, trailingslashit( home_url( '', $scheme ) ) );
		}

		return esc_url_raw( apply_filters( 'simple_event_list_api_request_url', $api_request_url, $request, $ssl ) );
	}

}
