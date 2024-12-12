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

	$PageId = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Report = IO::strValue("Report");
	$Brand  = IO::strValue("Brand");
	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Report  = IO::strValue("Report");
		$Code    = IO::strValue("Code");
		$Brands  = IO::getArray("Brands");
		$Failure = IO::strValue("Failure");
	}

	$sBrandsList    = getList("tbl_brands", "id", "brand", "parent_id>'0'");
        $sStagesList    = getList("tbl_audit_stages", "code", "stage");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/reports.js"></script>
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
			    <h1>report types</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-report.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Report<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Report" value="<?= $Report ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Code<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Code" value="<?= $Code ?>" maxlength="2" size="25" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Brand(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Brands[]" multiple size="10" style="min-width:204px;">
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Brands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
                                  <tr>
				    <td>Stages</td>
				    <td align="center">:</td>
                                    <td><select name="Stages[]" style="width:210px;" multiple>
<?
                                        foreach($sStagesList as $iStage => $sStage)
                                        {
?>
                                            <option value="<?=$iStage?>"><?=$sStage?></option>
<?
                                        }
    
?>
                                        </select></td>
				  </tr>  
				  <tr>
					<td>Failure DR (%)</td>
					<td align="center">:</td>
					<td><input type="text" name="Failure" value="<?= $Failure ?>" maxlength="5" size="10" class="textbox" /></td>
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
			          <td width="50">Report</td>
			          <td width="200"><input type="text" name="Report" value="<?= $Report ?>" class="textbox" maxlength="50" size="20" /></td>
					  <td width="50">Brand</td>

					  <td width="200">
					    <select name="Brand">
					      <option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Brand)) ? " selected" : "") ?>><?= $sValue ?></option>
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
	$sClass     = array("evenRow", "oddRow");
	$iPageSize  = PAGING_SIZE;
	$iPageCount = 0;

	if ($Report != "")
		$sConditions .= " AND report LIKE '%$Report%'";

	if ($Brand > 0)
		$sConditions .= " AND FIND_IN_SET('$Brand', brands) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_reports", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_reports $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="20%">Report Type</td>
				      <td width="15%">Report Code</td>
				      <td width="15%">Failure (%)</td>
				      <td width="30%">Brands</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId      = $objDb->getField($i, 'id');
		$sReport  = $objDb->getField($i, 'report');
		$sCode    = $objDb->getField($i, 'code');
		$sBrands  = $objDb->getField($i, 'brands');
		$fFailure = $objDb->getField($i, 'failure');
                $sStages  = explode(",", $objDb->getField($i, 'stages'));  
		$iBrands = @explode(",", $sBrands);
		$sBrands = "";

		for ($j = 0; $j < count($iBrands); $j ++)
			$sBrands .= ("- ".$sBrandsList[$iBrands[$j]]."<br />");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="20%"><span id="Report<?= $iId ?>"><?= $sReport ?></span></td>
				      <td width="15%"><span id="Code<?= $iId ?>"><?= $sCode ?></span></td>
				      <td width="15%"><span id="Failure<?= $iId ?>"><?= formatNumber($fFailure) ?></span></td>
				      <td width="30%"><span id="Brands<?= $iId ?>"><?= $sBrands ?></span></td>

				      <td width="12%" class="center">
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
				        <a href="quonda/delete-report.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Report?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="90">Report<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Report" value="<?= $sReport ?>" maxlength="50" size="25" class="textbox" /></td>
						</tr>

						<tr>
						  <td>Code<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $sCode ?>" maxlength="2"  size="25" class="textbox" /></td>
						</tr>

					    <tr valign="top">
						  <td>Brand(s)</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Brands[]" multiple size="10" style="min-width:204px;">
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iBrands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>
                                            <tr>
                                            <td>Stages</td>
                                            <td align="center">:</td>
                                            <td><select name="Stages[]" multiple>
<?
                                                    foreach($sStagesList as $iStage => $sStage)
                                                    {
?>
                                                <option value="<?=$iStage?>" <?=(in_array($iStage, $sStages)?'selected':'')?>><?=$sStage?></option>
<?
                                                    }
    
?>
                                            </select></td>
                                            </tr>   
						<tr>
						  <td>Failure DR (%)</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Failure" value="<?= $fFailure ?>" maxlength="5" size="10" class="textbox" /></td>
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
				      <td class="noRecord">No Report Type Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Report={$Report}&Brand={$Brand}");
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