<?php
namespace OK_IMPACT_TICKETS;

class Shortcode{

    public function __construct(){
        add_shortcode( 'WP_IMPACT_TICKETS',array($this,'shortcode'));
    }

    public function shortcode($atts){
        $acc_id = get_option('wp_impact_acc_id');
        $auth_token = get_option('wp_impact_auth_token');
        
        if(!$acc_id || !$auth_token){
            return;
        }
        
        $url = 'https://api.impact.com/Mediapartners/'.$acc_id.'/Catalogs/ItemSearch.json';
        $args['PageSize'] = '10';
        $performer = $atts['performer'];
        $category = $atts['category'];
        $venue = $atts['venue'];
        $city = $atts['city'];


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
            return '<p>No events returned.</p>';
        }


        ob_start();
            
        wp_enqueue_style( OK_IMPACT_TICKETS_NAME, OK_IMPACT_TICKETS_URL . 'public/wp-impact-tickets-public.css', array(), OK_IMPACT_TICKETS_NAME, 'all' );
        include(OK_IMPACT_TICKETS_PATH . 'view/tickets.php');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;


    }




}

new Shortcode();