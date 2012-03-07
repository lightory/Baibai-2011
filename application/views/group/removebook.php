<div class="newFancy returnBook">
  <div class="newFancy_header returnBook_header">
    <h3>确认要从小组收藏中删除《<?php echo $book->name; ?>》吗？</h3>
    <div class="newFancy_postmark"></div>
  </div>
  <div class="newFancy_middle returnBook_middle">
    <form class="returnBook_form" method="post" action="<?php echo site_url("group/removebook_do/"); ?>">
			<input type="hidden" name="groupId" value="<?php echo $group->id ?>">
			<input type="hidden" name="bookId" value="<?php echo $book->id ?>">
      <a href="javascript:void(0);" class="newFancy_button returnBook_form_button">确认删除</a>
    </form>
    <div class="clearfix"></div>
  </div>
</div>

<script type="text/javascript">
$("a.returnBook_form_button").click(function(){      
  $.fancybox.showActivity();

  $.ajax({
    type    : "POST",
    url   : $(this).parent().attr('action'),
    data    : $(this).parent().serializeArray(),
    success: function(data) {
      $.fancybox(data);
    }
  });
  
  return false;
});
</script>