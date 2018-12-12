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
//  12 December 2018
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	$wildcard = "";
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	
/////////////////////////////////////////////////////////// Content

?>
<div class="col-lg-12">
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;"><strong>Why do we need to know a journal's assigned field of research?</strong></p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">In Australia, every few years the Australian Research Council (ARC) releases a journal list of about 22,000 publications and next to each it has up to three values. These values are codes for fields of research and they identify which journals are assigned to which fields. While an article by Eugene Garfield,  considered a founder of the use of bibliometrics  and scientometrics in research impact, suggests a different approach to identifying which field a journal's strengths are aligned with, in Australia researchers do not have this option. Instead, journal assignment is externally set at the Federal Government level but, crucially, knowledge of that setting is neither widely known nor available in ways that are intuitive and accessible. Often researchers publish where they want, unaware that the journal they have selected may not actually be in their field. Yet is a relatively common need for colleagues to know what might be the 'best' journals to publish in and which journals connect well with their area of investigation.</p>
<p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">Noteworthy services which provide important alternative impact data on journals often assign publications in ways that conflict with the Australian context. For example, Eigenfactor  is a University of Washington project that measures a “journal's total importance to the scientific community” and is thought to provide more robust metrics of journal quality than the standard impact factor. Yet in sample searches conducted in the Eigenfactor service, many journals were delegated out of field, with titles like the Journal of Australian Studies improperly assigned to “Political Science”. There are gaps too in the journal coverage: Aboriginal History has citation data in Scopus but the journal does not exist in Eigenfactor search results. Overall, Social Sciences / Humanities areas and Australian journals are not well represented and it can be difficult to determine if a journal is counted as being in your field of research. Often researchers determine a journal’s field of research with regards to the publication’s title and scope policy but this can conflict with the actual fields assigned to the journal by the ARC.</p>
<p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">Within this context, the Journal Finder project is about helping researchers publish their work in relevant mid-to-high quality journals through providing contextual information on how a journal counts towards a particular field. This is within Australian contexts and with regards to conventional and alternative impact metrics plus Federal Government lists. At its core, the service provides researchers with information about journal FoRs (fields of research as per the ARC disciplinary matrix and journal list) and their journal impact trends. Journal Finder also draws data from major citation research assets like Scopus or Thomas Reuters, and display relative citation impact trends with respect to known national citation benchmarks in a field of research. (Given the issues of the impact factor and how it is derived, it remains important to align or contrast this information with other measures of journal evaluation.) </p>
<p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">The use case therefore for this Journal Finder service is about providing field and impact data that speaks to Australian contexts. As an example, the Australian Research Council officially describes publications in the following list:</p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;"><strong>ARC Draft and Final Journal Lists</strong></p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;"><img src="./img/position_2.png"alt="" width="100%" border="0" /></p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">This is incredibly unwieldy and quite opaque to the average researcher yet it contains information critical to making sure one is publishing in rather than out of your field of research (the FoR column in the list). So a key part of the interface is being able to drill down into the journals that are linked to one’s field with regards to Australia's disciplinary matrix. On that point, Australia’s disciplinary matrix is another example of how difficult this data is to digest:</p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;"><strong>ARC Disciplinary Matrix</strong></p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;"><img src="./img/position_1.png"alt="" width="100%" border="0" /></p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">So once researchers can quickly and accurately identify journals in their area, this is then where other existing services can be polled for contextual data. This ranges from providing information about whether the journal is indexed and abstracted by the best databases in one's field (as a crude indicator of being an effective dissemination of research) to where the journal ranks within one's field. Researchers want the freedom to publish where they can but they also want all the information that they need to make a sound decision collected together in as fewer places as possible.  Ideally, the building a dashboard like Journal Finder that draws down this information from existing external service and collates the data into one place will make such decisions a little easier.</p>
<p style="text-align:justify; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">Recognizing the need to use data ‘better’ and understanding the strength of digital humanities in that area, UWS Library engaged with the Digital Humanities group and developed a tool that would accumulate both subscribed and openly available metrics information and provide meaning to an otherwise complex task. Developed internally, using open standards, UWS’s Journal Finder provides researchers with a portal based on Field of Research that aggregates a range of metrics and assists the data-driven decision-making process. The Journal Finder project has not been about creating a new tool, it has been about organising existing information relating to journals and impact in ways that are relevant to the Australian situation and more easily identifiable. </p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;"><em>Dr Jason Ensor, Susan Robbins, Michael Gonzalez<br />Western Sydney University Library</em></p>
<p style="text-align:left; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 0.99em;">&nbsp;</p>
</div>
<?
	
/////////////////////////////////////////////////////////// Footer

	include("./admin/era.dbdisconnect.php");	
	
?>