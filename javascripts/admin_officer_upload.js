jQuery(document).ready(function() {
  jQuery('#ccc_officer_meta_picture').click(function() {
    window.send_to_editor = function(html) {
      imgurl = jQuery('img', html).attr('src');
      jQuery('#ccc_uploaded_image').attr('src', imgurl).show();
      tb_remove();
    }
    var postid = jQuery("#post_ID").val();
    tb_show('', 'media-upload.php?post_id=' + postid + '&amp;type=image&amp;TB_iframe=true');
    return false;
  });
});