<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.visioncreativegroup.com
 * @since      1.0.0
 *
 * @package    Vcg_Tab_Slider
 * @subpackage Vcg_Tab_Slider/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Vcg_Tab_Slider
 * @subpackage Vcg_Tab_Slider/includes
 * @author     Vision Creative Group <developer@visioncreativegroup.com>
 */
class Vcg_Tab_Slider_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		global $custom_table_example_db_version;
	
		$table_name = $wpdb->prefix . 'cte'; // do not forget about tables prefix
	
		// sql to create your table
		// NOTICE that:
		// 1. each field MUST be in separate line
		// 2. There must be two spaces between PRIMARY KEY and its name
		//    Like this: PRIMARY KEY[space][space](id)
		// otherwise dbDelta will not work
		$sql = "CREATE TABLE " . $table_name . " (
			id int(11) NOT NULL AUTO_INCREMENT,
			name tinytext NOT NULL,
			email VARCHAR(100) NOT NULL,
			age int(11) NULL,
			PRIMARY KEY  (id)
		);";
	
		// we do not execute sql directly
		// we are calling dbDelta which cant migrate database
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	
		// save current database version for later use (on upgrade)
		add_option('custom_table_example_db_version', $custom_table_example_db_version);
	
		/**
			* [OPTIONAL] Example of updating to 1.1 version
			*
			* If you develop new version of plugin
			* just increment $custom_table_example_db_version variable
			* and add following block of code
			*
			* must be repeated for each new version
			* in version 1.1 we change email field
			* to contain 200 chars rather 100 in version 1.0
			* and again we are not executing sql
			* we are using dbDelta to migrate table changes
			*/
		$installed_ver = get_option('custom_table_example_db_version');
		if ($installed_ver != $custom_table_example_db_version) {
			$sql = "CREATE TABLE " . $table_name . " (
				id int(11) NOT NULL AUTO_INCREMENT,
				name tinytext NOT NULL,
				email VARCHAR(200) NOT NULL,
				age int(11) NULL,
				PRIMARY KEY  (id)
			);";
	
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
	
			// notice that we are updating option, rather than adding it
			update_option('custom_table_example_db_version', $custom_table_example_db_version);
		}
	}
	

}
