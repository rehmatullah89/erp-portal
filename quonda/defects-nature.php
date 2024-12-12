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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$PageId = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Defect = IO::strValue("Defect");
        $Code   = IO::strValue("Code");
	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);
                
                $Code   = IO::strValue("Code");
		$Defect = IO::strValue("Defect");
	}
        
        $sNatureList = getList("tbl_tnc_defects_nature", "code_id", "nature", "report_id='54'");
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
    <script type="text/javascript" src="scripts/quonda/defects-nature.js"></script>
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
			    <h1>TNC Defects Nature</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="80">Code</td>
			          <td width="150"><input type="text" name="Code" value="<?= $Code ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass     = array("evenRow", "oddRow");
	$sColor     = array(EVEN_ROW_COLOR, ODD_ROW_COLOR);
	$iPageSize  = PAGING_SIZE;
	$iPageCount = 0;

        $sConditions = " WHERE dc.type_id=dt.id AND dc.report_id='54' ";
            
	if ($Code != "")
		$sConditions .= " AND dc.code LIKE '$Code'";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_defect_codes dc, tbl_defect_types dt", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT dc.id, dc.code, dc.defect, dt.type FROM tbl_defect_codes dc, tbl_defect_types dt $sConditions ORDER BY dc.id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="6%">#</td>
				      <td width="12%">Code</td>
                                      <td width="25%">Type</td>
                                      <td width="32%">Defect</td>
                                      <td width="10%">Nature</td>
				      <td width="15%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}


		$iId    = $objDb->getField($i, 'id');
		$sType  = $objDb->getField($i, 'type');
                $sDefect= $objDb->getField($i, 'defect');  
                $sCode  = $objDb->getField($i, 'code');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="12%"><span id="Code<?= $iId ?>"><?= $sCode ?></span></td>
                                      <td width="25%"><span id="Type<?= $iId ?>"><?= $sType ?></span></td>
                                      <td width="32%"><span id="Defect<?= $iId ?>"><?= $sDefect ?></span></td>
                                      <td width="10%"><span id="Nature<?= $iId ?>"><?= $sNatureList[$iId] ?></span></td>
				      <td width="15%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

?>
				      </td>
				    </tr>
				  </table>


				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
						  <td width="70">Code<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><?= $sCode ?></td>
						</tr>
                                              
                                                <tr>
						  <td width="70">Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><?= $sType ?></td>
						</tr>
                                                
                                                <tr>
						  <td width="70">Defect<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><?= $sDefect ?></td>
						</tr>
                                              
                                                <tr>
						  <td width="70">Nature<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
                                                  <td>
                                                      <select name="Nature" required="">
                                                          <option value=""></option>
                                                          <option value="Critical">Critical</option>
                                                          <option value="Major">Major</option>
                                                          <option value="Minor">Minor</option>
                                                      </select>
                                                  </td>
						</tr>

						<tr>
						  <td colspan="2"></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>
<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Defects Nature Record Found!</td>
				    </tr>
				  </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Type={$Type}");
?>

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