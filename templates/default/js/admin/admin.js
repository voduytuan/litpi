//jquery fx begin
$(document).ready(function()
{

	
	var activemenu = $('.page-header').attr('rel');
	var activemenuselector = $('#' + activemenu);
	if(activemenuselector.length)
	{
		activemenuselector.addClass('active');
	}
	
	// Check all checkboxes when the one in a table head is checked:

	$('.check-all').click(
		function(){
			$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));   
		}
	);
	
	
	

});

// JavaScript Document
function delm(theURL)
{
if (confirm(delConfirm))
{
	window.location.href=theURL;
}
		  
}


function scrollTo(selector)
{
	var target = $('' + selector);
	if (target.length)
	{
		var top = target.offset().top;
		$('html,body').animate({scrollTop: top}, 1000);
		return false;
	}	
}

//use this function to keep connection (prevent login session expired for contents manipulation) alive
function ping()
{
	var nulltimestamp = new Date().getTime();
	var t = setTimeout("ping()", 1000*60*5); //5 minute
    $.ajax({
		 type: "GET",
		 url: rooturl_admin + 'null/index/timestamp/' + nulltimestamp,
		 dataType: "xml",
		 success: function(xml) {}
	 }); //close $.ajax
}

function gameAddMorePhoto()
{
	$("input.subphoto:last").after('<br />Photo: <input type="file" name="fphoto[]" class="subphoto text-input">');	
}



