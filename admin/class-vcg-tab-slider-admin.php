<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.visioncreativegroup.com
 * @since      1.0.0
 *
 * @package    Vcg_Tab_Slider
 * @subpackage Vcg_Tab_Slider/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vcg_Tab_Slider
 * @subpackage Vcg_Tab_Slider/admin
 * @author     Vision Creative Group <developer@visioncreativegroup.com>
 */



class Vcg_Tab_Slider_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vcg_Tab_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vcg_Tab_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vcg-tab-slider-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vcg_Tab_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vcg_Tab_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vcg-tab-slider-admin.js', array( 'jquery' ), $this->version, false );

	}

	function display_admin_menu()
	{
		add_menu_page(__('VCG Tab Slider', 'custom_table_example'), __('VCG Tab Slider', 'custom_table_example'), 'activate_plugins', 'tabsliders', array($this,'tabsliders_page_handler'));
		add_submenu_page('tabsliders', __('VCG Tab Slider', 'custom_table_example'), __('Tab Sliders', 'custom_table_example'), 'activate_plugins', 'tabsliders', array( $this, 'tabsliders_page_handler' ));
		// add new will be described in next part
		add_submenu_page('tabsliders', __('Add new', 'custom_table_example'), __('Add new', 'custom_table_example'), 'activate_plugins', 'tabsliders_form', array($this, 'tabsliders_form_page_handler'));
	}

	/**
    * List page handler
    *
    * This function renders our custom table
    * Notice how we display message about successfull deletion
    * Actualy this is very easy, and you can add as many features
    * as you want.
    *
    * Look into /wp-admin/includes/class-wp-*-list-table.php for examples
    */
	function tabsliders_page_handler()
	{
		global $wpdb;

		$table = new Custom_Table_Example_List_Table();
		$table->prepare_items();

		$message = '';
		if ('delete' === $table->current_action()) {
			$message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'custom_table_example'), count($_REQUEST['id'])) . '</p></div>';
		}
		?>
	<div class="wrap">

		<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
		<h2><?php _e('Persons', 'custom_table_example')?> <a class="add-new-h2"
										href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=tabsliders_form');?>"><?php _e('Add new', 'custom_table_example')?></a>
		</h2>
		<?php echo $message; ?>

		<form id="persons-table" method="GET">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
			<?php $table->display() ?>
		</form>

	</div>
	<?php
	}

/**
    * PART 4. Form for adding andor editing row
    * ============================================================================
    *
    * In this part you are going to add admin page for adding andor editing items
    * You cant put all form into this function, but in this example form will
    * be placed into meta box, and if you want you can split your form into
    * as many meta boxes as you want
    *
    * http://codex.wordpress.org/Data_Validation
    * http://codex.wordpress.org/Function_Reference/selected
    */

/**
    * Form page handler checks is there some data posted and tries to save it
    * Also it renders basic wrapper in which we are callin meta box render
    */
	function tabsliders_form_page_handler()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'cte'; // do not forget about tables prefix
	
		$message = '';
		$notice = '';
	
		// this is default $item which will be used for new records
		$default = array(
			'id' => 0,
			'name' => '',
			'email' => '',
			'age' => null,
		);
	
		// here we are verifying does this request is post back and have correct nonce
		if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
			// combine our default item with request params
			$item = shortcode_atts($default, $_REQUEST);
			// validate data, and if all ok save item to database
			// if id is zero insert otherwise update
			$item_valid = $this->custom_table_example_validate_person($item);
			if ($item_valid === true) {
				if ($item['id'] == 0) {
					$result = $wpdb->insert($table_name, $item);
					$item['id'] = $wpdb->insert_id;
					if ($result) {
						$message = __('Item was successfully saved', 'custom_table_example');
					} else {
						$notice = __('There was an error while saving item', 'custom_table_example');
					}
				} else {
					$result = $wpdb->update($table_name, $item, array('id' => $item['id']));
					if ($result) {
						$message = __('Item was successfully updated', 'custom_table_example');
					} else {
						$notice = __('There was an error while updating item', 'custom_table_example');
					}
				}
			} else {
				// if $item_valid not true it contains error message(s)
				$notice = $item_valid;
			}
		}
		else {
			// if this is not post back we load item to edit or give new one to create
			$item = $default;
			if (isset($_REQUEST['id'])) {
				$item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
				if (!$item) {
					$item = $default;
					$notice = __('Item not found', 'custom_table_example');
				}
			}
		}
	
		// here we adding our custom meta box
		add_meta_box('persons_form_meta_box', 'Person data', array($this,'custom_table_example_persons_form_meta_box_handler'), 'person', 'normal', 'default');
	
		?>
	<div class="wrap">
		<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
		<h2><?php _e('Person', 'custom_table_example')?> <a class="add-new-h2"
									href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=tabsliders');?>"><?php _e('back to list', 'custom_table_example')?></a>
		</h2>
	
		<?php if (!empty($notice)): ?>
		<div id="notice" class="error"><p><?php echo $notice ?></p></div>
		<?php endif;?>
		<?php if (!empty($message)): ?>
		<div id="message" class="updated"><p><?php echo $message ?></p></div>
		<?php endif;?>
	
		<form id="form" method="POST">
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
			<?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
			<input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
	
			<div class="metabox-holder" id="poststuff">
				<div id="post-body">
					<div id="post-body-content">
						<?php /* And here we call our custom meta box */ ?>
						<?php do_meta_boxes('person', 'normal', $item); ?>
						<input type="submit" value="<?php _e('Save', 'custom_table_example')?>" id="submit" class="button-primary" name="submit">
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php
	}
	
	/**
		* This function renders our custom meta box
		* $item is row
		*
		* @param $item
		*/
	function custom_table_example_persons_form_meta_box_handler($item)
	{
		?>
	
	<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
		<tbody>
		<tr class="form-field">
			<th valign="top" scope="row">
				<label for="name"><?php _e('Name', 'custom_table_example')?></label>
			</th>
			<td>
				<input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
						size="50" class="code" placeholder="<?php _e('Your name', 'custom_table_example')?>" required>
			</td>
		</tr>
		<tr class="form-field">
			<th valign="top" scope="row">
				<label for="email"><?php _e('E-Mail', 'custom_table_example')?></label>
			</th>
			<td>
				<input id="email" name="email" type="email" style="width: 95%" value="<?php echo esc_attr($item['email'])?>"
						size="50" class="code" placeholder="<?php _e('Your E-Mail', 'custom_table_example')?>" required>
			</td>
		</tr>
		<tr class="form-field">
			<th valign="top" scope="row">
				<label for="age"><?php _e('Age', 'custom_table_example')?></label>
			</th>
			<td>
				<input id="age" name="age" type="number" style="width: 95%" value="<?php echo esc_attr($item['age'])?>"
						size="50" class="code" placeholder="<?php _e('Your age', 'custom_table_example')?>" required>
			</td>
		</tr>
		</tbody>
	</table>
	<?php
	}
	
	/**
		* Simple function that validates data and retrieve bool on success
		* and error message(s) on error
		*
		* @param $item
		* @return bool|string
		*/
	function custom_table_example_validate_person($item)
	{
		$messages = array();
	
		if (empty($item['name'])) $messages[] = __('Name is required', 'custom_table_example');
		if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'custom_table_example');
		if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'custom_table_example');
		//if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
		//if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
		//...
	
		if (empty($messages)) return true;
		return implode('<br />', $messages);
	}
	
	/**
		* Do not forget about translating your plugin, use __('english string', 'your_uniq_plugin_name') to retrieve translated string
		* and _e('english string', 'your_uniq_plugin_name') to echo it
		* in this example plugin your_uniq_plugin_name == custom_table_example
		*
		* to create translation file, use poedit FileNew catalog...
		* Fill name of project, add "." to path (ENSURE that it was added - must be in list)
		* and on last tab add "__" and "_e"
		*
		* Name your file like this: [my_plugin]-[ru_RU].po
		*
		* http://codex.wordpress.org/Writing_a_Plugin#Internationalizing_Your_Plugin
		* http://codex.wordpress.org/I18n_for_WordPress_Developers
		*/
	function custom_table_example_languages()
	{
		load_plugin_textdomain('custom_table_example', false, dirname(plugin_basename(__FILE__)));
	}	
}
