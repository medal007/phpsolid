<?php
require_once "envparser.php";

(new DotEnv(__DIR__ . '/.env'))->load();


$databaseHost = getenv('DB_HOST');
$databaseName = getenv('DB_NAME');
$databaseUsername = getenv('DB_USER');
$databasePassword = getenv('DB_PASSWORD');


$domainname = $_SERVER['SERVER_NAME'];
$domainname = explode(".",$domainname);
$domainname = $domainname[0];
//define("SESSIONDIR","/sessionfiles/");
//define('DEBUG', 'Yes');
define('TB_PRE', $domainname.'_');

 
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 

function dbescape($sqlparam){
    $sqlparam = strval($sqlparam);
    global $mysqli;
    return $mysqli->real_escape_string($sqlparam);
}

function dbquery($sql)
{
    global $mysqli;
		# code...
      
		$query=$mysqli->query($sql);
		if (!$mysqli->errno) {
			# code...
			if ($query instanceof mysqli_result) {
				# code...

				$data=array();

				while ($row=$query->fetch_assoc()) {
					# code...
					$data[]=$row;
				}

				$result=new stdClass();
				$result->num_rows=$query->num_rows;
				$result->row=isset($data[0])?$data[0]:array();
				$result->rows=$data;
				return $result;
				
			}else{
				return true;
			}
		}else{
          print_r($mysqli->error);
          
          return false;
		}
	}
	
function notify($string, $recipient){
    $table5 = TB_PRE . "notification";
    $currentdate = date("Y-m-d H:i:s");
    $notifyadd = dbquery("INSERT INTO " .$table5. " (message, recipient, date_created) VALUES ('".dbescape($string)."', '".dbescape($recipient)."', '".dbescape($currentdate)."')");
}


function getdiff($date){
    $seconds  = strtotime(date('Y-m-d H:i:s')) - strtotime($date);
    $months = floor($seconds / (3600*24*30));
    $day = floor($seconds / (3600*24));
    $hours = floor($seconds / 3600);
    $mins = floor(($seconds - ($hours*3600)) / 60);
    $secs = floor($seconds % 60);

    if($seconds < 60)
        $time = $secs." seconds ago";
    else if($seconds < 60*60 )
        $time = $mins." min ago";
    else if($seconds < 24*60*60)
        $time = $hours." hours ago";
    else if($seconds < 24*60*60)
        $time = $day." day ago";
    else
        $time = $months." month ago";

    return $time;
}

function shorten($title, $cutOffLength) {

    $charAtPosition = "";
    $titleLength = strlen($title);

    do {
        $cutOffLength++;
        $charAtPosition = substr($title, $cutOffLength, 1);
    } while ($cutOffLength < $titleLength && $charAtPosition != " ");

    return substr($title, 0, $cutOffLength) . '...';

}
?>