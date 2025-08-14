<?php
/**
 * Main admin view for option page time to read
 * 
 * @since 1.0.0
 */

if( ! defined('ABSPATH')) :
  exit; // Exit if accessed directly
endif;
?>

<div class="wrap ttr-option-wpr">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

  <?php if(count($tabs) > 1) : ?>
    <nav class="nav-tab-wrapper">
      <?php foreach($tabs as $tab_slug => $tab_name) : ?>
        <?php $is_active = ($active_tab === $tab_slug) ? 'active' : ''; ?>

        <a href="<?php echo esc_url($url_path) . '&tab=' . esc_attr($tab_slug); ?>" class="nav-tab <?php echo esc_attr($is_active); ?>"><?php echo esc_html($tab_name); ?></a>
      <?php endforeach; ?>
    </nav>
  <?php endif; ?>

  <form method="post" action="options.php">
    <?php if(is_array($tabs) && !empty($tabs)) : ?>
      <?php foreach($tabs as $slug => $name ) : ?>
        <div class="tab-content <?php echo $active_tab === $slug ? 'active' : ''; ?>">
          <?php settings_fields($settings_section_path . '_' . $slug); ?>
          <?php do_settings_sections($settings_section_path . '_' . $slug ); ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php submit_button(); ?>
  </form>
</div>