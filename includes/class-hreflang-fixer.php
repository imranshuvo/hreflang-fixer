<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://webkonsulenterne.dk
 * @since      1.0.0
 *
 * @package    Hreflang_Fixer
 * @subpackage Hreflang_Fixer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hreflang_Fixer
 * @subpackage Hreflang_Fixer/includes
 * @author     Imran Khan <imran@webkonsulenter.dk>
 */
class Hreflang_Fixer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hreflang_Fixer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	public $domains = array();

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'HREFLANG_FIXER_VERSION' ) ) {
			$this->version = HREFLANG_FIXER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'hreflang-fixer';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->set_domains();


		add_action('wp_head',array($this,'wk_add_hreflang_to_head'), 1);

	}

	public function set_domains(){
		$args = array(
			//'sv' => 'SE',
			'da' => 'DK',
		);
		$this->domains = array_merge($this->domains, $args);
	}


	/**
	 * 
	 * 
	 * Code for the wp_head
	 * 
	 **/

	public function wk_add_hreflang_to_head() {

		$url = $_SERVER['REQUEST_URI'];
		$uria = explode("/", $url);
		$uri = $uria[1];
		unset($uria[1]);
        


		$domains = $this->domains;
		$default = false;

		//First get the current request url 
        ?>
		<link rel="alternate" hreflang="x-default" href="https://micronordic.com/" />
		<link rel="alternate" hreflang="en" href="https://micronordic.com/" />
		<link rel="alternate" hreflang="en-US" href="https://micronordic.com/" />
        <?php 
        
        
		foreach($domains as $lang => $locale ){
		    $hreflang1 = $lang."-".$locale;
		    $hreflang2 = $lang;
		    $full_url = implode(" ", $uria);
		    $full_url = str_replace(" ","/", $full_url);
		    $full_url = strtolower($locale).$full_url;
		    
		    /*
		    if(is_user_logged_in()){
		        var_dump($full_url);
		    }
		    */
		    
		    if($url == "/"){
		        echo "<link rel='alternate' hreflang='".$hreflang1."' href='https://micronordic.com/".$full_url."/' />";
		        echo "<link rel='alternate' hreflang='".$hreflang2."' href='https://micronordic.com/".$full_url."/' />";
		    }else{
		        echo "<link rel='alternate' hreflang='".$hreflang1."' href='https://micronordic.com/".$full_url."' />";
		        echo "<link rel='alternate' hreflang='".$hreflang2."' href='https://micronordic.com/".$full_url."' />";
		    }
		    
		    
			
		}
		
		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Hreflang_Fixer_Loader. Orchestrates the hooks of the plugin.
	 * - Hreflang_Fixer_i18n. Defines internationalization functionality.
	 * - Hreflang_Fixer_Admin. Defines all hooks for the admin area.
	 * - Hreflang_Fixer_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hreflang-fixer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hreflang-fixer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hreflang-fixer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hreflang-fixer-public.php';

		$this->loader = new Hreflang_Fixer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hreflang_Fixer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hreflang_Fixer_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Hreflang_Fixer_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Hreflang_Fixer_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hreflang_Fixer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
