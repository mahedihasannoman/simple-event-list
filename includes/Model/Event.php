<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Event class.
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList\Model;

use SimpleEventList\PostTypes\SampleEvent;

defined( 'ABSPATH' ) || exit;

/**
 * Event Class.
 *
 * @class Event
 *
 * @since 1.0.0
 */
class Event {

	/**
	 * ID of the event item.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $id;

	/**
	 * ID of the event item in CPT
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $p_id = 0;

	/**
	 * Title of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $title;

	/**
	 * About of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $about;

	/**
	 * Organizer of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $organizer;

	/**
	 * Timestamp of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $timestamp;

	/**
	 * Email of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $email;

	/**
	 * Address of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $address;

	/**
	 * Latitude of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $latitude;

	/**
	 * Longitude of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $longitude;

	/**
	 * Tags of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $tags;

	/**
	 * Status of the event item
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $status;

	/**
	 * Constructor method.
	 *
	 * @since 1.0.0
	 *
	 * @param int|bool $p_id Optional. The ID of a specific event item.
	 */
	public function __construct( $p_id = false ) {
		if ( ! empty( $p_id ) ) {
			$this->p_id = (int) $p_id;
			$this->populate();
		}
	}

	/**
	 * Populate the object with data about the specific event item.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function populate() {
		$post = get_post( $this->p_id );

		$this->id        = get_post_meta( $post->ID, '_simple_event_id', true );
		$this->title     = $post->post_title;
		$this->about     = $post->post_content;
		$this->organizer = get_post_meta( $post->ID, '_simple_event_organizer', true );
		$this->timestamp = get_post_meta( $post->ID, '_simple_event_time', true );
		$this->email     = get_post_meta( $post->ID, '_simple_event_email', true );
		$this->address   = get_post_meta( $post->ID, '_simple_event_address', true );
		$this->latitude  = get_post_meta( $post->ID, '_simple_event_latitude', true );
		$this->longitude = get_post_meta( $post->ID, '_simple_event_longitude', true );
		$this->tags      = $this->get_tags( $post->ID );
	}

	/**
	 * Save the event
	 *
	 * @since 1.0.0
	 *
	 * @return bool True on success.
	 */
	public function save() {
		$event_args = array(
			'post_title'   => sanitize_text_field( $this->title ),
			'post_type'    => SampleEvent::post_type(),
			'post_status'  => $this->post_status( $this->timestamp ),
			'post_content' => wp_kses_post( $this->about ),
			'meta_input'   => array(
				'_simple_event_id'        => sanitize_text_field( $this->id ),
				'_simple_event_organizer' => sanitize_text_field( $this->organizer ),
				'_simple_event_email'     => sanitize_email( $this->email ),
				'_simple_event_time'      => sanitize_text_field( $this->timestamp ),
				'_simple_event_address'   => sanitize_text_field( $this->address ),
				'_simple_event_latitude'  => sanitize_text_field( $this->latitude ),
				'_simple_event_longitude' => sanitize_text_field( $this->longitude ),
			),
		);

		if ( 0 !== $this->p_id ) {
			// Update the event.
			$event_args['ID'] = $this->p_id;
			$event_id         = wp_update_post( $event_args );
		} else {
			// Insert the event.
			$event_id = wp_insert_post( $event_args );
		}

		if ( ! is_wp_error( $event_id ) ) {
			wp_set_object_terms( $event_id, sel_recursive_sanitize_text_field( $this->tags ), SampleEvent::taxonomy() );
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Get event cpt id, as specified by event id.
	 *
	 * @param int $event_id Event id.
	 *
	 * @return int|NULL $cpt_id Event cpt id.
	 */
	public static function get_ctp_id( $event_id ) {
		global $wpdb;

		$cache_key = 'simple_event_db_result_' . $event_id;
		$post_id   = wp_cache_get( $cache_key );
		if ( false === $post_id ) {
			$post_id = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$wpdb->prepare(
					"SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key` = '_simple_event_id' AND `meta_value` = %d",
					(int) $event_id
				)
			);
			wp_cache_set( $cache_key, $post_id );
		}

		return $post_id;
	}

	/**
	 * Get the post status by timestamp
	 *
	 * @param string $timestamp Timestamp of the event.
	 *
	 * @since 1.0.0
	 *
	 * @return string $status Event cpt status
	 */
	private function post_status( $timestamp ) {
		$status = 'draft';
		if ( strtotime( $timestamp ) > strtotime( current_time( 'mysql' ) ) ) {
			$status = 'publish';
		}
		return $status;
	}

	/**
	 * Get All events
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_all() {
		$args  = array(
			'post_type'      => SampleEvent::post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => '_simple_event_time', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
		);
		$query = new \WP_Query( $args );

		$events = array();

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				$events[] = array(
					'id'        => get_post_meta( $post->ID, '_simple_event_id', true ),
					'title'     => $post->post_title,
					'about'     => $post->post_content,
					'organizer' => get_post_meta( $post->ID, '_simple_event_organizer', true ),
					'timestamp' => get_post_meta( $post->ID, '_simple_event_time', true ),
					'email'     => get_post_meta( $post->ID, '_simple_event_email', true ),
					'address'   => get_post_meta( $post->ID, '_simple_event_address', true ),
					'latitude'  => get_post_meta( $post->ID, '_simple_event_latitude', true ),
					'longitude' => get_post_meta( $post->ID, '_simple_event_longitude', true ),
					'tags'      => wp_get_object_terms( $post->ID, SampleEvent::taxonomy(), array( 'fields' => 'slugs' ) ),
				);
			}
		}
		return $events;
	}

	/**
	 * Get the event tags
	 *
	 * @param int $post_id Event post id.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_tags( $post_id ) {
		return wp_get_object_terms( $post_id, SampleEvent::taxonomy(), array( 'fields' => 'slugs' ) );
	}

}
