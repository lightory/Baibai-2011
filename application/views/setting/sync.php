<style type="text/css">
	ul.syncList li{ padding:10px 0; border-bottom:1px dashed #E5E5E5; }
	ul.syncList li img{ margin-right: 20px; width:144px; height:46px; }
	ul.syncList li span{ font-size:14px; color:#929292; position:relative; bottom:16px; }
</style>
<div id="body">
	<div class="inner">
		<div id="config" class="content_box content">
			<div class="contentBox_tabNav">
        <a href="<?php echo site_url("setting/profile"); ?>" class="contentBox_tabNav_tab first">个人资料</a><!--
        --><a href="<?php echo site_url("setting/address"); ?>" class="contentBox_tabNav_tab">收货地址</a><!--
	      --><a href="<?php echo site_url("setting/sync"); ?>" class="contentBox_tabNav_tab current">同步动态</a><!--
        --><a href="<?php echo site_url("setting/notify"); ?>" class="contentBox_tabNav_tab">邮件提醒</a>
      </div>
			<form class="profile">
				<h3 style="padding-bottom:6px; border-bottom:1px solid #E5E5E5;">同步帐号</h3>
				<?php if ($this->session->flashdata('error')){ ?>
				<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
				<?php } ?>
				<ul class="syncList">
					<li>
						<?php if(!in_array('douban', $myLinkedProviders)): ?>
						<img src="<?php echo site_url('include/style/img/link/').'douban.jpg'; ?>" />
						<span><a href="<?php echo site_url('setting/link/').'?provider=douban'; ?>">绑定帐号，同步动态到豆瓣</a></span>
						<?php else: ?>
						<img src="<?php echo site_url('include/style/img/link/').'douban_linked.png'; ?>" />
						<span>绑定成功！<a href="<?php echo site_url('setting/unlink/').'?provider=douban'; ?>">解除绑定豆瓣</a></span>
						<?php endif; ?>
					</li>
					<li>
						<?php if(!in_array('tsina', $myLinkedProviders)): ?>
						<img src="<?php echo site_url('include/style/img/link/').'tsina.jpg'; ?>" />
						<span><a href="<?php echo site_url('setting/link/').'?provider=tsina'; ?>">绑定帐号，同步动态到新浪微博</a></span>
						<?php else: ?>
						<img src="<?php echo site_url('include/style/img/link/').'tsina_linked.png'; ?>" />
						<span>绑定成功！<a href="<?php echo site_url('setting/unlink/').'?provider=tsina'; ?>">解除绑定新浪微博</a></span>
						<?php endif; ?>
					</li>
					<li>
						<?php if(!in_array('tqq', $myLinkedProviders)): ?>
						<img src="<?php echo site_url('include/style/img/link/').'tqq.jpg'; ?>" />
						<span><a href="<?php echo site_url('setting/link/').'?provider=tqq'; ?>">绑定帐号，同步动态到腾讯微博</a></span>
						<?php else: ?>
						<img src="<?php echo site_url('include/style/img/link/').'tqq_linked.png'; ?>" />
						<span>绑定成功！<a href="<?php echo site_url('setting/unlink/').'?provider=tqq'; ?>">解除绑定腾讯微博</a></span>
						<?php endif; ?>
					</li>
				</ul>
			</form>
		</div>
		<?php echo $this->Common->sidebar(); ?>
		<div class="clearfix"></div>
	</div>
</div>