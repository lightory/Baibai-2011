<div id="alertInfo" class="fancy">
	<h3><?php echo $this->session->flashdata('title'); ?></h3>
	<img src="<?php echo site_url('include/style/img/').'alertInfo_icon.png'; ?>" />
	<p>
		<?php echo $this->session->flashdata('message'); ?>
		<br/>
		<span class="buttons">
		  <?php $redirectName = $this->session->flashdata('redirectName') ? $this->session->flashdata('redirectName') : '确定' ?>
			<a class="button" id="closeButton" href="javascript:void(0);"><?php echo $redirectName; ?></a>
			<?php if ($nextName = $this->session->flashdata('nextName')): ?>
			<a class="button" id="nextButton" href="javascript:void(0);"><?php echo $nextName; ?></a>
			<?php endif; ?>
		</span>
	</p>
</div>

<script type="text/javascript">
<?php if ($redirectName != '确定' ): ?>
  $('#closeButton').click(function(){
    $.fancybox.showActivity();
    
    $.ajax({
      type    : "POST",
      url   : "<?php echo $this->session->flashdata('redirectUrl'); ?>",
      data    : $(this).serializeArray(),
      success: function(data) {
        $.fancybox(data);
      }
    });

    return false;
  });
<?php elseif ($redirectUrl = $this->session->flashdata('redirectUrl') ): ?>
	$('#closeButton').click(function(){
		 location.href = '<?php echo $redirectUrl; ?>';
	});	
<?php else: ?>
	$('#closeButton').click(function(){
		$.fancybox.close();
	});
<?php endif; ?>

<?php if ($nextName): ?>
	$('#nextButton').click(function(){
		$.fancybox.showActivity();
		
		$.ajax({
			type		: "POST",
			url		: "<?php echo $this->session->flashdata('nextUrl'); ?>",
			data		: $(this).serializeArray(),
			success: function(data) {
				$.fancybox(data);
			}
		});

		return false;
	});
<?php endif; ?>
</script>