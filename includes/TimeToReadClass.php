<?php
/**
 * Main plugin class
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadClass') ) {
  class TimeToReadClass {
    
    /**
     * Version
     * 
     * @since 1.0.0
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class.
     * 
     * @since 1.0.0
     */
	  protected static $_instance = null;

    /**
     * Constructor
     * 
     * @since 1.0.0
     */
    public function __construct() {
      $this->define_constants();
      $this->includes();
      $this->hooks();
    }

    /**
     * Main Instance.
     *
     * Ensures only one instance is loaded or can be loaded.
     *
     * @since 1.0.0
     */
    public static function instance() {
      if ( is_null( self::$_instance ) ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    /**
     * Includes
     * 
     * @since 1.0.0
     */
    public function includes() {
      // Instantiate admin class
      \lc\timetoread\includes\admin\TimeToReadAdmin::instance();

      // Integrate into wp
      \lc\timetoread\includes\TimeToReadIntegrate::instance();

      // Create rest endpoints
      \lc\timetoread\includes\apis\TimeToReadRest::instance();

      include_once TIMETOREAD_ABSPATH . 'helpers/general-helpers.php';
    }

    /**
     * Constants
     * 
     * @since 1.0.0
     */
    public function define_constants() {
      // absolute path
      $this->define( 'TIMETOREAD_ABSPATH', dirname(TIMETOREAD_FILE) . '/' );
      // version
      $this->define( 'TIMETOREAD_VERSION', $this->version );
      // text domain
      $this->define( 'TIMETOREAD_TEXT_DOMAIN', 'time-to-read' );
      // admin url
      $this->define( 'TIMETOREAD_URL', $this->plugin_url() );
      // Assets folder public
      $this->define( 'TIMETOREAD_ASSETS_PUBLIC_URL', TIMETOREAD_URL . 'assets/public/' );
      // assets folder admin
      $this->define( 'TIMETOREAD_ASSETS_ADMIN_URL', TIMETOREAD_URL . 'assets/admin/' );
      // images folder
      $this->define( 'TIMETOREAD_IMAGES', TIMETOREAD_URL . 'images/');
      // templates folder
      $this->define( 'TIMETOREAD_TEMPLATES',  TIMETOREAD_ABSPATH . 'templates/');
      // Options data name
      $this->define( 'TIMETOREAD_OPTION_NAME', 'time_to_read_options');
      // Meta data name
      $this->define( 'TIMETOREAD_META_NAME', 'time_to_read_postmeta');
    }

    /**
     * Hooks
     * 
     * @since 1.0.0
     */
    public function hooks() {

      /**
       * Register frontend scripts
       * 
       * @since 1.0.0
       */
      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

      /**
       * Register frontend styles
       * 
       * @since 1.0.0
       */
      add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    /**
     * Enqueue primary scripts
     * 
     * @since 1.0.0
     */
    public function enqueue_scripts() {

      // Main frontend js script

    }

    /**
     * Enqueue primary stylesheets
     * 
     * @since 1.0.0
     */
    public function enqueue_styles() {
      // locations frontend css stylesheet
      if( ! wp_style_is('timetoread-css', 'enqueued') ) {
        wp_enqueue_style('timetoread-css', TIMETOREAD_ASSETS_PUBLIC_URL . 'css/timetoread.css', array(), '1.0.0');
      }

    }

    /**
     * Define constants is not set
     * 
     * @since 1.0.0
     */
    public function define($name, $value) {
      if( ! defined($name) ) {
        define($name, $value);
      }
    }

    /**
     * Get the plugin url.
     *
     * @since 1.0.0
     */
    public function plugin_url() {
      return trailingslashit( plugins_url( '/', TIMETOREAD_FILE ) );
    }

    /**
     * Get the plugin path.
     *
     * @since 1.0.0
     */
    public function plugin_path() {
      return trailingslashit( plugin_dir_path( TIMETOREAD_FILE ) );
    }
    
  }

}