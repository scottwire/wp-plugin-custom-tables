<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.visioncreativegroup.com
 * @since      1.0.0
 *
 * @package    Vcg_Tab_Slider
 * @subpackage Vcg_Tab_Slider/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Vcg_Tab_Slider
 * @subpackage Vcg_Tab_Slider/includes
 * @author     Vision Creative Group <developer@visioncreativegroup.com>
 */
class Vcg_Tab_Slider_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'vcg-tab-slider',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
