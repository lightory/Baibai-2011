<div id="body">
	<div class="inner">
		<div class="content_box">
			<div class="contentBox_tabNav">
				<a href="<?php echo site_url('history/donate/'); ?>" class="contentBox_tabNav_tab first">我捐赠的书</a><!--
        --><a href="<?php echo site_url('history/borrow/'); ?>" class="contentBox_tabNav_tab current">我借过的书</a>
      </div>
			<div class="orderList">
				<div class="orderList_title">
					<span class="orderList_li_book orderList_item">书籍</span>
					<span class="orderList_li_time orderList_item">借阅时间</span>
					<span class="orderList_li_time orderList_item">归还时间</span>
					<div class="clearfix"></div>
				</div>
				<?php foreach($stocks as $stock):
					$book = $this->MBook->getById($stock->bookId);
					$senter = $this->MUser->getByUid($stock->senterUid);
					$receiver = $this->MUser->getByUid($stock->receiverUid);
				?>
				<div class="orderList_li">
					<div class="orderList_li_book orderList_item">
						<img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="orderList_li_book_cover" />
						<span>
							<h3><a href="<?php echo site_url("book/subject/$book->id/"); ?>"><?php echo $book->name; ?></a></h3>
						</span>
					</div>
					<div class="orderList_li_time orderList_item">
						<span>
							<?php echo $stock->receiveTime; ?><br/>
							来自 <a href="<?php echo $this->MUser->getUrl($senter->uid); ?>"><?php echo $senter->nickname; ?></a>
						</span>
					</div>
					<div class="orderList_li_time orderList_item">
						<span>
							<?php echo $stock->sentTime; ?><br/>
							借给 <a href="<?php echo $this->MUser->getUrl($receiver->uid); ?>"><?php echo $receiver->nickname; ?></a>
						</span>
					</div>
					<div class="orderList_item orderList_moreLink">
						<span>
							<a href="<?php echo site_url("book/stock/$stock->id/"); ?>" class="button">漂流轨迹</a>
						</span>
					</div>
					<div class="clearfix"></div>
				</div>
				<?php endforeach; ?>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>