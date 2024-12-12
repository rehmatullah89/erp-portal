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

	$sHomeModule      = array("index.php", "create-account.php", "change-password.php", "my-account.php", "sms.php", "blog.php", "contact-us.php", "privacy-policy.php", "terms-and-conditions.php", "404.php", "signatures.php", "attendance.php", "emails.php", "calendar.php", "notifications.php", "survey.php", "delay-reason.php", "po-status.php", "donations.php", "sampling-comments.php", "trends.php");
	$sDataEntryModule = array("index.php", "countries.php", "customers.php", "cities.php", "ports.php", "country-blocks.php", "categories.php", "vendors.php", "edit-vendor.php", "suppliers.php", "brands.php", "edit-brand.php", "seasons.php", "destinations.php", "sizes.php", "styles.php", "edit-style.php", "purchase-orders.php", "add-purchase-order.php", "edit-purchase-order.php", "delete-purchase-order.php", "request-etd-revision.php", "po-commission.php", "forecasts.php", "revised-forecasts.php", "vendor-profiles.php", "vendor-pictures.php", "vendor-albums.php", "fabric-library.php", "fabric-categories.php", "library.php", "blog.php", "add-blog-post.php", "edit-blog-post.php", "blog-categories.php", "etd-revision-requests.php", "edit-etd-revision-request.php", "etd-revision-reasons.php", "brand-offices.php", "style-categories.php", "resize-sketch-file.php", "videos.php","products.php", "edit-product.php", "flipbooks.php", "edit-flipbook.php", "couriers.php", "edit-courier.php", "production-stages.php", "escrow-orders.php", "programs.php", "etd-managers.php", "yarn-rates.php", "cotton-rates.php", "posdd.php", "pos-import.php", "import-pos-csv.php");
	$sPccModule       = array("index.php", "explorer.php", "product.php", "yarn.php", "products.php", "add-product.php", "edit-product.php", "garments.php", "print.php", "embroidery.php", "descriptions.php", "collections.php", "galleries.php", "user-galleries.php", "user-pictures.php", "companies.php", "projects.php", "board-types.php", "boards.php", "markets.php", "seasons.php", "photos.php", "fabrics.php", "categories.php", "product-levels.php", "styles.php", "add-style.php", "edit-style.php", "style-comments.php", "style-photos.php", "samples.php", "add-sample.php", "edit-sample.php", "sample-comments.php", "colors.php", "sample-types.php", "embellishments.php", "constructions.php", "sources.php", "dyestuff.php", "trims.php", "yarn-fibers.php");
	$sVsnModule       = array("index.php", "vsn.php", "otp.php", "reports-comparison.php", "seasons-otp.php");
	$sVsrModule       = array("index.php", "vsr.php", "vsr-details.php", "vsr-data.php", "import-vsr.php", "edit-vsr-po.php", "deviation.php", "etd-revisions.php", "work-orders.php", "add-work-order.php", "edit-work-order.php", "work-order-details.php", "new-vsr.php", "fetch-styles.php");
	$sQsnModule       = array("index.php", "qsn.php");
	$sBtaModule       = array("index.php", "bta.php");
	$sCrcModule       = array("index.php", "ot-data.php", "ot-analysis.php", "era-converter.php", "categories.php", "weights.php", "reports.php", "dashboard.php", "safety-categories.php", "safety-questions.php", "safety-audits.php", "edit-safety-audit.php", "quality-audits.php", "edit-quality-audit.php", "quality-points.php", "compliance-types.php", "compliance-categories.php", "compliance-questions.php", "compliance-audits.php", "edit-compliance-audit.php", "certifications.php", "vendor-certifications.php", "production-categories.php", "production-questions.php", "production-audits.php", "edit-production-audit.php", "audit-pictures.php", "tnc-categories.php", "tnc-sections.php", "tnc-points.php", "tnc-audits.php", "edit-tnc-audit.php", "crc-audits.php", "edit-crc-audit.php", "edit-crc-audit-cap.php", "crc-audit-images.php", "tnc-audit-images.php", "tnc-dashboard.php", "tnc-audit-caps.php","chemical-types.php", "chemical-location-types.php", "chemical-locations.php", "chemical-compounds.php", "chemicals-inventory.php", "vmap.php", "tnc-schedules.php", "edit-tnc-schedule.php", "departments.php");
	$sShippingModule  = array("index.php", "terms-of-delivery.php", "pre-shipment-advice.php", "edit-pre-shipment-detail.php", "post-shipment-advice.php", "edit-post-shipment-detail.php", "edit-pre-shipment-detail-entry.php");
	$sQuondaModule    = array("index.php", "aql.php", "bookings.php", "qa-checklist.php", "defects-nature.php", "quonda-graphs.php", "statements.php", "questions.php", "sections.php", "packaging-defects.php", "audit-types.php", "auditors-correlation.php", "auditors-productivity.php", "lines.php", "line-types.php", "audit-codes.php", "qa-sections.php", "qa-reports.php", "edit-qa-report.php", "qa-report-images.php", "quonda-reports.php", "reports.php", "defect-types.php", "defect-codes.php", "audit-stages.php", "defect-areas.php", "defect-codes-gf.php", "defects-catalogue.php", "dashboard.php", "auditor-groups.php", "csc-audits.php", "edit-csc-audit.php", "reports-comparison.php", "emails.php", "send-qa-report.php", "auditors-swarm.php", "schedule.php", "audit-schedules.php", "qa-reviews.php", "vendor-reports.php", "qa-reports-analysis.php", "signatures.php", "edit-signature.php", "qa-commission.php", "floors.php", "audit-progress.php", "po-progress.php", "guidelines.php", "add-guideline.php", "edit-guideline.php", "sisense.php");
	$sReportsModule   = array("index.php", "shipping-report-dkc.php","oql-report.php","triumph-report.php","oql-tracking-report.php","shipment-summary.php", "quality-report.php", "commission-report.php", "invoice-report.php", "shipping-report.php", "ss-commission-report.php", "measurements-report.php", "inspection-certificate.php", "attendance-report.php", "portal-usuage-report.php", "current-standing.php", "forecast-report.php", "vsr-report.php", "kpis-report.php", "fob-value.php", "invoice-generator.php", "mgf-report.php", "levis-report.php");
	$sSamplingModule  = array("index.php", "dashboard.php", "emails.php", "send-merchandising-report.php", "dashboard-styles.php", "sales-samples.php", "add-sales-sample.php", "edit-sales-sample.php", "ss-commission.php", "types.php", "sizes.php", "categories.php", "washes.php", "measurement-points.php", "merchandisings.php", "add-merchandising.php", "edit-merchandising.php", "measurement-specs.php", "add-measurement-specs.php", "edit-measurement-specs.php", "sampling-reports.php", "reports.php", "defect-types.php", "defect-codes.php", "lab-dips.php", "lab-dip-colors.php", "fabric.php", "brand-rms.php", "defect-areas.php", "measurement-images.php", "style-specs.php", "edit-style-specs.php", "select-style-specs.php", "styles.php", "style-details.php", "style-comments.php", "add-style-comment.php", "edit-style-comment.php", "inline-audits.php", "edit-inline-audit.php", "inline-audit-images.php", "360-images.php", "360-view.php", "style-graph.php", "style-audits.php", "360.php");
	$sHrModule        = array("index.php", "employees.php", "edit-employee-evolutionary-profile.php", "edit-employee.php", "attendance.php", "leaves.php", "holidays.php", "departments.php", "designations.php", "visit-locations.php", "location-distances.php", "leave-types.php", "visits.php", "hrn.php", "view-employee-stats.php", "salaries.php", "board.php", "messages.php", "offices.php", "sms-attendance.php", "calendar.php", "brand-placements.php", "surveys.php", "survey-manager.php", "survey-feedback.php", "activities.php", "user-activities.php", "profile-assessment-areas.php");
	$sLibsModule      = array("index.php", "vendor-profiles.php", "vendor-profile.php", "fabric-library.php", "library.php", "cotton-futures.php", "videos.php", "extensions.php");
	$sAdminModule     = array("index.php", "users.php", "user-types.php", "clients.php", "edit-user.php", "backup-restore.php", "web-messages.php", "reply-web-message.php");
	$sYarnModule      = array("index.php", "trends.php", "specs.php", "edit-specs.php", "loom-types.php", "looms.php", "inquiries.php", "loom-plan.php", "edit-loom-plan.php");
	$sDropboxModule   = array("index.php", "dropbox.php");
	$sQmipModule      = array("index.php", "operators.php", "operations.php", "machines.php", "machine-types.php", "dashboard.php", "qmip-graphs.php", "qmip-graphs2.php", "qa-reports.php", "edit-qa-report.php", "qa-report-images.php", "send-qa-report.php", "brands.php", "auditors-performance.php", "qmip-daily-report.php", "audit-progress.php", "po-progress.php");


	$sDataEntryRights = array( );

	$sSQL = "SELECT `add`, (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='Data Entry') ORDER BY _Section";
	$objDb->query($sSQL);

	$iDataEntryCount = $objDb->getCount( );

	for ($i = 0; $i < $iDataEntryCount; $i ++)
	{
		$sDataEntryRights[$i]['add']     = $objDb->getField($i, 'add');
		$sDataEntryRights[$i]['section'] = $objDb->getField($i, '_Section');
	}


	$sQmipRights = array( );

	$sSQL = "SELECT `add`, (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='QMIP') ORDER BY _Section";
	$objDb->query($sSQL);

	$iQmipCount = $objDb->getCount( );

	for ($i = 0; $i < $iQmipCount; $i ++)
	{
		$sQmipRights[$i]['add']     = $objDb->getField($i, 'add');
		$sQmipRights[$i]['section'] = $objDb->getField($i, '_Section');
	}


	$sPccRights = array( );

	$sSQL = "SELECT `add`, (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='PCC') ORDER BY _Section";
	$objDb->query($sSQL);

	$iPccCount = $objDb->getCount( );

	for ($i = 0; $i < $iPccCount; $i ++)
	{
		$sPccRights[$i]['add']     = $objDb->getField($i, 'add');
		$sPccRights[$i]['section'] = $objDb->getField($i, '_Section');
	}


	$sVsnRights = array( );

	$sSQL = "SELECT (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='VSN') ORDER BY _Section";
	$objDb->query($sSQL);

	$iVsnCount = $objDb->getCount( );

	for ($i = 0; $i < $iVsnCount; $i ++)
		$sVsnRights[$i]['section'] = $objDb->getField($i, '_Section');


	$sVsrRights = array( );

	$sSQL = "SELECT `add`, (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='VSR') ORDER BY _Section";
	$objDb->query($sSQL);

	$iVsrCount = $objDb->getCount( );

	for ($i = 0; $i < $iVsrCount; $i ++)
	{
		$sVsrRights[$i]['add']     = $objDb->getField($i, 'add');
		$sVsrRights[$i]['section'] = $objDb->getField($i, '_Section');
	}


	$sQsnRights = array( );

	$sSQL = "SELECT (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='QSN') ORDER BY _Section";
	$objDb->query($sSQL);

	$iQsnCount = $objDb->getCount( );

	for ($i = 0; $i < $iQsnCount; $i ++)
		$sQsnRights[$i]['section'] = $objDb->getField($i, '_Section');


	$sBtaRights = array( );

	$sSQL = "SELECT (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='BTA') ORDER BY _Section";
	$objDb->query($sSQL);

	$iBtaCount = $objDb->getCount( );

	for ($i = 0; $i < $iBtaCount; $i ++)
		$sBtaRights[$i]['section'] = $objDb->getField($i, '_Section');



	$sCrcRights = array( );

	$sSQL = "SELECT (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='CRC') ORDER BY _Section";
	$objDb->query($sSQL);

	$iCrcCount = $objDb->getCount( );

	for ($i = 0; $i < $iCrcCount; $i ++)
		$sCrcRights[$i]['section'] = $objDb->getField($i, '_Section');


	$sShippingRights = array( );

	$sSQL = "SELECT (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='Shipping') ORDER BY _Section";
	$objDb->query($sSQL);

	$iShippingCount = $objDb->getCount( );

	for ($i = 0; $i < $iShippingCount; $i ++)
		$sShippingRights[$i]['section'] = $objDb->getField($i, '_Section');

	$sQuondaRights = array( );

	$sSQL = "SELECT `add`, (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='Quonda') ORDER BY _Section";
	$objDb->query($sSQL);

	$iQuondaCount = $objDb->getCount( );

	for ($i = 0; $i < $iQuondaCount; $i ++)
        {
            $sQuondaRights[$i]['add']     = $objDb->getField($i, 'add');
            $sQuondaRights[$i]['section'] = $objDb->getField($i, '_Section');
        }

	$sReportsRights = array( );

	$sSQL = "SELECT (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='Reports') ORDER BY _Section";
	$objDb->query($sSQL);

	$iReportsCount = $objDb->getCount( );

	for ($i = 0; $i < $iReportsCount; $i ++)
		$sReportsRights[$i]['section'] = $objDb->getField($i, '_Section');


	$sSamplingRights = array( );

	$sSQL = "SELECT `add`, (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='Sampling') ORDER BY _Section";
	$objDb->query($sSQL);

	$iSamplingCount = $objDb->getCount( );

	for ($i = 0; $i < $iSamplingCount; $i ++)
	{
		$sSamplingRights[$i]['add']     = $objDb->getField($i, 'add');
		$sSamplingRights[$i]['section'] = $objDb->getField($i, '_Section');
	}


	$sHrRights = array( );

	$sSQL = "SELECT `add`, (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='HR') ORDER BY _Section";
	$objDb->query($sSQL);

	$iHrCount = $objDb->getCount( );

	for ($i = 0; $i < $iHrCount; $i ++)
	{
		$sHrRights[$i]['add']     = $objDb->getField($i, 'add');
		$sHrRights[$i]['section'] = $objDb->getField($i, '_Section');
	}


	$sYarnRights = array( );

	$sSQL = "SELECT `add`, (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='Yarn') ORDER BY _Section";
	$objDb->query($sSQL);

	$iYarnCount = $objDb->getCount( );

	for ($i = 0; $i < $iYarnCount; $i ++)
	{
		$sYarnRights[$i]['add']     = $objDb->getField($i, 'add');
		$sYarnRights[$i]['section'] = $objDb->getField($i, '_Section');
	}


	$sDropboxRights = array( );

	$sSQL = "SELECT (SELECT section FROM tbl_pages WHERE id=tbl_user_rights.page_id) AS _Section FROM tbl_user_rights WHERE `view`='Y' AND user_id='{$_SESSION['UserId']}' AND page_id IN (SELECT id FROM tbl_pages WHERE module='Dropbox') ORDER BY _Section";
	$objDb->query($sSQL);

	$iDropboxCount = $objDb->getCount( );

	for ($i = 0; $i < $iDropboxCount; $i ++)
		$sDropboxRights[$i]['section'] = $objDb->getField($i, '_Section');
?>
	    <div id="Navigation">
		  <div id="MainNav">
		    <a href="./"<?= ((@in_array($sPage, $sHomeModule) && @in_array($sCurDir, array("Portal", "portal",  "www"))) ? ' class="selected"' : '') ?>><img src="images/icons/home<?= ((@in_array($sPage, $sHomeModule) && @in_array($sCurDir, array("Portal", "portal",  "www"))) ? '-selected' : '') ?>.png" width="16" height="16" vspace="7" alt="Home" title="Home" /></a>
<?
	if ($iVsnCount > 0)
	{
?>
		    <a href="vsn/"<?= ((@in_array($sPage, $sVsnModule) && $sCurDir == "vsn") ? ' class="selected"' : '') ?>>VSN</a>
<?
	}

	if ($iVsrCount > 0)
	{
?>
		    <a href="vsr/"<?= ((@in_array($sPage, $sVsrModule) && $sCurDir == "vsr") ? ' class="selected"' : '') ?>>VSR</a>
<?
	}

	if ($iQsnCount > 0)
	{
?>
		    <a href="qsn/"<?= ((@in_array($sPage, $sQsnModule) && $sCurDir == "qsn") ? ' class="selected"' : '') ?>>QSN</a>
<?
	}

	if ($iBtaCount > 0)
	{
?>
		    <a href="bta/"<?= ((@in_array($sPage, $sBtaModule) && $sCurDir == "bta") ? ' class="selected"' : '') ?>>BTA</a>
<?
	}

	if ($iPccCount > 0)
	{
?>
		    <a href="pcc/"<?= ((@in_array($sPage, $sPccModule) && $sCurDir == "pcc") ? ' class="selected"' : '') ?>>PCC</a>
<?
	}

	if ($iDataEntryCount > 0)
	{
?>
		    <a href="data/"<?= ((@in_array($sPage, $sDataEntryModule) && $sCurDir == "data") ? ' class="selected"' : '') ?>>Data Entry</a>
<?
	}

	if ($iShippingCount > 0)
	{
?>
		    <a href="shipping/"<?= ((@in_array($sPage, $sShippingModule) && $sCurDir == "shipping") ? ' class="selected"' : '') ?>>Shipping</a>
<?
	}

	if ($iQuondaCount > 0)
	{
?>
		    <a href="quonda/"<?= ((@in_array($sPage, $sQuondaModule) && $sCurDir == "quonda") ? ' class="selected"' : '') ?>>Quonda</a>
<?
	}

	if ($iQmipCount > 0)
	{
?>
		    <a href="qmip/"<?= ((@in_array($sPage, $sQmipModule) && $sCurDir == "qmip") ? ' class="selected"' : '') ?>>QMIP</a>
<?
	}

	if ($iSamplingCount > 0)
	{
?>
		    <a href="sampling/"<?= ((@in_array($sPage, $sSamplingModule) && $sCurDir == "sampling") ? ' class="selected"' : '') ?>>Protoware</a>
<?
	}

	if ($iReportsCount > 0)
	{
?>
		    <a href="reports/"<?= ((@in_array($sPage, $sReportsModule) && $sCurDir == "reports") ? ' class="selected"' : '') ?>>Reports</a>
<?
	}

	if ($iHrCount > 0)
	{
?>
		    <a href="hr/"<?= ((@in_array($sPage, $sHrModule) && $sCurDir == "hr") ? ' class="selected"' : '') ?>>HR</a>
<?
	}

	if ($iCrcCount > 0)
	{
?>
		    <a href="crc/"<?= ((@in_array($sPage, $sCrcModule) && $sCurDir == "crc") ? ' class="selected"' : '') ?>>VMAN</a>
<?
	}

	if ($iYarnCount > 0)
	{
?>
		    <a href="yarn/"<?= ((@in_array($sPage, $sYarnModule) && $sCurDir == "yarn") ? ' class="selected"' : '') ?>>Brandix</a>
<?
	}

	if ($iDropboxCount > 0)
	{
?>
		    <a href="dropbox/"<?= ((@in_array($sPage, $sDropboxModule) && $sCurDir == "dropbox") ? ' class="selected"' : '') ?>>Dropbox</a>
<?
	}
?>
		    <a href="libs/"<?= ((@in_array($sPage, $sLibsModule) && $sCurDir == "libs") ? ' class="selected"' : '') ?>>Libs</a>
<?
/*
	if ($_SESSION['Admin'] == "Y")
	{
?>
		    <a href="admin/"<?= ((@in_array($sPage, $sAdminModule) && $sCurDir == "admin") ? ' class="selected"' : '') ?>>Admin</a>
<?
	}
*/
?>
		  </div>

		  <div id="SubNav">
<?
	if (@in_array($sPage, $sHomeModule) && @in_array($sCurDir, array("Portal", "portal",  "www")))
	{
?>
		    <a href="calendar.php">Calendar</a> |
		    <a href="contact-us.php">Contact Us</a> |
<?
		if ($_SESSION['UserId'] == "")
		{
?>
		    <a href="create-account.php">Create Account</a> |
<?
		}

		else
		{
?>
		    <a href="my-account.php">My Account</a> |
		    <a href="notifications.php">Notifications</a> |
		    <a href="do-sign-out.php">Signout</a> |
<?
		}
	}

	else if (@in_array($sPage, $sPccModule) && $sCurDir == "pcc")
	{
		for ($i = 0; $i < $iPccCount; $i ++)
		{
			if ($sPccRights[$i]['section'] == "Product Explorer")
			{
?>
		    <a href="pcc/explorer.php">Product Explorer</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Yarn")
			{
?>
		    <a href="pcc/yarn.php">Yarn</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Garment Styles")
			{
?>
		    <a href="pcc/garments.php">Garment Styles</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Products")
			{
				if ($sPccRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="pcc/products.php">Products</a> (<a href="pcc/add-product.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="pcc/products.php">Products</a> |
<?
				}
			}

			else if ($sPccRights[$i]['section'] == "Print")
			{
?>
		    <a href="pcc/print.php">Print</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Embroidery")
			{
?>
		    <a href="pcc/embroidery.php">Embroidery</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Descriptions")
			{
?>
		    <a href="pcc/descriptions.php">Descriptions</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Collections")
			{
?>
		    <a href="pcc/collections.php">Collections</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Galleries")
			{
?>
		    <a href="pcc/galleries.php">Galleries</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "User Galleries")
			{
?>
		    <a href="pcc/user-galleries.php">User Galleries</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Companies")
			{
?>
		    <a href="pcc/companies.php">Companies</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Projects")
			{
?>
		    <a href="pcc/projects.php">Projects</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Board Types")
			{
?>
		    <a href="pcc/board-types.php">Board Types</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Boards")
			{
?>
		    <a href="pcc/boards.php">Boards</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Markets")
			{
?>
		    <a href="pcc/markets.php">Markets</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Seasons")
			{
?>
		    <a href="pcc/seasons.php">Seasons</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Photos")
			{
?>
		    <a href="pcc/photos.php">Photos</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Fabrics")
			{
?>
		    <a href="pcc/fabrics.php">Fabrics</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Categories")
			{
?>
		    <a href="pcc/categories.php">Categories</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Colors")
			{
?>
		    <a href="pcc/colors.php">Colors</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Product Levels")
			{
?>
		    <a href="pcc/product-levels.php">Product Stories</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Styles")
			{
				if ($sPccRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="pcc/styles.php">Styles</a> (<a href="pcc/add-style.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="pcc/styles.php">Styles</a> |
<?
				}
			}


			else if ($sPccRights[$i]['section'] == "Samples")
			{
				if ($sPccRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="pcc/samples.php">Samples</a> (<a href="pcc/add-sample.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="pcc/samples.php">Samples</a> |
<?
				}
			}
			
			else if ($sPccRights[$i]['section'] == "Sample Types")
			{
?>
		    <a href="pcc/sample-types.php">Sample Types</a> |
<?
			}
			
			else if ($sPccRights[$i]['section'] == "Embellishment")
			{
?>
		    <a href="pcc/embellishments.php">Embellishment</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Construction")
			{
?>
		    <a href="pcc/constructions.php">Construction</a> |
<?
			}
			
			else if ($sPccRights[$i]['section'] == "Sources")
			{
?>
		    <a href="pcc/sources.php">Sources</a> |
<?
			}
			
			else if ($sPccRights[$i]['section'] == "Dyestuff")
			{
?>
		    <a href="pcc/dyestuff.php">Dyestuff</a> |
<?
			}

			else if ($sPccRights[$i]['section'] == "Trims")
			{
?>
		    <a href="pcc/trims.php">Trims</a> |
<?
			}
			
			else if ($sPccRights[$i]['section'] == "Yarn/Fiber")
			{
?>
		    <a href="pcc/yarn-fibers.php">Yarn/Fiber</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sQmipModule) && $sCurDir == "qmip")
	{
		for ($i = 0; $i < $iQmipCount; $i ++)
		{
			if ($sQmipRights[$i]['section'] == "Dashboard")
			{
?>
		    <a href="qmip/dashboard.php">Dashboard</a> |
<?
			}

			else if ($sQmipRights[$i]['section'] == "QMIP Graphs")
			{
?>
		    <a href="qmip/qmip-graphs.php">QMIP Graphs</a> |
<?
			}

			else if ($sQmipRights[$i]['section'] == "Operators")
			{
?>
		    <a href="qmip/operators.php">Operators</a> |
<?
			}

			else if ($sQmipRights[$i]['section'] == "Operations")
			{
?>
		    <a href="qmip/operations.php">Operations</a> |
<?
			}

			else if ($sQmipRights[$i]['section'] == "Machine Types")
			{
?>
		    <a href="qmip/machine-types.php">Machine Types</a> |
<?
			}

			else if ($sQmipRights[$i]['section'] == "Machines")
			{
?>
		    <a href="qmip/machines.php">Machines</a> |
<?
			}

			else if ($sQmipRights[$i]['section'] == "QA Reports")
			{
?>
		    <a href="qmip/qa-reports.php">QA Reports</a> |
<?
			}

			else if ($sQmipRights[$i]['section'] == "Brands")
			{
?>
			<a href="qmip/brands.php">Brands</a> |
<?
			}
                        else if ($sQmipRights[$i]['section'] == "Auditors Performance")
			{
?>
		    <a href="qmip/auditors-performance.php">Auditors Performance</a> |
<?
			}
                        else if ($sQmipRights[$i]['section'] == "QMIP Daily Report")
			{
?>
		    <a href="qmip/qmip-daily-report.php">QMIP Daily Report</a> |
<?
			}
                        
		}
	}

	else if (@in_array($sPage, $sVsnModule) && $sCurDir == "vsn")
	{
		for ($i = 0; $i < $iVsnCount; $i ++)
		{
			if ($sVsnRights[$i]['section'] == "Vendors Status Navigator")
			{
?>
		    <a href="vsn/vsn.php">Vendors Status Navigator</a> |
<?
			}

			else if ($sVsnRights[$i]['section'] == "On-Time Performance")
			{
?>
		    <a href="vsn/otp.php">On-Time Performance</a> |
<?
			}

			else if ($sVsnRights[$i]['section'] == "Reports Comparison")
			{
?>
		    <a href="vsn/reports-comparison.php">Reports Comparison</a> |
<?
			}

			else if ($sVsnRights[$i]['section'] == "Seasons OTP")
			{
?>
		    <a href="vsn/seasons-otp.php">Seasons OTP</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sVsrModule) && $sCurDir == "vsr")
	{
		for ($i = 0; $i < $iVsrCount; $i ++)
		{
			if ($sVsrRights[$i]['section'] == "Vendors Status Report")
			{
?>
		    <a href="vsr/vsr.php">Vendors Status Report</a> |
<?
			}

			else if ($sVsrRights[$i]['section'] == "VSR Details")
			{
?>
		    <a href="vsr/vsr-details.php">VSR Details</a> |
<?
			}

			else if ($sVsrRights[$i]['section'] == "VSR Data")
			{
?>
		    <a href="vsr/vsr-data.php">VSR Data</a> |
<?
			}

			else if ($sVsrRights[$i]['section'] == "Deviation")
			{
?>
		    <a href="vsr/deviation.php">Deviation</a> |
<?
			}

			else if ($sVsrRights[$i]['section'] == "ETD Revisions")
			{
?>
		    <a href="vsr/etd-revisions.php">ETD Revisions</a> |
<?
			}

			else if ($sVsrRights[$i]['section'] == "Work Orders")
			{
				if ($sVsrRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="vsr/work-orders.php">Work Orders</a> (<a href="vsr/add-work-order.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="vsr/work-orders.php">Work Orders</a> |
<?
				}
			}

			else if ($sVsrRights[$i]['section'] == "Work Order Details")
			{
?>
		    <a href="vsr/work-order-details.php">Work Order Details</a> |
<?
			}

			else if ($sVsrRights[$i]['section'] == "New VSR")
			{
?>
		    <a href="vsr/new-vsr.php">New VSR</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sQsnModule) && $sCurDir == "qsn")
	{
		for ($i = 0; $i < $iQsnCount; $i ++)
		{
			if ($sQsnRights[$i]['section'] == "Quality Status Navigator")
			{
?>
		    <a href="qsn/qsn.php">Quality Status Navigator</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sBtaModule) && $sCurDir == "bta")
	{
		for ($i = 0; $i < $iBtaCount; $i ++)
		{
			if ($sBtaRights[$i]['section'] == "Business Trend Analysis")
			{
?>
		    <a href="bta/bta.php">Business Trend Analysis</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sCrcModule) && $sCurDir == "crc")
	{
		for ($i = 0; $i < $iCrcCount; $i ++)
		{
			if ($sCrcRights[$i]['section'] == "OT Data")
			{
?>
		    <a href="crc/ot-data.php">OT Data</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "OT Analysis")
			{
?>
		    <a href="crc/ot-analysis.php">OT Analysis</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "ERA Converter")
			{
?>
		    <a href="crc/era-converter.php">ERA Converter</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Categories")
			{
?>
		    <a href="crc/categories.php">Categories</a> |
<?
			}
                        
                        else if ($sCrcRights[$i]['section'] == "CRC Departments")
			{
?>
		    <a href="crc/departments.php">CRC Departments</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Weights")
			{
?>
		    <a href="crc/weights.php">Weights</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Reports")
			{
?>
		    <a href="crc/reports.php">Reports</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "VMAN")
			{
?>
		    <a href="crc/dashboard.php">VMAN</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "VMAP")
			{
?>
		    <a href="crc/vmap.php">VMAP</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Safety Categories")
			{
?>
		    <a href="crc/safety-categories.php">Safety Categories</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Safety Questions")
			{
?>
		    <a href="crc/safety-questions.php">Safety Questions</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Safety Audits")
			{
?>
		    <a href="crc/safety-audits.php">Safety Audits</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Quality Audits")
			{
?>
		    <a href="crc/quality-audits.php">Quality Audits</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Quality Points")
			{
?>
		    <a href="crc/quality-points.php">Quality Points</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Compliance Types")
			{
?>
		    <a href="crc/compliance-types.php">Compliance Types</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Compliance Categories")
			{
?>
		    <a href="crc/compliance-categories.php">Compliance Categories</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Compliance Questions")
			{
?>
		    <a href="crc/compliance-questions.php">Compliance Questions</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Compliance Audits")
			{
?>
		    <a href="crc/compliance-audits.php">Compliance Audits</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Production Categories")
			{
?>
		    <a href="crc/production-categories.php">Production Categories</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Production Questions")
			{
?>
		    <a href="crc/production-questions.php">Production Questions</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Production Audits")
			{
?>
		    <a href="crc/production-audits.php">Production Audits</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Certifications")
			{
?>
		    <a href="crc/certifications.php">Certifications</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Vendor Certifications")
			{
?>
		    <a href="crc/vendor-certifications.php">Vendor Certifications</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "Audit Pictures")
			{
?>
		    <a href="crc/audit-pictures.php">Audit Pictures</a> |
<?
			}

			else if ($sCrcRights[$i]['section'] == "CRC Sections")
			{
?>
				<a href="crc/tnc-sections.php">CRC Sections</a> |
<?
			}
			
			else if ($sCrcRights[$i]['section'] == "CRC Categories")
			{
?>
				<a href="crc/tnc-categories.php">CRC Categories</a> |
<?
			}
			
			else if ($sCrcRights[$i]['section'] == "CRC Points")
			{
?>
				<a href="crc/tnc-points.php">CRC Points</a> |
<?
			}
			
			else if ($sCrcRights[$i]['section'] == "CRC Old Audits")
			{
?>
				<a href="crc/tnc-audits.php">CRC Old Audits</a> |
<?
			}
                        
                        else if ($sCrcRights[$i]['section'] == "CRC Audits")
			{
?>
				<a href="crc/crc-audits.php">CRC Audits</a> |
<?
			}
			
                        else if ($sCrcRights[$i]['section'] == "CRC Dashboard")
			{
?>
				<a href="crc/tnc-dashboard.php">CRC Dashboard</a> |
<?
			}
                        
                        else if ($sCrcRights[$i]['section'] == "CRC Schedules")
			{
?>
				<a href="crc/tnc-schedules.php">CRC Schedules</a> |
<?
			}

            else if ($sCrcRights[$i]['section'] == "Chemical Types")
			{
?>
				<a href="crc/chemical-types.php">Chemical Types</a> |
<?
			}

            else if ($sCrcRights[$i]['section'] == "Chemical Compounds")
			{
?>
				<a href="crc/chemical-compounds.php">Chemical Compounds</a> |
<?
			}
			
            else if ($sCrcRights[$i]['section'] == "Chemical Location Types")
			{
?>
				<a href="crc/chemical-location-types.php">Chemical Location Types</a> |
<?
			}

            else if ($sCrcRights[$i]['section'] == "Chemical Locations")
			{
?>
				<a href="crc/chemical-locations.php">Chemical Locations</a> |
<?
			}

            else if ($sCrcRights[$i]['section'] == "Chemicals Inventory")
			{
?>
				<a href="crc/chemicals-inventory.php">Chemicals Inventory</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sDataEntryModule) && $sCurDir == "data")
	{
		for ($i = 0; $i < $iDataEntryCount; $i ++)
		{
			if ($sDataEntryRights[$i]['section'] == "Countries")
			{
?>
		    <a href="data/countries.php">Countries</a> |
<?
			}                        
                        else if($sDataEntryRights[$i]['section'] == "Customers")
			{
?>
		    <a href="data/customers.php">Customers</a> |
<?
			}
                        else if ($sDataEntryRights[$i]['section'] == "Cities")
			{
?>
		    <a href="data/cities.php">Cities</a> |
<?
			} 
                        else if ($sDataEntryRights[$i]['section'] == "Shipping Ports")
			{
?>
		    <a href="data/ports.php">Shipping Ports</a> |
<?
			}                        
                        else if ($sDataEntryRights[$i]['section'] == "Country Blocks")
			{
?>
		    <a href="data/country-blocks.php">Country Blocks</a> |
<?
			}
                        
			else if ($sDataEntryRights[$i]['section'] == "Categories")
			{
?>
		    <a href="data/categories.php">Categories</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Vendors")
			{
?>
		    <a href="data/vendors.php">Vendors</a> |
<?
			}
                        
                        else if ($sDataEntryRights[$i]['section'] == "Edit Vendor")
			{
?>
		    <a href="data/edit-vendor.php">Edit Vendor</a> |
<?
			}
                        
                        else if ($sDataEntryRights[$i]['section'] == "Suppliers")
			{
?>
		    <a href="data/suppliers.php">Suppliers</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Brands")
			{
?>
		    <a href="data/brands.php">Brands</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Seasons")
			{
?>
		    <a href="data/seasons.php">Seasons</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Destinations")
			{
?>
		    <a href="data/destinations.php">Destinations</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Sizes")
			{
?>
		    <a href="data/sizes.php">Sizes</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Style Categories")
			{
?>
		    <a href="data/style-categories.php">Style Categories</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Styles")
			{
?>
		    <a href="data/styles.php">Styles</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Purchase Orders")
			{
				if ($sDataEntryRights[$i]['add'] == "Y" && (@strpos($_SESSION["Email"], "@apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "@3-tree.com") !== FALSE || @strpos($_SESSION["Email"], "@selimpex.com") !== FALSE || @strpos($_SESSION["Email"], "@global-exports.com") !== FALSE))
				{
?>
		    <span><a href="data/purchase-orders.php">Purchase Orders</a> (<a href="data/add-purchase-order.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="data/purchase-orders.php">Purchase Orders</a> |
<?
				}
			}

			else if ($sDataEntryRights[$i]['section'] == "POs Import")
			{
?>
		    <a href="data/pos-import.php">POs Import</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "POSDD")
			{
?>
		    <a href="data/posdd.php">POSDD</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "PO Commission")
			{
?>
		    <a href="data/po-commission.php">PO Commission</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Forecasts")
			{
?>
		    <a href="data/forecasts.php">Forecasts</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Revised Forecasts")
			{
?>
		    <a href="data/revised-forecasts.php">Revised Forecasts</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Vendor Profiles")
			{
				if ($sDataEntryRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="data/vendor-profiles.php">Vendor Profiles</a> (<a href="data/vendor-albums.php">Picture Albums</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="data/vendor-profiles.php">Vendor Profiles</a> |
<?
				}
			}

			else if ($sDataEntryRights[$i]['section'] == "Fabric Library")
			{
				if ($sDataEntryRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="data/fabric-library.php">Fabric Library</a> (<a href="data/fabric-categories.php">Fabric Categories</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="data/fabric-library.php">Fabric Library</a> |
<?
				}
			}

			else if ($sDataEntryRights[$i]['section'] == "Library")
			{
?>
		    <a href="data/library.php">Library</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Blog Posts")
			{
				if ($sDataEntryRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="data/blog.php">Blog Posts</a> (<a href="data/add-blog-post.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="data/blog.php">Blog Posts</a> |
<?
				}
			}

			else if ($sDataEntryRights[$i]['section'] == "Blog Categories")
			{
?>
		    <a href="data/blog-categories.php">Blog Categories</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Programs")
			{
?>
		    <a href="data/programs.php">Programs</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "ETD Revision Requests")
			{
?>
		    <a href="data/etd-revision-requests.php">Etd Revision Requests</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "ETD Revision Reasons")
			{
?>
		    <a href="data/etd-revision-reasons.php">Etd Revision Reasons</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Brand Offices")
			{
?>
		    <a href="data/brand-offices.php">Brand Offices</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Videos")
			{
?>
		    <a href="data/videos.php">Videos</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Flipbook Products")
			{
?>
		    <a href="data/products.php">Flipbook Products</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Flipbooks")
			{
?>
		    <a href="data/flipbooks.php">Flipbooks</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Couriers")
			{
?>
		    <a href="data/couriers.php">Couriers</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Production Stages")
			{
?>
		    <a href="data/production-stages.php">Production Stages</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Escrow Orders")
			{
?>
		    <a href="data/escrow-orders.php">Escrow Orders</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "ETD Managers")
			{
?>
		    <a href="data/etd-managers.php">ETD Managers</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Cotton Rates")
			{
?>
		    <a href="data/cotton-rates.php">Cotton Rates</a> |
<?
			}

			else if ($sDataEntryRights[$i]['section'] == "Yarn Rates")
			{
?>
		    <a href="data/yarn-rates.php">Yarn Rates</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sShippingModule) && $sCurDir == "shipping")
	{
		for ($i = 0; $i < $iShippingCount; $i ++)
		{
			if ($sShippingRights[$i]['section'] == "Terms of Delivery")
			{
?>
		    <a href="shipping/terms-of-delivery.php">Terms of Delivery</a> |
<?
			}

			else if ($sShippingRights[$i]['section'] == "Pre-Shipment Advice")
			{
?>
		    <a href="shipping/pre-shipment-advice.php">Pre-Shipment Advice</a> |
<?
			}

			else if ($sShippingRights[$i]['section'] == "Post-Shipment Advice")
			{
?>
		    <a href="shipping/post-shipment-advice.php">Post-Shipment Advice</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sQuondaModule) && $sCurDir == "quonda")
	{
		for ($i = 0; $i < $iQuondaCount; $i ++)
		{
			if ($sQuondaRights[$i]['section'] == "Quonda Graphs")
			{
?>
		    <a href="quonda/quonda-graphs.php">Quonda Graphs</a> |
<?
			}                            
                        
                        else if ($sQuondaRights[$i]['section'] == "Qa Checklist")
			{
?>
		    <a href="quonda/qa-checklist.php">QA Checklist</a> |
<?
			}
                        
                        else if ($sQuondaRights[$i]['section'] == "Bookings")
			{
?>
		    <a href="quonda/bookings.php">Bookings</a> |
<?
			}
                        
                        else if ($sQuondaRights[$i]['section'] == "TNC Defects Nature")
			{
?>
		    <a href="quonda/defects-nature.php">TNC Defects Nature</a> |
<?
			}
                        
                        else if ($sQuondaRights[$i]['section'] == "AQL")
			{
?>
		    <a href="quonda/aql.php">AQL</a> |
<?
			}
                        else if ($sQuondaRights[$i]['section'] == "Sections")
			{
?>
		    <a href="quonda/sections.php">Sections</a> |
<?
			}
                        else if ($sQuondaRights[$i]['section'] == "Questionnaire")
			{
?>
		    <a href="quonda/questions.php">Questionnaire</a> |
<?
			}        
                        else if ($sQuondaRights[$i]['section'] == "Statements")
			{
?>
		    <a href="quonda/statements.php">Statements</a> |
<?
			}
                        else if ($sQuondaRights[$i]['section'] == "Packaging Defects")
			{
?>
		    <a href="quonda/packaging-defects.php">Packaging Defects</a> |
<?
			}                        
                        else if ($sQuondaRights[$i]['section'] == "Audit Types")
			{
?>
		    <a href="quonda/audit-types.php">Audit Types</a> |
<?
			}
                        else if ($sQuondaRights[$i]['section'] == "Auditors Correlation")
			{
?>
		    <a href="quonda/auditors-correlation.php">Auditors Correlation</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Auditors Productivity")
			{
?>
		    <a href="quonda/auditors-productivity.php">Auditors Productivity</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Auditor Groups")
			{
?>
		    <a href="quonda/auditor-groups.php">Auditor Groups</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Line Types")
			{
?>
		    <a href="quonda/line-types.php">Line Types</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Lines")
			{
?>
		    <a href="quonda/lines.php">Lines</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Floors")
			{
?>
		    <a href="quonda/floors.php">Floors</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Audit Codes")
			{
?>
		    <a href="quonda/audit-codes.php">Audit Codes</a> |
<?
			}
                        
                        else if ($sQuondaRights[$i]['section'] == "Qa Sections")
			{
?>
		    <a href="quonda/qa-sections.php">QA Sections</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "QA Reports")
			{
?>
		    <a href="quonda/qa-reports.php">QA Reports</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Quonda Reports")
			{
?>
		    <a href="quonda/quonda-reports.php">Quonda Reports</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Report Types")
			{
?>
		    <a href="quonda/reports.php">Report Types</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Defect Types")
			{
?>
		    <a href="quonda/defect-types.php">Defect Types</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Defect Codes")
			{
?>
		    <a href="quonda/defect-codes.php">Defect Codes</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Audit Stages")
			{
?>
		    <a href="quonda/audit-stages.php">Audit Stages</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Defect Areas")
			{
?>
		    <a href="quonda/defect-areas.php">Defect Areas</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Defects Catalogue")
			{
?>
		    <a href="quonda/defects-catalogue.php">Defects Catalogue</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Dashboard")
			{
?>
		    <a href="quonda/dashboard.php">Dashboard</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "QA Reviews")
			{
?>
		    <a href="quonda/qa-reviews.php">QA Reviews</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "CSC Audits")
			{
?>
		    <a href="quonda/csc-audits.php">CSC Audits</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Reports Comparison")
			{
?>
		    <a href="quonda/reports-comparison.php">Reports Comparison</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "QA Emails")
			{
?>
		    <a href="quonda/emails.php">QA Emails</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Auditors Swarm")
			{
?>
		    <a href="quonda/auditors-swarm.php">Auditors Swarm</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Audit Schedules")
			{
?>
		    <a href="quonda/audit-schedules.php">Audit Schedules</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Vendor Reports")
			{
?>
		    <a href="quonda/vendor-reports.php">Vendor Reports</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "QA Reports Data Analysis")
			{
?>
		    <a href="quonda/qa-reports-analysis.php">QA Reports Data Analysis</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "Signatures")
			{
?>
		    <a href="quonda/signatures.php">Signatures</a> |
<?
			}

			else if ($sQuondaRights[$i]['section'] == "QA Commission")
			{
?>
		    <a href="quonda/qa-commission.php">QA Commission</a> |
<?
			}
			
            else if ($sQuondaRights[$i]['section'] == "Guidelines")
			{
				if ($sQuondaRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="quonda/guidelines.php">Guidelines</a> (<a href="quonda/add-guideline.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="quonda/guidelines.php">Guidelines</a> |
<?
				}
			}
			
			else if ($sQuondaRights[$i]['section'] == "Sisense")
			{
?>
		    <a href="quonda/sisense.php">Sisense</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sReportsModule) && $sCurDir == "reports")
	{
		for ($i = 0; $i < $iReportsCount; $i ++)
		{
			if ($sReportsRights[$i]['section'] == "Shipment Summary")
			{
?>
		    <a href="reports/shipment-summary.php">Shipment Summary</a> |
<?
			}
                        
                        if ($sReportsRights[$i]['section'] == "Shipping Report DKC")
			{
?>
		    <a href="reports/shipping-report-dkc.php">Shipping Report DKC</a> |
<?
			}
                        
                        if ($sReportsRights[$i]['section'] == "OQL Report")
			{
?>
		    <a href="reports/oql-report.php">OQL Report</a> |
<?
			}
                        
                        if ($sReportsRights[$i]['section'] == "Triumph Report")
			{
?>
		    <a href="reports/triumph-report.php">Triumph Report</a> |
<?
			}
                        
                        if ($sReportsRights[$i]['section'] == "TNC OQL Tracking")
			{
?>
		    <a href="reports/oql-tracking-report.php">TNC OQL Tracking</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Commission Report")
			{
?>
		    <a href="reports/commission-report.php">Commission Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Quality Report")
			{
?>
		    <a href="reports/quality-report.php">Quality Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Invoice Report")
			{
?>
		    <a href="reports/invoice-report.php">Invoice Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Shipping Report")
			{
?>
		    <a href="reports/shipping-report.php">Shipping Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "SS Commission Report")
			{
?>
		    <a href="reports/ss-commission-report.php">SS Commission Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Measurements Report")
			{
?>
		    <a href="reports/measurements-report.php">Measurements Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Inspection Certificate")
			{
?>
		    <a href="reports/inspection-certificate.php">Inspection Certificate</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Attendance Report")
			{
?>
		    <a href="reports/attendance-report.php">Attendance Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Portal Usuage Report")
			{
?>
		    <a href="reports/portal-usuage-report.php">Portal Usuage Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Current Standing")
			{
?>
		    <a href="reports/current-standing.php">Current Standing</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Forecast Report")
			{
?>
		    <a href="reports/forecast-report.php">Forecast Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "VSR Report")
			{
?>
		    <a href="reports/vsr-report.php">VSR Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "KPI's Report")
			{
?>
		    <a href="reports/kpis-report.php">KPI's Report</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "FOB Value")
			{
?>
		    <a href="reports/fob-value.php">FOB Value</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "Invoice Generator")
			{
?>
		    <a href="reports/invoice-generator.php">Invoice Generator</a> |
<?
			}

			else if ($sReportsRights[$i]['section'] == "MGF Report")
			{
?>
		    <a href="reports/mgf-report.php">MGF Report</a> |
<?
			}
                        else if ($sReportsRights[$i]['section'] == "Levis Report")
			{
?>
		    <a href="reports/levis-report.php">Levis Report</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sSamplingModule) && $sCurDir == "sampling")
	{
		for ($i = 0; $i < $iSamplingCount; $i ++)
		{
			if ($sSamplingRights[$i]['section'] == "Sales Samples")
			{
				if ($sSamplingRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="sampling/sales-samples.php">Sales Samples</a> (<a href="sampling/add-sales-sample.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="sampling/sales-samples.php">Sales Samples</a> |
<?
				}
			}
                        
			else if ($sSamplingRights[$i]['section'] == "Sampling Types")
			{
?>
		    <a href="sampling/types.php">Types</a> |
<?
			}
                        
                        else if ($sSamplingRights[$i]['section'] == "Sampling Emails")
			{
?>
		    <a href="sampling/emails.php">Sampling Emails</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Sampling Sizes")
			{
?>
		    <a href="sampling/sizes.php">Sizes</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Sampling Categories")
			{
?>
		    <a href="sampling/categories.php">Categories</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Sampling Washes")
			{
?>
		    <a href="sampling/washes.php">Washes</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Measurement Points")
			{
?>
		    <a href="sampling/measurement-points.php">Measurement Points</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Merchandisings")
			{
				if ($sSamplingRights[$i]['add'] == "Y")
				{
?>
		    <span><a href="sampling/merchandisings.php">Merchandising</a> (<a href="sampling/add-merchandising.php">New</a>)</span> |
<?
				}

				else
				{
?>
		    <a href="sampling/merchandisings.php">Merchandising</a> |
<?
				}
			}

			else if ($sSamplingRights[$i]['section'] == "Requisition & Report")
			{
?>
		    <a href="sampling/measurement-specs.php">Requisition & Report</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "SS Commission")
			{
?>
		    <a href="sampling/ss-commission.php">SS Commission</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Sampling Reports")
			{
?>
		    <a href="sampling/sampling-reports.php">Sampling Reports</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Report Types")
			{
?>
		    <a href="sampling/reports.php">Report Types</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Defect Types")
			{
?>
		    <a href="sampling/defect-types.php">Defect Types</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Defect Codes")
			{
?>
		    <a href="sampling/defect-codes.php">Defect Codes</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Defect Areas")
			{
?>
		    <a href="sampling/defect-areas.php">Defect Areas</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Lab Dips")
			{
?>
		    <a href="sampling/lab-dips.php">Lab Dips</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Lab Dip Colors")
			{
?>
		    <a href="sampling/lab-dip-colors.php">Lab Dip Colors</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Fabric")
			{
?>
		    <a href="sampling/fabric.php">Fabric</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Brand RMS")
			{
?>
		    <a href="sampling/brand-rms.php">Brand RMS</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Style Specs")
			{
?>
		    <a href="sampling/style-specs.php">Style Specs</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Styles")
			{
?>
		    <a href="sampling/styles.php">Styles</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Style Comments")
			{
?>
		    <a href="sampling/style-comments.php">Style Comments</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Inline Audits")
			{
?>
		    <a href="sampling/inline-audits.php">Inline Audits</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "Dashboard")
			{
?>
		    <a href="sampling/dashboard.php">Dashboard</a> |
<?
			}

			else if ($sSamplingRights[$i]['section'] == "360 Check")
			{
?>
		    <a href="sampling/360.php">360 Check</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sHrModule) && $sCurDir == "hr")
	{
		for ($i = 0; $i < $iHrCount; $i ++)
		{
			if ($sHrRights[$i]['section'] == "Employees")
			{
?>
		    <a href="hr/employees.php">Employees</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Attendance")
			{
?>
		    <a href="hr/attendance.php">Attendance</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Leaves")
			{
?>
		    <a href="hr/leaves.php">Leaves</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Holidays")
			{
?>
		    <a href="hr/holidays.php">Holidays</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Departments")
			{
?>
		    <a href="hr/departments.php">Departments</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Designations")
			{
?>
		    <a href="hr/designations.php">Designations</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Visit Locations")
			{
?>
		    <a href="hr/visit-locations.php">Visit Locations</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Location Distances")
			{
?>
		    <a href="hr/location-distances.php">Location Distances</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Leave Types")
			{
?>
		    <a href="hr/leave-types.php">Leave Types</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Visits")
			{
?>
		    <a href="hr/visits.php">Visits</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "HR Navigator")
			{
?>
		    <a href="hr/hrn.php">HRN</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Salaries")
			{
?>
		    <a href="hr/salaries.php">Salaries</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Messages")
			{
?>
		    <a href="hr/messages.php">Messages</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "HR Board")
			{
?>
		    <a href="hr/board.php">HR Board</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Offices")
			{
?>
		    <a href="hr/offices.php">Offices</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "SMS Attendance")
			{
?>
		    <a href="hr/sms-attendance.php">SMS Attendance</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Calendar")
			{
?>
		    <a href="hr/calendar.php">Calendar</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Brand Placements")
			{
?>
		    <a href="hr/brand-placements.php">Brand Placements</a> |
<?
			}

			else if ($sHrRights[$i]['section'] == "Surveys")
			{
?>
		    <a href="hr/surveys.php">Surveys</a> |
<?
			}
			
			else if ($sHrRights[$i]['section'] == "Activities")
			{
?>
		    <a href="hr/activities.php">Activities</a> |
<?
			}
			
			else if ($sHrRights[$i]['section'] == "User Activities")
			{
?>
		    <a href="hr/user-activities.php">User Activities</a> |
<?
			}
			
			else if ($sHrRights[$i]['section'] == "Profile Assessment Areas")
			{
?>
		    <a href="hr/profile-assessment-areas.php">Profile Assessment Areas</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sYarnModule) && $sCurDir == "yarn")
	{
		for ($i = 0; $i < $iYarnCount; $i ++)
		{
			if ($sYarnRights[$i]['section'] == "Trends")
			{
?>
		    <a href="yarn/trends.php">Trends</a> |
<?
			}

			else if ($sYarnRights[$i]['section'] == "Specs")
			{
?>
		    <a href="yarn/specs.php">Specs</a> |
<?
			}

			else if ($sYarnRights[$i]['section'] == "Loom Types")
			{
?>
		    <a href="yarn/loom-types.php">Loom Types</a> |
<?
			}

			else if ($sYarnRights[$i]['section'] == "Looms")
			{
?>
		    <a href="yarn/looms.php">Looms</a> |
<?
			}

			else if ($sYarnRights[$i]['section'] == "Inquiries")
			{
?>
		    <a href="yarn/inquiries.php">Inquiries</a> |
<?
			}

			else if ($sYarnRights[$i]['section'] == "Loom Plan")
			{
?>
		    <a href="yarn/loom-plan.php">Loom Plan</a> |
<?
			}
		}
	}

	else if (@in_array($sPage, $sDropboxModule) && $sCurDir == "dropbox")
	{
		for ($i = 0; $i < $iDropboxCount; $i ++)
		{
			if ($sDropboxRights[$i]['section'] == "Dropbox")
			{
?>
		    <a href="dropbox/dropbox.php">Dropbox</a> |
<?
			}
		}
	}
        
	else if (@in_array($sPage, $sLibsModule) && $sCurDir == "libs")
	{
?>
		    <a href="libs/vendor-profiles.php">Vendor Profiles</a> |
		    <a href="libs/fabric-library.php">Fabric Library</a> |
		    <a href="libs/library.php">Library</a> |
		    <a href="libs/cotton-futures.php">Cotton Futures</a> |
		    <a href="libs/videos.php">Videos</a> |
		    <a href="libs/extensions.php">Extensions</a> |
<?
	}

	else if (@in_array($sPage, $sAdminModule) && $sCurDir == "admin")
	{
?>
		    <a href="admin/">Users</a> |
<?
                    if (@in_array($_SESSION["UserType"], array("TRIPLETREE")) && $_SESSION['Admin'] == "Y")
                    {
?>
                        <a href="admin/backup-restore.php">Backup / Restore</a> |
                        <a href="admin/web-messages.php">Web Messages</a> |
                        <a href="admin/user-types.php">User Types</a> |
                        <a href="admin/clients.php">Clients</a> |
<?
                    }
	}

	else
	{
?>
		    &nbsp;
<?
	}
?>
		  </div>

		  <div id="Trail">
		    Where am I?
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
	if (@in_array($sPage, $sHomeModule) && @in_array($sCurDir, array("Portal", "portal",  "www")))
	{
		if ($sPage != "index.php")
		{
?>
		    <a href="./">Home</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		}

		else
		{
?>
		    <b>Home</b>
<?
		}

		$sPageTitle = array( );

		$sPageTitle['create-account.php']       = "Create Account";
		$sPageTitle['change-password.php']      = "Change Password";
		$sPageTitle['my-account.php']           = "My Account";
		$sPageTitle['blog.php']                 = "Newsletter";
		$sPageTitle['sms.php']                  = "SMS Server";
		$sPageTitle['contact-us.php']           = "Contact Us";
		$sPageTitle['privacy-policy.php']       = "Privacy Policy";
		$sPageTitle['terms-and-conditions.php'] = "Terms & Conditions";
		$sPageTitle['404.php']                  = "Page Not Found";
		$sPageTitle['signatures.php']           = "Employee Signatures";
		$sPageTitle['emails.php']               = "Emails Stats";
		$sPageTitle['calendar.php']             = "Calendar";
		$sPageTitle['notifications.php']        = "Notifications";
		$sPageTitle['survey.php']               = "MATRIX Survey";
		$sPageTitle['delay-reason.php']         = "PO Delay Reason";
		$sPageTitle['po-status.php']            = "PO Status";
		$sPageTitle['donations.php']            = "Donations";
		$sPageTitle['sampling-comments.php']    = "Sampling Comments";
		$sPageTitle['trends.php']               = "Cotton/Yarn Rates";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sPccModule) && $sCurDir == "pcc")
	{
?>
		    <a href="pcc/">PCC</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']           = "Dashboard";
		$sPageTitle['explorer.php']        = "Product Explorer";
		$sPageTitle['product.php']         = "Product Explorer";
		$sPageTitle['yarn.php']            = "Yarn";
		$sPageTitle['garments.php']        = "Garment Styles";
		$sPageTitle['products.php']        = "Products";
		$sPageTitle['add-product.php']     = "Products";
		$sPageTitle['edit-product.php']    = "Products";
		$sPageTitle['print.php']           = "Print";
		$sPageTitle['embroidery.php']      = "Embroidery";
		$sPageTitle['descriptions.php']    = "Descriptions";
		$sPageTitle['collections.php']     = "Collections";
		$sPageTitle['galleries.php']       = "Galleries";
		$sPageTitle['user-galleries.php']  = "User Galleries";
		$sPageTitle['user-pictures.php']   = "User Galleries";
		$sPageTitle['companies.php']       = "Companies";
		$sPageTitle['projects.php']        = "Projects";
		$sPageTitle['board-types.php']     = "Board Types";
		$sPageTitle['boards.php']          = "Boards";
		$sPageTitle['markets.php']         = "Markets";
		$sPageTitle['seasons.php']         = "Seasons";
		$sPageTitle['photos.php']          = "Photos";
		$sPageTitle['fabrics.php']         = "Fabrics";
		$sPageTitle['categories.php']      = "Categories";
		$sPageTitle['product-levels.php']  = "Product Levels";
		$sPageTitle['styles.php']          = "Styles";
		$sPageTitle['add-style.php']       = "Styles";
		$sPageTitle['edit-style.php']      = "Styles";
		$sPageTitle['style-comments.php']  = "Styles";
		$sPageTitle['style-photos.php']    = "Styles";
		$sPageTitle['samples.php']         = "Samples";
		$sPageTitle['add-sample.php']      = "Samples";
		$sPageTitle['edit-sample.php']     = "Samples";
		$sPageTitle['sample-comments.php'] = "Samples";
		$sPageTitle['colors.php']          = "Colors";
		$sPageTitle['sample-types.php']    = "Sample Types";
		$sPageTitle['embellishments.php']  = "Embellishment";
		$sPageTitle['constructions.php']   = "Construction";
		$sPageTitle['sources.php']         = "Sources";
		$sPageTitle['dyestuff.php']        = "Dyestuff";
		$sPageTitle['trims.php']           = "Trims";
		$sPageTitle['yarn-fibers.php']     = "Yarn/Fibers";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sQmipModule) && $sCurDir == "qmip")
	{
?>
		    <a href="qmip/">QMIP</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']                = "Dashboard";
		$sPageTitle['dashboard.php']            = "Dashboard";
		$sPageTitle['audit-progress.php']       = "Audit Live View";
		$sPageTitle['po-progress.php']          = "PO Progress Analysis";
		$sPageTitle['qmip-graphs.php']          = "QMIP Graphs";
		$sPageTitle['qmip-graphs2.php']         = "QMIP Graphs";
		$sPageTitle['operators.php']            = "Operators";
		$sPageTitle['operations.php']           = "Operations";
		$sPageTitle['machine-types.php']        = "Machine Types";
		$sPageTitle['machines.php']             = "Machines";
		$sPageTitle['qa-reports.php']           = "QA Reports";
		$sPageTitle['edit-qa-report.php']       = "QA Reports";
		$sPageTitle['send-qa-report.php']       = "QA Reports";
		$sPageTitle['qa-report-images.php']     = "QA Reports";
		$sPageTitle['brands.php']               = "Brands";
		$sPageTitle['auditors-performance.php'] = "Auditors Performance";
		$sPageTitle['qmip-daily-report.php']    = "QMIP Daily Report";	
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sVsnModule) && $sCurDir == "vsn")
	{
?>
		    <a href="vsn/">VSN</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['vsn.php']                = "Vendors Status Navigator";
		$sPageTitle['otp.php']                = "On-Time Performance";
		$sPageTitle['reports-comparison.php'] = "Reports Comparison";
		$sPageTitle['seasons-otp.php']        = "Seasons OTP";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sVsrModule) && $sCurDir == "vsr")
	{
?>
		    <a href="vsr/">VSR</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']              = "Dashboard";
		$sPageTitle['vsr.php']                = "Vendors Status Report";
		$sPageTitle['vsr-details.php']        = "VSR Details";
		$sPageTitle['vsr-data.php']           = "VSR Data";
		$sPageTitle['import-vsr.php']         = "VSR Data";
		$sPageTitle['edit-vsr-po.php']        = "VSR Data";
		$sPageTitle['deviation.php']          = "Deviation";
		$sPageTitle['etd-revisions.php']      = "ETD Revisions";
		$sPageTitle['work-orders.php']        = "Work Orders";
		$sPageTitle['add-work-order.php']     = "Work Orders";
		$sPageTitle['edit-work-order.php']    = "Work Orders";
		$sPageTitle['work-order-details.php'] = "Work Order Details";
		$sPageTitle['new-vsr.php']			  = "VSR Data";
		$sPageTitle['fetch-styles.php']		  = "VSR Styles";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sQsnModule) && $sCurDir == "qsn")
	{
?>
		    <a href="qsn/">QSN</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['qsn.php'] = "Quality Status Navigator";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sBtaModule) && $sCurDir == "bta")
	{
?>
		    <a href="bta/">BTA</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['bta.php'] = "Business Trend Analysis";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sCrcModule) && $sCurDir == "crc")
	{
?>
		    <a href="crc/">CRC</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']                   = "Dashboard";
		$sPageTitle['ot-data.php']                 = "Over-Time Data";
		$sPageTitle['ot-analysis.php']             = "Over-Time Analysis";
		$sPageTitle['era-converter.php']           = "ERA Converter";
		$sPageTitle['categories.php']              = "Categories";
		$sPageTitle['weights.php']                 = "Weights";
		$sPageTitle['reports.php']                 = "Reports";
		$sPageTitle['dashboard.php']               = "VMAN";
		$sPageTitle['vmap.php']                    = "VMAP";
		$sPageTitle['safety-categories.php']       = "Safety Categories";
		$sPageTitle['safety-questions.php']        = "Safety Questions";
		$sPageTitle['safety-audits.php']           = "Safety Audits";
		$sPageTitle['edit-safety-audit.php']       = "Safety Audits";
		$sPageTitle['quality-audits.php']          = "Quality Audits";
		$sPageTitle['edit-quality-audit.php']      = "Quality Audits";
		$sPageTitle['quality-points.php']          = "Quality Points";
		$sPageTitle['compliance-types.php']        = "Compliance Types";
		$sPageTitle['compliance-categories.php']   = "Compliance Categories";
		$sPageTitle['compliance-questions.php']    = "Compliance Questions";
		$sPageTitle['compliance-audits.php']       = "Compliance Audits";
		$sPageTitle['edit-compliance-audit.php']   = "Compliance Audits";
		$sPageTitle['production-categories.php']   = "Production Categories";
		$sPageTitle['production-questions.php']    = "Production Questions";
		$sPageTitle['production-audits.php']       = "Production Audits";
		$sPageTitle['edit-production-audit.php']   = "Production Audits";
		$sPageTitle['certifications.php']          = "Certifications";
		$sPageTitle['vendor-certifications.php']   = "Vendor Certifications";
		$sPageTitle['audit-pictures.php']          = "Audit Pictures";
		$sPageTitle['tnc-sections.php']            = "CRC Sections";
		$sPageTitle['tnc-categories.php']          = "CRC Categories";
		$sPageTitle['tnc-points.php']              = "CRC Points";
		$sPageTitle['tnc-audits.php']              = "CRC Old Audits";
		$sPageTitle['edit-tnc-audit.php']          = "CRC Old Audits";
		$sPageTitle['crc-audits.php']              = "CRC Audits";
		$sPageTitle['edit-crc-audit.php']          = "CRC Audits"; 
		$sPageTitle['edit-crc-audit-cap.php']          = "CRC Audit CAP"; 
		$sPageTitle['departments.php']             = "CRC Departments"; 
		$sPageTitle['crc-audit-images.php']        = "CRC Audit Images"; 
		$sPageTitle['tnc-schedules.php']           = "CRC Schedules";
		$sPageTitle['edit-tnc-schedule.php']       = "CRC Schedules";
		$sPageTitle['tnc-dashboard.php']           = "CRC Dashboard"; 
		$sPageTitle['tnc-audit-caps.php']          = "CRC Audits"; 
		$sPageTitle['tnc-audit-images.php']        = "CRC Audits";
		$sPageTitle['chemical-types.php']          = "Chemical Types";
		$sPageTitle['chemical-compounds.php']      = "Chemical Compounds";
		$sPageTitle['chemical-location-types.php'] = "Chemical Location Types";
		$sPageTitle['chemical-locations.php']      = "Chemical Locations";
		$sPageTitle['chemicals-inventory.php']     = "Chemicals Inventory";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sDataEntryModule) && $sCurDir == "data")
	{
?>
		    <a href="data/">Data Entry</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']                 = "Dashboard";
		$sPageTitle['countries.php']             = "Countries";
                $sPageTitle['customers.php']             = "Customers";
		$sPageTitle['ports.php']                 = "Shipping Ports";
		$sPageTitle['country-blocks.php']        = "Country Blocks";
		$sPageTitle['categories.php']            = "Categories";
		$sPageTitle['vendors.php']               = "Vendors";
		$sPageTitle['edit-vendor.php']           = "Edit Vendor";
		$sPageTitle['suppliers.php']             = "Suppliers";
		$sPageTitle['brands.php']                = "Brands";
		$sPageTitle['edit-brand.php']            = "Brands";
		$sPageTitle['seasons.php']               = "Seasons";
		$sPageTitle['destinations.php']          = "Destinations";
		$sPageTitle['sizes.php']                 = "Sizes";
		$sPageTitle['style-categories.php']      = "Style Categories";
		$sPageTitle['styles.php']                = "Styles";
		$sPageTitle['edit-style.php']            = "Styles";
		$sPageTitle['resize-sketch-file.php']    = "Styles";
		$sPageTitle['purchase-orders.php']       = "Purchase Orders";
		$sPageTitle['add-purchase-order.php']    = "Purchase Orders";
		$sPageTitle['edit-purchase-order.php']   = "Purchase Orders";
		$sPageTitle['delete-purchase-order.php'] = "Purchase Orders";
		$sPageTitle['request-etd-revision.php']  = "Purchase Orders";
		$sPageTitle['pos-import.php']            = "POs Import";
		$sPageTitle['import-pos-csv.php']        = "POs Import";
		$sPageTitle['posdd.php']                 = "POSDD";
		$sPageTitle['po-commission.php']         = "PO Commission";
		$sPageTitle['forecasts.php']             = "Forecasts";
		$sPageTitle['revised-forecasts.php']     = "Revised Forecasts";
		$sPageTitle['vendor-profiles.php']       = "Vendor Profiles";
		$sPageTitle['vendor-pictures.php']       = "Vendor Pictures";
		$sPageTitle['vendor-albums.php']         = "Vendor Picture Albums";
		$sPageTitle['fabric-library.php']        = "Fabric Library";
		$sPageTitle['fabric-categories.php']     = "Fabric Categories";
		$sPageTitle['library.php']               = "Library";
		$sPageTitle['programs.php']              = "Programs";
		$sPageTitle['blog.php']                  = "Blog";
		$sPageTitle['add-blog-post.php']         = "Add Blog Post";
		$sPageTitle['edit-blog-post.php']        = "Edit Blog Post";
		$sPageTitle['blog-categories.php']       = "Blog Categories";
		$sPageTitle['etd-revision-requests.php'] = "ETD Revision Requests";
		$sPageTitle['edit-etd-revision-request.php'] = "ETD Revision Requests";
		$sPageTitle['etd-revision-reasons.php']  = "ETD Revision Reasons";
		$sPageTitle['etd-managers.php']          = "ETD Managers";
		$sPageTitle['brand-offices.php']         = "Brand Offices";
		$sPageTitle['videos.php']                = "Videos";
		$sPageTitle['products.php']              = "Flipbook Products";
		$sPageTitle['edit-product.php']          = "Flipbook Products";
		$sPageTitle['flipbooks.php']             = "Flipbooks";
		$sPageTitle['edit-flipbook.php']         = "Flipbooks";
		$sPageTitle['couriers.php']         	 = "Couriers";
		$sPageTitle['edit-courier.php']       	 = "Couriers";
		$sPageTitle['production-stages.php']     = "Production Stages";
		$sPageTitle['escrow-orders.php']         = "Escrow Orders";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sShippingModule) && $sCurDir == "shipping")
	{
?>
		    <a href="shipping/">Shipping</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']                     = "Dashboard";
		$sPageTitle['terms-of-delivery.php']         = "Terms of Delivery";
		$sPageTitle['pre-shipment-advice.php']       = "Pre-Shipment Advice";
		$sPageTitle['edit-pre-shipment-detail.php']  = "Pre-Shipment Advice";
		$sPageTitle['post-shipment-advice.php']      = "Post-Shipment Advice";
		$sPageTitle['edit-post-shipment-detail.php'] = "Post-Shipment Advice";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sQuondaModule) && $sCurDir == "quonda")
	{
?>
		    <a href="quonda/">Quonda</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']                 = "Dashboard";
		$sPageTitle['quonda-graphs.php']         = "Quonda Graphs";
                $sPageTitle['qa-checklist.php']          = "QA Checklist";
                $sPageTitle['bookings.php']              = "Bookings";
                $sPageTitle['defects-nature.php']        = "TNC Defects Nature";
                $sPageTitle['aql.php']                   = "AQL";
		$sPageTitle['statements.php']            = "Statements";
		$sPageTitle['questions.php']             = "Questionnaire";
		$sPageTitle['sections.php']              = "Auditor App Sections";
		$sPageTitle['audit-types.php']           = "Audit Types";
		$sPageTitle['packaging-defects.php']     = "Packaging Defects";
		$sPageTitle['auditors-correlation.php']  = "Auditors Correlation";
		$sPageTitle['auditors-productivity.php'] = "Auditors Productivity";
		$sPageTitle['auditor-groups.php']        = "Auditor Groups";
		$sPageTitle['lines.php']                 = "Lines";
		$sPageTitle['line-types.php']            = "Line Types";
		$sPageTitle['floors.php']                = "Floors";
		$sPageTitle['audit-codes.php']           = "Audit Codes";
                $sPageTitle['qa-sections.php']           = "QA Sections";
		$sPageTitle['qa-reports.php']            = "QA Reports";
		$sPageTitle['edit-qa-report.php']        = "QA Reports";
		$sPageTitle['send-qa-report.php']        = "QA Reports";
		$sPageTitle['qa-report-images.php']      = "QA Reports";
		$sPageTitle['quonda-reports.php']        = "Quonda Reports";
		$sPageTitle['reports.php']               = "Report Types";
		$sPageTitle['defect-types.php']          = "Defect Types";
		$sPageTitle['defect-codes.php']          = "Defect Codes";
		$sPageTitle['audit-stages.php']          = "Audit Stages";
		$sPageTitle['defect-areas.php']          = "Defect Areas";
		$sPageTitle['defect-codes-gf.php']       = "Defect Codes (GF)";
		$sPageTitle['defects-catalogue.php']     = "Defects Catalogue";
		$sPageTitle['dashboard.php']             = "Dashboard";
		$sPageTitle['audit-progress.php']        = "Audit Live View";
		$sPageTitle['po-progress.php']           = "PO Progress Analysis";
		$sPageTitle['qa-reviews.php']            = "QA Reviews";
		$sPageTitle['csc-audits.php']            = "CSC Audits";
		$sPageTitle['edit-csc-audit.php']        = "CSC Audits";
		$sPageTitle['reports-comparison.php']    = "Reports Comparison";
		$sPageTitle['emails.php']                = "QA Emails";
		$sPageTitle['auditors-swarm.php']        = "Auditors Swarm";
		$sPageTitle['schedule.php']              = "Audit Schedules";
		$sPageTitle['audit-schedules.php']       = "Audit Schedules";
		$sPageTitle['vendor-reports.php']        = "Vendor Reports";
		$sPageTitle['qa-reports-analysis.php']   = "QA Reports Data Analysis";
		$sPageTitle['signatures.php']            = "Signatures";
		$sPageTitle['edit-signature.php']        = "Signatures";
		$sPageTitle['qa-commission.php']         = "QA Commission";
		$sPageTitle['guidelines.php']            = "Guidelines";
		$sPageTitle['add-guideline.php']         = "Add Guideline";
		$sPageTitle['edit-guideline.php']        = "Edit Guideline";
		$sPageTitle['sisense.php']               = "Sisense Dashboard";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sReportsModule) && $sCurDir == "reports")
	{
?>
		    <a href="reports/">Reports</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']                  = "Dashboard";
		$sPageTitle['shipment-summary.php']       = "Shipment Summary";
                $sPageTitle['shipping-report-dkc.php']    = "Shipping Report DKC";
                $sPageTitle['oql-report.php']             = "OQL Report";
                $sPageTitle['triumph-report.php']         = "Triumph Report";
                $sPageTitle['oql-tracking-report.php']    = "TNC OQL Tracking";
		$sPageTitle['quality-report.php']         = "Quality Report";
		$sPageTitle['commission-report.php']      = "Commission Report";
		$sPageTitle['invoice-report.php']         = "Invoice Report";
		$sPageTitle['shipping-report.php']        = "Shipping Report";
		$sPageTitle['measurements-report.php']    = "Measurements Report";
		$sPageTitle['inspection-certificate.php'] = "Inspection Certificate";
		$sPageTitle['ss-commission-report.php']   = "Sales Ssamples Commission Report";
		$sPageTitle['attendance-report.php']      = "Attendance Report";
		$sPageTitle['portal-usuage-report.php']   = "Portal Usuage Report";
		$sPageTitle['current-standing.php']       = "Current Standing";
		$sPageTitle['forecast-report.php']        = "Forecast Report";
		$sPageTitle['vsr-report.php']             = "VSR Report";
		$sPageTitle['kpis-report.php']            = "KPI's Report";
		$sPageTitle['fob-value.php']              = "FOB Value";
		$sPageTitle['invoice-generator.php']      = "Invoice Generator";
		$sPageTitle['mgf-report.php']             = "MGF Report";
                $sPageTitle['levis-report.php']           = "Levis Report";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sSamplingModule) && $sCurDir == "sampling")
	{
?>
		    <a href="sampling/">Protoware</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']                 = "Dashboard";
		$sPageTitle['sales-samples.php']          = "Sales Samples";
		$sPageTitle['add-sales-sample.php']       = "Sales Samples";
		$sPageTitle['edit-sales-sample.php']      = "Sales Samples";
		$sPageTitle['ss-commission.php']          = "SS Commission";
		$sPageTitle['types.php']                  = "Types";
		$sPageTitle['sizes.php']                  = "Sizes";
		$sPageTitle['emails.php']                 = "Sampling Emails";
		$sPageTitle['send-merchandising-report.php']   = "Send Sampling Report";
		$sPageTitle['categories.php']             = "Categories";
		$sPageTitle['washes.php']                 = "Washes";
		$sPageTitle['measurement-points.php']     = "Measurement Points";
		$sPageTitle['merchandisings.php']         = "Merchandising";
		$sPageTitle['add-merchandising.php']      = "Merchandising";
		$sPageTitle['edit-merchandising.php']     = "Merchandising";
		$sPageTitle['measurement-specs.php']      = "Requisition & Report";
		$sPageTitle['add-measurement-specs.php']  = "Requisition & Report";
		$sPageTitle['edit-measurement-specs.php'] = "Requisition & Report";
		$sPageTitle['measurement-images.php']     = "Requisition & Report";
		$sPageTitle['sampling-reports.php']       = "Sampling Reports";
		$sPageTitle['reports.php']                = "Report Types";
		$sPageTitle['defect-types.php']           = "Defect Types";
		$sPageTitle['defect-codes.php']           = "Defect Codes";
		$sPageTitle['defect-areas.php']           = "Defect Areas";
		$sPageTitle['lab-dips.php']               = "Lab Dips";
		$sPageTitle['lab-dip-colors.php']         = "Lab Dip Colors";
		$sPageTitle['fabric.php']                 = "Fabric";
		$sPageTitle['brand-rms.php']              = "Brand RMS";
		$sPageTitle['style-specs.php']            = "Style Specs";
		$sPageTitle['select-style-specs.php']     = "Style Specs";
		$sPageTitle['edit-style-specs.php']       = "Style Specs";
		$sPageTitle['styles.php']                 = "Styles";
		$sPageTitle['style-details.php']          = "Styles";
		$sPageTitle['360-view.php']               = "Styles";
		$sPageTitle['style-audits.php']           = "Styles";
		$sPageTitle['style-graph.php']            = "Styles";
		$sPageTitle['style-comments.php']         = "Style Comments";
		$sPageTitle['add-style-comment.php']      = "Style Comments";
		$sPageTitle['edit-style-comment.php']     = "Style Comments";
		$sPageTitle['inline-audits.php']          = "Inline Audits";
		$sPageTitle['edit-inline-audit.php']      = "Inline Audits";
		$sPageTitle['inline-audit-images.php']    = "Inline Audits";
		$sPageTitle['360-images.php']             = "360 Images";
		$sPageTitle['dashboard.php']              = "Dashboard";
		$sPageTitle['dashboard-styles.php']       = "Dashboard";
		$sPageTitle['360.php']                    = "360 Check";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sHrModule) && $sCurDir == "hr")
	{
?>
		    <a href="hr/">HR</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']                              = "Dashboard";
		$sPageTitle['employees.php']                          = "Employees";
		$sPageTitle['edit-employee-evolutionary-profile.php'] = "Employee Evolutionary Profile";
		$sPageTitle['edit-employee.php']                      = "Employee Profile";
		$sPageTitle['attendance.php']                         = "Attendance";
		$sPageTitle['leaves.php']                             = "Leaves";
		$sPageTitle['holidays.php']                           = "Holidays";
		$sPageTitle['departments.php']                        = "Departments";
		$sPageTitle['designations.php']                       = "Designations";
		$sPageTitle['visit-locations.php']                    = "Visit Locations";
		$sPageTitle['location-distances.php']                 = "Location Distances";
		$sPageTitle['leave-types.php']                        = "Leave Types";
		$sPageTitle['visits.php']                             = "Visits";
		$sPageTitle['hrn.php']                                = "Human Resource Navigator";
		$sPageTitle['view-employee-stats.php']                = "Human Resource Navigator";
		$sPageTitle['salaries.php']                           = "Salaries";
		$sPageTitle['board.php']                              = "HR Board";
		$sPageTitle['messages.php']                           = "HR Board";
		$sPageTitle['offices.php']                            = "Matrix Offices";
		$sPageTitle['sms-attendance.php']                     = "SMS Attendance";
		$sPageTitle['calendar.php']                           = "Calendar";
		$sPageTitle['brand-placements.php']                   = "Brand Placements";
		$sPageTitle['surveys.php']                            = "Surveys";
		$sPageTitle['survey-manager.php']                     = "Surveys";
		$sPageTitle['survey-feedback.php']                    = "Surveys";
		$sPageTitle['activities.php']                         = "Activities";
		$sPageTitle['user-activities.php']                    = "User Activities";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sYarnModule) && $sCurDir == "yarn")
	{
?>
		    <a href="yarn/">Brandix</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']          = "Dashboard";
		$sPageTitle['cotton-rates.php']   = "Cotton Rates";
		$sPageTitle['yarn-rates.php']     = "Yarn Rates";
		$sPageTitle['trends.php']         = "Trends";
		$sPageTitle['specs.php']          = "Specs";
		$sPageTitle['loom-types.php']     = "Loom Types";
		$sPageTitle['looms.php']          = "Looms";
		$sPageTitle['inquiries.php']      = "Inquiries";
		$sPageTitle['loom-plan.php']      = "Loom Plan";
		$sPageTitle['edit-loom-plan.php'] = "Loom Plan";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sDropboxModule) && $sCurDir == "dropbox")
	{
?>
		    <a href="dropbox/">Dropbox</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['dropbox.php'] = "Dropbox";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sLibsModule) && $sCurDir == "libs")
	{
?>
		    <a href="libs/">Libs</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']           = "Dashboard";
		$sPageTitle['vendor-profiles.php'] = "Vendor Profiles";
		$sPageTitle['vendor-profile.php']  = "Vendor Profiles";
		$sPageTitle['fabric-library.php']  = "Fabric Library";
		$sPageTitle['library.php']         = "Library";
		$sPageTitle['cotton-futures.php']  = "Cotton Futures";
		$sPageTitle['videos.php']          = "Videos";
		$sPageTitle['extensions.php']      = "Extensions";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else if (@in_array($sPage, $sAdminModule) && $sCurDir == "admin")
	{
?>
		    <a href="admin/">Admin</a>
		    <img src="images/icons/trail-arrow.jpg" alt="" title="" />
<?
		$sPageTitle = array( );

		$sPageTitle['index.php']             = "Dashboard";
		$sPageTitle['users.php']             = "Users";
		$sPageTitle['clients.php']           = "Clients";
		$sPageTitle['user-types.php']        = "User Types";
		$sPageTitle['edit-user.php']         = "Users";
		$sPageTitle['backup-restore.php']    = "Backup / Restore";
		$sPageTitle['web-messages.php']      = "Web Messages";
		$sPageTitle['view-web-message.php']  = "Web Messages";
		$sPageTitle['reply-web-message.php'] = "Web Messages";
?>
		    <b><?= $sPageTitle[$sPage] ?></b>
<?
	}

	else
	{
?>
		    <b>Home</b>
<?
	}
?>
		  </div>
	    </div>
