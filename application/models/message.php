<?php
// system/application/models/message.php
class Message extends CI_Model{
	function __construct(){
        parent::__construct();
    }
	
	function load(){
		$CI =& get_instance();
		$CI->load->model('MStock');
		$CI->load->model('MBorrowRecord');
		$CI->load->model('MUser');
		$CI->load->model('MBook');
		$CI->load->helper('gravatar');
		
		$uid = $CI->session->userdata('uid');
		$messages = array();
		
		//  等待 Senter 确认奖励
		$records = $CI->MBorrowRecord->getStatue4Record($uid);
		foreach($records as $record){
			$receiver = $CI->MUser->getByUid($record->receiverUid);
			$receiver->avatar = getGravatar($receiver->email, 35);
			$book = $CI->MBook->getById($record->bookId);
			$content = '<a href="' . $CI->MUser->getUrl($receiver->uid) . '">' . $receiver->nickname . '</a>';
			$content .= $receiver->isMobileValidate ? "<img src=\"".site_url('include/style/img/')."mobile.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机已验证\" />" : "<img src=\"".site_url('include/style/img/')."mobilenone.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机未验证\" />";
			$content .= '已经收到 <a href="' . site_url("book/subject/$book->id/") .'">《' . $book->name . '》</a>。';
			$messages[] = array(
				'avatar' => $receiver->avatar,
				'content' => $content,
				'time' => mdate('%Y-%m-%d %H:%i', $record->time4),
				'actionName' => '查看详情',
				'actionUrl' => site_url("book/getreward/$record->id/")
			);
		}
		
		// Messages For Senter
		$stocks = $CI->MStock->getTransferByUser($uid);
		foreach ($stocks as $stock){
			$record = $CI->MBorrowRecord->getUnfishedByStockId($stock->id);
			$receiver = $CI->MUser->getByUid($record->receiverUid);
			$receiver->avatar = getGravatar($receiver->email, 35);
			$book = $CI->MBook->getById($record->bookId);
			
			switch($record->statue){
				case '1':// 等待 Receiver 提供收货地址
					$content = '您准备借一本 <a href="' . site_url("book/subject/$book->id/") .'">《' . $book->name . '》</a> 给 <a href="' . $CI->MUser->getUrl($receiver->uid) . '">' . $receiver->nickname . '</a>';
					$content .= $receiver->isMobileValidate ? "<img src=\"".site_url('include/style/img/')."mobile.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机已验证\" />" : "<img src=\"".site_url('include/style/img/')."mobilenone.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机未验证\" />";
					$content .= '，请等待对方确认并提供收货地址。';
					$messages[] = array(
						'avatar' => $receiver->avatar,
						'content' => $content,
						'time' => mdate('%Y-%m-%d %H:%i', $record->time1)
					);
					break;
				case '2':// 收到 Receiver 的地址，并发货
					$deadline = mdate('%Y-%m-%d', $record->time2 + 7*24*60*60);
					$content = '收到 <a href="' . $CI->MUser->getUrl($receiver->uid) . '">' . $receiver->nickname . '</a>';
					$content .= $receiver->isMobileValidate ? "<img src=\"".site_url('include/style/img/')."mobile.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机已验证\" />" : "<img src=\"".site_url('include/style/img/')."mobilenone.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机未验证\" />";
					$content .= '的地址，请在 '. $deadline .' 前寄出 <a href="' . site_url("book/subject/$book->id/") .'">《' . $book->name . '》</a>';
					$messages[] = array(
						'avatar' => $receiver->avatar,
						'content' => $content,
						'time' => mdate('%Y-%m-%d %H:%i', $record->time2),
						'actionName' => '查看详情',
						'actionUrl' => site_url("book/getaddress/$record->id/")
					);
					break;
				case '3':// 等待 Receiver 确认收货
				  if ($record->expressType=='邮政'){
						$deadline = mdate('%Y-%m-%d', $record->time3 + 30*24*60*60);
					} else{
						$deadline = mdate('%Y-%m-%d', $record->time3 + 14*24*60*60);
					}
					$content = '等待 <a href="' . $CI->MUser->getUrl($receiver->uid) . '">' . $receiver->nickname . '</a>';
					$content .= $receiver->isMobileValidate ? "<img src=\"".site_url('include/style/img/')."mobile.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机已验证\" />" : "<img src=\"".site_url('include/style/img/')."mobilenone.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机未验证\" />";
					$content .= '确认收到<a href="' . site_url("book/subject/$book->id/") .'">《' . $book->name . '》</a>，您会在 '.$deadline.' 前得到对方的收书回应。';
					$messages[] = array(
						'avatar' => $receiver->avatar,
						'content' => $content,
						'time' => mdate('%Y-%m-%d %H:%i', $record->time3)
					);
					break;
			}
		}
		
		// Messages For Receiver
		$borrowRecords = $CI->MBorrowRecord->getByReceiver($uid);
		foreach ($borrowRecords as $record){
			$senter = $CI->MUser->getByUid($record->senterUid);
			$senter->avatar = getGravatar($senter->email, 35);
			$book = $CI->MBook->getById($record->bookId);
			
			switch($record->statue){
				case '1':// Receiver 提供收货地址
					$deadline = mdate('%Y-%m-%d', $record->time1 + 3*24*60*60);
					$content = '<a href="' . $CI->MUser->getUrl($senter->uid) . '">' . $senter->nickname . '</a>';
					$content .= $senter->isMobileValidate ? "<img src=\"".site_url('include/style/img/')."mobile.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机已验证\" />" : "<img src=\"".site_url('include/style/img/')."mobilenone.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机未验证\" />";
					$content .= '愿意借一本 <a href="' . site_url("book/subject/$book->id/") .'">《' . $book->name . '》</a> 给您，请在 ' .$deadline. ' 前提供地址。';
					$messages[] = array(
						'avatar' => $senter->avatar,
						'content' => $content,
						'time' => mdate('%Y-%m-%d %H:%i', $record->time1),
						'actionName' => '查看详情',
						'actionUrl' => site_url("book/offeraddress/$record->id/")
					);
					break;
				case '2':// 等待 Senter 发货
					$deadline = mdate('%Y-%m-%d', $record->time2 + 7*24*60*60);
					$content = '<a href="' . $CI->MUser->getUrl($senter->uid) . '">' . $senter->nickname . '</a>';
					$content .= $senter->isMobileValidate ? "<img src=\"".site_url('include/style/img/')."mobile.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机已验证\" />" : "<img src=\"".site_url('include/style/img/')."mobilenone.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机未验证\" />";
					$content .= ' 已经收到您的地址，将在 ' .$deadline. ' 前寄给您 <a href="' . site_url("book/subject/$book->id/") .'">《' . $book->name . '》</a> 。';
					$messages[] = array(
						'avatar' => $senter->avatar,
						'content' => $content,
						'time' => mdate('%Y-%m-%d %H:%i', $record->time2)
					);
					break;
				case '3':// Receiver 确认收货
					if ($record->expressType=='邮政'){
						$deadline = mdate('%Y-%m-%d', $record->time3 + 30*24*60*60);
					} else{
						$deadline = mdate('%Y-%m-%d', $record->time3 + 14*24*60*60);
					}
					$content = '<a href="' . $CI->MUser->getUrl($senter->uid) . '">' . $senter->nickname . '</a>';
					$content .= $senter->isMobileValidate ? "<img src=\"".site_url('include/style/img/')."mobile.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机已验证\" />" : "<img src=\"".site_url('include/style/img/')."mobilenone.png\" style=\"position:relative; top:2px; margin:0 2px;\" title=\"手机未验证\" />";
					$content .= '借给您的 <a href="' . site_url("book/subject/$book->id/") .'">《' . $book->name . '》</a> 已于 ' .  $record->sentTime  . ' 发出。<br/>请在 '. $deadline . ' 前进行收书确认。';
					$messages[] = array(
						'avatar' => $senter->avatar,
						'content' => $content,
						'time' => mdate('%Y-%m-%d %H:%i', $record->time3),
						'actionName' => '查看详情',
						'actionUrl' => site_url("book/receivebook/$record->id/")
					);
					break;
			}
		}
		
		return $this->_sort($messages);
	}
	
	function _sort($array){
		$count = count($array);
		if ($count <= 0) {
			return array();
		}
		for($i=0; $i<$count; $i++){
		   for($j=$count-1; $j>$i; $j--){
				//如果后一个元素小于前一个，则调换位置
				if ($array[$j]['time'] > $array[$j-1]['time']){
					$tmp = $array[$j];
					$array[$j] = $array[$j-1];
					$array[$j-1] = $tmp;
				}
			}
		}
		return $array;
	}
}