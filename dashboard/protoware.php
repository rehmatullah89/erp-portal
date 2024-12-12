<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2008-15 (C) Triple Tree                                                        **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
</head>

<body style="margin:0px; background:#ffffff;">

<div>
  <table border="0" cellspacing="0" cellpadding="10" width="100%">
    <tr valign="top">
      <td width="54%">
        <div class="tblSheet">
          <h2 style="margin:0px;">Protoware Dashboard</h2>

		  <h2 style="background:#a1a1a1; padding:0px; height:25px; line-height:25px;">
			<span style="background:#777777; display:block; padding:0px 10px 0px 10px; margin-right:15px; float:left; height:25px; line-height:25px; ">STYLE <span id="StyleX">X</span> of <span id="StyleY">Y</span> Styles in SEASON <span id="SeasonX">X</span></span> <span id="Approved">X</span>
			STYLES APPROVED FOR PRODUCTION
		  </h2>

		  <div id="timeline">
			<div id="protoware"></div>
		  </div>
		</div>


		<script type="text/javascript" src="scripts/html5.js"></script>
		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script type="text/javascript" src="scripts/timeline/js/storyjs-embed.js"></script>

<?
	$iPageSize   = PAGING_SIZE;
	$sConditions = " WHERE m.style_id=s.id AND m.id=c.merchandising_id ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s", $sConditions, $iPageSize, 1);
?>
		<script type="text/javascript">
		<!--
			var iSlide = -1;
			var iPage  = 1;
			var iInterval;
			var bStart = false;


			jQuery.noConflict( );

			jQuery(document).ready(function($)
			{
				function showTimeline(iPageId, sStyle)
				{
					iSlide = -1;

					createStoryJS(
					{
						type               :  'timeline',
						width              :  '100%',
						height             :  '650',
						source             :  'dashboard/protoware.json.php',
						embed_id           :  'protoware',
						debug              :  true,
						hash_bookmark      :  true,
						start_at_end       :  ((bStart == false) ? true : false),
						start_zoom_adjust  :  -2
					});


					iInterval = setInterval(function( )
					{
						if (jQuery(".slider-item").length > 0 && iSlide >= 0 && jQuery("#Item" + iSlide).find(".caption").text( ) != "")
						{
							clearInterval(iInterval);

							setStatus( );
						}
					}, 1000);



					jQuery(".nav-next, .nav-previous, .marker").live("click", function( )
					{
						setStatus( );

						if (iSlide == 0 && iPage < <?= $iPageCount ?>)
						{
							if (jQuery(".marker").length == 51)
							{
								iPage ++;

								jQuery("#timeline #protoware div").remove( );

								bStart = false;

								showTimeline(iPage, "");
							}
						}

						else if (iSlide == 50 && iPage > 1)
						{
							iPage --;

							jQuery("#timeline #protoware div").remove( );

							bStart = true;

							showTimeline(iPage, "");
						}

						else
						{
							setTimeout(setStatus, 1000);
						}
					});


					jQuery("img.media-image").live("click", function( )
					{
						var sImage = jQuery(this).attr("src");
						var sTitle = jQuery(this).parent( ).parent( ).parent( ).parent( ).parent( ).find(".container h3 a").text( );

						if (sImage.indexOf("default.jpg") == -1)
						{
							Lightview.show({ href    : sImage,
											 rel     : "image",
											 title   : sTitle,
											 options :  { autosize:true, topclose:false }
										   });
						}
					});


					function setStatus( )
					{
						var sInfo  = jQuery("#Item" + iSlide).find(".caption").text( ).split("|");

						if (sInfo[0] == "Accepted")
							jQuery("#Item" + iSlide).find("div.media-image img").css("border-right", "solid 10px #00ff01");

						else if (sInfo[0] == "Rejected")
							jQuery("#Item" + iSlide).find("div.media-image img").css("border-right", "solid 10px #ff3f3e");

						else
							jQuery("#Item" + iSlide).find("div.media-image img").css("border-right", "solid 10px #666666");


						jQuery("#SeasonX").html(sInfo[1]);
						jQuery("#StyleX").html(sInfo[2]);
						jQuery("#StyleY").html(sInfo[3]);
						jQuery("#Approved").html(sInfo[4]);
					}


					jQuery("#timeline h3 .comments").live("click", function( )
					{
						var iStyle = jQuery(this).attr("rel");

						Lightview.show({ href     : ("sampling/view-style-comments.php?Id=" + iStyle),
										 rel      : "iframe",
										 title    :  "Style Comments",
										 caption  :  "",
										 options  :  { autosize:true, topclose:false, width:800, height:600 }
									   });
					});


					jQuery("#timeline a.style").live("click", function( )
					{
							var sStyle = $(this).attr("rel");


							iPage = 1;

							jQuery("#timeline #protoware div").remove( );

							bStart = false;

							showTimeline(iPage, sStyle);

							return false;
					});
				}


				showTimeline(1, "");
			});
		-->
		</script>
	    </div>
      </td>




      <td width="46%">
		<div class="tblSheet" style="position:relative; margin-bottom:10px; height:955px; overflow-x:hidden; overflow-y:scroll;">
		  <h2>Styles Listing</h2>

		  <div style="padding:0px 0px 0px 5px;">
<?
	$sSQL = "SELECT id, style, sketch_file FROM tbl_styles WHERE sketch_file != '' ORDER BY RAND( ) LIMIT 50";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			      <table border="0" cellpadding="5" cellspacing="0" width="100%">
<?
	for ($iIndex = 0, $i = 0; $iIndex < 4; $iIndex ++)
	{
?>
				    <tr>
<?
		for ($j = 0; $j < 7; $j ++)
		{
			if (@in_array($j, array(1,3,5)))
			{
?>
				      <td width="1"></td>
<?
				continue;
			}
?>
				      <td width="155">
<?
			if ($i < $iCount)
			{
				$iId         = $objDb->getField($i, 'id');
				$sStyle      = $objDb->getField($i, 'style');
				$sSketchFile = $objDb->getField($i, 'sketch_file');

				if ($sSketchFile == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
				{
					$i ++;
					$j --;

					continue;
				}

				if (!@file_exists($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile))
					createImage(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

				$sSketchFile = (STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
?>
				        <center>
				          <img src="<?= $sSketchFile ?>" width="155" height="135" vspace="4" alt="" title="" style="border:solid 1px #cccccc;" /><br />
				          <b><?= $sStyle ?></b><br />
				        </center>
<?
				$i ++;
			}
		}
?>
				    </tr>
<?
		if ($i < $iCount)
		{
?>
				    <tr>
				      <td colspan="7" height="10"></td>
				    </tr>
<?
		}
	}
?>
			      </table>
		  </div>
		</div>

      </td>
    </tr>
  </table>
</div>


<div id="Processing" style="display:none;">
  <img src="images/loading.gif" alt="Processing..." title="Processing..." />
  Processing your request...
</div>

<div id="UserMessage" style="display:none;">
  User Message
</div>


</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>