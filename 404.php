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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">

		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="585">
			    <h1><img src="images/h1/404.jpg" width="228" height="20" vspace="10" alt="" title="" /></h1>

			    <div class="tblSheet">
			      <img src="images/headers/404-error.jpg" width="581" height="205" alt="" title="" />

			      <div style="padding:25px 20px 172px 20px;">
			        We're sorry, but the call you are trying to make cannot be completed as dialed. That is to say, something went wrong and the page you're looking for is, sadly, not here.<br /><br />
			        <b>You may not be able to visit this page because of:</b><br />

			        <ol>
			          <li>an out-of-date bookmark/favourite</li>
			          <li>a mistyped address</li>
			          <li>you have no access to this page</li>
			          <li>The requested resource was not found.</li>
			          <li>An error has occurred while processing your request.</li>
			        </ol>

				    <b>Please try one of the following pages:</b><br />
				    <ul>
				      <li><a href="<?= SITE_URL ?>">Home Page</a></li>
				    </ul>

				    If difficulties persist, please <a href="contact-us.php">contact</a> the System Administrator of this site.<br /><br />
				    <b>Sorry for your inconvenience.</b><br />
				  </div>
			    </div>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/custom-feeds.php");
?>
			  </td>
			</tr>
		  </table>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>