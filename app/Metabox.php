<?php
namespace OK_IMPACT_TICKETS;
class Metabox{

    public function __construct(){
        add_action( 'add_meta_boxes',array($this,'add_metabox') );
        add_action( 'admin_enqueue_scripts', array($this,'add_scripts'));

        add_action( 'wp_ajax_wp_impact_search', array($this,'search') );
        add_action( 'wp_ajax_nopriv_wp_impact_search', array($this,'search') );

    }

    public function search(){
        $acc_id = get_option('wp_impact_acc_id');
        $auth_token = get_option('wp_impact_auth_token');
        if(!$acc_id || !$auth_token){
            return;
        }

        $url = 'https://api.impact.com/Mediapartners/'.$acc_id.'/Catalogs/ItemSearch.json';
        $args['PageSize'] = '10';
        $performer = $_POST['vars']['performer'];
        $category = $_POST['vars']['category'];
        $venue = $_POST['vars']['venue'];
        $city = $_POST['vars']['city'];


        $args['Query'] = '';
        if($performer){
            $args['Query'] = $args['Query']." Name='".$performer."'";
        }

        if($category){
            $args['Query'] = $args['Query'].(!empty($args['Query']) ? ' AND ' : '')."Category='".$category."'";
        }

        if($venue){
            $args['Query'] = $args['Query'].(!empty($args['Query']) ? ' AND ' : '')."Text1='".$venue."'";
        }

        if($city){
            $args['Query'] = $args['Query'].(!empty($args['Query']) ? ' AND ' : '')."Text2='".$city."'";
        }

        $url = add_query_arg( $args, $url);

		$params = array(
			'timeout'    => 20,
			'headers'    => array(
				'Content-Type' => 'application/json; charset=utf-8',
				'Authorization' => 'Basic ' . base64_encode( $acc_id . ':' . $auth_token ),
				'Content-type'  => 'application/json',
				'Accept'        => 'application/json',
			),
		);
        $req = wp_remote_get( $url,$params);

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

		wp_enqueue_style( OK_IMPACT_TICKETS_NAME, OK_IMPACT_TICKETS_URL . 'admin/wp-impact-tickets-admin.css', array(), OK_IMPACT_TICKETS_NAME, 'all' );

		wp_enqueue_script( OK_IMPACT_TICKETS_NAME, OK_IMPACT_TICKETS_URL . 'admin/wp-impact-tickets-admin.js', array( 'jquery' ), OK_IMPACT_TICKETS_VERSION, false );

        wp_localize_script(OK_IMPACT_TICKETS_NAME, 'wp_impact',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ),  ) );


	}


    function add_metabox() {
        add_meta_box( 'impact-performer', 'Search Impact Performer', array($this,'metabox_callback'), 'post','normal','high' );
    }
    
    function metabox_callback(){
		$client_id = get_option('wp_impact_acc_id');
		if($client_id == ''){
			return 'Please fill the client id first in the option page.';
		}
        
		$output = '<div class="sg_shortcodes"><h3>Search for events</h3>';
		$output .='<div class="field"><label for="performer">Performer</label>';
        $output.= '<input type="text" class="regular-text performer" id="performer"></div>';

		$output .='<div class="field"><label for="category">Category</label>';
        $output.= '<input type="text" class="regular-text category" id="category"></div>';

		$output .='<div class="field"><label for="venue">Venue</label>';
        $output.= '<input type="text" class="regular-text venue" id="venue"></div>';

		$output .='<div class="field"><label for="city">City</label>';
        $output.= '<input type="text" class="regular-text city" id="city"></div>';

		$output .= '<button class="sg_search button button-primary">Test Search</button>';
        $output .= '<button class="sg_generate button button-primary">Generate Shortcode</button>';

		$output .='<h3>Shortcodes<h3>';
        $output .= '<ul class="shortcodes"></ul><hr>';

		$output .='<h3>Results<h3>';
        $output .= '<ul class="results"></ul>';
        $output .= '</div>';

        $output.= '<style>
            .sg_shortcodes .field{    margin-bottom: 20px;}
            .sg_shortcodes .field label{display: block;
                font-weight: bold;
                margin-bottom: 10px;}
            .sg_shortcodes .field input{display: block;}
            .sg_shortcodes .button{margin-right: 30px;}
            .sg_shortcodes .results li{font-size: 16px;
                font-weight: 400;
                border-bottom: 1px solid #9b9b9b;
                padding: 9px 0;
                margin: 0;}

            .shortcodes li{
                font-size: 15px;
                border-bottom: 1px solid #d2d2d2;
                padding: 15px 0;
            }
            .shortcodes li input{
                width:80%;
                height: 40px;
                margin: 0;
                font-weight: bold;
                padding: 0 10px;
            }
            .shortcodes li .sg_copy{
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