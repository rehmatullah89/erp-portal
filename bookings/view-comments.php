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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id = IO::intValue('Id');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
    <script type="text/javascript" src="scripts/jquery.js"></script>  
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">
      <div id="GridMsg" class="msgOk" style="display:none;"></div>
<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>View/ Post Comments Against Booking# <?="B".str_pad($Id, 5, '0', STR_PAD_LEFT)?></h2>
          <h3>Comments</h3>
          <table border="0" cellpadding="3" cellspacing="0" width="100%">
            <tr>
                <td valign="top">
                                <br />            
<?
                $sSQL = "SELECT bc.*,
                                (SELECT name from tbl_users where id=bc.user_id) as _sUser,
                                (SELECT picture from tbl_users where id=bc.user_id) as _sImage
                        FROM tbl_booking_comments bc WHERE bc.booking_id='$Id' ORDER BY bc.id";
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
                }else
                {
?>
                    <div id="CommentArea"></div>
<?
                }

					$sUserPicture   = getDbValue("picture", "tbl_users", "id='{$_SESSION['UserId']}'");
					$sUserName      = getDbValue("name", "tbl_users", "id='{$_SESSION['UserId']}'");
					
					if($sUserPicture == "")
						$sUserPicture = 'default.jpg';
?>
				<BR/><BR/><BR/>
				<div>
					<div class="picture" width="10%" height="100%" style="display:inline; padding: 5px;"><img style="position: relative; top: -60px;" src="<?= ($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sUserPicture) ?>" alt="<?= $sUserName ?>" title="<?= $sUserName ?>" width="50" /></div><div width="80%" style="display:inline;"><textarea id="Comments" name="Comments" value="" rows="5" style="width:90%"></textarea></div>
                                        <button id="SaveComment" style="float: right; margin-top:5px;  margin-right: 12px; padding: 5px;">Post Comment</button>
				</div>
               </td>
            </tr>
        </table><br/>
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>
    <script>
    jQuery.noConflict();       
    (function($) {
          $(function() {

            $(document).on("click", "#SaveComment", function(event)
            {
                    var iBookingId = "<?= $Id?>";
                    var comm = document.getElementById("Comments").value;
                    
                    var dataimg = new FormData();
                    dataimg.append('Comment', $("#Comments").val());
                    dataimg.append('BookingId', iBookingId);
//                    dataimg.append('File', $("#attachment")[0].files[0]);
                    
                    if(comm == "")
                    {
                       alert("Please write comment first!");
                       return;
                    }
                    
                    $.ajax({
                        url: "ajax/bookings/booking-comments.php",
                        data: dataimg,
                        type: "POST",
                        processData: false,  
                        contentType: false, 
                        cache : false,
                        success: function(sResponse){
                        
                            var sParams = sResponse.split("|-|");

                            //showMessage("#GridMsg", sParams[0], sParams[1]);

                            if(sParams[0] == 'success')
                            {
                                document.getElementById("CommentArea").innerHTML = sParams[2];
                                document.getElementById("Comments").value = '';
                            }
                        },
                        error: function(){
                        }                        
                      });
                });               
          });
        })(jQuery); 
    </script>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>