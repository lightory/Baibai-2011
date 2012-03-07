<?php
	class MY_Exceptions extends CI_Exceptions {
		function __construct(){
			parent::__construct();
    	}
                
		function show_404($page = ''){  
			header("Location: http://bookfor.us/404/");
		}
	}
?>