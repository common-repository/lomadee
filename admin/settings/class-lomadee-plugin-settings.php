<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    lomadee_plugin
 * @subpackage lomadee_plugin/admin
 */
class lomadee_plugin_Settings extends lomadee_plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $lomadee_plugin    The ID of this plugin.
	 */
	private $lomadee_plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $lomadee_plugin       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $lomadee_plugin, $version, $domain ) {

		$this->id    = 'general';
		$this->lomadee_plugin = $lomadee_plugin;
        parent::__construct($lomadee_plugin, $version, $domain);
	}

	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){

		// register_setting( $option_group, $option_name, $settings_sanitize_callback );
		register_setting(
			$this->lomadee_plugin . '_options',
			$this->lomadee_plugin . '_options',
			array( $this, 'settings_sanitize' )
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->lomadee_plugin . '-basic-options', // section
			apply_filters( $this->lomadee_plugin . '-basic-section-title', __( '', $this->lomadee_plugin ) ),
			array( $this, 'basic_options_section' ),
			$this->lomadee_plugin . '-basic-options'
		);

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'publisher_id',
			apply_filters( $this->lomadee_plugin . '-disable-bar-label', __( 'Publisher Id', $this->lomadee_plugin ) ),
			array( $this, 'publisher_id_field' ),
			$this->lomadee_plugin . '-basic-options',
			$this->lomadee_plugin . '-basic-options' // section to add to
		);
		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'site_id',
			apply_filters( $this->lomadee_plugin . '-disable-bar-label', __( 'Site', $this->lomadee_plugin ) ),
			array( $this, 'site_id_field' ),
			$this->lomadee_plugin . '-basic-options',
			$this->lomadee_plugin . '-basic-options' // section to add to
		);
		add_settings_field(
			'advertisers',
			apply_filters( $this->lomadee_plugin . '-post-type-label', __( 'Anunciantes', $this->lomadee_plugin ) ),
			array( $this, 'advertisers_field' ),
			$this->lomadee_plugin . '-basic-options',
			$this->lomadee_plugin . '-basic-options' // section to add to
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->lomadee_plugin . '-display-options', // section
			'',
			array( $this, 'display_options_section' ),
			$this->lomadee_plugin
		);

		add_settings_field(
			'enable_inside_post',
			apply_filters( $this->lomadee_plugin . '-post-type-label', __( 'Dentro dos posts', $this->lomadee_plugin ) ),
			array( $this, 'enable_inside_post' ),
			$this->lomadee_plugin,
			$this->lomadee_plugin . '-display-options' // section to add to
		);
		

	}


	/**
	 * Creates a basic options settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function basic_options_section( $params ) {

		echo '<p>' . $params['title'] . '</p>';

	} // display_options_section()

	/**
	 * Creates a display options settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_options_section( $params ) {

		echo '';

	} // display_options_section()

	/**
	 * Publisher id field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function publisher_id_field() {

		$options  	= get_option( $this->lomadee_plugin . '_options' );
		$option 	= '';

		if ( ! empty( $options['publisher_id'] ) ) {
			$option = $options['publisher_id'];
		}
		?>
		<input type="hidden" id="<?php echo $this->lomadee_plugin; ?>_options[publisher_id]" name="<?php echo $this->lomadee_plugin; ?>_options[publisher_id]" value="<?php echo esc_attr( $option ); ?>">
		<input type="text" value="<?php echo esc_attr( $option ); ?>" disabled/>
		<a id="btLogout" class="button button-primary" >Logout</a>
		<?php
	} // publisher_id_field()

	/**
	 * Site Id field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function site_id_field() {

		$options  	= get_option( $this->lomadee_plugin . '_options' );
		$siteId 	= '';
		$siteName 	= '';

		if ( ! empty( $options['site_id'] ) ) {
			$siteId = $options['site_id'];
			$siteName = $options['site_name'];
		}
		?>
		<input type="hidden" id="<?php echo $this->lomadee_plugin; ?>_options[site_id]" name="<?php echo $this->lomadee_plugin; ?>_options[site_id]" value="<?php echo esc_attr( $siteId ); ?>">
		<input type="hidden" id="<?php echo $this->lomadee_plugin; ?>_options[site_name]" name="<?php echo $this->lomadee_plugin; ?>_options[site_name]" value="<?php echo esc_attr( $siteName ); ?>">
		<input type="text" value="<?php echo esc_attr( $siteName ); ?>" disabled/>
		<?php
	} // site_id_field()
	/**
	 * Advertisers field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function advertisers_field() {

		$advertisers = $this->call_advertisers();
		$options 	= get_option( $this->lomadee_plugin . '_options' );
		$option 	= array();

		if ( ! empty( $options['advertisers'] ) ) {
			$option = $options['advertisers'];
		}
		?>
		<ul class="checkboxes">
			<li><label><input type="checkbox" id="chkTodosAdv" name="<?php echo $this->lomadee_plugin; ?>_options[advertisers][]" value="0" <?php echo in_array(0, $option)? 'checked="checked"' : '';?>/> Todos</label></li>
		</ul>
		<ul id="advList" class="checkboxes">
		<?php
		if (!empty($advertisers)) {

			foreach ( $advertisers as $advertiser ) {
				if($advertiser{'empId'} != null){

				$checked = in_array($advertiser{'empId'}, $option)? 'checked="checked"' : ''; ?>
				<li><label><input type="checkbox" id="<?php echo $this->lomadee_plugin; ?>_options[advertisers]" name="<?php echo $this->lomadee_plugin; ?>_options[advertisers][]" value="<?php echo esc_attr( $advertiser{'empId'} ); ?>" <?php echo $checked; ?> />
		   			<?php echo $advertiser{'name'}; ?>			
		   		</label></li>
					
			<?php	
				}
			} 
		}else{?>
			<script>jQuery(document).ready(function(){ jQuery('#submit').prop( "disabled", true );});</script>
		<?php }  ?>
			</ul>
			<p class="description">Selecione as lojas para exibir as ofertas relacionadas.</p>
		<?php

	} // advertisers_field()
	
	/**
	 * Enable/disable plugin inside post
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function enable_inside_post() {

		$options 	= get_option( $this->lomadee_plugin . '_options' );
		$option 	= 0;

		if ( ! empty( $options['enable_inside_post'] ) ) {
			$option = $options['enable_inside_post'];
		}

		?><input type="checkbox" id="<?php echo $this->lomadee_plugin; ?>_options[enable_inside_post]" name="<?php echo $this->lomadee_plugin; ?>_options[enable_inside_post]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // enable_inside_post()
	
}
