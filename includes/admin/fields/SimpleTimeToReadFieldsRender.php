<?php
/**
 * Render admin input fields
 * 
 * @version 1.0.0
 */

namespace lc\sttrlsc\includes\admin\fields;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadFieldsRender') ) {
  class SimpleTimeToReadFieldsRender {

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
     * Defaults
     * 
     * @since 1.0.0
     * @return array
     */
    private static $defaults = [];

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

        self::$options = \lc\sttrlsc\includes\data\SimpleTimeToReadDataMeta::instance($post->ID);
        self::$field_name = STTRLSC_META_NAME;
      } else {
        self::$options = \lc\sttrlsc\includes\data\SimpleTimeToReadDataOptions::instance();
        self::$field_name = STTRLSC_OPTION_NAME;
      } 

      self::$defaults = \lc\sttrlsc\includes\data\SimpleTimeToReadDataDefaults::instance($type);
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
      $field_description = isset($args['description']) ? $args['description'] : '';

      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<input class="regular-text" type="%s" name="%s" placeholder="%s" value="%s">', $field_type, $name, $field_placeholder, $field_value),
        self::$allowed_html
      );

      if( $field_description ) {
        echo sprintf('<p class="description">%s</p>', esc_html($field_description));
      }
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
      $field_description = isset($args['description']) ? $args['description'] : '';
      $checked = !empty(self::$options[$field_id]) ? self::$options[$field_id] : 0;
      
      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<label><input type="checkbox" name="%s" value="1" %s> %s</label>', $name, checked($checked, 1, false), $field_label),
        self::$allowed_html
      );

      if( $field_description ) {
        echo sprintf('<p class="description">%s</p>', esc_html($field_description));
      }
    }

    /**
     * Render radio field
     * 
     * @since 1.0.0
     * @return void
     */
    public static function render_radio_field($args) {
      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_description = isset($args['description']) ? $args['description'] : '';
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

      if( $field_description ) {
        echo sprintf('<p class="description">%s</p>', esc_html($field_description));
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
      $field_description = isset($args['description']) ? $args['description'] : '';
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

      if( $field_description ) {
        echo sprintf('<p class="description">%s</p>', esc_html($field_description));
      }
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
      $field_description = isset($args['description']) ? $args['description'] : '';

      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<textarea name="%s" placeholder="%s" class="large-text">%s</textarea>', $name, $field_placeholder, $field_value),
        self::$allowed_html
      );

      if( $field_description ) {
        echo sprintf('<p class="description">%s</p>', esc_html($field_description));
      }
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
      wp_register_script('simple-time-to-read-lsc-colorpicker-init', '', [], '1.0.0', true);

      // Add the inline script once
      if ( ! wp_script_is('simple-time-to-read-lsc-colorpicker-init', 'done') ) {
        wp_enqueue_script('simple-time-to-read-lsc-colorpicker-init');

        wp_add_inline_script(
          'simple-time-to-read-lsc-colorpicker-init',
          "jQuery(document).ready(function($) {
            $('.simple-time-to-read-lsc-colorpicker').wpColorPicker();
          });"
        );
      }

      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_value = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) : '';
      $field_description = isset($args['description']) ? $args['description'] : '';
    
      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<input class="simple-time-to-read-lsc-colorpicker regular-text" type="text" name="%s" value="%s">', $name, $field_value),
        self::$allowed_html
      );

      if( $field_description ) {
        echo sprintf('<p class="description">%s</p>', esc_html($field_description));
      }
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
      wp_register_script('simple-time-to-read-lsc-datepicker-init', '', [], '1.0.0', true);
      
      // Only add the inline script once
      if ( ! wp_script_is('simple-time-to-read-lsc-datepicker-init', 'done') ) {
        wp_enqueue_script('simple-time-to-read-lsc-datepicker-init');

        wp_add_inline_script(
          'simple-time-to-read-lsc-datepicker-init',
          "jQuery(document).ready(function($) {
            $('.simple-time-to-read-lsc-datepicker').datepicker({
              dateFormat: 'dd-mm-yy'
            });
          });"
        );
      }

      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_value = isset(self::$options[$field_id]) ? esc_attr(self::$options[$field_id]) : '';
      $field_description = isset($args['description']) ? $args['description'] : '';

      $name = self::$field_name . '[' . $field_id . ']';

      echo wp_kses(
        sprintf('<input class="simple-time-to-read-lsc-datepicker regular-text" type="text" name="%s" value="%s">', $name, $field_value),
        self::$allowed_html
      );

      if( $field_description ) {
        echo sprintf('<p class="description">%s</p>', esc_html($field_description));
      }
    }

    /**
     * Post type selector
     * 
     * @since 1.0.0
     * @return string
     */
    public static function render_posttype_field($args) {
      $field_id = isset($args['id']) ? esc_attr($args['id']) : '';
      $field_default = isset(self::$defaults[$field_id]) ? self::$defaults[$field_id] : [];
      $field_value = isset(self::$options[$field_id]) ? self::$options[$field_id] : '';
      $field_description = isset($args['description']) ? $args['description'] : '';
      
      $post_types = get_post_types([
        'public' => true
      ], 'objects');

      // Exclusions 
      unset($post_types['attachment']);

      if( !empty($post_types)) {
        foreach($post_types as $post_type ) {
          $name = self::$field_name . '[' . $field_id . ']['.$post_type->name.']';
          $checked = !empty($field_value[$post_type->name]) ? $field_value[$post_type->name] : 0;

          echo wp_kses(
            sprintf('<div><label><input type="checkbox" name="%s" value="1" %s> %s</label></div>', $name, checked($checked, 1, false), $post_type->label),
            self::$allowed_html
          );
        }

        if( $field_description ) {
          echo sprintf('<p class="description">%s</p>', esc_html($field_description));
        }
      } 
       
    }

  }
}