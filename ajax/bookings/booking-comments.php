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
	**   Project Developer:                                                                      **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/
        @require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$iBookingId  = IO::intValue("BookingId");
        $sComments   = IO::strValue("Comment");
        $sBooking    = "B".str_pad($iBookingId, 5, '0', STR_PAD_LEFT);
        $sStatus     = getDbValue("status", "tbl_bookings", "id='$iBookingId'");
        
	if ($iBookingId > 0)
	{
                $iComment = getDbValue("MAX(id)", "tbl_booking_comments")+1;
            
                $sSQL = "INSERT INTO tbl_booking_comments SET id='$iComment', booking_id='$iBookingId', user_id ='{$_SESSION['UserId']}', comments='$sComments', ip_address ='{$_SERVER['REMOTE_ADDR']}', date_time=NOW()";
                
		if ($objDb->query($sSQL) == true)
		{
                    /******************Send Email***************************/
                        $sUser         = getDbValue("name", "tbl_users", "id='{$_SESSION['UserId']}'");
                        $iCreatedBy    = getDbValue("created_by", "tbl_bookings", "id='$iBookingId'");
                        $sName         = getDbValue("name", "tbl_users", "id='$iCreatedBy'");
                        $sEmail        = getDbValue("email", "tbl_users", "id='$iCreatedBy'");
                        
                        $sSelected      = "";
                        $ManagerName    = "";
                        $ManagerEmail   = "";
                        
                        if($sStatus == 'A')
                            $sSelected = "accepted_by";
                        else if($sStatus == 'R')
                            $sSelected = "rejected_by";
                        else if($sStatus == 'C')
                            $sSelected = "cancelled_by";
                        
                        if($sSelected != "")
                        {
                            $ManagerId      = getDbvalue("{$sSelected}", "tbl_bookings", "id='$iBookingId'");
                            $ManagerName    = getDbvalue("name", "tbl_users", "id='$ManagerId'");
                            $ManagerEmail   = getDbvalue("email", "tbl_bookings", "id='$ManagerId'");
                        }
                        
                        //Inspection Comments
                        $sBookingComments = "";
                        $sSQL = "SELECT bc.date_time, bc.comments,
                                   (SELECT name from tbl_admins where bc.user_id = id) as _USERNAME
                                   FROM tbl_booking_comments bc WHERE bc.booking_id='$iBookingId' Order By id DESC";
                        $objDb->query($sSQL);

                        $iCount     = $objDb->getCount( );
                        if ($iCount > 0)
                        {
                            for($i=0; $i<$iCount; $i++)
                            {
                                $sCommenter     = $objDb->getField($i, "_USERNAME");
                                $sCommentDate   = $objDb->getField($i, "date_time");
                                $sInspComments  = $objDb->getField($i, "comments");

                                $sBookingComments .= '<b>'.$sCommenter.'</b> '.'('.$sCommentDate.')<br/>'.$sInspComments.'<br/><br/>';
                            }

                        }else
                            $sBookingComments = "";
                        
                        $sBody = ("Dear {$sName},<br /><br />
                            A New Comment has been added to system against your booking# {$sBooking} <br/><br/> By {$sUser}<br/>{$sBookingComments}");

                            
                        $objEmail = new PHPMailer( );

                        $objEmail->Subject = "New Comment Added Against Booking No# {$sBooking}";
                        $objEmail->Body    = $sBody;
                        $objEmail->IsHTML(true);
                        $objEmail->AddAddress($sEmail, $sName);
                        
                        if($ManagerName != "" && $ManagerEmail != "")
                            $objEmail->AddAddress($ManagerEmail, $ManagerName);
                        $objEmail->Send( );
                    
                    ////////////*****Send Email Ends here ***********///////
                    print "success|-|A New Comment has been added to system successfully!|-|";
                    
                    $sSQL = "SELECT bc.*,
                                (SELECT name from tbl_users where id=bc.user_id) as _sUser,
                                (SELECT picture from tbl_users where id=bc.user_id) as _sImage
                        FROM tbl_booking_comments bc WHERE bc.booking_id='$iBookingId' ORDER BY bc.id";
                $objDb->query($sSQL);

                $iCount = $objDb->getCount( );

                    if($iCount > 0)
                    {
?>
                        <BR/>
                         <div id="CommentArea">
<?
                            for ($i = 0; $i < $iCount; $i ++)
                            {
                                $iComment       = $objDb->getField($i, "id");
                                $iCreater       = $objDb->getField($i, "user_id");
                                $sComments      = $objDb->getField($i, "comments");
                                $sUserName      = $objDb->getField($i, "_sUser");
                                $sUserPicture   = $objDb->getField($i, "_sImage");
                                $sIpAddress     = $objDb->getField($i, "ip_address");
                                $vDateTime      = $objDb->getField($i, "date_time"); 

                                if($sUserPicture == "")
                                    $sUserPicture = 'default.jpg';                                           
?>
                                <div class="div_hover"><img style="border:0px !important;" src="<?= ($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sUserPicture) ?>" alt="<?= $sUserName ?>" title="<?= $sUserName ?>" width="50" /><span style="margin-left:5px; display: inline-block;"><i><b><?=$sUserName;?></b></i><br/><i><?=$vDateTime;?></i><br/><?=$sComments;?></span></div> <br/>
<?
                            }
?>
                        </div>
<?
                    }
                    
		}else
                    print "error|-|There is some Error, Please Contact your web administrator!";
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>