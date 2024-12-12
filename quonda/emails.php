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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Name       = IO::strValue("Name");
	$Vendor     = IO::strValue("Vendor");
	$Language   = IO::strValue("Language");
	$AuditStage = IO::strValue("AuditStage");
	$PostId     = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Name         = IO::strValue("Name");
		$Email        = IO::strValue("Email");
		$Language     = IO::strValue("Language");
		$Vendors      = IO::getArray("Vendors");
		$AuditStages  = IO::getArray("AuditStages");
		$AuditResults = IO::getArray("AuditResults");
	}


	$sLanguageList    = array('en' => 'English', 'zh' => 'Chinese', 'de' => 'German', 'tr' => 'Turkish');
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y' AND id IN ({$_SESSION['Vendors']})");
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
	$iAuditStagesList = getList("tbl_audit_stages", "id", "stage", "FIND_IN_SET(code, '$sAuditStages')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/emails.js"></script>
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
			    <h1>QA emails</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-email.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add QA Email</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="100">Name</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Name" value="<?= $Name ?>" size="26" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Email</td>
					<td align="center">:</td>
					<td><input type="text" name="Email" value="<?= $Email ?>" size="26" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Vendor(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendors[]" id="Vendors" multiple size="10" style="min-width:200px;">
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Vendors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('Vendors'); return false;">Select All</a> | <a href="./" onclick="clearAll('Vendors'); return false;">Clear</a> ]<br />
					</td>
				  </tr>

				  <tr valign="top">
					<td>Audit Stage(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStages[]" id="AuditStages" multiple size="10" style="min-width:200px;">
<?
		foreach ($sAuditStagesList as $sKey => $sValue)
		{
			if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
				$sValue = "Firewall";

			if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
				 !@in_array($sKey, array("B", "C", "O", "F")) )
				continue;
?>
			              <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $AuditStages)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('AuditStages'); return false;">Select All</a> | <a href="./" onclick="clearAll('AuditStages'); return false;">Clear</a> ]<br />
					</td>
				  </tr>

				  <tr valign="top">
					<td>Audit Result(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditResults[]" id="AuditResults" multiple size="3" style="min-width:200px;">
						<option value="P"<?= ((@in_array("P", $AuditResults)) ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= ((@in_array("F", $AuditResults)) ? " selected" : "") ?>>Fail</option>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('AuditResults'); return false;">Select All</a> | <a href="./" onclick="clearAll('AuditResults'); return false;">Clear</a> ]<br />
					</td>
				  </tr>
                                    
                                  <tr valign="top">
					<td>Language</td>
					<td align="center">:</td>

					<td>
					  <select name="Language" id="Language" style="min-width:200px;">
<?
		foreach ($sLanguageList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Language)) ? " selected" : "") ?>><?= $sValue ?></option>
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
			          <td width="55">Vendor</td>

			          <td width="180">
					    <select name="Vendor">
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

			          <td width="45">Stage</td>

			          <td width="120">
			            <select name="AuditStage">
			              <option value="">All Stages</option>
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
			continue;
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

					  <td width="45">Name</td>
					  <td width="140"><input type="text" name="Name" value="<?= $Name ?>" size="15" class="textbox" /></td>
                                          
                                          <td width="70">Language</td>
                                          <td width="120">
					  <select name="Language" id="Language">
<?
		foreach ((array(''=>'All Languages')+$sLanguageList) as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Language) ? " selected" : "") ?>><?= $sValue ?></option>
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
	$sUserVendors = @explode(",", $_SESSION['Vendors']);
	$sUserStages  = @explode(",", $sAuditStages);	

	if ($Vendor > 0)
		$sConditions .= " AND FIND_IN_SET('$Vendor', vendors) ";

	else
	{
		$sConditions .= " AND (vendors='' ";
		
		foreach ($sUserVendors as $sVendor)
		{
			$sConditions .= " OR FIND_IN_SET('$sVendor', vendors) ";
		}
		
		$sConditions .= ") ";
	}

	if ($AuditStage != "")
		$sConditions .= " AND FIND_IN_SET('$AuditStage', audit_stages) ";

	else
	{
		$sConditions .= " AND (audit_stages='' ";
		
		foreach ($sUserStages as $sStage)
		{
			$sConditions .= " OR FIND_IN_SET('$sStage', audit_stages) ";
		}
		
		$sConditions .= ") ";
	}

	if ($Name != "")
		$sConditions .= (" AND (name LIKE '%".str_replace(" ", "%", $Name)."%' OR email LIKE '%".str_replace(" ", "%", $Name)."%') ");
	
	if ($Language != "")
		$sConditions .= " AND language = '$Language' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_qa_emails", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_qa_emails $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="20%">Name</td>
				      <td width="25%">Email</td>
				      <td width="22%">Vendor</td>
				      <td width="15%">Audit Stage</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId           = $objDb->getField($i, 'id');
		$sVendors      = $objDb->getField($i, 'vendors');
		$sAuditStages  = $objDb->getField($i, 'audit_stages');
		$sAuditResults = $objDb->getField($i, 'audit_results');
		$sName         = $objDb->getField($i, 'name');
		$sEmail        = $objDb->getField($i, 'email');
        $sLanguage     = $objDb->getField($i, 'language');

		$iVendors    = @explode(",", $sVendors);
		$sVendors    = "";
		$bEditDelete = true;

		for ($j = 0; $j < count($iVendors); $j ++)
		{
			if ($sVendorsList[$iVendors[$j]] == "")
			{
				$bEditDelete = false;
				
				continue;
			}
			
			$sVendors .= ("- ".$sVendorsList[$iVendors[$j]]."<br />");

			if ($j > 15)
				continue;
			
			else if ($j == 15)
				$sVendors .= "...";
		}


		$sAuditResults = @explode(",", $sAuditResults);
		
		if ($sAuditStages == "")
			$sAuditStages= "ALL";
		
		else
		{
			$iAuditStages  = @explode(",", $sAuditStages);
			$sAuditStages  = "";

			for ($j = 0; $j < count($iAuditStages); $j ++)
			{
				if ($iAuditStagesList[$iAuditStages[$j]] == "")
				{
					$bEditDelete = false;
					
					continue;
				}
				
				$sAuditStages .= ("- ".$sAuditStagesList[$iAuditStages[$j]]."<br />");
			}
		}		
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="20%"><span id="Name_<?= $iId ?>"><?= $sName ?></span></td>
				      <td width="25%"><span id="Email_<?= $iId ?>"><?= $sEmail ?></span></td>
				      <td width="22%"><span id="Vendors_<?= $iId ?>"><?= $sVendors ?></span></td>
				      <td width="15%"><span id="AuditStages_<?= $iId ?>"><?= $sAuditStages ?></span></td>

				      <td width="10%" class="center">
<?
		if ($sUserRights['Edit'] == "Y" && $bEditDelete == true)
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y" && $bEditDelete == true)
		{
?>
				        <a href="quonda/delete-email.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Email?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
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
						  <td width="100">Name</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Name" value="<?= $sName ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Email</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Email" value="<?= $sEmail ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Vendor(s)</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Vendors[]" id="Vendors<?= $iId ?>" multiple size="10" style="min-width:200px;">
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iVendors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>

						    <br />
						    <br style="line-height:3px;" />
						    [ <a href="./" onclick="selectAll('Vendors<?= $iId ?>'); return false;">Select All</a> | <a href="./" onclick="clearAll('Vendors<?= $iId ?>'); return false;">Clear</a> ]<br />
						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Audit Stage(s)</td>
						  <td align="center">:</td>

						  <td>
						    <select name="AuditStages[]" id="AuditStages<?= $iId ?>" multiple size="10" style="min-width:200px;">
<?
		foreach ($sAuditStagesList as $sKey => $sValue)
		{
			if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
				$sValue = "Firewall";

			if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
				 !@in_array($sKey, array("B", "C", "O", "F")) )
				continue;
?>
			              	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iAuditStages)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>

						    <br />
						    <br style="line-height:3px;" />
						    [ <a href="./" onclick="selectAll('AuditStages<?= $iId ?>'); return false;">Select All</a> | <a href="./" onclick="clearAll('AuditStages<?= $iId ?>'); return false;">Clear</a> ]<br />
						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Audit Result(s)</td>
						  <td align="center">:</td>

						  <td>
						    <select name="AuditResults[]" id="AuditResults<?= $iId ?>" multiple size="3" style="min-width:200px;">
							  <option value="P"<?= ((@in_array("P", $sAuditResults)) ? " selected" : "") ?>>Pass</option>
							  <option value="F"<?= ((@in_array("F", $sAuditResults)) ? " selected" : "") ?>>Fail</option>
						    </select>

						    <br />
						    <br style="line-height:3px;" />
						    [ <a href="./" onclick="selectAll('AuditResults<?= $iId ?>'); return false;">Select All</a> | <a href="./" onclick="clearAll('AuditResults<?= $iId ?>'); return false;">Clear</a> ]<br />
						  </td>
					    </tr>
                                           
                                   <tr valign="top">
					<td>Language</td>
					<td align="center">:</td>

					<td>
					  <select name="Language" id="Language<?= $iId ?>" style="min-width:200px;">
<?
		foreach ($sLanguageList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $sLanguage) ? " selected" : "") ?>><?= $sValue ?></option>
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
				      <td class="noRecord">No QA Email Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Vendor={$Vendor}&AuditStage={$AuditStage}&Name={$Name}");
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