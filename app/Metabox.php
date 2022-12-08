<?php
namespace OK_VIVID_SEATS_TICKETS;
class Metabox{

    public function __construct(){
        add_action( 'add_meta_boxes',array($this,'add_metabox') );
        add_action( 'admin_enqueue_scripts', array($this,'add_scripts'));

        add_action( 'wp_ajax_wp_vivid_seats_search', array($this,'search') );
        add_action( 'wp_ajax_nopriv_wp_vivid_seats_search', array($this,'search') );

    }

    public function search(){
        $performer = $_POST['vars']['performer'];
        $category = $_POST['vars']['category'];
        $venue = $_POST['vars']['venue'];
        $city = $_POST['vars']['city'];

        $req = App::get_events($performer,$category,$venue,$city);

        if(is_wp_error($req)){
            wp_send_json_success(['success'=>false,'output'=>'<li>API Error.</li>']);
        }
        $body_json = json_decode( wp_remote_retrieve_body( $req ) );
        $items = $body_json->Items;
        if(empty($items)){
            wp_send_json_success(['success'=>false,'output'=>'<li>No events returned.</li>']);
        }

        $output = '';
        foreach($items as $item){
            $output.= '<li>
                <b>Event: </b>'.$item->Name.' <br>
                <b>Category: </b>'.$item->Category.' <br>
                <b>Venue: </b>'.$item->Text1.' - '.$item->Text2.' <br>
            </li>';
        }
        wp_send_json_success(['success'=>true,'output'=>$output]);

    }



	public function add_scripts(){

		wp_enqueue_script( OK_VIVID_SEATS_TICKETS_NAME, OK_VIVID_SEATS_TICKETS_URL . 'admin/wp-vivid-seats-tickets-admin.js', array( 'jquery' ), OK_VIVID_SEATS_TICKETS_VERSION, false );

        wp_localize_script(OK_VIVID_SEATS_TICKETS_NAME, 'wp_vivid_seats',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ),  ) );


	}


    function add_metabox() {
        add_meta_box( 'vivid_seats-performer', 'Search Vivid Seats Performer', array($this,'metabox_callback'), 'post','normal','high' );
    }
    
    function metabox_callback(){
		$client_id = get_option('wp_vivid_seats_acc_id');
		if($client_id == ''){
			return 'Please fill the client id first in the option page.';
		}
        
		$output = '<div class="vst_block"><h3>Search for events</h3>';
		$output .='<div class="field"><label for="performer">Performer</label>';
        $output.= '<input type="text" class="regular-text performer" id="performer"></div>';

		$output .='<div class="field"><label for="category">Category</label>';
        $output.= '<input type="text" class="regular-text category" id="category"></div>';

		$output .='<div class="field"><label for="venue">Venue</label>';
        $output.= '<input type="text" class="regular-text venue" id="venue"></div>';

		$output .='<div class="field"><label for="city">City</label>';
        $output.= '<input type="text" class="regular-text city" id="city"></div>';

		$output .= '<button class="vst_search button button-primary">Test Search <img style="display:none;" src="'.site_url('wp-includes/images/wpspin.gif').'"></button>';
        $output .= '<button class="vst_generate button button-primary">Generate Shortcode</button>';

		$output .='<h3>Shortcodes<h3>';
        $output .= '<ul class="vst_shortcodes"></ul><hr>';

		$output .='<h3>Results<h3>';
        $output .= '<ul class="results"></ul>';
        $output .= '</div>';

        $output.= '<style>
            .vst_search img{display: inline-block;
                margin-bottom: -2px;}
            .vst_block .field{    margin-bottom: 20px;}
            .vst_block .field label{display: block;
                font-weight: bold;
                margin-bottom: 10px;}
            .vst_block .field input{display: block;}
            .vst_block .button{margin-right: 30px;}
            .vst_block .results li{font-size: 16px;
                font-weight: 400;
                border-bottom: 1px solid #9b9b9b;
                padding: 9px 0;
                margin: 0;}

            .vst_shortcodes li{
                font-size: 15px;
                border-bottom: 1px solid #d2d2d2;
                padding: 15px 0;
            }
            .vst_shortcodes li input{
                width:80%;
                height: 40px;
                margin: 0;
                font-weight: bold;
                padding: 0 10px;
            }
            .vst_shortcodes li .sg_copy{
                background: #c20000;
                border: none;
                height: 40px;
                cursor: pointer;
                color: #fff;
                padding: 0 15px;
            }


        </style>';
		echo $output;
    }
    

}

new Metabox();