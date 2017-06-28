function clearCookie(name) {
	var mydate = new Date();	
	mydate.setTime(mydate.getTime() - 1);
	document.cookie = name + "=; expires=" + mydate.toGMTString(); 
}

function logout() {
	clearCookie('userid');
	clearCookie('secret');
	clearCookie('PHPSESSID');
	location.reload();
}

function submit() {

	var missing = $('.question-group:not(:has(:radio:checked))').length

	if (missing > 0) {
		$("#confirm-num").text(missing)
		$("#confirm-modal").modal();
		return false;	
	} else {
		doSubmit();
	}
}

function init() {
	// Setup affix menu
	$('#nav').affix({offset: {top: $('.intro-header').height()}});
	$('#modal-submit').click(doSubmit);
	$('input[data-score]').click(function(e) { 
			popScore(
				e.pageY-10,
				e.pageX-10,
				$(this).attr('data-score')) })

}

function popScore(top, left, score) {
	if (score > 0) {
		var div = $("#score").clone();
		div.text("+" + score)
		$("body").append(div);

		div.css('position','absolute');
		div.offset({left: left, top: top});    
    	div.animate({"top": "-=40px"}, 500, "linear");
    	div.animate({"opacity": 0, "top": "-=40px"}, 500, "linear", function() {
			$(this).remove();
		});	
	}
	return true;
} 



function doSubmit() {
	$("#form").submit();
}

$(document).ready(init);