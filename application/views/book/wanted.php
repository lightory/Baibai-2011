<div id="body">
	<div class="inner">
		<div class="content">
			<div class="content_box">
				<div class="contentBox_tabNav">
					<a href="<?php echo site_url('book/all/'); ?>" class="contentBox_tabNav_tab first">全站书架</a><!--
					--><?php if(isset($location) && $location):?><a href="<?php echo site_url("location/{$location->url}/"); ?>" class="contentBox_tabNav_tab"><?php echo $location->name; ?>书架</a><?php endif; ?><!--
					--><a href="<?php echo site_url("book/wanted/"); ?>" class="contentBox_tabNav_tab last current">大家想借</a>
				</div>
				<div class="lib_contentModule" style="padding-bottom:10px;">
					<div class="highlight">
						这里是大家想借的书，如果您有一本能借给他们，可以立刻获得摆摆券。
					</div>
				</div>
				<div class="lib_contentModule book_list3 brd-bottom">
					<ul>
						<?php $i=0; ?>
						<?php foreach ($lastestWantedBooks as $book): ?>
							<?php $i++; ?>
							<li <?php if(0 == $i%5){ echo 'style="margin-right:0;"';} ?>>
								<a href="<?php echo site_url("book/subject/$book->id/"); ?>" title="<?php echo $book->name; ?>">
									<img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="book_cover" />
									<span class="book_name"><?php echo $book->name; ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<p class="clearfix"></p>
					<?php echo $this->pagination->create_links(); ?>
				</div>
			</div>
		</div><!--End Of Content -->
		<div class="sidebar">
			<div class="content_box user_list">
				<h3>捐赠排行榜</h3>
				<ul>
				<?php foreach($users as $user): ?>
					<li>
						<img src="<?php echo getGravatar($user->email, 35); ?>" class="avatar avatar-35" />
						<a class="username" href="<?php echo $this->MUser->getUrl($user->uid); ?>"><?php echo $user->nickname; ?></a>
						<?php $address = $this->MAddress->getDefaultByUid($user->uid); ?>
						<span class="district"><?php echo "$address->province $address->city"; ?></span>
						<span class="donate_count"><?php echo $user->count; ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php 
				$this->load->view("sidebar/components/sns_links.php"); 
				$this->load->view("sidebar/components/ext_links.php"); 
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>