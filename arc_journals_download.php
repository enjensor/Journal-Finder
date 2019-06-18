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
//  ARC ERA 2010 Journals List | www.arc.gov.au/era/era_2010/archive/era_journal_list.htm
//  ARC ERA 2012 Journals List | www.arc.gov.au/era/era_2012/era_journal_list.htm
//  ARC ERA 2015 Draft Journals List | www.arc.gov.au/era/current_consult.htm
//  ARC ERA 2015 Draft Conferences List | www.arc.gov.au/era/current_consult.htm
//  ARC ERA 2015 Draft Disciplinary Matrix | www.arc.gov.au/era/current_consult.htm
//  DOAJ Open Access Journal Metadata | www.doaj.org/faq
//  Journal Metrics SNIP & SJR Historical Data | www.journalmetrics.com/snip.php
//  JCR Impact Factors & Citation Reports | admin-apps.webofknowledge.com/JCR/JCR
//  JCR Impact Factors Excel SCI | docs.zoho.com/sheet/published.do?rid=ulvpzac533844c7c44b6d9411894426d1ab1c
//  SCImago Journal and Country Rank | www.scimagojr.com/journalrank.php
//
//	DATA API
//
//	ISI Web of Knowledge | wokinfo.com/wok-ws-docs 
//	Elsevier | searchapidocs.scopus.com/
//	OAKlist | www.oaklist.qut.edu.au/api/
//	Sherpa/ RoMEO | www.sherpa.ac.uk/romeo/api.html
//
//  WEB FRAMEWORK
//
//  Bootstrap Twitter v3.1.1 | getbootstrap.com/
//  Font Awesome v4.0.3 | fortawesome.github.io/Font-Awesome/
//  Google Fonts API | fonts.googleapis.com
//  Modernizr v.2.6.2 | modernizr.com/
//  Multi-Level Push Menu v2.1.4 | multi-level-push-menu.make.rs/
//  JQuery v.1.11.0 | jquery.com/download/
//	JQuery JQPlot v.1.0.8 | www.jqplot.com/
//	JQuery UI v.10.4 | jqueryui.com/
//
//  VERSION 0.1
//  18-19 FEBRUARY 2014
//
//	VERSION 0.2
//	24 MARCH - 11 APRIL 2014
//
//	LAST UPDATE
//	11 March 2019
//	17-18 June 2019
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	$wildcard = "";
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	require_once("./classes/PHPExcel.php");
	
/////////////////////////////////////////////////////////// Vars

	$i = 1;
	$forCode = $_GET['forCode'];
	$keywords = $_GET['keywords'];
	$eRAID = $_GET['eRAID'];
	$Order = $_GET['Order'];
	$wildcardSearch = "";
	$found = "";
	$dataShow = "y";
	
/////////////////////////////////////////////////////////// Logic switch
	
	if(($forCode != "")) { $dataShow = "y"; }
	if(($keywords == "VIEWSAVED")) { $dataShow = "y"; }
	if(($keywords != "") && ($keywords != "VIEWSAVED") && ($forCode == "") && ($eRAID == "") && ($Order == "")) { $dataShow = "y"; $wildcardSearch = "y"; }
	
/////////////////////////////////////////////////////////// Build excel
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Journal Finder Project")
			->setLastModifiedBy("Journal Finder Project")
			->setTitle("Office 2007 XLSX Results Download")
			->setSubject("Office 2007 XLSX Results Download")
			->setDescription("Publications in relevant Fields of Research")
			->setKeywords("journal finder fields research fors era arc")
			->setCategory("Publications");
	
/////////////////////////////////////////////////////////// Write header row
//						 
//	$objPHPExcel->setActiveSheetIndex(0)
//        	->setCellValue("A" . $i, "WSU OA Funded")
//			->setCellValue("B" . $i, "SCImago Quartile")
//			->setCellValue("C" . $i, "SCImago Fields")
//			->setCellValue("D" . $i, "Impact Factor")
//			->setCellValue("E" . $i, "5 Year IF")
//			->setCellValue("F" . $i, "SNIP")
//			->setCellValue("G" . $i, "ISSN")
//			->setCellValue("H" . $i, "Title")
//			->setCellValue("I" . $i, "Australian Business Deans Council Rank")
//			->setCellValue("J" . $i, "FoR 1")
//			->setCellValue("K" . $i, "FoR 2")
//			->setCellValue("L" . $i, "FoR 3")
//			->setCellValue("M" . $i, "Open Access");
//
//	WSI OA Funded
//	Q SCImago
//	Q SCImago Fields
//	SNIP
//	Q JCR
//	Rank JCR
//	Categories JCR
//	IF
//	IF 5YR
//	ISSN
//	Title
//	ABDC
//	FoR1
//	FoR2
//	FoR3
//	DOAJ

	$objPHPExcel->setActiveSheetIndex(0)
        	->setCellValue("A" . $i, "WSU OA Funded")
            ->setCellValue("B" . $i, "Q SCImago")
            ->setCellValue("C" . $i, "Q SCImago Fields")
            ->setCellValue("D" . $i, "SNIP")
            ->setCellValue("E" . $i, "Q JCR")
            ->setCellValue("F" . $i, "Rank JCR")
            ->setCellValue("G" . $i, "Categories JCR")
            ->setCellValue("H" . $i, "Impact Factor")
			->setCellValue("I" . $i, "Impact Factor 5 Year")
			->setCellValue("J" . $i, "ISSN")
			->setCellValue("K" . $i, "Title")
			->setCellValue("L" . $i, "Australian Business Deans Council Rank")
			->setCellValue("M" . $i, "FoR 1")
			->setCellValue("N" . $i, "FoR 2")
			->setCellValue("O" . $i, "FoR 3")
			->setCellValue("P" . $i, "DOAJ");		
	
/////////////////////////////////////////////////////////// Write no data if invalid get vars

	if(($dataShow == "n")) {
		$i++;
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A" . $i, "--")
            ->setCellValue("B" . $i, "--")
            ->setCellValue("C" . $i, "--")
            ->setCellValue("D" . $i, "--")
			->setCellValue("E" . $i, "--")
			->setCellValue("F" . $i, "--")
			->setCellValue("G" . $i, "--")
			->setCellValue("H" . $i, "--")
			->setCellValue("I" . $i, "--")
			->setCellValue("J" . $i, "--")
			->setCellValue("K" . $i, "--")
			->setCellValue("L" . $i, "--")
			->setCellValue("M" . $i, "--")
			->setCellValue("N" . $i, "--")
			->setCellValue("O" . $i, "--")
			->setCellValue("P" . $i, "--");
	}
	
/////////////////////////////////////////////////////////// Else construct database queries

	if(($dataShow == "y")) {
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// All FoR journals		
		
		if(($keywords != "VIEWSAVED")) { 
			if(($Order == "QUARTILE")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when SCIMAGO_Rank in('', '0') then 1 else 0 end, SCIMAGO_Rank ASC";
			}
			if(($Order == "QRANK")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when JCR_Rank in('', '0') then 1 else 0 end, cast(JCR_Rank as unsigned), JCR_Quartile ASC";
			} 
			if(($Order == "SNIP")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when SNIP_2017 in('', '0') then 1 else 0 end, convert(`SNIP_2017`, decimal(5,3)) DESC";
			} 
			if(($Order == "IF")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when IF_2012 in('', '0') then 1 else 0 end, convert(`IF_2012`, decimal(5,3)) DESC";
			}
			if(($Order == "AIS")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when AIS_2012 in('', '0') then 1 else 0 end, convert(`AIS_2012`, decimal(5,3)) DESC";
			}
			if(($Order == "Rank")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when Rank_2010 in('', '0') then 1 else 0 end, Rank_2010 ASC, Title ASC";
			}
			if(($Order == "ABDC")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when ABDC_Rank in('', '0') then 1 else 0 end, ABDC_Rank ASC, Title ASC";
			}
			if(($Order == "FoR1")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when FoR1 in('', '0') then 1 else 0 end, FoR1 ASC, Title ASC"; 
			} 
			if(($Order == "FoR2")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when FoR2 in('', '0') then 1 else 0 end, FoR2 ASC, Title ASC"; 
			} 
			if(($Order == "FoR3")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when FoR3 in('', '0') then 1 else 0 end, FoR3 ASC, Title ASC"; 
			} 
			if(($Order == "OA")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "OpenAccess DESC, SNIP_2017 DESC";
			}
			if(($Order == "OAF")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "WSU_Funded DESC, SNIP_2017 DESC";
			}
			if(($Order == "") && ($wildcardSearch == "")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY Title ASC"; 
			}
			if(($Order == "") && ($wildcardSearch == "y")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY Title ASC"; 
			}
		}
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// All saved journals			
		
		if(($keywords == "VIEWSAVED")) {
			
			$b = 0;
			$constructSQL = "";
			$c = count($_SESSION["ERAIDS"]);
			foreach($_SESSION["ERAIDS"] AS $e) {
				$b++;
				if(($b != $c)) {
					$constructSQL .= "ERAID = \"$e\" OR ";	
				} else {
					$constructSQL .= "ERAID = \"$e\"";
				}
			}
			if(($Order == "QUARTILE")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when SCIMAGO_Rank in('', '0') then 1 else 0 end, SCIMAGO_Rank ASC";
			}
			if(($Order == "QRANK")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when JCR_Rank in('', '0') then 1 else 0 end, cast(JCR_Rank as unsigned), JCR_Quartile ASC";
			} 
			if(($Order == "SNIP")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when SNIP_2017 in('', '0') then 1 else 0 end, convert(`SNIP_2017`, decimal(5,3)) DESC";
			} 
			if(($Order == "IF")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when IF_2012 in('', '0') then 1 else 0 end, convert(`IF_2012`, decimal(5,3)) DESC";
			}
			if(($Order == "AIS")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when AIS_2012 in('', '0') then 1 else 0 end, convert(`AIS_2012`, decimal(5,3)) DESC";
			}
			if(($Order == "Rank")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when Rank_2010 in('', '0') then 1 else 0 end, Rank_2010 ASC, Title ASC";
			}
			if(($Order == "ABDC")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when ABDC_Rank in('', '0') then 1 else 0 end, ABDC_Rank ASC, Title ASC";
			}
			if(($Order == "FoR1")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when FoR1 in('', '0') then 1 else 0 end, FoR1 ASC, Title ASC"; 
			} 
			if(($Order == "FoR2")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when FoR2 in('', '0') then 1 else 0 end, FoR2 ASC, Title ASC"; 
			} 
			if(($Order == "FoR3")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when FoR3 in('', '0') then 1 else 0 end, FoR3 ASC, Title ASC"; 
			} 
			if(($Order == "OA")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "OpenAccess DESC, SNIP_2017 DESC";
			} 
			if(($Order == "OAF")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "WSU_Funded DESC, SNIP_2017 DESC";
			}
			if(($Order == "")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY Title ASC"; 
			}			
		}		
	
/////////////////////////////////////////////////////////// Run sql statement	

		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
		
/////////////////////////////////////////////////////////// Clean Vars		
		
			$i++;
			$m++;
			$eRaids[$m] = $row[1];
			$snip=number_format((float)$row[43], 3, '.', '');
			$rank=$row[19];
			$FiveIF=$row[34];
			$quartilejcr=$row[29];
			$quartilejcr=preg_replace("/Q/","","$quartilejcr");
			$qjcrrank=$row[28];
			$qjcrcat=$row[27];
			$wsufund=$row[42];
			$quartile=$row[41];
			$quartile=preg_replace("/Q/","","$quartile");
			$qrank=$quartile;
			$qcat=$row[40];
			$OAccess=$row[24];
			if(($row[30] == "")) { $row[30] = " "; }
			$IFscore=number_format((float)$row[25], 3, '.', '');
			$AIscore=number_format((float)$row[26], 3, '.', '');
			$row[2]= preg_replace("/'/i","\\'","$row[2]");
			if(($IFscore == "0.000")) { $IFscore = ""; }
			if(($AIscore == "0.000")) { $AIscore = ""; }
			if(($snip == "0")) { $snip = ""; }	
			if(($FiveIF == "0")) { $FiveIF = ""; }	
			$title = preg_replace("/[^a-zA-Z0-9]+ /", "", html_entity_decode($row[2], ENT_QUOTES));	
			$ISSN = "";
			if(($row[10] != "") && ($row[10] != " ")) { $ISSN .= $row[10]; }
			if(($row[11] != "") && ($row[11] != " ")) { $ISSN .= ",".$row[11]; }
			if(($row[12] != "") && ($row[12] != " ")) { $ISSN .= ",".$row[12]; }
			if(($row[13] != "") && ($row[13] != " ")) { $ISSN .= ",".$row[13]; }
			if(($row[14] != "") && ($row[14] != " ")) { $ISSN .= ",".$row[14]; }
			if(($row[15] != "") && ($row[15] != " ")) { $ISSN .= ",".$row[15]; }
			if(($row[16] != "") && ($row[16] != " ")) { $ISSN .= ",".$row[16]; }

//	WSI OA Funded
//	Q SCImago
//	Q SCImago Fields
//	SNIP
//	Q JCR
//	Rank JCR
//	Categories JCR
//	IF
//	IF 5YR
//	ISSN
//	Title
//	ABDC
//	FoR1
//	FoR2
//	FoR3
//	DOAJ
		
			$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue("A" . $i, "$wsufund")
            	->setCellValue("B" . $i, "$qrank")
            	->setCellValue("C" . $i, "$qcat")
            	->setCellValue("D" . $i, "$snip")
            	->setCellValue("E" . $i, "$quartilejcr")
            	->setCellValue("F" . $i, "$qjcrrank")
            	->setCellValue("G" . $i, "$qjcrcat")
            	->setCellValue("H" . $i, "$IFscore")
				->setCellValue("I" . $i, "$FiveIF")
				->setCellValue("J" . $i, "$ISSN")
				->setCellValue("K" . $i, "$title")
				->setCellValue("L" . $i, "$row[30]")
				->setCellValue("M" . $i, "$row[4]")
				->setCellValue("N" . $i, "$row[6]")
				->setCellValue("O" . $i, "$row[8]")
				->setCellValue("P" . $i, "$OAccess");		
		}
	}

/////////////////////////////////////////////////////////// Finish spreadsheet	
	
	$objPHPExcel->getActiveSheet()->setTitle('Journals');
	$objPHPExcel->setActiveSheetIndex(0);

/////////////////////////////////////////////////////////// Send excel to web browser

	header('Content-Type: application/vnd.ms-excel');
	if(($keywords == "VIEWSAVED")) {
		header('Content-Disposition: attachment;filename="Journal_Finder_Results_Comparison.xls"');
	} else {
		header('Content-Disposition: attachment;filename="Journal_Finder_Results_'.$forCode.'.xls"');
	}
	header('Cache-Control: max-age=0');
	header('Cache-Control: max-age=1');
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header ('Cache-Control: cache, must-revalidate');
	header ('Pragma: public');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

/////////////////////////////////////////////////////////// Footer

	include("./admin/era.dbdisconnect.php");
	exit;

?>