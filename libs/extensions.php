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
			  <td width="585">
			    <h1>Extensions</h1>

			    <div class="tblSheet">
			      <img src="images/headers/libs/extensions.jpg" width="581" height="205" alt="" title="" /><br />

			      <div style="padding:10px 10px 25px 10px;">
			        Office Staff Telephone Extensions List - <b>Pakistan ONLY</b><br />

<?
	$sSQL = "SELECT id, department FROM tbl_departments ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iIndex = 1;
	$sClass = array("evenRow", "oddRow");

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDepartmentId = $objDb->getField($i, 0);
		$sDepartment   = $objDb->getField($i, 1);

		$sSQL = "SELECT name, mobile, phone_ext FROM tbl_users WHERE country_id='162' AND phone_ext!='' AND status='A' AND designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$iDepartmentId') ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 == 0)
			continue;
?>
			        <h2 style="margin:20px 0px 0px 0px; background:none; color:#333333; padding-left:0px; font-size:18px; font-weight:normal;"><?= $sDepartment ?></h2>

			        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				      <tr class="headerRow">
				        <td width="5%" class="center">#</td>
				        <td width="50%">Employee Name</td>
				        <td width="30%">Mobile No</td>
				        <td width="15%" class="center">Extension</td>
				      </tr>

<?
		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sName      = $objDb2->getField($j, 0);
			$sMobile    = $objDb2->getField($j, 1);
			$sExtension = $objDb2->getField($j, 2);
?>
			          <tr class="<?= $sClass[($j % 2)] ?>">
			            <td width="5%" class="center"><?= $iIndex ++ ?></td>
			            <td width="50%"><?= $sName ?></td>
			            <td width="30%"><?= $sMobile ?></td>
			            <td width="15%" class="center"><?= $sExtension ?></td>
			          </tr>
<?
		}
?>
			        </table>
<?
	}
?>
			      </div>

				  <div class="buttonsBar" style="margin:20px 1px 2px 1px;">
				    <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="document.location='<?= (SITE_URL."libs/export-extensions.php") ?>';" />
				  </div>
			    </div>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/contact-info.php");
?>
			  </td>
			</tr>
		  </table>
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