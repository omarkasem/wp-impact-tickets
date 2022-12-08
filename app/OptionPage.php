<?php
namespace OK_VIVID_SEATS_TICKETS;

class OptionPage{

    public function __construct(){

		add_action( 'admin_menu', array($this,'option_page') );
        add_action( 'admin_init', array($this,'plugin_register_settings') );

		add_action( 'admin_enqueue_scripts', array($this,'add_scripts'));

        
    }

	public function add_scripts(){

		wp_enqueue_style( OK_VIVID_SEATS_TICKETS_NAME.'bootstrap', OK_VIVID_SEATS_TICKETS_URL . 'admin/vivid-seats-bootstrap.css', array(), OK_VIVID_SEATS_TICKETS_NAME, 'all' );

	}

	public function option_page(){
		add_options_page('WP Vivid Seats Tickets','WP Vivid Seats Tickets','manage_options',OK_VIVID_SEATS_TICKETS_NAME.'.php',array($this, 'option_display'));
	}

	private function option_tabs(){
		return array(
			'settings'=>'Settings',
		);
	}

	public function option_display(){ ?>
		<div id="vivid_seats-iso-bootstrap">
			<div class="container-fluid">
			<div class="panel panel-default main_panel">
			  <div class="panel-body no-pad-bot">
				<div class="row">
					<div class="col-md-12" style="background-color:#1673E6;">
						<h1 style="color:#fff;">WP Vivid Seats Tickets</h1>
						<h3 style="color:#fff;">Version <?php echo OK_VIVID_SEATS_TICKETS_VERSION; ?></h3>
						<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'settings'; ?>
						<ul class="nav nav-tabs" role="tablist">
							<?php foreach($this->option_tabs() as $key => $value){ ?>
								<li class="<?php echo $active_tab == $key ? 'active' : ''; ?>" role="presentation"><a href="?page=<?php echo OK_VIVID_SEATS_TICKETS_NAME ?>.php&tab=<?php echo $key ?>"><?php echo $value; ?></a></li>
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
                    include_once(OK_VIVID_SEATS_TICKETS_PATH.'view/option-page.php');
                    echo '</div>';
                ?>
			  </div>
			</div>
			</div>
		</div>
	<?php }


	public function plugin_register_settings(){
		register_setting( 'wp_vivid_seats_settings', 'wp_vivid_seats_acc_id');
		register_setting( 'wp_vivid_seats_settings', 'wp_vivid_seats_auth_token');
	}


}

new OptionPage();