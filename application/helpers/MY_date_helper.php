<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function toRelativeTime($time){
  $now = time();
  $seconds = $now - $time;
  if($seconds<60){
    return $seconds."秒钟前";
  } 
  
  $minutes = floor($seconds/60);
  if($minutes<60){
    return $minutes."分钟前";
  } 
  
  $hours = floor($minutes/60);
  if($hours<24){
    return $hours."小时前";
  }
  
  $days = floor($hours/24);
  if($days<7){
    return $days."天前";
  }
  
  $weeks = floor($days/7);
  if($weeks<4){
    return $weeks."周前";
  }
  
  $months = round($days/30);
  if($months<12){
    return $months."个月前";
  }
  
  $year = round($days/365);
  return $year."年前";
}
