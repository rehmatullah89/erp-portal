<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Salamat School Systems                                                                   **
	**  Version 1.0                                                                              **
	**                                                                                           **
	**  Copyright 2010 (C) Salamat School Systems                                                **
	**  http://www.sss.edu.pk                                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://mts.sw3solutions.com                                                 **
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/index.js"></script>
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
			    <h1><img src="images/h1/dashboard.jpg" width="159" height="20" vspace="10" alt="" title="" /></h1>

			    <div class="tblSheet divDashboard">
			      <div style="margin:0px 1px 1px 0px; padding:15px 3px 15px 3px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>  
						<td width="120" align="center"><a href="quonda/dashboard.php"><img src="images/dashboard/quonda/dashboard.svg" width="70" height="70" vspace="10" alt="" title="" /></a></td>
						<td width="200"><b><a href="quonda/dashboard.php" class="link">View Today's Activity</a></b></td>
						<td></td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td  align="center"><img src="images/dashboard/quonda/qa-reports.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b class="link">View Filed QA Reports</b></td>
						
						<td>
						  <form name="frmViewReport" id="frmViewReport" method="get" action="quonda/qa-reports.php">
						    <input type="text" name="AuditCode" id="AuditCode" value="" size="15" maxlength="15" class="textbox" style="padding:4px;" placeholder="Audit Code" />
							<input type="submit" value="Go" class="button" onclick="return validateViewReport( );" />
						  </form>
						</td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td align="center"><img src="images/dashboard/quonda/new-report.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b class="link">Enter a New Report</b></td>
						
						<td>
						  <form name="frmNewReport" id="frmNewReport" method="get" action="quonda/edit-qa-report.php">
						    <select name="AuditCode" id="AuditCode" style="padding:3px;">
							  <option value="">Audit Code</option>
<?
	$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	
	
	$sSQL = "SELECT id, audit_code FROM tbl_qa_reports WHERE user_id='{$_SESSION['UserId']}' AND (audit_result='' OR ISNULL(audit_result)) AND FIND_IN_SET(report_id, '$sReportTypes') AND FIND_IN_SET(audit_stage, '$sAuditStages') ORDER BY audit_code";
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
							
							<input type="submit" value="Go" class="button" onclick="return validateNewReport( );" />
						  </form>
						</td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td align="center"><img src="images/dashboard/quonda/edit-report.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b class="link">Edit a Report</b></td>
						
						<td>
						  <form name="frmEditReport" id="frmEditReport" method="get" action="quonda/edit-qa-report.php">
						    <input type="text" name="AuditCode" id="AuditCode" value="" size="15" maxlength="15" class="textbox" style="padding:4px;" placeholder="Audit Code" />
							<input type="submit" value="Go" class="button" onclick="return validateEditReport( );" />
						  </form>
						</td>
			          </tr>
			        </table>
					
			      </div>
			    </div>

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

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>