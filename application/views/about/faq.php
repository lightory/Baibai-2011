	<div id="body">
		<div class="inner">
			<div class="content">
				<div class="content_box about_box">
					<?php if(isset($firstlogin)): ?>
					<h3>恭喜您成为摆摆书架的会员！请花一分钟读读下面的简介吧：</h3>
					<?php else: ?>
					<h3>FAQ</h3>
					<?php endif; ?>
					<ul>
						<li>
							<h4>1.什么是摆摆书架？</h4>
							<p>
								摆摆书架是一个社会化图书馆。<br/>
								摆摆书架的所有藏书均来自会员捐赠、寄存在会员处，并且所有会员都可以随时借阅。
							</p>
						</li>
						<li>
							<h4>2.什么是捐书？</h4>
							<p>
								将您闲置的书捐赠给摆摆书架，提供给其它会员借阅。<br/>
								在其它人借阅之前，书籍仍然寄存在您这儿。
							</p>
						</li>
						<li>
							<h4>3.我读完了借到的书，如何归还？</h4>
							<p>
								不需要归还。<br/>
								您可以将其标记为“已读完”，等待其它人借阅。在有人借阅之前，请您代为保管。
							</p>
						</li>
						<li id="borrowQuote">
							<h4>4.什么是摆摆券？</h4>
							<p>
								摆摆券决定了您还可以借多少本书。<br/>
								当摆摆券为零时，您将无法继续借书（您的所有借书请求会被隐藏，仅自己可见）。
							</p>		
						</li>
						<li>
							<h4>5.如何增加摆摆券？</h4>
							<p>每当您成功寄出一本书，摆摆券就会增加。</p>
						</li>
						<li id="expressFee">
							<h4>6.邮寄书籍的费用由谁承担？为什么？</h4>
							<p>
								建议由寄书方承担。<br/>
								当你寄出别人需要的书的时候，你在摆摆书架上的借书名额会增加一点，将来也会获得一本自己需要的书。某种意义上，其实是平等的。
							</p>
						</li>
					</ul>
					<?php if(isset($firstlogin)): ?>
					<p style="padding-bottom:20px;">
						<a href="<?php echo site_url('mine/') ?>" class="button">我知道了，进入摆摆书架</a>
					</p>
					<?php endif; ?>
				</div>
			</div>
			<div class="sidebar">
			</div>
			<div class="clearfix"></div>
		</div>
	</div>