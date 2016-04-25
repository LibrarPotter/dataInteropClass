<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_NOTICE);
$hostname = "dany.simmons.edu";
$username = "xxxx;
$password = "xxxx";
$dbname = "xxxx";
$table = "xxxx";
#end login info#OPENS CONNECTION OR REPORTS ERROR
$con = mysqli_connect($hostname, $username, $password, $dbname);
$db = mysqli_select_db($con ,$table);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);

	}
header("Content-Type:text/xml");
$xml= "<?xml version='1.0' encoding='utf-8'?>\n";
$root_element = $table."s"; 
$xml .="<".$root_element.">\n";

$sql = "SELECT * FROM demo1";

$result= $con -> query($sql);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}
 
if(mysqli_num_rows($result)>0)
{
   while($result_array = mysqli_fetch_assoc($result))
   {
      $xml .= "<".$table.">";
 
      //loop through each key,value pair in row
      foreach($result_array as $key => $value)
      {
         //$key holds the table column name
         $xml .= "<$key>";
 
         //embed the SQL data in a CDATA element to avoid XML entity issues
         $xml .= "<![CDATA[$value]]>";
 
         //and close the element
         $xml .= "</$key>";
      }
 
      $xml.="</".$table.">";
   }
}

$xml.="</".$root_element.">";
echo $xml;
?>
