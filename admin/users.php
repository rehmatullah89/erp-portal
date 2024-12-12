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


	$PageId        = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$User          = IO::strValue("User");
	$UserType      = IO::strValue("UserType");
	$Country       = IO::intValue("Country");
	$Status        = IO::strValue("Status");
	$AuditorType   = IO::intValue("AuditorType");
	$ReportType    = IO::intValue("ReportType");
	$AuditsManager = IO::strValue("AuditsManager");
	$AppVersion    = IO::strValue("AppVersion");

        $sClientsList  = getList("tbl_clients", "code", "title");

	if (@in_array($_SESSION["UserType"], array("MGF", "CONTROLIST", "HYBRID", "GLOBALEXPORTS", "LEVIS")))
		$sCountriesList = getList("tbl_countries", "id", "country", "id IN (SELECT DISTINCT(country_id) FROM tbl_users WHERE user_type='{$_SESSION['UserType']}')");
	
	else
		$sCountriesList = getList("tbl_countries", "id", "country", "id IN (SELECT DISTINCT(country_id) FROM tbl_users)");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			    <h1>Users Listing</h1>

		        <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="40">User</td>
			          <td width="150"><input type="text" name="User" value="<?= $User ?>" class="textbox" maxlength="50" size="15" /></td>
<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>					  
			          <td width="50">Client</td>

			          <td width="150">
			            <select name="UserType">
			              <option value="">All Clients</option>
<?
                                        foreach($sClientsList as $sCode => $sClient)       
                                        {
    ?>
                                         <option value="<?=$sCode?>" <?=($UserType == $sCode)?'selected':''?>><?=$sClient?></option>
    <?
                                        } 
?>
			            </select>
			          </td>					  
<?
	}
?>
			          <td width="60">Country</td>

			          <td width="160">
			            <select name="Country">
			              <option value="">All Countries</option>
<?
	foreach ($sCountriesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Country) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>
					  
			          <td width="50">Status</td>

			          <td width="100">
			            <select name="Status">
			              <option value="">All</option>
						  <option value="A"<?= (($Status == "A") ? " selected" : "") ?>>Active</option>
						  <option value="D"<?= (($Status == "D") ? " selected" : "") ?>>Disabled</option>
						  <option value="P"<?= (($Status == "P") ? " selected" : "") ?>>Pending</option>
			            </select>
			          </td>		

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
				
<?
	if ($_SESSION["UserType"] == "MGF")
	{
?>
			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="85">Auditor Type</td>

			          <td width="80">
			            <select name="AuditorType" id="AuditorType">
						  <option value=""></option>
	  	        		  <option value="1"<?= (($AuditorType == 1) ? " selected" : "") ?>>MCA</option>
	  	        		  <option value="2"<?= (($AuditorType == 2) ? " selected" : "") ?>>FCA</option>
                                          <option value="14"<?= (($AuditorType == 14) ? " selected" : "") ?>>MGF 3rd Party</option>
			            </select>
			          </td>

			          <td width="85">Report Type</td>

			          <td width="100">
			            <select name="ReportType" id="ReportType">
						  <option value=""></option>
	  	        		  <option value="14"<?= (($ReportType == 14) ? " selected" : "") ?>>MGF</option>
	  	        		  <option value="34"<?= (($ReportType == 34) ? " selected" : "") ?>>MGF Test</option>
			            </select>
			          </td>
					  
			          <td width="105">Audits Manager</td>

			          <td width="80">
			            <select name="AuditsManager" id="AuditsManager">
						  <option value=""></option>
	  	        		  <option value="Y"<?= (($AuditsManager == "Y") ? " selected" : "") ?>>Yes</option>
	  	        		  <option value="N"<?= (($AuditsManager == "N") ? " selected" : "") ?>>No</option>
			            </select>
			          </td>
					  
			          <td width="85">App Version</td>

			          <td width="100">
			            <select name="AppVersion" id="AppVersion">
						  <option value=""></option>
	  	        		  <option value="U"<?= (($AppVersion == "U") ? " selected" : "") ?>>Up-to-date</option>
	  	        		  <option value="O"<?= (($AppVersion == "O") ? " selected" : "") ?>>Old Version</option>
						  <option value="N"<?= (($AppVersion == "N") ? " selected" : "") ?>>Without App</option>
			            </select>
			          </td>					  

					  <td></td>
				    </tr>
				  </table>
			    </div>
<?
	}
?>
			    </form>				

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$objCurl = @curl_init(SITE_URL."app/version.php");

	@curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$sResponse = @curl_exec($objCurl);

	@curl_close($objCurl);
	
	
	$sParams     = @json_decode($sResponse, true);
	$iAppVersion = $sParams["Code"];

	
			
			
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($User != "")
		$sConditions .= " AND (name LIKE '%$User%' OR username LIKE '%$User%' OR email LIKE '%$User%'  OR mobile LIKE '%$User%') ";
	
	if ($UserType != "")
		$sConditions .= " AND user_type='$UserType' ";

	if ($Country > 0)
		$sConditions .= " AND country_id='$Country' ";
	
	if ($Status != "")
		$sConditions .= " AND status='$Status' ";

	if (@in_array($_SESSION["UserType"], array("MGF", "CONTROLIST", "HYBRID", "GLOBALEXPORTS", "LEVIS", "HOHENSTEIN", "JCREW")))
			$sConditions .= " AND user_type='{$_SESSION['UserType']}' ";

	if ($AuditorType > 0)
		$sConditions .= " AND auditor_type='$AuditorType' ";
	
	if ($ReportType > 0)
		$sConditions .= " AND FIND_IN_SET('$ReportType', report_types) ";
	
	if ($AuditsManager != "")
		$sConditions .= " AND audits_manager='$AuditsManager' ";

	if ($AppVersion != "")
	{
		if ($AppVersion == "N")
			$sConditions .= " AND (device_id='' OR ISNULL(device_id)) AND app_version='0' ";
		
		else if ($AppVersion == "O")
			$sConditions .= " AND device_id!='' AND NOT ISNULL(device_id) AND app_version<'$iAppVersion' ";

		else if ($AppVersion == "U")
			$sConditions .= " AND device_id!='' AND NOT ISNULL(device_id) AND app_version='$iAppVersion' ";
	}

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_users", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, name, email, country_id, username, date_time, status, device_id, app_version FROM tbl_users $sConditions ORDER BY name ASC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="18%">Name</td>
				      <td width="28%">Email</td>
				      <td width="14%">Username</td>
				      <td width="12%">Country</td>
				      <td width="10%">Date / Time</td>
				      <td width="13%" class="center">Options</td>
				    </tr>
<?
		}

		
		$iId      = $objDb->getField($i, 'id');
		$sDevice  = $objDb->getField($i, 'device_id');
		$iVersion = $objDb->getField($i, 'app_version');

		switch ($objDb->getField($i, "status"))
		{
			case "A" : $sStatus = "yes"; break;
			case "D" : $sStatus = "no"; break;
			case "P" : $sStatus = "pending"; break;
		}
		
		
		$sColor = "";
		
		if ($sDevice != "" && $iVersion < $iAppVersion)
			$sColor = " style='color:#aa0000;' ";
		
		else if ($sDevice != "" && $iVersion == $iAppVersion)
			$sColor = " style='color:#008800;' ";
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td rel="<?= $iVersion ?>  <?= $sDevice ?>"><?= ($iStart + $i + 1) ?></td>
				      <td<?= $sColor ?>><?= $objDb->getField($i, 'name') ?></td>
				      <td style="overflow:hidden;"><?= $objDb->getField($i, 'email') ?></td>
				      <td><?= $objDb->getField($i, 'username') ?></td>
				      <td><?= $sCountriesList[$objDb->getField($i, 'country_id')] ?></td>
				      <td><?= formatDate($objDb->getField($i, 'date_time')) ?></td>

				      <td class="center">
<?
		//if ($iId != $_SESSION['UserId'] || @in_array($_SESSION['UserId'], array(1,2,3)))
		{
?>
				        <a href="admin/toggle-user-status.php?Id=<?= $iId ?>&Status=<?= (($sStatus == 'yes') ? 'D' : 'A') ?>"><img src="images/icons/<?= $sStatus ?>.png" width="16" height="16" border="0" alt="Toggle Status" title="Toggle Status" /></a>
				        <a href="admin/edit-user.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="3" alt="Edit" title="Edit" /></a>
<?
		}
?>
				        <a href="admin/view-user.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="User # <?= $iId ?> :: :: width: 800, height: 550"><img src="images/icons/view.gif" width="16" height="16" hspace="3" alt="View" title="View" /></a>
<?
		if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR")))
		{
?>
				        <a href="admin/view-user-schedule.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="User # <?= $iId ?> :: :: width: 800, height: 550"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Schedule" title="Schedule" /></a>
<?
		}
?>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No User Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&User={$User}&UserType={$UserType}&Country={$Country}&Status={$Status}&AuditorType={$AuditorType}&ReportType={$ReportType}&AuditsManager={$AuditsManager}&AppVersion={$AppVersion}");
?>

			  </td>
			</tr>
		  </table>
<?
        if($iCount >0)
        {
?>
                    <div class="buttonsBar" style="margin-top:4px;">
                        <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."admin/export-users.php?User={$User}&UserType={$UserType}&Country={$Country}&Status={$Status}&AuditorType={$AuditorType}&ReportType={$ReportType}&AuditsManager={$AuditsManager}&AppVersion={$AppVersion}") ?>" />
                        <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
                    </div>                      
<?
        }

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
<script type="text/javascript">
    <!--
        function exportReport( )
        {
            $('BtnExport').disabled = true;

            document.location = $('ExportUrl').value;

            setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
        }    
    -->
</script>    
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>