<?php

///////////////////// Summary
//
//  This script updates the master journals table
//  (at this time it is 2017_journals_final_list)
//  with the latest JCR data, SJR data (ranks)
//  and ABDC data.
//
//  JCR SCIE Quartile, 5YR IF, IF
//  JCR SSCI Quartile, 5YR IF, IF
//  SJR SCImago
//  ADBC Ranks
//
//
///////////////////// Start

	include("config.php");
	include("era.dbconnect.php");

/////////////////////////////////////// Scopus Data Updates
        
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7, FoR1, FoR2, FoR3 FROM `2017_journals_final_list` ORDER BY ID ASC"; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) { 

///////////////////// Start Record

        $eraid = $row[0];
        $row[1] = preg_replace("/-/","","$row[1]");
        $row[2] = preg_replace("/-/","","$row[2]");
        $row[3] = preg_replace("/-/","","$row[3]");
        $row[4] = preg_replace("/-/","","$row[4]");
        $row[5] = preg_replace("/-/","","$row[5]");
        $row[6] = preg_replace("/-/","","$row[6]");
        $row[7] = preg_replace("/-/","","$row[7]");
    
///////////////////// 2011 SNIP       
    
        $snip = "";
        $queryD = "SELECT SNIP FROM `2017_Scopus_2011` WHERE Print_ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
        }
        $queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $snip = $rowD[0];
        }
        if(($snip != "")) {
            $queryDa = "UPDATE `2017_journals_snips` SET `2011_SNIP` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
            $queryDc = "UPDATE `2017_journals_final_list` SET `SNIP_2011` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDc = mysqli_query($mysqli_link, $queryDc);
            $x++;
        }
        
///////////////////// 2012 SNIP       
    
        $snip = "";
        $queryD = "SELECT SNIP FROM `2017_Scopus_2012` WHERE Print_ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
        }
        $queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $snip = $rowD[0];
        }
        if(($snip != "")) {
            $queryDa = "UPDATE `2017_journals_snips` SET `2012_SNIP` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
            $queryDc = "UPDATE `2017_journals_final_list` SET `SNIP_2012` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDc = mysqli_query($mysqli_link, $queryDc);
            $x++;
        }
        
///////////////////// 2013 SNIP       
    
        $snip = "";
        $queryD = "SELECT SNIP FROM `2017_Scopus_2013` WHERE Print_ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
        }
        $queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $snip = $rowD[0];
        }
        if(($snip != "")) {
            $queryDa = "UPDATE `2017_journals_snips` SET `2013_SNIP` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
            $queryDc = "UPDATE `2017_journals_final_list` SET `SNIP_2013` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDc = mysqli_query($mysqli_link, $queryDc);
            $x++;
        }
        
///////////////////// 2014 SNIP       
    
        $snip = "";
        $queryD = "SELECT SNIP FROM `2017_Scopus_2014` WHERE Print_ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
        }
        $queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $snip = $rowD[0];
        }
        if(($snip != "")) {
            $queryDa = "UPDATE `2017_journals_snips` SET `2014_SNIP` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
            $queryDc = "UPDATE `2017_journals_final_list` SET `SNIP_2014` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDc = mysqli_query($mysqli_link, $queryDc);
            $x++;
        }
        
///////////////////// 2015 SNIP       
    
        $snip = "";
        $queryD = "SELECT SNIP FROM `2017_Scopus_2015` WHERE Print_ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
        }
        $queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $snip = $rowD[0];
        }
        if(($snip != "")) {
            $queryDa = "UPDATE `2017_journals_snips` SET `2015_SNIP` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
            $queryDc = "UPDATE `2017_journals_final_list` SET `SNIP_2015` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDc = mysqli_query($mysqli_link, $queryDc);
            $x++;
        }
        
///////////////////// 2016 SNIP       
    
        $snip = "";
        $queryD = "SELECT SNIP FROM `2017_Scopus_2016` WHERE Print_ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
        }
        $queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $snip = $rowD[0];
        }
        if(($snip != "")) {
            $queryDa = "UPDATE `2017_journals_snips` SET `2016_SNIP` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
            $queryDc = "UPDATE `2017_journals_final_list` SET `SNIP_2016` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDc = mysqli_query($mysqli_link, $queryDc);
            $x++;
        }  

///////////////////// 2017 SNIP

        $snip = "";
        $queryD = "SELECT SNIP FROM `2017_Scopus_2017` WHERE Print_ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[7]\" "; 
        }
        $queryD = $queryD."ORDER BY ID DESC LIMIT 1"; 
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $snip = $rowD[0];
        }
        if(($snip != "")) {
            $queryDa = "UPDATE `2017_journals_snips` SET `2017_SNIP` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDa = mysqli_query($mysqli_link, $queryDa);
            $queryDc = "UPDATE `2017_journals_final_list` SET `SNIP_2017` = \"$snip\" WHERE ERAID = \"$eraid\"; ";
            $mysqli_resultDc = mysqli_query($mysqli_link, $queryDc);
            $x++;
        }         

///////////////////// Finish Record

    }
    echo "$parse: $x rows updated";
    die;  

/////////////////////////////////////////////////////////// Record OA record id
        
    $parse="Open Access";
    $query = "UPDATE 2017_journals_final_list SET OpenAccess = \"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 FROM 2017_journals_final_list WHERE OpenAccess =\"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) { 
        $OA = "";
        $queryD = "SELECT ID FROM 2018_data_oa WHERE Print_ISSN = \"$row[1]\" OR EISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[2]\" OR EISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[3]\" OR EISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[4]\" OR EISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[5]\" OR EISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[6]\" OR EISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN = \"$row[7]\" OR EISSN = \"$row[7]\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $OA = "$rowD[0]";
        }
        if(($OA !="")) {
            $r++;
            $queryD = "UPDATE 2017_journals_final_list SET OpenAccess = \"Yes\" WHERE ERAID = \"$row[0]\" ";
            $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        }
        $x++;
    }
    echo "$parse: $x rows processed | $r updated with OA";
    die;

///////////////////// WSU Funded

    $r = 0;
    $x = 0;
    $query = "UPDATE 2017_journals_final_list SET WSU_Funded = \"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 ";
    $query .= "FROM 2017_journals_final_list WHERE WSU_Funded = \"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) {
        $WSU = "";
        $queryD = "SELECT Title FROM 2018_data_wsu_funded ";
        $queryD .= "WHERE ISSN_Print LIKE \"%$row[1]%\" OR ISSN_Online LIKE \"%$row[1]%\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN_Print LIKE \"%$row[2]%\" OR ISSN_Online LIKE \"%$row[2]%\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN_Print LIKE \"%$row[3]%\" OR ISSN_Online LIKE \"%$row[3]%\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN_Print LIKE \"%$row[4]%\" OR ISSN_Online LIKE \"%$row[4]%\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN_Print LIKE \"%$row[5]%\" OR ISSN_Online LIKE \"%$row[5]%\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN_Print LIKE \"%$row[6]%\" OR ISSN_Online LIKE \"%$row[6]%\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN_Print LIKE \"%$row[7]%\" OR ISSN_Online LIKE \"%$row[7]%\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $WSU = "Yes";
        }
        if(($WSU == "Yes")){
            $r++;
            $queryD = "UPDATE 2017_journals_final_list ";
            $queryD .= "SET WSU_Funded = \"Yes\" ";
            $queryD .= "WHERE ERAID = \"$row[0]\" ";
            $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        }
        $x++;
    }
    echo "WSU funded updated $r rows through $x loops<br />";
    die;

///////////////////// SJR

    $r = 0;
    $x = 0;
    $query = "UPDATE 2017_journals_final_list SET SCImago = \"\", SCIMAGO_Cat = \"\", SCIMAGO_Rank = \"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 ";
    $query .= "FROM 2017_journals_final_list WHERE SCImago = \"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) {
        for($g=1;$g<8;$g++) {
            $row[$g] = preg_replace("/-/i","","$row[$g]");
        }			
        $newSCImago = "";
        $newSCIMAGO_Cat = "";
        $newSCIMAGO_Rank =  "";
        $tempSCIMAGO = "";
        $queryD = "SELECT Print_ISSN, Categories FROM 2018_data_ranks ";
        $queryD .= "WHERE Print_ISSN LIKE \"%$row[1]%\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR Print_ISSN LIKE \"%$row[2]%\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR Print_ISSN LIKE \"%$row[3]%\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR Print_ISSN LIKE \"%$row[4]%\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR Print_ISSN LIKE \"%$row[5]%\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR Print_ISSN LIKE \"%$row[6]%\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR Print_ISSN LIKE \"%$row[7]%\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $newSCImago = "$rowD[0]";
            $tempSCIMAGO = "$rowD[1]";
        }
        if(($newSCImago != "")) {
            $r++;
            if(preg_match("/; /",$tempSCIMAGO)){
            	$t = explode("; ",$tempSCIMAGO);
            	foreach($t as $rr){
            		$s = explode(" (Q",$rr);
            		$s[1] = substr_replace($s[1], "", -1);
            		$newSCIMAGO_Rank .= "Q".trim($s[1])."; ";
            		$newSCIMAGO_Cat .= trim($s[0])."; ";
            	}
            } else {
            	$s = explode(" (Q",$tempSCIMAGO);
            	$s[1] = substr_replace($s[1], "", -1);
            	$newSCIMAGO_Rank = "Q".trim($s[1])."; ";
            	$newSCIMAGO_Cat = trim($s[0])."; ";
            }
            $queryD = "UPDATE 2017_journals_final_list ";
            $queryD .= "SET SCImago = \"$newSCImago\", ";
            $queryD.= "SCIMAGO_Rank = \"$newSCIMAGO_Rank\", ";
            $queryD.= "SCIMAGO_Cat = \"$newSCIMAGO_Cat\" ";
            $queryD .= "WHERE ERAID = \"$row[0]\" ";
            $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        }
        $x++;
    }
    echo "SJR Ranks updated $r rows through $x loops<br />";

///////////////////// ABDC
    
    $r = 0;
    $x = 0;
    $query = "UPDATE `2017_journals_final_list` SET ";
    $query .= "ABDC_Rank = \"\", ABDC_FoR = \"\" ";
    $mysqli_result = mysqli_query($mysqli_link, $query);
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 ";
    $query .= "FROM `2017_journals_final_list` ORDER BY ID ASC";
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) {
        $jcr[0] = "";
        $jcr[1] = "";
        $queryD = "SELECT ABDC_Rank, ABDC_FoR FROM ";
        $queryD .= "`2018_data_abdc` WHERE ( ";
        $queryD .= "ISSN_Print = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN_Print = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN_Print = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN_Print = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN_Print = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN_Print = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN_Print = \"$row[7]\" ";
        }
        $queryD .= ") OR ( ";
        $queryD .= "ISSN_Online = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN_Online = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN_Online = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN_Online = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN_Online = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN_Online = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN_Online = \"$row[7]\" ";
        }
        $queryD .= ") ";
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) {
            $jcr[0] = "$rowD[0]";
            $jcr[1] = "$rowD[1]";
        }
        if(($jcr[0] !="")) {
            $r++;
            if(strlen($jcr[1]) == 6) {
                $jcr[1] = substr("$jcr[1]", 0, -2);
            }
            if(strlen($jcr[1]) == 3) {
                $jcr[1] = "0".$jcr[1];
            }
            $queryDE = "UPDATE `2017_journals_final_list` ";
            $queryDE .= "SET ABDC_Rank = \"$jcr[0]\", ";
            $queryDE .= "ABDC_FoR = \"$jcr[1]\" ";
            $queryDE .= "WHERE ERAID = \"$row[0]\" ";
            $mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
        }
        $x++;
    }
    echo "ABDC updated $r rows through $x loops<br />";
    die();
    
///////////////////// JCR SSCI
		
    $r = 0;
    $x = 0;
    $query = "UPDATE 2017_journals_final_list SET ";
    $query .= "JCR_Cat = \"\", JCR_Rank = \"\", JCR_Quartile = \"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 ";
    $query .= "FROM 2017_journals_final_list ORDER BY ID ASC"; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) { 
        $jcr[0] = "";
        $jcr[1] = "";
        $jcr[2] = "";
        $jcr[3] = "";
        $queryD = "SELECT CATEGORY_DESCRIPTION, CATEGORY_RANKING, ";
        $queryD .= "QUARTILE_RANK, IMPACT_FACTOR ";
        $queryD .= "FROM 2018_data_jcr_ssci ";
        $queryD .= "WHERE ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[7]\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $jcr[0] = "$rowD[0]";
            $jcr[1] = "$rowD[1]";
            $jcr[2] = "$rowD[2]";
            $jcr[3] = "$rowD[3]";
            if(($jcr[0] !="")) {
                $r++;
                $queryDE = "UPDATE 2017_journals_final_list ";
                $queryDE .= "SET JCR_Cat = CONCAT(JCR_Cat,\"$jcr[0]; \"), ";
                $queryDE .= "JCR_Rank = CONCAT(JCR_Rank,\"$jcr[1]; \"), ";
                $queryDE .= "JCR_Quartile = CONCAT(JCR_Quartile,\"$jcr[2]; \"), ";
                $queryDE .= "IF_2012 = \"$jcr[3]\" ";
                $queryDE .= "WHERE ERAID = \"$row[0]\" ";
                $mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
            }
        }
        $x++;
    }
    echo "JCR SSCI updated $r rows through $x loops<br />";

///////////////////// JCR SCIE

    $r = 0;
    $x = 0;
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 ";
    $query .= "FROM 2017_journals_final_list ORDER BY ID ASC"; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) { 
        $jcr[0] = "";
        $jcr[1] = "";
        $jcr[2] = "";
        $jcr[3] = "";
        $queryD = "SELECT CATEGORY_DESCRIPTION, CATEGORY_RANKING, ";
        $queryD .= "QUARTILE_RANK, IMPACT_FACTOR ";
        $queryD .= "FROM 2018_data_jcr_scie ";
        $queryD .= "WHERE ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[7]\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $jcr[0] = "$rowD[0]";
            $jcr[1] = "$rowD[1]";
            $jcr[2] = "$rowD[2]";
            $jcr[3] = "$rowD[3]";
            if(($jcr[0] !="")) {
                $r++;
                $queryDE = "UPDATE 2017_journals_final_list ";
                $queryDE .= "SET JCR_Cat = CONCAT(JCR_Cat,\"$jcr[0]; \"), ";
                $queryDE .= "JCR_Rank = CONCAT(JCR_Rank,\"$jcr[1]; \"), ";
                $queryDE .= "JCR_Quartile = ";
                $queryDE .= "CONCAT(JCR_Quartile,\"$jcr[2]; \"), ";
                $queryDE .= "IF_2012 = \"$jcr[3]\" ";
                $queryDE .= "WHERE ERAID = \"$row[0]\" ";
                $mysqli_resultDE = mysqli_query($mysqli_link, $queryDE);
            }
        }
        $x++;
    }
    echo "JCR SCIE updated $r rows through $x loops<br />";

///////////////////// JCR 5 Year Impact Factor

    $r = 0;
    $x = 0;
    $query = "UPDATE 2017_journals_final_list SET 5YR_IMPACT_FACTOR = \"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 ";
    $query .= "FROM 2017_journals_final_list ORDER BY ID ASC"; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) { 
        $jcrYR = "";
        $queryD = "SELECT 5YR_IMPACT_FACTOR FROM 2018_data_jcr_ssci ";
        $queryD .= "WHERE ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[7]\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $jcrYR = "$rowD[0]";
        }
        if(($jcrYR != "")) {
            $r++;
            $queryD = "UPDATE 2017_journals_final_list SET ";
            $queryD .= "5YR_IMPACT_FACTOR = \"$jcrYR\" ";
            $queryD .= "WHERE ERAID = \"$row[0]\" ";
            $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        }	
        $jcrYR = "";
        $queryD = "SELECT 5YR_IMPACT_FACTOR FROM 2018_data_jcr_scie ";
        $queryD .= "WHERE ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[7]\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $jcrYR = "$rowD[0]";
        }
        if(($jcrYR != "")) {
            $r++;
            $queryD = "UPDATE 2017_journals_final_list ";
            $queryD .= "SET 5YR_IMPACT_FACTOR = \"$jcrYR\" ";
            $queryD .= "WHERE ERAID = \"$row[0]\" ";
            $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        }			
        $x++;
    }	
    echo "JCR 5 Year Impact updated $r rows through $x loops<br />";

///////////////////// JCR Impact Factor

    $r = 0;
    $x = 0;
    $query = "UPDATE 2017_journals_final_list SET IF_2012 = \"\" "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    $query = "SELECT ERAID, ISSN1, ISSN2, ISSN3, ISSN4, ISSN5, ISSN6, ISSN7 ";
    $query .= "FROM 2017_journals_final_list ORDER BY ID ASC "; 
    $mysqli_result = mysqli_query($mysqli_link, $query);
    while($row = mysqli_fetch_row($mysqli_result)) {
        $IF="";
        $queryD = "SELECT IMPACT_FACTOR FROM 2018_data_jcr_ssci ";
        $queryD .= "WHERE ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[7]\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $IF = "$rowD[0]";
        }
        if(($IF !="")) {
            $r++;
            $queryD = "UPDATE 2017_journals_final_list SET ";
            $queryD .= "IF_2012 = \"$IF\" WHERE ERAID = \"$row[0]\" ";
            $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        }
        $IF="";
        $queryD = "SELECT IMPACT_FACTOR FROM 2018_data_jcr_scie ";
        $queryD .= "WHERE ISSN = \"$row[1]\" ";
        if(($row[2] != "") && ($row[2] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[2]\" ";
        }
        if(($row[3] != "") && ($row[3] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[3]\" ";
        }
        if(($row[4] != "") && ($row[4] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[4]\" ";
        }
        if(($row[5] != "") && ($row[5] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[5]\" ";
        }
        if(($row[6] != "") && ($row[6] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[6]\" ";
        }
        if(($row[7] != "") && ($row[7] != " ")) {
            $queryD = $queryD."OR ISSN = \"$row[7]\" "; 
        }
        $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
            $IF = "$rowD[0]";
        }
        if(($IF !="")) {
            $r++;
            $queryD = "UPDATE 2017_journals_final_list ";
            $queryD .= "SET IF_2012 = \"$IF\" WHERE ERAID = \"$row[0]\" ";
            $mysqli_resultD = mysqli_query($mysqli_link, $queryD);
        }
        $x++;
    }
    echo "JCR Impact updated $r rows through $x loops<br />";

///////////////////// Finish

	include("era.dbdisconnect.php");

?>
