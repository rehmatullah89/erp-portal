
var objStyle;
var objComments;
var objSpecs;

function loaded( )
{
	objStyle = new iScroll('StylesDetails', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:true, hideScrollbar:false });
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);


$(document).ready(function( )
{
	$("#Show").click(function( )
	{
		$(".popup.add").hide( );
		$(".popup.specs").hide( );
		$(".popup.show").toggle( );
		
		if (!objComments)
			objComments = new iScroll('ShowComments', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:true, hideScrollbar:false });
	});
	

	$("#Add").click(function( )
	{
		$(".popup.show").hide( );
		$(".popup.specs").hide( );
		$(".popup.add").toggle( );
	});
	
	$("#Specs").click(function( )
	{
		$(".popup.add").hide( );
		$(".popup.show").hide( );
		$(".popup.specs").toggle( );
		
		if (!objSpecs)
			objSpecs = new iScroll('MeasurementSpecs', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:false, hideScrollbar:true });
	});
	
	
	$(".popup.show, .popup.specs").click(function( )
	{
		$(this).hide( );
	});	
	
	$("#BtnCancel").click(function( )
	{
		$(".popup").hide( );
	});	
	
	
	$("#frmComments").submit(function( )
	{		
		if ($("#frmComments #From").val( ) == "")
		{
			$("#frmComments #From").css("border", "solid 1px #ff0000");
			
			return false;
		}
		
		else
			$("#frmComments #From").css("border", "solid 1px #aaaaaa");
			
			
		if ($("#frmComments #Comments").val( ) == "")
		{
			$("#frmComments #Comments").css("border", "solid 1px #ff0000");
			
			return false;
		}
		
		else
			$("#frmComments #Comments").css("border", "solid 1px #aaaaaa");
		
		
		return true;
	});
});