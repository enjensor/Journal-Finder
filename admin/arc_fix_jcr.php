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
	
/////////////////////////////////////////////////////////// Database affected
//
//	2014_journals_draft
//	2014_journals_snips
//	data_scopus_2015
//	data_snip_scopus
//
/////////////////////////////////////////////////////////// Clear from 2014_journals jcr category, ranking and quartile values	
	
	$do_clear_jcr="n";

/////////////////////////////////////////////////////////// Populate sci 2014_journals table with jcr category, ranking and quartile values	
	
	$do_jcr_ssci="n";
	
/////////////////////////////////////////////////////////// Populate ssci 2014_journals table with jcr category, ranking and quartile values	
	
	$do_jcr_sci="n";
	
/////////////////////////////////////////////////////////// Populate 2014_journals table with 5 year impact factor	
	
	$do5YrIF="n";
	
/////////////////////////////////////////////////////////// Compare current journal list against SJR and SNIP for updated values from data_snip_scopus table
	
	$doSnipScopus = "n";	
	
/////////////////////////////////////////////////////////// Update Scopus with latest vars
	
	$doScopusSnipsUpdate = "n";
	
/////////////////////////////////////////////////////////// Update journal draft with 2011 SNIP	
	
	$updateDraft2011 = "y";
	
/////////////////////////////////////////////////////////// Update journal draft with 2011 SNIP	 with data from 2014_journals_snips

	if(($updateDraft2011 == "y")) {
		$x++;
		$query = "SELECT ERAID FROM 2014_journals_draft ORDER BY ID ASC "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$snip2011 = "";
			$queryD = "SELECT 2011_SNIP FROM 2014_journals_snips WHERE ERAID = \"$row[0]\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$snip2011 = $rowD[0];
			}
			$queryD = "UPDATE 2014_journals_draft SET SNIP_2011 = \"$snip2011\" WHERE ERAID = \"$row[0]\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$r++;
		}
	}
	
/////////////////////////////////////////////////////////// Update data_snip_scopus table with latest vars after adding in new columns to table structure

	if(($doScopusSnipsUpdate == "y")) {
		$x++;
		$query = "SELECT SourceRecord_ID, 2012_SNIP, 2012_IPP, 2012_SJR, 2013_SNIP, 2013_IPP, 2013_SJR, 2014_SNIP, 2014_IPP, 2014_SJR FROM data_scopus_2015 ORDER BY SourceRecord_ID ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$queryB = "UPDATE data_snip_scopus SET ";
			$queryB .= "2012_SNIP = \"$row[1]\", 2012_IPP = \"$row[2]\", 2012_SJR = \"$row[3]\", 2013_SNIP = \"$row[4]\", 2013_IPP = \"$row[5]\", 2013_SJR = \"$row[6]\", 2014_SNIP = \"$row[7]\", 2014_IPP = \"$row[8]\", 2014_SJR = \"$row[9]\" ";
			$queryB .= "WHERE Scopus_Source_ID = \"$row[0]\" ";
			$mysqli_resultB = mysqli_query($mysqli_link, $queryB);
			$r++;
		}
	}
	
/////////////////////////////////////////////////////////// Compare current journal list against SJR and SNIP for updated values from data_snip_scopus table * dbs 2014_journals_draft, 2014_journals_snips, data_scopus_2015
	
	if(($doSnipScopus == "y")) {
		
		$parse="SNIP Scopus";
		
		$query = "UPDATE 2014_journals_draft SET SNIP_2012 = \"\", SNIP_2013 = \"\", SNIP_2014 = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
		$query = "UPDATE 2014_journals_snips SET 2012_SNIP = \"\", 2013_SNIP = \"\", 2014_SNIP = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);		
		
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ORDER BY ID ASC "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 

			$newSnip=""; 
			$newSource="";
			$latestSnip="";
			$oldSnip="";
			$Snip2012="";
			$Snip2013="";
			$Snip2014="";
			
			$row[1] = preg_replace("/-/","",$row[1]);
			$row[2] = preg_replace("/-/","",$row[2]);
			$row[3] = preg_replace("/-/","",$row[3]);
			$row[4] = preg_replace("/-/","",$row[4]);
			$row[5] = preg_replace("/-/","",$row[5]);
			$row[6] = preg_replace("/-/","",$row[6]);
			$row[7] = preg_replace("/-/","",$row[7]);
		
			$queryD = "SELECT SourceRecord_ID, 2012_SNIP, 2013_SNIP, 2014_SNIP FROM data_scopus_2015 WHERE Print_ISSN = \"$row[1]\" ";
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
			$queryD = $queryD."ORDER BY 2014_SNIP DESC LIMIT 1 "; 
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$newSource = "$rowD[0]";
				$Snip2012 = "$rowD[1]";
				$Snip2013 = "$rowD[2]";
				$Snip2014 = "$rowD[3]";
				$Snip2011 = "$rowD[4]";
			}
			if(($newSource!= "")) {
				
				$r++;
				$alreadyExists = "";
				
				$queryD = "UPDATE 2014_journals_draft SET Source_Record_ID = \"$newSource\", SNIP_2012 = \"$Snip2012\", SNIP_2013 = \"$Snip2013\", SNIP_2014 = \"$Snip2014\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
				$queryD = "UPDATE 2014_journals_snips SET Source_Record_ID = \"$newSource\", 2012_SNIP = \"$Snip2012\", 2013_SNIP = \"$Snip2013\", 2014_SNIP = \"$Snip2014\" WHERE ERAID = \"$row[0]\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				$queryD = "SELECT * FROM 2014_journals_snips WHERE Source_Record_ID = \"$newSource\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
					$alreadyExists = "y";
				}	
				
				if(($alreadyExists != "y")) {
					
					$ISSN1 = "";
					$ISSN2 = "";
					$ISSN3 = "";
					$ISSN4 = "";
					$ISSN5 = "";
					$ISSN6 = "";
					$ISSN7 = "";
					$FoR1 = "";
					$FoR2 = "";
					$FoR3 = "";
					$snip = array();
					
					$queryA = "SELECT ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7, FoR1, FoR2, FoR3 FROM 2014_journals_draft WHERE ERAID = \"$row[0]\" ";
					$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
					while($rowA = mysqli_fetch_row($mysqli_resultA)) { 
						$ISSN1 = "$rowA[0]";
						$ISSN2 = "$rowA[1]";
						$ISSN3 = "$rowA[2]";
						$ISSN4 = "$rowA[3]";
						$ISSN5 = "$rowA[4]";
						$ISSN6 = "$rowA[5]";
						$ISSN7 = "$rowA[6]";
						$FoR1 = "$rowA[7]";
						$FoR2 = "$rowA[8]";
						$FoR3 = "$rowA[9]";
					}
					
					$queryA = "SELECT 1999_SNIP, 2000_SNIP, 2001_SNIP, 2002_SNIP, 2003_SNIP, 2004_SNIP, 2005_SNIP, 2006_SNIP, 2007_SNIP, 2008_SNIP, 2009_SNIP, 2010_SNIP, 2011_SNIP FROM data_snip_scopus WHERE Scopus_Source_ID = \"$newSource\" ";
					$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
					while($rowA = mysqli_fetch_row($mysqli_resultA)) {
						$snip[1999] = $rowA[0];
						$snip[2000] = $rowA[1];
						$snip[2001] = $rowA[2];
						$snip[2002] = $rowA[3];
						$snip[2003] = $rowA[4];
						$snip[2004] = $rowA[5];
						$snip[2005] = $rowA[6];
						$snip[2006] = $rowA[7];
						$snip[2007] = $rowA[8];
						$snip[2008] = $rowA[9];
						$snip[2009] = $rowA[10];
						$snip[2010] = $rowA[11];
						$snip[2011] = $rowA[12];
					}
					
					$queryD = "INSERT INTO 2014_journals_snips VALUES(";
					$queryD .= "0, \"$row[0]\", ";
					$queryD .= "\"$ISSN1\", \"$ISSN2\", \"$ISSN3\", \"$ISSN4\", \"$ISSN5\", \"$ISSN6\", \"$ISSN7\", ";
					$queryD .= "\"$FoR1\", \"$FoR2\", \"$FoR3\", ";
					$queryD .= "\"$snip[1999]\", \"$snip[2000]\", \"$snip[2001]\", \"$snip[2002]\", \"$snip[2003]\", \"$snip[2004]\", \"$snip[2005]\", \"$snip[2006]\", \"$snip[2007]\", \"$snip[2008]\", \"$snip[2009]\", \"$snip[2010]\", \"$snip[2011]\", \"$Snip2012\", \"$Snip2013\", \"$Snip2014\", ";
					$queryD .= "\"$newSource\"";
					$queryD .= ")";
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					$g++;
				}
			}
			$x++;
		}
	}	
	
/////////////////////////////////////////////////////////// Clear jcr category, ranking and quartile values
	
	if(($do_clear_jcr == "y")) {
		
		$query = "UPDATE 2014_journals_draft SET JCR_Cat = \"\", JCR_Rank = \"\", JCR_Quartile = \"\" "; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		
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
			$queryD = "SELECT 5YR_IMPACT_FACTOR FROM data_jcr_ssci_2014 WHERE ISSN = \"$row[1]\" ";
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
			$queryD = "SELECT 5YR_IMPACT_FACTOR FROM data_jcr_sci_2014 WHERE ISSN = \"$row[1]\" ";
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
	
/////////////////////////////////////////////////////////// Populate ssci 2014_journals table with jcr category, ranking and quartile values
	
	if(($do_jcr_ssci == "y")) {
		
		$parse="Populate SSCI 2014 journals table with JCR values";
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ";
		$query .= "ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$jcr[1] = "";
			$jcr[2] = "";
			$jcr[3] = "";
			$queryD = "SELECT CATEGORY_DESCRIPTION, CATEGORY_RANKING, QUARTILE_RANK, IMPACT_FACTOR FROM data_jcr_ssci_2014 WHERE ( ISSN = \"$row[1]\" ";
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
			$queryD = $queryD.") ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$jcr[0] = "$rowD[0]";
				$jcr[1] = "$rowD[1]";
				$jcr[2] = "$rowD[2]";
				$jcr[3] = "$rowD[3]";
				if(($jcr[0] != "")) {
					$r++;
					$queryDE = "UPDATE 2014_journals_draft SET JCR_Cat = CONCAT(JCR_Cat,\"$jcr[0]; \"), JCR_Rank = CONCAT(JCR_Rank,\"$jcr[1]; \"), JCR_Quartile = CONCAT(JCR_Quartile,\"$jcr[2]; \"), IF_2012 = \"$jcr[3]\" WHERE ERAID = \"$row[0]\" ";
					$mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
				}
			}
			$x++;
		}
	}
	
/////////////////////////////////////////////////////////// Populate sci 2014_journals table with jcr category, ranking and quartile values

	if(($do_jcr_sci == "y")) {
		
		$parse="Populate SCI 2014 journals table with JCR values";
		$query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2014_journals_draft ";
		$query .= "ORDER BY ID ASC"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$jcr[0] = "";
			$jcr[1] = "";
			$jcr[2] = "";
			$jcr[3] = "";
			$queryD = "SELECT CATEGORY_DESCRIPTION, CATEGORY_RANKING, QUARTILE_RANK, IMPACT_FACTOR FROM data_jcr_sci_2014 WHERE ISSN = \"$row[1]\" ";
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
				if(($jcr[0] != "")) {
					$r++;
					$queryDE = "UPDATE 2014_journals_draft SET JCR_Cat = CONCAT(JCR_Cat,\"$jcr[0]; \"), JCR_Rank = CONCAT(JCR_Rank,\"$jcr[1]; \"), JCR_Quartile = CONCAT(JCR_Quartile,\"$jcr[2]; \"), IF_2012 = \"$jcr[3]\" WHERE ERAID = \"$row[0]\" ";
					$mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
				}
			}
			$x++;
		}
	}

/////////////////////////////////////////////////////////// Finish

	include("era.dbdisconnect.php");
	echo "$parse: $x rows processed | $r | $g";
?>