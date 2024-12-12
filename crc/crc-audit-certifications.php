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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue('Id');
        $Completed  = IO::strValue('Completed');

        $Certifications         = getList("tbl_certification_types", "id", "certification", "id>0", "id");
        $CertificationApply     = getList("tbl_crc_audit_certifications ac, tbl_certification_types ct", "ct.id", "ac.apply", "ct.id = ac.certification_id AND ac.audit_id='$Id'");
        $CertificationComment   = getList("tbl_crc_audit_certifications ac, tbl_certification_types ct", "ct.id", "ac.comments", "ct.id = ac.certification_id AND ac.audit_id='$Id'");        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
    <style>
	.evenRow {
		background: #f6f4f5 none repeat scroll 0 0;
	}
	.oddRow {
		background: #dcdcdc none repeat scroll 0 0;
	}
	</style> 
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">
      <div id="RecordMsg" class="hidden" style="width:100%; <?=($_SESSION["Flag1122"] != "")?'background-color:#FFFACD;':'';?>"><?=($_SESSION["Flag1122"] != ""?"<span style='color:black; background-color:#FFFACD; font-size:12px; font-weight:bold;'>Audit Certifications Saved Successfully!</span>":"")?></div>
    <form name="frmData1" id="frmData1" method="post" enctype="multipart/form-data" action="crc/update-crc-audit-certifications.php" class="frmOutline">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>Audit Certifications</h2>
<?
    if($Completed != 'Y')
    {
        print "<fieldset disabled>";
    }
?>
	  <table border="0" cellpadding="1" cellspacing="0" width="100%" style="padding-bottom:10px;">
	    <tr>
                  <td width="30"><h3>#</h3></td>  
		  <td width="260"><h3>Certifications</h3></td>
		  <td width="50"><h3>Valid</h3></td>
                  <td width="180"><h3>Attachment</h3></td>
		  <td><h3>Comments</h3></td>
	    </tr>
              <input type="hidden" name="AuditId" value="<?=$Id?>">

<?
                $sClass        = array("evenRow", "oddRow");
                $i =0;
                
                foreach($Certifications  as $iCertificate => $sCertificate){
?>

                  <tr valign="top" class="<?= $sClass[($i % 2)] ?>" >
				    <td align="center"><?= ($i + 1) ?></td>

				    <td>
					  <?= $sCertificate ?>
				    </td>

				    <td align="center">
                                        <select name="Apply<?=$iCertificate?>">
                                            <option value=""></option>
                                            <option value="Y" <?=($CertificationApply[$iCertificate] == 'Y')?'selected':'' ?>>Yes</option>
                                            <option value="N" <?=($CertificationApply[$iCertificate] == 'N')?'selected':'' ?>>No</option>
                                        </select>
				    </td>
                                    <td><input name="file<?=$iCertificate?>[]" multiple type="file" value="" maxlength="200" size="40" />
<?
                                    $CertificationFilesList = getList("tbl_crc_audit_pictures", "picture", "title", "certification_id = '$iCertificate' AND audit_id='$Id'");
                                    
                                    foreach($CertificationFilesList as $sPicture => $sTitle)
                                    {
                                        if($sPicture != "")
                                        {
                                            
                                                $extensions = explode('.', $sPicture);
                                                $extension  = end($extensions);
?>
                                        <span><br/><a href="<?= (TNC_PICS_DIR.$sPicture) ?>" class="<?=(@in_array(strtolower($extension), array('png', 'jpg', 'jpeg', 'gif', 'bmp'))?'lightview':'')?>" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><?=$sPicture;?></a></span>
<?
                                        }
                                    }
?>
                                    </td>
				    <td><Input type="text" name="Comments<?=$iCertificate?>" value="<?=$CertificationComment[$iCertificate]?>" style="width:98%" /></td>
				  </tr>
<?
                            $i++;
			}
?>
	            </table>    
<?
    if($Completed != 'Y')
    {
        print "</fieldset>";
    }
    else
    {
?>
          <input style="float:right; margin: 5px;" type="submit" value="Submit"/>
<?
    }
?>
          <br/><br/><br/><br/>
	</div>
<!--  Body Section Ends Here  -->

  </form>
  </div>
</div>
<script type="text/javascript">
    <!-- 
     function alertMsg() {
        document.getElementById("RecordMsg").innerHTML = "";
        <?$_SESSION["Flag1122"] = "";?>
     }
     setTimeout(alertMsg,3000);    
    -->
    </script>    
</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>