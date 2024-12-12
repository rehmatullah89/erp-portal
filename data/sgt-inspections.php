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
	$Factory    = IO::strValue("Factory");
	$Year       = IO::intValue("Year");
	
	if ($PostId != "")
	{
		$_REQUEST   = @unserialize($_SESSION[$PostId]);
                $Factory    = IO::strValue("Factory");
                $Year       = IO::intValue("Year");		
	}

        $sJcrewVendors = getDbValue("vendors", "tbl_brands", "id='526'");
        $sFactories    = getList("tbl_vendors", "id", "vendor", "id IN ($sJcrewVendors) AND parent_id='0' AND sourcing='Y'", "vendor");        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

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
			    <h1>Sgt Inspections</h1>
<br/>
<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-sgt-inspection.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Sgt Inspection Result</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                              
					    <tr>
						  <td width="140" style="padding-left: 15px;">Factory<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="Factory" id="Factory" required="" style="width:250px;">
                                                          <option value="">Select Factory</option>
<?
		foreach ($sFactories as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  		</select>
						  </td>
					    </tr>

					    <tr>
						  <td style="padding-left: 15px;">Year<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Year" id="Year" required="" style="width:250px;">
							  <option value="">Select Year</option>
                                                          <option value="2020">2020</option>
                                                          <option value="2019">2019</option>
                                                          <option value="2018">2018</option>
                                                          <option value="2017">2017</option>
                                                          <option value="2016">2016</option>
                                                          <option value="2015">2015</option>
                                                          <option value="2014">2014</option>
                                                          <option value="2013">2013</option>
                                                          <option value="2012">2012</option>
                                                          <option value="2011">2011</option>
						    </select>
						  </td>
					    </tr>
                                              <tr><td colspan="3"></td></tr>
                                              <tr><td colspan="3"></td></tr>
                                            <tr valign="top">
                                                <td colspan="3">
                                                    <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                        <tr valign="top" class="headerRow">
                                                            <td style="padding-left: 15px;"><h3>Month</h3></td>
                                                            <td width="200" align="center"><h3>Accepted %</h3></td>
                                                            <td width="200" align="center"><h3>Rejected %</h3></td>
                                                            <td width="200" align="center"><h3>Pending %</h3></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>January</b></td>
                                                            <td width="200" align="center"><input type="text" name="January[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="January[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="January[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>February</b></td>
                                                            <td width="200" align="center"><input type="text" name="February[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="February[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="February[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>March</b></td>
                                                            <td width="200" align="center"><input type="text" name="March[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="March[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="March[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>April</b></td>
                                                            <td width="200" align="center"><input type="text" name="April[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="April[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="April[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>May</b></td>
                                                            <td width="200" align="center"><input type="text" name="May[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="May[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="May[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>June</b></td>
                                                            <td width="200" align="center"><input type="text" name="June[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="June[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="June[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>July</b></td>
                                                            <td width="200" align="center"><input type="text" name="July[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="July[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="July[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>August</b></td>
                                                            <td width="200" align="center"><input type="text" name="August[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="August[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="August[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>September</b></td>
                                                            <td width="200" align="center"><input type="text" name="September[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="September[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="September[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>October</b></td>
                                                            <td width="200" align="center"><input type="text" name="October[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="October[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="October[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>November</b></td>
                                                            <td width="200" align="center"><input type="text" name="November[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="November[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="November[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>December</b></td>
                                                            <td width="200" align="center"><input type="text" name="December[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="December[]" value="" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="December[]" value="" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                    </table>
                                                </td>						  
					    </tr>					    
					  </table>

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
			          <td width="55">Factory</td>
                                  <td width="170">
                                      <select name="Factory" style="width:250px;">
                                                          <option value="">Select Factory</option>
<?
		foreach ($sFactories as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
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

	if ($Factory != "")
		$sConditions .= " AND factory_id ='$Factory' ";

	
	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_sgt_inspections", $sConditions, $iPageSize, $PageId);
        
	$sSQL = "SELECT * FROM tbl_sgt_inspections WHERE id != '0' $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
                                <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="10%">#</td>
				      <td width="20%">Year</td>
				      <td width="60%">Factory</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
                                </table>
<?
		}


		$iId            = $objDb->getField($i, 'id');
		$iYear          = $objDb->getField($i, 'year');
		$iFactory       = $objDb->getField($i, 'factory_id');		
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="10%"><?= ($iStart + $i + 1) ?></td>
                                      <td width="20%"><span id="Year<?= $iId ?>"><?= $iYear ?></span></td>
				      <td width="60%"><span id="Factory<?= $iId ?>"><?= $sFactories[$iFactory] ?></span></td>
	
				      <td width="10%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="data/edit-sgt-inspection.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-sgt-inspection.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Inspection Result?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="data/view-sgt-inspection.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Sgt Inspection Details :: :: width:700, height:550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Sgt Inspection Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Factory={$Factory}");
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

<script> 
    var button = document.getElementById('SpanId'); 

    button.onclick = function() {
        var div = document.getElementById('AdditionalInfoId');
        if (div.style.display !== 'none') {
            div.style.display = 'none';
            document.getElementById('SpanId').innerHTML = "+";
        }
        else {
            div.style.display = 'block';
            document.getElementById('SpanId').innerHTML = "-";
        }
    };
</script>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>