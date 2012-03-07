<div class="content_box book_info_box">
    <a href="<?php echo site_url("book/subject/$book->id/"); ?>">
		<img class="bookBox_cover" src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" />
	</a>
	<div class="bookBox_info">
		<h3 style="width:160px;"><a href="<?php echo site_url("book/subject/$book->id/"); ?>"><?php echo $book->name; ?></a></h3>
		<ul>
			<li>作者：<?php echo $book->author; ?></li>
			<li>出版时间：<?php echo $book->pubdate; ?></li>
			<li>上架时间：<?php echo mdate('%Y-%m-%d', $book->time); ?></li>
		</ul>
	</div>
	<div class="clearfix"></div>
</div>