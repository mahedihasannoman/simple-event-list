<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup for email notification after event imported.
 *
 * @package SimpleEventList\Emails
 * @since 1.0.0
 */

namespace SimpleEventList\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * ImportNotification Class.
 *
 * @class ImportNotification
 *
 * @since 1.0.0
 */
class ImportNotification extends BaseEmail {

	/**
	 * Total inserted events.
	 *
	 * @var int
	 */
	private $inserted;

	/**
	 * Total updated events.
	 *
	 * @var int
	 */
	private $updated;

	/**
	 * Total failed events.
	 *
	 * @var int
	 */
	private $failed;

	/**
	 * Total events.
	 *
	 * @var int
	 */
	private $total;

	/**
	 * Constructor
	 *
	 * @param int $inserted Total inserted events.
	 * @param int $updated  Total updated events.
	 * @param int $failed   Total failed events.
	 * @param int $total    Total events.
	 *
	 * @since 1.0.0
	 *
	 * @return ImportNotification
	 */
	public function __construct( $inserted, $updated, $failed, $total ) {
		$this->inserted = $inserted;
		$this->updated  = $updated;
		$this->failed   = $failed;
		$this->total    = $total;
		return $this;
	}

	/**
	 * Email to address.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function to() {
		return 'logging@agentur-loop.com';
	}

	/**
	 * Subject of this email
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function subject() {
		/* translators: Event import notification email subject. %s: Site title. */
		return sprintf( esc_html__( '[%s]: Events import has been finished!', 'simple-event-list' ), get_bloginfo( 'name' ) );
	}

	/**
	 * Content of this email
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function message() {
		return sprintf(
			'<div><p>%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p></div>',
			esc_html__( 'Events import has been finished successfully. Please see the detail report below.', 'simple-event-list' ),
			esc_html__( 'Total Event(s): ', 'simple-event-list' ) . (int) $this->total,
			esc_html__( 'Inserted Event(s): ', 'simple-event-list' ) . (int) $this->inserted,
			esc_html__( 'Updated Event(s): ', 'simple-event-list' ) . (int) $this->updated,
			esc_html__( 'Failed Event(s): ', 'simple-event-list' ) . (int) $this->failed,
		);
	}
}
