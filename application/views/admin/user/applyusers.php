<p>
	<a href="<?php echo site_url('admin/applyusers/'); ?>">未审核</a>
	<a href="<?php echo site_url('admin/applyusers/1/'); ?>">推迟邀请</a>
</p>

<?php foreach($users as $user): ?>
<div>
	<hr/>
	<p style="line-height:1.5em;">
		<?php echo mdate('%Y-%m-%d %H:%i', $user->time); ?><br/>
	  <strong><?php echo $user->nickname; ?> <?php if($user->donate){echo'愿意捐书';} ?></strong><br/>
	  <strong>地址：</strong><?php echo $user->address; ?><br/>
		<strong>博客：</strong><?php echo $user->blog; ?><br/>
		<strong>简介：</strong><?php echo $user->bio; ?><br/>
	</p>
	<form>
		<input type="text" name="email" value="<?php echo $user->email; ?>" style="width:200px; padding:3px;" />
		<input type="hidden" name="nickname" value="<?php echo $user->nickname; ?>" />
		<input type="hidden" name="oldEmail" value="<?php echo $user->email; ?>" />
		<a href="javascript:void(0);" class="invite inviteAction">发送邀请</a>
		<a href="javascript:void(0);" class="later inviteAction">稍后邀请</a>
		<a href="javascript:void(0);" class="delete inviteAction">删除</a>
	</form>
</div>
<?php endforeach; ?>

<script type="text/javascript">
$('a.inviteAction').click(function(){
	var $this = $(this);
	var email = $(this).parent().children('input[name=email]').val();
	var nickname = $(this).parent().children('input[name=nickname]').val();
	var oldEmail = $(this).parent().children('input[name=oldEmail]').val();
	
	var actionUrl = '';
  if($(this).attr('class').indexOf('invite ')!=-1){
		actionUrl = '<?php echo site_url('admin/invite_applyuser_do/'); ?>';
	} else if($(this).attr('class').indexOf('later ')!=-1){
		actionUrl = '<?php echo site_url('admin/later_applyuser_do/'); ?>';
	} else if($(this).attr('class').indexOf('delete ')!=-1){
		actionUrl = '<?php echo site_url('admin/delete_applyuser_do/'); ?>';
	}
  
  $.ajax({
    type: "POST",
    url: actionUrl,
    data: "email=" + email + "&nickname=" + nickname + "&oldEmail=" + oldEmail,
    success: function() {
      $this.parent().parent().hide();
    }
  });
  
  return false;
});
</script>