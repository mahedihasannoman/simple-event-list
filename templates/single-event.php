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

		<div class="sel_event_meta_data">
			<p><b><?php echo esc_html__( 'Organizer', 'simple-event-list' ); ?></b>: <?php echo esc_html( $event['organizer'] ); ?></p>
			<p><b><?php echo esc_html__( 'Email', 'simple-event-list' ); ?></b>: <a href="mailto:<?php echo esc_attr( $event['email'] ); ?>"><?php echo esc_html( $event['email'] ); ?></a></p>
			<p><b><?php echo esc_html__( 'Address', 'simple-event-list' ); ?></b>: <?php echo esc_html( $event['address'] ); ?></p>
			<p><b><?php echo esc_html__( 'Latitude', 'simple-event-list' ); ?></b>: <?php echo esc_html( $event['latitude'] ); ?></p>
			<p ><b><?php echo esc_html__( 'Longitude', 'simple-event-list' ); ?></b>: <?php echo esc_html( $event['longitude'] ); ?></p>
			<p><b><?php echo esc_html__( 'Time', 'simple-event-list' ); ?></b>: <?php echo esc_html( sel_relative_time_from_timestamp( $event['timestamp'] ) ); ?></p>
		</div>

		<div class="sel_event_tags">
			<?php if ( ! empty( $event['tags'] ) ) : ?>
				<?php foreach ( $event['tags'] as $event_tag ) : ?>
					<span><?php echo esc_html( $event_tag ); ?></span>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	</div>

</li>
