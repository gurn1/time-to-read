<?php
/**
 * Abstract class for registering options pages
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\admin\metaboxes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadAbstractMetabox') ) {
  abstract class TimeToReadAbstractMetabox {

    /**
     * ID
     * 
     * @since 1.0.0
     * @return string
     */
    public static $ID = '';

    /**
     * Title
     * 
     * @since 1.0.0
     * @return string
     */
    public static $title = '';

    /**
     * Where to show metabox
     * 
     * @since 1.0.0
     * @return array
     */
    public static $screen = [];

    /**
     * Context
     * 'normal', 'side', 'advanced'
     * 
     * @since 1.0.0
     * @return string
     */
    public static $context = 'normal';

    /**
     * Priority
     * 'high', 'core', 'default', 'low'
     * 
     * @since 1.0.0
     * @return string
     */
    public static $priority = 'default';

    /**
     * meta name
     * 
     * @since 1.0.0
     * @return string
     */
    public static $meta_name;

    /**
     * Nonce action
     * 
     * @since 1.0.0
     * @return string
     */
    private static $nonce_action = '';

    /**
     * Nonce name
     * 
     * @since 1.0.0
     * @return string
     */
    private static $nonce_name = '';

    /**
     * Register hooks
     * 
     * @since 1.0.0
     */
    public function __construct() {
      self::$meta_name = TIMETOREAD_META_NAME;

      // Generate nonce identifiers
      static::$nonce_action = static::$ID . '_action';
      static::$nonce_name   = static::$ID . '_nonce';

      // register the metabox
      add_action('add_meta_boxes', array($this, 'add_meta_box'));

      // save input fields
      add_action('save_post', array($this, 'handle_save'), 10, 3);
    }

    /**
     * Admin menu
     * 
     * @since 1.0.0
     */
    public function add_meta_box() { 
      global $post;

      $post_type = property_exists($post, 'post_type') ? $post->post_type : '';
      $options = \lc\timetoread\includes\data\TimeToReadDataOptions::instance();
      $post_type_checker = isset($options['posttype_selector']) ? $options['posttype_selector'] : [];

      if( is_array($post_type_checker) && array_key_exists($post_type, $post_type_checker) && $post_type_checker[$post_type] === '1' ) {
        add_meta_box(
          static::$ID,
          esc_html(static::$title),
          array($this, 'render_output'),
          static::$screen,
          static::$context,
          static::$priority 
        );

        if( !wp_style_is('timetoread-admin-css', 'enqueued') ) {
          wp_enqueue_style('timetoread-admin-css');
        }
      }

    }

    /**
     * Implement fields in subclass
     * 
     * @since 1.0.0
     */
    abstract protected static function register_fields();

    /**
     * Register fields
     * 
     * @since 1.0.0
     * @return string
     */
    protected static function render_fields() {
      $output =  new \lc\timetoread\includes\admin\fields\TimeToReadFieldsRender('post');

      foreach(static::register_fields() as $field_id => $field) {
        $label = isset($field['label']) ? esc_html($field['label']) : '';
        $type = isset($field['type']) ? esc_attr($field['type']) : 'text';
        $method = null;
        
        echo sprintf(
          "<div class='ttr-input-wpr %s'> 
            <label>%s</label>
            <div class='ttr-field'>
          ", 
          esc_attr(static::$context), esc_html($label)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        switch($type) {
          case 'text' : 
            $method = 'render_input_field';
            break;
          case 'checkbox' :
            $method ='render_checkbox_field';
            break;
          case 'radio' :
            $method = 'render_radio_field';
            break;
          case 'select' : 
            $method = 'render_select_field';
            break;
          case 'textarea' :
            $method = 'render_textarea_field';
            break;
          case 'datepicker' :
            $method = 'render_datepicker_field';
            break;
          case 'colorpicker' :
            $method = 'render_colorpicker_field';
            break;
          default :
            $method = 'render_input_field';
            break;
        }

        if(method_exists($output, $method)) {
          $args = [];
          $args['id'] = $field_id;
          $args = array_merge($args, $field);

          call_user_func([$output, $method], $args);
        } else {
          echo "<p class='error'>No field types found.</p>";
        }

        echo '</div></div>';
      }

    }

    /**
     * Handle the data to save
     * 
     * @since 1.0.0
     */
    public function handle_save($post_id, $post, $update) {
      // prevent inifite loop
      if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
      }

      // Check nonce
      // phpcs:ignore
      if(!isset($_POST[static::$nonce_name]) || !wp_verify_nonce(wp_unslash($_POST[static::$nonce_name]), static::$nonce_action)) {
        if(WP_DEBUG) {
          error_log(sprintf('Nonce verification failed for %s, on post #%s', static::$nonce_name, $post_id)); // phpcs:ignore
        }
        return;
      }

      // Check user permissions
      if( !current_user_can('edit_post', $post_id) ) {
        return;
      }

      // ensure the fields is an array and not empty
      if(!is_array(static::register_fields()) && !empty(static::register_fields())) {
        return;
      }

      // phpcs:ignore
      $raw_data = isset($_POST[self::$meta_name]) ? wp_unslash($_POST[self::$meta_name]) : [];
      $sanitized_data = [];

      if(empty($raw_data)) {
        return;
      }

      foreach(static::register_fields() as $key => $field) {
        $raw_value = isset($raw_data[$key]) ? $raw_data[$key] : null;
      
        switch ($field['type']) {
          case 'text':
          case 'radio':
          case 'select':
          case 'date':
              $sanitized_data[$key] = sanitize_text_field($raw_value);
              break;
          case 'textarea':
              $sanitized_data[$key] = sanitize_textarea_field($raw_value);
              break;
          case 'email':
              $sanitized_data[$key] = sanitize_email($raw_value);
              break;
          case 'url':
              $sanitized_data[$key] = esc_url_raw($raw_value);
              break;
          case 'number':
              $sanitized_data[$key] = intval($raw_value);
              break;
          case 'checkbox':
              $sanitized_data[$key] = isset($raw_value) && $raw_value ? 1 : 0;
              break;
          case 'color':
              $sanitized_data[$key] = sanitize_hex_color($raw_value);
              break;
          default:
              $sanitized_data[$key] = sanitize_text_field($raw_value); // Fallback
              break;
        }
      }

      update_post_meta($post_id, self::$meta_name, $sanitized_data);
    }

    /**
     * Render page
     * 
     * @since 1.0.0
     */
    public static function render_output() {
      global $post;

      if(!$post) {
        error_log(__METHOD__ . ': $post object not found.'); // phpcs:ignore
        return;
      }

      $template_path = TIMETOREAD_ABSPATH . '/includes/admin/metaboxes/views/metabox-' . static::$ID . '.php';

      // throw error if view template isn't found
      if(!file_exists($template_path)) {
        trigger_error(sprintf('The file %s, is missing from this plugin installation', esc_url($template_path)), E_USER_ERROR); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
      }

      new \lc\timetoread\includes\admin\fields\TimeToReadFieldsRender('post');

      // set the nonce
      wp_nonce_field(static::$nonce_action, static::$nonce_name);

      include_once($template_path);
    }

  }
}