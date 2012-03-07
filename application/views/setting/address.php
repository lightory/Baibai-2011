	<div id="body">
		<div class="inner">
			<div id="config" class="content_box content">
				<div class="contentBox_tabNav">
          <a href="<?php echo site_url("setting/profile"); ?>" class="contentBox_tabNav_tab first">个人资料</a><!--
          --><a href="<?php echo site_url("setting/address"); ?>" class="contentBox_tabNav_tab current">收货地址</a><!--
			    --><a href="<?php echo site_url("setting/sync"); ?>" class="contentBox_tabNav_tab">同步动态</a><!--
		      --><a href="<?php echo site_url("setting/notify"); ?>" class="contentBox_tabNav_tab">邮件提醒</a>
        </div>
				<form class="profile" method="post" action="<?php echo site_url('setting/address_do/'); ?>">
					<input type="text" name="id" value="<?php echo $address->id; ?>" style="display:none;" ?>
					<?php if ($this->session->flashdata('error')){ ?>
					<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
					<?php } ?>
					<p>
						<label for="name">收货人：</label>
						<input type="text" name="name" id="name" class="text" value="<?php echo $address->name; ?>" />
					</p>
					<p>
						<label for="province">地区：</label>
						<select name="province" id="province"></select>
						<select name="city" id="city"></select>
						<select name="district" id="district"></select>
						<input name="province2" id="province2" value="<?php echo $address->province; ?>" style="display:none" />
							<input name="city2" id="city2" value="<?php echo $address->city; ?>" style="display:none" />
							<input name="district2" id="district2" value="<?php echo $address->district; ?>" style="display:none" />
					<p>
						<label for="address">街道地址：</label>
						<input type="text" name="address" id="address" class="text" value="<?php echo $address->address; ?>" />
					</p>
					<p>
						<label for="postcode">邮政编码：</label>
						<input type="text" name="postcode" id="postcode" class="text" value="<?php echo $address->postcodeTrue; ?>" />
					<p>
						<label for="mobile">手机号码：</label>
						<input type="text" name="phone" id="mobile" class="text" value="<?php echo $user->mobile; ?>" disabled />
						<a href="<?php echo site_url('setting/profile/'); ?>" target="_blank">修改</a>
					</p>
					<p>
						<input type="submit" value="好了，修改吧" class="nolabel button" />
					</p>
				</form>
			</div>
			<?php echo $this->Common->sidebar(); ?>
			<div class="clearfix"></div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.pack.js'; ?>"></script>
<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.extendByYt.js'; ?>"></script>
<script type="text/javascript"> 
$('form.profile').validate({   
/* 设置验证规则 */  
rules: {   
	name: {
		required:true
	},
	phone:{   
		required:true,   
		isPhone:true  
	},   
	address:{   
		required:true,   
		byteRangeLength:[10,100]   
	}   
},   
     
/* 设置错误信息 */  
messages: {   
	name: {
		required: "请填写姓名"
	},
	phone:{   
		required: "请输入您的联系电话",   
		isPhone: "请输入一个有效的联系电话"  
	},   
	address:{   
		required: "请输入您的联系地址",   
		byteRangeLength: "请详实您的联系地址以便于能够收到书籍"  
	}   
},   

/* 设置验证触发事件 */  
focusInvalid: false,   
onkeyup: true,   
    
/* 设置错误信息提示DOM */  
errorPlacement: function(error, element) {       
	error.appendTo( element.parent());       
},     
  
});  
</script>

<script type="text/javascript" src="<?php echo site_url('include/script/').'districtData.js'; ?>"></script>
<script type="text/javascript" src="<?php echo site_url('include/script/').'district.js'; ?>"></script>
<script type="text/javascript">
  var district = '<?php echo $address->postcode; ?>';
  var city = data[district][1];
  var province = data[city][1];
  
  if(province == 1){
    city = '<?php echo $address->postcode; ?>';
    province = data[city][1];
  }

	$('#province').val(province);
	refreshCity();
	$('#city').val(city);
	refreshDistrict();
	$('#district').val(district);
</script>