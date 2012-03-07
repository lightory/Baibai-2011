<div id="alertError" class="fancy">
	<h3><?php echo $this->session->flashdata('title'); ?></h3>
	<img src="<?php echo site_url('include/style/img/').'alertError_icon.png'; ?>" />
	<p>
		<?php echo $this->session->flashdata('message'); ?>
		<br/>
		<span class="buttons">
			<a class="button" id="closeButton" href="javascript:void(0);">确认</a>
		</span>
	</p>
</div>

<script type="text/javascript">
<?php if  ($redirectUrl = $this->session->flashdata('redirectUrl') ): ?>
	$('#closeButton').click(function(){
		 location.href = '<?php echo $redirectUrl; ?>';
	});
<?php else: ?>
	$('#closeButton').click(function(){
		$.fancybox.close();
	});
<?php endif; ?>
</script>