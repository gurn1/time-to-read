<?php
/**
 * Main admin view for option page time to read
 * 
 * @since 1.0.0
 */

?>

<div class="wrap">
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
    <?php settings_fields($settings_section_path . '_' . $active_tab); ?>
    <?php do_settings_sections($settings_section_path . '_' . $active_tab ); ?>
    <?php submit_button(); ?>
  </form>
</div>