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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_crc_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
                $Brand          = $objDb->getField(0, "brand_id");
		$sAuditDate     = $objDb->getField(0, "audit_date");
		$iVendor        = $objDb->getField(0, "vendor_id");
                $Section        = $objDb->getField(0, "section_id");
		$iAuditor       = $objDb->getField(0, "auditor_id");
                $iBrand         = $objDb->getField(0, "brand_id");
                $sPoints        = $objDb->getField(0, "points");
                $sAuditType     = $objDb->getField(0, "audit_type_id");
                $iUnit          = $objDb->getField(0, "unit_id");
                $ddQuestion     = $objDb->getField(0, "questions_type");
                $AuditSections  = $objDb->getField(0, "audit_sections");
                
		$sVendorsList   = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
		$sAuditorsList  = getList("tbl_users", "id", "name", "auditor_type='6'");
                $sBrandsList    = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
                $sSectionList   = getList("tbl_tnc_sections", "id", "section");
                $sAuditTypesList= getList("tbl_crc_audit_types", "id", "type", "id>0", "position");

                
                $sBrand         = $sBrandsList[$iBrand];
		$sVendor        = getDbValue("vendor", "tbl_vendors", "id='$iVendor'");
                $sUnitsList     = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$iVendor' AND sourcing='Y'");
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
	<div id="Body">
	  <h2>CRC Audit Schedule Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="165">Audit Date</td>
		  <td width="20" align="center">:</td>
		  <td><?= formatDate($sAuditDate) ?></td>
	    </tr>

            <tr valign="top">
		  <td>Brand</td>
		  <td align="center">:</td>
		  <td><?= $sBrand ?></td>
	    </tr>  
    
	   <tr>
		  <td>Vendor</td>
		  <td align="center">:</td>
		  <td><?= $sVendor ?></td>
	    </tr>

	    <tr valign="top">
		  <td>Auditor</td>
		  <td align="center">:</td>
		  <td><?= $sAuditorsList[$iAuditor]; ?></td>
	    </tr>
              
              
            <tr valign="top">
		  <td>Unit</td>
		  <td align="center">:</td>
		  <td><?= $sUnitsList[$iUnit] ?></td>
	    </tr>  
            
            <tr valign="top">
		  <td>Audit Type</td>
		  <td align="center">:</td>
		  <td><?= $sAuditTypesList[$sAuditType]; ?></td>
	    </tr>
              
            <tr valign="top">
		  <td>Audit Section</td>
		  <td align="center">:</td>
		  <td><?= $sSectionList[$Section] ?></td>
	    </tr>
              
            <tr valign="top">
		  <td>Audit Points</td>
		  <td align="center">:</td>
                  <td>
<?
                    if($ddQuestion == 'S')
                    {
?>
                      <tr id="PointsBlock2"> <td>&nbsp</td>
                                            <td colspan="2">
                                                <div id="Questions">
<?
                                            $iAuditSections = explode(",", $AuditSections);
                                            $sBrandSections   = getList("tbl_tnc_points", "DISTINCT section_id", "section_id", "FIND_IN_SET('$Brand', brands)");
                                            $sSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='$Section' AND id IN (". implode(",", $sBrandSections).")");
                                            
                                            foreach($sSectionsList as $iSection => $sSection)
                                            {
                                                echo "<h3><div style='padding-bottom:1px;'><input type='checkbox' name=Sections[] id='".$iSection."' value='".$iSection."' ".(@in_array($iSection, $iAuditSections)?'checked':'')." disabled/><label for='".$iSection."'>".$sSection."</label></div></h3><br/>";
                                            }
?>
                                                </div>
                                            </td>
                                        </tr> 
<?
                    }
                    else    
                    {
                        $sBrandSections   = getList("tbl_tnc_points", "DISTINCT section_id", "section_id", "FIND_IN_SET('$iBrand', brands)");
                        $sSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='$Section' AND id IN (". implode(",", $sBrandSections).")");

                        foreach($sSectionsList as $iSection => $sSection)
                        {
?>
                            <h2><?= $sSection ?></h2>
<?
                            $sCategoriesList  = getList("tbl_tnc_categories", "id", "category", (($iSection > 0) ? "section_id='$iSection'" : ""), "position");
                            foreach ($sCategoriesList as $iCategory => $sCategory)
                            {
?>    
                                <h3><?= $sCategory ?></h3>
<?
                                $sPointsList = getList("tbl_tnc_points", "id", "point", "category_id=$iCategory AND FIND_IN_SET('$iBrand', brands)");

                                foreach ($sPointsList as $iPoint => $sPoint)
                                {
?>
                                <div style='padding-bottom:1px;'><input type='checkbox' name=Points[] id='<?=$iPoint?>' value='<?=$iPoint?>' <?=(@in_array($iPoint, explode(",", $sPoints))?'checked':'')?> disabled/><label for='<?=$iPoint?>'><?=$sPoint?></label></div><br/>
<?
                                }
                            }

                        }
                    }
?>
                  </td>
	    </tr>  
	  </table>

	  <br />

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