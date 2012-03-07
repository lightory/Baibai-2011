<ul>
  <?php foreach ($books as $book): ?>
  <?php $tags = $this->MTag->getMyTagOfBook('0', $book->id); ?>
   <li style="line-height:24px;">
     <form method="POST" action="<?php echo site_url("admin/updatetag_do/$book->id/"); ?>" style="display:inline;">
        <input type="hidden" name="oldTags" value="<?php echo $tags; ?>" />
        <input type="text" name="newTags" value="<?php echo $tags; ?>" style="width:200px;" />
        <input type="submit" />
      </form>
      <a href="<?php echo site_url("book/subject/$book->id/"); ?>" target="_blank"><?php echo $book->name; ?></a>
   </li>
  <?php endforeach; ?>
</ul>

<?php echo $this->pagination->create_links(); ?>

<script type="text/javascript">
$('form').submit(function(){
  var actionUrl = $(this).attr('action');
  
  $.ajax({
    type: "POST",
    url: actionUrl,
    data: $(this).serializeArray(),
    success: function() {
      alert('已更新Tag');
    }
  });
  
  return false;
});
</script>