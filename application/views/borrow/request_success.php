<div id="body">
	<div class="inner">
		<div class="content">
			<div class="content_box" style="padding:20px;">
				<div class="successBox">
					<img src="<?php echo site_url("include/style/img/").'alertInfo_icon.png'; ?>" />
					<div class="successBox_info">
						<h3>您已经成功发送借书请求</h3>
						<?php if($user->borrowQuote>0): ?>
							<?php if($bookRealInventory>0): ?>
								<p><?php echo "《{$book->name}》目前有 {$bookRealInventory} 本库存，请等待书籍持有者答复您。"; ?></p>
							<?php elseif($i = $bookInventory-$bookRealInventory): ?>
								<p><?php echo "有 {$i} 本《{$book->name}》正在漂流中，漂流的下一站或许就是你哦～"; ?></p>
							<?php else: ?>
								<p><?php echo "《{$book->name}》目前没有库存，或许不久就会有好心人捐一本上来～"; ?></p>
							<?php endif; ?>
						<?php else: ?>
							<p>但当前您的摆摆券不足，借书请求仅自己可见。请及时补充摆摆券。</p>
						<?php endif; ?>
						<p>
							<a href="<?php echo site_url("book/") ?>" class="button">返回书架</a>
							<a href="<?php echo site_url("book/subject/{$book->id}/"); ?>" class="button2">查看本书</a>
						</p>
					</div>
				</div>
				<?php 
					if(sizeof($slides1['books']))
						$this->load->view("book/components/slide_books.php", $slides1); 
					if($user->borrowQuote<=0)
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