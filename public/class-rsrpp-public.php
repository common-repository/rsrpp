<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.marcin-mrowiec.pl/
 * @since      1.0.0
 *
 * @package    Rsrpp
 * @subpackage Rsrpp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rsrpp
 * @subpackage Rsrpp/public
 * @author     Marcin Mrowiec <marcin.mrowiec@outlook.com>
 */
class Rsrpp_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rsrpp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rsrpp-public.js', array( 'jquery' ), $this->version, false );

	}
	public function get_realted_posts_by_category($params) {
		$return = '';
		$categories = get_the_category($post->ID);
		if ($categories) {
			$category_ids = array();
			foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
		}

		
		$args = array(
			'category__in' => $category_ids,
			'post__not_in' => array($post->ID),
			'posts_per_page' => $params,
			'orderby' => 'rand'
		);	 
		$related = get_posts( $args );
		return $related;
	} 
	public function get_realted_posts_by_tags($params) {
		$return = '';
		$tags = wp_get_post_tags($post->ID);
		if ($tags) {
			$tag_ids = array();
			foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
		}
		$args = array(
			'tag__in' => $tag_ids,
			'post__not_in' => array($post->ID),
			'posts_per_page' => $params,
			'orderby' => 'rand'
		);	 
		$related = get_posts( $args );
		return $related;
	} 
	public function register_shortcodes() {
		add_shortcode( 'getrelatedposts', array( $this, 'getrelatedposts' ) );
	} // register_shortcodes()

	public function getrelatedposts( $atts = array() ){

		$options = get_option( 'rsrpp_options');
		$settings_args['numberofposts'] = $options['number_numerofposts'];
		$related_post_title = $options['text_title'];
		$settings_args['title'] = $options['text_title'];		
		$settings_args['relation'] = $options['select_relation'];	

		ob_start();	

		$args = shortcode_atts( array(
			'numberofposts' => 3,
			'title' => __('Related posts:','rsrpp'),
			'relation' => 2),
			$settings_args
			
		);	 
		//var_dump($settings_args);
		if ($atts['relation']=='tags') {
			$atts['relation']=1;
		}elseif ($atts['relation']=='category') {
			$atts['relation']=2;
		}
		$args = shortcode_atts( $args,$atts);
		
		//var_dump($args);
		if ($args['relation']==2) {
			//echo "<br>CATEGORY!<br>";
			$related_posts = $this->get_realted_posts_by_category($args['numberofposts']);
		}elseif ($args['relation']==1) {
			//echo "<br>TAGS!<br>";
			$related_posts = $this->get_realted_posts_by_tags($args['numberofposts']);
		}

		//var_dump($related_posts);
		
		if (!file_exists(get_stylesheet_directory().'/rsrpp-template.php')) {   
			echo '<b>'.__('Missing template! Created new template "rsrpp-template.php" in theme directory.','rsrpp').'</b>';  
			$this->copy_templates()         ;  
		}else{
			if (!empty($related_posts)) {
				include(get_stylesheet_directory().'/rsrpp-template.php');
			}else{
				echo '<b>'.__('No related posts.','rsrpp').'</b>';
			}
		}	 
		$output = ob_get_contents();
		ob_end_clean();	 
		return $output;	 	
	}
	public function copy_templates() {
		$templates_dir = trailingslashit(trailingslashit(RSRPP_DIR).'templates');
		
		require_once(ABSPATH.'wp-admin/includes/file.php');
		WP_Filesystem(false, get_stylesheet_directory());
		global $wp_filesystem;
		if ( $wp_filesystem->method !== 'direct') return false;
		
		return copy_dir($templates_dir, get_stylesheet_directory(), array('.svn'));
	}
}
