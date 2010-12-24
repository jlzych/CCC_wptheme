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
      'singular_label' => __('Officer'),
      'public' => true,
      'show_ui' => true,
      '_builtin' => false,
      'capability_type' => 'post',
      'hierarchical' => false,
      'rewrite' => array('slug' => 'officer')
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
      'name' => '',
      'email' => '',
      'year' => '',
      'what_i_do' => '',
      'interests' => '',
      'favorite_foods' => ''
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
      
      echo '<p>';
      echo '<label for="' . $id . '" style="display: inline-block; padding-right: 1em; text-align: right; width: 20%;">' . ucwords(str_replace('_', ' ', $key)) . '</label>';
      echo '<input type="text" name="'. $id . '" id="' . $id . '" value="' . get_post_meta($post->ID, $id, true) . '" style="width: 75%;" />';
      echo '</p>';
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

  if (function_exists('register_sidebar'))
    register_sidebar();
?>