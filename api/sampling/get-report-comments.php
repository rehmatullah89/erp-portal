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

	$User         = IO::strValue('User');
	$RequestCode  = IO::strValue('RequestCode');
	$iRequestCode = intval(substr($RequestCode, 1));

	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($iRequestCode == 0 || strlen($RequestCode) == 0 || $RequestCode{0} != "M")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Request Code";
	}

	else
	{
		$sSQL = "SELECT status FROM tbl_merchandisings WHERE id='$iRequestCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No Report Request Found!";
		}

		else
		{
			$sStatus = $objDb->getField(0, "status");


			$sSQL = "SELECT * FROM tbl_comment_sheets WHERE merchandising_id='$iRequestCode'";
			$objDb->query($sSQL);

			$sMerchComments   = $objDb->getField(0, "merch_comments");
			$sSpecComments    = $objDb->getField(0, "spec_comments");
			$sOtherComments   = $objDb->getField(0, "other_comments");
			$sFittingComments = $objDb->getField(0, "fitting_comments");
			$sNoteSuggestions = $objDb->getField(0, "note_suggestions");



			$aResponse['Status']       = "OK";
			$aResponse['Merchant']     = $sMerchComments;
			$aResponse['Specs']        = $sSpecComments;
			$aResponse['Construction'] = $sOtherComments;
			$aResponse['Fitting']      = $sFittingComments;
			$aResponse['Suggestions']  = $sNoteSuggestions;
			$aResponse['ReportStatus'] = $sStatus;
		}
	}


	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>