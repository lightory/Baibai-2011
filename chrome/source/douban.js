var url = window.location.toString();

// Book Page
if ( url.indexOf('subject')!=-1 ){
	var doubanId = url.replace(/[^\d]/g,'');
	
	// 想借&捐赠按钮
	var borrowButton = $('<a href="http://bookfor.us/book/doubanId/'+ doubanId +'/?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin#borrow" class="baibai_actionButton baibai_greyButton" style="color:#FFF; float:left;" target="_blank">想借</a>');
	var donateButton = $('<a href="http://bookfor.us/book/doubanId/'+ doubanId +'/?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin#donate" class="baibai_actionButton" style="color:#FFF; float:left;" target="_blank">捐赠</a>');
	$('div.a_stars').before(borrowButton).before(donateButton);
	
	$.ajax({
    	type : "POST",
    	url : "http://bookfor.us/api/book/getStocks/",
    	data : "type=doubanId&keyword=" + doubanId,
    	success : function(data) {
    		data = eval(data);
      		var stocksNumber = data.length;
      		
      		if(stocksNumber==0){
      			return;
      		}
      		
      		borrowButton.removeClass('baibai_greyButton');
      		
      		// 标题，库存量
      		var titleSpan = $('<span style="font-size: 12px; color: rgb(188, 188, 188); ">（库存'+ stocksNumber +'本）</span>');
      		$('h1 .clear').before(titleSpan);
      		
      		// 侧边栏持书人列表
      		var sidebarDiv = $('<div class="gray_ad" style="position:relative;"></div>');
      		sidebarDiv.append($('<h2>谁手上有这本书? &nbsp; ·&nbsp;·&nbsp;·&nbsp;·&nbsp;·&nbsp;·&nbsp;</h2>'))
      		var limit = stocksNumber<8 ? stocksNumber : 8;
      		for (var i=0; i<stocksNumber; i++){
      			var dt = $('<dt></dt>');
      			dt.append($('<a href="' + data[i].url + '?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin"><img src="'+ data[i].avatar +'" class="m_sub_img" alt="' + data[i].username +'"></a>'));
      			var dd = $('<dd></dd>');
      			dd.append($('<a href="'+ data[i].url +'?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin">'+ data[i].nickname +'</a> <span>('+ data[i].province +')</span>'));
      			var dl = $('<dl class="ob" style="width:72px;margin:0px;"></dl>').append(dt).append(dd);
      			sidebarDiv.append(dl);
      		}
      		sidebarDiv.append('<div class="clear"></div>');
      		sidebarDiv.append('<a href="http://bookfor.us" style="position: absolute; bottom: 6px; right: 10px; color:#BCBCBC;">Via Bookfor.us</a>');
      		sidebarDiv.prependTo('div.aside');
    	}
  	});
}

// People's Book List Page
else if( (url.indexOf('mine')!=-1)||(url.indexOf('people')!=-1) ){
	$('div.item ul').each(function(){
		var bookUrl = $('li.title a', this).attr('href');
		var doubanId = bookUrl.replace(/[^\d]/g,'');
		
		var borrowButton = $('<a href="http://bookfor.us/book/doubanId/'+ doubanId +'/?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin#borrow" class="baibai_actionButton baibai_greyButton" style="color: rgb(255, 255, 255); margin-left:6px;" target="_blank">想借</a>');
		var donateButton = $('<a href="http://bookfor.us/book/doubanId/'+ doubanId +'/?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin#donate" class="baibai_actionButton" style="color: rgb(255, 255, 255); " target="_blank">捐赠</a>');
		$('div.opt-l', this).append(borrowButton).append(donateButton);
		
		var $this = $(this);
		$.ajax({
    		type : "POST",
    		url : "http://bookfor.us/api/book/getStocks/",
    		data : "type=doubanId&keyword=" + doubanId,
    		success : function(data) {
    			data = eval(data);
      			if(data.length>0){
      				borrowButton.removeClass('baibai_greyButton');
      				
      				// 标题，库存量
      				var titleSpan = $('<span style="font-size: 12px; color: rgb(188, 188, 188); ">（库存'+ data.length +'本）</span>');
      				$this.children('li.title').append(titleSpan);
      			}
    		}
    	});
	});
}

// System's Book List Page : doulist
else if( url.indexOf('doulist')!=-1 ){
	$('div.article table').each(function(){
		var bookUrl = $('div.pl2 a', this).attr('href');
		var doubanId = bookUrl.replace(/[^\d]/g,'');
		
		var borrowButton = $('<a href="http://bookfor.us/book/doubanId/'+ doubanId +'/?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin#borrow" class="baibai_actionButton baibai_greyButton" style="color: rgb(255, 255, 255); margin-left:6px; float:left;" target="_blank">想借</a>');
		var donateButton = $('<a href="http://bookfor.us/book/doubanId/'+ doubanId +'/?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin#donate" class="baibai_actionButton" style="color: rgb(255, 255, 255); float:left; " target="_blank">捐赠</a>');
		$('td > span.rr', this).prepend(donateButton).prepend(borrowButton);
		
		var $this = $(this);
		$.ajax({
    		type : "POST",
    		url : "http://bookfor.us/api/book/getStocks/",
    		data : "type=doubanId&keyword=" + doubanId,
    		success : function(data) {
    			data = eval(data);
      			if(data.length>0){
      				borrowButton.removeClass('baibai_greyButton');
      				
      				// 标题，库存量
      				var titleSpan = $('<span style="font-size: 12px; color: rgb(188, 188, 188); ">（库存'+ data.length +'本）</span>');
      				$this.children('div.pl2 a').after(titleSpan);
      			}
    		}
    	});
	});
}

// System's Book List Page : tag
else if( url.indexOf('tag')!=-1 ){
	$('div.article table').each(function(){
		var bookUrl = $('div.pl2 a', this).attr('href');
		var doubanId = bookUrl.replace(/[^\d]/g,'');
		
		var borrowButton = $('<a href="http://bookfor.us/book/doubanId/'+ doubanId +'/?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin#borrow" class="baibai_actionButton baibai_greyButton" style="color: rgb(255, 255, 255); margin-left:6px; float:left;" target="_blank">想借</a>');
		var donateButton = $('<a href="http://bookfor.us/book/doubanId/'+ doubanId +'/?utm_source=chrome&utm_medium=chrome&utm_campaign=plugin#donate" class="baibai_actionButton" style="color: rgb(255, 255, 255); float:left; " target="_blank">捐赠</a>');
		$('td p span.rr', this).prepend(donateButton).prepend(borrowButton);
		
		var $this = $(this);
		$.ajax({
    		type : "POST",
    		url : "http://bookfor.us/api/book/getStocks/",
    		data : "type=doubanId&keyword=" + doubanId,
    		success : function(data) {
    			data = eval(data);
      			if(data.length>0){
      				borrowButton.removeClass('baibai_greyButton');
      				
      				// 标题，库存量
      				var titleSpan = $('<span style="font-size: 12px; color: rgb(188, 188, 188); ">（库存'+ data.length +'本）</span>');
      				$this.children('div.pl2 a').after(titleSpan);
      			}
    		}
    	});
	});
}