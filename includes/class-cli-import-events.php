<?php
/**
 * Setup cli command for importing events.
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

defined( 'ABSPATH' ) || exit;

/**
 * CLI_Import_Events Class.
 *
 * @class CLI_Import_Events
 *
 * @since 1.0.0
 */
class CLI_Import_Events {

	/**
	 * Handle CLI command to import events from JSON file
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function import() {
		$source = SIMPLE_EVENT_LIST_ABSPATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'events.json';
		if ( file_exists( $source ) ) {

			// Check if file_get_contents is available.
			if ( function_exists( 'file_get_contents' ) ) {

				$events = file_get_contents( $source );
				$events = json_decode( $events );

				$inserted = 0;
				$updated  = 0;
				$failed   = 0;
				foreach ( $events as $event ) {

					$result = sel_insert_event( $event );
					if ( $result ) {
						$inserted++;
						\WP_CLI::log( 'Event ID: ' . esc_html( $event['id'] ) . ' Inserted!' );
					} else {
						$failed++;
						\WP_CLI::log( 'Event ID: ' . esc_html( $event['id'] ) . ' Failed.' );
					}
				}

				\WP_CLI::success( esc_html__( 'All events are imported correctly!', 'simple-event-list' ) );

			} else {
				\WP_CLI::error( esc_html__( 'Source not found.', 'simple-event-list' ), $exit = true );
			}
		} else {
			\WP_CLI::error( esc_html__( 'Source not found.', 'simple-event-list' ), $exit = true );
		}

	}
}
