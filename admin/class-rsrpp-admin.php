<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.marcin-mrowiec.pl/
 * @since      1.0.0
 *
 * @package    Rsrpp
 * @subpackage Rsrpp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rsrpp
 * @subpackage Rsrpp/admin
 * @author     Marcin Mrowiec <marcin.mrowiec@outlook.com>
 */
class Rsrpp_Admin {

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
		add_action('admin_menu', array( $this ,'rsrpp_admin_add_page'));
		add_action('admin_init', array( $this ,'rsrpp_admin_init'));

	}
	function rsrpp_admin_add_page() {
		add_options_page('RSRPP Settings Page', 'RSRPP Settings', 'manage_options', 'rsrpp', array( $this ,'rsrpp_options_page'));
	}
	function rsrpp_options_page() {
		?>
		<div>
		<h2><?php__('Really Simple Related Posts Plugin Settings','rsrpp')?></h2>
		<form action="options.php" method="post">
		<?php settings_fields('rsrpp_options'); ?>
		<?php do_settings_sections('rsrpp'); ?>		 
		<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form></div>		 
		<?php
	}
	
	function rsrpp_admin_init(){
		register_setting( 'rsrpp_options', 'rsrpp_options', array( $this ,'rsrpp_options_validate') );
		add_settings_section('rsrpp_main', __('Main Settings','rsrpp'), array( $this ,'rsrpp_section_text'), 'rsrpp');
		add_settings_field('rsrpp_text_title', __('Title above related posts:','rsrpp'), array( $this ,'rsrpp_setting_title'), 'rsrpp', 'rsrpp_main');
		add_settings_field('rsrpp_number_numerofposts', __('Numer of related posts to display:','rsrpp'), array( $this ,'rsrpp_setting_numerofposts'), 'rsrpp', 'rsrpp_main');
		add_settings_field('rsrpp_select_relation', __('Relation type:','rsrpp'), array( $this ,'rsrpp_setting_relation'), 'rsrpp', 'rsrpp_main');
	}
	function rsrpp_setting_title() {
		$options = get_option('rsrpp_options',__('Related posts:','rsrpp'));
		//var_dump($options);
		echo "<input id='rsrpp_text_title' name='rsrpp_options[text_title]' size='40' type='text' value='{$options['text_title']}' />";
	}
	function rsrpp_setting_numerofposts() {
		$options = get_option('rsrpp_options','3');
		//var_dump($options);
		echo "<input id='rsrpp_number_numerofposts' name='rsrpp_options[number_numerofposts]' size='40' type='number' value='{$options['number_numerofposts']}' />";
	}
	function rsrpp_setting_relation() {
		$options = get_option( 'rsrpp_options');
		//var_dump($options);
		?>
		<select name='rsrpp_options[select_relation]'>
			<option value='0' <?php selected( $options['select_relation'], 0 )?> > <?php echo __('select','rsrpp') ?> </option>
			<option value='1' <?php selected( $options['select_relation'], 1 )?> > <?php echo __('by tags','rsrpp') ?> </option>
			<option value='2' <?php selected( $options['select_relation'], 2 )?> > <?php echo __('by category','rsrpp') ?> </option>
		</select>
		<?php
	}
	
	function rsrpp_options_validate($input) {
		
    // Create our array for storing the validated options
    $output = array();
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
        } // end if
    } // end foreach
    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'rsrpp_options', $output, $input );
	}

	function rsrpp_section_text() {
		echo '';
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
		 * defined in Rsrpp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rsrpp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rsrpp-admin.css', array(), $this->version, 'all' );

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
		 * defined in Rsrpp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rsrpp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rsrpp-admin.js', array( 'jquery' ), $this->version, false );

	}


}
