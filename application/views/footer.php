	<div id="footer">
		<div class="inner">
			<span class="copyright">&copy; 2010－2011 <a href="<?php echo site_url(); ?>">摆摆书架 bookfor.us</a></span>
			<span class="links">
				<a href="http://blog.bookfor.us">博客</a>
				<a href="<?php echo site_url('about/'); ?>">关于</a>
			</span>
			<a class="info" href="<?php echo site_url('book/'); ?>">
			   摆摆书架目前共有 <span><?php echo $stockNumber; ?></span> 本书 · 
			   来自 <span><?php echo $cityNumber; ?></span> 个城市 · 
			   已节省 <span><?php echo $savedMoney; ?></span> 元
			</a>
		</div>
	</div>
	<?php if($this->session->userdata('uid')): ?>
	<a href="<?php echo site_url('group/baibai/'); ?>" class="feedback" title="欢迎反馈摆摆书架的使用问题、Bug和建议～">意见反馈</a>
  <?php endif; ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19615786-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
  try {
     var pageTracker = _gaq._getTracker("UA-19615786-1");
     pageTracker._addOrganic("baidu", "word");
     pageTracker._addOrganic("soso", "w");
     pageTracker._addOrganic("3721", "name");
     pageTracker._addOrganic("yodao", "q");
     pageTracker._addOrganic("vnet", "kw");
     pageTracker._addOrganic("sogou", "query");
  } catch (err) { }
</script>
</body>
</html>