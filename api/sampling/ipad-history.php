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

	$Id = IO::intValue("Id");

	$iStyle    = getDbValue("style_id", "tbl_merchandisings", "id='$Id'");
	$aResponse = array( );


	if ($Id == 0 || $iStyle == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Merchandising ID";
	}

	else
	{
		$aData      = array( );
		$sTypesList = getList("tbl_sampling_types", "id", "type");

		$sSQL = "SELECT m.id, m.sample_type_id, m.status, c.created
				 FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
				 WHERE m.style_id=s.id AND m.id=c.merchandising_id AND m.style_id='$iStyle' AND m.id<'$Id'
				 ORDER BY m.created DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId          = $objDb->getField($i, 'id');
			$iSampleType  = $objDb->getField($i, "sample_type_id");
			$sStatus      = $objDb->getField($i, "status");
			$sDate        = $objDb->getField($i, "created");

			switch ($sStatus)
			{
				case "A" : $sStatusText = "Accepted"; break;
				case "R" : $sStatusText = "Rejected"; break;
				case "W" : $sStatusText = "Working"; break;
			}

			$aData[] = @implode("||", array($iId, $sTypesList[$iSampleType], $sStatusText, formatDate($sDate)));
		}

		$aResponse['Status'] = "OK";
		$aResponse['Data']   = @implode("|--|", $aData);
	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>