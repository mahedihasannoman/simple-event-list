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
 * SE_Setup_Metaboxes Class.
 *
 * @class SimpleEventList
 *
 * @since 1.0.0
 */
class SE_Setup_Metaboxes {

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
	 * Constructor for SE_Setup_Metaboxes.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type custom post type for simple event.
	 */
	public function __construct( $post_type = 'simple-events' ) {
		$this->post_type = $post_type;
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	/**
	 * Add Meta boxes for the custom post type
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_meta_boxes() {

		$metaboxes = array(
			'organizer' => array(
				'name'     => __( 'Organizer', 'simple-event-list' ),
				'callback' => array( $this, 'render_organizer_field' ),
			),
			'email'     => array(
				'name'     => __( 'Email', 'simple-event-list' ),
				'callback' => array( $this, 'render_email_field' ),
			),
			'timestamp' => array(
				'name'     => __( 'Time', 'simple-event-list' ),
				'callback' => array( $this, 'render_time_field' ),
			),
			'address'   => array(
				'name'     => __( 'Address', 'simple-event-list' ),
				'callback' => array( $this, 'render_address_field' ),
			),
			'latitude'  => array(
				'name'     => __( 'Latitude', 'simple-event-list' ),
				'callback' => array( $this, 'render_latitude_field' ),
			),
			'longitude' => array(
				'name'     => __( 'Longitude', 'simple-event-list' ),
				'callback' => array( $this, 'render_longitude_field' ),
			),
		);

		foreach ( $metaboxes as $key => $metabox ) {
			add_meta_box( 'simple-event-' . $key, $metabox['name'], $metabox['callback'], $this->post_type, 'side', 'high' );
		}

	}

	/**
	 * Render organizer field
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The object for the current post/page.
	 *
	 * @return void
	 */
	public function render_organizer_field( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce );
		$value = get_post_meta( $post->ID, '_simple_event_organizer', true );
		echo '<input type="text" name="_simple_event_organizer" value="' . esc_attr( $value ) . '" >';
	}

	/**
	 * Render email field
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The object for the current post/page.
	 *
	 * @return void
	 */
	public function render_email_field( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce );
		$value = get_post_meta( $post->ID, '_simple_event_email', true );
		echo '<input type="email" name="_simple_event_email" value="' . esc_attr( $value ) . '" >';
	}

	/**
	 * Render time field
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The object for the current post/page.
	 *
	 * @return void
	 */
	public function render_time_field( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce );
		$value = get_post_meta( $post->ID, '_simple_event_time', true );
		echo '<input type="text" name="_simple_event_time" value="' . esc_attr( $value ) . '" >';
	}

	/**
	 * Render address field
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The object for the current post/page.
	 *
	 * @return void
	 */
	public function render_address_field( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce );
		$value = get_post_meta( $post->ID, '_simple_event_address', true );
		echo '<textarea name="_simple_event_address"> ' . esc_attr( $value ) . ' </textarea>';
	}

	/**
	 * Render latitude field
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The object for the current post/page.
	 *
	 * @return void
	 */
	public function render_latitude_field( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce );
		$value = get_post_meta( $post->ID, '_simple_event_latitude', true );
		echo '<input type="text" name="_simple_event_latitude" value="' . esc_attr( $value ) . '" >';
	}

	/**
	 * Render longitude field
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The object for the current post/page.
	 *
	 * @return void
	 */
	public function render_longitude_field( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce );
		$value = get_post_meta( $post->ID, '_simple_event_longitude', true );
		echo '<input type="text" name="_simple_event_longitude" value="' . esc_attr( $value ) . '" >';
	}
}
