
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if ($('Auditors').selectedIndex == -1)
	{
		alert("Please select at-least One Auditor.");

		return false;
	}

	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;

	return true;
}

jQuery.noConflict();
(function($) {
  $(function() {
      jQuery('#Mytable .ParentAuditToggle').hide();
      
       jQuery("#FollowUpAudit, #Vendor").change(function(){
           
           jQuery("#PreviousAudit").html("");
            if(jQuery("#FollowUpAudit").is(":checked")){
                var vendor_id = jQuery("#Vendor").val();
                 if(vendor_id != ''){

                     jQuery('.ParentAuditToggle').show();
                     $.ajax({
                        type: 'post',
                        url: 'ajax/crc/get_previous_audits.php',
                        data: {
                            'Id': vendor_id
                        },

                        success: function (response) {
                          jQuery("#PreviousAudit").append(response); 
                        }
                    });
                 }else{
                     alert("Please Select Vendor First");
                     jQuery('#FollowUpAudit').attr('checked', false);
                     jQuery('.ParentAuditToggle').hide();
                 }   
             }else{
                 jQuery('.ParentAuditToggle').hide();
             }
       });
       
       
  });
})(jQuery);
