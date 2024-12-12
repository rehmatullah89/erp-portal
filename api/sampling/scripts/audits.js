
var objAudits;
var objPicture;
var objComments;
var objSpecs;

function loaded( )
{
	objAudits = new iScroll('Audits', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:true, hideScrollbar:false });
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);


$(document).ready(function( )
{
	$("ul.audits li a").click(function( )
	{
		var sAuditId = $(this).parent( ).attr("id");

		$("ul.audits li div.pictures").each(function( )
		{
			if ($(this).css("display") == "block" && $(this).parent( ).attr("id") != sAuditId)
				$(this).hide("slide");
		});
		
		
		if ($(this).parent( ).find("div.pictures").css("display") != "block")
		{
			$("#Measurements").show( );		
			$("#MeasurementSpecs #Scroller").html($(this).parent( ).find("div.specs").html( ));
		}
		
		else
		{
			$("#Measurements").hide( );
		}
		
		$(this).parent( ).find("div.pictures").toggle("slide");
		
		setTimeout(function( ) { objAudits.refresh( ); }, 500);

		return false;
	});
	
	
	
	$(".popup.picture #Picture img").load(function( )
	{
		if (!objPicture)
			objPicture = new iScroll('Picture', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:false, hideScrollbar:true });

		objPicture.refresh( );
	});
	
	
	$("ul.audits li img").click(function( )
	{
		var sPicture = $(this).attr("src");

		$(".popup").hide( );
		$(".popup.picture").show( );
		
		$(".popup.picture img").attr("src", sPicture);
	});
	
	
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
	
	
	$(".popup.show, .popup.specs").click(function( )
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
	
	
	$("#Specs").click(function( )
	{
		$(".popup.add").hide( );
		$(".popup.show").hide( );
		$(".popup.specs").toggle( );

		if (!objSpecs)
			objSpecs = new iScroll('MeasurementSpecs', { hScroll:true, hScrollbar:false, vScroll:true, vScrollbar:false, hideScrollbar:true });
			
		objSpecs.refresh( );
	});
});