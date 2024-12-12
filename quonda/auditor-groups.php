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

	$PageId  = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Name    = IO::strValue("Name");
	$Auditor = IO::intValue("Auditor");
	$PostId  = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Name     = IO::strValue("Name");
		$Code     = IO::strValue("Code");
		$Auditors = IO::getArray("Auditors");
	}

	if ($PageId == 1 && $AuditCode == 0 && $Auditor == 0 && $Vendor == 0 && $Region == 0 && ($FromDate == "" || $ToDate == ""))
	{
		$FromDate = date("Y-m-d");
		$ToDate   = date("Y-m-d");
	}

	$sAuditorsList    = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
	$sAllAuditorsList = getList("tbl_users", "id", "name", "auditor='Y'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/auditor-groups.js"></script>
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
			    <h1>auditor groups</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-auditor-group.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Auditors Group</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Group Name<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Name" value="<?= $Name ?>" size="26" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Group Code<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Code" value="<?= $Code ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Auditors<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select id="Auditors" name="Auditors[]" multiple size="10">
<?
		foreach ($sAuditorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Auditors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
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
			          <td width="45">Group</td>
			          <td width="165"><input type="text" name="Name" value="<?= $Name ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td width="55">Auditor</td>

			          <td width="200">
					    <select name="Auditor">
						  <option value="">All Auditors</option>
<?
	foreach ($sAllAuditorsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Auditor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
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

	if ($Name != "")
		$sConditions .= " AND (name LIKE '%$Name%' OR code LIKE '%$Name%') ";

	if ($Auditor > 0)
		$sConditions .= " AND FIND_IN_SET(users, '$Auditor') ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_auditor_groups", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, name, code, users, date_time,
	                (SELECT COUNT(1) FROM tbl_qa_reports WHERE group_id=tbl_auditor_groups.id) AS _Audits
	         FROM tbl_auditor_groups
	         $sConditions
	         ORDER BY id DESC
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="25%">Group Name</td>
				      <td width="15%">Group Code</td>
				      <td width="30%">Auditors</td>
				      <td width="14%">Creation Date</td>
				      <td width="8%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$sName     = $objDb->getField($i, 'name');
		$sCode     = $objDb->getField($i, 'code');
		$sUsers    = $objDb->getField($i, 'users');
		$sDateTime = $objDb->getField($i, 'date_time');
		$iAudits   = $objDb->getField($i, '_Audits');

		$iAuditors = @explode(",", $sUsers);
		$sAuditors = "";

		for ($j = 0; $j < count($iAuditors); $j ++)
			$sAuditors .= ($sAllAuditorsList[$iAuditors[$j]]."<br />");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="25%"><span id="Name_<?= $iId ?>"><?= $sName ?></span></td>
				      <td width="15%"><span id="Code_<?= $iId ?>"><?= $sCode ?></span></td>
				      <td width="30%"><span id="Auditors_<?= $iId ?>"><?= $sAuditors ?></span></td>
				      <td width="14%"><?= formatDate($sDateTime) ?></span></td>

				      <td width="8%" class="center">
<?
		if ($sUserRights['Edit'] == "Y" && $iAudits == 0)
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y" && $iAudits == 0)
		{
?>
				        <a href="quonda/delete-auditor-group.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Auditors Group?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="90">Group Name<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Name" value="<?= $sName ?>" size="26" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Group Code<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $sCode ?>" size="10" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Auditors<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select id="Auditors<?= $iId ?>" name="Auditors" multiple size="10">
<?
		foreach ($sAuditorsList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iAuditors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
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
				      <td class="noRecord">No Auditor Group Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Name={$Name}&Auditor={$Auditor}");
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