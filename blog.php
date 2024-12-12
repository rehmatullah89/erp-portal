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

	if ($_POST['PostId'] != "")
		$_REQUEST = @unserialize($_SESSION[$_POST['PostId']]);

	$PostId = IO::intValue("PostId");

	if ($PostId == 0)
	{
		$_SESSION['Flag'] = "ERROR";

		header("Location: {$_SERVER['HTTP_REFERER']}");
		exit( );
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/blog.js"></script>
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
			    <h1><img src="images/h1/newsletter.jpg" width="162" height="20" vspace="10" alt="" title="" /></h1>

<?
	$sSQL = "SELECT *, (SELECT category FROM tbl_blog_categories WHERE id=tbl_blog.category_id) AS _Category FROM tbl_blog WHERE id='$PostId'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount == 0)
	{
		$_SESSION['Flag'] = "DB_ERROR";

		header("Location: {$_SERVER['HTTP_REFERER']}");
		exit( );
	}

	$sTitle    = $objDb->getField(0, "title");
	$sPost     = $objDb->getField(0, "post");
	$sPicture  = $objDb->getField(0, "picture");
	$sDateTime = $objDb->getField(0, "modified");

	if ($sPicture != "" && @file_exists(BLOG_IMG_PATH.'medium/'.$sPicture))
	{
?>
               	<img src="<?= BLOG_IMG_PATH.'medium/'.$sPicture ?>" width="585" height="205" alt="" title="" style="margin:0px 0px 5px 0px;" />
<?
	}
?>
			    <div class="tblSheet">
			      <h2 style="margin-right:1px; _margin:1px 1px 10px 1px; #margin:1px 1px 10px 1px;"><?= $sTitle ?></h2>

			      <div style="padding:0px 10px 10px 10px;">
			    	<i class="dateTime"><?= formatDate($sDateTime, "F j, Y h:i A") ?></i><br /><br />
                  	<?= $sPost ?><br />
					<br />

<?
	if ($PostId == 272)
	{
?>
                    <script type="text/javascript" src="scripts/flow-player.js"></script>

					<div id="Player" style="background:#000000; width:565px; height:470px;"></div>

					<script type="text/javascript">
					<!--
						flashembed("Player", { src:'movies/flow-player.swf', width:565, height:470 }, { config: { autoPlay:true, autoBuffering:true, videoFile:'<?= SITE_URL.VIDEO_FILES_DIR."ms.flv" ?>' } } );
					-->
					</script>

					<br />
<?
	}

	if ($_SESSION['UserId'] != "")
	{
		$sUser        = "Editor";
		$sDesignation = "";
		$sPicture     = "default.jpg";


		$sSQL = "SELECT designation_id, name, picture FROM tbl_users WHERE designation_id='189' LIMIT 1";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sUser        = $objDb->getField(0, "name");
			$iDesignation = $objDb->getField(0, "designation_id");
			$sPicture     = $objDb->getField(0, "picture");

			if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
				$sPicture = "default.jpg";

			$sDesignation = getDbValue("designation", "tbl_designations", "id='$iDesignation'");
		}
?>
			        <br />

			        <table width="100%" cellspacing="0" cellpadding="0" border="0">
			          <tr valign="top">
			            <td width="78"><div style="border:solid 1px #888888; padding:1px; margin-right:10px;"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" width="64" alt="<?= $sName ?>" title="<?= $sName ?>" /></div></td>

			            <td>
			              <b><?= $sUser ?></b><br />
			              <?= $sDesignation ?><br />
			              <br style="line-height:10px;" />
			              <a href="editor.php" class="lightview" rel="iframe" title=" :: :: width: 600, height: 360">Click here to write to the editor</a><br />
			            </td>
			          </tr>
			        </table>
<?
	}
?>
                  </div>
                </div>

                <br />

<?
/*
	if ($PostId == 95)
	{
		if ($_SESSION['UserId'] != "")
		{
			$sSQL = "SELECT name, email FROM tbl_users WHERE id='{$_SESSION['UserId']}'";
			$objDb->query($sSQL);

			$sName  = $objDb->getField(0, 'name');
			$sEmail = $objDb->getField(0, 'email');
?>
			    <form name="frmDonation" id="frmDonation" method="post" action="save-donation.php" class="frmOutline" onsubmit="$('BtnDonate').disable( );">
			    <h2>Donate Now</h2>

			    <div style="padding:10px 10px 25px 10px;">
			      <b>Here you can donate to MATRIX Sourcing Flood Relief Fund.</b><br />
			      <br />
			      Please enter the amount below that you would like to donate. This amount will be deducted from your next month salary.<br />
			    </div>

			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="110">Full Name</td>
				    <td width="20" align="center">:</td>
				    <td><b><?= $sName ?></b></td>
				  </tr>

				  <tr>
				    <td>Email Address</td>
				    <td align="center">:</td>
				    <td><b><?= $sEmail ?></b></td>
				  </tr>

				  <tr>
				    <td>Donation Amount</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Amount" value="" size="10" maxlength="10" class="textbox" /> PKR</td>
				  </tr>
			    </table>

			    <br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnDonate" value="" class="btnDonate" onclick="return validateDonationForm( );" />
			      <input type="button" value="" class="btnCancel" onclick="document.location='./';" />
			    </div>
			    </form>
<?
		}

		else
		{
?>
                <div class="tblSheet">
                  <h2>Donate Now</h2>
                  <br />
	              <center><b style="color:#ff0000;">Please login in order to donate for MATRIX Sourcing Flood Relief Fund</b></center>
	              <br />
	              <br />
	            </div>
<?
		}
	}

	else
	{
*/
?>
                <div class="tblSheet">
				  <h2 style="margin-right:1px; _margin:1px 1px 10px 1px; #margin:1px 1px 10px 1px;">User Comments</h2>

				  <div style="padding:0px 10px 10px 10px;">
<?
	$sSQL = "SELECT c.id, c.comments, c.date_time, u.name, u.picture, u.designation_id
	         FROM tbl_blog_comments c, tbl_users u
	         WHERE c.user_id=u.id AND c.post_id='$PostId'
	         ORDER BY c.id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCommentsId  = $objDb->getField($i, "id");
			$sUser        = $objDb->getField($i, "name");
			$sPicture     = $objDb->getField($i, "picture");
			$iDesignation = $objDb->getField($i, "designation_id");
			$sComments    = $objDb->getField($i, "comments");
			$sDateTime    = $objDb->getField($i, "date_time");

			if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
				$sPicture = "default.jpg";

			$sDesignation = getDbValue("designation", "tbl_designations", "id='$iDesignation'");
?>
			        <table width="100%" cellspacing="0" cellpadding="0" border="0">
			          <tr valign="top">
			            <td width="78"><div style="border:solid 1px #888888; padding:1px; margin-right:10px;"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" width="64" alt="<?= $sName ?>" title="<?= $sName ?>" /></div></td>

			            <td>
			              <b><?= $sUser ?></b><br />
			              <?= $sDesignation ?><br />
			              <i class="dateTime" style="font-size:10px;"><?= formatDate($sDateTime, "F j, Y h:i A") ?></i><br />
			            </td>
			          </tr>
			        </table>

			        <br style="line-height:8px;" />
			        <?= nl2br($sComments) ?><br />

<?
			if ($_SESSION['Admin'] == "Y")
			{
?>
				    <br />
				    <b class="red">[ <a href="delete-blog-comments.php?Id=<?= $iCommentsId ?>" onclick="return confirm('Are you sure, You want to delete these comments?');">Delete</a> ]</b><br />
<?
			}

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
				    <b>No Comments posted yet!</b><br />
<?
	}
?>
				  </div>
				</div>

				<br />
<?
	if ($_POST["Error"] != "")
	{
?>
				<div class="error">
				  <b>Please provide the valid values of following fields:</b><br />
				  <br style="line-height:5px;" />
				  <?= $_POST["Error"] ?><br />
				  <br />
				</div>

<?
	}

	if ($_SESSION['UserId'] != "")
	{
?>
				<h1><img src="images/h1/post-your-comments.jpg" width="294" height="20" vspace="10" alt="" title="" /></h1>

				<div class="tblSheet">
				  <form name="frmComments" id="frmComments" method="post" action="save-blog-comments.php" onsubmit="$('BtnSave').disable( );" style="margin:0px 1px 1px 0px; _margin:0px 1px 1px 1px; #margin:0px 1px 1px 1px;">
				  <input type="hidden" name="PostId" value="<?= $PostId ?>" />
				  <textarea name="Comments" style="width:99%; height:150px;"><?= IO::getFormValue("Comments") ?></textarea>

				  <div class="buttonsBar">
				    <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
				    <input type="button" value="" class="btnBack" onclick="document.location='./';" />
				  </div>
				  </form>
				</div>
<?
	}
//	}
?>
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