<?php
namespace JET_APB;

/**
 * WooCommerce integration class
 */
class WC_Integration {

	private $is_enbaled             = false;
	private $product_id             = 0;
	private $price_adjusted         = false;
	public  $appointmet_product_key = '_is_jet_appointment';
	public  $data_key               = 'appointment_data';
	public  $price_key              = 'wc_appointment_price';
	public  $form_data_key          = 'appointment_form_data';
	public  $form_id_key            = 'appointment_form_id';

	/**
	 * Constructir for the class
	 */
	public function __construct() {

		if ( ! class_exists( '\WooCommerce' ) ) {
			return;
		}

		add_action( 'jet-apb/settings/before-write', array( $this, 'maybe_create_appointment_product' ) );

		$this->set_status();

		if ( ! $this->get_status() || ! $this->get_product_id() ) {
			return;
		}

		// Form-related
		add_action( 'jet-apb/form/notification/success', array( $this, 'process_wc_notifictaion' ), 10, 3 );

		// Cart-related
		add_filter( 'woocommerce_get_item_data', array( $this, 'add_formatted_cart_data' ), 10, 2 );
		add_filter( 'woocommerce_get_cart_contents', array( $this, 'set_appointment_price' ) );
		add_filter( 'woocommerce_checkout_get_value', array( $this, 'maybe_set_checkout_defaults' ), 10, 2 );

		// Order-related
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'process_order' ), 10, 3 );
		add_action( 'woocommerce_thankyou', array( $this, 'order_details' ), 0 );
		add_action( 'woocommerce_view_order', array( $this, 'order_details' ), 0 );
		add_action( 'woocommerce_email_order_meta', array( $this, 'email_order_details' ), 0, 3 );
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'admin_order_details' ) );
		add_action( 'woocommerce_order_status_changed', array( $this, 'update_status_on_order_update' ), 10, 4 );

		new WC_Order_Details_Builder();

	}

	/**
	 * Set checkout default fields values for checkout forms
	 */
	public function maybe_set_checkout_defaults( $value, $field ) {

		$fields = WC()->session->get( 'jet_apb_fields' );

		if ( ! empty( $fields ) && ! empty( $fields[ $field ] ) ) {
			return $fields[ $field ];
		} else {
			return $value;
		}

	}

	/**
	 * Returns checkout fields list
	 */
	public function get_checkout_fields() {

		if ( ! $this->get_status() ) {
			return array();
		}

		$result = array(
			'billing_first_name',
			'billing_last_name',
			'billing_email',
			'billing_phone',
			'billing_company',
			'billing_country',
			'billing_address_1',
			'billing_address_2',
			'billing_city',
			'billing_state',
			'billing_postcode',
			'shipping_first_name',
			'shipping_last_name',
			'shipping_company',
			'shipping_country',
			'shipping_address_1',
			'shipping_address_2',
			'shipping_city',
			'shipping_state',
			'shipping_postcode',
			'order_comments',
		);

		return apply_filters( 'jet-apb/wc-integration/checkout-fields', $result );

	}

	/**
	 * Update an appointment status on related order update
	 *
	 * @return [type] [description]
	 */
	public function update_status_on_order_update( $order_id, $old_status, $new_status, $order ) {
		$appointment = $this->get_appointment_by_order_id( $order_id );

		if ( ! $appointment ) {
			return;
		}

		$this->set_order_data( $appointment, $order_id, $order );

		if ( in_array( $new_status, Plugin::instance()->statuses->invalid_statuses() ) ) {
			Plugin::instance()->db->remove_appointment_date_from_excluded( $appointment );
		}

		if ( in_array( $old_status, Plugin::instance()->statuses->invalid_statuses() ) && in_array( $new_status, Plugin::instance()->statuses->valid_statuses() ) ) {
			Plugin::instance()->db->maybe_exclude_appointment_date( $appointment );
		}
	}

	/**
	 * Process new order creation
	 *
	 * @param  [type] $order [description]
	 * @param  [type] $data  [description]
	 * @return [type]        [description]
	 */
	public function process_order( $order_id, $data, $order ) {

		$cart = WC()->cart->get_cart_contents();

		foreach ( $cart as $item ) {
			if ( ! empty( $item[ $this->data_key ] ) ) {
				$this->set_order_data(
					$item[ $this->data_key ],
					$order_id,
					$order,
					$item
				);
			}
		}
	}

	/**
	 * Setup order data
	 */
	public function set_order_data( $app_data, $order_id, $order, $cart_item = array() ) {
		$service  = ! empty( $app_data['service'] ) ? absint( $app_data['service'] ) : false;
		$provider = ! empty( $app_data['provider'] ) ? absint( $app_data['provider'] ) : false;
		$date     = ! empty( $app_data['date'] ) ? absint( $app_data['date'] ) : false;
		$slot     = ! empty( $app_data['slot'] ) ? absint( $app_data['slot'] ) : false;
		$slot_end = ! empty( $app_data['slot_end'] ) ? absint( $app_data['slot_end'] ) : false;
		$id       = ! empty( $app_data['ID'] ) ? absint( $app_data['ID'] ) : false;

		if ( ! $service || ! $date || ! $slot ) {
			return;
		}

		$where = array(
			'service' => $service,
			'date'    => $date,
			'slot_end'=> $slot_end,
			'slot'    => $slot,
		);

		if ( $id ) {
			$where['ID'] = $id;
		}

		if ( $provider ) {
			$where['provider'] = $provider;
		}

		Plugin::instance()->db->appointments->update( array(
			'order_id' => $order_id,
			'status'   => $order->get_status(),
		), $where );

		do_action( 'jet-appointment/wc-integration/process-order', $order_id, $order, $cart_item );
	}

	/**
	 * Set custom price per appointemnt
	 *
	 * @param [type] $cart [description]
	 */
	public function set_appointment_price( $cart_items ) {

		if ( $this->price_adjusted ) {
			return $cart_items;
		}

		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $item ) {
				if ( ! empty( $item[ $this->data_key ] ) ) {

					if ( ! empty( $item[ $this->price_key ] ) ) {
						$price = $item[ $this->price_key ];
					} else {

						$data    = $item[ $this->data_key ];
						$service = ! empty( $data['service'] ) ? $data['service'] : 0;
						$price   = get_post_meta( $service, '_app_price', true );

					}

					if ( $price ) {
						$item['data']->set_price( floatval( $price ) );
					}

					$this->price_adjusted = true;
				}
			}
		}

		return $cart_items;

	}

	/**
	 * Add appointment infor,ation into cart meta data
	 *
	 * @param [type] $item_data [description]
	 * @param [type] $cart_item [description]
	 */
	public function add_formatted_cart_data( $item_data, $cart_item ) {

		if ( ! empty( $cart_item[ $this->data_key ] ) ) {
			$item_data = array_merge(
				$item_data,
				$this->get_formatted_appointment_info(
					$cart_item[ $this->data_key ],
					$cart_item[ $this->form_data_key ],
					$cart_item[ $this->form_id_key ]
				)
			);
		}

		return $item_data;

	}

	/**
	 * Show appointment-related order details on order page
	 *
	 * @param  [type] $order_id [description]
	 * @return [type]           [description]
	 */
	public function order_details_template( $order_id, $template = 'order-details' ) {

		$appointment = $this->get_appointment_by_order_id( $order_id );

		if ( ! $appointment ) {
			return;
		}

		$details = apply_filters(
			'jet-appointment/wc-integration/pre-get-order-details', false, $order_id, $appointment
		);

		if ( ! $details || empty( $details ) ) {
			$service         = get_the_title( $appointment['service'] );
			$service_label   = Plugin::instance()->tools->get_services_label();
			$provider        = ! empty( $appointment['provider'] ) ? get_the_title( $appointment['provider'] ) : false;
			$preovider_label = Plugin::instance()->tools->get_providers_label();
			$slot            = Plugin::instance()->tools->get_verbosed_slot( absint( $appointment['slot'] ) );
			$slot_end        = Plugin::instance()->tools->get_verbosed_slot( absint( $appointment['slot_end'] ) );

			$details[] = array(
				'key'     => $service_label,
				'display' => $service,
			);
			$details[] = array(
				'key'     => $preovider_label,
				'display' => $provider,
			);
			$details[] = array(
				'key'     => esc_html__( 'Appointments Start Time', 'jet-appointments-booking' ),
				'display' => $slot,
			);

			$details[] = array(
				'key'     => esc_html__( 'Appointments End Time', 'jet-appointments-booking' ),
				'display' => $slot_end,
			);
		}

		$details = apply_filters(
			'jet-appointment/wc-integration/order-details', $details, $order_id, $appointment
		);

		include Plugin::instance()->tools->get_template( $template . '.php' );
	}

	/**
	 * Show booking-related order details on order page
	 *
	 * @param  [type] $order_id [description]
	 * @return [type]           [description]
	 */
	public function order_details( $order_id ) {
		$this->order_details_template( $order_id );
	}

	/**
	 * Show booking-related order details on order page
	 *
	 * @param  [type] $order_id [description]
	 * @return [type]           [description]
	 */
	public function email_order_details( $order, $sent_to_admin, $plain_text ) {

		if ( $plain_text ) {
			$template = 'email-order-details-plain';
		} else {
			$template = 'email-order-details-html';
		}

		$this->order_details_template( $order->get_id(), $template );

	}

	/**
	 * Returns appointment detail by order id
	 *
	 * @return [type] [description]
	 */
	public function get_appointment_by_order_id( $order_id ) {

		$appointment = Plugin::instance()->db->get_appointment_by( 'order_id', $order_id );

		if ( ! $appointment['service'] ) {
			return false;
		}

		return $appointment;

	}

	/**
	 * Admin order details
	 *
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	public function admin_order_details( $order ) {
		$order_id = $order->get_id();
		$this->order_details( $order_id );
	}

	/**
	 * Get formatted appointment information
	 *
	 * @return [type] [description]
	 */
	public function get_formatted_appointment_info( $data = array(), $form_data = array(), $form_id = null  ) {

		$pre_cart_info = apply_filters(
			'jet-appointment/wc-integration/pre-cart-info',
			false, $data, $form_data, $form_id
		);

		if ( $pre_cart_info ) {
			return $pre_cart_info;
		}

		$service  = ! empty( $data['service'] ) ? absint( $data['service'] ) : false;
		$provider = ! empty( $data['provider'] ) ? absint( $data['provider'] ) : false;
		$date     = ! empty( $data['date'] ) ? absint( $data['date'] ) : false;
		$slot     = ! empty( $data['slot'] ) ? absint( $data['slot'] ) : false;
		$slot_end = ! empty( $data['slot_end'] ) ? absint( $data['slot_end'] ) : false;
		$result   = array();

		if ( ! $service || ! $date || ! $slot ) {
			return;
		}

		$result[] = array(
			'key'     => esc_html__( 'Time', 'jet-appointments-booking' ),
			'display' => Plugin::instance()->tools->get_verbosed_slot( $slot ),
		);

		$result[] = array(
			'key'     => esc_html__( 'End Time', 'jet-appointments-booking' ),
			'display' => Plugin::instance()->tools->get_verbosed_slot( $slot_end ),
		);

		$result[] = array(
			'key'     => Plugin::instance()->tools->get_services_label(),
			'display' => get_the_title( $service ),
		);

		if ( $provider ) {
			$result[]      = array(
				'key'     => Plugin::instance()->tools->get_providers_label(),
				'display' => get_the_title( $provider ),
			);
		}

		return apply_filters( 'jet-appointment/wc-integration/cart-info', $result, $data, $form_data );

	}

	/**
	 * Process WC-related notification part
	 *
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function process_wc_notifictaion( $data, $notifications, $n_args ) {

		if ( ! $this->get_status() || ! $this->get_product_id() ) {
			return;
		}

		$cart_item_data = array(
			$this->data_key      => $data,
			$this->form_data_key => $notifications->data,
			$this->form_id_key   => $notifications->form,
		);

		$price_field = ! empty( $n_args['appointment_wc_price'] ) ? $n_args['appointment_wc_price'] : false;
		$price       = false;

		if ( $price_field && isset( $notifications->data[ $price_field ] ) ) {
			$price = floatval( $notifications->data[ $price_field ] );
		}

		if ( $price ) {
			$cart_item_data[ $this->price_key ] = $price;
		}

		WC()->cart->empty_cart();
		WC()->cart->add_to_cart( $this->get_product_id(), 1, 0, array(), $cart_item_data );

		$checkout_fields_map = array();

		foreach ( $n_args as $key => $value ) {

			if ( false !== strpos( $key, 'wc_fields_map__' ) && ! empty( $value ) ) {
				$checkout_fields_map[ str_replace( 'wc_fields_map__', '', $key ) ] = $value;
			}

		}

		if ( ! empty( $checkout_fields_map ) ) {

			$checkout_fields = array();

			foreach ( $checkout_fields_map as $checkout_field => $form_field ) {
				if ( ! empty( $notifications->data[ $form_field ] ) ) {
					$checkout_fields[ $checkout_field ] = $notifications->data[ $form_field ];
				}
			}

			if ( ! empty( $checkout_fields ) ) {

				WC()->session->set( 'jet_apb_fields', $checkout_fields );
			}
		}

		add_filter( 'jet-engine/forms/handler/query-args', function( $query_args, $args, $handler ) {

			if ( 'success' !== $args['status'] ) {
				return $query_args;
			}

			$url = apply_filters( 'jet-engine/forms/handler/wp_redirect_url', wc_get_checkout_url() ) ;

			if ( $handler->is_ajax ) {
				$query_args['redirect'] = $url;
				return $query_args;
			} else {
				wp_redirect( $url );
				die();
			}

		}, 10, 3 );

	}

	/**
	 * Check if we need to create new Appointment product
	 *
	 * @return [type] [description]
	 */
	public function maybe_create_appointment_product( $settings ) {

		$new_status = $settings->get( 'wc_integration' );

		if ( ! $new_status ) {
			return;
		}

		$product_id = $this->get_product_id_from_db() ? $this->get_product_id_from_db() : $settings->get( 'wc_product_id' ) ;
		$product = get_post( $product_id );

		if ( ! $product || $product->post_status !== 'publish' ){
			$product_id = $this->create_appointment_product();
		}

		$settings->update( 'wc_product_id', $product_id, false );
	}

	/**
	 * Try to get previousle created product ID in db.
	 * @return [type] [description]
	 */
	public function get_product_id_from_db() {

		global $wpdb;

		$table      = $wpdb->postmeta;
		$key        = $this->appointmet_product_key;
		$product_id = $wpdb->get_var(
			"SELECT `post_id` FROM $table WHERE `meta_key` = '$key' ORDER BY post_id DESC;"
		);

		if ( ! $product_id ) {
			return false;
		}

		if ( 'product' !== get_post_type( $product_id ) ) {
			return false;
		}

		return absint( $product_id );
	}

	/**
	 * Returns product name
	 *
	 * @return [type] [description]
	 */
	public function get_product_name() {

		return apply_filters(
			'jet-apb/wc-integration/product-name',
			__( 'Appointment', 'jet-appointments-booking' )
		);

	}

	/**
	 * Create new appointment product
	 *
	 * @return [type] [description]
	 */
	public function create_appointment_product() {

		$product = new \WC_Product_Simple( 0 );

		$product->set_name( $this->get_product_name() );
		$product->set_status( 'publish' );
		$product->set_price( 1 );
		$product->set_regular_price( 1 );
		$product->set_slug( sanitize_title( $this->get_product_name() ) );

		$product->save();

		$product_id = $product->get_id();

		if ( $product_id ) {
			update_post_meta( $product_id, $this->appointmet_product_key, true );
		}

		return $product_id;
	}

	/**
	 * Set WC integration status
	 */
	public function set_status() {

		$is_enbaled       = Plugin::instance()->settings->get( 'wc_integration' );
		$product_id       = Plugin::instance()->settings->get( 'wc_product_id' );
		$this->is_enbaled = filter_var( $is_enbaled, FILTER_VALIDATE_BOOLEAN );
		$this->product_id = $product_id;

	}

	/**
	 * Return WC integration status
	 *
	 * @return [type] [description]
	 */
	public function get_status() {
		return $this->is_enbaled;
	}

	/**
	 * Return WC integration product
	 *
	 * @return [type] [description]
	 */
	public function get_product_id() {
		return $this->product_id;
	}

}
