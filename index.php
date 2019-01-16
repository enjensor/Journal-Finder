<?php

/////////////////////////////////////////////////////////// Credits
//
//
//	Journal Finder
//	Western Sydney University
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
//  UPDATED
//  18-19 FEBRUARY 2014
//	24 MARCH - 11 APRIL 2014
//	29 AUGUST 2017
//  12 December 2018
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");

/////////////////////////////////////////////////////////// Page header

?>
<!--


	Project: ERA Journal Identification Toolkit
	Project Team: Susan Robbins, Michael Gonzalez, Jason Ensor (Team Leader & Developer)
	Project Base: Western Sydney University Library
	Project Methodology: Procedural Scripting PHP | MySQL | JQuery



	FOR ALL ENQUIRIES ABOUT CODE

	Who:	Dr Jason Ensor
	Email: 	j.ensor@westernsydney.edu.au | jasondensor@gmail.com
	Web: 	www.jasonensor.com
	Mobile:	0419 674 770



  	WEB FRAMEWORK

  	Bootstrap Twitter v3.3.5 | getbootstrap.com/
  	Font Awesome v4.4.0 | fortawesome.github.io/Font-Awesome/
  	Google Fonts API | fonts.googleapis.com
  	Modernizr v.2.8.3 | modernizr.com/
  	JQuery v.2.1.4 | jquery.com/download/
	JQuery UI v.1.11.4 | jqueryui.com/
	Bootstrap Material Design | fezvrasta.github.io/bootstrap-material-design/



  	UPDATED
    
  	Development Started: 18 February 2014
	Last updated: 11 December 2018
















//-->
<!DOCTYPE html>
<html lang="en">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]> 	   <html class="no-js"> <![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Journal Finder - Western Sydney University Library</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Journal Finder">
        <meta name="robots" content="noindex,nofollow">
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700&subset=latin,cyrillic-ext,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="./js/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="./js/mlpm/jquery.multilevelpushmenu_grey.css">
		<link rel="stylesheet" href="./js/jquery-ui/themes/base/jquery.ui.all.css">
        <style type="text/css">
		
			body {
				background: #FFFFFF;
                overflow: hidden !important;
                height: 100%;
			}

			#pushobj {
    			margin-left: 411px;
                margin-bottom: 0px;
                padding-bottom: 0px;
			}

			#menu {
    			-ms-opacity: 1;
    			opacity: 1;
			}
			
			.ui-autocomplete {
				max-height: 360px;
				overflow-y: auto;
				overflow-x: hidden;
			}
			
			.axis path, .axis line {
				fill: none;
				stroke: #000;
				shape-rendering: crispEdges;
			}
			
			.bar {
  				fill: steelblue;
			}

			.x.axis path {
				display: none;
			}

			.line {
				fill: none;
				stroke: steelblue;
				stroke-width: 1.5px;
			}
			
			* html .ui-autocomplete {
				height: 360px;
			}
			
			.fancybox-custom .fancybox-skin {
				box-shadow: 0 0 50px #222;
			}
			
			.tooltip-inner {
    			white-space:pre-wrap;
			}
			
			::-webkit-scrollbar {
    			/*-webkit-appearance: none; */
    			width: 15px;
			}
			
			::-webkit-scrollbar-thumb {
    			border-radius: 3px;
                background-color: #aaaaaa;
    			-webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
			}

            ::-webkit-scrollbar-track-piece {
                background: #dddddd;
            }

			.btn-customa {
  				background-color: hsl(0, 0%, 36%) !important;
  				background-repeat: repeat-x;
  				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#8e8e8e", endColorstr="#5b5b5b");
  				background-image: -khtml-gradient(linear, left top, left bottom, from(#8e8e8e), to(#5b5b5b));
  				background-image: -moz-linear-gradient(top, #8e8e8e, #5b5b5b);
  				background-image: -ms-linear-gradient(top, #8e8e8e, #5b5b5b);
  				background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #8e8e8e), color-stop(100%, #5b5b5b));
  				background-image: -webkit-linear-gradient(top, #8e8e8e, #5b5b5b);
  				background-image: -o-linear-gradient(top, #8e8e8e, #5b5b5b);
  				background-image: linear-gradient(#8e8e8e, #5b5b5b);
  				border-color: #5b5b5b #5b5b5b hsl(0, 0%, 31%);
  				color: #fff !important;
  				width:80px;
  				text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33);
  				-webkit-font-smoothing: antialiased;
			}
			
			.btn-customb {
  				background-color: hsl(195, 60%, 35%) !important;
  				background-repeat: repeat-x;
  				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#2d95b7", endColorstr="#23748e");
  				background-image: -khtml-gradient(linear, left top, left bottom, from(#2d95b7), to(#23748e));
  				background-image: -moz-linear-gradient(top, #2d95b7, #23748e);
  				background-image: -ms-linear-gradient(top, #2d95b7, #23748e);
  				background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2d95b7), color-stop(100%, #23748e));
  				background-image: -webkit-linear-gradient(top, #2d95b7, #23748e);
  				background-image: -o-linear-gradient(top, #2d95b7, #23748e);
  				background-image: linear-gradient(#2d95b7, #23748e);
  				border-color: #23748e #23748e hsl(195, 60%, 32.5%);
  				color: #fff !important;
  				width:80px;
  				text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.16);
  				-webkit-font-smoothing: antialiased;
			}
			
			.btn-customc {
  				background-color: hsl(0, 69%, 32%) !important;
  				background-repeat: repeat-x;
  				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#891919", endColorstr="#891919");
  				background-image: -khtml-gradient(linear, left top, left bottom, from(#891919), to(#891919));
  				background-image: -moz-linear-gradient(top, #891919, #891919);
  				background-image: -ms-linear-gradient(top, #891919, #891919);
  				background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #891919), color-stop(100%, #891919));
  				background-image: -webkit-linear-gradient(top, #891919, #891919);
  				background-image: -o-linear-gradient(top, #891919, #891919);
  				background-image: linear-gradient(#891919, #891919);
  				border-color: #891919 #891919 hsl(0, 69%, 32%);
  				color: #fff !important;
  				text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.00);
  				-webkit-font-smoothing: antialiased;
  				width:120px;
			}
			
			a {
				color: #9f2137;	
			}
		
		</style>
        <script language="javascript" type="text/javascript" src="./js/modernizr.min.js"></script>
        <script language="javascript" type="text/javascript" src="./js/d3.v3.min.js"></script>
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="./js/jqplot/jquery.jqplot.css" />
        <link rel="stylesheet" type="text/css" href="https://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.css" />
        <!--[if lt IE 9]>
			<script language="javascript" type="text/javascript" src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script language="javascript" type="text/javascript" src="./js/jqplot/excanvas.js"></script>
		<![endif]-->
    </head>
    <body style="overflow-x: hidden;">
	<!--[if lt IE 7]>
    	<p class="browsehappy">You are using an <strong>outdated</strong> browser. 
    	Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
<?php

/////////////////////////////////////////////////////////// Begin wrapper

?>       
	<div id="page-wrap" style="display: none;">
    	<div class="container-fluid">
        	<div id="bodyDiv">
				<div class="row">
				
<?php

/////////////////////////////////////////////////////////// Right panel content scaffold start

?>
				
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 0px; padding: 0px; overflow: auto;" id="scrollingP">
                    	<div>
                        	<div id="pushobj">
                                <div id="pushobjhome" class="panel panel-default"  style="margin-bottom: 0px; padding-bottom: 0px; background: #9f2137; border: 0px solid #000000;">
									<div class="panel-heading" id="matrixHeader"  style="background: #9f2137;">
                                        <h3><a href="./" target="_self" style="text-decoration: none; color: #ffffff;font-variant: small-caps;" data-toggle="tooltip" data-placement="bottom" title="Click here to return to the home page and search for a journal."><span class="glyphicon glyphicon-search"></span> &nbsp;Journal Finder</a></h3>
                                    </div>
  									<div class="panel-body" id="matrixBody" style="background-color: #efefef; min-height: 1600px;">
<?php

/////////////////////////////////////////////////////////// Right panel content area

?>  
										
  										<div class="ui-widget col-lg-12" style="padding:30px;">
                                            <form role="form" name="journalFind" id="journalFind">
												<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em !important;"><strong>SEARCH BY PUBLICATION TITLE</strong></p>
                                                <p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em !important;">By entering a few characters, the autocomplete function will suggest journal titles from the database whereby you can click on a name to discover more about that publication. Alternatively, you can use an asterisk (*) as a wildcard character to limit your search results to journals only containing the search term. For example, searching for "Austr*" will return all journals with Austria, Austrian, Australia, Australian, Australasian, etc., in their names (but you should avoid using common phrases like 'Journal of' and 'International Journal of').</p>
												<p style="text-align:left;">&nbsp;
                                                <br /><input type="text" name="journalKeywords" id="journalKeywords" 
                                                	placeholder="Please enter 5 or more characters from the title ..." class="form-control" autofocus >
												</p>
                                        		<p style="text-align:left;">&nbsp;
                                                <br /><input type="submit" name="submit" value="Search" class="btn btn-customb">
													<input type="reset" name="Reset" value="Reset" class="btn btn-customa" onclick="$('#journalKeywords').focus();">
													<?php
														$c = count($_SESSION["ERAIDS"]); 
														if(($c >0)) {
													?>
													<input type="button" id="viewSaved" name="viewSaved" value="View Saved" class="btn btn-customc" onclick="">
													<?php
														}
													?> 
												</p>
                                            </form>
                                        </div>                                               
                                        &nbsp;<br />                           
                                    	<div class="col-lg-12" style="padding-left:30px;padding-right:30px;">
                                        	<div class="col-lg-12" style="padding: 30px; background-color:#ffffff;">
                                                <p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em;">&nbsp;<br /><strong>SEARCH BY FIELD OF RESEARCH</strong></p>
                                                <p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em;">Please select a research cluster from the left-hand panel to view its discipline groups and discipline fields. All clusters, groups and fields of research have been organised according to the latest Australia Research Council Discipline Matrix.</p>
                                                <p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em;">&nbsp;<br /><strong>YOUR GUIDE TO THE BEST JOURNALS</strong></p>
                                                <p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em;">Journal Finder is a tool to help all Western Sydney University researchers publish their work in relevant high quality journals by providing contextual information on how a journal counts towards a particular field in Australia. This is a work in progress as we roll out new features and updates over the coming months. Please note that some functions are configured for use on a computer connected to a UWS campus network only and that it works best on large screens. As a web service in development, this tool is fully functional on the latest version of <a href="https://www.google.com/chrome" target="_GoogleChrome">Google Chrome</a>. If you would like to know more about the rationale behind this project, please click <a href="javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); var doThis = $( '#matrixBody' ).load( 'arc_paper.php' ); var doThisAlso = $('#scrollingP').scrollTop(0);">here</a> to read a <em>position paper</em> or contact the <a href="mailto:lib-research@westernsydney.edu.au?subject=Journal_Finder">Library</a>.</p>
                                                <p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em;">&nbsp;<br /><strong>ERA RESULTS 2015</strong> - <a href="./arc_era_results_2015_cat3.php" target="_ERAresults1">Ranking 3</a> | <a href="./arc_era_results_2015_cat4.php" target="_ERAresults1">Ranking 4</a> | <a href="./arc_era_results_2015_cat5.php" target="_ERAresults3">Ranking 5</a> | <strong>2012</strong> - <a href="./arc_era_results_cat3.php" target="_ERAresults4">Ranking 3</a> | <a href="./arc_era_results_cat4.php" target="_ERAresults5">Ranking 4</a> | <a href="./arc_era_results_cat5.php" target="_ERAresults6">Ranking 5</a><!-- | <strong>Interactive Graph</strong> - <a href="./arc_modal_era_factor.php" target="_ERAanalysis">Analysis (Beta)</a>//--></p>
                                                 <p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em;">&nbsp;<br />Please note that only rankings 3, 4 and 5 are available as these demonstrate FoRs (fields of research) in Australia that are at World Standard or above. The FoRs are in the same order for each rank level although only universities that have an ERA rating in at least one of the FoRs appears on the list. FoRs are grouped according to discipline clusters and an FoR's citation or peer reviewed status. Going clockwise from the university list, they are grouped as follows: Citation-Based FoRs in Clusters BB, EE, MHS, MIC, PCE, followed by Peer-Review-Based FoRs in Clusters EC, EE, EHS, HCA, MIC. Some clusters have a mixture of citation and peer-review FoRs. For example, Pure Mathematics (0101) is a peer-reviewed FoR and not a Citation-reviewed FoR like its companions 0102, 0103, etc. So it is categorised with the peer-review side of the graph. The color code is meant to signal weight, moving from green (eight or more institution connections) to red (only one connection), with grey indicating no universities ranking in this field at this rating.</p>
                                                 <p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;">
                                                    &nbsp;<br />&nbsp;<br />
                                                    <img src="./img/logo_library.png" border="0" alt="" style="padding-right:0px;">
                                                    <!-- <img src="./img/logo_dhrg.png" border="0" alt="" style="padding-left:35px;"> //-->
                                                 </p>
                                                 <p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 11px; color: #777777;">&nbsp;<br />&nbsp;<br /><em>Last Updated, Dr Jason Ensor, 06 December 2018 Version 0.9.7<br />Authored by the Western Sydney University Library.<br />Project Team: Susan Robbins (2014-2018), Michael Gonzalez (2014-2018), Jason Ensor (2014-2018).<br />Western Sydney University CRICOS Provider No: 00917k.</em></p>
                                             </div>
                                             &nbsp;<br /> 
  										</div>
                                        &nbsp;

<?php

/////////////////////////////////////////////////////////// Right panel content scaffold finish

?>																
  									</div>
								</div>
                        	</div>
                    	</div>
             		</div>
<?php

/////////////////////////////////////////////////////////// Responsive menu

?>
                	<div id="menu">
                    	<nav>
                        	<h2><i class="fa fa-reorder"></i><span style="color:#aaaaaa;">&nbsp;&nbsp;&nbsp;Clusters of Research</span></h2>
                        	<ul>
<?php

								$query = "SELECT * FROM disciplinecluster ORDER BY DisciplineCluster ASC"; 
								$mysqli_result = mysqli_query($mysqli_link, $query);
								while($row = mysqli_fetch_row($mysqli_result)) { 
						
//////////////////////////// Start cluster

									echo "<li>";
									echo "<a href=\"javascript: $( '#matrixBody' ).load( 'arc_matrix_default.php?for2=&for4=' );\" style=\"text-decoration: none;\">";
									echo "<i class=\"fa fa-folder-o\"></i>$row[1]</a>\n";
									echo "<h2><i class=\"fa fa-folder-open-o\"></i><span style=\"color:#aaaaaa;\">$row[1]<br />Groups of Research</span></h2>";
									echo "<ul>\n";
							
//////////////////////////// Start 2-digit FoR
							
									$queryD = "SELECT * FROM forname2 WHERE DisciplineClusterID = \"$row[2]\" ORDER BY FoRCode ASC"; 
									$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
									while($rowD = mysqli_fetch_row($mysqli_resultD)) {
										echo "<li>";
										echo "<a href=\"javascript: $( '#matrixBody' ).load( 'arc_matrix_default.php?for2=".$rowD[0]."' );\" style=\"text-decoration: none;\">";
										echo "<i class=\"fa fa-folder-o\"></i>$rowD[0] $rowD[1]</a>\n";
										echo "<h2><i class=\"fa fa-folder-open-o\"></i><span style=\"color:#aaaaaa;\">$rowD[0] $rowD[1]<br />Fields of Research</span></h2>";
										echo "<ul>\n";
								
//////////////////////////// Start 4-digit FoR
								
										$queryB = "SELECT * FROM forname4 WHERE DisciplineClusterID = \"$row[2]\" AND FoRCode LIKE \"$rowD[0]%\" ORDER BY FoRCode ASC"; 
										$mysqli_resultB = mysqli_query($mysqli_link, $queryB);
										while($rowB = mysqli_fetch_row($mysqli_resultB)) {
											echo "<li>";
											echo "<a href=\"javascript: $( '#matrixBody' ).load( 'arc_matrix_default.php?for2=&for4=".$rowB[0]."' );\" style=\"text-decoration: none;\">";
											echo "<i class=\"fa fa-list\"></i>$rowB[0] $rowB[1]</a>\n";
								
//////////////////////////// Close 4-digit FoR

											echo "</li>";
										}
                                        
										echo "</ul>";
										mysqli_free_result($mysqli_resultB);
							
//////////////////////////// Close 2-digit FoR
							
										echo "</li>";
									}
									mysqli_free_result($mysqli_resultD);
									echo "</ul>";
							
//////////////////////////// Close cluster
							
									echo "</li>\n";
								}
								mysqli_free_result($mysqli_result);

?>      
								<!-- <li><p><img src="./img/combined.png" style="display: block; margin-left: auto; margin-right: auto; margin-top:15px; margin-bottom:30px;"></p></li> //-->
                                <li><p style="color: #ffffff; text-align: right;padding-right:7px;">&nbsp;<br /><strong>Data Sources</strong><br />
                                Ulrich's Periodicals Directory<br />
								The Australian Business Dean's Council List<br />
                                Western Sydney University Library Databases<br />
                                ARC ERA 2010 Journals List, ARC ERA 2012 Journals List<br />
                                ARC ERA 2018 Journals List, ARC ERA 2018 Disciplinary Matrix<br />
                                DOAJ Open Access Journal Metadata, Journal Metrics SNIP & SJR Historical Data<br />
                                JCR Impact Factors & Citation Reports, JCR Impact Factors Excel SCI<br />
                                SHERPA/RoMEO Publisher Copyright &amp; Self-Archiving Policies<br />
                                SCImago Journal and Country Rank<br />
                                Scopus Journal Metrics 2017<br />
                                Elsevier Database<br />&nbsp;</p></li>
                   	     	</ul>
                    	</nav>
                	</div>
<?php

/////////////////////////////////////////////////////////// End wrapper

?>                
            	</div>
        	</div>
    	</div>
	</div>   
<?php

	include("./admin/era.dbdisconnect.php");

/////////////////////////////////////////////////////////// Configuration JS

?>        
    <script language="javascript" type="text/javascript" src="./js/jquery-1.11.0.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/bootstrap.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/mlpm/jquery.multilevelpushmenu.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.core.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.widget.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.position.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.menu.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.autocomplete.js"></script>
    <script language="javascript" type="text/javascript" src="./js/spin.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jqplot/jquery.jqplot.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jqplot/plugins/jqplot.highlighter.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jqplot/plugins/jqplot.cursor.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
    <script language="javascript" type="text/javascript" >

		$(document).ready(function () {
			
			$('body').on('hidden.bs.modal', '.modal', function () {
        		$(this).removeData('bs.modal');
      		});

			$('[data-toggle="tooltip"]').tooltip({'placement': 'bottom'});
		
			$(function () {	
				$("#journalKeywords").autocomplete({
					source: "arc_journals_search.php", 
					minLength: 5,
					delay: 600, 
					maxCacheLength: 5, 
					appendTo: ".ui-widget",
					select: function(event, ui) {
        				if(ui.item){
							var valink = ui.item.value;
							valink = valink.replace(/(<u>|<\/u>)/g, "");
							valink = valink.replace(/\+/g, "%2B");
							$('#journalKeywords').val(valink);
        				}
        				$('form#journalFind').submit();
    				}
				});
			});
		
			$("form#journalFind").submit(function(event) { 
				event.preventDefault(); 
				var journalKs = $('#journalKeywords').val();
				if(journalKs != "") {
					var data = 'keywords='+ $('#journalKeywords').val();
					var target = document.getElementById('matrixBody'); 
					var spinner = new Spinner().spin(target); 
					$( '#matrixBody' ).load('arc_journals.php',data); 
					$( '#scrollingP' ).scrollTop(0);
				} else {
					return false;
				}
			});
			
			$("#viewSaved").click(function(event) {
				event.preventDefault(); 
				var data = 'keywords=VIEWSAVED';
				var target = document.getElementById('matrixBody'); 
				var spinner = new Spinner().spin(target); 
				$( '#matrixBody' ).load('arc_journals.php',data); 
				$( '#scrollingP' ).scrollTop(0);				
			});
		
    		$('#menu').multilevelpushmenu({
				
				onItemClick: function() {
        			var event = arguments[0];
					var $menuLevelHolder = arguments[1];
					var $item = arguments[2];
					var options = arguments[3];
					var target = document.getElementById('matrixBody'); 
					var spinner = new Spinner().spin(target); 
        			var itemHref = $item.find( 'a:first' ).attr( 'href' );
					eval(itemHref);
					$("#scrollingP").scrollTop(0);
				},
				
				onGroupItemClick: function() {
        			var event = arguments[0];
					var $menuLevelHolder = arguments[1];
					var $item = arguments[2];
					var options = arguments[3];
					var target = document.getElementById('matrixBody'); 
					var spinner = new Spinner().spin(target);
        			var itemHref = $item.find( 'a:first' ).attr( 'href' );
					eval(itemHref);
					$("#scrollingP").scrollTop(0);
				},
				
				onBackItemClick: function() {
					$( '#matrixBody' ).load( 'arc_matrix_default.php?display=default' );
				},
				
        		containersToPush: [$('#pushobj')],
				mode: 'cover',
        		menuWidth: '410px',
        		menuHeight: '100%',
				backText: 'Back',
				backItemIcon: 'fa fa-angle-left',
				groupIcon: 'fa fa-angle-right',
				swipe: 'both', 
				direction: 'ltr',
        		collapsed: false
    		});
    		$('#menu').multilevelpushmenu('option', 'menuHeight', $(document).height());
    		$('#menu').multilevelpushmenu('redraw');
			$('#page-wrap').delay(500).fadeIn(1000);
			$('#scrollingP').css("height", $(document).height());
			$('#journalKeywords').focus();
		});

		$(window).resize(function (e) {
            var journalBox = $(".ui-widget").width();
            var windowH = $(window).height();
    		$('#menu').multilevelpushmenu('option', 'menuHeight', $(document).height());
    		$('#menu').multilevelpushmenu('redraw');
			$("#scrollingP").css("height", $(document).height());
			$(".ui-autocomplete").css('width', ''+journalBox);
            $("html, body").css("height", $(window).height(), "important");
		});
		
		$.extend($.ui.autocomplete.prototype.options, {
			open: function(event, ui) {
				$(this).autocomplete("widget").css({
            		"width": ($(".ui-widget").width() + "px")
        		});
    		}
		});
		
    </script>
    <script language="javascript" type="text/javascript" >
	
  		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  		ga('create', 'UA-66510853-1', 'auto');
  		ga('send', 'pageview');

	</script>
<?php

/////////////////////////////////////////////////////////// Close page

?> 
    </body>
</html>