<a href="<?php echo site_url("admin/weekly_send/{$weekly->id}/{$newStartUid}/"); ?>" id="nextWeeklyLink">
	开始发送第<?php echo $newStartUid; ?>
</a>

<script>
	var nextWeekly = function(){
		var nextWeeklyLink = $('#nextWeeklyLink').attr('href');
		window.location.href = nextWeeklyLink;
	}
	setTimeout(nextWeekly, 2000);
</script>