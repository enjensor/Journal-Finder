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
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	require_once('./classes/tcpdf/config/tcpdf_config.php');
	require_once('./classes/tcpdf/tcpdf.php');
	require_once('./classes/jpgraph/jpgraph.php');
	require_once('./classes/jpgraph/jpgraph_line.php');
	
/////////////////////////////////////////////////////////// Vars
	
	$mainName = "";
	$pub = "";
	$country = "";
	$data_page = "";
	$jTitle = "";
	$eraid = $_GET['eraid'];
	$ISSNa = $_GET['ISSNa'];
	$for2 = $_GET['for2'];
	$for4 = $_GET['for4'];
	$mainFOR = $_GET['mainFOR'];
	$snip = array();
	$snipA = array();
	$sjr = array();
	$len_FoR = strlen($mainFOR);
	
/////////////////////////////////////////////////////////// Get main FoR name

	if(($len_FoR < 4)) {
	
		$query = "SELECT FoRName2 FROM forname2 WHERE FoRCode = \"$mainFOR\" ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			$mainName = "$row[0]";
		}
	}
	
	if(($len_FoR > 2)) {
			 
		$query = "SELECT FoRName4 FROM forname4 WHERE FoRCode = \"$mainFOR\" ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) {
			$mainName = "$row[0]";
		}
	}
	
/////////////////////////////////////////////////////////// Get SNIP data
	
	$query = "SELECT * FROM 2017_journals_final_list WHERE ERAID = \"$eraid\" ";
	$mysqli_result = mysqli_query($mysqli_link, $query);
	while($row = mysqli_fetch_row($mysqli_result)) { 
	
		$ISSNb = $row[10];
		$isbn0 = $row[10];
		
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
		
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
		
			$ISSNa = $rowD[3];
			for($j=8;$j<53;$j++) {
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
			
			$pub = $rowD[5];
			$country = $rowD[7];
		}
	}
	
/////////////////////////////////////////////////////////// Get average annual SNIP data
		
	for($x=1999;$x<2014; $x++) {
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
	
/////////////////////////////////////////////////////////// Setup PDF page
	
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Journal Finder');
	$pdf->SetTitle('Journal Finder');
	$pdf->SetSubject('Journal Finder');
	$pdf->SetKeywords('Journal Finder');
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}
	$pdf->SetFont('dejavusans', '', 10, '', true);
	$pdf->AddPage();

/////////////////////////////////////////////////////////// Generate image

	$ydata3  = array($sjr[1999], $sjr[2000], $sjr[2001], $sjr[2002], $sjr[2003], $sjr[2004], $sjr[2005], $sjr[2006], $sjr[2007], $sjr[2008], $sjr[2009], $sjr[2010], $sjr[2011], $sjr[2012], $sjr[2013]);
	$ydata2  = array($snip[1999], $snip[2000], $snip[2001], $snip[2002], $snip[2003], $snip[2004], $snip[2005], $snip[2006], $snip[2007], $snip[2008], $snip[2009], $snip[2010], $snip[2011], $snip[2012], $snip[2013]);
	$ydata1  = array($snipA[1999], $snipA[2000], $snipA[2001], $snipA[2002], $snipA[2003], $snipA[2004], $snipA[2005], $snipA[2006], $snipA[2007], $snipA[2008], $snipA[2009], $snipA[2010], $snipA[2011], $snipA[2012], $snipA[2013]);

	$graph = new Graph(475,300,'auto');
	$graph->SetScale("textlin");
	$graph->SetShadow();
	$graph->img->SetImgFormat('png');
	$graph->img->SetQuality(100);
	$graph->SetMargin(50,10,10,50);
	$graph->title->Set('Elsevier & Scopus Source Normalised Impact per Paper (SNIP)');
	$graph->subtitle->Set('Journal Performance in Field of Research '.$mainFOR);
	$graph->subsubtitle->Set($mainName);
	$graph->title->SetColor('black:1.0');
	$graph->subtitle->SetColor('black:1.0');
	$graph->subsubtitle->SetColor('black:1.0');
	$graph->yaxis->title->Set('Impact Factor');
	$graph->yaxis->title->SetColor('black:1.0');
	$graph->yaxis->SetColor('black:1.0');
	$graph->xaxis->SetColor('black:1.0');
	$graph->yaxis->SetTitleMargin(35);
	$graph->yaxis->scale->SetGrace(5,0);
	$graph->xaxis->SetTickLabels(array('1999','2000','2001','2002','2003','2004','2005','2006','2007','2008','2009','2010','2011','2012','2013'));
	$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,7);
	$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,7);
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(true,false);
	$graph->xaxis->HideLine(false);
	$graph->xaxis->HideTicks(false,false);
	
	$lineplot1=new LinePlot($ydata1);
	$lineplot2=new LinePlot($ydata2);
	$lineplot3=new LinePlot($ydata3);
	
	$graph->Add($lineplot1);
	$graph->Add($lineplot2);
	$graph->Add($lineplot3);
	
	$lineplot1->SetWeight(5);
	$lineplot2->SetWeight(5);
	$lineplot3->SetWeight(5);
	$lineplot1->SetLegend("Avg. Annual SNIP in FoR");
	$lineplot2->SetLegend("Avg. Annual SNIP in Journal");
	$lineplot3->SetLegend("SCImago Journal Rank");
	
	$lineplot1->SetColor("#000000");
	$lineplot2->SetColor("#990000");
	$lineplot3->SetColor("#009900");
	
	$graph->legend->SetFrameWeight(1);
	$graph->legend->SetColumns(3);
	$graph->legend->SetColor('#000000','#ffffff');
	$graph->legend->SetFont(FF_ARIAL,FS_NORMAL,8);


/////////////////////////////////////////////////////////// Append image to PDF

	$time = time();
	$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
	$fileName = "./tmp/plot_".$time.".png";
	$graph->img->Stream($fileName);
	$pdf->Image($fileName, 15, 15, 180, 100, 'PNG', '', '', true, 72, '', false, false, 0, false, false, false);
	unlink($fileName);
	$pdf->Ln(100);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Start cache

	ob_start();
 
/////////////////////////////////////////////////////////// Get journal data

	$query = "SELECT * FROM 2017_journals_final_list WHERE ERAID = \"$eraid\" ";
	$mysqli_result = mysqli_query($mysqli_link, $query);
	while($row = mysqli_fetch_row($mysqli_result)) { 
	
		$jTitle = $row[2];
		$pub = "";
		$country = "";
		$ISSNb = $row[10];
		$isbn0 = $row[10];
		$apaisC = "";
		$apais = "No";
		$erih = "No";
		$erihD = "";
		
/////////////////////////////////////////////////////////// Get primary ISSN
		
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
		
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$ISSNa = $rowD[3];
			$pub = $rowD[5];
			$country = $rowD[7];
		}
		
/////////////////////////////////////////////////////////// Get APAIS data
		
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
		
/////////////////////////////////////////////////////////// Complete journal data
		
	}
	
/////////////////////////////////////////////////////////// Get SHERPA data
	
	$query = "SELECT * FROM data_sherpa WHERE ISSN = \"$ISSNa\" ";
	$mysqli_result = mysqli_query($mysqli_link, $query);
	while($row = mysqli_fetch_row($mysqli_result)) {
		$data_page = $row[2];
		$fSherpa = "y";
	}

/////////////////////////////////////////////////////////// Display journal information
		
	echo "<h3><em>$jTitle</em></h3>";
	if(($pub != "")) { echo "<strong>Publisher</strong> $pub</strong><br /><strong>Country</strong> $country<br />"; }
	echo "<strong>ISSN(S)</strong> $isbn0 $isbn1 $isbn2 $isbn3 $isbn4 $isbn5 $isbn6";	
	$data_page = html_entity_decode($data_page,ENT_QUOTES,"UTF-8");	
	echo $data_page;
	
/////////////////////////////////////////////////////////// Display APAIS and ERIH details
	
	echo "<div id=\"apaisDisplay\">";
	echo "<p style=\"padding-left:10px;\"><strong>Indexed by Australian Public Affairs Information Service</strong></p>";
	echo "<p style=\"padding-left:10px;\">";
	echo "<ul>";
	echo "<li>$apais";
	if(($apais == "Yes")) { echo " ($apaisC)"; }
	echo "</li>";
	echo "</ul>";
	echo "</p>";
	echo "<p style=\"padding-left:10px;\"><strong>Indexed by European Reference Index for the Humanities</strong></p>";
	echo "<p style=\"padding-left:10px;\">";
	echo "<ul>";
	echo "<li>$erih";
	if(($erih == "Yes")) { echo " ($erihD)"; }
	echo "</li>";
	echo "</ul>";
	echo "</p>";
	echo "</div>";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Flush Cache

	$output = ob_get_contents();
	ob_end_clean();
	
/////////////////////////////////////////////////////////// Print PDF

	$pdf->writeHTMLCell(0, 0, '', '', $output, 0, 1, 0, true, '', true);
    $pdf->IncludeJS($javascript);
	$pdf->Output('Journal_Finder_'.$ISSNa.'.pdf', 'I');
	
/////////////////////////////////////////////////////////// Footer

	include("./admin/era.dbdisconnect.php");

?>