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
//	19 September 2017
//  07 December 2018
//	13 June 2019
//	17 June 2019
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	
/////////////////////////////////////////////////////////// Vars

	$for2 = $_GET['for2'];
	$for4 = $_GET['for4'];
	$display = $_GET['display'];
	$found = "";
	$dataShow = "n";
	
/////////////////////////////////////////////////////////// Explanatory text
	
	if(($display == "default")) {
		echo "<div class=\"ui-widget col-lg-12\" style=\"padding:30px;\"><div class=\"col-lg-12\" style=\"padding: 30px; background-color:#ffffff;\"><p style=\"text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em !important;\">Fields of Research have been organised into clusters according to the latest Australian Research Council Discipline Matrix (2018). No journal data is available at the cluster level.<br /><br />Please select a 4-digit or 2-digit Field of Research from the left-hand panel. If you cannot find the field of research code or name that you are seeking, please contact the administrator of this service.</p></div></div>";
	}
	
	if((!$for2 && !$for4 && !$display)) {
		echo "<div class=\"ui-widget col-lg-12\" style=\"padding:30px;\"><div class=\"col-lg-12\" style=\"padding: 30px; background-color:#ffffff;\"><p style=\"text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 1.0em !important;\">Fields of Research have been organised into clusters according to the latest Australian Research Council Discipline Matrix (2018). No journal data is available at the cluster level.<br /><br />Please select a 4-digit or 2-digit Field of Research from the left-hand panel. If you cannot find the field of research code or name that you are seeking, please contact the administrator of this service.</p></div></div>";
	}
	
	if(($for2)) {
		$dataShow = "y";
		echo "<p style=\"text-align:justify;\">";
		echo "<strong>Please click on the 'List' button for publication and impact factor information ";
		echo "which can help identify high quality journals within this group.</strong> ";
		echo "For more field-specific information, please select a 4-digit research area from the left-hand panel.</p>";
	}
	
	if(($for4)) {
		$dataShow = "y";
		echo "<p style=\"text-align:justify;\">";
		echo "<strong>Please click on the 'List' button for publication and impact factor information ";
		echo "which can help identify high quality journals within this field.</strong> ";
		echo "For broader group information, please click 'back' and then select a 2-digit research area from the left-hand panel.</p>";
	}
	
/////////////////////////////////////////////////////////// Matrix data

	if(($dataShow == "y")) {
	
		$rowNames = array("ID", "Cluster", "ForCode", "ForName", "LowVolumeThreshold", "CitationAnalysis", "Books", "BookChapters", "JournalArticles", "ConferencePublications", "NonTraditionalOutputs", "ResearchReports", "TraditionalOutput", "HERDCIncome", "EditorPrestigious", "Membership", "Cat1Fellowship", "StatutoryCommittee", "AustaliaCouncilGrants", "Patents", "RegisteredDesigns", "PlantBreeders", "NHMRCGuidelines", "Commercialisation", "ResearchersLevel", "TraditionalOutputsType", "NonTraditionalOutputsType", "ResearchReportsType");
		
		$aliasNames = array("ID", "Cluster", "ForCode", "ForName", "Low Volume Threshold", "Journal Article Citation Analysis", "Peer Review: Books", "Peer Review: Book Chapters", "Peer Review: Journal Articles", "Peer Review: Conference Publications", "Peer Review: Non Traditional Research Outputs", "Peer Review: Research Reports for External Bodies", "Traditional Outputs by Outlet Frequency", "HERDC Research Income (Categories 1-4)", "Esteem: Editor Prestigious Works of Reference", "Esteem: Membership of Learned Academy", "Esteem: Category 1 Research Fellowship", "Esteem: Member of Statutory Committees", "Esteem,: Austalia Council Grants or Fellowships", "Applied Measures: Patents", "Applied Measures: Registered Designs", "Applied Measures: Plant Breeder's Rights", "Applied Measures: NHMRC Endorsed Guidelines", "Applied Measures: Research Commercialisation Income", "Staff Data: Researchers by Level", "Assessable: Traditional Outputs by Type", "Assessable: Other Non Traditional Outputs", "Assessable: Research Reports for External Body");
	
		echo "<div class=\"table-responsive\">&nbsp;<br />\n";
		$query = "SELECT ForName, CitationAnalysis, ForCode FROM disciplinematrix WHERE (ForCode = \"$for2\" OR ForCode = \"$for4\")"; 
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			if(($row[1] == "Yes")) {
				echo "<h3 style=\"text-align:center;\">";
				echo "<span class=\"label label-warning\">";
				echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); ";
				echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?for2=".$for2."&for4=".$for4."' ); var doThisAlso = $('#scrollingP').scrollTop(0);\" ";
				echo "style=\"text-decoration: none; color:#FFFFFF;\">";
				echo "$row[2] $row[0]";
				echo "</a>";
				echo "</span>";
				echo "&nbsp;";
				echo "<span class=\"label label-success\">";
				echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); ";
				echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?for2=".$for2."&for4=".$for4."' ); var doThisAlso = $('#scrollingP').scrollTop(0);\" ";
				echo "style=\"text-decoration: none; color:#FFFFFF;\">";
				echo "List Publications</a></span>";
				echo "&nbsp;";
				echo "</h3><br />\n";
			} else {
				echo "<h3 style=\"text-align:center;\">";
				echo "<span class=\"label label-info\">";
				echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); ";
				echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?for2=".$for2."&for4=".$for4."' ); var doThisAlso = $('#scrollingP').scrollTop(0);\" ";
				echo "style=\"text-decoration: none; color:#FFFFFF;\">";
				echo "$row[2] $row[0]";
				echo "</a>";
				echo "</span>";
				echo "&nbsp;";
				echo "<span class=\"label label-success\">";
				echo "<a href=\"javascript: var target = document.getElementById('matrixBody'); var spinner = new Spinner().spin(target); ";
				echo "var doThis = $( '#matrixBody' ).load( 'arc_journals.php?for2=".$for2."&for4=".$for4."' ); var doThisAlso = $('#scrollingP').scrollTop(0);\" ";
				echo "style=\"text-decoration: none; color:#FFFFFF;\">";
				echo "List Publications</a></span>";
				echo "&nbsp;";
				echo "</h3>\n";
			}
		}
		
/////////////////////////////////////////////////////////// Individual Journal SNIP History

		if(($for4 != "")) {
			echo "<p>&nbsp;</p>";		
			echo "<div id=\"chartdivContainer\" style=\"background-color: #dfdfdf; padding:25px;\">";
			echo "<div id=\"chartdivB\" style=\"position: relative; height:400px; width:99%; background-color: #dfdfdf;";
			echo "padding:0px; border: 0px solid #aaaaaa;\" name=\"chartdivB\" class=\"chartdivB\"></div>";
			echo "<p>&nbsp;</p>";
			echo "<p style=\"padding-left: 10px; text-indent: -10px;\">";
			echo "<strong>* SNIP</strong> measures a source’s contextual citation impact by weighting citations based on the ";
			echo "total number of citations in a subject field.";
			echo "It helps you make a direct comparison of sources in different subject fields.</p>";
			echo "</div>";
			echo "<p>&nbsp;</p>";
		}
		
/////////////////////////////////////////////////////////// Multiple Journals SNIP History
		
		
		if(($for2 != "") && ($for4 == "")) {
			echo "<p>&nbsp;</p>";		
			echo "<div id=\"chartdivContainer\" style=\"background-color: #dfdfdf; padding:25px;\">";
			echo "<div id=\"chartdivB\" style=\"position: relative; height:600px; width:99%; background-color: #dfdfdf;";
			echo "padding:0px; border: 0px solid #aaaaaa;\" name=\"chartdivB\" class=\"chartdivB\"></div>";
			echo "<p>&nbsp;</p>";
			echo "<p style=\"padding-left: 10px; text-indent: -10px;\">";
			echo "<strong>* SNIP</strong> measures a source’s contextual citation impact by weighting citations based on the ";
			echo "total number of citations in a subject field.";
			echo "It helps you make a direct comparison of sources in different subject fields.</p>"; 
			echo "</div>";
			echo "<p>&nbsp;</p>";
		}
		
/////////////////////////////////////////////////////////// ERA Indicators
		
		if(($show_eraIndicators == "y")) {
			echo "<p>&nbsp;</p>";
			echo "<table class=\"table table-striped table-bordered\">\n";
			echo "<thead><tr><th width=\"80%\" style=\"width:80%;\">ERA Indicators : What is countable?</th><th width=\"20%\" style=\"width:20%	;\">#</th></tr></thead>\n";
			echo "<tbody>\n";
			$query = "SELECT * FROM disciplinematrix WHERE (ForCode = \"$for2\" OR ForCode = \"$for4\")"; 
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				for($x=1;$x<28;$x++) {
					if(($row[$x] != "") && ($row[$x] != " ") && ($x != "1") && ($x != "2") && ($x != "3") && ($x != "4") && ($x != "24")) {
						echo "<tr><td width=\"80%\" style=\"width:80%;\">$aliasNames[$x]</td><td width=\"20%\" style=\"width:20%;\">$row[$x]</td></tr>\n";
					};
				}
			}
			echo "</tbody>\n";
			echo "</table>\n";
			echo "<p>&nbsp;</p>";
			echo "</div>\n";
		}
	}
	
/////////////////////////////////////////////////////////// Gather Chart Data for Multiple 4-digit FoRs

	if(($for2 != "") && ($for4 == "")) {
		$gotFoRs = "";
		$FoRs = array();
		$FoRsName = array();
		$query = "SELECT ForCode, ForName FROM disciplinematrix WHERE ForCode LIKE \"$for2%\" AND ForCode != \"$for2\" ORDER BY ForCode ASC";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			$FoRs[] = $row[0];
			$FoRsName[] = $row[1];
			$gotFoRs = "y";
		}		
	}
	
	$g = count($FoRs);
	if(($for2 != "") && ($for4 == "") && ($gotFoRs == "y")) {
		foreach($FoRs as $F) {
			for($x=1999;$x<2018; $x++) {
				$snip[$F][$x] = "0";
				$query = "SELECT (SUM(".$x."_SNIP) / COUNT(".$x."_SNIP)) AS AverageSnip ";
				$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$F\" OR FoR2 = \"$F\" OR FoR3 = \"$F\") ";
				$query .= "AND ".$x."_SNIP != \"\" AND ".$x."_SNIP IS NOT NULL ";
				$query .= "AND FoR1 != \"$for2\" AND FoR2 != \"$for2\" AND FoR3 != \"$for2\" ";	
				$mysqli_result = mysqli_query($mysqli_link, $query);
				while($row = mysqli_fetch_row($mysqli_result)) {
					$snip[$F][$x] = $row[0];
					$snip[$F][$x] = number_format($snip[$F][$x],3);
				}
			}	
		}
	}

/////////////////////////////////////////////////////////// Display Chart for Multiple 4-digit FoRs

	if(($for2 != "") && ($for4 == "") && ($gotFoRs == "y")) {
		$doFoR2 = "y";
	
?>
    <script language="javascript" type="text/javascript">	
	
		var ensorL = $("#chartdivB").empty();
		
<?php		
		$colors = array(
		
			"Indian Red" 		 => "#CD5C5C",
			"Light Coral" 		 => "#F08080",
			"Salmon" 			 => "#FA8072",
			"Dark Salmon" 		 => "#E9967A",
			"Light Salmon" 		 => "#FFA07A",
			"Crimson" 			 => "#DC143C",
			"Red" 				 => "#FF0000",
			"Fire Brick" 		 => "#B22222",
			"Dark Red" 			 => "#8B0000",
			
//			"*TITLE02*"			 => "Pinks",
			"Pink" 				 => "#FFC0CB",
			"Light Pink" 		 => "#FFB6C1",
			"Hot Pink" 			 => "#FF69B4",
			"Deep Pink" 		 => "#FF1493",
			"Medium Violet Red"  => "#C71585",
			"Pale Violet Red" 	 => "#DB7093",
			
//			"*TITLE03*"			 => "Oranges",
			"Light Salmon" 		 => "#FFA07A",
			"Coral" 			 => "#FF7F50",
			"Tomato" 			 => "#FF6347",
			"Orange Red" 		 => "#FF4500",
			"Dark Orange" 		 => "#FF8C00",
			"Orange" 			 => "#FFA500",
			
//			"*TITLE04*"			 => "Yellows",
			"Gold" 				 => "#FFD700",
			"Yellow" 			 => "#FFFF00",
			"Light Yellow" 		 => "#FFFFE0",
			"Lemon Chiffon" 	 => "#FFFACD",
			"Light Gold Yellow"	 => "#FAFAD2",
			"Papaya Whip" 		 => "#FFEFD5",
			"Moccasin" 			 => "#FFE4B5",
			"Peach Puff" 		 => "#FFDAB9",
			"Pale Goldenrod" 	 => "#EEE8AA",
			"Khaki" 			 => "#F0E68C",
			"Dark Khaki" 		 => "#BDB76B",
					
//			"*TITLE05*" 		 => "Purples",
			"Lavender" 			 => "#E6E6FA",
			"Thistle" 			 => "#D8BFD8",
			"Plum" 				 => "#DDA0DD",
			"Violet" 			 => "#EE82EE",
			"Orchid" 			 => "#DA70D6",
			"Fuchsia" 			 => "#FF00FF",
			"Magenta" 			 => "#FF00FF",
			"Medium Orchid" 	 => "#BA55D3",
			"Medium Purple" 	 => "#9370DB",
			"Blue Violet" 		 => "#8A2BE2",
			"Dark Violet" 		 => "#9400D3",
			"Dark Orchid" 		 => "#9932CC",
			"Dark Magenta" 		 => "#8B008B",
			"Purple" 			 => "#800080",
			"Indigo" 			 => "#4B0082",
			"Slate Blue" 		 => "#6A5ACD",
			"Dark Slate Blue" 	 => "#483D8B",
			
//			"*TITLE06*"			 => "Greens",
			"Green Yellow" 		 => "#ADFF2F",
			"Chartreuse" 		 => "#7FFF00",
			"Lawn Green" 		 => "#7CFC00",
			"Lime" 				 => "#00FF00",
			"Lime Green" 		 => "#32CD32",
			"Pale Green" 		 => "#98FB98",
			"Light	Green" 		 => "#90EE90",
			"Medium Sp Green" 	 => "#00FA9A",
			"Spring Green" 		 => "#00FF7F",
			"Medium Sea Green" 	 => "#3CB371",
			"Sea Green" 		 => "#2E8B57",
			"Forest Green" 		 => "#228B22",
			"Green" 			 => "#008000",
			"Dark Green" 		 => "#006400",
			"Yellow Green" 		 => "#9ACD32",
			"Olive Drab" 		 => "#6B8E23",
			"Olive" 			 => "#808000",
			"Dark Olive Green" 	 => "#556B2F",
			"Medium Aquamarine"  => "#66CDAA",
			"Dark Sea Green" 	 => "#8FBC8F",
			"Light Sea Green" 	 => "#20B2AA",
			"Dark Cyan" 		 => "#008B8B",
			"Teal" 				 => "#008080",
			
//			"*TITLE07*"			 => "Blues",
			"Aqua" 				 => "#00FFFF",
			"Cyan" 				 => "#00FFFF",
			"Light Cyan" 		 => "#E0FFFF",
			"Pale Turquoise" 	 => "#AFEEEE",
			"Aquamarine" 		 => "#7FFFD4",
			"Turquoise" 		 => "#40E0D0",
			"Medium Turquoise" 	 => "#48D1CC",
			"Dark Turquoise" 	 => "#00CED1",
			"Cadet Blue" 		 => "#5F9EA0",
			"Steel Blue" 		 => "#4682B4",
			"Light Steel Blue" 	 => "#B0C4DE",
			"Powder Blue" 		 => "#B0E0E6",
			"Light Blue" 		 => "#ADD8E6",
			"Sky Blue" 			 => "#87CEEB",
			"Light Sky Blue" 	 => "#87CEFA",
			"Deep Sky Blue" 	 => "#00BFFF",
			"Dodger Blue" 		 => "#1E90FF",
			"Cornflower Blue" 	 => "#6495ED",
			"Medium Slate Blue"  => "#7B68EE",
			"Royal Blue" 		 => "#4169E1",
			"Blue" 				 => "#0000FF",
			"Medium Blue" 		 => "#0000CD",
			"Dark Blue"			 => "#00008B",
			"Navy" 				 => "#000080",
			"Midnight Blue"	 	 => "#191970",
					
//			"*TITLE08*"			 => "Browns",
			"Cornsilk" 			 => "#FFF8DC",
			"Blanched Almond" 	 => "#FFEBCD",
			"Bisque" 			 => "#FFE4C4",
			"Navajo White" 		 => "#FFDEAD",
			"Wheat" 			 => "#F5DEB3",
			"Burly Wood" 		 => "#DEB887",
			"Tan" 				 => "#D2B48C",
			"Rosy Brown" 		 => "#BC8F8F",
			"Sandy Brown" 		 => "#F4A460",
			"Goldenrod" 		 => "#DAA520",
			"Dark Goldenrod" 	 => "#B8860B",
			"Peru" 				 => "#CD853F",
			"Chocolate" 		 => "#D2691E",
			"Saddle Brown" 		 => "#8B4513",
			"Sienna" 			 => "#A0522D",
			"Brown" 			 => "#A52A2A",
			"Maroon" 			 => "#800000",
					
//			"*TITLE09*"			 => "Whites",
			"White" 			 => "#FFFFFF",
			"Snow" 				 => "#FFFAFA",
			"Honeydew" 			 => "#F0FFF0",
			"Mint Cream" 		 => "#F5FFFA",
			"Azure" 			 => "#F0FFFF",
			"Alice Blue" 		 => "#F0F8FF",
			"Ghost White" 		 => "#F8F8FF",
			"White Smoke" 		 => "#F5F5F5",
			"Seashell"			 => "#FFF5EE",
			"Beige"				 => "#F5F5DC",
			"Old Lace"			 => "#FDF5E6",
			"Floral White"		 => "#FFFAF0",
			"Ivory"				 => "#FFFFF0",
			"Antique White"		 => "#FAEBD7",
			"Linen"				 => "#FAF0E6",
			"Lavender Blush"	 => "#FFF0F5",
			"Misty Rose"		 => "#FFE4E1",
			
//			"*TITLE10*"			 => "Greys",
			"Gainsboro"			 => "#DCDCDC",
			"Light Grey"		 => "#D3D3D3",
			"Silver"			 => "#C0C0C0",
			"Dark Gray"			 => "#A9A9A9",
			"Gray"				 => "#808080",
			"Dim Gray"			 => "#696969",
			"Light Slate Gray"	 => "#778899",
			"Slate Gray"		 => "#708090",
			"Dark Slate Gray"	 => "#2F4F4F",
			"Black"				 => "#000000"
		);
		
		$plots = "";
		$h = ($g-1);
		for($x=0;$x<$g;$x++) {
			$F = $FoRs[$x];
?>

			var aline<?php echo $x; ?>=[
				['1999', <?php echo $snip[$F][1999]; ?>], 
				['2000', <?php echo $snip[$F][2000]; ?>], 
				['2001', <?php echo $snip[$F][2001]; ?>], 
				['2002', <?php echo $snip[$F][2002]; ?>],
      			['2003', <?php echo $snip[$F][2003]; ?>], 
				['2004', <?php echo $snip[$F][2004]; ?>], 
				['2005', <?php echo $snip[$F][2005]; ?>], 
				['2006', <?php echo $snip[$F][2006]; ?>],
      			['2007', <?php echo $snip[$F][2007]; ?>], 
				['2008', <?php echo $snip[$F][2008]; ?>], 
				['2009', <?php echo $snip[$F][2009]; ?>], 
				['2010', <?php echo $snip[$F][2010]; ?>],
				['2011', <?php echo $snip[$F][2011]; ?>], 
				['2012', <?php echo $snip[$F][2012]; ?>],
				['2013', <?php echo $snip[$F][2013]; ?>],
				['2014', <?php echo $snip[$F][2014]; ?>],
				['2015', <?php echo $snip[$F][2015]; ?>],
				['2016', <?php echo $snip[$F][2016]; ?>],
				['2017', <?php echo $snip[$F][2017]; ?>]
			];

<?php
			$plots .= "aline".$x;
			if(($x != $h)) { $plots .= ","; }
		}
?>

			var aplot1 = $.jqplot('chartdivB', [<?php echo $plots; ?>], {
      			title:'Average source normalised impact per paper (SNIP) for each 4-digit field of research (FoR) *<br />A field with journals having high SNIPs will have a high world standard.<br />&nbsp;',
				 axes:{
        			xaxis:{
          				renderer:$.jqplot.DateAxisRenderer,
          				tickOptions:{
            				formatString:'%Y'
          				},
						min:'1998', 
						max:'2018',
          				tickInterval:'1 year',
						label:'Years',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer
        			},
        			yaxis:{
						renderer: $.jqplot.CategoryAxisRenderer,
          				tickOptions:{
            				formatString:'%.3f'
            			},
						min:0,
						tickInterval:'0.1',
						label:'Source Normalised Impact per Paper',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer
        			}
      			},
				series: 
				[

<?php
		for($x=0;$x<$g;$x++) {
			$F = $FoRs[$x];
			$z = ($x * 3);
?>

            		{
						label:'&nbsp; <?php echo $F; ?> &nbsp;',
						color: '<?php echo $colors[$z] ?>',
						rendererOptions: {
                    		smooth: true,
                		},
						markerOptions: { 
							style:"filledCircle",
							color: '<?php echo $colors[$z] ?>',
							size: 10
						}
					}<?php if(($x != $h)) { echo ","; } ?>
<?php
		}
?>
        		],
				legend: {
            		show: true,
            		placement: 'outsideGrid'
        		},
      			highlighter: {
        			show: true,
        			sizeAdjust: 10
      			},
      			cursor: {
        			show: false
      			}
  			});
	
    </script> 
<?php

	} else {
		if(($for4 == "") && ($gotFoRs == "y")) {
?>
    <script language="javascript" type="text/javascript">
	
		var ensore = $("#chartdivB").remove();
		
    </script> 
<?php

		}
	}

/////////////////////////////////////////////////////////// Gather Chart Data for Individual 4-digit FoR

	if(($for4 != "") && ($doFoR2 != "y")) {
		$mainFOR = $for4; 
		$query = "SELECT `2010`, `2010w` FROM 2012_citation_benchmarks WHERE ForCode = \"$mainFOR\" ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			$citationBenchmark = "$row[0]";
			$citationBenchmarkw = "$row[1]";
		}
	}
	
	if(($for4 != "") && ($doFoR2 != "y")) {
		for($x=1999;$x<2018; $x++) {
			$snip[$x] = "0";
			$query = "SELECT (SUM(".$x."_SNIP) / COUNT(".$x."_SNIP)) AS AverageSnip ";
			$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$for4\" OR FoR2 = \"$for4\" OR FoR3 = \"$for4\") ";
			$query .= "AND ".$x."_SNIP != \"\" AND ".$x."_SNIP IS NOT NULL ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				$snip[$x] = $row[0];
				$snip[$x] = number_format($snip[$x],3);
			}
		}
	}

/////////////////////////////////////////////////////////// Display Chart for Individual 4-digit FoR

	if(($for4 != "") && ($snip[2014] != "0") && ($doFoR2 != "y")) {
	
?>
    <script language="javascript" type="text/javascript">
		
			var ensorL = $("#chartdivB").empty();
			
			var aline1=[
				['1999', <?php echo $snip[1999]; ?>], 
				['2000', <?php echo $snip[2000]; ?>], 
				['2001', <?php echo $snip[2001]; ?>], 
				['2002', <?php echo $snip[2002]; ?>],
      			['2003', <?php echo $snip[2003]; ?>], 
				['2004', <?php echo $snip[2004]; ?>], 
				['2005', <?php echo $snip[2005]; ?>], 
				['2006', <?php echo $snip[2006]; ?>],
      			['2007', <?php echo $snip[2007]; ?>], 
				['2008', <?php echo $snip[2008]; ?>], 
				['2009', <?php echo $snip[2009]; ?>], 
				['2010', <?php echo $snip[2010]; ?>],
				['2011', <?php echo $snip[2011]; ?>], 
				['2012', <?php echo $snip[2012]; ?>],
				['2013', <?php echo $snip[2013]; ?>],
				['2014', <?php echo $snip[2014]; ?>],
				['2015', <?php echo $snip[2015]; ?>],
				['2016', <?php echo $snip[2016]; ?>],
				['2017', <?php echo $snip[2017]; ?>]
			];
			
			var aplot1 = $.jqplot('chartdivB', [aline1], {
      			title:'Average source normalised impact per paper (SNIP) within a 4-digit field of research (FoR) *<br />A field with journals having high SNIPs will have a high world standard.<br />&nbsp;',
				 axes:{
        			xaxis:{
          				renderer:$.jqplot.DateAxisRenderer,
          				tickOptions:{
            				formatString:'%Y'
          				},
						min:'1998', 
						max:'2018',
          				tickInterval:'1 year',
						label:'Years',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer
        			},
        			yaxis:{
						renderer: $.jqplot.CategoryAxisRenderer,
          				tickOptions:{
            				formatString:'%.3f'
            			},
						min:-1,
						tickInterval:'0.1',
						label:'Source Normalised Impact per Paper',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer
        			}
      			},
				series: 
				[
            		{
						label:'&nbsp; <?php echo $for4; ?> &nbsp;',
						color: 'rgba(198,88,88,.6)',
						rendererOptions: {
                    		smooth: true,
                		},
						markerOptions: { 
							style:"filledCircle",
							color: 'rgba(198,88,88,.6)',
							size: 10
						}
					}
        		],
				legend: {
            		show: true,
            		placement: 'outsideGrid'
        		},
      			highlighter: {
        			show: true,
        			sizeAdjust: 10
      			},
      			cursor: {
        			show: false
      			}
  			});
		
    </script> 
<?php

	} else {
		if(($doFoR2 != "y")) {

?>
    <script language="javascript" type="text/javascript">
	
		var ensore = $("#chartdivB").remove();
		
    </script> 
<?php

		}
	}

/////////////////////////////////////////////////////////// Footer

	include("./admin/era.dbdisconnect.php");	
	
?>
