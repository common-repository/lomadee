<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    lomadee-oficial
 * @subpackage lomadee-oficial/admin
 */
class lomadee_plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $lomadee_plugin    The ID of this plugin.
	 */
	private $lomadee_plugin;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
    
    /**
	 * The domain for requests
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $domain    The domain for requests.
	 */
	private $domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $lomadee_plugin       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 * @var      string    $domain     The domain for requests.
	 */
	public function __construct( $lomadee_plugin, $version, $domain ) {

		$this->lomadee_plugin = $lomadee_plugin;
		$this->version = $version;
        $this->domain = $domain;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->lomadee_plugin, plugin_dir_url( __FILE__ ) . 'css/lomadee-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->lomadee_plugin, $this->domain . '/assets/plugin/js/admin.js', array( 'jquery' ), '4.5.2', false );
		wp_enqueue_script( $this->lomadee_plugin . 'analytics', '//tagmanager.lomadee.com/wp_deeplink_plugin.js', array( 'jquery' ), '4.5.2', false );

	}
	/**
	 * Register the Settings page.
	 *
	 * @since    1.0.0
	 */
	public function lomadee_plugin_admin_menu() {

		 add_options_page( __('Configurações | Lomadee', $this->lomadee_plugin), __('Lomadee', $this->lomadee_plugin), 'manage_options', $this->lomadee_plugin, array($this, 'display_plugin_admin_page'));
		 
	}

	/**
	 * Settings - Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function settings_sanitize( $input ) {

		// Initialize the new array that will hold the sanitize values
		$new_input = array();

		if(isset($input)) {
			// Loop through the input and sanitize each of the values
			foreach ( $input as $key => $val ) {

				if($key == 'advertisers') { // dont sanitize array
					$new_input[ $key ] = $val;
				} else {
					$new_input[ $key ] = sanitize_text_field( $val );
				}
				
			}

		}

		return $new_input;

	} // sanitize()


	/**
	 * Callback function for the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page(){	

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lomadee-plugin-admin-display.php';
	}

	/**
	 * Ajax function for save initial settings.
	 *
	 * @since    1.0.0
	 */
	public function save_publisher() {
		$data = explode(";", $_POST['data']);
		$pubId = intval( $data[0] );
		$siteId = $data[1] ;

		if(strcasecmp($siteId,'NO_SITES') == 0){
			update_option( $this->lomadee_plugin . '_options', 
				array('no_sites'=>1));
		}else{
			$siteName = $data[2] ;
			update_option( $this->lomadee_plugin . '_options', 
			array('publisher_id'=>$pubId,
                  'site_id'=>$siteId,
                  'site_name'=>$siteName,
                  'enable_header'=>1,
                  'enable_footer'=>1,
                  'enable_sidebar'=>1,
                  'enable_inside_post'=>1,
                  'enable_footer_post'=>1,
                  'advertisers'=>array("0"))
        	);
		}
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	/**
	 * Ajax function for logout publisher.
	 *
	 * @since    1.0.0
	 */
	public function logout_publisher(){
		delete_option( $this->lomadee_plugin . '_options');

		wp_die(); // this is required to terminate immediately and return a proper response
	}

	/**
	 * Function for verify if publisher is logged in.
	 *
	 * @since    1.0.0
	 */
	public function verify_publisher(){
		$options = get_option($this->lomadee_plugin . '_options');
		if( ! empty( $options['publisher_id'] ) ){
			return true;
		}
		return false;
	}

	/**
	 * Function for verify if publisher has error on site.
	 *
	 * @since    1.0.0
	 */
	public function verify_error_site(){
		
		$options = get_option($this->lomadee_plugin . '_options');
		if( ! empty( $options['no_sites'] ) ){
			delete_option($this->lomadee_plugin . '_options');
			return true;
		}
		
		return false;
	}

	/**
	 * Function for get all advertisers.
	 *
	 * @since    1.0.0
	 */
	public function call_advertisers() {
		global $wpdb; // this is how you get access to the database

		$advertisers = $this->CallAPI("GET", $this->domain . "/rest/publisher/advertiser/?generatingCache=true");
		if(! empty($advertisers)){
			return $advertisers{'advertisers'};	
		}
		return [];
	}

	/**
	 * Function for call external apis.
	 *
	 * @since    1.0.0
	 */
	private function CallAPI($method, $url, $data = false, $cookie = false)
	{
	    
        if(! empty($data)){
            $postdata = http_build_query($data);    
        }else{
            $postdata = false;
        }
        
        $opts = array('http' =>
            array(
                'method'  => $method,
                'header'  => 'Cookie: ' . $cookie,
                'content' => $postdata
            )
        );

        $context = stream_context_create($opts);

        $result = file_get_contents($url, false, $context);
        
        return json_decode($result, true);
	}
}
