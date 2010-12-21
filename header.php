<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title>Cal Cooking Club</title>
    <meta content="Cal Cooking Club: Information on events, recipes, officers, and more." name="description">
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/stylesheets/reset.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/stylesheets/jquery.lightbox.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <!-- Google Analytics tracking -->
    <script type='text/javascript'>
      //<![CDATA[
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-19187205-1']);
        _gaq.push(['_trackPageview']);
    
        (function() {
          var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
      //]]>
    </script>
  </head>

<body <?php body_class(); ?>>
  <div id="nav">
    <ul>
      <li>
        <a href="index.php" id="logo">
          <img alt="CCC" src="<?php bloginfo('template_directory') ?>/images/ccc.120.png">
        </a>
      </li>
  		<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
  		<?php wp_list_pages( array( 'depth' => 1, 'title_li' => null) ); ?>
		</ul>
	</div>
	<div id="content"><!-- Closed in sidebar.php -->
