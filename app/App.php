<?php
namespace OK_VIVID_SEATS_TICKETS;

class App{

    public function __construct(){
        add_action('init',array($this,'redirect_to_ticket'));
        add_action('init',array($this,'load_more_redirect'));
        
    }

    public static function get_events($performer,$keyword,$category,$venue,$city,$count=10){
        $acc_id = get_option('wp_vivid_seats_acc_id');
        $auth_token = get_option('wp_vivid_seats_auth_token');
        if(!$acc_id || !$auth_token){
            return;
        }

        $url = 'https://api.impact.com/Mediapartners/'.$acc_id.'/Catalogs/ItemSearch.json';
        $args['PageSize'] = $count;

        $args['Query'] = '';

        if($keyword){
            $args['keyword'] = $keyword;
        }

        if($performer){
            $args['Query'] = $args['Query']." Name~'".$performer."'";
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
        return $req;
    }

    public function load_more_redirect(){
        if(!isset($_GET['load_more']) && !isset($_GET['ticket'])){
            return;
        }

        $url = 'https://www.vividseats.com/search?searchTerm='.$_GET['ticket'].'&irclickid=WW4xIJwNtxyNUiqVqVVaMziqUkAxKZWWGTx:040&utm_source=impact&utm_medium=affiliate&utm_content=3177810&utm_term=loadmore&irgwc=1';
        wp_redirect($url);
        exit;
        
    }

    public function redirect_to_ticket(){


        $acc_id = get_option('wp_vivid_seats_acc_id');
        $auth_token = get_option('wp_vivid_seats_auth_token');
        if(!$acc_id || !$auth_token){
            return;
        }

        $url = 'https://api.impact.com/Mediapartners/'.$acc_id.'/Catalogs/'.intval($_GET['cat']).'/Items/'.sanitize_text_field($_GET['ticket']).'';
        
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
        if(!$body_json->Url){
            return;
        }

        wp_redirect($body_json->Url);
        exit;
    }

}

new App();