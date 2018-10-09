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
/////////////////////////////////////////////////////////// Main DB configuration

	$localhost = "localhost";
	$username = "***";
	$password = "***";
	$database = "era2015";

/////////////////////////////////////////////////////////// Detaint all vars

	$dbc = @mysqli_connect($localhost, $username, $password);

	foreach($_POST as $key => $value) {
		$newVal = trim($value);
    	$newVal = htmlspecialchars($newVal);
    	$newVal = mysqli_real_escape_string($dbc,$newVal);
		$_POST[$key] = $newVal;
	}

	foreach($_GET as $key => $value) {
		$newVal = trim($value);
    	$newVal = htmlspecialchars($newVal);
    	$newVal = mysqli_real_escape_string($dbc,$newVal);
		$_GET[$key] = $newVal;
	}

	if(($_GET["doit"] == "date")) {
		echo gmmktime(0,0,0,2,18,2014);
	}

?>
