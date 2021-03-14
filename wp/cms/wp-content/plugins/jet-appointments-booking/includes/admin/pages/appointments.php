<?php
namespace JET_APB\Admin\Pages;

use JET_APB\Admin\Helpers\Page_Config;
use JET_APB\Plugin;

/**
 * Base dashboard page
 */
class Appointments extends Base {

	/**
	 * Page slug
	 *
	 * @return string
	 */
	public function slug() {
		return 'jet-apb-appointments';
	}

	/**
	 * Page title
	 *
	 * @return string
	 */
	public function title() {
		return __( 'Appointments', 'jet-appointments-booking' );
	}

	/**
	 * Return  page config object
	 *
	 * @return [type] [description]
	 */
	public function page_config() {
		return new Page_Config(
			$this->slug(),
			array(
				'api'          => Plugin::instance()->rest_api->get_urls( false ),
				'services'     => Plugin::instance()->tools->get_posts( 'services', [ 'post_status' => 'any', 'posts_per_page' => -1 ] ),
				'providers'    => Plugin::instance()->tools->get_posts( 'providers', [ 'post_status' => 'any', 'posts_per_page' => -1 ] ),
				'statuses'     => Plugin::instance()->statuses->get_schema(),
				'all_statuses' => Plugin::instance()->statuses->get_statuses(),
				'edit_link'    => add_query_arg( array(
					'post'   => '%id%',
					'action' => 'edit',
				), admin_url( 'post.php' ) ),
			)
		);
	}

	/**
	 * Page render funciton
	 *
	 * @return void
	 */
	public function render() {
		?>
		<style type="text/css">
			.cell--id {
				flex: 0 0 4%;
			}
			.cell--user_email,
			.cell--provider,
			.cell--date,
			.cell--slot,
			.cell--order_id,
			.cell--service {
				flex: 0 0 11%;
			}
			.cell--status {
				flex: 0 0 30%;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			.jet-apb-actions button {
				margin: 0 0 0 10px;
			}
			.jet-apb-details p {
				font-size: 15px;
				line-height: 23px;
				padding: 0;
				margin: 0;
			}
			.jet-apb-details b {
				color: #23282d;
			}
			.jet-apb-loading {
				opacity: .6;
			}
			.jet-apb-filters {
				display: flex;
			}
			.cx-vui-component--jet-apb-filter.cx-vui-component {
				flex-direction: column;
				width: 200px;
			}
			.cx-vui-component--jet-apb-filter.cx-vui-component .cx-vui-component__label {
				margin-bottom: 5px;
			}
			.cx-vui-component--jet-apb-filter.cx-vui-component .cx-vui-component__control {
				position: relative;
			}
			.cx-vui-component--jet-apb-filter.cx-vui-component .cx-vui-component__control select {
				width: 100%;
			}
			.jet-apb-date-clear {
				position: absolute;
				right: 8px;
				top: 6px;
				line-height: 20px;
				font-size: 13px;
				cursor: pointer;
				opacity: .6;
			}
			.jet-apb-date-clear:hover {
				opacity: 1;
			}
		</style>
		<div id="jet-apb-appointments-page"></div>
		<?php
	}

	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function assets() {
		$this->enqueue_script( 'momentjs', 'admin/lib/moment.min.js' );
		$this->enqueue_script( 'vuejs-datepicker', 'admin/lib/vuejs-datepicker.min.js' );
		$this->enqueue_script( $this->slug(), 'admin/appointments.js' );
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return array(
			'appointments',
			'appointments-list',
		);
	}

}
