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

	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	
/////////////////////////////////////////////////////////// Render page
	
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <link type="text/css" rel="stylesheet" href="./js/d3/style.css"/>
    <style type="text/css">

path.arc {
  cursor: move;
  fill: #fff;
}

.node {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 14px;
}

.node:hover {
  fill: #1f77b4;
}

.link {
  fill: none;
  stroke: #1f77b4;
  stroke-opacity: .4;
  pointer-events: none;
}

.link.source, .link.target {
  stroke-opacity: 1;
  stroke-width: 2px;
}

.node.target {
  fill: #D40026 !important;
}

.link.source {
  stroke: #D40026;
}

.node.source {
  fill: #D40026;
}

.link.target {
  stroke: #D40026;
}

    </style>
    <style type="text/css">
<?php

		$unis = array("Australian Catholic University", "Australian National University", "Batchelor Institute of Indigenous Tertiary Education", "Bond University", "Central Queensland University", "Charles Darwin University", "Charles Stuart University", "Curtin University of Technology", "Deakin University", "Edith Cowan University", "Flinders University", "Griffith University", "James Cook University", "La Trobe University", "Macquarie University", "Melbourne College of Divinity", "Monash University", "Murdoch University", "Queensland University of Technology", "RMIT University", "Southern Cross University", "Swinburne University of Technology", "University of Adelaide", "University of Ballarat", "University of Canberra", "University of Melbourne", "University of New England", "University of New South Wales", "University of Newcastle", "University of Notre Dame Australia", "University of Queensland", "University of South Australia", "University of Southern Queensland", "University of Sydney", "University of Tasmania", "University of Technology Sydney", "University of the Sunshine Coast", "University of Western Australia", "University of Western Sydney", "University of Wollongong", "Victoria University");
		
		foreach($unis AS $u) {
			$namedU = preg_replace("/ /i","_","$u");
			echo "\n#node-".$namedU."{";
			echo "\n	font-weight: bold;";
			echo "\n	color: #800000 !important";
			echo "\n}\n";
		}
		
		$fields = array("ID", "Institution", "01 Mathematical Sciences", "0101 Pure Mathematics", "0102 Applied Mathematics", "0103 Numerical and Computational Mathematics", "0104 Statistics", "0105 Mathematical Physics", "0199 Other Mathematical Sciences", "02 Physical Sciences", "0201 Astronomical and Space Sciences", "0202 Atomic Molecular Nuclear Particle and Plasma Physics", "0203 Classical Physics", "0204 Condensed Matter Physics", "0205 Optical Physics", "0206 Quantum Physics", "0299 Other Physical Sciences", "03 Chemical Sciences", "0301 Analytical Chemistry", "0302 Inorganic Chemistry", "0303 Macromolecular and Materials Chemistry", "0304 Medicinal and Biomolecular Chemistry", "0305 Organic Chemistry", "0306 Physical Chemistry", "0307 Theoretical and Computational Chemistry", "0399 Other Chemical Sciences", "04 Earth Sciences", "0401 Atmospheric Sciences", "0402 Geochemistry", "0403 Geology", "0404 Geophysics", "0405 Oceanography", "0406 Physical Geography and Environmental Geoscience", "0499 Other Earth Sciences", "05 Environmental Sciences", "0501 Ecological Applications", "0502 Environmental Science and Management", "0503 Soil Sciences", "0599 Other Environmental Sciences", "06 Biological Sciences", "0601 Biochemistry and Cell Biology", "0602 Ecology", "0603 Evolutionary Biology", "0604 Genetics", "0605 Microbiology", "0606 Physiology", "0607 Plant Biology", "0608 Zoology", "0699 Other Biological Sciences", "07 Agriculture and Veterinary Sciences", "0701 Agriculture Land and Farm Management", "0702 Animal Production", "0703 Crop and Pasture Production", "0704 Fisheries Sciences", "0705 Forestry Sciences", "0706 Horticultural Production", "0707 Veterinary Sciences", "0799 Other Agricultural and Veterinary Sciences", "08 Information and Computing Sciences", "0801 Artificial Intelligence and Image Processing", "0802 Computation Theory and Mathematics", "0803 Computer Software", "0804 Data Format", "0805 Distributed Computing", "0806 Information Systems", "0807 Library and Information Studies", "0899 Other Information and Computing Sciences", "09 Engineering", "0901 Aerospace Engineering", "0902 Automotive Engineering", "0903 Biomedical Engineering", "0904 Chemical Engineering", "0905 Civil Engineering", "0906 Electrical and Electronic Engineering", "0907 Environmental Engineering", "0908 Food Sciences", "0909 Geomatic Engineering", "0910 Manufacturing Engineering", "0911 Maritime Engineering", "0912 Materials Engineering", "0913 Mechanical Engineering", "0914 Resources Engineering and Extractive Metallurgy", "0915 Interdisciplinary Engineering", "0999 Other Engineering", "10 Technology", "1001 Agricultural Biotechnology", "1002 Environmental Biotechnology", "1003 Industrial Biotechnology", "1004 Medical Biotechnology", "1005 Communications Technologies", "1006 Computer Hardware", "1007 Nanotechnology", "1099 Other Technology", "11 Medical and Health Sciences", "1101 Medical Biochemistry and Metabolomics", "1102 Cardiovascular Medicine and Haematology", "1103 Clinical Sciences", "1104 Complementary and Alternative Medicine", "1105 Dentistry", "1106 Human Movement and Sports Science", "1107 Immunology", "1108 Medical Microbiology", "1109 Neurosciences", "1110 Nursing", "1111 Nutrition and Dietetics", "1112 Oncology and Carcinogenesis", "1113 Ophthalmology and Optometry", "1114 Paediatrics and Reproductive Medicine", "1115 Pharmacology and Pharmaceutical Sciences", "1116 Medical Physiology", "1117 Public Health and Health Services", "1199 Other Medical and Health Sciences", "12 Built Environment and Design", "1201 Architecture", "1202 Building", "1203 Design Practice and Management", "1204 Engineering Design", "1205 Urban and Regional Planning", "1299 Other Built Environment and Design", "13 Education", "1301 Education Systems", "1302 Curriculum and Pedagogy", "1303 Specialist Studies In Education", "1399 Other Education", "14 Economics", "1401 Economic Theory", "1402 Applied Economics", "1403 Econometrics", "1499 Other Economics", "15 Commerce Management Tourism and Services", "1501 Accounting Auditing and Accountability", "1502 Banking Finance and Investment", "1503 Business and Management", "1504 Commercial Services", "1505 Marketing", "1506 Tourism", "1507 Transportation and Freight Services", "1599 Other Commerce Management Tourism and Services", "16 Studies In Human Society", "1601 Anthropology", "1602 Criminology", "1603 Demography", "1604 Human Geography", "1605 Policy and Administration", "1606 Political Science", "1607 Social Work", "1608 Sociology", "1699 Other Studies In Human Society", "17 Psychology and Cognitive Science", "1701 Psychology", "1702 Cognitive Science", "1799 Other Psychology and Cognitive Science", "18 Law and Legal Studies", "1801 Law", "1802 Maori Law", "1899 Other Law and Legal Studies", "19 Studies In Creative Arts and Writing", "1901 Art Theory and Criticism", "1902 Film Television and Digital Media", "1903 Journalism and Professional Writing", "1904 Performing Arts and Creative Writing", "1905 Visual Arts and Crafts", "1999 Other Studies In Creative Arts and Writing", "20 Language Communication and Culture", "2001 Communication and Media Studies", "2002 Cultural Studies", "2003 Language Studies", "2004 Linguistics", "2005 Literary Studies", "2099 Other Language Literature and Culture", "21 History and Archaeology", "2101 Archaeology", "2102 Curatorial and Related Studies", "2103 Historical Studies", "2199 Other History and Archaeology", "22 Philosophy and Religious Studies", "2201 Applied Ethics", "2202 History and Philosophy Of Specific Fields", "2203 Philosophy", "2204 Religion and Religious Studies", "2299 Other Philosophy and Religious Studies");
		
		foreach($fields AS $f) {
			$namedF = preg_replace("/ /i","_","$f");
			$em = "#000000";
			$node = "1";
			
			if(($f != "ID") && ($f != "Institution")) {
				$queryD = "SELECT COUNT(`$f`) AS World_Standard FROM `2012_era` WHERE `$f` = \"5\"";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) {
					$node = $rowD[0];
				} 
				
				if(($node == 0)) { $em = "#888888"; }
				if(($node > 0) && ($node < 2)) { $em = "#DB161D"; }
				if(($node > 1) && ($node < 3)) { $em = "#EC4DB4"; }
				if(($node > 2) && ($node < 4)) { $em = "#BF1384"; }
				if(($node > 3) && ($node < 5)) { $em = "#AB44EC"; }
				if(($node > 4) && ($node < 6)) { $em = "#7712B7"; }
				if(($node > 5) && ($node < 7)) { $em = "#2412B7"; }
				if(($node > 6) && ($node < 8)) { $em = "#125DB7"; }
				if(($node > 7) && ($node < 9)) { $em = "#12A5B7"; }
				if(($node > 8)) { $em = "#1FB913"; }
			
				echo "\n#node-".$namedF." {";
				echo "\n	fill: $em; ";
				echo "\n}\n"; 
			
				echo "\n#node-".$namedF.":hover {";
				echo "\n	fill: #009900; ";
				echo "\n}\n";
			}
		}
		
?>
    </style>
  </head>
  <body>
    <!-- <div style="position:absolute;bottom:0;font-size:18px;">Tension: <input style="position:relative;top:3px;" type="range" min="0" max="100" value="85"></div> //-->
    <script type="text/javascript" src="./js/d3/d3.other.js"></script>
    <script type="text/javascript" src="./js/d3/d3.layout.js"></script>
    <script type="text/javascript" src="./js/d3/packages.js"></script>
    <script type="text/javascript">

var w = 1800,
    h = 1500,
    rx = w / 2,
    ry = h / 2,
    m0,
    rotate = 0;

var splines = [];

var cluster = d3.layout.cluster()
    .size([360, ry - 360])
    .sort(function(a, b) { return d3.ascending(a.key, b.key); });

var bundle = d3.layout.bundle();

var line = d3.svg.line.radial()
    .interpolate("bundle")
    .tension(.85)
    .radius(function(d) { return d.y; })
    .angle(function(d) { return d.x / 180 * Math.PI; });

// Chrome 15 bug: <https://code.google.com/p/chromium/issues/detail?id=98951>

var div = d3.select("body").insert("div", "h2")
    .style("top", "-80px")
    .style("left", "-160px")
    .style("width", w + "px")
    .style("height", w + "px")
    .style("position", "absolute")
    .style("-webkit-backface-visibility", "hidden");

var svg = div.append("svg:svg")
    .attr("width", w)
    .attr("height", w)
  .append("svg:g")
    .attr("transform", "translate(" + rx + "," + ry + ")");

svg.append("svg:path")
    .attr("class", "arc")
    .attr("d", d3.svg.arc().outerRadius(ry - 120).innerRadius(0).startAngle(0).endAngle(2 * Math.PI))
    .on("mousedown", mousedown);

d3.json("arc_era_imports.json-cat5.php", function(classes) {
  var nodes = cluster.nodes(packages.root(classes)),
      links = packages.imports(nodes),
      splines = bundle(links);

  var path = svg.selectAll("path.link")
      .data(links)
    .enter().append("svg:path")
      .attr("class", function(d) { return "link source-" + d.source.key + " target-" + d.target.key; })
      .attr("d", function(d, i) { return line(splines[i]); });

  svg.selectAll("g.node")
      .data(nodes.filter(function(n) { return !n.children; }))
    .enter().append("svg:g")
      .attr("class", "node")
      .attr("id", function(d) { return "node-" + d.key; })
      .attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")"; })
    .append("svg:text")
      .attr("dx", function(d) { return d.x < 180 ? 8 : -8; })
      .attr("dy", ".31em")
      .attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
      .attr("transform", function(d) { return d.x < 180 ? null : "rotate(180)"; })
      .text(function(d) { return d.key; })
      .on("mouseover", mouseover)
      .on("mouseout", mouseout);

  d3.select("input[type=range]").on("change", function() {
    line.tension(this.value / 100);
    path.attr("d", function(d, i) { return line(splines[i]); });
  });
});

d3.select(window)
    .on("mousemove", mousemove)
    .on("mouseup", mouseup);

function mouse(e) {
  return [e.pageX - rx, e.pageY - ry];
}

function mousedown() {
  m0 = mouse(d3.event);
  d3.event.preventDefault();
}

function mousemove() {
  if (m0) {
    var m1 = mouse(d3.event),
        dm = Math.atan2(cross(m0, m1), dot(m0, m1)) * 180 / Math.PI;
    div.style("-webkit-transform", "translateY(" + (ry - rx) + "px)rotateZ(" + dm + "deg)translateY(" + (rx - ry) + "px)");
  }
}

function mouseup() {
  if (m0) {
    var m1 = mouse(d3.event),
        dm = Math.atan2(cross(m0, m1), dot(m0, m1)) * 180 / Math.PI;

    rotate += dm;
    if (rotate > 360) rotate -= 360;
    else if (rotate < 0) rotate += 360;
    m0 = null;

    div.style("-webkit-transform", null);

    svg
        .attr("transform", "translate(" + rx + "," + ry + ")rotate(" + rotate + ")")
      .selectAll("g.node text")
        .attr("dx", function(d) { return (d.x + rotate) % 360 < 180 ? 8 : -8; })
        .attr("text-anchor", function(d) { return (d.x + rotate) % 360 < 180 ? "start" : "end"; })
        .attr("transform", function(d) { return (d.x + rotate) % 360 < 180 ? null : "rotate(180)"; });
  }
}

function mouseover(d) {
  svg.selectAll("path.link.target-" + d.key)
      .classed("target", true)
      .each(updateNodes("source", true));

  svg.selectAll("path.link.source-" + d.key)
      .classed("source", true)
      .each(updateNodes("target", true));
}

function mouseout(d) {
  svg.selectAll("path.link.source-" + d.key)
      .classed("source", false)
      .each(updateNodes("target", false));

  svg.selectAll("path.link.target-" + d.key)
      .classed("target", false)
      .each(updateNodes("source", false));
}

function updateNodes(name, value) {
  return function(d) {
    if (value) this.parentNode.appendChild(this);
    svg.select("#node-" + d[name].key).classed(name, value);
  };
}

function cross(a, b) {
  return a[0] * b[1] - a[1] * b[0];
}

function dot(a, b) {
  return a[0] * b[0] + a[1] * b[1];
}

    </script>
  </body>
</html>
<?php
	
/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");
?>