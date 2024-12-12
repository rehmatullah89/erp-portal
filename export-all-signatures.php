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
	@require_once("requires/Rtf/Rtf.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL = "SELECT name, email, mobile, phone_ext, (SELECT designation FROM tbl_designations WHERE id=tbl_users.designation_id) AS _Designation, (SELECT country FROM tbl_countries WHERE id=tbl_users.country_id) AS _Country, (SELECT office FROM tbl_offices WHERE id=tbl_users.office_id) AS _Office, (SELECT phone FROM tbl_offices WHERE id=tbl_users.office_id) AS _Phone, (SELECT fax FROM tbl_offices WHERE id=tbl_users.office_id) AS _Fax FROM tbl_users WHERE status='A' AND (email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com') ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName        = $objDb->getField($i, 'name');
		$sDesignation = $objDb->getField($i, '_Designation');
		$sCountry     = $objDb->getField($i, '_Country');
		$sOffice      = $objDb->getField($i, '_Office');
		$sEmail       = $objDb->getField($i, 'email');
		$sMobile      = $objDb->getField($i, 'mobile');
		$sPhone       = $objDb->getField($i, '_Phone');
		$sPhoneExt    = $objDb->getField($i, 'phone_ext');
		$sFax         = $objDb->getField($i, '_Fax');


		$sHtml  = ('<div style="text-align:left;">'."\n");
		$sHtml .= ('  <div style="line-height:18px;">'."\n");
		$sHtml .= ('    <span style="font-family:\'BlairMdITC TT\', verdana, arial; font-size:12pt; color:#7f7f7f; font-variant:small-caps;">'.strtolower($sName).'</span><br />'."\n");
		$sHtml .= ('    <span style="font-family:\'BlairMdITC TT\', verdana, arial; font-size:10pt; color:#7f7f7f; font-variant:small-caps; letter-spacing:1.5;">'.strtolower($sDesignation).'</span><br />'."\n");
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
		$sHtml .= ('  <span style="font-family:\'BlairMdITC TT\', verdana, arial; font-size:7pt; color:#999999; font-variant:small-caps; letter-spacing:1.5;">pakistan &nbsp; &nbsp; bangladesh &nbsp; &nbsp; canada &nbsp; &nbsp; jordan &nbsp; &nbsp; egypt</span><br />'."\n");
		$sHtml .= ('  <br />'."\n");
		$sHtml .= ('  <span style="font-family:Helvetica, verdana, arial; font-size:7pt; color:#999999;">'."\n");
		$sHtml .= ('    '.$sOffice.', '.strtoupper($sCountry).'<br />'."\n");
		$sHtml .= ('    Tel: '.$sPhone.' '.(($sPhoneExt != '') ? ('ext '.$sPhoneExt) : '').'<br />'."\n");

		if ($sFax != "")
			$sHtml .= ('    Fax: '.$sFax.'<br />'."\n");

		$sHtml .= ('    Cell: '.$sMobile.'<br />'."\n");
		$sHtml .= ('    E-mail: <a href="mailto:'.IO::strValue('Email').'" style="color:#7f7f7f;">'.$sEmail.'</a><br />'."\n");
		$sHtml .= ('    URL: <a href="http://www.3-tree.com" target="_blank" style="color:#7f7f7f;">http://www.3-tree.com</a><br />'."\n");
		$sHtml .= ('  </span>'."\n");
		$sHtml .= ('</div>');

		$sFile = ($sBaseDir.SIGNATURES_DIR.str_replace(" ", "_", $sName).".html");
		$hFile = @fopen($sFile, 'w');
		@fwrite($hFile, $sHtml);
		@fclose($hFile);


		$sPlain  = (strtoupper($sName)."\r\n");
		$sPlain .= (strtoupper($sDesignation)."\r\n\r\n");
		$sPlain .= ("Matrix Sourcing\r\n");
		$sPlain .= ("Pakistan   Bangladesh   Canada   Jordan   Egypt\r\n\r\n");
		$sPlain .= ($sOffice.', '.strtoupper($sCountry)."\r\n");
		$sPlain .= ('Tel: '.$sPhone.' '.(($sPhoneExt != '') ? ('ext '.$sPhoneExt) : '')."\r\n");

		if ($sFax != "")
			$sPlain .= ('Fax: '.$sFax."\r\n");

		$sPlain .= ('Cell: '.$sMobile."\r\n");
		$sPlain .= ('E-mail: '.$sEmail."\r\n");
		$sPlain .= ('URL: http://www.3-tree.com');


		$sFile = ($sBaseDir.SIGNATURES_DIR.str_replace(" ", "_", $sName).".txt");
		$hFile = @fopen($sFile, "w");

		@fwrite($hFile, $sPlain);
		@fclose($hFile);


		$objRtf = new Rtf( );

		$objSection = &$objRtf->addSection( );
		$objSection->writeText($sPlain, new Font(8, "Verdana"), new ParFormat('left'));

		$sFile = ($sBaseDir.SIGNATURES_DIR.str_replace(" ", "_", $sName).".rtf");
		$objRtf->save($sFile);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	redirect($_SERVER['HTTP_REFERER'], "SIGNATURES_EXPORTED");

	@ob_end_flush( );
?>