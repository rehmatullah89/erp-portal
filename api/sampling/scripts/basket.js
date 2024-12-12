
$(document).ready(function( )
{
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
		{
			$("#RemoveFromBasket").removeClass("hidden");
			$("#SendRequest").removeClass("hidden");
		}
		
		else
		{
			$("#RemoveFromBasket").addClass("hidden");
			$("#SendRequest").addClass("hidden");
		}
	});
	
	
	$("#RemoveFromBasket a").click(function( )
	{
		var sStyles = "0";
		
		$("input.style").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
				sStyles = (sStyles + "," + $(this).val( ));
		});


		document.location = ($(this).attr("href") + "&Action=Remove&Styles=" + sStyles);
	
		return false;
	});
	
	
	$("#SendRequest img").click(function( )
	{
		var sStylesId = "0";
		var sStylesNo = "";
		
		$("input.style").each(function(iIndex)
		{
			if ($(this).prop("checked") == true)
			{
				sStylesId = (sStylesId + "," + $(this).val( ));
				
				
				if (sStylesNo != "")
					sStylesNo = (sStylesNo + ", ");
				
				sStylesNo = (sStylesNo + $(this).parent( ).attr("rel"));
			}
		});


		Android.sendRequest($("#User").val( ), sStylesId, sStylesNo);
	});	
});


var myScroll;

function loaded( )
{
	myScroll = new iScroll('StylesListing', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:true, hideScrollbar:false });
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
