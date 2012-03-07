	<div id="body">
		<div class="inner">
			<div id="login" class="content_box">
				<div class="login_main">
					<h2>登录</h2>
					<form class="profile" method="post" action="<?php echo site_url("account/login_do/"); ?>">
						<?php if ($this->session->flashdata('error')){ ?>
						<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
						<?php } ?>
						<p>
							<label for="email">Email：</label>
							<input type="text" name="email" id="email" class="text" />
						</p>
						<p>
							<label for="password">密码：</label>
							<input type="password" name="password" id="password" class="text" />
						</p>
						<p>
							<label class="nolabel">
								<input type="checkbox" name="remember" value="1" />记住我
							</label>
							<a href="<?php echo site_url("account/resetpassword/"); ?>" id="forget_pw">忘记密码</a>
						</p>
						<p>
							<input type="submit" value="登录" class="nolabel button" />
						</p>
					</form>
				</div>
				<div class="login_side">
					<p><a href="<?php echo site_url('account/apply/'); ?>">我还没有注册</a></p>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>