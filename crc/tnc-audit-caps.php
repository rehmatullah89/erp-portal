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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        $objDb3      = new Database( );

	$Id   = IO::intValue('Id');
	$Step = IO::intValue("Step");

	$sSQL = "SELECT * FROM tbl_tnc_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Vendor    = $objDb->getField(0, "vendor_id");
		$Auditors  = $objDb->getField(0, "auditors");
		$AuditDate = $objDb->getField(0, "audit_date");

		$Auditors  = @explode(",", $Auditors);
	}

	else
		redirect($_SERVER['HTTP_REFERER'], "ERROR");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/edit-tnc-audit.js"></script>
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
			  <td width="100%">
			   <h1><img src="images/h1/crc/tnc-caps.JPG" width="153" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="crc/update-tnc-audit-caps.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Step" value="<?= $Step ?>" />

<?
	//if ($Step > 0){
            
            
          /*  $ParentSectionArray = array(0 => '');
            $sParentSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id='0'", "id");
            foreach($sParentSectionsList as $ParentSectionId => $ParentSection){
                $ParentSectionArray[] = $ParentSectionId;
            }
            
            $ParentSectionId = $ParentSectionArray[$Step];*/
            $Step = 1;
            $sParentSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id='0'", "id");
            
            foreach($sParentSectionsList as $ParentSectionId => $ParentSection){ 
                
            $counter = 0;    
            $sSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id='$ParentSectionId'");
            
            foreach($sSectionsList as $SectionId => $Section){

                    $sCategoryList = getList("tbl_tnc_categories", "id", "category", "section_id='$SectionId'");

                    $sClass        = array("evenRow", "oddRow");

                    foreach ($sCategoryList as $iCategory => $sCategory){
                            
                            $sSQL = "SELECT tad.id, tad.score, tad.not_applicable, tad.remarks, tp.point, tp.nature, tad.cap
                                FROM tbl_tnc_audit_details tad, tbl_tnc_points tp
                                WHERE tad.point_id=tp.id AND tad.audit_id='$Id' AND tp.category_id='$iCategory' AND tad.score='0'
                                ORDER BY tp.position";
                            
                            $objDb->query($sSQL);

                            $iCount = $objDb->getCount( );
                            if($iCount>0){
                                if($counter == 0){
                                ?>
                                    <h2 style="margin-bottom:0px;"><?= $Step++ ?>. <?= $ParentSection ?></h2>
<?                
                                $counter++;
                                }
?>
                                    <h3><?= $sCategory ?></h3>    
                                    <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
                                      <tr bgcolor="#eaeaea">
                                                <td width="5%"><b>#</b></td>
                                                <td width="52%"><b>Point</b></td>
                                                <td width="8%" align="center"><b>Score</b></td>
                                                <td width="35%"><b>CAP</b></td>
                                      </tr>

        <?
                                for ($i = 0; $i < $iCount; $i ++)
                                {
                                        $iPoint   = $objDb->getField($i, 'id');
                                        $sPoint   = $objDb->getField($i, 'point');
                                        $iScore   = $objDb->getField($i, 'score');
                                        $sCap     = $objDb->getField($i, 'cap');
                                        $sNature  = $objDb->getField($i, 'nature');

                                        if($sNature == 'Z')
                                            $Color = 'red';
                                        else if($sNature == 'C')
                                            $Color = 'lightcoral';
                                        else
                                            $Color = 'orange';
        ?>

                                          <tr valign="top" class="<?= $sClass[($i % 2)] ?>">
                                            <td align="center"><?= ($i + 1) ?></td>

                                            <td>
                                                  <input type="hidden" name="Point[]" value="<?= $iPoint ?>" />
                                                  <?= $sPoint ?>
                                            </td>

                                            <td align="center">
                                                <select name="Score<?= $iPoint ?>" disabled style="background:<?=$Color?>;">
                                                    <option value="-1">N/A</option>
                                                    <option value="1"<?= (($iScore == 1) ? " selected" : "") ?> style="background:#00ff00;">1</option>
                                                    <option value="0"<?= (($iScore == 0) ? " selected" : "") ?> style="background:#ff0000;">0</option>
                                                  </select>
                                            </td>

                                            <td><textarea name="Cap<?= $iPoint ?>" rows="4" style="width:98%; height:100%;"><?= $sCap ?></textarea></td>
                                          </tr>
        <?
                                }
        ?>
                            </table>
        <?
                        }//end if
                    } // end category foreach
                }//end section foreach
            }//end parent section foreach
            $Step = 1;
	//}//step
?>
				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='crc/<?= (($Step == 1) ? 'tnc-audits.php' : ('tnc-audit-caps.php?Id='.$Id.'&Step='.($Step - 1))) ?>';" />
				</div>
			    </form>

			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
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