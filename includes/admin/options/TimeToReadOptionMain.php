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
      $render_field_path = '\lc\timetoread\includes\admin\fields\TimeToReadfieldsRender';

      /** Test Input */
      add_settings_field(
        'test_field_setting',
        __('Test field setting', 'time-to-read'),
        array($render_field_path, 'render_colorpicker_field'),
        self::$menu_slug . '_settings_general',
        ttr_generate_admin_settings_field_path('settings_general'),
        array(
          'id' => 'settings_general',
          'placeholder' => 'Testing placeholder'
        ) 
      );

      /** Test Input */
      add_settings_field(
        'test_field_setting',
        __('Test field setting', 'time-to-read'),
        array($render_field_path, 'render_colorpicker_field'),
        self::$menu_slug . '_settings_style',
        ttr_generate_admin_settings_field_path('settings_style'),
        'time_to_read_settings_style_section',
        array(
          'id' => 'settings_general',
          'placeholder' => 'Testing placeholder'
        ) 
      );

    }

  }
}