<?php
/*
 * Plugin Name: Reset the Net splash screen
 * Plugin URI: http://wordpress.org/extend/plugins/reset-the-net-splash-screen
 * Description: Runs the “Reset the Net” splash screen in protest on June 5th, to take a stand for privacy and to start making mass surveillance too hard for any government.  The script is set to hide until the day of; so it’s working properly if the plugin is activated but you don’t see anything.
 * Author: G.Breant
 * Version: 1.0.1
 * Author URI: http://sandbox.pencil2d.org/
 * License: GPL2+
 * Text Domain: rtnss
 * Domain Path: /languages/
 */


class ResetTheNetSplashScreen{
	/** Version ***************************************************************/

	/**
	 * @public string plugin version
	 */
	public $version = '1.01';
        public $db_version = '0100';
	
	/** Paths *****************************************************************/

	public $file = '';
	
	/**
	 * @public string Basename of the plugin directory
	 */
	public $basename = '';
	
	/**
	 * @public string Prefix for the plugin
	 */
        public $prefix = '';

	/**
	 * @public string Absolute path to the plugin directory
	 */
	public $plugin_dir = '';
        
	/**
	 * @public string Absolute path to the plugin directory
	 */
	public $plugin_url = '';
        
	/**
	 * @var The one true Instance
	 */
	private static $instance;

	/**
	 * Main Instance
	 *
	 * Insures that only one instance of the plugin exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @staticvar array $instance
	 * @uses ukeGeeks::setup_globals() Setup the globals needed
	 * @uses ukeGeeks::includes() Include the required files
	 * @uses ukeGeeks::setup_actions() Setup the hooks and actions
	 * @see ukegeeks()
	 * @return The instance
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new ResetTheNetSplashScreen;
			self::$instance->setup_globals();
			self::$instance->includes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}
        
	/**
	 * A dummy constructor to prevent the plugin from being loaded more than once.
	 */
	private function __construct() { /* Do nothing here */ }

        
        function setup_globals(){
            global $wpdb;
            
            /** Paths *************************************************************/
            $this->file       = __FILE__;
            $this->basename   = plugin_basename( $this->file );
            $this->plugin_dir = plugin_dir_path( $this->file );
            $this->plugin_url = plugin_dir_url ( $this->file );
            $this->prefix = 'rtnss';
        }
 
        
        function includes(){
        }
        
        function setup_actions(){

            //localization
            //add_action('init', array($this, 'load_plugin_textdomain'));
            
            //upgrade
            add_action( 'plugins_loaded', array($this, 'upgrade'));
            
            //scripts & styles
            add_action('init', array($this, 'register_scripts_styles'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
      
        }
        
        public function load_plugin_textdomain(){
            load_plugin_textdomain($this->basename, FALSE, $this->plugin_dir.'/languages/');
        }
        
        function upgrade(){
            global $wpdb;
            
            $db_meta_name = $this->prefix."-db_version";
            
            $current_version = get_option($db_meta_name);
            
            
            if ($current_version==$this->db_version) return false;
                
            //install
            /*
            if(!$current_version){
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }
             */

            //update DB version
            update_option($db_meta_name, $this->db_version );
        }
        
        function register_scripts_styles(){
            wp_register_script('rtnss_remote','//fightforthefuture.github.io/reset-the-net-widget/widget/rtn.js',false,$this->version, true);
        }

        function enqueue_scripts_styles() {
                wp_enqueue_script('rtnss_remote');
        }
        
}

/**
 * The main function responsible for returning the one true Instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @return The one true Instance
 */

function rtnss() {
	return ResetTheNetSplashScreen::instance();
}

rtnss();