<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', function () {
    add_options_page( 'Artificial Maintenance', 'Maintenance', 'manage_options',
                      'artmaint_settings', 'artmaint_render_settings' );

    foreach ([
        'artmaint_enabled','artmaint_html','artmaint_logo','artmaint_title','artmaint_desc',
        'artmaint_bg_type','artmaint_bg_img','artmaint_bg_vid','artmaint_bg_yt','artmaint_bg_poster',
        'artmaint_title_glow','artmaint_show_login'
    ] as $opt){
        register_setting( 'artmaint_settings', $opt );
    }
});

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'settings_page_artmaint_settings' !== $hook ) return;
    wp_enqueue_media();
    wp_enqueue_script( 'artmaint-uploader', plugin_dir_url(__FILE__).'js/uploader.js',
                       [ 'jquery' ], ARTMAINT_VERSION, true );
});

function artmaint_render_settings(){
    $bg = get_option( 'artmaint_bg_type', 'none' );
    $val = fn($k,$d='') => esc_attr( get_option($k,$d) );
?>
<div class="wrap"><h1>Artificial Maintenance</h1>
<form method="post" action="options.php">
<?php settings_fields('artmaint_settings'); ?>
<table class="form-table">
<tr><th>Enable</th><td><input type="checkbox" name="artmaint_enabled" value="1" <?php checked(get_option('artmaint_enabled'),1);?>></td></tr>

<tr><th>Show lock/login</th><td><label><input type="checkbox" name="artmaint_show_login" value="1" <?php checked(get_option('artmaint_show_login'),1);?>> Enable lock + login form</label></td></tr>
<tr><th>Glow title</th><td><label><input type="checkbox" name="artmaint_title_glow" value="1" <?php checked(get_option('artmaint_title_glow'),1);?>> Glow title like HTML block</label></td></tr>

<tr><th>Custom HTML</th><td><textarea name="artmaint_html" rows="8" cols="60" class="large-text code"><?php echo esc_textarea(get_option('artmaint_html',''));?></textarea></td></tr>

<?php $logo=$val('artmaint_logo'); ?>
<tr><th>Logo</th><td>
  <input id="am-logo-url" class="regular-text" name="artmaint_logo" value="<?php echo $logo; ?>">
  <button type="button" id="am-logo-upload" class="button">Upload</button>
  <button type="button" id="am-logo-remove" class="button">Remove</button><br><br>
  <img id="am-logo-preview" src="<?php echo $logo; ?>" style="max-width:150px;<?php echo $logo?'':'display:none'; ?>">
</td></tr>

<tr><th>Title</th><td><input class="regular-text" name="artmaint_title" value="<?php echo $val('artmaint_title','Site Under Maintenance'); ?>"></td></tr>
<tr><th>Description</th><td><textarea name="artmaint_desc" rows="3" cols="60" class="large-text"><?php echo esc_textarea(get_option('artmaint_desc',"We'll be back shortly."));?></textarea></td></tr>

<tr><th>Background type</th><td>
<label><input type="radio" name="artmaint_bg_type" value="none" <?php checked($bg,'none');?>> None</label><br>
<label><input type="radio" name="artmaint_bg_type" value="image" <?php checked($bg,'image');?>> Image URL</label><br>
<label><input type="radio" name="artmaint_bg_type" value="video" <?php checked($bg,'video');?>> MP4 URL</label><br>
<label><input type="radio" name="artmaint_bg_type" value="youtube" <?php checked($bg,'youtube');?>> YouTube link</label>
</td></tr>

<tr><th>Image URL</th><td><input class="regular-text" name="artmaint_bg_img" value="<?php echo $val('artmaint_bg_img'); ?>"></td></tr>
<tr><th>Video URL</th><td><input class="regular-text" name="artmaint_bg_vid" value="<?php echo $val('artmaint_bg_vid'); ?>"></td></tr>
<tr><th>Poster URL</th><td><input class="regular-text" name="artmaint_bg_poster" value="<?php echo $val('artmaint_bg_poster'); ?>"></td></tr>
<tr><th>YouTube URL</th><td><input class="regular-text" name="artmaint_bg_yt" value="<?php echo $val('artmaint_bg_yt'); ?>"></td></tr>
</table><?php submit_button(); ?></form></div>
<?php } ?>
