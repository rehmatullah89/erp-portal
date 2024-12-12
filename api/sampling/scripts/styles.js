
$(document).ready(function( )
{
	$("#Seasons").dropdown( );
	
	$("input.sortby").click(function( )
	{
		document.location = $(this).val( );
	});
	
	
	$("input.style").click(function( )
	{
		var bFlag = false;
		
		$("input.style").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				if ($(this).prop("disabled") == false)
				{
					$(this).parent( ).css("background", "#65d270").find("span").text("selected");
				
					bFlag = true;
				}
			}
			
			else
				$(this).parent( ).css("background", "#dddddd").find("span").text("select");
		});
		

		if (bFlag == true)
			$("#AddToBasket").removeClass("hidden");
		
		else
			$("#AddToBasket").addClass("hidden");
	});
	
	
	$("#AddToBasket a").click(function( )
	{
		var sStyles = "0";
		
		$("input.style").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				sStyles = (sStyles + "," + $(this).val( ));
		});


		document.location = ($(this).attr("href") + "&Action=Add&Styles=" + sStyles);
	
		return false;
	});
});


var myScroll;

function loaded( )
{
	myScroll = new iScroll('StylesListing', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:true, hideScrollbar:false });
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
