<div class="newFancy returnBook">
  <div class="newFancy_header returnBook_header">
    <h3>还书，传递给下一位读者</h3>
    <div class="newFancy_postmark"></div>
  </div>
  <div class="newFancy_middle returnBook_middle">
    <form class="returnBook_form" method="post" action="<?php echo site_url("book/returnbook_do/$stockId/"); ?>" style="position:relative;">
			<?php if(count($myLinkedProviders)>0): ?>
			<span style="position:absolute; left:20px; top:21px; color:#929292;">
				同步到
				<?php if(in_array('douban',$myLinkedProviders)): ?>
				<input type="checkbox" name="douban1" id="douban" checked style="margin:0 2px 0 6px;">豆瓣
				<?php endif; ?>
				<?php if(in_array('tsina',$myLinkedProviders)): ?>
				<input type="checkbox" name="tsina1" id="tsina" checked style="margin:0 2px 0 6px;">新浪微博
				<?php endif; ?>
				<?php if(in_array('tqq',$myLinkedProviders)): ?>
				<input type="checkbox" name="tqq1" id="tqq" checked style="margin:0 2px 0 6px;">腾讯微博
				<?php endif; ?>
			</span>
			<?php endif; ?>
      <a href="javascript:void(0);" class="newFancy_button returnBook_form_button">确认还书</a>
    </form>
    <div class="clearfix"></div>
  </div>
</div>

<script type="text/javascript">
$("a.returnBook_form_button").click(function(){      
  if ( $('form textarea').val() == prompt ){
    $('form textarea').val('');
  }

  $.fancybox.showActivity();

	var postData = $(this).parent().serialize()+'&tsina='+$('#tsina').attr('checked')+'&douban='+$('#douban').attr('checked')+'&tqq='+$('#tqq').attr('checked');

  $.ajax({
    type    : "POST",
    url   : $(this).parent().attr('action'),
    data    : postData,
    success: function(data) {
      $.fancybox(data);
    }
  });
  
  return false;
});
</script>