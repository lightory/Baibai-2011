<?php
	$books = array_chunk($books, 5);
	$id = isset($id) ? $id : 'slide';
?>
<div class="slideBooks book_list3" id="<?php echo $id; ?>" style="position:relative;">
	<div class="slideBooks_header">
		<h3 style="margin-bottom:0;"><?php echo $title; ?></h3>
		<?php if(isset($tips)): ?>
			<span style="color:#999; margin-top:10px; display:block;"><?php echo $tips; ?></span>
		<?php endif; ?>
		<?php if(isset($viewMoreLink)): ?>
		<p class="slideBooks_btn">
			<a href="<?php echo $viewMoreLink; ?>" style="font-size:14px;position: relative; bottom: 16px;">查看更多</a>
		</p>
		<?php else: ?>
		<p class="slideBooks_btn">
			<a class="btn-prev" href="javascript:void(0);"><</a>
			<a class="btn-next" href="javascript:void(0);">></a>
		</p>
		<?php endif; ?>
	</div>
	<div class="slideBooks_content" style="margin-top:10px;">
		<?php $j=0; ?>
		<?php foreach($books as $fiveBooks): ?>
			<?php $j++; ?>
			<ul style="left:<?php echo $j*618-618; ?>px;">
				<?php $i=0; ?>
				<?php foreach ($fiveBooks as $book): ?>
					<?php $i++; ?>
					<li <?php if(0 == $i%5){ echo 'style="margin:0;"';} ?>>
						<a href="<?php echo site_url("book/subject/$book->id/"); ?>" title="<?php echo $book->name; ?>">
							<img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="book_cover" />
							<span class="book_name"><?php echo $book->name; ?></span>
						</a>
					</li>
		       <?php endforeach; ?>
		     </ul>
	     <?php endforeach; ?>
	</div>
</div>

<?php if(!isset($viewMoreLink)): ?>
<script type="text/javascript">
// @todo: 这堆代码太恶心了，以后要找时间干掉
if (!slideMax) var slideMax = [];
if (!slideCurrent) var slideCurrent = [];
if (!sliding) var sliding = [];

slideMax['<?php echo $id; ?>'] = <?php echo sizeof($books); ?>;
slideCurrent['<?php echo $id; ?>'] = 1;
sliding['<?php echo $id; ?>'] = false;

if (1==slideMax['<?php echo $id; ?>']) {
	$('.slideBooks_btn', '#'+'<?php echo $id; ?>').hide();
}

var checkSlideBtn = function(i){
	if(slideCurrent[i]==1){
		$('.btn-prev', '#'+i).css('background-position', '18px 18px');
	} else{
		$('.btn-prev', '#'+i).css('background-position', '0 18px');
	}
	if(slideCurrent[i]==slideMax[i]){
		$('.btn-next', '#'+i).css('background-position', '18px 0');
	} else{
		$('.btn-next', '#'+i).css('background-position', '0 0');
	}
}

$('.btn-next', '#'+'<?php echo $id; ?>').click(function(){
  if(slideCurrent['<?php echo $id; ?>']==slideMax['<?php echo $id; ?>'] || sliding['<?php echo $id; ?>']){
    return false;
  }
  
  sliding['<?php echo $id; ?>'] = true;
  $(this).parent().parent().parent().children('div.slideBooks_content').children().each(function(){
    var left = parseInt($(this).css('left'));
    $(this).animate( {left:left-618}, 'slow');
  });
  
  slideCurrent['<?php echo $id; ?>']++;
  checkSlideBtn('<?php echo $id; ?>');
  sliding['<?php echo $id; ?>'] = false;
});

$('.btn-prev', '#'+'<?php echo $id; ?>').click(function(){
  if(slideCurrent['<?php echo $id; ?>']==1 || sliding['<?php echo $id; ?>']){
    return false;
  }
  
  sliding['<?php echo $id; ?>'] = true;
  $(this).parent().parent().parent().children('div.slideBooks_content').children().each(function(){
    var left = parseInt($(this).css('left'));
    $(this).animate( {left:left+618}, 'slow');
  });
  
  slideCurrent['<?php echo $id; ?>']--;
  checkSlideBtn('<?php echo $id; ?>');
  sliding['<?php echo $id; ?>'] = false;
});

checkSlideBtn('<?php echo $id; ?>');
</script>
<?php endif; ?>