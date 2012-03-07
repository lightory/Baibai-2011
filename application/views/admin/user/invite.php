<h2>邀请</h2>
<form method="POST" action="<?php echo site_url('admin/invite_do/'); ?>">
	<p>
		<label for="email">Email</label>
		<input type="text" name="email" />
	</p>
	<p>
		<label for="emailTitle">标题</label>
		<input type="text" name="emailTitle" value="摆摆书架邀请您参加内测" />
	</p>
	<p>
		<label for="emailContent">内容</label>
		<textarea name="emailContent" style="width:400px;height:150px;"></textarea>
	</p>
	<p>
		<input type="submit" value="邀请" />
	</p>
</form>


<h2>尚未注册用户</h2>
<ul>
<?php foreach($unregisterUsers as $user): ?>
	<li style="line-height:1.8em;">
	 <?php echo mdate('%Y-%m-%d %H:%i', $user->time); ?>
	 -
	 <?php echo $user->email; ?> 
	 <a href="<?php echo site_url("account/register/$user->code/"); ?>">注册链接</a>
	</li>
<?php endforeach; ?>
</ul>