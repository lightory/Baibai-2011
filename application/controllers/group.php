<?php

class Group extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->userdata('uid')==1){
			$this->output->enable_profiler(TRUE);
		}
	}
  
	function index(){
		$this->latest();
	} 
  
  function latest($offset=0){
    $this->Validate->isLogin();
    
    $this->load->model('MGroup');
    $this->load->model('MGroupTopic');
	$this->load->model('MGroupMember');
    $this->load->helper('string');

	// Auto Join Baibai Group
	if (!$this->MGroup->getUserType($this->session->userdata('uid'), 1)) {
		$this->MGroupMember->addMember($this->session->userdata('uid'), 1);
	}
    
    // Load Latest Topics
    $topics = $this->MGroupTopic->getUserFollowGroupTopics($this->session->userdata('uid'), 15, $offset);
    
    // Load All Groups
    $groups = $this->MGroup->getGroups();
    
    $headerData['title'] = '小组';
    $headerData['current'] = 'group';
		$headerData['styles'] = array('group.css');
    $data['topics'] = $topics;
    $data['groups'] = $groups;
    $data['type'] = 'latest';
    
    // Setup Pagniation
    $this->load->library('pagination');
    $config['full_tag_open'] = '<p class="pages">';
    $config['full_tag_close'] = '</p>';
    $config['uri_segment'] = 3;
    $config['per_page'] = '15'; 
    $config['total_rows'] = $this->MGroupTopic->getUserFollowGroupTopicsCount($this->session->userdata('uid'));
    $config['num_links'] = 5;
    $config['base_url'] = site_url("group/latest");
    $this->pagination->initialize($config); 
    
    $this->load->view('header', $headerData);
    $this->load->view('group/topiclist', $data);
    $this->Common->groupSidebar();
    $this->Common->footer();
  }
  
  function mytopics($offset=0){
    $this->Validate->isLogin();
    
    $this->load->model('MGroupTopic');
    $this->load->helper('string');
    
    // Load Latest Topics
    $topics = $this->MGroupTopic->getUserTopics($this->session->userdata('uid'), 15, $offset);
    
    $headerData['title'] = '我发表的话题 - 小组';
    $headerData['current'] = 'group';
		$headerData['styles'] = array('group.css');
    $data['topics'] = $topics;
    $data['type'] = 'mytopics';
    
    // Setup Pagniation
    $this->load->library('pagination');
    $config['full_tag_open'] = '<p class="pages">';
    $config['full_tag_close'] = '</p>';
    $config['uri_segment'] = 3;
    $config['per_page'] = '15'; 
    $config['total_rows'] = $this->MGroupTopic->getUserTopicsCount($this->session->userdata('uid'));
    $config['num_links'] = 5;
    $config['base_url'] = site_url("group/mytopics");
    $this->pagination->initialize($config); 
    
    $this->load->view('header', $headerData);
    $this->load->view('group/topiclist', $data);
    $this->Common->groupSidebar();
    $this->Common->footer();
  }
  
  function myreplies($offset=0){
    $this->Validate->isLogin();
    
    $this->load->model('MGroupTopic');
    $this->load->helper('string');
    
    // Load Latest Topics
    $topics = $this->MGroupTopic->getUserReplyTopics($this->session->userdata('uid'), 15, $offset);
    
    $headerData['title'] = '我回应的话题 - 小组';
    $headerData['current'] = 'group';
		$headerData['styles'] = array('group.css');
    $data['topics'] = $topics;
    $data['type'] = 'myreplies';
    
    // Setup Pagniation
    $this->load->library('pagination');
    $config['full_tag_open'] = '<p class="pages">';
    $config['full_tag_close'] = '</p>';
    $config['uri_segment'] = 3;
    $config['per_page'] = '15'; 
    $config['total_rows'] = $this->MGroupTopic->getUserReplyTopicsCount($this->session->userdata('uid'));
    $config['num_links'] = 5;
    $config['base_url'] = site_url("group/myreplies");
    $this->pagination->initialize($config); 
    
    $this->load->view('header', $headerData);
    $this->load->view('group/topiclist', $data);
    $this->Common->groupSidebar();
    $this->Common->footer();
  }
  
  function grp($groupUrl, $offset=0){
    $this->Validate->isLogin();
    
    $this->load->model('MGroup');
    $this->load->model('MGroupTopic');
    $this->load->helper('gravatar');
    $this->load->helper('string');
    
    // Load Current Group
    $group = $this->MGroup->getByUrl($groupUrl);
    if(!$group){
      $group = $this->MGroup->getById($groupUrl);
      //redirect("group/$group->url/");
    }
		// 如果小组不存在，404
		if (!$group){
			show_404();
		}
    $group->memberCount = $this->MGroup->getMembersCount($group->id);
    $group->userType = $this->MGroup->getUserType($this->session->userdata('uid'), $group->id);
    
    // Load Latest Topics
    $topics = $this->MGroup->getLatestTopics($group->id, 15, $offset);
    
    $headerData['title'] = $group->name.' - 小组';
    $headerData['current'] = 'group';
		$headerData['styles'] = array('group.css');
    $data['group'] = $group;
    $data['topics'] = $topics;
    
    // Setup Pagniation
    $this->load->library('pagination');
    $config['full_tag_open'] = '<p class="pages">';
    $config['full_tag_close'] = '</p>';
    $config['uri_segment'] = 3;
    $config['per_page'] = '15'; 
    $config['total_rows'] = $this->MGroup->getTopicsCount($group->id);
    $config['num_links'] = 5;
    $config['base_url'] = site_url("group/$groupUrl");
    $this->pagination->initialize($config); 
    
    $this->load->view('header', $headerData);
    $this->load->view('group/group', $data);
    $this->Common->groupSidebar();
    $this->Common->footer();
  }
  
	function topic($topicId){	
    	$this->Validate->isLogin();

		if (!is_numeric($topicId)) {
			show_404();
		}

		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->model('MGroup');
		$this->load->model('MGroupTopic');
		$this->load->helper('gravatar');
    
		// Load Current User
		$user = $this->MUser->getByUid($this->session->userdata('uid'));
		$user->avatar = getGravatar( $user->email, 45);
		$user->address = $this->MAddress->getDefaultByUid($user->uid);
    
		// Load Current Topic
		$topic = $this->MGroupTopic->getById($topicId);
			
		// 如果小组话题不存在，404
		if (!$topic){
			show_404();
		}
	    
		$topic->author = $this->MUser->getByUid($topic->userId);
		$topic->author->avatar = getGravatar( $topic->author->email, 42);
		$topic->author->address = $this->MAddress->getDefaultByUid($topic->author->uid);
		$topic->replys = $this->MGroupTopic->getTopicReplys($topicId);
    
		// Load Current Group
		$group = $this->MGroup->getById($topic->groupId);
		$group->userType = $this->MGroup->getUserType($this->session->userdata('uid'), $topic->groupId);
    
		$headerData['title'] = $topic->title . ' - ' . $group->name;
		$headerData['current'] = 'group';
		$headerData['styles'] = array('group.css');
		$data['user'] = $user;
		$data['topic'] = $topic;
		$data['group'] = $group;
    
		$this->load->view('header', $headerData);
		$this->load->view('group/topic', $data);
		$this->Common->groupSidebar();
		$this->Common->footer();
	}
  
	function newtopic($groupId){
		$this->Validate->isLogin();
    
		$this->load->model('MGroup');
    
		// Load Current Group
		$group = $this->MGroup->getById($groupId);
    
		$headerData['title'] = '新主题 - '.$group->name;
		$headerData['current'] = 'group';
		$headerData['styles'] = array('group.css');
		$data['group'] = $group;
    
		$this->load->view('header', $headerData);
		$this->load->view('group/newtopic', $data);
		$this->Common->groupSidebar();
		$this->Common->footer();
	}
  
  function newtopic_do(){
    $this->Validate->isLogin();
    
    $this->load->model('MGroup');
    $this->load->model('MGroupTopic');
    
    // Load Post Data
    $groupId = $this->input->post('groupId');
    $userId = $this->session->userdata('uid');
    $title = $this->input->post('postTitle');
    $content = nl2br($this->input->post('postContent'));
    
    if(!$title || !$content){
      redirect("group/$groupId/newtopic/");
    }
    
    // If not group member, error
    if ($groupId != 1 && $this->MGroup->getUserType($userId, $groupId) == ''){
      redirect("group/$groupId");
    }
    
    // Add Topic
    $topicId = $this->MGroupTopic->addTopic($groupId, $userId, $title, $content);
    
    // Redirect
    redirect("group/topic/$topicId/");
  }
  
	function reply_do(){
		$this->Validate->isLogin();
    
		$this->load->model('MGroup');
		$this->load->model('MGroupTopic');
		$this->load->model('MGroupPost');
    
		// Load Post Data
		$topicId = $this->input->post('topicId');
		$userId = $this->session->userdata('uid');
		$content = nl2br($this->input->post('postContent'));
    
		// If not group member, error
		$topic = $this->MGroupTopic->getById($topicId);
		$group = $this->MGroup->getById($topic->groupId);
		if($group->id!=1 && $group->type != 'location'){
			if ($this->MGroup->getUserType($userId, $topic->groupId) == ''){
				redirect("group/$topic->groupId/");
			}
		}
    
		// Add Post
		$this->MGroupPost->addPost($topicId, $userId, $content);
		$this->MGroupTopic->updateAcitveTime($topicId);
    
		redirect("group/topic/$topicId/");
	}
  
  function members($groupUrl){
    $this->Validate->isLogin();
    
    $this->load->model('MGroup');
    $this->load->model('MGroupMember');
    $this->load->model('MUser');
    $this->load->helper('gravatar');
    $this->load->helper('string');
    
    // Load Current Group
    $group = $this->MGroup->getByUrl($groupUrl);
    if(!$group){
      $group = $this->MGroup->getById($groupUrl);
    }
    $group->memberCount = $this->MGroup->getMembersCount($group->id);
    $group->userType = $this->MGroup->getUserType($this->session->userdata('uid'), $group->id);
    
    // Load Group Members
    $owner = $this->MGroupMember->getGroupOwner($group->id);
    $members = $this->MGroupMember->getGroupMembers($group->id);
    
    $headerData['title'] = $group->name.' - 小组';
    $headerData['current'] = 'group';
		$headerData['styles'] = array('group.css');
    $data['group'] = $group;
    $data['owner'] = $owner;
    $data['members'] = $members;
    
    // Setup Pagniation
    $this->load->library('pagination');
    $config['full_tag_open'] = '<p class="pages">';
    $config['full_tag_close'] = '</p>';
    $config['uri_segment'] = 3;
    $config['per_page'] = '15'; 
    $config['total_rows'] = $this->MGroup->getTopicsCount($group->id);
    $config['num_links'] = 5;
    $config['base_url'] = site_url("group/$groupUrl");
    $this->pagination->initialize($config); 
    
    $this->load->view('header', $headerData);
    $this->load->view('group/members', $data);
    $this->Common->groupSidebar();
    $this->Common->footer();
  }
  
  function join_do($groupId){
    $this->Validate->isLogin();
    
    $this->load->model('MGroup');
    $this->load->model('MGroupMember');
    
    $userId = $this->session->userdata('uid');
    
    // Add Membership 
    if ($this->MGroup->getUserType($userId, $groupId) == ''){
      $this->MGroupMember->addMember($userId, $groupId);
    }
    
    // Redirect
    redirect("group/$groupId/");
  }
  
  function quit_do($groupId){
    $this->Validate->isLogin();
    
    $this->load->model('MGroup');
    $this->load->model('MGroupMember');
    
    $userId = $this->session->userdata('uid');
    
    // Add Membership 
    if ($this->MGroup->getUserType($userId, $groupId) == 'member'){
      $this->MGroupMember->removeMember($userId, $groupId);
    }
    
    // Redirect
    redirect("group/$groupId/");
  }
	
	function groupedit($groupUrl){
		$this->Validate->isLogin();
		
		$this->load->model('MGroup');
		$this->load->model('MGroupMember');
		
		$group = $this->MGroup->getByUrl($groupUrl);
		$userId = $this->session->userdata('uid');
		
		if (!$this->MGroupMember->isAdmin($group->id, $userId)){
			$this->session->set_flashdata('error','您没有权限执行此项操作');
		  redirect("group/$groupUrl/");
		}
		
		$headerData['title'] = '修改小组设置';
    $headerData['current'] = 'group';
		$data['group'] = $group;
		
		$this->load->view('header', $headerData);
    $this->load->view('group/edit', $data);
    $this->Common->groupSidebar();
    $this->Common->footer();
	}
	
	function groupedit_do($groupUrl){
		$this->Validate->isLogin();
		
		$this->load->model('MGroup');
		$this->load->model('MGroupMember');
		
		$group = $this->MGroup->getByUrl($groupUrl);
		$userId = $this->session->userdata('uid');
		
		if (!$this->MGroupMember->isAdmin($group->id, $userId)){
			$this->session->set_flashdata('error','您没有权限执行此项操作');
		  redirect("group/$groupUrl/");
		}
		
		$groupName = $this->input->post('groupName');
		$groupDesc = $this->input->post('groupDesc');
		$this->MGroup->updateBasicInfo($group->id, $groupName, $groupDesc);
		$this->session->set_flashdata('error','修改完成');
	  redirect("group/$groupUrl/");
	}
	
	function editicon($groupUrl){
		$this->Validate->isLogin();
		
		$this->load->model('MGroup');
		$this->load->model('MGroupMember');
		
		$group = $this->MGroup->getByUrl($groupUrl);
		$userId = $this->session->userdata('uid');
		
		if (!$this->MGroupMember->isAdmin($group->id, $userId)){
			$this->session->set_flashdata('error','您没有权限执行此项操作');
		  redirect("group/$groupUrl/");
		}
		
		$headerData['title'] = '修改小组设置';
    $headerData['current'] = 'group';
		$headerData['styles'] = array('group.css');
		$data['group'] = $group;
		
		$this->load->view('header', $headerData);
    $this->load->view('group/editicon', $data);
    $this->Common->groupSidebar();
    $this->Common->footer();
	}
	
	function editicon_do($groupUrl){
		$this->Validate->isLogin();
		
		$this->load->model('MGroup');
		$this->load->model('MGroupMember');
		
		$group = $this->MGroup->getByUrl($groupUrl);
		$userId = $this->session->userdata('uid');
		
		if (!$this->MGroupMember->isAdmin($group->id, $userId)){
			$this->session->set_flashdata('error','您没有权限执行此项操作');
		  redirect("group/$groupUrl/");
		}
		
		if ($group->picFid && $group->picId){
			$picFid = $group->picFid;
		} else{
			$picFid = mdate('%Y%m', time());
		}
		$config['upload_path'] = "upload/group/icon/$picFid/";
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
	  $config['max_size'] = '500';
	  $config['max_width']  = '1024';
	  $config['max_height']  = '768';
	  $this->load->library('upload', $config);
	
		if(!file_exists($config['upload_path'])){
	    mkdir($config['upload_path']);
	  }

	  if ( ! $this->upload->do_upload('groupIcon')){
	  	$this->session->set_flashdata('error', $this->upload->display_errors());
		  redirect("group/$groupUrl/editicon/");
	  } else {
	 		$uploadData = $this->upload->data();
			
			$this->load->library('image_lib');
	  	$config['image_library'] = 'GD2';
	    $config['source_image'] = $uploadData['full_path'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 48;
			$config['height'] = 48;
	    $this->image_lib->initialize($config); 
	    $this->image_lib->resize();
	
			// 删除原图标
			$oldIcon = "upload/group/icon/$group->picFid/$group->picId";
			if(file_exists($oldIcon)){
			   unlink($oldIcon);
			}
			
			// 更新数据库
			$this->MGroup->updateIcon($group->id, $picFid, $uploadData['file_name']);
			
			// Redirect
			$this->session->set_flashdata('error','修改完成');
		  redirect("group/$groupUrl/editicon/");
	  }
	}
}