<?php
namespace JET_APB\Rest_API;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Endpoint_Appointments_List extends \Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'appointments-list';
	}

	/**
	 * API callback
	 *
	 * @return void
	 */
	public function callback( $request ) {

		$params       = $request->get_params();
		$offset       = ! empty( $params['offset'] ) ? absint( $params['offset'] ) : 0;
		$per_page     = ! empty( $params['per_page'] ) ? absint( $params['per_page'] ) : 50;
		$query        = ! empty( $params['query'] ) ? json_decode( $params['query'], true ) : array();

		if ( ! empty( $query ) && is_array( $query ) ) {
			$query = array_filter( $query );
		} else {
			$query = array();
		}

		if ( ! empty( $query['date'] ) && ! is_int( $query['date'] ) ) {
			$query['date'] = strtotime( $query['date'] );
		}

		$appointments = Plugin::instance()->db->appointments->query(
			$query,
			$per_page,
			$offset,
			array(
				'orderby' => 'ID',
				'order'   => 'DESC',
			)
		);

		if ( empty( $appointments ) ) {
			$appointments = array();
		}

		return rest_ensure_response( array(
			'success' => true,
			'data'    => $this->format_dates( $appointments ),
			'total'   => Plugin::instance()->db->appointments->count( $query ),
		) );

	}

	public function format_dates( $appointments = array() ) {

		$date_format = get_option( 'date_format', 'F j, Y' );
		$time_format = get_option( 'time_format', 'H:i' );

		return array_map( function( $appointment ) use ( $date_format, $time_format ) {
			$appointment['date']     = date_i18n( $date_format, $appointment['date'] );
			$appointment['slot']     = date_i18n( $time_format, $appointment['slot'] );
			$appointment['slot_end'] = date_i18n( $time_format, $appointment['slot_end'] );
			return $appointment;
		}, $appointments );
	}

	/**
	 * Check user access to current end-popint
	 *
	 * @return bool
	 */
	public function permission_callback( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Returns arguments config
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'offset' => array(
				'default'  => 0,
				'required' => false,
			),
			'per_page' => array(
				'default'  => 50,
				'required' => false,
			),
			'query' => array(
				'default'  => array(),
				'required' => false,
			),
		);
	}

}
