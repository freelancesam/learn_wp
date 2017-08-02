<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Email_Customer_Completed_Order', false ) ) :

/**
 * Customer Completed Order Email.
 *
 * Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.
 *
 * @class       WC_Email_Customer_Completed_Order
 * @version     2.0.0
 * @package     WooCommerce/Classes/Emails
 * @author      WooThemes
 * @extends     WC_Email
 */
class WC_Email_Customer_Completed_Order extends WC_Email {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id             = 'customer_completed_order';
		$this->customer_email = true;
<<<<<<< HEAD

		$this->title          = __( 'Completed order', 'woocommerce' );
		$this->description    = __( 'Order complete emails are sent to customers when their orders are marked completed and usually indicate that their orders have been shipped.', 'woocommerce' );

=======
		$this->title          = __( 'Completed order', 'woocommerce' );
		$this->description    = __( 'Order complete emails are sent to customers when their orders are marked completed and usually indicate that their orders have been shipped.', 'woocommerce' );

		$this->heading        = __( 'Your order is complete', 'woocommerce' );
		$this->subject        = __( 'Your {site_title} order from {order_date} is complete', 'woocommerce' );

>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		$this->template_html  = 'emails/customer-completed-order.php';
		$this->template_plain = 'emails/plain/customer-completed-order.php';

		// Triggers for this email
		add_action( 'woocommerce_order_status_completed_notification', array( $this, 'trigger' ), 10, 2 );

<<<<<<< HEAD
=======
		// Other settings
		$this->heading_downloadable = $this->get_option( 'heading_downloadable', __( 'Your order is complete - download your files', 'woocommerce' ) );
		$this->subject_downloadable = $this->get_option( 'subject_downloadable', __( 'Your {site_title} order from {order_date} is complete - download your files', 'woocommerce' ) );

>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		// Call parent constuctor
		parent::__construct();
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param int $order_id The order ID.
	 * @param WC_Order $order Order object.
	 */
	public function trigger( $order_id, $order = false ) {
		if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
			$order = wc_get_order( $order_id );
		}

		if ( is_a( $order, 'WC_Order' ) ) {
			$this->object                  = $order;
			$this->recipient               = $this->object->get_billing_email();

			$this->find['order-date']      = '{order_date}';
			$this->find['order-number']    = '{order_number}';

			$this->replace['order-date']   = wc_format_datetime( $this->object->get_date_created() );
			$this->replace['order-number'] = $this->object->get_order_number();
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

<<<<<<< HEAD
		$this->setup_locale();
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		$this->restore_locale();
=======
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Get email subject.
	 *
<<<<<<< HEAD
	 * @since  3.1.0
	 * @return string
	 */
	public function get_default_subject() {
		return __( 'Your {site_title} order from {order_date} is complete', 'woocommerce' );
=======
	 * @access public
	 * @return string
	 */
	public function get_subject() {
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() ) {
			return apply_filters( 'woocommerce_email_subject_customer_completed_order', $this->format_string( $this->subject_downloadable ), $this->object );
		} else {
			return apply_filters( 'woocommerce_email_subject_customer_completed_order', $this->format_string( $this->subject ), $this->object );
		}
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Get email heading.
	 *
<<<<<<< HEAD
	 * @since  3.1.0
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Your order is complete', 'woocommerce' );
=======
	 * @access public
	 * @return string
	 */
	public function get_heading() {
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() ) {
			return apply_filters( 'woocommerce_email_heading_customer_completed_order', $this->format_string( $this->heading_downloadable ), $this->object );
		} else {
			return apply_filters( 'woocommerce_email_heading_customer_completed_order', $this->format_string( $this->heading ), $this->object );
		}
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false,
			'email'			=> $this,
		) );
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true,
			'email'			=> $this,
		) );
	}

	/**
	 * Initialise settings form fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'         => __( 'Enable/Disable', 'woocommerce' ),
				'type'          => 'checkbox',
				'label'         => __( 'Enable this email notification', 'woocommerce' ),
				'default'       => 'yes',
			),
			'subject' => array(
				'title'         => __( 'Subject', 'woocommerce' ),
				'type'          => 'text',
<<<<<<< HEAD
				'desc_tip'      => true,
				/* translators: %s: list of placeholders */
				'description'   => sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>{site_title}, {order_date}, {order_number}</code>' ),
				'placeholder'   => $this->get_default_subject(),
				'default'       => '',
=======
				/* translators: %s: default subject */
				'description'   => sprintf( __( 'Defaults to %s', 'woocommerce' ), '<code>' . $this->subject . '</code>' ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true,
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			),
			'heading' => array(
				'title'         => __( 'Email heading', 'woocommerce' ),
				'type'          => 'text',
<<<<<<< HEAD
				'desc_tip'      => true,
				/* translators: %s: list of placeholders */
				'description'   => sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>{site_title}, {order_date}, {order_number}</code>' ),
				'placeholder'   => $this->get_default_heading(),
				'default'       => '',
=======
				/* translators: %s: default heading */
				'description'   => sprintf( __( 'Defaults to %s', 'woocommerce' ), '<code>' . $this->heading . '</code>' ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true,
			),
			'subject_downloadable' => array(
				'title'         => __( 'Subject (downloadable)', 'woocommerce' ),
				'type'          => 'text',
				/* translators: %s: default subject */
				'description'   => sprintf( __( 'Defaults to %s', 'woocommerce' ), '<code>' . $this->subject_downloadable . '</code>' ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true,
			),
			'heading_downloadable' => array(
				'title'         => __( 'Email heading (downloadable)', 'woocommerce' ),
				'type'          => 'text',
				/* translators: %s: default heading */
				'description'   => sprintf( __( 'Defaults to %s', 'woocommerce' ), '<code>' . $this->heading_downloadable . '</code>' ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true,
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			),
			'email_type' => array(
				'title'         => __( 'Email type', 'woocommerce' ),
				'type'          => 'select',
				'description'   => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'       => 'html',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_email_type_options(),
				'desc_tip'      => true,
			),
		);
	}
}

endif;

return new WC_Email_Customer_Completed_Order();
