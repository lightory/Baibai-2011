function getWindowHeight(){  
   var windowWidth, windowHeight;
   if (self.innerHeight) {   // all except Explorer
     windowWidth = self.innerWidth;
     windowHeight = self.innerHeight;
   } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
     windowWidth = document.documentElement.clientWidth;
     windowHeight = document.documentElement.clientHeight;
   } else if (document.body) { // other Explorers
     windowWidth = document.body.clientWidth;
     windowHeight = document.body.clientHeight;
   }  
  
   return windowHeight;
}

$(document).ready(function(){
  // Auto Change Footer Position
  chgFooterPosition = function(){
    var pageHeight = parseInt($('body').css('height'));
    var windowHeight = getWindowHeight();
    if(pageHeight<windowHeight){
      $('#footer').css('position', 'absolute').css('bottom', '0px').css('width', '100%');
    } else{
      $('#footer').css('position', 'static');
    }
  }
  chgFooterPosition();
  setInterval('chgFooterPosition()', '100');
  
	// FancyBox
	$(".fancybox").fancybox({
        'centerOnScroll' : 'yes',
        'transitionIn'   : 'elastic',
        'transitionOut'  : 'elastic',
        'type'           : 'ajax'
    });
	
	// SeachBox Prompt
	var searchbox_prompt = '搜索您想要的图书';
	$('form#search input.keyword').val(searchbox_prompt);
	$('form#search input.keyword').focus(function(){
		if ( $(this).val() == searchbox_prompt ){
			$(this).val('');
		}
	});
	$('form#search input.keyword').blur(function(){
		if ( $(this).val() == '' ){
			$(this).val(searchbox_prompt);
		}
	});
	
	// Search
	$('form.search').submit(function(){
		var q = $('input.keyword', this).val();
		window.location.href = SITE_URL+'book/search/'+q+'/';
		return false;
	});
	
	// Show Book Message
	$('.book_more').live('click', function(){
		var statue =  $(this).attr('statue');
		
		switch(statue){
			case '0':
				var href = $(this).attr('href');
				var $this = $(this);
				
				$.ajax({
					type		: "GET",
					url		: href,
					success: function(data) {
						$this.parent().append(data);
					}
				});
				
				$('img', this).attr('src', SITE_URL + 'include/style/img/books_list_more2.png');
				$(this).attr('statue', '1');
				
				return false;
				break;
			case '1':
				$(this).parent().find('div.borrow_messages').hide();
				$('img', this).attr('src', SITE_URL + 'include/style/img/books_list_more.png');
				$(this).attr('statue', '2');
				
				return false;
				break;
			case '2':
				$(this).parent().find('div.borrow_messages').show();
				$('img', this).attr('src', SITE_URL + 'include/style/img/books_list_more2.png');
				$(this).attr('statue', '1');
				
				return false;
				break;
		}
	});
	
	// Confirm Dialog
	$('.confirmAct').live('click', function(){
		return confirm( $(this).attr('confirmMessage') );
	});
});
