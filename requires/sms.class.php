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

	class Sms
	{
		function Sms( )
		{

		}


		function error( )
		{

		}


		function format($sMobile)
		{
			$sMobile = str_replace(array(" ", "-", ".", "(", ")"), "", $sMobile);

			if (substr($sMobile, 0, 3) == "+03")
				$sMobile = str_replace("+03", "+923", $sMobile);

			if (substr($sMobile, 0, 2) == "03")
				$sMobile = substr_replace($sMobile, "+923", 0, 2);

			if (substr($sMobile, 0, 5) == "+0088")
				$sMobile = str_replace("+0088", "+88", $sMobile);

			if (substr($sMobile, 0, 4) == "0088")
				$sMobile = str_replace("0088", "+88", $sMobile);

/*
			$sMobile = str_replace(" ", "", $sMobile);
			$sMobile = str_replace("-", "", $sMobile);
			$sMobile = str_replace("(", "", $sMobile);
			$sMobile = str_replace(")", "", $sMobile);

			if (substr($sMobile, 0, 5) == "+9203")
				$sMobile = str_replace("+9203", "923", $sMobile);

			if (substr($sMobile, 0, 6) == "009203")
				$sMobile = str_replace("009203", "923", $sMobile);

			if (substr($sMobile, 0, 3) == "+03")
				$sMobile = str_replace("+03", "923", $sMobile);

			if (substr($sMobile, 0, 2) == "03")
				$sMobile = substr_replace($sMobile, "923", 0, 2);

			if (substr($sMobile, 0, 5) == "+0088")
				$sMobile = str_replace("+0088", "88", $sMobile);

			if (substr($sMobile, 0, 4) == "0088")
				$sMobile = str_replace("0088", "88", $sMobile);
*/
			return $sMobile;
		}


		function send($sMobile, $sName, $sMessage, $sSubject = "", $bDebug = false)
		{
			$sMobileNo = $this->format($sMobile);
			$bFlag     = false;

			if (strlen($sMobile) < 7)
				return "Invalid Mobile No";
/*
			if (@substr($sMobileNo, 0, 2) == "92")
			{
				$bFlag = $this->objTelenor->send($sMobileNo, ($sSubject.'\r\n'.$sMessage));

				if ($bFlag == false && $bDebug == true)
					print ('<span style="color:#ff0000;">'.$this->objTelenor->error( ).'</span><br />');
			}
*/

			if ($bFlag == false)
			{
				$this->smsOut($sMobileNo, ($sSubject."\n".$sMessage));


//				$objEmail = new PHPMailer( );

//				$objEmail->IsSMTP( );
//				$objEmail->IsHTML(false);

//				$objEmail->SMTPAuth = true;
//				$objEmail->Subject  = $sSubject;
//				$objEmail->Body     = $sMessage;
/*
				if (substr($sMobileNo, 0, 5) == "+0088" || substr($sMobileNo, 0, 4) == "0088")
				{
					$sMobileNo = str_replace("+0088", "", $sMobileNo);
					$sMobileNo = str_replace("0088", "", $sMobileNo);

					$objEmail->AddAddress(($sMobileNo."@smsbd.apparelco.com"), $sName);
				}

				else
*/
//				$objEmail->AddAddress((((@strpos($sMobileNo, "+") === FALSE) ? "+" : "").$sMobileNo."@sms.apparelco.com"), $sName);

//				$objEmail->Send( );

//				if ($objEmail->ErrorInfo != "")
//					return $objEmail->ErrorInfo;
			}

			return "OK";
		}


		function smsOut($sPhone, $sMessage, $sSender = "")
		{
			if (@strpos($_SERVER['HTTP_HOST'], "localhost") !== FALSE)
				return true;


			$hSocket = @fsockopen(SMS_NOW_HOST, SMS_NOW_PORT, $sErrorNo, $sError);

			if (!$hSocket)
				return false;


			@fwrite($hSocket, ("GET /?Phone=".@rawurlencode($sPhone)."&Text=".@rawurlencode($sMessage)."&Sender={$sSender} HTTP/1.0\n"));

			if (SMS_NOW_USERNAME != "")
			{
				$sAuthentication = (SMS_NOW_USERNAME.":".SMS_NOW_PASSWORD);
				$sAuthentication = @base64_encode($sAuthentication);

				@fwrite($hSocket, "Authorization: Basic {$sAuthentication}\n");
			}

			@fwrite($hSocket, "\n");

			$sResponse = "";

			while(!@feof($hSocket))
			{
				$sResponse .= @fread($hSocket, 1);
			}

			@fclose($hSocket);

			if (@strpos($sResponse, "OK") !== FALSE)
				return true;

			return false;
		}


		function close( )
		{

		}
	}
?>