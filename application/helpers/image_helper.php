<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function grabImage($url, $path, $filename="") { 
  if($url==""){
    return false;
  }
  
  if(!file_exists($path)){
    mkdir($path);
  }
  
  if($filename=="") { 
    $ext=strrchr($url,"."); 
    if($ext!=".gif" && $ext!=".jpg"){
      return false;
    }
    $filename=time().$ext; 
  }
  
  ob_start(); 
  readfile($url); 
  $img = ob_get_contents(); 
  ob_end_clean(); 
  $size = strlen($img);
  
  $fp2 = @fopen($path.$filename, "a"); 
  fwrite($fp2,$img); 
  fclose($fp2);
  
  return $filename; 
}
?>