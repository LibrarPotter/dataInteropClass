<?php

/* ***********************************

*

*	UPDATED WITH ANSWERS PER PATRICK'S QUESTIONS

*	MAY 12, 2016, 6:20 pm

*   GB

*

************************************** */

/* ***********************************

*

*   UPDATED TO BETTER MATCH DI SCHEMA MAP

*   MAY 15, 2016, 5:20 pm

*   MP

*

************************************** */

/* ***************************************************

*

*   Start of the script to extract db records and create enterprise-wide xml

*   Version 1.0;  Date:  Apr 29, 2016

*   Author: G Benoit, benoit@simmons.edu

*   Modifications: Megan Potterbusch, pottem@simmons.edu

*

***************************************************** */



/* -----> ADDING SOME HTML STUFF FOR DISPLAY */

echo "<!DOCTYPE html><html><head><title>Output Test</title>

	<style>body { padding: 10px; font-family: 'Avenir Next', sans-serif; }

	</style></head><body>";



/* ***************************************************

*

*   GLOBAL VARIABLES (usually we try to avoid these)

*

***************************************************** */

$okToProceed = false;

$i = 0;

$headerPrinted = false;

$footerPrinted = false;

$fileToCreate="";

$stringToAdd="";

/* ***************************************************

*

*   MAP THE FIELDS ACROSS THE NAMESPACES

*

***************************************************** */

$xml = '<?xml version="1.0" ?>

<di xmlns="urn:loc.gov:books" 

    xmlns:dc="urn:ISBN:xxx"

    xmlns:vra="http://web.vra.com/">

<diRecords>';



$diTags = ["creatorArea","titleArea","subjectArea"];

$vraTags = ["agent","title","subject"];

$dcTags = ["creator","title","subject"];






/* ***************************************************

*

*   START SCREEN OUTPUT TO END-USER

*

***************************************************** */



echo 'This is a test of connecting to a MySQL table using an object-oriented approach,'.

    ' and a config.ini file on dany.simmons.edu';

/* ***************************************************

*

*   GET VARIABLES FOR CONNECTING TO THE DATABASE

*

***************************************************** */

/* ************* GET THE DATA TO CONNECT TO THE DB *********** */

echo '<ol><li>Opening the config.ini file.</li>';



$config = parse_ini_file('config.ini');

$mainTable = $config['tablename'];

echo "<li>Creating connection variable.  Table to use: $mainTable</li><li>Host: $config[hostname]</li>

	<li>DB Name $config[dbname]</li>";



$con = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);



if (mysqli_connect_errno()) {

    echo "<li>Sorry, there's been an error connecting [line 79]: ".mysqli_connect_error()."</li>";



} else {

    $okToProceed = true;  // SET TO TRUE AS A WAY OUT IF NEC.

    echo("<li>You are successfully connected!</li>");

}



/* ***************************************************

*

*   SQL QUERY - would usually use a "Prepared Statement"

*

***************************************************** */



echo ("<li> The table being used for this query is ".$mainTable."</li>");



/* -----> THERE ARE TOO MANY RECORDS TO PROCESS - SO LETS LIMIT THE QUERY TO 5 

starting with record 100 */

//$test_query = "SELECT * FROM $mainTable LIMIT 5 OFFSET 100";
$test_query = "SELECT * FROM datafiles WHERE recno<21;";




echo '<li>The test query is <code>'.$test_query.'.....</code></li>';

echo "<li>And be sure to escape the string to prevent <i>insertion</i></li>";

$test_query = $con->real_escape_string($test_query);



/* ***************************************************

*

*   ISSUE QUERY

*

***************************************************** */



echo '<li>Now issuing query.</li></ol><hr />';



/* ***************************************************

*

*   IF THERE'S DATA, PROCESS AND DISPLAY ON THE SCREEN

*

***************************************************** */

if ($result = $con->query($test_query)) {



    $activeNameToProcess = "";  // group all by name.

			

	echo "<br /><ol>";			

    while ($row = $result->fetch_object()) { 

    	$currentRecno = $row->recno;

        $recordID = $row->ObjectID;

        /*BE CERTAIN TO USE THE CORRECT MAPPING FOR YOUR DATABASE STRUCTURE HERE*/

    	//$name=$row->creatorArtist1; 
        $name=$row->creatorName;

    	//$type=$row->objectWorkType1;
        $type=$row->objectType;

    	//$location=$row->locationName;

    	//$name=$row->creatorArtist1;

    	//$copyright=$row->copyrightYear;

    	$title=$row->titleOfObject;

    	$description=$row->description;

    	//$creationDate=$row->recordCreationDate;

    	

    	echo '<li>Recno: '.$currentRecno.'.  Name: '.$name.'</li>';

    	//$activeNameToProcess = $row->creatorArtist1;
        $activeNameToProcess = $row->creatorName;



	    /* -----> THIS IS BETTER ... NOTICE WE DO NOT NEED THE IF STATEMENT! */

    	createXMLHeader($currentRecno,$recordID,$xml,$fileToCreate,$stringToAdd);

		appendCreatorToXMLRecord($currentRecno,$fileToCreate,$stringToAdd,$name);

		appendTitleAreaToXMLRecord($currentRecno,$fileToCreate,$stringToAdd,$title);

		appendCopyrightArea($currentRecno,$fileToCreate,$stringToAdd);

		closeXMLFile($currentRecno);

	}

	echo "</ol>";





    /* ***************************************************

  	*

  	* RELEASE THE VARIABLE STORAGE

  	*

  	***************************************************** */

    $result->close();



} else { //check for error if query was wrong

    /* ***************************************************

    *

    *   SOME ERROR ...

    *

    ***************************************************** */

    echo $con->error;

}



/* ***************************************************

*

*   CLOSE THE CONNECTION TO THE DB

*

***************************************************** */

$con->close();

echo '<hr />Closing the connection.';





function createXMLHeader($currentRecno,$recordID,$xml,$fileToCreate,$stringToAdd) {



	$fileToCreate = "xmloutputtest/$currentRecno.xml";

	

    $stringToAdd = "

    	<diRecord>

    	<record>

    		<id>$currentRecno</id>
            <recordID>$recordID</recordID>

    	</record>

    	";

    

    echo "<font color='red'>File to create: <a href='$fileToCreate'>$fileToCreate</a></font><br />";

    file_put_contents($fileToCreate,$xml,FILE_APPEND);

    

    echo "&nbsp;&nbsp;&nbsp;<strong>Appending XML Header</strong> <i>$stringToAdd</i>";

    file_put_contents($fileToCreate,$stringToAdd,FILE_APPEND);

}



function appendCreatorToXMLRecord($currentRecno,$fileToCreate,$stringToAdd,$name) {

	$fileToCreate = "xmloutputtest/$currentRecno.xml";

    $stringToAdd = "

    <creatorArea>

        <creatorName>$name</creatorName>

        <vra:agent>$name</vra:agent>

        <dc:creator>$name</dc:creator>

    </creatorArea>";

            // echo $name;

    echo "<br />&nbsp;&nbsp;&nbsp;<strong>Appending Creator:</strong> <i>$stringToAdd</i><br />";

	file_put_contents($fileToCreate,$stringToAdd,FILE_APPEND);

}



function appendTitleAreaToXMLRecord($currentRecno,$fileToCreate,$stringToAdd,$title) {

	$fileToCreate = "xmloutputtest/$currentRecno.xml";

    $stringToAdd = "

    <titleArea>

            <title lang='en'>".$title."</title>

            <dc:title lang='XX'>".$title."</dc:title>

            <vra:title>".$title."</vra:title>

        </titleArea>

        ";

	echo "<br />&nbsp;&nbsp;&nbsp;<strong>Appending Title Area</strong>: <i>$stringToAdd</i><br />";

	file_put_contents($fileToCreate,$stringToAdd,FILE_APPEND);

}



function appendCopyrightArea($currentRecno,$fileToCreate,$stringToAdd) {

	$fileToCreate = "xmloutputtest/$currentRecno.xml";
    $rightsStatement = "Please see the copyright url for detailed rights information.";

    $stringToAdd = "

    <rightsArea>
    <icon_url_base>http://web.simmons.edu/~benoit/mmbpl/</icon_url_base>

        <copyright_url>http://www.digroup2016.edu/copyright.html</copyright_url>
        <rightsStatement>$rightsStatement</rightsStatement>
        <vra:rights>$rightsStatement</vra:rights>
        <dc:rights>$rightsStatement</dc:rights>
    </rightsArea>

    </diRecord>

    ";

	echo "<br />&nbsp;&nbsp;&nbsp;<strong>Appending Copyright</strong>: <i>$stringToAdd</i><br />";

	file_put_contents($fileToCreate,$stringToAdd,FILE_APPEND);

}



function closeXMLFile($currentRecno) {

	$fileToCreate = "xmloutputtest/$currentRecno.xml";

    $stringToAdd = "

    </diRecords>

</di>";

	echo "<br />&rarr;&nbsp;&nbsp;&nbsp;<strong>Closing</strong> the record.<i>$stringToAdd</i><hr />";

	file_put_contents($fileToCreate,$stringToAdd,FILE_APPEND);

}



/* ***************************************************

*

*   FINISH UP THE OUTPUT TO THE SCREEN

*

***************************************************** */

echo '<hr /></body></html>';

?>