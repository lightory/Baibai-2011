<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package   CodeIgniter
 * @author    Pradeep Kumar
 * @copyright Copyright (c) 2009, hyderabad, Inc.
 * @license   http://tech-pundits.com/license.html
 * @link    http://tech-pundits.com
 * @since   Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Rss Class
 *
 * @package   CodeIgniter
 * @subpackage  Libraries
 * @category  Rss Parser
 * @author    Pradeep Kumar
 */
class Rss {
   
  public $url = ""; // saves the rss feed url
  private $aFiledata = ""; // saves the file data in array format
  private $sFiledata = "";
  private $item = "";
  private $entry = "";
  private $title = "";
  private $link = "";
  private $data = "";
  private $description = "";
  private $date = "";
  private $date_gmt = "";
  private $image = "";
  private $result;
  /**
   * @return unknown
   */
  public function getAFiledata() {
    return $this->aFiledata;
  }
  
  /**
   * @return unknown
   */
  public function getEntry() {
    return $this->entry;
  }
  
  /**
   * @return unknown
   */
  public function getItem() {
    return $this->item;
  }
  
  /**
   * @return unknown
   */
  public function getSFiledata() {
    return $this->sFiledata;
  }
  
  /**
   * @return unknown
   */
  public function getTitle() {
    return $this->title;
  }
  
  /**
   * @return unknown
   */
  public function getUrl() {
    return $this->url;
  }
  
  /**
   * @return unknown
   */
  public function getData() {
    return $this->data;
  }
  
  /**
   * @return unknown
   */
  public function getDescription() {
    return $this->description;
  }
  
  /**
   * @return unknown
   */
  public function getDate() {
    return $this->date;
  }
  
  /**
   * @return unknown
   */
  public function getDate_gmt() {
    return $this->date_gmt;
  }
  
  /**
   * @return unknown
   */
  public function getImage() {
    return $this->image;
  }
  
  /**
   * @return unknown
   */
  public function getResult() {
    return $this->result;
  }
  
  /**
   * @param unknown_type $result
   */
  public function setResult($result) {
    $this->result = $result;
  }

  /**
   * @param unknown_type $image
   */
  public function setImage($image) {
    $this->image = $image;
  }

  /**
   * @param unknown_type $date_gmt
   */
  public function setDate_gmt($date_gmt) {
    $this->date_gmt = $date_gmt;
  }
  
  /**
   * @return unknown
   */
  public function getLink() {
    return $this->link;
  }
  
  /**
   * @param unknown_type $link
   */
  public function setLink($link) {
    $this->link = $link;
  }

  /**
   * @param unknown_type $date
   */
  public function setDate($date) {
    $this->date = $date;
  }
  /**
   * @param unknown_type $data
   */
  public function setData($data) {
    $this->data = $data;
  }
  
  /**
   * @param unknown_type $description
   */
  public function setDescription($description) {
    $this->description = $description;
  }

  /**
   * @param unknown_type $aFiledata
   */
  public function setAFiledata($aFiledata) {
    $this->aFiledata = $aFiledata;
  }
  
  /**
   * @param unknown_type $Entry
   */
  public function setEntry($entry) {
    $this->entry = $entry;
  }
  
  /**
   * @param unknown_type $Item
   */
  public function setItem($item) {
    $this->item = $item;
  }
  
  /**
   * @param unknown_type $sFiledata
   */
  public function setSFiledata($sFiledata) {
    $this->sFiledata = $sFiledata;
  }
  
  /**
   * @param unknown_type $Title
   */
  public function setTitle($title) {
    $this->title = $title;
  }
  
  /**
   * @param unknown_type $Url
   */
  public function setUrl($url) {
    $this->url = $url;
  }
  
  
/**
   * Constructor
   *
   * @access  public
   * @param array initialization parameters
   */
  function Rss($params = array())
  {
    if (count($params) > 0)
    {
      $this->initialize($params);   
    }
    
    log_message("debug", "Rss Class Initialized");
  }
  
  // --------------------------------------------------------------------
  
  /**
   * Initialize Preferences
   *
   * @access  public
   * @param array initialization parameters
   * @return  void
   */
  function initialize($params = array())
  {
    if (count($params) > 0)
    {
      foreach ($params as $key => $val)
      {
        if (isset($this->$key))
        {
          $this->$key = $val;
        }
      }
    }
  }
  
  
  function getRss()
  {
    return $this->run();
    
  }
  
  function run()
  {
    $this->setAFiledata(file($this->getUrl()));

      $this->setSFiledata(implode("", $this->getAFiledata()));
       
    $this->setSFiledata(str_replace(array ("\r\n", "\r"), "\n", $this->getSFiledata()));
    preg_match_all("|<item[^>]*>(.*?)</item>|is", $this->getSFiledata(), $this->item);
    $items = $this->getItem();
    
    $this->setItem($items[0]);
    $this->setData("<table>");
    $this->result = array();
    
    if(empty($this->item))
    {
      preg_match_all("|<entry[^>]*>(.*?)</entry>|is", $this->getSFiledata(), $this->item);
      $items = $this->getItem();
      $this->setItem($items[0]);
      $this->setData("<table>");
      $this->result = array();
    }
    foreach ($this->getItem() as $item)
    {
      preg_match("|<title[^>]*>(.*?)</title>|is", $item, $this->title);
      $this->setTitle(strip_tags(str_replace(array("<![CDATA[', ']]>"), array("",""), trim($this->title[1]))));
      
      preg_match("|<pubdate[^>]*>(.*?)</pubdate>|is", $item, $this->date_gmt);
      if ($this->date_gmt) {
        $this->setDate_gmt(strtotime($this->date_gmt[1]));
      } else {
        preg_match("|<dc:date[^>]*>(.*?)</dc:date>|is", $item, $this->date_gmt);
        if(isset($this->date_gmt[1]))
        {
          $this->setDate_gmt(preg_replace("|([-+])([0-9]+):([0-9]+)$|", "\1\2\3", $this->date_gmt[1]));
          $this->setDate_gmt(str_replace("T", " ", $this->date_gmt));
          $this->setDate_gmt(strtotime($this->date_gmt));
        }
      }
      if($this->getDate_gmt())
      {
        $this->setDate_gmt(gmdate("Y-m-d H:i:s", $this->getDate_gmt()));
        $this->setDate($this->getDate_gmt());
        $this->getDate();
      }
      preg_match('|<media:content url="(.*?)" />|is', $item, $this->image);
      preg_match("|<media:text[^>]*>(.*?)</media:text>|is", $item, $this->image);
      
      $sarr = array("&amp;nbsp;","#60;","&gt;","&lt;");
        $repl = array("","<",">","<");
        if(isset($this->image[1]))
        {
          $this->image = str_replace($sarr,$repl,$this->image[1]);
          preg_match('|<img[^>]*src="(.*?)"[^>]*/>|is', $this->image, $this->image);
          $this->setImage($this->image[1]);
        }
        else 
          $this->image = "";

      preg_match("|<description[^>]*>(.*?)</description>|is", $item, $this->description);
  

      if($this->getDescription())
      {
        $this->setDescription(str_replace(array("<![CDATA[', ']]>"), "", trim($this->description[1])));
        $this->setDescription(str_replace($sarr,$repl,$this->description));
      }
      else 
      {
        preg_match("|<summary[^>]*>(.*?)</summary>|is", $item, $this->description);
        if($this->getDescription())
        {
        $this->setDescription(str_replace(array("<![CDATA[', ']]>"), "", trim($this->description[1])));
        
        $this->setDescription(str_replace($sarr,$repl,$this->description));
        }
        else 
        $this->setDescription("");
      }
      preg_match("|<link[^>]*>(.*?)</link>|is", $item, $this->link);
      
      if(!empty($this->link))
      {
      /*
      if(eregi("<media",$this->link[1]))
        {
          $img ="";
        
          preg_match("|<media:thumbnail[^>]*>(.*?)</media:thumbnail>|is", $item, $img);
          preg_match('|<link rel="alternate"[^>]* href="(.*?)">|is', $item, $this->link);
          $this->setDescription($img[1].$this->getDescription());
        }*/
      $this->setLink(str_replace(array("<![CDATA[', ']]>",'"/>'), "", trim(strip_tags($this->link[1]))));
      $this->link;
      }
      else 
      {
          preg_match('|<link rel="alternate"[^>]* href="(.*?)"/>|is', $item, $this->link);
          $this->setLink(str_replace(array('<![CDATA[', ']]>','"/>'), '', trim(strip_tags($this->link[1]))));
          $this->link;
      }
      
      
      $this->data .= '<tr><td> <a title="'.strip_tags($this->getDescription()).'" href="'.$this->getLink().'">'.$this->getTitle().'</a></td><td>'.$this->getDate().'</td></tr>';
      $this->result[] = array(
                    "title" => $this->getTitle(),
                    "link"  => $this->getLink(),
                    "date"  => $this->getDate(),
                    "image" => $this->getImage(),
                    "description" => $this->getDescription(),
                  );  
    }
    $this->data .="</table>";
    
   return $this->getResult();
  }
 
}