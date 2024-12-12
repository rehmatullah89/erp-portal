
var objStyle;
var objPicture;
var objComments;

function loaded( )
{
	objStyle = new iScroll('StylesDetails', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:true, hideScrollbar:false });
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);


$(document).ready(function( )
{
	$(".popup.picture #Picture img").load(function( )
	{
		if (!objPicture)
			objPicture = new iScroll('Picture', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:false, hideScrollbar:true });

		objPicture.refresh( );
	});
	
	
	$("#Show").click(function( )
	{
		$(".popup.add").hide( );
		$(".popup.show").toggle( );
		
		if (!objComments)
			objComments = new iScroll('ShowComments', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:true, hideScrollbar:false });
	});
	

	$("#Add").click(function( )
	{
		$(".popup.show").hide( );
		$(".popup.add").toggle( );
	});
	
	
	$(".popup.show").click(function( )
	{
		$(this).hide( );
	});	
	
	$("#BtnCancel, .popup.picture").click(function( )
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
	
	
	$("#Zoom").click(function( )
	{
		var sPicture = $("#View360 img").attr("src").replace("thumbs/", "originals/");

		$(".popup").hide( );
		$(".popup.picture").show( );
		
		$(".popup.picture img").attr("src", sPicture);
	});
	

	$("#Rotate").click(function( )
	{
		$("#View360").spritespin("animate", true);
	});


	$("#Next").click(function( )
	{
		$("#View360").spritespin("frame", ($("#View360").spritespin("frame") + 1));
	});


	$("#Back").click(function( )
	{
		$("#View360").spritespin("frame", ($("#View360").spritespin("frame") - 1));
	});
});