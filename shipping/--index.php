<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Salamat School Systems                                                                   **
	**  Version 1.0                                                                              **
	**                                                                                           **
	**  Copyright 2010 (C) Salamat School Systems                                                **
	**  http://www.sss.edu.pk                                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://mts.sw3solutions.com                                                 **
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

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
			    <h1><img src="images/h1/dashboard.jpg" width="159" height="20" vspace="10" alt="" title="" /></h1>

			    <div class="tblSheet divDashboard">
			      <div style="margin:0px 1px 1px 0px; padding:6px 3px 6px 3px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sLinks    = array( );
	$sPictures = array( );
	$sModule   = "Shipping";
	
	
	$sSQL = "SELECT p.section, p.scripts
	         FROM tbl_pages p, tbl_user_rights ur
	         WHERE p.id=ur.page_id AND ur.view='Y' AND ur.user_id='{$_SESSION['UserId']}' AND p.module='{$sModule}'
	         ORDER BY p.section";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sTitle   = $objDb->getField($i, "section");
		$sScripts = $objDb->getField($i, "scripts");
		
		$sScripts = @explode(",", $sScripts);
		$sScript  = trim($sScripts[0], "'");

			
		$sLinks[] = array($sTitle, $sScript);
	}

	
	$iRows = @ceil($iCount / 3);

	for ($i = 0, $j = 0; $j < $iRows; $j ++)
	{
		if ($j > 0)
		{
?>

			          <tr>
			            <td colspan="3" height="10"></td>
			          </tr>

<?
		}

?>
			          <tr>
<?
		for ($k = 0; $k < 3; $k ++)
		{
			if ($i < $iCount)
			{
				$sPicture = str_replace(".php", ".jpg", $sLinks[$i][1]);
				
				if (!@file_exists("images/dashboard/{$sCurDir}/{$sPicture}"))
					$sPicture = "place-holder.jpg";
?>
			            <td width="3<?= (($k == 1) ? 4 : 3) ?>%" align="<?= (($k == 1) ? 'center' : (($k == 2) ? 'right' : 'left')) ?>">
						  <div class="dashboardLink">
						    <a href="<?= ($sCurDir."/".$sLinks[$i][1]) ?>" class="image"><img src="images/dashboard/<?= $sCurDir ?>/<?= $sPicture ?>" width="300" height="150" alt="" title="" /></a>
							<a href="<?= ($sCurDir."/".$sLinks[$i][1]) ?>" class="title"><?= $sLinks[$i][0] ?></a>
						  </div>
						</td>
<?
			}

			else
			{
?>
			            <td width="3<?= (($k == 1) ? 4 : 3) ?>%" align="<?= (($k == 1) ? 'center' : (($k == 2) ? 'right' : 'left')) ?>"><img src="images/dashboard/<?= $sCurDir ?>/place-holder.jpg" width="300" height="150" alt="" title="" /></td>
<?
			}

			$i ++;
		}
?>
			          </tr>
<?
	}
?>
			        </table>
					
			      </div>
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

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>