<?php
class BorrowExpire extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
  
  function index(){
    echo '';
  }
  
  // 3天未提供地址，自动失效
  function offeraddress(){
    $this->load->model('MBorrowRecord');
    $this->load->model('MStock');
    $this->load->model('MUser');
    $this->load->model('MBook');
    $this->load->model('MNotification');
    
    // Load Statue 1 BorrowRecord
    $records = $this->MBorrowRecord->getRecords('1');
    
    // Process Every Record
    $currentTime = time();
    foreach ($records as $record){
      $senter = $this->MUser->getByUid($record->senterUid);
      $receiver = $this->MUser->getByUid($record->receiverUid);
      $book = $this->MBook->getById($record->bookId);
      $stockId = $record->stockId;
      
      // Process 3 day before's Record 
      if(($currentTime-$record->time1)>=3*24*60*60){
        // Delete Current Record
        $this->MBorrowRecord->deleteRecord($record->id);
        
        // Change Stock Statue To Available
        $this->MStock->setAvailable($stockId);
        
        // Plus One to Receiver's BorrowQuote
        $this->MUser->addBorrowQuote($receiver->uid);
        
        // Send Notification to Senter
        $toSenter->receiverUid = $senter->uid;
        $toSenter->avatar = 'http://www.gravatar.com/avatar/ba034163a60d54a96b214b284b152062?s=20';
        $toSenter->content = "由于 <a href=\"". $this->MUser->getUrl($receiver->uid) ."\">".$receiver->nickname."</a> 3天内没有提供地址，<a href=\"". site_url("book/subject/$book->id/") ."\">《".$book->name."》</a> 已经重新上架，您可以将其借给其它人 :-)";
        $toSenter->time = time();
        $this->MNotification->insert($toSenter);
        
        // Send Notification to Receiver
        $toReceiver->receiverUid = $receiver->uid;
        $toReceiver->avatar = 'http://www.gravatar.com/avatar/ba034163a60d54a96b214b284b152062?s=20';
        $toReceiver->content = "由于您3天内没有提供地址给 <a href=\"". $this->MUser->getUrl($senter->uid) ."\">".$senter->nickname."</a>，<a href=\"". site_url("book/subject/$book->id/") ."\">《".$book->name."》</a> 已经重新上架。您的借书限额已恢复，可以借阅其它书籍 :)";
        $toReceiver->time = time();
        $this->MNotification->insert($toReceiver);
      } 
      
      // Process 2 day before's Record
      else if(($currentTime-$record->time1)>2*24*60*60){ 
        // Send Email To Receiver
        $this->load->model('Mail');
        $this->Mail->someoneWantSendYouBook($record);
      }
    }
  }

  // 7天未发货，自动失效
  function sendbook(){
    $this->load->model('MBorrowRecord');
	$this->load->model('MBorrowRequest');
    $this->load->model('MStock');
    $this->load->model('MUser');
    $this->load->model('MBook');
    $this->load->model('MNotification');
    $this->load->library('email');
    
    // Load Statue 2 BorrowRecord
    $records = $this->MBorrowRecord->getRecords('2');
    
    // Process Every Record
    $currentTime = time();
    
    foreach ($records as $record){
      if(($currentTime-$record->time2)>7*24*60*60){
        $senter = $this->MUser->getByUid($record->senterUid);
        $receiver = $this->MUser->getByUid($record->receiverUid);
        $book = $this->MBook->getById($record->bookId);
        $stockId = $record->stockId;
        
        // Delete Senter & Set Statue to 0
		$this->MBorrowRequest->insert($record->bookId, $record->receiverUid, $record->message);
        $this->MBorrowRecord->deleteRecord($record->id);
          
        // Change Stock Statue To Available
        $this->MStock->setAvailable($stockId);
          
        // Plus One to Receiver's BorrowQuote
        $this->MUser->addBorrowQuote($receiver->uid);
          
        // Send Notification to Senter
        $toSenter->receiverUid = $senter->uid;
        $toSenter->avatar = 'http://www.gravatar.com/avatar/ba034163a60d54a96b214b284b152062?s=20';
        $toSenter->content = "由于您没有及时寄出<a href=\"". site_url("book/subject/$book->id/") ."\">《".$book->name . "》</a>给 <a href=\"". $this->MUser->getUrl($receiver->uid) ."\">". $receiver->nickname . "</a>，本次借书已经取消，<a href=\"". site_url("book/subject/$book->id/") ."\">《".$book->name . "》</a>已经重新上架 :)";
        $toSenter->time = time();
        $this->MNotification->insert($toSenter);
          
        // Send Notification to Receiver
        $toReceiver->receiverUid = $receiver->uid;
        $toReceiver->avatar = 'http://www.gravatar.com/avatar/ba034163a60d54a96b214b284b152062?s=20';
        $toReceiver->content = "由于 <a href=\"". $this->MUser->getUrl($senter->uid) ."\">". $senter->nickname ."</a> 没有及时寄出<a href=\"". site_url("book/subject/$book->id/") ."\">《". $book->name ."》</a>，本次借书已经取消。您的摆摆券已恢复，可以借阅其它书籍 :)";
        $toReceiver->time = time();
        $this->MNotification->insert($toReceiver);

		// Send Mail to Receiver
        $to = $receiver->email;
        $subject = "$senter->nickname 未及寄出《$book->name"."》";
        $message = "<p>由于 <a href=\"". $this->MUser->getUrl($senter->uid) ."\">". $senter->nickname ."</a> 没有及时寄出<a href=\"". site_url("book/subject/$book->id/") ."\">《". $book->name ."》</a>，本次借书已经取消。您的摆摆券已恢复，可以借阅其它书籍 :)</p>";
            
        $this->email->baibaiSend($to, $subject, $message);
      } 
      
      // Process 4 day before's Record
      else if(($currentTime-$record->time2)>4*24*60*60){ 
        // Send Email To Senter
        $url = site_url('home/');
        $deadline = mdate('%Y-%m-%d', $record->time2 + 7*24*60*60);
        
        $to = $senter->email;
        $subject = "快把《$book->name"."》寄给 $receiver->nickname 吧~";
        $message = "<p>您好像还没有寄出《$book->name"."》哦，$receiver->nickname 该等着急了，请在 $deadline 前寄出《$book->name"."》吧 ~</p>";
        $message .= "<p>如果您已经将书寄出，请记得上摆摆标记一下哦 :)</p>";
        $message .= "详情请点击以下链接：</p>";
        $message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
            
        $this->email->baibaiSend($to, $subject, $message);
      }
    }
  }
  
  // 14天未确认收书，自动确认
  function receivebook(){
    $this->load->model('MBorrowRecord');
    $this->load->model('MStock');
    $this->load->model('MUser');
    $this->load->model('MBook');
    $this->load->model('MNotification');
    $this->load->library('email');
    
    // Load Statue 3 BorrowRecord
    $records = $this->MBorrowRecord->getRecords('3');
    
    // Process Every Record
    $currentTime = time();
    foreach ($records as $record){
      $senter = $this->MUser->getByUid($record->senterUid);
      $receiver = $this->MUser->getByUid($record->receiverUid);
      $book = $this->MBook->getById($record->bookId);
      $stockId = $record->stockId;
      
      // Process 14 day before's Record 
      if ( ($record->expressType!='邮政')&&(($currentTime-$record->time3)>=14*24*60*60) || ($record->expressType=='邮政')&&(($currentTime-$record->time3)>=30*24*60*60) ){
        // Auto Receive
        $receiveTime = mdate('%Y-%m-%d', time());
        $this->MBorrowRecord->updateReceiveInfo($record->id, $receiveTime);
        $this->MUser->addBorrowQuote($record->senterUid);
        $this->MStock->changeReader($record->stockId, $record->receiverUid);
        
        // Send Notification to Receiver
        $toReceiver->receiverUid = $receiver->uid;
        $toReceiver->avatar = 'http://www.gravatar.com/avatar/ba034163a60d54a96b214b284b152062?s=20';
        $toReceiver->content = "由于您未及时确认收到 <a href=\"". site_url("book/subject/$book->id/") ."\">《". $book->name ."》</a>，系统已经自动进行确认。下次收到书，记得及时确认哦～";
        $toReceiver->time = time();
        $this->MNotification->insert($toReceiver);
        
        // Mail To Senter
        $url = site_url('home/');
        $to = $senter->email;
        $subject = "$receiver->nickname 已经收到《$book->name".'》';
        $message = "<p>由于 $receiver->nickname 没有及时确认收到《$book->name"."》，系统已自动为您确认，您已经获得一点借书限额奖励~</p>";
        $message .= "<p>详情请点击以下链接：</p>";
        $message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
        $this->email->baibaiSend($to, $subject, $message);
      } 
      
      // Process 11 day before's Record
      else if ( ($record->expressType!='邮政')&&(($currentTime-$record->time3)>=11*24*60*60) || ($record->expressType=='邮政')&&(($currentTime-$record->time3)>=27*24*60*60) ){ 
        // Send Email To Receiver
        $url = site_url('home/');
        $sentDate = mdate('%Y-%m-%d', $record->time3);
    
        $to = $receiver->email;
        $subject = "您收到了《$book->name".'》吗？';
        $message = "<p>$senter->nickname 已于 $sentDate 寄出《$book->name"."》。如果您已经收到后，请上摆摆确认一下~";
        $message .= "<p>如果您一直没有收到，可以向摆摆投诉。</p>";
        $message .= "<p>详情请点击以下链接：</p>";
        $message .= "<p><a href=\"$url\">$url</a></p>";
        
        $this->email->baibaiSend($to, $subject, $message);
      }
    }
  }

  // 30天没还书，催
  function returnbook(){
    $this->load->model('MBorrowRecord');
    $this->load->model('MUser');
    $this->load->model('MBook');
    $this->load->model('MNotification');
    $this->load->library('email');
    
    // Load Statue 4&5 Records
    $records = $this->MBorrowRecord->getRecordsByStatue(array(4,5));
    
    // Process Every Record
    $currentTime = time();
    foreach ($records as $record){
      // Process 30 day before's Record 
      if(($currentTime-$record->time4)>=30*24*60*60){
        $receiver = $this->MUser->getByUid($record->receiverUid);
        $book = $this->MBook->getById($record->bookId);
        
        // Send Notification to Receiver
        $toReceiver->receiverUid = $receiver->uid;
        $toReceiver->avatar = 'http://www.gravatar.com/avatar/ba034163a60d54a96b214b284b152062?s=20';
        $toReceiver->content = "您已经借阅 <a href=\"". site_url("book/subject/$book->id/") ."\">《". $book->name ."》</a> 一个多月，如果已经已经读完，请让书继续漂流哦～";
        $toReceiver->time = $currentTime;
        $this->MNotification->insert($toReceiver);
        
        // Mail To Receiver
        $url = site_url("book/subject/$book->id/");
        $to = $receiver->email;
        $subject = "$receiver->nickname 您读完了《$book->name".'》吗？';
        $message = "<p>您已经借阅 <a href=\"". site_url("book/subject/$book->id/") ."\">《". $book->name ."》</a> 一个多月了，如果已经已经读完，请让书继续漂流哦～</p>";
        $message .= "<p>您可以点击下面的链接进入书籍页面，并点击“读完了”，让书继续漂流：</p>";
        $message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
        $this->email->baibaiSend($to, $subject, $message);
      } 
    }
  }
}