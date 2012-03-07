<div class="newFancy borrowBox">
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
      <?php echo mdate('%Y-%m-%d %H:%i', $request->time); ?>
    </div>
	</div>
	<div class="newFancy_middle borrowBox_middle">
		<div class="newFancy_bookInfo">
      <img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="newFancy_bookInfo_cover" />
      <ul class="newFancy_bookInfo_ul">
        <li class="newFancy_bookInfo_ul_name"><?php echo $book->name; ?></li>
        <li>ISBN：<?php echo $book->isbn; ?></li>
        <li>作者：<?php echo $book->author; ?></li>
        <?php if($book->translator): ?><li>译者：<?php echo $book->translator; ?></li><?php endif; ?>
        <li>出版社：<?php echo $book->publisher; ?></li>
        <li>出版时间：<?php echo $book->pubdate; ?></li>
        <li>定价：<?php echo $book->price; ?></li>
      </ul>
    </div>
	  <a href="<?php echo site_url("book/choosereceiver_do/$request->id/"); ?>" id="next" class="newFancy_button borrowBox_middle_submitButton">我愿意借给TA，索取地址^_^</a>
	  <p class="clearfix"></p>
	  <div class="newFancy_postmark"></div>
	</div>
</div>
<script type="text/javascript">
$("#next").bind("click", function() {
	$.fancybox.showActivity();

	$.ajax({
		type		: "POST",
		url		: "<?php echo site_url("book/choosereceiver_do/$request->id/"); ?>",
		data		: $(this).serializeArray(),
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
});
</script>