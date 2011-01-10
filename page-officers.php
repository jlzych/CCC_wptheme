<?php
/*
 * Template Name: Officers
 * Officers template file.
 */
?>
<?php get_header(); ?>
<h1><?php echo ucwords(wp_title(null, false)); ?></h1>
<?php
  global $officer_meta_name, $officer_post_type;
  $posts = new WP_Query(array(
    'post_type' => $officer_post_type,
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ));
  while($posts->have_posts()) :
    $posts->the_post();
    $post = $posts->post;
    $fields = get_post_custom();
?>

<div class="officer vcard">
  <div class="title_container">
    <div class="thumb">
      <?php
        echo ccc_get_officer_picture($post->ID);
      ?>
    </div>
    <div class="title">
      <h2><?php echo the_title('', '', false); ?></h2>
      <h4 class="officer_name fn">
        <?php echo $fields[$officer_meta_name . '_name'][0] . " - "; ?>
        <a class="email" href="mailto:<?php echo $fields[$officer_meta_name . '_email'][0]; ?>" title="Email"><?php echo $fields[$officer_meta_name . '_email'][0]; ?></a>
      </h4>
    </div>
    <ul>
      <?php foreach($fields as $key => $value) : ?>
        <?php if(filter_meta_fields($key)) :
          $key = str_replace($officer_meta_name, '', $key); ?>
          <li>
            <span class="label"><?php echo ucwords(str_replace('_', ' ', $key)); ?></span>
            <span class="text"><?php echo $value[0]; ?></span>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endwhile ?>

<?php get_footer(); ?>
<?php get_sidebar(); ?>

<?php
function filter_meta_fields($field) {
  // Special fields that are part of the header, not to be reprinted
  $blacklist = array(
    $officer_meta_name . '_name',
    $officer_meta_name . '_email'
  );
  
  global $officer_meta_name;
  
  if(strstr($field, $officer_meta_name) !== false && !in_array($field, $blacklist)) {
    return true;
  }
  else {
    return false;
  }
}
?>