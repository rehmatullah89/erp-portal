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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$User    = IO::strValue('User');
	$StyleId = IO::intValue('StyleId');

	if ($User == "" || $StyleId == 0)
		die("Invalid Request");


	$sSQL = "SELECT status FROM tbl_users WHERE MD5(id)='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		die("Invalid User");

	else if ($objDb->getField(0, "status") != "A")
		die("User Account is Disabled");
?>

<!DOCTYPE html>

<html lang="en">

<head>
<?
	@include("../includes/meta-tags.php");
?>
</head>

<body>

<section id="Comments">
<?
	$sSQL = "SELECT * FROM tbl_style_comments WHERE style_id='$StyleId' ORDER BY `date` DESC";
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

				if ($sPicture == "" || !@file_exists(ABSOLUTE_PATH.USERS_IMG_PATH.'thumbs/'.$sPicture))
					$sPicture = "default.jpg";

				$sDesignation = getDbValue("designation", "tbl_designations", "id='$iDesignation'");
			}
?>
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr valign="top">
	  <td width="78"><div class="picture"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" width="64" alt="<?= $sName ?>" title="<?= $sName ?>" /></div></td>

	  <td>
		<b><?= $sUser ?></b><br />
		<?= $sDesignation ?><br />
		<i class="dateTime"><?= formatDate($sDate, "F j, Y") ?></i><br />
	  </td>
<!--
	  <td align="right">
		<b><?= (($iMerchandisingId > 0) ? getDbValue("type", "tbl_sampling_types", "id=(SELECT sample_type_id FROM tbl_merchandisings WHERE id='$iMerchandisingId')") : "") ?></b><br />
		<i><?= $sNature ?></i><br />
	  </td>
-->
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
  No Comments posted yet!<br />
<?
	}
?>
</section>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>