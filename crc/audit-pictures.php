<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Vendor   = IO::intValue("Vendor");
	$Category = IO::strValue("Category");
	$Title    = IO::strValue("Title");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Vendor   = IO::intValue("Vendor");
		$Category = IO::strValue("Category");
		$Question = IO::strValue("Question");
		$Date     = IO::strValue("Date");
		$Title    = IO::strValue("Title");
		$Picture  = IO::strValue("Picture");
	}


	$sVendorsList      = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sCategoriesList   = getList("tbl_safety_categories", "id", "title");
	$sQuestionsList    = getList("tbl_safety_questions", "id", "title", "category_id='$Category'");
	$sAllQuestionsList = getList("tbl_safety_questions", "id", "title");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/audit-pictures.js"></script>
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
			    <h1>Audit Pictures</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-audit-picture.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Picture</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
			     <tr>
					<td width="60">Vendor</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Vendor">
						<option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
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
					  <select name="Category" id="Category" onchange="getQuestionsList('');">
					    <option value=""></option>
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Question</td>
					<td align="center">:</td>

					<td>
					  <select name="Question" id="Question">
					    <option value=""></option>
<?
		foreach ($sQuestionsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Question) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Audit Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="AuditDate" id="AuditDate" value="<?= IO::strValue("AuditDate") ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr valign="top">
					<td>Title</td>
					<td align="center">:</td>
					<td><input type="text" name="Title" value="<?= $Title ?>" size="40" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Picture</td>
					<td align="center">:</td>
					<td><input type="file" name="Picture" value="" size="27" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>


		        <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="70">Keywords</td>
			          <td width="180"><input type="text" name="Title" value="<?= $Title ?>" class="textbox" maxlength="250" /></td>

			          <td width="52">Vendor</td>

			          <td width="180">
			            <select name="Vendor" style="width:180px;">
			              <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>


			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="65">Category</td>

			          <td width="400">
					    <select name="Category">
						  <option value=""></option>
<?
	foreach ($sCategoriesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td></td>
				    </tr>
				  </table>
			    </div>
			    </form>


			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Vendor > 0)
		$sSQL .= " WHERE vendor_id='$Vendor' ";

	else
		$sSQL .= " WHERE vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Category > 0)
		$sConditions .= " AND category_id='$Category' ";

	if ($Title != "")
		$sConditions .= " AND title LIKE '%$Title%' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_audit_pictures", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_audit_pictures $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="20%">Title</td>
				      <td width="10%">Date</td>
				      <td width="15%">Vendor</td>
				      <td width="20%">Question</td>
				      <td width="20%">Category</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$sTitle     = $objDb->getField($i, 'title');
		$iVendor    = $objDb->getField($i, 'vendor_id');
		$iCategory  = $objDb->getField($i, 'category_id');
		$iQuestion  = $objDb->getField($i, 'question_id');
		$sAuditDate = $objDb->getField($i, 'date');
		$sPicture   = $objDb->getField($i, 'picture');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="20%"><?= $sTitle ?></td>
				      <td width="10%"><?= formatDate($sAuditDate) ?></td>
				      <td width="15%"><?= $sVendorsList[$iVendor] ?></td>
				      <td width="20%"><?= $sAllQuestionsList[$iQuestion] ?></td>
				      <td width="20%"><?= $sCategoriesList[$iCategory] ?></td>

				      <td width="10%" class="center">
<?
		if ($sPicture != "" && @file_exists($sBaseDir.CRC_AUDITS_IMG_PATH.$sPicture))
		{
?>
				        <a href="<?= CRC_AUDITS_IMG_PATH.$sPicture ?>" class="lightview" title="<?= $sCategory ?>"><img src="images/icons/thumb.gif" width="16" height="16" alt="Picture" title="Picture" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="crc/delete-audit-picture.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Picture?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" method="post" action="crc/update-audit-picture.php" enctype="multipart/form-data" class="frmInlineEdit" onsubmit="$('BtnSave<?= $iId ?>').disabled=true;">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />
					  <input type="hidden" name="OldPicture" value="<?= $sPicture ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
						  <td width="60">Vendor</td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Vendor">
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iVendor) ? " selected" : "") ?>><?= $sValue ?></option>
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
						    <select name="Category" id="Category<?= $iId ?>" onchange="getQuestionsList('<?= $iId ?>');">
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iCategory) ? " selected" : "") ?>><?= $sValue ?></option>
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
						    <select name="Question" id="Question<?= $iId ?>">
<?
		$sQuestionsList = getList("tbl_safety_questions", "id", "title", "category_id='$iCategory'");

		foreach ($sQuestionsList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iCategory) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>

					    <tr valign="top">
						  <td>Audit Date</td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="AuditDate" id="AuditDate<?= $iId ?>" value="<?= $sAuditDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
						    </table>

						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Title</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Title" value="<?= $sTitle ?>" size="40" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Picture</td>
						  <td align="center">:</td>
						  <td><input type="file" name="Picture" value="" size="27" class="file" /></td>
					    </tr>

					    <tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" id="BtnSave<?= $iId ?>" value="SAVE" class="btnSmall" onclick="return validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Vendor Category Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Category={$Category}");
?>

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
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>