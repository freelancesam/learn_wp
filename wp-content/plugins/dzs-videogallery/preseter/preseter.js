jQuery(document).ready(function($){
	
	$('.select-wrapper select').unbind();
	$('.select-wrapper select').change(change_select);
	setTimeout(function(){
	$('.preseter').animate({'left' : -140},{duration:700, queue:false});
	},500)
	$('.preseter .the-icon').bind("click", function(){
		var _t = jQuery(this);
		if(parseInt(_t.parent().css('left')) < 0)
		_t.parent().animate({'left' : 0},{duration:300, queue:false});
		else
		_t.parent().animate({'left' : -140},{duration:300, queue:false});
	})
})
function change_select(){
	var selval = (jQuery(this).find(':selected').text());
	jQuery(this).parent().children('span').text(selval);
}
