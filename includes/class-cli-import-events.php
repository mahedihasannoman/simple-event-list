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
				$events = json_decode( $events, true );

				$inserted = 0;
				$updated  = 0;
				$failed   = 0;
				foreach ( $events as $event ) {

					// Check if event is already exists.
					$post_id = sel_get_post_id( $event['id'] );
					if ( ! is_null( $post_id ) ) {
						// Update the event.
						$result = sel_update_event( $post_id, $event );
						if ( $result ) {
							$updated++;
							// translators: %d: Event ID.
							\WP_CLI::log( sprintf( esc_html__( 'Event ID: %d updated!', 'simple-event-list' ), (int) $event['id'] ) );
						} else {
							$failed++;
							// translators: %d: Event ID.
							\WP_CLI::log( sprintf( esc_html__( 'Event ID: %d update failed.', 'simple-event-list' ), (int) $event['id'] ) );
						}
					} else {
						// Insert event.
						$result = sel_insert_event( $event );
						if ( $result ) {
							$inserted++;
							// translators: %d: Event ID.
							\WP_CLI::log( sprintf( esc_html__( 'Event ID: %d inserted!', 'simple-event-list' ), (int) $event['id'] ) );
						} else {
							$failed++;
							// translators: %d: Event ID.
							\WP_CLI::log( sprintf( esc_html__( 'Event ID: %d insert failed.', 'simple-event-list' ), (int) $event['id'] ) );
						}
					}
				}
				// translators: %d: event inserted or updated or failed.
				\WP_CLI::success( sprintf( esc_html__( '%1$d event(s) are inserted, %2$d event(s) are updated, and %3$d event(s) failed to insert or update!', 'simple-event-list' ), $inserted, $updated, $failed ) );

			} else {
				\WP_CLI::error( esc_html__( 'file_get_contents function does not exists. Unable to import events.', 'simple-event-list' ), $exit = true );
			}
		} else {
			\WP_CLI::error( esc_html__( 'Source not found. Unable to import events', 'simple-event-list' ), $exit = true );
		}

	}
}
