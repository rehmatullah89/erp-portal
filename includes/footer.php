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
?>
	    <div id="Footer">
		  <div id="Terms">This service is provided on Triple Tree's standard <a href="terms-and-conditions.php">Terms & Conditions</a>. <!--Please read our <a href="privacy-policy.php">Privacy Policy</a>.--> Designed, Developed & Maintained by the Creative & IT Division, Triple Tree.</div>

		  <div id="FooterLinks">
		    <a href="./">Home</a> |
<?
	if ($iVsnCount > 0)
	{
?>
		    <a href="vsn/">VSN</a> |
<?
	}

	if ($iVsrCount > 0)
	{
?>
		    <a href="vsr/">VSR</a> |
<?
	}

	if ($iQsnCount > 0)
	{
?>
		    <a href="qsn/">QSN</a> |
<?
	}

	if ($iBtaCount > 0)
	{
?>
		    <a href="bta/">BTA</a> |
<?
	}

	if ($iPccCount > 0)
	{
?>
		    <a href="pcc/">PCC</a> |
<?
	}

	if ($iDataEntryCount > 0)
	{
?>
		    <a href="data/">Data Entry</a> |
<?
	}

	if ($iShippingCount > 0)
	{
?>
		    <a href="shipping/">Shipping</a> |
<?
	}

	if ($iQuondaCount > 0)
	{
?>
		    <a href="quonda/">Quonda</a> |
<?
	}

	if ($iSamplingCount > 0)
	{
?>
		    <a href="sampling/">Sampling</a> |
<?
	}

	if ($iCrcCount > 0)
	{
?>
		    <a href="crc/">VMAN</a> |
<?
	}

	if ($iReportsCount > 0)
	{
?>
		    <a href="reports/">Reports</a> |
<?
	}

	if ($iHrCount > 0)
	{
?>
		    <a href="hr/">HR</a> |
<?
	}

	if ($iYarnCount > 0)
	{
?>
		    <a href="yarn/">Yarn</a> |
<?
	}

	if ($iDropboxCount > 0)
	{
?>
		    <a href="dropbox/">Dropbox</a> |
<?
	}
?>
		    <a href="libs/">Libs</a> |
<?
	if ($_SESSION['Admin'] == "Y")
	{
?>
		    <a href="admin/">Admin</a> |
<?
	}
?>
		    <a href="contact-us.php">Contact Us</a>
		  </div>

                  <div style="text-align: center; margin-bottom: 10px;"><img src="images/LogoTt.png" alt="" title="" /></div>
		  <div id="Copyright">                      
		    <big style="font-size: 14px;">Copyright <?= date("Y") ?> &copy; Triple Tree. All Rights Reserved.</big><br />
		    <br style="line-height:5px;" />
<?
	@list($iMicroSeconds, $iSeconds) = @explode(" ", @microtime( ));
	$iPageEndTime = ((float)$iMicroSeconds + (float)$iSeconds);

	$fPageLoadTime = @round(($iPageEndTime - $iPageStartTime), 3);
?>
		    Current System Date/Time is <?= date("l, F d, Y h:i A") ?><br />
		    Page Loading Time is <?= @number_format($fPageLoadTime, 2, '.', '') ?> Seconds<br />
			<br />
			
			<center>
				<script language="JavaScript" type="text/javascript">
					TrustLogo("https://sourcepro.3-tree.com/images/comodo_secure_seal_113x59_transp.png", "CL1", "none");
				</script>
			</center>
			
			<style type="text/css">
				#tl_popupCL1
				{
					z-index  :  9999999 !important;
				}
			</style>			
		  </div>
	    </div>
