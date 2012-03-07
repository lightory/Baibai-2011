<div id="body">
	<div class="inner">
		<div class="content">
			<div class="content_box" style="padding:20px;">
				<div class="successBox">
					<img src="<?php echo site_url("include/style/img/").'alertInfo_icon.png'; ?>" />
					<div class="successBox_info">
						<h3>上架成功</h3>
						<?php if($bookNeeds>0): ?>
							<p><?php echo $bookNeeds; ?>人想借《<?php echo $book->name; ?>》，去看看他们都为什么需要这本书吧</p>
							<p>
								<a href="<?php echo site_url("mine/available/")."#book-{$book->id}" ?>" class="button">查看请求</a>
								<a href="<?php echo site_url("book/donate"); ?>" class="fancybox button2" onclick="return false;">继续捐赠</a>
								<a href="<?php echo site_url("book/"); ?>" class="button2">查看书架</a>
							</p>
						<?php else: ?>
							<p>《<?php echo $book->name; ?>》已经上架，有人想借时，您会收到邮件通知～</p>
							<p>
								<a href="<?php echo site_url("book/donate"); ?>" class="fancybox button" onclick="return false;">继续捐赠</a>
								<a href="<?php echo site_url("book/"); ?>" class="button2">查看书架</a>
								<a href="<?php echo site_url("book/subject/{$book->id}/"); ?>" class="button2">查看本书</a>
							</p>
						<?php endif; ?>
					</div>
				</div>
				<?php 
					if(sizeof($slides1['books'])) $this->load->view("book/components/slide_books.php", $slides1); 
					$this->load->view("book/components/slide_books.php", $slides2);
				?>
			</div>
		</div>
		<div class="sidebar">
			<?php 
				$this->load->view("sidebar/components/sns_links.php"); 
				$this->load->view("sidebar/components/ext_links.php"); 
			?>	
		</div>
		<div class="clearfix"></div>
	</div>
</div>