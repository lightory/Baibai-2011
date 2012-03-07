<div class="newFancy borrowBox">
	<div class="newFancy_header borrowBox_header">
		<div class="newFancy_header_left newFancy_header_user">
      <?php $senter->address = $this->MAddress->getDefaultByUid($senter->uid); ?>
      <img src="<?php echo getGravatar($senter->email, 30); ?>" class="newFancy_avatar" />
      <p>
		<a class="username" href="<?php echo $this->MUser->getUrl($senter->uid); ?>"><?php echo $senter->nickname; ?></a>
		<?php if($senter->isMobileValidate): ?>
		<img src="<?php echo site_url("include/style/img/"); ?>mobile.png" style="position:relative; top:2px;" title="手机已验证" />
		<?php else: ?>
		<img src="<?php echo site_url("include/style/img/"); ?>mobilenone.png" style="position:relative; top:2px;" title="手机未验证" />
		<?php endif; ?>
		愿意寄给您
		<a href="<?php echo site_url("book/subject/$book->id/"); ?>">《<?php echo $book->name; ?>》</a> 
	</p>
      <p><span class="address"><?php echo $senter->address->province."，".$senter->address->city; ?></span></p>
    </div>
    <div class="newFancy_header_right newFancy_header_time">
      <?php echo mdate('%Y-%m-%d %H:%i', $record->time1); ?>
    </div>
	</div>
	<div class="newFancy_middle borrowBox_middle">
	  <div class="newFancy_addressInfo">
  		<h3>将您的地址发给 TA</h3>
  		<a href="<?php echo site_url('setting/address/'); ?>" style="position:absolute; top:2px; left:130px;">(修改)</a>
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
	<div class="newFancy_footer">
		<p class="nav" style="text-align:right; padding:10px 20px;">
			<a href="<?php echo site_url("book/offeraddress_back_do/$record->id/"); ?>" id="prev" class="button cancel_button" style="margin-right:10px;">我不借了</a>
		  <a href="<?php echo site_url("book/offeraddress_do/$record->id/"); ?>" id="next" class="newFancy_button">确认^_^</a>
		</p>
	</div>
</div>
<script type="text/javascript">
$("#next").bind("click", function() {
	$.fancybox.showActivity();

	$.ajax({
		type		: "POST",
		url		: "<?php echo site_url("book/offeraddress_do/$record->id/"); ?>",
		data		: $(this).serializeArray(),
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
});

$("#prev").bind("click", function() {
	$.fancybox.showActivity();

	$.ajax({
		type		: "POST",
		url		: "<?php echo site_url("book/offeraddress_back_do/$record->id/"); ?>",
		data		: $(this).serializeArray(),
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
});
</script>