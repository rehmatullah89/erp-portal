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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_designations WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sDesignation    = $objDb->getField(0, 'designation');
		$iDepartment     = $objDb->getField(0, 'department_id');
		$iReportingTo    = $objDb->getField(0, 'reporting_to');
		$sJobDescription = $objDb->getField(0, 'job_description');


		$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");
		$sReportingTo = getDbValue("designation", "tbl_designations", "id='$iReportingTo'");
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
	<div id="Body" style="min-height:394px; height:394px;">
	  <h2>Designation Details</h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">

		    <table border="0" cellpadding="3" cellspacing="0" width="98%" align="center">
			  <tr>
			    <td width="75">Designation</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sDesignation ?></td>
			  </tr>

			  <tr>
			    <td>Department</td>
			    <td align="center">:</td>
			    <td><?= $sDepartment ?></td>
			  </tr>

			  <tr>
			    <td>Reporting To</td>
			    <td align="center">:</td>
			    <td><?= $sReportingTo ?></td>
			  </tr>

			  <tr>
			    <td colspan="3">
			      <br />
			      <b>Job Description</b><br />
			      <br />
<?
	$sJobDescription = @str_replace("\r\n\r\n", "\n", $sJobDescription);
	$sJobDescription = @str_replace("\n\n", "\n", $sJobDescription);
	$sJobDescription = @str_replace("\r\n", "\n", $sJobDescription);
	$sJobDescription = @explode("\n", $sJobDescription);

	if (count($sJobDescription) > 0)
	{
		$bStart     = true;
		$bSubBullet = false;
		$iCount     = count($sJobDescription);

		for ($i = 0; $i < $iCount; $i ++)
		{
			if (substr($sJobDescription[$i], 0, 2) == "o " && $bSubBullet == false)
			{
?>
				    <li style="list-style:none;">
				      <ul>
<?
				$bSubBullet = true;
			}

			else if (substr($sJobDescription[$i], 0, 2) != "o " && $bSubBullet == true)
			{
?>
				      </ul>
				    </li>
<?
				$bSubBullet = false;
			}

			if (substr($sJobDescription[$i], 0, 2) == "h ")
			{
				if ($i > 0)
				{
?>
			      </ul>
<?
				}
?>
			      <div style="padding:5px 0px 5px 0px;"><b><?= substr($sJobDescription[$i], 2) ?></b></div>
<?
				$bStart = true;
			}

			else
			{
				if ($bStart == true)
				{
?>
			      <ul class="hr">
<?
					$bStart = false;
				}
?>
				    <li><?= ((substr($sJobDescription[$i], 0, 2) == "o ") ? substr($sJobDescription[$i], 2) : $sJobDescription[$i]) ?></li>
<?
			}

			if ($i == ($iCount - 1) && $bSubBullet == true)
			{
?>
				      </ul>
				    </li>
<?
				$bSubBullet = false;
			}
		}
?>
			      </ul>
<?
	}
?>
			    </td>
			  </tr>
		    </table>

		  </td>
	    </tr>
	  </table>

	  <br style="line-height:2px;" />
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