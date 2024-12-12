
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
	***********************************************************************************************
	\*********************************************************************************************/

function validateForm( )
{
	var objFV = new FormValidator("frmData");

        if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
            
	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
            
        if (!objFV.validate("Auditor", "B", "Please select the Auditor."))
		return false;   
            
        if (!objFV.validate("AuditType", "B", "Please select the Audit Type."))
		return false;   
    
        /*if (objFV.value("AuditType") == "4" || objFV.value("AuditType") == "5")        
        {
            if (!objFV.validate("PreviousAudit", "B", "Please select a Previous Audit."))
		return false;
        }*/ 
        
        if (objFV.value("GroupAudit") == "Y")
        {
            if (!objFV.validate("PreviousAudit", "B", "Please select a Parent Audit."))
		return false;
        }
       
        if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;  
        
        if (!objFV.validate("Section", "B", "Please select a Audit Section."))
                return false;

        if (!objFV.validate("ddQuestions", "B", "Please select a Question Type."))
            return false;
        
	return true;
}

function getPreviousAudits(Group)
{
    if(Group == 'Y')
    {
        if(document.getElementById("Vendor").value == "" || document.getElementById("Brand").value == "")
        {
            document.getElementById("GroupAudit").value = "N";
            alert("Please Select Brand/ Vendor First to Proceed!");            
        }
        
        if(document.getElementById("Vendor").value > 0 && document.getElementById("Brand").value > 0)
        {
            document.getElementById('PreviousAuditBlock').style.display = '';
            
            var Unit = 0;
            if(document.getElementById("Unit").value > 0)
                Unit = document.getElementById("Unit").value;
            
            var sUrl    = "ajax/crc/get-previous-audits.php";
            var sParams = ("VendorId="+document.getElementById("Vendor").value+"&Unit="+Unit+"&AuditId=0");
            new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getPrevAuditsList });
        }
    }
    else
        document.getElementById('PreviousAuditBlock').style.display = 'none';
}

function getQuestionsOptions(AuditType)
{
    if(AuditType == 4)
    {
        document.getElementById("ddQuestions").value = "";
        document.getElementById('toggleOpt').style.display = '';
        document.getElementById('AuditSectionId').style.display = '';
        document.getElementById('SelectQuestionsBlock').style.display = '';
        
    }
    else if(AuditType == 5)
    {        
        document.getElementById('AuditSectionId').style.display = 'none';
        document.getElementById('SelectQuestionsBlock').style.display = 'none';        
    }    
    else
    {
        document.getElementById("ddQuestions").value = "";
        document.getElementById('toggleOpt').style.display = 'none';
        document.getElementById('AuditSectionId').style.display = '';
        document.getElementById('SelectQuestionsBlock').style.display = '';
    }
}

function checkAll(bx){
    var form = bx.form;
    var ischecked = bx.checked;
    for (var i = 0; i < form.length; ++i) {
        if (form[i].type == 'checkbox') {
            form[i].checked = ischecked;
        }
    }
}

function resetPoints()
{
    document.getElementById("ddQuestions").value = "";
    document.getElementById("Questions").innerHTML = "";
    document.getElementById('PointsBlock').style.display = 'none';
    document.getElementById('PointsBlock2').style.display = 'none';
    document.getElementById("toggleChecks").style.display = 'none';
    
    return;
}

function resetSections()
{    
    document.getElementById("Section").value = "";
    document.getElementById("AuditType").value = "";
    document.getElementById("Questions").innerHTML = "";    
    document.getElementById('PointsBlock').style.display = 'none';
    document.getElementById('PointsBlock2').style.display = 'none';
    document.getElementById("toggleChecks").style.display = 'none';
    document.getElementById("PreviousAuditBlock").style.display = 'none';
    
    return;
}

function hideQuestionType()
{
    document.getElementById("PreviousAudit").value = "";
    document.getElementById("AuditType").value = "";
    document.getElementById("GroupAudit").value = "N";
    document.getElementById("PreviousAuditBlock").style.display = 'none';
}

function resetSections2()
{
    
    document.getElementById("Section").value = "";
    document.getElementById("Questions").innerHTML = "";    
    document.getElementById('PointsBlock').style.display = 'none';
    document.getElementById('PointsBlock2').style.display = 'none';
    document.getElementById("toggleChecks").style.display = 'none';
    
    return;
}

function getPoints(iId, QType, sChild)
{
	if (iId == "")
		return;
            
        var pChild  = document.getElementById('Brand').value; 
        
        if((QType == 'Q' || QType == 'S') && pChild > 0)
        {            
            var sUrl    = "ajax/crc/get-tnc-catpoints-list.php";
            var sParams = ("Id=" + iId + "&List=" + sChild + "&Brand=" + pChild + "&Type=" + QType);

            new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getResponseList });

            document.getElementById('PointsBlock').style.display = '';
            document.getElementById('PointsBlock2').style.display = '';
            document.getElementById('toggleChecks').style.display = '';           
        }
        else if(pChild == "")
        {           
            alert("Please select a Brand!");
            return;
        }
        else
        {
            document.getElementById('PointsBlock').style.display = 'none';
            document.getElementById('PointsBlock2').style.display = 'none';
            document.getElementById('toggleChecks').style.display = 'none';
            return;
        }
}

function _getResponseList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var sChild = sParams[1];
                        var mydiv = document.getElementById("Questions");
                        mydiv.innerHTML = sParams[2];
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}

function _getPrevAuditsList(sResponse)
{
    if (sResponse.status == 200 && sResponse.statusText == "OK")
    {
            var sParams = sResponse.responseText.split('|-|');

            if (sParams[0] == "OK")
            {
                    document.getElementById("PreviousAudit").innerHTML = sParams[1];
            }

            else
            {
                    document.getElementById("PreviousAudit").innerHTML = "";
                    _showError(sParams[1]);
            }
    }

    else
            _showError( );
}
