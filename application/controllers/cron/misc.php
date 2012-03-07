<?php
class Misc extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		echo '';
	}

	function backupdb(){
		// Load the date helper and get current date
		$this->load->helper('date');
		$today = mdate("%Y%m%d", time());	

		// Load the DB utility class and Backup your entire database and assign it to a variable
		$this->load->dbutil();
		$backup = & $this->dbutil->backup(); 

		// Load the file helper and write the file to your server
		$this->load->helper('file');
		$path = 'backup/db/';
		$filename = 'baibai-'.$today.'.gzip';
		write_file($path.$filename, $backup);
	}
	
	function stockexpire(){
		$this->load->model('MStock');
		$this->load->model('MUser');
		$this->load->model('MUserLogin');
		$this->load->library('email');
		
		$currentTime = time();
		
		$usersLastLogin = $this->MUserLogin->getUsersLastLogin('live');
		// 统计
		$deletedStocksCount = 0;
		$deletedUsersCount = 0;
		$disabledStocksCount = 0;
		$disabledUsersCount = 0;
		for ($i=0; $i<count($usersLastLogin); $i++) {
			if ($usersLastLogin[$i]->maxTime + 90*24*60*60 < $currentTime && $usersLastLogin[$i]->maxTime + 91*24*60*60 > $currentTime) { 
				// 90天没登陆，删掉捐的书
				$userId = $usersLastLogin[$i]->userId;
				$user = $this->MUser->getByUid($userId);
				$userDonateStocks = $this->MStock->getByOwnerId($userId);
				$deletedUsersCount++;
				foreach ($userDonateStocks as $stock) {
					if ($stock->ownerId == $stock->readerId && $stock->time == $stock->transforTime) {
						$this->MStock->delete($stock->bookId, $userId);
						$deletedStocksCount++;
					}
				}
			} elseif ($usersLastLogin[$i]->maxTime + 80*24*60*60 < $currentTime && $usersLastLogin[$i]->maxTime + 81*24*60*60 > $currentTime) { 
				// 80天没登陆，暂时下
				$userId = $usersLastLogin[$i]->userId;
				$user = $this->MUser->getByUid($userId);
				if ($user->statue == 'away') {
					continue;
				}
				$this->MStock->updateReaderStatue($userId, 'away');
				$this->MUser->updateStatue($userId, 'away');
				
				$disabledUsersCount++;
				$disabledStocksCount += $this->MStock->getReadingCount($userId);
				// mail
				if($this->MStock->getAvailableCount($usersLastLogin[$i]->userId)>0){
					$url = site_url('mine/');
					$to = $user->email;
					$subject = "{$user->nickname}，您捐赠的书将在10天后下架";
					$message = "<p>由于您长时间没有登录摆摆书架，为了保证大家能及时借到想要的书，您捐赠的书籍将在10天后下架。</p>";
					$message .= "<p>您只要登录一次即可让书籍重新上架，点此登录：</p>";
					$message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
					$this->email->baibaiSend($to, $subject, $message);
				}
			} elseif ($usersLastLogin[$i]->maxTime + 60*24*60*60 < $currentTime && $usersLastLogin[$i]->maxTime + 61*24*60*60 > $currentTime) { 
				// 60天没登陆，提醒登陆
				$userId = $usersLastLogin[$i]->userId;
				$user = $this->MUser->getByUid($userId);
				$url = site_url();
				$to = $user->email;
				$subject = "{$user->nickname}，您很久没来摆摆书架看看啦";
				$message = "<p>{$user->nickname}，您已经 60 天没有访问过摆摆书架了。这段时间，上架了不少好书，快来看看吧：</p>";
				$message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
				$this->email->baibaiSend($to, $subject, $message);
			}
		}
		
		$to = 'lightory@gmail.com';
		$subject = mdate('%Y-%m-%d', $currentTime).' 过期书籍报告';
		$message = "<p>deletedUser:{$deletedUsersCount}<br/>";
		$message .= "deletedStock:{$deletedStocksCount}<br/>";
		$message .= "disabledUser:{$disabledUsersCount}<br/>";
		$message .= "disabledStock:{$disabledStocksCount}<br/>";
		$this->email->baibaiSend($to, $subject, $message);
	}
}