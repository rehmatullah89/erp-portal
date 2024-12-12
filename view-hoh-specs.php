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

	@require_once("requires/session.php");

	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id             = IO::intValue('Id');
        $OrderId        = IO::intValue('OrderId');
        $StyleDetailId  = IO::intValue('StyleDetailId');
        $SizeId         = IO::intValue('SizeId');
        
                
       $sSQL = "SELECT o.id, o.order_no, osd.style_id, osd.weight, osd.product_name, osd.product_description, osd.target_group, osd.category_code, o.ian, osd.identifier_no, osd.composition,
                                od.size_id, od.color, od.ean,
		                (SELECT supplier FROM tbl_suppliers WHERE id=o.supplier_id) AS _Supplier,
                                (SELECT size FROM tbl_sampling_sizes WHERE id=od.size_id) AS _Size,
                                (SELECT concat(style_name,' - ', style) FROM tbl_styles WHERE id=osd.style_id) AS _Style,
                                (SELECT GROUP_CONCAT(season SEPARATOR ', ') FROM tbl_seasons WHERE id IN (SELECT sub_season_id FROM tbl_styles WHERE id IN (osd.style_id)) GROUP BY id) AS _Season,
		                (SELECT vendor FROM tbl_vendors WHERE id=o.vendor_id) AS _Vendor
		         FROM tbl_hoh_orders o, tbl_hoh_order_style_details osd, tbl_hoh_order_details od
		         WHERE o.id=osd.hoh_order_id AND osd.id=od.style_detail_id AND o.id='$OrderId' AND osd.style_id='$Id' AND od.size_id='$SizeId'";
        $objDb->query($sSQL);

        $sOrderNo     = $objDb->getField(0, 'order_no');
        $iStyle       = $objDb->getField(0, 'style_id');
        $sStyle       = $objDb->getField(0, '_Style');
        $iSize        = $objDb->getField(0, 'size_id');
        $sSize        = $objDb->getField(0, '_Size');
        $sEan         = $objDb->getField(0, 'ean');
        $sColor       = $objDb->getField(0, 'color');
        $sSupplier    = $objDb->getField(0, '_Supplier');
        $sVendor      = $objDb->getField(0, '_Vendor');
        $sSeason      = $objDb->getField(0, '_Season');
        $sWeight      = $objDb->getField(0, 'weight');
        $sComposition = $objDb->getField(0, 'composition');
        $sProductName = $objDb->getField(0, 'product_name');
        $sProductDesc = $objDb->getField(0, 'product_description');
        $sTargetGroup = $objDb->getField(0, 'target_group');
        $sCategoryCode= $objDb->getField(0, 'category_code');
        $sIAN         = $objDb->getField(0, 'ian');
        $sIdentifier  = $objDb->getField(0, 'identifier_no');

	$sSQL = "SELECT mp.point_en, mp.tolerance, mp.position, mp.comments, ss.specs, mp.tolerance_unit					
			 FROM tbl_hoh_measurement_points mp, tbl_hoh_style_specs ss
			 WHERE mp.style_id='$Id' AND mp.hoh_order_id='$OrderId' AND style_detail_id='$StyleDetailId' AND mp.id=ss.point_id AND ss.size_id='$SizeId'";
        //echo $sSQL;exit;
	$objDb->query($sSQL);

        $iCount = $objDb->getCount( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
</head>
<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:514px; height:514px;">
	  <h2>Style Specs</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="60"><b>Style No</b></td>
		  <td width="20" align="center">:</td>
		  <td><b><?= $sStyle ?></b></td>
                  
                  <td width="130"><b>Product Name</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sProductName ?></td>
	    </tr>
            <tr>
		  <td width="60"><b>Size</b></td>
		  <td width="20" align="center">:</td>
		  <td><b><?= $sSize ?></b></td>
                  
                  <td width="60"><b>Product Description</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sProductDesc ?></td>
	    </tr>
            <tr>
		  <td width="60"><b>Color</b></td>
		  <td width="20" align="center">:</td>
		  <td><b><?= $sColor ?></b></td>
                  
                  <td width="60"><b>Target Group</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sTargetGroup ?></td>
	    </tr>
            <tr>
		  <td width="60"><b>Season</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sSeason ?></td>
                  
                   <td width="60"><b>Category Code</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sCategoryCode ?></td>
	    </tr>
            <tr>
		  <td width="60"><b>Supplier</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sSupplier ?></td>
                  
                  <td width="60"><b>IAN No</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sIAN ?></td>
	    </tr>
            <tr>
		  <td width="60"><b>Vendor</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sVendor ?></td>
                  
                  <td width="60"><b>Identifier</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sIdentifier ?></td>
	    </tr>
            <tr>
		  <td width="60"><b>Ean</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sEan ?></td>
                                   
                  <td width="60"><b>Composition</td>
		  <td width="20" align="center">:</td>
		  <td><?= $sComposition ?></td>                  
	    </tr>  
            <tr>
		  <td width="60"><b>Weight</b></td>
		  <td width="20" align="center">:</td>
		  <td><?= $sWeight ?></td>
	    </tr>  

	  </table>
	  <br />
	  <h2>Measurement Specs</h2>

	  <div style="margin:0px 8px 0px 8px; overflow:hidden;">
	    <div style="width:100%; overflow:auto;">
		  <table border="1" bordercolor="#ffffff" cellpadding="3" cellspacing="0" width="100%">
                      <tr class="sdRowHeader">
                          <td  align="center" width="60"><b> Sketch Position</b></td>
                          <td align="center"><b>Measurement Point</b></td>
                          <td align="center" width="70"><b>Tolerance</b></td>
                          <td align="center" width="70"><b>Tol. Unit</b></td>
                          <td  align="center" width="60"><b>Spec (cm)</b></td>                          
                          <td  align="center" width="160"><b>Comments</b></td>
                      </tr>
		    
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sPoint         = $objDb->getField($i, 'point_en');
		$sTolerance     = $objDb->getField($i, 'tolerance');
                $sToleranceUnit = $objDb->getField($i, 'tolerance_unit');
                $sPosition      = $objDb->getField($i, 'position');
                $sComments      = $objDb->getField($i, 'comments');
                $sSpecs         = $objDb->getField($i, 'specs');
?>

			<tr class="sdRowColor">
                          <td align="center"><?= $sPosition ?></td>
                          <td align="left"><?= $sPoint ?></td>
                          <td align="center"><?= $sTolerance ?></td>
                          <td align="center"><?= ($sToleranceUnit == 0?'cm':'%age') ?></td>
                          <td align="center"><?= $sSpecs ?></td>
                          <td align="left"><?= $sComments ?></td>
			</tr>
<?
	}
?>
		  </table>
	    </div>
	  </div>

	  <br style="line-height:2px;" />
	</div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>