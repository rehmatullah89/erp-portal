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

	if (!isset($_SERVER['PHP_AUTH_USER']))
	{
		header('WWW-Authenticate: Basic realm="Matrix Signatures System"');
		header('HTTP/1.0 401 Unauthorized');

		print  "Sorry, you don't have the rights to access this System.";

		exit( );
	}

	else
	{
		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		$Username = $_SERVER['PHP_AUTH_USER'];
		$Password = $_SERVER['PHP_AUTH_PW'];

		$sSQL = "SELECT status, admin FROM tbl_users WHERE username='$Username' AND password=PASSWORD('$Password')";

		if ($objDbGlobal->query($sSQL) == false || $objDbGlobal->getCount( ) != 1 || $objDbGlobal->getField(0, "status") != "A" || $objDbGlobal->getField(0, "admin") != "Y")
		{
			header('WWW-Authenticate: Basic realm="Matrix Signatures System"');
			header('HTTP/1.0 401 Unauthorized');

			print  "Sorry, you don't have the rights to access this System.";

			exit( );
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/signatures.js"></script>
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
			    <h1><img src="images/h1/employee-signatures.jpg" width="303" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmSignatures" id="frmSignatures" method="post" action="signatures.php" class="frmOutline" onsubmit="$('BtnSubmit').disable( );">
			    <div style="padding:10px 10px 25px 10px;">
			      Please provide the required employee information below to generate the Employee Signatures for Microsoft Outlook.<br /><br />
			      <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
			    </div>


			    <h2>Employee Information</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="90">Employee</td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select name="User" onchange="showEmpInfo(this.value);" style="min-width:226px;">
					    <option value="">[ select employee ]</option>
<?
	$sSQL = "SELECT id, name FROM tbl_users WHERE status='A' AND (email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com') ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
		            	<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <script type="text/javascript">
					  <!--
					  	document.frmSignatures.User.value = "<?= IO::strValue('User') ?>";
					  -->
					  </script>
				    </td>
				  </tr>

				  <tr>
				    <td>Full Name<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Name" id="Name" value="<?= IO::strValue('Name') ?>" size="34" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Designation<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select name="Designation" id="Designation">
					    <option value=""></option>
<?
	$sSQL = "SELECT designation FROM tbl_designations ORDER BY designation";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sValue = $objDb->getField($i, 0);
?>
		            	<option value="<?= $sValue ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <script type="text/javascript">
					  <!--
					  	document.frmSignatures.Designation.value = "<?= ((IO::strValue('Designation') == '') ? '' : IO::strValue('Designation')) ?>";
					  -->
					  </script>
				    </td>
				  </tr>

				  <tr valign="top">
				    <td>Office<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
				      <select name="Office" id="Office">
<?
	$sSQL = "SELECT office FROM tbl_offices ORDER BY office";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sValue = $objDb->getField($i, 0);
?>
		            	<option value="<?= $sValue ?>"><?= $sValue ?></option>
<?
	}
?>
				      </select>

					  <script type="text/javascript">
					  <!--
					  	document.frmSignatures.Office.value = "<?= ((IO::strValue('Office') == '') ? 'Lahore Office' : IO::strValue('Office')) ?>";
					  -->
					  </script>
				    </td>
				  </tr>

				  <tr valign="top">
				    <td>Country<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select name="Country" id="Country">
<?
	$sSQL = "SELECT country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sValue = $objDb->getField($i, 0);
?>
		            	<option value="<?= $sValue ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <script type="text/javascript">
					  <!--
					  	document.frmSignatures.Country.value = "<?= ((IO::strValue('Country') == '') ? 'Pakistan' : IO::strValue('Country')) ?>";
					  -->
					  </script>
				    </td>
				  </tr>

				  <tr>
				    <td>Email Address<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Email" id="Email" value="<?= ((IO::strValue('Email') == '') ? '@apparelco.com' : IO::strValue('Email')) ?>" size="35" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Phone<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
				      <input type="text" name="Phone" id="Phone" value="<?= ((IO::strValue('Phone') == '') ? '0092 42 111 111 118' : IO::strValue('Phone')) ?>" size="20" maxlength="25" class="textbox" />
				      &nbsp;&nbsp;ext&nbsp;
				      <input type="text" name="Ext" id="Ext" value="<?= IO::strValue('Ext') ?>" size="5" maxlength="5" class="textbox" />
				    </td>
				  </tr>

				  <tr>
				    <td>Fax</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Fax" id="Fax" value="<?= ((IO::strValue('Fax') == '') ? '0092 42 111 111 119' : IO::strValue('Fax')) ?>" size="20" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Cell</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Cell" id="Cell" value="<?= IO::strValue('Cell') ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
				</table>

				<br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSubmit" value="" class="btnSubmit" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnCancel" onclick="document.location='signatures.php';" />
<?
	if (!$_POST)
	{
?>
			      <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="document.location='<?= SITE_URL ?>/export-all-signatures.php';" />
<?
	}
?>
			    </div>
			    </form>

<?
	if ($_POST)
	{
		$sHtml  = ('<div style="text-align:left;">'."\n");
		$sHtml .= ('  <div style="line-height:18px;">'."\n");
		$sHtml .= ('    <span style="font-family:\'BlairMdITC TT\', verdana, arial; font-size:12pt; color:#7f7f7f; text-transform:uppercase;">'.IO::strValue('Name').'</span><br />'."\n");
		$sHtml .= ('    <span style="font-family:\'BlairMdITC TT\', verdana, arial; font-size:10pt; color:#7f7f7f; font-variant:small-caps; letter-spacing:2;">'.strtolower(IO::strValue('Designation')).'</span><br />'."\n");
		$sHtml .= ('  </div>'."\n");
		$sHtml .= (''."\n");
		$sHtml .= ('  <br />'."\n");
		$sHtml .= (''."\n");
		$sHtml .= ('  <table border="0" cellpadding="0" cellspacing="0" width="400">'."\n");
		$sHtml .= ('    <tr>'."\n");
		$sHtml .= ('      <td width="100%"><a href="http://portal.3-tree.com/" target"_blank"><img src="http://mail.3-tree.com/logo/matrix.jpg" width="240" height="45" border="0" alt="Matrix Soucing" title="Matrix Sourcing" /></a></td>'."\n");
		$sHtml .= ('    </tr>'."\n");
		$sHtml .= ('    <tr>'."\n");
		$sHtml .= ('      <td height="5"></td>'."\n");
		$sHtml .= ('    </tr>'."\n");
		$sHtml .= ('  </table>'."\n");
		$sHtml .= (''."\n");
		$sHtml .= ('  <span style="font-family:\'BlairMdITC TT\', verdana, arial; font-size:7pt; color:#7f7f7f; font-variant:small-caps; letter-spacing:1.5;">PAKISTAN &nbsp; &nbsp; BANGLADESH &nbsp; &nbsp; CANADA &nbsp; &nbsp; JORDAN &nbsp; &nbsp; EGYPT</span><br />'."\n");
		$sHtml .= ('  <br />'."\n");
		$sHtml .= ('  <span style="font-family:Helvetica, verdana, arial; font-size:9pt; color:#bfbfbf;">'."\n");
		$sHtml .= ('    '.IO::strValue('Office').', '.strtoupper(IO::strValue('Country')).'<br />'."\n");
		$sHtml .= ('    Tel: '.IO::strValue('Phone').' '.((IO::strValue('Ext') != '') ? ('ext '.IO::strValue('Ext')) : '').'<br />'."\n");

		if (IO::strValue('Fax') != "")
			$sHtml .= ('    Fax: '.IO::strValue('Fax').'<br />'."\n");

		$sHtml .= ('    Cell: '.IO::strValue('Cell').'<br />'."\n");
		$sHtml .= ('    E-mail: <a href="mailto:'.IO::strValue('Email').'" style="color:#7f7f7f;">'.IO::strValue('Email').'</a><br />'."\n");
		$sHtml .= ('    URL: <a href="http://www.3-tree.com" target="_blank" style="color:#7f7f7f;">http://www.3-tree.com</a><br />'."\n");
		$sHtml .= ('  </span>'."\n");
		$sHtml .= ('</div>');
?>
				<br />
				<br />

			    <form name="frmHtmlSignatures" id="frmHtmlSignatures" method="post" action="export-signatures.php" class="frmOutline">
			    <input type="hidden" name="File" value="<?= str_replace(" ", "_", IO::strValue('Name')) ?>.html" />

			    <h2>HTML Signatures</h2>
			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="100%">
				      <textarea name="Code" id="Html" nowrap style="width:99%; height:150px; background:#f9f9f9;"><?= htmlentities($sHtml) ?></textarea>
				      <br />
				      [ <a href="#" onclick="$('Html').select( ); $('Html').focus( ); return false;">Select All</a> | <a href="#" onclick="Effect.toggle('HtmlPreview', 'slide'); return false;">Preview</a> | <a href="signatures.php#" onclick="$('frmHtmlSignatures').submit( ); return false;">Export</a> ]
				    </td>
				  </tr>
				</table>

				<div id="HtmlPreview" style="display:none; margin:20px 20px 0px 20px; border:solid 1px #aaaaaa;">
				  <div style="padding:10px;">
				    <?= $sHtml ?>
				  </div>
				</div>

				<br />
				</form>

				<br />

<?
		$sPlain  = (strtoupper(IO::strValue('Name'))."\n");
		$sPlain .= (strtoupper(IO::strValue('Designation'))."\n\n");
		$sPlain .= ("Matrix Sourcing\n");
		$sPlain .= ("PAKISTAN &nbsp; &nbsp; BANGLADESH &nbsp; &nbsp; CANADA &nbsp; &nbsp; JORDAN &nbsp; &nbsp; EGYPT\n\n");
		$sPlain .= (IO::strValue('Office').', '.strtoupper(IO::strValue('Country'))."\n");
		$sPlain .= ('Tel: '.IO::strValue('Phone').' '.((IO::strValue('Ext') != '') ? ('ext '.IO::strValue('Ext')) : '')."\n");

		if (IO::strValue('Fax') != "")
			$sPlain .= ('Fax: '.IO::strValue('Fax')."\n");

		$sPlain .= ('Cell: '.IO::strValue('Cell')."\n");
		$sPlain .= ('E-mail: '.IO::strValue('Email')."\n");
		$sPlain .= ('URL: http://www.3-tree.com');
?>
			    <form name="frmPlainSignatures" id="frmPlainSignatures" method="post" action="export-signatures.php" class="frmOutline">
			    <input type="hidden" name="File" value="<?= str_replace(" ", "_", IO::strValue('Name')) ?>.rtf" />

			    <h2>Plain/Rtf Text Signatures</h2>

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="100%">
				      <textarea name="Code" id="Plain" style="width:99%; height:160px; background:#f9f9f9;"><?= $sPlain ?></textarea>
				      <br />
				      [ <a href="#" onclick="$('Plain').select( ); $('Plain').focus( ); return false;">Select All</a> | <a href="signatures.php#" onclick="$('frmPlainSignatures').submit( ); return false;">Export</a> ]
				    </td>
				  </tr>
				</table>

				<br />
				</form>
<?
	}
?>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/contact-info.php");
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