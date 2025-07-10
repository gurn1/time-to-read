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
    public static $option_name = 'time_to_read_options';

    /**
     * Declare options variable
     * 
     * @since 1.0.0
     * @return array
     */
    private static $options = [];

    /**
     * Allowed html
     * 
     * @since 1.0.0
     */
    private static $allowed_html = array(
      'input' => array(
        'id' => true,
        'type' => true,
        'name' => true,
        'placeholder' => true,
        'value' => true,
        'class' => true,
      ),
      'textarea' => array(
        'id' => true,
        'name' => true,
        'placeholder' => true,
        'class' => true
      )
    );

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
      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_type = isset($args['type']) ? esc_attr($args['type']) : 'text';
      $field_placeholder = isset($args['placeholder']) ? esc_attr($args['placeholder']) : '';
      $field_value = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) : '';

      $name = self::$option_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<input class="regular-text" type="%s" name="%s" placeholder="%s" value="%s">', $field_type, $name, $field_placeholder, $field_value),
        self::$allowed_html
      );
    }

    /**
     * Render textarea field
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_textarea_field($args) {
      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_placeholder = isset($args['placeholder']) ? esc_attr($args['placeholder']) : '';
      $field_value = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) : '';

      $name = self::$option_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<textarea name="%s" placeholder="%s" class="large-text">%s</textarea>', $name, $field_placeholder, $field_value),
        self::$allowed_html
      );
    }

    /**
     * Render colour picker field
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_colorpicker_field($args) {
      if ( ! wp_script_is('wp-color-picker', 'enqueued') ) {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
      }

      // Register a dummy script handle to attach inline script
      wp_register_script('time-to-read-colorpicker-init', '', [], '', true);

      // Add the inline script once
      if ( ! wp_script_is('time-to-read-colorpicker-init', 'done') ) {
        wp_enqueue_script('time-to-read-colorpicker-init');

        wp_add_inline_script(
          'time-to-read-colorpicker-init',
          "jQuery(document).ready(function($) {
            $('.time-to-read-colorpicker').wpColorPicker();
          });"
        );
      }

      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_value = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) : '';
    
      $name = esc_attr(self::$option_name . '[' . $field_id . ']');

      echo wp_kses(
        sprintf('<input class="time-to-read-colorpicker regular-text" type="text" name="%s" value="%s">', $name, $field_value),
        self::$allowed_html
      );
    }

    /**
     * Render datepicker field
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_datepicker_field($args) {
      if( ! wp_script_is('jquery-ui-datepicker', 'enqueued')) {
        wp_enqueue_script('jquery-ui-datepicker');
      }

      // Register a custom init script (empty, just to attach inline script)
      wp_register_script('time-to-read-datepicker-init', '', [], '', true);
      
      // Only add the inline script once
      if ( ! wp_script_is('time-to-read-datepicker-init', 'done') ) {
        wp_enqueue_script('time-to-read-datepicker-init');

        wp_add_inline_script(
          'time-to-read-datepicker-init',
          "jQuery(document).ready(function($) {
            $('.time-to-read-datepicker').datepicker({
              dateFormat: 'dd-mm-yy'
            });
          });"
        );
      }

      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_value = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) : '';

      $name = self::$option_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<input class="time-to-read-datepicker regular-text" type="text" name="%s" value="%s">', $name, $field_value),
        self::$allowed_html
      );
    }


  }
}