<?php
/**
 * Template for event list
 *
 * @since 1.0.0
 *
 * @package SimpleEventList
 */

defined( 'ABSPATH' ) || exit; ?>

<li class="sel-single-event">

	<div class="sel-event-content">

		<h3 class="sel-event-title"><?php echo esc_html( $event['title'] ); ?></h3>
		<p class="sel-event-description"><?php echo wp_kses_post( $event['about'] ); ?></p>

		<div class="sel-event-meta-data">
			<p>
				<span><?php echo esc_html__( 'Organizer', 'simple-event-list' ); ?></span> 
				<?php echo esc_html( $event['organizer'] ); ?>
			</p>
			<p>
				<span><?php echo esc_html__( 'Email', 'simple-event-list' ); ?></span> 
				<a href="mailto:<?php echo esc_attr( $event['email'] ); ?>"><?php echo esc_html( $event['email'] ); ?></a>
			</p>
			<p>
				<span><?php echo esc_html__( 'Address', 'simple-event-list' ); ?></span> 
				<?php echo esc_html( $event['address'] ); ?>
			</p>
			<p>
				<span><?php echo esc_html__( 'Latitude', 'simple-event-list' ); ?></span> 
				<?php echo esc_html( $event['latitude'] ); ?>
			</p>
			<p>
				<span><?php echo esc_html__( 'Longitude', 'simple-event-list' ); ?></span> 
				<?php echo esc_html( $event['longitude'] ); ?>
			</p>
			<p>
				<span><?php echo esc_html__( 'Time', 'simple-event-list' ); ?></span> 
				<?php echo esc_html( ucfirst( sel_relative_time_from_timestamp( $event['timestamp'] ) ) ); ?>
			</p>
		</div>

		<div class="sel-event-tags">
			<span><?php echo esc_html__( 'Tags:', 'simple-event-list' ); ?></span>
			<?php if ( ! empty( $event['tags'] ) ) : ?>
				<?php foreach ( $event['tags'] as $event_tag ) : ?>
					<span><?php echo esc_html( $event_tag ); ?></span>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	</div>

</li>
