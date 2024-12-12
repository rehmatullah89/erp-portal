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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_sgt_inspections WHERE id = '$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
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

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>View Sgt Inspection Details</h2>

<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                              
					    <tr>
						  <td width="140" style="padding-left: 15px;">Factory<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><?=$sFactories[$iFactory]?></td>
					    </tr>

					    <tr>
						  <td style="padding-left: 15px;">Year<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><?=$iYear?></td>
					    </tr>
                                              <tr><td colspan="3"></td></tr>
                                              <tr><td colspan="3"></td></tr>
                                            <tr valign="top">
                                                <td colspan="3">
                                                    <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                        <tr valign="top" class="headerRow">
                                                            <td style="padding-left: 15px;"><h3>Month</h3></td>
                                                            <td width="150" align="center"><h3>Accepted %</h3></td>
                                                            <td width="150" align="center"><h3>Rejected %</h3></td>
                                                            <td width="150" align="center"><h3>Pending %</h3></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>January</b></td>
                                                            <td width="200" align="center"><?=$sJanuary[0]?></td>
                                                            <td width="200" align="center"><?=$sJanuary[1]?></td>
                                                            <td width="200" align="center"><?=$sJanuary[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>February</b></td>
                                                            <td width="200" align="center"><?=$sFebruary[0]?></td>
                                                            <td width="200" align="center"><?=$sFebruary[1]?></td>
                                                            <td width="200" align="center"><?=$sFebruary[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>March</b></td>
                                                            <td width="200" align="center"><?=$sMarch[0]?></td>
                                                            <td width="200" align="center"><?=$sMarch[1]?></td>
                                                            <td width="200" align="center"><?=$sMarch[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>April</b></td>
                                                            <td width="200" align="center"><?=$sApril[0]?></td>
                                                            <td width="200" align="center"><?=$sApril[1]?></td>
                                                            <td width="200" align="center"><?=$sApril[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>May</b></td>
                                                            <td width="200" align="center"><?=$sMay[0]?></td>
                                                            <td width="200" align="center"><?=$sMay[1]?></td>
                                                            <td width="200" align="center"><?=$sMay[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>June</b></td>
                                                            <td width="200" align="center"><?=$sJune[0]?></td>
                                                            <td width="200" align="center"><?=$sJune[1]?></td>
                                                            <td width="200" align="center"><?=$sJune[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>July</b></td>
                                                            <td width="200" align="center"><?=$sJuly[0]?></td>
                                                            <td width="200" align="center"><?=$sJuly[1]?></td>
                                                            <td width="200" align="center"><?=$sJuly[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>August</b></td>
                                                            <td width="200" align="center"><?=$sAugust[0]?></td>
                                                            <td width="200" align="center"><?=$sAugust[1]?></td>
                                                            <td width="200" align="center"><?=$sAugust[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>September</b></td>
                                                            <td width="200" align="center"><?=$sSeptember[0]?></td>
                                                            <td width="200" align="center"><?=$sSeptember[1]?></td>
                                                            <td width="200" align="center"><?=$sSeptember[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>October</b></td>
                                                            <td width="200" align="center"><?=$sOctober[0]?></td>
                                                            <td width="200" align="center"><?=$sOctober[1]?></td>
                                                            <td width="200" align="center"><?=$sOctober[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>November</b></td>
                                                            <td width="200" align="center"><?=$sNovember[0]?></td>
                                                            <td width="200" align="center"><?=$sNovember[1]?></td>
                                                            <td width="200" align="center"><?=$sNovember[2]?></td>
                                                        </tr>
                                                        
                                                        <tr valign="top">
                                                            <td style="padding-left: 20px;"><b>December</b></td>
                                                            <td width="200" align="center"><?=$sDecember[0]?></td>
                                                            <td width="200" align="center"><?=$sDecember[1]?></td>
                                                            <td width="200" align="center"><?=$sDecember[2]?></td>
                                                        </tr>                                                        
                                                    </table>
                                                </td>						  
					    </tr>					    
					  </table>
	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>