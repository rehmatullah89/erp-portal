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

	$Id = IO::intValue("Id");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv" style="width:auto;">
  <h2>Style Comments</h2>

  <div style="padding:10px; width:680px;">
<?
	$sSQL = "SELECT * FROM tbl_style_comments WHERE style_id='$Id' ORDER BY `date` DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
		for ($i = 0; $i < $iCount; $i ++)
		{
			$sFrom            = $objDb->getField($i, "from");
			$sDate            = $objDb->getField($i, "date");
			$sNature          = $objDb->getField($i, "nature");
			$sComments        = $objDb->getField($i, "comments");
			$iMerchandisingId = $objDb->getField($i, "merchandising_id");
			$iUserId          = $objDb->getField($i, "user_id");
			$iDateTime        = $objDb->getField($i, "date_time");

			if ($sFrom == "Buyer")
			{
				$sUser        = "Buyer";
				$sDesignation = "";
				$sPicture     = "default.jpg";
			}

			else
			{
				$sSQL = "SELECT designation_id, name, picture FROM tbl_users WHERE id='$iUserId'";
				$objDb2->query($sSQL);

				$sUser        = $objDb2->getField(0, "name");
				$iDesignation = $objDb2->getField(0, "designation_id");
				$sPicture     = $objDb2->getField(0, "picture");

				if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
					$sPicture = "default.jpg";

				$sDesignation = getDbValue("designation", "tbl_designations", "id='$iDesignation'");
			}
?>
	  <table width="100%" cellspacing="0" cellpadding="0" border="0">
	    <tr valign="top">
		  <td width="78"><div style="border:solid 1px #888888; padding:1px; margin-right:10px;"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" width="64" alt="<?= $sName ?>" title="<?= $sName ?>" /></div></td>

		  <td>
		    <b><?= $sUser ?></b><br />
		    <?= $sDesignation ?><br />
		    <i class="dateTime" style="font-size:10px;"><?= formatDate($sDate, "F j, Y") ?></i><br />
		  </td>

		  <td width="300" align="right">
		    <b><?= (($iMerchandisingId > 0) ? getDbValue("type", "tbl_sampling_types", "id=(SELECT sample_type_id FROM tbl_merchandisings WHERE id='$iMerchandisingId')") : "") ?></b><br />
		    <i><?= $sNature ?></i><br />
		  </td>
	    </tr>
	  </table>

	  <br />
	  <?= nl2br($sComments) ?><br />

<?
			if ($i < ($iCount - 1))
			{
?>
	  <hr />

<?
			}
		}
	}

	else
	{
?>
	  <b>No Comments posted yet!</b><br />
<?
	}
?>
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