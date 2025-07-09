<?php
/**
 * Abstract class for registering options pages
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\admin\options;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadAbstractOption') ) {
  abstract class TimeToReadAbstractOption {

    /**
     * Menu slug
     * 
     * @since 1.0.0
     * @return string
     */
    public static $menu_slug = '';

    /**
     * Menu title
     * 
     * @since 1.0.0
     * @return string
     */
    public static $menu_title = '';

    /**
     * Setting name
     * 
     * @since 1.0.0
     * @return array
     */
    public static $settings_name = [];

    /**
     * Options name
     * 
     * @since 1.0.0
     * @return string
     */
    public static $options_name;

    /**
     * Register hooks
     * 
     * @since 1.0.0
     */
    public function __construct() {
      self::$options_name = \lc\timetoread\includes\admin\options\TimeToReadOptionInputRender::$option_name;

      add_action('admin_menu', array($this, 'add_admin_menu'));
      add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Admin menu
     * 
     * @since 1.0.0
     */
    public function add_admin_menu() {
      add_management_page( 
        esc_html__(static::$menu_title, TIMETOREAD_TEXT_DOMAIN),
        esc_html__(static::$menu_title, TIMETOREAD_TEXT_DOMAIN),
        'manage_options',
        static::$menu_slug,
        array($this, 'render_page'),
        100
      );
    }

    /**
     * Register settings
     * 
     * @since 1.0.0
     */
    public function register_settings() {
      foreach (static::$settings_name as $setting_slug => $setting_name) {
        register_setting(static::$menu_slug . '_' . $setting_slug, static::$options_name);

        add_settings_section(
          ttr_generate_admin_settings_field_path($setting_slug),
          __($setting_name, TIMETOREAD_TEXT_DOMAIN),
          '__return_false',
          static::$menu_slug . '_' . $setting_slug
        );

        $this->register_fields($setting_slug);
      }
    }

    /**
     * Implement fields in subclass
     * 
     * @since 1.0.0
     */
    abstract protected function register_fields($setting_name);
    
    /**
     * Render page
     * 
     * @since 1.0.0
     */
    public static function render_page() {
      global $pagenow;

      $template_path = TIMETOREAD_ABSPATH . '/includes/admin/options/views/option-' . static::$menu_slug . '.php';

      // throw error if view template isn't found
      if( ! file_exists($template_path) ) {
        trigger_error(sprintf('The file %s, is missing from this plugin installation', $template_path), E_USER_ERROR);
      }

      // Throw error here if sections array is string or empty
      if ( empty(static::$settings_name) || ! is_array(static::$settings_name) ) {
        throw new \RuntimeException(__METHOD__ . ': $settings_name must be a non-empty array.');
      }

      new \lc\timetoread\includes\admin\options\TimeToReadOptionInputRender();

      $settings_section_path = static::$menu_slug;
      $tabs = static::$settings_name;
      $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : key(static::$settings_name);
      $current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
      $url_path = admin_url() . $pagenow . '?page=' . $current_page; 

      include_once($template_path);
    }

  }
}