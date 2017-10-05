$(function($){
	$('.content').on('click','.tab-link',function(event) {
		var _this=$(this);
		var _thisdata="#"+_this.attr('data-type');
		var _thisSpan=_this.find('span');
		_this.addClass('active').siblings().removeClass('active')
		$(_thisdata).addClass('active').siblings().removeClass('active');
		_this.addClass('active').find("span").addClass('active').parent().siblings().find('span').removeClass('active')
	});
})