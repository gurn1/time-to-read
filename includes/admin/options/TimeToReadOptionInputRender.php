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

if( ! class_exists('TimeToReadOptionInputRender') ) {
  class TimeToReadOptionInputRender {

    /**
     * Get the options table name
     * 
     * @since 1.0.0
     * @return string
     */
    private static $option_name = 'time_to_read_options';

    /**
     * Declare options variable
     * 
     * @since 1.0.0
     * @return array
     */
    private static $options = [];

    /**
     * construct
     * 
     * @since 1.0.0
     */
    public function __construct() {
      self::$options = get_option(self::$option_name);
    }

    /**
     * Render basic input fields
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_input_field($args) {
      $field_id = isset($args['id']) ?? sanitize_text_field($args['id']);
      $field_type = isset($args['type']) ? sanitize_text_field($args['type']) : 'text';
      $field_placeholder = isset($args['placeholder']) ? sanitize_text_field($args['placeholder']) : '';
      $field_value = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) ? '';

      echo sprintf('<input class="regular-text" type="%s" name="%s" placeholder="%s" value="%s">', $field_type, self::$option_name . '[' . $field_id . ']', $field_placeholder, $field_value);
    }

    /**
     * Render textarea field
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_textarea_field($args) {

    }

    /**
     * Render colour picker field
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_color_picker_field($args) {

    }

    /**
     * Render datepicker field
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_date_picker_field($args) {

    }


  }
}