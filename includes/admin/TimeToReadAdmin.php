<?php
/**
 * Installation related hooks and actions
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\admin;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadAdmin') ) {
  class TimeToReadAdmin {
    
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
      // Options
      //\rti\engine\includes\admin\options\RTIEngineOptions::instance();
      // Metaboxes
      //\rti\engine\includes\admin\metaboxes\RTIEngineMetaBoxesLessons::instance();
      // Tables
      //\rti\engine\includes\admin\tables\RTIEngineTables::instance();
    }

    /**
     * Hooks
     * 
     * @since 1.0.0
     */
    public function hooks() {
      
      /**
       * Register menu item
       * 
       * @since 1.0.0
       */
      add_action('admin_menu', array($this, 'add_admin_menus'));

      /**
       * Register admin scripts
       * 
       * @since 1.0.0
       */
      add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

      /**
       * Register admin styles
       * 
       * @since 1.0.0
       */
      add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));

    }

    /**
     * Enqueue primary scripts
     * 
     * @since 1.0.0
     */
    public function enqueue_scripts() {
      // Main admin js script
      if( ! wp_script_is('timetoread-admin', 'enqueued') ) {
        wp_enqueue_script('timetoread-admin', TIMETOREAD_ASSETS_ADMIN_URL . 'js/rti-engine-admin.js', array(), '1.0.0', true);
      }

      wp_localize_script(
        'timetoread-admin',
        'timetoreadObject',
        array(
          'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
        )
      );

    }

    /**
     * Enqueue primary stylesheets
     * 
     * @since 1.0.0
     */
    public function enqueue_styles() {
      // locations frontend css stylesheet
      if( ! wp_style_is('timetoread-admin-css', 'registered') ) {
        wp_register_style('timetoread-admin-css', TIMETOREAD_ASSETS_ADMIN_URL . 'css/timetoread-admin.css', array(), '1.0.0');
      }

    }

    /**
     * Admin menus
     * 
     * @since 1.0.0
     */
    public function add_admin_menus() {

      /**
       * Register primary menu page
       * 
       * @since 1.0.0
       * @return string|false
       */
      add_management_page( 
        esc_html__('Time to Read', TIMETOREAD_TEXT_DOMAIN),
        esc_html__('Time to Read', TIMETOREAD_TEXT_DOMAIN),
        'manage_options',
        'time-to-read',
        array('\lc\timetoread\includes\admin\options\TimeToReadOptionMain', 'render_page'),
        100
      );
    }

  }
}