<?php foreach($weeklyList as $weekly): ?>
<p>
	<a href="<?php echo site_url("weekly/{$weekly->id}/"); ?>" target="_blank">
		<?php echo "第{$weekly->id}期 : $weekly->name"; ?>
	</a>
	<?php if (0 == $weekly->isSent): ?>
		 - 
		<a href="<?php echo site_url("admin/weekly/{$weekly->id}/"); ?>">编辑</a>
		<a href="<?php echo site_url("admin/weekly_send/{$weekly->id}/"); ?>">发送</a>
	<?php endif; ?>
</p>
<?php endforeach; ?>