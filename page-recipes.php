<?php
/**
* Template Name: Recipes
* Recipes template file.
 */

get_header(); ?>
<h1><?php echo ucwords(wp_title(null, false)); ?></h1>
<?php
  global $recipe_post_type;
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $wp_query = new WP_Query(array(
    'post_type' => $recipe_post_type,
    'posts_per_page' => 10,
    'paged' => $paged
  ));
  while($wp_query->have_posts()) :
    $wp_query->the_post();
?>

  <div class="recipe">
    <h2>
      <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
        <?php the_title(); ?>
      </a>
    </h2>
    <?php the_content(); ?>
  </div>

<?php endwhile ?>

<div class="pagination">
  <?php echo paginate_links(array(
    'base' => add_query_arg('paged', '%#%'),
    'format' => '',
    'total' => $wp_query->max_num_pages,
    'current' => $paged
  )); ?>
</div>

<?php get_footer(); ?>
<?php get_sidebar(); ?>
