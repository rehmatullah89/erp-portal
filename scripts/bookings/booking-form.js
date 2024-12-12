
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
jQuery.noConflict();

function validateForm( )
{
	var objFV = new FormValidator("frmData");

        if (!objFV.validate("Vendor", "B", "Please select the Supplier."))
		return false;
        
        if (!objFV.validate("ContactPersonName", "B", "Please enter the Contact Person Name."))
		return false;
            
        if (!objFV.validate("ContactPersonEmail", "B", "Please enter the Contact Person Email."))
		return false;  
            
        if (!objFV.validate("Factory", "B", "Please select the Factory."))
		return false;
        
        if (!objFV.validate("FactoryPersonName", "B", "Please enter the Factory Person Name."))
		return false;
            
        if (!objFV.validate("FactoryPersonEmail", "B", "Please enter the Factory Person Email."))
		return false; 
            
	if (!objFV.validate("Brand", "B", "Please Select the Client."))
		return false;

        if (!objFV.validate("Article", "B", "Please enter the Article Description."))
		return false;
        
        if (!objFV.validate("Ian", "B", "Please enter the IAN No."))
		return false;

        if (!objFV.validate("LotSize", "B", "Please enter the Quantity of Shipment."))
		return false;

        if (!objFV.validate("ReqInspectionDate", "B", "Please enter the Requested Inspection Date."))
		return false;
            
        if (!objFV.validate("ShippingDate", "B", "Please enter the Shipping Date."))
		return false;    
    
        /*if(objFV.value("ReqInspectionDate") != "" && objFV.value("ShippingDate") != "")
        {
            var res = fn_DateCompare(objFV.value("ReqInspectionDate"),objFV.value("ShippingDate"));
            if(res > 0)
            {
                alert("Requested Shipping Date can not be prior to the requested inspection date!");
                return false;
            }
            
        }*/
        
        if(CheckCustomSelect("Services") == false)
        {
            alert("Please select a Service.");
		return false;
        }
        
        if(CheckCustomSelect("SamplePickFor") == false)
        {
                alert("Please select Sample Pick For.");
		return false;        
        }   
        
        if(objFV.value("PortRequired") == 'Y')
        {
            alert("Please select a Port.");
		return false;
        }
        
            
        if(objFV.value("MaterialInfo") > 0)
        {
            if(document.getElementById("Material").value == "")
            {
                alert("Please enter a Material.");
		return false;
            }           
        }
            
        if(objFV.value("EditForm") == 'N')
        {
            var DisplayMsg = "By clicking `OK` confirms that, you accept our terms and condition to proceed!";
            
            if(objFV.value("MaterialInfo") > 0)
                DisplayMsg = "I/We confirm that all types of materials of the order which are listed above are available for sample pick up.";
            
            if (confirm(DisplayMsg))
                return true;
            else 
                return false;
        }
}

function CheckCustomSelect(SelectClass)
{
	var checked=false;
	var element = document.getElementsByClassName(SelectClass);

	for(var i=0; i < element.length; i++){
            if(element[i].checked)
                checked = true;
	}	
        
        return checked ;
}

function UpDateFactoryPersonInfo(obj)
{
    if(obj.value > 0)
    {
        (function($) {
              $(function() {

                    $.ajax({
                        url: "ajax/bookings/get-factoryperson-info.php",
                        data: {Id : obj.value},
                        type: "POST",
                        success: function(sResponse){

                           var sParams = sResponse.split('|-|');
                           document.getElementById('FactoryPersonName').value = sParams[0];
                           document.getElementById('FactoryPersonEmail').value = sParams[1];
                           document.getElementById('FactoryPersonPhone').value = sParams[2];
                           document.getElementById('FactoryPersonFax').value = sParams[3];
                        },
                        error: function(){
                            console.log("THERE IS SOME ERROR");
                        }                 
                   });
              });
            })(jQuery); 
        }else
        {
                           document.getElementById('FactoryPersonName').value = "";
                           document.getElementById('FactoryPersonEmail').value = "";
                           document.getElementById('FactoryPersonPhone').value = "";
                           document.getElementById('FactoryPersonFax').value = "";
        }
}

function UpDateContactPersonInfo(obj)
{
    (function($) {
          $(function() {

                $.ajax({
                    url: "ajax/bookings/get-contactperson-info.php",
                    data: {Id : obj.value},
                    type: "POST",
                    success: function(sResponse){
                        
                       var sParams = sResponse.split('|-|');
                       document.getElementById('ContactPersonName').value = sParams[0];
                       document.getElementById('ContactPersonEmail').value = sParams[1];
                       document.getElementById('ContactPersonPhone').value = sParams[2];
                       document.getElementById('ContactPersonFax').value = sParams[3];
                       document.getElementById('PortRequired').value = sParams[4];
                       
                    },
                    error: function(){
                        console.log("THERE IS SOME ERROR");
                    }                 
               });
          });
        })(jQuery);     
}

function DeleteBookingImage(iId, URL)
{
    if (confirm("Are you sure, You want to delete this booking attachment?")) 
    {
            jQuery.ajax({
                url: "ajax/bookings/delete-booking-image.php",
                data: encodeURI(URL),
                type: "POST",
                success: function(sResponse){
                    alert("File Deleted Successfully.");
                    location.reload();
                },
                error: function(){
                    $('Msg' + iId).innerHTML = "There is an error occured, while Deleting this file!";
                }                 
           });
    }
    return false;   
}

function fn_DateCompare(DateA, DateB) {     // this function is good for dates > 01/01/1970

    var a = new Date(DateA);
    var b = new Date(DateB);

    var msDateA = Date.UTC(a.getFullYear(), a.getMonth()+1, a.getDate());
    var msDateB = Date.UTC(b.getFullYear(), b.getMonth()+1, b.getDate());

    if (parseFloat(msDateA) < parseFloat(msDateB))
      return -1;  // lt
    else if (parseFloat(msDateA) == parseFloat(msDateB))
      return 0;  // eq
    else if (parseFloat(msDateA) > parseFloat(msDateB))
      return 1;  // gt
    else
      return null;  // error
}


(function($) {
    $(function() {
            $('#ReqInspectionDate').datepick({ 
            minDate: 0,
            dateFormat: 'yyyy-mm-dd',
            showTrigger: '#calImg'});
    });
    
    $(function() {
            $('#ShippingDate').datepick({ 
            minDate: 0,
            dateFormat: 'yyyy-mm-dd',
            showTrigger: '#calImg'});
    });
})(jQuery);     
/*function validateImportForm( )
{
	var objFV = new FormValidator("frmImport");

        if (!objFV.validate("Supplier", "B", "Please Select a Supplier."))
		return false;
            
	if (!objFV.validate("XmlFile", "B", "Please Select the XML File."))
		return false;

	if (objFV.value("XmlFile") != "")
	{
		if (!checkXmlFile(objFV.value("XmlFile")))
		{
			alert("Invalid File Format. Please select a valid XML File.");

			objFV.focus("XmlFile");
			objFV.select("XmlFile");

			return false;
		}
	}

	return true;
}*/

/*function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Vendor", "B", "Please select the Supplier."))
		return false;
            
	if (!objFV.validate("Brand", "B", "Please Select the Buyer."))
		return false;

	if (!objFV.validate("Factory", "B", "Please select the Factory."))
		return false;

        if (!objFV.validate("Article", "B", "Please enter the Article Description."))
		return false;
        
        if (!objFV.validate("Ian", "B", "Please enter the IAN No."))
		return false;

        if (!objFV.validate("LotSize", "B", "Please enter the Quantity of Shipment."))
		return false;

        if (!objFV.validate("ReqInspectionDate", "B", "Please enter the Requested Inspection Date."))
		return false;
            
        if (!objFV.validate("ShippingDate", "B", "Please enter the Shipping Date."))
		return false;    
    
        if (!objFV.validate("Services[]", "B", "Please Select a Service."))
		return false;        
                
        if (!objFV.validate("SamplePickFor[]", "B", "Please select Sample Pick For."))
		return false;
            
        if (!objFV.validate("ContactPersonName", "B", "Please enter the Contact Person Name."))
		return false;
            
        if (!objFV.validate("ContactPersonEmail", "B", "Please enter the Contact Person Email."))
		return false;    
    
	$('Processing').show( );

        

	//var sUrl    = "ajax/quonda/update-booking-form.php";
	//var sParams = $('frmData' + iId).serialize( );

	//var objForm = $("frmData" + iId);
	//objForm.disable( );

	//new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateData });
        
        var BookingFormData = new FormData();     
        var formElements=document.getElementById('frmData' + iId).elements;    
     
        for (var i=0; i<formElements.length; i++)
            if (formElements[i].type!="submit" && formElements[i].type!="button")
            {
                var elementName = formElements[i].name;
                
                if (elementName.indexOf('Attachments[]') != -1)
                {
                   //BookingFormData.append(formElements[i].name, jQuery("#Attachments"+iId)[0].files[0]);
                   jQuery.each(jQuery('#Attachments'+iId)[0].files, function(j, file) {
                        BookingFormData.append('Attachment-'+j, file);
                    });
                }
                else if(elementName.indexOf('Ports[]') != -1)
                {
                    BookingFormData.append(formElements[i].name, jQuery('#Ports'+iId).val());
                }
                else if(elementName.indexOf('SamplePickFor[]') != -1)
                {
                    BookingFormData.append(formElements[i].name, jQuery('#SamplePickFor'+iId).val());
                }
                else if(elementName.indexOf('Services[]') != -1)
                {
                    BookingFormData.append(formElements[i].name, jQuery('#Services'+iId).val());
                }
                else
                    BookingFormData.append(formElements[i].name, formElements[i].value);
                
            }
            
            //BookingFormData.append('SamplePickFor', jQuery('input[name=SamplePickFor]:checked').val());    

        (function($) {
          $(function() {

                $.ajax({
                    url: "ajax/bookings/update-booking-form.php",
                    data: BookingFormData,
                    type: "POST",
                    processData: false,  
                    contentType: false, 
                    cache : false,
                    success: function(sResponse){

                       _updateData(sResponse);                       
                    },
                    error: function(){
                        _showError();
                    }                 
               });
          });
        })(jQuery);         
}*/


/*function _updateData(sResponse)
{
	if (sResponse != "")
	{
		var sParams = sResponse.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$('Msg' + iId).innerHTML = sParams[2];
			$('Msg' + iId).show( );
			$('Edit' + iId).hide( );

			setTimeout(
				    function( )
				    {
					new Effect.SlideUp("Msg" + iId);

						$('Brand_' + iId).innerHTML         = sParams[3];
						$('Vendor_' + iId).innerHTML        = sParams[4];
						$('InspectionDate_' + iId).innerHTML= sParams[5];
                                                $('Stage_' + iId).innerHTML         = sParams[6];
				    },

				    2000
				  );
		}

		else if (sParams[0] == "INFO")
			_showError(sParams[2]);

		else
			_showError(sParams[1]);

		$('Processing').hide( );
                setTimeout(function(){
                    window.location.reload(1);
                 }, 3000);
		//var objForm = $("frmData" + iId);
		//objForm.enable( );
	}

	else
		_showError( );
}*/

