<?php
namespace OK_IMPACT_TICKETS;

class OptionPage{

    public function __construct(){

		add_action( 'admin_menu', array($this,'option_page') );
        add_action( 'admin_init', array($this,'plugin_register_settings') );

		add_action( 'admin_enqueue_scripts', array($this,'add_scripts'));

        
    }

	public function add_scripts(){

		wp_enqueue_style( OK_IMPACT_TICKETS_NAME.'bootstrap', OK_IMPACT_TICKETS_URL . 'admin/impact-bootstrap.css', array(), OK_IMPACT_TICKETS_NAME, 'all' );

	}

	public function option_page(){
		add_options_page('WP Impact Tickets','WP Impact Tickets','manage_options',OK_IMPACT_TICKETS_NAME.'.php',array($this, 'option_display'));
	}

	private function option_tabs(){
		return array(
			'settings'=>'Settings',
		);
	}

	public function option_display(){ ?>
		<div id="impact-iso-bootstrap">
			<div class="container-fluid">
			<div class="panel panel-default main_panel">
			  <div class="panel-body no-pad-bot">
				<div class="row">
					<div class="col-md-12" style="background-color:#1673E6;">
						<h1 style="color:#fff;">WP Impact Tickets</h1>
						<h3 style="color:#fff;">Version <?php echo OK_IMPACT_TICKETS_VERSION; ?></h3>
						<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'settings'; ?>
						<ul class="nav nav-tabs" role="tablist">
							<?php foreach($this->option_tabs() as $key => $value){ ?>
								<li class="<?php echo $active_tab == $key ? 'active' : ''; ?>" role="presentation"><a href="?page=<?php echo OK_IMPACT_TICKETS_NAME ?>.php&tab=<?php echo $key ?>"><?php echo $value; ?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			  </div>
			</div>
			<div class="panel panel-default">
			  <div class="panel-body">
				<?php
                    echo '<div class="row">';
                    include_once(OK_IMPACT_TICKETS_PATH.'view/option-page.php');
                    echo '</div>';
                ?>
			  </div>
			</div>
			</div>
		</div>
	<?php }


	public function plugin_register_settings(){
		register_setting( 'wp_impact_settings', 'wp_impact_acc_id');
		register_setting( 'wp_impact_settings', 'wp_impact_auth_token');
	}


}

new OptionPage();