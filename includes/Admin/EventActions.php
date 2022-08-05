<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup Event Actions
 *
 * @package SimpleEventList\Admin
 * @since 1.0.0
 */

namespace SimpleEventList\Admin;

use SimpleEventList\PostTypes\SimpleEvent;

defined( 'ABSPATH' ) || exit;

/**
 * EventActions Class.
 *
 * @class EventActions
 *
 * @since 1.0.0
 */
class EventActions {

	/**
	 * Post type
	 *
	 * @var string
	 */
	private $post_type;

	/**
	 * Nonce for metaboxes
	 *
	 * @var string
	 */
	private $nonce = 'simple_event_nonce';

	/**
	 * Nonce value for metaboxes
	 *
	 * @var string
	 */
	private $nonce_value = 'simple_event_nonce_value';

	/**
	 * Constructor for SE_Setup_Metaboxes.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->post_type = SimpleEvent::post_type();
		// Add metaboxes for event.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		// Save metabox data for event.
		add_action( 'save_post', array( $this, 'save_post' ), 100 );

		// Add metabox styles to event edit page.
		add_action( 'admin_print_styles-post.php', array( $this, 'load_css' ), 10 );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'load_css' ), 10 );

		// Add custom column in event table.
		add_filter( "manage_edit-{$this->post_type}_columns", array( $this, 'add_columns' ), 10 );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'column_content' ), 10, 2 );
		add_filter( "manage_edit-{$this->post_type}_sortable_columns", array( $this, 'sortable_columns' ), 10 );

		// Sort by event time.
		add_action( 'pre_get_posts', array( $this, 'column_orderby' ) );
	}

	/**
	 * Add columns
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Event table columns.
	 *
	 * @return array
	 */
	public function add_columns( $columns ) {
		$columns['timestamp'] = __( 'Event Time', 'simple-event-list' );
		return $columns;
	}

	/**
	 * Add column content
	 *
	 * @since 1.0.0
	 *
	 * @param string $column_name Column name.
	 * @param int    $post_id     Post ID.
	 * @return null|string
	 */
	public function column_content( $column_name, $post_id ) {
		if ( 'timestamp' !== $column_name ) {
			return;
		}
		// Get timestamp from post meta.
		$timestamp = get_post_meta( $post_id, '_simple_event_time', true );
		echo esc_html( ucfirst( sel_relative_time_from_timestamp( $timestamp ) ) );
	}

	/**
	 * Make columns sortable.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Event table columns.
	 *
	 * @return array
	 */
	public function sortable_columns( $columns ) {
		$columns['timestamp'] = 'event_time';
		return $columns;
	}

	/**
	 * Orderby custom column
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Query $query The WordPress Query class.
	 * @return void
	 */
	public function column_orderby( $query ) {
		$orderby = $query->get( 'orderby' );

		if ( 'event_time' === $orderby ) {
			$query->set( 'meta_key', '_simple_event_time' );
			$query->set( 'orderby', 'meta_value' );
		}
	}

	/**
	 * Add Meta boxes for the custom post type
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box( 'simple-event-metabox', __( 'Event Metadata', 'simple-event-list' ), array( $this, 'render_metabox' ), $this->post_type, 'side', 'high' );
	}

	/**
	 * Render style for Event post type
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_css() {
		$screen = get_current_screen();
		if ( $this->post_type === $screen->post_type ) {
			wp_enqueue_style( 'simple-event-list-admin-css' );
		}
	}

	/**
	 * Render metabox
	 *
	 * @param WP_Post $post The object for the current post/page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_metabox( $post ) {
		wp_nonce_field( $this->nonce_value, $this->nonce );
		$organizer = get_post_meta( $post->ID, '_simple_event_organizer', true );
		$email     = get_post_meta( $post->ID, '_simple_event_email', true );
		$time      = get_post_meta( $post->ID, '_simple_event_time', true );
		$address   = get_post_meta( $post->ID, '_simple_event_address', true );
		$latitude  = get_post_meta( $post->ID, '_simple_event_latitude', true );
		$longitude = get_post_meta( $post->ID, '_simple_event_longitude', true );
		?>
		<div class="sel_form_container">
			<div class="sel_form_field">
				<label for="_simple_event_organizer"><?php echo esc_html__( 'Organizer', 'simple-event-list' ); ?></label>
				<input type="text" name="_simple_event_organizer" value="<?php echo esc_attr( $organizer ); ?>" >
			</div>
			<div class="sel_form_field">
				<label for="_simple_event_email"><?php echo esc_html__( 'Email', 'simple-event-list' ); ?></label>
				<input type="email" name="_simple_event_email" value="<?php echo esc_attr( $email ); ?>" >
			</div>
			<div class="sel_form_field">
				<label for="_simple_event_time"><?php echo esc_html__( 'Timestamp', 'simple-event-list' ); ?></label>
				<input type="text" name="_simple_event_time" value="<?php echo esc_attr( $time ); ?>" >
			</div>
			<div class="sel_form_field">
				<label for="_simple_event_address"><?php echo esc_html__( 'Address', 'simple-event-list' ); ?></label>
				<input type="text" name="_simple_event_address" value="<?php echo esc_attr( $address ); ?>" >
			</div>
			<div class="sel_form_field">
				<label for="_simple_event_latitude"><?php echo esc_html__( 'Latitude', 'simple-event-list' ); ?></label>
				<input type="text" name="_simple_event_latitude" value="<?php echo esc_attr( $latitude ); ?>" >
			</div>
			<div class="sel_form_field">
				<label for="_simple_event_longitude"><?php echo esc_html__( 'Longitude', 'simple-event-list' ); ?></label>
				<input type="text" name="_simple_event_longitude" value="<?php echo esc_attr( $longitude ); ?>" >
			</div>
		</div>
		<?php
	}

	/**
	 * Save the metabox values
	 *
	 * @param int $post_id Post id.
	 *
	 * @return void
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST[ $this->nonce ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST[ $this->nonce ], $this->nonce_value ) ) { // phpcs:ignore WordPress.SuperGlobalInputUsage.AccessDetected,WordPress.Security
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && $this->post_type === $_POST['post_type'] ) {
			if ( isset( $_POST['_simple_event_organizer'] ) ) {
				$organizer = sanitize_text_field( wp_unslash( $_POST['_simple_event_organizer'] ) );
				update_post_meta( $post_id, '_simple_event_organizer', $organizer );
			}
			if ( isset( $_POST['_simple_event_email'] ) ) {
				$email = sanitize_email( wp_unslash( $_POST['_simple_event_email'] ) );
				update_post_meta( $post_id, '_simple_event_email', $email );
			}
			if ( isset( $_POST['_simple_event_time'] ) ) {
				$time = sanitize_text_field( wp_unslash( $_POST['_simple_event_time'] ) );
				update_post_meta( $post_id, '_simple_event_time', $time );
			}
			if ( isset( $_POST['_simple_event_address'] ) ) {
				$address = sanitize_text_field( wp_unslash( $_POST['_simple_event_address'] ) );
				update_post_meta( $post_id, '_simple_event_address', $address );
			}
			if ( isset( $_POST['_simple_event_latitude'] ) ) {
				$latitude = sanitize_text_field( wp_unslash( $_POST['_simple_event_latitude'] ) );
				update_post_meta( $post_id, '_simple_event_latitude', $latitude );
			}
			if ( isset( $_POST['_simple_event_longitude'] ) ) {
				$longitude = sanitize_text_field( wp_unslash( $_POST['_simple_event_longitude'] ) );
				update_post_meta( $post_id, '_simple_event_longitude', $longitude );
			}
		}
	}
}
