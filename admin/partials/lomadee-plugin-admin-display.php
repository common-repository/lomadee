<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    lomadee-oficial
 * @subpackage lomadee-oficial/admin/partials
 */
?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <?php if($this->verify_publisher()){ ?>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="postbox-container-2" class="postbox-container">
				<form method="post" action="options.php">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div id="itsec_get_started" class="postbox ">
							<h3 class="hndle"><span>Configurações Gerais</span></h3>
							<div class="inside">
								<?php
									settings_fields( 'lomadee_plugin_options' );

									do_settings_sections( 'lomadee_plugin-basic-options' );
									
								?>
								<div class="clear"></div>
							</div>
						</div>
					</div>
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div id="itsec_get_started" class="postbox ">
							<h3 class="hndle"><span>Habilitar Anúncios</span></h3>
							<div class="inside">
								<?php
									settings_fields( 'lomadee_plugin_options' );

									do_settings_sections( 'lomadee_plugin' );
									
									submit_button( 'Save Settings' );
								?>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <?php } else if($this->verify_error_site()){ ?>
    	<h3>Para utilizar o plugin, você precisa de ao menos um site ativo na plataforma Lomadee.</h3>
    	<h3>Favor cadastrar um site em <a target="_blank" href="<?php echo $this->domain ?>/dashboard/#/">lomadee.com</a> ou aguarde seu site ser aprovado.</h3>
    <?php } else { ?>
    <iframe id="iframeToLomadee" frameborder="0" style="width:100%;" border="0" scrolling="no" allowfullscreen=""></iframe>
    <?php } ?>
</div>



