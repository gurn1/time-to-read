<?php
/**
 * Abstract class for registering options pages
 * 
 * @version 1.0.0
 */

namespace lc\stimetoreadlsc\includes\admin\options;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadAbstractOption') ) {
  abstract class SimpleTimeToReadAbstractOption {

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
      self::$options_name = STIMETOREADLSC_OPTION_NAME;

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
        esc_html(static::$menu_title),
        esc_html(static::$menu_title),
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
        register_setting(
          static::$menu_slug . '_' . $setting_slug, 
          static::$options_name,
          [ 'sanitize_callback' => [ static::class, 'sanitize_options' ] ]
        );

        add_settings_section(
          sttr_generate_admin_settings_field_path($setting_slug),
          esc_html($setting_name),
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
     * Sanitize fields
     * 
     * @since 1.0.0
     */
    public static function sanitize_options($input, $saved = null) {
      // Load saved data only once, during the root call
      if ($saved === null) {
          $saved = get_option(STIMETOREADLSC_OPTION_NAME, []);
      }

      $sanitized = [];

      // Sanitize provided input recursively
      foreach ($input as $key => $value) {
          if (is_array($value)) {
              $sanitized[$key] = self::sanitize_options($value, $saved[$key] ?? []);
          } else {
              $sanitized[$key] = sanitize_text_field($value);
          }
      }

      // Now check for any keys in saved data that are missing in input
      foreach ($saved as $key => $value) {
          if (!array_key_exists($key, $sanitized)) {
              if (is_array($value)) {
                  // Recursively fill in missing nested arrays
                  $sanitized[$key] = self::fill_missing_keys([], $value);
              } else {
                  // If missing, assume it's an unchecked checkbox → set to 0
                  $sanitized[$key] = 0;
              }
          }
      }

      return $sanitized;
     
    }

    protected static function fill_missing_keys(array $input, array $reference): array {
      $result = [];

      foreach ($reference as $key => $value) {
          if (is_array($value)) {
              $result[$key] = isset($input[$key]) && is_array($input[$key])
                  ? self::fill_missing_keys($input[$key], $value)
                  : self::fill_missing_keys([], $value);
          } else {
              $result[$key] = isset($input[$key]) ? sanitize_text_field($input[$key]) : 0;
          }
      }

      return $result;
    }

    
    /**
     * Render page
     * 
     * @since 1.0.0
     */
    public static function render_page() {
      global $pagenow;

      $template_path = STIMETOREADLSC_ABSPATH . '/includes/admin/options/views/option-' . static::$menu_slug . '.php';

      // throw error if view template isn't found
      if(!file_exists($template_path)) {
        trigger_error(sprintf('The file %s, is missing from this plugin installation', esc_url($template_path)), E_USER_ERROR); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
      }

      // Throw error here if sections array is string or empty
      if (empty(static::$settings_name) || !is_array(static::$settings_name)) {
        throw new \RuntimeException(__METHOD__ . ': $settings_name must be a non-empty array.');
      }

      new \lc\stimetoreadlsc\includes\admin\fields\SimpleTimeToReadFieldsRender();

      $settings_section_path = static::$menu_slug;
      $tabs = static::$settings_name;
      $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : key(static::$settings_name); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
      $current_page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
      $url_path = admin_url() . $pagenow . '?page=' . $current_page; 

      if( !wp_style_is('timetoread-admin-css', 'enqueued') ) {
        wp_enqueue_style('timetoread-admin-css');
      }

      include_once($template_path);
    }

  }
}