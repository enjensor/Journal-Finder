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
//	20 September 2017
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	
/////////////////////////////////////////////////////////// Vars
	
	$eraid = $_GET["eraid"];
	$ISSNa = $_GET['ISSNa'];
	$shortversion = $_GET['shortversion'];
	$context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
	$contextTwo = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
	$url = "http://www.sherpa.ac.uk/romeo/api29.php?issn=".$ISSNa."&ak=AurhiRrRl5g";
	$urlTwo = "http://www.oaklist.qut.edu.au/api/basic?query=".$ISSNa;
	$fSherpa = "n";
	$fOaklist = "n";
	$data_page = "";
	$data_pageTwo = "";
	$doAPAIS_ERIH = "y";
	$apaisC = "";
	$apais = "No";
	$erih = "No";
	$erihD = "";
	$jTitle = "";
	$loaded = "";
	
//////////////////////////////////////////////////////////////////////////////// Check SHERPA RoMEO and OAKList data tables for pre-existing record

	if(($ISSNa != "")) {
	
		$query = "SELECT * FROM data_sherpa WHERE ISSN = \"$ISSNa\" ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			$data_page = $row[2];
			$fSherpa = "y";
			$loaded = "Cached Version";	
			if(preg_match("/Archiving Policies/i",$data_page) && ($eraid != "")) {
				$queryB = "UPDATE 2017_journals_final_list SET OpenAccess = \"Yes\" WHERE ERAID = \"$eraid\" ";
				$mysqli_resultB = mysqli_query($mysqli_link, $queryB);
			}		
		}
		
		if(($doOak == "y")) {
			$query = "SELECT * FROM data_oaklist WHERE ISSN = \"$ISSNa\" ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				$data_pageTwo = $row[2];
				$fOaklist = "y";
			}
		}
		
		$query = "SELECT Title FROM 2017_journals_final_list ";
		$query .= "WHERE ISSN1 = \"$ISSNa\" OR ISSN2 = \"$ISSNa\" OR ISSN3 = \"$ISSNa\" ";
		$query .= "OR ISSN4 = \"$ISSNa\" OR ISSN5 = \"$ISSNa\" OR ISSN6 = \"$ISSNa\" OR ISSN7 = \"$ISSNa\" ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			$jTitle = $row[0];
		}
	}
	
//////////////////////////////////////////////////////////////////////////////// Display journal title
	
	if(($jTitle != "")) {
		echo "<p><h3><em>$jTitle</em><br />&nbsp;</h3></p>";
	}
	
//////////////////////////////////////////////////////////////////////////////// Read SHERPA RoMEO XML Source
	
	if(($fSherpa != "y")) {
	
		$foundS = "";
		$xml = file_get_contents($url, false, $context);
		$sherpa = simplexml_load_string($xml);
		if(($sherpa) && ($ISSNa)) {
	
/////////////////////////////////////////////////////////// Start SHERPA RoMEO capture

			ob_start();
			$fSherpa = $sherpa->publishers->publisher->preprints->prearchiving[0];
			if(($fSherpa != "")) {
				
/////////////////////////////////////////////////////////// Header
				
				$foundS = "y";
				echo "<div id=\"srDisplay\">";
				echo "<p class=\"bg-success\" style=\"padding:10px;\"><strong>SHERPA/RoMEO Summary</strong></p>";
				echo "<p>&nbsp;</p>";
				
/////////////////////////////////////////////////////////// Parse archiving policies
				
				echo "<p style=\"padding-left:10px;\"><strong>Archiving Policies</strong></p>";
				echo "<p style=\"padding-left:10px;\">";
				echo "<ul>";
				echo "<li>".ucwords($sherpa->publishers->publisher->preprints->prearchiving[0])." archive pre-print</li>";
				echo "<li>".ucwords($sherpa->publishers->publisher->postprints->postarchiving[0])." archive post-print</li>";
				echo "<li>".ucwords($sherpa->publishers->publisher->pdfversion->pdfarchiving[0])." archive publisher version</li>";
				echo "</ul>";
				echo "</p>";
	
/////////////////////////////////////////////////////////// Parse archiving conditions
	
				$c = count($sherpa->publishers->publisher->conditions->condition);
				if(($c > 0)) {
					echo "<p style=\"padding-left:10px;\"><strong>Open Access Conditions</strong></p>";
					echo "<p style=\"padding-left:10px;\">";
					echo "<ul>";
					for($a=0;$a<$c;$a++) {
						if(($sherpa->publishers->publisher->conditions->condition[$a] != "") && ($sherpa->publishers->publisher->conditions->condition[$a] != " ")) {
							echo "<li>".$sherpa->publishers->publisher->conditions->condition[$a]."</li>";
						}
					}
					echo "</ul>";
					echo "</p>";
				}
		
/////////////////////////////////////////////////////////// Parse copyright conditions

				$c = count($sherpa->publishers->publisher->copyrightlinks->copyrightlink);
				if(($c > 0)) {
					echo "<p style=\"padding-left:10px;\"><strong>Publisher Policies</strong></p>";
					echo "<p style=\"padding-left:10px;\">";
					echo "<ul>";
					for($a=0;$a<$c;$a++) {
						$cText = ucwords($sherpa->publishers->publisher->copyrightlinks->copyrightlink[$a]->copyrightlinktext[0]);
						if(($cText != "") && ($cText != " ")) {
							$cUrl = $sherpa->publishers->publisher->copyrightlinks->copyrightlink[$a]->copyrightlinkurl[0];
							echo "<li><a href=\"$cUrl\" target=\"_blank\">$cText</a></li>";
						}
					}
					echo "</ul>";
					echo "</p>";
				}
				
/////////////////////////////////////////////////////////// Close SHERPA RoMEO found record parse
				
				echo "</div>";
				$sherpaUpdate = "y";
			} else {
				echo "<div id=\"srDisplay\"><p class=\"bg-info\" style=\"padding:10px;\"><strong>No SHERPA/RoMEO Data</strong></p></div>";
				$sherpaUpdate = "NO";
			}
			
/////////////////////////////////////////////////////////// Finish SHERPA RoMEO capture
					
			$data_page = ob_get_contents();
			ob_end_clean();
			
/////////////////////////////////////////////////////////// Update data tables

			if(($sherpaUpdate == "y") && ($eraid != "")) {
				$query = "UPDATE 2017_journals_final_list SET OpenAccess = \"Yes\" WHERE ERAID = \"$eraid\" ";
				$mysqli_result = mysqli_query($mysqli_link, $query);
			}

			if(($foundS == "y")) {
				$data_page = htmlentities($data_page, ENT_QUOTES,"UTF-8");
				$phptime = time() + (365 * 24 * 60 * 60);
				$query = "INSERT INTO data_sherpa VALUES (0, \"$ISSNa\", \"$data_page\", \"$phptime\") ";
				$mysqli_result = mysqli_query($mysqli_link, $query);
			}
		}
	}
	
//////////////////////////////////////////////////////////////////////////////// Read OAKList XML Source

	if(($doOak == "y")) {
		if(($fOaklist != "y")) {

			$foundO = "";
			$xmlTwo = file_get_contents($urlTwo, false, $contextTwo);
			$xmlTwo = preg_replace('/(\r?\n){2}/', "\n", $xmlTwo);
			$oaklist = simplexml_load_string($xmlTwo);
			if(($oaklist) && ($ISSNa)) {
			
/////////////////////////////////////////////////////////// Parse search summary
			
				ob_start();
				$fOaklist = $oaklist->searchresults->searchsummary->total[0];
				if(($fOaklist > 0)) {
				
/////////////////////////////////////////////////////////// Start OAKList capture
				
					echo "<div id=\"foDisplay\">";
					echo "<p class=\"bg-success\" style=\"padding:10px;\"><strong>OAKList Summary</strong></p>";
					echo "<p>&nbsp;</p>";
				
/////////////////////////////////////////////////////////// Parse journal description
				
					$oDesc = $oaklist->searchresults->results->searchresult->record[0]->description[0];
					if(($oDesc != "")) {
						echo "<p style=\"padding-left:10px;\"><strong>Description</strong></p>";
						echo "<p style=\"padding-left:10px;\">$oDesc</p>";
						$foundO = "y";
					}
				
/////////////////////////////////////////////////////////// Parse archiving policies
				
					$c = count($oaklist->searchresults->results->searchresult->record[0]->summaries->summary);
					if(($c > 0)) {
						$tempA = "";
						$tempB = "";
						echo "<p style=\"padding-left:10px;\"><strong>Archiving Policies</strong></p>";
						echo "<p style=\"padding-left:10px;\">";
						echo "<ul>";
						for($a=0;$a<$c;$a++) {
							$tempA = $oaklist->searchresults->results->searchresult->record[0]->summaries->summary[$a]->summarydescription;
							$tempA = trim($tempA);
							if(($tempA != "") && ($tempA != " ") && ($tempA != "$tempB")) {
								echo "<li>$tempA</li>";
								$tempB = $tempA;
								$foundO = "y";
							}
						}
						echo "</ul>";
						echo "</p>";
					}
				
/////////////////////////////////////////////////////////// Parse archiving conditions

					$c = count($oaklist->searchresults->results->searchresult->record[0]->conditions->condition);
					if(($c > 0)) {
						$tempA = "";
						$tempB = "";
						echo "<p style=\"padding-left:10px;\"><strong>Archiving Conditions</strong></p>";
						echo "<p style=\"padding-left:10px;\">";
						echo "<ul>";
						for($a=0;$a<$c;$a++) {
							$tempA = $oaklist->searchresults->results->searchresult->record[0]->conditions->condition[$a];
							$tempA = trim($tempA);
							if(($tempA != "") && ($tempA != " ") && ($tempA != "$tempB")) {
								echo "<li>$tempA</li>";
								$tempB = $tempA;
								$foundO = "y";
							}
						}
						echo "</ul>";
						echo "</p>";
					}

/////////////////////////////////////////////////////////// Close OAKList found record parse

					echo "</div>";
				} else {
					echo "<div id=\"foDisplay\"><p class=\"bg-info\" style=\"padding:10px;\"><strong>No OAKList Data</strong></p></div>";
				}
		
/////////////////////////////////////////////////////////// Finish OAKList capture
		
				$data_pageTwo = ob_get_contents();
				ob_end_clean();
			
/////////////////////////////////////////////////////////// Update data table
			
				if(($foundO == "y")) {
					$data_pageTwo = htmlentities($data_pageTwo, ENT_QUOTES,"UTF-8");
					$phptime = time() + (365 * 24 * 60 * 60);
					$query = "INSERT INTO data_oaklist VALUES (0, \"$ISSNa\", \"$data_pageTwo\", \"$phptime\") ";
					$mysqli_result = mysqli_query($mysqli_link, $query);
				}
			} 
		}
	}
	
//////////////////////////////////////////////////////////////////////////////// Get APAIS and ERIH data
	
	if(($ISSNa != "") && ($doAPAIS_ERIH == "y")) {
		
		$queryD = "SELECT Coverage FROM data_apais WHERE ISSN = \"$ISSNa\" ";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$apais = "Yes";
			$apaisC = $rowD[0];
		}
		
		$queryD = "SELECT Discipline, Category_2011 FROM data_erih WHERE ISSN = \"$ISSNa\" AND Category_2011 != \"\" ";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			$erihD = $rowD[0]; 
			$erih = "Yes";
		}
	}

//////////////////////////////////////////////////////////////////////////////// Display SHERPA RoMEO
			
	$data_page = html_entity_decode($data_page,ENT_QUOTES,"UTF-8");	
	echo $data_page;
	echo "<div id=\"paragraphSpace\"><p>&nbsp;</p></div>";
	
//////////////////////////////////////////////////////////////////////////////// Display OAKList record
	
	if(($doOak == "y")) {
		if(($foundO == "y")) {
			$data_pageTwo = html_entity_decode($data_pageTwo,ENT_QUOTES,"UTF-8");
			echo $data_pageTwo;
			echo "<div id=\"paragraphSpace\"><p>&nbsp;</p></div>";
		} else {
			echo "<div id=\"foDisplay\"><p class=\"bg-info\" style=\"padding:10px;\"><strong>No OAKList Data</strong></p></div>";
			echo "<div id=\"paragraphSpace\"><p>&nbsp;</p></div>";
		}
	}
	
//////////////////////////////////////////////////////////////////////////////// Display APAIS and ERIH details
	
	echo "<div id=\"apaisDisplay\">";
	echo "<p class=\"bg-success\" style=\"padding:10px;\"><strong>Other Data</strong></p>";
	echo "<p>&nbsp;</p>";
	echo "<p style=\"padding-left:10px;\"><strong>Indexed by Australian Public Affairs Information Service</strong></p>";
	echo "<p style=\"padding-left:10px;\">";
	echo "<ul>";
	echo "<li>$apais";
	if(($apais == "Yes")) { 
		echo " ($apaisC)"; 
	}
	echo "</li>";
	echo "</ul>";
	echo "</p>";
	echo "<p style=\"padding-left:10px;\"><strong>Indexed by European Reference Index for the Humanities</strong></p>";
	echo "<p style=\"padding-left:10px;\">";
	echo "<ul>";
	echo "<li>$erih";
	if(($erih == "Yes")) { 
		echo " ($erihD)"; 
	}
	echo "</li>";
	echo "</ul>";
	echo "</p>";
	echo "</div>";
	echo "<div id=\"paragraphSpace\"><p style=\"text-align:right;\">&nbsp;<br /><span style=\"font-size:0.7em;\">* $loaded</span><br />&nbsp;</p></div>";
		
//////////////////////////////////////////////////////////////////////////////// End
		
	include("./admin/era.dbdisconnect.php");	
	
?>