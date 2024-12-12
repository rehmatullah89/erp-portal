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

	if ($iBrandId == 124)
	{
		$sSQL = "SELECT quality_managers FROM tbl_departments WHERE FIND_IN_SET('$iBrandId', brands)";
		$objDb->query($sSQL);

		$iCount    = $objDb->getCount( );
		$iManagers = array( );

		if ($iCount > 0)
		{
			$sManagers  = "0";

			for ($i = 0; $i < $iCount; $i ++)
				$sManagers .= (",".$objDb->getField($i, 0));

			$iManagers = @explode(",", $sManagers);
			$iManagers = array_unique($iManagers);
		}


		for ($i = 0; $i < count($iManagers); $i ++)
		{
			$iUserId = $iManagers[$i];

			if ($iUserId == 0)
				continue;


			$sSQL = "SELECT name, email FROM tbl_users WHERE id='$iUserId' AND status='A' AND email_alerts='Y' AND FIND_IN_SET('$iBrandId', brands) AND FIND_IN_SET('$iVendorId', vendors)";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 0)
				continue;

			$sName  = $objDb->getField(0, 'name');
			$sEmail = $objDb->getField(0, 'email');


			$sSubject = "*** Inline Audit {$sAuditCode} ***";
			$sBody  = "An Inline Audit Report has been uploaded on the Portal, kindly review the report and update the status.<br /><br />";
			$sBody .= ("<a href='".SITE_URL."quonda/qa-reviews.php'>".SITE_URL."quonda/qa-reviews.php</a><br /><br />");
			$sBody .= "Audit Code: {$sAuditCode}";

			if ($sVendor != "")
				$sBody .= "<br />Vendor: $sVendor";

			if ($sBrand != "")
				$sBody .= "<br />Brand: $sBrand";

			if ($sStyle != "")
				$sBody .= "<br />Style: $sStyle";

			if ($sPO != "")
				$sBody .= "<br />PO: $sPO";

			if ($sAuditResult != "")
			{
				switch ($sAuditResult)
				{
					case "P"  :  $sStatus = "Pass";  break;
					case "F"  :  $sStatus = "Fail";  break;
					case "H"  :  $sStatus = "Hold";  break;
					case "A"  :  $sStatus = "Grade A";  break;
					case "B"  :  $sStatus = "Grade B";  break;
					case "C"  :  $sStatus = "Grade C";  break;
				}

				$sBody .= "<br />Status: $sStatus";
			}

			if ($iShipQty > 0)
				$sBody .= "<br />Ship Qty: $iShipQty";


			$objEmail = new PHPMailer( );
			$objEmail->IsSMTP( );
			$objEmail->SMTPAuth = true;
			$objEmail->IsHTML(true);
			$objEmail->Subject = $sSubject;
			$objEmail->Body = $sBody;
			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
		}
	}

	else
	{
		$iAuditId = substr($sAuditCode, 1);

		$sSQL  = "UPDATE tbl_qa_reports SET status='$sAuditResult' WHERE id='$iAuditId' AND status=''";
		$bFlag = $objDb->execute($sSQL);
	}
?>