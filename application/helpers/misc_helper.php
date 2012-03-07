<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getGroupIcon($group){
	if($group->picFid && $group->picId){
		return site_url("upload/group/icon/$group->picFid/").$group->picId;
	} else{
		return site_url('upload/group/icon/').'default.jpg';
	}
}