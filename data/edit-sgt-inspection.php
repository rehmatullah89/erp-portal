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

	
	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT * FROM tbl_sgt_inspections WHERE id = '$Id'";
	$objDb->query($sSQL);
	
	$iYear             = $objDb->getField(0, 'year');
	$iFactory          = $objDb->getField(0, 'factory_id');
	$sJanuary          = explode(",", $objDb->getField(0, 'january'));
	$sFebruary         = explode(",", $objDb->getField(0, 'february'));
	$sMarch            = explode(",", $objDb->getField(0, 'march'));
	$sApril            = explode(",", $objDb->getField(0, 'april'));
	$sMay              = explode(",", $objDb->getField(0, 'may'));
	$sJune             = explode(",", $objDb->getField(0, 'june'));
	$sJuly             = explode(",", $objDb->getField(0, 'july'));
	$sAugust           = explode(",", $objDb->getField(0, 'august'));
	$sSeptember        = explode(",", $objDb->getField(0, 'september'));
        $sOctober          = explode(",", $objDb->getField(0, 'october'));
        $sNovember         = explode(",", $objDb->getField(0, 'november'));
        $sDecember         = explode(",", $objDb->getField(0, 'december'));

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
			    <form name="frmData" id="frmData" method="post" action="data/update-sgt-inspection.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
                                <input type="hidden" name="Id" value="<?=$Id?>"/>
				<h2>Edit Sgt Inspection Result</h2>

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
			            	  <option value="<?= $sKey ?>" <?= (($sKey == $iFactory) ? " selected" : "") ?>><?= $sValue ?></option>
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
                                                          <option value="2020" <?= (($iYear == "2020") ? " selected" : "") ?>>2020</option>
                                                          <option value="2019" <?= (($iYear == "2019") ? " selected" : "") ?>>2019</option>
                                                          <option value="2018" <?= (($iYear == "2018") ? " selected" : "") ?>>2018</option>
                                                          <option value="2017" <?= (($iYear == "2017") ? " selected" : "") ?>>2017</option>
                                                          <option value="2016" <?= (($iYear == "2016") ? " selected" : "") ?>>2016</option>
                                                          <option value="2015" <?= (($iYear == "2015") ? " selected" : "") ?>>2015</option>
                                                          <option value="2014" <?= (($iYear == "2014") ? " selected" : "") ?>>2014</option>
                                                          <option value="2013" <?= (($iYear == "2013") ? " selected" : "") ?>>2013</option>
                                                          <option value="2012" <?= (($iYear == "2012") ? " selected" : "") ?>>2012</option>
                                                          <option value="2011" <?= (($iYear == "2011") ? " selected" : "") ?>>2011</option>
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
                                                            <td width="200" align="center"><input type="text" name="January[]" value="<?=$sJanuary[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="January[]" value="<?=$sJanuary[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="January[]" value="<?=$sJanuary[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>February</b></td>
                                                            <td width="200" align="center"><input type="text" name="February[]" value="<?=$sFebruary[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="February[]" value="<?=$sFebruary[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="February[]" value="<?=$sFebruary[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>March</b></td>
                                                            <td width="200" align="center"><input type="text" name="March[]" value="<?=$sMarch[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="March[]" value="<?=$sMarch[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="March[]" value="<?=$sMarch[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>April</b></td>
                                                            <td width="200" align="center"><input type="text" name="April[]" value="<?=$sApril[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="April[]" value="<?=$sApril[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="April[]" value="<?=$sApril[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>May</b></td>
                                                            <td width="200" align="center"><input type="text" name="May[]" value="<?=$sMay[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="May[]" value="<?=$sMay[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="May[]" value="<?=$sMay[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>June</b></td>
                                                            <td width="200" align="center"><input type="text" name="June[]" value="<?=$sJune[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="June[]" value="<?=$sJune[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="June[]" value="<?=$sJune[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>July</b></td>
                                                            <td width="200" align="center"><input type="text" name="July[]" value="<?=$sJuly[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="July[]" value="<?=$sJuly[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="July[]" value="<?=$sJuly[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>August</b></td>
                                                            <td width="200" align="center"><input type="text" name="August[]" value="<?=$sAugust[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="August[]" value="<?=$sAugust[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="August[]" value="<?=$sAugust[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>September</b></td>
                                                            <td width="200" align="center"><input type="text" name="September[]" value="<?=$sSeptember[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="September[]" value="<?=$sSeptember[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="September[]" value="<?=$sSeptember[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>October</b></td>
                                                            <td width="200" align="center"><input type="text" name="October[]" value="<?=$sOctober[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="October[]" value="<?=$sOctober[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="October[]" value="<?=$sOctober[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>November</b></td>
                                                            <td width="200" align="center"><input type="text" name="November[]" value="<?=$sNovember[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="November[]" value="<?=$sNovember[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="November[]" value="<?=$sNovember[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>December</b></td>
                                                            <td width="200" align="center"><input type="text" name="December[]" value="<?=$sDecember[0]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="December[]" value="<?=$sDecember[1]?>" class="text" size="10"/></td>
                                                            <td width="200" align="center"><input type="text" name="December[]" value="<?=$sDecember[2]?>" class="text" size="10"/></td>
                                                        </tr>
                                                        
                                                    </table>
                                                </td>						  
					    </tr>					    
					  </table>

				<div class="buttonsBar">
                                    <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
                                    <input type="button" value="" class="btnBack" onclick="document.location='./data/sgt-inspections.php';" />
                                </div>
			    </form>
<?
        }
?>
			    <hr />

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