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
//  ERA FACTOR VERSION 0.1
//  02 FEBRUARY 2016
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	$show = $_GET['show'];
	$orderby = $_GET['orderby'];
	$cluster = $_GET['cluster'];
	
/////////////////////////////////////////////////////////// Vars

	if(($cluster == "") or ($cluster == "ALL")) {
		$dbase = "2015_era_averages";	
	}
	if(($cluster == "BBS")) {
		$dbase = "2015_era_averages_bbs";	
	}
	if(($cluster == "EC")) {
		$dbase = "2015_era_averages_ec";	
	}
	if(($cluster == "EES")) {
		$dbase = "2015_era_averages_ees";	
	}
	if(($cluster == "EHS")) {
		$dbase = "2015_era_averages_ehs";	
	}
	if(($cluster == "HCA")) {
		$dbase = "2015_era_averages_hca";	
	}
	if(($cluster == "MCS")) {
		$dbase = "2015_era_averages_mcs";	
	}
	if(($cluster == "MHS")) {
		$dbase = "2015_era_averages_mhs";	
	}
	if(($cluster == "PCES")) {
		$dbase = "2015_era_averages_pces";	
	}
		
/////////////////////////////////////////////////////////// Display data

	echo "Annual,2010,2012,2015\n";
	
	$queryD = "SELECT * FROM $dbase ORDER BY ";
	if(($show == "")) {
		if(($orderby == "")) {
			$queryD .= "2015_efactor DESC";
		}
		if(($orderby == "university")) {
			$queryD .= "unID ASC";
		}
	}
	if(($show == "average")) {
		if(($orderby == "")) {
			$queryD .= "2015_era DESC";
		}
		if(($orderby == "university")) {
			$queryD .= "unID ASC";
		}		
	}
	if(($show == "rank")) {
		if(($orderby == "")) {
			$queryD .= "2015_erank DESC";
		}
		if(($orderby == "university")) {
			$queryD .= "unID ASC";
		}		
	}
	
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
		if(($show == "")) {
			echo "$rowD[8],$rowD[7],$rowD[6],$rowD[5]\n";
		}
		if(($show == "average")) {
			echo "$rowD[8],$rowD[4],$rowD[3],$rowD[2]\n";
		}
		if(($show == "rank")) {
			echo "$rowD[8],$rowD[11],$rowD[10],$rowD[9]\n";
		}
	}
		
/////////////////////////////////////////////////////////// End content layout

	include("./admin/era.dbdisconnect.php");	
	
?>