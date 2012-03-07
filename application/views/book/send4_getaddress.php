<div class="newFancy">
	<div class="newFancy_header borrowBox_header">
    <div class="newFancy_header_left newFancy_header_user">
      <?php $receiver->address = $this->MAddress->getDefaultByUid($receiver->uid); ?>
      <img src="<?php echo getGravatar($receiver->email, 30); ?>" class="newFancy_avatar" />
      <p>
		<a class="username" href="<?php echo $this->MUser->getUrl($receiver->uid); ?>"><?php echo $receiver->nickname; ?></a>
		<?php if($receiver->isMobileValidate): ?>
		<img src="<?php echo site_url("include/style/img/"); ?>mobile.png" style="position:relative; top:2px;" title="手机已验证" />
		<?php else: ?>
		<img src="<?php echo site_url("include/style/img/"); ?>mobilenone.png" style="position:relative; top:2px;" title="手机未验证" />
		<?php endif; ?>
		想借
		<a href="<?php echo site_url("book/subject/$book->id/"); ?>">《<?php echo $book->name; ?>》</a> 
		</p>
      <p><span class="address"><?php echo $receiver->address->province."，".$receiver->address->city; ?></span></p>
    </div>
    <div class="newFancy_header_right newFancy_header_time">
      <?php echo mdate('%Y-%m-%d %H:%i', $record->time2); ?>
    </div>
  </div>
	<div class="newFancy_middle borrowBox_middle">
    <div class="newFancy_addressInfo">
      <h3>TA的地址</h3>
      <ul>
        <li>
          <label class="name">姓名</label>
          <?php echo $receiverAddress->name; ?>
        </li>
        <li>
          <label class="address">地址</label>
          <?php echo "$receiverAddress->province · $receiverAddress->city · $receiverAddress->district · $receiverAddress->address";  ?>
        </li>
        <li>
          <label class="postcode">邮编</label>
          <?php echo $receiverAddress->postcodeTrue;  ?>
        </li>
        <li>
          <label class="phone">电话</label>
          <?php echo $receiver->mobile; ?>
        </li>
      </ul>
    </div>
    <img class="borrowBox_middle_bookCover" src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" />
    <p class="clearfix"></p>
    <div class="newFancy_postmark"></div>
  </div>
	<div class="newFancy_footer"  style="text-align:right; padding:10px 20px;">
		<p class="nav">
			<a href="<?php echo site_url("book/fillexpress/$record->id/"); ?>" id="next" class="newFancy_button">我已寄书</a>
		</p>
	</div>
</div>
<script type="text/javascript">
$("#next").bind("click", function() {
	$.fancybox.showActivity();

	$.ajax({
		type		: "POST",
		url		: "<?php echo site_url("book/fillexpress/$record->id/"); ?>",
		data		: $(this).serializeArray(),
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
});
</script>