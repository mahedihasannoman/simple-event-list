<?php
/**
 * Simple Event List Functions.
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Insert an event
 *
 * @param array $event {
 *     Array of arguments.
 *     @type int    $id         ID of the event.
 *     @type string $title      Title of the event.
 *     @type string $about      Event details.
 *     @type string $organizer  Organizer of this event.
 *     @type string $timestamp  Time of this event.
 *     @type string $email      Organizer/event email address.
 *     @type string $address    Address of this event.
 *     @type string $latitude   Latitude of event.
 *     @type string $longitude  Longitude of event.
 *     @type array  $tags       Array of tags.
 * }
 *
 * @since 1.0.0
 *
 * @return mixed $event_id Event id or false.
 */
function sel_insert_event( $event ) {

	$defaults = array(
		'id'        => '',
		'title'     => '',
		'about'     => '',
		'organizer' => '',
		'timestamp' => '1992-10-07 12:13:01',
		'email'     => '',
		'address'   => '',
		'latitude'  => '',
		'longitude' => '',
		'tags'      => array(),
	);

	$args = wp_parse_args( $event, $defaults );

	$event_args = array(
		'post_title'   => sanitize_text_field( $args['title'] ),
		'post_type'    => sel_post_type(),
		'post_staus'   => sel_post_status( $args['timestamp'] ),
		'post_content' => wp_kses_post( $args['about'] ),
		'meta_input'   => array(
			'_simple_event_id'        => sanitize_text_field( $args['id'] ),
			'_simple_event_organizer' => sanitize_text_field( $args['organizer'] ),
			'_simple_event_email'     => sanitize_email( $args['email'] ),
			'_simple_event_time'      => sanitize_text_field( $args['timestamp'] ),
			'_simple_event_address'   => sanitize_text_field( $args['address'] ),
			'_simple_event_latitude'  => sanitize_text_field( $args['latitude'] ),
			'_simple_event_longitude' => sanitize_text_field( $args['longitude'] ),
		),
	);

	$event_id = wp_insert_post( $event_args );

	if ( ! is_wp_error( $event_id ) ) {
		wp_set_object_terms( $event_id, sel_recursive_sanitize_text_field( $args['tags'] ), sel_taxonomy() );
		if ( 'publish' === sel_post_status( $args['timestamp'] ) ) {
			wp_publish_post( $event_id );
		}
	} else {
		return false;
	}

	return $event_id;
}

/**
 * Update an event
 *
 * @param int   $id ID of the event.
 *
 * @param array $event {
 *     Array of arguments.
 *     @type int    $id         ID of the event.
 *     @type string $title      Title of the event.
 *     @type string $about      Event details.
 *     @type string $organizer  Organizer of this event.
 *     @type string $timestamp  Time of this event.
 *     @type string $email      Organizer/event email address.
 *     @type string $address    Address of this event.
 *     @type string $latitude   Latitude of event.
 *     @type string $longitude  Longitude of event.
 *     @type array  $tags       Array of tags.
 * }
 *
 * @since 1.0.0
 *
 * @return mixed $event_id Event id or false.
 */
function sel_update_event( $id, $event ) {

	$defaults = array(
		'id'        => '',
		'title'     => '',
		'about'     => '',
		'organizer' => '',
		'timestamp' => '1992-10-07 12:13:01',
		'email'     => '',
		'address'   => '',
		'latitude'  => '',
		'longitude' => '',
		'tags'      => array(),
	);

	$args = wp_parse_args( $event, $defaults );

	$event_args = array(
		'ID'           => $id,
		'post_title'   => sanitize_text_field( $args['title'] ),
		'post_type'    => sel_post_type(),
		'post_staus'   => sel_post_status( $args['timestamp'] ),
		'post_content' => wp_kses_post( $args['about'] ),
		'meta_input'   => array(
			'_simple_event_id'        => sanitize_text_field( $args['id'] ),
			'_simple_event_organizer' => sanitize_text_field( $args['organizer'] ),
			'_simple_event_email'     => sanitize_email( $args['email'] ),
			'_simple_event_time'      => sanitize_text_field( $args['timestamp'] ),
			'_simple_event_address'   => sanitize_text_field( $args['address'] ),
			'_simple_event_latitude'  => sanitize_text_field( $args['latitude'] ),
			'_simple_event_longitude' => sanitize_text_field( $args['longitude'] ),
		),
	);

	$event_id = wp_update_post( $event_args );
	if ( ! is_wp_error( $event_id ) ) {
		wp_set_object_terms( $event_id, sel_recursive_sanitize_text_field( $args['tags'] ), sel_taxonomy() );
		if ( 'publish' === sel_post_status( $args['timestamp'] ) ) {
			wp_publish_post( $event_id );
		}
	} else {
		return false;
	}

	return $event_id;
}

/**
 * Get post ID by Event ID.
 *
 * @param int $event_id Event ID.
 *
 * @return int|NULL $post_id Post ID or NULL.
 */
function sel_get_post_id( $event_id ) {
	global $wpdb;

	$cache_key = 'simple_event_db_result_' . $event_id;
	$post_id   = wp_cache_get( $cache_key );
	if ( false === $post_id ) {
		$post_id = $wpdb->get_var( // phpcs:ignore WordPress.Sniffs.DB.DirectDatabaseQuery
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
 * Return the post status depending on timestamp
 *
 * @param string $timestamp Event timestamp.
 *
 * @return string $status Status of the event.
 */
function sel_post_status( $timestamp ) {
	$status = 'draft';
	if ( strtotime( $timestamp ) > strtotime( current_time( 'mysql' ) ) ) {
		$status = 'publish';
	}
	return $status;
}

/**
 * Custom post type for events
 *
 * @since 1.0.0
 *
 * @return string $post_type Post type for events.
 */
function sel_post_type() {
	return 'simple-event';
}

/**
 * Taxonomy for simple event
 *
 * @since 1.0.0
 *
 * @return string $taxonomy Taxonomy for event.
 */
function sel_taxonomy() {
	return 'event-tag';
}

/**
 * Recursive sanitation for an array.
 *
 * @param array $array Array of data to sanitize.
 *
 * @since 1.0.0
 *
 * @return array $array Array of data.
 */
function sel_recursive_sanitize_text_field( $array ) {
	foreach ( $array as $key => &$value ) {
		if ( is_array( $value ) ) {
			$value = sel_recursive_sanitize_text_field( $value );
		} else {
			$value = sanitize_text_field( $value );
		}
	}
	return $array;
}
