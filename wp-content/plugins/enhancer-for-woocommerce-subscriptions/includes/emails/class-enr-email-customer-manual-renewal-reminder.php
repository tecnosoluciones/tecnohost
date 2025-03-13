<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manual Renewal Reminder Email.
 *
 * An email will be sent to the customer before the subscription price is to be charged manually from the customer account.
 *
 * @class ENR_Email_Customer_Manual_Renewal_Reminder
 * @extends ENR_Abstract_Email
 */
class ENR_Email_Customer_Manual_Renewal_Reminder extends ENR_Abstract_Email {

	/**
	 * Email supports.
	 *
	 * @var array Supports
	 */
	public $supports = array( 'multiple_content' );

	/**
	 * Strings to find/replace in multiple content supported email.
	 *
	 * @var array Supports
	 */
	public $multiple_content_placeholders = array(
		'{view_subscription_url}' => '',
		'{next_payment_date}'     => '',
		'{renewal_amount}'        => '',
		'{subscription_details}'  => '',
		'{customer_addresses}'    => ''
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id             = ENR_PREFIX . 'customer_manual_renewal_reminder';
		$this->customer_email = true;
		$this->title          = __( 'Manual Renewal Reminder', 'enhancer-for-woocommerce-subscriptions' );
		$this->description    = __( 'Manual renewal reminder emails are sent to the customers(subscribers) when their manually renewing subscriptions are due for renewal.', 'enhancer-for-woocommerce-subscriptions' );
		$this->heading        = __( 'Your subscription renewal is nearing', 'enhancer-for-woocommerce-subscriptions' );
		$this->subject        = __( 'Your {blogname} subscription renewal is nearing', 'enhancer-for-woocommerce-subscriptions' );
		$this->template_html  = 'emails/customer-manual-renewal-reminder.php';
		$this->template_plain = 'emails/plain/customer-manual-renewal-reminder.php';

		add_action( 'enr_wc_subscriptions_remind_before_manual_renewal_notification', array( $this, 'trigger' ), 10, 2 );

		// Call parent constructor
		parent::__construct();
	}

	/**
	 * Get default content to show as main email content.
	 *
	 * @return string
	 */
	public function get_default_content() {
		return __( 'This email is to inform you that your Subscription {view_subscription_url} is due for renewal on {next_payment_date}. {customer_addresses}', 'enhancer-for-woocommerce-subscriptions' );
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param WC_Subscription|false $subscription Subscription object.
	 * @param int|null $day_to_remind
	 */
	public function trigger( $subscription, $day_to_remind = null ) {
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			$subscription = wcs_get_subscription( $subscription );
		}

		if ( is_a( $subscription, 'WC_Subscription' ) ) {
			$this->object            = $subscription;
			$this->recipient         = $this->object->get_billing_email();
			$this->email_template_id = is_numeric( $day_to_remind ) ? _enr_get_mapped_email_template_id( $this->id, $day_to_remind, $this->object ) : null;
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->wpml_switch_language();
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

}

return new ENR_Email_Customer_Manual_Renewal_Reminder();
