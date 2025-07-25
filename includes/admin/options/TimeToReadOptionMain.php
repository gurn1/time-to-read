<?php
/**
 * Installation related hooks and actions
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\admin\options;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadOptionMain') ) {
  class TimeToReadOptionMain extends TimeToReadAbstractOption {

    /**
     * Menu slug
     * 
     * @since 1.0.0
     * @return string
     */
    public static $menu_slug = 'time-to-read-main';

    /**
     * Menu title
     * 
     * @since 1.0.0
     * @return string
     */
    public static $menu_title = 'Time to Read';

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
      $render_field_path = '\lc\timetoread\includes\admin\fields\TimeToReadFieldsRender';

      add_settings_field(
        'posttype_general_select_posttype',
        __('Select post type', 'time-to-read'),
        array($render_field_path, 'render_posttype_field'),
        self::$menu_slug . '_settings_general',
        ttr_generate_admin_settings_field_path('settings_general'),
        array(
          'id' => 'posttype_selector',
        )
      );

      add_settings_field(
        'reading_time_text',
        __('Reading time text', 'time-to-read'),
        array($render_field_path, 'render_input_field'),
        self::$menu_slug . '_settings_general',
        ttr_generate_admin_settings_field_path('settings_general'),
        array(
          'id' => 'reading_time_text'
        )
      );

      /** Test Input */
      add_settings_field(
        'test_field_setting',
        __('Test field setting', 'time-to-read'),
        array($render_field_path, 'render_colorpicker_field'),
        self::$menu_slug . '_settings_style',
        ttr_generate_admin_settings_field_path('settings_style'),
        array(
          'id' => 'settings_color',
          'placeholder' => 'Testing placeholder'
        ) 
      );

      add_settings_field(
        'text_field_setting',
        __('Text field setting', 'time-to-read'),
        array($render_field_path, 'render_input_field'),
        self::$menu_slug . '_settings_style',
        ttr_generate_admin_settings_field_path('settings_style'),
        array(
          'id' => 'settings_text',
          'placeholder' => 'Testing placeholder'
        ) 
      );

    }

  }
}