<?php
class MApp extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('MMAppSession');
	}
  
  function index(){
    $key = $this->config->item('yt_MAppKey');
  }
  
  // 登录验证
  function login(){
    $this->load->model('MUser');
    
    // If Key isn't right
    if ($this->input->post('key') != $this->config->item('yt_MAppKey')){
      echo 0;
      return;
    }
    
    // Load Post Data
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    
    // Validate User
    if ($user = $this->MUser->loginValid($email, $password)){
      $session = $this->MMAppSession->newSession($user->uid);
      
      $result = new SimpleXMLElement("<user></user>");
      $result->addChild('sessionId', $session->sessionId);
      $result->addChild('uid', $user->uid);
      $result->addChild('nickname', $user->nickname);
      $result->addChild('email', $user->email);
      $result->addChild('password', $user->password);
      Header('Content-type: text/xml');
      echo $result->asXML();
    } else {
      echo 0;
      return;
    }
  }
  
  // 通过ISBN获取书籍信息
  function isbn(){
    // If Key isn't right
    if ($this->input->post('key') != $this->config->item('yt_MAppKey')){
      echo 0;
      return;
    }
    
    $this->load->model('MBook');
    $this->load->model('MTag');
    
    // Load Post Data
    $isbn = $this->input->post('isbn');
    
    # 如果数据库中无此书，从豆瓣读取
    if (!$book = $this->MBook->getByIsbn($isbn)){
      $this->load->helper('douban');
      $book = getBookDetail($isbn);
      if($book==false){
        return 0;
      }
      if (!$this->MBook->getByIsbn($book->isbn)){
         $bookId = $this->MBook->insert($book);
         
         // add tags
         foreach($book->tags as $tag){
            $this->MTag->insert(0, $bookId, $tag);
         }
      }
    }
    
    $book = $this->MBook->getByIsbn($book->isbn);
    
    $result = new SimpleXMLElement("<book></book>");
    $result->addChild('id', $book->id);
    $result->addChild('doubanId', $book->doubanId);
    $result->addChild('name', $book->name);
    $result->addChild('summary', str_replace('&nbsp;', ' ', $book->summary));
    $result->addChild('pic', $book->pic);
    $result->addChild('author', $book->author);
    $result->addChild('translator', $book->translator);
    $result->addChild('publisher', $book->publisher);
    $result->addChild('pubdate', $book->pubdate);
    $result->addChild('price', $book->price);
    Header('Content-type: text/xml');
    echo $result->asXML();
  }
  
  // 捐书
  function donate(){
    // If Key isn't right
    if ($this->input->post('key') != $this->config->item('yt_MAppKey')){
      echo 0;
      return;
    }
    
    $this->load->model('MStock');
    
    // Load Post Data
    $sessionId = $this->input->post('sessionId');
    $userId = $this->input->post('userId');
    $bookId = $this->input->post('bookId');
    
    // If SessionId is not right
    $session = $this->MMAppSession->getById($sessionId);
    if((!$session) || ($session->uid != $userId)){
      echo 0;
      return;
    }
    
    // Add Stock And Return
    if ($stockId = $this->MStock->insert($bookId, $userId)){
      $result = new SimpleXMLElement("<stock></stock>");
      $result->addChild('id', $stockId);
      Header('Content-type: text/xml');
      echo $result->asXML();
      return;
    }

    echo 0;
  }
  
  // 获取我可借出的书
  function getMyAvailable(){
    // If Key isn't right
    if ($this->input->post('key') != $this->config->item('yt_MAppKey')){
      echo 0;
      return;
    }
    
    $this->load->model('MStock');
    $this->load->model('MBook');
    
    // Load Post Data
    $sessionId = $this->input->post('sessionId');
    $userId = $this->input->post('userId');
    
    // If SessionId is not right
    $session = $this->MMAppSession->getById($sessionId);
    if((!$session) || ($session->uid != $userId)){
      echo 0;
      return;
    }
    
    // Load My Available Book
    $stocks = $this->MStock->getAvailableByReaderId($userId);
    
    // Return
    $result = new SimpleXMLElement("<books></books>");
    foreach ($stocks as $stock){
      $book = $this->MBook->getById($stock->bookId);
      $book->finishTime = $stock->finishTime;
      
      $resultNode = $result->addChild('book');
      $resultNode->addChild('id', $book->id);
      $resultNode->addChild('doubanId', $book->doubanId);
      $resultNode->addChild('name', $book->name);
      $resultNode->addChild('summary', str_replace('&nbsp;', ' ', $book->summary));
      $resultNode->addChild('pic', $book->pic);
      $resultNode->addChild('author', $book->author);
      $resultNode->addChild('translator', $book->translator);
      $resultNode->addChild('publisher', $book->publisher);
      $resultNode->addChild('pubdate', $book->pubdate);
      $resultNode->addChild('price', $book->price);
      $resultNode->addChild('finishTime', $book->finishTime);
    }
    Header('Content-type: text/xml; charset=utf-8');
    echo $result->asXML();
    return;
  }
}