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
//	LATEST UPDATE
//	30 April 2018
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	$wildcard = "";
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	
/////////////////////////////////////////////////////////// Vars
	
	if(preg_match("/\*/i",$_GET['keywords'])) {
		$_GET['keywords'] = preg_replace("/\*/i","",$_GET['keywords']);
		$temp = preg_replace("/ /i","",$_GET['keywords']);
		if((strlen($temp) > 0)) {
			$wildcard = "y";
		}
	}
	
	$for2 = $_GET['for2'];
	$for4 = $_GET['for4'];
	$keywords = $_GET['keywords'];
	$eRAID = $_GET['eRAID'];
	$Conference = $_GET['Conference'];
	
	if(($keywords) && ($keywords != "VIEWSAVED")) {
		if(($wildcard != "y")) {
			$queryD = "SELECT FoR1, ERAID FROM 2017_journals_final_list WHERE Title = \"$keywords\" AND Title != \"\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				$forTemp = $rowD[0];
				$eRAID = $rowD[1];
				if(strlen($forTemp) > 2) {
					$for4 = $forTemp;
				} else {
					$for2 = $forTemp;
				}
			}
		}
	}
	
	$display = $_GET['display'];
	$Order = $_GET['Order'];
	$found = "";
	$dataShow = "n";
	
/////////////////////////////////////////////////////////// Logic switch
	
	if(($for2 OR $for4)) { $dataShow = "y"; }
	if(($keywords AND $wildcard == "y")) { $dataShow = "y"; }
	if(($keywords == "VIEWSAVED")) { $dataShow = "y"; }
	
/////////////////////////////////////////////////////////// Legend
	
	if(($dataShow == "y")) {
		echo "<div class=\"table-responsive\">\n";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			$query = "SELECT ForName, CitationAnalysis, ForCode FROM disciplinematrix WHERE (ForCode = \"$for2\" OR ForCode = \"$for4\")"; 
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				$forCode = $row[2];
				$forName = $row[0];
			}
		}
		echo "<input type=\"button\" id=\"Toggle\" class=\"Toggle\" value=\"Hide / Show Table Legend\"></input> ";
		echo "<input type=\"button\" id=\"ToggleB\" class=\"ToggleB\" value=\"Hide / Show Conference List\"></input> ";
		echo "<input type=\"button\" id=\"ToggleC\" class=\"ToggleC\" value=\"Hide / Show Cluster Menu\"></input> ";
		echo "<br />";
		echo "<div id=\"tableLegend\" name=\"tableLegend\" class=\"tableLegend\">\n";
		echo "&nbsp;<br />";
		echo "<table class=\"table table-bordered table-striped\" style=\"background-color: #ffffff;\">\n";
		echo "<thead><tr><th colspan=\"2\">Impact Data Legend</th></tr></thead>\n";
		echo "<tbody>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>Q</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">Quartile Rank. This is a Rank by Journal Impact Factor expressed as a quartile of the whole category. ";
		echo "Top ranked journals are in the first quartile, which is 1.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>Rank</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">This is the Rank placement by Journal Impact Factor of the journal in its Thomson Reuters subject category or categories. ";
		echo "The categories can be viewed by hovering the mouse over the rank value. ";
		echo "Please note that these categories do not necessarily align with ARC fields of research and that some journals may be in more than one category.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>IF</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">The Thomson Reuters impact factor is a measure of the frequency with which the 'average article' ";
		echo "in a journal has been cited in a particular year or period. ";
		echo "The annual JCR impact factor is a ratio between citations and recent citable items published. ";
		echo "Thus, the impact factor of a journal is calculated by dividing the number of current year citations to ";
		echo "the source items published in that journal during the previous two years.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>5YR IF</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">The 5-year journal Impact Factor is the average number of times articles from the journal published in the past five years have ";
		echo "been cited in the year. It is caclulated by dividing the number of citations in the year by the total number of articles published in the five previous years. ";
		echo "Although Impact Factors are based on cites to articles published in the previous two years, a base of five years may be more appropriate for journals in certain fields ";
		echo "because the body of citations may not be large enough to make reasonable comparisons, publication schedules may be consistently late, or it may take longer than two ";
		echo "years to disseminate and respond to published works.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>SNIP</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">SNIP or Source Normalized Impact per Paper is an index of citation impact calculated by Journal Metrics. ";
		echo "The SNIP value measures contextual citation impact by weighting citations based on the total number of citations in a subject field. ";
		echo "This means that the impact of a single citation is given higher value in subject areas where citations are less likely, and vice versa. ";
		echo "A field with journals having high SNIPs will have a high world standard.</td></tr>\n";		
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>TITLE - LINKS</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">If a data-match is found between Journal Finder ";
		echo "and an external service then a link will be provided under the title which ";
		echo "will create a search query on that same service. Currently, Journal Finder data is ";
		echo "checked against three external data providers. The first link is SJR or SCImago ";
		echo "Journal and Country Rank, which is a portal that includes the journals and country ";
		echo "scientific indicators developed from the information contained in the Scopus database. ";
		echo "These indicators can be used to assess and analyze scientific domains. The second ";
		echo "link is the UWS Library to see if the title is available. And the final link is Ulrichs Web Global ";
		echo "Serials Directory, which is a source of detailed information on more than 300,000 periodicals (also called serials) of all types.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>ABDC</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">In 2007, Australian Business Deans Council ";
		echo "established a Journal Quality list for use by its member business ";
		echo "schools. The aim of this initial list was to overcome the regional and discipline bias of international lists. ";
		echo "The current list comprises 2,767 ";
		echo "different journal titles, divided into four categories of quality, A*: 6.9%; A: 20.8%; ";
		echo "B: 28.4%; and C: 43.9% journals. In each Field of Research ";
		echo "(FoR) group, journals deemed NOT to reach the quality threshold level are not listed. The FoR that corresponds with the ";
		echo "ABDC value can be viewed by hovering the mouse cursor over the rank.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>FoR</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">The field(s) of research in the 2012 ERA Draft for this ";
		echo "journal. Hover your cursor over the FoR code for the full field name.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>OA</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">A tick indicates that the journal is indexed on the ";
		echo "Directory of Open Access publications.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>#</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">Click on the 'View' button to yield a 15 year ";
		echo "SNIP trend plus Open Access metadata, JCR impact factors and SCImago journal and country rank.</td></tr>\n";
		
		echo "</tbody>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "<p>&nbsp;</p>";		
		
/////////////////////////////////////////////////////////// Journal data
		
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {

////////////////////////////////// 2010 Snip from all indexed

//			$query = "SELECT (SUM(SNIP_2010) / COUNT(SNIP_2010)) AS AverageSnip ";
//			$query .= "FROM 2017_journals_final_list WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
//			$query .= "AND SNIP_2010 != \"\" AND SNIP_2010 IS NOT NULL ";
			
			$query = "SELECT (SUM(2010_SNIP) / COUNT(2010_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2010_SNIP != \"\" AND 2010_SNIP IS NOT NULL ";
			
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$Nten = number_format($row[0],3); 
			} 
			
////////////////////////////////// 2011 Snip from all indexed

//			$query = "SELECT (SUM(SNIP_2011) / COUNT(SNIP_2011)) AS AverageSnip ";
//			$query .= "FROM 2017_journals_final_list WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
//			$query .= "AND SNIP_2011 != \"\" AND SNIP_2011 IS NOT NULL ";

			$query = "SELECT (SUM(2011_SNIP) / COUNT(2011_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2011_SNIP != \"\" AND 2011_SNIP IS NOT NULL ";

			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NEleven = number_format($row[0],3); 
			} 

////////////////////////////////// 2012 Snip from all indexed

//			$query = "SELECT (SUM(SNIP_2012) / COUNT(SNIP_2012)) AS AverageSnip ";
//			$query .= "FROM 2017_journals_final_list WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
//			$query .= "AND SNIP_2012 != \"\" AND SNIP_2012 IS NOT NULL ";
			
			$query = "SELECT (SUM(2012_SNIP) / COUNT(2012_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2012_SNIP != \"\" AND 2012_SNIP IS NOT NULL ";
			
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NTwelve = number_format($row[0],3); 
			} 
			
////////////////////////////////// 2013 Snip from all indexed

//			$query = "SELECT (SUM(SNIP_2013) / COUNT(SNIP_2013)) AS AverageSnip ";
//			$query .= "FROM 2017_journals_final_list WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
//			$query .= "AND SNIP_2013 != \"\" AND SNIP_2013 IS NOT NULL "; 
			
			$query = "SELECT (SUM(2013_SNIP) / COUNT(2013_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2013_SNIP != \"\" AND 2013_SNIP IS NOT NULL ";
			
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NThirteen = number_format($row[0],3); 
			} 
			
////////////////////////////////// 2014 Snip from all indexed

//			$query = "SELECT (SUM(SNIP_2014) / COUNT(SNIP_2014)) AS AverageSnip ";
//			$query .= "FROM 2017_journals_final_list WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
//			$query .= "AND SNIP_2014 != \"\" AND SNIP_2014 IS NOT NULL ";
			
			$query = "SELECT (SUM(2014_SNIP) / COUNT(2014_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2014_SNIP != \"\" AND 2014_SNIP IS NOT NULL ";
			 
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NFourteen = number_format($row[0],3); 
			} 
			
////////////////////////////////// 2015 Snip from all indexed

//			$query = "SELECT (SUM(SNIP_2015) / COUNT(SNIP_2015)) AS AverageSnip ";
//			$query .= "FROM 2017_journals_final_list WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
//			$query .= "AND SNIP_2015 != \"\" AND SNIP_2015 IS NOT NULL "; 
			
			$query = "SELECT (SUM(2015_SNIP) / COUNT(2015_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2015_SNIP != \"\" AND 2015_SNIP IS NOT NULL ";
			
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NFifteen = number_format($row[0],3); 
			} 			

////////////////////////////////// 2016 Snip from all indexed

//			$query = "SELECT (SUM(SNIP_2016) / COUNT(SNIP_2016)) AS AverageSnip ";
//			$query .= "FROM 2017_journals_final_list WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
//			$query .= "AND SNIP_2016 != \"\" AND SNIP_2016 IS NOT NULL ";
			
			$query = "SELECT (SUM(2016_SNIP) / COUNT(2016_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2016_SNIP != \"\" AND 2016_SNIP IS NOT NULL ";
			
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NSixteen = number_format($row[0],3); 
			} 
			
////////////////////////////////// Calculate direction of SNIP			
			
			if(($NSixteen > $NFifteen)) {
				$snipc = "danger";
				$icon = "<i class=\"glyphicon glyphicon-arrow-up\"></i>";
			} else {
				$snipc = "success";
				$icon = "<i class=\"glyphicon glyphicon-arrow-down\"></i>";
			}
		
////////////////////////////////// Display field data	
		
			echo "<table class=\"table table-bordered table-responsive\" style=\"background-color: #ffffff;\>\n";
			echo "<thead><tr class=\"active\"><th colspan=\"6\"><h2>$forCode $forName</h2></th></tr></thead>\n";
			echo "<tbody>\n";
			
/////////////////////// Header			
			
			echo "<tr><td width=\"25%\" colspan=\"6\" style=\"color:#000000;";
			echo "background-color:#CFDFEB;text-align:left\">ANNUAL CITATION AVERAGES IN THIS FIELD OF RESEARCH</td></tr>\n";
			
/////////////////////// 2016
			
			echo "<tr>\n";
			echo "<td width=\"25%\" style=\"width:16%;\"><strong>2016 SNIP</strong></td>";
			echo "<td width=\"25%\" style=\"width:18%;\" class=\"$snipc\">$NSixteen $icon</td>";			
			
/////////////////////// 2015
			
			echo "<td width=\"25%\" style=\"width:16%;\"><strong>2015 SNIP</strong></td>";
			echo "<td width=\"25%\" style=\"width:18%;\">$NFifteen</td>";
			
/////////////////////// 2014
			
			echo "<td width=\"25%\" style=\"width:16%;\"><strong>2014 SNIP</strong></td>";
			echo "<td width=\"25%\" style=\"width:18%;\">$NFourteen</td>";
			echo "</tr>\n";
			
/////////////////////// 2013
			
			echo "<tr>\n";
			echo "<td width=\"25%\" style=\"width:16%;\"><strong>2013 SNIP</strong></td>";
			echo "<td width=\"25%\" style=\"width:18%;\">$NThirteen</td>";			
			
/////////////////////// 2012
			
			echo "<td width=\"25%\" style=\"width:16%;\"><strong>2012 SNIP</strong></td>";
			echo "<td width=\"25%\" style=\"width:17%;\">$NTwelve</td>";
			
/////////////////////// 2011
			
			echo "<td width=\"25%\" style=\"width:16%;\"><strong>2011 SNIP</strong></td>";
			echo "<td width=\"25%\" style=\"width:18%;\">$NEleven</td>";
			echo "</tr>\n";
			
////////////////////////////////// Close table			
			
			echo "</tbody>\n";
			echo "</table>\n";
			echo "<p><small>* A red background for the Mean 2016 SNIP indicates that it has risen since ";
			echo "the last ERA round; a green background indicates it has fallen since the last ERA round.</small><p>";
			echo "<p>&nbsp;</p>";
		
////////////////////////////////// Close wildcard switch
		
		}
		
//////////////////////////////////////////////////////////////////// Display conferences

		$ccount = 0;
		if(strlen($forCode > 2)) {
			$forCodeB = substr("$forCode",0,2);
		} else {
			$forCodeB = $forCode;
		}
		$query = "SELECT COUNT(*) FROM data_core WHERE Rank_FoR = \"$forCodeB\" OR Rank_FoR = \"$forCode\" "; 
		if(($Conference != "")) { 
			$query != "ORDER BY $Conference ASC ";
		}
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$ccount="$row[0]";	 
		}
		if(($ccount > 0)) {
			echo "<div id=\"tableConference\" name=\"tableConference\" class=\"tableConference\">\n";
			echo "<table class=\"table table-bordered table-striped tablesorter\" id=\"myTable2\" style=\"background-color: #ffffff;\>\n";
			echo "<thead><tr><th colspan=\"4\">";
			echo "<strong>$ccount Conference(s)</strong>";
			echo "<br />&nbsp;</th></tr></thead>\n";
			echo "<tbody>\n";
			echo "<tr class=\"warning\">\n";
			
////////////////////////////////////// Rank			
			
			echo "<td class=\"text-center\"><strong>";
			echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); ";
			echo "var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); ";
			echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
			if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
				echo "eRAID=".$eRAID."&";
			} else {
				echo "keywords=".$keywords."*&";
			}
			echo "for2=".$for2."&for4=".$for4."&Order=".$Order."&Conference=Rank' ); \" ";
			echo "data-toggle=\"tooltip\" title=\" \nClick to organise conferences by Rank\n \">";			
			echo "Rank</a><strong></td>";
			
////////////////////////////////////// Source
			
			echo "<td class=\"text-left\"><strong>Source<strong></td>";
			
////////////////////////////////////// Title
			
			echo "<td class=\"text-left\"><strong>";
			echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); ";
			echo "var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); ";
			echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
			if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
				echo "eRAID=".$eRAID."&";
			} else {
				echo "keywords=".$keywords."*&";
			}
			echo "for2=".$for2."&for4=".$for4."&Order=".$Order."&Conference=Title' ); \" ";
			echo "data-toggle=\"tooltip\" title=\" \nClick to organise conferences by Rank\n \">";			
			echo "Title</a><strong></td>";
			
////////////////////////////////////// Acronym
			
			echo "<td class=\"text-left\"><strong>";
			echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); ";
			echo "var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); ";
			echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
			if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
				echo "eRAID=".$eRAID."&";
			} else {
				echo "keywords=".$keywords."*&";
			}
			echo "for2=".$for2."&for4=".$for4."&Order=".$Order."&Conference=Acronym' ); \" ";
			echo "data-toggle=\"tooltip\" title=\" \nClick to organise conferences by Rank\n \">";			
			echo "Acronym</a><strong></td>";
			
////////////////////////////////////// FoR
			
			echo "<td class=\"text-left\"><strong>";
			echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); ";
			echo "var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); ";
			echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
			if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
				echo "eRAID=".$eRAID."&";
			} else {
				echo "keywords=".$keywords."*&";
			}
			echo "for2=".$for2."&for4=".$for4."&Order=".$Order."&Conference=Rank_FoR' ); \" ";
			echo "data-toggle=\"tooltip\" title=\" \nClick to organise conferences by Rank\n \">";			
			echo "FoR</a><strong></td>";
			
////////////////////////////////////// Finish
			
			echo "</tr>";
			if(($Conference != "")) { 
				$orderby = $Conference;
			} else {
				$orderby = "Title";
			}
			$query = "SELECT * FROM data_core WHERE Rank_FoR = \"$forCodeB\" OR Rank_FoR = \"$forCode\" ORDER BY ";
			$query .= "case when $orderby in('', '0') then 1 else 0 end, $orderby ASC, Title ASC ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				echo "<tr>";
				echo "<td class=\"text-center\">$row[4]</td>";
				echo "<td class=\"text-left\">$row[3]</td>";
				echo "<td class=\"text-left\">$row[1]</td>";
				echo "<td class=\"text-left\">$row[2]</td>";
				echo "<td class=\"text-left\">$row[6]</td>";
				echo "</tr>";
			}
			echo "</tbody>\n";
			echo "</table>\n";
			echo "<p>&nbsp;</p>";
			echo "</div>";
		} else {
			echo "<div id=\"tableConference\" name=\"tableConference\" class=\"tableConference\">\n";
			echo "<table class=\"table table-bordered table-striped tablesorter\" id=\"myTable2\" style=\"background-color: #ffffff;\>\n";
			echo "<thead><tr><th colspan=\"4\">";
			echo "<strong>0 Conference(s)</strong>";
			echo "<br />&nbsp;</th></tr></thead>\n";
			echo "</table>\n";
			echo "<p>&nbsp;</p>";
			echo "</div>";
		}
        
////////////////////////////////// Display journal data
		
        echo "<h3>";
		$count=0;
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			$query = "SELECT COUNT(*) FROM 2017_journals_final_list WHERE FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\" ORDER BY Title ASC"; 
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$count="$row[0]";	 
			}
		}
		if(($wildcard == "y") && ($keywords != "VIEWSAVED")) {
			$query = "SELECT COUNT(*) FROM 2017_journals_final_list WHERE Title LIKE \"%$keywords%\" ORDER BY Title ASC"; 
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$count="$row[0]";	 
			}			
		}
        if(($keywords == "VIEWSAVED")) {
			$count = count($_SESSION["ERAIDS"]);
		}
        if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "<strong>$count Journals</strong>";
		}
		if(($wildcard == "y") && ($keywords != "VIEWSAVED")) {
			echo "<strong>$count Journals with '".$keywords."' in title ...</strong>";
		}
		if(($keywords == "VIEWSAVED")) {
			echo "<strong>$count Journals saved for comparison ...</strong>";
		}  
        echo "</h3>";

////////////////////////////////// Display Short Links        
        
?>
        <br />
		<ul style="font-size: 0.9em !important; line-height: 1.4em;">
			<li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "")) { echo "color: #800000 !important;"; } ?>">Order by Journal Title</a></li>
            <li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=QUARTILE' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "QUARTILE")) { echo "color: #800000 !important;"; } ?>" <?php
                    echo "data-toggle=\"tooltip\" ";
                    echo "data-placement=\"right\" ";
                    echo "title=\" \nClick to organise by Quartile. ";
                    echo "This is a Rank by Journal Impact Factor ";
                    echo "expressed as a quartile of the whole category. ";
                    echo "Top ranked journals are in the first ";
                    echo "quartile, which is 1.\n \">";
                ?>Order by Quartile</a></li>
            <li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=QRANK' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "QRANK")) { echo "color: #800000 !important;"; } ?>" <?php
                    echo "data-toggle=\"tooltip\" ";
                    echo "data-placement=\"right\" ";
                    echo "title=\" \nClick to organise ";
                    echo "by Rank Placement. ";
                    echo "This is calculated by Journal Impact ";
                    echo "Factor of the journal in its ";
                    echo "Thomson Reuters subject category or ";
                    echo "categories.\n\n";
                    echo "Please note that these categories ";
                    echo "do not necessarily align with ARC ";
                    echo "fields of research and that some ";
                    echo "journals may be in more than ";
                    echo "one category.\n \">"; 
                ?>Order by Quartile Rank</a></li>
			<li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=IF' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "IF")) { echo "color: #800000 !important;"; } ?>" <?php
                    echo "data-toggle=\"tooltip\" ";
                    echo "data-placement=\"right\" ";
                    echo "title=\" \nClick to organise ";
                    echo "by Impact Factor score.\n \">"; 
                ?>Order by Impact Factor</a></li>
			<li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=5YR' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "5YR")) { echo "color: #800000 !important;"; } ?>" <?php
                    echo "data-toggle=\"tooltip\" ";
                    echo "data-placement=\"right\" ";
                    echo "title=\" \nClick to organise by 5 ";
                    echo "year Impact Factor score.\n \">"; 
                ?>Order by Impact Factor (5 Yr)</a></li>
			<li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=SNIP' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "SNIP")) { echo "color: #800000 !important;"; } ?>" <?php
                    echo "data-toggle=\"tooltip\" ";
                    echo "data-placement=\"right\" ";
                    echo "title=\" \nClick to organise by ";
                    echo "Source Normalized Impact per Paper ";
                    echo "scores.\n \">"; 
                ?>Order by SNIP</a></li>
			<li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=ABDC' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "ABDC")) { echo "color: #800000 !important;"; } ?>" <?php
                    echo "data-toggle=\"tooltip\" ";
                    echo "data-placement=\"right\" ";
                    echo "title=\" \nOrder by Australian Business Deans ";
                    echo "Council journal quality rank.\n \">"; 
                ?>Order by ABDC</a></li>
			<li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=OA' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "OA")) { echo "color: #800000 !important;"; } ?>" <?php
                    echo "data-toggle=\"tooltip\" ";
                    echo "data-placement=\"right\" ";
                    echo "title=\" \nOrder by Open Access\n \">"; 
                ?>Order by Open Access</a></li>
		</ul>
<?php  
        
/////////////////////////////////////////////////////////////// Icon legend
        
        echo "<p style=\"padding-top: 10px; padding-bottom: 20px; \">";
        echo "<em>If the these icons appear under a journal title they are links to the following websites and can be clicked on for seeing the indexed information for that journal title on the selected website.</em>";
        echo "<br /><br />"; 
        echo "<img ";
        echo "src=\"./assets/images/link_elsevier.png\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.3em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;Elsevier&nbsp;&nbsp;&nbsp;";
        echo "<img ";
        echo "src=\"./assets/images/link_sjr.jpg\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.3em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;Scimago Journal & Country Rank&nbsp;&nbsp;&nbsp;";
        echo "<img ";
        echo "src=\"./assets/images/link_ulrichsweb.jpg\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.3em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;Ulrichsweb&nbsp;&nbsp;&nbsp;";
        echo "<img ";
        echo "src=\"./assets/images/link_wsu.jpg\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.3em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;Western Sydney University Library";
        echo "</p>";
		
/////////////////////////////////////////////////////////////// Search in-page bar
		 
		echo "<p>";
		echo "<form id=\"live-search\" action=\"\" method=\"post\" class=\"form-inline\" role=\"form\">";
		echo "<div class=\"form-group\">";
		echo "<div class=\"input-group\">";
		echo "<div class=\"input-group-addon\"><i class=\"glyphicon glyphicon-search\"></i></div>";
		echo "<input class=\"form-control input-lg\" type=\"text\" id=\"filter\" value=\"\" placeholder=\"Type a keyword from a journal title to search this page ...\" />";
		echo "<div class=\"input-group-addon\" style=\"background-color: #23748F;\">";
		echo "<a href=\"./arc_journals_download.php?forCode=$forCode&Order=$Order&keywords=$keywords&eRAID=$eRAID\" data-toggle=\"tooltip\" ";
		echo "title=\" \nClick here to download the\nresults on this page as\nan Excel Spreadsheet.";
		echo "\nPlease allow up to a minute for\nthe file to be generated.\n \" style=\"color: #ffffff;\">";
		echo "<strong>Download Results (.XLS)</strong></a></div>";
		echo "</div>";
		echo "</div>";
		echo "</form>";
		echo "</p>";
  
////////////////////////////////// Open Table        
        
		echo "<table class=\"table table-bordered table-striped tablesorter\" ";
		echo "id=\"myTable\" style=\"background-color: #ffffff;\">\n";
		echo "<tbody>\n";
	
//////////////////////////////////////////// Start rows
	
		echo "<tr>\n";

//////////////////////////////////////////// Q

		echo "<td class=\"text-right\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); ";
		echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=QUARTILE' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to organise by Quartile. This is a Rank by Journal Impact Factor expressed as a quartile of the whole category. ";
		echo "Top ranked journals are in the first quartile, which is 1.\n \">";
		echo "Q</a></strong></td>\n";
		
//////////////////////////////////////////// Rank		
		
		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); ";
		echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=QRANK' ); \" ";
		echo "data-toggle=\"tooltip\" data-placement=\"top\" title=\" \nClick to organise by Rank Placement. ";
		echo "This is calculated by Journal Impact Factor of the journal in its Thomson Reuters subject category or ";
		echo "categories.\n\nHover the cursor over a rank to view publication JCR categories.\n\n";
		echo "Please note that these categories do not necessarily align with ACR fields of research and that some journals may be in more than one category.\n \">";
		echo "Rank</a></strong></td>\n";
		
//////////////////////////////////////////// IF		
		
		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=IF' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to organise by Impact Factor score (2016 value).\n \">";
		echo "IF</a></strong></td>\n";
		
//////////////////////////////////////////// 5YR IF

		echo "<td class=\"text-right\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=5YR' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to organise by 5 year Impact Factor score (2016 value).\n \">";
		echo "5YR&nbsp;IF</a></strong></td>\n";
		
//////////////////////////////////////////// SNIP

		echo "<td class=\"text-right\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=SNIP' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to organise by Source Normalized Impact per Paper scores (2016 value).\n \">";
		echo "SNIP</a></strong></td>\n";

//////////////////////////////////////////// ISSN

		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>ISSN(s)</strong></td>\n";
		
//////////////////////////////////////////// Title
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>Title</strong></td>\n";
		
//////////////////////////////////////////// ABDC		
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=ABDC' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nAustralian Business Deans Council journal quality rank.\n \">";
		echo "ABDC</a></strong></td>\n";
		
//////////////////////////////////////////// FoRs 1-3		
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=FoR1' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nField of Research.\n \">";
		echo "FoR1</a></strong></td>\n";
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=FoR2' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nField of Research.\n \">";
		echo "FoR2</a></strong></td>\n";
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=FoR3' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nField of Research.\n \">";
		echo "FoR3</a></strong></td>\n";
		
//////////////////////////////////////////// OA		
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); ";
		echo "var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=OA' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nOpen Access?\n \">";
		echo "OA</a></strong></td>\n";
		
//////////////////////////////////////////// Buttons		
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>#</strong></td>\n";
		echo "</tr>\n";
		
////////////////////////////////// Selected journal from search
		
		$m = 0;
		$eRaids = array();
		if(($eRAID != "") && ($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			$query = "SELECT * FROM 2017_journals_final_list WHERE ERAID = \"$eRAID\" ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$m++;
				$eRaids[$m] = $row[1];
				$snip=number_format((float)$row[39], 3, '.', '');
				$rank=$row[19];
				$quartile=$row[29];
				$quartile=preg_replace("/Q/","","$quartile");
				$qrank=$row[28];
				$qcat=$row[27];
				$OAccess=$row[24];
				$fiveyrif=$row[34];
				$IFscore=number_format((float)$row[25], 3, '.', '');
				$AIscore=number_format((float)$row[26], 3, '.', '');
				$OAccessImg="";
				if(($OAccess != "")) {
					$OAccessImg = "<span class=\"glyphicon glyphicon glyphicon-ok\"></span>";	
				}
				$NJ="";
				$newJournal=$row[18];
				$pattern="/Y$forCode,/i";
				$pattern2="/N$forCode,/i";
				if(preg_match("$pattern","$newJournal")) { $NJ = "Yes";$rowClass = "info"; }
				if(preg_match("$pattern2","$newJournal")) { $NJ = ""; $rowClass = ""; }
				if(($rank == "No")) { $rank = "";}
				if(($snip == "No") OR ($snip == "0.000")) { $snip = ""; }
				$row[2]= preg_replace("/'/i","\\'","$row[2]");
				if(($IFscore == "0.000")) { $IFscore = ""; }
				if(($AIscore == "0.000")) { $AIscore = ""; }
				
				$quartile = rtrim($quartile, "; ");
				$qcat = rtrim($qcat, "; ");
				$qrank = rtrim($qrank, "; ");
				$quartile = preg_replace("/; /i","<br />","$quartile");
				$qcat = preg_replace("/; /i","; ","$qcat");
				$qrank = preg_replace("/; /i","<br />","$qrank");
				
				$IFscore = number_format($IFscore,3);
				$fiveyrif = number_format($fiveyrif,3);
				$snip = number_format($snip,3);
				
				echo "<tr class=\"success\">\n";
				echo "<td class=\"text-center\">$quartile</a></td>";
				echo "<td class=\"text-center\"><a href=\"#\" data-toggle=\"tooltip\" title=\"$qcat\" style=\"color:#000000;text-decoration:none;\">$qrank</a></td>";
				echo "<td class=\"text-right\">$IFscore</td>";
				echo "<td class=\"text-right\">$fiveyrif</td>";
				echo "<td class=\"text-right\">$snip</td>";
				echo "<td class=\"text-center\" nowrap>";
				for($r=0;$r<7;$r++) {
					$q=(10+$r);
					if(($row[$q] != "") && ($row[$q] != " ")) {
						echo "$row[$q]<br />";
					}
				}
				echo "</td>";
				echo "<td class=\"text-left\">";
				echo stripslashes(htmlentities($row[2]));
				echo "<br />";
				echo "<div style=\"float:right; position:relative; padding-top: 10px;\">";
                
////////////////////////////////////////////////////////////////// Open Form                
                
				echo "<form action=\"https://ulrichsweb.serialssolutions.com/widget/search/?query=";
				echo $row[10];
				if(($row[11] != "")) { echo " OR ".$row[11]; }
				if(($row[12] != "")) { echo " OR ".$row[12]; }
				echo "\" method=\"POST\" target=\"_UlrichSearch\" style=\"margin-bottom:0px; padding: 0px;\">";
                
////////////////////////////////////////////////////////////////// Elsevier
				
				if(($row[32] != "")) {
					echo "<a title=\"Search for this on the Elsevier website\" href=\"";
					echo "https://www.elsevier.com/search-results?query=";
					$searchTitlePub = preg_replace("/\s\s/"," ","$row[2]");
					$searchTitlePub = preg_replace("/\s/","+","$row[2]");
					echo "$searchTitlePub";
					echo "&labels=journals";
					echo "\" target=\"_ElsevierSearch\" style=\"margin:0px; padding: 0px;\">";
//				    echo "<img src=\"./img/link_elsevier.png\" height=\"20\" ";
//				    echo "border=\"0\" style=\"margin-top:0px; margin-bottom:14px; padding: 0px;\">";
                    echo "<img src=\"./assets/images/link_elsevier.png\" ";
                    echo "height=\"30\" border=\"0\" style=\"";
                    echo "margin-top: 0px; ";
                    echo "margin-bottom: 0px; ";
                    echo "vertical-align: top; ";
                    echo "padding: 0px; ";
                    echo "padding-right: 0.3em; ";
                    echo "border: 0px solid #222222; \">";
					echo "</a> ";
				}
				
////////////////////////////////////////////////////////////////// SJR

				if(($row[33] != "")) {
					echo "<a title=\"Search for this on the Scimago Journal & Country Rank website\" href=\"";
                    echo "https://www.scimagojr.com/";
                    echo "journalsearch.php?q=".$row[33]."&tip=iss\" target=\"_SJRSearch\" ";
                    echo "style=\"margin:0px; padding: 0px;\">";
//				    echo "<img src=\"./img/link_sjr.png\" height=\"20\" ";
//                  echo "border=\"0\" style=\"margin-top:0px; margin-bottom:14px; padding: 0px;\">";
                    echo "<img src=\"./assets/images/link_sjr.jpg\" ";
                    echo "height=\"30\" border=\"0\" style=\"";
                    echo "margin-top: 0px; ";
                    echo "margin-bottom: 0px; ";
                    echo "vertical-align: top; ";
                    echo "padding: 0px; ";
                    echo "padding-right: 0.3em; ";
                    echo "border: 0px solid #222222; \">";
					echo "</a> ";
				}
                
////////////////////////////////////////////////////////////////// Ulrich
				
//			    echo "<input type=\"image\" src=\"./img/link_ulrich.png\" alt=\"Search Ulrich Database\" ";
//	            echo "style=\"border: 1px solid #aaaaaa; margin-top:3px; margin-bottom:0px; padding: 0px;\" /> ";  
                echo "<input type=\"image\" ";
                echo "src=\"./assets/images/link_ulrichsweb.jpg\" ";
                echo "alt=\"Search Ulrich Database\" ";
                echo "style=\"";
                echo "margin-top: 0px; ";
                echo "margin-bottom: 0px; ";
                echo "padding: 0px; ";
                echo "width: 35px !important; ";
                echo "height: 30px !important; ";
                echo "vertical-align: top; ";
                echo "padding-right: 0.3em; ";
                echo "border: 0px solid #222222; ";
                echo "\" /> ";  
                
////////////////////////////////////////////////////////////////// UWS Library
				
				echo "<a title=\"Search for this on the WSU Library website\" href=\"";
                echo "https://west-sydney-primo.hosted.exlibrisgroup.com/";
                echo "primo-explore/search?query=title,exact,";
				echo htmlentities($row[2]);
				echo ",AND&pfilter=pfilter,exact,journals,AND&tab=default_tab&";
                echo "search_scope=default_scope&vid=UWS-ALMA&lang=en_US&";
                echo "mode=advanced&offset=0&fn=search";
				echo "\" target=\"_LibrarySearch\" style=\"margin:0px; padding: 0px;\">";
//			    echo "<img src=\"./img/link_library.png\" height=\"20\" ";
//              echo "border=\"0\" style=\"margin-top:0px; margin-bottom:14px; padding: 0px;\">";
                echo "<img src=\"./assets/images/link_wsu.jpg\" height=\"30\" ";
                echo "border=\"0\" ";
                echo "style=\"";
                echo "margin-top: 0px; ";
                echo "vertical-align: top; ";
                echo "margin-bottom: 0px; ";
                echo "padding: 0px; ";
                echo "padding-right: 0px; ";
                echo "border: 0px solid #222222; ";
                echo "\">";
				echo "</a> ";               
                
////////////////////////////////////////////////////////////////// Close Form                
                
				echo "</form>";
				echo "</div>";
				echo "</td>";
				
				echo "<td class=\"text-center\"><a href=\"#\" data-toggle=\"tooltip\" title=\"$row[31]\" style=\"color:#000000;text-decoration:none;\">$row[30]</a></td>";
				
				echo "<td class=\"text-left\"><a ";
				echo "href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?eRAID=".$eRAID."&for4=".$row[4]."&for2=&Order=' ); \" ";
				echo "data-toggle=\"tooltip\" title=\"$row[5]\" style=\"color:#000000;text-decoration:none;\">$row[4]</a></td>";
				echo "<td class=\"text-left\"><a ";
				echo "href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?eRAID=".$eRAID."&for4=".$row[6]."&for2=&Order=' ); \" ";
				echo "data-toggle=\"tooltip\" title=\"$row[7]\" style=\"color:#000000;text-decoration:none;\">$row[6]</a></td>";
				echo "<td class=\"text-left\"><a ";
				echo "href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?eRAID=".$eRAID."&for4=".$row[8]."&for2=&Order=' ); \" ";
				echo "data-toggle=\"tooltip\" title=\"$row[9]\" style=\"color:#000000;text-decoration:none;\">$row[8]</a></td>";
			
/////////////////////////////////////////////////////////// View button trigger
			
				echo "<td class=\"text-center\">$OAccessImg</td>";
				echo "<td class=\"text-left\"><a data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" class=\"btn ";
				if(($snip != "")) {
					echo "btn-warning ";
					$fsnip = "y";
				} else {
					echo "btn-default ";
					$fsnip = "n";
				}
				echo "btn-sm\" ";
                echo "style=\"width: 75px !important;\" ";
				echo "href=\"./arc_modal.php?eraid=$row[1]&fsnip=$fsnip&AmeanSnip=$NSixteen&for4=$for4&for2=$for\">View</a>";
				echo "</td>";
				echo "</tr>";		
			}
		}
		
////////////////////////////////// All journals non-wildcard
		
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
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
				$query .= "case when SNIP_2013 in('', '0') then 1 else 0 end, convert(`SNIP_2013`, decimal(5,3)) DESC";
			} 
			if(($Order == "5YR")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when 5YR_IMPACT_FACTOR in('', '0') then 1 else 0 end, convert(`5YR_IMPACT_FACTOR`, decimal(5,3)) DESC";
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
				$query .= "OpenAccess DESC, SNIP_2013 DESC";
			} 
			if(($Order == "")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY Title ASC"; 
			}
		}
			
////////////////////////////////// All journals with wildcard
			
		if(($wildcard == "y") && ($keywords != "VIEWSAVED")) {
			if(($Order == "QUARTILE")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when JCR_Quartile in('', '0') then 1 else 0 end, JCR_Quartile ASC, cast(JCR_Rank as unsigned)";
			} 
			if(($Order == "QRANK")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when JCR_Rank in('', '0') then 1 else 0 end, cast(JCR_Rank as unsigned), JCR_Quartile ASC";
			} 
			if(($Order == "SNIP")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when SNIP_2013 in('', '0') then 1 else 0 end, convert(`SNIP_2013`, decimal(5,3)) DESC";
			} 
			if(($Order == "5YR")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when 5YR_IMPACT_FACTOR in('', '0') then 1 else 0 end, convert(`5YR_IMPACT_FACTOR`, decimal(5,3)) DESC";
			} 
			if(($Order == "IF")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when IF_2012 in('', '0') then 1 else 0 end, convert(`IF_2012`, decimal(5,3)) DESC";
			}
			if(($Order == "AIS")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when AIS_2012 in('', '0') then 1 else 0 end, convert(`AIS_2012`, decimal(5,3)) DESC";
			}
			if(($Order == "Rank")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when Rank_2010 in('', '0') then 1 else 0 end, Rank_2010 ASC, Title ASC";
			}
			if(($Order == "ABDC")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when ABDC_Rank in('', '0') then 1 else 0 end, ABDC_Rank ASC, Title ASC";
			}
			if(($Order == "FoR1")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when FoR1 in('', '0') then 1 else 0 end, FoR1 ASC, Title ASC"; 
			} 
			if(($Order == "FoR2")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when FoR2 in('', '0') then 1 else 0 end, FoR2 ASC, Title ASC"; 
			} 
			if(($Order == "FoR3")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when FoR3 in('', '0') then 1 else 0 end, FoR3 ASC, Title ASC"; 
			} 
			if(($Order == "OA")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "OpenAccess DESC, SNIP_2013 DESC";
			} 
			if(($Order == "")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY Title ASC"; 
			}			
		}
		
////////////////////////////////// All saved journals
		
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
				$query .= "case when SNIP_2013 in('', '0') then 1 else 0 end, convert(`SNIP_2013`, decimal(5,3)) DESC";
			} 
			if(($Order == "5YR")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when 5YR_IMPACT_FACTOR in('', '0') then 1 else 0 end, convert(`5YR_IMPACT_FACTOR`, decimal(5,3)) DESC";
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
				$query .= "OpenAccess DESC, SNIP_2013 DESC";
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
		
			$m++;
			$eRaids[$m] = $row[1];
			$snip=number_format((float)$row[39], 3, '.', '');
			$rank=$row[19];
			$quartile=$row[29];
			$quartile=preg_replace("/Q/","","$quartile");
			$qrank=$row[28];
			$qcat=$row[27];
			$OAccess=$row[24];
			$fiveyrif=$row[34];
			$IFscore=number_format((float)$row[25], 3, '.', '');
			$AIscore=number_format((float)$row[26], 3, '.', '');
			$OAccessImg="";
			if(($OAccess != "")) {
				$OAccessImg = "<span class=\"glyphicon glyphicon glyphicon-ok\"></span>";	
			}
			$NJ="";
			$newJournal=$row[18];
			$pattern="/Y$forCode,/i";
			$pattern2="/N$forCode,/i";
			if(preg_match("$pattern","$newJournal")) { 
				$NJ = "Yes"; 
			//	$rowClass = "info"; 
			}
			if(preg_match("$pattern2","$newJournal")) { $NJ = ""; $rowClass = ""; }
			if(($rank == "No")) { $rank = "";}
			if(($snip == "No") OR ($snip == "0.000")) { $snip = ""; }
			$row[2]= preg_replace("/'/i","\\'","$row[2]");
			if(($IFscore == "0.000")) { $IFscore = ""; }
			if(($AIscore == "0.000")) { $AIscore = ""; }
			
			$quartile = rtrim($quartile, "; ");
			$qcat = rtrim($qcat, "; ");
			$qrank = rtrim($qrank, "; ");
			$quartile = preg_replace("/; /i","<br />","$quartile");
			$qcat = preg_replace("/; /i",". ","$qcat");
			$qrank = preg_replace("/; /i","<br />","$qrank");
			
			$IFscore = number_format($IFscore,3);
			$fiveyrif = number_format($fiveyrif,3);
			$snip = number_format($snip,3);
			
			echo "<tr class=\"$rowClass\">\n";
			echo "<td class=\"text-center\">$quartile</a></td>";
			echo "<td class=\"text-center\">";
			echo "<a href=\"#\" data-toggle=\"tooltip\" title=\"$qcat\" style=\"color:#000000;text-decoration:none;\">$qrank</a></td>";
			echo "<td class=\"text-right\">$IFscore</td>";
			echo "<td class=\"text-right\">$fiveyrif</td>";
			echo "<td class=\"text-right\">$snip</td>";
			echo "<td class=\"text-center\" nowrap>";
			for($r=0;$r<7;$r++) {
				$q=(10+$r);
				if(($row[$q] != "") && ($row[$q] != " ")) {
					echo "$row[$q]<br />";
				}
			}
			echo "</td>";
			echo "<td class=\"text-left\">";
			echo stripslashes(htmlentities($row[2]));
			echo "<br />";
			echo "<div style=\"float:right; position:relative; padding-top: 10px;\">";
            
////////////////////////////////////////////////////////////////// Open Form            
            
			echo "<form action=\"https://ulrichsweb.serialssolutions.com/widget/search/";
			echo $row[10];
			if(($row[11] != "")) { echo " OR ".$row[11]; }
			if(($row[12] != "")) { echo " OR ".$row[12]; }
			echo "\" method=\"POST\" target=\"_UlrichSearch\" style=\"margin-bottom:0px; padding: 0px;\">"; 
            echo "<input type=\"hidden\" name=\"query\" value=\"";
            echo $row[10];
            if(($row[11] != "")) { echo " OR ".$row[11]; }
            if(($row[12] != "")) { echo " OR ".$row[12]; }
            echo"\">";
			
////////////////////////////////////////////////////////////////// Elsevier
			
			if(($row[32] != "")) {
				echo "<a title=\"Search for this on the Elsevier website\" href=\"";
				echo "https://www.elsevier.com/search-results?query=";
				$searchTitlePub = preg_replace("/\s\s/"," ","$row[2]");
				$searchTitlePub = preg_replace("/\s/","+","$row[2]");
				echo "$searchTitlePub";
				echo "&labels=journals";
				echo "\" target=\"_ElsevierSearch\" style=\"margin:0px; padding: 0px;\">";
//				echo "<img src=\"./img/link_elsevier.png\" height=\"20\" ";
//				echo "border=\"0\" style=\"margin-top:0px; margin-bottom:14px; padding: 0px;\">";
                echo "<img src=\"./assets/images/link_elsevier.png\" ";
                echo "height=\"30\" border=\"0\" style=\"";
                echo "margin-top: 0px; ";
                echo "margin-bottom: 0px; ";
                echo "vertical-align: top; ";
                echo "padding: 0px; ";
                echo "padding-right: 0.3em; ";
                echo "border: 0px solid #222222; \">";
				echo "</a> ";
			}
			
////////////////////////////////////////////////////////////////// SJR

			if(($row[33] != "")) {
				echo "<a title=\"Search for this on the Scimago Journal & Country Rank website\" href=\"";
                echo "https://www.scimagojr.com/";
                echo "journalsearch.php?q=".$row[33]."&tip=iss\" target=\"_SJRSearch\" ";
                echo "style=\"margin:0px; padding: 0px;\">";
//				echo "<img src=\"./img/link_sjr.png\" height=\"20\" ";
//              echo "border=\"0\" style=\"margin-top:0px; margin-bottom:14px; padding: 0px;\">";
                echo "<img src=\"./assets/images/link_sjr.jpg\" ";
                echo "height=\"30\" border=\"0\" style=\"";
                echo "margin-top: 0px; ";
                echo "margin-bottom: 0px; ";
                echo "vertical-align: top; ";
                echo "padding: 0px; ";
                echo "padding-right: 0.3em; ";
                echo "border: 0px solid #222222; \">";
				echo "</a> ";
			}
			
////////////////////////////////////////////////////////////////// Ulrich
			
//			echo "<input type=\"image\" src=\"./img/link_ulrich.png\" alt=\"Search Ulrich Database\" ";
//			echo "style=\"border: 1px solid #aaaaaa; margin-top:3px; margin-bottom:0px; padding: 0px;\" /> ";  
            echo "<input type=\"image\" ";
            echo "src=\"./assets/images/link_ulrichsweb.jpg\" ";
            echo "alt=\"Search Ulrich Database\" ";
            echo "style=\"";
            echo "margin-top: 0px; ";
            echo "margin-bottom: 0px; ";
            echo "padding: 0px; ";
            echo "width: 35px !important; ";
            echo "height: 30px !important; ";
            echo "vertical-align: top; ";
            echo "padding-right: 0.3em; ";
            echo "border: 0px solid #222222; ";
            echo "\" /> ";
            
////////////////////////////////////////////////////////////////// UWS Library
		
            echo "<a title=\"Search for this on the WSU Library website\" href=\"";
			echo "https://west-sydney-primo.hosted.exlibrisgroup.com/primo-explore/search?query=title,exact,";
			echo htmlentities($row[2]);
			echo ",AND&pfilter=pfilter,exact,journals,AND&tab=default_tab&";
            echo "search_scope=default_scope&vid=UWS-ALMA&lang=en_US&mode=advanced&offset=0&fn=search";
			echo "\" target=\"_LibrarySearch\" style=\"margin:0px; padding: 0px;\">";
//			echo "<img src=\"./img/link_library.png\" height=\"20\" ";
//          echo "border=\"0\" style=\"margin-top:0px; margin-bottom:14px; padding: 0px;\">";
            echo "<img src=\"./assets/images/link_wsu.jpg\" height=\"30\" ";
            echo "border=\"0\" ";
            echo "style=\"";
            echo "margin-top: 0px; ";
            echo "vertical-align: top; ";
            echo "margin-bottom: 0px; ";
            echo "padding: 0px; ";
            echo "padding-right: 0px; ";
            echo "border: 0px solid #222222; ";
            echo "\">";
			echo "</a> ";            
            
////////////////////////////////////////////////////////////////// Close Form            
            
			echo "</form>";
			echo "</div>";
			echo "</td>";
			
			echo "<td class=\"text-center\"><a href=\"#\" data-toggle=\"tooltip\" title=\"$row[31]\" style=\"color:#000000;text-decoration:none;\">$row[30]</a></td>";
				
			echo "<td class=\"text-left\"><a ";
			echo "href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?eRAID=".$row[1]."&for4=".$row[4]."&for2=&Order=' ); \" ";
			echo "data-toggle=\"tooltip\" title=\"$row[5]\" style=\"color:#000000;text-decoration:none;\">$row[4]</a></td>";
			echo "<td class=\"text-left\"><a ";
			echo "href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?eRAID=".$row[1]."&for4=".$row[6]."&for2=&Order=' ); \" ";
			echo "data-toggle=\"tooltip\" title=\"$row[7]\" style=\"color:#000000;text-decoration:none;\">$row[6]</a></td>";
			echo "<td class=\"text-left\"><a ";
			echo "href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?eRAID=".$row[1]."&for4=".$row[8]."&for2=&Order=' ); \" ";
			echo "data-toggle=\"tooltip\" title=\"$row[9]\" style=\"color:#000000;text-decoration:none;\">$row[8]</a></td>";
			
/////////////////////////////////////////////////////////// View, Save and Remove buttons triggers
			
			echo "<td class=\"text-center\">$OAccessImg</td>";
			echo "<td class=\"text-left\" style=\"white-space: nowrap;\" nowrap><a data-toggle=\"modal\" ";
			echo "data-target=\".bs-example-modal-lg\" class=\"btn ";
			if(($snip != "")) {
				echo "btn-warning ";
				$fsnip = "y";
			} else {
				echo "btn-default ";
				$fsnip = "n";
			}
			echo "btn-sm\" ";
            echo "style=\"width: 75px !important; margin-bottom: 5px !important; \" ";
			echo "href=\"./arc_modal.php?";
			echo "eraid=$row[1]&fsnip=$fsnip&AmeanSnip=$NSixteen&for4=$for4&for2=$for2\">";
			echo "View";
			echo "</a><br />";
			$eraidz = $row[1];
			$myVar++;
			if(($_SESSION["ERAIDS"]["$eraidz"] == "")) {
				
				echo "<button id=\"eraidSave_".$myVar."\" class=\"btn btn-default btn-sm\" ";
                echo "style=\"width: 75px !important; margin-bottom: 5px !important; \" ";
                echo ">";
				echo "<a id=\"eraidSaveHref_".$myVar."\" style=\"color: #000000; text-decoration: none;\" ";
				echo "href=\"javascript: ";
				echo "var eraidsaver = $.post('./arc_journals_saved.php',";
				echo "{'eraidSave': '$eraidz', 'doAction': 'SAVE'}).done(function(){";
				echo "var eraiddisable = $('#eraidSave_".$myVar."').attr('disabled','disabled');";
				echo "var eraiddisableB = $('#eraidSave_".$myVar."').addClass('btn-info');";
				echo "var eraiddisableC = $('#eraidSave_".$myVar."').removeClass('btn-default');";
				echo "var eraiddisableD = $('#eraidSaveHref_".$myVar."').css('color','white');";
				echo "var eraidremove = $('#eraidRemove_".$myVar."').removeAttr('disabled');";
				echo "});";
				echo "\">";
				echo "Save";
				echo "</a>";
				echo "</button><br />";
				
				echo "<button id=\"eraidRemove_".$myVar."\" class=\"btn btn-default btn-sm\" disabled ";
                echo "style=\"width: 75px !important; margin-bottom: 5px !important; \" ";
                echo ">";
				echo "<a style=\"color: #000000; text-decoration: none;\" ";
				echo "href=\"javascript: ";
				echo "var eraidsaver = $.post('./arc_journals_saved.php',";
				echo "{'eraidSave': '$eraidz', 'doAction': 'REMOVE'}).done(function(){";
				echo "var eraidremove = $('#eraidSave_".$myVar."').removeAttr('disabled');";
				echo "var eraiddisableB = $('#eraidSave_".$myVar."').addClass('btn-default');";
				echo "var eraiddisableC = $('#eraidSave_".$myVar."').removeClass('btn-info');";
				echo "var eraiddisableD = $('#eraidSaveHref_".$myVar."').css('color','black');";
				echo "var eraiddisable = $('#eraidRemove_".$myVar."').attr('disabled','disabled');";
				echo "});";
				echo "\">";
				echo "Remove";
				echo "</a>";
				echo "</button>";
				
			} else {
				
				echo "<button id=\"eraidSave_".$myVar."\" class=\"btn btn-info btn-sm\" disabled ";
                echo "style=\"width: 75px !important; margin-bottom: 5px !important; \" ";
                echo ">";
				echo "<a id=\"eraidSaveHref_".$myVar."\" style=\"color: #FFFFFF; text-decoration: none;\" ";
				echo "href=\"javascript: ";
				echo "var eraidsaver = $.post('./arc_journals_saved.php',";
				echo "{'eraidSave': '$eraidz', 'doAction': 'SAVE'}).done(function(){";
				echo "var eraiddisable = $('#eraidSave_".$myVar."').attr('disabled','disabled');";
				echo "var eraiddisableB = $('#eraidSave_".$myVar."').addClass('btn-info');";
				echo "var eraiddisableC = $('#eraidSave_".$myVar."').removeClass('btn-default');";
				echo "var eraiddisableD = $('#eraidSaveHref_".$myVar."').css('color','white');";
				echo "var eraidremove = $('#eraidRemove_".$myVar."').removeAttr('disabled');";
				echo "});";
				echo "\">";
				echo "Save";
				echo "</a>";
				echo "</button><br />";
				
				echo "<button id=\"eraidRemove_".$myVar."\" class=\"btn btn-default btn-sm\" ";
                echo "style=\"width: 75px !important; margin-bottom: 5px !important; \" ";
                echo ">";
				echo "<a style=\"color: #000000; text-decoration: none;\" ";
				echo "href=\"javascript: ";
				echo "var eraidsaver = $.post('./arc_journals_saved.php',";
				echo "{'eraidSave': '$eraidz', 'doAction': 'REMOVE'}).done(function(){";
				echo "var eraidremove = $('#eraidSave_".$myVar."').removeAttr('disabled');";
				echo "var eraiddisableB = $('#eraidSave_".$myVar."').addClass('btn-default');";
				echo "var eraiddisableC = $('#eraidSave_".$myVar."').removeClass('btn-info');";
				echo "var eraiddisableD = $('#eraidSaveHref_".$myVar."').css('color','black');";
				echo "var eraiddisable = $('#eraidRemove_".$myVar."').attr('disabled','disabled');";
				echo "});";
				echo "\">";
				echo "Remove";
				echo "</a>";
				echo "</button>";
			}
			echo "</td>";
			echo "</tr>";
		}
		
		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p>&nbsp;</p>";
		
/////////////////////////////////////////////////////////// Finish
				
		echo "</div>\n";
	}	
?>
	<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
    		<div class="modal-content"></div>
  		</div>
	</div>
    <script type="text/javascript">
		
		$(document).ready(function(){
			
			$.ajaxSetup ({
    			cache: false
			});
				
			$("#tableLegend").toggle();
			
			$('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
			
    		$(".Toggle").click(function () {
          		$("#tableLegend").toggle();
        	});
			
			$(".ToggleC").click(function () {
          		$("#menu").toggle();
				var panelW = $("#pushobj").css("margin-left");
				if(panelW == "470px") {
					$("#pushobj").css({marginLeft: "25px"});
        		};
				if(panelW == "25px") {
    				$("#pushobj").css({marginLeft: "470px"});
				};
			});
			
<?php
	if(($Conference == "")) {	
?>				
			$("#tableConference").toggle();	
<?php
	}
?>
			
    		$(".ToggleB").click(function () {
          		$("#tableConference").toggle();
        	});
			
			$('a[data-toggle="modal"]').off('click').on('click', function(e){
				e.preventDefault();
				$('.modal-content').empty();
				$('.modal-content').load(
					$(this).attr('href'),
					function(response, status, xhr) {
						return this;
					}
				);
			});
			
			$("#filter").keyup(function(){
				var filter = $(this).val(), count = 0;
				$("#myTable tr").each(function(){
					if ($(this).text().search(new RegExp(filter, "i")) < 0) {
						if ($(this).text().search(new RegExp("ISSN", "i")) < 0) {
							$(this).fadeOut();
						}
					} else {
						$(this).show();
						count++;
					}
				});
			});

<?php

/////////////////////////////////////////////////////////// Modal logic

?>
			
//			$('body').off('hidden.bs.modal').on('hidden.bs.modal', '.modal', function () {
//				$("#chartdiv").empty();	
//				$("#sherpadiv").empty();
//				$(this).removeData('bs.modal').find(".modal-content").empty();
//				$(this).data('bs.modal', null);
//			});

			$(".bs-example-modal-lg").on('hidden.bs.modal', function () {
				$("#chartdiv").empty();	
				$("#sherpadiv").empty();
    			$(this).data('.bs-example-modal-lg', null);
			});
		});
		
    </script>
<?php
	
/////////////////////////////////////////////////////////// Footer

	include("./admin/era.dbdisconnect.php");	
	
?>
