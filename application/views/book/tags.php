<div id="body">
	<div class="inner">
		<div class="content">
			<div class="content_box book_list4">
				<h3><a href="<?php echo site_url("book/"); ?>">书架</a> > <?php echo $title; ?></h3>
				<div class="tagsCloud">
					<?php foreach($tags as $tag): ?>
						<a href="<?php echo site_url("book/tag/$tag->tag"); ?>" class="tagsCloud_tag <?php if($tag->count<=25){echo 'tagsCloud_tag1';}elseif($tag->count<=50){echo 'tagsCloud_tag2';}elseif($tag->count<=100){echo 'tagsCloud_tag3';}elseif($tag->count<=250){echo 'tagsCloud_tag4';}else{echo 'tagsCloud_tag5';} ?>">
						<?php echo "$tag->tag($tag->count)"; ?>
					</a>
					<?php endforeach; ?>
				</div>
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