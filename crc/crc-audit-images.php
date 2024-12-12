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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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

	$AuditId    = IO::intValue('AuditId');
        $PSectionId = getDbValue("section_id", "tbl_crc_audits", "id='$AuditId'");
        $BrandId    = getDbValue("brand_id", "tbl_crc_audits", "id='$AuditId'");
        $Points     = getDbValue("points", "tbl_crc_audits", "id='$AuditId'");
	$Referer    = urldecode(IO::strValue("Referer"));
        
        $Section  = IO::intValue("Section");
        $Category = IO::intValue("Category");
	$Point    = IO::intValue("Point");
        
        $sParentSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='0'");
        $sCategoriesList  = getList("tbl_tnc_categories", "DISTINCT id", "DISTINCT category", "section_id='$PSectionId'");
        
        $sCondition = (($Section > 0) ? "section_id='$Section'" : "");
        
        if(!empty($sAnd))
            $sCondition .= (($Category > 0) ? " AND category_id='$Category'" : "");
        
        $sPointsList = getList("tbl_tnc_points", "id", "point", $sCondition);
        
	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
    <script type="text/javascript" src="scripts/crc/crc-audit-images.js"></script>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/crc/tnc-audit-images.jpg" width="245" height="20" alt="" title="" vspace="10" /></h1>

			    <div class="tblSheet">
			      <br />

<?
	$sAuditDate = getDbValue("audit_date", "tbl_crc_audits", "id='$AuditId'");


	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

	$sTncDir = (TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");



	$sSQL = "SELECT id, title, point_id, picture FROM tbl_crc_audit_pictures WHERE audit_id='$AuditId'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount == 0)
	{
?>
				  <div class="noRecord">No Audit Image Found!</div>
				  <br />
<?
	}

	else
	{
?>
				  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="qaImages">
<?
		for ($i = 0; $i < $iCount;)
		{
?>
                    <tr valign="top">
<?
			for ($j = 0; $j < 5; $j ++, $i ++)
			{
				if ($i < $iCount)
				{
					$iImageId = $objDb->getField($i, "id");
                                        $iPointId = $objDb->getField($i, "point_id");
					$sTitle   = $objDb->getField($i, "title");
					$sPicture = $objDb->getField($i, "picture");
?>
			  		  <td width="20%" align="center">
                        <div class="qaPic">
                          <div><a href="<?= ($sTncDir.$sPicture) ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= ($sTncDir.$sPicture) ?>" alt="" title="" /></a></div>
			    		</div>

						<span id="Pic<?= $i ?>" name="Pic<?= $i ?>"><?= $sTitle ?></span><br />
						<br />

                        <div>
<?
					if ($sUserRights['Edit'] == "Y")
					{
?>
						<a href="./" id="Edit<?= $i ?>" onclick="objEditor<?= $i ?>.enterEditMode( ); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
		          	    &nbsp;
<?
					}

					if ($sUserRights['Delete'] == "Y")
					{
?>
					    <a id="Delete<?= $i ?>" href="crc/delete-crc-audit-image.php?File=<?= $sPicture ?>&ImageId=<?= $iImageId ?>&AuditId=<?= $AuditId ?>&AuditDate=<?= $sAuditDate ?>&Referer=<?= urlencode($Referer) ?>" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
					}
?>
					  </div>

<?
					if ($sUserRights['Edit'] == "Y")
					{
?>
					  <script type="text/javascript">
						<!--
							var objEditor<?= $i ?> = new Ajax.InPlaceEditor('Pic<?= $i ?>', 'ajax/crc/update-crc-audit-image.php', { cancelControl:'button', okText:' Ok ', cancelText:'Cancel', clickToEditText:'Click to Edit Title', highlightcolor:'#f1edcd', highlightendcolor:'#ffffff', callback:function(form, value) { return 'Id=<?= $iImageId ?>&Title=' + encodeURIComponent(value); }, onComplete:function( ) { }, onEnterEditMode:function(form, value) { $('Pic<?= $i ?>').focus( ); } });
						-->
					  </script>
<?
					}
?>
					</td>
<?
				}

				else
				{
?>
					<td width="20%"></td>
<?
				}
		}
?>
					</tr>

					<tr>
					  <td colspan="5"><hr /></td>
					</tr>
<?
		}
?>
	  			  </table>
<?
	}
?>
		  		  &nbsp; [ <b><a href="<?= $Referer ?>">Back</a></b> ]<br />
                  <br />
                </div>

	    		<br />

                <form name="frmData" id="frmData" method="post" action="crc/save-crc-audit-images.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
	            <input type="hidden" name="AuditId" value="<?= $AuditId ?>" />
	            <input type="hidden" name="AuditDate" value="<?= $sAuditDate ?>" />
	            <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<h2>Audit Images</h2>

<?
	if ($_SESSION['Message'] != "")
	{
?>
				<div class="error" style="padding:10px 10px 0px 10px;">
				  <?= $_SESSION['Message'] ?><br />
				</div>
				<hr />
<?
	}
?>
				<table border="0" cellpadding="2" cellspacing="0" width="280px">
                                  <tr>
				    <td width="70">Section</td>
				    <td width="20" align="center">:</td>

				    <td>
				      <select name="Section" id="Section" onchange="getCategoriesList(this.value, 'Category', '<?=$AuditId?>')">
				        <option value=""></option>
<?
		foreach ($sParentSectionsList as $iLabelKey => $sLabel)
		{
                            $sBrandSections   = getList("tbl_tnc_points", "DISTINCT section_id", "section_id", "FIND_IN_SET('$BrandId', brands)");
                            $sSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='$iLabelKey' AND id IN (". implode(",", $sBrandSections).")");
?>
                        <optgroup label="<?php echo $sLabel; ?>">
                        <?
                            foreach ($sSectionsList as $sKey => $sValue)
                            {?>
                                <option value="<?= $sKey ?>"<?= (($sKey == $Section) ? " selected" : "") ?>><?= $sValue ?></option>
<?                          }
                        ?>        
                        </optgroup>                            
			            
<?
		}
?>
					  </select>
                    </td>
				  </tr>

				  <tr>
					<td>Category</td>
					<td align="center">:</td>

					<td>
					  <select name="Category" id="Category" onchange="getPointsList(this.value, 'Point', '<?=$AuditId?>')">
						<option value=""></option>
<?
		if ($Section > 0)
		{
			foreach ($sCategoriesList as $sKey => $sValue)
			{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
		}
?>
					  </select>
					</td>
				  </tr>
                                  <tr>
					<td>Points</td>
					<td align="center">:</td>

					<td>
					  <select name="Point" id="Point">
						<option value=""></option>
<?
		if ($Category > 0)
		{
			foreach ($sPointsList as $sKey => $sValue)
			{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $point) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
		}
?>
					  </select>
					</td>
				  </tr>
				  <tr>
				    <td width="70">Image # 1</td>
				    <td width="20" align="center">:</td>
				    <td><input type="file" name="Image1" size="40" class="file" /></td>
				  </tr>

				  <tr>
				    <td>Image # 2</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image2" size="40" class="file" /></td>
				  </tr>

				  <tr>
				    <td>Image # 3</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image3" size="40" class="file" /></td>
				  </tr>

				  <tr>
				    <td>Image # 4</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image4" size="40" class="file" /></td>
				  </tr>

				  <tr>
				    <td>Image # 5</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image5" size="40" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save"  onclick="return validateForm( );" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
				</div>
			    </form>

			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$_SESSION['Message'] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>