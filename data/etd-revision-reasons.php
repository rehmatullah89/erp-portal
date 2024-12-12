<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	$objDb3      = new Database( );

	$PageId = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Reason = IO::strValue("Reason");
	$Parent = IO::strValue("Parent");
	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Code   = IO::strValue("Code");
		$Reason = IO::strValue("Reason");
		$Parent = IO::strValue("Parent");
	}


	$sReasons = array( );

	$sSQL = "SELECT id, code, reason FROM tbl_etd_revision_reasons WHERE parent_id='0' ORDER BY reason";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId     = $objDb->getField($i, 'id');
		$sCode   = $objDb->getField($i, 'code');
		$sReason = $objDb->getField($i, 'reason');

		$sReasons[$iId]['Reason'] = $sReason;
		$sReasons[$iId]['Code']   = $sCode;
		$sReasons[$iId]['Parent'] = 0;
		$sParent                  = $sReason;
		$iParent                  = $iId;


		$sSQL = "SELECT id, code, reason FROM tbl_etd_revision_reasons WHERE parent_id='$iId' ORDER BY reason";
		$objDb2->query($sSQL);

		$iCount2 =$objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iId     = $objDb2->getField($j, 'id');
			$sCode   = $objDb2->getField($j, 'code');
			$sReason = $objDb2->getField($j, 'reason');

			$sReasons[$iId]['Reason'] = ($sParent.' � '.$sReason);
			$sReasons[$iId]['Code']   = $sCode;
			$sReasons[$iId]['Parent'] = $iParent;
			$sSubParent               = $sReason;
			$iSubParent               = $iId;


			$sSQL = "SELECT id, code, reason FROM tbl_etd_revision_reasons WHERE parent_id='$iId' ORDER BY reason";
			$objDb3->query($sSQL);

			$iCount3 =$objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iId     = $objDb3->getField($k, 'id');
				$sCode   = $objDb3->getField($k, 'code');
				$sReason = $objDb3->getField($k, 'reason');

				$sReasons[$iId]['Reason'] = ($sSubParent.' � '.$sParent.' � '.$sReason);
				$sReasons[$iId]['Code']   = $sCode;
				$sReasons[$iId]['Parent'] = $iSubParent;
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/etd-revision-reasons.js"></script>
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
			    <h1>ETD Revision Reasons</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-etd-revision-reason.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Reason</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Parent</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Parent">
						<option value=""></option>
<?
		$sSQL = "SELECT id, reason FROM tbl_etd_revision_reasons WHERE parent_id='0' ORDER BY reason";
		$objDb->query($sSQL);

		$iCount =$objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId     = $objDb->getField($i, 'id');
			$sReason = $objDb->getField($i, 'reason');
?>
					    <option value="<?= $iId ?>"<?= (($iId == $Parent) ? " selected" : "") ?>><?= $sReason ?></option>
<?
			$sParent = $sReason;


			$sSQL = "SELECT id, reason FROM tbl_etd_revision_reasons WHERE parent_id='$iId' ORDER BY reason";
			$objDb2->query($sSQL);

			$iCount2 =$objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iId     = $objDb2->getField($j, 'id');
				$sReason = $objDb2->getField($j, 'reason');
?>
					    <option value="<?= $iId ?>"<?= (($iId == $Parent) ? " selected" : "") ?>><?= $sParent ?> � <?= $sReason ?></option>
<?
			}
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Reason<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Reason" value="<?= $Reason ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Reason Code<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Code" value="<?= $Code ?>" maxlength="2" size="10" class="textbox" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="54">Reason</td>
			          <td width="160"><input type="text" name="Reason" value="<?= $Reason ?>" class="textbox" maxlength="50" /></td>

			          <td width="50">Parent</td>

			          <td>
					    <select name="Parent">
						  <option value="">All Reasons</option>
<?
	$sSQL = "SELECT id, reason FROM tbl_etd_revision_reasons WHERE parent_id='0' ORDER BY reason";
	$objDb->query($sSQL);

	$iCount =$objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId     = $objDb->getField($i, 'id');
		$sReason = $objDb->getField($i, 'reason');
?>
					    <option value="<?= $iId ?>"<?= (($iId == $Parent) ? " selected" : "") ?>><?= $sReason ?></option>
<?
		$sParent = $sReason;


		$sSQL = "SELECT id, reason FROM tbl_etd_revision_reasons WHERE parent_id='$iId' ORDER BY reason";
		$objDb2->query($sSQL);

		$iCount2 =$objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iId     = $objDb2->getField($j, 'id');
			$sReason = $objDb2->getField($j, 'reason');
?>
					    <option value="<?= $iId ?>"<?= (($iId == $Parent) ? " selected" : "") ?>><?= $sParent ?> � <?= $sReason ?></option>
<?
		}
	}
?>
					    </select>
			          </td>


			          <td width="103"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Reason != "")
		$sConditions .= " AND (code LIKE '%$Reason%' OR title LIKE '%$Reason%') ";

	if ($Parent != "")
		$sConditions .= " AND parent_id='$Parent' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_etd_revision_reasons", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_etd_revision_reasons $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="6%">#</td>
				      <td width="10%">Code</td>
				      <td width="30%">Reason</td>
				      <td width="46%">Parent</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId     = $objDb->getField($i, 'id');
		$iParent = $objDb->getField($i, 'parent_id');
		$sCode   = $objDb->getField($i, 'code');
		$sReason = $objDb->getField($i, 'reason');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="10%"><span id="Code<?= $iId ?>"><?= $sReasons[$sReasons[$iParent]['Parent']]['Code'] ?><?= $sReasons[$iParent]['Code'] ?><?= $sCode ?></span></td>
				      <td width="30%"><span id="Reason<?= $iId ?>"><?= $sReason ?></span></td>
				      <td width="46%"><span id="Parent<?= $iId ?>"><?= $sReasons[$iParent]['Reason'] ?></span></td>

				      <td width="10%" class="center">
<?
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
				        <a href="data/delete-etd-revision-reason.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Reason?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="90">Parent</td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Parent">
							  <option value=""></option>
<?
		$sSQL = "SELECT id, reason FROM tbl_etd_revision_reasons WHERE parent_id='0' ORDER BY reason";
		$objDb2->query($sSQL);

		$iCount2 =$objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sKey   = $objDb2->getField($j, 'id');
			$sValue = $objDb2->getField($j, 'reason');
?>
					          <option value="<?= $sKey ?>"<?= (($sKey == $iParent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			$sParent = $sValue;


			$sSQL = "SELECT id, reason FROM tbl_etd_revision_reasons WHERE parent_id='$sKey' ORDER BY reason";
			$objDb3->query($sSQL);

			$iCount3 =$objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$sKey   = $objDb3->getField($k, 'id');
				$sValue = $objDb3->getField($k, 'reason');
?>
					          <option value="<?= $sKey ?>"<?= (($sKey == $iParent) ? " selected" : "") ?>><?= $sParent ?> � <?= $sValue ?></option>
<?
			}
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Reason<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Reason" value="<?= $sReason ?>" maxlength="100" size="30" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Reason Code<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $sCode ?>" maxlength="2" size="10" class="textbox" /></td>
					    </tr>

						<tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Reason Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Reason={$Reason}&Parent={$Parent}");
?>

				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."data/export-etd-revision-reasons.php") ?>" />
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
				</div>

<?
	if ($_SESSION['Guest'] != "Y")
	{
?>
		        <hr />

			    <form name="frmEmail" id="frmEmail" method="post" action="data/email-etd-revision-reasons.php" class="frmOutline" onsubmit="$('BtnEmail').disabled=true;">
				<h2>Email Reasons File</h2>

			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="35">User</td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select name="User">
					    <option value=""></option>
<?
		$sSQL = "SELECT id, name, email FROM tbl_users WHERE status='A' ORDER BY name";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iUserId = $objDb->getField($i, "id");
			$sName   = $objDb->getField($i, "name");
			$sEmail  = $objDb->getField($i, "email");
?>
			              <option value="<?= $iUserId ?>"><?= $sName ?> &lt;<?= $sEmail ?>&gt;</option>
<?
		}
?>
					  </select>
				    </td>
				  </tr>
			    </table>

			    <br />

			    <div class="buttonsBar"><input type="submit" id="BtnEmail" value="" class="btnSubmit" title="Email" onclick="return validateEmailForm( );" /></div>
			    </form>
<?
	}
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>