<?php
/**
 * Installation related hooks and actions
 * 
 * @version 1.0.0
 */

namespace lc\sttrlsc\includes\admin\options;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadOptionMain') ) {
  class SimpleTimeToReadOptionMain extends SimpleTimeToReadAbstractOption {

    /**
     * Menu slug
     * 
     * @since 1.0.0
     * @return string
     */
    public static $menu_slug = 'simple-time-to-read-lsc-main';

    /**
     * Menu title
     * 
     * @since 1.0.0
     * @return string
     */
    public static $menu_title = 'Simple Time to Read';

    /**
     * Declare option sections
     * 
     * @since 1.0.0
     * @return array
     */
    public static $settings_name = [
      'settings_general' => 'General',
      'settings_style' => 'Styles'
    ];

    /**
     * Register fields
     * 
     * @since 1.0.0
     * @return string
     */
    protected function register_fields($settings_name) {
      $render_field_path = '\lc\sttrlsc\includes\admin\fields\SimpleTimeToReadFieldsRender';

      add_settings_field(
        'posttype_general_select_posttype',
        __('Show on post types', 'simple-time-to-read-lsc'),
        array($render_field_path, 'render_posttype_field'),
        self::$menu_slug . '_settings_general',
        sttr_generate_admin_settings_field_path('settings_general'),
        array(
          'id' => 'posttype_selector',
          'description' => 'Select which post types time to read should display on.'
        )
      );

      add_settings_field(
        'reading_time_text',
        __('Reading time text', 'simple-time-to-read-lsc'),
        array($render_field_path, 'render_input_field'),
        self::$menu_slug . '_settings_general',
        sttr_generate_admin_settings_field_path('settings_general'),
        array(
          'id' => 'reading_time_text'
        )
      );

      /** Disable stylesheet */
      add_settings_field(
        'disable_stylesheet',
        __('Disable Simple Time To Read stylesheet', 'simple-time-to-read-lsc'),
        array($render_field_path, 'render_checkbox_field'),
        self::$menu_slug . '_settings_style',
        sttr_generate_admin_settings_field_path('settings_style'),
        array(
          'id' => 'disable_stylesheet'
        ) 
      );

    }

  }
}