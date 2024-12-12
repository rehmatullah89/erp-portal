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

	if ($iDepartment == 0 && $sBrands == "")
	{
?>
          <h2 style="background:#444444; font-size:21px; font-weight:normal; text-align:center; color:#ffffff; padding:8px; margin:15px 0px 2px 0px;">RECENT SAMPLES</h2>
<?
		$sTypesList = getList("tbl_sampling_types", "id", "type");


		$sSQL = "SELECT s.sub_brand_id, s.style, s.sketch_file, m.sample_type_id, m.status
		         FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
		         WHERE m.style_id=s.id AND m.id=c.merchandising_id
		         ORDER BY c.created DESC
		         LIMIT 24";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );


		if ($iCount > 0)
		{
?>
		  <ul style="margin:0px 0px 0px 10px; padding:0px; list-style:none; width:100%; overflow:hidden;">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iBrand   = $objDb->getField($i, 'sub_brand_id');
				$iType    = $objDb->getField($i, 'sample_type_id');
				$sStyle   = $objDb->getField($i, 'style');
				$sPicture = $objDb->getField($i, 'sketch_file');
				$sStatus  = $objDb->getField($i, 'status');

				if ($sPicture == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sPicture))
					$sPicture = (STYLES_SKETCH_DIR."default.jpg");

				else
				{
					if (!@file_exists($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sPicture))
						createImage(($sBaseDir.STYLES_SKETCH_DIR.$sPicture), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sPicture), 160, 160);

					$sPicture = (STYLES_SKETCH_DIR.'thumbs/'.$sPicture);
				}
?>
		      <li style="padding:0px; margin:15px 15px 0px 0px; width:163px; height:220px; float:left; overflow:hidden;">
		        <div class="pic" style="border:solid 6px #<?= (($sStatus == "A") ? '63b200' : (($sStatus == "R") ? 'ff0f00' : 'ff8400')) ?>; padding:0px;"><img src="<?= $sPicture ?>" width="151" height="151" alt="" title="" /></div>
		        <div class="style" style="text-align:center; font-weight:bold; font-size:13px; color:#333333; padding:5px 0px 5px 0px;"><?= $sBrandsList[$iBrand] ?> / <span style="color:#666666;"><?= $sTypesList[$iType] ?></span> / <?= $sStyle ?></div>
		      </li>
<?
			}
?>
		  </ul>
<?
		}

		else
		{
?>
		  <div style="padding:10px; font-size:17px;">
		    No Sampling Audit Found!<br />
		  </div>
<?
		}
	}




	else
	{
		$sTypesList = getList("tbl_sampling_types", "id", "type");

		for ($iPriority = 1; $iPriority <= 3; $iPriority ++)
		{
?>
          <h2 style="background:#444444; font-size:21px; font-weight:normal; text-align:center; color:#ffffff; padding:8px; margin:15px 0px 2px 0px;">PRIORITY-<?= $iPriority ?> &nbsp; SAMPLES</h2>
<?
			$sSQL = "SELECT s.sub_brand_id, s.style, s.sketch_file, m.sample_type_id, m.status
					 FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
					 WHERE m.style_id=s.id AND m.id=c.merchandising_id AND FIND_IN_SET(s.sub_brand_id, '$sBrands')
						   AND m.sample_type_id IN (SELECT id FROM tbl_sampling_types WHERE priority='$iPriority' AND brand_id IN (SELECT DISTINCT(parent_id) FROM tbl_brands WHERE FIND_IN_SET(id, '$sBrands')))
					 ORDER BY c.created DESC
					 LIMIT 8";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );


			if ($iCount > 0)
			{
?>
		  <ul style="margin:0px 0px 0px 10px; padding:0px; list-style:none; width:100%; height:235px; overflow:hidden;">
<?
				for ($i = 0; $i < $iCount; $i ++)
				{
					$iBrand   = $objDb->getField($i, 'sub_brand_id');
					$iType    = $objDb->getField($i, 'sample_type_id');
					$sStyle   = $objDb->getField($i, 'style');
					$sPicture = $objDb->getField($i, 'sketch_file');
					$sStatus  = $objDb->getField($i, 'status');

					if ($sPicture == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sPicture))
						$sPicture = (STYLES_SKETCH_DIR."default.jpg");

					else
					{
						if (!@file_exists($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sPicture))
							createImage(($sBaseDir.STYLES_SKETCH_DIR.$sPicture), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sPicture), 160, 160);

						$sPicture = (STYLES_SKETCH_DIR.'thumbs/'.$sPicture);
					}
?>
		      <li style="padding:0px; margin:15px 15px 0px 0px; width:163px; height:235px; float:left; overflow:hidden;">
		        <div class="pic" style="border:solid 6px #<?= (($sStatus == "A") ? '63b200' : (($sStatus == "R") ? 'ff0f00' : 'ff8400')) ?>; padding:0px;"><img src="<?= $sPicture ?>" width="151" height="151" alt="" title="" /></div>
		        <div class="style" style="text-align:center; font-weight:bold; font-size:13px; color:#333333; padding:5px 0px 5px 0px;"><?= $sBrandsList[$iBrand] ?> / <span style="color:#666666;"><?= $sTypesList[$iType] ?></span> / <?= $sStyle ?></div>
		      </li>
<?
				}
?>
		  </ul>
<?
			}

			else
			{
?>
		  <div style="padding:10px; font-size:17px;">
		    No Sampling Audit Found!<br />
		  </div>
<?
			}
		}
	}
?>