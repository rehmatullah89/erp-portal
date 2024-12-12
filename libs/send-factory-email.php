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
	$objDb3      = new Database( );

	$Id          = IO::intValue('Id');
	$Emails      = explode(",", IO::strValue('Email'));
        $Message     = IO::strValue('Message')."<br/>";


	if(!empty($Emails))
	{
		$objEmail = new PHPMailer( );

		$objEmail->FromName = "SourcePro Portal";		
		$objEmail->Subject  = ("Factory information request Alert");
		
                
                
                $sLink  = ("http:///sourcepro.3-tree.com/factory-info-form.php?Id=".@base64_encode($Id));
                        
                $sBody = "Dear User,<br /><br />
                                    Please <a href='{$sLink}' target='_blank'>click here</a> to update the ". getDbValue("vendor", "tbl_vendors", "id='$Id'")." factory information.<br />
                                    <br />
                                    $Message
                                    <br />
                                    SourcePro Portal";
                        
		$objEmail->MsgHTML($sBody);

                foreach($Emails as $Email)
                {
                    $Email =  trim($Email);
                    
                    if($Email != "")
                    {
                        $Name = explode("@", $Email);
                        $Name = @$Name[0];

                        $objEmail->AddAddress($Email, $Name);
                    }
                }
                
		$objEmail->Send( );
	}

	
	redirect("vendor-profile.php?Id={$Id}", "FACTORY_EMAIL_SENT");


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>