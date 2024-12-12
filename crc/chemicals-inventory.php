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

	$PageId             = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$PreprationName     = IO::strValue("PreprationName");
        $FormulationName    = IO::strValue("FormulationName");
        $CompoundId         = IO::intValue("CompoundId");
        $LocationId         = IO::intValue("LocationId");
        $EinecsNo           = IO::strValue("EinecsNo");
        $HazardDataP        = IO::strValue("HazardDataP");
        $HazardDataH        = IO::strValue("HazardDataH");
        $HazardDataE        = IO::strValue("HazardDataE");
        $Concentration      = IO::strValue("Concentration");
        $SubstanceUsed      = IO::strValue("SubstanceUsed");
        $SupplierName       = IO::strValue("SupplierName");
        $SupplierEmail      = IO::strValue("SupplierEmail");
        $Consumption        = IO::strValue("Consumption");
        $QualityCheck       = IO::strValue("QualityCheck");
	$SdsPresent         = IO::strValue("SdsPresent");
        $Remarks            = IO::strValue("Remarks");
        $ResponsiblePerson  = IO::strValue("ResponsiblePerson");
        $UpdatedDate        = IO::strValue("UpdatedDate");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$PreprationName     = IO::strValue("PreprationName");
                $FormulationName    = IO::strValue("FormulationName");
                $CompoundId         = IO::intValue("CompoundId");
                $LocationId         = IO::intValue("LocationId");
                $EinecsNo           = IO::strValue("EinecsNo");
                $HazardDataP       = IO::strValue("HazardDataP");
                $HazardDataH       = IO::strValue("HazardDataH");
                $HazardDataE       = IO::strValue("HazardDataE");
                $Concentration      = IO::strValue("Concentration");
                $SubstanceUsed      = IO::strValue("SubstanceUsed");
                $SupplierName       = IO::strValue("SupplierName");
                $SupplierEmail      = IO::strValue("SupplierEmail");
                $Consumption        = IO::strValue("Consumption");
                $QualityCheck       = IO::strValue("QualityCheck");
                $SdsPresent         = IO::strValue("SdsPresent");
                $Remarks            = IO::strValue("Remarks");
                $ResponsiblePerson  = IO::strValue("ResponsiblePerson");
                $UpdatedDate        = IO::strValue("UpdatedDate");
	}

        $sCompoundsTypeList = getList("tbl_chemical_types", "id", "type");
	$sCompoundList     = getList("tbl_chemical_compounds", "id", "compound");
        
        $sChemicalLocationTypeList = getList("tbl_chemical_location_types", "id", "type");
	$sChemicalLocationsList    = getList("tbl_chemical_locations", "id", "location");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/chemicals-inventory.js"></script>
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
			    <h1>Chemicals Inventory</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-chemical-inventory.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add New Chemical Inventory</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="200">Preparation Name<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="PreprationName" value="<?= $PreprationName ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>
                                  <tr>
					<td width="190">Formulation Name (Commercial)<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="FormulationName" value="<?= $FormulationName ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>
				  <tr>
					<td>Chemical Substance<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
                                        <?
                                        if (count($sCompoundsTypeList) > 0)
                                        {
                                                echo '<select name="CompoundId">';
                                                echo '<option value="">Select a Compound</option>';
                                                foreach ($sCompoundsTypeList as $iCompType => $sCompType)
                                                {
                                                        echo @utf8_encode('<optgroup label="'.$sCompType.'">');


                                                        $sCompoundsList = getList("tbl_chemical_compounds", "id", "compound", "type_id='$iCompType'");

                                                        foreach ($sCompoundsList as $iComp => $sComp)
                                                        {
                                                                echo @utf8_encode('<option value="'.$iComp.'" "'.(($iComp == $CompoundId) ? " selected" : "").'" >'.$sComp.'</option>');
                                                        }

                                                        echo '</optgroup>';
                                                }

                                                echo '</select>';
                                        }
                                        ?>    
                                            
					</td>
				  </tr>
                                  <tr>
					<td>Chemical Location<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
                                        <?
                                        if (count($sChemicalLocationTypeList) > 0)
                                        {
                                                echo '<select name="LocationId">';
                                                echo '<option value="">Select a Location</option>';
                                                foreach ($sChemicalLocationTypeList as $iCompLocType => $sCompLocType)
                                                {
                                                        echo @utf8_encode('<optgroup label="'.$sCompLocType.'">');


                                                        $sChmicalLocationList = getList("tbl_chemical_locations", "id", "location", "type_id='$iCompLocType'");

                                                        foreach ($sChmicalLocationList as $iChemLoc => $sChemLoc)
                                                        {
                                                                echo @utf8_encode('<option value="'.$iChemLoc.'" "'.(($iChemLoc == $LocationId) ? " selected" : "").'" >'.$sChemLoc.'</option>');
                                                        }

                                                        echo '</optgroup>';
                                                }

                                                echo '</select>';
                                        }
                                        ?>    
                                            
					</td>
				  </tr>  
                                  <tr>
					<td width="190">EINECS No.</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="EinecsNo" value="<?= $EinecsNo ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>  
                                  <tr>
					<td width="190">Hazard Data (P)</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="HazardDataP" value="<?= $HazardDataP ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>
                                  <tr>
					<td width="190">Hazard Data (H)</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="HazardDataH" value="<?= $HazardDataH ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>
                                  <tr>
					<td width="190">Hazard Data (E)</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="HazardDataE" value="<?= $HazardDataE ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>
                                  <tr>
					<td width="190">Concentration</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Concentration" value="<?= $Concentration ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>  
                                  <tr>
					<td width="190">Substance Used</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="SubstanceUsed" value="<?= $SubstanceUsed ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>  
                                  <tr>
					<td width="190">Supplier Name</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="SupplierName" value="<?= $SupplierName ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>    
                                  <tr>
					<td width="190">Supplier Email</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="SupplierEmail" value="<?= $SupplierEmail ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>  
                                  <tr>
					<td width="190">Consumption</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Consumption" value="<?= $Consumption ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>     
                                  <tr>
					<td width="190">Quality Check</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="QualityCheck" value="<?= $QualityCheck ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr> 
                                 <tr>
					<td width="190">Sds Present</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="SdsPresent" value="<?= $SdsPresent ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>
                                  <tr>
					<td width="190">Responsible Person</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="ResponsiblePerson" value="<?= $ResponsiblePerson ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr> 
                                  <tr>
                                        <td width="190">Updated Date</td>
					<td width="20" align="center">:</td>
                                        <td>
					  <table border="0" cellpadding="0" cellspacing="0" width="116">
                                            <tr>
                                                <td width="82"><input type="text" name="UpdatedDate" id="UpdatedDate" value="<?= $UpdatedDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('UpdatedDate'), 'yyyy-mm-dd', this);" /></td>
                                                <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('UpdatedDate'), 'yyyy-mm-dd', this);" /></td>
                                            </tr>
                                          </table>
                                        </td>
                                  </tr>
                                 <tr>
					<td width="190">Remarks</td>
					<td width="20" align="center">:</td>
                                        <td><textarea name="Remarks"><?=$Remarks?></textarea></td>
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
			          <td width="120">Preparation Name</td>
			          <td width="160"><input type="text" name="PreprationName" value="<?= $PreprationName ?>" class="textbox" maxlength="50" /></td>

			          <td width="120">Formulation Name</td>
                                  <td width="160"><input type="text" name="FormulationName" value="<?= $FormulationName ?>" class="textbox" maxlength="50" /></td>
                                  <td width="130">Chemical Substance</td>
                                  <td>
                                        <?
                                        if (count($sCompoundsTypeList) > 0)
                                        {
                                                echo '<select name="CompoundId" width="150">';
                                                echo '<option value="">All Chemical Substances</option>';
                                                foreach ($sCompoundsTypeList as $iCompType => $sCompType)
                                                {
                                                        echo @utf8_encode('<optgroup label="'.$sCompType.'">');


                                                        $sCompoundsList = getList("tbl_chemical_compounds", "id", "compound", "type_id='$iCompType'");

                                                        foreach ($sCompoundsList as $iComp => $sComp)
                                                        {
                                                                echo @utf8_encode('<option value="'.$iComp.'" "'.(($iComp == $CompoundId) ? "selected" : "").'" >'.$sComp.'</option>');
                                                        }

                                                        echo '</optgroup>';
                                                }

                                                echo '</select>';
                                        }
                                        ?>    
                                            
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

	if ($PreprationName != "")
		$sConditions .= " AND prepration_name LIKE '%$PreprationName%' ";

        if ($FormulationName != "")
		$sConditions .= " AND formulation_name LIKE '%$FormulationName%' ";
        
	if ($CompoundId != "")
		$sConditions .= " AND compound_id='$CompoundId' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_chemicals_inventory", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_chemicals_inventory $sConditions ORDER BY position LIMIT $iStart, $iPageSize";
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
				      <td width="30%">Preparation Name</td>
				      <td width="30%">Formulation Name</td>
                                      <td width="20%">Compound</td>
				      <td width="14%" class="center">Options</td>
				    </tr>
                              </table>
<?
		}

		$iId              = $objDb->getField($i, 'id');
		$sPreprationName  = $objDb->getField($i, 'prepration_name');
		$sFormulationName = $objDb->getField($i, 'formulation_name');
                $iCompoundId      = $objDb->getField($i, 'compound_id');
                $iLocationId      = $objDb->getField($i, 'location_id');
                $sEinecsNo        = $objDb->getField($i, 'einecs_no');
                $sHazardDataP     = $objDb->getField($i, 'hazard_data_p');
                $sHazardDataH     = $objDb->getField($i, 'hazard_data_h');
                $sHazardDataE     = $objDb->getField($i, 'hazard_data_e');
                $sConcentration   = $objDb->getField($i, 'concentration');
                $sSubstanceUsed   = $objDb->getField($i, 'substance_used');
                $sSupplierName    = $objDb->getField($i, 'supplier_name');
                $sSupplierEmail   = $objDb->getField($i, 'supplier_email');
                $sConsumption     = $objDb->getField($i, 'consumption');
                $sQualityCheck    = $objDb->getField($i, 'quality_check');
                $sSdsPresent      = $objDb->getField($i, 'sds_present');
                $sRemarks         = $objDb->getField($i, 'remarks');
                $sResponsiblePerson  = $objDb->getField($i, 'responsible_person');
                $sUpdatedDate     = $objDb->getField($i, 'updated_date');
		$iPosition        = $objDb->getField($i, 'position');

?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="30%"><span id="PreprationName<?= $iId ?>"><?= $sPreprationName ?></span></td>
				      <td width="30%"><span id="FormulationName<?= $iId ?>"><?= $sFormulationName ?></span></td>
                                      <td width="20%"><span id="CompoundId<?= $iId ?>"><?= $sCompoundList[$iCompoundId] ?></span></td>
				      <td width="14%" class="right">
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
			 <a href="crc/delete-chemical-inventory.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Inventory Item?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
                         <a href="crc/view-chemical-inventory.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Chemical Inventory :: :: width: 800, height: 500"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                
                                              <tr>
                                                    <td width="200">Preparation Name<span class="mandatory">*</span></td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="PreprationName" value="<?= $sPreprationName ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>
                                              <tr>
                                                    <td width="190">Formulation Name (Commercial)<span class="mandatory">*</span></td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="FormulationName" value="<?= $sFormulationName ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>
                                              <tr>
                                                    <td>Chemical Substance<span class="mandatory">*</span></td>
                                                    <td align="center">:</td>

                                                    <td>
                                                    <?
                                                    if (count($sCompoundsTypeList) > 0)
                                                    {
                                                            echo '<select name="CompoundId" id="CompoundId'.$i.'">';
                                                            
                                                            foreach ($sCompoundsTypeList as $iCompType => $sCompType)
                                                            {
                                                                    echo @utf8_encode('<optgroup label="'.$sCompType.'">');


                                                                    $sCompoundsList = getList("tbl_chemical_compounds", "id", "compound", "type_id='$iCompType'");
                                                                    
                                                                    foreach ($sCompoundsList as $iComp => $sComp)
                                                                    {
                                                                            echo @utf8_encode('<option value="'.$iComp.'" '.($iComp == $iCompoundId?'selected':'').' >'.$sComp.'</option>');
                                                                    }

                                                                    echo '</optgroup>';
                                                            }

                                                            echo '</select>';
                                                    }
                                                    ?>    

                                                    </td>
                                              </tr>
                                              <tr>
                                                    <td>Chemical Location<span class="mandatory">*</span></td>
                                                    <td align="center">:</td>

                                                    <td>
                                                    <?
                                                    if (count($sChemicalLocationTypeList) > 0)
                                                    {
                                                            echo '<select name="LocationId" id="LocationId'.$i.'">';
                                                            echo '<option value="">Select a Location</option>';
                                                            foreach ($sChemicalLocationTypeList as $iCompLocType => $sCompLocType)
                                                            {
                                                                    echo @utf8_encode('<optgroup label="'.$sCompLocType.'">');


                                                                    $sChmicalLocationList = getList("tbl_chemical_locations", "id", "location", "type_id='$iCompLocType'");

                                                                    foreach ($sChmicalLocationList as $iChemLoc => $sChemLoc)
                                                                    {
                                                                            echo @utf8_encode('<option value="'.$iChemLoc.'" '.($iChemLoc == $iLocationId?'selected':'').' >'.$sChemLoc.'</option>');
                                                                    }

                                                                    echo '</optgroup>';
                                                            }

                                                            echo '</select>';
                                                    }
                                                    ?>    

                                                    </td>
                                              </tr>
                                              <tr>
                                                    <td width="190">EINECS No.</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="EinecsNo" value="<?= $sEinecsNo ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>  
                                              <tr>
                                                    <td width="190">Hazard Data (P)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="HazardDataP" value="<?= $sHazardDataP ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>
                                              <tr>
                                                    <td width="190">Hazard Data (H)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="HazardDataH" value="<?= $sHazardDataH ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>
                                              <tr>
                                                    <td width="190">Hazard Data (E)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="HazardDataE" value="<?= $sHazardDataE ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>
                                              <tr>
                                                    <td width="190">Concentration</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="Concentration" value="<?= $sConcentration ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>  
                                              <tr>
                                                    <td width="190">Substance Used</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="SubstanceUsed" value="<?= $sSubstanceUsed ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>  
                                              <tr>
                                                    <td width="190">Supplier Name</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="SupplierName" value="<?= $sSupplierName ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>    
                                              <tr>
                                                    <td width="190">Supplier Email</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="SupplierEmail" value="<?= $sSupplierEmail ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>  
                                              <tr>
                                                    <td width="190">Consumption</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="Consumption" value="<?= $sConsumption ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>     
                                              <tr>
                                                    <td width="190">Quality Check</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="QualityCheck" value="<?= $sQualityCheck ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr> 
                                             <tr>
                                                    <td width="190">Sds Present</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="SdsPresent" value="<?= $sSdsPresent ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr>
                                              <tr>
                                                    <td width="190">Responsible Person</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="ResponsiblePerson" value="<?= $sResponsiblePerson ?>" maxlength="100" size="30" class="textbox" /></td>
                                              </tr> 
                                              <tr>
                                                    <td width="190">Updated Date</td>
                                                    <td width="20" align="center">:</td>
                                                    <td>
                                                      <table border="0" cellpadding="0" cellspacing="0" width="116">
                                                        <tr>
                                                            <td width="82"><input type="text" name="UpdatedDate" id="UpdatedDate<?= $i ?>" value="<?= $sUpdatedDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('UpdatedDate<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
                                                            <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('UpdatedDate<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
                                                        </tr>
                                                      </table>
                                                    </td>
                                             </tr>
                                             <tr>
                                                    <td width="190">Remarks</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><textarea name="Remarks"><?=$sRemarks?></textarea></td>
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
				      <td class="noRecord">No Section Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Section={$Section}&Parent={$Parent}");
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