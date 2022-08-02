<?php
/**
 * Simple Event List Functions.
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use SimpleEventList\Model\Event;

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
 * @return bool True on success.
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

	$event = new Event();

	$event->id        = $args['id'];
	$event->title     = $args['title'];
	$event->organizer = $args['organizer'];
	$event->about     = $args['about'];
	$event->timestamp = $args['timestamp'];
	$event->email     = $args['email'];
	$event->address   = $args['address'];
	$event->latitude  = $args['latitude'];
	$event->longitude = $args['longitude'];
	$event->tags      = $args['tags'];

	return $event->save();
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
 * @return bool True on success.
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

	$event = new Event( $id );

	$event->id        = $args['id'];
	$event->title     = $args['title'];
	$event->organizer = $args['organizer'];
	$event->about     = $args['about'];
	$event->timestamp = $args['timestamp'];
	$event->email     = $args['email'];
	$event->address   = $args['address'];
	$event->latitude  = $args['latitude'];
	$event->longitude = $args['longitude'];
	$event->tags      = $args['tags'];

	return $event->save();
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

/**
 * Send email notification for import
 *
 * @param int $inserted Total inserted events.
 * @param int $updated  Total updated events.
 * @param int $failed   Total failed events.
 * @param int $total    Total events.
 *
 * @since 1.0.0
 * @return void
 */
function sel_send_import_notification( $inserted, $updated, $failed, $total ) {

	$to = 'logging@agentur-loop.com';

	/* translators: Password change notification email subject. %s: Site title. */
	$subject = sprintf( esc_html__( '[%s]: Events import has been finished!', 'simple-event-list' ), get_bloginfo( 'name' ) );
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );

	$message = sprintf(
		'<div><p>%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p></div>',
		esc_html__( 'Events import has been finished successfully. Please see the detail report below.', 'simple-event-list' ),
		esc_html__( 'Total Event(s): ', 'simple-event-list' ) . (int) $total,
		esc_html__( 'Inserted Event(s): ', 'simple-event-list' ) . (int) $inserted,
		esc_html__( 'Updated Event(s): ', 'simple-event-list' ) . (int) $updated,
		esc_html__( 'Failed Event(s): ', 'simple-event-list' ) . (int) $failed,
	);

	wp_mail( $to, $subject, $message, $headers );
}

/**
 * Convert timestamp to relative time.
 *
 * @param string $timestamp Timestamp.
 *
 * @since 1.0.0
 *
 * @return string
 */
function sel_relative_time_from_timestamp( $timestamp ) {
	// Bail if no timestamp.
	if ( '' === $timestamp ) {
		return '';
	}

	$seconds_per_minute = 60;
	$seconds_per_hour   = 3600;
	$seconds_per_day    = 86400;
	$seconds_per_month  = 2592000;
	$seconds_per_year   = 31104000;
	$time               = strtotime( $timestamp );
	$current_time       = time();

	// creates the "remaining time" string.
	$time_remaining = '';

	// finds the time difference.
	$time_difference = $current_time - $time;

	$is_future = false;
	// Check if the timestamp is future timestamp.
	if ( $time_difference < 0 ) {
		$is_future       = true;
		$time_difference = abs( $time_difference );
	}

	// less than 29secs.
	if ( $time_difference <= 29 ) {

		$time_remaining = __( 'less than a minute', 'simple-event-list' );

	} elseif ( $time_difference > 29 && $time_difference <= 89 ) {

		// more than 29secs and less than 1min29secss.
		$time_remaining = __( '1 minute', 'simple-event-list' );

	} elseif (
		$time_difference > 89 &&
		$time_difference <= (
			( $seconds_per_minute * 44 ) + 29
		)
	) {
		// between 1min30secs and 44mins29secs.
		$minutes = floor( $time_difference / $seconds_per_minute );
		// translators: %d: Minutes.
		$time_remaining = sprintf( __( '%d minutes', 'simple-event-list' ), $minutes );
	} elseif (
		$time_difference > (
			( $seconds_per_minute * 44 ) + 29
		)
		&&
		$time_difference < (
			( $seconds_per_minute * 89 ) + 29
		)
	) {
		// between 44mins30secs and 1hour29mins29secs.
		$time_remaining = __( '1 hour', 'simple-event-list' );
	} elseif (
		$time_difference > (
			( $seconds_per_minute * 89 ) + 29
		)
		&&
		$time_difference <= (
			( $seconds_per_hour * 23 ) +
			( $seconds_per_minute * 59 ) + 29
		)
	) {
		// between 1hour29mins30secs and 23hours59mins29secs.
		$hours = floor( $time_difference / $seconds_per_hour );
		// translators: %d: Hours.
		$time_remaining = sprintf( __( '%d hours', 'simple-event-list' ), $hours );
	} elseif (
		$time_difference > (
			( $seconds_per_hour * 23 ) +
			( $seconds_per_minute * 59 ) + 29
		)
		&&
		$time_difference <= (
			( $seconds_per_hour * 47 ) +
			( $seconds_per_minute * 59 ) + 29
		)
	) {
		// between 23hours59mins30secs and 47hours59mins29secs.
		$time_remaining = __( '1 day', 'simple-event-list' );
	} elseif (
		$time_difference > (
			( $seconds_per_hour * 47 ) +
			( $seconds_per_minute * 59 ) + 29
		)
		&&
		$time_difference <= (
			( $seconds_per_day * 29 ) +
			( $seconds_per_hour * 23 ) +
			( $seconds_per_minute * 59 ) + 29
		)
	) {
		// between 47hours59mins30secs and 29days23hours59mins29secs.
		$days = floor( $time_difference / $seconds_per_day );
		// translators: %d: Days.
		$time_remaining = sprintf( __( '%d days', 'simple-event-list' ), $days );
	} elseif (
		$time_difference > (
			( $seconds_per_day * 29 ) +
			( $seconds_per_hour * 23 ) +
			( $seconds_per_minute * 59 ) + 29
		)
		&&
		$time_difference <= (
			( $seconds_per_day * 59 ) +
			( $seconds_per_hour * 23 ) +
			( $seconds_per_minute * 59 ) + 29
		)
	) {
		// between 29days23hours59mins30secs and 59days23hours59mins29secs.
		$time_remaining = __( '1 month', 'simple-event-list' );
	} elseif (
		$time_difference > (
			( $seconds_per_day * 59 ) +
			( $seconds_per_hour * 23 ) +
			( $seconds_per_minute * 59 ) + 29
		)
		&&
		$time_difference < $seconds_per_year
	) {
		// between 59days23hours59mins30secs and 1year (minus 1sec).
		$months = round( $time_difference / $seconds_per_month );
		// translators: %d: Months.
		$time_remaining = sprintf( __( '%d months', 'simple-event-list' ), $months );
	} elseif (
		$time_difference >= $seconds_per_year &&
		$time_difference < ( $seconds_per_year * 2 )
	) {
		// between 1year and 2years (minus 1sec).
		$time_remaining = __( '1 year', 'simple-event-list' );
	} else {
		// 2years or more.
		$years = floor( $time_difference / $seconds_per_year );
		// translators: %d: Years.
		$time_remaining = sprintf( __( '%d years', 'simple-event-list' ), $years );
	}

	if ( $is_future ) {
		// translators: %s: Relative time such as "in 20 minutes" or "in 5 days".
		return sprintf( __( 'in %s', 'simple-event-list' ), $time_remaining );
	} else {
		// translators: %s: Relative time such as "in 20 minutes" or "in 5 days".
		return sprintf( __( '%s ago', 'simple-event-list' ), $time_remaining );
	}

}
