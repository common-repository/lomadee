<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    lomadee_plugin
 * @subpackage lomadee_plugin/includes
 * @author     Lomadee
 */
class lomadee_plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      lomadee_plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $lomadee_plugin    The string used to uniquely identify this plugin.
	 */
	protected $lomadee_plugin;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The domain for requests.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $domain    The domain for requests.
	 */
	protected $domain;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->lomadee_plugin = 'lomadee_plugin';
		$this->version = '0.0.1';
        $this->domain = 'https://www.lomadee.com';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - lomadee_plugin_Loader. Orchestrates the hooks of the plugin.
	 * - lomadee_plugin_i18n. Defines internationalization functionality.
	 * - lomadee_plugin_Admin. Defines all hooks for the dashboard.
	 * - lomadee_plugin_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lomadee-plugin-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lomadee-plugin-admin.php';

		/**
		 * The class responsible for defining all Settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-lomadee-plugin-settings.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-lomadee-plugin-enable-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-lomadee-plugin-public.php';

		
		$this->loader = new lomadee_plugin_Loader();

	}

	/**
	 * Register all of the hooks related to the admin functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new lomadee_plugin_Admin( $this->get_lomadee_plugin(), $this->get_version(), $this->get_domain() );
		$settings_init_general = new lomadee_plugin_Settings( $this->get_lomadee_plugin(), $this->get_version(), $this->get_domain() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'lomadee_plugin_admin_menu' );
		$this->loader->add_action( 'wp_ajax_save_publisher', $plugin_admin, 'save_publisher' );
		$this->loader->add_action( 'wp_ajax_logout_publisher', $plugin_admin, 'logout_publisher' );
		$this->loader->add_action( 'admin_init', $settings_init_general, 'settings_api_init' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new lomadee_plugin_Public( $this->get_lomadee_plugin(), $this->get_version(), $this->get_domain() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'the_content', $plugin_public, 'change_post_plugin' );
		
		
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
	public function get_lomadee_plugin() {
		return $this->lomadee_plugin;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    lomadee_plugin_Loader    Orchestrates the hooks of the plugin.
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
    
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_domain() {
		return $this->domain;
	}

}
