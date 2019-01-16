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
//
/////////////////////////////////////////////////////////// Vars

	session_start();
	$wildcard = "";
	$do_not_process = "";
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	$needle = strtolower($_GET['term']);
	
/////////////////////////////////////////////////////////// Clean out generic phrase
	
	$haystack = "journal of ";
	if(strlen($needle)<12) {
		if (strlen(strstr($haystack,$needle))>0) { 
			$do_not_process = "y"; 
		}
	} else {
		$donot_process = "";
	}
	
/////////////////////////////////////////////////////////// Clean out another generic phrase

	$haystack = "international journal of ";
	if(strlen($needle)<26) {
		if (strlen(strstr($haystack,$needle))>0) { 
			$do_not_process = "y"; 
		}
	} else {
		$donot_process = "";
	}

/////////////////////////////////////////////////////////// Create JSON encoded array

	if(($do_not_process == "")) {
		if(preg_match("/\*/i",$_GET['term'])) {
			$wildcard = "y";
			$_GET['term'] = preg_replace("/\*/i","",$_GET['term']);
		}

		if (isset($_GET['term'])){
			$_GET['term'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $_GET['term']);;
			$seek = $_GET['term'];
			$return_arr = array();
			if(($wildcard == "y")) {
				$return_arr[] = "<u>".$_GET['term']."</u>*";	
			}
			$query = "SELECT Title FROM 2017_journals_final_list WHERE Title LIKE \"%$seek%\" ORDER BY Title ASC";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$row[0] = preg_replace("/$seek/i","<u>$seek</u>","$row[0]");
		        $return_arr[] =  $row[0];
		    }
   		 	echo json_encode($return_arr);
		}
	}

/////////////////////////////////////////////////////////// Footer	

	include("./admin/era.dbdisconnect.php");
	
?>