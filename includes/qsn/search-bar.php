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

	$Vendor     = IO::intValue("Vendor".$iIndex);
	$Brand      = IO::intValue("Brand".$iIndex);
	$Style      = IO::strValue("Style".$iIndex);
	$Po         = IO::strValue("Po".$iIndex);
	$Line       = @implode(",", IO::getArray("Line".$iIndex));
	$FromDate   = IO::strValue("FromDate".$iIndex);
	$ToDate     = IO::strValue("ToDate".$iIndex);
	$ReportType = IO::strValue("ReportType".$iIndex);
	$AuditStage = IO::strValue("AuditStage".$iIndex);
	$sVendor    = "";
	$sBrand     = "";

	$FromDate     = (($FromDate == "") ? date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y"))) : $FromDate);
	$ToDate       = (($ToDate == "") ? date("Y-m-d") : $ToDate);
?>
					    <div class="qsnSearch">
					      <table border="0" cellpadding="0" cellspacing="0" width="100%">
						    <tr bgcolor="#494949">
						      <td width="8"></td>
<?
	if ($Mode == "Brands" ||$Mode == "VendorsBrands")
	{
?>
						      <td width="52"><b>Brand</b></td>

						      <td width="180">
							    <select name="Brand<?= $iIndex ?>" id="Brand<?= $iIndex ?>">
							      <option value=""></option>
<?
		foreach($sBrandsList as $sKey => $sValue)
		{
			if (!@in_array($sKey, @explode(",", $sBrands)))
				continue;


			if ($sKey == $Brand)
				$sBrand = $sValue;
?>
						  	      <option value="<?= $sKey ?>" <?= (($sKey == $Brand) ? 'selected' : '') ?>><?= $sValue ?></option>
<?
		}
?>
							    </select>
						      </td>
<?
	}
?>
						      <td width="85"><b>Report Type</b></td>

						      <td>
							    <select name="ReportType<?= $iIndex ?>" id="ReportType<?= $iIndex ?>" style="width:128px;">
							      <option value=""></option>
							      <option value="5 Major Defects" <?= (($ReportType == "5 Major Defects") ? 'selected' : '') ?>>5 Major Defects</option>
							      <option value="High DR" <?= (($ReportType == "High DR") ? 'selected' : '') ?>>High DR</option>
							    </select>
						      </td>
						    </tr>
					      </table>

					      <div style="height:8px; background:#494949;"></div>
					      <div style="height:8px;"></div>

                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
						      <td width="8"></td>
						      <td width="40"><b>Style</b></td>
						      <td width="95"><input type="text" name="Style<?= $iIndex ?>" value="<?= $Style ?>" class="textbox" size="10" /></td>
						      <td width="30"><b>PO</b></td>
						      <td width="95"><input type="text" name="Po<?= $iIndex ?>" value="<?= $Po ?>" class="textbox" size="10" /></td>

							  <td width="85"><b>Audit Stage</b></td>

							  <td>
								<select name="AuditStage<?= $iIndex ?>">
								  <option value="">All Stages</option>
								  <option value="B">Batch</option>
								  <option value="C">Cutting</option>
								  <option value="F">Final</option>
								  <option value="O">Output</option>
								  <option value="S">Sorting</option>
								  <option value="ST">Stitching</option>
								  <option value="FI">Finishing</option>
								  <option value="OL">Off Loom</option>
								  <option value="SK">Stock</option>
								</select>

								<script type="text/javascript">
								<!--
								  document.frmSearch.AuditStage<?= $iIndex ?>.value = "<?= $AuditStage ?>";
								-->
								</script>
							  </td>
			                </tr>
			              </table>

					      <div style="height:8px;"></div>
					      <div style="height:1px; background:#494949;"></div>
					      <div style="height:5px;"></div>

<?
		if ($Mode == "Vendors" || $Mode == "VendorsBrands")
		{
?>
                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr valign="top">
						      <td width="8"></td>
						      <td width="52"><b>Vendor</b></td>

						      <td width="180">
							    <select name="Vendor<?= $iIndex ?>" id="Vendor<?= $iIndex ?>">
							      <option value=""></option>
<?
			foreach($sVendorsList as $sKey => $sValue)
			{
				if (!@in_array($sKey, @explode(",", $sVendors)))
					continue;


				if ($sKey == $Vendor)
					$sVendor = $sValue;
?>
						  	      <option value="<?= $sKey ?>" <?= (($sKey == $Vendor) ? 'selected' : '') ?>><?= $sValue ?></option>
<?
			}
?>
							    </select>
						      </td>

						      <td width="35"><b>Line</b></td>

						      <td>
								<select id="Line<?= $iIndex ?>" name="Line<?= $iIndex ?>" multiple size="10" style="width:95%;">
<?
			$sSQL = "SELECT id, line FROM tbl_lines WHERE vendor_id='$Vendor' ORDER BY line";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);
?>
	  	        		  		  <option value="<?= $sKey ?>"<?= (($sKey == $Line) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
			            	    </select>
						      </td>
			                </tr>
			              </table>

					      <div style="height:8px;"></div>
					      <div style="height:1px; background:#494949;"></div>
					      <div style="height:5px;"></div>
<?
		}
?>

                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
						      <td width="8"></td>
						      <td width="40"><b>Form</b></td>
						      <td width="78"><input type="text" name="FromDate<?= $iIndex ?>" value="<?= $FromDate ?>" id="FromDate<?= $iIndex ?>" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('FromDate<?= $iIndex ?>'), 'yyyy-mm-dd', this);" /></td>
						      <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate<?= $iIndex ?>'), 'yyyy-mm-dd', this);" /></td>
						      <td width="35" align="center"><b>To</b></td>
						      <td width="78"><input type="text" name="ToDate<?= $iIndex ?>" value="<?= $ToDate ?>" id="ToDate<?= $iIndex ?>" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('ToDate<?= $iIndex ?>'), 'yyyy-mm-dd', this);" /></td>
						      <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate<?= $iIndex ?>'), 'yyyy-mm-dd', this);" /></td>
						      <td width="70" align="center">[ <a href="./" onclick="$('FromDate<?= $iIndex ?>').value=''; $('ToDate<?= $iIndex ?>').value=''; return false;" style="color:#eeeeee;">Clear</a> ]</td>
						      <td align="right"><input type="submit" value="" class="btnGo" title="Go!" /></td>
						      <td width="8"></td>
			                </tr>
			              </table>

			              <div style="height:5px;"></div>
			            </div>
