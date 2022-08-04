<?php
/**
 * Template for the Help page.
 *
 * @package SimpleEventList
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="wrap"> 
	<div class="sel-menu-page-content">

		<h1><?php echo esc_html__( 'Simple Event List', 'simple-event-list' ); ?></h1>
		<p class="desc"><?php echo esc_html__( 'A Very simple event listing plugin for WordPress.', 'simple-event-list' ); ?></p>

		<div class="sel-menu-page-row">
			<h2><?php echo esc_html__( 'Import Data', 'simple-event-list' ); ?></h2>
			<p>
				<?php
				echo sprintf(
					'%s <span>%s</span>',
					esc_html__( 'To import data, you can use this WordPress CLI command:', 'simple-event-list' ),
					'wp simple-events import'
				);
				?>
			</p>
		</div>
		<hr />
		<div class="sel-menu-page-row">
			<h2><?php echo esc_html__( 'Show Data', 'simple-event-list' ); ?></h2>
			<p>
				<?php
				echo sprintf(
					'%s <span>%s</span>',
					esc_html__( 'To show data, You can use the following shortcode to display the event list on a page or post:', 'simple-event-list' ),
					'[simple-events]'
				);
				?>
			</p>
		</div>
		<hr />
		<div class="sel-menu-page-row">
			<h2><?php echo esc_html__( 'Export Data', 'simple-event-list' ); ?></h2>
			<p>
				<?php
				echo sprintf(
					'%s <span>%s</span>',
					esc_html__( 'To export data, you can browse this API endpoint:', 'simple-event-list' ),
					'{GET} /wp-json/simple-event-list/v2/events'
				);
				?>
			<br>
			<?php
			echo sprintf(
				'%1$s <a href="%2$s" target="_blank">%2$s</a>',
				esc_html__( 'See the full REST URL:', 'simple-event-list' ),
				esc_url_raw(
					get_rest_url( null, "{$GLOBALS['simple_event_list']->slug}/{$GLOBALS['simple_event_list']->rest_version}/events" )
				)
			);
			?>
			</p>
		</div>

	</div>
</div>
