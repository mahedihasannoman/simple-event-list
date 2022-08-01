<?php
/**
 * Template for event list
 *
 * @since 1.0.0
 *
 * @package SimpleEventList
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="sel_event_list_container">
	<?php if ( empty( $events ) ) : ?>
		<p><?php echo esc_html__( 'No events found!', 'simple-event-list' ); ?></p>
	<?php else : ?>
		<ul class="sel_event_list">
			<?php foreach ( $events as $event ) : ?>
				<?php include SIMPLE_EVENT_LIST_ABSPATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'single-event.php'; ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
