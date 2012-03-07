<div id="body">
	<div class="inner">
		<div class="content_box">
			<div class="contentBox_tabNav">
				<a href="<?php echo site_url('history/donate/'); ?>" class="contentBox_tabNav_tab first current">我捐赠的书</a><!--
        --><a href="<?php echo site_url('history/borrow/'); ?>" class="contentBox_tabNav_tab">我借过的书</a>
      </div>
			<div class="orderList">
				<div class="orderList_title">
					<span class="orderList_li_book orderList_item">书籍</span>
					<span class="orderList_li_user orderList_item">当前持有者</span>
					<span class="orderList_li_num orderList_item">已传阅</span>
					<span class="orderList_li_time orderList_item">捐赠时间</span>
					<span class="orderList_li_time orderList_item">最后活动时间</span>
					<div class="clearfix"></div>
				</div>
				<?php foreach($stocks as $stock):
					$book = $this->MBook->getById($stock->bookId);
					$reader = $this->MUser->getByUid($stock->readerId);
					$reader->address = $this->MAddress->getDefaultByUid($stock->readerId);
					$readersCount = $this->MStock->getReadersCount($stock->id);
				?>
				<div class="orderList_li">
					<div class="orderList_li_book orderList_item">
						<img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="orderList_li_book_cover" />
						<span>
							<h3><a href="<?php echo site_url("book/subject/$book->id/"); ?>"><?php echo $book->name; ?></a></h3>
						</span>
					</div>
					<div class="orderList_li_user orderList_item">
						<span>
							<a href="<?php echo $this->MUser->getUrl($reader->uid); ?>"><?php echo $reader->nickname.'('.$reader->address->province.')'; ?></a>
						</span>
					</div>
					<div class="orderList_li_num orderList_item">
						<span><?php echo $readersCount.' 人'; ?></span>
					</div>
					<div class="orderList_li_time orderList_item">
						<span><?php echo $stock->sentTime; ?></span>
					</div>
					<div class="orderList_li_time orderList_item">
						<span><?php echo mdate('%Y-%m-%d', $stock->transforTime); ?></span>
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