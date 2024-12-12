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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/aql-list.js"></script>
  <style>
.zoom {
    transition: transform .2s; /* Animation */
    width: 500px;
    margin: 0 auto;
}

.zoom:hover {
    transform: scale(1.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>
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
			    <h1>AQL Chart</h1>

                            <div class="tblSheet" style="width:100%;">
                                <br/>
                                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="50%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                
                                                <tr>
                                                      <td width="155">General Inspection Level<span class="mandatory">*</span></td>
                                                      <td width="20" align="center">:</td>
                                                      <td>
                                                          <select name="InspecLevel" id="InspecLevel"  style="width: 150px;" onchange="resetValue();">
                                                              <option value="1">Level - I</option>
                                                              <option value="2" selected="">Level - II</option>
                                                              <option value="3">Level - III</option>
                                                          </select>
                                                      </td>
                                                </tr>
                                                
                                                <tr>
                                                      <td>AQL<span class="mandatory">*</span></td>
                                                      <td align="center">:</td>
                                                      <td>
                                                          <select name="AQL" id="AQL"  style="width: 150px;" onchange="resetValue();">
                                                              <option value="0.065">0.065</option>
                                                              <option value="0.10">0.10</option>
                                                              <option value="0.15">0.15</option>
                                                              <option value="0.25">0.25</option>
                                                              <option value="0.40">0.40</option>
                                                              <option value="0.65">0.65</option>
                                                              <option value="1.0">1.0</option>
                                                              <option value="1.5">1.5</option>
                                                              <option value="2.5" selected="">2.5</option>
                                                              <option value="4.0">4.0</option>
                                                              <option value="6.5">6.5</option>
                                                          </select>
                                                      </td>
                                                </tr>
                                                
                                                <tr>
                                                      <td width="155">Lot Size<span class="mandatory">*</span></td>
                                                      <td width="20" align="center">:</td>
                                                      <td>
                                                          <select name="LotSize" id="LotSize" style="width: 150px;"  onchange="resetValue();">
                                                              <option value="8">2 to 8</option>
                                                              <option value="15">9 to 15</option>
                                                              <option value="25">16 to 25</option>
                                                              <option value="50">26 to 50</option>
                                                              <option value="90">51 to 90</option>
                                                              <option value="150">91 to 150</option>
                                                              <option value="280">151 to 280</option>
                                                              <option value="500">281 to 500</option>
                                                              <option value="1200">501 to 1200</option>
                                                              <option value="3200">1201 to 3200</option>
                                                              <option value="10000">3201 to 10000</option>
                                                              <option value="35000">10001 to 35000</option>
                                                              <option value="150000">35001 to 150000</option>
                                                              <option value="500000">150001 to 500000</option>
                                                              <option value="500001">500001 and over</option>
                                                          </select>
                                                      </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                    <td>
                                                        <input type="button" name="Calculate" value="Calculate" onclick="getAqlInfo();" style="padding: 3px; cursor: pointer;"/>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="50%" style="background: lightgray;">
                                            <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                <tr style="font-size: 18px !important; font-weight: bold;">
                                                    <td width="150">Sample Size : </td>
                                                    <td id="SampleSizeId"></td>
                                                </tr>

                                                <tr style="font-size: 18px !important; font-weight: bold;">
                                                    <td width="150">Max. Defects Allowed : </td>
                                                    <td id="DefectsAllowedId"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <br/><br/><br/><br/>
                                <table style="width: 100%;">
                                    <tr>
                                        <td width="46%"><img src="images/sample_size.jpg" class="zoom" style="width: 100%;"/></td>
                                        <td width="8%"><img src="images/double-arrow.png" style="width: 100%; background-color: white;"/></td>
                                        <td width="46%"><img src="images/sample_plan.jpg" class="zoom" style="width: 100%;"/></td>
                                    </tr>
                                </table>
			    </div>


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