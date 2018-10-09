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
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	
/////////////////////////////////////////////////////////// Vars
	
	$doARC = "n";
	$eraid = $_GET['eraid'];
	$fsnip = $_GET['fsnip'];
	$AmeanSnip = $_GET['AmeanSnip'];
	$for2 = $_GET['for2'];
	$for4 = $_GET['for4'];
	if(($for2 != "")) { 
		$mainFOR = $for2; 
		$citationBenchmark = "0";
		$citationBenchmarkw = "0";
	} else { 
		if(($for4 != "")) {
			$mainFOR = $for4; 
			$query = "SELECT `2010`, `2010w` FROM 2012_citation_benchmarks WHERE ForCode = \"$mainFOR\" ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				$citationBenchmark = "$row[0]";
				$citationBenchmarkw = "$row[1]";
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
		
		$apaisC = "";
		$apais = "No";
		$erih = "No";
		$erihD = "";
		$eraidF = "y";
		$pub = "";
		$country = "";
		$ISSNb = $row[10];
		
		if(($fsnip == "y")) {
			$queryD = "SELECT * FROM data_snip WHERE Print_ISSN = \"$row[10]\" ";
			if(($row[11] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[11]\" ";
			}
			if(($row[12] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[12]\" ";
			}
			if(($row[13] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[13]\" ";
			}
			if(($row[14] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[14]\" ";
			}
			if(($row[15] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[15]\" ";
			}
			if(($row[16] != "")) {
				$queryD = $queryD."OR Print_ISSN = \"$row[16]\" "; 
			}
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			
				$ISSNa = $rowD[3];
			
				for($j=8;$j<36;$j++) {
					if(($rowD[$j] == "")) {
						$rowD[$j] = "0";
					}
				}
			
				$snip[1999] = $rowD[8];
				$snip[2000] = $rowD[10];
				$snip[2001] = $rowD[12];
				$snip[2002] = $rowD[14];
				$snip[2003] = $rowD[16];
				$snip[2004] = $rowD[18];
				$snip[2005] = $rowD[20];
				$snip[2006] = $rowD[22];
				$snip[2007] = $rowD[24];
				$snip[2008] = $rowD[26];
				$snip[2009] = $rowD[28];
				$snip[2010] = $rowD[30];
				$snip[2011] = $rowD[32];
				$snip[2012] = $rowD[34];
				
				$sjr[1999] = $rowD[9];
				$sjr[2000] = $rowD[11];
				$sjr[2001] = $rowD[13];
				$sjr[2002] = $rowD[15];
				$sjr[2003] = $rowD[17];
				$sjr[2004] = $rowD[19];
				$sjr[2005] = $rowD[21];
				$sjr[2006] = $rowD[23];
				$sjr[2007] = $rowD[25];
				$sjr[2008] = $rowD[27];
				$sjr[2009] = $rowD[29];
				$sjr[2010] = $rowD[31];
				$sjr[2011] = $rowD[33];
				$sjr[2012] = $rowD[35];
				
				$pub = $rowD[5];
				$country = $rowD[7];
			}
		}
		
/////////////////////////////////////////////////////////// Get average annual SNIP data
		
		if(($doARC != "y")) {
			for($x=1999;$x<2013; $x++) {
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
		
		echo "<div class=\"modal-header\"><button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>";
		echo "<h3>$row[2]</h3>";
		if(($pub != "")) {
			echo "<strong>Publisher</strong> $pub | <strong>Country</strong> $country | ";
		}
		echo "<strong>ISSN(S)</strong> $row[10] $row[11] $row[12] $row[13] $row[14] $row[15] $row[16]";
		echo "</div>\n";
		echo "<div class=\"modal-body\">";
		echo "<div id=\"chartdiv\" style=\"position: relative; height:500px; width:60%; padding:0px; border: 0px solid #aaaaaa; float: left;\"></div>";
		echo "<div id=\"sherpadiv\" style=\"position: relative; height:500px; width: 38%; padding:0px; ";
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
		} else {
			echo "<button id=\"eraidSave\" class=\"btn btn-success\" disabled=\"disabled\">Save for Comparison</button>";
			echo "<button id=\"eraidRemove\" class=\"btn btn-danger\">Remove</button>";
		}
		echo "</div>";
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// End content layout
		
	}
	if(($eraidF == "y") && ($fsnip == "y")) {
?>
    <script language="javascript" type="text/javascript">

  		$("#eraidSave").click(function(event) {
			event.preventDefault();
			var eraidsave = $.post("./arc_journals_saved.php",{'eraidSave': '<?php echo $eraid; ?>', 'doAction': 'SAVE'}).done(function(){
				var eraiddisable = $("#eraidSave").attr("disabled","disabled");
				var eraidremove = $("#eraidRemove").removeAttr("disabled");
			});
		});
		
  		$("#eraidRemove").click(function(event) {
			event.preventDefault();
			var eraidsave = $.post("./arc_journals_saved.php",{'eraidSave': '<?php echo $eraid; ?>', 'doAction': 'REMOVE'}).done(function(){
				var eraidremove = $("#eraidSave").removeAttr("disabled");
				var eraiddisable = $("#eraidRemove").attr("disabled","disabled");
			});
		});

		$('.modal').off('show.bs.modal').on('show.bs.modal', function () {
			
			var ensora = $("#chartdiv").empty();	
			var ensorb = $("#sherpadiv").empty();
			var ensore = $("#chartdiv").css('width','60%');
			var ensorf = $("#sherpadiv").css('width','38%');
 			var ensorc = $(this).find('.modal-dialog').css({'width':'90%', 'height':'600px', 'max-height':'80%'});

		});		
			
		$('.modal').off('shown.bs.modal').on('shown.bs.modal', function (e) {
			
			var ensora = $("#chartdiv").empty();	
			var ensorb = $("#sherpadiv").empty();
			var ensore = $("#chartdiv").css('width','60%');
			var ensorf = $("#sherpadiv").css('width','38%');
    		var ensorc = $(this).find('.modal-dialog').css({'width':'90%', 'height':'600px', 'max-height':'80%'});
			
			var target = document.getElementById('sherpadiv'); 
			var spinner = new Spinner().spin(target);
			
			var ensord = $.get("arc_sherpa_xml.php?ISSNa=<?php echo $ISSNa; ?>", function(datas) {
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
				['2012', <?php echo $snip[2012]; ?>]
			];
			
			var line2=[
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
				['2012', <?php echo $sjr[2012]; ?>]
			];

			var line3 = [
				['1999', <?php echo $AmeanSnip; ?>], 
				['2000', <?php echo $AmeanSnip; ?>], 
				['2001', <?php echo $AmeanSnip; ?>], 
				['2002', <?php echo $AmeanSnip; ?>],
      			['2003', <?php echo $AmeanSnip; ?>], 
				['2004', <?php echo $AmeanSnip; ?>], 
				['2005', <?php echo $AmeanSnip; ?>], 
				['2006', <?php echo $AmeanSnip; ?>],
      			['2007', <?php echo $AmeanSnip; ?>], 
				['2008', <?php echo $AmeanSnip; ?>], 
				['2009', <?php echo $AmeanSnip; ?>], 
				['2010', <?php echo $AmeanSnip; ?>],
				['2011', <?php echo $AmeanSnip; ?>], 
				['2012', <?php echo $AmeanSnip; ?>]
			];
			
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ARC benchmarks or average SNIP selector begin

			if(($doARC == "y")) {
?>			
			
			var line4 = [
				['1999', <?php echo $citationBenchmark; ?>], 
				['2000', <?php echo $citationBenchmark; ?>], 
				['2001', <?php echo $citationBenchmark; ?>], 
				['2002', <?php echo $citationBenchmark; ?>],
      			['2003', <?php echo $citationBenchmark; ?>], 
				['2004', <?php echo $citationBenchmark; ?>], 
				['2005', <?php echo $citationBenchmark; ?>], 
				['2006', <?php echo $citationBenchmark; ?>],
      			['2007', <?php echo $citationBenchmark; ?>], 
				['2008', <?php echo $citationBenchmark; ?>], 
				['2009', <?php echo $citationBenchmark; ?>], 
				['2010', <?php echo $citationBenchmark; ?>],
				['2011', <?php echo $citationBenchmark; ?>], 
				['2012', <?php echo $citationBenchmark; ?>]
			];
			
			var line5 = [
				['1999', <?php echo $citationBenchmarkw; ?>], 
				['2000', <?php echo $citationBenchmarkw; ?>], 
				['2001', <?php echo $citationBenchmarkw; ?>], 
				['2002', <?php echo $citationBenchmarkw; ?>],
      			['2003', <?php echo $citationBenchmarkw; ?>], 
				['2004', <?php echo $citationBenchmarkw; ?>], 
				['2005', <?php echo $citationBenchmarkw; ?>], 
				['2006', <?php echo $citationBenchmarkw; ?>],
      			['2007', <?php echo $citationBenchmarkw; ?>], 
				['2008', <?php echo $citationBenchmarkw; ?>], 
				['2009', <?php echo $citationBenchmarkw; ?>], 
				['2010', <?php echo $citationBenchmarkw; ?>],
				['2011', <?php echo $citationBenchmarkw; ?>], 
				['2012', <?php echo $citationBenchmarkw; ?>]
			];
  			
<?php
			} else {
?>			
			
			var line4 = [
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
				['2012', <?php echo $snipA[2012]; ?>]
			];
  			
<?php			
			}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ARC benchmarks or average SNIP selector finish

			if(($doARC == "y")) {
?>			
			
			var plot1 = $.jqplot('chartdiv', [line1,line2,line3,line4,line5], {
<?php
			} else {
?>
			var plot1 = $.jqplot('chartdiv', [line1,line2,line4], {
<?php
			}
?>
      			title:'Elsevier &amp; Scopus Journal Metrics',
				 axes:{
        			xaxis:{
          				renderer:$.jqplot.DateAxisRenderer,
          				tickOptions:{
            				formatString:'%Y'
          				},
						min:'1998', 
						max:'2013',
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
						color: 'rgba(198,88,88,.6)',
						rendererOptions: {
                    		smooth: true,
                		},
						markerOptions: { 
							style:"filledCircle",
							color: 'rgba(198,88,88,.6)',
							lineWidth: 2,
							size: 7
						}
					},
            		{
						label:'&nbsp;SCImago Journal Rank (SJR)&nbsp;',
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
<?php
			if(($doARC == "y")) {					
?>	
            		{
						label:'&nbsp;Mean 2012 SNIP in Field of Research <?php echo $mainFOR; ?>&nbsp;',
						color: 'rgba(255, 128, 0, 0.7)',
						rendererOptions: {
                    		smooth: true,
                		},
						markerOptions: { 
							style:"filledCircle",
							color: 'rgba(255, 128, 0, 0.7)',
							lineWidth: 2,
							size: 7
						}
					},								
            		{
						label:'&nbsp;Australian (2012) Citation Benchmark in FoR <?php echo $mainFOR; ?>&nbsp;',
						color: 'rgba(0, 76, 153, 0.7)',
						rendererOptions: {
                    		smooth: true,
                		},
						markerOptions: { 
							style:"filledCircle",
							color: 'rgba(0, 76, 153, 0.7)',
							lineWidth: 2,
							size: 7
						}
					},
            		{
						label:'&nbsp;World (2012) Citation Benchmark in FoR <?php echo $mainFOR; ?>&nbsp;',
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
					}
<?php
			} else {					
?>
            		{
						label:'&nbsp;Average Annual SNIP in FoR <?php echo $mainFOR; ?>&nbsp;',
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
					}
<?php
			}
?>
        		],
				legend: {
            		show: true,
            		placement: 'insideGrid'
        		},
      			highlighter: {
        			show: true,
        			sizeAdjust: 10
      			},
      			cursor: {
        			show: false
      			}
  			});
		});	
	
    </script>  
<?php	
	} else {
?>
    <script language="javascript" type="text/javascript">
  
  		$("#eraidSave").click(function(event) {
			event.preventDefault();
			var eraiddisable = $("#eraidSave").attr("disabled","disabled");
			var eraidsave = $.post('./arc_journals_saved.php', {'eraidSave': $eraid});
		});

		$('.modal').off('show.bs.modal').on('show.bs.modal', function () {
			
			var ensora = $("#chartdiv").empty();	
			var ensorb = $("#sherpadiv").empty();
			var ensore = $("#chartdiv").remove();
			var ensorf = $("#sherpadiv").css('width','98%');
    		var ensorc = $(this).find('.modal-dialog').css({'width':'45%', 'height':'600px', 'max-height':'80%'});

		});	
	
		$('.modal').off('shown.bs.modal').on('shown.bs.modal', function () {
		
			var ensora = $("#chartdiv").empty();	
			var ensorb = $("#sherpadiv").empty();
			var ensore = $("#chartdiv").remove();
			var ensorf = $("#sherpadiv").css('width','98%');
    		var ensorc = $(this).find('.modal-dialog').css({'width':'45%', 'height':'600px', 'max-height':'80%'});
			
			var target = document.getElementById('sherpadiv'); 
			var spinner = new Spinner().spin(target);
			
			var ensord = $.get("arc_sherpa_xml.php?ISSNa=<?php echo $ISSNb; ?>&shortversion=yes", function(datas) {
				$("#sherpadiv").html(datas);
			});
			
		});	
		
    </script> 
<?php
	}

/////////////////////////////////////////////////////////// End

	include("./admin/era.dbdisconnect.php");	
	
?>