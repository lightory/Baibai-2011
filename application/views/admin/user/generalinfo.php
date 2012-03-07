<h2>用户记录</h2>
<p>昵称：<?php echo $user->nickname?></p>
<p>注册时间：<?php echo date('Y-m-d H:i:s',$user->joinTime);?></p>
<p>最后登录：<?php echo date('Y-m-d H:i:s',$lastLogin);?></p>
<p>用户主页：<a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></p>
<p>摆摆券：<?php echo $user->borrowQuote; ?></p>
<?php if($gifts): ?>
<p>赠送记录：</p>
<table>
	<tr>
		<th>赠送数量</th>
		<th>赠送理由</th>
		<th>赠送时间</th>		
	</tr>	
	<?php foreach($gifts as $gift): ?>
	<tr>
		<td><?php echo $gift->title; ?></td>
		<td><?php echo $gift->content; ?></td>
		<td><?php echo date('Y-m-d H:i:s',$gift->time); ?></td>		
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>
<h2>赠送摆摆卷</h2>
<form method="POST" action="<?php echo site_url('admin/gift_do/'.$user->uid); ?>">
	<p>
		<label for="giftCount">赠送数量：</label>
		<input type="text" name="giftCount" />
	</p>	
	<p>
		<label for="giftReason">赠送理由：</label>
		<textarea name="giftReason" style="width:400px;height:150px;"></textarea>
	</p>
	<p>
		<input type="submit" value="赠送" />
	</p>
</form>