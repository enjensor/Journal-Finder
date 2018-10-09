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
//  VERSION 0.1
//  18-19 FEBRUARY 2014
//
//	VERSION 0.2
//	24 MARCH - 11 APRIL 2014
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	$wildcard = "";
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	require_once dirname(__FILE__) . '/classes/PHPExcel.php';
	
/////////////////////////////////////////////////////////// Vars

	$i = 1;
	$forCode = $_GET['forCode'];
	$keywords = $_GET['keywords'];
	$eRAID = $_GET['eRAID'];
	$Order = $_GET['Order'];
	$wildcardSearch = "";
	$found = "";
	$dataShow = "n";
	
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
							 
	$objPHPExcel->setActiveSheetIndex(0)
        	->setCellValue("A" . $i, "Quartile")
            ->setCellValue("B" . $i, "JCR Rank")
            ->setCellValue("C" . $i, "JCR Field")
            ->setCellValue("D" . $i, "Impact Factor")
			->setCellValue("E" . $i, "5 Year IF")
			->setCellValue("F" . $i, "SNIP")
			->setCellValue("G" . $i, "ISSN")
			->setCellValue("H" . $i, "Title")
			->setCellValue("I" . $i, "Australian Business Deans Council Rank")
			->setCellValue("J" . $i, "FoR 1")
			->setCellValue("K" . $i, "FoR 2")
			->setCellValue("L" . $i, "FoR 3")
			->setCellValue("M" . $i, "Open Access");
	
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
			->setCellValue("M" . $i, "--");
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
				$query .= "case when JCR_Quartile in('', '0') then 1 else 0 end, JCR_Quartile ASC, cast(JCR_Rank as unsigned)";
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
				$query .= "case when SNIP_2014 in('', '0') then 1 else 0 end, convert(`SNIP_2014`, decimal(5,3)) DESC";
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
				$query .= "OpenAccess DESC, SNIP_2014 DESC";
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
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when JCR_Quartile in('', '0') then 1 else 0 end, JCR_Quartile ASC, cast(JCR_Rank as unsigned)";
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
				$query .= "case when SNIP_2014 in('', '0') then 1 else 0 end, convert(`SNIP_2014`, decimal(5,3)) DESC";
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
				$query .= "OpenAccess DESC, SNIP_2014 DESC";
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
			$snip=number_format((float)$row[39], 3, '.', '');
			$rank=$row[19];
			$FiveIF=$row[34];
			$quartile=$row[29];
			$quartile=preg_replace("/Q/","","$quartile");
			$qrank=$row[28];
			$qcat=$row[27];
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
			if(($row[17] != "") && ($row[17] != " ")) { $ISSN .= ",".$row[17]; }
		
			$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue("A" . $i, "$quartile")
            	->setCellValue("B" . $i, "$qrank")
            	->setCellValue("C" . $i, "$qcat")
            	->setCellValue("D" . $i, "$IFscore")
				->setCellValue("E" . $i, "$FiveIF")
				->setCellValue("F" . $i, "$snip")
				->setCellValue("G" . $i, "$ISSN")
				->setCellValue("H" . $i, "$title")
				->setCellValue("I" . $i, "$row[30]")
				->setCellValue("J" . $i, "$row[4]")
				->setCellValue("K" . $i, "$row[6]")
				->setCellValue("L" . $i, "$row[8]")
				->setCellValue("M" . $i, "$OAccess");		
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
	exit;	

/////////////////////////////////////////////////////////// Footer

	include("./admin/era.dbdisconnect.php");

?>