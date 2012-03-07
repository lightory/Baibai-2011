<div id="body">
	<div class="inner">
		<div class="content">
			<div class="content_box">
				<div class="contentBox_tabNav">
					<a href="<?php echo site_url('book/all/'); ?>" class="contentBox_tabNav_tab current first">全站书架</a><!--
					--><?php if(isset($location) && $location):?><a href="<?php echo site_url("location/{$location->url}/"); ?>" class="contentBox_tabNav_tab"><?php echo $location->name; ?>书架</a><?php endif; ?><!--
						--><a href="<?php echo site_url("book/wanted/"); ?>" class="contentBox_tabNav_tab last">大家想借</a>
					</div>
					<div class="lib_contentModule book_list3 brd-bottom">
						<p class="lib_tags">
							类别：
							<?php $i=false; ?>
							<a href="<?php echo site_url("book/") ?>" <?php if(!isset($tagName)):?>class="active"<?php endif; ?>>全部</a>
							<a href="<?php echo site_url("book/tag/小说/") ?>" <?php if(isset($tagName) &&($tagName=='小说')):$i=true;?>class="active"<?php endif; ?>>小说</a>
							<a href="<?php echo site_url("book/tag/随笔/") ?>" <?php if(isset($tagName) &&($tagName=='随笔')):$i=true;?>class="active"<?php endif; ?>>随笔</a>
							<a href="<?php echo site_url("book/tag/经济/") ?>" <?php if(isset($tagName) &&($tagName=='经济')):$i=true;?>class="active"<?php endif; ?>>经济</a>
							<a href="<?php echo site_url("book/tag/哲学/") ?>" <?php if(isset($tagName) &&($tagName=='哲学')):$i=true;?>class="active"<?php endif; ?>>哲学</a>
							<a href="<?php echo site_url("book/tag/心理学/") ?>" <?php if(isset($tagName) &&($tagName=='心理学')):$i=true;?>class="active"<?php endif; ?>>心理学</a>
							<a href="<?php echo site_url("book/tag/互联网/") ?>" <?php if(isset($tagName) &&($tagName=='互联网')):$i=true;?>class="active"<?php endif; ?>>互联网</a>
							<?php if((false==$i)&&(isset($tagName))): ?>
							<a href="<?php echo site_url("book/tag/{$tagName}/") ?>" class="active"><?php echo $tagName; ?></a>
							<?php endif; ?>
							<span class="more"><a href="<?php echo site_url("book/tags/") ?>" >更多</a></span>
						</p>
						<ul>
						<?php $i=0; ?>
						<?php foreach ($lastestAvailableBooks as $book): ?>
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
					<div style="height:auto; border-top:1px solid #EEE; padding:15px 0;">
						<p style="float:left; font-size:14px;">
							<a href="<?php echo site_url("borrow/request/"); ?>">还没找到想借的书？点此发布一条想借信息</a>
						</p>
						<?php echo $this->pagination->create_links(); ?>
					</div>
				</div>
			</div>
			<div class="lib_contentModule book_list3 content_box">
				<?php $this->load->view("book/components/slide_books.php", $slideBooks); ?>
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