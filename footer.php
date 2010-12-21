<?php
/**
 * The template for displaying the footer.
 */
?>
    <!--
      JS placed at bottom for better performance
      Use Google's CDN jQuery, fall back to local otherwise
    -->
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
    <script type='text/javascript'>
      //<![CDATA[
        !window.jQuery && document.write('<script src="<?php bloginfo('template_directory') ?>/javascripts/jquery-1.4.2.min.js"><\/script>')
      //]]>
    </script>
    <script src='<?php bloginfo('template_directory') ?>/javascripts/jquery.lightbox-0.5.pack.js'></script>
    <script type='text/javascript'>
      //<![CDATA[
        $(document).ready(function() {
          $('#content a:has(img):not(.no_lb)').lightBox();
        });
      //]]>
    </script>
    <?php
    	/* Always have wp_footer() just before the closing </body>
    	 * tag of your theme, or you will break many plugins, which
    	 * generally use this hook to reference JavaScript files.
    	 */

    	wp_footer();
    ?>
  </body>
</html>
