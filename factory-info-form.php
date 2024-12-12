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

	//checkLogin(false);

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = (int)base64_decode($_GET["Id"]);
        
        $sSQL = "SELECT *
	        FROM tbl_vendors
	        WHERE id='$Id'";
        $objDb->query($sSQL);
        
        $ChangeAddress              = $objDb->getField(0, 'change_address');
        $Customer                   = $objDb->getField(0, 'active_customers');
        $FactoryCrName              = $objDb->getField(0, 'factory_cr_name');
        $FactoryCrPhone             = $objDb->getField(0, 'factory_cr_phone');
        $FactoryCrEmail             = $objDb->getField(0, 'factory_cr_email');
        $FactoryOwn                 = $objDb->getField(0, 'factory_ownership');  
        $TotalEmployees             = $objDb->getField(0, 'total_employees');
        $PermanentEmployees         = $objDb->getField(0, 'permanent_employees');
        $TemporaryEmployees         = $objDb->getField(0, 'temp_employees');
        $ContractualEmployees       = $objDb->getField(0, 'contract_employees');
        $PeakMonth                  = $objDb->getField(0, 'peak_season');
        $LowMonth                   = $objDb->getField(0, 'low_season');
        $ManufactAge                = $objDb->getField(0, 'manufact_age');  
        $EmployeeTurnover           = $objDb->getField(0, 'month_turnover');
        $RSLPolicy                  = $objDb->getField(0, 'rsl_policy');
        $Certification              = $objDb->getField(0, 'certifications');
        $RSLCompliant               = $objDb->getField(0, 'rsl_compliant');
        $Products                   = $objDb->getField(0, 'product_range');
        $MajorBuyer                 = $objDb->getField(0, 'major_buyer');
        $Prodcapacity               = $objDb->getField(0, 'production_capacity');
        $Machines                   = $objDb->getField(0, 'stitching_machines');
        $SubContractors             = $objDb->getField(0, 'subcontractors');
        $Practices                  = $objDb->getField(0, 'practices');
        $ApprenticeProgram          = $objDb->getField(0, 'apprentice_program');
        $CommunicationChannel       = $objDb->getField(0, 'communication_channel');
        $Documentation              = $objDb->getField(0, 'documentation');
        $FundBenefits               = $objDb->getField(0, 'fund_benefits');   
        $Area                       = $objDb->getField(0, 'factory_area');   
        $PortionFacility            = $objDb->getField(0, 'portion_facility');
        $HazardousChemicals         = $objDb->getField(0, 'hazardous_chemicals');
        $WasteWater                 = $objDb->getField(0, 'waste_water');
        $Canteen                    = $objDb->getField(0, 'canteen');
        $ChildCare                  = $objDb->getField(0, 'child_care');
        $Dormotories                = $objDb->getField(0, 'dormotories');  
        
        if ($objDb->getCount( ) != 1)
            redirect("http://portal.3-tree.com/404.php", "ACCESS_DENIED");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
    <script type="text/javascript" src="scripts/factory-info-form.js"></script>
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
			  <td>
			    <h1>Factory Information</h1>

			    <form name="frmAccount" id="frmAccount" method="post" action="update-factory-info.php" class="frmOutline" onsubmit="$('BtnCreate').disable( );">
                                <input type="hidden" name="Id" value="<?=$Id?>"/>
			    <div style="padding:10px 10px 25px 10px;">
			      <b>Welcome to SourcePro Customer Portal</b><br />
			      <br />
			      Please provide the information below in order to complete factory details.<br />
			    </div>

<?
	if ($_POST["Error"] != "")
	{
?>
				<div class="error">
				  <b>Please provide the valid values of following fields:</b><br />
				  <br style="line-height:5px;" />
				  <?= $_POST["Error"] ?><br />
				</div>

				<br />

<?
	}
?>

			    <h2>General Information</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="350">Factory Name<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
                                    <td><?= getDbValue("vendor", "tbl_vendors", "id='$Id'"); ?></td>
				  </tr>

				  <tr valign="top">
				    <td>Customer Name</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CustomerName" value="<?= $Customer?>" maxlength="100" size="26" class="textbox" /></td>
				  </tr>

                                    <tr>
                                        <td>Factory Address <br/><span style="font-size: 9px;">(If factory address is changed, please specify the new address in the comment field.)</span></td>
                                        <td align="center">:</td>
                                        <td><textarea name="Address" rows="3" cols="24" style="width:196px;"><?= $ChangeAddress ?></textarea></td>
                                    </tr>

                                    <tr>
                                        <td>Factory CR Contact Name <br/><span style="font-size: 9px;">(At least 1 contact name who oversee's factory compliance audit)</span></td>
                                        <td align="center">:</td>
                                        <td><input type="text" name="FactoryCrName" value="<?= $FactoryCrName ?>" maxlength="100" size="26" class="textbox" /></td>
                                    </tr>

                                    <tr>
                                      <td>Factory CR Contact Phone</td>
                                      <td align="center">:</td>
                                      <td><input type="text" name="FactoryCrPhone" value="<?= $FactoryCrPhone ?>" maxlength="100" size="26" class="textbox" /></td>
                                    </tr>

                                    <tr>
                                      <td>Factory CR Contact Email</td>
                                      <td align="center">:</td>
                                      <td><input type="text" name="FactoryCrEmail" value="<?= $FactoryCrEmail ?>" maxlength="100" size="26" class="textbox" /></td>
                                    </tr>
                                
                                    <tr>
                                      <td>Factory Owned/Rented</td>
                                      <td align="center">:</td>
                                      <td>
                                          <select nme="FactoryOwn" style="width:200px;">
                                            <option value=""></option>
                                            <option <?=($FactoryOwn == 'O'?'selected':'')?> value="O">Owned</option>
                                            <option <?=($FactoryOwn == 'R'?'selected':'')?> value="R">Rented</option>
                                          </select>
                                      </td>
                                    </tr>
                                
                                    <tr>
                                        <td>Total number of employees <br/><span style="font-size: 9px;">(Male & Female)</span></td>
                                        <td align="center">:</td>
                                        <td><input type="number" name="TotalEmployees" value="<?= $TotalEmployees ?>" maxlength="100" size="26" class="textbox" style="width: 196px;" /></td>
                                    </tr>
                                
                                    <tr>
                                        <td>Number of Permanent workers</td>
                                        <td align="center">:</td>
                                        <td><input type="number" name="PermanentEmployees" value="<?= $PermanentEmployees ?>" maxlength="100" size="26" class="textbox" style="width: 196px;" /></td>
                                    </tr>
                                
                                    <tr>
                                        <td>Number of Temporary Piece rate workers <br/><span style="font-size:9px;">(If not permanent.)</span></td>
                                        <td align="center">:</td>
                                        <td><input type="number" name="TemporaryEmployees" value="<?= $TemporaryEmployees ?>" maxlength="100" size="26" class="textbox" style="width: 196px;" /></td>
                                    </tr>
                                
                                    <tr>
                                        <td>Number of Temporary daily wage/contractual workers <br/><span style="font-size:9px;">(If not permanent.)</span></td>
                                        <td align="center">:</td>
                                        <td><input type="number" name="ContractEmployees" value="<?= $ContractualEmployees ?>" maxlength="100" size="26" class="textbox" style="width: 196px;" /></td>
                                    </tr>

                                    <tr valign="top">
                                    <td>Peak Season Month</td>
                                    <td align="center">:</td>
                                    <td>
                                        <select nme="PeakMonth" style="width: 200px;">
                                            <option value=""></option>
                                            <option <?=($PeakMonth == '1'?'selected':'')?> value="1">January</option>
                                            <option <?=($PeakMonth == '2'?'selected':'')?> value="2">February</option>
                                            <option <?=($PeakMonth == '3'?'selected':'')?> value="3">March</option>
                                            <option <?=($PeakMonth == '4'?'selected':'')?> value="4">April</option>
                                            <option <?=($PeakMonth == '5'?'selected':'')?> value="5">May</option>
                                            <option <?=($PeakMonth == '6'?'selected':'')?> value="6">June</option>
                                            <option <?=($PeakMonth == '7'?'selected':'')?> value="7">July</option>
                                            <option <?=($PeakMonth == '8'?'selected':'')?> value="8">August</option>
                                            <option <?=($PeakMonth == '9'?'selected':'')?> value="9">September</option>
                                            <option <?=($PeakMonth == '10'?'selected':'')?> value="10">Ocober</option>
                                            <option <?=($PeakMonth == '11'?'selected':'')?> value="11">November</option>
                                            <option <?=($PeakMonth == '12'?'selected':'')?> value="12">December</option>                                                          
                                        </select>
                                    </td>
                                  </tr>

                                  <tr valign="top">
                                    <td>Low Season Month</td>
                                    <td align="center">:</td>
                                    <td>
                                        <select nme="LowMonth" style="width: 200px;">
                                            <option value=""></option>
                                            <option <?=($LowMonth == '1'?'selected':'')?> value="1">January</option>
                                            <option <?=($LowMonth == '2'?'selected':'')?> value="2">February</option>
                                            <option <?=($LowMonth == '3'?'selected':'')?> value="3">March</option>
                                            <option <?=($LowMonth == '4'?'selected':'')?> value="4">April</option>
                                            <option <?=($LowMonth == '5'?'selected':'')?> value="5">May</option>
                                            <option <?=($LowMonth == '6'?'selected':'')?> value="6">June</option>
                                            <option <?=($LowMonth == '7'?'selected':'')?> value="7">July</option>
                                            <option <?=($LowMonth == '8'?'selected':'')?> value="8">August</option>
                                            <option <?=($LowMonth == '9'?'selected':'')?> value="9">September</option>
                                            <option <?=($LowMonth == '10'?'selected':'')?> value="10">Ocober</option>
                                            <option <?=($LowMonth == '11'?'selected':'')?> value="11">November</option>
                                            <option <?=($LowMonth == '12'?'selected':'')?> value="12">December</option>                                                          
                                        </select>
                                    </td>
                                  </tr>
                                
                                      <tr valign="top">
                                        <td>Age of Facility & Manufacturing Operations</td>
                                        <td align="center">:</td>
                                        <td><input type="text" name="ManufactAge" value="<?= $ManufactAge ?>" size="26" class="textbox" /></td>
                                      </tr>

                                      <tr valign="top">
                                        <td>Monthly Employee Turnover</td>
                                        <td align="center">:</td>
                                        <td><input type="text" name="EmployeeTurnover" value="<?= $EmployeeTurnover ?>" size="26" class="textbox" /></td>
                                      </tr>

                                      <tr valign="top">
                                        <td>Is a copy of Restricted Substances List (RSL) policy available for review?</td>
                                        <td align="center">:</td>
                                        <td>
                                            <select nme="RSLPolicy"  style="width: 200px;">
                                                <option value=""></option>
                                                <option <?=($RSLPolicy == 'Y'?'selected':'')?> value="Y">Available</option>
                                                <option <?=($RSLPolicy == 'N'?'selected':'')?> value="N">Not Available</option>
                                            </select>
                                        </td>
                                      </tr>

                                      <tr valign="top">
                                        <td>Is there a process to ensure RSL compliant materials are used?</td>
                                        <td align="center">:</td>
                                        <td>
                                            <select nme="RSLCompliant"  style="width: 200px;">
                                                <option value=""></option>
                                                <option <?=($RSLCompliant == 'Y'?'selected':'')?> value="Y">Available</option>
                                                <option <?=($RSLCompliant == 'N'?'selected':'')?> value="N">Not Available</option>
                                            </select>
                                        </td>
                                      </tr>
				</table>

				<br />

			    <h2>Products</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="350">Please provide a brief description of the factory's product(s)?</td>
				    <td width="20" align="center">:</td>
				    <td><textarea name="Products" rows="3" cols="24" style="width:196px;"><?= $Products ?></textarea></td>
				  </tr>

				  <tr>
				    <td>Major Buyer(s)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="MajorBuyer" value="<?= $MajorBuyer ?>" maxlength="100" size="26" class="textbox" /></td>
				  </tr>
				</table>

				<br />

			    <h2>Factory Capacity</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="350">Total Production Capacity(Monthly)</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="ProductionCapacity" id="Username" value="<?= $Prodcapacity ?>" maxlength="100" size="26" class="textbox"  /> </td>
				  </tr>

				  <tr>
				    <td>Total number of sewing machines. </td>
				    <td align="center">:</td>
				    <td><input type="text" name="TotalMachines" value="<?= $Machines ?>" size="26" maxlength="100" class="textbox" /></td>
				  </tr>
			    </table>
                            
                            <br />

			    <h2>Subcontractors</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
                                      <td width="350">Does the factory use subcontractors? <br/><span style="font-size: 9px;">(Fabric processing, embelishment, embroidery, Printing, Garment wash)</span></td>
				    <td width="20" align="center">:</td>
                                    <td>
                                        <textarea name="SubContractors" rows="5" cols="30" style="width:196px;"><?= $SubContractors  ?></textarea>
                                    </td>
				  </tr>
			    </table>
                            <br />
                            
                            <h2>Certifications</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
                                      <td width="350">Please list all available certificaions including validity? <br/><span style="font-size: 9px;">(ISO, OHSAS, Sedex, BSCI, WRAP etc)</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Certifications" id="Username" value="<?= $Certification ?>" maxlength="100" size="26" class="textbox"  /> </td>
				  </tr>
                                
                                  <tr>
                                      <td width="350">Beyond Compliance initiatives OR Best Practices?</td>
				    <td width="20" align="center">:</td>
                                    <td>
                                        <select name="Practices"  style="width: 200px;">
                                            <option value=""></option>
                                            <option <?=($Practices == 'C'?'selected':'')?> value="C">Beyond Compliance Initative</option>
                                            <option <?=($Practices == 'B'?'selected':'')?> value="B">Best Practices</option>
                                        </select>
                                    </td>
				  </tr>
			    </table>
                            <br />
                            
                            <h2>Labor</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
                                      <td width="350">Is there any apprentice program in the factory?</td>
				    <td width="20" align="center">:</td>
				    <td> 
                                        <select name="ApprenticeProgram"  style="width: 200px;">
                                            <option value=""></option>
                                            <option <?=($ApprenticeProgram == 'Y'?'selected':'')?> value="Y">Yes</option>
                                            <option <?=($ApprenticeProgram == 'N'?'selected':'')?> value="N">No</option>
                                        </select></td>
				  </tr>
                                
                                  <tr valign="top">
                                    <td width="150">Are there any formal/informal communication channels (Worker Committee or work Council)?</td>
                                    <td width="20" align="center">:</td>
                                    <td>
                                        <select name="CommunicationChannel"  style="width: 200px;">
                                            <option value=""></option>
                                            <option <?=($CommunicationChannel == 'Y'?'selected':'')?> value="Y">Yes</option>
                                            <option <?=($CommunicationChannel == 'N'?'selected':'')?> value="N">No</option>
                                        </select>
                                    </td>
                                  </tr>

                                  <tr valign="top">
                                    <td width="150">Do workers receive documented oriemntation at the time of hiring?</td>
                                    <td width="20" align="center">:</td>
                                    <td>
                                        <select name="Documentation"  style="width: 200px;">
                                            <option value=""></option>
                                            <option <?=($Documentation == 'Y'?'selected':'')?> value="Y">Yes</option>
                                            <option <?=($Documentation == 'N'?'selected':'')?> value="N">No</option>
                                        </select>
                                    </td>
                                  </tr>

                                  <tr valign="top">
                                    <td width="150">Does the factory provide Gratuity or PF benefits to its workers?</td>
                                    <td width="20" align="center">:</td>
                                    <td>
                                        <select name="FundBenefits"  style="width: 200px;">
                                            <option value=""></option>
                                            <option <?=($FundBenefits == 'Y'?'selected':'')?> value="Y">Yes</option>
                                            <option <?=($FundBenefits == 'N'?'selected':'')?> value="N">No</option>
                                        </select>
                                    </td>
                                  </tr>
			    </table>
                            <br/>
                            
                            <h2>HSE</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
                                      <td width="350">What is the total factory building area in square meters?</td>
				    <td width="20" align="center">:</td>
                                    <td><input type="text" name="BuildingArea" value="<?= $Area ?>" maxlength="100" size="26" class="textbox"  /></td> 
				  </tr>
                                  
                                    <tr valign="top">
                                        <td width="150">Are there multi-storey buildings where factory occupies only a portion of the facility?</td>
                                        <td width="20" align="center">:</td>
                                        <td>
                                            <select name="PortionFacility"  style="width: 200px;">
                                                <option value=""></option>
                                                <option <?=($PortionFacility == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                <option <?=($PortionFacility == 'N'?'selected':'')?> value="N">No</option>
                                            </select>
                                        </td>
                                      </tr>

                                      <tr valign="top">
                                        <td width="150">Are there any hazardous chemicals used at this factory?</td>
                                        <td width="20" align="center">:</td>
                                        <td>
                                            <select name="HazardousChemicals"  style="width: 200px;">
                                                <option value=""></option>
                                                <option <?=($HazardousChemicals == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                <option <?=($HazardousChemicals == 'N'?'selected':'')?> value="N">No</option>
                                            </select>
                                        </td>
                                      </tr>

                                      <tr valign="top">
                                        <td width="150">Does this factory generate any wastewater that requires treatment?</td>
                                        <td width="20" align="center">:</td>
                                        <td>
                                            <select name="WasteWater"  style="width: 200px;">
                                                <option value=""></option>
                                                <option <?=($WasteWater == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                <option <?=($WasteWater == 'N'?'selected':'')?> value="N">No</option>
                                            </select>
                                        </td>
                                      </tr>

                                      <tr valign="top">
                                        <td width="150">Is there a canteen in the factory?</td>
                                        <td width="20" align="center">:</td>
                                        <td>
                                            <select name="Canteen"  style="width: 200px;">
                                                <option value=""></option>
                                                <option <?=($Canteen == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                <option <?=($Canteen == 'N'?'selected':'')?> value="N">No</option>
                                            </select>
                                        </td>
                                      </tr>

                                      <tr valign="top">
                                        <td width="150">Does the factory provide childcare?</td>
                                        <td width="20" align="center">:</td>
                                        <td>
                                            <select name="ChildCare"  style="width: 200px;">
                                                <option value=""></option>
                                                <option <?=($ChildCare == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                <option <?=($ChildCare == 'N'?'selected':'')?> value="N">No</option>
                                            </select>
                                        </td>
                                      </tr>

                                      <tr valign="top">
                                        <td width="150">Does the factory provide onsite or factory owned offiste dormotories?</td>
                                        <td width="20" align="center">:</td>
                                        <td>
                                            <select name="Dormotories"  style="width: 200px;">
                                                <option value=""></option>
                                                <option <?=($Dormotories == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                <option <?=($Dormotories == 'N'?'selected':'')?> value="N">No</option>
                                            </select>
                                        </td>
                                      </tr>                                                
                            </table>
				<br />

			    <h2>&nbsp;</h2>

			    <div style="padding:10px 0px 20px 40px;">
			      Please enter the Spam Protection code below in the box:<br />

			      <table width="100%" cellspacing="0" cellpadding="0" border="0" class="text">
				    <tr>
				      <td width="125"><img src="requires/captcha.php" width="120" height="22" vspace="5" alt="" title="" /></td>
				      <td><input type="text" name="SpamCode" maxlength="5" value="" size="26" autocomplete="off" class="textbox" style="padding:3px; height:14px;" /></td>
				    </tr>
			      </table>
			    </div>

			    <div class="buttonsBar">
			      <input type="submit" id="BtnCreate" value="" class="btnSubmit" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnCancel" onclick="document.location='./';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
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

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>