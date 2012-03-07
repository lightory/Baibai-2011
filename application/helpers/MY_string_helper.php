<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function utf_substr($str,$len){
     for($i=0;$i<$len;$i++)
     {
         $temp_str=substr($str,0,1);
         if(ord($temp_str) > 127){
             $i++;
         if($i<$len)    {
             $new_str[]=substr($str,0,3);
             $str=substr($str,3);
             }
         }
     else {
         $new_str[]=substr($str,0,1);
         $str=substr($str,1);
         }
     }
     return join($new_str);
 }
 
 function utf_strlen($str) {
	 $i = 0;
	 $count = 0;
	 $len = strlen ($str);
	 while ($i < $len) {
		$chr = ord ($str[$i]);
		$count++;
		$i++;
		if($i >= $len) break;
		if($chr & 0x80) {
		 $chr <<= 1;
		 while ($chr & 0x80) {
		 $i++;
		 $chr <<= 1;
		 }
		}
	 }
	 return $count;
 }