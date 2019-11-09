<?php
$url_array = explode("/", $_SERVER['REQUEST_URI']);
//I'm getting the link details here and I split with "/"

$indexOfIndexPHP = array_search("apiroute.php", $url_array);
//Get position of apiroute.php in the link in case the tester tests using deep folder

//Routing
if (array_key_exists($indexOfIndexPHP , $url_array) && $url_array[$indexOfIndexPHP] != "" && isset($url_array[$indexOfIndexPHP + 1])) {
	//If url as first parameter and the parameter is not /
	
	require_once "backend/api.php";
			$api = new Api;
			$method = $url_array[$indexOfIndexPHP + 1];
			$parameter = isset($url_array[$indexOfIndexPHP + 2]) ? $url_array[$indexOfIndexPHP + 2]: NULL;
			$api->$method($parameter);
			/*modified so that /apiroute.php/getProfile/4
				will call the getProfile method and insert 4 as parameter 
			*/
}else {
  //home
	echo "This Home, Direct Access Not Allowed This is for both ajax and Fetch API only";
}
?>