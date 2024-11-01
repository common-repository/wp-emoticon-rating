<?php
/**
 * @package WP_EMOTICON_RATING
 * @version 1.0.1
 */
/*
Plugin Name: WP Emoticon Rating
Plugin URI: 
Description: Allow your users to express their emotions.
Author: WebsEfficient
Version: 1.0.1
Author URI: http://websefficient.com/
*/
 if( !defined( 'ABSPATH' ) )
     exit;
 
 define( 'WP_EMO_PATH', WP_PLUGIN_DIR . '/wp-emoticon-rating' );
 define( 'WP_EMO_URL', WP_PLUGIN_URL . '/wp-emoticon-rating' );
 define( 'WP_EMO_TEXT_DOMAIN', 'wp-emoticon-rating' );
 if( !class_exists( 'WPEmoticonRating' ) ) {
     class WPEmoticonRating{
        protected static $instance = null;
 
        final protected function __construct(){
        }
 
        public static function app(){
            if( !(self::$instance instanceof self ) ){
               self::$instance = new self();
               require_once( WP_EMO_PATH . '/src/wp-emo-display.php' );
               if( is_admin() ){
                   require_once( WP_EMO_PATH . '/admin/wp-emo-admin.php' );
               }
               load_plugin_textdomain( WP_EMO_TEXT_DOMAIN, false, WP_EMO_PATH . '/languages' );
            }
            return self::$instance;
        }
    }
 }
 
 function WPEmoticonRating(){
     static $app;
     if( !( $app instanceof WPEmoticonRating ) ) $app = WPEmoticonRating::app();
         
     return $app;
 }
 WPEmoticonRating();
 
 
 //add_action( 'plugins_loaded', 'wp_geo_load_textdomain' );
 /**
  * Load plugin textdomain.
  *
  * @since 1.0.0
  */
 function wp_emo_load_textdomain() {
     load_plugin_textdomain( WP_EMO_TEXT_DOMAIN, false, WP_EMO_PATH . '/language' );
 }
