<?php

/////////////////////////////////////////////////////////// Vars

	include("config.php");
	include("era.dbconnect.php");

/////////////////////////////////////////////////////////// Do Routines

	$doJournalSnipsScopus = "n";
	$do2017Scopus = "n";	
	$do_elsevier = "n";	
	$do_abdc = "n";
	$doOA = "n";
	$doISBNScopusSnips = "n";
	$fixFors = "n";	
	$doSnipScopus = "n";	
	$doSnip = "y";
	$doRank = "n";
	$doEra15 = "n";
	$do_clear_jcr = "n";	
	$do_jcr_sci = "n";	
	$do_jcr_ssci = "n";
	$do5YrIF = "n";
	$doIF_JCR = "n";
	$doSCImago = "n";
	$doNewTitle = "n";	
	$doNew = "n";
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Do all journals Snips from data_snip_scopus table

	if(($doJournalSnipsScopus == "y")) {
	
		$parse = "Clone journal list with full history of SNIP for easy annual FoR SNIP totals";
		$query = "TRUNCATE TABLE `2017_journals_snips`;";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7, FoR1, FoR2, FoR3 FROM `2017_journals_final_list` ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$snip = "";
			$queryD = "SELECT * FROM `2017_data_snip_scopus` WHERE Print_ISSN = \"$row[1]\" ";
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
					$queryDa = "INSERT INTO `2017_journals_snips` VALUES (";
					$queryDa .= "\"0\", ";
					$queryDa .= "\"$row[0]\", ";
					$queryDa .= "\"$row[1]\", ";
					$queryDa .= "\"$row[2]\", ";
					$queryDa .= "\"$row[3]\", ";
					$queryDa .= "\"$row[4]\", ";
					$queryDa .= "\"$row[5]\", ";
					$queryDa .= "\"$row[6]\", ";
					$queryDa .= "\"$row[7]\", ";
					$queryDa .= "\"$row[8]\", ";
					$queryDa .= "\"$row[9]\", ";
					$queryDa .= "\"$row[10]\", ";
					$queryDa .= "\"$rowD[8]\", ";
					$queryDa .= "\"$rowD[11]\", ";
					$queryDa .= "\"$rowD[14]\", ";
					$queryDa .= "\"$rowD[17]\", ";
					$queryDa .= "\"$rowD[20]\", ";
					$queryDa .= "\"$rowD[23]\", ";
					$queryDa .= "\"$rowD[26]\", ";
					$queryDa .= "\"$rowD[29]\", ";
					$queryDa .= "\"$rowD[32]\", ";
					$queryDa .= "\"$rowD[35]\", ";
					$queryDa .= "\"$rowD[38]\", ";
					$queryDa .= "\"$rowD[41]\", ";
					$queryDa .= "\"$rowD[44]\", ";
					$queryDa .= "\"$rowD[47]\", ";
					$queryDa .= "\"$rowD[50]\", ";
					$queryDa .= "\"$rowD[53]\", ";
					$queryDa .= "\"\", ";
					$queryDa .= "\"\", ";
					$queryDa .= "\"$rowD[1]\"";
					$queryDa .= ");";
					$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
					$x++;					
				}
			}
			$r++;
		}
	}	
	
	if(($do2017Scopus == "y")) {
		
		$parse = "Populate Journals SNIP table with new 2011-2016 Scopus data";
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7, FoR1, FoR2, FoR3 FROM `2017_journals_final_list` ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$eraid = $row[0];
			$row[1] = preg_replace("/-/","","$row[1]");
			$row[2] = preg_replace("/-/","","$row[2]");
			$row[3] = preg_replace("/-/","","$row[3]");
			$row[4] = preg_replace("/-/","","$row[4]");
			$row[5] = preg_replace("/-/","","$row[5]");
			$row[6] = preg_replace("/-/","","$row[6]");
			$row[7] = preg_replace("/-/","","$row[7]");
		
/////////////////////////////////////// 2011 SNIP		
		
			$snip = "";
			$queryD = "SELECT SNIP FROM `2017_Scopus_2011` WHERE Print_ISSN = \"$row[1]\" ";
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
			$queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip = $rowD[0];
			}
			if(($snip != "")) {
				$queryDa = "UPDATE `2017_journals_snips` SET 2011_SNIP = \"$snip\" WHERE ERAID = \"$eraid\"; ";
				$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
				$x++;
			}
			
/////////////////////////////////////// 2012 SNIP		
		
			$snip = "";
			$queryD = "SELECT SNIP FROM `2017_Scopus_2012` WHERE Print_ISSN = \"$row[1]\" ";
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
			$queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip = $rowD[0];
			}
			if(($snip != "")) {
				$queryDa = "UPDATE `2017_journals_snips` SET 2012_SNIP = \"$snip\" WHERE ERAID = \"$eraid\"; ";
				$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
				$x++;
			}
			
/////////////////////////////////////// 2013 SNIP		
		
			$snip = "";
			$queryD = "SELECT SNIP FROM `2017_Scopus_2013` WHERE Print_ISSN = \"$row[1]\" ";
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
			$queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip = $rowD[0];
			}
			if(($snip != "")) {
				$queryDa = "UPDATE `2017_journals_snips` SET 2013_SNIP = \"$snip\" WHERE ERAID = \"$eraid\"; ";
				$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
				$x++;
			}
			
/////////////////////////////////////// 2014 SNIP		
		
			$snip = "";
			$queryD = "SELECT SNIP FROM `2017_Scopus_2014` WHERE Print_ISSN = \"$row[1]\" ";
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
			$queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip = $rowD[0];
			}
			if(($snip != "")) {
				$queryDa = "UPDATE `2017_journals_snips` SET 2014_SNIP = \"$snip\" WHERE ERAID = \"$eraid\"; ";
				$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
				$x++;
			}
			
/////////////////////////////////////// 2015 SNIP		
		
			$snip = "";
			$queryD = "SELECT SNIP FROM `2017_Scopus_2015` WHERE Print_ISSN = \"$row[1]\" ";
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
			$queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip = $rowD[0];
			}
			if(($snip != "")) {
				$queryDa = "UPDATE `2017_journals_snips` SET 2015_SNIP = \"$snip\" WHERE ERAID = \"$eraid\"; ";
				$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
				$x++;
			}
			
/////////////////////////////////////// 2016 SNIP		
		
			$snip = "";
			$queryD = "SELECT SNIP FROM `2017_Scopus_2016` WHERE Print_ISSN = \"$row[1]\" ";
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
			$queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip = $rowD[0];
			}
			if(($snip != "")) {
				$queryDa = "UPDATE `2017_journals_snips` SET 2016_SNIP = \"$snip\" WHERE ERAID = \"$eraid\"; ";
				$mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
				$x++;
			}																
		}
	}
	
/////////////////////////////////////////////////////////// Do Elsevier

	if(($do_elsevier == "y")) {
	
		$parse="Populate 2017 journals table with Elsevier flag";
		$query = "UPDATE `2017_journals_final_list` SET Elsevier = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
	
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM `2017_journals_final_list` ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$queryD = "SELECT ISSN FROM `2017_data_elsevier` WHERE ISSN = \"$row[1]\" ";
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
				$queryD = "UPDATE `2017_journals_final_list` SET Elsevier = \"$jcr[0]\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}	
	}
	
/////////////////////////////////////////////////////////// Do ABDC

	if(($do_abdc == "y")) {
		
		$parse="Populate 2017 journals table with ABDC values";
		$query = "UPDATE `2017_journals_final_list` SET ABDC_Rank = \"\", ABDC_FoR = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM `2017_journals_final_list` ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$jcr[1] = "";
			$queryD = "SELECT ABDC_Rank, ABDC_FoR FROM `2017_data_abdc` WHERE ISSN_Print = \"$row[1]\" ";
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
				if(strlen($jcr[1]) == 3) {
					$jcr[1] = "0".$jcr[1];
				}
				$queryD = "UPDATE `2017_journals_final_list` SET ABDC_Rank = \"$jcr[0]\", ABDC_FoR = \"$jcr[1]\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}
		
	}
	
/////////////////////////////////////////////////////////// Clear jcr category, ranking and quartile values
	
	if(($do_clear_jcr == "y")) {
		
		$query = "UPDATE 2017_journals_final_list SET JCR_Cat = \"\", JCR_Rank = \"\", JCR_Quartile = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);

	}
	
/////////////////////////////////////////////////////////// Populate ssci 2014_journals table with jcr category, ranking and quartile values
	
	if(($do_jcr_ssci == "y")) {
		
		$parse="Populate SSCI 2017 journals table with JCR values";
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2017_journals_final_list ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$jcr[1] = "";
			$jcr[2] = "";
			$jcr[3] = "";
			$queryD = "SELECT CATEGORY_DESCRIPTION, CATEGORY_RANKING, QUARTILE_RANK, IMPACT_FACTOR ";
			$queryD .= "FROM data_jcr_ssci_2016 ";
			$queryD .= "WHERE ISSN = \"$row[1]\" ";
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
				if(($jcr[0] !="")) {
					$r++;
					$queryDE = "UPDATE 2017_journals_final_list SET JCR_Cat = CONCAT(JCR_Cat,\"$jcr[0]; \"), ";
					$queryDE .= "JCR_Rank = CONCAT(JCR_Rank,\"$jcr[1]; \"), JCR_Quartile = CONCAT(JCR_Quartile,\"$jcr[2]; \"), IF_2012 = \"$jcr[3]\" ";
					$queryDE .= "WHERE ERAID = \"$row[0]\" ";
					$mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
				}
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Populate sci 2014_journals table with jcr category, ranking and quartile values

	if(($do_jcr_sci == "y")) {
		
		$parse="Populate SCI 2017 journals table with JCR values";
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2017_journals_final_list ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$jcr[1] = "";
			$jcr[2] = "";
			$jcr[3] = "";
			$queryD = "SELECT CATEGORY_DESCRIPTION, CATEGORY_RANKING, QUARTILE_RANK, IMPACT_FACTOR ";
			$queryD .= "FROM data_jcr_sci_2016 ";
			$queryD .= "WHERE ISSN = \"$row[1]\" ";
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
				if(($jcr[0] !="")) {
					$r++;
					$queryDE = "UPDATE 2017_journals_final_list SET JCR_Cat = CONCAT(JCR_Cat,\"$jcr[0]; \"), ";
					$queryDE .= "JCR_Rank = CONCAT(JCR_Rank,\"$jcr[1]; \"), JCR_Quartile = CONCAT(JCR_Quartile,\"$jcr[2]; \"), IF_2012 = \"$jcr[3]\" ";
					$queryDE .= "WHERE ERAID = \"$row[0]\" ";
					$mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
				}
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
		$query = "UPDATE 2014_journals_final_list SET IF_2012 = \"\", AIS_2012 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_final_list WHERE OpenAccess =\"\" "; 
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
				$queryD = "UPDATE 2014_journals_final_list SET IF_2012 = \"$IF\", AIS_2012 = \"$AIS\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Record OA record id

	if(($doOA == "y")) {
		
		$parse="Open Access";
		$query = "UPDATE 2017_journals_final_list SET OpenAccess = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2017_journals_final_list WHERE OpenAccess =\"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$OA = "";
			$queryD = "SELECT ID FROM 2017_data_oa WHERE Print_ISSN = \"$row[1]\" OR EISSN = \"$row[1]\" ";
			if(($row[2] != "") && ($row[2] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[2]\" OR EISSN = \"$row[2]\" ";
			}
			if(($row[3] != "") && ($row[3] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[3]\" OR EISSN = \"$row[3]\" ";
			}
			if(($row[4] != "") && ($row[4] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[4]\" OR EISSN = \"$row[4]\" ";
			}
			if(($row[5] != "") && ($row[5] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[5]\" OR EISSN = \"$row[5]\" ";
			}
			if(($row[6] != "") && ($row[6] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[6]\" OR EISSN = \"$row[6]\" ";
			}
			if(($row[7] != "") && ($row[7] != " ")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[7]\" OR EISSN = \"$row[7]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$OA = "$rowD[0]";
			}
			if(($OA !="")) {
				$r++;
				$queryD = "UPDATE 2017_journals_final_list SET OpenAccess = \"Yes\" WHERE ERAID = \"$row[0]\" ";
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
		$query = "SELECT ID, FoR1, FoR2, FoR3, ERAID FROM 2017_journals_final_list ORDER BY ID ASC "; 
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

			$queryD = "UPDATE 2017_journals_final_list SET FoR1 = \"$row[1]\", FoR2 = \"$row[2]\", FoR3 = \"$row[3]\" WHERE ID = \"$row[0]\" AND ERAID = \"$row[4]\" "; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		}
	}
	
/////////////////////////////////////////////////////////// Compare current journal list against SJR and SNIP for updated values (2010/11, 2012)
	
	if(($doSnip == "y")) {
		
		$parse = "SNIPS 2010, 2011, 2012, 2013, 2014, 2015, 2016";
		$query = "UPDATE 2017_journals_final_list SET ";
		$query .= "SNIP_2010 = \"\", ";
		$query .= "SNIP_2011 = \"\", ";
		$query .= "SNIP_2012 = \"\", ";
		$query .= "SNIP_2013 = \"\", ";
		$query .= "SNIP_2014 = \"\", ";
		$query .= "SNIP_2015 = \"\", ";
		$query .= "SNIP_2016 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);

///////////////// 2011 to 2016 SNIP Loop

		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2017_journals_final_list ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newSnip = ""; 
			$newSource = "";
			$row[2] = preg_replace("/-/i","","$row[2]");
			$row[3] = preg_replace("/-/i","","$row[3]");
			$row[4] = preg_replace("/-/i","","$row[4]");
			$row[5] = preg_replace("/-/i","","$row[5]");
			$row[6] = preg_replace("/-/i","","$row[6]");
			$row[7] = preg_replace("/-/i","","$row[7]");
			
			for($q=1; $q<7; $q++) {
				$v = 2010 + $q;
				$cSnip = "SNIP_".$v;
				$queryD = "SELECT SNIP, Scopus_SourceID FROM 2017_scopus_".$v." WHERE ";
				$queryD .= "Print_ISSN = \"$row[1]\" OR EISSN = \"$row[1]\" ";
				if(($row[2] != "") && ($row[2] != " ")) {
					$queryD = $queryD."OR Print_ISSN = \"$row[2]\" OR EISSN = \"$row[2]\" ";
				}
				if(($row[3] != "") && ($row[3] != " ")) {
					$queryD = $queryD."OR Print_ISSN = \"$row[3]\" OR EISSN = \"$row[3]\" ";
				}
				if(($row[4] != "") && ($row[4] != " ")) {
					$queryD = $queryD."OR Print_ISSN = \"$row[4]\" OR EISSN = \"$row[4]\" ";
				}
				if(($row[5] != "") && ($row[5] != " ")) {
					$queryD = $queryD."OR Print_ISSN = \"$row[5]\" OR EISSN = \"$row[5]\" ";
				}
				if(($row[6] != "") && ($row[6] != " ")) {
					$queryD = $queryD."OR Print_ISSN = \"$row[6]\" OR EISSN = \"$row[6]\" ";
				}
				if(($row[7] != "") && ($row[7] != " ")) {
					$queryD = $queryD."OR Print_ISSN = \"$row[7]\" OR EISSN = \"$row[7]\" "; 
				}
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
					$newSnip = "$rowD[0]";
					$newSource = "$rowD[1]";
				}
				if(($newSource != "") && ($newSnip != "")) { 
					$queryD = "UPDATE 2017_journals_final_list SET ".$cSnip." = \"$newSnip\", Source_Record_ID = \"$newSource\" WHERE ERAID = \"$row[0]\" ";
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$x++;
				}
			}
		}	
	}
	
/////////////////////////////////////////////////////////// Compare current journal list against 2010 for depreciated rank

	if(($doRank == "y")) {
		
		$parse="Rank";
		$query = "UPDATE 2017_journals_final_list SET Rank_2010 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID FROM 2017_journals_final_list WHERE Rank_2010 =\"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newRank=""; 
			$queryD = "SELECT Rank FROM 2017_data_era_rank WHERE ERAID = \"$row[0]\" LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newRank = "$rowD[0]";
			}
			if(($newRank == "")) {
				$newRank = "";
			}
			$queryD = "UPDATE 2017_journals_final_list SET Rank_2010 = \"$newRank\" WHERE ERAID = \"$row[0]\" "; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$x++;
		}
	}

/////////////////////////////////////////////////////////// Compare current journal list against old for new titles

	if(($doNewTitle == "y")) {
		
		$parse="New Titles";
		$query = "UPDATE 2017_journals_final_list SET New_2014_Title = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID FROM 2017_journals_final_list ORDER BY TITLE ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
				
			$x++;
			$newJournal="";
			$queryD = "SELECT * FROM 2014__journals_final_list WHERE ERAID = \"$row[0]\" LIMIT 1"; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newJournal = "No";
			}
			
			if(($newJournal == "")) {
				$newJournal = "Yes";
				$queryD = "UPDATE 2017_journals_final_list SET New_2014_Title = \"$newJournal\" WHERE ERAID = \"$row[0]\" "; 
				$mysqli_resultBD = mysqli_query($mysqli_link, $queryD);
				$r++;
			}
		}
	}

/////////////////////////////////////////////////////////// Compare current journal list against old for new FoR assignment

	if(($doNew == "y")) {
		
		$parse="New FoRs";
		$query = "UPDATE 2017_journals_final_list SET New_2014 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, FoR1, FoR2, FoR3, New_2014 FROM 2017_journals_final_list WHERE New_2014 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newJournal=""; 
			$queryD = "SELECT * FROM 2014_journals_final_list WHERE ERAID = \"$row[0]\" AND ( FoR1=\"$row[1]\" OR FoR2=\"$row[1]\" OR FoR3=\"$row[1]\") "; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newJournal = "N$row[1],";
			}
			if(($newJournal == "")) {
				$newJournal = "Y$row[1],";
				$r++;
			}
			$queryD = "UPDATE 2017_journals_final_list SET New_2014 = CONCAT(New_2014,\"$newJournal\") WHERE ERAID = \"$row[0]\" "; 
			$mysqli_resultBD = mysqli_query($mysqli_link, $queryD);

			if(($row[2] !="")) { 
			$newJournal=""; 
				$queryD = "SELECT * FROM 2014_journals_final_list WHERE ERAID = \"$row[0]\" AND ( FoR1=\"$row[2]\" OR FoR2=\"$row[2]\" OR FoR3=\"$row[2]\") "; 
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
					$newJournal = "N$row[2],";
				}
				if(($newJournal == "")) {
					$newJournal = "Y$row[2],";
					$r++;
				}
				$queryD = "UPDATE 2017_journals_final_list SET New_2014 = CONCAT(New_2014,\"$newJournal\") WHERE ERAID = \"$row[0]\" "; 
				$mysqli_resultBD = mysqli_query($mysqli_link, $queryD);
			}

			if(($row[3] !="")) { 
				$newJournal=""; 
				$queryD = "SELECT * FROM 2014_journals_final_list WHERE ERAID = \"$row[0]\" AND ( FoR1=\"$row[3]\" OR FoR2=\"$row[3]\" OR FoR3=\"$row[3]\") "; 
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
					$newJournal = "N$row[3],";
				}
				if(($newJournal == "")) {
					$newJournal = "Y$row[3],";
					$r++;
				}
				$queryD = "UPDATE 2017_journals_final_list SET New_2014 = CONCAT(New_2014,\"$newJournal\") WHERE ERAID = \"$row[0]\" "; 
				$mysqli_resultBD = mysqli_query($mysqli_link, $queryD);
			}

			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with flag that indicates title is in SCImago database (for cross-linking)

	if(($doSCImago == "y")) {
		
		$parse="Add SCImago ISSN flag";
		$query = "UPDATE 2017_journals_final_list SET SCImago = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2017_journals_final_list WHERE SCImago = \"\" "; 
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
				$queryD = "UPDATE 2017_journals_final_list SET SCImago = \"$newSCImago\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with 5 year impact factor from both JCR tables
	
	if(($do5YrIF == "y")) {
		
		$parse="Populate journals table with 5 year IF from both JCR tables";
		$query = "UPDATE 2017_journals_final_list SET 5YR_IMPACT_FACTOR = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2017_journals_final_list ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
		
			$jcrYR = "";
			$queryD = "SELECT 5YR_IMPACT_FACTOR FROM data_jcr_ssci_2016 WHERE ISSN = \"$row[1]\" ";
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
				$queryD = "UPDATE 2017_journals_final_list SET 5YR_IMPACT_FACTOR = \"$jcrYR\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
				
			$jcrYR = "";
			$queryD = "SELECT 5YR_IMPACT_FACTOR FROM data_jcr_sci_2016 WHERE ISSN = \"$row[1]\" ";
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
				$queryD = "UPDATE 2017_journals_final_list SET 5YR_IMPACT_FACTOR = \"$jcrYR\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}			
			
			$x++;
		}		
	}
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with Impact Factor Score from JCR tables
	
	if(($doIF_JCR == "y")) {
		
		$parse="Impact Factor Score from JCR";
		$query = "UPDATE 2017_journals_final_list SET IF_2012 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2017_journals_final_list ORDER BY ID ASC "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			
			$IF="";
			$queryD = "SELECT IMPACT_FACTOR FROM data_jcr_ssci_2016 WHERE ISSN = \"$row[1]\" ";
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
				$queryD = "UPDATE 2017_journals_final_list SET IF_2012 = \"$IF\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			
			$IF="";
			$queryD = "SELECT IMPACT_FACTOR FROM data_jcr_sci_2016 WHERE ISSN = \"$row[1]\" ";
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
				$queryD = "UPDATE 2017_journals_final_list SET IF_2012 = \"$IF\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
			$x++;
		}	
	}	

/////////////////////////////////////////////////////////// Finish

	include("era.dbdisconnect.php");
	echo "$parse: $x rows processed | $r";
?>