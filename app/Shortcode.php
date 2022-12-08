<?php
namespace OK_VIVID_SEATS_TICKETS;

class Shortcode{

    public function __construct(){
        add_shortcode( 'WP_VIVID_SEATS_TICKETS',array($this,'shortcode'));
    }

    public function shortcode($atts){
        $acc_id = get_option('wp_vivid_seats_acc_id');
        $auth_token = get_option('wp_vivid_seats_auth_token');
        
        if(!$acc_id || !$auth_token){
            return;
        }
        
        $url = 'https://api.vivid_seats.com/Mediapartners/'.$acc_id.'/Catalogs/ItemSearch.json';
        $args['PageSize'] = '10';
        $performer = $atts['performer'];
        $category = $atts['category'];
        $venue = $atts['venue'];
        $city = $atts['city'];

        $req = App::get_events($performer,$category,$venue,$city);
        if(is_wp_error($req)){
            return '<p>API Error.</p>';
        }

        $body_json = json_decode( wp_remote_retrieve_body( $req ) );
        
        $items = $body_json->Items;
        if(empty($items)){
            return '<p>No events returned.</p>';
        }


        ob_start();
            
        wp_enqueue_style( OK_VIVID_SEATS_TICKETS_NAME, OK_VIVID_SEATS_TICKETS_URL . 'public/wp-vivid-seats-tickets-public.css', array(), OK_VIVID_SEATS_TICKETS_NAME, 'all' );
        include(OK_VIVID_SEATS_TICKETS_PATH . 'view/tickets.php');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;


    }




}

new Shortcode();