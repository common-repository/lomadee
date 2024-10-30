<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    lomadee_plugin
 * @subpackage lomadee_plugin/public
 * @author     Lomadee
 */
class lomadee_plugin_Public {

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

	private $content;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $lomadee_plugin       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $lomadee_plugin, $version, $domain ) {

		$this->lomadee_plugin = $lomadee_plugin;
		$this->version = $version;
        $this->domain = $domain;
		$this->content = "";

	}
	/**
	 * Register the stylesheets for the public area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	}

	/**
	 * Register the JavaScript for the public area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->lomadee_plugin , $this->domain . '/assets/plugin/js/plugin.js', array( 'jquery' ), '', false );
		wp_enqueue_script( $this->lomadee_plugin . 'analytics', '//tagmanager.lomadee.com/wp_deeplink_plugin.js', array( 'jquery' ), '4.5.2', false );

	}
	/**
	 * Insert div on post-content and info for plugin.
	 *
	 * @since    1.0.0
	 */
	public function change_post_plugin($post_object) {
		
		if(is_single()){

		?><div id="plugin_lomadee">
			<?php echo $post_object; ?>
		</div><?php

		$options = get_option($this->lomadee_plugin . '_options');	
			

			$this->insert_info($options);
		}else{
			echo $post_object;
		}
		
	}
	/**
	 * Insert info for plugin if activated.
	 *
	 * @since    1.0.0
	 */
	public function insert_info($options) {
		
		if(!empty($options['enable_inside_post']) && ! empty($options['advertisers']) ){
			$advertisers = $options['advertisers'];

			?><script type='text/javascript' >
					var publisherId = '<?php echo $options['publisher_id'] ?>';
					var siteId = '<?php echo $options['site_id'] ?>';
					var advertisers = [<?php echo implode(",",$advertisers) ?>];
			  </script>
			<?php

		}

	}

}
