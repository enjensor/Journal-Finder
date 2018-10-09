<?php

/////////////////////////////////////////////////////////// Credits
//
//
//	ERA Journal Identification Toolkit
//	Digital Humanities Research Group
//  School of Humanities and Communication Arts
//  University of Western Sydney
//
//	Procedural Scripting: PHP | MySQL | JQuery
//
//	FOR ALL ENQUIRIES ABOUT CODE
//
//	Who:	Dr Jason Ensor
//	Email: 	j.ensor@uws.edu.au | jasondensor@gmail.com
//	Mobile: 0419 674 770
//
//  DATA SOURCES
//
//  ARC ERA 2010 Journals List | http://www.arc.gov.au/era/era_2010/archive/era_journal_list.htm
//  ARC ERA 2012 Journals List | http://www.arc.gov.au/era/era_2012/era_journal_list.htm
//  ARC ERA 2015 Draft Journals List | http://www.arc.gov.au/era/current_consult.htm
//  ARC ERA 2015 Draft Conferences List | http://www.arc.gov.au/era/current_consult.htm
//  ARC ERA 2015 Draft Disciplinary Matrix | http://www.arc.gov.au/era/current_consult.htm
//  DOAJ Open Access Journal Metadata | http://www.doaj.org/faq
//  Journal Metrics SNIP & SJR Historical Data | http://www.journalmetrics.com/snip.php
//  JCR Impact Factors & Citation Reports | http://admin-apps.webofknowledge.com/JCR/JCR
//  JCR Impact Factors Excel SCI | https://docs.zoho.com/sheet/published.do?rid=ulvpzac533844c7c44b6d9411894426d1ab1c
//  SCImago Journal and Country Rank | http://www.scimagojr.com/journalrank.php
//
//	DATA API
//
//	ISI Web of Knowledge | http://wokinfo.com/wok-ws-docs 
//	Elsevier | http://searchapidocs.scopus.com/
//	OAKlist | https://www.oaklist.qut.edu.au/api/
//	Sherpa/ RoMEO | http://www.sherpa.ac.uk/romeo/api.html
//
//  WEB FRAMEWORK
//
//  Bootstrap Twitter v3.1.1 | http://getbootstrap.com/
//  Font Awesome v4.0.3 | http://fortawesome.github.io/Font-Awesome/
//  Google Fonts API | http://fonts.googleapis.com
//  Modernizr v.2.6.2 | http://modernizr.com/
//  Multi-Level Push Menu v2.1.4 | http://multi-level-push-menu.make.rs/
//  JQuery v.1.11.0 | http://jquery.com/download/
//	JQuery JQPlot v.1.0.8 | http://www.jqplot.com/
//	JQuery UI v.10.4 | https://jqueryui.com/
//
//
/////////////////////////////////////////////////////////// Vars

	include("config.php");
	include("era.dbconnect.php");

/////////////////////////////////////////////////////////// Do all journals Snips from data_snip_scopus table and create 2014_journals_snips table

	$doJournalSnipsScopus = "n";
		
/////////////////////////////////////////////////////////// Clone journal list in journals_snips table with full history of SNIP for easy annual FoR SNIP totals	
//	
//	$doJournalSnips="n";
//	
/////////////////////////////////////////////////////////// Populate 2014 journals table with Elsevier flag
	
	$do_elsevier="n";
	
/////////////////////////////////////////////////////////// Populate 2014 journals table with ABDC values
	
	$do_abdc = "n";
	
/////////////////////////////////////////////////////////// Clear from 2014_journals jcr category, ranking and quartile values	
	
	$do_clear_jcr="y";

/////////////////////////////////////////////////////////// Populate sci 2014_journals table with jcr category, ranking and quartile values	
	
	$do_jcr_ssci="y";
	
/////////////////////////////////////////////////////////// Populate ssci 2014_journals table with jcr category, ranking and quartile values	
	
	$do_jcr_sci="y";
	
/////////////////////////////////////////////////////////// Populate 2012 ERA table with institution names	
//
//	$createERAResults2012="n";
//	
/////////////////////////////////////////////////////////// Create 2010 ERA table and populate from previous round data	
//
//	$createERAResults2010="n";
//	
/////////////////////////////////////////////////////////// Populate 2014_journals table with impact factor and article influence score	
//	
//	$doIF="n";
//
/////////////////////////////////////////////////////////// Populate 2014_journals table with OA record id

	$doOA="n";
	
/////////////////////////////////////////////////////////// Standardise ISBNs in data_scopus table	
//	
//	$doISBN_scopus="n";
//	
/////////////////////////////////////////////////////////// Standardise ISBNs in data_snip_scopus tables

	$doISBNScopusSnips = "n";
	
/////////////////////////////////////////////////////////// Standardise ISBNs in data_elsevier table	
//	
//	$doISBN_elsevier="n";
//	
/////////////////////////////////////////////////////////// Standardise ISBNs in data_snip and data_ranks tables	
//	
//	$doISBN="n";
//	
/////////////////////////////////////////////////////////// Fix FoRs

	$fixFors="n";
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with flag that compares current journal list against SJR and SNIP for updated values	
//	
//	$doSnip="n";
//
/////////////////////////////////////////////////////////// Compare current journal list against SJR and SNIP for updated values from data_snip_scopus table
	
	$doSnipScopus = "n";
			
/////////////////////////////////////////////////////////// Compare current journal list against 2010 SJR and SNIP values from data_snip_scopus table
//	
//	$doSnipOldScopus = "n";
//		
/////////////////////////////////////////////////////////// Populate 2014_journals table with flag that compares current journal list against 2010 SJR and SNIP values	
//	
//	$doSnipOld="n";
//	
/////////////////////////////////////////////////////////// Populate 2014_journals table with flag that compares current journal list against 2010 for depreciated rank	
	
	$doRank="n";
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with flag that compares current journal list against old for new titles	
	
	$doNewTitle="n";
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with flag that compares current journal list against old for new FoR assignment	
	
	$doNew="n";	
	
/////////////////////////////////////////////////////////// Fix SCImago ISSNs
	
	$doupdateSCImago = "n";
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with flag that indicates title is in SCImago database (for cross-linking)

	$doSCImago="n";
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with 5 year impact factor	
	
	$do5YrIF="y";
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with Impact Factor Score from JCR tables
	
	$doIF_JCR="y";
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Begin
	
	echo "start ...<br />";
	
/////////////////////////////////////////////////////////// Do all journals Snips from data_snip_scopus table

	if(($doJournalSnipsScopus == "y")) {
	
		$parse = "Clone journal list with full history of SNIP for easy annual FoR SNIP totals";
		$query = "TRUNCATE TABLE 2014_journals_snips;";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7, FoR1, FoR2, FoR3 FROM 2014_journals_draft ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$snip = "";
			$queryD = "SELECT * FROM data_snip_scopus WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$queryD = $queryD."ORDER BY 2013_SNIP DESC LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip = $rowD[1];
				if(($snip != "")) {
					$queryDa = "INSERT INTO 2014_journals_snips VALUES (\"0\", \"$row[0]\", ";
					$queryDa .= "\"$row[1]\", \"$row[2]\", \"$row[3]\", \"$row[4]\", \"$row[5]\", \"$row[6]\", \"$row[7]\", ";
					$queryDa .= "\"$row[8]\", \"$row[9]\", \"$row[10]\", ";
					$queryDa .= "\"$rowD[8]\", \"$rowD[11]\", \"$rowD[14]\", \"$rowD[17]\", \"$rowD[20]\", \"$rowD[23]\", \"$rowD[26]\", ";
					$queryDa .= "\"$rowD[29]\", \"$rowD[32]\", \"$rowD[35]\", \"$rowD[38]\", \"$rowD[41]\", \"$rowD[44]\", \"$rowD[47]\", ";
					$queryDa .= "\"$rowD[50]\", \"$rowD[1]\")";
					$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
					$x++;					
				}
			}
			$r++;
		}
	}	
	
/////////////////////////////////////////////////////////// Do All Journals Snips

	if(($doJournalSnips == "y")) {
	
		$parse = "Clone journal list with full history of SNIP for easy annual FoR SNIP totals";
		$query = "TRUNCATE TABLE 2014_journals_snips;";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7, FoR1, FoR2, FoR3 FROM 2014_journals_draft ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$snip = "";
			$queryD = "SELECT * FROM data_snip WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$queryD = $queryD."LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip = $rowD[1];
				if(($snip != "")) {
					$queryDa = "INSERT INTO 2014_journals_snips VALUES (\"0\", \"$row[0]\", ";
					$queryDa .= "\"$row[1]\", \"$row[2]\", \"$row[3]\", \"$row[4]\", \"$row[5]\", \"$row[6]\", \"$row[7]\", ";
					$queryDa .= "\"$row[8]\", \"$row[9]\", \"$row[10]\", ";
					$queryDa .= "\"$rowD[8]\", \"$rowD[10]\", \"$rowD[12]\", \"$rowD[14]\", \"$rowD[16]\", \"$rowD[18]\", \"$rowD[20]\", ";
					$queryDa .= "\"$rowD[22]\", \"$rowD[24]\", \"$rowD[26]\", \"$rowD[28]\", \"$rowD[30]\", \"$rowD[32]\", \"$rowD[34]\", ";
					$queryDa .= "\"$rowD[1]\")";
					$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
					$x++;					
				}
			}
			$r++;
		}
	}
	
/////////////////////////////////////////////////////////// Do Elsevier

	if(($do_elsevier == "y")) {
	
		$parse="Populate 2014 journals table with Elsevier flag";
	
		$query = "UPDATE 2014_journals_draft SET Elsevier = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
	
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$queryD = "SELECT ISSN FROM data_elsevier WHERE ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$jcr[0] = "$rowD[0]";
			}
			if(($jcr[0] !="")) {
				$r++;
				$queryD = "UPDATE 2014_journals_draft SET Elsevier = \"$jcr[0]\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}	
	}
	
/////////////////////////////////////////////////////////// Do ABDC

	if(($do_abdc == "y")) {
		
		$parse="Populate 2014 journals table with ABDC values";
		
		$query = "UPDATE 2014_journals_draft SET ABDC_Rank = \"\", ABDC_FoR = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$jcr[1] = "";
			$queryD = "SELECT ABDC_Rank, ABDC_FoR FROM data_abdc WHERE ISSN_Print = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$jcr[0] = "$rowD[0]";
				$jcr[1] = "$rowD[1]";
			}
			if(($jcr[0] !="")) {
				$r++;
				if(strlen($jcr[1]) == 6) {
					$jcr[1] = substr("$jcr[1]", 0, -2);
				}
				$queryD = "UPDATE 2014_journals_draft SET ABDC_Rank = \"$jcr[0]\", ABDC_FoR = \"$jcr[1]\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}
		
	}
	
/////////////////////////////////////////////////////////// Clear jcr category, ranking and quartile values
	
	if(($do_clear_jcr == "y")) {
		
		$query = "UPDATE 2014_journals_draft SET JCR_Cat = \"\", JCR_Rank = \"\", JCR_Quartile = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
	}
	
/////////////////////////////////////////////////////////// Populate ssci 2014_journals table with jcr category, ranking and quartile values
	
	if(($do_jcr_ssci == "y")) {
		
		$parse="Populate SSCI 2014 journals table with JCR values";
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$jcr[1] = "";
			$jcr[2] = "";
			$jcr[3] = "";
			$queryD = "SELECT CATEGORY_DESCRIPTION, CATEGORY_RANKING, QUARTILE_RANK, IMPACT_FACTOR FROM data_jcr_ssci WHERE ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$jcr[0] = "$rowD[0]";
				$jcr[1] = "$rowD[1]";
				$jcr[2] = "$rowD[2]";
				$jcr[3] = "$rowD[3]";
			}
			if(($jcr[0] !="")) {
				$r++;
				$queryDE = "UPDATE 2014_journals_draft SET JCR_Cat = CONCAT(JCR_Cat,\"$jcr[0]; \"), JCR_Rank = CONCAT(JCR_Rank,\"$jcr[1]; \"), JCR_Quartile = CONCAT(JCR_Quartile,\"$jcr[2]; \"), IF_2012 = \"$jcr[3]\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Populate sci 2014_journals table with jcr category, ranking and quartile values

	if(($do_jcr_sci == "y")) {
		
		$parse="Populate SCI 2014 journals table with JCR values";
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$jcr[1] = "";
			$jcr[2] = "";
			$jcr[3] = "";
			$queryD = "SELECT CATEGORY_DESCRIPTION, CATEGORY_RANKING, QUARTILE_RANK, IMPACT_FACTOR FROM data_jcr_sci WHERE ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$jcr[0] = "$rowD[0]";
				$jcr[1] = "$rowD[1]";
				$jcr[2] = "$rowD[2]";
				$jcr[3] = "$rowD[3]";
			}
			if(($jcr[0] !="")) {
				$r++;
				$queryDE = "UPDATE 2014_journals_draft SET JCR_Cat = CONCAT(JCR_Cat,\"$jcr[0]; \"), JCR_Rank = CONCAT(JCR_Rank,\"$jcr[1]; \"), JCR_Quartile = CONCAT(JCR_Quartile,\"$jcr[2]; \"), IF_2012 = \"$jcr[3]\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Populate 2012 ERA table with institution names

	if(($createERAResults2012 == "y")) {
		
		$parse="Create Past ERA Results Table 2012";
		
		$unis = array("Australian_Catholic_University", "Australian_National_University", "Batchelor_Institute_of_Indigenous_Tertiary_Education", 
		"Bond_University", "Central_Queensland_University", "Charles_Darwin_University", "Charles_Stuart_University", "Curtin_University_of_Technology", 
		"Deakin_University", "Edith_Cowan_University", "Flinders_University", "Griffith_University", "James_Cook_University", "La_Trobe_University", 
		"Macquarie_University", "Melbourne_College_of_Divinity", "Monash_University", "Murdoch_University", "Queensland_University_of_Technology", 
		"RMIT_University", "Southern_Cross_University", "Swinburne_University_of_Technology", "University_of_Adelaide", "University_of_Ballarat", 
		"University_of_Canberra", "University_of_Melbourne", "University_of_New_England", "University_of_New_South_Wales", "University_of_Newcastle", 
		"University_of_Notre_Dame_Australia", "University_of_Queensland", "University_of_South_Australia", "University_of_Southern_Queensland", 
		"University_of_Sydney", "University_of_Tasmania", "University_of_Technology,_Sydney", "University_of_the_Sunshine_Coast", 
		"University_of_Western_Australia", "University_of_Western_Sydney", "University_of_Wollongong", "Victoria_University");
		
		foreach($unis AS $u) {
			$queryD = "INSERT INTO 2012_era VALUES (\"0\", \"$u\"";
			for($x=1;$x<180;$x++) {
				$queryD .= ", \"\"";	
			}
			$queryD .= ");";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Create 2010 ERA table and populate from previous round data

	if(($createERAResults2010 == "y")) {
		
		$parse="Create Past ERA Results Table 2010";
		
		$query = "DROP TABLE IF EXISTS 2010_era;\n";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "CREATE TABLE IF NOT EXISTS 2010_era (\n";
		$query .= "ID int(11) NOT NULL AUTO_INCREMENT,\n";
		$query .= "Institution varchar(250) DEFAULT NULL,\n";
		
		$queryD = "SELECT ForCode, ForName FROM disciplinematrix WHERE ForCode != \"\" ORDER BY ForCode ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			$new = $rowD[0]." ".$rowD[1];
			$new = preg_replace("/ /i","_","$new");
			$new = preg_replace("/,/i","","$new");
			$new = preg_replace("/\./i","","$new");
			$new = preg_replace("/\_\(incl_Structural\)/i","","$new");
			$query .= "$new varchar(250) DEFAULT NULL,\n"; 	
		}
		
		$query .= "PRIMARY KEY (ID)\n";
		$query .= ") ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$unis = array("Australian_Catholic_University", "Australian_National_University", "Batchelor_Institute_of_Indigenous_Tertiary_Education", 
		"Bond_University", "Central_Queensland_University", "Charles_Darwin_University", "Charles_Stuart_University", "Curtin_University_of_Technology", 
		"Deakin_University", "Edith_Cowan_University", "Flinders_University", "Griffith_University", "James_Cook_University", "La_Trobe_University", 
		"Macquarie_University", "Melbourne_College_of_Divinity", "Monash_University", "Murdoch_University", "Queensland_University_of_Technology", 
		"RMIT_University", "Southern_Cross_University", "Swinburne_University_of_Technology", "University_of_Adelaide", "University_of_Ballarat", 
		"University_of_Canberra", "University_of_Melbourne", "University_of_New_England", "University_of_New_South_Wales", "University_of_Newcastle", 
		"University_of_Notre_Dame_Australia", "University_of_Queensland", "University_of_South_Australia", "University_of_Southern_Queensland", 
		"University_of_Sydney", "University_of_Tasmania", "University_of_Technology,_Sydney", "University_of_the_Sunshine_Coast", 
		"University_of_Western_Australia", "University_of_Western_Sydney", "University_of_Wollongong", "Victoria_University");
		
		foreach($unis AS $u) {
			$queryD = "INSERT INTO 2010_era VALUES (\"0\", \"$u\"";
			for($x=1;$x<180;$x++) {
				$queryD .= ", \"\"";	
			}
			$queryD .= ");";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		}
		
		foreach($unis AS $u) {
			$query = "SELECT $u, ForCode, FoRName FROM 2010_era_results ORDER BY ForCode ASC";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				$f = strlen($row[1]);
				if($f == 1 OR $f == 3) {
					$forcode = "0".$row[1];
				} else {
					$forcode = $row[1];
				}
				$new = $forcode." ".$row[2];
				$new = preg_replace("/ /i","_","$new");
				$new = preg_replace("/,/i","","$new");
				$new = preg_replace("/\./i","","$new");
				$new = preg_replace("/\_\(incl_Structural\)/i","","$new");
				if($new == "07_Agricultural_and_Veterinary_Sciences") {
					$new = "07_Agriculture_and_Veterinary_Sciences";	
				}
				if($new == "1702_Cognitive_Sciences") {
					$new = "1702_Cognitive_Science";	
				}
				if($new == "1799_Other_Psychology_and_Cognitive_Sciences") {
					$new = "1799_Other_Psychology_and_Cognitive_Science";	
				}
				if($new == "2099_Other_Language_Communication_and_Culture") {
					$new = "2099_Other_Language_Literature_and_Culture";	
				}
				$queryD = "UPDATE 2010_era SET $new = \"$row[0]\" WHERE Institution = \"$u\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				$r++;
			}
		}
		$x++;	
	}
	
/////////////////////////////////////////////////////////// Record impact factor and article influence score

	if(($doIF == "y")) {
		
		$parse="Impact Factor and Article Influence Score";
		
		$query = "UPDATE 2014_journals_draft SET IF_2012 = \"\", AIS_2012 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft WHERE OpenAccess =\"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			$IF="";
			$AIS="";
			$queryD = "SELECT Impact_Factor, Article_Influence_Score FROM data_impact WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$IF = "$rowD[0]";
				$AIS = "$rowD[1]";
			}
			if(($IF !="") OR ($AIS != "")) {
				$r++;
				$queryD = "UPDATE 2014_journals_draft SET IF_2012 = \"$IF\", AIS_2012 = \"$AIS\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Record OA record id

	if(($doOA == "y")) {
		
		$parse="Open Access";
		
		$query = "UPDATE 2014_journals_draft SET OpenAccess = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft WHERE OpenAccess =\"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$OA = "";
			$queryD = "SELECT ID FROM data_oa WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$OA = "$rowD[0]";
			}
			if(($OA !="")) {
				$r++;
				$queryD = "UPDATE 2014_journals_draft SET OpenAccess = \"Yes\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Standardise ISBNs in data_elsevier table

	if(($doISBN_elsevier == "y")) {
	
		$parse="ISBN_Elsevier";
		
		$query = "SELECT `ID`, `ISSN` FROM data_elsevier ORDER BY `ID` ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$x++;
			if(($row[1] != "") && ($row[1] != " ")) {
				if (strpos($row[1], '-') !== FALSE) {
					$doNothing = "y";
				} else {
					$aISBN = substr($row[1],0,4);
					$bISBN = substr($row[1],4);
					$nISBN = $aISBN."-".$bISBN;
					$queryD = "UPDATE data_elsevier SET `ISSN` = \"$nISBN\" WHERE`ID` = \"$row[0]\""; 
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$r++;
				}
			}
		}
	}
	
/////////////////////////////////////////////////////////// Standardise ISSNs in data_scopus table

	if(($doISBN_scopus == "y")) {
	
		$parse="ISBN_Scopus";
		
		$query = "SELECT `ID`, `Print-ISSN`, `E-ISSN` FROM data_scopus ORDER BY `ID` ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$x++;
			if(($row[1] != "") && ($row[1] != " ")) {
				if (strpos($row[1], '-') !== FALSE) {
					$doNothing = "y";
				} else {
					$aISBN = substr($row[1],0,4);
					$bISBN = substr($row[1],4);
					$nISBN = $aISBN."-".$bISBN;
					$queryD = "UPDATE data_scopus SET `Print-ISSN` = \"$nISBN\" WHERE`ID` = \"$row[0]\""; 
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$r++;
				}
			}
			
			if(($row[2] != "") && ($row[2] != " ")) {
				if (strpos($row[2], '-') !== FALSE) {
					$doNothing = "y";
				} else {
					$aISBN = substr($row[2],0,4);
					$bISBN = substr($row[2],4);
					$nISBN = $aISBN."-".$bISBN;
					$queryD = "UPDATE data_scopus SET `E-ISSN` = \"$nISBN\" WHERE`ID` = \"$row[0]\""; 
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$r++;
				}
			}
		}
	}
	
/////////////////////////////////////////////////////////// Standardise ISSNs in data_snip_scopus tables

	if(($doISBNScopusSnips == "y")) {	
	
		$parse="ISSN Scopus SNIP";
		
		$query = "SELECT `ID`, `Print_ISSN`, `E_ISSN` FROM data_snip_scopus ORDER BY `ID` ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$x++;
			if(($row[1] != "") && ($row[1] != " ")) {
				if (strpos($row[1], '-') !== FALSE) {
					$doNothing = "y";
				} else {
					$aISBN = substr($row[1],0,4);
					$bISBN = substr($row[1],4);
					$nISBN = $aISBN."-".$bISBN;
					$queryD = "UPDATE data_snip_scopus SET `Print_ISSN` = \"$nISBN\" WHERE`ID` = \"$row[0]\""; 
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$r++;
				}
			}
			
			if(($row[2] != "") && ($row[2] != " ")) {
				if (strpos($row[2], '-') !== FALSE) {
					$doNothing = "y";
				} else {
					$aISBN = substr($row[2],0,4);
					$bISBN = substr($row[2],4);
					$nISBN = $aISBN."-".$bISBN;
					$queryD = "UPDATE data_snip_scopus SET `E_ISSN` = \"$nISBN\" WHERE`ID` = \"$row[0]\""; 
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$r++;
				}
			}
		}	
	}
	
/////////////////////////////////////////////////////////// Standardise ISBNs in data_snip and data_ranks tables

	if(($doISBN == "y")) {
		
		$parse="ISSN";
		
		$query = "SELECT `ID`, `Print_ISSN`, `E-ISSN` FROM data_snip ORDER BY `ID` ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$x++;
			if(($row[1] != "") && ($row[1] != " ")) {
				if (strpos($row[1], '-') !== FALSE) {
					$doNothing = "y";
				} else {
					$aISBN = substr($row[1],0,4);
					$bISBN = substr($row[1],4);
					$nISBN = $aISBN."-".$bISBN;
					$queryD = "UPDATE data_snip SET `Print_ISSN` = \"$nISBN\" WHERE`ID` = \"$row[0]\""; 
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$r++;
				}
			}
			
			if(($row[2] != "") && ($row[2] != " ")) {
				if (strpos($row[2], '-') !== FALSE) {
					$doNothing = "y";
				} else {
					$aISBN = substr($row[2],0,4);
					$bISBN = substr($row[2],4);
					$nISBN = $aISBN."-".$bISBN;
					$queryD = "UPDATE data_snip SET `E-ISSN` = \"$nISBN\" WHERE`ID` = \"$row[0]\""; 
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$r++;
				}
			}
		}
		
		$query = "SELECT `ID`, `Print_ISSN` FROM data_ranks ORDER BY `ID` ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$x++;
			if(($row[1] != "") && ($row[1] != " ")) {
				if (strpos($row[1], '-') !== FALSE) {
					$doNothing = "y";
				} else {
					$aISBN = substr($row[1],0,4);
					$bISBN = substr($row[1],4);
					$nISBN = $aISBN."-".$bISBN;
					$queryD = "UPDATE data_ranks SET `Print_ISSN` = \"$nISBN\" WHERE`ID` = \"$row[0]\""; 
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$r++;
				}
			}		
		}
	}
	
/////////////////////////////////////////////////////////// Fix FoRs

	if(($fixFors == "y")) {
		
		$parse="Fix FoRs";	
		
		$query = "SELECT ID, FoR1, FoR2, FoR3, ERAID FROM 2014_journals_draft ORDER BY ID ASC "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
		
			$row[1] = ltrim($row[1], '0');
			$row[2] = ltrim($row[2], '0');
			$row[3] = ltrim($row[3], '0');
			$x++;
			if(strlen($row[1]) == "1") { $row[1] = "0".$row[1]; $r++; }
			if(strlen($row[1]) == "3") { $row[1] = "0".$row[1]; $r++; }
			if(strlen($row[2]) == "1") { $row[2] = "0".$row[2]; $r++; }
			if(strlen($row[2]) == "3") { $row[2] = "0".$row[2]; $r++; }
			if(strlen($row[3]) == "1") { $row[3] = "0".$row[3]; $r++; }
			if(strlen($row[3]) == "3") { $row[3] = "0".$row[3]; $r++; }

			$queryD = "UPDATE 2014_journals_draft SET FoR1 = \"$row[1]\", FoR2 = \"$row[2]\", FoR3 = \"$row[3]\" WHERE ID = \"$row[0]\" AND ERAID = \"$row[4]\" "; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		}
	}
	
/////////////////////////////////////////////////////////// Compare current journal list against SJR and SNIP for updated values
	
	if(($doSnip == "y")) {
		
		$parse="SNIP";
		
		$query = "UPDATE 2014_journals_final_list SET SNIP_2012 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_final_list WHERE SNIP_2012 =\"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newSnip=""; 
			$newSource="";
			
		//	for($g=0;$g<8;$g++) {
		//		$row[$g] = preg_replace("/-/i","","$row[$g]");
		//	}
		
			$queryD = "SELECT 2012_SNIP, Source_Record_ID FROM data_snip WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newSnip = "$rowD[0]";
				$newSource = "$rowD[1]";
			}
			if(($newSource=="")) {$r++;}
			$queryD = "UPDATE 2014_journals_final_list SET SNIP_2012 = \"$newSnip\", Source_Record_ID = \"$newSource\" WHERE ERAID = \"$row[0]\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Compare current journal list against SJR and SNIP for updated values from data_snip_scopus table
	
	if(($doSnipScopus == "y")) {
		
		$parse="SNIP Scopus";
		
		$query = "UPDATE 2014_journals_draft SET SNIP_2010 = \"\", SNIP_2012 = \"\", SNIP_2013 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newSnip=""; 
			$newSource="";
			$latestSnip="";
			$oldSnip="";
		
			$queryD = "SELECT 2012_SNIP, Scopus_Source_ID, 2013_SNIP, 2010_SNIP FROM data_snip_scopus WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$queryD = $queryD."ORDER BY 2013_SNIP DESC LIMIT 1 "; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newSnip = "$rowD[0]";
				$newSource = "$rowD[1]";
				$latestSnip = "$rowD[2]";
				$oldSnip = "$rowD[3]";
			}
			if(($newSource!="")) {$r++;}
			$queryD = "UPDATE 2014_journals_draft SET SNIP_2010 = \"$oldSnip\", SNIP_2012 = \"$newSnip\", Source_Record_ID = \"$newSource\", SNIP_2013 = \"$latestSnip\" WHERE ERAID = \"$row[0]\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Compare current journal list against 2010 SJR and SNIP values from data_snip_scopus table
	
	if(($doSnipOldScopus == "y")) {
		
		$parse="SNIP OLD SCOPUS";
		
		$query = "UPDATE 2014_journals_draft SET SNIP_2010 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newSnip=""; 
			$newSource="";
		
			$queryD = "SELECT 2010_SNIP, Scopus_Source_ID FROM data_snip_scopus WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newSnip = "$rowD[0]";
				$newSource = "$rowD[1]";
			}
			if(($newSource=="")) {$r++;}
			$queryD = "UPDATE 2014_journals_draft SET SNIP_2010 = \"$newSnip\" WHERE ERAID = \"$row[0]\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Compare current journal list against 2010 SJR and SNIP values
	
	if(($doSnipOld == "y")) {
		
		$parse="SNIP OLD";
		
		$query = "UPDATE 2014_journals_draft SET SNIP_2010 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft WHERE SNIP_2010 =\"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newSnip=""; 
			$newSource="";
			
		//	for($g=0;$g<8;$g++) {
		//		$row[$g] = preg_replace("/-/i","","$row[$g]");
		//	}
		
			$queryD = "SELECT 2010_SNIP, Source_Record_ID FROM data_snip WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newSnip = "$rowD[0]";
				$newSource = "$rowD[1]";
			}
			if(($newSource=="")) {$r++;}
			$queryD = "UPDATE 2014_journals_draft SET SNIP_2010 = \"$newSnip\" WHERE ERAID = \"$row[0]\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$x++;
		}
	}	
	
/////////////////////////////////////////////////////////// Compare current journal list against 2010 for depreciated rank

	if(($doRank == "y")) {
		
		$parse="Rank";
		
		$query = "UPDATE 2014_journals_draft SET Rank_2010 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID FROM 2014_journals_draft WHERE Rank_2010 =\"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newRank=""; 
			$queryD = "SELECT Rank FROM 2010_journals WHERE ERAID = \"$row[0]\" LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newRank = "$rowD[0]";
			}
			if(($newRank == "")) {
				$newRank = "";
			}
			$queryD = "UPDATE 2014_journals_draft SET Rank_2010 = \"$newRank\" WHERE ERAID = \"$row[0]\" "; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$x++;
		}
	}

/////////////////////////////////////////////////////////// Compare current journal list against old for new titles

	if(($doNewTitle == "y")) {
		
		$parse="New Titles";
		
		$query = "UPDATE 2014_journals_draft SET New_2014_Title = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID FROM 2014_journals_draft ORDER BY TITLE ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
				
			$x++;
			$newJournal="";
			$queryD = "SELECT * FROM 2011_journals WHERE ERAID = \"$row[0]\" LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newJournal = "No";
			}
			
			if(($newJournal == "")) {
				$newJournal = "Yes";
				$queryD = "UPDATE 2014_journals_draft SET New_2014_Title = \"$newJournal\" WHERE ERAID = \"$row[0]\" "; 
				$mysqli_resultBD = mysqli_query($mysqli_link, $queryD);
				$r++;
			}
		}
	}

/////////////////////////////////////////////////////////// Compare current journal list against old for new FoR assignment

	if(($doNew == "y")) {
		
		$parse="New FoRs";
		
		$query = "UPDATE 2014_journals_draft SET New_2014 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, FoR1, FoR2, FoR3, New_2014 FROM 2014_journals_draft WHERE New_2014 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newJournal=""; 
			$queryD = "SELECT * FROM 2011_journals WHERE ERAID = \"$row[0]\" AND ( FoR1=\"$row[1]\" OR FoR2=\"$row[1]\" OR FoR3=\"$row[1]\") "; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newJournal = "N$row[1],";
			}
			if(($newJournal == "")) {
				$newJournal = "Y$row[1],";
				$r++;
			}
			$queryD = "UPDATE 2014_journals_draft SET New_2014 = CONCAT(New_2014,\"$newJournal\") WHERE ERAID = \"$row[0]\" "; 
			$mysqli_resultBD = mysqli_query($mysqli_link, $queryD);

			if(($row[2] !="")) { 
			$newJournal=""; 
				$queryD = "SELECT * FROM 2011_journals WHERE ERAID = \"$row[0]\" AND ( FoR1=\"$row[2]\" OR FoR2=\"$row[2]\" OR FoR3=\"$row[2]\") "; 
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
					$newJournal = "N$row[2],";
				}
				if(($newJournal == "")) {
					$newJournal = "Y$row[2],";
					$r++;
				}
				$queryD = "UPDATE 2014_journals_draft SET New_2014 = CONCAT(New_2014,\"$newJournal\") WHERE ERAID = \"$row[0]\" "; 
				$mysqli_resultBD = mysqli_query($mysqli_link, $queryD);
			}

			if(($row[3] !="")) { 
				$newJournal=""; 
				$queryD = "SELECT * FROM 2011_journals WHERE ERAID = \"$row[0]\" AND ( FoR1=\"$row[3]\" OR FoR2=\"$row[3]\" OR FoR3=\"$row[3]\") "; 
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
					$newJournal = "N$row[3],";
				}
				if(($newJournal == "")) {
					$newJournal = "Y$row[3],";
					$r++;
				}
				$queryD = "UPDATE 2014_journals_draft SET New_2014 = CONCAT(New_2014,\"$newJournal\") WHERE ERAID = \"$row[0]\" "; 
				$mysqli_resultBD = mysqli_query($mysqli_link, $queryD);
			}

			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Fix SCImago ISSNs

	if(($doupdateSCImago == "y")) {
		
		$parse="Fix SCImago ISSNs ... ";
		$zeroes = array("0","0","00","000","0000","0000");
		
		$queryD = "SELECT Print_ISSN, ID FROM data_ranks_latest WHERE CHAR_LENGTH(Print_ISSN) > 0 AND CHAR_LENGTH(Print_ISSN) < 8 ORDER BY CHAR_LENGTH(Print_ISSN) ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$x++;
			$newSCImago = "$rowD[0]";
			if(($newSCImago != "")) {
				$rA = strlen($newSCImago);
				$rB = (8 - $rA);
				if(($rB > 0)) {
					$newSCImago = $zeroes[$rB].$newSCImago;	
					$y++;
				}
			}
			$queryE = "UPDATE data_ranks_latest SET Print_ISSN =\"$newSCImago\" WHERE ID = \"$rowD[1]\" ";
			$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
		}
	}

/////////////////////////////////////////////////////////// Populate 2014_journals table with flag that indicates title is in SCImago database (for cross-linking)

	if(($doSCImago == "y")) {
		
		$parse="Add SCImago ISSN flag";	
		$query = "UPDATE 2014_journals_draft SET SCImago = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft WHERE SCImago = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			
			for($g=1;$g<8;$g++) {
				$row[$g] = preg_replace("/-/i","","$row[$g]");
			}			
			
			$newSCImago="";
			$queryD = "SELECT Print_ISSN FROM data_ranks_latest WHERE Print_ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newSCImago = "$rowD[0]";
			}
			if(($newSCImago != "")) {
				$r++;
				$queryD = "UPDATE 2014_journals_draft SET SCImago = \"$newSCImago\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with 5 year impact factor from both JCR tables
	
	if(($do5YrIF == "y")) {
		
		$parse="Populate journals table with 5 year IF from both JCR tables";
		
		$query = "UPDATE 2014_journals_draft SET 5YR_IMPACT_FACTOR = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
		
			$jcrYR = "";
			$queryD = "SELECT 5YR_IMPACT_FACTOR FROM data_jcr_ssci WHERE ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$jcrYR = "$rowD[0]";
			}
			if(($jcrYR != "")) {
				$r++;
				$queryD = "UPDATE 2014_journals_draft SET 5YR_IMPACT_FACTOR = \"$jcrYR\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
				
			$jcrYR = "";
			$queryD = "SELECT 5YR_IMPACT_FACTOR FROM data_jcr_sci WHERE ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$jcrYR = "$rowD[0]";
			}
			if(($jcrYR != "")) {
				$r++;
				$queryD = "UPDATE 2014_journals_draft SET 5YR_IMPACT_FACTOR = \"$jcrYR\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}			
			
			$x++;
		}		
	}
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with Impact Factor Score from JCR tables
	
	if(($doIF_JCR == "y")) {
		
		$parse="Impact Factor Score from JCR";
		
		$query = "UPDATE 2014_journals_draft SET IF_2012 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			
			$IF="";
			$queryD = "SELECT IMPACT_FACTOR FROM data_jcr_ssci WHERE ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$IF = "$rowD[0]";
			}
			if(($IF !="")) {
				$r++;
				$queryD = "UPDATE 2014_journals_draft SET IF_2012 = \"$IF\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			
			$IF="";
			$queryD = "SELECT IMPACT_FACTOR FROM data_jcr_sci WHERE ISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR ISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$IF = "$rowD[0]";
			}
			if(($IF !="")) {
				$r++;
				$queryD = "UPDATE 2014_journals_draft SET IF_2012 = \"$IF\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}	
	}	

/////////////////////////////////////////////////////////// Finish

	include("era.dbdisconnect.php");
	echo "$parse: $x rows processed | $r";
?>