<h2>用户数据</h2>
<ul>
	<li><?php echo $registerUserCount; ?> 名注册用户</li>
	<li><?php echo $unregisterUserCount; ?> 名被邀请但尚未注册的用户</li>
	<li><?php echo $inviteQuoteCount; ?> 个尚未使用的邀请</li>
</ul>

<h2>书籍数据</h2>
<ul>
	<li><?php echo $bookCount; ?> 本书</li>
	<li><?php echo "$availableStockCount/$totalStockCount"; ?> 本库存书</li>
	<li><?php echo $readingStockCount; ?> 本在读中的库存书</li>
	<li><?php echo $transforStockCount; ?> 本在转移途中的库存书</li>
</ul>
	
<h2>借书行为数据</h2>
<ul>
	<li><?php echo $statue0RecordCount; ?> 条还没人搭理的借书留言 - <a href="<?php echo site_url('admin/record0/'); ?>">详细</a></li>
	<li><?php echo $statue1RecordCount; ?> 条刚有人搭理的借书留言 - <a href="<?php echo site_url('admin/record1/'); ?>">详细</a></li>
	<li><?php echo $statue2RecordCount; ?> 次借书行为，receiver已经提供地址，等待senter发货 - <a href="<?php echo site_url('admin/record2/'); ?>">详细</a></li>
	<li><?php echo $statue3RecordCount; ?> 次借书行为，senter已经发货，等待receiver确认收到 - <a href="<?php echo site_url('admin/record3/'); ?>">详细</a></li>
	<li><?php echo $statue4RecordCount+$statue5RecordCount;; ?> 次非常成功的借书 - <a href="<?php echo site_url('admin/record4/'); ?>">详细</a></li>
  <li><?php echo $statue6RecordCount; ?> 次借出并读完</li>
</ul>
<h2>摆摆卷记录</h2>
<ul>
	<li>储存中摆摆券：<?php echo $baibaijuanSaveCount; ?></li>
	<li>流通中摆摆券： <?php echo $baibaijuanTranCount; ?></li>
	<li>全部摆摆券： <?php echo $baibaijuanSaveCount+$baibaijuanTranCount; ?></li>
</ul>
	