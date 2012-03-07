jQuery.extend(jQuery.validator.messages, {
	required: "必须填写",
	remote: "请修正该字段",
	email: "请输入正确的电子邮件",
	url: "请输入合法的网址",
	date: "请输入合法的日期",
	dateISO: "请输入合法的日期 (ISO).",
	number: "请输入合法的数字",
	digits: "只能输入整数",
	creditcard: "请输入合法的信用卡号",
	equalTo: "请再次输入相同的值",
	accept: "请输入拥有合法后缀名的字符串",
	maxlength: jQuery.validator.format("最多只能 {0} 位"),
	minlength: jQuery.validator.format("最少需要 {0} 位"),
	rangelength: jQuery.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),
	range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
	max: jQuery.validator.format("请输入一个最大为 {0} 的值"),
	min: jQuery.validator.format("请输入一个最小为 {0} 的值")
});

// 联系电话(手机/电话皆可)验证   
jQuery.validator.addMethod("isPhone", function(value,element) {   
	var length = value.length;   
	var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;   
	var tel = /^\d{3,4}-?\d{7,9}$/;   
	return this.optional(element) || (tel.test(value) || mobile.test(value));   
}, "请正确填写您的联系电话");   

jQuery.validator.addMethod("isMobile", function(value, element) {       
    var length = value.length;   
    var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;   
    return this.optional(element) || (length == 11 && mobile.test(value));       
}, "请正确填写您的手机号码");

// 字符验证       
jQuery.validator.addMethod("stringCheck", function(value, element) {       
	return this.optional(element) || /^[\u0391-\uFFE5\w]+$/.test(value);       
}, "只能包括中文字、英文字母、数字和下划线");  

// 中文字两个字节       
jQuery.validator.addMethod("byteRangeLength", function(value, element, param) {       
	var length = value.length;       
	for(var i = 0; i < value.length; i++){       
		if(value.charCodeAt(i) > 127){       
			length++;       
		}       
	}       
	return this.optional(element) || ( length >= param[0] && length <= param[1] );       
}, "请确保输入的值在3-15个字节之间(一个中文字算2个字节)");   