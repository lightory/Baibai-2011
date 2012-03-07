<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms {
	private $username = 'lightory';
	private $password = 'jiubugaosunimima';
	private $comId = '260';
	private $smsNumber = '1061';
	
	public function send($mobile, $msg) {
		if (ENVIRONMENT == 'Development') {
			$CI = & get_instance();
			$CI->load->library('email');
			$CI->email->baibaiSend('lightory@gmail.com', $mobile, $msg);
			return;
		}
		
		$msg = mb_convert_encoding($msg, 'gb2312', 'utf-8');
		$msg = urlencode($msg);
		$sendUrl =  "http://jiekou.ruan56.com/sms/HttpInterface.aspx?comid={$this->comId}&username={$this->username}&userpwd={$this->password}&handtel={$mobile}&sendcontent={$msg}&smsnumber={$this->smsNumber}";
		$respondString = file_get_contents($sendUrl);
		
		$s = intval(substr($respondString, 0, 1));
		if ($s == 1) {
			$error = '发送成功';
		} else {
			$b = intval(substr($respondString, 0, 2));
			switch($b){
				case -1:$error = '手机号码不正确';break;
				case -2:$error = '除时间外，所有参数不能为空';break;
				case -3:$error = '用户名密码不正确';break;
				case -4:$error = '平台不存在';break;
				case -5:$error = '客户短信数量为0';break;
				case -6:$error = '客户账户余额小于要发送的条数';break;
				case -7:$error = '不能超过70个字';break;
				case -8:$error = '非法短信内容';break;
				case -9:$error = '未知系统故障';break;
				case -10:$error = '网络性错误';break;
				default:$error = false;
			} 
		}
		return $error;
	}
}