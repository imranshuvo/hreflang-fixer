<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://webkonsulenterne.dk
 * @since      1.0.0
 *
 * @package    Hreflang_Fixer
 * @subpackage Hreflang_Fixer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Hreflang_Fixer
 * @subpackage Hreflang_Fixer/includes
 * @author     Imran Khan <imran@webkonsulenter.dk>
 */
class Hreflang_Fixer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'hreflang-fixer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
