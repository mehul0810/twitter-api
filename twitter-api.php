<?php
/*
 *  @package Twitter API
 *  @version 1.0
 *  @credits https://github.com/abraham/twitteroauth
 */

require_once "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

if (!class_exists('TwitterAPI')) {

    class TwitterAPI {

        private $options;
        private $connection;
        private $debug_report = array();

        /*
         *  Initialise Twitter API
         *  @since 1.0
         */
        public function __construct( $options = array() ){

            $this->options = array_merge(
                array(
                    'consumer_key'          =>  '',
                    'consumer_secret'       =>  '',
                    'access_token'          =>  '',
                    'access_token_secret'   =>  '',
                    'twitter_screen_name'   =>  '',
                    'debug_mode'            =>  'false'
                ),
                $options
              );

            # Authorise Connection to Twitter
            $this->establish_connection();

            # Enable Error Reporting, if Debug Mode is enabled
            if (isset($this->options['debug_mode']) && 'true' === $this->options['debug_mode']) {
                error_reporting(E_ALL);
                echo $this->get_debug_information_list();
            }

            $statuses = $this->connection->get("search/tweets", ["q" => "webbymonks", "count" => "5"]);
            echo "<pre>"; print_r($statuses); echo "</pre>";

        }

        /*
         *  Authorise Connection to Twitter
         *  @since 1.0
         */
        public function establish_connection() {

            $this->connection = new TwitterOAuth( $this->options['consumer_key'], $this->options['consumer_secret'], $this->options['access_token'], $this->options['access_token_secret'] );
            $connection_details = $this->connection->get("account/verify_credentials");
            if( isset($connection_details->errors['0']->code) && '0' < $connection_details->errors['0']->code ){
                $this->add_debug_information( 'Error #251: Error Establishing Connection with Twitter. Please check the Connection Details.' );
            }

        }

        /*
         *  Add Debug Information
         *  @since 1.0
         */
        public function add_debug_information($msg) {

            array_push($this->debug_report, $msg);

        }

        /*
         *  Fetch Debug Information List
         *  @since 1.0
         */
        public function get_debug_information_list() {

            $debug_list = '<ul>';
            foreach($this->debug_report as $debug_item) {
                $debug_list .= '<li>' . $debug_item . '</li>';
            }
            $debug_list .= '</ul>';

            if (isset($this->options['debug_mode']) && 'true' === $this->options['debug_mode']) {
                return $debug_list;
            }

        }



    }

}







/*define( 'CONSUMER_KEY', 'bTOHe1WwRkAmqEgrRqnBUJhAP' );
define( 'CONSUMER_SECRET', 'FEUAHF316LrwAOvobDpVvxeHVi2qLhdvBbdIeObzCI4B1kiNYe' );
define( 'ACCESS_TOKEN', '2910652015-DGH4Lo9lUKhXOlmSEZ5FnepRYchFbOmNLGpXpOG' );
define( 'ACCESS_TOKEN_SECRET', 'crQPvj1tJVE1RlQhkWseHf3bkEEnTbQ55zIXCFXpbV7dG' );*/

$defaults = array(
    'consumer_key'          =>  'bTOHe1WwRkAmqEgrRqnBUJhAP',
    'consumer_secret'       =>  'FEUAHF316LrwAOvobDpVvxeHVi2qLhdvBbdIeObzCI4B1kiNYe',
    'access_token'          =>  '2910652015-DGH4Lo9lUKhXOlmSEZ5FnepRYchFbOmNLGpXpOG',
    'access_token_secret'   =>  'crQPvj1tJVE1RlQhkWseHf3bkEEnTbQ55zIXCFXpbV7dG',
    'twitter_screen_name'   =>  'LuicePhillips',
    'debug_mode'            =>  'true',
);
new TwitterAPI($defaults);
