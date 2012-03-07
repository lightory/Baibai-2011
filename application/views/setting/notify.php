	<div id="body">
		<div class="inner">
			<div id="config" class="content_box content">
				<div class="contentBox_tabNav">
          <a href="<?php echo site_url("setting/profile"); ?>" class="contentBox_tabNav_tab first">个人资料</a><!--
          --><a href="<?php echo site_url("setting/address"); ?>" class="contentBox_tabNav_tab">收货地址</a><!--
				  --><a href="<?php echo site_url("setting/sync"); ?>" class="contentBox_tabNav_tab">同步动态</a><!--
	        --><a href="<?php echo site_url("setting/notify"); ?>" class="contentBox_tabNav_tab current">邮件提醒</a>
        </div>
				<form class="profile" method="post" action="<?php echo site_url('setting/notify_do/'); ?>">
					<?php if ($this->session->flashdata('error')){ ?>
					<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
					<?php } ?>
					<p>
						<input type="checkbox" name="mail" class="nolabel" value="1" <?php if($notifySetting->mail): ?>checked<?php endif; ?> />
						当有人给我发站内信时提醒我
					</p>
					<p>
						<input type="checkbox" name="weekly" class="nolabel" value="1" <?php if($notifySetting->weekly): ?>checked<?php endif; ?> />
						接收摆摆特刊（两周一期的书籍精选推荐）
					</p>
					<p>
						<input type="submit" value="更新邮件提醒设置" class="nolabel button" />
					</p>
				</form>
			</div>
			<?php echo $this->Common->sidebar(); ?>
			<div class="clearfix"></div>
		</div>
	</div>