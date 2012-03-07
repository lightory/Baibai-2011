<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getBookDetail($isbn){
	$apikey = '05550a4a098bad3d0fff1ede1ca307b7';
	$isbn = str_replace(' ', '', $isbn);
	$xmlFile = 'http://api.douban.com/book/subject/isbn/' . $isbn . '?apikey=' . $apikey;

	$obBook = simpleXML_load_file($xmlFile);
	
	$book->isbn = $isbn;
	$book->doubanId = preg_replace('/[^0-9]/','',$obBook->id);
	$book->name = (string)$obBook->title;
	$book->summary = nl2br(str_replace(' ', '&nbsp;', (string)$obBook->summary));
	$authors = Array();
	foreach($obBook->author as $author) {
		$authors[] = $author->name; 
	}
	$book->author = implode('/', $authors);
	$book->pic = str_replace('spic', 'lpic', $obBook->link[2]['href']);
	$book->translator = '';
	
	foreach($obBook->children('http://www.douban.com/xmlns/')->attribute as $attribute){
		$type = $attribute->attributes();
		$type = $type['name'];
		switch($type){
			case 'translator':$book->translator = (string)$attribute;break;
			case 'publisher':$book->publisher = (string)$attribute;break;
			case 'pubdate':$book->pubdate = (string)$attribute;break;
			case 'price':$book->price = (string)$attribute;break;
    		case 'isbn13':$book->isbn = (string)$attribute;break;
		}
	}
  
	foreach($obBook->children('http://www.douban.com/xmlns/')->tag as $tag){
		$tag = $tag->attributes();
		$tag = $tag['name'];
    	$book->tags[] = (string)$tag;
	}
		
	return $book;
}

function getBookDetailByDoubanId($doubanId){
	$apikey = '05550a4a098bad3d0fff1ede1ca307b7';
	$doubanId = str_replace(' ', '', $doubanId);
	$xmlFile = 'http://api.douban.com/book/subject/' . $doubanId . '?apikey=' . $apikey;

  //if (file_exists($xmlFile)) {
	  $obBook = simpleXML_load_file($xmlFile);
  //} else {
    //return false;
  //}
	
	$book->doubanId = preg_replace('/[^0-9]/','',$obBook->id);
	$book->name = (string)$obBook->title;
	$book->summary = nl2br(str_replace(' ', '&nbsp;', (string)$obBook->summary));
	$authors = Array();
	foreach($obBook->author as $author) {
		$authors[] = $author->name; 
	}
	$book->author = implode('/', $authors);
	$book->pic = str_replace('spic', 'lpic', $obBook->link[2]['href']);
	$book->translator = '';
	
	foreach($obBook->children('http://www.douban.com/xmlns/')->attribute as $attribute){
		$type = $attribute->attributes();
		$type = $type['name'];
		switch($type){
			case 'translator':$book->translator = (string)$attribute;break;
			case 'publisher':$book->publisher = (string)$attribute;break;
			case 'pubdate':$book->pubdate = (string)$attribute;break;
			case 'price':$book->price = (string)$attribute;break;
      		case 'isbn13':$book->isbn = (string)$attribute;break;
		}
	}
  
	foreach($obBook->children('http://www.douban.com/xmlns/')->tag as $tag){
		$tag = $tag->attributes();
		$tag = $tag['name'];
		$book->tags[] = (string)$tag;
	}
		
	return $book;
}