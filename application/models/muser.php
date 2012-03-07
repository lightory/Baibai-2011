<?php
// system/application/models/muser.php
class MUser extends CI_Model{
	var $email = '';
	var $mobile = '';
	var $username = '';
	var $nickname = '';
	var $password = '';
	var $blog = '';
	var $bio = '';
	var $borrowQuote = '';
	var $inviteQuote = '';
	var $isMobileValidate = '';
	var $joinTime = '';
	var $introducerUid = '';
	var $statue = '';
	var $kill = '';
	
	function __construct(){
        parent::__construct();
    }
  
	function delete($uid){    
		$this->db->where('uid', $uid);
		$this->db->delete('user'); 
	}
  
	// 重发邀请，更新邀请时间
	function updateInviteTime($uid){
		$data = array(
			'inviteTime'     => time()
		);
    
		$this->update($uid, $data);
	}
	
	// 注册
	function register($email, $mobile, $nickname, $password, $introducerUid, $borrowQuote){
		$this->email = $email;
		$this->mobile = $mobile;
		$this->nickname = $nickname;
		$this->password = $password;
		$this->borrowQuote = $borrowQuote;
		$this->inviteQuote = 5;
		$this->introducerUid = $introducerUid;
		$this->isMobileValidate = 0;
		$this->joinTime = time();
		$this->statue = 'live';
		$this->kill = 0;
		
		$this->db->insert('user',$this);
		
		$uid = $this->db->insert_id();
		$this->updateUsername($uid, $uid);
		return $uid;
	}
	
	// 获取我邀请的好友
	function getByIntroducer($introducerUid){
		$query = $this->db->get_where('user', array('introducerUid' => $introducerUid, ));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getByUid($uid){
		$query = $this->db->get_where('user', array('uid' => $uid), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function getByUsername($username){
		$query = $this->db->get_where('user', array('username' => $username), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function getByEmail($email){
		$query = $this->db->get_where('user', array('email' => $email), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function getByMobile($mobile) {
		$query = $this->db->get_where('user', array('mobile' => $mobile), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function isMobileBind($mobile) {
		$user = $this->getByMobile($mobile);
		if ($user && $user->isMobileValidate) {
			return true;
		} else {
			return false;
		}
	}
	
	function mobilePassword($mobile) {
		$key = 'lig.ht' . date('Ymd');
		return substr(str_pad(hexdec(substr(md5($mobile . $key), -7)), 6, '0', STR_PAD_LEFT), -6);
	}
	
	function bindMobile($userId, $mobile) {
		if ($this->isMobileBind($mobile)) {
			return false;
		}
		
		$data = array(
			'mobile' => $mobile,
			'isMobileValidate' => 1
		);
		
		$this->update($userId, $data);
		return true;
	}
	
	function unbindMobile($userId) {
		$data = array(
			'isMobileValidate' => 0
		);
		
		$this->update($userId, $data);
		return true;
	}
	
	function isInvited($email){
		$query = $this->db->get_where('user', array('email' => $email), 1, 0);
		if ($query->row()){
			return true;
		}
		
		$query = $this->db->get_where('tempuser', array('email' => $email), 1, 0);
		if ($query->row()){
			return true;
		}
		
		return false;
	}
	
	// 登录验证
	function loginValid($email,$password){
		$query = $this->db->get_where('user', array('email' => $email, 'password' => $password, 'kill' => 0), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	
	function update($uid, $data){
		/*
		$data = array(
            'name'       => $argName,
			'bio'        => $argBio
        );
		*/
		$this->db->where('uid', $uid);
		$this->db->update('user', $data); 
	}
	
	function updateUsername($uid, $username){
		if($this->getByUsername($username)){
			return false;
		}
		
		$data = array(
			'username' => $username
		);
		$this->update($uid, $data);
		return true;
	}
	
	// 更新密码
	function updatePassword($uid, $password){
		$data = array(
			'password' => md5($password)
		);
		
		$this->update($uid, $data);
	}
	
	// 通过Email验证，更新密码
	function updatePasswordByEmail($email, $password){
		$user = $this->getByEmail($email);
		$this->updatePassword($user->uid, $password);
	}
	
	// 更新资料
	function updateProfile($uid, $nickname, $blog, $bio){
		$data = array(
			'nickname' => $nickname,
			'blog' => $blog,
			'bio' => $bio
		);
		
		$this->update($uid, $data);
	}
	
	function updateMobile($uid, $mobile) {
		$data = array(
			'mobile' => $mobile,
		);
		
		$this->update($uid, $data);
	}
	
	// 修改密码
	function changePassword($uid, $oldPassword, $newPassword){
		$user = $this->getByUid($uid);
		if (md5($oldPassword) != $user->password){
			return false;
		}
		
		$this->updatePassword($uid, $newPassword);
		return true;
	}
	
	// -1邀请限额
	function minusInviteQuote($uid){
		$user = $this->getByUid($uid);
		$data = array(
    	'inviteQuote' => $user->inviteQuote-1
    );
		
		$this->update($uid, $data);
	}
	
	function addInviteQuote($uid, $addNumber=1){
		$user = $this->getByUid($uid);
		$data = array(
    	'inviteQuote' => $user->inviteQuote + $addNumber
    );
		
		$this->update($uid, $data);
	}
	
	// -1借书限额
	function minusBorrowQuote($uid){
		$user = $this->getByUid($uid);
		$data = array(
			'borrowQuote' => $user->borrowQuote-1
		);
		
		$this->update($uid, $data);
	}
	
	// +1借书限额
	function addBorrowQuote($uid){
		$user = $this->getByUid($uid);
		$data = array(
			'borrowQuote' => $user->borrowQuote+1
		);
		
		$this->update($uid, $data);
	}
	
	//增加一定数量的借书限额
	function addSomeBorrowQuote($uid,$count){
		$user = $this->getByUid($uid);
		$data = array(
			'borrowQuote' => $user->borrowQuote+$count
		);
		
		$this->update($uid, $data);
	}
	
	// 获取指定Uid的主页地址
	function getUrl($userId){
		if($userId==0){
			return site_url();
		}
		$user = $this->getByUid($userId);
		return site_url("user/$user->username/");
	}
	
	// 获取捐赠排行榜
	function getDonateList($limit=5, $offset=0){
		$sql[] = 'SELECT bf_user.*,count(bf_stock.id) count FROM bf_user,bf_stock';
		$sql[] = 'WHERE bf_stock.ownerId != bf_stock.readerId';
		$sql[] = 'AND bf_user.uid = bf_stock.ownerId';
		$sql[] = 'GROUP BY bf_stock.ownerId';
		$sql[] = 'ORDER BY count DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 获取某城市的捐赠排行榜
	function getDonateListByCity($cityName, $limit=5, $offset=0){
		$sql[] = 'SELECT bf_user.*,count(bf_stock.id) count FROM bf_user, bf_address, bf_stock';
		$sql[] = 'WHERE bf_stock.ownerId != bf_stock.readerId';
		$sql[] = 'AND bf_user.uid = bf_stock.ownerId';
		$sql[] = "AND bf_user.uid = bf_address.uid";
		$sql[] = "AND bf_address.city = '$cityName'";
		$sql[] = 'GROUP BY bf_stock.ownerId';
		$sql[] = 'ORDER BY count DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// ADMIN, 获取所有用户列表
	function getAll($statue='register', $limit=50, $offset=0){
		$sql[] = 'SELECT * FROM bf_user';
		$sql[] = 'ORDER BY joinTime DESC';
		if ($limit != 'all'){
			$sql[] = "LIMIT $offset,$limit";
		}
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getCount() {
		return $this->db->count_all('user');
	}
		
	
	//存储中的摆摆卷
	function  getBaiBaiSaveCount(){			
		$sql[] = 'SELECT sum(borrowQuote) borrowQuote FROM bf_user';
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($result = $query->row()){
			return $result->borrowQuote;
		} else {
			return 0;
		}
	}
	
	function getMaxUserId() {
		$this->db->order_by('uid', 'desc');
		$query = $this->db->get('user', array('id' => $id), 1, 0);
		if ($user = $query->row()) {
			return $user->uid;
		} else {
			return 0;
		}
	}
	
	function getToalInviteQuote(){
		$sql[] = 'SELECT sum(inviteQuote) inviteQuoteCount FROM bf_user';
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($result = $query->row()){
			return $result->inviteQuoteCount;
		} else {
			return 0;
		}
	}
  
	// 用户有效捐赠数
	function getUserDonateCount($userId){
		$sql[] = 'SELECT * FROM bf_stock';
		$sql[] = "WHERE bf_stock.ownerId = $userId";
		$sql[] = 'AND bf_stock.ownerId != readerId';
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 用户捐书传递数
	function getUserDonateTransCount($userId){
		$sql[] = 'SELECT bf_borrowrecord.* FROM bf_stock,bf_borrowrecord';
		$sql[] = "WHERE bf_stock.ownerId = $userId";
		$sql[] = 'AND bf_stock.id = bf_borrowrecord.stockId';
		$sql[] = 'AND bf_borrowrecord.statue >= 4';
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 用户借书数
	function getUserBorrowCount($userId){
		$sql[] = 'SELECT bf_borrowrecord.* FROM bf_borrowrecord';
		$sql[] = "WHERE bf_borrowrecord.receiverUid = $userId";
		$sql[] = 'AND bf_borrowrecord.statue >= 4';
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 用户借书并归还数
	function getUserBorrowedCount($userId){
		$sql[] = 'SELECT bf_borrowrecord.* FROM bf_stock,bf_borrowrecord';
		$sql[] = "WHERE bf_borrowrecord.senterUid = $userId";
		$sql[] = 'AND bf_borrowrecord.statue >= 4';
		$sql[] = "AND bf_borrowrecord.stockId = bf_stock.id";
		$sql[] = "AND bf_stock.ownerId != $userId";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	// 用户加入的小组数量
	function getUserJoinedGroupCount($userId){
		$sql[] = 'SELECT * FROM bf_groupmember';
		$sql[] = "WHERE bf_groupmember.userId = $userId";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 用户发表的主题数量
	function getUserTopicCount($userId){
		$sql[] = 'SELECT * FROM bf_grouptopic';
		$sql[] = "WHERE bf_grouptopic.userId = $userId";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 用户参与的主题数量
	function getUserJoinedTopicCount($userId){
		$sql[] = 'SELECT DISTINCT topicId FROM bf_grouppost';
		$sql[] = "WHERE bf_grouppost.userId = $userId";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 一段时间内的借书排行榜
	function getSentBookList($left, $right){
		$sql[] = 'SELECT bf_user.uid, bf_user.email, bf_user.nickname, bf_user.inviteQuote, COUNT( bf_user.uid ) count';
		$sql[] = 'FROM bf_user, bf_borrowrecord';
		$sql[] = 'WHERE bf_borrowrecord.statue >= 4';
		$sql[] = 'AND bf_borrowrecord.senterUid = bf_user.uid';
		$sql[] = "AND bf_borrowrecord.time4 >= $left";
		$sql[] = "AND bf_borrowrecord.time4 < $right";
		$sql[] = "GROUP BY bf_user.uid";
		$sql[] = "ORDER BY count DESC";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function updateStatue($userId, $userStatue){
		$data = array(
			'statue' => $userStatue
		);
    
		$this->db->where('uid', $userId);
		$this->db->update('user', $data);
	}
}