	<div id="body">
		<div class="inner">
			<div class="content">
				<div class="content_box book_list4">
					<?php if(!isset($scope) || ($scope=='all')): ?>
          <h3><a href="<?php echo site_url("book/all/"); ?>">全部书架</a> > <?php echo $title; ?></h3>
          <?php elseif($scope=='area'): ?>
          <h3><a href="<?php echo site_url("book/area/"); ?>">区域书架</a> > <?php echo $title; ?></h3>
          <?php endif; ?>
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
							<p class="buttons">
								<a href="<?php echo site_url("book/iwantborrow/$book->id/"); ?>" onclick="return false;"  class="iwantborrow fancybox button2">想借</a>
							</p>
						</li>
						<?php endforeach; ?>
					</ul>
					<div style="height:auto; padding:0 0 10px;">
						<p style="float:left; font-size:14px;">
							<a href="<?php echo site_url("book/borrow/"); ?>" onclick="return false;" class="fancybox">还没找到想借的书？点此发布一条想借信息</a>
						</p>
						<?php echo $this->pagination->create_links(); ?>
					</div>
					
					<?php if (sizeof($books)==0): ?>
					<div class="searchNotFound">
						<h4>很抱歉目前还没有人捐赠这本书</h4>
						<p>
							<span class="searchNotFound-label">您可以</span>
							<a href="<?php echo site_url("book/borrow/"); ?>" onclick="return false;"  class="searchNotFount-donate fancybox button">发一条求借信息</a>
							或者去<a href="<?php echo site_url('book/'); ?>">书架</a>看看有什么新书
						</p>
						<form class="search inline-search" method="POST" action="<?php echo site_url('book/search/'); ?>" >
						<p>
							<span class="searchNotFound-label">或者</span>
								<input type="text" name="keyword" class="keyword" />
								<input type="submit" class="submit" />
						</p>
						</form>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="sidebar">
				<?php 
					$this->load->view("sidebar/components/sns_links.php"); 
					$this->load->view("sidebar/components/ext_links.php"); 
				?>
  			<div class="sidebar_tagBox">
           <h3>热门标签</h3>
           <a class="more" href="<?php echo site_url("book/tags/"); ?>">更多</a>
           <ul>
              <?php 
                $i=0;
                foreach($tags as $tag):
                  $i++;
                  if($i==1){
                    $maxCount = $tag->count;
                    $tag->width = '100';
                  } else{
                    $tag->width = 100*$tag->count/$maxCount;
                  }
              ?>
              <li><a href="<?php echo site_url("book/tag/$tag->tag/"); ?>" style="width:<?php echo $tag->width; ?>%;"><?php echo $tag->tag; ?></a></li>
              <?php endforeach; ?>
           </ul>
        </div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>