<?php
/**
 * Render admin input fields
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\admin\fields;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadfieldsRender') ) {
  class TimeToReadfieldsRender {

    /**
     * Get the options table name
     * 
     * @since 1.0.0
     * @return string
     */
    public static $option_name = 'time_to_read_options';

    /**
     * Get the post meta name
     * 
     * @since 1.0.0
     * @return string
     */
    public static $meta_name = 'time_to_read_meta';

    /**
     * Field name
     * 
     * @since 1.0.0
     * @return string
     */
    public static $field_name = '';

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
        'type' => true,
        'name' => true,
        'placeholder' => true,
        'value' => true,
        'class' => true,
        'checked' => true
      ),
      'textarea' => array(
        'name' => true,
        'placeholder' => true,
        'class' =>true
      ),
      'label' => array(
        'for' => true,
        'class' =>true
      ),
      'select' => array(
        'name' => true,
        'class' =>true
      ),
      'option' => array(
        'value' => true,
        'selected' => true
      ),
      'div' => array(
        'class' => true
      )
    );

    /**
     * construct
     * 
     * @since 1.0.0
     */
    public function __construct($type = 'option') {
      if($type === 'post') {
        global $post;

        if(!$post) {
          return;
        }

        self::$options = get_post_meta($post->ID, self::$meta_name, true);
        self::$field_name = self::$meta_name;
      } else {
        self::$options = get_option(self::$option_name);
        self::$field_name = self::$option_name;
      } 
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

      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<input class="regular-text" type="%s" name="%s" placeholder="%s" value="%s">', $field_type, $name, $field_placeholder, $field_value),
        self::$allowed_html
      );
    }

    /**
     * Render checkbox field
     * 
     * @since 1.0.0
     * @return void
     */
    public static function render_checkbox_field($args) {
      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_label = isset($args['label']) ? esc_html($args['label']) : '';
      $checked = !empty(self::$options[$field_id]) ? self::$options[$field_id] : 0;
      
      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<label><input type="checkbox" name="%s" value="1" %s> %s</label>', $name, checked($checked, 1, false), $field_label),
        self::$allowed_html
      );
    }

    /**
     * Render radio field
     * 
     * @since 1.0.0
     * @return void
     */
    public static function render_radio_field($args) {
      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $choices = isset($args['choices']) && is_array($args['choices']) ? $args['choices'] : [];
      $selected = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) : '';
      
      $name = self::$field_name . '[' . $field_id . ']';

      foreach ($choices as $value => $label) {
        $checked = checked($selected, $value, false);

        echo wp_kses(
          sprintf('<label><input type="radio" name="%s" value="%s" %s> %s</label><br>', $name, esc_attr($value), $checked, esc_html($label)),
          self::$allowed_html
        );
      }
    }

    /**
     * Render select dropdown field
     * 
     * @since 1.0.0
     * @return void
     */
    public static function render_select_field($args) {
      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $choices = isset($args['choices']) && is_array($args['choices']) ? $args['choices'] : [];
      $selected = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) : '';
      
      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses( 
        sprintf('<select name="%s" class="regular-text">', esc_attr($name)), 
        self::$allowed_html
      );

      foreach ($choices as $value => $label) {
        echo wp_kses(
          sprintf('<option value="%s"%s>%s</option>', esc_attr($value), selected($selected, $value, false), esc_html($label)),
          self::$allowed_html
        );
      }

      echo '</select>';
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

      $name = self::$field_name . '[' . $field_id . ']';

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
      wp_register_script('time-to-read-colorpicker-init', '', [], '1.0.0', true);

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
    
      $name = self::$field_name . '[' . $field_id . ']';

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
      wp_register_script('time-to-read-datepicker-init', '', [], '1.0.0', true);
      
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

      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<input class="time-to-read-datepicker regular-text" type="text" name="%s" value="%s">', $name, $field_value),
        self::$allowed_html
      );
    }

    /**
     * Post type selector
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_posttype_field($args) {
      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_value = isset(self::$options[$field_id]) ? self::$options[$field_id] : '';
      
      $post_types = get_post_types([
        'public' => true,
        '_builtin' => true
      ], 'objects');

      // Exclusions 
      unset($post_types['attachment' ]);


      if( !empty($post_types)) {
        foreach($post_types as $post_type ) {
          $name = self::$field_name . '[' . $field_id . ']['.$post_type->name.']';
          $checked = !empty($field_value[$post_type->name]) ? $field_value[$post_type->name] : 0;

          echo wp_kses(
            sprintf('<div><label><input type="checkbox" name="%s" value="1" %s> %s</label></div>', $name, checked($checked, 1, false), $post_type->label),
            self::$allowed_html
          );
        }
      } 
       
    }

  }
}