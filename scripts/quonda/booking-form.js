
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

        if(objFV.value("SamplePickFor") == "")
        {
            alert("Please Select Sample Pick For.");
            return false;
        }
        
        if (!objFV.validate("Service", "B", "Please Select a Service."))
		return false;    

        if (!objFV.validate("Ports[]", "B", "Please select Shipping Ports."))
		return false;
            
        if (confirm('By clicking `OK` confirms that, you accept our terms and condition to proceed!'))
            return true;
        else 
            return false;

}

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

function validateEditForm(iId)
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

        if(objFV.value("SamplePickFor") == "")
        {
            alert("Please Select Sample Pick For.");
            return false;
        }
        
        if (!objFV.validate("Service", "B", "Please Select a Service."))
		return false; 
        
        if (!objFV.validate("Ports[]", "B", "Please select Shipping Ports."))
		return false;
            
	$('Processing').show( );

        
/*
	var sUrl    = "ajax/quonda/update-booking-form.php";
	var sParams = $('frmData' + iId).serialize( );

	var objForm = $("frmData" + iId);
	objForm.disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateData });
        */
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
                else if(elementName.indexOf('SamplePickFor') != -1)
                {
                    //BookingFormData.append(formElements[i].name, jQuery('input[name=SamplePickFor]:checked').val());
                }
                else
                    BookingFormData.append(formElements[i].name, formElements[i].value);
                
            }
            
            BookingFormData.append('SamplePickFor', jQuery('input[name=SamplePickFor]:checked').val());    

        (function($) {
          $(function() {

                $.ajax({
                    url: "ajax/quonda/update-booking-form.php",
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
           ////////////      
}

function _updateData(sResponse)
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
}

function DeleteBookingImage(iId, URL)
{
    if (confirm("Are you sure, You want to delete this booking attachment?")) 
    {
            jQuery.ajax({
                url: "ajax/quonda/delete-booking-image.php",
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