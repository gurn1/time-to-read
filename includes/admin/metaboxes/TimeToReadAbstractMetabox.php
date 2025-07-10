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
      self::$meta_name = \lc\timetoread\includes\admin\fields\TimeToReadfieldsRender::$meta_name;

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
      add_meta_box(
        esc_html__(static::$ID, TIMETOREAD_TEXT_DOMAIN),
        esc_html__(static::$title, TIMETOREAD_TEXT_DOMAIN),
        array($this, 'render_output'),
        static::$screen,
        static::$context,
        static::$priority  
      );
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
      $output =  new \lc\timetoread\includes\admin\fields\TimeToReadfieldsRender('post');

      foreach(static::register_fields() as $field_id => $field) {
        $label = isset($field['label']) ? esc_html($field['label']) : '';
        $type = isset($field['type']) ? esc_attr($field['type']) : 'text';
        $method = null;
        
        echo "
          <div class='input-wpr'>
            <label>$label</label>
            <div class='field'>
        ";

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
          call_user_func([$output, $method], [
            'id' => $field_id,
            'name' => isset($field['name']) ? esc_attr($field['name']) : '',
            'placeholder' => isset($field['placeholder']) ? esc_attr($field['placeholder']) : '',
            'class' => isset($field['class']) ? esc_attr($field['class']) : ''
          ]);
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
      if(!isset($_POST[static::$nonce_name]) || !wp_verify_nonce($_POST[static::$nonce_name], static::$nonce_action)) {
        if(WP_DEBUG) {
          error_log(sprintf('Nonce verification failed for %s, on post #%s', static::$nonce_name, $post_id));
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

      $field_keys = array_keys(static::register_fields());
      $raw_data = isset($_POST[self::$meta_name]) ? $_POST[self::$meta_name] : [];

      if(empty($raw_data)) {
        return;
      }

      foreach($raw_data as $data ) {
        
      }
      error_log(wp_json_encode($raw_data));

      foreach($field_keys as $field) {
        if(isset($_POST[$field]) ) {
          update_post_meta($post_id, self::$meta_name, sanitize_text_field($_POST[$field]));
        }
      }

      //update_post_meta($post_id, self::$meta_name, )
    }

    /**
     * Render page
     * 
     * @since 1.0.0
     */
    public static function render_output() {
      global $post;

      if(!$post) {
        error_log(__METHOD__ . ': $post object not found.');
        return;
      }

      $template_path = TIMETOREAD_ABSPATH . '/includes/admin/metaboxes/views/metabox-' . static::$ID . '.php';

      // throw error if view template isn't found
      if(!file_exists($template_path)) {
        trigger_error(sprintf('The file %s, is missing from this plugin installation', $template_path), E_USER_ERROR);
      }

      new \lc\timetoread\includes\admin\fields\TimeToReadfieldsRender('post');

      // set the nonce
      wp_nonce_field(static::$nonce_action, static::$nonce_name);

      include_once($template_path);
    }

  }
}