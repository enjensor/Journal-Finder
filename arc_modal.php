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
//	20 September 2018
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
	
	$doARC = "n";
	$mainName = "";
	$eraid = $_GET['eraid'];
	$fsnip = $_GET['fsnip'];
	$AmeanSnip = $_GET['AmeanSnip'];
	$AmeanSnipFourteen = $_GET['AmeanSnipFourteen'];
	$for2 = $_GET['for2'];
	$for4 = $_GET['for4'];
	if(($for2 != "")) { 
	
		$mainFOR = $for2; 
		$citationBenchmark = "0";
		$citationBenchmarkw = "0";
		
		$query = "SELECT FoRName2 FROM forname2 WHERE FoRCode = \"$mainFOR\" ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			$mainName = "$row[0]";
		}
		
	} else { 
	
		if(($for4 != "")) {
			
			$mainFOR = $for4; 
			$query = "SELECT `2010`, `2010w` FROM 2012_citation_benchmarks WHERE ForCode = \"$mainFOR\" ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				$citationBenchmark = "$row[0]";
				$citationBenchmarkw = "$row[1]";
			}
			
			$query = "SELECT FoRName4 FROM forname4 WHERE FoRCode = \"$mainFOR\" ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				$mainName = "$row[0]";
			}
			
		} else {
			
			$mainFOR = "NA";
			$citationBenchmark = "0";
			$citationBenchmarkw = "0";	
		}
	}
	
	if(($AmeanSnip == "")) { $AmeanSnip = "0"; }
	$eraidF = "";

/////////////////////////////////////////////////////////// Get graphing data

	$query = "SELECT * FROM 2017_journals_final_list WHERE ERAID = \"$eraid\" ";
	$mysqli_result = mysqli_query($mysqli_link, $query);
	while($row = mysqli_fetch_row($mysqli_result)) { 
	
		$snip = array();
		$sjr = array();
		$ipp = array();
		
		if(($mainFOR == "NA")) {
			$mainFOR = $row[4];
			$mainName = $row[5];
		}
		
		$apaisC = "";
		$apais = "No";
		$erih = "No";
		$erihD = "";
		$eraidF = "y";
		$pub = "";
		$country = "";
		$ISSNb = $row[10];
		$isbn0 = $row[10];
		$jTitle = $row[2];
		
		if(($fsnip == "y")) {
			$queryD = "SELECT * FROM 2017_data_snip_scopus WHERE Print_ISSN = \"$row[10]\" ";
			if(($row[11] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[11]\" ";
				$isbn1 = $row[11];
			}
			if(($row[12] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[12]\" ";
				$isbn2 = $row[12];
			}
			if(($row[13] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[13]\" ";
				$isbn3 = $row[13];
			}
			if(($row[14] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[14]\" ";
				$isbn4 = $row[14];
			}
			if(($row[15] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[15]\" ";
				$isbn5 = $row[15];
			}
			if(($row[16] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[16]\" "; 
				$isbn6 = $row[16];
			}
			$queryD .= "ORDER BY 2014_SNIP DESC LIMIT 1";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			
				$ISSNa = $rowD[3];
				$Scopus_Source_ID = $rowD[1];
			
				for($j=8;$j<62;$j++) {
					$rowD[$j] = preg_replace("/[^0-9\.]/","","$rowD[$j]");
					if(($rowD[$j] == "")) {
						$rowD[$j] = "0";
					}
				}
			
				$snip[1999] = $rowD[8];
				$snip[2000] = $rowD[11];
				$snip[2001] = $rowD[14];
				$snip[2002] = $rowD[17];
				$snip[2003] = $rowD[20];
				$snip[2004] = $rowD[23];
				$snip[2005] = $rowD[26];
				$snip[2006] = $rowD[29];
				$snip[2007] = $rowD[32];
				$snip[2008] = $rowD[35];
				$snip[2009] = $rowD[38];
				$snip[2010] = $rowD[41];
				$snip[2011] = $rowD[44];
				$snip[2012] = $rowD[47];
				$snip[2013] = $rowD[50];
				$snip[2014] = $rowD[53];
				$snip[2015] = $rowD[56];
				$snip[2016] = $rowD[59];
			
				$queryE = "SELECT SNIP_2015, SNIP_2016 FROM 2017_journals_final_list WHERE Source_Record_ID = \"$Scopus_Source_ID\" ";
				$mysqli_resultE = mysqli_query($mysqli_link, $queryE);		
				while($rowE = mysqli_fetch_row($mysqli_resultE)) { 
					$snip[2015] = $rowE[0];
					$snip[2016] = $rowE[1];
				}
				
				$ipp[1999] = $rowD[9];
				$ipp[2000] = $rowD[12];
				$ipp[2001] = $rowD[15];
				$ipp[2002] = $rowD[18];
				$ipp[2003] = $rowD[21];
				$ipp[2004] = $rowD[24];
				$ipp[2005] = $rowD[27];
				$ipp[2006] = $rowD[30];
				$ipp[2007] = $rowD[33];
				$ipp[2008] = $rowD[36];
				$ipp[2009] = $rowD[39];
				$ipp[2010] = $rowD[42];
				$ipp[2011] = $rowD[45];
				$ipp[2012] = $rowD[48];
				$ipp[2013] = $rowD[51];
				$ipp[2014] = $rowD[54];
				$ipp[2015] = $rowD[57];
				$ipp[2016] = $rowD[60];
				
				$sjr[1999] = $rowD[10];
				$sjr[2000] = $rowD[13];
				$sjr[2001] = $rowD[16];
				$sjr[2002] = $rowD[19];
				$sjr[2003] = $rowD[22];
				$sjr[2004] = $rowD[25];
				$sjr[2005] = $rowD[28];
				$sjr[2006] = $rowD[31];
				$sjr[2007] = $rowD[34];
				$sjr[2008] = $rowD[37];
				$sjr[2009] = $rowD[40];
				$sjr[2010] = $rowD[43];
				$sjr[2011] = $rowD[46];
				$sjr[2012] = $rowD[49];
				$sjr[2013] = $rowD[52];
				$sjr[2014] = $rowD[55];
				$sjr[2015] = $rowD[58];
				$sjr[2016] = $rowD[61];
					
				$pub = $rowD[5];
				$country = $rowD[7];
			}
		} else {
			$pub = "Not Specified";
			$country = "Not Specified";
		}
		
/////////////////////////////////////////////////////////// Get average annual SNIP data
		
		if(($doARC != "y")) {
			for($x=1999;$x<2017; $x++) {
				$snipA[$x] = "0";
				$query = "SELECT (SUM(".$x."_SNIP) / COUNT(".$x."_SNIP)) AS AverageSnip ";
				$query .= "FROM 2017_journals_snips WHERE (FoR1 = \"$mainFOR\" OR FoR2 = \"$mainFOR\" OR FoR3 = \"$mainFOR\") ";
				$query .= "AND ".$x."_SNIP != \"\" AND ".$x."_SNIP IS NOT NULL ";
				$mysqli_result = mysqli_query($mysqli_link, $query);
				while($row = mysqli_fetch_row($mysqli_result)) {
					$snipA[$x] = $row[0];
					$snipA[$x] = number_format($snipA[$x],3);
				}
			}
		}
		
/////////////////////////////////////////////////////////// Get APAIS data
		
		if(($doAPAIS_ERIH == "y")) {
			$queryD = "SELECT Coverage FROM data_apais WHERE ISSN = \"$row[10]\" ";
			if(($row[11] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[11]\" ";
			}
			if(($row[12] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[12]\" ";
			}
			if(($row[13] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[13]\" ";
			}
			if(($row[14] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[14]\" ";
			}
			if(($row[15] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[15]\" ";
			}
			if(($row[16] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[16]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				$apais = "Yes";
				$apaisC = $rowD[0];
			}
		
/////////////////////////////////////////////////////////// Get ERIH data

			$queryD = "SELECT Discipline, Category_2011 FROM data_erih WHERE ( ISSN = \"$row[10]\" ";
			if(($row[11] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[11]\" ";
			}
			if(($row[12] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[12]\" ";
			}
			if(($row[13] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[13]\" ";
			}
			if(($row[14] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[14]\" ";
			}
			if(($row[15] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[15]\" ";
			}
			if(($row[16] != "")) {
				$queryD = $queryD."OR ISSN = \"$row[16]\" "; 
			}
			$queryD = $queryD.") AND Category_2011 != \"\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				$erihD = $rowD[0]; 
				$erih = "Yes";
			}
		}
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Display content
		
		echo "<div class=\"modal-header\" style=\"text-align: center;\">";
		echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>";
		echo "<strong>Publisher</strong> $pub | ";
		echo "<strong>Country</strong> $country | ";
		echo "<strong>ISSN(S)</strong> $isbn0 $isbn1 $isbn2 $isbn3 $isbn4 $isbn5 $isbn6";
		echo "</div>\n";
		echo "<div class=\"modal-body\">";
		echo "<div id=\"chartdiv\" style=\"position: relative; ";
		echo "max-height:80%!important; height:650px; width:60%; padding:0px; border: 0px solid #aaaaaa; float: left;\"></div>";
		echo "<div id=\"sherpadiv\" style=\"position: relative; max-height:80%!important; height:650px; width: 38%; padding:0px; ";
		echo "padding-bottom: 25px; padding-top: 4px; padding-right: 20px;border: 0px solid #aaaaaa; float: right; overflow-y: scroll;\">";		
		echo "</div>";
		echo "</div>\n";
		echo "<div class=\"modal-footer\" style=\"clear: both;\">";
		if(($doAPAIS_ERIH == "y")) {
			echo "<strong>Indexed by APAIS</strong> $apais";
			if(($apais == "Yes")) { echo " ($apaisC)"; }
			echo " | ";
			echo "<strong>European Reference Index for the Humanities</strong> $erih";
			if(($erih == "Yes")) { echo " ($erihD)"; }
		}
		if(($_SESSION["ERAIDS"]["$eraid"] == "")) {
			echo "<button id=\"eraidSave\" class=\"btn btn-success\">Save for Comparison</button>";
			echo "<button id=\"eraidRemove\" class=\"btn btn-danger\" disabled=\"disabled\">Remove</button>";
			if(($for4 != "")) {
				echo " <a href=\"./arc_modal_download.php?eraid=$eraid&mainFOR=$for4\" target=\"_PDFview\">";
			} else  {
				echo " <a href=\"./arc_modal_download.php?eraid=$eraid&mainFOR=$for2\" target=\"_PDFview\">";
			}
			echo "<button id=\"eraidPrint\" class=\"btn btn-primary\">Download As PDF</button></a>";
		} else {
			echo "<button id=\"eraidSave\" class=\"btn btn-success\" disabled=\"disabled\">Save for Comparison</button>";
			echo "<button id=\"eraidRemove\" class=\"btn btn-danger\">Remove</button>";
			echo " <a href=\"./arc_modal_download.php?eraid=$eraid&mainFOR=$mainFOR\" target=\"_PDFview\">";
			echo "<button id=\"eraidPrint\" class=\"btn btn-primary\">Download As PDF</button></a>";
		}
		echo "</div>";
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// End content layout
		
	}
	if(($eraidF == "y") && ($fsnip == "y")) {
?>
    <script language="javascript" type="text/javascript">
	
		$(document).ready(function(){
	
			$.ajaxSetup ({
				cache: false
			});
	
			$("#eraidSave").click(function(event) {
				
				event.preventDefault();
				var eraidsaver = $.post("./arc_journals_saved.php",{'eraidSave': '<?php echo $eraid; ?>', 'doAction': 'SAVE'}).done(function(){
					var eraiddisable = $("#eraidSave").attr("disabled","disabled");
					var eraidremove = $("#eraidRemove").removeAttr("disabled");
				});
				
			});
			
			$("#eraidRemove").click(function(event) {
				
				event.preventDefault();
				var eraidsaver = $.post("./arc_journals_saved.php",{'eraidSave': '<?php echo $eraid; ?>', 'doAction': 'REMOVE'}).done(function(){
					var eraidremove = $("#eraidSave").removeAttr("disabled");
					var eraiddisable = $("#eraidRemove").attr("disabled","disabled");
				});
				
			});
	
			$('.modal').off('show.bs.modal').on('show.bs.modal', function (e) {
				
				var ensora = $("#chartdiv").empty();	
				var ensorb = $("#sherpadiv").empty();
				var ensore = $("#chartdiv").css('width','60%');
				var ensorf = $("#sherpadiv").css('width','38%');
				var ensorc = $(this).find('.modal-dialog').css({'width':'1300px', 'height':'800px', 'max-height':'80%'});
	
			});		
				
			$('.modal').off('shown.bs.modal').on('shown.bs.modal', function (e) {
				
				var ensora = $("#chartdiv").empty();	
				var ensorb = $("#sherpadiv").empty();
				var ensore = $("#chartdiv").css('width','60%');
				var ensorf = $("#sherpadiv").css('width','38%');
				var ensorc = $(this).find('.modal-dialog').css({'width':'1300px', 'height':'800px', 'max-height':'80%'});
				var target = document.getElementById('sherpadiv'); 
				var spinner = new Spinner().spin(target);
				var ensord = $.get("arc_sherpa_xml.php?ISSNa=<?php echo $ISSNa; ?>&eraid=<?php echo $eraid; ?>", function(datas) {
					$("#sherpadiv").html(datas);
				});
				
				var line1=[
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
					['2016', <?php echo $snip[2016]; ?>]
				];
				
				var line2 = [
					['1999', <?php echo $snipA[1999]; ?>], 
					['2000', <?php echo $snipA[2000]; ?>], 
					['2001', <?php echo $snipA[2001]; ?>], 
					['2002', <?php echo $snipA[2002]; ?>],
					['2003', <?php echo $snipA[2003]; ?>], 
					['2004', <?php echo $snipA[2004]; ?>], 
					['2005', <?php echo $snipA[2005]; ?>], 
					['2006', <?php echo $snipA[2006]; ?>],
					['2007', <?php echo $snipA[2007]; ?>], 
					['2008', <?php echo $snipA[2008]; ?>], 
					['2009', <?php echo $snipA[2009]; ?>], 
					['2010', <?php echo $snipA[2010]; ?>],
					['2011', <?php echo $snipA[2011]; ?>], 
					['2012', <?php echo $snipA[2012]; ?>],
					['2013', <?php echo $snipA[2013]; ?>],
					['2014', <?php echo $snipA[2014]; ?>],
					['2015', <?php echo $snipA[2015]; ?>],
					['2016', <?php echo $snipA[2016]; ?>]
				];	
				
				var line3=[
					['1999', <?php echo $sjr[1999]; ?>], 
					['2000', <?php echo $sjr[2000]; ?>], 
					['2001', <?php echo $sjr[2001]; ?>], 
					['2002', <?php echo $sjr[2002]; ?>],
					['2003', <?php echo $sjr[2003]; ?>], 
					['2004', <?php echo $sjr[2004]; ?>], 
					['2005', <?php echo $sjr[2005]; ?>], 
					['2006', <?php echo $sjr[2006]; ?>],
					['2007', <?php echo $sjr[2007]; ?>], 
					['2008', <?php echo $sjr[2008]; ?>], 
					['2009', <?php echo $sjr[2009]; ?>], 
					['2010', <?php echo $sjr[2010]; ?>],
					['2011', <?php echo $sjr[2011]; ?>], 
					['2012', <?php echo $sjr[2012]; ?>],
					['2013', <?php echo $sjr[2013]; ?>],
					['2014', <?php echo $sjr[2014]; ?>],
					['2015', <?php echo $sjr[2015]; ?>],
					['2016', <?php echo $sjr[2016]; ?>]
				];		
				
				var line4=[
					['1999', <?php echo $ipp[1999]; ?>], 
					['2000', <?php echo $ipp[2000]; ?>], 
					['2001', <?php echo $ipp[2001]; ?>], 
					['2002', <?php echo $ipp[2002]; ?>],
					['2003', <?php echo $ipp[2003]; ?>], 
					['2004', <?php echo $ipp[2004]; ?>], 
					['2005', <?php echo $ipp[2005]; ?>], 
					['2006', <?php echo $ipp[2006]; ?>],
					['2007', <?php echo $ipp[2007]; ?>], 
					['2008', <?php echo $ipp[2008]; ?>], 
					['2009', <?php echo $ipp[2009]; ?>], 
					['2010', <?php echo $ipp[2010]; ?>],
					['2011', <?php echo $ipp[2011]; ?>], 
					['2012', <?php echo $ipp[2012]; ?>],
					['2013', <?php echo $ipp[2013]; ?>],
					['2014', <?php echo $ipp[2014]; ?>],
					['2015', <?php echo $ipp[2015]; ?>],
					['2016', <?php echo $ipp[2016]; ?>]
				];		
	
				setTimeout(function(){var plot1 = $.jqplot('chartdiv', [line1,line2,line3,line4], {
					title:'Elsevier &amp; Scopus Source Normalised Impact per Paper (SNIP),<br />Journal Performance in \'<?php 
						echo $mainName; 
					?> (<?php 
							echo $mainFOR; 
						?>)\'<br /><?php 
							$jTitle = addslashes($jTitle);
							echo "* ".$jTitle. " *";
						?><br />&nbsp;',
					 axes:{
						xaxis:{
							renderer:$.jqplot.DateAxisRenderer,
							tickOptions:{
								formatString:'%Y'
							},
							min:'1998', 
							max:'2017',
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
							tickInterval:'1',
							label:'Impact Factor',
							labelRenderer: $.jqplot.CanvasAxisLabelRenderer
						}
					},
					series: 
					[
						{
							label:'&nbsp;Average Annual SNIP in Journal&nbsp;',
							color: 'rgba(198,88,88,0.7)',
							rendererOptions: {
								smooth: true,
							},
							markerOptions: { 
								style:"filledCircle",
								color: 'rgba(198,88,88,0.7)',
								lineWidth: 2,
								size: 7
							}
						},
						{
							label:'&nbsp;Average Annual SNIP in Field of Research&nbsp;',
							color: 'rgba(64, 64, 64, 0.7)',
							rendererOptions: {
								smooth: true,
							},
							markerOptions: { 
								style:"filledCircle",
								color: 'rgba(64, 64, 64, 0.7)',
								lineWidth: 2,
								size: 7
							}
						},
						{
							label:'&nbsp;Annual SCImago Journal Rank (SJR)&nbsp;',
							color: 'rgba(44, 190, 160, 0.7)',
							rendererOptions: {
								smooth: true,
							},
							markerOptions: { 
								style:"filledCircle",
								color: 'rgba(44, 190, 160, 0.7)',
								lineWidth: 2,
								size: 7
							}
						},
						{
							label:'&nbsp;Average Annual Impact Per Publication (IPP)&nbsp;',
							color: 'rgba(45, 74, 190, 0.7)',
							rendererOptions: {
								smooth: true,
							},
							markerOptions: { 
								style:"filledCircle",
								color: 'rgba(45, 74, 190, 0.7)',
								lineWidth: 2,
								size: 7
							}
						}
					],
					legend: {
						show: true,
						placement: 'insideGrid'
					},
					highlighter: {
						show: true,
						sizeAdjust: 18
					},
					cursor: {
						show: false
					}
				}) }, 1250);
			});	
		});
	
    </script>  
<?php	

	} else {
	
?>
    <script language="javascript" type="text/javascript">
	
		$(document).ready(function(){
		
			$.ajaxSetup ({
				cache: false
			});
	  
			$("#eraidSave").click(function(event) {
				
				event.preventDefault();
				var eraidsaver = $.post("./arc_journals_saved.php",{'eraidSave': '<?php echo $eraid; ?>', 'doAction': 'SAVE'}).done(function(){
					var eraiddisable = $("#eraidSave").attr("disabled","disabled");
					var eraidremove = $("#eraidRemove").removeAttr("disabled");
				});
				
			});
			
			$("#eraidRemove").click(function(event) {
				
				event.preventDefault();
				var eraidsaver = $.post("./arc_journals_saved.php",{'eraidSave': '<?php echo $eraid; ?>', 'doAction': 'REMOVE'}).done(function(){
					var eraidremove = $("#eraidSave").removeAttr("disabled");
					var eraiddisable = $("#eraidRemove").attr("disabled","disabled");
				});
			});
	
			$('.modal').off('show.bs.modal').on('show.bs.modal', function () {
				
				var ensora = $("#chartdiv").empty();	
				var ensorb = $("#sherpadiv").empty();
				var ensore = $("#chartdiv").css('width','60%');
				var ensorf = $("#sherpadiv").css('width','38%');
				var ensorc = $(this).find('.modal-dialog').css({'width':'90%', 'height':'750px', 'max-height':'80%'});
	
			});	
		
			$('.modal').off('shown.bs.modal').on('shown.bs.modal', function () {
			
				var ensora = $("#chartdiv").empty();	
				var ensorb = $("#sherpadiv").empty();
				var ensore = $("#chartdiv").css('width','60%');
				var ensorf = $("#sherpadiv").css('width','38%');
				var ensorc = $(this).find('.modal-dialog').css({'width':'1300px', 'height':'800px', 'max-height':'80%'});
				var target = document.getElementById('sherpadiv'); 
				var spinner = new Spinner().spin(target);
				var ensord = $.get("arc_sherpa_xml.php?ISSNa=<?php echo $ISSNb; ?>&shortversion=yes", function(datas) {
					$("#sherpadiv").html(datas);
				});
				var ensorHtml = "<p style='text-align:center;padding:50px;'>Not enough SNIP data is avialable to visualise.</p>";
				var ensorz = $("#chartdiv").html(ensorHtml);
				
			});	
		
		});
		
    </script> 
<?php

	}

/////////////////////////////////////////////////////////// End

	include("./admin/era.dbdisconnect.php");	
	
?>