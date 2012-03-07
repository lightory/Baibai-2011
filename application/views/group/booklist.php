<div id="body">
  <div class="inner">
		<div class="breadcrumbs">
			<a href="<?php echo site_url("group/"); ?>">小组</a>
			<span style="font-size:12px;">&gt;</span>
			<a href="<?php echo site_url("group/$group->url/"); ?>"><?php echo $group->name; ?></a>
		</div>
    <div class="content_box content group">
      <div class="contentBox_tabNav">
        <a href="<?php echo site_url("group/$group->url/"); ?>" class="contentBox_tabNav_tab first">讨论</a><!--
        --><a href="<?php echo site_url("group/$group->url/books"); ?>" class="contentBox_tabNav_tab current">藏书</a>
      </div>
      <div class="group_main book_list4">
				<ul>
					<?php foreach($books as $book): ?>
					<li>
						<a href="<?php echo site_url("book/subject/$book->id/"); ?>"><img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>"  class="book_cover" /></a>
						<div class="book_desc">
							<p class="book_name"><a href="<?php echo site_url("book/subject/$book->id/"); ?>"><?php echo $book->name; ?></a></p>
							<p class="book_info">
								<?php echo "$book->author / $book->pubdate / $book->publisher / $book->price"; ?>
							</p>
							<p class="book_stock">
								<?php
									$stockNumber = $this->MBook->getInventory($book->id);
									$availableStockNumber = $this->MBook->getRealInventory($book->id);
									$needNumber = $this->MBook->getNeeds($book->id);
								?>
								<span class="stock_number"><?php echo $availableStockNumber.' / '.$stockNumber; ?></span>库存 | 
								<?php echo $needNumber; ?>人想借
							</p>
						</div>
						<?php if(($group->userType=='owner') || ($this->MGroupBook->isMyColletion($group->id, $book->id, $this->session->userdata('uid')))): ?>
						<p class="buttons">
							<a href="<?php echo site_url("group/removebook/$group->id/$book->id/"); ?>" onclick="return false;"  class="fancybox button2">删除</a>
						</p>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php echo $this->pagination->create_links(); ?>
      </div>
    </div>