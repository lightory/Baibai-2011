<div class="borrow_messages">
	<?php foreach($borrowRequests as $request):
		$borrower = $this->MUser->getByUid($request->userId);
		if (($type=='all') && ($borrower->borrowQuote < 1)){
			continue;
		}
	
		$borrower->avatar = getGravatar($borrower->email, 45);
		$messagerAddress = $this->MAddress->getDefaultByUid($borrower->uid);
	?>
	<div class="message">
		<img src="<?php echo $borrower->avatar; ?>" class="avatar" />
		<p class="message_meta">
			<a href="<?php echo $this->MUser->getUrl($borrower->uid); ?>" class="messager"><?php echo $borrower->nickname; ?></a><!--
			--><span class="disctrict">(<?php echo $messagerAddress->province; ?>)</span>
			<span class="time"><?php echo mdate('%Y-%m-%d %H:%i', $request->time); ?></span>
		</p>
		<p class="message_content"><?php echo $request->message; ?></p>
		<?php if('all' == $type): ?>
			<a href="<?php echo site_url("book/choosereceiver/$request->id/"); ?>" onclick="return false;"  class="message_act fancybox button">借给TA</a>
		<?php elseif('mine' == $type): ?>
			<a href="<?php echo site_url("borrow/request/{$request->bookId}/"); ?>" class="message_act button">编辑</a>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>
<script type="text/javascript">
	$('.message_act', '.borrow_messages').hide();
	$('.message', '.borrow_messages').hover(function(){
		$('.message_act', this).show();
		$(this).css('background', '#F2F6FD');
	},function(){
		$('.message_act', this).hide();
		$(this).css('background', '#F4F5F5');
	});
	
	// FancyBox
	$(".fancybox").fancybox({
		'transitionIn'	 : 'fade',
		'transitionOut'	 : 'fade',
		'type'				 : 'ajax'
	});
</script>