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
//  Website: http://www.jasonensor.com/
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
	if(($cluster == "") or ($cluster == "ALL")) { $clusterName = "All Clusters and Disciplines"; }
	if(($cluster == "BBS")) { $clusterName = "Biology &amp; Biotechnological Sciences"; }
	if(($cluster == "EC")) { $clusterName = "Economics &amp; Commerce"; }
	if(($cluster == "EES")) { $clusterName = "Engineering &amp; Environmental Sciences"; }
	if(($cluster == "EHS")) { $clusterName = "Education &amp; Human Society"; }
	if(($cluster == "HCA")) { $clusterName = "Humanities &amp; Creative Arts"; }
	if(($cluster == "MCS")) { $clusterName = "Mathmematical &amp; Computing Sciences"; }
	if(($cluster == "MHS")) { $clusterName = "Medical &amp; Health Sciences"; }
	if(($cluster == "PCES")) { $clusterName = "Physical, Chemical &amp; Earth Sciences"; }
		
/////////////////////////////////////////////////////////// Display content
		
?>
<!--


	Project: ERA Journal Identification Toolkit
	Project Team: Susan Robbins, Michael Gonzalez, Paul Arthur, Jason Ensor (Team Leader & Developer)
	Project Base: Western Sydney University Library and the Digital Humanities Research Group, School of Humanities and Communication Arts
	Project Methodology: Procedural Scripting PHP | MySQL | JQuery



	FOR ALL ENQUIRIES ABOUT CODE

	Who:	Dr Jason Ensor
	Email: 	j.ensor@westernsydney.edu.au | jasondensor@gmail.com
	Web: 	http://www.jasonensor.com
	Mobile:	0419 674 770



  	WEB FRAMEWORK

  	Bootstrap Twitter v3.3.5 | http://getbootstrap.com/
  	Font Awesome v4.4.0 | http://fortawesome.github.io/Font-Awesome/
  	Google Fonts API | http://fonts.googleapis.com
  	Modernizr v.2.8.3 | http://modernizr.com/
  	JQuery v.2.1.4 | http://jquery.com/download/
	JQuery UI v.1.11.4 | https://jqueryui.com/
	Bootstrap Material Design  | https://fezvrasta.github.io/bootstrap-material-design/



  	VERSION 0.1
    
  	Development Started: 18 February 2014
	Last updated: 04 February 2016
















//-->
<!DOCTYPE html>
<html lang="en">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Journal Finder - Western Sydney University Library and Digital Humanities Research Group</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Journal Finder">
<meta name="robots" content="noindex,nofollow">
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700&subset=latin,cyrillic-ext,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="./js/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="./js/jquery-ui/themes/base/jquery.ui.all.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">
<link rel="stylesheet" href="./css/bootstrap-theme.min.css">
<style>
			body {
				font: 10px sans-serif;
				padding: 25px;
			}

			.axis path, .axis line {
				fill: none;
				stroke: #000;
				shape-rendering: crispEdges;
			}
			
			.bar:hover {
  				fill: orangered ;
			}

			.x.axis path {
				display: none;
			}
			
			.d3-tip {
				line-height: 1;
				font-weight: bold;
				padding: 12px;
				background: rgba(0, 0, 0, 0.8);
				color: #fff;
				border-radius: 2px;
			}

			.d3-tip:after {
				box-sizing: border-box;
				display: inline;
				font-size: 10px;
				width: 100%;
				line-height: 1;
				color: rgba(0, 0, 0, 0.8);
				content: "\25BC";
				position: absolute;
				text-align: center;
			}

			.d3-tip.n:after {
				margin: -1px 0 0 0;
				top: 100%;
				left: 0;
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
			}	
</style>
<!--[if lt IE 9]>
	<script language="javascript" type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jqplot/excanvas.js"></script>
<![endif]-->
</head>
<body>
<!--[if lt IE 7]>
	<p class="browsehappy">You are using an <strong>outdated</strong> browser. 
	Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<div class="container-fluid">
	<div class="row">
		<div id="eraHeader" name="eraheader" style="width:100%; text-align:center;">
        	<input type="button" name="eraFactor" value="ERA Impact Factor" class="btn<?php 
				if(($show == "")) { echo " btn-customa"; } ?>" onclick="window.location='./arc_modal_era_factor.php?show=&amp;orderby=<?php 
					echo $orderby; ?>&amp;cluster=<?php echo $cluster; ?>'" style="width:250px;">
			<input type="button" name="eraAverage" value="ERA Average Rank Totals" class="btn<?php 
				if(($show == "average")) { echo " btn-customa"; } ?>" onclick="window.location='./arc_modal_era_factor.php?show=average&amp;orderby=<?php 
					echo $orderby; ?>&amp;cluster=<?php echo $cluster; ?>'" style="width:250px;">
			<input type="button" name="eraRank" value="ERA Average Rank" class="btn<?php 
				if(($show == "rank")) { echo " btn-customa"; } ?>" onclick="window.location='./arc_modal_era_factor.php?show=rank&amp;orderby=<?php 
					echo $orderby; ?>&amp;cluster=<?php echo $cluster; ?>'" style="width:250px;">
            <br />
			<?php
	  	
				if(($show == "")) {
	 		 		echo "<p style=\"margin: auto; text-align:justify; width: 755px; font-size: 1.7em;\">&nbsp<br />";
					echo "This graph adjusts the annual averages displayed in 'ERA Average Rank Totals' with regards to the ";
					echo "number of fields of research (FoRs) submitted. For example, an institution with a pre-average ";
					echo "total of 20 made up of 4 x ranked 5 FoRs should rate higher than an institution with a pre-average total ";
					echo "of 21 made up of 7 x ranked 3 FoRs. Like 'ERA Average Rank Totals', the result has been ";
					echo "multiplied by a 100 to make it more plottable and can be understood as a raw index of ";
					echo "strength. An institution may have a lot of ERA ranked FoRs but they may be low whereas another institution ";
					echo "may have a smaller submission of fields but higher ERA ranked results. Here the correlation therefore is ";
					echo "between size of submission and number of rank 5s with respect to the highest possible score, favouring quality over quantity. ";
					echo "<span style=\"color:#aa0000;\">The dashed red line indicates 2015 sector average.</span>";
					echo "<br /><br />The 'ERA Impact Factor' score is calculated as ";
					echo "follows: Adjusted Annual Average = ((Totalled Ranks / 895 [or cluster-specific total]) x 100) x (Totalled Ranks / (Number of Submitted FoRs x 5)). ";
					echo "<br />&nbsp;</p>";
				}
				if(($show == "average")) {
					echo "<p style=\"margin: auto; text-align:justify; width: 755px; font-size: 1.7em;\">&nbsp<br />";
					echo "The graph displays the total amount of ranks (i.e., adding up ranks for FoRs that received a result) divided by ";
					echo "the total score possible (if all fields are being considered then this is 890 or 179 FoRs x the highest possible rank of 5; ";
					echo "if a cluster is only being considered then this is the total number of FoRs in that cluster x 5). The result is then multiplied ";
					echo "by a 100 to make it more plottable. ";
					echo "<span style=\"color:#aa0000;\">The dashed red line indicates 2015 sector average.</span>";
					echo "<br /><br />Giving an idea of raw ranking power, the calculation for this is: ";
					echo "Annual Average Totals = (Totalled Ranks / 895 [or cluster-specific total]) x 100. ";
					echo "<br />&nbsp;</p>";
				}
				if(($show == "rank")) {
					echo "<p style=\"margin: auto; text-align:justify; width: 755px; font-size: 1.7em;\">&nbsp<br />";
					echo "This is a visualisation of the average rank that an institution received across its entire ";
					echo "submission or across the selected cluster plotted across three ERA rounds. ";
					echo "It is the sum of ranks returned during the ERA round divided by the number of fields that ";
					echo "received an ERA rank, either for all fields or for a specifc cluster. Like a grade point average, it indicates a generalised sense of quality. ";
					echo "<span style=\"color:#aa0000;\">The dashed red line indicates 2015 sector average.</span>";
					echo "<br /><br />The calculation behind this is: Average Rank = (Totalled Ranks / Number of Submitted FoRs). ";
					echo "<br />&nbsp;</p>";
				}
	  
	  		?>
            <br />
            <input type="button" name="orderbyA" value="Order by Scores" class="btn<?php 
				if(($orderby == "")) { echo " btn-customa"; } ?>" onclick="window.location='./arc_modal_era_factor.php?show=<?php 
					echo $show; ?>&amp;orderby=&amp;cluster=<?php echo $cluster; ?>'" style="width:250px;">
			<input type="button" name="orderbyB" value="Order by University" class="btn<?php 
				if(($orderby == "university")) { echo " btn-customa"; } ?>" onclick="window.location='./arc_modal_era_factor.php?show=<?php 
					echo $show; ?>&amp;orderby=university&amp;cluster=<?php echo $cluster; ?>'" style="width:250px;">
<!--                    
            <input type="button" name="cluster_hca" value="HCA Only" class="btn<?php 
				if(($cluster == "HCA")) { echo " btn-customa"; } ?>" onclick="window.location='./arc_modal_era_factor.php?show=<?php 
					echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=HCA'" style="width:120px;">
            <input type="button" name="cluster_all" value="All Fields" class="btn<?php 
				if(($cluster == "ALL") or ($cluster == "")) { echo " btn-customa"; } ?>" onclick="window.location='./arc_modal_era_factor.php?show=<?php 
					echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=ALL'" style="width:120px;">
//-->                    
			<div class="btn-group" style="text-align:left; width:250px;">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align:left; width:250px;">
					Fields of Research
				</button>
				<ul class="dropdown-menu">
					<li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=ALL">All Clusters and Disciplines</a></li>
					<li role="separator" class="divider"></li>
                    <li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=BBS">Biological &amp; Biotechnological Sciences</a></li> 
                    <li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=EC">Economics &amp; Commerce</a></li> 
                    <li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=EHS">Education &amp; Human Society</a></li> 
                    <li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=EES">Engineering &amp; Environmental Sciences</a></li> 
                    <li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=HCA">Humanities &amp; Creative Arts</a></li> 
                    <li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=MCS">Mathmematical &amp; Computing Sciences</a></li> 
                    <li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=MHS">Medical &amp; Health Sciences</a></li> 
					<li><a href="./arc_modal_era_factor.php?show=<?php echo $show; ?>&amp;orderby=<?php echo $orderby; ?>&amp;cluster=PCES">Physical, Chemical &amp; Earth Sciences</a></li> 
      			</ul>
			</div> 
            <h3>&nbsp;<br /><?php echo $clusterName; ?></h3>                              
        </div>
    </div>
    <div class="row">
		<div id="eraFactor" name="eraFactor" style="width:100%; text-align:center;"></div>
    </div>
    <div class="row">
		<div id="eraFooter" name="eraFooter" style="width:100%; text-align:center;">
        <?php
			echo "<p style=\"margin: auto; text-align:justify; width: 1320px; font-size: 1.3em;\">&nbsp<br />";
			$queryD = "SELECT * FROM 2015_era_institutions ORDER BY ShortName ASC";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
				echo "<strong><span style=\"color:#000080;\">$rowD[1]</span></strong> $rowD[2] | ";
			}
			echo "<br />&nbsp;</p>";
		?>
        </div>
    </div>
<!--    
    <div class="row">
		<div id="eraLogo" name="eraLogo" style="width:100%; text-align:center;">
        	&nbsp;<br />&nbsp;<br />
        	<img src="./img/logo_library.png" border="0" alt="" style="padding-right:35px;">
            <img src="./img/logo_dhrg.png" border="0" alt="" style="padding-left:35px;">
        </div>
    </div>
//-->
</div>
<script language="javascript" type="text/javascript" src="./js/jquery-1.11.0.min.js"></script>
<script language="javascript" type="text/javascript" src="./js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="./js/d3.v3.min.js"></script>
<script language="javascript" type="text/javascript" src="./js/d3.tip.v0.6.3.js"></script>
<script language="javascript" type="text/javascript">

var margin = {top: 40, right: 40, bottom: 40, left: 40},
    width = 1400 - margin.left - margin.right,
    height = 640 - margin.top - margin.bottom;

var x0 = d3.scale.ordinal().rangeRoundBands([0, width], .1);

var x1 = d3.scale.ordinal();

var y = d3.scale.linear().range([height, 0]);

var color = d3.scale.ordinal()
	<?php
	  	
		if(($show == "")) {
	  		echo ".range([\"#98abc5\", \"#d0743c\", \"#6b486b\", \"#8a89a6\", \"#7b6888\", \"#a05d56\"]);";
		}
		if(($show == "average")) {
			echo ".range([\"#98abc5\", \"#d0743c\", \"#6b486b\", \"#8a89a6\", \"#7b6888\", \"#a05d56\"]);";
		}
		if(($show == "rank")) {
			echo ".range([\"#98abc5\", \"#d0743c\", \"#6b486b\", \"#8a89a6\", \"#7b6888\", \"#a05d56\"]);";
		}
	  
	  ?>

var xAxis = d3.svg.axis()
    .scale(x0)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(d3.format(".2"));

var tip = d3.tip()
  	.attr('class', 'd3-tip')
  	.offset([-10, 0])
  	.html(function(d) {
   		return "<strong>"+ d.keyword + " - " + d.name + "</strong> : <span style='color:#FFCCCC;'>" + d.value + "</span>";
  	});

var svg = d3.select("#eraFactor").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  	.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.call(tip);

d3.csv("arc_modal_era_factor_data.php?show=<?php echo $show; ?>&orderby=<?php echo $orderby; ?>&cluster=<?php echo $cluster; ?>", function(error, data) {
  
  if (error) throw error;

  var ageNames = d3.keys(data[0]).filter(function(key) { return key !== "Annual"; });

  data.forEach(function(d) {
    d.ages = ageNames.map(function(name) { return { name: name, value: +d[name], keyword: d.Annual }; });
  });

  x0.domain(data.map(function(d) { return d.Annual; }));
  x1.domain(ageNames).rangeRoundBands([0, x0.rangeBand()]);
  y.domain([0, d3.max(data, function(d) { return d3.max(d.ages, function(d) { return d.value; }); })]);

  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
      .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("");

  var state = svg.selectAll(".state")
      .data(data)
      .enter().append("g")
      .attr("class", "state")
      .attr("transform", function(d) { return "translate(" + x0(d.Annual) + ",0)"; });

  state.selectAll("rect")
      .data(function(d) { return d.ages; })
      .enter().append("rect")
      .attr("width", x1.rangeBand())
      .attr("x", function(d) { return x1(d.name); })
      .attr("y", function(d) { return y(d.value); })
      .attr("height", function(d) { return height - y(d.value); })
      .style("fill", function(d) { return color(d.name); })
	  .attr("class", "bar")
	  .on('mouseover', tip.show)
      .on('mouseout', tip.hide);

  var legend = svg.selectAll(".legend")
      .data(ageNames.slice().reverse())
      .enter().append("g")
      .attr("class", "legend")
      .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });

  legend.append("rect")
      .attr("x", width - 18)
      .attr("width", 18)
      .attr("height", 18)
      .style("fill", color);

  legend.append("text")
      .attr("x", width - 24)
      .attr("y", 9)
      .attr("dy", ".35em")
      .style("text-anchor", "end")
      .text(function(d) { return d; });

  });

svg.append("line")
	.style("stroke", "black")
	.style("stroke-dasharray", ("3, 3"))
	.attr("x1", 0)
	.attr("y1", y(0.5))
	.attr("x2", width)
	.attr("y2", y(0.5));

<?php

/////////////////////////////////////////////////////////// ERA Sector Average Calculation

	if(($show == "")) {
		$queryD = "SELECT SUM(2015_efactor) FROM 2015_era_averages";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$eAverage = ($rowD[0] / 41);
		}
		$queryD = "SELECT 2015_efactor FROM 2015_era_averages ORDER BY 2015_efactor DESC LIMIT 1";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$eTop = $rowD[0];
		}
		$eLine = round(($eAverage / $eTop),2);
	}
	
	if(($show == "average")) {
		$queryD = "SELECT SUM(2015_era) FROM 2015_era_averages";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$eAverage = ($rowD[0] / 41);
		}
		$queryD = "SELECT 2015_era FROM 2015_era_averages ORDER BY 2015_era DESC LIMIT 1";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$eTop = $rowD[0];
		}
		$eLine = round(($eAverage / $eTop),2);
	}

	if(($show == "rank")) {
		$queryD = "SELECT SUM(2015_erank) FROM 2015_era_averages";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$eAverage = ($rowD[0] / 41);
		}
		$queryD = "SELECT 2015_erank FROM 2015_era_averages ORDER BY 2015_erank DESC LIMIT 1";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$eTop = $rowD[0];
		}
		$eLine = round(($eAverage / $eTop),2);
	}
	
?>
	  
svg.append("line")
	.style("stroke", "red")
	.style("stroke-dasharray", ("3, 3"))
	.attr("x1", 0)
	.attr("y1", y(<?php echo $eLine; ?>))
	.attr("x2", width)
	.attr("y2", y(<?php echo $eLine; ?>));

</script>
</body>
</html>
<?php
		
/////////////////////////////////////////////////////////// End content layout

	include("./admin/era.dbdisconnect.php");	
	
?>