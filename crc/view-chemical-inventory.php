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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_chemicals_inventory WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sPreprationName  = $objDb->getField(0, 'prepration_name');
		$sFormulationName = $objDb->getField(0, 'formulation_name');
                $iCompoundId      = $objDb->getField(0, 'compound_id');
                $iLocationId      = $objDb->getField(0, 'location_id');
                $sEinecsNo        = $objDb->getField(0, 'einecs_no');
                $sHazardDataP     = $objDb->getField(0, 'hazard_data_p');
                $sHazardDataH     = $objDb->getField(0, 'hazard_data_h');
                $sHazardDataE     = $objDb->getField(0, 'hazard_data_e');
                $sConcentration   = $objDb->getField(0, 'concentration');
                $sSubstanceUsed   = $objDb->getField(0, 'substance_used');
                $sSupplierName    = $objDb->getField(0, 'supplier_name');
                $sSupplierEmail   = $objDb->getField(0, 'supplier_email');
                $sConsumption     = $objDb->getField(0, 'consumption');
                $sQualityCheck    = $objDb->getField(0, 'quality_check');
                $sSdsPresent      = $objDb->getField(0, 'sds_present');
                $sRemarks         = $objDb->getField(0, 'remarks');
                $sResponsiblePerson  = $objDb->getField(0, 'responsible_person');
                $sUpdatedDate     = $objDb->getField(0, 'updated_date');
	}
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
	<div id="Body" style="min-height:344px; height:495px;">
	  <h2>Chemical Inventory Detials</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="130">Preparation Name</td>
		  <td width="20" align="center">:</td>
		  <td><?= $sPreprationName ?></td>
	    </tr>

            <tr>
		  <td>Formulation Name</td>
		  <td align="center">:</td>
		  <td><?= $sFormulationName ?></td>
	    </tr>
  
            <tr>
		  <td>Chemical Compound </td>
		  <td align="center">:</td>
		  <td><?= getDbValue("compound", "tbl_chemical_compounds", "id='$iCompoundId'") ?></td>
	    </tr>
            
           <tr>
		  <td>CAS No </td>
		  <td align="center">:</td>
		  <td><?= getDbValue("cas_no", "tbl_chemical_compounds", "id='$iCompoundId'") ?></td>
	    </tr>   
              
	    <tr>
		  <td>Location Type</td>
		  <td align="center">:</td>
		  <td><?= getDbValue("location", "tbl_chemical_locations", "id='$iLocationId'") ?></td>
	    </tr>

            <tr>
		  <td>Einecs No</td>
		  <td align="center">:</td>
		  <td><?= $sEinecsNo ?></td>
	    </tr>
            
            <tr>
		  <td>Hazard Data (P)</td>
		  <td align="center">:</td>
		  <td><?= $sHazardDataP ?></td>
	    </tr>
            <tr>
		  <td>Hazard Data (H)</td>
		  <td align="center">:</td>
		  <td><?= $sHazardDataH ?></td>
	    </tr>
            <tr>
		  <td>Hazard Data (E)</td>
		  <td align="center">:</td>
		  <td><?= $sHazardDataE ?></td>
	    </tr>
            
            <tr>
		  <td>Concentration</td>
		  <td align="center">:</td>
		  <td><?= $sConcentration ?></td>
	    </tr>

            <tr>
		  <td>Substance Used</td>
		  <td align="center">:</td>
		  <td><?= $sSubstanceUsed ?></td>
	    </tr>

            <tr>
		  <td>Supplier Name</td>
		  <td align="center">:</td>
		  <td><?= $sSupplierName ?></td>
	    </tr>

            <tr>
		  <td>Supplier Email</td>
		  <td align="center">:</td>
		  <td><?= $sSupplierEmail ?></td>
	    </tr>
  
            <tr>
		  <td>Consumption</td>
		  <td align="center">:</td>
		  <td><?= $sConsumption ?></td>
	    </tr>

            <tr>
		  <td>Quality Check</td>
		  <td align="center">:</td>
		  <td><?= $sQualityCheck ?></td>
	    </tr>

            <tr>
		  <td>Sds Present</td>
		  <td align="center">:</td>
		  <td><?= $sSdsPresent ?></td>
	    </tr>

            <tr>
		  <td>Responsible Person</td>
		  <td align="center">:</td>
		  <td><?= $sResponsiblePerson ?></td>
	    </tr>

            <tr>
		  <td>Updated Date</td>
		  <td align="center">:</td>
		  <td><?= $sUpdatedDate ?></td>
	    </tr>

	    <tr>
		  <td>Remarks</td>
		  <td align="center">:</td>
		  <td><?= nl2br($sRemarks) ?></td>
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