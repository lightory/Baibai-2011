<?php
// system/application/models/common.php
class Common extends CI_Model{
	function __construct(){
        parent::__construct();
    }
	
	function footer(){
		$CI =& get_instance();
		$CI->load->model('MStock');
		$CI->load->model('MAddress');
		$CI->load->model('MBorrowRecord');
    
		$data['stockNumber'] = $CI->MStock->getNumber();
		$data['cityNumber'] = $CI->MAddress->getCitiesCount();
		$data['savedMoney'] = $CI->MBorrowRecord->getSavedMoney();
	
		$CI->load->view('footer', $data);
	}
	
	function sidebar($uid = 0, $isSelf = true){
		$CI =& get_instance();
		$CI->load->model('MUser');
		$CI->load->model('MFollow');
		$CI->load->model('MAddress');
		$CI->load->model('MUserOAuth');
		
		if ( $uid == 0 ){
			$uid = $CI->session->userdata('uid');
		}
		
		$user = $CI->MUser->getByUid($uid);
		$address = $CI->MAddress->getDefaultByUid($uid);
		
		// get gravatar
		$CI->load->helper('gravatar');
		$user->avatar = getGravatar($user->email, 45);
    
		// is invite Module Display
		$myInviteUsers = $CI->MUser->getByIntroducer( $this->session->userdata('uid') );
		if ( ($isSelf)&&( ($user->inviteQuote>0) || (sizeof($myInviteUsers)>0) ) ){
			$data['isInviteModuleDisplay'] = true;
		} else{
			$data['isInviteModuleDisplay'] = false;
		}
	
		$data['user'] = $user;
		$data['address'] = $address;
		$data['isSelf'] = $isSelf;
    
		// Load Numbers
		$data['donateCount'] = $CI->MUser->getUserDonateCount($uid);
		$data['donateTransCount'] = $CI->MUser->getUserDonateTransCount($uid);
		$data['borrowCount'] = $CI->MUser->getUserBorrowCount($uid);
		$data['borrowedCount'] = $CI->MUser->getUserBorrowedCount($uid);
		
		// Load UserOAuth
		if ($isSelf){
			$data['myLinkedProviders'] = $CI->MUserOAuth->getUserLinkedProviders($uid);
		}
		
		// Load Follow
		$data['followings'] = $this->MFollow->getUserFollowings($uid);
		$data['followingsCount'] = $this->MFollow->getUserFollowingsCount($uid);
		$data['followersCount'] = $this->MFollow->getUserFollowersCount($uid);
		if(!$data['isSelf']){
			$data['isFollow'] = $this->MFollow->isExist($this->session->userdata('uid'), $uid);
		}
		
		// Load Last Login Time
		if($this->session->userdata('uid') == '1'){
			$this->load->model('MUserLogin');
			$data['lastLogin'] = $this->MUserLogin->getUserLastLogin($uid);
		}
		
		$CI->load->view('sidebar', $data);
	}

	function groupSidebar(){
		$CI =& get_instance();
		$CI->load->model('MUser');
		$CI->load->model('MGroup');
		$CI->load->helper('gravatar');
    
		// Load User's Group
		$joinedGroups = $CI->MGroup->getUserJoinedGroups($this->session->userdata('uid'));
    
		$data['joinedGroups'] = $joinedGroups;
    
		$CI->load->view('group/sidebar', $data);
	}
}