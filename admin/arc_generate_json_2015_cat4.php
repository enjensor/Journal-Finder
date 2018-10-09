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
/////////////////////////////////////////////////////////// Vars

	include("config.php");
	include("era.dbconnect.php");
	$dojson = "y";
	$doAltFields = "";
	
/////////////////////////////////////////////////////////// JSON array data

	if(($dojson == "y")) {
		$parse="Generate JSON for ERA data";
		
		$unis = array("Australian Catholic University", "Australian National University", "Batchelor Institute of Indigenous Tertiary Education", "Bond University", "Central Queensland University", "Charles Darwin University", "Charles Stuart University", "Curtin University of Technology", "Deakin University", "Edith Cowan University", "Flinders University", "Griffith University", "James Cook University", "La Trobe University", "Macquarie University", "Melbourne College of Divinity", "Monash University", "Murdoch University", "Queensland University of Technology", "RMIT University", "Southern Cross University", "Swinburne University of Technology", "University of Adelaide", "University of Ballarat", "University of Canberra", "University of Melbourne", "University of New England", "University of New South Wales", "University of Newcastle", "University of Notre Dame Australia", "University of Queensland", "University of South Australia", "University of Southern Queensland", "University of Sydney", "University of Tasmania", "University of Technology Sydney", "University of the Sunshine Coast", "University of Western Australia", "University of Western Sydney", "University of Wollongong", "Victoria University");
		
		$fields = array("ID", "Institution", "01 Mathematical Sciences", "0101 Pure Mathematics", "0102 Applied Mathematics", "0103 Numerical and Computational Mathematics", "0104 Statistics", "0105 Mathematical Physics", "0199 Other Mathematical Sciences", "02 Physical Sciences", "0201 Astronomical and Space Sciences", "0202 Atomic Molecular Nuclear Particle and Plasma Physics", "0203 Classical Physics", "0204 Condensed Matter Physics", "0205 Optical Physics", "0206 Quantum Physics", "0299 Other Physical Sciences", "03 Chemical Sciences", "0301 Analytical Chemistry", "0302 Inorganic Chemistry", "0303 Macromolecular and Materials Chemistry", "0304 Medicinal and Biomolecular Chemistry", "0305 Organic Chemistry", "0306 Physical Chemistry", "0307 Theoretical and Computational Chemistry", "0399 Other Chemical Sciences", "04 Earth Sciences", "0401 Atmospheric Sciences", "0402 Geochemistry", "0403 Geology", "0404 Geophysics", "0405 Oceanography", "0406 Physical Geography and Environmental Geoscience", "0499 Other Earth Sciences", "05 Environmental Sciences", "0501 Ecological Applications", "0502 Environmental Science and Management", "0503 Soil Sciences", "0599 Other Environmental Sciences", "06 Biological Sciences", "0601 Biochemistry and Cell Biology", "0602 Ecology", "0603 Evolutionary Biology", "0604 Genetics", "0605 Microbiology", "0606 Physiology", "0607 Plant Biology", "0608 Zoology", "0699 Other Biological Sciences", "07 Agriculture and Veterinary Sciences", "0701 Agriculture Land and Farm Management", "0702 Animal Production", "0703 Crop and Pasture Production", "0704 Fisheries Sciences", "0705 Forestry Sciences", "0706 Horticultural Production", "0707 Veterinary Sciences", "0799 Other Agricultural and Veterinary Sciences", "08 Information and Computing Sciences", "0801 Artificial Intelligence and Image Processing", "0802 Computation Theory and Mathematics", "0803 Computer Software", "0804 Data Format", "0805 Distributed Computing", "0806 Information Systems", "0807 Library and Information Studies", "0899 Other Information and Computing Sciences", "09 Engineering", "0901 Aerospace Engineering", "0902 Automotive Engineering", "0903 Biomedical Engineering", "0904 Chemical Engineering", "0905 Civil Engineering", "0906 Electrical and Electronic Engineering", "0907 Environmental Engineering", "0908 Food Sciences", "0909 Geomatic Engineering", "0910 Manufacturing Engineering", "0911 Maritime Engineering", "0912 Materials Engineering", "0913 Mechanical Engineering", "0914 Resources Engineering and Extractive Metallurgy", "0915 Interdisciplinary Engineering", "0999 Other Engineering", "10 Technology", "1001 Agricultural Biotechnology", "1002 Environmental Biotechnology", "1003 Industrial Biotechnology", "1004 Medical Biotechnology", "1005 Communications Technologies", "1006 Computer Hardware", "1007 Nanotechnology", "1099 Other Technology", "11 Medical and Health Sciences", "1101 Medical Biochemistry and Metabolomics", "1102 Cardiovascular Medicine and Haematology", "1103 Clinical Sciences", "1104 Complementary and Alternative Medicine", "1105 Dentistry", "1106 Human Movement and Sports Science", "1107 Immunology", "1108 Medical Microbiology", "1109 Neurosciences", "1110 Nursing", "1111 Nutrition and Dietetics", "1112 Oncology and Carcinogenesis", "1113 Ophthalmology and Optometry", "1114 Paediatrics and Reproductive Medicine", "1115 Pharmacology and Pharmaceutical Sciences", "1116 Medical Physiology", "1117 Public Health and Health Services", "1199 Other Medical and Health Sciences", "12 Built Environment and Design", "1201 Architecture", "1202 Building", "1203 Design Practice and Management", "1204 Engineering Design", "1205 Urban and Regional Planning", "1299 Other Built Environment and Design", "13 Education", "1301 Education Systems", "1302 Curriculum and Pedagogy", "1303 Specialist Studies In Education", "1399 Other Education", "14 Economics", "1401 Economic Theory", "1402 Applied Economics", "1403 Econometrics", "1499 Other Economics", "15 Commerce Management Tourism and Services", "1501 Accounting Auditing and Accountability", "1502 Banking Finance and Investment", "1503 Business and Management", "1504 Commercial Services", "1505 Marketing", "1506 Tourism", "1507 Transportation and Freight Services", "1599 Other Commerce Management Tourism and Services", "16 Studies In Human Society", "1601 Anthropology", "1602 Criminology", "1603 Demography", "1604 Human Geography", "1605 Policy and Administration", "1606 Political Science", "1607 Social Work", "1608 Sociology", "1699 Other Studies In Human Society", "17 Psychology and Cognitive Science", "1701 Psychology", "1702 Cognitive Science", "1799 Other Psychology and Cognitive Science", "18 Law and Legal Studies", "1801 Law", "1802 Maori Law", "1899 Other Law and Legal Studies", "19 Studies In Creative Arts and Writing", "1901 Art Theory and Criticism", "1902 Film Television and Digital Media", "1903 Journalism and Professional Writing", "1904 Performing Arts and Creative Writing", "1905 Visual Arts and Crafts", "1999 Other Studies In Creative Arts and Writing", "20 Language Communication and Culture", "2001 Communication and Media Studies", "2002 Cultural Studies", "2003 Language Studies", "2004 Linguistics", "2005 Literary Studies", "2099 Other Language Literature and Culture", "21 History and Archaeology", "2101 Archaeology", "2102 Curatorial and Related Studies", "2103 Historical Studies", "2199 Other History and Archaeology", "22 Philosophy and Religious Studies", "2201 Applied Ethics", "2202 History and Philosophy Of Specific Fields", "2203 Philosophy", "2204 Religion and Religious Studies", "2299 Other Philosophy and Religious Studies");
		
		echo "[<br />";
		$v = 0;
		
/////////////////////////////////////////////////////////// Generate field clusters + Citation or Peer Review Distinction
		
		if(($doAltFields == "")) {
			foreach($fields AS $f) {
				if(($v > 1)) {
					$f = preg_replace("/ /i","_","$f");
					$prefix = "";
					$tempFields = explode("_","$f");
					$queryD = "SELECT CitationAnalysis, Cluster FROM disciplinematrix WHERE ForCode = \"$tempFields[0]\" ";
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					while($rowD = mysqli_fetch_row($mysqli_resultD)) {
						if(($rowD[1] == "***")) { $rowD[1] = "EE"; }
						if(($rowD[0] != "")) {
							$prefix = "CIT".".".$rowD[1];
						} else {
							$prefix = "ERA".".".$rowD[1];
						}
					}
					echo "{";
					echo "\"name\": \"$prefix.$f\", ";
					echo "\"size\": 5, ";
					echo "\"imports\": []";
					echo "},<br />";
				}
				$v++;
			}
		}
		
/////////////////////////////////////////////////////////// Generate university field relationships + Citation or Peer Review Distinction
		
		foreach($unis AS $u) {
			$tempLine = "";
			$found = "";
			$namedU = "";
			$namedU = preg_replace("/ /i","_","$u");
			
//////////////////////////// Note university cluster prefix
			
			$namedU = "UNI.".$namedU;
			
//////////////////////////// Routine cont.
			
			$tempLine .= "{\"name\":\"$namedU\",";
			$tempLine .= "\"size\":5,";
			$tempLine .= "\"imports\":[";
			$query = "SELECT * FROM 2015_era WHERE Institution = \"$u\" ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				for($z=2;$z<181;$z++) {
					if(($row[$z] != "") && ($row[$z] > 3) && ($row[$z] < 5)) {
						$prefix = "";
						$tempFields = explode(" ",$fields[$z]);
						$tF = $tempFields[0];
						$queryD = "SELECT CitationAnalysis, Cluster FROM disciplinematrix WHERE ForCode = \"$tF\" ";
						$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
						while($rowD = mysqli_fetch_row($mysqli_resultD)) {
							if(($rowD[1] == "***")) { $rowD[1] = "EE"; }
							if(($rowD[0] != "")) {
								$prefix = "CIT"."."."$rowD[1]";
							} else {
								$prefix = "ERA"."."."$rowD[1]";
							}
						}	
						$namedSpace = "";
						$myfield = "";
						$myfield = preg_replace("/ /i","_","$fields[$z]");
						$namedSpace = "$prefix.".$myfield;
						$tempLine .= "\"".$namedSpace."\",";
						$found = "y";
					}
				}
			}
			$tempLine = rtrim($tempLine, ",");
			if(($u == "Victoria University")) {
				$tempLine .= "]}<br />";
			} else {
				$tempLine .= "]},<br />";	
			}
			if(($found == "y")) {
				echo $tempLine;
			}
		}
		
		echo "]<br />";
	}

/////////////////////////////////////////////////////////// Finish

	include("era.dbdisconnect.php");
?>