<?php

class Setting extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->Validate->isLogin();
	}
	
	function profile(){
		$this->load->model('MUser');
		$this->load->helper('gravatar');
		$user = $this->MUser->getByUid($this->session->userdata('uid'));
		$user->avatar = getGravatar($user->email, 45);
		
		$headerData['title'] = '修改资料';
		$headerData['current'] = 'home';
		$data['user'] = $user;
		
		$this->load->view('header', $headerData);
		$this->load->view('setting/profile', $data);
		$this->Common->footer();
	}
	
	function profile_do(){
		$this->load->model('MUser');
		
		$uid = $this->session->userdata('uid');
		$nickname = $this->input->post('nickname');
		$blog = $this->input->post('blog');
		$bio = str_replace('<', '&lt;', $this->input->post('bio'));
		$this->MUser->updateProfile($uid, $nickname, $blog, $bio);
		
		$user = $this->MUser->getByUid($uid);
		if (!$user->isMobileValidate) {
			$mobile = $this->input->post('mobile');
			if ($this->MUser->isMobileBind($mobile)) {
				$this->session->set_flashdata('error', '该手机号码已经被其他人使用');
				redirect("setting/profile/");
			} else {
				$this->MUser->updateMobile($uid, $mobile);
			}
		}
		
		if ($username = strtolower($this->input->post('username'))){
			if (!preg_match("/^[a-z]{1}[0-9a-z]{1,15}$/",$username)){
				$this->session->set_flashdata('error', '用户名中只能出现英文字母和数字，且必须以字母开始');
				redirect('setting/profile/');
			}
			
			if (!$this->MUser->updateUsername($uid, $username)){
				$this->session->set_flashdata('error', '该用户名已存在');
				redirect('setting/profile/');
			}
		}
		
		$this->session->set_flashdata('error', '您的个人资料已更新。');
		redirect('setting/profile/');
	}
	
	function password(){
		$headerData['title'] = '修改密码';
		$headerData['current'] = 'home';
		
		$this->load->view('header', $headerData);
		$this->load->view('setting/password');
		$this->Common->footer();
	}
	
	function password_do(){
		$this->load->model('MUser');
		
		$uid = $this->session->userdata('uid');
		$oldPW = $this->input->post('oldPW');
		$newPW = $this->input->post('newPW');
		
		if($this->MUser->changePassword($uid, $oldPW, $newPW)){
			$this->session->set_flashdata('error', '您的密码已更新。');
		} else{
			$this->session->set_flashdata('error', '原始密码输入错误。');
		}
		redirect('setting/password/');
	}
	
	function mobile() {
		$this->load->model('MUser');
		
		$user = $this->MUser->getByUid($this->session->userdata('uid'));
		if ($user->isMobileValidate) {
			redirect('setting/profile/');
		}
		
		$mobile = $this->input->post('mobile');
		
		$headerData['title'] = '绑定手机';
		$headerData['current'] = 'home';
		
		$data['user'] = $user;
		$data['mobile'] = $mobile;
		
		if ($mobile && !preg_match('/^1(3|4|5|8)\d{9}$/', $mobile)) {
			$this->session->set_flashdata('error', '请正确输入您的手机号码');
			redirect('setting/mobile/');
		}
		
		if ($this->MUser->isMobileBind($mobile)) {
			$this->session->set_flashdata('error', "其他用户已经绑定 {$mobile}");
			redirect('setting/mobile/');
		}
		
		if ($mobile) {
			$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
			$this->cache->memcached->is_supported();
			$cache1Name = "userId-{$user->uid}-validate";
			$cache2Name = "mobile-{$mobile}-validate";
			$cahce1 = $this->cache->get($cache1Name);
			$cache2 = $this->cache->get($cache2Name);
			$cache1->count++;
			$cache2->count++;
			if ($cache1->count > 3) {
				$this->session->set_flashdata('error', "您已经连续尝试验证 3 次，请一小时后再试。");
				redirect('setting/mobile/');
			}
			if ($cache2->count > 3) {
				$this->session->set_flashdata('error', "该号码已经连续尝试验证 3 次，请一小时后再试。");
				redirect('setting/mobile/');
			}
			$this->cache->delete($cache1Name);
			$this->cache->delete($cache2Name);
			$this->cache->save($cache1Name, $cache1, 1*60*60);
			$this->cache->save($cache2Name, $cache2, 1*60*60);
			
			$this->load->library('sms');
			$mobilePassword = $this->MUser->mobilePassword($mobile);
			$msg = "摆摆书架账号绑定验证码 {$mobilePassword}，本验证码在今天有效。";
			$this->sms->send($mobile, $msg);
		}
		
		$this->load->view('header', $headerData);
		$this->load->view('setting/mobile', $data);
		$this->Common->footer();
	}
	
	function mobile_link_do($mobile) {
		$this->load->model('MUser');
		
		$userId = $this->session->userdata('uid');
		$user = $this->MUser->getByUid($userId);
		if ($user->isMobileValidate) {
			redirect('setting/profile/');
		}
		
		$code = $this->input->post('code');
		
		if ($this->MUser->isMobileBind($mobile)) {
			$this->session->set_flashdata('error', "其他用户已经绑定 {$mobile}");
			redirect('setting/mobile/');
		}
		
		if (!$mobile || !$code || strlen($mobile)!=11) {
			$this->session->set_flashdata('error', "手机号码填写有误");
			redirect("setting/mobile/");
		}
		
		if (($code == $this->MUser->mobilePassword($mobile)) && ($this->MUser->bindMobile($userId, $mobile))) {
			$this->load->model('MEventLog');
			$eventCategory = $this->config->item('EVENT_CATEGORY_MOBILEAUTHGIFTBAIBAIQUAN');
			if (!$this->MEventLog->getByUserId($userId, $eventCategory) && !$this->MEventLog->getByTitle($mobile, $eventCategory)) {
				$this->MUser->addBorrowQuote($userId);
				
				$event->uid = $userId;
				$event->title = $mobile;
				$event->content = '';
				$event->category = $eventCategory;
				$this->MEventLog->insert($event);
				$this->session->set_flashdata('error', "首次绑定手机，获得摆摆券一张^^");
			} else {
				$this->session->set_flashdata('error', "成功绑定手机");
			}
			redirect('setting/profile/');
		} else {
			$this->session->set_flashdata('error', "绑定失败，请重试");
			redirect('setting/mobile/');
		}
	}
	
	function mobile_unlink_do() {
		$this->load->model('MUser');

		$userId = $this->session->userdata('uid');
		$this->MUser->unbindMobile($userId);
		
		$this->session->set_flashdata('error', "成功解除绑定手机");
		redirect('setting/profile/');
	}
	
	function address(){
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$user = $this->MUser->getByUid($this->session->userdata('uid'));
		$address = $this->MAddress->getDefaultByUid($this->session->userdata('uid'));
		
		$headerData['title'] = '修改地址';
		$headerData['current'] = 'home';
		$data['user'] = $user;
		$data['address'] = $address;
		
		$this->load->view('header', $headerData);
		$this->load->view('setting/address', $data);
		$this->Common->footer();
	}
	
	function address_do(){
		$this->load->model('MUser');
		$this->load->model('MAddress');
		
		// TBD
		// 验证该地址是否是该用户的
		
		// Change Address
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$postcode = $this->input->post('district') ? $this->input->post('district') : $this->input->post('city');
		$postcodeTrue = $this->input->post('postcode');
		$province = $this->input->post('province2');
		$city = $this->input->post('city2');
		$district = $this->input->post('district2');
		$address = $this->input->post('address');
		$this->MAddress->changeAddress($id, $name, $postcode, $postcodeTrue, $province, $city, $district, $address);
		
		// Redirect
		$this->session->set_flashdata('error', '您的地址已更新。');
		redirect('setting/address/');
	}

	function notify(){
		$this->load->model('MEmailNotify');
    
		$headerData['title'] = '邮件提醒';
		$headerData['current'] = 'home';
		$data['notifySetting'] = $this->MEmailNotify->getByUid($this->session->userdata('uid'));
    
		$this->load->view('header', $headerData);
		$this->load->view('setting/notify', $data);
		$this->Common->footer();
	}
	
	function notify_do(){
		$this->load->model('MEmailNotify');
    
		$uid = $this->session->userdata('uid');
		$weekly = $this->input->post('weekly') ? '1' : '0';
		$mail = $this->input->post('mail') ? '1' : '0';
		$this->MEmailNotify->update($uid, $weekly, $mail);
		
		// Redirect
		$this->session->set_flashdata('error', '已更新邮件提醒设置');
		redirect('setting/notify/');
	}
	
	function sync(){
		$this->load->model('MUserOAuth');
		
		$headerData['title'] = '同步动态';
		$headerData['current'] = 'home';
    
		$data['myLinkedProviders'] = $this->MUserOAuth->getUserLinkedProviders($this->session->userdata('uid'));
    
		$this->load->view('header', $headerData);
		$this->load->view('setting/sync', $data);
		$this->Common->footer();
	}
	
	function link(){
		$this->load->model('MUserOAuth');
		$this->load->helper('oauth');
		
		$provider = $_GET['provider'];
		switch($provider){
			case 'tsina':
				$baseurl = 'http://api.t.sina.com.cn/oauth/request_token';
				$authorizeUrl = 'http://api.t.sina.com.cn/oauth/authorize';
				$appKey = '1470536138';
				$appSecret = '0051ebf056d66dad5a26c968080420f0';
				break;
			case 'douban':
				$baseurl = 'http://www.douban.com/service/auth/request_token';
				$authorizeUrl = 'http://www.douban.com/service/auth/authorize';
				$appKey = '05550a4a098bad3d0fff1ede1ca307b7';
				$appSecret = 'd860bf42aaeeab8b';
				break;
			case 'tqq':
				$baseurl = 'https://open.t.qq.com/cgi-bin/request_token';
				$authorizeUrl = 'https://open.t.qq.com/cgi-bin/authorize';
				$appKey = '2595e7182a3d445f93778287e9a869f2';
				$appSecret = '482918146ad547ef6c00d3235e3056f7';
				break;
		}
		
		$auth = build_auth_array($baseurl, $appKey, $appSecret, array('oauth_callback'=>urlencode(site_url('setting/link_callback/').'?provider='.$provider)));
		
		if($provider=='tqq'){
			$str = '';
			foreach($auth AS $key=>$value)
				$str .= "&{$key}={$value}";//Do not include scope in the Authorization string.
			$str = substr($str, 1);//Remove leading ,
			//echo $str.'<br/><br/>';
			//echo "{$baseurl}?{$str}";
			
			$ch = curl_init("{$baseurl}?{$str}");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth realm=\"\", {$str}"));
			$response = curl_exec($ch);
			curl_close($ch);
		} else{
			$str = '';
			foreach($auth AS $key=>$value)
				$str .= ", {$key}=\"{$value}\"";//Do not include scope in the Authorization string.
			$str = substr($str, 2);//Remove leading ,
			//echo $str.'<br/><br/>';

			$ch = curl_init("{$baseurl}");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth realm=\"\", {$str}"));
			$response = curl_exec($ch);
			curl_close($ch);
		}
		
		//echo $response;
		$token = parse_token_to_array($response);
		//print_r($token);
		
		$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), $provider);
		if($userOAuth && $userOAuth->access_token && $userOAuth->access_token_secret){
			redirect('setting/sync/');
		}
		if(!$userOAuth || !$userOAuth->request_token_secret){
			$this->MUserOAuth->insert($this->session->userdata('uid'), $provider, $token['oauth_token_secret']);
		}else{
			$this->MUserOAuth->updateRequestToken($this->session->userdata('uid'), $provider, $token['oauth_token_secret']);
		}
		
		$callback = site_url('setting/link_callback/').'?provider='.$provider;
		$authorizeUrl .= '?oauth_token='.$token['oauth_token'].'&oauth_callback='.urlencode($callback);
		//echo $authorizeUrl;
		header('Location: '.$authorizeUrl);
	}
	
	function link_callback(){
		$this->load->model('MUserOAuth');
		$this->load->helper('oauth');
		
		$provider = $_GET['provider'];
		switch($provider){
			case 'tsina':
				$baseurl = 'http://api.t.sina.com.cn/oauth/access_token';
				$appKey = '1470536138';
				$appSecret = '0051ebf056d66dad5a26c968080420f0';
				break;
			case 'douban':
				$baseurl = 'http://www.douban.com/service/auth/access_token';
				$appKey = '05550a4a098bad3d0fff1ede1ca307b7';
				$appSecret = 'd860bf42aaeeab8b';
				break;
			case 'tqq':
				$baseurl = 'https://open.t.qq.com/cgi-bin/access_token';
				$appKey = '2595e7182a3d445f93778287e9a869f2';
				$appSecret = '482918146ad547ef6c00d3235e3056f7';
				break;
		}
		
		$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), $provider);
		
		$extra = array(
			'oauth_token' => $_GET['oauth_token'],
			'oauth_token_secret' => $userOAuth->request_token_secret
		);
		if(isset($_GET['oauth_verifier'])){
			$extra['oauth_verifier'] = $_GET['oauth_verifier'];
		}
		$auth = build_auth_array($baseurl, $appKey, $appSecret, $extra, 'GET');
		
		if($provider=='tqq'){
			$str = '';
			foreach($auth AS $key=>$value)
				$str .= "&{$key}={$value}";//Do not include scope in the Authorization string.
			$str = substr($str, 1);//Remove leading ,
			//echo $str.'<br/><br/>';
			//echo "{$baseurl}?{$str}";
			
			$ch = curl_init("{$baseurl}?{$str}");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth realm=\"\", {$str}"));
			$response = curl_exec($ch);
			curl_close($ch);
		} else{
			$str = '';
			foreach($auth AS $key=>$value)
				if(strtolower($key) != 'oauth_token_secret')
					$str .= ", {$key}=\"{$value}\"";
			$str = substr($str, 2);//Remove leading ,
			//echo $str.'<br/><br/>';

			$ch = curl_init("{$baseurl}");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth realm=\"\", {$str}"));
			$response = curl_exec($ch);
			curl_close($ch);
		}
		
		$token = parse_token_to_array($response);
		switch($provider){
			case 'douban':
				$token['user_id'] = $token['douban_user_id'];
				break;
			case 'tqq':
				$token['user_id'] = 0;
				break;
		}
		
		$this->MUserOAuth->updateAccessToken($this->session->userdata('uid'), $provider, $token['oauth_token'], $token['oauth_token_secret'], $token['user_id']);
		
		redirect('setting/sync/');
	}
	
	function unlink(){
		$this->load->model('MUserOAuth');
		
		$uid = $this->session->userdata('uid');
		$provider = $_GET['provider'];
		$this->MUserOAuth->delete($uid, $provider);
		
		redirect('setting/sync/');
	}
}