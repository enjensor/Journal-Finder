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
//	08 March 2019
//	13-14 June 2019
//	17-18 June 2019
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
		echo "<input type=\"button\" id=\"Toggle\" class=\"Toggle\" value=\"  Hide / Show Glossary  \" style=\"font-size: 14px; height: 46px; padding: 6px 12px; background-color: #436db5; color: #FFFFFF;\"></input> ";
		echo "<br />";
		echo "<div id=\"tableLegend\" name=\"tableLegend\" class=\"tableLegend\">\n";
		echo "&nbsp;<br />";
		echo "<table class=\"table table-bordered table-striped\" style=\"background-color: #ffffff;\">\n";
		echo "<tbody>\n";
		
		echo "<tr><td width=\"10%\" style=\"width:10%;padding:15px;\" class=\"text-right\" nowrap><strong>Q (SCImago)<br />2017</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">Quartile Rank (<a href=\"https://www.scimagojr.com/aboutus.php\" target=\"q_scimago\">SCImago</a>).<br />The SCImago Journal Rank (SJR) is independently developed using information contained in the <a href=\"http://ezproxy.uws.edu.au/login?url=https://www.scopus.com/scopus/home.url\" target=\"scopus\">Scopus</a> database. SJR expresses the average number of weighted citations received in the selected year by the documents published in the journal in the three previous years. Journals are grouped by subject category. The rank is then placed within a quartile of the whole category. Top ranked journals are in Quartile 1. The SCImago subject(s) can be viewed by hovering the mouse over the Quartile value. Note: These categories differ to the ERA 2018 Journal Submission List FoR codes and journals may be in more than one category. A journal’s actual position within the subject category can be viewed on the SCImago website</td></tr>\n";

		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>SNIP<br />2017<br />(Data from 30/4/18)</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\"><a href=\"https://service.elsevier.com/app/answers/detail/a_id/14884/supporthub/\" target=\"snip\">SNIP</a> or Source Normalized Impact per Paper is the ratio of a source's average citation count per paper and the citation potential of its subject field. Citation potential is important because it accounts for the fact that typical citation counts vary widely between research disciplines. SNIP helps to make a direct comparison of sources in different subject fields. A journal with a SNIP value > 1 has above average citation potential. A journal with a SNIP value < 1 has below average citation potential.</td></tr>\n";

		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>Q (JCR)<br />2017<br />(Data released 26/6/2018)</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">Quartile Rank (<a href=\"http://ezproxy.uws.edu.au/login?url=https://jcr.clarivate.com\" target=\"_clarivate\">Journal Citation Reports</a>).<br />Clarivate’s Journal Citation Reports (JCR) quartile ranking is based on the journal’s Impact Factor (IF). You can use this number to evaluate or compare a journal’s relative importance in the larger context of its assigned subject area(s). Top ranked journals are in Quartile 1. Each journal in JCR is assigned to at least one subject category, indicating a general area of science or the social sciences. Journals may be included in more than one subject category across the two broad areas. The JCR subject(s) can be viewed by hovering the mouse over the Quartile value. Note: These categories differ to the ERA 2018 Journal Submission List FoR codes.</td></tr>\n";

		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>Rank (JCR)<br />2017<br />(Data released 26/6/2018)</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">The rank provides information on the particular journal title’s position in the subject area(s), ordered by Impact Factor.</td></tr>\n";

		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>IF<br />2017<br />(Data released 26/6/2018)</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">The Impact Factor (IF) found in Journal Citation Reports (JCR) is a measure of the frequency with which the 'average article' in a journal has been cited in a particular year. IF is calculated using data from <a href=\"http://ezproxy.uws.edu.au/login?url=http://www.webofknowledge.com/wos\" target=\"wos\">Web of Science</a> citation indexes at a particular time. The annual IF calculation is current year citations to all items published in the journal in the previous two years, divided by the total number of citable items published in the journal in the previous two years, e.g. 2017 Journal Impact Factor = (2017 citations to items published in 2016 + 2015) / (total citable items in 2016 and 2015).</td></tr>\n";

		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>5YR IF<br />2017<br />(Data released 26/6/2018)</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">The Five-Year Impact Factor is the average number of times articles from the journal published in the last five years have been cited in the JCR year. This metric can be used to better gauge the impact of journals in fields where the influence of published research evolves over a longer period of time. A base of five years may be more appropriate for journals in certain fields because the body of citations may not be large enough to make reasonable comparisons, publication schedules may be consistently late, or it may take longer than two years to disseminate and respond to published works.</td></tr>\n";	

		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>ISSN</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">International Standard Serial Number. <a href=\"https://www.issn.org/understanding-the-issn/what-is-an-issn/\" target=\"issn\">More information about ISSN</a>.</td></tr>\n";	

		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>ABDC<br  />2016</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">The Australian Business Deans Council established a <a href=\"https://abdc.edu.au/research/abdc-journal-list/\" target=\"abdc\">Journal Quality List</a> for use by its member business schools in 2007, with the aim of overcoming the regional and discipline bias of international lists. The last full review of the ABDC List was conducted in 2013, followed by an <a href=\"https://abdc.edu.au/research/abdc-journal-list/2016-interim-review/\" target=\"review\">interim review</a> in 2016. A full review is underway; results are due in December in 2019.<br /><br />The ABDC Journal Quality List is based on four mutually exclusive (and collectively exhaustive) rating categories labelled: A*; A; B and C, defined as follows:<br /><br /><strong>A*</strong>: highest quality category, and indicatively represents approximately the top 5-7% of the journals assigned to the given primary FoR panel.<br /><br /><strong>A:</strong> second highest quality category, and indicatively represents approximately the next 15-25% of the journals assigned to the given primary FoR panel.<br /><br /><strong>B:</strong> third highest quality category, and indicatively represents approximately the next 35-40% of the journals assigned to the given primary FoR group.<br /><br /><strong>C:</strong> fourth highest quality category, and represents the remaining recognised quality journals assigned to the given primary FoR panel.<br /><br />In each Field of Research (FoR) group, journals deemed NOT to reach the quality threshold level are not listed. Click the ABDC column header to sort the list by ABDC rating.</td></tr>\n";

		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>FoR<br />2018</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">The <a href=\"https://www.abs.gov.au/Ausstats/abs@.nsf/Latestproducts/4AE1B46AE2048A28CA25741800044242?opendocument\" target=\"fors\">Field(s) of Research</a> allocated to the journal by the Australian Research Council, for the purposes of <a href=\"https://www.arc.gov.au/excellence-research-australia\" target=\"arc_era\">ERA</a>. Hover the cursor over the FoR code for the full field descriptor. Click the FoR column header to sort the list by that content.</td></tr>\n";	
		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>DOAJ<br />May 2019</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">A tick in this column indicates that the journal is indexed on the <a href=\"https://doaj.org/\" target=\"doaj\">Directory of Open Access Journals</a>. DOAJ is an online directory that indexes and provides access to quality open access, peer-reviewed journals. Note: This is separate to the Green tick icon which indicates availability of institutional funding for Open Access.</td></tr>\n";
		
		echo "<tr><td width=\"10%\" style=\"padding:15px;width:10%;\" class=\"text-right\"><strong>Actions</strong></td>";
		echo "<td width=\"90%\" style=\"padding:15px;width:90%;text-align: justify;\">Click on the 'ViewOA' button to see the publisher copyright policies & self-archiving rights for the journal as recorded in the SHERPA/RoMEO database. Click on the ‘Save’ button to save the journal for comparison. This can be done for the results of one or more searches i.e. in different clusters. Once selections are complete, return to the home page and click ‘View Saved’ for a comparison list. Note: The saved selections will be lost once the browser session is closed.</td></tr>\n";

		echo "</tbody>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "<p>&nbsp;</p>";		
		
/////////////////////////////////////////////////////////// Journal data
		
		if(($wildcard != "y") && ($keywords != "VIEWSAVED") && ($do_sniptable == "y")) {

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
        
////////////////////////////////// Display journal data
		
		echo "<h2>$forCode $forName</h2>";
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
			echo "$count Journals";
		}
		if(($wildcard == "y") && ($keywords != "VIEWSAVED")) {
			echo "$count Journals with '".$keywords."' in title ...";
		}
		if(($keywords == "VIEWSAVED")) {
			echo "$count Journals saved for comparison ...";
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
                    echo "This is a Rank by SCIMago ";
                    echo "expressed as a quartile of the whole category. ";
                    echo "Top ranked journals are in the first ";
                    echo "quartile, which is 1.\n \">";
                ?>Order by Quartile (Q SCImago)</a></li>
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
                ?>Order by Impact Factor (IF)</a></li>
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
                ?>Order by Open Access (DOAJ)</a></li>
        	<li><a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?<?php
                if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
                    echo "eRAID=".$eRAID."&";
                } else {
                    echo "keywords=".$keywords."*&";
                }
                echo "for2=".$for2."&for4=".$for4."&Order=OAF' );";
                ?>" style="text-decoration: none !important; <?php if(($Order == "OAF")) { echo "color: #800000 !important;"; } ?>" <?php
                    echo "data-toggle=\"tooltip\" ";
                    echo "data-placement=\"right\" ";
                    echo "title=\" \nOrder by WSU OA Funding Available\n \">"; 
                ?>Order by WSU OA Funding Available</a></li>
		</ul>
<?php  
        
/////////////////////////////////////////////////////////////// Icon legend
        
		echo "<br />&nbsp;<br />";
		echo "<table class=\"table table-bordered table-striped tablesorter\" ";
		echo "id=\"myTable\" style=\"background-color: #ffffff;\">\n";
		echo "<tbody>\n";
		echo "<tr>";
		echo "<td class=\"text-left\" style=\"border-top: 1px solid #000000 !important; padding: 20px;\">";
        echo "<em>If these icons appear with a journal title, click to access additional information about the journal / search for the journal in the Library’s collection / check institutional open access funding criteria. You may need to click the Ulrichsweb link twice as the first time authenticates your access but does not search.</em>";
       	echo "</td></tr>"; 
       	echo "<tr>";
		echo "<td class=\"text-center\" style=\"border-bottom: 1px solid #000000 !important; padding: 20px;\">";
        echo "<img ";
        echo "src=\"./assets/images/link_elsevier.png\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.0em; ";
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
        echo "padding-right: 0.0em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;Scimago&nbsp;&nbsp;&nbsp;";
        echo "<img ";
        echo "src=\"./assets/images/link_ulrichsweb.jpg\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.0em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;Ulrichsweb&nbsp;&nbsp;&nbsp;";
        echo "<img ";
        echo "src=\"./assets/images/link_scopus.png\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.0em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;Scopus&nbsp;&nbsp;&nbsp;";        
        echo "<img ";
        echo "src=\"./assets/images/link_wsu.jpg\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.0em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;Library&nbsp;&nbsp;&nbsp;";
        echo "<img ";
        echo "src=\"./assets/images/link_checkmark.png\" ";
        echo "height=\"30\" ";
        echo "border=\"0\" ";
        echo "style=\"";
        echo "margin-top: 0px; ";
        echo "margin-bottom: 0px; ";
        echo "vertical-align: middle; ";
        echo "padding: 0px; ";
        echo "padding-right: 0.0em; ";
        echo "\">";
        echo "&nbsp;&nbsp;&nbsp;OA Funding Available";
        echo "</td></tr>";
        echo "</tbody>";
        echo "</table>";
		
/////////////////////////////////////////////////////////////// Search in-page bar
		 
		echo "<p>";
		echo "&nbsp;<br />";
		echo "<form id=\"live-search\" action=\"\" method=\"post\" class=\"form-inline\" role=\"form\">";
		echo "<div class=\"form-group\">";
		echo "<div class=\"input-group\">";
		echo "<div class=\"input-group-addon\"><i class=\"glyphicon glyphicon-search\"></i></div>";
		echo "<input class=\"form-control input-lg\" type=\"text\" id=\"filter\" value=\"\" placeholder=\"Type a keyword from a journal title to search this page ...\" />";
		echo "<span class=\"input-group-addon\" style=\"border: 0px solid #aaaaaa!important;\" >&nbsp;</span>";
		echo "<div class=\"input-group-addon\" style=\"background-color: #436db5;\">";
		echo "<a href=\"./arc_journals_download.php?forCode=$forCode&Order=$Order&keywords=$keywords&eRAID=$eRAID\" data-toggle=\"tooltip\" ";
		echo "title=\" \nClick here to download the\nresults on this page as\nan Excel Spreadsheet.";
		echo "\nPlease allow up to a minute for\nthe file to be generated.\n \" style=\"color: #ffffff;\">";
		echo "Download Results</a></div>";
		echo "</div>";
		echo "</div>";
		echo "</form>";
		echo "<br />";
		echo "Hover over a column header to see a brief explanation, or click at the top of the page to display the Glossary. Click a column header to order results by that aspect of the data.<br />&nbsp;";
		echo "</p>";
  
////////////////////////////////// Open Table        
        
		echo "<table class=\"table table-bordered table-striped tablesorter\" ";
		echo "id=\"myTable\" style=\"background-color: #ffffff; font-size: 1.0em!important;\">\n";
		echo "<tbody>\n";
	
//////////////////////////////////////////////////////////////////////////////////////// Start rows
	
		echo "<tr>\n";

//////////////////////////////////////////// Q (SCImago)

		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\" nowrap><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); ";
		echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=QUARTILE' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to organise by SCImago Quartile within a category. Top ranked journals are in Quartile 1. Hover the cursor over a rank to view all applicable SCImago subject categories in order.\n \">";
		echo "Q<br />(SCImago)</a></strong></td>\n";

//////////////////////////////////////////// SNIP

		echo "<td class=\"text-right\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=SNIP' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to organise by Source Normalized Impact per Paper (2017) value. A SNIP value of 1 means the journal has average citation potential in its field.\n \">";
		echo "SNIP</a></strong></td>\n";

//////////////////////////////////////////// Q (JCR)

		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\" nowrap><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); ";
		echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=QUARTILEJCR' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to organise by JCR IF Quartile within a category. Top ranked journals are in Quartile 1. Hover the cursor over a rank to view all applicable JCR subject categories in order.\n \">";
		echo "Q<br />(JCR)</a></strong></td>\n";
		
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
		echo "data-toggle=\"tooltip\" data-placement=\"top\" title=\" \nJCR IF Rank within subject category /categories.\n \">";
		echo "Rank<br />(JCR)</a></strong></td>\n";
		
//////////////////////////////////////////// IF		
		
		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=IF' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to order by JCR Impact Factor value (highest to lowest).\n \">";
		echo "IF</a></strong></td>\n";
		
//////////////////////////////////////////// 5YR IF

		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=5YR' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to order by JCR 5-year Impact Factor value (highest to lowest).\n \">";
		echo "IF<br />(5YR)</a></strong></td>\n";

//////////////////////////////////////////// ISSN

		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong><a name=\"ISSN\" data-toggle=\"tooltip\" title=\" \nInternational Standard Serial Number(s) assigned to the journal title.\n \">ISSN</a></strong></td>\n";
		
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
		echo "data-toggle=\"tooltip\" title=\" \nClick to order by Australian Business Deans Council Journal Quality rating. A* is the highest quality category.\n \">";
		echo "ABDC</a></strong></td>\n";
		
//////////////////////////////////////////// FoR 1		
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=FoR1' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nField of Research allocated by the Australian Research Council. Click to re-order content by FoR.\n \">";
		echo "FoR1</a></strong></td>\n";

//////////////////////////////////////////// FoR 2
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=FoR2' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nField of Research allocated by the Australian Research Council. Click to re-order content by FoR.\n \">";
		echo "FoR2</a></strong></td>\n";

//////////////////////////////////////////// FoR 3
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=FoR3' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nField of Research allocated by the Australian Research Council. Click to re-order content by FoR.\n \">";
		echo "FoR3</a></strong></td>\n";
		
//////////////////////////////////////////// DOAJ		
		
		echo "<td class=\"text-left\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong>";
		echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); ";
		echo "var spinner = new Spinner().spin(target); var doThisAlso = $('#scrollingP').scrollTop(0); var doThis = $( '#matrixBody' ).load( 'arc_journals.php?";
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			echo "eRAID=".$eRAID."&";
		} else {
			echo "keywords=".$keywords."*&";
		}
		echo "for2=".$for2."&for4=".$for4."&Order=OA' ); \" ";
		echo "data-toggle=\"tooltip\" title=\" \nClick to order based on inclusion in the Directory of Open Access Journals.\n \">";
		echo "DOAJ</a></strong></td>\n";
		
//////////////////////////////////////////// Actions		
		
		echo "<td class=\"text-center\" style=\"border-top: 7px solid #000000 !important; border-bottom: 7px solid #000000 !important;\"><strong><a name=\"Actions\" data-toggle=\"tooltip\" title=\" \nView additional information or Save for comparison.\n \">Actions</a></strong></td>\n";
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
				$snip=number_format((float)$row[43], 3, '.', '');
				$rank=$row[19];
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
				$qcat = preg_replace("/; /i",". ","$qcat");
				$qrank = preg_replace("/; /i","<br />","$qrank");

				$quartilejcr = rtrim($quartilejcr, "; ");
				$qjcrcat = rtrim($qjcrcat, "; ");
				$qjcrrank = rtrim($qjcrrank, "; ");
				$quartilejcr = preg_replace("/; /i","<br />","$quartilejcr");
				$qjcrcat = preg_replace("/; /i","; ","$qjcrcat");
				$qjcrrank = preg_replace("/; /i","<br />","$qjcrrank");
				
				$IFscore = number_format($IFscore,3);
				$fiveyrif = number_format($fiveyrif,3);
				$snip = number_format($snip,3);

////////////////////////////////////////////////////////////////// Metrics (Search)
				
				echo "<tr class=\"success\">\n";
				echo "<td class=\"text-right\"><a href=\"#\" data-toggle=\"tooltip\" title=\"$qcat\" style=\"color:#000000;text-decoration:none;\">$qrank</a></td>";
				echo "<td class=\"text-right\">$snip</td>";
				echo "<td class=\"text-right\"><a href=\"#\" data-toggle=\"tooltip\" title=\"$qjcrcat\" style=\"color:#000000;text-decoration:none;\">$quartilejcr</a></td>";
				echo "<td class=\"text-right\">$qjcrrank</td>";
				echo "<td class=\"text-right\">$IFscore</td>";
				echo "<td class=\"text-right\">$fiveyrif</td>";
				echo "<td class=\"text-center\" nowrap>";
				for($r=0;$r<7;$r++) {
					$q=(10+$r);
					if(($row[$q] != "") && ($row[$q] != " ")) {
						echo "$row[$q]<br />";
					}
				}
				echo "</td>";
				echo "<td class=\"text-left\"><strong>";
				echo stripslashes(htmlentities($row[2]));
				echo "</strong><br />";
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
                    echo "<img src=\"./assets/images/link_elsevier.png\" ";
                    echo "height=\"30\" border=\"0\" style=\"";
                    echo "margin-top: 0px; ";
                    echo "margin-bottom: 0px; ";
                    echo "vertical-align: top; ";
                 // echo "padding: 0px; ";
                    echo "padding: 0.3em; ";
                    echo "border: 0px solid #222222; \">";
					echo "</a> ";
				}

////////////////////////////////////////////////////////////////// WSU OA Funding

				if(($row[42] == "Yes")){
					echo "<a title=\"Read more about Western's Open Access Memberships\" href=\"";
	                echo "https://library.westernsydney.edu.au/main/researchers/open-access/open-access-membership";
	                echo "\" target=\"_SJRSearch\" ";
	                echo "style=\"margin:0px; padding: 0px;\">";
	                echo "<img src=\"./assets/images/link_checkmark.png\" ";
	                echo "height=\"30\" border=\"0\" style=\"";
	                echo "margin-top: 0px; ";
	                echo "margin-bottom: 0px; ";
	                echo "vertical-align: top; ";
	                // echo "padding: 0px; ";
                    echo "padding: 0.3em; ";
	                echo "border: 0px solid #222222; \">";
	                echo "</a> ";
				}
				
////////////////////////////////////////////////////////////////// SJR

				if(($row[33] != "")) {
					echo "<a title=\"Search for this on the Scimago Journal & Country Rank website\" href=\"";
                    echo "https://www.scimagojr.com/";
                    echo "journalsearch.php?q=".$row[33]."&tip=iss\" target=\"_SJRSearch\" ";
                    echo "style=\"margin:0px; padding: 0px;\">";
                    echo "<img src=\"./assets/images/link_sjr.jpg\" ";
                    echo "height=\"30\" border=\"0\" style=\"";
                    echo "margin-top: 0px; ";
                    echo "margin-bottom: 0px; ";
                    echo "vertical-align: top; ";
                    // echo "padding: 0px; ";
                    echo "padding: 0.3em; ";
                    echo "border: 0px solid #222222; \">";
					echo "</a> ";
				}
                
////////////////////////////////////////////////////////////////// Ulrich
				
                echo "<input type=\"image\" ";
                echo "src=\"./assets/images/link_ulrichsweb.jpg\" ";
                echo "alt=\"Search Ulrich Database\" ";
                echo "style=\"";
                echo "margin-top: 0px; ";
                echo "margin-bottom: 0px; ";
                echo "width: 35px !important; ";
                echo "height: 30px !important; ";
                echo "vertical-align: top; ";
                // echo "padding: 0px; ";
                echo "padding: 0.3em; ";
                echo "border: 0px solid #222222; ";
                echo "\" /> ";  

////////////////////////////////////////////////////////////////// Scopus
			
				if(($row[20] != "")){
					echo "<a title=\"Search for this journal in Scopus\" href=\"";
	            	echo "https://www.scopus.com/sourceid/".$row[20]."\" target=\"_ScopusSearch\" ";
	            	echo "style=\"margin:0px; padding: 0px;\">";
	            	echo "<img src=\"./assets/images/link_scopus.png\" ";
	            	echo "height=\"30\" border=\"0\" style=\"";
	            	echo "margin-top: 0px; ";
	            	echo "margin-bottom: 0px; ";
	            	echo "vertical-align: top; ";
	            // echo "padding: 0px; ";
                	echo "padding: 0.3em; ";
	            	echo "border: 0px solid #222222; \">";
					echo "</a> ";
				}
                
////////////////////////////////////////////////////////////////// UWS Library
				
				echo "<a title=\"Search for this in the WSU Library collection\" href=\"";
                echo "https://west-sydney-primo.hosted.exlibrisgroup.com/";
                echo "primo-explore/search?query=title,exact,";
				echo htmlentities($row[2]);
				echo ",AND&pfilter=pfilter,exact,journals,AND&tab=default_tab&";
                echo "search_scope=default_scope&vid=UWS-ALMA&lang=en_US&";
                echo "mode=advanced&offset=0&fn=search";
				echo "\" target=\"_LibrarySearch\" style=\"margin:0px; padding: 0px;\">";
                echo "<img src=\"./assets/images/link_wsu.jpg\" height=\"30\" ";
                echo "border=\"0\" ";
                echo "style=\"";
                echo "margin-top: 0px; ";
                echo "vertical-align: top; ";
                echo "margin-bottom: 0px; ";
                // echo "padding: 0px; ";
                echo "padding: 0.3em; ";
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
			
/////////////////////////////////////////////////////////// View trigger
			
				echo "<td class=\"text-center\">$OAccessImg</td>";
				echo "<td class=\"text-left\">";
				echo "<a class=\"btn ";
				if(($snip != "")) {
					echo "btn-default ";
					$fsnip = "y";
				} else {
					echo "btn-default ";
					$fsnip = "n";
				}
				echo "btn-sm\" ";
                echo "style=\"width: 75px !important; margin-bottom: 5px !important; \" ";
				echo "href=\"http://sherpa.ac.uk/romeo/search.php?issn=".$row[10]."\" target=\"_sherpa\">";
				echo "ViewOA</a>";

/////////////////////////////////////////////////////////// Save and Remove triggers

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
		}
		
////////////////////////////////// All journals non-wildcard
		
		if(($wildcard != "y") && ($keywords != "VIEWSAVED")) {
			if(($Order == "QUARTILEJCR")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY ";
				$query .= "case when JCR_Quartile in('', '0') then 1 else 0 end, JCR_Quartile ASC, cast(JCR_Rank as unsigned)";
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
				$query .= "WHERE (FoR1 = \"$forCode\" OR FoR2 = \"$forCode\" OR FoR3 = \"$forCode\") ";
				if(($eRAID != "")) {
					$query .= "AND ERAID != \"$eRAID\" ";
				}
				$query .= "ORDER BY Title ASC"; 
			}
		}
			
////////////////////////////////// All journals with wildcard
			
		if(($wildcard == "y") && ($keywords != "VIEWSAVED")) {
			if(($Order == "QUARTILEJCR")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when JCR_Quartile in('', '0') then 1 else 0 end, JCR_Quartile ASC, cast(JCR_Rank as unsigned)";
			} 
			if(($Order == "QUARTILE")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE Title LIKE \"%$keywords%\" ";
				$query .= "ORDER BY ";
				$query .= "case when SCIMAGO_Rank in('', '0') then 1 else 0 end, SCIMAGO_Rank ASC";
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
				$query .= "case when SNIP_2017 in('', '0') then 1 else 0 end, convert(`SNIP_2017`, decimal(5,3)) DESC";
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
			if(($Order == "QUARTILEJCR")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
				$query .= "case when JCR_Quartile in('', '0') then 1 else 0 end, JCR_Quartile ASC, cast(JCR_Rank as unsigned)";
			} 
			if(($Order == "QUARTILE")) {
				$query = "SELECT * FROM 2017_journals_final_list ";
				$query .= "WHERE ($constructSQL) ";
				$query .= "ORDER BY ";
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
		
			$m++;
			$eRaids[$m] = $row[1];
			$snip=number_format((float)$row[43], 3, '.', '');
			$rank=$row[19];
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
			$qcat = preg_replace("/; /i",".\n","$qcat");
			$qrank = preg_replace("/; /i","<br />","$qrank");

			$quartilejcr = rtrim($quartilejcr, "; ");
			$qjcrcat = rtrim($qjcrcat, "; ");
			$qjcrrank = rtrim($qjcrrank, "; ");
			$quartilejcr = preg_replace("/; /i","<br />","$quartilejcr");
			$qjcrcat = preg_replace("/; /i","; ","$qjcrcat");
			$qjcrrank = preg_replace("/; /i","<br />","$qjcrrank");
			
			$IFscore = number_format($IFscore,3);
			$fiveyrif = number_format($fiveyrif,3);
			$snip = number_format($snip,3);

////////////////////////////////////////////////////////////////// Metrics (All)
			
			echo "<tr class=\"$rowClass\">\n";
			echo "<td class=\"text-right\">";
			echo "<a href=\"#\" data-toggle=\"tooltip\" title=\"$qcat\" style=\"color:#000000;text-decoration:none;\">$qrank</a></td>";
			echo "<td class=\"text-right\">$snip</td>";
			echo "<td class=\"text-right\"><a href=\"#\" data-toggle=\"tooltip\" title=\"$qjcrcat\" style=\"color:#000000;text-decoration:none;\">$quartilejcr</a></td>";
			echo "<td class=\"text-right\">$qjcrrank</td>";
			echo "<td class=\"text-right\">$IFscore</td>";
			echo "<td class=\"text-right\">$fiveyrif</td>";
			echo "<td class=\"text-center\" nowrap>";
			for($r=0;$r<7;$r++) {
				$q=(10+$r);
				if(($row[$q] != "") && ($row[$q] != " ")) {
					echo "$row[$q]<br />";
				}
			}
			echo "</td>";
			echo "<td class=\"text-left\"><strong>";
			echo stripslashes(htmlentities($row[2]));
			echo "</strong><br />";
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
                echo "<img src=\"./assets/images/link_elsevier.png\" ";
                echo "height=\"30\" border=\"0\" style=\"";
                echo "margin-top: 0px; ";
                echo "margin-bottom: 0px; ";
                echo "vertical-align: top; ";
                // echo "padding: 0px; ";
                echo "padding: 0.3em; ";
                echo "border: 0px solid #222222; \">";
				echo "</a> ";
			}

////////////////////////////////////////////////////////////////// WSU OA Funding

			if(($row[42] == "Yes")){
				echo "<a title=\"Read more about Western's Open Access Memberships\" href=\"";
                echo "https://library.westernsydney.edu.au/main/researchers/open-access/open-access-membership";
                echo "\" target=\"_SJRSearch\" ";
                echo "style=\"margin:0px; padding: 0px;\">";
                echo "<img src=\"./assets/images/link_checkmark.png\" ";
                echo "height=\"30\" border=\"0\" style=\"";
                echo "margin-top: 0px; ";
                echo "margin-bottom: 0px; ";
                echo "vertical-align: top; ";
                // echo "padding: 0px; ";
                echo "padding: 0.3em; ";
                echo "border: 0px solid #222222; \">";
                echo "</a> ";
			}
			
////////////////////////////////////////////////////////////////// SJR

			if(($row[33] != "")) {
				echo "<a title=\"Search for this on the Scimago Journal & Country Rank website\" href=\"";
                echo "https://www.scimagojr.com/";
                echo "journalsearch.php?q=".$row[33]."&tip=iss\" target=\"_SJRSearch\" ";
                echo "style=\"margin:0px; padding: 0px;\">";
                echo "<img src=\"./assets/images/link_sjr.jpg\" ";
                echo "height=\"30\" border=\"0\" style=\"";
                echo "margin-top: 0px; ";
                echo "margin-bottom: 0px; ";
                echo "vertical-align: top; ";
                // echo "padding: 0px; ";
                echo "padding: 0.3em; ";
                echo "border: 0px solid #222222; \">";
				echo "</a> ";
			}
			
////////////////////////////////////////////////////////////////// Ulrich
			
            echo "<input type=\"image\" ";
            echo "src=\"./assets/images/link_ulrichsweb.jpg\" ";
            echo "alt=\"Search Ulrich Database\" ";
            echo "style=\"";
            echo "margin-top: 0px; ";
            echo "margin-bottom: 0px; ";
            echo "width: 35px !important; ";
            echo "height: 30px !important; ";
            echo "vertical-align: top; ";
            // echo "padding: 0px; ";
            echo "padding: 0.3em; ";
            echo "border: 0px solid #222222; ";
            echo "\" /> ";

////////////////////////////////////////////////////////////////// Scopus
			
			if(($row[20] != "")){
				echo "<a title=\"Search for this journal in Scopus\" href=\"";
            	echo "https://www.scopus.com/sourceid/".$row[20]."\" target=\"_ScopusSearch\" ";
            	echo "style=\"margin:0px; padding: 0px;\">";
            	echo "<img src=\"./assets/images/link_scopus.png\" ";
            	echo "height=\"30\" border=\"0\" style=\"";
            	echo "margin-top: 0px; ";
            	echo "margin-bottom: 0px; ";
            	echo "vertical-align: top; ";
            // 	echo "padding: 0px; ";
            	echo "padding: 0.3em; ";
            	echo "border: 0px solid #222222; \">";
				echo "</a> ";
			}
            
////////////////////////////////////////////////////////////////// UWS Library
		
            echo "<a title=\"Search for this in the WSU Library collection\" href=\"";
			echo "https://west-sydney-primo.hosted.exlibrisgroup.com/primo-explore/search?query=title,exact,";
			echo htmlentities($row[2]);
			echo ",AND&pfilter=pfilter,exact,journals,AND&tab=default_tab&";
            echo "search_scope=default_scope&vid=UWS-ALMA&lang=en_US&mode=advanced&offset=0&fn=search";
			echo "\" target=\"_LibrarySearch\" style=\"margin:0px; padding: 0px;\">";
            echo "<img src=\"./assets/images/link_wsu.jpg\" height=\"30\" ";
            echo "border=\"0\" ";
            echo "style=\"";
            echo "margin-top: 0px; ";
            echo "vertical-align: top; ";
            echo "margin-bottom: 0px; ";
            // echo "padding: 0px; ";
            echo "padding: 0.3em; ";
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
			
/////////////////////////////////////////////////////////// View triggers
			
			echo "<td class=\"text-center\">$OAccessImg</td>";
			echo "<td class=\"text-left\" style=\"white-space: nowrap;\" nowrap>";
			echo "<a class=\"btn ";
			if(($snip != "")) {
				echo "btn-default ";
				$fsnip = "y";
			} else {
				echo "btn-default ";
				$fsnip = "n";
			}
			echo "btn-sm\" ";
            echo "style=\"width: 75px !important; margin-bottom: 5px !important; \" ";
			echo "href=\"http://sherpa.ac.uk/romeo/search.php?issn=".$row[10]."\" target=\"_sherpa\">";
			echo "ViewOA";
			echo "</a><br />";

/////////////////////////////////////////////////////////// Save and Remove triggers

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
	
/////////////////////////////////////////////////////////// Finish

		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p>&nbsp;</p>";
		
/////////////////////////////////////////////////////////// Finish
				
		echo "</div>\n";
	}	
?>
	<div class="modal fade bs-example-modal-lg" 
		tabindex="-1" 
		role="dialog" 
		aria-labelledby="myLargeModalLabel" 
		aria-hidden="true">
  		<div class="modal-dialog modal-lg">
    		<div class="modal-content">
      			<div class="modal-body">
    				<iframe id="iframesrc" name="iframesrc" 
	    				width="100%" 
	    				height="850" 
	    				style="overflow: hidden;" 
	    				frameborder="0" 
	    				marginheight="0" 
	    				marginwidth="0" 
	    				scrolling="yes" 
	    				src = "">
    				</iframe>
    			</div>
    		</div>
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

			$('a[data-toggle="modal"]').off('click').on('click', function(e){
				e.preventDefault();
				var href = $(this).attr('href');
		        $(this).on('click', function () {
		            $('#iframesrc').attr('src', '');
		            $('#iframesrc').attr('src', href);
		        });
		    });

			$(".bs-example-modal-lg").on('hidden.bs.modal', function () {
    			$(this).removeData('bs.modal');
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

		});
		
    </script>
<?php
	
/////////////////////////////////////////////////////////// Footer

	include("./admin/era.dbdisconnect.php");	
	
?>
