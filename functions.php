<?php
  $officer_post_type = 'ccc_officer';
  $officer_meta_name = $officer_post_type . '_meta';
  $nonce_id = $officer_post_type . '_nonce';

  add_action('init', 'ccc_init');
  add_action('admin_init', 'ccc_admin_init');
  add_action('save_post', 'ccc_save_post', 1, 2);
  
  function ccc_admin_init() {
    global $officer_post_type, $officer_meta_name;
    add_meta_box(
      $officer_meta_name,
      'Officer Details',
      'ccc_officer_meta_output',
      $officer_post_type,
      'normal',
      null,
      null
    );
  }
  
  function ccc_init() {
    global $officer_post_type;
    // Register custom Officer type
    register_post_type($officer_post_type, array(
      'label' => __('Officers'),
      'labels' => array(
        'singular_name' => __('Officer'),
        'add_new_item' => __('Add New Officer'),
        'edit_item' => __('Edit Officer'),
        'new_item' => __('New Officer'),
        'view_item' => __('View Officer')
      ),
      'public' => true,
      'show_ui' => true,
      '_builtin' => false,
      'capability_type' => 'post',
      'hierarchical' => false,
      'rewrite' => array('slug' => 'officer'),
      'supports' => array('title', 'page-attributes')
    ));
    
    // Custom columns for dashboard view
    add_action('manage_posts_custom_column', 'ccc_officer_custom_columns');
    add_filter('manage_edit-' . $officer_post_type . '_columns', 'ccc_officer_columns');
  }

  function ccc_officer_columns($columns) {
    global $officer_meta_name;
    return array(
      'cb' => '<input type="checkbox" />',
      'title' => 'Position',
      $officer_meta_name . '_name' => 'Name',
      $officer_meta_name . '_email' => 'Email',
      'status' => "Status"
    );
  }

  function ccc_officer_custom_columns($column) {
    global $post;

    if ($column == "ID") echo $post->ID;
    elseif ($column == "title") echo $post->post_title;
    elseif ($column == "status") echo ucfirst($post->post_status) . 'ed<br />' . substr($post->post_date, 0, 10);
    else {
      echo get_post_meta($post->ID, $column, true);
    }
  }

  function ccc_officer_meta_fields() {
    return array(
      'picture' => array('type' => 'media_upload'),
      'name' => array('type' => 'text'),
      'email' => array('type' => 'text'),
      'year' => array('type' => 'text'),
      'what_i_do' => array('type' => 'textarea'),
      'interests' => array('type' => 'textarea'),
      'favorite_foods' => array('type' => 'textarea')
    );
  }
  
  function ccc_officer_meta_output() {
    global $officer_post_type, $officer_meta_name, $nonce_id, $post;
    // Add custom metadata fields for each element
    $meta_fields = ccc_officer_meta_fields();
    
    // Use nonce for verification
    echo '<input type="hidden" name="' . $nonce_id . '" id="' .  $nonce_id . '" value="' . wp_create_nonce($nonce_id) . '" />';
    
    foreach ($meta_fields as $key => $value) {  
      $id = $officer_meta_name . '_' . $key;
      
      echo '<div style="margin-bottom: 1em;">';
      echo '<label for="' . $id . '" style="display: inline-block; padding: .5em 1em 0 0; text-align: right; vertical-align: top; width: 20%;">' . ucwords(str_replace('_', ' ', $key)) . '</label>';
      $atts = ' name="' . $id . '" id="' . $id . '" style="width: 75%;" ';

      switch ($value['type']) {
        case 'media_upload':
          echo '<div style="display: inline-block; width: 75%;">';
          $src = ccc_get_officer_picture($post->ID, true);
          $display_val = ($src ? 'inherit' : 'none');
          echo '<a href="#" id="' . $id . '">';
          echo '<img id="ccc_uploaded_image" src="' . $src . '" style="display: ' . $display_val . ';" /><br />';
          echo 'Upload image</a>';
          echo '</div>';
          break;

        case 'text':
          echo '<input type="text" name="'. $id . '" id="' . $id . '" value="' . get_post_meta($post->ID, $id, true) . '" style="width: 75%;" />';
          break;
          
        case 'textarea':
          echo '<textarea ' . $atts . '>' . get_post_meta($post->ID, $id, true) . '</textarea>';
          break;
        
        default:
          echo 'default';
          break;
      }
      echo '</div>';
    }
  }
  
  function ccc_save_post() {
    global $officer_post_type, $officer_meta_name, $nonce_id, $post;
    $post_id = $post->ID;
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (!wp_verify_nonce($_POST[$nonce_id], $nonce_id)) return $post_id;
    
    // verify if this is an auto save routine. If it is our form has not 
    // been submitted, so we dont want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
    
    // If we have CCC officer content to deal with loop through the fields
    if ($post->post_type == $officer_post_type) {
      $meta_fields = ccc_officer_meta_fields();
      foreach ($meta_fields as $key => $value) {
        $id = $officer_meta_name . '_' . $key;
        $user_value = @$_POST[$id];
        if (empty($user_value)) {
          delete_post_meta($post_id, $id);
          continue;
        }
        // Update meta
        else {
          add_post_meta($post_id, $id, $user_value, true) or  update_post_meta($post_id, $id, $user_value);
        }
      }
    }
  }

  function ccc_admin_officer_order($wp_query) {
    global $officer_post_type;
    if (is_admin()) {
      $post_type = $wp_query->query['post_type'];
      if ($post_type == $officer_post_type) {
        $wp_query->set('orderby', 'menu_order');
        $wp_query->set('order', 'ASC');
      }
    }
  }
  
  add_filter('pre_get_posts', 'ccc_admin_officer_order');

  function ccc_admin_scripts() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_register_script('officer-upload', get_bloginfo('template_url') . '/javascripts/admin_officer_upload.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('officer-upload');
  }
  
  function ccc_admin_styles() {
    wp_enqueue_style('thickbox');
  }
  add_action('admin_print_scripts', 'ccc_admin_scripts');
  add_action('admin_print_styles', 'ccc_admin_styles');

  if (function_exists('register_sidebar'))
    register_sidebar();
  
  /* Helper functions */
  function ccc_get_officer_picture($id, $src = false) {
    $picture = get_posts(array(
      'numberposts' => 1,
      'post_type' => 'attachment',
      'post_parent' => $id
    ));
    
    $link = '';
    if ($src) {
      $link = wp_get_attachment_image_src($picture[0]->ID, array(100, 100));
      $link = $link[0];
    }
    else
      $link = wp_get_attachment_link($picture[0]->ID, array(100, 100));
    
    if ($link != 'Missing Attachment')
      return $link;
    else
      return NULL;
  }
?>