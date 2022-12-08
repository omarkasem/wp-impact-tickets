<?php
namespace OK_IMPACT_TICKETS;

class App{

    public function __construct(){
        add_action('init',array($this,'redirect_to_ticket'));
        
    }

    public function redirect_to_ticket(){
        if(!isset($_GET['cat']) && !isset($_GET['ticket'])){
            return;
        }

        $acc_id = get_option('wp_impact_acc_id');
        $auth_token = get_option('wp_impact_auth_token');
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