<?php

// Queries
$invoicedbutnoteam = "
				SELECT crmid,
				cf_612 as 'sales_progress',
				cf_615 as 'invoice_number',
				cf_627 as 'install_team',
				contact_no
				FROM vtiger_crmentity, vtiger_contactscf, vtiger_contactdetails
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND (cf_615 NOT LIKE '' AND cf_615 NOT LIKE '%can%')
				AND cf_612 LIKE 'Sale'
				AND (cf_627 LIKE '' OR cf_627 LIKE 'Unknown')

";
$teamedbutnoinvoice = "
				SELECT crmid,
				cf_612 as 'sales_progress',
				cf_615 as 'invoice_number',
				cf_627 as 'install_team',
				contact_no
				FROM vtiger_crmentity, vtiger_contactscf, vtiger_contactdetails
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND (cf_615 LIKE '' OR cf_615 IS NULL)
				AND cf_612 LIKE 'Sale'
				AND cf_627 NOT LIKE ''
				AND cf_627 NOT LIKE 'Unknown'
";



$noinvoice = "
				SELECT crmid,
				cf_612 as 'sales_progress',
				cf_615 as 'invoice_number',
				contact_no				
				FROM vtiger_crmentity, vtiger_contactscf, vtiger_contactdetails
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				AND (cf_615 LIKE '' OR cf_615 IS NULL)
				";
$invoicebutwrongprogress = "
				SELECT crmid,
				cf_612 as 'sales_progress',
				cf_615 as 'invoice_number',
				contact_no				
				FROM vtiger_crmentity, vtiger_contactscf, vtiger_contactdetails
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND (cf_615 NOT LIKE '' AND cf_615 NOT LIKE '%can%')
				AND cf_612 NOT LIKE 'Sale'
				";
				
				
				
				
				
				
				
$q = "SELECT crmid
				FROM vtiger_crmentity, vtiger_contactscf, vtiger_contactaddress
				WHERE crmid = vtiger_contactscf.contactid
				AND crmid = vtiger_contactaddress.contactaddressid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				";
$qn = "SELECT crmid
				FROM vtiger_crmentity, vtiger_contactscf, vtiger_contactaddress
				WHERE crmid = vtiger_contactscf.contactid
				AND crmid = vtiger_contactaddress.contactaddressid
				AND deleted = 0
				";
$q1 = "$q
				AND (mailingstreet LIKE concat('% ', mailingcity, ' %')
				OR mailingstreet LIKE concat('% ', mailingstate, ' %')
				OR mailingstreet LIKE concat('% ', mailingzip, ' %'))
				AND mailingstreet NOT REGEXP '[0-9a-zA-Z]* [a-zA-Z]* [a-zA-Z]*'";
$q2 = "$q
				AND (mailingstate != 'VIC' AND mailingstate != 'QLD' AND mailingstate != 'NSW')";
			
$q3 = "$q
				AND (mailingcity LIKE 'MELB%' OR mailingcity LIKE 'BRIS%')";
$q4 = "$q
				AND (
							mailingstreet LIKE '%Post%' 
							OR mailingstreet LIKE '%box%'
							OR mailingstreet LIKE '%install%'
							)
				AND mailingstreet NOT LIKE '%postle%'";
$q5 = "$q
			AND (mailingstreet LIKE '%,' OR mailingstreet LIKE '%, ' OR mailingcity LIKE '%,')";
$q6 = "$q
				AND (cf_636 IS NOT NULL OR cf_636 != '')
				AND (cf_650 IS NULL OR cf_650 = '')
				AND cf_625 <= '$today'";
$q7 = "SELECT crmid
				FROM vtiger_crmentity, vtiger_contactscf
				WHERE crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_646 = 1
				AND cf_619 LIKE 'Yes'
				AND cf_612 NOT LIKE 'Sale'";
$q8 = "$qn
				AND cf_648 LIKE  'Yes'
				AND (cf_659 IS NULL OR cf_660 IS NULL OR cf_661 IS NULL)";
$q9 = "$qn
				AND cf_646 = 1
				AND (cf_659 IS NULL OR cf_660 IS NULL OR cf_661 IS NULL)";

				
				?>