<?php
namespace JET_ABAF\Dashboard\Pages;

use JET_ABAF\Dashboard\Helpers\Page_Config;
use JET_ABAF\Plugin;

/**
 * Base dashboard page
 */
class Set_Up extends Base {

	/**
	 * Page slug
	 *
	 * @return string
	 */
	public function slug() {
		return 'jet-abaf-set-up';
	}

	/**
	 * Page title
	 *
	 * @return string
	 */
	public function title() {
		return __( 'Set Up', 'jet-appointments-booking' );
	}

	/**
	 * Page render funciton
	 *
	 * @return void
	 */
	public function render() {
		echo '<div id="jet-abaf-set-up-page"></div>';
	}

	/**
	 * Return  page config object
	 *
	 * @return [type] [description]
	 */
	public function page_config() {
		return new Page_Config(
			$this->slug(),
			array()
		);
	}

	/**
	 * Define that is setup page
	 *
	 * @return boolean [description]
	 */
	public function is_setup_page() {
		return true;
	}

	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function assets() {

		$this->enqueue_script( $this->slug(), 'admin/set-up.js' );
		$this->enqueue_style( $this->slug(), 'admin/set-up.css' );

	}

	/**
	 * Set to true to hide page from admin menu
	 * @return boolean [description]
	 */
	public function is_hidden() {
		return Plugin::instance()->settings->get( 'hide_setup' );
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return array(
			'set-up',
		);
	}

}
