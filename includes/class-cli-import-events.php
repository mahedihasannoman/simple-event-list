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
		$source_path = SIMPLE_EVENT_LIST_ABSPATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'events.json';
		$source_url  = SIMPLE_EVENT_LIST_PLUGIN_URL . 'data/events.json';
		if ( file_exists( $source_path ) ) {
			try {
				$response = wp_remote_get( $source_url );

				if ( is_wp_error( $response ) ) {
					\WP_CLI::error( $response->get_error_message(), $exit = true );
				}

				if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
					$events = json_decode( $response['body'], true );
					if ( json_last_error() === JSON_ERROR_NONE ) {
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

						// Send email notification.
						sel_send_import_notification( $inserted, $updated, $failed, count( $events ) );

						// translators: %d: event inserted or updated or failed.
						\WP_CLI::success( sprintf( esc_html__( '%1$d event(s) are inserted, %2$d event(s) are updated, and %3$d event(s) failed to insert or update!', 'simple-event-list' ), $inserted, $updated, $failed ) );

					} else {
						\WP_CLI::error( json_last_error_msg(), $exit = true );
					}
				} else {
					\WP_CLI::error( $response['response']['message'], $exit = true );
				}
			} catch ( \Exception $ex ) {
				\WP_CLI::error( $ex->getMessage(), $exit = true );
			}
		} else {
			\WP_CLI::error( esc_html__( 'Source not found. Unable to import events', 'simple-event-list' ), $exit = true );
		}

	}
}
