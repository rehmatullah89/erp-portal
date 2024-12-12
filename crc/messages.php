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

	if ($_SESSION['Flag'] != "")
	{
		$sMessages = array(
						    'ERROR'                                => 'An Error occured while processing your request. Please try again!',
						    'DB_ERROR'                             => 'An Error is returned from Database while processing your request. Please try again!',
							'MAIL_ERROR'                           => 'An error occured while sending you an Email. Please try again.',
						    'DATA_SAVED'                           => 'The selected Form Data has been Saved succesfully.',
							'INVALID_SPAM_CODE'                    => 'Please enter the exact Spam Code as shown in the image.',
							'DONATION_SAVED'                       => 'Thankyou for donating into the MATRIX Sourcing Flood Relief Fund.',

						    'EMAIL_EXISTS'                         => 'The specified Email Adress is already in Use. Please specify another Email Address.',
						    'USERNAME_EXISTS'                      => 'The specified Username is already in Use. Please specify another Username.',
						    'ACCOUNT_CREATED'                      => 'Your Account has been Created successfully. You will be notified on Approval of your Account by Administrator.',
							'ACCOUNT_DISABLED'                     => 'Your Account is Disabled, please contact Administrator for more details.',
							'ACCOUNT_NOT_ACTIVE'                   => 'Your Account is not Approved by the Administrator. Contact Administrator for Details.',
						    'ALREADY_LOGGED_IN'                    => 'You are already Logged into your Account.',
						    'INVALID_LOGIN'                        => 'Please provide the Correct Login Info!',
						    'LOGIN'                                => 'Please Login into your Account to access the requested section.',
						    'ACCESS_DENIED'                        => 'You havn\'t enough Rights to access the requested page/section.',
						    'ACCOUNT_DELETED'                      => 'Your Account has been Deleted by the Website Administrator.',
							'PASSWORD_CHANGED'                     => 'Your Account Password has been Changed successfully.',
							'PASSWORD_CHANGE_ERROR'                => 'Invalid Password Change Request. Please try again.',
							'ACCOUNT_UPDATED'                      => 'Your Account Information has been Saved successfully.',
							'SIGNATURES_EXPORTED'                  => 'The Signatures of all Matrix Employees have been Generated successfully.',
							'CURRENT_STANDING_SENT'                => 'The selected Brand POs has been Emailed to the respective Brand Manager.',
							'DELAY_SEASON_SAVED'                   => 'The selected PO Delay Reason has been saved successfully.',

							'USER_STATUS_UPDATED'                  => 'The Status of the selected User has been changed successfully.',
							'USER_ACCOUNT_UPDATED'                 => 'The selected User Account has been Updated successfully.',
							'USER_EVOLUTIONARY_PROFILE_UPDATED'    => 'The Evolutionary Profile of selected Employee has been Updated successfully.',

                                                    'GUIDELINE_ADDED'                      => 'The specified Guideline has been Added into the System successfully.',
                                                    'GUIDELINE_UPDATED'                    => 'The specified Guideline has been Updated successfully.',
                                                    'GUIDELINE_DELETED'                    => 'The specified Guideline has been Deleted successfully.',
                    
						    'COUNTRY_ADDED'                        => 'The specified Country has been Added into the System successfully.',
						    'COUNTRY_DELETED'                      => 'The selected Country has been Deleted from the System successfully.',

						    'OFFICE_ADDED'                         => 'The specified Office has been Added into the System successfully.',
						    'OFFICE_EXISTS'                        => 'The specified Office already exists in the System.',
						    'OFFICE_DELETED'                       => 'The selected Office has been Deleted from the System successfully.',

						    'CATEGORY_ADDED'                       => 'The specified Category has been Added into the System successfully.',
						    'CATEGORY_EXISTS'                      => 'The specified Category already exists in the System.',
						    'CATEGORY_DELETED'                     => 'The selected Category has been Deleted from the System successfully.',
                    
                                                    'CRC_DEPARTMENT_ADDED'                 => 'The specified Department has been Added into the System successfully.',
						    'CRC_DEPARTMENT_EXISTS'                => 'The specified Department already exists in the System.',
						    'CRC_DEPARTMENT_DELETED'               => 'The selected Department has been Deleted from the System successfully.',
                                                    'CRC_DEPARTMENT_POSITION_UPDATED'      => 'The selected Department Position has been Updated successfully.',

						    'GENDER_ADDED'                         => 'The specified Gender has been Added into the System successfully.',
						    'GENDER_EXISTS'                        => 'The specified Gender already exists in the System.',
						    'GENDER_DELETED'                       => 'The selected Gender has been Deleted from the System successfully.',

						    'VENDOR_ADDED'                         => 'The specified Vendor has been Added into the System successfully.',
						    'VENDOR_EXISTS'                        => 'The specified Vendor / Code / Country already exists in the System.',
						    'VENDOR_DELETED'                       => 'The selected Vendor has been Deleted from the System successfully.',

						    'VIDEO_ADDED'                          => 'The specified Video has been Added into the System successfully.',
						    'VIDEO_EXISTS'                         => 'The specified Video Title already exists in the System.',
						    'VIDEO_DELETED'                        => 'The selected Video has been Deleted from the System successfully.',
						    'VIDEO_UPDATED'                        => 'The selected Video has been Updated successfully.',

						    'VENDOR_ALBUM_ADDED'                   => 'The specified Vendor Album has been Added into the System successfully.',
						    'VENDOR_ALBUM_EXISTS'                  => 'The specified Vendor Album already exists in the System.',
						    'VENDOR_ALBUM_UPDATED'                 => 'The selected Vendor Album has been Updated successfully.',
						    'VENDOR_ALBUM_DELETED'                 => 'The selected Vendor Album has been Deleted from the System successfully.',

						    'VENDOR_ALBUM_PICS_ADDED'              => 'The selected Vendor Pictures have been Saved into the System successfully.',
						    'VENDOR_ALBUM_PIC_UPDATED'             => 'The selected Vendor Picture has been Updated successfully.',
						    'VENDOR_ALBUM_PIC_DELETED'             => 'The selected Vendor Picture has been Deleted from the System successfully.',

						    'FABRIC_CATEGORY_ADDED'                => 'The specified Fabric Category has been Added into the System successfully.',
						    'FABRIC_CATEGORY_EXISTS'               => 'The specified Fabric Category already exists in the System.',
						    'FABRIC_CATEGORY_UPDATED'              => 'The selected Fabric Category has been Updated successfully.',
						    'FABRIC_CATEGORY_DELETED'              => 'The selected Fabric Category has been Deleted from the System successfully.',

						    'FABRIC_CATEGORY_PICS_ADDED'           => 'The selected Fabric Pictures have been Saved into the System successfully.',
						    'FABRIC_CATEGORY_PIC_UPDATED'          => 'The selected Fabric Picture has been Updated successfully.',
						    'FABRIC_CATEGORY_PIC_DELETED'          => 'The selected Fabric Picture has been Deleted from the System successfully.',

						    'BRAND_ADDED'                          => 'The specified Brand has been Added into the System successfully.',
						    'BRAND_UPDATED'                        => 'The selected Brand has been Updated successfully.',
						    'BRAND_EXISTS'                         => 'The specified Brand already exists in the System.',
						    'BRAND_DELETED'                        => 'The selected Brand has been Deleted from the System successfully.',

						    'SEASON_ADDED'                         => 'The specified Season has been Added into the System successfully.',
						    'SEASON_EXISTS'                        => 'The specified Season already exists in the System.',
						    'SEASON_DELETED'                       => 'The selected Season has been Deleted from the System successfully.',
						    'SEASON_POSITION_UPDATED'              => 'The Position of selected Season has been Updated successfully.',

						    'DESTINATION_ADDED'                    => 'The specified Destination has been Added into the System successfully.',
						    'DESTINATION_EXISTS'                   => 'The specified Destination already exists in the System.',
						    'DESTINATION_DELETED'                  => 'The selected Destination has been Deleted from the System successfully.',

						    'SIZE_ADDED'                           => 'The specified Size has been Added into the System successfully.',
						    'SIZE_EXISTS'                          => 'The specified Size already exists in the System.',
						    'SIZE_DELETED'                         => 'The selected Size has been Deleted from the System successfully.',
						    'SIZE_POSITION_UPDATED'                => 'The Position of selected Size has been Updated successfully.',

						    'STYLE_CATEGORY_ADDED'                 => 'The specified Style Category has been Added into the System successfully.',
						    'STYLE_CATEGORY_EXISTS'                => 'The specified Style Category already exists in the System.',
						    'STYLE_CATEGORY_DELETED'               => 'The selected Style Category has been Deleted from the System successfully.',

						    'STYLE_ADDED'                          => 'The specified Style has been Added into the System successfully.',
						    'STYLE_EXISTS'                         => 'The specified Style already exists in the System.',
						    'STYLE_UPDATED'                        => 'The selected Style has been Updated successfully.',
						    'STYLE_DELETED'                        => 'The selected Style has been Deleted from the System successfully.',
						    'STYLE_SKETCH_SAVED'                   => 'The Style Sketch/Image has been Saved successfully.',

						    'PO_SAVED'                             => 'The specified Purchase Order has been Saved into the System successfully.',
						    'PO_DELETED'                           => 'The selected Purchase Order has been Deleted from the System successfully.',
						    'ORDER_NO_EXISTS'                      => 'The specified (Order No + Style No) already exists in the System.',
						    'INVALID_PO'                           => 'The specified Purchase Order is Invalid, No record found in the System.',
						    'PO_STATUS_UPDATED'                    => 'The Status of selected Purchase Order(s) have been Updated successfully.',
						    'PO_ACKNOWLEDGED'                      => 'The selected PO has been Acknowledged successfully.',
						    'PO_RESTORED'                          => 'The selected PO has been Restored successfully.',
						    'NO_POS_CSV_FILE'                      => 'Please select a valid POs CSV File.',
						    'INVALID_POS_CSV_FILE'                 => 'Invalid PO CSV File Format',
						    'POS_CSV_IMPORT_OK'                    => 'The selected POs Csv File has been Processed successfully.',
						    'POS_CSV_IMPORT_ERROR'                 => 'The selected POs Csv File contain errors, please correct and re-import.',

						    'ETD_REVISION_REASON_ADDED'            => 'The specified ETD Revision Reason has been Added into the System successfully.',
						    'ETD_REVISION_REASON_EXISTS'           => 'The specified ETD Revision Reason already exists in the System.',
						    'ETD_REVISION_REASON_DELETED'          => 'The selected ETD Revision Reason has been Deleted from the System successfully.',
						    'ETD_REVISION_FILE_SENT'               => 'The ETD Revision Reasons File has been Emailed to the selected User.',

						    'ETD_REVISION_REQUEST_SAVED'           => 'The ETD Revision of selected PO has been Submitted successfully.',
						    'ETD_REVISION_REQUEST_UPDATED'         => 'The selected ETD Revision Request has been Updated successfully.',
						    'ETD_REVISION_REQUEST_DELETED'         => 'The selected ETD Revision Request has been Deleted from the System successfully.',

						    'ETD_MANAGER_ADDED'                    => 'The specified ETD Manager has been Added into the System successfully.',
						    'ETD_MANAGER_EXISTS'                   => 'The specified ETD Manager already exists in the System.',
						    'ETD_MANAGER_DELETED'                  => 'The selected ETD Manager has been Deleted from the System successfully.',

						    'BRAND_OFFICE_ADDED'                   => 'The specified Brand Office has been Added into the System successfully.',
						    'BRAND_OFFICE_DELETED'                 => 'The selected Brand Office has been Deleted from the System successfully.',
						    'BRAND_OFFICE_EXISTS'                  => 'The specified Brand Office already exists in the successfully.',

						    'LIBRARY_ITEM_ADDED'                   => 'The sepecified Library Item have been Saved into the System successfully.',
						    'LIBRARY_ITEM_UPDATED'                 => 'The selected Library Item has been Updated successfully.',
						    'LIBRARY_ITEM_DELETED'                 => 'The selected Library Item has been Deleted from the System successfully.',

						    'TERMS_OF_DELIVERY_ADDED'              => 'The specified Terms of Delivery has been Added into the System successfully.',
						    'TERMS_OF_DELIVERY_EXISTS'             => 'The specified Terms of Delivery already exists in the System.',
						    'TERMS_OF_DELIVERY_DELETED'            => 'The selected Terms of Delivery has been Deleted from the System successfully.',

						    'SHIPMENT_DETAIL_ADDED'                => 'A Shipment Record has been Added for the selected PO successfully.',
						    'SHIPMENT_DETAIL_DELETED'              => 'The selected Shipment Record has been Deleted from the System successfully.',
						    'SHIPMENT_DETAIL_COPIED'               => 'The Shipment Details of the selected PO has been Copied successfully.',

						    'AUDITORS_GROUP_ADDED'                 => 'The specified Auditors Group has been Added into the System successfully.',
						    'AUDITORS_GROUP_EXISTS'                => 'The specified Auditors group already exists in the System',
						    'AUDITORS_GROUP_DELETED'               => 'The selected Auditors Group has been Deleted from the System successfully.',

						    'LINE_ADDED'                           => 'The specified Vendor Line has been Added into the System successfully.',
						    'LINE_EXISTS'                          => 'The specified Vendor Line already exists in the System.',
						    'LINE_DELETED'                         => 'The selected Vendor Line has been Deleted from the System successfully.',
						    'LINE_NO_ALLOWED'                      => 'You cannot Create a New Line in the selected Vendor.',

						    'FLOOR_ADDED'                          => 'The specified Vendor Floor has been Added into the System successfully.',
						    'FLOOR_EXISTS'                         => 'The specified Vendor Floor already exists in the System.',
						    'FLOOR_DELETED'                        => 'The selected Vendor Floor has been Deleted from the System successfully.',

						    'AUDIT_CODE_ADDED'                     => 'The specified Audit Code has been Generated successfully.',
						    'AUDIT_CODE_DELETED'                   => 'The selected Audit Entry has been Deleted from the System successfully.',
						    'INVALID_AUDIT_CODE'                   => 'The specified Audit Code is Invalid, No record found in the System.',
						    'INVALID_ORDER_NO'                     => 'Invalid Order No. Please enter a valid Order No.',
						    'INVALID_STYLE_NO'                     => 'Invalid Style No. Please enter a valid Style No w.r.t the Order No.',
						    'INVALID_PO_STYLE_NO'                  => 'Invalid Order No & Style No Combination. Please enter valid values.',
						    'AUDIT_SUBSCRIBED'                     => 'You have Subscribed the selected Audit successfully.',
						    'INVALID_AUDIT_GROUP'                  => 'The selected Auditor is not a Member of selected Audit Group.',
						    'INVALID_AUDIT_TIME'                   => 'Invalid Audit Start/End Time, Time is overlapping with another Audit Entry.',
						    'INVALID_AUDIT_END_TIME'               => 'Invalid Audit End Time, End Time should be greater than the Audit Start Time.',

						    'REPORT_TYPE_ADDED'                    => 'The specified Report Type has been Added into the System successfully.',
						    'REPORT_TYPE_EXISTS'                   => 'The specified Report Type / Code already exists in the System.',
						    'REPORT_TYPE_DELETED'                  => 'The selected Report Type has been Deleted from the System successfully.',

						    'DEFECT_TYPE_ADDED'                    => 'The specified Defect Type has been Added into the System successfully.',
						    'DEFECT_TYPE_EXISTS'                   => 'The specified Defect Type already exists in the System.',
						    'DEFECT_TYPE_DELETED'                  => 'The selected Defect Type has been Deleted from the System successfully.',

						    'LINE_TYPE_ADDED'                      => 'The specified Line Type has been Added into the System successfully.',
						    'LINE_TYPE_EXISTS'                     => 'The specified Line Type already exists in the System.',
						    'LINE_TYPE_DELETED'                    => 'The selected Line Type has been Deleted from the System successfully.',

						    'AUDIT_STAGE_ADDED'                    => 'The specified Audit Stage has been Added into the System successfully.',
						    'AUDIT_STAGE_EXISTS'                   => 'The specified Audit Stage already exists in the System.',
						    'AUDIT_STAGE_DELETED'                  => 'The selected Audit Stage has been Deleted from the System successfully.',
						    'AUDIT_STAGE_POSITION_UPDATED'         => 'The Position of selected Audit Stage has been Updated successfully.',

						    'DEFECT_CODE_ADDED'                    => 'The specified Defect Code has been Added into the System successfully.',
						    'DEFECT_CODE_EXISTS'                   => 'The specified Defect Code already exists in the System.',
						    'DEFECT_CODE_DELETED'                  => 'The selected Defect Code has been Deleted from the System successfully.',
						    'DEFECT_CODES_COPIED'                  => 'The selected Defect Codes have been Copied into the selected Brand successfully.',

						    'DEFECT_AREA_ADDED'                    => 'The specified Defect Area has been Added into the System successfully.',
						    'DEFECT_AREA_EXISTS'                   => 'The specified Defect Area already exists in the System.',
						    'DEFECT_AREA_DELETED'                  => 'The selected Defect Area has been Deleted from the System successfully.',

						    'QA_REPORT_SAVED'                      => 'The specified QA Report has been Saved successfully.',
						    'PACKING_IMAGES_SAVED'                 => 'The selected Packing Images have been Saved Successfully.',
						    'SPECS_SHEETS_SAVED'                   => 'The selected Specs Sheets / Lab Reports have been Saved Successfully.',
						    'QA_REPORT_NOTIFICATIONS_SENT'         => 'The selected QA Report Notifications have been Sent Successfully.',
						    'QA_IMAGE_DELETED'                     => 'The specified QA Image has been Deleted successfully.',
						    'QA_EMAIL_SENT'                        => 'The selected QA Report has been Emailed successfully.',
						    'QA_REPORT_DUPLICATE_SIZE_COLOR'       => 'Duplicate Size/Color selection in Sample Quantities.',

						    'QA_EMAIL_ADDED'                       => 'The specified Email has been Added into the System successfully.',
						    'QA_EMAIL_EXISTS'                      => 'The specified Email already exists in the System.',
						    'QA_EMAIL_DELETED'                     => 'The selected Email has been Deleted from the System successfully.',

						    'SIGNATURE_ADDED'                      => 'The specified Signature has been Added into the System successfully.',
						    'SIGNATURE_EXISTS'                     => 'The specified Signature already exists in the System.',
						    'SIGNATURE_UPDATED'                    => 'The selected Signature has been Updated successfully.',
						    'SIGNATURE_DELETED'                    => 'The selected Signature has been Deleted from the System successfully.',

						    'CSC_AUDIT_SAVED'                      => 'The specified CSC Audit Report has been Saved successfully.',
						    'CSC_AUDIT_UPDATED'                    => 'The selected CSC Audit Report has been Updated successfully.',
						    'CSC_AUDIT_DELETED'                    => 'The selected CSC Audit Report has been Deleted successfully.',

						    'INLINE_AUDIT_SAVED'                   => 'The specified Inline Audit Report has been Saved successfully.',
						    'INLINE_AUDIT_UPDATED'                 => 'The selected Inline Audit Report has been Updated successfully.',
						    'INLINE_AUDIT_DELETED'                 => 'The selected Inline Audit Report has been Deleted successfully.',

						    'SALES_SAMPLE_ADDED'                   => 'The specified Sales Sample has been Added into the System successfully.',
						    'SALES_SAMPLE_UPDATED'                 => 'The selected Sales Sample has been Updated successfully.',
						    'SALES_SAMPLE_DELETED'                 => 'The selected Sales Sample has been Deleted from the System successfully.',

						    'SAMPLING_CATEGORY_ADDED'              => 'The specified Sampling Category has been Added into the System successfully.',
						    'SAMPLING_CATEGORY_EXISTS'             => 'The specified Sampling Category already exists in the System.',
						    'SAMPLING_CATEGORY_DELETED'            => 'The selected Sampling Category has been Deleted from the System successfully.',

						    'SAMPLING_WASH_ADDED'                  => 'The specified Sampling Wash has been Added into the System successfully.',
						    'SAMPLING_WASH_EXISTS'                 => 'The specified Sampling Wash already exists in the System.',
						    'SAMPLING_WASH_DELETED'                => 'The selected Sampling Wash has been Deleted from the System successfully.',

						    'SAMPLING_SIZE_ADDED'                  => 'The specified Sampling Size has been Added into the System successfully.',
						    'SAMPLING_SIZE_EXISTS'                 => 'The specified Sampling Size already exists in the System.',
						    'SAMPLING_SIZE_DELETED'                => 'The selected Sampling Size has been Deleted from the System successfully.',
						    'SAMPLING_SIZE_ORDER_UPDATED'          => 'The Display Order of the selected Sampling Size has been Updated successfully.',

						    'SAMPLING_TYPE_ADDED'                  => 'The specified Sampling Type has been Added into the System successfully.',
						    'SAMPLING_TYPE_EXISTS'                 => 'The specified Sampling Type already exists in the System.',
						    'SAMPLING_TYPE_DELETED'                => 'The selected Sampling Type has been Deleted from the System successfully.',
						    'SAMPLING_TYPE_ORDER_UPDATED'          => 'The Display Order of the selected Sampling Type has been Updated successfully.',

						    'STYLE_SPECS_ADDED'                    => 'The specified Style Specs Entry has been Added into the System successfully.',
						    'STYLE_SPECS_UPDATED'                  => 'The specified Style Specs Entry has been Saved successfully.',
						    'STYLE_SPECS_DELETED'                  => 'The selected Style Specs has been Deleted successfully.',

						    'MERCHANDISING_ENTRY_EXISTS'           => 'The specified Merchandising Entry already exists in the System.',
						    'MERCHANDISING_ENTRY_ADDED'            => 'The specified Merchandising Entry has been Added into the System successfully.',
						    'MERCHANDISING_ENTRY_UPDATED'          => 'The selected Merchandising Entry has been Update successfully.',
						    'MERCHANDISING_ENTRY_DELETED'          => 'The selected Merchandising Entry has been Deleted from the System successfully.',
						    'INVALID_MERCHANDISING_ENTRY'          => 'Please first make the Merchandising Entry into the System.',

						    'MEASUREMENT_POINT_ADDED'              => 'The specified Measurement Point has been Added successfully.',
						    'MEASUREMENT_POINT_UPDATED'            => 'The selected Measurement Point has been Updated successfully.',
						    'MEASUREMENT_POINT_DELETED'            => 'The selected Measurement Point has been Deleted successfully.',

						    'MEASUREMENT_SPECS_ADDED'              => 'The specified Measurement Specs Entry has been Added into the System successfully.',
						    'MEASUREMENT_SPECS_DELETED'            => 'The specified Measurement Specs Entry has been Deleted from the System successfully.',
						    'MEASUREMENT_SPECS_UPDATED'            => 'The specified Measurement Specs Entry has been Saved successfully.',
						    'MEASUREMENT_SPECS_COPIED'             => 'The specified Measurement Specs have been copied successfully.',
						    'MEASUREMENT_SPECS_INVALID'            => 'The specified Measurement Specs cannot be Copied.',
						    'NO_MEASUREMENT_DETAILS_FILE'          => 'Please select a Measurement Details Excel File.',
						    'INVALID_MEASUREMENT_DETAILS_FILE'     => 'Please select a valid Measurement Details Excel File.',
						    'MEASUREMENT_DETAILS_IMPORTED'         => 'The selected Measurement Details Excel File has been Imported successfully.',
						    'SAMPLING_COMMENTS_SAVED'              => 'Your Comments have been Saved successfully.',
						    'MEASUREMENT_COMMENTS_DELETED'         => 'The Buyer Measurement Comments have been Deleted successfully.',
						    '360_IMAGE_DELETED'                    => 'The selected 360 View Image has been Deleted successfully.',
						    'NO_MEASUREMENT_SPECS'                 => 'Specs not entered for this Style. Please first enter the specs for this style.',

						    'PRODUCT_ADDED'                        => 'The specified Product Entry has been Added into the System successfully.',
						    'PRODUCT_UPDATED'                      => 'The selected Product Entry has been Updated successfully.',
						    'PRODUCT_DELETED'                      => 'The selected Product Entry has been Deleted from the System successfully.',
						    'PRODUCT_DUPLICATED'                   => 'The selected Product Entry has been Duplicated successfully.',
						    'PRODUCT_STATUS_UPDATED'               => 'The Status of the selected Product has been Updated successfully.',
						    'PRODUCT_PICTURE_DELETED'              => 'The selected Product Picture has been Deleted from the System successfully.',

						    'PRODUCT_COLOR_ADDED'                  => 'The specified Product Color has been Added into the System successfully.',
						    'PRODUCT_COLOR_DELETED'                => 'The selected Product Color has been Deleted from the System successfully.',
						    'PRODUCT_COLOR_EXISTS'                 => 'The specified Product Color already exists in the successfully.',

						    'FABRIC_ADDED'                         => 'The specified Fabric Entry has been Added into the System successfully.',
						    'FABRIC_UPDATED'                       => 'The selected Fabric Entry has been Updated successfully.',
						    'FABRIC_DELETED'                       => 'The selected Fabric Entry has been Deleted from the System successfully.',

						    'BRAND_RMS_ADDED'                      => 'The specified Brand RMS has been Added into the System successfully.',
						    'BRAND_RMS_DELETED'                    => 'The selected Brand RMS has been Deleted from the System successfully.',
						    'BRAND_RMS_EXISTS'                     => 'The specified Brand RMS already exists in the successfully.',

						    'BLOG_CATEGORY_ADDED'                  => 'The specified Blog Category has been Added into the System successfully.',
						    'BLOG_CATEGORY_EXISTS'                 => 'The specified Blog Category already exists in the System.',
						    'BLOG_CATEGORY_DELETED'                => 'The selected Blog Category has been Deleted from the System successfully.',

						    'BLOG_POST_ADDED'                      => 'The specified Blog Post has been Added into the System successfully.',
						    'BLOG_POST_UPDATED'                    => 'The selected Blog Post has been Updated successfully.',
						    'BLOG_POST_DELETED'                    => 'The selected Blog Post has been Deleted from the System successfully.',
						    'BLOG_POST_ORDER_UPDATED'              => 'The Display Order of the selected Blog Post has been Updated successfully.',

						    'BLOG_COMMENTS_SAVED'                  => 'Your Comments on the seleted Post has been Saved successfully.',
						    'BLOG_COMMENTS_DELETED'                => 'The selected Blog Post Comments have been Deleted successfully.',

						    'MESSAGE_SENT'                         => 'Your Message has been Sent to the Site Administrator successfully.',
							'MESSAGE_DELETED'                      => 'The selected Message has been Deleted from the System Successfully',
							'MESSAGE_REPLY_POSTED'                 => 'Your Reply to the selected Message has been Sent Successfully',

						    'FORECAST_ADDED'                       => 'The specified Forecast Entry has been Added into the System successfully.',
						    'FORECAST_EXISTS'                      => 'The specified Forecast Entry already exists in the System.',
						    'FORECAST_DELETED'                     => 'The selected Forecast Entry has been Deleted from the System successfully.',

						    'REVISED_FORECAST_ADDED'               => 'The specified Revised Forecast Entry has been Added into the System successfully.',
						    'REVISED_FORECAST_EXISTS'              => 'The specified Revised Forecast Entry already exists in the System.',
						    'REVISED_FORECAST_DELETED'             => 'The selected Revised Forecast Entry has been Deleted from the System successfully.',

						    'NO_VSR_FILE'                          => 'Please select a VSR File to Import into the System.',
							'INVALID_VSR_FILE'                     => 'Please select a valid VSR File to Import into the System.',
							'VSR_FILE_IMPORTED_WITH_ERRORS'        => 'The selected VSR File has been Imported into the System with some Erros.',
							'VSR_FILE_IMPORTED'                    => 'The selected VSR File has been Imported into the System successfully.',
							'VSR_ENTRY_SAVED'                      => 'The selected VSR Entry has been Saved successfully.',
							'VSR_ENTRY_DELETED'                    => 'The selected VSR Entry has been Deleted successfully.',

						    'POST_SAVED'                           => 'Your Message has been Posted successfully.',
						    'POST_DELETED'                         => 'The selected Post has been Deleted successfully.',
						    'POST_UPDATED'                         => 'The selected Post has been Updated successfully.',
						    'ATTACHMENT_DELETED'                   => 'The Attachment of selected Post has been Deleted successfully.',
						    'DOCUMENT_ADDED'                       => 'The specified Documents have been added to the selected Style.',

						    'DEPARTMENT_ADDED'                     => 'The specified Department has been Added into the System successfully.',
						    'DEPARTMENT_EXISTS'                    => 'The specified Department already exists in the System.',
						    'DEPARTMENT_DELETED'                   => 'The selected Department has been Deleted from the System successfully.',
						    'DEPARTMENT_POSITION_UPDATED'          => 'The Position of selected Department has been Updated successfully.',

						    'DESIGNATION_ADDED'                    => 'The specified Designation has been Added into the System successfully.',
						    'DESIGNATION_EXISTS'                   => 'The specified Designation already exists in the System.',
						    'DESIGNATION_DELETED'                  => 'The selected Designation has been Deleted from the System successfully.',

						    'HOLIDAY_ADDED'                        => 'The specified Holiday has been Added into the System successfully.',
						    'HOLIDAY_EXISTS'                       => 'The specified Holiday already exists in the System.',
						    'HOLIDAY_DELETED'                      => 'The selected Holiday has been Deleted from the System successfully.',

						    'VISIT_LOCATION_ADDED'                 => 'The specified Visit Location has been Added into the System successfully.',
						    'VISIT_LOCATION_EXISTS'                => 'The specified Visit Location already exists in the System.',
						    'VISIT_LOCATION_DELETED'               => 'The selected Visit Location has been Deleted from the System successfully.',

						    'LOCATIONS_DISTANCE_ADDED'             => 'The specified Locations Distance Record has been Added into the System successfully.',
						    'LOCATIONS_DISTANCE_EXISTS'            => 'The specified Locations Distance Record already exists in the System.',
						    'LOCATIONS_DISTANCE_DELETED'           => 'The selected Locations Distance Record has been Deleted from the System successfully.',

						    'ATTENDANCE_ADDED'                     => 'The specified Attendance Record has been Added into the System successfully.',
						    'ATTENDANCE_EXISTS'                    => 'The specified Attendance Record already exists in the System.',
						    'ATTENDANCE_DELETED'                   => 'The selected Attendance Record has been Deleted from the System successfully.',
						    'SMS_ATTENDANCE_DELETED'               => 'The selected SMS Attendance Record has been Deleted from the System successfully.',

						    'LEAVE_TYPE_ADDED'                     => 'The specified Leave Type has been Added into the System successfully.',
						    'LEAVE_TYPE_EXISTS'                    => 'The specified Leave Type already exists in the System.',
						    'LEAVE_TYPE_DELETED'                   => 'The selected Leave Type has been Deleted from the System successfully.',

						    'LEAVE_ADDED'                          => 'The specified Employee Leave Record has been Added into the System successfully.',
						    'LEAVE_EXISTS'                         => 'The specified Employee Leave Record already exists in the System.',
						    'LEAVE_DELETED'                        => 'The selected Employee Leave Record has been Deleted from the System successfully.',
						    'LEAVE_UPDATED'                        => 'The selected Employee Leave Record has been Updated successfully.',

						    'VISIT_ADDED'                          => 'The specified Employee Visit Record has been Added into the System successfully.',
						    'VISIT_DELETED'                        => 'The selected Employee Visit Record has been Deleted from the System successfully.',
						    'VISIT_UPDATED'                        => 'The selected Employee Visit Record has been Updated successfully.',

						    'SALARY_ADDED'                         => 'The specified Salary Record has been Added into the System successfully.',
						    'SALARY_DELETED'                       => 'The selected Salary Record has been Deleted from the System successfully.',
						    'SALARY_EXISTS'                        => 'The specified Salary Record already exists in the System.',

						    'CALENDAR_ENTRY_ADDED'                 => 'The specified Calendar Entry has been Added into the System successfully.',
						    'CALENDAR_ENTRY_EXISTS'                => 'The specified Calendar Entry already exists in the System.',
						    'CALENDAR_ENTRY_DELETED'               => 'The selected Calendar Entry has been Deleted from the System successfully.',

							'HR_MESSAGE_SENT'                      => 'Your Message has been Sent to the HR Manager successfully.',
							'HR_REPLY_SENT'                        => 'A Reply to the Message has been posted successfully.',

						    'NOTIFICATION_ADDED'                   => 'The specified Notification has been Added into the System successfully.',
						    'NOTIFICATION_DELETED'                 => 'The selected Notification has been Deleted from the System successfully.',
						    'NOTIFICATION_EXISTS'                  => 'The specified Notification already exists in the System.',
						    'NOTIFICATION_STATUS_UPDATED'          => 'The Status of the selected Notification has been changed successfully.',

						    'USER_ALBUM_ADDED'                     => 'The specified Photo Album has been Added into the System successfully.',
						    'USER_ALBUM_EXISTS'                    => 'The specified Photo Album already exists in the System.',
						    'USER_ALBUM_UPDATED'                   => 'The selected Photo Album has been Updated successfully.',
						    'USER_ALBUM_DELETED'                   => 'The selected Photo Album has been Deleted from the System successfully.',

						    'USER_PHOTOS_ADDED'                    => 'The selected Album Photos have been Saved into the System successfully.',
						    'USER_PHOTO_UPDATED'                   => 'The selected Album Photo has been Updated successfully.',
						    'USER_PHOTO_DELETED'                   => 'The selected Album Photo has been Deleted from the System successfully.',

						    'INVALID_SCHEDULE_TIME'                => 'The specified User Schedule is overlapping with another entry, please review the schedule.',
						    'INVALID_SCHEDULE_END_TIME'            => 'Invalid End Date/Time, End Date/Time should be greater than the Start Date/Time.',
						    'USER_SCHEDULE_ADDED'                  => 'The specified User Schedule has been Added into the System successfully.',
						    'USER_SCHEDULE_DELETED'                => 'The selected User Schedule has been Deleted from the System successfully.',

						    'SURVEY_ADDED'                         => 'The specified Survey has been Added into the System successfully.',
						    'SURVEY_DELETED'                       => 'The selected Survey has been Deleted from the System successfully.',
						    'SURVEY_EXISTS'                        => 'The specified Survey already exists in the System.',
						    'SURVEY_STATUS_UPDATED'                => 'The Status of the selected Survey has been changed successfully.',
						    'SURVEY_QUESTION_ADDED'                => 'The specified Survey Question has been Added into the System successfully.',
						    'SURVEY_QUESTION_DELETED'              => 'The selected Survey Question has been Deleted from the System successfully.',
						    'SURVEY_QUESTION_ORDER_UPDATED'        => 'The Order of selected Survey Question has been Updated successfully.',
						    'SURVEY_FEEDBACK_DELETED'              => 'The selected Survey Feedback has been Deleted from the System successfully.',
						    'SURVEY_DUPLICATED'                    => 'The selected Survey has been Duplicated successfully.',
						    'SURVEY_FEEDBACK_SAVED'                => 'Thankyou for sparing your time for submitting your Feedback.',
						    'SURVEY_FEEDBACK_SCORE_UPDATED'        => 'The Score of the selected Survey Feedback has been Updated successfully.',

							'BACKUP_TAKEN'                         => 'The Backup of the Database has been Taken Successfully',
							'BACKUP_DELETED'                       => 'The Selected Database Backup has been Deleted Successfully',
							'BACKUP_WRITE_ERROR'                   => 'Unable to Create the Database Backup File.',
							'BACKUP_RESTORED'                      => 'The Database has been Restored from the selected Backup File successfully',
							'BACKUP_READ_ERROR'                    => 'Unable to Read the Database Backup File.',

						    'YARN_ADDED'                           => 'The specified Yarn has been Added into the System successfully.',
						    'YARN_EXISTS'                          => 'The specified Yarn already exists in the System.',
						    'YARN_DELETED'                         => 'The selected Yarn has been Deleted from the System successfully.',

						    'PRINT_ADDED'                          => 'The specified Print has been Added into the System successfully.',
						    'PRINT_EXISTS'                         => 'The specified Print already exists in the System.',
						    'PRINT_DELETED'                        => 'The selected Print has been Deleted from the System successfully.',

						    'EMBROIDERY_ADDED'                     => 'The specified Embroidery has been Added into the System successfully.',
						    'EMBROIDERY_EXISTS'                    => 'The specified Embroidery already exists in the System.',
						    'EMBROIDERY_DELETED'                   => 'The selected Embroidery has been Deleted from the System successfully.',

						    'DESCRIPTION_ADDED'                    => 'The specified Description has been Added into the System successfully.',
						    'DESCRIPTION_EXISTS'                   => 'The specified Description already exists in the System.',
						    'DESCRIPTION_DELETED'                  => 'The selected Description has been Deleted from the System successfully.',

						    'GARMENT_STYLE_ADDED'                  => 'The specified Garment Style has been Added into the System successfully.',
						    'GARMENT_STYLE_EXISTS'                 => 'The specified Garment Style already exists in the System.',
						    'GARMENT_STYLE_DELETED'                => 'The selected Garment Style has been Deleted from the System successfully.',

						    'STYLE_COMMENT_ADDED'                  => 'The specified Style Comments have been Added into the System successfully.',
						    'STYLE_COMMENT_UPDATED'                => 'The selected Style Comments have been Updated successfully.',
						    'STYLE_COMMENT_DELETED'                => 'The selected Style Comments have been Deleted from the System successfully.',

						    'NO_OT_FILE'                           => 'Please select a OT File to Import into the System.',
							'INVALID_OT_FILE'                      => 'Please select a valid OT File to Import into the System.',
							'OT_FILE_IMPORTED'                     => 'The selected OT File has been Imported into the System successfully.',

						    'NO_ERA_FILE'                          => 'Please select a ERA File to Convert it into an Excel File.',

						    'AUDIT_SCHEDULE_DELETED'               => 'The selected Audit Schedule has been Deleted from the System successfully.',
						    'AUDIT_SCHEDULE_CONFIRMED'             => 'The specified Audit Schedule has been Confirmed successfully.',


						    'CRC_CATEGORY_ADDED'                   => 'The specified Category has been Added into the System successfully.',
						    'CRC_CATEGORY_EXISTS'                  => 'The specified Category already exists in the System.',
						    'CRC_CATEGORY_DELETED'                 => 'The selected Category has been Deleted from the System successfully.',
						    'CRC_CATEGORY_POSITION_UPDATED'        => 'The Position of selected Size has been Updated successfully.',

						    'CRC_WEIGHT_ADDED'                     => 'The specified Category/Brand Weight has been Added into the System successfully.',
						    'CRC_WEIGHT_EXISTS'                    => 'The specified Category/Brand Weight already exists in the System.',
						    'CRC_WEIGHT_DELETED'                   => 'The selected Category/Brand has been Deleted from the System successfully.',

						    'YARN_RATE_ADDED'                      => 'The specified Yarn Rates has been Added into the System successfully.',
						    'YARN_RATE_EXISTS'                     => 'The specified Yarn Day Rates already exists in the System.',
						    'YARN_RATE_DELETED'                    => 'The selected Yarn Day Rates have been Deleted from the System successfully.',

						    'COTTON_RATE_ADDED'                    => 'The specified Cotton Rates has been Added into the System successfully.',
						    'COTTON_RATE_EXISTS'                   => 'The specified Cotton Day Rates already exists in the System.',
						    'COTTON_RATE_DELETED'                  => 'The selected Cotton Day Rates have been Deleted from the System successfully.',

						    'PCC_COMMENTS_SAVED'                   => 'Your Comments on the seleted Product has been Saved successfully.',
						    'PCC_COMMENTS_DELETED'                 => 'The selected Product Comments have been Deleted successfully.',

						    'COLLECTION_ADDED'                     => 'The specified Collection has been Added into the System successfully.',
						    'COLLECTION_EXISTS'                    => 'The specified Collection already exists in the System.',
						    'COLLECTION_DELETED'                   => 'The selected Collection has been Deleted from the System successfully.',

						    'PCC_GALLERY_ADDED'                    => 'The specified Gallery has been Added into the System successfully.',
						    'PCC_GALLERY_EXISTS'                   => 'The specified Gallery already exists in the System.',
						    'PCC_GALLERY_DELETED'                  => 'The selected Gallery has been Deleted from the System successfully.',

						    'USER_GALLERY_ADDED'                   => 'The specified User Gallery has been Added into the System successfully.',
						    'USER_GALLERY_EXISTS'                  => 'The specified User Gallery already exists in the System.',
						    'USER_GALLERY_UPDATED'                 => 'The selected User Gallery has been Updated successfully.',
						    'USER_GALLERY_DELETED'                 => 'The selected User Gallery has been Deleted from the System successfully.',

						    'USER_GALLERY_PICS_ADDED'              => 'The selected User Pictures have been Saved into the System successfully.',
						    'USER_GALLERY_PIC_UPDATED'             => 'The selected User Picture has been Updated successfully.',
						    'USER_GALLERY_PIC_DELETED'             => 'The selected User Picture has been Deleted from the System successfully.',

						    'GF_SPECS_ADDED'                       => 'The specified GF Specs Entry has been Added into the System successfully.',
						    'GF_SPECS_UPDATED'                     => 'The specified GF Specs Entry has been Saved successfully.',
						    'GF_SPECS_DELETED'                     => 'The selected GF Specs has been Deleted successfully.',

						    'LOOM_TYPE_ADDED'                      => 'The specified Loom Type has been Added into the System successfully.',
						    'LOOM_TYPE_EXISTS'                     => 'The specified Loom Type already exists in the System.',
						    'LOOM_TYPE_DELETED'                    => 'The selected Loom Type have been Deleted from the System successfully.',

						    'LOOM_ADDED'                           => 'The specified Loom has been Added into the System successfully.',
						    'LOOM_EXISTS'                          => 'The specified Loom already exists in the System.',
						    'LOOM_DELETED'                         => 'The selected Loom have been Deleted from the System successfully.',

						    'LOOM_PLAN_ADDED'                      => 'The specified Loom Plan has been Added into the System successfully.',
						    'LOOM_PLAN_EXISTS'                     => 'The specified PO Loom Plan already exists in the System.',
						    'LOOM_PLAN_UPDATED'                    => 'The selected PO Loom Plan has been Updated successfully.',
						    'LOOM_PLAN_DELETED'                    => 'The selected Loom Plan have been Deleted from the System successfully.',

						    'INQUIRY_ADDED'                        => 'The specified Inquiry has been Added into the System successfully.',
						    'INQUIRY_EXISTS'                       => 'The specified Inquiry already exists in the System.',
						    'INQUIRY_DELETED'                      => 'The selected Inquiry have been Deleted from the System successfully.',

						    'FILES_UPLOADED'                       => 'The selected Files have been Uploaded successfully.',
						    'FILE_DELETED'                         => 'The selected File has been Deleteed successfully.',

						    'CRC_REPORT_ADDED'                     => 'The specified Report has been Added into the System successfully.',
						    'CRC_REPORT_EXISTS'                    => 'The specified Report already exists in the System.',
						    'CRC_REPORT_DELETED'                   => 'The selected Report has been Deleted from the System successfully.',

						    'PCC_COMPANY_ADDED'                    => 'The specified Company has been Added into the System successfully.',
						    'PCC_COMPANY_EXISTS'                   => 'The specified Company already exists in the System.',
						    'PCC_COMPANY_UPDATED'                  => 'The selected Company has been Updated successfully.',
						    'PCC_COMPANY_DELETED'                  => 'The selected Company has been Deleted from the System successfully.',

						    'PCC_PROJECT_ADDED'                    => 'The specified Project has been Added into the System successfully.',
						    'PCC_PROJECT_EXISTS'                   => 'The specified Project already exists in the System.',
						    'PCC_PROJECT_DELETED'                  => 'The selected Project has been Deleted from the System successfully.',
						    'PCC_PROJECT_STATUS_UPDATED'           => 'The Status of the selected Project has been Updated successfully.',

						    'PCC_BOARD_TYPE_ADDED'                 => 'The specified Board Type has been Added into the System successfully.',
						    'PCC_BOARD_TYPE_EXISTS'                => 'The specified Board Type already exists in the System.',
						    'PCC_BOARD_TYPE_UPDATED'               => 'The selected Board Type has been Updated successfully.',
						    'PCC_BOARD_TYPE_DELETED'               => 'The selected Board Type has been Deleted from the System successfully.',

						    'PCC_BOARD_ADDED'                      => 'The specified Board has been Added into the System successfully.',
						    'PCC_BOARD_EXISTS'                     => 'The specified Board already exists in the System.',
						    'PCC_BOARD_DELETED'                    => 'The selected Board has been Deleted from the System successfully.',
						    'PCC_BOARD_UPDATED'                    => 'The selected Board has been Updated successfully.',
						    'PCC_BOARD_STATUS_UPDATED'             => 'The Status of the selected Board has been Updated successfully.',

						    'PCC_MARKET_ADDED'                     => 'The specified Market has been Added into the System successfully.',
						    'PCC_MARKET_EXISTS'                    => 'The specified Market already exists in the System.',
						    'PCC_MARKET_DELETED'                   => 'The selected Market has been Deleted from the System successfully.',
						    'PCC_MARKET_UPDATED'                   => 'The selected Market has been Updated successfully.',
						    'PCC_MARKET_STATUS_UPDATED'            => 'The Status of the selected Market has been Updated successfully.',

						    'PCC_SEASON_ADDED'                     => 'The specified Season has been Added into the System successfully.',
						    'PCC_SEASON_EXISTS'                    => 'The specified Season already exists in the System.',
						    'PCC_SEASON_DELETED'                   => 'The selected Season has been Deleted from the System successfully.',
						    'PCC_SEASON_UPDATED'                   => 'The selected Season has been Updated successfully.',
						    'PCC_SEASON_STATUS_UPDATED'            => 'The Status of the selected Season has been Updated successfully.',

						    'PCC_PHOTO_ADDED'                      => 'The specified Photo has been Added into the System successfully.',
						    'PCC_PHOTO_DELETED'                    => 'The selected Photo has been Deleted from the System successfully.',
						    'PCC_PHOTO_UPDATED'                    => 'The selected Photo has been Updated successfully.',

						    'PCC_FABRIC_ADDED'                     => 'The specified Fabric has been Added into the System successfully.',
						    'PCC_FABRIC_EXISTS'                    => 'The specified Fabric already exists in the System.',
						    'PCC_FABRIC_DELETED'                   => 'The selected Fabric has been Deleted from the System successfully.',
						    'PCC_FABRIC_UPDATED'                   => 'The selected Fabric has been Updated successfully.',
						    'PCC_FABRIC_STATUS_UPDATED'            => 'The Status of the selected Fabric has been Updated successfully.',

						    'PCC_CATEGORY_ADDED'                   => 'The specified Category has been Added into the System successfully.',
						    'PCC_CATEGORY_EXISTS'                  => 'The specified Category already exists in the System.',
						    'PCC_CATEGORY_DELETED'                 => 'The selected Category has been Deleted from the System successfully.',
						    'PCC_CATEGORY_UPDATED'                 => 'The selected Category has been Updated successfully.',
						    'PCC_CATEGORY_STATUS_UPDATED'          => 'The Status of the selected Category has been Updated successfully.',

						    'PCC_PRODUCT_LEVEL_ADDED'              => 'The specified Product Level has been Added into the System successfully.',
						    'PCC_PRODUCT_LEVEL_EXISTS'             => 'The specified Product Level already exists in the System.',
						    'PCC_PRODUCT_LEVEL_DELETED'            => 'The selected Product Level has been Deleted from the System successfully.',
						    'PCC_PRODUCT_LEVEL_UPDATED'            => 'The selected Product Level has been Updated successfully.',
						    'PCC_PRODUCT_LEVEL_STATUS_UPDATED'     => 'The Status of the selected Product Level has been Updated successfully.',

						    'PCC_STYLE_ADDED'                      => 'The specified Style has been Added into the System successfully.',
						    'PCC_STYLE_EXISTS'                     => 'The specified Style already exists in the System.',
						    'PCC_STYLE_DELETED'                    => 'The selected Style has been Deleted from the System successfully.',
						    'PCC_STYLE_UPDATED'                    => 'The selected Style has been Updated successfully.',
						    'PCC_STYLE_STATUS_UPDATED'             => 'The Status of the selected Style has been Updated successfully.',
						    'PCC_STYLE_COMMENTS_ADDED'             => 'The specified Style Comments have been Added into the System successfully.',
						    'PCC_STYLE_COMMENTS_DELETED'           => 'The selected Style Comments have been Deleted from the System successfully.',
						    'PCC_STYLE_PHOTOS_ADDED'               => 'The selected Style Photos have been Saved into the System successfully.',
						    'PCC_STYLE_PHOTO_DELETED'              => 'The selected Style Photo has been Deleted from the System successfully.',

						    'PCC_SAMPLE_ADDED'                     => 'The specified Sample has been Added into the System successfully.',
						    'PCC_SAMPLE_EXISTS'                    => 'The specified Sample already exists in the System.',
						    'PCC_SAMPLE_DELETED'                   => 'The selected Sample has been Deleted from the System successfully.',
						    'PCC_SAMPLE_UPDATED'                   => 'The selected Sample has been Updated successfully.',
						    'PCC_SAMPLE_COMMENTS_ADDED'            => 'The specified Sample Comments have been Added into the System successfully.',
						    'PCC_SAMPLE_COMMENTS_DELETED'          => 'The selected Sample Comments have been Deleted from the System successfully.',
						    'PCC_SAMPLE_PHOTO_DELETED'             => 'The selected Sample Photo has been Deleted successfully.',

						    'PCC_COLOR_ADDED'                      => 'The specified Color has been Added into the System successfully.',
						    'PCC_COLOR_EXISTS'                     => 'The specified Color already exists in the System.',
						    'PCC_COLOR_DELETED'                    => 'The selected Color has been Deleted from the System successfully.',
						    'PCC_COLOR_UPDATED'                    => 'The selected Color has been Updated successfully.',

						    'QUALITY_POINT_ADDED'                  => 'The specified Quality Point has been Added into the System successfully.',
						    'QUALITY_POINT_EXISTS'                 => 'The specified Quality Point already exists in the System.',
						    'QUALITY_POINT_DELETED'                => 'The selected Quality Point has been Deleted from the System successfully.',
						    'QUALITY_POINT_UPDATED'                => 'The selected Quality Point has been Updated successfully.',
						    'QUALITY_POINT_POSITION_UPDATED'       => 'The Position of selected Quality Point has been Updated successfully.',

						    'QUALITY_AUDIT_ADDED'                  => 'The specified Quality Audit has been Added into the System successfully.',
						    'QUALITY_AUDIT_EXISTS'                 => 'The specified Quality Audit already exists in the System.',
						    'QUALITY_AUDIT_DELETED'                => 'The selected Quality Audit has been Deleted from the System successfully.',
						    'QUALITY_AUDIT_UPDATED'                => 'The selected Quality Audit has been Updated successfully.',

						    'COMPLIANCE_TYPE_ADDED'                => 'The Compliance Audit Type has been Added into the System successfully.',
						    'COMPLIANCE_TYPE_EXISTS'               => 'The Compliance Audit Type already exists in the System.',
						    'COMPLIANCE_TYPE_DELETED'              => 'The Compliance Audit Type has been Deleted from the System successfully.',
						    'COMPLIANCE_TYPE_UPDATED'              => 'The Compliance Audit Type has been Updated successfully.',

						    'COMPLIANCE_CATEGORY_ADDED'            => 'The Compliance Audit Category has been Added into the System successfully.',
						    'COMPLIANCE_CATEGORY_EXISTS'           => 'The Compliance Audit Category already exists in the System.',
						    'COMPLIANCE_CATEGORY_DELETED'          => 'The Compliance Audit Category has been Deleted from the System successfully.',
						    'COMPLIANCE_CATEGORY_UPDATED'          => 'The Compliance Audit Category has been Updated successfully.',
						    'COMPLIANCE_CATEGORY_POSITION_UPDATED' => 'The Compliance Audit Category Position has been Updated successfully.',

						    'COMPLIANCE_QUESTION_ADDED'            => 'The Compliance Audit Question has been Added into the System successfully.',
						    'COMPLIANCE_QUESTION_EXISTS'           => 'The Compliance Audit Question already exists in the System.',
						    'COMPLIANCE_QUESTION_DELETED'          => 'The Compliance Audit Question has been Deleted from the System successfully.',
						    'COMPLIANCE_QUESTION_UPDATED'          => 'The Compliance Audit Question has been Updated successfully.',

						    'COMPLIANCE_AUDIT_ADDED'               => 'The specified Compliance Audit has been Added into the System successfully.',
						    'COMPLIANCE_AUDIT_EXISTS'              => 'The specified Compliance Audit already exists in the System.',
						    'COMPLIANCE_AUDIT_DELETED'             => 'The selected Compliance Audit has been Deleted from the System successfully.',
						    'COMPLIANCE_PICTURE_DELETED'           => 'The selected Compliance Audit Picture has been Deleted successfully.',
						    'COMPLIANCE_AUDIT_UPDATED'             => 'The selected Compliance Audit has been Updated successfully.',

						    'PRODUCTION_CATEGORY_ADDED'            => 'The Production Audit Category has been Added into the System successfully.',
						    'PRODUCTION_CATEGORY_EXISTS'           => 'The Production Audit Category already exists in the System.',
						    'PRODUCTION_CATEGORY_DELETED'          => 'The Production Audit Category has been Deleted from the System successfully.',
						    'PRODUCTION_CATEGORY_UPDATED'          => 'The Production Audit Category has been Updated successfully.',
						    'PRODUCTION_CATEGORY_POSITION_UPDATED' => 'The Production Audit Category Position has been Updated successfully.',

						    'PRODUCTION_QUESTION_ADDED'            => 'The Production Audit Question has been Added into the System successfully.',
						    'PRODUCTION_QUESTION_EXISTS'           => 'The Production Audit Question already exists in the System.',
						    'PRODUCTION_QUESTION_DELETED'          => 'The Production Audit Question has been Deleted from the System successfully.',
						    'PRODUCTION_QUESTION_UPDATED'          => 'The Production Audit Question has been Updated successfully.',

						    'PRODUCTION_AUDIT_ADDED'               => 'The specified Production Audit has been Added into the System successfully.',
						    'PRODUCTION_AUDIT_EXISTS'              => 'The specified Production Audit already exists in the System.',
						    'PRODUCTION_AUDIT_DELETED'             => 'The selected Production Audit has been Deleted from the System successfully.',
						    'PRODUCTION_AUDIT_UPDATED'             => 'The selected Production Audit has been Updated successfully.',

						    'WORK_ORDER_SAVED'                     => 'The specified Work Order has been Saved into the System successfully.',
						    'WORK_ORDER_UPDATED'                   => 'The specified Work Order has been Updated into the System successfully.',
						    'WORK_ORDER_DELETED'                   => 'The selected Work Order has been Deleted from the System successfully.',
						    'WORK_ORDER_EXISTS'                    => 'The specified (Vendor/Work Order) already exists in the System.',

						    'MDL_PRODUCT_ADDED'                    => 'The specified Product has been Added into the System successfully.',
						    'MDL_PRODUCT_EXISTS'                   => 'A Product with same Style already exists in the System.',
						    'MDL_PRODUCT_DELETED'                  => 'The selected Product has been Deleted from the System successfully.',
						    'MDL_PRODUCT_UPDATED'                  => 'The selected Product has been Updated successfully.',

						    'MDL_FLIPBOOK_ADDED'                   => 'The specified Flipbook has been Added into the System successfully.',
						    'MDL_FLIPBOOK_EXISTS'                  => 'A Flipbook with same Title already exists in the System.',
						    'MDL_FLIPBOOK_DELETED'                 => 'The selected Flipbook has been Deleted from the System successfully.',
						    'MDL_FLIPBOOK_UPDATED'                 => 'The selected Flipbook has been Updated successfully.',

						    'COURIER_ITEM_ADDED'                   => 'The specified Courier Item has been Added into the System successfully.',
						    'COURIER_ITEM_EXISTS'                  => 'A Courier Item with same Airway Bill Number already exists in the System.',
						    'COURIER_ITEM_DELETED'                 => 'The selected Courier Item has been Deleted from the System successfully.',
						    'COURIER_ITEM_UPDATED'                 => 'The selected Courier Item has been Updated successfully.',

						    'PRODUCTION_STAGE_ADDED'               => 'The Production Stage has been Added into the System successfully.',
						    'PRODUCTION_STAGE_EXISTS'              => 'The Production Stage already exists in the System.',
						    'PRODUCTION_STAGE_DELETED'             => 'The Production Stage has been Deleted from the System successfully.',
						    'PRODUCTION_STAGE_UPDATED'             => 'The Production Stage has been Updated successfully.',
						    'PRODUCTION_STAGE_POSITION_UPDATED'    => 'The Production Stage Position has been Updated successfully.',

						    'CERTIFICATION_ADDED'                  => 'The Certification has been Added into the System successfully.',
						    'CERTIFICATION_EXISTS'                 => 'The Certification already exists in the System.',
						    'CERTIFICATION_DELETED'                => 'The Certification has been Deleted from the System successfully.',
						    'CERTIFICATION_UPDATED'                => 'The Certification has been Updated successfully.',
						    'CERTIFICATION_POSITION_UPDATED'       => 'The Certification Position has been Updated successfully.',

						    'VENDOR_CERTIFICATION_ADDED'           => 'The sepecified Vendor Certification have been Saved into the System successfully.',
						    'VENDOR_CERTIFICATION_UPDATED'         => 'The selected Vendor Certification has been Updated successfully.',
						    'VENDOR_CERTIFICATION_DELETED'         => 'The selected Vendor Certification has been Deleted from the System successfully.',

						    'SAFETY_CATEGORY_ADDED'                => 'The Safety Audit Category has been Added into the System successfully.',
						    'SAFETY_CATEGORY_EXISTS'               => 'The Safety Audit Category already exists in the System.',
						    'SAFETY_CATEGORY_DELETED'              => 'The Safety Audit Category has been Deleted from the System successfully.',
						    'SAFETY_CATEGORY_UPDATED'              => 'The Safety Audit Category has been Updated successfully.',
						    'SAFETY_CATEGORY_POSITION_UPDATED'     => 'The Safety Audit Category Position has been Updated successfully.',

						    'SAFETY_QUESTION_ADDED'                => 'The Safety Audit Question has been Added into the System successfully.',
						    'SAFETY_QUESTION_EXISTS'               => 'The Safety Audit Question already exists in the System.',
						    'SAFETY_QUESTION_DELETED'              => 'The Safety Audit Question has been Deleted from the System successfully.',
						    'SAFETY_QUESTION_UPDATED'              => 'The Safety Audit Question has been Updated successfully.',

						    'SAFETY_AUDIT_ADDED'                   => 'The specified Safety Audit has been Added into the System successfully.',
						    'SAFETY_AUDIT_EXISTS'                  => 'The specified Safety Audit already exists in the System.',
						    'SAFETY_AUDIT_DELETED'                 => 'The selected Safety Audit has been Deleted from the System successfully.',
						    'SAFETY_PICTURE_DELETED'               => 'The selected Safety Audit Picture has been Deleted successfully.',
						    'SAFETY_AUDIT_UPDATED'                 => 'The selected Safety Audit has been Updated successfully.',

						    'AUDIT_PICTURE_ADDED'                  => 'The selected Audit Picture has been Added into the System successfully.',
						    'AUDIT_PICTURE_DELETED'                => 'The selected Audit Picture has been Deleted from the System successfully.',
						    'AUDIT_PICTURE_UPDATED'                => 'The selected Audit Picture has been Updated successfully.',

                            'TNC_SECTION_ADDED'                    => 'The specified Section has been Added into the System successfully.',
                            'TNC_SECTION_EXISTS'                   => 'The specified Section already exists in the System.',
                            'TNC_SECTION_POSITION_UPDATED'         => 'The Position of selected Size has been Updated successfully.',
                            'TNC_SECTION_DELETED'                  => 'The selected Section has been Deleted from the System successfully.',
                    
                            'CHEMICAL_TYPE_ADDED'                  => 'The Chemical Type has been Added into the System successfully.',
                            'CHEMICAL_TYPE_EXISTS'                 => 'The specified Chemical Type already exists in the System.',
                            'CHEMICAL_TYPE_DELETED'                => 'The selected Chemical Type has been Deleted from the System successfully.',
                    
                            'CHEMICAL_COMPOUND_ADDED'              => 'The Chemical Compound has been Added into the System successfully.',
                            'CHEMICAL_COMPOUND_EXISTS'             => 'The specified Chemical Compound already exists in the System.',
                            'CHEMICAL_COMPOUND_DELETED'            => 'The selected Chemical Compound has been Deleted from the System successfully.',
							
                            'CHEMICAL_LOCATION_TYPE_ADDED'         => 'The Chemical Location Type has been Added into the System successfully.',
                            'CHEMICAL_LOCATION_TYPE_EXISTS'        => 'The specified Chemical Location Type already exists in the System.',
                            'CHEMICAL_LOCATION_TYPE_DELETED'       => 'The selected Chemical Location Type has been Deleted from the System successfully.',
                    
                            'CHEMICAL_LOCATION_ADDED'              => 'The Chemical Location has been Added into the System successfully.',
                            'CHEMICAL_LOCATION_EXISTS'             => 'The specified Chemical Location already exists in the System.',
                            'CHEMICAL_LOCATION_DELETED'            => 'The selected Chemical Location has been Deleted from the System successfully.',
                            
                            'CHEMICAL_INVENTORY_ADDED'             => 'The Chemical Inventory has been Added into the System successfully.',
                            'CHEMICAL_INVENTORY_EXISTS'            => 'The specified Chemical Inventory already exists in the System.',
                            'CHEMICAL_INVENTORY_DELETED'           => 'The selected Chemical Inventory has been Deleted from the System successfully.',

                            'TNC_CATEGORY_ADDED'                   => 'The specified Category has been Added into the System successfully.',
                            'TNC_CATEGORY_EXISTS'                  => 'The specified Category already exists in the System.',
                            'TNC_CATEGORY_POSITION_UPDATED'        => 'The Position of selected Category has been Updated successfully.',
                            'TNC_CATEGORY_DELETED'                 => 'The selected Category has been Deleted from the System successfully.',

							'TNC_POINT_ADDED'                      => 'The specified T&C Point has been Added into the System successfully.',
                            'TNC_POINT_EXISTS'                     => 'The specified T&C Point already exists in the System.',
                            'TNC_POINT_POSITION_UPDATED'           => 'The Position of selected Point has been Updated successfully.',
                            'TNC_POINT_DELETED'                    => 'The selected T&C Point has been Deleted from the System successfully.',

							'TNC_AUDIT_ADDED'                      => 'The specified T&C Audit has been Added into the System successfully.',
							'TNC_AUDIT_EXISTS'                     => 'The specified T&C Audit already exists in the System.',
							'TNC_AUDIT_UPDATED'    		           => 'The selected T&c Audit has been Updated successfully.',
							'TNC_AUDIT_DELETED'                    => 'The selected T&C Audit has been Deleted from the System successfully.',
							'TNC_AUDIT_IMAGE_DELETED' 			   => 'The selected Audit Image has been deleted successfully.',
							
						    'ACTIVITY_ADDED'                       => 'The specified Activity has been Added into the System successfully.',
						    'ACTIVITY_EXISTS'                      => 'The specified Activity already exists in the System.',
						    'ACTIVITY_DELETED'                     => 'The selected Activity has been Deleted from the System successfully.',
							
							'USER_ACTIVITY_DELETED'                => 'The selected User Activity has been Deleted from the System successfully.',
							'USER_ACTIVITY_ADDED'                  => 'The specified User Activity has been Added into the System successfully.',
							
                            'PCC_SAMPLE_TYPE_ADDED'                => 'The Sample Type has been Added into the System successfully.',
                            'PCC_SAMPLE_TYPE_EXISTS'               => 'The specified Sample Type already exists in the System.',
                            'PCC_SAMPLE_TYPE_DELETED'              => 'The selected Sample Type has been Deleted from the System successfully.',
							
                            'PCC_EMBELLISHMENT_ADDED'              => 'The Embellishment has been Added into the System successfully.',
                            'PCC_EMBELLISHMENT_EXISTS'             => 'The specified Embellishment already exists in the System.',
                            'PCC_EMBELLISHMENT_DELETED'            => 'The selected Embellishment has been Deleted from the System successfully.',
							
                            'PCC_CONSTRUCTION_ADDED'               => 'The Construction has been Added into the System successfully.',
                            'PCC_CONSTRUCTION_EXISTS'              => 'The specified Construction already exists in the System.',
                            'PCC_CONSTRUCTION_DELETED'             => 'The selected Construction has been Deleted from the System successfully.',
							
                            'PCC_SOURCE_ADDED'                     => 'The Merchandising Source has been Added into the System successfully.',
                            'PCC_SOURCE_EXISTS'                    => 'The specified Merchandising Source already exists in the System.',
                            'PCC_SOURCE_DELETED'                   => 'The selected Merchandising Source has been Deleted from the System successfully.',
							
                            'PCC_DYESTUFF_ADDED'                   => 'The Dyestuff has been Added into the System successfully.',
                            'PCC_DYESTUFF_EXISTS'                  => 'The specified Dyestuff already exists in the System.',
                            'PCC_DYESTUFF_DELETED'                 => 'The selected Dyestuff has been Deleted from the System successfully.',
							
                            'PCC_TRIMS_ADDED'                      => 'The Trims has been Added into the System successfully.',
                            'PCC_TRIMS_EXISTS'                     => 'The specified Trims already exists in the System.',
                            'PCC_TRIMS_DELETED'                    => 'The selected Trims has been Deleted from the System successfully.',
							
						    'PCC_YARN_FIBER_ADDED'                 => 'The specified Yarn/Fiber has been Added into the System successfully.',
						    'PCC_YARN_FIBER_EXISTS'                => 'The specified Yarn/Fiber already exists in the System.',
						    'PCC_YARN_FIBER_DELETED'               => 'The selected Yarn/Fiber has been Deleted from the System successfully.',
						  );


		$sMsgType = "Alert";

		if (@strstr($_SESSION['Flag'], 'EXISTS') || @strstr($_SESSION['Flag'], 'ERRORS') || @strstr($_SESSION['Flag'], 'INVALID'))
			$sMsgType = "Info";

		else if (@strstr($_SESSION['Flag'], 'ERROR'))
			$sMsgType = "Error";
?>
<!--  Message Alert Section Starts Here  -->
  <div id="SysMsg">
    <div id="<?= $sMsgType ?>">
      <img src="images/icons/<?= @strtolower($sMsgType) ?>.gif" width="32" height="32" hspace="5" title="<?= $sMsgType ?>" alt="<?= $sMsgType ?>" align="absmiddle" /> <?= $sMessages[$_SESSION['Flag']] ?>
    </div>
  </div>

  <script type="text/javascript">
  <!--

  	setTimeout( function( ) { new Effect.SlideUp("SysMsg"); }, 10000);

  -->
  </script>
<!--  Message Alert Section Ends Here  -->

<?
	}

	$_SESSION['Flag'] = "";
?>