<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Vendor   = IO::intValue("Vendor");
	$Category = IO::intValue("Category");


	$sQuestionsList = getList("tbl_safety_questions", "id", "title");
	$sCategory      = getDbValue("title", "tbl_safety_categories", "id='$Category'");


	$sSQL = "SELECT title, question_id, `date`, picture FROM tbl_audit_pictures WHERE vendor_id='$Vendor' AND category_id='$Category' ORDER BY `date`, question_id, title";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
{
	"timeline":
	{
		"headline":"Safety Footprint",
		"type":"default",
		"text":"<?= $sCategory ?>",
		"startDate":"<?= date("Y,m,d") ?>",

		"asset":
		{
			"media":"http://portal.3-tree.com/images/logo.png",
			"credit":"-",
			"caption":"-"
		},

		"date":
		[
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sTitle     = $objDb->getField($i, "title");
		$iQuestion  = $objDb->getField($i, "question_id");
		$sAuditDate = $objDb->getField($i, "date");
		$sPicture   = $objDb->getField($i, "picture");

		if ($sPicture == "" || !@file_exists($sBaseDir.CRC_AUDITS_IMG_PATH.$sPicture))
			continue;
?>
			{
				"startDate":"<?= formatDate($sAuditDate, "Y,m,j,G,i,s") ?>",
				"endDate":"<?= formatDate($sAuditDate, "Y,m,j,G,i,s") ?>",
				"headline":"<?= formatDate($sAuditDate, "F Y") ?>",
				"tag":"",
				"text":"<p><?= $sTitle ?></p>",
				"asset":
				{
					"media":"<?= (CRC_AUDITS_IMG_PATH.$sPicture) ?>",
					"thumbnail":"<?= (CRC_AUDITS_IMG_PATH.$sPicture) ?>",
					"credit":"<?= $sQuestionsList[$iQuestion] ?>",
					"caption":""
				}
			}<?= (($i < ($iCount - 1)) ? "," : "") ?>
<?
	}
?>
		]
	}
}
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>