<?php

/////////////////////////////////////////////////////////// Credits
//
//
//	ERA Journal Identification Toolkit
//  University of Western Sydney
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
//	06 December 2018
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
		echo "<td width=\"90%\" style=\"width:90%;\">This is the Rank placement by Journal Impact Factor ";
        echo "of the journal in its Thomson Reuters subject category or categories. ";
		echo "The categories can be viewed by hovering the mouse over the rank value. ";
		echo "Please note that these categories do not necessarily align with ARC fields of research ";
        echo "and that some journals may be in more than one category.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>IF</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">The Thomson Reuters impact factor is a measure of the frequency with which the 'average article' ";
		echo "in a journal has been cited in a particular year or period. ";
		echo "The annual JCR impact factor is a ratio between citations and recent citable items published. ";
		echo "Thus, the impact factor of a journal is calculated by dividing the number of current year citations to ";
		echo "the source items published in that journal during the previous two years.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;\" class=\"text-right\"><strong>5YR IF</strong></td>";
		echo "<td width=\"90%\" style=\"width:90%;\">The 5-year journal Impact Factor is the average number of times ";
        echo "articles from the journal published in the past five years have ";
		echo "been cited in the year. It is caclulated by dividing the number of citations in the year by the total ";
        echo "number of articles published in the five previous years. ";
		echo "Although Impact Factors are based on cites to articles published in the previous two years, ";
        echo "a base of five years may be more appropriate for journals in certain fields ";
		echo "because the body of citations may not be large enough to make reasonable comparisons, ";
        echo "publication schedules may be consistently late, or it may take longer than two ";
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
			
			$query = "SELECT (SUM(2010_SNIP) / COUNT(2010_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2010_SNIP != \"\" AND 2010_SNIP IS NOT NULL ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$Nten = number_format($row[0],3); 
			} 
			
////////////////////////////////// 2011 Snip from all indexed

			$query = "SELECT (SUM(2011_SNIP) / COUNT(2011_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2011_SNIP != \"\" AND 2011_SNIP IS NOT NULL ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NEleven = number_format($row[0],3); 
			} 

////////////////////////////////// 2012 Snip from all indexed
			
			$query = "SELECT (SUM(2012_SNIP) / COUNT(2012_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2012_SNIP != \"\" AND 2012_SNIP IS NOT NULL ";			
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NTwelve = number_format($row[0],3); 
			} 
			
////////////////////////////////// 2013 Snip from all indexed
			
			$query = "SELECT (SUM(2013_SNIP) / COUNT(2013_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2013_SNIP != \"\" AND 2013_SNIP IS NOT NULL ";			
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NThirteen = number_format($row[0],3); 
			} 
			
////////////////////////////////// 2014 Snip from all indexed
			
			$query = "SELECT (SUM(2014_SNIP) / COUNT(2014_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2014_SNIP != \"\" AND 2014_SNIP IS NOT NULL ";			 
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NFourteen = number_format($row[0],3); 
			} 
			
////////////////////////////////// 2015 Snip from all indexed
			
			$query = "SELECT (SUM(2015_SNIP) / COUNT(2015_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
			$query .= "AND 2015_SNIP != \"\" AND 2015_SNIP IS NOT NULL ";			
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$NFifteen = number_format($row[0],3); 
			} 			

////////////////////////////////// 2016 Snip from all indexed
			
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
		
////////////////////////////////// Close wildcard switch
		
		}
		
////////////////////////////////// Display conferences

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
		
//////////////////////////////////////////// Display journal data
		
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
        
//////////////////////////////////////////// Header
        
        echo "<h3>";
        $ncount = number_format($count);
        if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "<strong>$ncount Journals</strong>";
		}
		if(($wildcard == "y") && ($keywords != "VIEWSAVED")) {
			echo "<strong>$ncount Journals ";
            echo "with '".$keywords."' in title ...</strong>";
		}
		if(($keywords == "VIEWSAVED")) {
			echo "<strong>$ncount Journals saved for ";
            echo "comparison ...</strong>";
		}
        echo "</h3>";
        
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
        <br />
<?php
        
//////////////////////////////////////////// Search in-page bar
		 
		echo "<p>";
		echo "<form id=\"live-search\" action=\"\" ";
		echo "method=\"post\" class=\"form-inline\" role=\"form\">";
		echo "<div class=\"form-group\">";
		echo "<div class=\"input-group\">";
		echo "<div class=\"input-group-addon\">";
		echo "<i class=\"glyphicon glyphicon-search\"></i></div>";
		echo "<input class=\"form-control input-lg\" ";
		echo "type=\"text\" id=\"filter\" value=\"\" ";
        echo "placeholder=\"Type a keyword from a journal ";
		echo "title to search this page ...\" />";
		echo "<div class=\"input-group-addon\" ";
		echo "style=\"background-color: #23748F;\">";
		echo "<a href=\"./arc_journals_download.php?forCode=$forCode";
		echo "&Order=$Order&keywords=$keywords&eRAID=$eRAID\" ";
		echo "data-toggle=\"tooltip\" ";
		echo "title=\" \nClick here to download the\nresults ";
		echo "on this page as\nan Excel Spreadsheet.";
		echo "\nPlease allow up to a minute for\nthe ";
		echo "file to be generated.\n \" style=\"color: #ffffff;\">";
		echo "<strong>Download Results (.XLS)</strong></a></div>";
		echo "</div>";
		echo "</div>";
		echo "</form>";
		echo "</p>";        

//////////////////////////////////////////// Table start        
        
		echo "<table class=\"";
		echo "table table-bordered ";
		echo "table-striped tablesorter\" ";
		echo "id=\"myTable\" ";
		echo "style=\"background-color: #ffffff;\" ";
		echo ">\n";
		echo "<tbody>\n";
		
////////////////////////////////// Selected journal from search
		
		$m = 0;
		$eRaids = array();
		if(($eRAID != "") && ($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			$query = "SELECT * FROM 2017_journals_final_list WHERE ERAID = \"$eRAID\" ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
                
//////////////////////// IDs                
                
				$m++;
				$eRaids[$m] = $row[1];
                $metrics = "";
                $fsnip = "n";
                
//////////////////////// Get Metrics                 
                
				$snip = number_format((float)$row[39], 3, '.', '');
                $rank = $row[19];
                $quartile = $row[29];
                $quartile = preg_replace("/Q/","","$quartile");
                $qrank = $row[28];
                $qcat = $row[27];
                $OAccess = $row[24];
                $fiveyrif = $row[34];
                $IFscore = number_format((float)$row[25], 3, '.', '');
                $AIscore = number_format((float)$row[26], 3, '.', '');
                $OAccessImg = "";
                if(($OAccess != "")) {
                    $OAccessImg = "<span class=\"glyphicon glyphicon glyphicon-ok\"></span>";	
                }
                $NJ = "";
                $newJournal = $row[18];
                $pattern = "/Y$forCode,/i";
                $pattern2 = "/N$forCode,/i";
                if(preg_match("$pattern","$newJournal")) { 
                    $NJ = "Yes";
                    $rowClass = "info";
                }
                if(preg_match("$pattern2","$newJournal")) { 
                    $NJ = ""; 
                    $rowClass = ""; 
                } 
				if(($rank == "No")) { $rank = "";}
				if(($snip == "No") OR ($snip == "0.000")) { $snip = ""; }		
				if(($IFscore == "0.000")) { $IFscore = ""; }
				if(($AIscore == "0.000")) { $AIscore = ""; }	
				$quartile = rtrim($quartile, "; ");
				$qcat = rtrim($qcat, "; ");
				$qrank = rtrim($qrank, "; ");
				$quartile = preg_replace("/; /i","<br />","$quartile");
				$qcats = explode(";",$qcat);
                $qranks = explode(";",$qrank);	
				$IFscore = number_format($IFscore,3);
				$fiveyrif = number_format($fiveyrif,3);
				$snip = number_format($snip,3);
				$row[2]= preg_replace("/'/i","\\'","$row[2]");
                $ABDC = $row[30];
                if(($snip != "") OR ($IFscore != "") OR ($fiveyrif != "") OR ($quartiles[0] != "") OR ($OAccess != "") OR ($ABDC != "")) {
                    $metrics = "y";
                    $fsnip = "y";
                }
                
//////////////////////// Display Metrics List Row Item                 
                
                echo "<tr>\n";
                echo "<td style=\"";
                echo "margin-bottom: 1px; ";
                echo "word-wrap: break-word; ";
                echo "white-space: normal; ";
                echo "color: #000000 !important; ";
                echo "padding: 15px; ";
                echo "border-top: 7px solid #000000 !important; ";
                echo "border-left: 1px solid #cccccc !important; ";
                echo "border-right: 0px solid #000000 !important; ";
                echo "border-bottom: 0px solid #000000 !important; ";
                if(($altRow == "b")) {
                    echo "background-color: #eeeeee !important; ";
                    $altRow = "a";
                } else {
                    $altRow = "b";
                }
                echo "\">";
                echo "<span style=\"";
                echo "color: #800000; ";
                echo "font-size: 1.2em; ";
                echo "font-weight: 700; ";
                echo "line-height: 2.4em; ";
                echo "width: 100%; ";
                echo "white-space: normal; ";
                echo "\">".htmlentities($row[2])."</span>";
                echo "<p style=\"";
                echo "color: #000000 !important; ";
                echo "word-wrap: break-word; ";
                echo "white-space: normal; ";
                echo "width: 100%; ";
                echo "\">";

//////////////////////// ISSN(S)

                echo "ISSNs ";
                for($r=0;$r<7;$r++) {
                    $q=(10+$r);
                    if(($row[$q] != "") && ($row[$q] != " ")) {
                        echo "$row[$q] ";
                    }
                }
                echo "<br />";

//////////////////////// FoRs		

                if(($row[5] != "")) { 
                    echo "($row[4]) $row[5]"; 
                }
                if(($row[7] != "")) { 
                    echo "<br />($row[6]) $row[7]"; 
                }
                if(($row[9] != "")) { 
                    echo "<br />($row[8]) $row[9]";  
                }   

//////////////////////// Q            

                if(($quartiles[0] != "")) {
                    $q = count($quartiles);
                    if($q > 0){
                        for($v=0;$v<$q;$v++){					
                            echo "<div class=\"ui-grid-a\" style=\"width:100%;\">";
                            echo "<div class=\"ui-block-a\" ";
                            echo "style=\"color: #000000; ";
                            echo "font-size: 1.0em; ";
                            echo "text-align: right; ";
                            echo "padding-right: 0.7em; ";
                            echo "width: 20%;\">";
                            echo "<strong>Q</strong>";
                            echo "</div>";
                            echo "<div class=\"ui-block-b\" ";
                            echo "style=\"color: #000000; font-size: 1.0em\">";
                            echo ucwords(strtolower($qcats[$v]));
                            echo "<br />(Quartile ";
                            echo $quartiles[$v].", ".$qranks[$v];
                            echo ")";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                }            

//////////////////////// 5YRIF		

                if(($fiveyrif != "")) {
                    echo "<div class=\"ui-grid-a\">";
                    echo "<div class=\"ui-block-a\" ";
                    echo "style=\"color: #000000; ";
                    echo "font-size: 1.0em; ";
                    echo "text-align: right; ";
                    echo "padding-right: 0.7em; ";
                    echo "width: 20%;\">";
                    echo "<strong>5YR IF</strong>";
                    echo "</div>";
                    echo "<div class=\"ui-block-b\" ";
                    echo "style=\"color: #000000; font-size: 1.0em;\">";
                    echo $fiveyrif;
                    echo "</div>";
                    echo "</div>";
                }

//////////////////////// IF

                if(($IFscore != "")) {
                    echo "<div class=\"ui-grid-a\">";
                    echo "<div class=\"ui-block-a\" ";
                    echo "style=\"color: #000000; ";
                    echo "font-size: 1.0em; ";
                    echo "text-align: right; ";
                    echo "padding-right: 0.7em; ";
                    echo "width: 20%;\">";
                    echo "<strong>IF</strong>";
                    echo "</div>";
                    echo "<div class=\"ui-block-b\" ";
                    echo "style=\"color: #000000; font-size: 1.0em;\">";
                    echo $IFscore;
                    echo "</div>";
                    echo "</div>";
                }

//////////////////////// SNIP

                if(($snip != "")) {
                    echo "<div class=\"ui-grid-a\">";
                    echo "<div class=\"ui-block-a\" ";
                    echo "style=\"color: #000000; ";
                    echo "font-size: 1.0em; ";
                    echo "text-align: right; ";
                    echo "padding-right: 0.7em; ";
                    echo "width: 20%;\">";
                    echo "<strong>SNIP</strong>";
                    echo "</div>";
                    echo "<div class=\"ui-block-b\" ";
                    echo "style=\"color: #000000; font-size: 1.0em;\">";
                    echo $snip;
                    echo "</div>";
                    echo "</div>";
                }		

//////////////////////// ABDC

                if(($ABDC != "")) {
                    echo "<div class=\"ui-grid-a\">";
                    echo "<div class=\"ui-block-a\" ";
                    echo "style=\"color: #000000; ";
                    echo "font-size: 1.0em; ";
                    echo "text-align: right; ";
                    echo "padding-right: 0.7em; ";
                    echo "width: 20%;\">";
                    echo "<strong>ABDC</strong>";
                    echo "</div>";
                    echo "<div class=\"ui-block-b\" ";
                    echo "style=\"color: #000000; font-size: 1.0em;\">";
                    echo $ABDC;
                    echo "</div>";
                    echo "</div>";
                }		

//////////////////////// QA

                if(($OAccessImg != "")) {
                    echo "<div class=\"ui-grid-a\">";
                    echo "<div class=\"ui-block-a\" ";
                    echo "style=\"color: #000000; ";
                    echo "font-size: 1.0em; ";
                    echo "text-align: right; ";
                    echo "padding-right: 0.7em; ";
                    echo "width: 20%;\">";
                    echo "<strong>OA</strong>";
                    echo "</div>";
                    echo "<div class=\"ui-block-b\" ";
                    echo "style=\"color: #000000; font-size: 1.0em;\">";
                    echo $OAccessImg;
                    echo "</div>";
                    echo "</div>";
                }

                echo "</td>";

//////////////////////// View, Save and Remove buttons triggers            

                echo "<td style=\"";
                echo "margin-bottom: 1px; ";
                echo "word-wrap: break-word; ";
                echo "width: 95px; ";
                echo "white-space: normal; ";
                echo "color: #000000 !important; ";
                echo "padding: 15px; ";
                echo "border-top: 7px solid #000000 !important; ";
                echo "border-left: 1px solid #cccccc !important; ";
                echo "border-right: 1px solid #cccccc !important; ";
                echo "border-bottom: 0px solid #000000 !important; ";
                echo "\">";        
                echo "<a data-toggle=\"modal\" ";
                echo "data-target=\".bs-example-modal-lg\" class=\"btn ";
                if(($snip != "")) {
                    echo "btn-warning ";
                    $fsnip = "y";
                } else {
                    echo "btn-default ";
                    $fsnip = "n";
                }
                echo "btn-sm\" ";
                echo "style=\"width: 75px !important;\" ";
                echo "href=\"./arc_modal.php?";
                echo "eraid=$row[1]&fsnip=$fsnip&AmeanSnip=$NSixteen&for4=$for4&for2=$for2\">";
                echo "View";
                echo "</a><br />";
                $eraidz = $row[1];
                $myVar++;

                if(($_SESSION["ERAIDS"]["$eraidz"] == "")) {

                    echo "<button id=\"eraidSave_".$myVar."\" class=\"btn btn-default btn-sm\" ";
                    echo "style=\"width: 75px !important; margin-top: 6px !important;\">";
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

                    echo "<button id=\"eraidRemove_".$myVar."\" class=\"btn btn-default btn-sm\" ";
                    echo "disabled style=\"width: 75px !important; margin-top: 6px !important;\">";
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

                    echo "<button id=\"eraidSave_".$myVar."\" class=\"btn btn-info btn-sm\" ";
                    echo "disabled style=\"width: 75px !important; margin-top: 6px !important;\">";
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
                    echo "style=\"width: 75px !important; margin-top: 6px !important;\">";
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
		
//////////////////////// Run sql statement
		
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
		
//////////////////////// IDs
		
			$m++;
			$eRaids[$m] = $row[1];
            $metrics = "";
            $fsnip = "n";
            
//////////////////////// Get Metrics            
            
			$snip = number_format((float)$row[39], 3, '.', '');
            $rank = $row[19];
            $quartile = $row[29];
            $quartile = preg_replace("/Q/","","$quartile");
            $qrank = $row[28];
            $qcat = $row[27];
            $OAccess = $row[24];
            $fiveyrif = $row[34];
            $IFscore = number_format((float)$row[25], 3, '.', '');
            $AIscore = number_format((float)$row[26], 3, '.', '');
            $OAccessImg = "";
            if(($OAccess != "")) {
				$OAccessImg = "<span class=\"glyphicon glyphicon glyphicon-ok\"></span>";	
			}
			$NJ = "";
			$newJournal = $row[18];
			$pattern = "/Y$forCode,/i";
			$pattern2 = "/N$forCode,/i";
			if(preg_match("$pattern","$newJournal")) { 
				$NJ = "Yes";
			}
			if(preg_match("$pattern2","$newJournal")) { 
                $NJ = ""; 
                $rowClass = ""; 
            }       
			if(($rank == "No")) { $rank = "";}
            if(($snip == "No") OR ($snip == "0.000")) { $snip = ""; }
            if(($IFscore == "0.000")) { $IFscore = ""; }
            if(($AIscore == "0.000")) { $AIscore = ""; }
            $quartile = rtrim($quartile, "; ");
            $qcat = rtrim($qcat, "; ");
            $qrank = rtrim($qrank, "; ");	
            $quartiles = explode(";",$quartile);
            $qcats = explode(";",$qcat);
            $qranks = explode(";",$qrank);	
            $IFscore = number_format($IFscore,3);
            $fiveyrif = number_format($fiveyrif,3);
            $snip = number_format($snip,3);
            $row[2]= preg_replace("/'/i","\\'","$row[2]");
            $ABDC = $row[30];
            if(($snip != "") OR ($IFscore != "") OR ($fiveyrif != "") OR ($quartiles[0] != "") OR ($OAccess != "") OR ($ABDC != "")) {
                $metrics = "y";
                $fsnip = "y";
            }

//////////////////////// Display Metrics List Row Item            
            
			echo "<tr>\n";
			echo "<td style=\"";
            echo "margin-bottom: 1px; ";
            echo "word-wrap: break-word; ";
            echo "white-space: normal; ";
            echo "color: #000000 !important; ";
            echo "padding: 15px; ";
            echo "border-top: 7px solid #000000 !important; ";
            echo "border-left: 1px solid #cccccc !important; ";
            echo "border-right: 0px solid #000000 !important; ";
            echo "border-bottom: 0px solid #000000 !important; ";
            if(($altRow == "b")) {
                echo "background-color: #eeeeee !important; ";
                $altRow = "a";
            } else {
                $altRow = "b";
            }
            echo "\">";
            echo "<span style=\"";
            echo "color: #800000; ";
            echo "font-size: 1.2em; ";
            echo "font-weight: 700; ";
            echo "line-height: 2.4em; ";
            echo "width: 100%; ";	
            echo "white-space: normal; ";
            echo "\">".htmlentities($row[2])."</span>";
            echo "<p style=\"";
            echo "color: #000000 !important; ";
            echo "word-wrap: break-word; ";
            echo "white-space: normal; ";
            echo "width: 100%; ";
            echo "\">";
            
//////////////////////// ISSN(S)
		
            echo "ISSNs ";
            for($r=0;$r<7;$r++) {
                $q=(10+$r);
                if(($row[$q] != "") && ($row[$q] != " ")) {
                    echo "$row[$q] ";
                }
            }
            echo "<br />";
		
//////////////////////// FoRs		
		
            if(($row[5] != "")) { 
                echo "($row[4]) $row[5]"; 
            }
            if(($row[7] != "")) { 
                echo "<br />($row[6]) $row[7]"; 
            }
            if(($row[9] != "")) { 
                echo "<br />($row[8]) $row[9]";  
            }   

//////////////////////// Q            
            
            if(($quartiles[0] != "")) {
                $q = count($quartiles);
                if($q > 0){
                    for($v=0;$v<$q;$v++){					
                        echo "<div class=\"ui-grid-a\" style=\"width:100%;\">";
                        echo "<div class=\"ui-block-a\" ";
                        echo "style=\"color: #000000; ";
                        echo "font-size: 1.0em; ";
                        echo "text-align: right; ";
                        echo "padding-right: 0.7em; ";
                        echo "width: 20%;\">";
                        echo "<strong>Q</strong>";
                        echo "</div>";
                        echo "<div class=\"ui-block-b\" ";
                        echo "style=\"color: #000000; font-size: 1.0em\">";
                        echo ucwords(strtolower($qcats[$v]));
                        echo "<br />(Quartile ";
                        echo $quartiles[$v].", ".$qranks[$v];
                        echo ")";
                        echo "</div>";
                        echo "</div>";
                    }
                }
            }            
			
//////////////////////// 5YRIF		
		
            if(($fiveyrif != "")) {
                echo "<div class=\"ui-grid-a\">";
                echo "<div class=\"ui-block-a\" ";
                echo "style=\"color: #000000; ";
                echo "font-size: 1.0em; ";
                echo "text-align: right; ";
                echo "padding-right: 0.7em; ";
                echo "width: 20%;\">";
                echo "<strong>5YR IF</strong>";
                echo "</div>";
                echo "<div class=\"ui-block-b\" ";
                echo "style=\"color: #000000; font-size: 1.0em;\">";
                echo $fiveyrif;
                echo "</div>";
                echo "</div>";
            }
		
//////////////////////// IF
		
            if(($IFscore != "")) {
                echo "<div class=\"ui-grid-a\">";
                echo "<div class=\"ui-block-a\" ";
                echo "style=\"color: #000000; ";
                echo "font-size: 1.0em; ";
                echo "text-align: right; ";
                echo "padding-right: 0.7em; ";
                echo "width: 20%;\">";
                echo "<strong>IF</strong>";
                echo "</div>";
                echo "<div class=\"ui-block-b\" ";
                echo "style=\"color: #000000; font-size: 1.0em;\">";
                echo $IFscore;
                echo "</div>";
                echo "</div>";
            }
		
//////////////////////// SNIP

            if(($snip != "")) {
                echo "<div class=\"ui-grid-a\">";
                echo "<div class=\"ui-block-a\" ";
                echo "style=\"color: #000000; ";
                echo "font-size: 1.0em; ";
                echo "text-align: right; ";
                echo "padding-right: 0.7em; ";
                echo "width: 20%;\">";
                echo "<strong>SNIP</strong>";
                echo "</div>";
                echo "<div class=\"ui-block-b\" ";
                echo "style=\"color: #000000; font-size: 1.0em;\">";
                echo $snip;
                echo "</div>";
                echo "</div>";
            }		
		
//////////////////////// ABDC

            if(($ABDC != "")) {
                echo "<div class=\"ui-grid-a\">";
                echo "<div class=\"ui-block-a\" ";
                echo "style=\"color: #000000; ";
                echo "font-size: 1.0em; ";
                echo "text-align: right; ";
                echo "padding-right: 0.7em; ";
                echo "width: 20%;\">";
                echo "<strong>ABDC</strong>";
                echo "</div>";
                echo "<div class=\"ui-block-b\" ";
                echo "style=\"color: #000000; font-size: 1.0em;\">";
                echo $ABDC;
                echo "</div>";
                echo "</div>";
            }		
		
//////////////////////// QA
		
            if(($OAccessImg != "")) {
                echo "<div class=\"ui-grid-a\">";
                echo "<div class=\"ui-block-a\" ";
                echo "style=\"color: #000000; ";
                echo "font-size: 1.0em; ";
                echo "text-align: right; ";
                echo "padding-right: 0.7em; ";
                echo "width: 20%;\">";
                echo "<strong>OA</strong>";
                echo "</div>";
                echo "<div class=\"ui-block-b\" ";
                echo "style=\"color: #000000; font-size: 1.0em;\">";
                echo $OAccessImg;
                echo "</div>";
                echo "</div>";
            }
            
//////////////////////// Library Links
		
            echo "<div class=\"ui-grid-b\" style=\"";
            echo "padding-top: 0.5em; ";
            echo "padding-bottom: 0.5em; ";
            echo "white-space: nowrap; ";
            echo "\">";

//////////////////////// WSU		
		
            echo "<form action=\"";
            echo "https://ulrichsweb.serialssolutions.com/widget/search/\" ";
            echo "method=\"POST\" target=\"_UlrichSearch\" ";
            echo "style=\"";
            echo "margin-top: 0px; ";
            echo "margin-bottom: 0px; ";
            echo "padding: 0px; ";
            echo "padding-left: 0.9em; ";
            echo "\" />";
            echo "<a href=\"";
            echo "https://west-sydney-primo.hosted.exlibrisgroup.com/";
            echo "primo-explore/search?query=title,exact,";
            echo htmlentities($row[2]);
            echo ",AND&pfilter=pfilter,exact,journals,";
            echo "AND&tab=default_tab&search_scope=default_scope&";
            echo "vid=UWS-ALMA&lang=en_US&mode=advanced&offset=0&";
            echo "fn=search";
            echo "\" target=\"_LibrarySearch\" ";
            echo "style=\"";
            echo "margin:0px; ";
            echo "padding: 0px; ";
            echo "\">";
            echo "<img src=\"./assets/images/link_wsu.jpg\" height=\"30\" ";
            echo "border=\"0\" ";
            echo "style=\"";
            echo "margin-top: 0px; ";
            echo "vertical-align: top; ";
            echo "margin-bottom: 0px; ";
            echo "padding: 0px; ";
            echo "padding-right: 0.3em; ";
            echo "border: 0px solid #222222; ";
            echo "\">";
            echo "</a> ";
		
//////////////////////// Ulrichsweb		
		
            echo "<input type=\"hidden\" name=\"query\" value=\"";
            echo $row[10];
            if(($row[11] != "")) { echo " OR ".$row[11]; }
            if(($row[12] != "")) { echo " OR ".$row[12]; }
            echo"\">";
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
		
//////////////////////// SJR		
		
            if(($row[33] != "")) {
                echo "<a href=\"https://www.scimagojr.com/journalsearch.php";
                echo "?q=".$row[33]."&tip=iss\" target=\"_SJRSearch\" ";
                echo "style=\"";
                echo "margin: 0px; ";
                echo "padding: 0px;\">";
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
            
//////////////////////// Elsevier
		
            if(($row[32] != "")) {
                echo "<a href=\"";
                echo "https://www.elsevier.com/search-results?query=";
                $searchTitlePub = preg_replace("/\s\s/"," ","$row[2]");
                $searchTitlePub = preg_replace("/\s/","+","$row[2]");
                echo "$searchTitlePub";
                echo "&labels=journals";
                echo "\" target=\"_ElsevierSearch\" ";
                echo "style=\"";
                echo "margin: 0px; ";
                echo "padding: 0px; \">";
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
            
//////////////////////// Finish
            
            echo "</form>";
            echo "</div>";
			
//////////////////////// Close first table column            
            
			echo "</td>";
            
//////////////////////// View, Save and Remove buttons triggers            
            
            echo "<td style=\"";
            echo "margin-bottom: 1px; ";
            echo "word-wrap: break-word; ";
            echo "width: 95px; ";
            echo "white-space: normal; ";
            echo "color: #000000 !important; ";
            echo "padding: 15px; ";
            echo "border-top: 7px solid #000000 !important; ";
            echo "border-left: 1px solid #cccccc !important; ";
            echo "border-right: 1px solid #cccccc !important; ";
            echo "border-bottom: 0px solid #000000 !important; ";
            echo "\">";        
			echo "<a data-toggle=\"modal\" ";
			echo "data-target=\".bs-example-modal-lg\" class=\"btn ";
			if(($snip != "")) {
				echo "btn-warning ";
				$fsnip = "y";
			} else {
				echo "btn-default ";
				$fsnip = "n";
			}
			echo "btn-sm\" ";
            echo "style=\"width: 75px !important;\" ";
			echo "href=\"./arc_modal.php?";
			echo "eraid=$row[1]&fsnip=$fsnip&AmeanSnip=$NSixteen&for4=$for4&for2=$for2\">";
			echo "View";
			echo "</a><br />";
			$eraidz = $row[1];
			$myVar++;
            
			if(($_SESSION["ERAIDS"]["$eraidz"] == "")) {
				
				echo "<button id=\"eraidSave_".$myVar."\" class=\"btn btn-default btn-sm\" ";
				echo "style=\"width: 75px !important; margin-top: 6px !important;\">";
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
				
				echo "<button id=\"eraidRemove_".$myVar."\" class=\"btn btn-default btn-sm\" ";
				echo "disabled style=\"width: 75px !important; margin-top: 6px !important;\">";
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
				
                echo "<button id=\"eraidSave_".$myVar."\" class=\"btn btn-info btn-sm\" ";
				echo "disabled style=\"width: 75px !important; margin-top: 6px !important;\">";
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
				echo "style=\"width: 75px !important; margin-top: 6px !important;\">";
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
		
        function delay(callback, ms) {
          var timer = 0;
          return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
              callback.apply(context, args);
            }, ms || 0);
          };
        }
        
		$(document).ready(function(){
			
			$.ajaxSetup ({
    			cache: false
			});
				
			$("#tableLegend").toggle();
			
			$('[data-toggle="tooltip"]').tooltip({'placement': 'right'});
			
    		$(".Toggle").click(function () {
          		$("#tableLegend").toggle();
        	});
			
			$(".ToggleC").click(function () {
          		$("#menu").toggle();
				var panelW = $("#pushobj").css("margin-left");
				if(panelW == "411px") {
					$("#pushobj").css({marginLeft: "1px"});
        		};
				if(panelW == "1px") {
    				$("#pushobj").css({marginLeft: "411px"});
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
			
            
			$("#filter").keyup(delay(function(){
				var filter = $(this).val(), count = 0;
                var nfilter = $(this).val().length;
                if (nfilter > 3) {
                    $("#myTable tr").each(function(){
                        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                            $(this).fadeOut();
                        } else {
                            $(this).show();
                            count++;
                        }
                    });
                }
			},350));

<?php

/////////////////////////////////////////////////////////// Modal logic

?>

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
