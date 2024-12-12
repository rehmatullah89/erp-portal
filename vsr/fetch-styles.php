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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Style    = IO::strValue("Style");
	$Brand    = IO::intValue("Brand");

	$Vendor   	= IO::intValue("Vendor");
	$Brand    	= IO::intValue("Brand");
	$Region   	= IO::intValue("Region");
	$Season   	= IO::intValue("Season");
	$Department = IO::intValue("Department");

	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$Week_Start_Date = IO::strValue("Week_Start_Date");
	$Week_End_Date   = IO::strValue("Week_End_Date");

	if($Week_End_Date  == '' && $Week_End_Date ==''){

		$Week_Start_Date = $arrDates[1];
		$Week_End_Date 	= $arrDates[2];

	}

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']}) AND id!='43'");
	$sSeasonsList = array( );

	if ($Brand > 0)
	{
		$iParent = getDbValue("parent_id", "tbl_brands", "id='$Brand'");

		$sSeasonsList    = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
		$sCategoriesList = getList("tbl_style_categories", "id", "category", "id IN (SELECT DISTINCT(category_id) FROM tbl_styles WHERE sub_brand_id='$Brand') AND FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
	}

	else
		$sCategoriesList = getList("tbl_style_categories", "id", "category", "FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/sampling/styles.js"></script>
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
			    <h1><img src="images/h1/sampling/styles-listing.jpg" width="196" height="20" vspace="10" alt="" title="" /></h1>



			    <div class="tblSheet">
<?
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Week_Start_Date != "" && $Week_End_Date != "")
			$sSQL .= " poc.etd_required BETWEEN '$Week_Start_Date' and '$Week_End_Date'  ";

	//if ($Department > 0)
	//	$sConditions .= " AND d.id = $Department ";

	if ($Brand > 0)
		$sConditions .= " AND b.id = $Brand ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5)." GROUP BY poc.po_id ORDER BY OrdrQty DESC ");



	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_po_colors AS poc LEFT JOIN tbl_vsr_details AS vd ON poc.po_id=vd.po_id
				 LEFT JOIN tbl_po AS po ON poc.po_id= po.id
				 LEFT JOIN tbl_departments AS d ON FIND_IN_SET(po.brand_id,d.brands)
				 LEFT JOIN tbl_brands AS b ON po.brand_id=b.id
				 LEFT JOIN tbl_styles AS st ON poc.style_id=st.id", $sConditions, $iPageSize, $PageId);


	//$sSQL = "SELECT st.id, st.style, st.sketch_file FROM tbl_styles $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";

	$sSQL = "SELECT st.id, st.style, st.sketch_file, po.brand_id,b.brand,
					SUM(poc.order_qty) AS OrdrQty,
					SUM(if(poc.po_id = vd.po_id AND vd.final_date <= poc.etd_required AND vd.final_date IS NOT NULL AND  vd.final_date != '0000-00-00',poc.order_qty,0))
					AS OntimeOrdrQty,
					 COUNT( DISTINCT CASE WHEN (poc.po_id = vd.po_id AND vd.final_date > poc.etd_required OR vd.final_date IS NULL OR vd.final_date = '0000-00-00') THEN poc.po_id END)
					 AS shortQty,
					COUNT( DISTINCT CASE WHEN ( poc.po_id = vd.po_id AND vd.final_date <= poc.etd_required AND vd.final_date IS NOT NULL AND  vd.final_date != '0000-00-00') THEN poc.po_id END)
					AS ontime,
					COUNT(DISTINCT(poc.po_id)) AS total_order
			FROM
				 tbl_po_colors AS poc LEFT JOIN tbl_vsr_details AS vd ON poc.po_id=vd.po_id
				 LEFT JOIN tbl_po AS po ON poc.po_id= po.id
				 LEFT JOIN tbl_departments AS d ON FIND_IN_SET(po.brand_id,d.brands)
				 LEFT JOIN tbl_brands AS b ON po.brand_id=b.id
				 LEFT JOIN tbl_styles AS st ON poc.style_id=st.id
			WHERE
  				poc.etd_required BETWEEN '$Week_Start_Date' and '$Week_End_Date'

  				and b.id = $Brand
  			GROUP BY poc.po_id ORDER BY OrdrQty DESC";

  	//print $sSQL;

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
			      <h2 style="background:#a1a1a1; padding:0px; height:25px; line-height:25px;">
			        <a href="sampling/dashboard.php?Brand=<?= $Brand ?>&Type=<?= $Type ?>&Season=<?= $Season ?>&Status=<?= $Status ?>&Category=<?= $Category ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>"><img src="images/icons/list.gif" width="30" height="20" alt="Timeline View" title="Timeline View" border="0" style="float:right; margin:2px 0px 0px 0px;" /></a>
			      </h2>
<?
	}
?>
			      <table border="0" cellpadding="5" cellspacing="0" width="100%">
<?
	for ($i = 0; $i < $iCount;)
	{
?>
				    <tr>
<?
		for ($j = 0; $j < 9; $j ++)
		{
			if (@in_array($j, array(1,3,5,7)))
			{
?>
				      <td width="1"></td>
<?
				continue;
			}
?>
				      <td width="160">
<?
			if ($i < $iCount)
			{
				$iId         = $objDb->getField($i, 'id');
				$sStyle      = $objDb->getField($i, 'style');
				$sSketchFile = $objDb->getField($i, 'sketch_file');

				$OntimeOrdrQty = $objDb->getField($i, 'OntimeOrdrQty');
				$OrdrQty = $objDb->getField($i, 'OrdrQty');

				if ($sSketchFile == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
					$sSketchFile = (STYLES_SKETCH_DIR."default.jpg");

				else
				{
					if (!@file_exists($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile))
						createImage(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

					$sSketchFile = (STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
				}
?>
				        <center>
				          <a href="sampling/style-details.php?Id=<?= $iId ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="<?= $sSketchFile ?>" width="160" height="160" vspace="4" alt="" title="" style="border:solid 1px #cccccc;" /></a><br />
				          <b><?= $sStyle;?></b><br />
				          <? print "Order :".number_format($OrdrQty)." pcs";?> <br />
				          <? print "Ontime :".number_format($OntimeOrdrQty)." pcs";?> <br />
				        </center>
<?
				$i ++;
			}
		}
?>
				    </tr>
<?
		if ($i < $iCount)
		{
?>
				    <tr>
				      <td colspan="9" height="15"></td>
				    </tr>
<?
		}
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Style Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Style={$Style}&Brand={$Brand}&Season={$Season}&Category={$Category}&Type={$Type}&FromDate={$FromDate}&ToDate={$ToDate}&Status={$Status}");
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