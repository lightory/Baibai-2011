	<div id="body">
		<div class="inner">
			<div id="config" class="content_box content">
				<div class="contentBox_tabNav">
          <a href="<?php echo site_url("account/myinvites"); ?>" class="contentBox_tabNav_tab first current">邀请的朋友</a>
        </div>
				<div class="myinvites">
					<h4>共邀请了 <?php echo $totalCount; ?> 人，<?php echo $registerUsersCount; ?> 人已接受。</h4>
					<ul class="registerUser">
						<?php foreach($registerUsers as $user): ?>
						<li>
							<a href="<?php echo $this->MUser->getUrl($user->uid); ?>">
								<img src="<?php echo $user->avatar ?>" class="avatar" />
							</a>
						</li>
						<?php endforeach; ?>
						<div class="clearfix"></div>
					</ul>
					<h4>还未注册的好友：</h4>
					<ul class="unregisterUser">
						<?php foreach($unregisterUsers as $user): ?>
						<li>
							<span><?php echo $user->email; ?></span>
							<a href="<?php echo site_url("account/register/$user->code/"); ?>">注册链接（右键复制，直接给好友吧～）</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<?php echo $this->Common->sidebar(); ?>
			<div class="clearfix"></div>
		</div>
	</div>