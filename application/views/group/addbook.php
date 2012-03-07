<div class="newFancy returnBook">
  <div class="newFancy_header returnBook_header">
    <h3>将《<?php echo $book->name; ?>》推荐到</h3>
    <div class="newFancy_postmark"></div>
  </div>
  <div class="newFancy_middle">
    <form class="returnBook_form" method="post" action="<?php echo site_url("group/addbook_do/"); ?>">
      <div class="iconsGrid" style="margin:0;">
        <?php foreach($groups as $group): ?>
				<?php if($group->type=='location'){continue;} ?>
        <dl style="margin-bottom:10px;">
          <dt>
            <a href="<?php echo site_url("group/$group->url/"); ?>">
              <img src="<?php echo getGroupIcon($group); ?>" style="width:48px; height:48px;" />
            </a>
          </dt>
          <dd style="height:30px;">
            <a href="<?php echo site_url("group/$group->url/"); ?>"><?php echo utf_substr($group->name, 16); ?></a>
          </dd>
          <input type="radio" name="groupId" value="<?php echo $group->id; ?>" />
        </dl>
        <?php endforeach; ?>
        <input type="hidden" name="bookId" value="<?php echo $book->id; ?>" />
        <p class="clearfix"></p>
      </div>
      <a href="javascript:void(0);" class="newFancy_button returnBook_form_button">推荐</a>
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