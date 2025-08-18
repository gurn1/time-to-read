<?php
/**
 * Abstract class for registering blocks
 * 
 * @version 1.0.0
 */

namespace lc\sttrlsc\includes\blocks;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadAbstractBlock') ) {
  class SimpleTimeToReadAbstractBlock {

    /**
     * Block name
     * 
     * @since 1.0.0
     * @return string
     */
    public static $name = '';

    /**
     * Callback
     * 
     * @since 1.0.0
     * @return string
     */
    public static $callback = '';

    /**
     * Path
     * 
     * @since 1.0.0
     * @return string
     */
    private static $path = '';

    /**
     * Manifest path
     * 
     * @since 1.0.0
     * @return string 
     */
    private static $manifest = '';

    /**
     * Constructor
     * 
     * @since 1.0.0
     */
    public function __construct() {
      self::$path = trailingslashit(STTRLSC_BLOCK_PATH . static::$name);
      self::$manifest = self::$path . 'build/blocks-manifest.php';

      add_action('init', array($this, 'register_block'));
    }

    /**
     * Manifest paht 
     */

    /**
     * Block exists 
     * 
     * @since 1.0.0
     * @return bool
     */
    private static function block_exists() {
      return file_exists(self::$manifest);
    }

    /**
     * Manifest data
     * 
     * @since 1.0.0
     * @return array
     */
    protected static function manifest_data() {
      return require self::$manifest;
    }

    /**
     * Register block
     * 
     * @since 1.0.0
     */
    public function register_block() {
      // Check the manifest exists before registering the block. 
      if( ! $this->block_exists() ) {
        return;
      }

      if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		    wp_register_block_metadata_collection( self::$path . 'build', self::$manifest );
	    }

      foreach ( array_keys( self::manifest_data() ) as $block_type ) {
        $block_path = static::$path . "build/{$block_type}";
        $callback  = array();

				if ( static::$callback && is_callable( static::$callback ) ) {
					$callback['render_callback'] = static::$callback;
				}

				register_block_type( $block_path, $callback );
		    
	    }

    }
    
  }

}