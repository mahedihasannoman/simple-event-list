<?php
/**
 * Template for event list
 *
 * @since 1.0.0
 *
 * @package SimpleEventList
 */

defined( 'ABSPATH' ) || exit; ?>

<li class="sel_single_event">
	<div class="sel_event_content">
		<h3 class="sel_event_title"><?php echo esc_html( $event['title'] ); ?></h3>
		<p class="sel_event_description"><?php echo wp_kses_post( $event['about'] ); ?></p>
		<p class="sel_event_meta_data"><b><?php echo esc_html__( 'Organizer', 'simple-event-list' ); ?></b>: <?php echo esc_html( $event['organizer'] ); ?></p>
		<p class="sel_event_meta_data"><b><?php echo esc_html__( 'Email', 'simple-event-list' ); ?></b>: <a href="mailto:<?php echo esc_attr( $event['email'] ); ?>"><?php echo esc_html( $event['email'] ); ?></a></p>
		<p class="sel_event_meta_data"><b><?php echo esc_html__( 'Address', 'simple-event-list' ); ?></b>: <?php echo esc_html( $event['address'] ); ?></p>
		<p class="sel_event_meta_data"><b><?php echo esc_html__( 'Time Remaining', 'simple-event-list' ); ?></b>: <?php echo esc_html( sel_relative_time_from_timestamp( $event['timestamp'] ) ); ?></p>
	</div>
</li>
