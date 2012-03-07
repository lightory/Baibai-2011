<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "account";
$route['404_override'] = 'misc/e404';

// Misc
$route['404'] = 'misc/e404';
$route['weekly/(:num)'] = "weekly/view/$1";

// 用户系统
$route['home'] = "account/home";
$route['mine/wishes'] = "account/wishes";
$route['mine/onhand'] = "account/onhand";
$route['mine/available'] = "account/available";
$route['mine'] = "account/home";
$route['user/(:any)/wishes'] = "account/wishes/$1";
$route['user/(:any)/onhand'] = "account/onhand/$1";
$route['user/(:any)/available'] = "account/available/$1";
$route['user/(:any)/follows'] = "account/follows/$1";
$route['user/(:any)/fans'] = "account/fans/$1";
$route['user/(:any)'] = "account/view/$1";

// 书架
$route['book/(:num)'] = "book/all/$1";

// 小组
$route['group'] = "group";
$route['group/latest'] = "group/latest";
$route['group/latest/(:num)'] = "group/latest/$1";
$route['group/mytopics'] = "group/mytopics";
$route['group/mytopics/(:num)'] = "group/mytopics/$1";
$route['group/myreplies'] = "group/myreplies";
$route['group/myreplies/(:num)'] = "group/myreplies/$1";
$route['group/topic/(:num)'] = "group/topic/$1";
$route['group/(:num)/newtopic'] = "group/newtopic/$1";
$route['group/newtopic_do'] = "group/newtopic_do";
$route['group/reply_do'] = "group/reply_do";
$route['group/(:num)/join_do'] = "group/join_do/$1";
$route['group/(:num)/quit_do'] = "group/quit_do/$1";
$route['group/addbook/(:num)'] = "group/addbook/$1";
$route['group/addbook_do'] = "group/addbook_do";
$route['group/removebook/(:num)/(:num)'] = "group/removebook/$1/$2";
$route['group/removebook_do'] = "group/removebook_do";
$route['group/newgroup'] = "group/newgroup";
$route['group/newgroup_do'] = "group/newgroup_do";
$route['group/(:any)/edit'] = "group/groupedit/$1";
$route['group/(:any)/edit_do'] = "group/groupedit_do/$1";
$route['group/(:any)/editicon'] = "group/editicon/$1";
$route['group/(:any)/editicon_do'] = "group/editicon_do/$1";
$route['group/(:any)/members'] = "group/members/$1";
$route['group/(:any)/books'] = "group/books/$1";
$route['group/(:any)/books/(:num)'] = "group/books/$1/$2";
$route['group/(:any)'] = "group/grp/$1";

// 位置
$route['location/(:any)'] = "location/view/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */