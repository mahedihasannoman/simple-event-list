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
			<p><?php echo esc_html__( 'To import data, you can use this WordPress CLI command: wp simple-events import', 'simple-event-list' ); ?></p>
		</div>
		<hr />
		<div class="sel-menu-page-row">
			<h2><?php echo esc_html__( 'Show Data', 'simple-event-list' ); ?></h2>
			<p><?php echo esc_html__( 'To show data, You can use the following shortcode to display the event list on a page or post: [simple-events]', 'simple-event-list' ); ?></p>
		</div>
		<hr />
		<div class="sel-menu-page-row">
			<h2><?php echo esc_html__( 'Export Data', 'simple-event-list' ); ?></h2>
			<p><?php echo esc_html__( 'To export data, you can browse this API endpoint: {GET} /wp-json/simple-event-list/v2/events/', 'simple-event-list' ); ?></p>
		</div>

	</div>
</div>
